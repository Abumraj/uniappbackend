<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\cart;


class AspirantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      $id = auth()->user()->id;
     $couresepresent = Cart::where('stalite_id', $id)->sum('price');
     $comment = "No Discount Applied. Spend above ₦5000 to get 20% off";

     if($couresepresent > 5000){
        $couresepresent = 0.85*$couresepresent;
        $comment = "Hurray! 20% discount applied.";
     }


        return [
            'courseId' =>$this->id,
            'courseName' =>$this->description,
            'coursecode' =>$this->code,
            'coursePrice' =>$this->price,
            'courseUnit' =>$this->unit,
            'introUrl' =>$this->introUrl,
            'cartTotal' => $couresepresent,
            'comment' =>$comment,
        ];
    }
}
