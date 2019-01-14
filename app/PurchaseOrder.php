<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_masters';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['eas_id','vendor_name', 'vendor_address', 'subject', 'bid_number', 'date_of_bid', 'title_of_bid', 'copy_to','email_users','fa_date','status_id','location_id','office_type_id','department_id','created_by','status_approved_date','email_status','po_pdf'];
}
