<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PermissionMapper extends Model
{
    use SoftDeletes;
    protected $table = 'role_permission_mapper';
    public $fillable = ['id','role_id','entity_id','can_create','can_update','can_delete','can_view','can_approve','can_reject','created_by','created_at','updated_at','updated_by','deleted_at'];
}
