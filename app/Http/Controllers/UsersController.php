<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Notification;
use App\Friend;
use App\Who;
use App\Where;
use App\Airport;
use App\Message;
use App\Plan;
use App\How;
use App\PasswordSecurity;
use Auth;
use Lang;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use DB;
use Artisan;
use App\Helper\Helper;
class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }



    // permissions

    public function permissions($id)
    {   
        // dd('sad');
        $granted_permissions;
        $granted_permissions = DB::table('module_permissions_users')->where('user_id' , $id)->first();
        if($granted_permissions == null)
             {
             $granted_permissions = [' ' , ' '];
             // dd($granted_permissions);
             }
         elseif ($granted_permissions != null) {
                 # code...
                 $granted_permissions = explode(',',$granted_permissions->allowed_module);
                 // dd($granted_permissions);
             }    
        $permissions = DB::table('module_permissions')->pluck('module');
         $user = Auth::user()->role;
        // dd($user);
        if($user == 1){
            $user_type = 'admin';
        }
        else{
            $user_type = 'client';
        }
        return view('admin.users.permission_add_remove' , compact('permissions' , 'granted_permissions' , 'user_type' , 'id'));
    }

    public function permissions_store(Request $request){
        // dd($request->all());
       $is_assigned_any_permissions = DB::table('module_permissions_users')->where('user_id' , $request->id)->first();
       if($is_assigned_any_permissions != null){
       $data = $request->permiss;
       // dd($data);
       if($data == null)
       {
              $data = ['nodata , nodata'];
       }
            $new = implode(',', $data);
            $result = DB::table('module_permissions_users')->where('user_id' , $request->id)->update([ 
                "user_id" => $request->id,
               "allowed_module" => $new
            ]);

            \Session::flash('success', Lang::get('Permission set for user'));
             return redirect('admin');
            // dd($result . 'record updated');
       }
       elseif ($is_assigned_any_permissions == null) {
            # code...
            $data = $request->permiss;
            if($data == null)
               {
                     $data = ['nodata , nodata'];
               }
             $new = implode(',', $data);
            $result = DB::table('module_permissions_users')->insert([ 
                "user_id" => $request->id,
               "allowed_module" => $new
            ]);
            \Session::flash('success', Lang::get('Permission set for user'));
             return redirect('admin');

        } 
       
  

    }
    // 
    public function index() 
    {      
            return redirect('dashboard');
        
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
            return view('admin.users.add_admin', compact('users'));
        }
        else
        {
            return abort('404');
        }        
    }
    
    public function add_admin_act (Request $request)
    {
        $email = $request->input('email');
        $name  = $request->input('name');
        $pswrd = $request->input('password');

        $test = DB::table('users')->where('email','=',$email)->first();
        
        if ($request->hasFile('images')) {
           
            $image_size = $request->file('images')->getsize();
            
            $request->validate([
                'images' => 'dimensions:max_width=800,max_height=600',
            ]);             
        
            if ( $image_size > 1000000 ) {
                return redirect('users/add')->with('alert', 'Maximum size of Image 1MB!')->withInput();            
            } 
            
           
         
        }

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
         return redirect('users/add')->with('alert', 'Password must be Min 8 Characters, Alphanumeric with an Upper and lower case!')->withInput();            
    }
    elseif($pswrd != $request->rpassword)
    {
        return redirect('users/add')->with('alert', 'Password did not match!')->withInput();
    }
    elseif (empty($test)) {
        $imgname ='';
        if($request->hasfile('images')){
            $file=$request->file('images');
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $ext=$file->getClientOriginalExtension();
            $imgname=uniqid().$filename.'.'.$ext;
            $destinationpath=public_path('/img');
            $file->move($destinationpath,$imgname);
        }
            // print_r($imgname);exit();
        if($slider== "on"){
        $data = array(
           "name" => $request->input('name'),
           "email" => $request->input('email'),
           "role" => 2,
           "image_name" => $imgname,
           "tfa" => 1,
           "client_id" => 0,
           "created_by" =>Auth::user()->id,
        );
        }else{
             $data = array(
           "name" => $request->input('name'),
           "email" => $request->input('email'),
           "role" => 2,
           "image_name" => $imgname,
           "tfa" => 0,
            "client_id" => 0,
            "created_by" =>Auth::user()->id,
        );
        }
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
            return redirect('users/add')->with('alert', 'Email already exists!')->withInput();
        }   
    }
    

    public function edit($id) 
    {  

        if(Auth::user()->role==1) {
         $user = User::find($id);
        return view('admin.users.edit', compact("user"));
    }else{
        return redirect('dashboard');
        }
    }

    public function addUser($user_id = '') 
    {


        $fixed_company = false;
        $client = DB::table('users')->where('role',4)->get();
        if (!empty($user_id))
        {
            $client = $client->where('id', $user_id)->first();
            $fixed_company = true;
        }
        
        return view('admin.users.addUser', compact('client', 'fixed_company'));
    }

    public function addClient() 
    { 
        // echo "string";exit();
        return view('admin.users.addClient');
    }

    public function store(Request $request) 
    { 

       

        // dd($request->all());
        // dd('walla00');
            // $img = Helper::resizeImage(‘/path/to/some/image.jpg’, 200, 200);
        $slider = $request['slider'];
        $clientid = Input::get('team');
        $value = $request['optradio'];
        $any = $request->input('email');
        $test = DB::table('users')->where('email','=',$any)->first();
        
        // if ($request->hasFile('images')) {
            
        //     // $request->validate([
        //     //     'images' => 'dimensions:max_width=800,max_height=600',
        //     // ]);            
           
        // $image_size = $request->file('images')->getsize();
        
        //     if ( $image_size > 1000000 ) {
        //       return redirect('users/add')->with('alert', 'Maximum size of Image 1MB!')->withInput();            
        //    }            
         
        // }
        
        $inputs = [
        'password' => $request->password,
                ];
         $rules = [
        'password' => [
            'required',
            'string',
            'min:8',             // must be at least 8 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
        ],
    ];
    $validation = \Validator::make( $inputs, $rules );

    if ( $validation->fails() ) {
          return redirect('users/add')->with('alert', 'Password must be Min 8 Characters, Alphanumeric with an Upper and lower case!')->withInput();            
           }elseif($request->password != $request->rpassword)
                {
            return redirect('users/add')->with('alert', 'Password did not match!')->withInput();
                }
        elseif (empty($test)) {
            $imgname =NULL;
            // if($request->hasfile('images')){
                
            //     // $request->validate([
            //     //     'images' => 'dimensions:max_width=800,max_height=600',
            //     // ]);                 
                
            //     $file = $request->file('images');
            //     $filename = str_replace(' ', '', $file->getClientOriginalName());
            //     $ext=$file->getClientOriginalExtension();
            //     $img = Helper::resizeImage($file ,800, 600);
            //     $imgname = uniqid().".".$ext;
            //     // $ext = $file->getClientOriginalExtension();
            //     $path = public_path('/img/'.$imgname);
            //     imagejpeg($img, $path);
            //     // $imgname=uniqid().$filename.'.'.$ext;
            //     // $destinationpath=public_path('/img');
            //     // $file->move($destinationpath,$imgname);
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
        if($slider== "on"){
        $data = array(
           "name" => $request->input('name'),
           "email" => $request->input('email'),
        //   "company"=> $request->input('company'),
           "role" => 2,
           "image_name" => $imgname,
           "tfa" => 1,
            "client_id" => $clientid,
            "created_by" =>Auth::user()->id,
        );
        }else{
             $data = array(
             "name" => $request->input('name'),
             "email" => $request->input('email'),
        //   "company"=> $request->input('company'),
             "role" => 2,
             "image_name" => $imgname,
             "tfa" => 0,
             "client_id" => $clientid,
            "created_by" =>Auth::user()->id,
        );
        }
        if($request->input('password')) { 
            $data['password'] = bcrypt($request->input('password'));
        }


        if($request->input('id')) {
            User::where("id", $request->input("id"))->update($data);
            $insert_id = $request->input("id");
        } else { 
            $insert_id =  User::insertGetId($data);
            // dd($insert_id);
            DB::table('module_permissions_users')->insert([
                 'user_id' => $insert_id,
                 'allowed_module' => 'My Assigned Forms,Manage Forms,Completed Forms,SAR Forms,SAR Forms Submitted,SAR Forms pending,Users Management,Global Data Inventory,Detailed Data Inventory,Assets List,Activities List,Incident Register,Sub Forms Expiry Settings,SAR Expiry Settings,Generated Forms',
            ]);
        }

        \Session::flash('success', Lang::get('general.success_message'));
        return redirect('admin');
        }
        else
        {
            return redirect('users/add')->with('alert', 'Email already exists!')->withInput();
            
        }          
    }

    
    public function clientStore(Request $request) 
    {   
       
        // dd($request->all());


        // dd('KJDHKLJASHDJKLAHSD');
        // dd($request);
        $slider = $request['slider'];        
        $value = $request['optradio'];
        $any = $request->input('email');
        $test = DB::table('users')->where('email','=',$any)->first();
        $company = $request->input('company');
        $company_check = DB::table('users')->where('company','=',$company)->first();
  
        // if($request->hasfile('images')){
        //     // $request->validate([
        //     //     'images' => 'dimensions:max_width=400,max_height=240',
        //     // ]);             
            
        //     $image_size = $request->file('images')->getsize();
        //     if ( $image_size > 1000000 ) {
        //         return redirect('client/add')->with('alert', 'Maximum size of Image 1MB!')->withInput();            
        //     }            
        // }
        // if($request->base_string){


        // }
        $file_name = NULL;  
        if ( $company_check) {
          return redirect('client/add')->with('alert', 'Enter the Unique Company Name!')->withInput();
          }else{
            $imgname ='';
            if($request->base_string != null){
                $ext = explode('/', mime_content_type($request->base_string))[1];
                $img = $request->base_string;
                     $file_name = 'image_'.time().'.jpg';
                     @list($type, $img) = explode(';', $img);
                     @list(, $img)      = explode(',', $img);
                     if($img!=""){
                       \Storage::disk('public')->put($file_name,base64_decode($img));
                       File::move(storage_path().'/app/public/'.$file_name , 'public/img/'.$file_name);                  
                     }
                
            }
           

          
            // dd('moved');
          
            $data = array(
                  "name"=> $request->input('company'),
                  "company" => $request->input('company'),
                  "website" => $request->input('website'),
                  "phone" => $request->input('phone'),
                  "role" => 4,
                  "image_name" => $file_name,
                  "tfa" => 0,
                  "created_by" => Auth::user()->id,
                );
        
       
        }
        if($request->input('id')) {
            User::where("id", $request->input("id"))->update($data);
            $insert_id = $request->input("id");
        } else { 
            $insert_id =  User::insertGetId($data);
        }
        
        $client_id = $insert_id;

		$all_forms = DB::table('forms')->get();
		
		$expiry_time    = date('Y-m-d H:i:s', strtotime("+10 days"));
		
		$insert_data = [];
		
		foreach ($all_forms as $form)
		{
			$insert_data = ['client_id' => $client_id, 'form_id' => $form->id];
			
			if ($form->code == 'f10') 
			{
				DB::table('sub_forms')->insert(['title'          => $form->title, 
												'client_id'      => $client_id, 
												'parent_form_id' => $form->id,
												'expiry_time'    => $expiry_time]);
			}	
			DB::table('client_forms')->insert($insert_data);
		}
		
        
        \Session::flash('success', Lang::get('general.success_message'));
        return redirect('company');


    //     $inputs = [
    //     'password' => $request->password,
    //             ];
    //      $rules = [
    //     'password' => [
    //         'required',
    //         'string',
    //         'min:8',             // must be at least 8 characters in length
    //         'regex:/[a-z]/',      // must contain at least one lowercase letter
    //         'regex:/[A-Z]/',      // must contain at least one uppercase letter
    //         'regex:/[0-9]/',      // must contain at least one digit
    //     ],
    // ];
    // $validation = \Validator::make( $inputs, $rules );

    // if ( $validation->fails() ) {
    //       return redirect('client/add')->with('alert', 'Password must be Min 8 Characters, Alphanumeric with an Upper and lower case!')->withInput();            
    //       }elseif($request->password != $request->rpassword)
    //             {
    //         return redirect('client/add')->with('alert', 'Password did not match!')->withInput();
    //             }
    //     elseif (empty($test)) {
    //         $imgname ='';
    //         if($request->hasfile('images')){
    //             $file=$request->file('images');
    //             $filename = str_replace(' ', '', $file->getClientOriginalName());
    //             $ext=$file->getClientOriginalExtension();
    //             $imgname=uniqid().$filename;
    //             $destinationpath=public_path('/img');
    //             $file->move($destinationpath,$imgname);
    //         }
    //         // print_r($imgname);exit();
    //   if($slider== "on"){
    //     $data = array(
    //       "name" => $request->input('name'),
    //       "email" => $request->input('email'),
    //       "company"=> $request->input('company'),
    //       "role" => 2,
    //       "image_name" => $imgname,
    //       "tfa" => 1,
    //     );
    //     }else{
    //          $data = array(
    //       "name" => $request->input('name'),
    //       "email" => $request->input('email'),
    //       "company"=> $request->input('company'),
    //       "role" => 2,
    //       "image_name" => $imgname,
    //       "tfa" => 0,
    //     );
    //     }
    //     if($request->input('password')) { 
    //         $data['password'] = bcrypt($request->input('password'));
    //     }


    //     if($request->input('id')) {
    //         User::where("id", $request->input("id"))->update($data);
    //         $insert_id = $request->input("id");
    //     } else { 
    //         $insert_id =  User::insertGetId($data);
    //     }
    //     \Session::flash('success', Lang::get('general.success_message'));
    //     return redirect('admin');
    //     }
    //     else
    //     {
    //         return redirect('client/add')->with('alert', 'Email already exists!')->withInput();
    //     }          
    }

    public function edit_store(Request $request , $id) 
    { 
            // dd($request->all());
            $slider = $request['slider'];
            $data = User::where("id", $request->input("id"))->first();    
            
            // if ($request->hasFile('images'))
            // {
            //     $request->validate([
            //         'images' => 'dimensions:max_width=800,max_height=600',
            //     ]);                 
                
            //     $image_size = $request->file('images')->getsize();

            //     if ( $image_size > 1000000 ) {
            //         return redirect('users/edit/'.$id)->with('alert', 'Maximum size of Image 1MB!')->withInput();            
            //     }                
                
            // }

            $test = $data->image_name;
            $inputs = [
        'password' => $request->password,
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
          return redirect('users/edit/'.$id)->with('alert', 'Password must be Min 8 Characters, Alphanumeric with an Upper and lower case!')->withInput();            
           }elseif($request->password != $request->rpassword)
                {
            return redirect('users/edit/'.$id)->with('alert', 'Password did not match!');
                } 
                else{    

                  
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
            else{
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
        if($fa->tfa==0){                
                DB::table('password_securities')->where('user_id',$id)->delete();
            }
            return redirect("/category");
        }
    }else{
         if($request->password != $request->rpassword)
                {
            return redirect('users/edit/'.$id)->with('alert', 'Password did not match!');
                } 
                else{      
            if($request->hasfile('images')){
                $request->validate([
                    'images' => 'dimensions:max_width=800,max_height=600',
                ]);                 
                
                $destinationpath=public_path("img/$test");
                File::delete($destinationpath);
                $file=$request->file('images');
                $filename = str_replace(' ', '', $file->getClientOriginalName());
                $ext=$file->getClientOriginalExtension();
                $imgname=uniqid().$filename;
                $destinationpath=public_path('img');
                $file->move($destinationpath,$imgname);
            }
            else{
                $imgname =$request->profile_image;
            }

            if($slider=="on"){
            $record = array(
           "name" => $request->input('name'),
           "image_name" => $imgname,
           "tfa" => 1,           
        );
        }else{
            $record = array(
           "name" => $request->input('name'),
           "image_name" => $imgname,
           "tfa" => 0,           
        );
        }

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
        if($fa->tfa==0){                
                DB::table('password_securities')->where('user_id',$id)->delete();
            }
            return redirect("/category");
        }
    }
    }


    
    public function change_status(Request $request) 
    { 
         $data = array(
           "status" => $request->input('status')
          );
         User::where("id", $request->input("id"))->update($data);  
    }

    public function destroy(Request $request) 
    { 
        $id = $request->input("id");
      
      $data = DB::table('users')->where('id',$id)->first();
    //   print_r($data);exit;
      $test = $data->image_name;
      $destinationpath=public_path("img/$test");
      File::delete($destinationpath);
        
        User::where("id", $id)->delete();
       
    }

    public function dashboard ()
    {
           // Artisan::call('cache:clear');
           // Artisan::call('route:clear');
           // Artisan::call('config:clear');
           // Artisan::call('config:cache');
           
            
        // dd('walla');
        $client_id = auth()->user()->client_id;
        $lat_value[] = '';
        $lat_detail[] = '';

        $lat_lng = DB::table('assets')->where('lat','!=','')
        ->where('client_id',$client_id)
        ->get();
        // dd($lat_lng);

        if($lat_lng != ''){
            foreach($lat_lng as $lat_val){
                 $lang = $lat_val->lng;
                 $lng = number_format((float)$lang, 6, '.', '');
                 $lng = floatval($lng);
                 $late = $lat_val->lat;
                 $lat = number_format((float)$late, 6, '.', '');
                 $lat = floatval($lat);
                $lat_value[] =array($lat_val->country , $lat, $lng);
                $lat_detail[] = array($lat_val->country , $lat_val->city, $lat_val->state , $lat_val->name ,$lat_val->hosting_provider , $lat_val->asset_type);
            }
        }
            // dd($lat_value);
      if(Auth::user()->role != 3){
                    if (Auth::user()->role == 3 && Auth::user()->user_type != '1') {
                        return redirect(route('client_user_subforms_list'));
                    }
                }
        $id = Auth::user()->client_id;
        
        // ================================================================================================================= //
                                                    /* PENDING SAR REQUEST ALERTS */
        
        //SELECT *, DATEDIFF(due_date, NOW()) FROM `sar_requests`
        $incomplete_sar_requests = DB::table('sar_requests')
                                     ->selectRaw('*, DATEDIFF(due_date, NOW()) AS days_left')
                                     ->where('status', 0)
                                     ->where('client_id', $id)
                                     ->where(DB::raw('DATEDIFF(due_date, NOW())'), '<=', '10')
                                     ->orderBy('days_left');
                                    // ->limit(1);
        
        $days_left = $incomplete_sar_requests->limit(1)->pluck('days_left')->first();

        $incomplete_sar_requests_counts = $incomplete_sar_requests->count();
        
        $sar_pending_request_info = [
                                        'days_left' => $days_left, 
                                        'request_count' => $incomplete_sar_requests_counts,
                                    ];
        
        
                                                    /* PENDING SAR REQUEST ALERTS */
        // ================================================================================================================= //
         
                        
        // ================================================================================================================= //
                                                    /* DASHBOARD COUNTS */
        
        $org_users_count           = DB::table('users')->where('client_id', $id)->count();
        $ext_users_count           = DB::table('external_users_forms')->where('client_id', $id)->distinct('email')->count();
        $total_users               = $org_users_count + $ext_users_count;
        
        $subforms_count            = DB::table('sub_forms')->where('client_id',$id)->where('title','!=','SAR Form')->count();
        
        $forms_count               = DB::table('client_forms')->where('client_id', $id)->count();
        
        $ext_sent_forms_count      = DB::table('external_users_forms')->where('client_id',$id)->count();
        $int_sent_forms_count      = DB::table('user_forms')->where('client_id', $id)->count();
        $total_shared_forms        = $ext_sent_forms_count + $int_sent_forms_count;
 
        $ext_completed_forms_count = DB::table('external_users_forms')->where('client_id',$id)->where('is_locked', 1)->count();
        $int_completed_forms_count = DB::table('user_forms')->where('client_id', $id)->where('is_locked', 1)->count();
        $total_completed_forms     = $ext_completed_forms_count + $int_completed_forms_count; 
        
        $e_incomplete_forms_count  = DB::table('external_users_forms')->where('client_id',$id)->where('is_locked', 0)->count();
        $i_incomplete_forms_count  = DB::table('user_forms')->where('client_id', $id)->where('is_locked', 0)->count();
        $total_incomplete_forms    = $e_incomplete_forms_count + $i_incomplete_forms_count;

        $int_sar_completed_forms   = DB::table('user_forms')->where('user_forms.client_id' , $id)
                                        ->join('sub_forms', 'sub_forms.id', '=', 'user_forms.sub_form_id')
                                        ->join('forms', 'sub_forms.parent_form_id', '=', 'forms.id')
                                        ->where('forms.code', '=', 'f10')
                                        ->where('user_forms.is_locked', '=', '1')
                                        ->count();

       

        $ext_sar_completed_forms   = DB::table('external_users_forms')->where('external_users_forms.client_id' , $id)
                                        ->join('sub_forms', 'sub_forms.id', '=', 'external_users_forms.sub_form_id')
                                        ->join('forms', 'sub_forms.parent_form_id', '=', 'forms.id')
                                        ->where('forms.code', '=' , 'f10')									   
                                        ->where('external_users_forms.is_locked', '=', '1')
                                        ->count();


        // dd($ext_sar_completed_forms);
        $total_sar_completed_forms = $int_sar_completed_forms + $ext_sar_completed_forms;


        $int_sar_incomplete_forms   = DB::table('user_forms')
                                        ->join('sub_forms', 'sub_forms.id', '=', 'user_forms.sub_form_id')
                                        ->join('forms', 'sub_forms.parent_form_id', '=', 'forms.id')
                                        ->where('forms.code', '=', 'f10')
                                        ->where('user_forms.is_locked', '=', '0')
                                        ->where('user_forms.client_id', $id)
                                        ->count();
									   
									   
									   
        $ext_sar_incomplete_forms   = DB::table('external_users_forms')
                                        ->join('sub_forms', 'sub_forms.id', '=', 'external_users_forms.sub_form_id')
                                        ->join('forms', 'sub_forms.parent_form_id', '=', 'forms.id')
                                        ->where('forms.code', '=' , 'f10')									   
                                        ->where('external_users_forms.is_locked', '=', '0')
                                        ->where('external_users_forms.client_id', $id)
                                        ->count();

        // SELECT sub_forms.id as subform_id FROM `sub_forms` JOIN forms on sub_forms.parent_form_id = forms.id WHERE type = 'sar' and client_id = 120
        $sar_subform_id = DB::table('sub_forms')->select('sub_forms.id as subform_id')->join('forms', 'sub_forms.parent_form_id', '=', 'forms.id')->where('type', '=', 'sar')->where('client_id', '=', Auth::user()->client_id)->pluck('subform_id')->first();

	    $total_sar_incomplete_forms = $int_sar_incomplete_forms + $ext_sar_incomplete_forms;	
		
		
        $total_incident_register_forms   = DB::table('incident_register')->where('organization_id', $id)->count();		
		

        // add filter by client id
        $external_user_activities  = DB::table('external_users_filled_response')
                                       ->whereIn('external_user_form_id', DB::table('external_users_forms')
                                       ->where('client_id', $id)->pluck('id'))
                                       ->whereIn('question_id', DB::table('questions')
                                       ->where('question', 'like', '%What activity are you assessing%')->pluck('id'))
                                       ->distinct('question_response')
                                       ->count();
        // add filter by client id
        $internal_user_activities  = DB::table('internal_users_filled_response')
                                        ->whereIn('user_form_id', DB::table('user_forms')
                                       ->where('client_id', $id)->pluck('id'))
                                       ->whereIn('question_id', DB::table('questions')
                                       ->where('question', 'like', '%What activity are you assessing%')->pluck('id'))
                                       ->distinct('question_response')
                                       ->count(); 
                                       
        $total_activities = $external_user_activities + $internal_user_activities;
        
        $sar_completed_requests = DB::table('sar_requests')
                                    ->where('status', 1)
                                    ->where('client_id', $id)
                                    ->count();
                                    // dd($sar_completed_requests);
                                    // dd('walla');
		
        $sar_incomplete_requests = DB::table('sar_requests')
                                    ->where('status', 0)
                                    ->where('client_id', $id)
                                    ->count();		
                                       
                                       
                                                    /* DASHBOARD COUNTS */
        // ================================================================================================================= //

        // ================================================================================================================= //
                                                        /* BAR CHART */ 
                                                        
        // SELECT sub_forms.title, forms.title, user_forms.user_id, COUNT(user_forms.user_id) AS user_count
        // from   sub_forms
        // JOIN   user_forms ON user_forms.sub_form_id = sub_forms.id
        // JOIN   forms      ON sub_forms.parent_form_id = forms.id
        // GROUP 
        // BY     forms.title                                                

        $num_of_internal_users          = DB::table('sub_forms')
                                        ->join('user_forms', 'user_forms.sub_form_id', '=', 'sub_forms.id')
                                        ->join('forms', 'sub_forms.parent_form_id', '=', 'forms.id')
                                        ->where('user_forms.client_id', $id)
                                        ->select(DB::raw('forms.id, sub_forms.title as subform_title, forms.title as form_title, user_forms.user_id, COUNT(user_forms.user_id) AS user_count'))
                                        ->groupBy('forms.id')
                                        ->orderBy('forms.id')
                                        ->get();
                                        
        // SELECT sub_forms.title, forms.title, external_users_forms.user_email, COUNT(external_users_forms.user_email) AS user_count
        // from   sub_forms
        // JOIN   external_users_forms ON external_users_forms.sub_form_id = sub_forms.id
        // JOIN   forms      ON sub_forms.parent_form_id = forms.id
        // WHERE  external_users_forms.client_id = 120
        // GROUP 
        // BY     forms.title   
        
        $num_of_external_users          = DB::table('sub_forms')
                                        ->join('external_users_forms', 'external_users_forms.sub_form_id', '=', 'sub_forms.id')
                                        ->join('forms', 'sub_forms.parent_form_id', '=', 'forms.id')
                                        ->where('external_users_forms.client_id', $id)
                                        ->select(DB::raw('forms.id, sub_forms.title as subform_title, forms.title as form_title, external_users_forms.user_email, COUNT(external_users_forms.user_email) AS user_count'))
                                        ->groupBy('forms.id')
                                        ->orderBy('forms.id')
                                        ->get(); 
                                        
        $num_of_form_users = [];
        
        foreach ($num_of_internal_users as $key => $int_form_info)
        {
            $num_of_form_users[$int_form_info->id]['name']     = $int_form_info->form_title;
            $num_of_form_users[$int_form_info->id]['internal'] = $int_form_info->user_count;
            $num_of_form_users[$int_form_info->id]['total']    = $int_form_info->user_count;
        }
        
        foreach ($num_of_external_users as $key => $ext_form_info)
        {
            $num_of_form_users[$ext_form_info->id]['name']     = $ext_form_info->form_title;
            $num_of_form_users[$ext_form_info->id]['external'] = $ext_form_info->user_count;
            $num_of_form_users[$ext_form_info->id]['total']    = $ext_form_info->user_count + ((isset($num_of_form_users[$ext_form_info->id]['total']))?($num_of_form_users[$ext_form_info->id]['total']):(0));
        }        

        // echo "<pre>";
        // print_r($num_of_internal_users);
        // echo "</pre>"; 
        
        // echo "<pre>";
        // print_r($num_of_external_users);
        // echo "</pre>";        
                                        
        // echo "<pre>";
        // print_r($num_of_form_users);
        // echo "</pre>";
        // exit;        
   
                                                        /* BAR CHART */        
        // ================================================================================================================= //
        
        // ================================================================================================================= //
                                                        /* STATS TABLE */        
        
        // SELECT   forms.id, forms.title, 
        //          COUNT(sub_forms.id) AS total_forms, 
        //          SUM(CASE WHEN is_locked = 1 THEN 1 ELSE 0 END) AS completed,
        //          SUM(CASE WHEN is_locked = 0 THEN 1 ELSE 0 END) AS not_completed        
        // FROM     forms
        // JOIN     sub_forms  ON forms.id     = sub_forms.parent_form_id
        // JOIN     user_forms ON sub_forms.id = user_forms.sub_form_id
        // group BY forms.id
        // ORDER by forms.title
        
        $int_user_forms = DB::table('forms')
                            ->join('sub_forms',  'forms.id',      '=', 'sub_forms.parent_form_id')
                            ->join('user_forms', 'sub_forms.id',  '=', 'user_forms.sub_form_id')
                            ->where('user_forms.client_id', '=', $id)
                            ->select(DB::raw('forms.id, forms.title, 
                                              COUNT(sub_forms.id) AS subforms_count, 
                                              SUM(CASE WHEN is_locked = 1 THEN 1 ELSE 0 END) AS completed,
                                              SUM(CASE WHEN is_locked = 0 THEN 1 ELSE 0 END) AS not_completed'))
                            ->groupBy('forms.id')
                            ->orderBy('forms.title')
                            ->get();
        
        $ext_user_forms = DB::table('forms')
                            ->join('sub_forms',  'forms.id',      '=', 'sub_forms.parent_form_id')
                            ->join('external_users_forms', 'sub_forms.id',  '=', 'external_users_forms.sub_form_id')
                            ->where('external_users_forms.client_id', '=', $id)
                            ->select(DB::raw('forms.id, forms.title, 
                                              COUNT(sub_forms.id) AS subforms_count, 
                                              SUM(CASE WHEN is_locked = 1 THEN 1 ELSE 0 END) AS completed,
                                              SUM(CASE WHEN is_locked = 0 THEN 1 ELSE 0 END) AS not_completed'))
                            ->groupBy('forms.id')
                            ->orderBy('forms.title')
                            ->get();
                            
        $form_completion_stats = [];
        
        foreach ($int_user_forms as $key => $int_user_form)
        {
            $form_completion_stats[$int_user_form->id]['form_name']      =  $int_user_form->title;
            //$form_completion_stats[$int_user_form->id]['subforms_count'] =  $int_user_form->subforms_count;            
            $form_completion_stats[$int_user_form->id]['internal']       = ['completed' => $int_user_form->completed, 'not_completed' => $int_user_form->not_completed]; 
        }
        
        foreach ($ext_user_forms as $key => $ext_user_form)
        {
            $form_completion_stats[$ext_user_form->id]['form_name']      =  $ext_user_form->title;
            //$form_completion_stats[$ext_user_form->id]['subforms_count'] =  $ext_user_form->subforms_count;            
            $form_completion_stats[$ext_user_form->id]['external']       = ['completed' => $ext_user_form->completed, 'not_completed' => $ext_user_form->not_completed]; 
        }
        
        // SELECT forms.title, count(DISTINCT sub_forms.id) FROM `forms`
        // JOIN sub_forms ON forms.id = sub_forms.parent_form_id
        // WHERE client_id = 159
        // GROUP by forms.id

        $main_forms_count = DB::table('forms')
                            ->join('sub_forms',  'forms.id',      '=', 'sub_forms.parent_form_id')
                            ->where('client_id', '=', $id)
                            ->select(DB::raw('forms.id, forms.title, 
                                              COUNT(sub_forms.id) AS subforms_count'))
                            ->groupBy('forms.id')
                            ->get();
                            
        foreach ($main_forms_count as $fcount)
        {
            if (isset($form_completion_stats[$fcount->id]))
            {
                $form_completion_stats[$fcount->id]['subforms_count'] =  $fcount->subforms_count;            
            }
        }
        
        // echo "<pre>";
        // print_r($form_completion_stats);
        // echo "</pre>";
        // exit;
        

                                                        /* STATS TABLE */        
        // ================================================================================================================= //        
        
        return view('home' , compact("total_users",
                                    "org_users_count",
                                    "ext_users_count",
                                    "subforms_count", 
                                    "forms_count", 
                                    "total_shared_forms",
                                    "total_completed_forms",
                                    "total_incomplete_forms",
                                    "num_of_form_users",
                                    "form_completion_stats",
                                    "total_activities",
                                    "sar_subform_id",
                                    "total_sar_completed_forms",
                                    "total_sar_incomplete_forms",
                                    "total_incident_register_forms",
                                    "sar_pending_request_info",
                                    "sar_completed_requests",
                                    "sar_incomplete_requests",
                                    "lat_value",
                                    "lat_detail"));
    }
    
    public function company()
    {
        if (Auth::user()->role != 1)
        {
            return abort('404');
        }
       //$users = User::where('role',4)->get();
       $users = DB::table('users')->where('role',4)->get()->toArray();
       // get number of users against each company
       $users_count = DB::select('SELECT   c.id, c.company, u.role, count(u.role) as role_count
                                  FROM     users u JOIN users c ON u.client_id = c.id
                                  GROUP BY company, role');
                                  
        $user_ids = array_column($users_count, 'id');
        
        $roles_count = ['Administrators' => 0, 'Users' => 0];
                                  
        foreach ($users as $key => $company)
        {
            $users[$key]->users_count = $roles_count;
            if (($id_index = array_search($company->id, $user_ids)) !== false)
            {
                while (isset($user_ids[$id_index]) && $company->id == $user_ids[$id_index])
                {
                    switch ($users_count[$id_index]->role)
                    {
                        case '2':   // update administrators count in array
                            $users[$key]->users_count['Administrators'] = $users_count[$id_index]->role_count;
                            break;
                        case '3':  // update users count in array
                            $users[$key]->users_count['Users']          = $users_count[$id_index]->role_count;
                            break;
                    }
                    $id_index++;
                }
            }
        }
       
        return view('admin.users.company', compact("users"));  
    }
    
    public function edit_company($id)
    {
        if(Auth::user()->role==1) {
         $user = User::find($id);
        return view('admin.users.company_edit', compact("user"));
    }else{
        return redirect('dashboard');
        }
    }
    
    public function editCompany_store(Request $request, $id)
    {


           // dd($request->all());
            $slider = $request['slider'];
            // dd($slider);
            $data = User::where("id", $request->input("id"))->first(); 
            // dd($data);  
            
            // if ($request->hasFile('images')) {
            //     $request->validate([
            //         'images' => 'dimensions:max_width=400,max_height=240',
            //     ]); 
            //     $image_size = $request->file('images')->getsize();
        
            //     if ( $image_size > 1000000 ) {
            //         return redirect('users/edit_company/'.$id)->with('alert', 'Maximum size of Image 1MB!')->withInput();            
            //     }  
            // }
            

            
            
                
            $test = $data->image_name;
            $imgname = null;
    //         $inputs = [
    //     'password' => $request->password,
    //             ];
    //      $rules = [
    //     'password' => [
    //         'string',
    //         'min:8',             // must be at least 8 characters in length
    //         'regex:/[a-z]/',      // must contain at least one lowercase letter
    //         'regex:/[A-Z]/',      // must contain at least one uppercase letter
    //         'regex:/[0-9]/',      // must contain at least one digit
    //     ],
    // ];
    // $validation = \Validator::make( $inputs, $rules );

    // if($request->password!=""){
    // if ( $validation->fails() ) {
    //       return redirect('users/edit/'.$id)->with('alert', 'Password must be Min 8 Characters, Alphanumeric with an Upper and lower case!')->withInput();            
    //       }elseif($request->password != $request->rpassword)
    //             {
    //         return redirect('users/edit/'.$id)->with('alert', 'Password did not match!');
    //             } 
                // if{   

            // if($request->hasfile('images')){
            //     $request->validate([
            //         'images' => 'dimensions:max_width=800,max_height=600',
            //     ]);                 
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
                $imgname = $data->image_name;
            }
            $record = array(
           "name"       => $request->input('name'),
           "company"    => $request->input('name'),
           "phone"      => $request->input('phone'),
           "website"    => $request->input('website'),
           "image_name" => $imgname,
        );
        // if($request->input('password')) { 
        //     $record['password'] = bcrypt($request->input('password'));
        // }
        if($request->input('id')) {
            
            User::where("id", $request->input("id"))->update($record);
            $insert_id = $request->input("id");
        } else { 
            $insert_id =  User::insertGetId($record);
        }            
        // $fa = User::where("id", $request->input("id"))->first();
        // if($fa->tfa==0){                
        //         DB::table('password_securities')->where('user_id',$id)->delete();
        //     }
            return redirect("/company");
        // }
    // }else{
        //  if($request->password != $request->rpassword)
                // {
        //     return redirect('users/edit/'.$id)->with('alert', 'Password did not match!');
        //         } 
        //         else{      
            if($request->hasfile('images')){
                $request->validate([
                    'images' => 'dimensions:max_width=800,max_height=600',
                ]);                 
                $destinationpath=public_path("img/$test");
                File::delete($destinationpath);
                $file=$request->file('images');
                $filename = str_replace(' ', '', $file->getClientOriginalName());
                $ext=$file->getClientOriginalExtension();
                $imgname=uniqid().$filename;
                $destinationpath=public_path('img');
                $file->move($destinationpath,$imgname);
            }
            else{
                $imgname =$request->profile_image;
            }

            if($slider=="on"){
            $record = array(
           "name" => $request->input('name'),
           "image_name" => $imgname,
        );
        }else{
            $record = array(
           "name" => $request->input('name'),
           "image_name" => $imgname,
        );
        }

        // if($request->input('password')) { 
        //     $record['password'] = bcrypt($request->input('password'));
        // }
        if($request->input('id')) {

            
            User::where("id", $request->input("id"))->update($record);           
            $insert_id = $request->input("id");
            
        } else { 
            $insert_id =  User::insertGetId($record);
        }

    //   $fa = User::where("id", $request->input("id"))->first();
        // if($fa->tfa==0){                
        //         DB::table('password_securities')->where('user_id',$id)->delete();
        //     }
            return redirect("/company");
        // }
    }
        
    // }
    
    
}
