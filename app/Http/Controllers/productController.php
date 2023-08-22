<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class productController extends Controller
{
    function addProduct(Request $req){
        $product=new Product;
        $product->name=$req->input('name');
        $product->price=$req->input('price');
        $product->description=$req->input('description');
        $product->file_path=$req->file('file')->store('products');
        $product->save();
        // return response()->json(['message'=>'Product added successfully'],200);
        return $product;
    }
}
