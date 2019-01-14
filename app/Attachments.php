<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachments extends Model
{
     use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'attachments';

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
    protected $fillable = ['title', 'description', 'entity_type_id', 'entity_id','file_name','created_at','updated_at','deleted_at'];

}
