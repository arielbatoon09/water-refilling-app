<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GallonType;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Refill;
use Throwable;

class RefillController extends Controller
{
    public function getAllRefills()
    {
        return $this->getAllRefillsFunction();
    }

    public function addRefilling(Request $req)
    {
        return $this->addRefillingFunction($req);
    }

    private function getAllRefillsFunction()
    {
        try {
            $getRefillers = Refill::with(['user', 'gallonType'])->get();

            $refillsData = [];

            foreach ($getRefillers as $data) {
                $refillsData[] = [
                    'id' => $data->id,
                    'user_id' => $data->user_id,
                    'user_name' => $data->user->name,
                    'gallon_id' => $data->gallon_id,
                    'gallonType_details' => $data->gallonType->gallon_details,
                    'gallonType_size' => $data->gallonType->gallon_size,
                    'gallonType_price' => $data->gallonType->price,
                    'gallonType_delivery_fee' => $data->gallonType->delivery_fee,
                    'delivery_type' => $data->delivery_type,
                    'mop' => $data->mop,
                    'no_of_gallon' => $data->no_of_gallon,
                    'delivery_date' => $data->delivery_date,
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            }

            return response()->json([
                'data' => $refillsData,
                'message' => 'All Data Retrieved Successfully'
            ], 200);
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in ' . $th));
        }
    }

    private function addRefillingFunction(Request $req)
    {
        try {
            Refill::create([
                "user_id" => $req->user_id,
                "gallon_id" => $req->gallon_id,
                "delivery_type" => $req->delivery_type,
                "mop" => $req->mop,
                "no_of_gallon" => $req->no_of_gallon,
                "delivery_date" => $req->delivery_date,
                "status" => 0
            ]);
            return response($this->response(200, 'Successfully Addded!'));
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in ' . $th));
        }
    }

    private function response($status, $message)
    {
        return [
            'status' => $status,
            'message' => $message,
        ];
    }
}
