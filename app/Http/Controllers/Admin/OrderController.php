<?php

namespace App\Http\Controllers\Admin;

use App\Common\Order\Services\ValueObjects\InputData;
use App\Http\Requests\OrderFormSave;
use App\Common\Order\Models\Order as OrderModel;
use App\Common\Order\Models\Partner;
use App\Common\Order\Services\Order as OrderService;
use \Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class OrderController
 * @package App\Http\Controllers\Admin
 */
class OrderController extends BaseController
{
    /**
     * Лимит просроченных заказов на страницу
     *
     * @var int
     */
    const LIMIT_PAST_DUE_ORDERS = 50;

    /**
     * Лимит новых заказов на страницу
     *
     * @var int
     */
    const LIMIT_NEW_ORDERS = 50;

    /**
     * Лимит выполненных заказов на страницу
     *
     * @var int
     */
    const LIMIT_COMPLETED_ORDERS = 50;

    /**
     * Список заказов
     *
     * @param Request $request
     * @param OrderService $orderService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, OrderService $orderService)
    {
        $currentActiveTab = $orderService->getCurrentActiveTab($request->get('active-tab'));

        $ordersPastDue = $orderService->getOrdersPastDue(self::LIMIT_PAST_DUE_ORDERS);
        $ordersCurrent = $orderService->getOrdersCurrent();
        $ordersNew = $orderService->getOrdersNew(self::LIMIT_NEW_ORDERS);
        $ordersCompleted = $orderService->getOrdersCompleted(self::LIMIT_COMPLETED_ORDERS);

        return view('admin.order.index',
            [
                'title' => 'Список заказов',
                'ordersPastDue' => $ordersPastDue,
                'ordersCurrent' => $ordersCurrent,
                'ordersNew' => $ordersNew,
                'ordersCompleted' => $ordersCompleted,
                'currentActiveTab' => $currentActiveTab,
            ]
        );
    }

    /**
     * Отображение формы заказа
     *
     * @param Request $request
     * @param OrderService $orderService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderForm(Request $request, OrderService $orderService)
    {
        $order = $orderService->getById($request->orderId);
        if (!($order instanceof OrderModel)) {
            abort(404);
        }

        $partners = Partner::all();

        /** @var $inputData InputData */
        $inputData = $orderService->getInputData($order);

        return view('admin.order.form', [
            'title' => 'Форма заказа',
            'order' => $order,
            'partners' => $partners,
            'action' => route('admin.order.form', ['orderId' => $order->id]),
            'inputData' => [
                'partner_id' => $inputData->getPartnerId(),
                'client_email' => $inputData->getClientEmail(),
                'status' => $inputData->getStatus(),
                'products' => [
                    'current' => $inputData->getProducts(),
                    'for_add' => []
                ],
                'product_src' => $inputData->getProductsSrc()
            ],
        ]);
    }

    /**
     * Сохранение формы заказа
     *
     * @param OrderFormSave $request
     * @param OrderService $orderService
     * @return array
     */
    public function orderFormSave(OrderFormSave $request, OrderService $orderService)
    {
        $order = OrderModel::where('id', $request->orderId)->withRelations()->firstOrFail();
        $orderService->saveOrder($order, $request);
        $orderService->refreshOrderProducts($order, $request->products);

        return [
            'success' => true,
            'url' => route('admin.order.form', ['id' => $request->orderId])
        ];
    }
}
