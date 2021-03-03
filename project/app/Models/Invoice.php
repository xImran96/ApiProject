<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
		public $timestamps = false;
    	protected $fillable = ['user_id', 'order_id', 'method', 'pay_amount'];

    	public function order()
    	{
    		return $this->belongsTo('App\Models\Order', 'order_id');
    	}
}
