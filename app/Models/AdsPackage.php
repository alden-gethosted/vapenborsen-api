<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $types
 * @property int $quantity
 * @property boolean $expire_day
 * @property float $price
 * @property string $banner
 * @property string $description
 * @property boolean $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Ad[] $ads
 * @property Coupon[] $coupons
 * @property PurchasePackage[] $purchasePackages
 */
class AdsPackage extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'types', 'quantity', 'expire_day', 'price', 'banner', 'description', 'status', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany('App\Models\Ad', 'ads_packages_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupons()
    {
        return $this->hasMany('App\Models\Coupon', 'ads_packages_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchasePackages()
    {
        return $this->hasMany('App\Models\PurchasePackage', 'ads_packages_id');
    }
}
