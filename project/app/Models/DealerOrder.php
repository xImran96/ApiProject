<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealerOrder extends Model
{
    //


        public function dealerOrder()
    {
        return $this->hasMany('App\Models\DealerOrderDetail');
    }

}