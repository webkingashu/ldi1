<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DispatchRegister extends Model
{
    use SoftDeletes;
    protected $table = 'dispatch_register';
    protected $fillable = ['gar_id','dispatch_register_no','eas_file_no','created_by','eas_file_no','vendor_name','amount_paid','date_of_receiving','date_of_forwarding','created_at','deleted_at','updated_at','updated_by'];
}
