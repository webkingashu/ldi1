<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
     use SoftDeletes;
    protected $table = 'status';

    public $fillable = ['status_name','created_by','created_at','updated_at','deleted_at'];
}

