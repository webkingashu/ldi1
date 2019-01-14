<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class UserRoleMapper extends Model
{
    protected $table = 'user_role_mapper';
    public $fillable = ['id','user_id','role_dept_mapper_id'];
    public $timestamps = false;
}
