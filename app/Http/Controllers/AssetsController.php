<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\User;
use Validator;

class AssetsController extends Controller
{
    public function __construct()
    {

   
    }
    
    public function index ()
    {   
        $user = Auth::user()->id;
            $assigned_permissions =array();
            $data = DB::table('module_permissions_users')->where('user_id' , $user)->pluck('allowed_module');

            if($data != null){
                 foreach ($data as $value) {
                $assigned_permissions = explode(',',$value);
                 
            }
            }
            // if(Auth::user()->role != 3 ){
            if(!in_array('Assets List', $assigned_permissions)){
                return redirect('dashboard');
            }
        // }
         
         
        $client_id = Auth::user()->client_id;
        $asset_list = DB::table('assets')->whereNotNull('name')->where('client_id', $client_id)->orderBy('name','ASC')->get();
        $countries = DB::table('countries')->get();
        
        return view('assets.assets', ['asset_list' => $asset_list, 'countries' => $countries, 'user_type' => (Auth::user()->role == 1)?('admin'):('client')]);
    }
    
    public function add_asset (Request $request)
    {
        $asset = $request->input('asset');
        $client_id = Auth::user()->client_id;
        $status = 'error';
        $title  = 'The asset could not be added';
        $msg    = 'Something went wrong while inserting the asset';      
        
        if (DB::table('assets')->where('name', trim($asset))->exists())
        {
            $status = 'error';
            $title  = 'Duplicate Asset';
            $msg    = 'This asset is already present';
        }
        else
        {
            if (DB::table('assets')->insert(['name' => trim($asset) , 'client_id' => $client_id]))
            {
                $status = 'success';
                $title  = 'Asset Added';
                $msg    = 'Asset Added successfully..!';                
            }
        }
 

        return response()->json(['status' => $status, 'title' => $title, 'msg' => $msg]);
    }

    public function asset_update(Request $request){
        $name = $request->name;

        if (DB::table('assets')->where('name',$request->first_name)->update(['name' => $name]))
            {
                return redirect()->back()->with('message','Asset Updated successfully..!');                
            }

        return redirect()->back()->with('message','Asset Updated Unsuccessfully..!');
    }

    public function asset_delete($id){

        if (DB::table('assets')->where('id',$id)->delete())
            {
                return redirect()->back()->with('message','Asset Delete successfully..!');                
            }

        return redirect()->back()->with('message','Asset Delete Unsuccessfully..!');
    }

    public function asset_add(Request $request){
        // dd($request);
        $request->validate([
            'name' => 'required|max:255',
            'hosting_provider' => 'required',
        ],
        [
            'name.required' => 'Please provide proper name to proceed.',
            'hosting_provider.required' => 'Please Provide Hosting Provider.'
        ],
    );
        // dd($request);

        // $latts = Session::get('latz');
        // dd($latts);
        // $value = $_COOKiE["lat"];
        // dd($value);
        $client_id = Auth::user()->client_id;

        if (DB::table('assets')->where('name', $request->name)->where('client_id' , '=' , $client_id)->exists())
        {
        $status = 'error';
        $title  = 'Already Exists';
        $msg    = 'The requested asset was already exists';            

        return response()->json(['status' => $status, 'title' => $title, 'msg' => $msg]);
        // return response()->json($request);
        }

        DB::table('assets')->insert([
                'asset_type' => $request->asset_type,
                'name' => $request->name ,
                'hosting_type' => $request->hosting_type ,
                'hosting_provider' => $request->hosting_provider ,
                'country' => $request->country ,
                'city' => $request->city , 
                'state' => $request->state , 
                'lng' => $request->lng , 
                'lat' => $request->lat , 
                'client_id' => $client_id

            ]);
        // return redirect()->back()->with('message','Added successfully..!');
        $status = 'success';
        $title  = 'Removed';
        $msg    = 'The requested asset was successfully removed';            

        return response()->json(['status' => $status, 'title' => $title, 'msg' => $msg]);
        return response()->json($request);
        // dd($request->all());

        // $country_select = $request->country;
        // $city1 = $request->city;

        // $response = array('GET', "https://maps.googleapis.com/maps/api/geocode/json?address=pakistan+Lahore&key=AIzaSyDaCml5EZAy3vVRySTNP7_GophMR8Niqmg");
        // dd($response);

        // $validator = Validator::make($request->all(), [ 
             
        //     'asset_type' => 'required', 
        //     'hosting_provider' => 'required', 
        //     'country' => 'required'
        // ]);
        // $request->validate([
        //             'asset_type' => 'required', 
        //             'name' => 'required',
        //             'hosting_provider' => 'required', 
        //             'country' => 'required'
        //     ]);
        // if ($validator->fails()) { 
        //     return redirect()->back()->json(['message'=>$validator->errors()], 401);        
        // }

        dd('walla');

        $client_id = Auth::user()->client_id;

        if (DB::table('assets')->where('client_id' , '!='  , $client_id)->where('name', $request->name)->exists())
        {
            return redirect()->back()->with('message','This asset is already present..!');
        }
        else
        {
            if($request->hasfile('image')){
                $image = $request->file('image');
                $imageName = time() . "." .$image->extension();
                $imagePath = public_path() . '/img';
                $image->move($imagePath, $imageName);
                $imageDbPath = $imageName;
            }

            if (DB::table('assets')->insert([
                'asset_type' => $request->asset_type,
                'name' => $request->name ,
                'hosting_type' => $request->hosting_type ,
                'hosting_provider' => $request->hosting_provider ,
                'country' => $request->country ,
                'city' => $request->city , 
                'state' => $request->state , 
                'lng' => $request->lng , 
                'lat' => $request->lat , 
                'client_id' => $client_id

            ]))

            {
                return redirect()->back()->with('message','Asset Added successfully..!');                
            }   
        }
        return redirect()->back()->with('message','Request Denide..!');

    }

    public function asset_edit($id){

        $data = DB::table('assets')->where('id',$id)->get()->first();
        $cont = DB::table('countries')->where('country_name',$data->country)->get();
        // dd($cont);
        $countries = DB::table('countries')->get();
        // dd($data);
        return view('assets.assets', ['data' => $data, 'cont' => $cont, 'countries' => $countries, 'user_type' => (Auth::user()->role == 1)?('admin'):('client')]);

    }

    public function update_asset(Request $request)
    {   
        $request->validate([
            'name' => 'required|max:255',
            'hosting_provider' => 'required',
        ],
        [
            'name.required' => 'Please provide proper name to proceed.',
            'hosting_provider.required' => 'Please Provide Hosting Provider.'
        ],
    );

        DB::table('assets')->where('id',$request->id)->update([
                'asset_type' => $request->asset_type,
                'hosting_type' => $request->hosting_type ,
                'hosting_provider' => $request->hosting_provider ,
                'country' => $request->country ,
                'city' => $request->city , 
                'lng' => $request->lng , 
                'lat' => $request->lat , 
                'state' => $request->state

            ]);

        $status = 'success';
        $title  = 'Removed';
        $msg    = 'The requested asset was successfully removed';            

        return response()->json(['status' => $status, 'title' => $title, 'msg' => $msg]);

        return response()->json($request);

        // $res_data =  Http::get('https://maps.googleapis.com/maps/api/geocode/json?address=Pakistan+Islamabad&key=AIzaSyDaCml5EZAy3vVRySTNP7_GophMR8Niqmg')->json();

        // dd($request->all());

        if($request->hasfile('image')){
                $image = $request->file('image');
                $imageName = time() . "." .$image->extension();
                $imagePath = public_path() . '/img';
                $image->move($imagePath, $imageName);
                $imageDbPath = $imageName;

                if (DB::table('assets')->where('id',$request->id)->update([
                'asset_type' => $request->asset_type,
                'hosting_type' => $request->hosting_type ,
                'hosting_provider' => $request->hosting_provider ,
                'country' => $request->country ,
                'city' => $request->city , 
                'state' => $request->state

            ])){
                    return redirect('assets')->with('message','Asset Updated successfully..!');
                }
            }
            else
            {
                if (DB::table('assets')->where('id',$request->id)->update([
                'asset_type' => $request->asset_type,
                'hosting_type' => $request->hosting_type ,
                'hosting_provider' => $request->hosting_provider ,
                'country' => $request->country ,
                'city' => $request->city , 
                'state' => $request->state

            ])){
                    return redirect('assets')->with('message','Asset Updated successfully..!');
                }
                return redirect('assets')->with('message','Asset Updated successfully..!');
            }
    }
    
    public function delete_asset (Request $request)
    {
        $asset_id = $request->id;
        $status = 'error';
        $title  = 'Unable to Delete';
        $msg    = 'You are not allowed to perform this operation';        
        
        
        DB::table('assets')->where('id', $asset_id)->delete();
        $status = 'success';
        $title  = 'Removed';
        $msg    = 'The requested asset was successfully removed';            

        return response()->json(['status' => $status, 'title' => $title, 'msg' => $msg]);
    }
}