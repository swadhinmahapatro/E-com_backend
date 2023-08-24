<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class productController extends Controller
{
    function addProduct(Request $req){
        $product=new Product;
        $product->name=$req->input('name');
        $product->price=$req->input('price');
        $product->type=$req->input('type');
        $product->description=$req->input('description');
        $product->file_path=$req->file('file')->store('products',['max_file_size'=>'4MB']);
        $product->save();
        // return response()->json(['message'=>'Product added successfully'],200);
        return $product;
    }
    
    function getProducts(){
        if(Auth::guard('api')->check()){
            $products=Product::all();
            return response()->json(['products'=>$products],200);
        }else{
            return response()->json(['message'=>'Unauthorized'],401);
        }
    }
}
