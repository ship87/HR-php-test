<?php

namespace App\Common\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Order - модель заказа
 * @package App\Common\Order\Models
 */
class Order extends Model
{
    /**
     * Новый заказ
     *
     * @var int
     */
    const STATUS_NEW = 0;

    /**
     * Заказ подтвержден
     *
     * @var int
     */
    const STATUS_ACCEPTED = 10;

    /**
     * Заказ завершен
     *
     * @var int
     */
    const STATUS_FINISHED = 20;

    /**
     * Псевдоним названия
     *
     * @var string
     */
    const ALIAS_TITLE = 'title';

    /**
     * Текущие заказы
     *
     * @var int
     */
    const CURRENT_ORDERS = 1;

    /**
     * Просроченные заказы
     *
     * @var int
     */
    const PAST_DUE_ORDERS = 2;

    /**
     * Новые заказы
     *
     * @var int
     */
    const NEW_ORDERS = 3;

    /**
     * Выполненные заказы
     *
     * @var int
     */
    const COMPLETED_ORDERS = 4;

    /**
     * Статусы заказов
     *
     * @var array
     */
    CONST STATUS_DATA = [
        self::STATUS_NEW => [
            self::ALIAS_TITLE => 'новый'
        ],
        self::STATUS_ACCEPTED => [
            self::ALIAS_TITLE => 'подтвержден'
        ],
        self::STATUS_FINISHED => [
            self::ALIAS_TITLE => 'завершен'
        ]
    ];

    /**
     * @inheritdoc
     */
    protected $guarded = ['id'];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'status',
        'client_email',
        'partner_id'
    ];

    /**
     * Партнер
     *
     * @return HasOne
     */
    public function partner(): HasOne
    {
        return $this->hasOne(Partner::class, 'id', 'partner_id');
    }

    /**
     * Продукты заказа
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    /**
     * Scope for eloquent builder
     * @param $builder
     * @return mixed
     */
    public function scopeWithRelations($builder)
    {
        return $builder->with(['partner', 'products', 'products.product', 'products.product.vendor']);
    }

    /**
     * Получить сумму заказа
     *
     * @return int
     */
    public function getSum()
    {
        $sum = 0;
        foreach ($this->products as $orderProduct) {
            $sum += $orderProduct->price * $orderProduct->quantity;
        }
        return $sum;
    }

    /**
     * Получить наименования товаров
     *
     * @return string
     */
    public function getTitleProducts()
    {
        $titles = [];
        foreach ($this->products as $orderProduct) {
            $product = $orderProduct->product;
            $titles[] = $product->name;
        }
        return implode(', ', $titles);
    }

    /**
     * Получить наименование статуса
     *
     * @return string
     */
    public function getStatusTitle()
    {
        return isset(self::STATUS_DATA[$this->status]) ?
            self::STATUS_DATA[$this->status][self::ALIAS_TITLE] : 'не определен';
    }
}
