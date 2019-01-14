<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkFlowTransactionMapper extends Model
{
     use SoftDeletes;
    protected $table = 'workflow_transaction_mapper';

    public $fillable = ['id','workflow_id','transaction_id','role_id','created_at','updated_at','deleted_at'];
}
