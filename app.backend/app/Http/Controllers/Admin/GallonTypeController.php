<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GallonType;
use Throwable;

class GallonTypeController extends Controller
{
    public function getAllGallonType(){
        return $this->getAllGallonTypeFunction();
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
            GallonType::create([
                "gallon_size" => $request->input('gallon_size'),
                "gallon_price" => $request->input('gallon_price'),
                "gallon_image" => $request->input('gallon_image'),
                "delivery_fee" => $request->input('delivery_fee'),
                "flag" => 1
            ]);

            return response([
                'status' => 200,
                'source' => 'GallonTypeController',
                'message' => "Added New Gallon Size!"
            ]);

        } catch (\Throwable $th) {
            return response($this->response(501, $th));
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
            $getGallonType = GallonType::where('gallon_size', $request->size)->first();

            if($getGallonType){
                $getGallonTypeID = GallonType::where('id', $getGallonType->id)->first();
                $getGallonTypeID->update([
                    "gallon_details" => $request->details,
                    "gallon_size" => $request->updateSize,
                    "price" => $request->price,
                    "delivery_fee" => $request->fee
                ]);

                return response($this->response(200, 'Successfully Updated!'));
            }else{
                return response($this->response(409, 'Gallon Size Not Existing in the data!'));
            }
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in ' .$th));
        }
    }

    private function deleteGallonTypeFuntion(Request $request)
    {
        try {
            $getGallonTypes = GallonType::where('id', $request->id)->first();

            if($getGallonTypes){
                $getGallonTypes->update([
                    'flag' => 1
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
