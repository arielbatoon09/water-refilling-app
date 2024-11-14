<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refill;
use App\Models\Addresses;
use DateTime;
use Throwable;

class RefillController extends Controller
{
    
    public function getAllRefills(){
        try {
            $getRefillers = Refill::orderBy('created_at', 'desc')->with('User')->get();

            $refillsData = [];

            foreach ($getRefillers as $data) {
                $getAddress = Addresses::where('user_id', $data->user->id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();

                $gallonDetails = json_decode($data->gallon_details, true);
                $filteredGallonDetails = array_map(function ($gallon) {
                    return [
                        'gallon_size' => $gallon['gallon_size'],
                        'no_of_gallon' => $gallon['no_of_gallon']
                    ];
                }, $gallonDetails);

                $refillsData[] = [
                    'id' => $data->id,
                    'name' => $data->user->name,
                    'user_role' => $data->user->user_role,
                    'address' => $getAddress, 
                    "gallon_details" => $filteredGallonDetails,
                    "delivery_type" => $data->delivery_type,
                    "mop" => $data->mop,
                    "delivery_date" => (new DateTime($data->delivery_date))->format('M j, Y'),
                    "t_refill_fee" => $data->t_refill_fee,
                    "t_delivery_fee" => $data->t_delivery_fee,
                    "t_overall_fee" => $data->t_refill_fee + $data->t_delivery_fee,
                    "status" => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            }


            return response([
                'status' => 200,
                'source' => 'RefillController',
                'data' => $refillsData,
            ]);
            
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in ' . $th));
        }
    }

    public function updateRefilling(Request $req){
        return $this->updateRefillingFunction($req);
    }

    private function updateRefillingFunction(Request $req){
        try {
            $getRefill = Refill::where('id', $req->id)->first();

            if($getRefill){
                $getRefill->update([
                    'status' => $req->status
                ]);
                
                return response($this->response(200, 'Sucessfully Updated!'));
            }else{
                return response($this->response(409,'This Gallon size is not existing in the data!'));
            }
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in '.$th));
        }
    }

    public function changeToDelivered(Request $req){
        try {
            $getRefill = Refill::where('id', $req->id)->first();

            if($getRefill){
                $getRefill->update([
                    'status' => 'Delivered'
                ]);
                
                return response($this->response(200, 'Sucessfully Updated!'));
            }else{
                return response($this->response(409,'This Gallon size is not existing in the data!'));
            }
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in '.$th));
        }
    }
    
}
