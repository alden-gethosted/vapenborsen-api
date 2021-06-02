<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $coupons_id
 * @property integer $ads_packages_id
 * @property integer $users_id
 * @property string $name
 * @property string $types
 * @property int $quantity
 * @property float $amount
 * @property float $discount
 * @property string $status
 * @property string $expire
 * @property boolean $is_percent
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property AdsPackage $adsPackage
 * @property Coupon $coupon
 * @property User $user
 */
class PurchasePackage extends Model
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
    protected $fillable = ['coupons_id', 'ads_packages_id', 'companies_id', 'users_id', 'name', 'types', 'quantity', 'amount', 'discount', 'status', 'expire', 'is_percent', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adsPackage()
    {
        return $this->belongsTo('App\Models\AdsPackage', 'ads_packages_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo('App\Models\Coupon', 'coupons_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id');
    }

    public function company(){
        return $this->belongsTo('App\Models\Company', 'companies_id');
    }
}
