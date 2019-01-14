<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Transaction extends Model
{
    use SoftDeletes;
    protected $table = 'transactions';

    public $fillable = ['transaction_name','from_status','to_status','created_by','created_at','updated_at','deleted_at','role_id'];
}
