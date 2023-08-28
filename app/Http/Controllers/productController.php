<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class productController extends Controller
{
    function addProduct(Request $req)
    {
        $product = new Product;
        $product->name = $req->input('name');
        $product->price = $req->input('price');
        $product->type = $req->input('type');
        $product->description = $req->input('description');
        $product->file_path = $req->file('file')->store('public/products', ['max_file_size' => '4MB']);
        $product->save();
        // return response()->json(['message'=>'Product added successfully'],200);
        return $product;
    }

    function getProducts()
    {
        if (Auth::guard('api')->check()) {
            $products = Product::all();

            $productsWithImageUrls = $products->map(function ($product) {
                $product->image_url = asset('storage/' . $product->file_path);
                return $product;
            });

            return response()->json(['products' => $productsWithImageUrls], 200);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    function deleteProduct($id)
    {
        try {
            if (Auth::guard('api')->check()) {
                $product = Product::find($id)->delete();
                if ($product) {
                    return response()->json(['message' => 'Product deleted successfully'], 200);
                }
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['message' => 'Failed to delete product. Product not found'], 404);
            }
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}
