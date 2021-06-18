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

class ParentsController extends Controller
{
    //
    public function index(){
        $data = DB::table('parents')->get();
        return view('admin.users.parents.index', compact('data'));
    }

    public function add_description(){
        return view('admin.users.parents.add');
    }

    public function store_description(Request $request){
        // dd($request);
        $this->validate($request, [
            'title' => 'required|max:30',
            'description' => 'required|max:1000',
        ]);
        $data = DB::table('parents')->insert([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        // dd($data);

        return redirect('description');
    }

    public function edit_description($id){
        $user = DB::table('parents')->where('id', $id)->first();
        return view('admin.users.parents.edit', compact('user'));
    }

    public function update_description(Request $request, $id){
        // dd($request);
        $this->validate($request, [
            'title' => 'required|max:30',
            'description' => 'max:1000',
        ]);
        $data = DB::table('parents')->where('id', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return redirect('description');
    }

    public function delete_description(Request $request){
        DB::table('parents')->where('id', $request->id)->delete();
        return redirect()->back();
    }
}
