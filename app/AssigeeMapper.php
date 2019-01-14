<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssigeeMapper extends Model
{
    use SoftDeletes;
    protected $table = 'assignee_mapper';
    protected $fillable = ['entity_id','master_id','assignee','created_by','status_id','created_at','deleted_at','updated_at'];
}
