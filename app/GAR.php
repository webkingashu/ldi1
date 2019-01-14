<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GAR extends Model
{
    use SoftDeletes;
    protected $table = 'gar';
    protected $fillable = ['eas_id','ro_id','release_order_amount','gar_bill_type','amount_paid','deducted_gst','actual_payment_amount','copy_to','email_users','created_by','created_at','deleted_at','updated_at','status_id','is_diary_register','amount_used_till_date','updated_by','is_dispatch_register','tally_entry','gst_amount','tds_amount','other_amount','forwarding_letter','gst_tds_amount','email_status','status_approved_date','with_held_amount','ld_amount','gar_pdf','cheque_id','gar_register_entry','is_ec_register','is_ecr_entry','tds_deducted_amount'];
    
}
