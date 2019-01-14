<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Toast;
use App\UserRoleMapper;
use App\RoleDepartmentMapper;
use DB;

class RegisterController extends Controller
{
   /*
   |--------------------------------------------------------------------------
   | Register Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the registration of new users as well as their
   | validation and creation. By default this controller uses a trait to
   | provide this functionality without requiring any additional code.
   |
   */


   use RegistersUsers;

   /**
    * Where to redirect users after login / registration.
    *
    * @var string
    */
   protected $redirectTo = '/home';

   /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function __construct()
   {
       //$this->middleware('guest');
        $this->middleware('auth');
   }

   /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
   protected function validator(array $data)
   {
      
       return Validator::make($data, [
           'name' => 'required|max:255',
           'email' => 'required|email|max:255|unique:users',
           //'phone_number' => 'required|max:10|unique:users',
           'password' => 'required|min:6|confirmed',
           'role_id'=>'required',
           'designation' => 'required',
       ]);

   }

   /**
    * Create a new user instance after a valid registration.
    *
    * @param  array  $data
    * @return User
    */
   protected function create(array $data)
   {
   
       $user = User::create([
           'name' => $data['name'],
           'email' => $data['email'],
           'password' => Hash::make($data['password']),
           'designation' => $data['designation'],
          // 'role_id' => $data['role_id'],
       ]);

       if ($user) {
         if (isset($data['role_id']) && count($data['role_id']) > 0) {
           foreach ($data['role_id'] as $key => $value) {
             $add_mapper = UserRoleMapper::create(['user_id'=>$user->id,'role_dept_mapper_id'=>$value]);
           }
           if ($add_mapper) {
             Toast::success('User Successfully Created.');
             return redirect('/users');
           }
         }

       }
   }

   public function showRegistrationForm()
   {
       $roles_list = RoleDepartmentMapper::select('role_department_mapper.id','roles.name as role_name','departments.name as department_name','location.location_name')
         ->join('roles','roles.id','=','role_department_mapper.role_id')
         ->join('departments','departments.id','=','role_department_mapper.department_id')
         ->join('location','location.id','=','departments.location_id')
         ->where('roles.deleted_at',NULL)->where('departments.deleted_at',NULL)->get();
      $is_update_url = 0;
       //dd($roles_list);
      return view('auth.register',compact('roles_list','is_update_url'));
   }

   public function usersList()
   {

     $users = User::select('roles.name as role_name','departments.name  as department_name','users.name','users.email','users.id','users.user_status')
       ->leftjoin('user_role_mapper','user_role_mapper.user_id','=','users.id')
       ->leftjoin('role_department_mapper','role_department_mapper.id','=','user_role_mapper.role_dept_mapper_id')
       ->leftjoin('roles','roles.id','=','role_department_mapper.role_id')
       ->leftjoin('departments','departments.id','=','role_department_mapper.department_id')
       ->where(['roles.deleted_at'=>NULL,'users.deleted_at'=>NULL,'departments.deleted_at'=>NULL])
       // ->groupBy("users.id")
       ->get();
// dd($users);
       foreach ($users as $key => $value) {
        // dd($value);
         $data[$value->id]['id'] = $value->id;
         $data[$value->id]['name'] = $value->name;
         $data[$value->id]['email'] = $value->email;
         $data[$value->id]['user_status'] = $value->user_status;
         if (isset($data[$value->id]['role'])) {
           $data[$value->id]['role'] = $data[$value->id]['role'].",".$value->role_name." (".$value->department_name.")";
         } else {
            $data[$value->id]['role'] = $value->role_name." (".$value->department_name.")";
         }
         
       }

       // dd($data);


     // dd($users);
     // foreach ($users as $key => $value) {
     //  // dd($value->role_name);
     //   $roles = implode(",", ($value->role_name));
     // }
     // dd($roles);
       return view('users.list',compact('data'));
   }

    public function edit(Request $request, $id)
   {
       $users = User::select('users.id','users.name','users.email','users.designation')->where(['users.deleted_at'=>NULL,'users.id'=>$id])->first();
       $users['role_id'] = UserRoleMapper::select('user_role_mapper.role_dept_mapper_id as id')
            ->where('user_role_mapper.user_id','=',$id)
            ->get()->toArray();
       // dd($users);
       // $roles_list = RoleDepartmentMapper::select('role_department_mapper.id','roles.name as role_name','departments.name as department_name')->join('roles','roles.id','=','role_department_mapper.role_id')->join('departments','departments.id','=','role_department_mapper.department_id')
       // ->join('location','location.id','=','departments.location_id')
       // ->where('roles.deleted_at',NULL)
       // ->where('departments.deleted_at',NULL)->get();
       $roles_list = RoleDepartmentMapper::select('role_department_mapper.id','roles.name as role_name','departments.name as department_name','location.location_name')
         ->join('roles','roles.id','=','role_department_mapper.role_id')
         ->join('departments','departments.id','=','role_department_mapper.department_id')
         ->join('location','location.id','=','departments.location_id')
         ->where('roles.deleted_at',NULL)->where('departments.deleted_at',NULL)->get();
        // dd($roles_list);
       $is_update_url = 1;
       return view('auth.register',compact('users','is_update_url','roles_list'));
   }

    public function update(Request $request, $id)
    {
      if(isset($id) && !empty($id)) {
        // dd($request);
       $update= User::where('id',$id)->update(['name'=>$request->name,'email'=>$request->email]);

           if ($update) {
            if (isset($request['role_id']) && count($request['role_id']) > 0) {
              $delete_mapper = UserRoleMapper::where('user_id','=',$id)->delete();
           foreach ($request['role_id'] as $key => $value) {
             $add_mapper = UserRoleMapper::create(['user_id'=>$id,'role_dept_mapper_id'=>$value]);
           }
           if ($add_mapper) {
             Toast::success('User Updated Deleted.');
             return redirect('/users');
           }
         }

       } else {
              Toast::error('Something went wrong while updating records.');
               return redirect('/users');
           }

      } else {
        Toast::error('Id not found.');
       return redirect('/users');
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
       if(isset($id) && !empty($id)) {

       $delete_data = User::where('id',$id)->update(['user_status'=>'Disable']);

           if(isset($delete_data) && !empty($delete_data)) {
              Toast::success('User Successfully Deleted.');
             return redirect('/users');

           } else {
              Toast::error('Something went wrong while deleteing data.');
              return redirect('/users');
           }

      } else {
        Toast::error('Id not found.');
       return redirect('/users');
      }

   }

   public function changeStatus(Request $request,$id)
   {
    
    $status = $request->get('user_status');
   // dd($id);
   // dd($status);
     if(isset($id) && !empty($id)) {

            if($status == 'Enable')
            {
               $change_status = User::where('id',$id)->update(['user_status'=>'Disable']);
            }else{
               $change_status = User::where('id',$id)->update(['user_status'=>'Enable']);
            }
             
     // dd($change_status);

           if(isset($change_status) && !empty($change_status)) {
              Toast::success('User Successfully Updated.');
             return redirect('/users');

           } else {
              Toast::error('Something went wrong while updating data.');
              return redirect('/users');
           }
      

      } else {
        Toast::error('Id not found.');
       return redirect('/users');
      }

   }

   public function resetPassword(Request $request)
   {
       //dd($request);
        $id = $request->user_id;
        $password = $request->pass; 
       

        if(isset($id) && !empty($id)) {

         $reset_password = User::where('id',$id)->update(['password'=> Hash::make($password)]);

          if(isset($reset_password) && !empty($reset_password)) {
              Toast::success('Password Successfully Updated.');
             return redirect('/users');

           } else {
              Toast::error('Something went wrong while updating data.');
              return redirect('/users');
           }

         } else {
        Toast::error('Id not found.');
       return redirect('/users');
      }
   }
}
