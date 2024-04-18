<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $items = Category::where('status', 1);
        $res = [
            'success'    => true,
            'data'       => $items->select('id','title as label','id as value')->get(),
        ];

        return response()->json($res);
    }
}