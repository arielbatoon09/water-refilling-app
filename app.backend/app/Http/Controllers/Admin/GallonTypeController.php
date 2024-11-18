<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\GallonType;
use Throwable;

class GallonTypeController extends Controller
{
    public function getAllGallonType(){
        return $this->getAllGallonTypeFunction();
    }

    public function getSelectedGallon(Request $request){
        try {
            $gallonType = GallonType::where('id', $request->id)->first();;

            if ($gallonType) {
                $gallonData = [
                    'id' => $gallonType->id,
                    'gallon_size' => $gallonType->gallon_size,
                    'gallon_price' => $gallonType->gallon_price,
                    'delivery_fee' => $gallonType->delivery_fee,
                    'gallon_image' => $gallonType->gallon_image,
                    'flag' => $gallonType->flag,
                    'created_at' => $gallonType->created_at,
                    'updated_at' => $gallonType->updated_at,
                ];
            
                return response([
                    'status' => 200,
                    'source' => 'GallonTypeController',
                    'data' => $gallonData,
                ]);
            } else {
                return response([
                    'status' => 404,
                    'message' => 'Gallon type not found',
                ]);
            }
        } catch (\Throwable $th) {
            return response($this->response(501, $th));
        }
    }

    public function AddGallonType(Request $request)
    {
        return $this->AddGallonTypeFunction($request);
    }

    public function updateGallonTypes(Request $request){
        return $this->updateGallonTypesFunction($request);
    }

    public function deleteGallonType(Request $request)
    {
        return $this->deleteGallonTypeFuntion($request);
    }

    private function AddGallonTypeFunction(Request $request)
    {
        try {
            // Validate request inputs
            $validator = Validator::make($request->all(), [
                'gallon_size' => 'required|string|max:255',
                'gallon_price' => 'required|numeric',
                'gallon_image' => 'required|string',
                'delivery_fee' => 'required|numeric',
            ]);
    
            if ($validator->fails()) {
                return response([
                    'status' => 422,
                    'source' => 'GallonTypeController',
                    'message' => 'Make sure fill up the booking details.',
                    'errors' => $validator->errors()
                ]);
            }
    
            // Check if gallon_size already exists
            if (GallonType::where('gallon_size', $request->input('gallon_size'))->exists()) {
                return response([
                    'status' => 409,
                    'source' => 'GallonTypeController',
                    'message' => 'Gallon size already exists!'
                ]);
            }
    
            // Handle image upload from Base64 string
            if ($request->filled('gallon_image')) {
                $base64Image = $request->input('gallon_image');
                preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches);
                $extension = $matches[1] ?? 'png';
    
                // Remove the base64 prefix from the string
                $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
                $imageData = base64_decode($imageData);
    
                // Generate a random name for the image
                $randomImageName = 'image-' . Str::random(40) . '.' . $extension;
                $imagePath = 'gallon/' . $randomImageName;
    
                Storage::disk('public')->put($imagePath, $imageData);
            } else {
                return response([
                    'status' => 400,
                    'source' => 'GallonTypeController',
                    'message' => 'No valid image provided!'
                ]);
            }
    
            // Create the new GallonType entry
            GallonType::create([
                "gallon_size" => $request->input('gallon_size'),
                "gallon_price" => $request->input('gallon_price'),
                "gallon_image" => env('APP_URL').'/storage/'.$imagePath,
                "delivery_fee" => $request->input('delivery_fee'),
                "flag" => 1
            ]);
    
            return response([
                'status' => 200,
                'source' => 'GallonTypeController',
                'message' => "Added New Gallon Type!"
            ]);
        } catch (\Throwable $th) {
            return response($this->response(501, $th));
        }
    }
    
    public function getAllAdminGallon()
    {
        try {
            $gallonTypes = GallonType::get();

            $gallonDatas = [];

            foreach ($gallonTypes as $data) {
                $gallonDatas[$data->id] = [
                    'id' => $data->id,
                    "gallon_size" => $data->gallon_size,
                    "gallon_price" => $data->gallon_price,
                    "delivery_fee" => $data->delivery_fee,
                    "gallon_image" => $data->gallon_image,
                    "flag" => $data->flag,
                    "created_at" => $data->created_at,
                    "updated_at" => $data->updated_at,
                ];
            }

            return response([
                'status' => 200,
                'source' => 'GallonTypeController',
                'data' => $gallonDatas,
            ]);

        } catch (Throwable $e) {
            return response($this->response(501, 'Error in '.$e));
        }
    }

    private function getAllGallonTypeFunction()
    {
        try {
            $gallonTypes = GallonType::where('flag', 1)->get();

            $gallonDatas = [];

            foreach ($gallonTypes as $data) {
                $gallonDatas[$data->id] = [
                    'id' => $data->id,
                    "gallon_size" => $data->gallon_size,
                    "gallon_price" => $data->gallon_price,
                    "delivery_fee" => $data->delivery_fee,
                    "gallon_image" => $data->gallon_image,
                    "flag" => $data->flag,
                    "created_at" => $data->created_at,
                    "updated_at" => $data->updated_at,
                ];
            }

            return response([
                'status' => 200,
                'source' => 'GallonTypeController',
                'data' => $gallonDatas,
            ]);

        } catch (Throwable $e) {
            return response($this->response(501, 'Error in '.$e));
        }
    }

    private function updateGallonTypesFunction(Request $request)
    {
        try {
            $getGallonTypeID = GallonType::where('id', $request->id)->first();

            if (!$getGallonTypeID) {
                return response([
                    'status' => 404,
                    'source' => 'GallonTypeController',
                    'message' => 'Gallon type not found!'
                ]);
            }

            $validator = Validator::make($request->all(), [
                'gallon_size' => 'required|string|max:255',
                'gallon_price' => 'required|numeric',
                'delivery_fee' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response([
                    'status' => 422,
                    'source' => 'GallonTypeController',
                    'message' => 'Make sure fill up the booking details.',
                    'errors' => $validator->errors()
                ]);
            }

            // Check if gallon_size already exists
            if (GallonType::where('gallon_size', $request->input('gallon_size'))->exists()) {
                return response([
                    'status' => 409,
                    'source' => 'GallonTypeController',
                    'message' => 'Gallon size already exists!'
                ]);
            }

            $updateData = [
                "gallon_size" => $request->input('gallon_size'),
                "gallon_price" => $request->input('gallon_price'),
                "delivery_fee" => $request->input('delivery_fee')
            ];

            if ($request->filled('gallon_image')) {
                $base64Image = $request->input('gallon_image');
                preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches);
                $extension = $matches[1] ?? 'png';

                $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
                $imageData = base64_decode($imageData);

                $randomImageName = 'image-' . Str::random(40) . '.' . $extension;
                $imagePath = 'gallon/' . $randomImageName;

                Storage::disk('public')->put($imagePath, $imageData);

                $updateData['gallon_image'] = env('APP_URL') . '/storage/' . $imagePath;
            }

            $getGallonTypeID->update($updateData);

            return response([
                'status' => 200,
                'source' => 'GallonTypeController',
                'message' => 'Successfully Updated!'
            ]);

        } catch (Throwable $th) {
            return response($this->response(501, 'Error in ' .$th));
        }
    }

    public function updateGallonStatus(Request $request)
    {
        try {
            // Find the gallon type by ID
            $getGallonType = GallonType::find($request->id);
    
            if ($getGallonType) {
                // Toggle the flag value
                $newFlag = $getGallonType->flag === 1 ? 0 : 1;
    
                // Update the flag
                $getGallonType->update([
                    'flag' => $newFlag,
                ]);
    
                return response()->json([
                    'status' => 200,
                    'message' => 'Gallon status updated successfully!',
                    'updated_flag' => $newFlag,
                ]);
            } else {
                return response()->json([
                    'status' => 409,
                    'message' => 'This gallon size does not exist in the data!',
                ]);
            }
        } catch (Throwable $th) {
            return response()->json([
                'status' => 501,
                'message' => 'Error: ' . $th->getMessage(),
            ]);
        }
    }    

    private function deleteGallonTypeFuntion(Request $request)
    {
        try {
            $getGallonTypes = GallonType::where('id', $request->id)->first();

            if($getGallonTypes){
                $getGallonTypes->update([
                    'flag' => 0
                ]);
                return response($this->response(200, 'Removed!'));
            }else{
                return response($this->response(409,'This Gallon size is not existing in the data!'));
            }
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in '.$th));
        }
    }

}
