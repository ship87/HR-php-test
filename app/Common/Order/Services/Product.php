<?php

namespace App\Common\Order\Services;

use App\Common\Order\Models\Product as ProductModel;

/**
 * Class Product - сервис для работы с продуктами
 * @package App\Common\Product\Services
 */
class Product
{
    /**
     * Получить продукты
     *
     * @param $limit
     * @return mixed
     */
    public function get($limit)
    {
        return ProductModel::withRelations()
            ->orderBy('name', 'ASC')
            ->paginate($limit);
    }

    /**
     * Изменить цену продукта
     *
     * @param int $id
     * @param int $value
     * @return bool
     */
    public function updatePrice(int $id, int $value)
    {

        /** @var $product ProductModel */
        $product = ProductModel::find($id);

        if (!($product instanceof ProductModel)) {
            return false;
        }

        $product->price = $value;
        $product->update();

        return true;
    }
}