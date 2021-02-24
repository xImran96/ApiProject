<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealerOrderDetail extends Model
{
    
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'dealer_id')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }
    public function order()
    {
        return $this->belongsTo('App\Models\DealerOrder', 'dealer_order_id')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }
}
