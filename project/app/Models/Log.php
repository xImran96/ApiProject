<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    //

     protected $fillable = ['topic', 'code', 'log_topic', 'log_message', 'log_level', 'user_id'];
     
}
