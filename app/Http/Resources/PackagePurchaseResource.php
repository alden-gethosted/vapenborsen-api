<?php

namespace App\Http\Resources;

use App\Models\AdsPackage;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PackagePurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'types'       => $this->types,
            'quantity'    => $this->quantity,
            'amount'  => $this->amount,
            'expire'       => $this->expire,
            'discount'      => $this->discount,
            'coupon'      => new CouponResource($this->coupon),
            'package'      => new AdPackageResource($this->adsPackage),
            'customer'      => new CustomerResource($this->user)
        ];
    }
}
