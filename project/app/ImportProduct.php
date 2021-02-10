<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Currency;

use Illuminate\Support\Facades\Session;

class ImportProduct extends Model
{
    //
	 protected $fillable = ['product_id','category_id','product_type',
    'affiliate_link','sku','min_order_qty', 'subcategory_id', 'childcategory_id',
     'attributes', 'name_en','name_ar', 'photo', 'size','size_qty','size_price',
      'color', 'details_en','details_ar','price','previous_price','stock','policy','status',
       'views','tags','featured','best','top','hot','latest','big','trending',
       'sale','features','colors','product_condition','ship','meta_tag',
       'meta_description','youtube','type','file','license','license_qty'
       ,'link','platform','region','licence_type','measure','discount_date',
       'is_discount','whole_sell_qty','whole_sell_discount','catalog_id','slug','profit_percentage','import_price'];


    public function users()
    {
        return $this->belongsToMany('App\Models\Product');
    }


    public function vendorPrice() {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->price;
        if($this->user_id != 0){
        $price = $this->price + $gs->fixed_commission + ($this->price/100) * $gs->percentage_commission ;
        }

        return $price;
    }

    public function vendorSizePrice() {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->price;
        if($this->user_id != 0){
        $price = $this->price + $gs->fixed_commission + ($this->price/100) * $gs->percentage_commission ;
        }
        if(!empty($this->size) && !empty($this->size_price)){
            $price += $this->size_price[0];
        }

    // Attribute Section

    $attributes = $this->attributes["attributes"];
      if(!empty($attributes)) {
          $attrArr = json_decode($attributes, true);
      }

      if (!empty($attrArr)) {
          foreach ($attrArr as $attrKey => $attrVal) {
            if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

                foreach ($attrVal['values'] as $optionKey => $optionVal) {
                  $price += $attrVal['prices'][$optionKey];
                  // only the first price counts
                  break;
                }

            }
          }
      }

    // Attribute Section Ends

        return $price;
    }


    public  function setCurrency() {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->price;
        if (Session::has('currency'))
        {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return DB::table('currencies')->find(Session::get('currency'));
            });
        }
        else
        {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return DB::table('currencies')->where('is_default','=',1)->first();
            });
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }


    public function showPrice() {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->price;

        if($this->user_id != 0){
        $price = $this->price + $gs->fixed_commission + ($this->price/100) * $gs->percentage_commission ;
        }

        if(!empty($this->size) && !empty($this->size_price)){
            $price += $this->size_price[0];
        }
    // Attribute Section

    $attributes = $this->attributes["attributes"];
      if(!empty($attributes)) {
          $attrArr = json_decode($attributes, true);
      }
      // dd($attrArr);
      if (!empty($attrVal['values']) && is_array($attrVal['values'])) {
          foreach ($attrArr as $attrKey => $attrVal) {
            if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

                foreach ($attrVal['values'] as $optionKey => $optionVal) {
                  $price += $attrVal['prices'][$optionKey];
                  // only the first price counts
                  break;
                }

            }
          }
      }


    // Attribute Section Ends


    if (Session::has('currency'))
    {
        $curr = cache()->remember('session_currency', now()->addDay(), function () {
            return DB::table('currencies')->find(Session::get('currency'));
        });
    }
    else
    {
        $curr = cache()->remember('default_currency', now()->addDay(), function () {
            return DB::table('currencies')->where('is_default','=',1)->first();
        });
    }
 


        $price = round(($price) * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public function showPreviousPrice() {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $price = $this->previous_price;
        if(!$price){
            return '';
        }
        if($this->user_id != 0){
        $price = $this->previous_price + $gs->fixed_commission + ($this->previous_price/100) * $gs->percentage_commission ;
        }

        if(!empty($this->size) && !empty($this->size_price)){
            $price += $this->size_price[0];
        }

    // Attribute Section

    $attributes = $this->attributes["attributes"];
      if(!empty($attributes)) {
          $attrArr = json_decode($attributes, true);
      }
      // dd($attrArr);
      if (!empty($attrVal['values']) && is_array($attrVal['values'])) {
          foreach ($attrArr as $attrKey => $attrVal) {
            if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

                foreach ($attrVal['values'] as $optionKey => $optionVal) {
                  $price += $attrVal['prices'][$optionKey];
                  // only the first price counts
                  break;
                }

            }
          }
      }


    // Attribute Section Ends


    if (Session::has('currency'))
    {
        $curr = cache()->remember('session_currency', now()->addDay(), function () {
            return DB::table('currencies')->find(Session::get('currency'));
        });
    }
    else
    {
        $curr = cache()->remember('default_currency', now()->addDay(), function () {
            return DB::table('currencies')->where('is_default','=',1)->first();
        });
    }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public static function convertPrice($price) {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        if (Session::has('currency'))
        {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return DB::table('currencies')->find(Session::get('currency'));
            });
        }
        else
        {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return DB::table('currencies')->where('is_default','=',1)->first();
            });
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public static function vendorConvertPrice($price) {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });

        $curr = cache()->remember('default_currency', now()->addDay(), function () {
            return DB::table('currencies')->where('is_default','=',1)->first();
        });
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public static function convertPreviousPrice($price) {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        if (Session::has('currency'))
        {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return DB::table('currencies')->find(Session::get('currency'));
            });
        }
        else
        {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return DB::table('currencies')->where('is_default','=',1)->first();
            });
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $price.$curr->sign;
        }
    }

    public function showName() {
        if(Session::get('language')==2) 
        $name = mb_strlen( $this->name_ar,'utf-8') > 55 ? mb_substr( $this->name_ar,0,55,'utf-8').'...' :  $this->name_ar; 
         else
         $name = mb_strlen($this->name_en,'utf-8') > 55 ? mb_substr($this->name_en,0,55,'utf-8').'...' : $this->name_en;
        return $name;
    }


    public function emptyStock() {

        $stck = (string)$this->stock;
        if($stck == "0"){
            return true;            
        }
    }

    public function minQty($qty) {
        
        $stck = (string)$this->min_order;
        if($stck == "0"){
            return true;            
        }
    }

    public static function showTags() {
        $tags = null;
        $tagz = '';
        $name = Product::where('status','=',1)->pluck('tags')->toArray();
        foreach($name as $nm)
        {
            if(!empty($nm))
            {
                foreach($nm as $n)
                {
                    $tagz .= $n.',';
                }
            }
        }
        $tags = array_unique(explode(',',$tagz));
        return $tags;
    }


    public function getSizeAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getSizeQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getSizePriceAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getTagsAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getMetaTagAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getFeaturesAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorsAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getLicenseAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',,', $value);
    }

    public function getLicenseQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellDiscountAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }


    


}
