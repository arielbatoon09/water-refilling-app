<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\order_checkout as checkout;
use App\Models\order_carts as order;
use Throwable;

class OrderCheckoutController extends Controller
{
    public function checkout(Request $req){
        try {
            $getIdOfItem = order::where('id', $req->cart_id)->first();

            $checkout = checkout::create([
                "user_id" => $req->id,
                "cart_id" => $req->cart_id,
                "total_price" => $getIdOfItem->order_total_price, // Get the total of all order price
                "shipping_address" => $req->shipping,
                "billing_address" => $req->billing,
                "mop" => $req->mop,
                "payment_status" => $req->status,
                "order_status" => "Pending",
            ]);

            if($checkout){
                // The cart will be hidden after checkout
                $getIdOfItem->update([
                    'flag' => 1
                ]);
                return response($this->response(200,'Successfully Checkouted!'));
            }else{
                return response($this->response(401,'Checkout Failed!'));
            }
        } catch (Throwable $th) {
            return response($this->response(501,'Error in '.$th));
        }
    }
}
