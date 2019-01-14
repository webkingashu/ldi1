<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RejectComment extends Model
{
    use SoftDeletes;
    protected $table = 'reject_comment';
    protected $fillable = ['entity_id','master_id','comment','created_by','workflow_id','created_at','deleted_at','updated_at'];
}
