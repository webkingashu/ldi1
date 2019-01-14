<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;


class PermissionRole extends Model

{   //use SoftDeletes;

    protected $table = 'permission_role';
    public $fillable = ['permission_id','entity_id','role_dept_mapper_id','created_by','created_at','updated_at'];

}

