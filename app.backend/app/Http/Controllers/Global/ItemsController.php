<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\item_inventory as item;
use App\Models\order_carts as order;
use Throwable;

class ItemsController extends Controller
{
        
    public function getAllItem()
    {
        try {
            $items = order::all();

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

    public function getFetchItem(Request $req)
    {
        try {
            $items = order::where('id', $req->id)->first();

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

            return response(["data" => $itemStored,$this->response(200, 'Successfully get the data!')]);
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in '.$th));
        }
    }

}
