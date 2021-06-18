<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Auth;


// 
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\ValidateSecretRequest;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo() {
        $user = auth()->user();
        switch($user->role) {
            case 1:
                return '/category';
            default:
                return '/dashboard';
        }
    }

    // public function showLoginForm()
    // {
    //     if(!session()->has('url.intended'))
    //     {
    //         session(['url.intended' => url()->previous()]);
    //     }
    //     // dd('asdasd');
    //     return view('auth.login');
    // }

    private function authenticated(Request $request, Authenticatable $user)
    {

        // dd(Auth::user());
        // admin
        // dashboard

        if( Auth::user()->role == 1 )
        {
            // redirect to login if loggedIn.
            return redirect('/category');
            
        } elseif( Auth::user()->role == 2 ) {
            // company 
            return redirect('/dashboard');
        }

        return redirect('dashboard');
        
    }




}
