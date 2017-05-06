<?php

namespace Myshop\Domain\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Myshop\Common\Model\Money;

/**
 * @property string title
 * @property int stock
 * @property \Myshop\Common\Model\Money price
 * @property string description
 */
class Product extends Model
{
    use SoftDeletes;

    // RELATIONSHIPS

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ACCESSOR & MUTATORS

    public function getPriceAttribute(int $price)
    {
        return new Money($price);
    }

    public function setPriceAttribute(Money $price)
    {
        $this->attributes['price'] = $price->getAmount();
    }
}
