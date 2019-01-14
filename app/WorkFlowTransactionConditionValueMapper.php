<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkFlowTransactionConditionValueMapper extends Model
{
    use SoftDeletes;
    protected $table = 'workflow_transaction_condition_value_mapper';

    public $fillable = ['id','condition_id','transaction_id','workflow_id','variable_type','value','created_at','updated_at','deleted_at'];
}
