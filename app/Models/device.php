<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class device extends Model
{
   protected $fillable = ['name', 'last_seen'];
   protected $casts = [
    'last_seen' => 'datetime',
];

}
