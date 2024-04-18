<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $per_page = $request->per_page ?? 10;
        $skip = $page * $per_page;
        $products = Product::where('status', 1);
        $total_page = round($products->count() / $per_page);
        $res = [
            'success'    => true,
            'total_page' => $total_page,
            'data'       => $products->skip($skip)->take($per_page)->get(),
        ];

        return response()->json($res);
    }

    public function store(Request $request)
    {
        $item_id = $request->item_id ;
        $category = $request->category ;
        $cat = new Product();
        $cat->user_id = auth()->id() ;
        $cat->title = $request->title ;
        $cat->price = $request->price ;
        $cat->description = $request->description ;
        $cat->category_id = $category['id'] ;
        $cat->save() ;
        $res =  [
            'success'=>true,
            'message'=>'ثبت کالا با موفقیت صورت پذیرفت',
        ] ;

        return response()->json($res) ;
    }
}