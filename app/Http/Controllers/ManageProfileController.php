<?php

/**
 * UIDAI
 *
 * PHP Version 7.1
 * 
 * @category   Manage Profile
 * @author    Choice Tech Lab <contact@choicetechlab.com>
 * @copyright 2017-2018 Choice Tech Lab (https://choicetechlab.com)
 * @license   https://choicetechlab.com/licenses/ctl-license.php CTL General Public License
 * @version  1.0.1
 * @package App\Http\Controllers\ManageProfileController
 * @link      https://choicetechlab.com/
 */ 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;
use Auth;
use Toast;


/**
 * This class provides a all operation to manage the Profile of user
 *
 * The ManageProfileController is responsible for managing the user basic details
 */

class ManageProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

   /**
     * Display User details in edit Mode
     *
     * @return \Illuminate\Http\Response
     * This Function Provide the User details in edit mode
     * 
     * @var string  $user_id to fetch user id and details
     *  * @var string  $manage_profile to find user id and details
     * @return json response for audit checklist list.
     */
   public function index()
   {   
    try { 
        $user_id = Auth::user()->id;

        $roles = Role::select('roles.*','departments.name as department_name')
        ->join('departments','departments.id','=','roles.department_id')
        ->where(['roles.deleted_at'=> Null,'departments.deleted_at'=>Null])
        ->get();  

        $manage_profile = User::find($user_id);
       
        return view('manage_profile.manage_profile',compact('manage_profile','roles'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            $error = $this->getErrorString($e);
            Log::error($error);
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
        }
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

     /**
     * Update the specified User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
       * This Function Use To Update User Data
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success audit checklist list.
     * 
     * @var string $user Should contain list of data return by the query of respected id. 
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $user to fetch data from id
     * 
     */
     public function update(Request $request, $id)
     {
        try { 

            $user = User::find($id);

            //dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'. $user->id,
                 //'email' => 'required',
                
            ]);

            if ($validator->fails()) {
             
                return Redirect::to('manage-profile')
                ->withErrors($validator)
                ->withInput();
            } else {

                if (User::find($id)->update($request->all())){
                     Toast::success('Profile Data Updated Successfully');
                    return redirect('manage-profile');
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $error = $this->getErrorString($e);
            Log::error($error);
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

     /**
     * Update the specified User password.
     *
     * @param  \Illuminate\Http\Request  $s
     * @param  int  $id
       * This Function Use To Update User Data
     * Pseudo Steps:<br>
     * 1) Using validator class fetch inputs from $request and add required fields.<br>
     * 2) If Validation fails return to the same view if validation is success pass array of requested data to create.<br>
     * 3) Redirect to listing page with suscces or error message <br>
     * @param array[] $request request structure to get the post data for storing in to the database
     * 
     * @return json result for success audit checklist list.
     * 
     * @var string $error if any error occur then it will be store in this variable.
     * @var string $validator is used to make the fields as required and if Validation failed flash message of Error will be diplayed
     * @var array[] $user to fetch data from id
     * 
     */
    public function change_password(Request $request){
      try {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);

        //check input password with already regitered password
        if (!(Hash::check($request->password, Auth::user()->password))) {

            Toast::error('Your Current password does not matches with the Password you provided.');
            return redirect('manage-profile');
        }
        //compare old password and new password and if it is same throw flash message 
        if(strcmp($request->password, $request->new_password) == 0){

            Toast::error('New Password cannot be same as your current password.');
            return redirect('manage-profile');

        }       
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->save();
        Toast::success('Your Password changed Successfully!');
        return redirect('manage-profile');
         } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
        }
    }


    public function change_password_for_admin(Request $request){
        try {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);
        //Change Password
        $user = User::find($request->user_id);
        $user->password = bcrypt($request->new_password);
        $user->save();
        return redirect()->back()->with("success","User Password Changed Successfully !");
         } catch (\Exception $e) {
            Log::critical($e->getMessage());
            app('sentry')->captureException($e);
        }
    }

}
