<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends EntrustPermission
{
	use SoftDeletes;

	protected $table = 'permissions';
	protected $fillable = ['name','display_name','created_by'];
}
