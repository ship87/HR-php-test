<?php

namespace App\Common\Order\Services;

use App\Common\Order\Mail\OrderCompletedMail;
use App\Common\Order\Models\Order as OrderModel;
use App\Common\Order\Services\ValueObjects\InputData;
use App\Http\Requests\OrderFormSave;
use App\Common\Order\Models\Product as ProductModel;
use App\Helpers\ArrayHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Exception;

/**
 * Class Order - сервис для работы с заказами
 * @package App\Common\Order\Services
 */
class Order
{
    /**
     * Получить активную вкладку типа заказа
     *
     * @param int|null $activeTab
     * @return int
     */
    public function getCurrentActiveTab($activeTab)
    {

        $currentActiveTab = OrderModel::CURRENT_ORDERS;

        switch ($activeTab) {
            case OrderModel::PAST_DUE_ORDERS:
                $currentActiveTab = OrderModel::PAST_DUE_ORDERS;
                break;
            case OrderModel::NEW_ORDERS:
                $currentActiveTab = OrderModel::NEW_ORDERS;
                break;
            case OrderModel::COMPLETED_ORDERS:
                $currentActiveTab = OrderModel::COMPLETED_ORDERS;
                break;
        }

        return $currentActiveTab;
    }

    /**
     * Получить данные для формы заказа
     *
     * @param OrderModel $order
     * @return InputData
     */
    public function getInputData(OrderModel $order)
    {
        $isErrorExist = !empty(Session::get('errors'));
        $oldProducts = old('products');

        $orderProducts = [];
        foreach ($order->products as $orderProduct) {
            $productId = $orderProduct->product_id;

            $orderProducts [] = [
                'product_id' => $productId,
                'name' => $orderProduct->product->name,
                'quantity' => $isErrorExist ? $oldProducts[$productId]['quantity'] : $orderProduct->quantity,
                'price' => $orderProduct->price,
                'id' => $orderProduct->id
            ];
        }

        $allProducts = [];
        foreach (ProductModel::all() as $product) {
            $allProducts [] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
            ];
        }

        $inputData = new InputData();
        $inputData->setPartnerId(($isErrorExist) ? old('partner_id') : $order->partner_id);
        $inputData->setClientEmail(($isErrorExist) ? old('client_email') : $order->client_email);
        $inputData->setStatus(($isErrorExist) ? old('status') : $order->status);
        $inputData->setProducts($orderProducts);
        $inputData->setProductsSrc($allProducts);

        return $inputData;
    }

    /**
     * Получить заказ по id
     *
     * @param int $id
     * @return OrderModel|null
     */
    public function getById(int $id)
    {

        return OrderModel::where('id', $id)->withRelations()->first();
    }

    /**
     * Получить просроченные заказы
     *
     * @param $limit
     * @return LengthAwarePaginator
     */
    public function getOrdersPastDue($limit)
    {
        return OrderModel::withRelations()
            ->where('status', OrderModel::STATUS_ACCEPTED)
            ->whereDate('delivery_dt', '<', Carbon::now())
            ->orderBy('delivery_dt', 'DESC')
            ->paginate($limit, ['*'], 'past-due')
            ->appends('active-tab', OrderModel::PAST_DUE_ORDERS);
    }

    /**
     * Получить текущие заказы
     *
     * @return Collection
     */
    public function getOrdersCurrent()
    {
        $dateDelivery = Carbon::now()->addHours(24);
        return OrderModel::withRelations()
            ->where('status', OrderModel::STATUS_ACCEPTED)
            ->whereDate('delivery_dt', '>=', $dateDelivery)
            ->orderBy('delivery_dt', 'ASC')
            ->get();
    }

    /**
     * Получить новые заказы
     *
     * @param $limit
     * @return LengthAwarePaginator
     */
    public function getOrdersNew($limit)
    {
        return OrderModel::withRelations()
            ->where('status', OrderModel::STATUS_NEW)
            ->whereDate('delivery_dt', '>', Carbon::now())
            ->orderBy('delivery_dt', 'ASC')
            ->paginate($limit, ['*'], 'new')
            ->appends('active-tab', OrderModel::NEW_ORDERS);
    }

    /**
     * Получить выполненные заказы
     *
     * @param $limit
     * @return LengthAwarePaginator
     */
    public function getOrdersCompleted($limit)
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $from = $currentDate . ' 00:00:00';
        $to = $currentDate . ' 23:59:59';

        return OrderModel::withRelations()
            ->where('status', OrderModel::STATUS_FINISHED)
            ->whereBetween('delivery_dt', [$from, $to])
            ->orderBy('delivery_dt', 'DESC')
            ->paginate($limit, ['*'], 'completed')
            ->appends('active-tab', OrderModel::COMPLETED_ORDERS);
    }

    /**
     * Сохранение заказа
     *
     * @param OrderModel $order
     * @param OrderFormSave $request
     */
    public function saveOrder(OrderModel $order, OrderFormSave $request)
    {
        $oldOrder = $this->getById($order->id);
        $order->fill($request->all());
        $order->save();
        $newOrder = $this->getById($order->id);

        if ($oldOrder instanceof OrderModel && $order instanceof OrderModel) {
            $this->sendEmailPartnerAndVendors($oldOrder, $newOrder);
        }
    }

    /**
     * Отправить письма партнеру и поставщикам
     *
     * @param OrderModel $oldOrder
     * @param OrderModel $newOrder
     * @return bool
     */
    public function sendEmailPartnerAndVendors(OrderModel $oldOrder, OrderModel $newOrder)
    {
        if ($oldOrder->status === OrderModel::STATUS_FINISHED
            || $newOrder->status !== OrderModel::STATUS_FINISHED
        ) {
            return false;
        }

        $recipients = [$newOrder->partner->email];

        /**
         * @var $products Collection
         */
        $products = $newOrder->products;
        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                $email = $product->product->vendor->email;
                if (!in_array($email, $recipients)) {
                    $recipients[] = $email;
                }
            }
        }

        try {
            $mail = new OrderCompletedMail();
            $mail->setOrder($newOrder);
            $mail->setRecipients($recipients);
            Mail::send($mail);
        } catch (Exception $e) {
            report($e);

        }

        return true;
    }

    /**
     * Обновить продукты заказа
     *
     * @param OrderModel $order
     * @param array $arrSrc
     */
    public function refreshOrderProducts(OrderModel $order, array $arrSrc)
    {
        $requestOrderProducts = isset($arrSrc['current']) ? $arrSrc['current'] : [];
        $requestOrderProductIds = array_map(function ($product) {
            return $product['id'];
        }, $requestOrderProducts);

        foreach ($order->products as $model) {
            if (in_array($model->id, $requestOrderProductIds)) {
                $input = ArrayHelper::getByAliasValue($requestOrderProducts, 'id', $model->id);
                $model->fill($input);
                $model->save();
            } else {
                $model->delete();
            }
        }

        $this->addNewProductsToOrder($order, $arrSrc);
    }

    /**
     * Добавить новые продукты к заказу
     *
     * @param OrderModel $order
     * @param array $arrSrc
     * @return bool
     */
    public function addNewProductsToOrder(OrderModel $order, array $arrSrc)
    {
        $requestOrderProductsForAdd = isset($arrSrc['for_add']) ? $arrSrc['for_add'] : [];
        if (empty($requestOrderProductsForAdd)) {
            return false;
        }

        $productIds = [];
        foreach ($requestOrderProductsForAdd as $data) {
            $productIds [] = $data['product_id'];
        }
        $products = ProductModel::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            $input = ArrayHelper::getByAliasValue($requestOrderProductsForAdd, 'product_id', $product->id);
            $attrs = array_merge($input, [
                'price' => $product->price
            ]);
            $order->products()->create($attrs);
        }

        return true;
    }
}