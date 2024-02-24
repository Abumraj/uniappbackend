<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RemedialResource extends JsonResource
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
            'planName' => $this->planName,
            'planPrice' => $this->planPrice,
            'planCode' => $this->code,
            'description1' => $this->description1,
            'description2'  => $this->description2,
            'description3' => $this->description3,
            'description4' => $this->description4,
            'description5' => $this->description5,
            'introUrl' => $this->introUrl,

        ];
    }
}
