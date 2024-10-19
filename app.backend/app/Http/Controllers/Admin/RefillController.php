<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refill;
use Throwable;

class RefillController extends Controller
{
    
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
    
}
