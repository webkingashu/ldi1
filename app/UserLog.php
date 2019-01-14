<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    public $timestamps = false;
	protected $table = 'user_log';
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'otp', 'session_id','created_at',	
    ];
}
