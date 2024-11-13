<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProducts(){
        $items = Product::orderBy('created_at', 'desc')->where('flag', 1)->get();

        return response([
            'status' => 200,
            'source' => 'ProductController',
            'data' => $items,
        ]);
    }

    public function addProductItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'stock' => 'required|numeric',
            'product_image' => 'required|string',
            'description' => 'required|string|max:255',
            'cost' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 422,
                'source' => 'GallonTypeController',
                'message' => 'Make sure fill up the booking details.',
                'errors' => $validator->errors()
            ]);
        }

        if ($request->filled('product_image')) {
            $base64Image = $request->input('product_image');
            preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches);
            $extension = $matches[1] ?? 'png';
    
            $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($imageData);
    
            $randomImageName = 'image-' . Str::random(40) . '.' . $extension;
            $imagePath = 'product/' . $randomImageName;
    
            Storage::disk('public')->put($imagePath, $imageData);
            } else {
                return response([
                'status' => 400,
                'source' => 'ProductController',
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
                'message' => "Added New Product!"
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

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'stock' => 'required|numeric',
            'description' => 'required|string|max:255',
            'cost' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 422,
                'source' => 'GallonTypeController',
                'message' => 'Make sure fill up the booking details.',
                'errors' => $validator->errors()
            ]);
        }

        if ($request->filled('product_image')) {
            $base64Image = $request->input('product_image');
            preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches);
            $extension = $matches[1] ?? 'png';
    
            $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($imageData);
    
            $randomImageName = 'image-' . Str::random(40) . '.' . $extension;
            $imagePath = 'product/' . $randomImageName;
    
            Storage::disk('public')->put($imagePath, $imageData);
            } else {
                return response([
                'status' => 400,
                'source' => 'ProductController',
                'message' => 'No valid image provided!'
            ]);
        }

        $productID = Product::where('id', $product->id)->first();

        $productID->update([
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
                'message' => "Added New Product!"
            ]);
        }else{
            return response($this->response(401,'Add Product Failed!'));
        }

    }

    public function removeProduct(Request $request){
        $product = Product::where('id', $request->id)->first();

        if($product){
            $product->update([
                "flag" => 0,
            ]);

            return response($this->response(200, 'Successfully Updated!'));
        }else{
            return response($this->response(409, 'Gallon Size Not Existing in the data!'));
        }
    }

}
