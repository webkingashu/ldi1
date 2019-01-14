<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Mail;
use App\User;
use Session;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Toast;
use Redirect;

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
    public  $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
        // $this->user= Auth::user();

    }

    protected function redirectTo()
    {
        
      // return '/';
       if(\Auth::check()) { 
           return '/';
            //return 'otp';
        }    
        else { 
            return '/';
        }    
    }

    public function showLoginForm()
    { 
      
        if(\Auth::check()) { 
         
            if (session()->has('otp_verified')) {
                return redirect()->back();
              
            } else {
              return view('auth.otp');
            }
        } else {
        
        return view('auth.login');
        }
    }

       public function login(Request $request)
    {

       
    $users = User::select('email')->where(['deleted_at'=>NULL,'user_status'=>'Enable','email'=>$request->email])->first();
    
       if(isset($users) && !empty($users)) {
         $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
       } else {
         
          Toast::error('Your not authorized to perform this action. Please Contact to your Administrator.');
         return Redirect('login');
       }
        
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {

        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
     
    // $phone_number = User::select('phone_number')->where(['email'=>$request->email])->first()->phone_number;
    
    // if(isset($phone_number) && !empty($phone_number)){ 

    //  $otp_details = generateOtp($phone_number,$request->email);

    //  if(isset($otp_details) && !empty($otp_details) && $otp_details['code']== 200) {

    //    return view('auth.otp',compact($otp_details));

    //  } else {

    //     Toast::danger('Users Successfully Updated.');
    //     return redirect('/login');
    //  }

    // } 
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }



    // protected function redirectTo(){
      
    // //dd(Session::all());
    //    //  if(Auth::check()) { 
    //    //  //redirect('otp');
    //    //  Toast::success('Otp Send Successfully.');
    //    //  return Redirect('otp');
    //    // } else {
    //    //   return redirect()->back()->with('danger', 'Otp Validation failed1'); 
    //    // }

    //    if(\Auth::check()) {
    //     Toast::success('Otp Send Successfully.');
    //     return Redirect('otp');
    //     } else {
    //     return '/';
    //     }
    // }

    // public function sendOtp()
    // {
        
    //     $response = array();
    //     $userId = Auth::user()->id;

    //     $users = User::where('id', $userId)->first();

    //     // dd($users['email']);

    //     if ( !isset($users['email']) && $users['email'] =="" ) {
    //         $response['error'] = 1; 
    //         $response['message'] = 'Invalid Eamil Address';
    //         $response['loggedIn'] = 1;
    //     } else {
    //         $otp =  mt_rand(100000, 999999);
    //         $msg_response = $this->sendEmail($users['email'],$otp);

    //         // dd($msg_response);
    //         if($msg_response['error'] == 1) {
    //             $response['error'] = 1;
    //             $response['message'] = $msg_response['message'];
    //             $response['loggedIn'] = 1;
    //             return Redirect('/');
    //             //return view('auth.login');
    //         } else {
    //             // $otp->session();
    //             // Session::put('OTP', $otp);

    //             $users->otp_token = $otp;
    //             $users->save();

    //             return view('auth.otp');
    //         }
    //     }
        
    // }
    public function sendOtp()
    {
       
        if (!session()->has('otp_generated') || (session()->has('otp_generated') && time() - Session::get('otp_generated') > 500)) { // 300 seconds = 5 minutes
            
            if (\Auth::check() && !session()->has('otp_verified') ) {
            $userId = Auth::user()->id;
            $users = User::where(['id'=> $userId,'deleted_at'=>NULL,'user_status' => 'Enable'])->first();

                if (isset($users) && isset($users['email']) && !empty($users['email'])) {
                    
                    $data = array('otp' => mt_rand(100000, 999999),
                            'session_id' => uniqid(),
                            'user_id'=>$users['id'],
                            'email' => $users['email']);
                    $data['generated_at'] = date("Y-m-d H:i:s");
                    $data['expires_on'] = date('Y-m-d H:i:s', strtotime("{$data['generated_at']} + 15 minute"));
                          
                    //$mail_response = $this->sendEmail($users['email'],$data['otp']);
                    $email_list['email'] = array('email' =>$users['email'] );
                    $mail_response = sendMail($email_list,$subject='UIDAI New Login', $data);
                     
                    if(isset($mail_response) && !empty($mail_response['error']) && $mail_response['error'] == 1) {
                        
                       return redirect()->back()->with('danger', 'Something went wrong mail send.'); 
                    
                    } else {
                        if (DB::table('otp_details_log')->insert($data)) {    
                            session()->put('otp_generated', time());
                            session()->put('session_id', $data['session_id']);
                            session()->put('user_id', $data['user_id']);
                            session()->put('email', $data['email']);
                        
                            return view('auth.otp',compact('data')); 
                        } else {
                            session::flush(); 
                            return redirect()->back()->with('danger', 'Something went wrong mail send.'); 
                        }    
                    } 
                } else {
                   session::flush();   
                  return redirect()->back()->with('danger', 'User details not found.'); 
                } 
            } else {
               
               return redirect()->back();
            }
        } else {
         return view('auth.otp');
        } 
    }


    public function verifyOtp(Request $request)
    { 

            $this->validate($request, [
                'otp' => 'required|numeric'
            ]);

            $otp = $request->otp;
            $session_id = $request->session_id;
       
            $result = DB::table('otp_details_log')
                            ->where('otp', $otp)
                            ->where('session_id', $session_id)
                            ->where('status', 'N')
                            ->where('expires_on', '>=', date('Y-m-d H:i:s'))->get();
                           
            
            if (isset($result) && !$result->isEmpty()) {
                $result = DB::table('otp_details_log')
                        ->where('id', $result[0]->id)
                        ->update(['status' => 'V']);

                session()->put('otp_verified', true);
                Toast::success('Otp Verified Successfully.');
                return Redirect('dashboard');
            } else {
               // session::flush('otp_generated');   
                return redirect()->back()->with('danger', 'OTP is either invalid or expired.'); 
               // return Redirect('dashboard');
                //return response()->json(['status' => 'error', 'message' => 'OTP is either invalid or expired']);
                //return redirect()->back()->with('danger', 'OTP is either invalid or expired.'); 
            }
            
        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     return response()->json(['message' => 'Invalid request parameters']);
        // }

                // Updating user's status "isVerified" as 1.

    }

    // public function verifyOtp(Request $request)
    // {
    //     $this->validate($request, ['otp' => 'required|numeric']);
    //     $response = array();

    //     $enteredOtp = $request->input('otp');
    //     $userId = Auth::user()->id;  //Getting UserID.
    //     //Getting UserID.
    //     // dd($user_otp);
    //     if($userId == "" || $userId == null){
    //         $response['error'] = 1;
    //         $response['message'] = 'You are logged out, Login again.';
    //         $response['loggedIn'] = 0;
    //         session::flush();
    //         Toast::error('You are logged out, Login again.');
    //         return Redirect('/');
    //         //return view('/login');
    //     }else{
    //         $OTP = User::select('otp_token')->where('id', $userId)->first();
    //         if($OTP['otp_token'] === $enteredOtp){

    //             // Updating user's status "isVerified" as 1.

    //             User::where('id', $userId)->update(['isVerified' => 1]);

    //             //Removing Session variable
    //             // Session::forget('OTP');

    //             $response['error'] = 0;
    //             $response['isVerified'] = 1;
    //             $response['loggedIn'] = 1;
    //             $response['message'] = "Your Number is Verified.";
    //             session()->put('otp_verified', true);
    //             Toast::success('Otp Verified Successfully.');
    //              return Redirect('dashboard');
    //             //return redirect('/dashboard');
    //         } else {
    //             $response['error'] = 1;
    //             $response['isVerified'] = 0;
    //             $response['loggedIn'] = 1;
    //             $response['message'] = "OTP does not match.";
    //          return redirect()->back()->with('danger', 'Otp Validation failed'); 
    //            // return view('auth.otp');
    //         }
    //     }
    //     // echo json_encode($response);
    // }

    public function sendEmail($email,$otp){
        $isError = 0;
        $errorMessage = true;
        // dd($email);
        //Your message to send, Adding URL encoding.
        
     
        $data = [
            'session_id' => uniqid(),
            'email' => $email
        ];
        // dd($data);
        $OTP = $otp;
        $data['generated_at'] = date("Y-m-d H:i:s");
        $data['expires_on'] = date('Y-m-d H:i:s', strtotime("{$data['generated_at']} + 15 minute"));
        //Preparing post parameters
        $message = urlencode("Welcome to UIDAI Accounting System , Your OPT is : $OTP");

        $admin_subject = "UIDAI New Login";
        $send_mail = Mail::send('mail.mail',
            [
               'email' => $email,
               'admin_subject' => $admin_subject,
               'otp' => $OTP
             ],
        function ($message) use ($email,$otp,$admin_subject) {
          $message->to($email)->subject($admin_subject);
          $message->from('noreply@choicetechlab.com', 'UIDAI');
        });
        // dd($send_mail);
        if(Mail::failures()){
            $data['error'] = 1;
            $data['message'] = "Mail is not Sent !!";
            return $data;
        }else{
            $data['error'] = 0;
            $data['message'] = "Mail Sent !!";
            return $data;
        }
    }
}
