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

class ItemsController extends Controller
{
    //
    public function index($id){
        $data = DB::table('items')->where('category_id', $id)->get();
        return view('admin.users.items.index', compact('data','id'));
    }

    public function add_item($id){
        // dd($id);
        return view('admin.users.items.add', compact('id'));
    }

    public function store_item(Request $request){
        // dd($request);
        $this->validate($request, [
            'name' => 'required|max:30',
            'age' => 'required|max:10',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sound'=>'required|mimes:mp3,mp4,3gb|max:2048',
        ]);
        if ($files = $request->file('image')) {
            $name=$files->getClientOriginalName();
            $image = time().'.'.$request->image->getClientOriginalExtension();
            // $request->image->move(public_path('storage/'), $image);
            $request->image->move(public_path() .'/assets/items', $image);
       }else{
        $request->image=$image;
       }
       if ($files = $request->file('sound')) {
        $name=$files->getClientOriginalName();
        $sound = time().'.'.$request->sound->getClientOriginalExtension();
        // $request->sound->move(public_path('storage/'), $sound);
        $request->sound->move(public_path() .'/assets/sound', $sound);
    }else{
        $request->sound=$sound;
    }
        $data = DB::table('items')->insert([
            'age' => $request->age,
            'name' => $request->name,
            'image' => $image,
            'sound' => $sound,
            'category_id' => $request->category_id,
        ]);
        // dd($data);
        $id = $request->category_id;
        return redirect('items/'. $id);
    }

    public function edit_item($id){
        $user = DB::table('items')->where('id', $id)->first();
        return view('admin.users.items.edit', compact('user'));
    }

    public function update_item(Request $request, $id){
        // dd($request, $id);
        $this->validate($request, [
            'name' => 'required|max:30',
            'age' => 'required|max:10',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sound'=>'mimes:mp3,mp4,3gb|max:2048',
        ]);
        if ($files = $request->file('image')) {
            $name=$files->getClientOriginalName();
            $image = time().'.'.$request->image->getClientOriginalExtension();
            // $request->image->move(public_path('storage/'), $image);
            $request->image->move(public_path() .'/assets/items', $image);
       $request->image=$image;
        $data = DB::table('items')->where('id', $id)->update([
            'age' => $request->age,
            'name' => $request->name,
            'image' => $image,
            ]);
        }elseif($files = $request->file('sound')){
            $name=$files->getClientOriginalName();
            $sound = time().'.'.$request->sound->getClientOriginalExtension();
            // $request->sound->move(public_path('storage/'), $sound);
            $request->sound->move(public_path() .'/assets/items/sounds', $sound);
            $request->sound=$sound;
            $data = DB::table('items')->where('id', $id)->update([
                'age' => $request->age,
                'name' => $request->name,
               
                'sound' => $sound,
                ]);
        }else{
        $data = DB::table('items')->where('id', $id)->update([
            'age' => $request->age,
            'name' => $request->name,
        ]);
    }
    // dd($data);
    $id = $request->category_id;
    return redirect('items/'. $id);
    }

    public function delete_item(Request $request){
        DB::table('items')->where('id', $request->id)->delete();
        return redirect()->back();
    }
}
