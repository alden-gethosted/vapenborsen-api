<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   
        // dd( $this->productType );

        return [
            'id'                    => $this->id,
            'product_brands'        => isset( $this->productBrand ) ? new ProductBrandResource( $this->productBrand ) : '',
            'product_categories'    => isset( $this->productCategory ) ? new ProductCategoriesResource( $this->productCategory ) : '', 
            'product_types_id'      => isset( $this->productType ) ? new ProductTypeResource( $this->productType ) : '',
            'companies'             => isset( $this->companies_id ) ? new CompanyResource( $this->company ) : '',
            'ads_packages'          => isset( $this->adsPackage ) ? $this->adsPackage : '',
            'products'              => isset( $this->product ) ? new ProductResource( $this->product ) : '',
            'users'                 => isset( $this->user ) ? new UserResource( $this->user ) : '',
            'reviews'               => isset( $this->adsReviews ) ? AdReviewsResource::collection( $this->adsReviews ): [],
            'gallery'               => isset( $this->adsPhotos ) ?  AdPhotosResource::collection( $this->adsPhotos ) : [],
            'tags'                  => isset( $this->adsTags ) ? AdsTagsResource::collection( $this->adsTags ) : [],
            'items'                  => isset( $this->adsItems ) ? AdsItemResource::collection( $this->adsItems ) : [],
            'name'                  => $this->name,
            'state'                 => $this->state, 
            'seller'                => isset( $this->seller ) ? $this->seller : '', 
            'email'                 => isset( $this->email ) ? $this->email : '',
            'phone'                 => isset( $this->phone ) ? $this->phone : '',
            'contact_time'          => isset( $this->contact_time ) ? $this->contact_time : '', 
            'brand'                 => isset( $this->brand ) ? $this->brand : '',
            'category'              => isset( $this->category ) ? $this->category : '',
            'product_types'         => isset( $this->product_types ) ? $this->product_types : '',
            'photo'                 => isset( $this->photo ) ? asset( $this->photo ) : '',
            'price'                 => $this->price,
            'descriptions'          => isset( $this->descriptions ) ? $this->descriptions : '',
            'is_used'               => $this->is_used,
            'is_shipping'           => $this->is_shipping,
            'status'                => $this->status,
            'expire'                => $this->expire
        ];
    }
}
