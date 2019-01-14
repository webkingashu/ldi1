<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EasLog extends Model
{
    protected $table = 'log_details';
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entity_id', 'entity_type_id', 'detail_data',
    ];
}
