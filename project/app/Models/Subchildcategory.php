<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subchildcategory extends Model
{
    //
    
    protected $fillable = ['childcategory_id','name_en','slug','name_ar'];
    public $timestamps = false;
   

    public function childcategory()
    {
    	return $this->belongsTo('App\Models\Childcategory')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $value);
    }

    public function attributes() {
        return $this->morphMany('App\Models\Attribute', 'attributable');
    }
}
