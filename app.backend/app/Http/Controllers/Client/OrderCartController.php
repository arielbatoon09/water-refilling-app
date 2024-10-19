<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\order_carts as order;
use App\Models\item_inventory as item;
use Throwable;

class OrderCartController extends Controller
{
    public function addToCart(Request $req)
    {
        try {
            $getIdOfItem = item::where('id', $req->item_id)->first();
            order::create([
                "user_id" => $req->user_id,
                "item_id" => $req->item_id,
                "order_quantity" => $req->order_quantity,
                "order_total_price" => $req->order_quantity * $getIdOfItem->item_price, // the item regular price multiply to the quantity. Place automatically
                "flag" => 0,
                "status" => $req->status
            ]);

            return response($this->response(200, 'Successfully Added to cart!'));

        } catch (\Throwable $th) {
            return response($this->response(501, 'Error in ' .$th));
        }
    }

    public function removeOrderToCart(Request $req)
    {
        try {
            $selectRemove = order::where('id', $req->id)->first();
            if($selectRemove){
                $selectRemove->delete();
                return response($this->response(200, 'Successfully removed!'));
            }else{
                return response($this->response(409, 'This does not exist on order table!'));
            }
        } catch (Throwable $th) {

        }
    }
}
