<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\PasswordSecurity;
use Auth;
use Lang;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use DB;

class CategoriesController extends Controller
{
    //
    public function category ()
    {
        
        // if (Auth::user()->role == 1)
        // {
            $category = DB::table('categories')->get();

           // dd($category);
            
            return view('admin.users.site_admins', compact('category'));
        // }
      
    }

    public function add_category(){
        return view('admin.users.add_admin');
    }

    public function store_category(Request $request){

        $this->validate($request, [
            'name' => 'required|max:30',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($files = $request->file('image')) {
            $name=$files->getClientOriginalName();
            $image = time().'.'.$request->image->getClientOriginalExtension();
            // $request->image->move(public_path('storage/'), $image);
            $request->image->move(public_path() .'/assets/category', $image);
       }else{
        $request->image=$image;
       }


        $data = DB::table('categories')->insert([
            'name' => $request->name,
            'image' => $image,
        ]);
        // dd($data);
        return redirect('category');
    }

    public function edit_category($id){
        $user = DB::table('categories')->where('id', $id)->first();
        return view('admin.users.edit_admin', compact('user'));
    }

    public function update_category(Request $request, $id){
        // dd($request, $id);

        $this->validate($request, [
            'name' => 'required|max:30',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($files = $request->file('image')) {
            $name=$files->getClientOriginalName();
            $image = time().'.'.$request->image->getClientOriginalExtension();
            // $request->image->move(public_path('storage/'), $image);
            $request->image->move(public_path() .'/assets/category', $image);
       $request->image=$image;
        $data = DB::table('categories')->where('id', $id)->update([
           
            'name' => $request->name,
            'image' => $image,
            ]);
        }else{
        $data = DB::table('categories')->where('id', $id)->update([
           
            'name' => $request->name,
        ]);

    }

        return redirect('category');
    }

    public function delete_category(Request $request){
        DB::table('categories')->where('id', $request->id)->delete();
        return redirect()->back();
    }
}
