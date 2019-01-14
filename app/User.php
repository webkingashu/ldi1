<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{  
   use EntrustUserTrait;
    //use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id','phone_number','otp_token','isVerified', 'user_status','designation','created_at','updated_at','deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // public function hasEntity()
    // {
        
    //    $user_details = User::select('users.id')
    //            // ->join('users','users.role_id', '=','roles.id')
    //             ->leftjoin('user_role_mapper','user_role_mapper.user_id' ,'=', 'users.id')
    //             ->leftjoin('role_department_mapper','user_role_mapper.role_dept_mapper_id' ,'=', 'role_department_mapper.id')
    //             ->leftjoin('permission_role','permission_role.role_dept_mapper_id' ,'=', 'role_department_mapper.id')
    //             ->leftjoin('entity_type','entity_type.id' ,'=', 'permission_role.entity_id')
    //             ->leftjoin('permissions','permissions.id' ,'=', 'permission_role.permission_id')
    //             ->where('users.id', '=',auth()->user()->id)
    //             ->where('entity_type.entity_slug',$entity)
    //             ->where('permissions.name', '=','can_view')
    //             ->where(['users.deleted_at' => Null,'entity_type.deleted_at' => Null])
    //             ->first();
    //            //dd($user_details);
    //         if( $user_details ){
    //             return true;
    //         } else {
    //             return false;
    //         }
        
    // }
}
