<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class CategoryController extends Controller
{
    //
    public function category_list ()
    {
       
            $category = DB::table('categories')->get();
        foreach($category as $cat){
            $item=DB::table('items')->where('category_id',$cat->id)->get();
            $cat->itemslist=$item;
        }
            
            return response()->json([
                'data' => $category
            ]);
     
      
    }
}
