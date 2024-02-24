<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AspirantResource;
use App\Models\Cart;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        $id = auth()->user()->id;
        $courseCode = $request->courseCode;
        //$courseCode = $request->courseCode;
        $couresepresent = Cart::where('code',$request->courseCode)->where('stalite_id', $id)->count();

if($couresepresent > 0){
    return  "$courseCode already Added to Cart.";

}else{
                    $cart = new Cart;
                    $cart['stalite_id'] = $id;
                    $cart['course_id'] = $request->courseId;
                    $cart['code'] = $request->courseCode;
                    $cart['price'] = $request->coursePrice;
                    $cart->save();
                    return "$courseCode added to Cart Successfully";
}
                }

public function getCart(){
            $id = auth()->user()->id;
        $getcart = Cart::where('stalite_id',$id)->get();

        return AspirantResource::collection($getcart);
    }

    public function cartdelete($courseCode)
    {
        $id = auth()->user()->id;
        $cartproduct = Cart::where('stalite_id',$id)
            ->where('code',$courseCode)->first();
        $cartproduct->delete();

        return "$courseCode deleted from Cart successfully";
    }

   public function emptyCart()
   {
    $id = auth()->user()->id;
    $cartproducts = Cart::where('stalite_id',$id);
     $cartproducts->delete();
     return 'Cart emptied Successfully';

   }
}
