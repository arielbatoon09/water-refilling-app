<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProducts(){
        $items = Product::orderBy('created_at', 'desc')->get();

        return response([
            'status' => 200,
            'source' => 'ProductController',
            'data' => $items,
        ]);
    }

    public function addProductItem(Request $request){

        if ($request->filled('product_image')) {
            $base64Image = $request->input('product_image');
            preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches);
            $extension = $matches[1] ?? 'png';

            $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($imageData);

            $randomImageName = 'image-' . Str::random(40) . '.' . $extension;
            $imagePath = 'Product/' . $randomImageName;

            Storage::disk('public')->put($imagePath, $imageData);
        } else {
            return response([
                'status' => 400,
                'source' => 'ProductTypeController',
                'message' => 'No valid image provided!'
            ]);
        }

        $product = Product::create([
            "image_url" => env('APP_URL').'/storage/'.$imagePath,
            "item_name" => $request->product_name,
            "item_description" => $request->description,
            "item_stocks" => $request->stock,
            "item_price" => $request->cost,
            "flag" => 1,
        ]);

        if($product){
            return response([
                'status' => 200,
                'source' => 'ProductController',
                'message' => "Add product Image"
            ]);
        }else{
            return response($this->response(401,'Add Product Failed!'));
        }
    }

    public function selectedProduct(Request $request){
        $product = Product::where('id', $request->id)->first();

        return response([
            'status' => 200,
            'source' => 'ProductController',
            'data' => $product,
        ]);
    }   

    public function updateProduct(Request $request){
        $product = Product::where('id', $request->id)->first();

        if($product){
            $productID = Product::where('id', $product->id)->first();
            $productID->update([
                "gallon_details" => $request->details,
                "gallon_size" => $request->updateSize,
                "price" => $request->price,
                "delivery_fee" => $request->fee
            ]);

            return response($this->response(200, 'Successfully Updated!'));
        }else{
            return response($this->response(409, 'Gallon Size Not Existing in the data!'));
        }
    }

}
