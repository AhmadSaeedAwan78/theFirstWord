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
class Admin extends Controller
{
    
    public function edit_form ($form_id)
    {
        $form = DB::table('forms')->where('id', $form_id)->first();
        //print_r($form);exit;
        return view('forms.edit_form_info', compact('form'));
    }
    
    public function edit_form_act (Request $request, $form_id)
    {
        $title = $request->name;
        $id    = $request->id;
        
        $validatedData = $request->validate([
                'name' => 'required',
        ]); 
        
        DB::table('forms')
            ->where('id', $id)
            ->update(['title' => $title]);
            
        return redirect('Forms/AdminFormsList');
        
    }     
    
    public function site_admins ()
    {
        if (Auth::user()->role == 1)
        {
            $users = User::where('role',1)->get();
            
            return view('admin.users.site_admins', compact('users'));
        }
        else
        {
            return abort('404');
        }
    }
    
    public function add_admin ()
    {
        if (Auth::user()->role == 1)
        {
            return view('admin.users.add_admin');
        }
        else
        {
            return abort('404');
        }        
    }
    
    public function edit_admin($id) 
    { 
        if(Auth::user()->role==1) {
            $user = User::find($id);
            return view('admin.users.edit_admin', compact("user"));
        }
        else {
            return redirect('dashboard');
        }
    }
    
    public function edit_admin_act (Request $request , $id) 
    { 

            // dd($request->all());
            $data = User::where("id", $request->input("id"))->first();  
            
            $name     = $request->name;
            $password = $request->password;
            
            // if ($request->hasFile('images'))
            // {
            //     $request->validate([
            //         'images' => 'dimensions:max_width=800,max_height=600',
            //     ]); 
                
            //     $image_size = $request->file('images')->getsize();
                 

            //     if ( $image_size > 1000000 ) 
            //     {
            //         return redirect('edit_admin/'.$id)->with('alert', 'Maximum size of Image 1MB!')->withInput();            
            //     }                
            // }

            $test = $data->image_name;
            $inputs = [
                'password' => $password,
            ];
            
            $rules = [
                'password' => [
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                ],
            ];
    $validation = \Validator::make( $inputs, $rules );

    if($request->password!=""){
    if ( $validation->fails() ) {
          return redirect('edit_admin/'.$id)->with('alert', 'Password must be Min 8 Characters, Alphanumeric with an Upper and lower case!')->withInput();            
           }elseif($request->password != $request->rpassword)
                {
            return redirect('edit_admin/'.$id)->with('alert', 'Password did not match!');
                } 
                else{      
           
            // if($request->hasfile('images')){
            //     $destinationpath=public_path("img/$test");
            //     File::delete($destinationpath);
            //     $file=$request->file('images');
            //     $filename = str_replace(' ', '', $file->getClientOriginalName());
            //     $ext=$file->getClientOriginalExtension();
            //     $imgname=uniqid().$filename;
            //     $destinationpath=public_path('img');
            //     $file->move($destinationpath,$imgname);
            // }
                    if($request->base_string ){
                        // dd('yess');
                $ext = explode('/', mime_content_type($request->base_string))[1];
                $img = $request->base_string;
                     $file_name = 'image_'.time().'.jpg';
                     @list($type, $img) = explode(';', $img);
                     @list(, $img)      = explode(',', $img);
                     if($img!=""){
                       \Storage::disk('public')->put($file_name,base64_decode($img));
                       File::move(storage_path().'/app/public/'.$file_name , 'public/img/'.$file_name); 
                        $imgname = $file_name;

                     }
          
                 }


            else{
                        // dd('noo');

                $imgname =$request->profile_image;
            }
            $record = array(
           "name" => $request->input('name'),
           "image_name" => $imgname,
        );
        if($request->input('password')) { 
            $record['password'] = bcrypt($request->input('password'));
        }
        if($request->input('id')) {
            
            User::where("id", $request->input("id"))->update($record);
            $insert_id = $request->input("id");
        } else { 
            $insert_id =  User::insertGetId($record);
        }            
        $fa = User::where("id", $request->input("id"))->first();
       
            return redirect("site_admins");
        }
    }
    else
    {
         if($request->password != $request->rpassword)
         {
            return redirect('users/edit/'.$id)->with('alert', 'Password did not match!');
         } 
         else
         {      
            if($request->hasfile('images'))
            {
                $destinationpath=public_path("img/$test");
                File::delete($destinationpath);
                $file=$request->file('images');
                $filename = str_replace(' ', '', $file->getClientOriginalName());
                $ext=$file->getClientOriginalExtension();
                $imgname=uniqid().$filename;
                $destinationpath=public_path('img');
                $file->move($destinationpath,$imgname);
            }
            else
            {
                $imgname =$request->profile_image;
            }
        
            $record = array(
               "name" => $request->input('name'),
               "image_name" => $imgname,
               "tfa" => 0,           
            );

            if($request->input('password')) 
            { 
                $record['password'] = bcrypt($request->input('password'));
            }
        
            if($request->input('id')) 
            {
                User::where("id", $request->input("id"))->update($record);           
                $insert_id = $request->input("id");
            
            } 
            else 
            { 
                $insert_id =  User::insertGetId($record);
            }

            $fa = User::where("id", $request->input("id"))->first();

            return redirect("site_admins");
        }
    }
    }    
    
    public function add_admin_act (Request $request)
    {
        // dd($request->all());
        $email = $request->input('email');
        $name  = $request->input('name');
        $pswrd = $request->input('password');

        $test = DB::table('users')->where('email','=',$email)->first();
        
        // if ($request->hasFile('images')) {
            
        //     $request->validate([
        //         'images' => 'dimensions:max_width=800,max_height=600',
        //     ]);             
           
        //     $image_size = $request->file('images')->getsize();
        
        //     if ( $image_size > 1000000 ) {
        //         return redirect('add_admin')->with('alert', 'Maximum size of Image 1MB!')->withInput();            
        //     }            
         
        // }

        $inputs = [
        'password' => $pswrd,
                ];
         $rules = [
        'password' => [
            'required',
            'string',
            'min:8',              // must be at least 8 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
        ],
    ];
    $validation = \Validator::make( $inputs, $rules);

    if ($validation->fails()) 
    {
         return redirect('add_admin')->with('alert', 'Password must be Min 8 Characters, Alphanumeric with an Upper and lower case!')->withInput();            
    }
    elseif($pswrd != $request->rpassword)
    {
        return redirect('add_admin')->with('alert', 'Password did not match!')->withInput();
    }
    elseif (empty($test)) {
        
        $imgname ='';
        
        // if($request->hasfile('images')){
        //     $file=$request->file('images');
        //     $filename = str_replace(' ', '', $file->getClientOriginalName());
        //     $ext=$file->getClientOriginalExtension();
        //     $imgname=uniqid().$filename.'.'.$ext;
        //     $destinationpath=public_path('/img');
        //     $file->move($destinationpath,$imgname);
        // }

         if($request->base_string ){
                $ext = explode('/', mime_content_type($request->base_string))[1];
                $img = $request->base_string;
                     $file_name = 'image_'.time().'.jpg';
                     @list($type, $img) = explode(';', $img);
                     @list(, $img)      = explode(',', $img);
                     if($img!=""){
                       \Storage::disk('public')->put($file_name,base64_decode($img));
                       File::move(storage_path().'/app/public/'.$file_name , 'public/img/'.$file_name); 
                        $imgname = $file_name;

                     }
          
            }
            // print_r($imgname);exit();

        $data = array(
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "role" => 1,
            "image_name" => $imgname,
            "tfa" => 0,
            "client_id" => 0,
            "created_by" =>Auth::user()->id,
        );
            
        if($request->input('password')) { 
            $data['password'] = bcrypt($request->input('password'));
        }

        if($request->input('id')) {
            User::where("id", $request->input("id"))->update($data);
            $insert_id = $request->input("id");
        } else { 
            $insert_id =  User::insertGetId($data);
        }
        \Session::flash('success', Lang::get('general.success_message'));
        return redirect('admin');
        }
        else
        {
            return redirect('add_admin')->with('alert', 'Email already exists!')->withInput();
        }   
    }
    
}