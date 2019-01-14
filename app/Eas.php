<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Eas extends Model
{
     use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'eas_masters';

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
    protected $fillable = ['sanction_title', 'sanction_purpose', 'competent_authority', 'serial_no_of_sanction', 'file_number', 'sanction_total', 'budget_code', 'validity_sanction_period', 'date_issue', 'vendor_id', 'cfa_note_number', 'cfa_dated', 'cfa_designation','whether_being_issued_under', 'fa_number', 'fa_dated','department_id','status_id','created_by','status_approved_date','email_status','copy_to'];

    
}
