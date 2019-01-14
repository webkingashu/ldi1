<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiaryRegister extends Model
{
    use SoftDeletes;
    protected $table = 'diary_register';
    protected $fillable = ['gar_id','diary_register_no','created_by','amount_paid','date_of_receiving','date_of_forwarding','created_at','deleted_at','updated_at','updated_by'];
}
