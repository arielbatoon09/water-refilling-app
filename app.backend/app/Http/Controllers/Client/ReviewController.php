<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Orders;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function addReview(Request $request)
    {
        try {
            if ($request->resource === 'Orders') {
                $details = collect($request->orders)->map(function ($order) {
                    return "{$order['product_name']} ({$order['order_quantity']}pcs)";
                })->join(', ');
    
                Review::create([
                    "user_id" => auth()->user()->id,
                    "resource" => $request->resource,
                    "resource_ref" => $request->resource_ref,
                    "details" => $details,
                    "rate" => $request->rate,
                    "comment" => $request->comment,
                ]);

                // Update Status of Orders to Rated
                $updatedStatus = Orders::where('refid', $request->resource_ref)->update(['status' => 'Rated']);
            }
    
            return response([
                'status' => 200,
                'source' => 'ReviewController',
                'message' => 'Updated Order(s) Status to Rated',
            ]);
        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'ReviewController',
                'message' => 'Error in ' . $th->getMessage(),
            ], 501);
        }
    }    
}
