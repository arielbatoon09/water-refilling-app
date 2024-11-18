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

    public function addUserAddress(Request $request)
    {
        try {
            $userId = auth()->user()->id;
    
            // Validate the request to ensure no null or empty values
            $validated = $request->validate([
                'addressLine' => 'required|string|max:255',
                'municipality' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'postalCode' => 'required|string|max:10',
                'phone_number' => 'required|string|max:15',
            ]);
    
            // Check if an address already exists for the user
            $existingAddress = Addresses::where('user_id', $userId)->first();
    
            if ($existingAddress) {
                return response([
                    'status' => 409,
                    'source' => 'ClientAccountSettingController',
                    'message' => 'Address already exists for this user. Please update it instead.',
                ]);
            }
    
            // If no address exists, create a new one
            $result = Addresses::create([
                'user_id' => $userId,
                'address' => $validated['addressLine'],
                'municipality' => $validated['municipality'],
                'city' => $validated['city'],
                'postal_code' => $validated['postalCode'],
                'phone_number' => $validated['phone_number'],
            ]);
    
            if ($result) {
                return response([
                    'status' => 201,
                    'source' => 'ClientAccountSettingController',
                    'message' => 'Address successfully added!',
                ]);
            }
    
            return response([
                'status' => 500,
                'source' => 'ClientAccountSettingController',
                'message' => 'Failed to add address. Please try again.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response([
                'status' => 422,
                'source' => 'ClientAccountSettingController',
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'ClientAccountSettingController',
                'message' => 'An error occurred: ' . $th->getMessage(),
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
                    'status' => 401,
                    'source' => 'ClientAccountSettingController',
                    'message' => 'Incorrect Current Password!',
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

    public function changeAddress(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            
            // Check if any of the address fields are null
            if (is_null($request->addressLine) || is_null($request->municipality) || is_null($request->city) || is_null($request->postalCode) || is_null($request->phone_number)) {
                return response([
                    'status' => 400,
                    'source' => 'ClientAccountSettingController',
                    'message' => 'All address fields are required.',
                ], 400);
            }
    
            // Check if an address already exists for the user
            $address = Addresses::updateOrCreate(
                ['user_id' => $userId],
                [
                    'address' => $request->addressLine,
                    'municipality' => $request->municipality,
                    'city' => $request->city,
                    'postal_code' => $request->postalCode,
                    'phone_number' => $request->phone_number,
                ]
            );
    
            // Determine if the address was updated or created
            $message = $address->wasRecentlyCreated
                ? 'Address Successfully Added!'
                : 'Address Successfully Updated!';
    
            return response([
                'status' => $address->wasRecentlyCreated ? 201 : 200,
                'source' => 'ClientAccountSettingController',
                'message' => $message,
            ]);
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
