<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cheque extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'cheque_master';

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
    protected $fillable = ['cheque_name', 'cheque_date', 'cheque_amount', 'cheque_number','file_path', 'forwarding_letter_id','created_by','created_at','updated_at','deleted_at'];

    
}
