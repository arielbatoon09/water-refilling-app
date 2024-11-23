<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refill;
use App\Models\Addresses;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
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
                    'uid' => $data->user->id,
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

    public function updateWaitingDeliveryStatus(Request $request){
        try {
            $getRefill = Refill::where('id', $request->id)->first();

            if($getRefill){
                $getRefill->update([
                    'status' => 'Waiting Delivery'
                ]);
                
                return response($this->response(200, 'Sucessfully Updated!'));
            }else{
                return response($this->response(409,'This Gallon size is not existing in the data!'));
            }
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in '.$th));
        }
    }

    public function addRefillingFunction(Request $request)
    {
        $addresses = Addresses::where('user_id', $request->user_id)->first();

        if($addresses){

            if($request->user_id == null){
                $validator = Validator::make($request->all(), [
                    'gallon_details' => 'required|array|min:1',
                    'delivery_type' => 'required|string',
                    'mop' => 'required|string',
                    'delivery_date' => 'required|date',
                ]);
            
                // Check if validation fails
                if ($validator->fails()) {
                    return response([
                        'status' => 422,
                        'source' => 'RefillController',
                        'message' => 'Error Refill Submission!',
                        'errors' => $validator->errors()
                    ]);
                }
            
                try {
                    $gallonDetails = json_encode($request->gallon_details);
                    $t_refill_fee = 0;
                    $t_delivery_fee = 0;
                    $delivery_extra_fee = 0;
                    
                    foreach ($request->gallon_details as $gallon) { 
                        $t_refill_fee += $gallon['gallon_each_price'] * $gallon['no_of_gallon'];
                        $t_delivery_fee += $gallon['gallon_each_dfee'] * $gallon['no_of_gallon'];
                    }
    
                    $status = 'Pending Payment'; 
                    if ($request->delivery_type === 'Door-To-Door' && $request->mop === 'COD') {
                        $status = 'For Pick-up';
                    } elseif ($request->delivery_type === 'Walk-In' && $request->mop === 'COD') {
                        $status = 'Visit Shop';
                    } elseif ($request->delivery_type === 'Walk-In' && $request->mop === 'Over-The-Counter') {
                        $status = 'Completed';
                    } else {
                        $status = 'Pending Payment';
                    }
                    
                    Refill::create([
                        "user_id" => auth()->user()->id,
                        "gallon_details" => $gallonDetails,
                        "delivery_type" => $request->delivery_type,
                        "mop" => $request->mop,
                        "delivery_date" => $request->delivery_date,
                        "t_refill_fee" => $t_refill_fee,
                        "t_delivery_fee" => $request->delivery_type === 'Walk-In' ? $t_delivery_fee = 0 : $t_delivery_fee,
                        "status" => $status
                    ]);
                    
                    return response([
                        'status' => 200,
                        'source' => 'RefillController',
                        'message' => 'Water refill booking completed successfully!',
                    ]);
                } catch (Throwable $th) {
                    return response([
                        'status' => 501,
                        'source' => 'RefillController',
                        'message' => 'Error: ' . $th->getMessage(),
                    ], 501);
                }
            }else{
               $userId = User::where('id', $request->user_id )->first();
    
                if($userId){
                    $validator = Validator::make($request->all(), [
                        'gallon_details' => 'required|array|min:1',
                        'delivery_type' => 'required|string',
                        'mop' => 'required|string',
                        'delivery_date' => 'required|date',
                    ]);
                
                    // Check if validation fails
                    if ($validator->fails()) {
                        return response([
                            'status' => 422,
                            'source' => 'RefillController',
                            'message' => 'Error Refill Submission!',
                            'errors' => $validator->errors()
                        ]);
                    }
                
                    try {
                        $gallonDetails = json_encode($request->gallon_details);
                        $t_refill_fee = 0;
                        $t_delivery_fee = 0;
                        $delivery_extra_fee = 0;
                        
                        foreach ($request->gallon_details as $gallon) { 
                            $t_refill_fee += $gallon['gallon_each_price'] * $gallon['no_of_gallon'];
                            $t_delivery_fee += $gallon['gallon_each_dfee'] * $gallon['no_of_gallon'];
                        }
    
                        $status = 'Pending Payment'; 
                        if ($request->delivery_type === 'Door-To-Door' && $request->mop === 'COD') {
                            $status = 'For Pick-up';
                        } elseif ($request->delivery_type === 'Walk-In' && $request->mop === 'COD') {
                            $status = 'Visit Shop';
                        } elseif ($request->delivery_type === 'Walk-In' && $request->mop === 'Over-The-Counter') {
                            $status = 'Completed';
                        } else {
                            $status = 'Pending Payment';
                        }
                        
                        Refill::create([
                            // "user_id" => auth()->user()->id,
                            "user_id" => $request->user_id,
                            "gallon_details" => $gallonDetails,
                            "delivery_type" => $request->delivery_type,
                            "mop" => $request->mop,
                            "delivery_date" => $request->delivery_date,
                            "t_refill_fee" => $t_refill_fee,
                            "t_delivery_fee" => $request->delivery_type === 'Walk-In' ? $t_delivery_fee = 0 : $t_delivery_fee,
                            "status" => $status
                        ]);
                        
                        return response([
                            'status' => 200,
                            'source' => 'RefillController',
                            'message' => 'Water refill booking completed successfully!',
                        ]);
                    } catch (Throwable $th) {
                        return response([
                            'status' => 501,
                            'source' => 'RefillController',
                            'message' => 'Error: ' . $th->getMessage(),
                        ], 501);
                    }
                }else{
                    return response([
                        'status' => 200,
                        'source' => 'RefillController',
                        'message' => 'Not Found',
                    ]);
                }
            }
        }else{
            return response([
                'status' => 409,
                'source' => 'RefillController',
                'message' => 'No Address',
            ]);
        }

        
    }
    
}
