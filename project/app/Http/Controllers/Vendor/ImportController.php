<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Gallery;
use App\ImportProduct;
use App\Models\Product;
use App\Models\Log;
use Auth;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Image;
use Session;
use Validator;

class ImportController extends Controller
{
    public $global_language;

    public function __construct()
    {
        $this->middleware('auth');

            if (Session::has('language')) 
            {
                $data = DB::table('languages')->find(Session::get('language'));
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->vendor_language = json_decode($data_results);
            }
            else
            {
                $data = DB::table('languages')->where('is_default','=',1)->first();
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->vendor_language = json_decode($data_results);
                
            } 

    }

    //*** JSON Request
    public function datatables()
    {
         $user = Auth::user();
         $datas = $user->products()->where('product_type','affiliate')->orderBy('id','desc')->get();
         
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('name', function(Product $data) {
                                $name = mb_strlen(strip_tags($data->name),'utf-8') > 50 ? mb_substr(strip_tags($data->name),0,50,'utf-8').'...' : strip_tags($data->name);
                                $id = '<small>Product ID: <a href="'.route('front.product', [$data->id, $data->slug_name]).'" target="_blank">'.sprintf("%'.08d",$data->id).'</a></small>';
                                return  $name.'<br>'.$id;
                            })
                            ->editColumn('price', function(Product $data) {
                                $sign = Currency::where('is_default','=',1)->first();
                                $price = $sign->sign.$data->price;
                                return  $price;
                            })
                            ->addColumn('status', function(Product $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('vendor-prod-status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>'.$this->vendor_language->lang713.'</option><<option data-val="0" value="'. route('vendor-prod-status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>'.$this->vendor_language->lang714.'</option>/select></div>';
                            })                             
                            ->addColumn('action', function(Product $data) {
                                return '<div class="action-list"><a href="' . route('vendor-import-edit',$data->id) . '"> <i class="fas fa-edit"></i>'.$this->vendor_language->lang715.'</a><a href="javascript" class="set-gallery" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="'.$data->id.'"><i class="fas fa-eye"></i> '.$this->vendor_language->lang716.'</a><a href="javascript:;" data-href="' . route('vendor-prod-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['name', 'status', 'action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('vendor.productimport.index');
    }

    //*** GET Request
    public function createImport()
    {
        $cats = Category::all();
        $sign = Currency::where('is_default','=',1)->first();
        return view('vendor.productimport.createone',compact('cats','sign'));
    }

    //*** GET Request
    public function importCSV()
    {
        $cats = Category::all();
        $sign = Currency::where('is_default','=',1)->first();
        return view('vendor.productimport.importcsv',compact('cats','sign'));
    }

    //*** POST Request
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

        $data = Product::findOrFail($id);

        //--- Validation Section Ends
        $image = $request->image;
        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $image = base64_decode($image);
        $image_name = Str::random(12).'.png';
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

                        
        return response()->json(['status'=>true,'file_name' => $image_name]);
    }

    //*** POST Request
    public function store(Request $request)
    {
        $user = Auth::user();
        $package = $user->subscribes()->orderBy('id','desc')->first();
        $prods = $user->products()->orderBy('id','desc')->get()->count();

        if(!$package){
            return response()->json(array('errors' => [ 0 => 'You don\'t have any subscription plan.']));
        }

        if($prods < $package->allowed_products)
        {

            if($request->image_source == 'file')
            {
                //--- Validation Section
                $rules = [
                    'photo'      => 'mimes:jpeg,jpg,png,svg',
                        ];  

            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends

            }

            //--- Validation Section
            $rules = [
                'file'       => 'mimes:zip'
                 ];  

            $validator = Validator::make($request->all(), $rules);
     
            if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends

        //--- Logic Section        
            $data = new Product;
            $sign = Currency::where('is_default','=',1)->first();
            $input = $request->all();

            // Check File
            if ($file = $request->file('file')) 
            {              
                $name = time().str_replace(' ', '', $file->getClientOriginalName());
                $file->move('assets/files',$name);           
                $input['file'] = $name;
            }

            $input['photo'] = "";
            if($request->image_source == "file"){
                if ($file = $request->file('photo')) 
                {      
                   $name = time().str_replace(' ', '', $file->getClientOriginalName());
                   $file->move('assets/images/products',$name);           
                   $input['photo'] = $name;
                } 
            }else{
                $input['photo'] = $request->photolink;
            }

            // Check Physical
            if($request->type == "Physical")
            {
                    //--- Validation Section
                    $rules = ['sku'      => 'min:8|unique:products'];

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
                    if(in_array(null, $request->size) || in_array(null, $request->size_qty))
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

            // Check Color
            if(empty($request->color_check))
            {
                $input['color'] = null;
            }
            else{
                $input['color'] = implode(',', $request->color); 
            }     

            // Check Measurement
            if ($request->mesasure_check == "") 
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

            if($request->type == "License")
            {

                if(in_array(null, $request->license) || in_array(null, $request->license_qty))
                {
                    $input['license'] = null;  
                    $input['license_qty'] = null;
                }
                else 
                {             
                    $input['license'] = implode(',,', $request->license);  
                    $input['license_qty'] = implode(',', $request->license_qty);                 
                }

            }

             // Check Features
            if(in_array(null, $request->features) || in_array(null, $request->colors))
            {
                $input['features'] = null;  
                $input['colors'] = null;
            }
            else 
            {             
                $input['features'] = implode(',', str_replace(',',' ',$request->features));  
                $input['colors'] = implode(',', str_replace(',',' ',$request->colors));                 
            }

            //tags 
            if (!empty($request->tags)) 
             {
                $input['tags'] = implode(',', $request->tags);       
             }  

            // Conert Price According to Currency
             $input['price'] = ($input['price'] / $sign->value);
             $input['previous_price'] = ($input['previous_price'] / $sign->value);    
             $input['product_type'] = "affiliate";
             $input['user_id'] = Auth::user()->id;
            // Save Data 
                $data->fill($input)->save();

            // Set SLug
                $prod = Product::find($data->id);
                if($prod->type != 'Physical'){
                    $prod->slug = Str::slug($data->name,'-').'-'.strtolower(Str::random(3).$data->id.Str::random(3));
                }
                else {
                    $prod->slug = Str::slug($data->name,'-').'-'.strtolower($data->sku);               
                }
                
                $fimageData = public_path().'/assets/images/products/'.$prod->photo;


                // Set Photo
                $resizedImage = Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(800, null, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $photo = Str::random(10).'.jpg';
                $resizedImage->save(public_path().'/assets/images/products/'.$photo);


                if(filter_var($prod->photo,FILTER_VALIDATE_URL)) {
                    $fimageData = $prod->photo;
                }


                $background = Image::canvas(300, 300);
                $resizedImage = Image::make($fimageData)->resize(300, 300, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                // insert resized image centered into background
                $background->insert($resizedImage, 'center');
                // save or do whatever you like
                $thumbnail = Str::random(12).'.jpg';
                $background->save(public_path().'/assets/images/thumbnails/'.$thumbnail);



                
                $prod->thumbnail  = $thumbnail;
                $prod->photo  = $photo;
                $prod->update();

            // Add To Gallery If any
                $lastid = $data->id;
                if ($files = $request->file('gallery')){
                    foreach ($files as  $key => $file){
                        if(in_array($key, $request->galval))
                        {
                            $gallery = new Gallery;
                            $name = time().str_replace(' ', '', $file->getClientOriginalName());
                            $file->move('assets/images/galleries',$name);
                            $gallery['photo'] = $name;
                            $gallery['product_id'] = $lastid;
                            $gallery->save();
                        }
                    }
                }
        //logic Section Ends

        //--- Redirect Section        
        $msg = 'New Affiliate Product Added Successfully.<a href="'.route('vendor-import-index').'">View Product Lists.</a>';
        return response()->json($msg);      
        //--- Redirect Section Ends   
        }
        else
        {
        //--- Redirect Section        
        return response()->json(array('errors' => [ 0 => 'You Can\'t Add More Product.']));       

        //--- Redirect Section Ends    
        }

    }

    //*** GET Request
    public function edit($id)
    {
        $cats = Category::all();
        $data = Product::findOrFail($id);
        $sign = Currency::where('is_default','=',1)->first();
        return view('vendor.productimport.editone',compact('cats','data','sign'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {

        $prod = Product::find($id);
        //--- Validation Section
        if($request->image_source == 'file')
        {

            $rules = [
                'photo'      => 'mimes:jpeg,jpg,png,svg',
                    ];  

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
        return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }


        }

        $rules = [
            'file'       => 'mimes:zip'
             ];  

        $validator = Validator::make($request->all(), $rules);
 
        if ($validator->fails()) {
        return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends


        //-- Logic Section
        $data = Product::findOrFail($id);
        $sign = Currency::where('is_default','=',1)->first();
        $input = $request->all();

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

        if($request->image_source == 'file'){
                if ($file = $request->file('photo')) 
                {      
                $name = time().str_replace(' ', '', $file->getClientOriginalName());
                $file->move('assets/images/products',$name);           
                $input['photo'] = $name;
                } 
            }else{
                $input['photo'] = $request->photolink;
            }

 
            // Check Physical
            if($data->type == "Physical")
            {
                    //--- Validation Section
                    $rules = ['sku' => 'min:8|unique:products,sku,'.$id];

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

         $input['price'] = $input['price'] / $sign->value;
         $input['previous_price'] = $input['previous_price'] / $sign->value; 
   
         $data->update($input);

        //-- Logic Section Ends

                if($data->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/thumbnails/'.$data->thumbnail)) {
                        unlink(public_path().'/assets/images/thumbnails/'.$data->thumbnail);
                    }
                } 
                $prod = Product::find($data->id);

        // Set SLug
        $prod->slug = Str::slug($data->name,'-').'-'.strtolower($data->sku);


                // Set Photo
                $resizedImage = Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(800, null, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $photo = Str::random(12).'.jpg';
                $resizedImage->save(public_path().'/assets/images/products/'.$photo);


                
        $fimageData = public_path().'/assets/images/products/'.$prod->photo;

        if(filter_var($prod->photo,FILTER_VALIDATE_URL)){
            $fimageData = $prod->photo;
        }



        $background = Image::canvas(300, 300);
        $resizedImage = Image::make($fimageData)->resize(300, 300, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        });
        // insert resized image centered into background
        $background->insert($resizedImage, 'center');
        // save or do whatever you like
        $thumbnail = Str::random(10).'.jpg';
        $background->save(public_path().'/assets/images/thumbnails/'.$thumbnail);



        $prod->thumbnail  = $thumbnail;
        $prod->photo  = $photo;
        $prod->update();

        //--- Redirect Section        
        $msg = 'Product Updated Successfully.<a href="'.route('vendor-import-index').'">View Product Lists.</a>';
        return response()->json($msg);      
        //--- Redirect Section Ends    
    }



     public function importProducts($product_id) {
    
        // dd(auth()->user()->logs());
        // $checkVendorProduct = auth()->user()->myProducts->count();
        $check = auth()->user()->myProducts->where('product_id', $product_id)->count();

        
        if($check == 0){

        $product   = Product::find($product_id);


        $defaultProfit = auth()->user()->profit;
        

        $getProfit = ($defaultProfit / 100) * $product->price;
        $newPrice  = $product->price + $getProfit;

        // dd($newPrice);



        $input = [
                 'category_id' => $product->category_id,
                 'product_type'=> $product->product_type,
                 'product_id'=>$product->id,
                 'affiliate_link' => $product->affiliate_link,
                 'sku'=>$product->sku ,
                 'min_order_qty'=>$product->min_order_qty,
                 'subcategory_id'=>$product->subcategory_id,
                 'childcategory_id'=>$product->childcategory_id,
                 'attributes'=>$product->attributes,
                 'name_en'=>$product->name_en,
                 'name_ar'=>$product->name_ar,
                 'photo'=>$product->photo,
                 'size'=>$product->size,
                 'size_qty'=>$product->size_qty,
                 'size_price'=>$product->size_price,
                 'color'=>$product->color,
                 'details_en'=>$product->details_en,
                 'details_ar'=>$product->details_ar,
                 'price'=>$product->price,
                 'previous_price'=>$product->previous_price,
                 'stock'=>$product->stock,
                 'policy'=>$product->policy,
                 'status'=>$product->status,
                 'views'=>$product->views,
                 'tags'=>$product->tags,
                 'featured'=>$product->featured,
                 'best'=>$product->best,
                 'top'=>$product->top,
                 'hot'=>$product->hot,
                 'latest'=>$product->latest,
                 'big'=>$product->big,
                 'trending'=>$product->trending,
                 'sale'=>$product->sale,
                 'features'=>$product->features,
                 'colors'=>$product->colors,
                 'product_condition'=>$product->product_condition,
                 'ship'=>$product->ship,
                 'meta_tag'=>$product->meta_tag,
                 'meta_description'=>$product->meta_description,
                 'youtube'=>$product->youtube,
                 'type'=>$product->type,
                 'file'=>$product->file,
                 'license'=>$product->license,
                 'license_qty'=>$product->license_qty,
                 'link'=>$product->link,
                 'platform'=>$product->platform,
                 'region'=>$product->region,
                 'license_type'=>$product->license_type,
                 'measure'=>$product->measure,
                 'discount_date'=>$product->discount_date,
                 'is_discount'=>$product->is_discount,
                 'whole_sell_qty'=>$product->whole_sell_qty,
                 'whole_sell_discount'=>$product->whole_sell_discount,
                 'is_catalog'=>$product->is_catalog,   
                 'catalog_id'=>$product->catalog_id,
                 'slug'=>$product->slug,
                 'profit_percentage'=>$defaultProfit,
                 'import_price'=>$newPrice,
                 'new_price'=>$newPrice
             ];    
                $import = new ImportProduct($input);

                auth()->user()->myProducts()->save($import);

                $log = new Log([
                        'topic'=>'Imported',
                        'code'=>200,
                        'log_topic'=>'Product-Imported',
                        'log_message'=> $import->sku.' '.$import->name_en.' is Imported Successfully.',
                        'log_level'=>'Creation',
                        ]);

                auth()->user()->logs()->save($log);
            

      

        return back()->with('success', 'Product Imported Successfully.');
        }else{
            return back()->with('unsuccess', 'Product Already Imported.');
        }


        
    }
}