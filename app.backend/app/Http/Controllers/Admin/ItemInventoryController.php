<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\item_inventory as item;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Throwable;

// image_url
// item_name
// item_description
// item_stocks
// item_stocks
// item_price
// status

class ItemInventoryController extends Controller
{
    public function addItemInventory(Request $req)
    {
        try {
            $item = item::where('item_name', $req->item_name)->first();

            if(!$item){
                item::create([
                    "image_url" => $req->image_url,
                    "item_name" => $req->item_name,
                    "item_description" => $req->item_description,
                    "item_stocks" => $req->item_stocks,
                    "item_stocks" => $req->item_stocks,
                    "item_price" => $req->item_price,
                    "status" => $req->status
                ]);

                // Need to ask where the image will store
                // if ($req->hasFile('image_url')) {
                //     $image_url = $req->file('addItemImage')->store('images', 'public');
                // }

                return response($this->response(200, 'Item Successfully Added!'));
            }else{
                return response($this->response(409, 'Item ' . $req->item_name . ' is already exist!'));
            }

        } catch (Throwable $th) {
            return response($this->response(501, 'Error in '.$th));
        }
    }
    
    public function getAllItemInventory()
    {
        try {
            $items = item::all();

            $itemStored = [];

            foreach ($items as $item) {
                $itemStored[$item->id] = [
                    "item_id" => $item->id,
                    "image_url" => $item->image_url,
                    "item_name" => $item->item_name,
                    "item_description" => $item->item_description,
                    "item_stocks" => $item->item_stocks,
                    "item_stocks" => $item->item_stocks,
                    "item_price" => $item->item_price,
                    "status" => $item->status,
                ];
            }

            return response(["data" => $itemStored,$this->response(200, 'This is all datas from inventory!')]);
        } catch (Throwable $th) {
            
            return response($this->response(501, 'Error in '.$th));
        }
    }

    public function updateItemInventory(Request $req)
    {
        try {
            $getName = item::where('item_name', $req->item_name)->first();
            if($getName){
                $getId = item::where('id', $getName->id)->first();

                $getId->update([
                    "image_url" => $req->updateImage_url,
                    "item_name" => $req->updateItem_name,
                    "item_description" => $req->updateItem_description,
                    "item_stocks" => $req->updateItem_stocks,
                    "item_stocks" => $req->updateItem_stocks,
                    "item_price" => $req->updateItem_price,
                    "status" => $req->updateStatus,
                ]);

                return response($this->response(200, 'Successfully Updated!'));

            }else{
                return response($this->response(409, 'Item not found!'));
            }
        } catch (Throwable $th) {
            return response($this->response(501, 'Erorr in '.$th));
        }
    }

    public function deleteItemInventory(Request $req)
    {
        try {
            $getId = item::where('id', $req->id)->delete();

            if($getId){
                return response($this->response(200, 'Successfully deleted this data!'));
            }else{
                return response($this->response(409, 'This item is not existing in the data!'));
            }
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in '.$th));
        }
    }

}
