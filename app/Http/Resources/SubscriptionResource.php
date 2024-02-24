<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'id' => $this->id,
            'planName' => $this->name,
            'planPrice' => $this->plan_price,
            'discount' => $this->percent_discount,
            'quantity' => $this->quantity,
            'duration' => $this->duration,
        ];
    }
}

