<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class RoleDepartmentMapper extends Model
{
    protected $table = 'role_department_mapper';
    public $fillable = ['id','department_id','role_id'];
    public $timestamps = false;
}
