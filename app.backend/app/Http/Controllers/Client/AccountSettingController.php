<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Addresses;
use Throwable;

class AccountSettingController extends Controller
{
    public function getUserInformation(){
        try {
            $userId = auth()->user()->id;
            $user = User::where('id', $userId)->first();
        
            return response([
                'status' => 200,
                'source' => 'ClientAccountSettingController',
                'data' => $user,
            ]);

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'ClientAccountSettingController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    public function addUserAddress(Request $request){
        try {
            $userId = auth()->user()->id;

            $result = Addresses::create([
                "user_id" => $userId,
                "address" => $request->addressLine,
                "municipality" => $request->municipality,
                "city" => $request->city,
                "postal_code" => $request->postalCode,
                "phone_number" => $request->phone_number,
            ]);

            if($result){
                return response([
                    'status' => 200,
                    'source' => 'ClientAccountSettingController',
                    'message' => 'Address Successfully Added!',
                ]);
            }else{
                return response([
                    'status' => 409,
                    'source' => 'ClientAccountSettingController',
                    'message' => 'Address Failed to Add!',
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

    public function changePassword(Request $request){
        try {
            $userId = auth()->user()->id;
            
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string'
            ]);

            if (!Hash::check($request->current_password, auth()->user()->password)) {
                return response([
                    'status' => 200,
                    'source' => 'ClientAccountSettingController',
                    'message' => 'Your old password is not match!',
                ]);
            }

            auth()->user()->update([
                'password' => Hash::make($request->new_password),
            ]);

            return response([
                'status' => 200,
                'source' => 'ClientAccountSettingController',
                'message' => 'Password Successfully Changed!',
            ]);

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'ClientAccountSettingController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    public function getAddressInformation(){
        try {
            $userId = auth()->user()->id;
            $address = Addresses::where('user_id', $userId)->first();
        
            return response([
                'status' => 200,
                'source' => 'ClientAccountSettingController',
                'data' => $address,
            ]);

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'ClientAccountSettingController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    public function updateUserInfo(Request $request){
        $userId = auth()->user()->id;
            
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 422,
                'source' => 'ClientAccountSettingController',
                'message' => 'Invalid input data!',
                'errors' => $validator->errors()
            ]);
        }

        auth()->user()->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response([
            'status' => 200,
            'source' => 'ClientAccountSettingController',
            'message' => 'Change User Information Successfully!',
        ]);
    }

    public function changeAddress(Request $request){
        try {
            $userId = auth()->user()->id;

            $address = Addresses::where('user_id', $userId)->first();
        
            if ($address) {
                $address->update([
                    'address' => $request->addressLine,
                    'municipality' => $request->municipality,
                    'city' => $request->city,
                    'postal_code' => $request->postalCode,
                    'phone_number' => $request->phone_number,
                ]);
                
                return response([
                    'status' => 200,
                    'source' => 'ClientAccountSettingController',
                    'message' => 'Address Successfully Updated!',
                ]);
            } else {
                $newAddress = Addresses::create([
                    'user_id' => $userId,
                    'address' => $request->addressLine,
                    'municipality' => $request->municipality,
                    'city' => $request->city,
                    'postal_code' => $request->postalCode,
                    'phone_number' => $request->phone_number,
                ]);
                
                return response([
                    'status' => 201,
                    'source' => 'ClientAccountSettingController',
                    'message' => 'Address Successfully Added!',
                ]);
            }
        } catch (\Exception $e) {
            return response([
                'status' => 500,
                'source' => 'ClientAccountSettingController',
                'message' => 'An error occurred while updating the address.',
                'error' => $e->getMessage(),
            ]);
        }
        
    }
}
