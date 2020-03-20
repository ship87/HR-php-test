<?php

namespace App\Http\Controllers\Admin;

use App\Common\Order\Services\Product as ProductService;
use \Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class ProductController
 * @package App\Http\Controllers\Admin
 */
class ProductController extends BaseController
{
    /**
     * Лимит продуктов на страницу
     *
     * @var int
     */
    const LIMIT_PRODUCTS = 25;

    /**
     * Список продуктов
     *
     * @param ProductService $productService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ProductService $productService)
    {
        $products = $productService->get(self::LIMIT_PRODUCTS);

        return view('admin.product.index',
            [
                'title' => 'Список продуктов',
                'products' => $products,
            ]
        );
    }

    /**
     * Изменить цену продукта
     *
     * @param Request $request
     * @param ProductService $productService
     */
    public function updatePrice(Request $request, ProductService $productService)
    {
        $id = $request->input('pk');
        $value = (int)$request->input('value');

        if ($request->ajax() && $id !== null) {
            $productService->updatePrice((int)$id, $value);

        }
    }
}
