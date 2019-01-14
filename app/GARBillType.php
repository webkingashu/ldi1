<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GARBillType extends Model
{
    use SoftDeletes;
    protected $table = 'gar_bill_type';
    
}
