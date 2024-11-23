<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Addresses;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function checkout(Request $request)
    {
        $addresses = Addresses::where('user_id', auth()->user()->id)->first();

        if($addresses){ 
            try {
                // Validate request inputs
                $validator = Validator::make($request->all(), [
                    'mop' => 'required|string',
                    'deliveryType' => 'required|string',
                    'cartArray' => 'required|array',
                ]);
    
                // Check if validation fails
                if ($validator->fails()) {
                    return response([
                        'status' => 422,
                        'source' => 'OrdersController',
                        'message' => 'Invalid input data!',
                        'errors' => $validator->errors()
                    ]);
                }
                
                $refNumber = $this->generateUniqueRefNumber();
                $totalOrderPrice = 0;
    
                foreach ($request->cartArray as $cart) {
                    $totalItemPrice = $cart['unit_price'] * $cart['order_quantity'];
                    $totalOrderPrice += $totalItemPrice;
    
                    // Create the Cart record
                    Orders::create([
                        "refid" => $refNumber,
                        "user_id" => auth()->user()->id,
                        "cart_id" => $cart['cart_id'],
                        "order_quantity" => $cart['order_quantity'],
                        "total_item_price" => $totalItemPrice,
                        "mop" => $request->mop,
                        "delivery_type" => $request->deliveryType,
                        "status" => $request->mop === 'COD' ? "To Receive" : 'To Pay',
                    ]);
    
                    // Deduct the Order Quantity to Product Stocks
                    $product = Product::where('id', $cart['product_id'])->first();
                    if ($product) {
                        $newStock = $product->item_stocks - $cart['order_quantity'];
                        if ($newStock >= 0) {
                            $product->item_stocks = $newStock;
                            $product->save();
                        } else {
                            return response([
                                'status' => 400,
                                'message' => 'Insufficient stock for product: ' . $product->product_name,
                            ], 400);
                        }
                    }
    
                    // Update the card id flag
                    $cartId = Cart::where('id', $cart['cart_id'])->first();
                    if ($cartId) {
                        $cartId->update([
                            'flag' => 0,
                        ]);
                    }
                }
    
                return response([
                    'status' => 200,
                    'source' => 'OrdersController',
                    'message' => 'Successfully checked out the order(s)!',
                ]);
    
            } catch (Throwable $th) {
                return response([
                    'status' => 501,
                    'source' => 'OrdersController',
                    'message' => 'Error: ' . $th->getMessage(),
                ], 501);
            }
        }else{
            return response([
                'status' => 409,
                'source' => 'RefillController',
                'message' => 'No Address!',
            ]);
        }
        
    }

    public function purchaseNow(Request $request)
    {
        try {
            // Validate request inputs
            $validator = Validator::make($request->all(), [
                'mop' => 'required|string',
                'deliveryType' => 'required|string',
                'cartArray' => 'required|array',
                'cart_id' => 'required',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response([
                    'status' => 422,
                    'source' => 'OrdersController',
                    'message' => 'Invalid input data!',
                    'errors' => $validator->errors()
                ]);
            }
            
            $refNumber = $this->generateUniqueRefNumber();
            $totalOrderPrice = 0;

            foreach ($request->cartArray as $cart) {
                $totalItemPrice = $cart['unit_price'] * $cart['order_quantity'];
                $totalOrderPrice += $totalItemPrice;

                // Create the Cart record
                Orders::create([
                    "refid" => $refNumber,
                    "user_id" => auth()->user()->id,
                    "cart_id" => $request->cart_id,
                    "order_quantity" => $cart['order_quantity'],
                    "total_item_price" => $totalItemPrice,
                    "mop" => $request->mop,
                    "delivery_type" => $request->deliveryType,
                    "status" => $request->mop === 'COD' ? "To Receive" : 'To Pay',
                ]);

                // Deduct the Order Quantity to Product Stocks
                $product = Product::where('id', $cart['product_id'])->first();
                if ($product) {
                    $newStock = $product->item_stocks - $cart['order_quantity'];
                    if ($newStock >= 0) {
                        $product->item_stocks = $newStock;
                        $product->save();
                    } else {
                        return response([
                            'status' => 400,
                            'message' => 'Insufficient stock for product: ' . $product->product_name,
                        ], 400);
                    }
                }

                // Update the card id flag
                $cartId = Cart::where('id', $request->cart_id)->first();
                if ($cartId) {
                    $cartId->update([
                        'flag' => 0,
                    ]);
                }
            }

            return response([
                'status' => 200,
                'source' => 'OrdersController',
                'message' => 'Successfully checked out the order(s)!',
            ]);

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'OrdersController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    public function getOrdersUID(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            $orders = Orders::where('user_id', $userId)->with('cart.product')->orderBy('created_at', 'desc')->get();
            $groupedOrders = $orders->groupBy('refid');
            $ordersData = [];
    
            foreach ($groupedOrders as $refid => $orderItems) {
                $mop = $orderItems->first()->mop;
                $status = $orderItems->first()->status;
                $deliveryType = $orderItems->first()->delivery_type;

                $orderDetails = [
                    'refid' => $refid,
                    'mop' => $mop,
                    'status' => $status,
                    'deliveryType' => $deliveryType,
                    'total_items' => count($orderItems),
                    'total_price' => $orderItems->sum('total_item_price'),
                    'orders' => []
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

    public function generatePayNow(Request $request)
    {
        try {
            $orderDetails = $request->data;
    
            $requestData = [
                'data' => [
                    'attributes' => [
                        'send_email_receipt' => false,
                        'show_description' => true,
                        'show_line_items' => true,
                        'line_items' => [],
                        'payment_method_types' => ['gcash', 'paymaya', 'grab_pay'],
                        'reference_number' => $orderDetails['refid'],
                        'success_url' => "http://127.0.0.1:5173/success?source=shop&refid={$orderDetails['refid']}",
                        'description' => 'Payment for ' . $orderDetails['deliveryType'] . ' - ' . $orderDetails['mop'] . ' Order',
                    ]
                ]
            ];
            
            foreach ($orderDetails['orders'] as $item) {
                $perItemPrice = intval(($item['total_item_price'] / $item['order_quantity']) * 100);

                $requestData['data']['attributes']['line_items'][] = [
                    'currency' => 'PHP',
                    'amount' => $perItemPrice,
                    'description' => $item['product_description'],
                    'name' => $item['product_name'],
                    'quantity' => $item['order_quantity'],
                ];
            }
    
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Basic " . env("PAYMONGO_SKEY")
            ])
            ->post('https://api.paymongo.com/v1/checkout_sessions', $requestData);
    
            return response()->json([
                'status' => $response->status(),
                'data' => $response->json(),
                'message' => 'Checkout session created successfully!',
            ]);
            
        } catch (Throwable $th) {
            return response()->json([
                'status' => 501,
                'source' => 'OrdersController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    public function updateOrderStatus(Request $request)
    {
        try {
            $updatedStatus = Orders::where('refid', $request->refid)->update(['status' => $request->status]);
    
            if ($updatedStatus > 0) {
                return response([
                    'status' => 200,
                    'source' => 'OrdersController',
                    'message' => 'Updated Order(s) Status to "To Receive"',
                ]);
            } else {
                return response([
                    'status' => 404,
                    'source' => 'OrdersController',
                    'message' => 'No orders found with the provided refid.',
                ], 404);
            }
        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'OrdersController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }
    
    public function removeOrderByCartId($id)
    {
        try {
            $selectRemove = Orders::where('cart_id', $id)->first();
            if($selectRemove){
                $selectRemove->delete();
                return response([
                    'status' => 200,
                    'source' => 'OrdersController',
                    'message' => 'Successfully removed order item!',
                ]);
            }else{
                return response([
                    'status' => 409,
                    'source' => 'OrdersController',
                    'message' => 'This does not exist on order table!',
                ]);
            }

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'OrdersController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }
    

    private function generateUniqueRefNumber()
    {
        do {
            $randomNumber = rand(10000, 99999);
            $refNumber = 'Ref_' . $randomNumber;
        } while (Orders::where('refid', $refNumber)->exists());

        return $refNumber;
    }
}
