<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class WorkflowName extends Model
{
   
     use SoftDeletes;
    protected $table = 'workflow_name';

    public $fillable = ['id','name','default_status','created_by','created_at','updated_at','deleted_at'];
}
