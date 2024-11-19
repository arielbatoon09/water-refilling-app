<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Throwable;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            // Validate request inputs
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|numeric',
                'order_quantity' => 'required|numeric',
                'unit_price' => 'required|numeric',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response([
                    'status' => 422,
                    'source' => 'CartController',
                    'message' => 'Failed Add to Cart!',
                    'errors' => $validator->errors()
                ]);
            }

            $cart = Cart::create([
                "user_id" => auth()->user()->id,
                "product_id" => $request->product_id,
                "order_quantity" => $request->order_quantity,
                "unit_price" => $request->unit_price,
                "flag" => 1,
            ]);

            return response([
                'status' => 200,
                'id' => $cart->id,
                'source' => 'CartController',
                'message' => 'Added to cart!',
            ]);

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'CartController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    public function getAllUIDCart()
    {
        try {
            
            $userId = auth()->user()->id;
            $getCartList = Cart::where('user_id', $userId)
            ->where('flag', 1)
            ->whereHas('product', function ($query) {
                $query->where('flag', 1);
            })
            ->orderBy('created_at', 'desc')
            ->with('product')
            ->get();        

            $cartData = [];
            
            foreach ($getCartList as $data) {
                $cartData[] = [
                    'cart_id' => $data->id,
                    'product_id' => $data->product_id,
                    'order_quantity' => $data->order_quantity,
                    'unit_price' => $data->unit_price,
                    'product_stocks' => $data->product->item_stocks,
                    'product_name' => $data->product->item_name,
                    'product_description' => $data->product->item_description,
                    'product_imageurl' => $data->product->image_url,
                ];
            }

            return response()->json([
                'status' => 200,
                'message' => 'Cart items retrieved successfully!',
                'data' => $cartData
            ]);

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'CartController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    public function updateCartOrderQuantity(Request $request)
    {
        try {
            $cart = Cart::where('id', $request->id)->first();

            if (!$cart) {
                return response([
                    'status' => 404,
                    'source' => 'CartController',
                    'message' => 'Cart item not found!',
                ], 404);
            }

            $cart->update([
                "order_quantity" => $request->order_quantity,
            ]);

            return response([
                'status' => 200,
                'source' => 'CartController',
                'message' => 'Updated Cart Order Quantity!',
            ]);

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'CartController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    public function removeOrderToCart(Request $request)
    {
        try {
            $selectRemove = Cart::where('id', $request->id)->first();
            if($selectRemove){
                $selectRemove->delete();
                return response([
                    'status' => 200,
                    'source' => 'CartController',
                    'message' => 'Successfully removed cart item!',
                ]);
            }else{
                return response([
                    'status' => 409,
                    'source' => 'CartController',
                    'message' => 'This does not exist on order table!',
                ]);
            }
        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'CartController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }
}
