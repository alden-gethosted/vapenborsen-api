<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'email'       => isset( $this->address ) ? $this->address : '',
            'longitude'     => isset( $this->longitude ) ? $this->longitude : '',
            'latitude'      => isset( $this->latitude ) ? $this->latitude : '',
        ];
    }
}
