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

    public function AddGallonType(Request $req)
    {
        return $this->AddGallonTypeFunction($req);
    }

    public function updateGallonTypes(Request $req){
        return $this->updateGallonTypesFunction($req);
    }

    public function deleteGallonType(Request $req)
    {
        return $this->deleteGallonTypeFuntion($req);
    }

    private function AddGallonTypeFunction(Request $req)
    {
        try {
            $gallonType = GallonType::where('gallon_size', $req->size)->first();

            if(!$gallonType){
                GallonType::create([
                    "gallon_details" => $req->input('details'),
                    "gallon_size" => $req->input('size'),
                    "price" => $req->input('price'),
                    "delivery_fee" => $req->input('fee'),
                    "flag" => 0
                ]);
                return response($this->response(200, 'Successfully Addded!'));
            }else{
                return response($this->response(409, 'This gallon is already exist!'));
            }
        } catch (\Throwable $th) {
            return response($this->response(501, $th));
        }
    }

    private function getAllGallonTypeFunction()
    {
        try {
            $gallonTypes = GallonType::where('flag', 0)->get();

            $gallonDatas = [];

            foreach ($gallonTypes as $data) {
                $gallonDatas[$data->id] = [
                    'id' => $data->id,
                    "gallon_details" => $data->gallon_details,
                    "gallon_size" => $data->gallon_size,
                    "price" => $data->price,
                    "delivery_fee" => $data->delivery_fee,
                    "flag" => $data->flag,
                    "created_at" => $data->created_at,
                    "updated_at" => $data->updated_at,
                ];
            }

            return response([
                'data' => $gallonDatas,
                $this->response(200, 'All Data in Flag 0')
            ]);

        } catch (Throwable $e) {
            return response($this->response(501, 'Error in '.$e));
        }


    }

    private function updateGallonTypesFunction(Request $req)
    {
        try {
            $getGallonType = GallonType::where('gallon_size', $req->size)->first();

            if($getGallonType){
                $getGallonTypeID = GallonType::where('id', $getGallonType->id)->first();
                $getGallonTypeID->update([
                    "gallon_details" => $req->details,
                    "gallon_size" => $req->updateSize,
                    "price" => $req->price,
                    "delivery_fee" => $req->fee
                ]);

                return response($this->response(200, 'Successfully Updated!'));
            }else{
                return response($this->response(409, 'Gallon Size Not Existing in the data!'));
            }
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in ' .$th));
        }
    }

    private function deleteGallonTypeFuntion(Request $req)
    {
        try {
            $getGallonTypes = GallonType::where('id', $req->id)->first();

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
