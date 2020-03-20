<?php

namespace App\Common\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Product - модель продукта
 * @package App\Common\Order\Models
 */
class Product extends Model
{
    /**
     * Поставщик
     *
     * @return HasOne
     */
    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

    /**
     * Scope for eloquent builder
     * @param $builder
     * @return mixed
     */
    public function scopeWithRelations($builder)
    {
        return $builder->with(['vendor']);
    }

}
