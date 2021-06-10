<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdReviewsResource extends JsonResource
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
            'id'        => $this->id,
            'comment'   => $this->comment,
            'rating'    => $this->rating,
            'ads'    => new AdsResource($this->ad),
            'customer'  => new CustomerResource($this->user)
        ];
    }
}
