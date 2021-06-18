<?php

namespace App\Http\Controllers;

use App\LoginSecurity;
use Auth;
use Hash;
use Illuminate\Http\Request;

class LoginSecurityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show 2FA Setting form
     */
    // public function show2faForm(Request $request){
    //     $user = Auth::user();
    //     $google2fa_url = "";
    //     $secret_key = "";

    //     if($user->loginSecurity()->exists()){
    //         $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
    //         $google2fa_url = $google2fa->getQRCodeInline(
    //             'MyNotePaper Demo',
    //             $user->email,
    //             $user->loginSecurity->google2fa_secret
    //         );
    //         $secret_key = $user->loginSecurity->google2fa_secret;
    //     }

    //     $data = array(
    //         'user' => $user,
    //         'secret' => $secret_key,
    //         'google2fa_url' => $google2fa_url
    //     );

    //     return view('auth.2fa_settings')->with('data', $data);
    // }

    // /**
    //  * Generate 2FA secret key
    //  */
    // public function generate2faSecret(Request $request){
    //     $user = Auth::user();
    //     // Initialise the 2FA class
    //     $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

    //     // Add the secret key to the registration data
    //     $login_security = LoginSecurity::firstOrNew(array('user_id' => $user->id));
    //     $login_security->user_id = $user->id;
    //     $login_security->google2fa_enable = 0;
    //     $login_security->google2fa_secret = $google2fa->generateSecretKey();
    //     $login_security->save();

    //     return redirect('/2fa')->with('success',"Secret key is generated.");
    // }

    /**
     * Enable 2FA
     */
    // public function enable2fa(Request $request){
    //     $user = Auth::user();
    //     $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

    //     $secret = $request->input('secret');
    //     $valid = $google2fa->verifyKey($user->loginSecurity->google2fa_secret, $secret);

    //     if($valid){
    //         $user->loginSecurity->google2fa_enable = 1;
    //         $user->loginSecurity->save();
    //         return redirect('2fa')->with('success',"2FA is enabled successfully.");
    //     }else{
    //         return redirect('2fa')->with('error',"Invalid verification Code, Please try again.");
    //     }
    // }


   public function show2faForm(Request $request){
        $user = Auth::user();
        $google2fa_url = "";
        $secret_key = "";

        if($user->loginSecurity()->exists()){
            $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
            $google2fa_url = $google2fa->getQRCodeInline(
                'MyNotePaper Demo',
                $user->email,
                $user->loginSecurity->google2fa_secret
            );
            $secret_key = $user->loginSecurity->google2fa_secret;
        }

        $data = array(
            'user' => $user,
            'secret' => $secret_key,
            'google2fa_url' => $google2fa_url
        );

        return view('auth.2fa_settings')->with('data', $data);
    }

    /**
     * Generate 2FA secret key
     */
    public function generate2faSecret(Request $request){
        $user = Auth::user();
        // Initialise the 2FA class
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

        // Add the secret key to the registration data
        $login_security = LoginSecurity::firstOrNew(array('user_id' => $user->id));
        $login_security->user_id = $user->id;
        $login_security->google2fa_enable = 0;
        $login_security->google2fa_secret = $google2fa->generateSecretKey();
        $login_security->save();

        return redirect('/2fa')->with('success',"Secret key is generated.");
    }
    

    public function enable2fa(Request $request){
        $user = Auth::user();
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

        $secret = $request->input('secret');
        $valid = $google2fa->verifyKey($user->loginSecurity->google2fa_secret, $secret);

        if($valid){
            $user->loginSecurity->google2fa_enable = 1;
            $user->loginSecurity->save();
            return redirect('admin')->with('success',"2FA is enabled successfully.");
        }else{
            return redirect('2fa')->with('error',"Invalid verification Code, Please try again.");
        }
    }


    /**
     * Disable 2FA
     */
    public function disable2fa(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your password does not matches with your account password. Please try again.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
        ]);
        $user = Auth::user();
        $user->loginSecurity->google2fa_enable = 0;
        $user->loginSecurity->save();
        return redirect('/2fa')->with('success',"2FA is now disabled.");
    }
	


    ///////////////////////////////////////////////////////////////////////
	// CUSTOM 
	// enable 
	// public function enable(Request $request)
 //    {
       
 //        $user = Auth::user();
 //        $google2fa_url = "";
 //        $secret_key = "";

 //        if($user->loginSecurity()->exists()){
 //            $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
 //            $google2fa_url = $google2fa->getQRCodeInline(
 //                'MyNotePaper Demo',
 //                $user->email,
 //                $user->loginSecurity->google2fa_secret
 //            );
 //            $secret_key = $user->loginSecurity->google2fa_secret;
 //        }

 //        $data = array(
 //            'user' => $user,
 //            'secret' => $secret_key,
 //            'google2fa_url' => $google2fa_url
 //        );

 //        return view('2fa_enable')->with('data', $data);

 //    }

 //    // Generate secret
 //    public function generateSecret(Request $request)
 //    {
 //        $user = Auth::user();
 //        // Initialise the 2FA class
 //        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

 //        // Add the secret key to the registration data
 //        $login_security = LoginSecurity::firstOrNew(array('user_id' => $user->id));
 //        $login_security->user_id = $user->id;
 //        // $login_security->google2fa_enable = 0;
 //        $login_security->google2fa_enable = 1;
 //        $login_security->google2fa_secret = $google2fa->generateSecretKey();
 //        $login_security->save();

 //        return redirect('2fa/enable')->with('success',"Secret key is generated and 2fa Activated");
 //    }

 //    public function enableme(Request $request){
 //        $user = Auth::user();
 //        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

 //        $user->loginSecurity->google2fa_enable = 1;
 //        $user->loginSecurity->save();
 //        return redirect('2fa/enable')->with('success',"2FA is enabled successfully.");
 //    }

 //    public function disable(Request $request)
 //    {
 //        $user = Auth::user();
 //        $user->loginSecurity->google2fa_enable = 0;
 //        $user->loginSecurity->save();
 //        return redirect('/2fa/enable')->with('success',"2FA is now disabled.");
 //    }    
 
 
 
 
 
 
 /////////////////////////////////////
 public function enableTwoFactor(Request $request)
    {
        //generate new secret
        $secret = $this->generateSecret();
        
        //get user
        $user = $request->user();
        
        //encrypt and then save secret
        // Add the secret key to the registration data
         $login_security = LoginSecurity::firstOrNew(array('user_id' => $user->id));
         $login_security->user_id = $user->id;         
         $login_security->google2fa_enable = 1;
         $login_security->google2fa_secret = $secret;
         $login_security->save();
		
		$google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
		$google2fa_url = $google2fa->getQRCodeInline(
			'MyNotePaper Demo',
			$user->email,
			$user->loginSecurity->google2fa_secret
		);
		$secret_key = $user->loginSecurity->google2fa_secret;
		
		

        
		return view('2fa/enableTwoFactor', ['image' => $google2fa_url,'secret' => $secret_key]);
		
		
    }
	
	public function generateSecret()
    {
         // Initialise the 2FA class
         $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
		 return $google2fa->generateSecretKey();
    }
	public function enableTwoFactorvalidate(Request $request){
		return redirect('admin')->with('success',"2FA is enabled successfully.");
		
		
        $user = Auth::user();
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

        $secret = $request->input('secret');
        $valid = $google2fa->verifyKey($user->loginSecurity->google2fa_secret, $secret);

        if($valid){
            
            return redirect('admin')->with('success',"2FA is enabled successfully.");
        }else{
            return redirect('/2fa/validate')->with('error',"Invalid verification Code, Please try again.");
        }
    }
 
}