<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function getAllReviewAdmin()
    {
        try {
            $review = Review::all();
            return response()->json([
                'status' => 200,
                'source' => 'ReviewController',
                'data' => $review,
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
