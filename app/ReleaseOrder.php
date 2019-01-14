<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReleaseOrder extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'release_order_master';

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
    protected $fillable = ['ro_title','eas_id','status_id','title_of_account', 'current_account_number', 'release_order_amount', 'advance_ro','fa_diary_number', 'copy_to', 'email_users','created_by','created_at','updated_at','deleted_at','status_approved_date','email_status','ro_pdf'];

    
}
