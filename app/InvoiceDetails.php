<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
class InvoiceDetails extends Model
{
    //use SoftDeletes;
    protected $table = 'invoice_details';
    protected $fillable = ['ro_id','invoice_no','created_by','agency_name','qty','period','created_at','deleted_at','amount_payment','sla_amount','applicable_taxes','withheld_amount','net_payable_amount'];
}
