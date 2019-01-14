<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaMaster extends Model
{
    use SoftDeletes;
    protected $table = 'media_master';
    protected $fillable = ['entity_id','master_id','document_name','file_name','created_by','created_at','deleted_at','updated_at'];
}
