<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use SoftDeletes;
    protected $table = 'entity_type';

    public $fillable = ['id','type_name','workflow_id','entity_slug','created_by','created_at','updated_at','deleted_at'];
}
