<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Cart;
use App\Models\Addresses;
use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getAllOrder(){
        try {
            $orders = Orders::with(['user', 'cart.product'])->orderBy('created_at', 'desc')->get();
            $groupedOrders = $orders->groupBy('refid');
            $ordersData = [];

            foreach ($groupedOrders as $refid => $orderItems) {
                $firstItem = $orderItems->first();
                $mop = $firstItem->mop;
                $status = $firstItem->status;
                $deliveryType = $firstItem->delivery_type;
                $user = $firstItem->user; 
                $getAddress = Addresses::where('user_id', $firstItem->user_id)->orderBy('created_at', 'desc')->first();

                $orderDetails = [
                    'refid' => $refid,
                    'mop' => $mop,
                    'status' => $status,
                    'deliveryType' => $deliveryType,
                    'total_items' => count($orderItems),
                    'total_price' => $orderItems->sum('total_item_price'),
                    'orders' => [],
                    'user_role' => $user->user_role,
                    'user' => [
                        'uid' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'address' => $getAddress
                ];

                foreach ($orderItems as $item) {
                    $orderDetails['orders'][] = [
                        'cart_id' => $item->cart_id,
                        'product_name' => $item->cart->product->item_name,
                        'product_description' => $item->cart->product->item_description,
                        'product_imageurl' => $item->cart->product->image_url,
                        'order_quantity' => $item->order_quantity,
                        'total_item_price' => $item->total_item_price,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                }

                $ordersData[] = $orderDetails;
            }
    
            return response()->json([
                'status' => 200,
                'message' => 'Order items retrieved successfully!',
                'data' => $ordersData
            ]);
        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'OrdersController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        } 
    }

    public function updateStatus(Request $request){
        try {
            $getOrders = Orders::where('refid', $request->id)->get();

            if ($getOrders->isNotEmpty()) {
                Orders::where('refid', $request->id)->update([
                    'status' => 'Delivered'
                ]);

                return response($this->response(200, 'Successfully Updated!'));
            } else {
                return response($this->response(409, 'This Order does not exist in the data!'));
            }

        } catch (Throwable $th) {
            return response($this->response(501, 'Error in '.$th));
        }
    }
}
