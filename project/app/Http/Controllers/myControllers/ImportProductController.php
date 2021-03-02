<?php

namespace App\Http\Controllers\myControllers;

use Image;
use App\Models\User;
use App\ImportProduct;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Attribute;
use App\Models\DealerOrder;
use App\Models\Subcategory;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Str;
// use Intervention\Image\Image;
use Illuminate\Http\Request;
use App\Models\Childcategory;
use App\Models\VendorGallery;
use App\Models\Subchildcategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class ImportProductController extends Controller
{
   public function showMyProducts()
   {
      return view('layouts.My-product.myProduct');
   }
    public function importProduct(Request $req)
    { 
      // return $req->product_id;
         $product_id =  $req->product_id;

           $originalProduct = Product::where('id',$product_id)->first();

           $checkProduct = ImportProduct::where('product_id',$product_id)->where('user_id',auth()->user()->id)->first();
          
           if ($checkProduct) {
              return "you alredy imported this product";
           }
      $checkigProfitPercentage = User::where('id',auth()->user()->id)->first();
     
         	$product = new ImportProduct;
            $product->sku = $originalProduct['sku'];
         	$product->product_type = $originalProduct['product_type'];
         	$product->product_id = $originalProduct['id'];
         	$product->user_id = auth()->user()->id;
         	$product->affiliate_link = $originalProduct['affiliate_link'];
         	$product->category_id = $originalProduct['category_id'];
            $product->subcategory_id = $originalProduct['subcategory_id'];
         	$product->childcategory_id = $originalProduct['childcategory_id'];
         	$product->attributes = $originalProduct['attributes'];
         	$product->name_en = $originalProduct['name_en'];
         	$product->name_ar = $originalProduct['name_ar'];
         	$product->slug = $originalProduct['slug'];
         	$product->photo = $originalProduct['photo'];
         	$product->thumbnail= $originalProduct['thumbnail'];
         	$product->file = $originalProduct['file'];
         	$product->size= $originalProduct['size'];
         	$product->size_qty= $originalProduct['size_qty'];
         	$product->size_price = $originalProduct['size_price'];
         	$product->color = $originalProduct['color'];
         	$product->price = $originalProdsuct['price'];
         	$product->details_en = $originalProduct['details_en'];
         	$product->details_ar = $originalProduct['details_ar'];
         	$product->stock = $originalProduct['stock'];
         	$product->policy = $originalProduct['policy'];
         	$product->status = $originalProduct['status'];
         	$product->views= $originalProduct['views'];
         	$product->tags = $originalProduct['tags'];
         	$product->features = $originalProduct['features'];
         	$product->region = $originalProduct['region'];
         	$product->colors = $originalProduct['colors'];
         	$product->product_condition = $originalProduct['product_condition'];
         	$product->product_type = $originalProduct['product_type'];
         	$product->ship = $originalProduct['ship'];
         	$product->is_meta = $originalProduct['is_meta'];
         	$product->meta_tag = $originalProduct['meta_tag'];
         	$product->meta_description = $originalProduct['meta_description'];
         	$product->youtube = $originalProduct['youtube'];
         	$product->type = $originalProduct['type'];
         	$product->license = $originalProduct['license'];
            $product->license_qty = $originalProduct['license'];
         	$product->link = $originalProduct['link'];
         	$product->platform = $originalProduct['platform'];
         	$product->license_type = $originalProduct['license_type'];
         	$product->measure  = $originalProduct['measure'];
         	$product->featured = $originalProduct['featured'];
         	$product->best = $originalProduct['best'];
         	$product->top= $originalProduct['top'];
         	$product->min_order_qty = $originalProduct['min_order_qty'];
         	$product->hot = $originalProduct['hot'];
         	$product->latest = $originalProduct['latest'];
         	$product->big = $originalProduct['big'];
         	$product->trending = $originalProduct['trending'];
         	$product->sale = $originalProduct['sale'];
         	$product->is_discount = $originalProduct['is_discount'];
         	$product->discount_date = $originalProduct['discount_date'];
         	$product->whole_sell_qty = $originalProduct['whole_sell_qty'];
         	$product->whole_sell_discount = $originalProduct['whole_sell_discount'];
         	$product->is_catalog = $originalProduct['is_catalog'];
         	$product->catalog_id = $originalProduct['catalog_id'];
            
           if ($checkigProfitPercentage) {
               if ($checkigProfitPercentage->profit_percentage > 0) {
           $product->profit_percentage = $checkigProfitPercentage->profit_percentage; 
           
    $totalProfit = ($originalProduct->price*$checkigProfitPercentage->profit_percentage)/100;
            $newPrice = $originalProduct->price+$totalProfit;
          $product->new_price = $newPrice;
            }
           }
           
         	
         	
         	
           
             
            if ($product->Save()) {
              return 'imported successfully';
             }else{
               return 'something went wrong';
             }





    }

    public function subCategory(Request $req)
    {
      $id =  $req->id;
      $subCategory = Subcategory::where('category_id',$id)->get();
     return response()->json($subCategory);
      // return $subCategory;
    }
    public function childCategory(Request $req)
    {
       $id =  $req->id;
      $subCategory = Childcategory::where('subcategory_id',$id)->get();
     return response()->json($subCategory);
    }
    public function subChildCategory(Request $req)
    {
       $id =  $req->id;
      $subChildCategory = Subchildcategory::where('childcategory_id',$id)->get();
     return response()->json($subChildCategory);
    }
    public function searchProduct(Request $req)
    {
        $category = $req->category;
        $subcategory = $req->subcategory;
        $childcategory = $req->childcategory;
        $subchildcategory = $req->subchildcategory;
        
      $categoriesWiseProducts = Product::where('category_id',$category)->orWhere('subcategory_id',$subcategory)->orWhere('childcategory_id',$childcategory)->paginate(10);
     
     return view('vendor.product.index',compact('categoriesWiseProducts'));
    }

    public function deleteMyProduct($id)
    {
     
      $myProduct = ImportProduct::find($id);
      $delete = $myProduct->delete();
      if ($delete) {
         return back();
      }
    }
    public function editMyProduct($id)
    {
       if(!ImportProduct::where('id',$id)->exists())
        {
            // return redirect()->route('admin.dashboard')->with('unsuccess',__('Sorry the page does not exist.'));
         return "record does not found";
        }
        $cats = Category::all();
        $data = ImportProduct::findOrFail($id);
        $sign = Currency::where('is_default','=',1)->first();

 
        if($data->type == 'Digital')
            return view('layouts.My-product.editdigital',compact('cats','data','sign'));
        elseif($data->type == 'License')
            return view('layouts.My-product.editdlicense',compact('cats','data','sign'));
        else
            return view('layouts.My-product.editphysical',compact('cats','data','sign'));
    }

    public function update(Request $request, $id)
    {
      
      // return $request;
        //--- Validation Section
      //   dd($request->all()); 
        $rules = [
               'file'       => 'mimes:zip'
                ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends


        //-- Logic Section
        $data = ImportProduct::findOrFail($id);
        $sign = Currency::where('is_default','=',1)->first();
        $input = $request->all();
        

        // $input['name'] = [
        //     'ar'=>$request->input('name_ar'),
        //     'en'=>$request->input('name_en')
        // ];
        // $input['details'] = [
        //     'ar'=>$request->input('details_ar'),
        //     'en'=>$request->input('details')
        // ];
            //Check Types
            if($request->type_check == 1)
            {
                $input['link'] = null;
            }
            else
            {
                if($data->file!=null){
                        if (file_exists(public_path().'/assets/files/'.$data->file)) {
                        unlink(public_path().'/assets/files/'.$data->file);
                    }
                }
                $input['file'] = null;
            }
            //profit percentage code for individual product.
        
         $data->profit_percentage = $request->profit_percentage;
         $getPercentage = ($data->price*$request->profit_percentage)/100;
         $newPrice = $data->price+$getPercentage;
         $data->specific_percentage_status = 1;
         $data->new_price = $newPrice;


            // Check Physical
            if($data->type == "Physical")
            {

                    //--- Validation Section
                    $rules = ['sku' => 'min:6|unique:products,sku,'.$id];

                    $validator = Validator::make($request->all(), $rules);

                    if ($validator->fails()) {
                        return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
                    }
                    //--- Validation Section Ends

                        // Check Condition
                        if ($request->product_condition_check == ""){
                            $input['product_condition'] = 0;
                        }

                        // Check Shipping Time
                        if ($request->shipping_time_check == ""){
                            $input['ship'] = null;
                        }

                        // Check Size

                        if(empty($request->size_check ))
                        {
                            $input['size'] = null;
                            $input['size_qty'] = null;
                            $input['size_price'] = null;
                        }
                        else{
                                if(in_array(null, $request->size) || in_array(null, $request->size_qty) || in_array(null, $request->size_price))
                                {
                                    $input['size'] = null;
                                    $input['size_qty'] = null;
                                    $input['size_price'] = null;
                                }
                                else
                                {

                                    if(in_array(0,$input['size_qty'])){
                                        return response()->json(array('errors' => [0 => 'Size Qty can not be 0.']));
                                    }

                                    $input['size'] = implode(',', $request->size);
                                    $input['size_qty'] = implode(',', $request->size_qty);
                                    $size_prices = $request->size_price;
                                    $s_price = array();
                                    foreach($size_prices as $key => $sPrice){
                                        $s_price[$key] = $sPrice / $sign->value;
                                    }
                                    
                                    $input['size_price'] = implode(',', $s_price);
                                }
                        }



                        // Check Whole Sale
            if(empty($request->whole_check ))
            {
                $input['whole_sell_qty'] = null;
                $input['whole_sell_discount'] = null;
            }
            else{
                if(in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount))
                {
                $input['whole_sell_qty'] = null;
                $input['whole_sell_discount'] = null;
                }
                else
                {
                    $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                    $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                }
            }

                        // Check Color
                        if(empty($request->color_check ))
                        {
                            $input['color'] = null;
                        }
                        else{
                            if (!empty($request->color))
                             {
                                $input['color'] = implode(',', $request->color);
                             }
                            if (empty($request->color))
                             {
                                $input['color'] = null;
                             }
                        }

                        // Check Measure
                    if ($request->measure_check == "")
                     {
                        $input['measure'] = null;
                     }
            }


            // Check Seo
        if (empty($request->seo_check))
         {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
         }
         else {
        if (!empty($request->meta_tag))
         {
            $input['meta_tag'] = implode(',', $request->meta_tag);
         }
         }



        // Check License
        if($data->type == "License")
        {

        if(!in_array(null, $request->license) && !in_array(null, $request->license_qty))
        {
            $input['license'] = implode(',,', $request->license);
            $input['license_qty'] = implode(',', $request->license_qty);
        }
        else
        {
            if(in_array(null, $request->license) || in_array(null, $request->license_qty))
            {
                $input['license'] = null;
                $input['license_qty'] = null;
            }
            else
            {
                $license = explode(',,', $prod->license);
                $license_qty = explode(',', $prod->license_qty);
                $input['license'] = implode(',,', $license);
                $input['license_qty'] = implode(',', $license_qty);
            }
        }

        }
            // Check Features
            if(!in_array(null, $request->features) && !in_array(null, $request->colors))
            {
                    $input['features'] = implode(',', str_replace(',',' ',$request->features));
                    $input['colors'] = implode(',', str_replace(',',' ',$request->colors));
            }
            else
            {
                if(in_array(null, $request->features) || in_array(null, $request->colors))
                {
                    $input['features'] = null;
                    $input['colors'] = null;
                }
                else
                {
                    $features = explode(',', $data->features);
                    $colors = explode(',', $data->colors);
                    $input['features'] = implode(',', $features);
                    $input['colors'] = implode(',', $colors);
                }
            }

        //Product Tags
        if (!empty($request->tags))
         {
            $input['tags'] = implode(',', $request->tags);
         }
        if (empty($request->tags))
         {
            $input['tags'] = null;
         }


         // $input['price'] = $input['price'] / $sign->value;
         // $input['previous_price'] = $input['previous_price'] / $sign->value;

         // store filtering attributes for physical product
         $attrArr = [];
         if (!empty($request->category_id)) {
           $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('attributable_type', 'App\Models\Category')->get();
           if (!empty($catAttrs)) {
             foreach ($catAttrs as $key => $catAttr) {
               $in_name = $catAttr->input_name;
               if ($request->has("$in_name")) {
                 $attrArr["$in_name"]["values"] = $request["$in_name"];
                 $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                 if ($catAttr->details_status) {
                   $attrArr["$in_name"]["details_status"] = 1;
                 } else {
                   $attrArr["$in_name"]["details_status"] = 0;
                 }
               }
             }
           }
         }

         if (!empty($request->subcategory_id)) {
           $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('attributable_type', 'App\Models\Subcategory')->get();
           if (!empty($subAttrs)) {
             foreach ($subAttrs as $key => $subAttr) {
               $in_name = $subAttr->input_name;
               if ($request->has("$in_name")) {
                 $attrArr["$in_name"]["values"] = $request["$in_name"];
                 $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                 if ($subAttr->details_status) {
                   $attrArr["$in_name"]["details_status"] = 1;
                 } else {
                   $attrArr["$in_name"]["details_status"] = 0;
                 }
               }
             }
           }
         }
         if (!empty($request->childcategory_id)) {
           $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('attributable_type', 'App\Models\Childcategory')->get();
           if (!empty($childAttrs)) {
             foreach ($childAttrs as $key => $childAttr) {
               $in_name = $childAttr->input_name;
               if ($request->has("$in_name")) {
                 $attrArr["$in_name"]["values"] = $request["$in_name"];
                 $attrArr["$in_name"]["prices"] = $request["$in_name"."_price"];
                 if ($childAttr->details_status) {
                   $attrArr["$in_name"]["details_status"] = 1;
                 } else {
                   $attrArr["$in_name"]["details_status"] = 0;
                 }
               }
             }
           }
         }



         if (empty($attrArr)) {
           $input['attributes'] = NULL;
         } else {
           $jsonAttr = json_encode($attrArr);
           $input['attributes'] = $jsonAttr;
         }
         

         $data->update($input);
        //-- Logic Section Ends


      //   $prod = ImportProduct::find($data->id);

      //   // Set SLug
      //   $prod->slug = Str::slug($data->name_en,'-').'-'.strtolower($data->sku);
         
      //   $prod->update();


      //   if (count($prod->imports)!=0) {
            
      //           $input2 = $input;


      //       foreach ($prod->imports as $import) {


      //           $profit =  $import->profit_percentage;
      //           $profitGet = ($profit / 100) * $prod->price;
      //           $newPrice = $prod->price + $profitGet;
      //           $input2['profit_percentage'] = $profit;
      //           $input2['import_price'] = $newPrice;
      //           $import->update($input2);

      //       }

      //   }
        

        //--- Redirect Section
        $msg = 'Product Updated Successfully.<a href="'.route('my-products').'">View Product Lists.</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function storegallery(Request $request)
    { 
     

        $data = null;
        $lastid = $request->product_id;
        if ($files = $request->file('gallery')){
            foreach ($files as  $key => $file){
                $val = $file->getClientOriginalExtension();
                if($val == 'jpeg'|| $val == 'jpg'|| $val == 'png'|| $val == 'svg')
                  {
                    $gallery = new VendorGallery;


        $img = Image::make($file->getRealPath())->resize(800, 800);
        $thumbnail = Str::random(10).'.jpg';
        $img->save(public_path().'/assets/images/galleries/'.$thumbnail);

                    $gallery['photo'] = $thumbnail;
                    $gallery['import_product_id'] = $lastid;
                    $gallery['user_id'] = auth()->user()->id;
                    $gallery->save();
                    $data[] = $gallery;                        
                  }
            }
        }
        return response()->json($data);      
    } 

    public function destroygallery()
    {
     

        $id = $_GET['id'];
        $gal = VendorGallery::findOrFail($id);
            if (file_exists(public_path().'/assets/images/galleries/'.$gal->photo)) {
                unlink(public_path().'/assets/images/galleries/'.$gal->photo);
            }
        $gal->delete();
            
    } 

    public function uploadUpdate(Request $request,$id)
    {
        //--- Validation Section
      

        $rules = [
          'image' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = ImportProduct::findOrFail($id);

        //--- Validation Section Ends
        $image = $request->image;
        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $image = base64_decode($image);
        $image_name = Str::random(10).'.png';
        $path = 'assets/images/products/'.$image_name;
        file_put_contents($path, $image);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/products/'.$data->photo)) {
                        unlink(public_path().'/assets/images/products/'.$data->photo);
                    }
                }
                        $input['photo'] = $image_name;
         $data->update($input);
                if($data->thumbnail != null)
                {
                    if (file_exists(public_path().'/assets/images/thumbnails/'.$data->thumbnail)) {
                        unlink(public_path().'/assets/images/thumbnails/'.$data->thumbnail);
                    }
                }

        $img = Image::make(public_path().'/assets/images/products/'.$data->photo)->resize(285, 285);
        $thumbnail = Str::random(10).'.jpg';
        $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
        $data->thumbnail  = $thumbnail;
        $data->update();
        return response()->json(['status'=>true,'file_name' => $image_name]);
    }

    public function showgallery()
    {
      
        $data[0] = 0;
        $id = $_GET['id'];
        $prod = ImportProduct::findOrFail($id);
        if(count($prod->galleries))
        {
            $data[0] = 1;
            $data[1] = $prod->galleries;
        }
        return response()->json($data);              
    }
    
    public function showlinkstore(){
      return view('layouts.My-product.linkstore');
    }

    public function finance(){
      $sum = 0;
      // $dealerOrders = DealerOrder::with('importProduct')->where('dealer_id',auth()->user()->id)->get();
      // foreach($dealerOrders as $dealerOrder){
      //   // dd($dealerOrder->cart);
      //  $sum+=$dealerOrder->pay_amount;
      // }

       $sum = auth()->user()->orders()->sum('per_order_profit');
             
      $active_balance = User::where('id',auth()->user()->id)->first();
      $blc =  $active_balance->active_balance;
      return view('layouts.My-product.finance',compact('sum','blc'));
    }
    
    public function profitPerOrder(){
      $vOrders = auth()->user()->orders;
      return view('layouts.My-product.ProfitPerOrder',compact('vOrders'));
    }

}
