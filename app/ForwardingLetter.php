<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ForwardingLetter extends Model
{
    use SoftDeletes;
    protected $table = 'forwarding_letter_master';

    public $fillable = ['total_amount','location_id','date_of_issue','file_path','created_by','created_at','updated_at','deleted_at'];
}
