<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use Hash;

use Session;

use App\User;

use App\Cart;

use App\Ticket;

use App\Package;

use App\Faq;

use App\Blog;

use DB;

use Sentinel;
use Rminder;
use Mail;
use App\PasswordSecurity;

class AdminController extends Controller
{
 public function competition()

    {

        $data['featuredProducts']=Package::where('featured',1)->limit(6)->inRandomOrder()->get();

        $data['products']=Package::where('featured',0)->latest()->get();

        return view('activecompition',$data);
        // $fa=PasswordSecurity::where('user_id',Auth::user()->id)->first();
        // if($fa->google2fa_enable==0){
        //     redirect('/2fa');
        // }



    }

        public function index()
    {
        return view('home');
    }

  

    public function cart()

    {

        $data['title']='cart';

        return view('page', $data);

    } 

    public function contact()

    {

        return view('contact');

    }



     public function winners()

 

    {

        $data['title']='Winners';

        return view('winners', $data);

    } 



     public function profile()

    {

        if (!Auth::check())

        {

           return redirect('/');

        }

        $data['title']='Profile';

        return view('profile', $data);

    } 



    public function product_ticket()

    {

        if (!Auth::check())

        {

           return redirect('/');

        }

        $user_id=Auth::id();

        $cart=Cart::where('user_id','=',$user_id)->get();

        // dd($cart);

        foreach ($cart as $key => $value) {

           Ticket::where(['id'=>$value->ticket_id])->update(['status'=>1]);

        }

        Cart::where('user_id','=',$user_id)->delete();

        $tickets = Ticket::where('user_id','=',$user_id)->where('status',1)->orderBy('code','DESC')->get();

        // echo '<pre>';print_r($tickets);exit;

        return view('product_ticket',compact('tickets'));

    }



    public function faqs()

    {

        $title='FAQS';

        $faqs=Faq::orderBy('id','DESC')->limit(5)->get();

        return view('faqs',compact('title','faqs'));



    }

    public function blogshow()

    {

        $title='Blog';

        $blog = Blog::orderBy('id','DESC')->limit(5)->get();

        return view('blogshow',compact('title','blog'));

    }



   public function register_user(Request $request)

    {

        $email=User::where('email', $request->email)->first();

        if($email)

            return ['status'=>0,'msg'=>'Email already in use'];

        if($request->password != $request->repeat)

            return ['status'=>0,'msg'=>'Password did not match'];

        $refer = $request->refer;

        $user = new User;

        if($refer !='')

        {

            $user->referrer = $refer;

        }

        $user->password = Hash::make($request->password);

        $user->email = $request->email;

        $user->name = $request->name;

        $user->phone = $request->phone;

        $user->status = 1;

        $user->user_role = 0;

        $success=$user->save();

        if($success){

           Auth::login($user);

           return ['status'=>1,'msg'=>'Succesfully Registered'];

        }else{

            return ['status'=>0,'msg'=>'User not registered'];

        }

    }



    public function login(Request $request)

    {

         $email     = $request->email;

         $password  = $request->password;
         
         $data = $request->only('email', 'password');

        $test = DB::table('users')->where('email',$email)->first();
        // print_r($test);exit();

        if($test->user_role==2){


            if (Auth::attempt($data)) {

                $user = Auth::user();

                 Auth::login($user);

                 return ['status'=>1,'msg'=>'Succesfully Loggedin'];

            } else {

                return ['status'=>0,'msg'=>'User not found'];

            }

        }else{

            return ['status'=>0,'msg'=>'User are not autherize'];

        }

    }





        public function adminlogin(Request $request)

    {

        // $data=bcrypt(123);

        // print_r($data);exit();

         $email     = $request->email;

         $password  = $request->password;

         $test = $request->only('email', 'password');

         $data = DB::table('users')->where('email',$email)->first();

         // print_r($data);exit();

            

        if($data->user_role==1){



        if (Auth::attempt($test)) {



            $user = Auth::user();

             Auth::login($user);

             $test =$data->user_role;

             // print_r($test);exit();

             return ['status'=>1,'msg'=>'Succesfully Loggedin', compact('test')];

        }else{

                return ['status'=>0,'msg'=>'User not found'];

             }

        

        }else{

                return ['status'=>0,'msg'=>'User not found'];

             }

    }



    public function livecompetition()

    {

        return view('livecompition');

    }

    public function terms()

    {

        return view('terms');

    }

    public function privacy()

    {

        return view('privacy');

    }

    public function subscriber()

    {

        return view('subscriber');

    }

    public function howtoplay()

    {

        return view('howplay');

    }

    public function reset(Request $request)
    {
        $email = $request->email;
        $user = User::where('email',$email)->first();
        if($user == null)
        {
            return ['status'=>0,'msg'=>'User not found'];
        }else{
            $user = Sentinel::findById($user->id);
            $reminder = Reminder::exist($user)? : Reminder::create($user);
            $this->sendEmail($user, $reminder->code);

            return ['status'=>1,'msg'=>'User not found'];            
        }
    }

    public function sendEmail($user, $code) 
    {
        Mail::send(
            'email.forget',
            ['user'=>$user, 'code'=>$code],
            function($message) use ($user){
                $message->to($user->email);
                $message->subject(" user->name, reset your password");
            });
    }

    public function merchant($user, $merchant)
    {
        $data = DB::table('users')->where('name',$merchant)->first();
        return view('login', compact('data'));
    }    

}