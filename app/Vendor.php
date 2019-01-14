<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;
    protected $table = 'vendor_master';

    public $fillable = ['id','vendor_name','email','contact_no','mobile_no','address','bank_acc_no','ifsc_code', 'bank_name','bank_branch','branch_code','created_by','created_at','updated_at','deleted_at','account_type','gstin','vendor_status'];
}
