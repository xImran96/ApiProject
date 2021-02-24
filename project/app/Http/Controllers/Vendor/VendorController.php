<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Generalsetting;
use App\Models\Subcategory;
use App\Models\VendorOrder;
use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductClick;
use App\Models\Rating;
use App\Models\Verification;
use Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Session;


use Validator;

class VendorController extends Controller
{

    public $lang;
    public function __construct()
    {

        $this->middleware('auth');

            if (Session::has('language')) 
            {
                $data = DB::table('languages')->find(Session::get('language'));
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->lang = json_decode($data_results);
            }
            else
            {
                $data = DB::table('languages')->where('is_default','=',1)->first();
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->lang = json_decode($data_results);
                
            } 
    }

    //*** GET Request
    public function index()
    {
        $user = Auth::user();  
        $pending = VendorOrder::where('user_id','=',$user->id)->where('status','=','pending')->get(); 
        $processing = VendorOrder::where('user_id','=',$user->id)->where('status','=','processing')->get(); 
        $completed = VendorOrder::where('user_id','=',$user->id)->where('status','=','completed')->get(); 
        return view('vendor.index',compact('user','pending','processing','completed'));
    }

    public function profileupdate(Request $request)
    {
        //--- Validation Section
        $rules = [
               'shop_image'  => 'mimes:jpeg,jpg,png,svg',
                ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $input = $request->all();  
        $data = Auth::user();    

        if ($file = $request->file('shop_image')) 
         {      
            $name = time().str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/images/vendorbanner',$name);           
            $input['shop_image'] = $name;
        }

        $data->update($input);
        $msg = 'Successfully updated your profile';
        return response()->json($msg); 
    }

    // Spcial Settings All post requests will be done in this method
    public function socialupdate(Request $request)
    {
        //--- Logic Section
        $input = $request->all(); 
        $data = Auth::user();   
        if ($request->f_check == ""){
            $input['f_check'] = 0;
        }
        if ($request->t_check == ""){
            $input['t_check'] = 0;
        }

        if ($request->g_check == ""){
            $input['g_check'] = 0;
        }

        if ($request->l_check == ""){
            $input['l_check'] = 0;
        }
        $data->update($input);
        //--- Logic Section Ends
        //--- Redirect Section        
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);      
        //--- Redirect Section Ends                

    }

    //*** GET Request
    public function profile()
    {
        $data = Auth::user();  
        return view('vendor.profile',compact('data'));
    }

    //*** GET Request
    public function ship()
    {
        $gs = Generalsetting::find(1);
        if($gs->vendor_ship_info == 0) {
            return redirect()->back();
        }
        $data = Auth::user();  
        return view('vendor.ship',compact('data'));
    }

    //*** GET Request
    public function banner()
    {
        $data = Auth::user();  
        return view('vendor.banner',compact('data'));
    }

    //*** GET Request
    public function social()
    {
        $data = Auth::user();  
        return view('vendor.social',compact('data'));
    }

    //*** GET Request
    public function subcatload($id)
    {
        $cat = Category::findOrFail($id);
        return view('load.subcategory',compact('cat'));
    }

    //*** GET Request
    public function childcatload($id)
    {
        $subcat = Subcategory::findOrFail($id);
        return view('load.childcategory',compact('subcat'));
    }

    //*** GET Request
    public function verify()
    {
        $data = Auth::user();  
        if($data->checkStatus())
        {
            return redirect()->back();
        }
        return view('vendor.verify',compact('data'));
    }

    //*** GET Request
    public function warningVerify($id)
    {
        $verify = Verification::findOrFail($id);
        $data = Auth::user();  
        return view('vendor.verify',compact('data','verify'));
    }

    //*** POST Request
    public function verifysubmit(Request $request)
    {
        //--- Validation Section
        $rules = [
          'attachments.*'  => 'mimes:jpeg,jpg,png,svg|max:10000'
           ];
        $customs = [
            'attachments.*.mimes' => 'Only jpeg, jpg, png and svg images are allowed',
            'attachments.*.max' => 'Sorry! Maximum allowed size for an image is 10MB',
                   ];

        $validator = Validator::make($request->all(), $rules,$customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $data = new Verification();
        $input = $request->all();

        $input['attachments'] = '';
        $i = 0;
                if ($files = $request->file('attachments')){
                    foreach ($files as  $key => $file){
                        $name = time().str_replace(' ', '', $file->getClientOriginalName());
                        if($i == count($files) - 1){
                            $input['attachments'] .= $name;
                        }
                        else {
                            $input['attachments'] .= $name.',';
                        }
                        $file->move('assets/images/attachments',$name);

                    $i++;
                    }
                }
        $input['status'] = 'Pending';        
        $input['user_id'] = Auth::user()->id;
        if($request->verify_id != '0')
        {
            $verify = Verification::findOrFail($request->verify_id);
            $input['admin_warning'] = 0;
            $verify->update($input);
        }
        else{

            $data->fill($input)->save();
        }

        //--- Redirect Section        
        $msg = '<div class="text-center"><i class="fas fa-check-circle fa-4x"></i><br><h3>'.$this->lang->lang804.'</h3></div>';
        return response()->json($msg);      
        //--- Redirect Section Ends     
    }


         public function catalog()
    {
      $user = Auth::user();  
        return view('vendor.catalog.index', compact('user'));
    }


    public function category(Request $request, $slug=null, $slug1=null, $slug2=null)
    {
      if (Session::has('currency')) 
      {
        $curr = Currency::find(Session::get('currency'));
      }
      else
      {
          $curr = Currency::where('is_default','=',1)->first();
      }
      $cat = null;
      $subcat = null;
      $childcat = null;
      $minprice = $request->min;
      $maxprice = $request->max;
      $sort = $request->sort;
      $search = $request->search;
    
      $minprice = round(($minprice / $curr->value),2);
      $maxprice = round(($maxprice / $curr->value),2);

      if (!empty($slug)) {
        $cat = Category::where('slug', $slug)->firstOrFail();
        $data['cat'] = $cat;
      }
      if (!empty($slug1)) {
        $subcat = Subcategory::where('slug', $slug1)->firstOrFail();
        $data['subcat'] = $subcat;
      }
      if (!empty($slug2)) {
        $childcat = Childcategory::where('slug', $slug2)->firstOrFail();
        $data['childcat'] = $childcat;
      }

      $prods = Product::when($cat, function ($query, $cat) {
                                      return $query->where('category_id', $cat->id);
                                  })
                                  ->when($subcat, function ($query, $subcat) {
                                      return $query->where('subcategory_id', $subcat->id);
                                  })
                                  ->when($childcat, function ($query, $childcat) {
                                      return $query->where('childcategory_id', $childcat->id);
                                  })
                                  ->when($search, function ($query, $search) use ($request) {
                                 
                                /// return  DB::table('products')->whereJsonContains('name', 'like', ['ar' => $search]);
                                  return $query->where('name_ar', 'LIKE', "%{$search}%")->orWhere('name_en', 'LIKE', "%{$search}%");
                                 //  return $query->where("name",'LIKE', '%'.'"'.$request->search.'"'.'%');
                                  })
                                  ->when($minprice, function($query, $minprice) {
                                    return $query->where('price', '>=', $minprice);
                                  })
                                  ->when($maxprice, function($query, $maxprice) {
                                    return $query->where('price', '<=', $maxprice);
                                  })
                                   ->when($sort, function ($query, $sort) {
                                      if ($sort=='date_desc') {
                                        return $query->orderBy('id', 'DESC');
                                      }
                                      elseif($sort=='date_asc') {
                                        return $query->orderBy('id', 'ASC');
                                      }
                                      elseif($sort=='price_desc') {
                                        return $query->orderBy('price', 'DESC');
                                      }
                                      elseif($sort=='price_asc') {
                                        return $query->orderBy('price', 'ASC');
                                      }
                                   })
                                  ->when(empty($sort), function ($query, $sort) {
                                      return $query->orderBy('id', 'DESC');
                                  });

                                  $prods = $prods->where(function ($query) use ($cat, $subcat, $childcat, $request) {
                                              $flag = 0;

                                              if (!empty($cat)) {
                                                foreach ($cat->attributes as $key => $attribute) {
                                                  $inname = $attribute->input_name;
                                                  $chFilters = $request["$inname"];
                                                  if (!empty($chFilters)) {
                                                    $flag = 1;
                                                    foreach ($chFilters as $key => $chFilter) {
                                                      if ($key == 0) {
                                                        $query->where('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                                                      } else {
                                                        $query->orWhere('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                                                      }

                                                    }
                                                  }
                                                }
                                              }


                                              if (!empty($subcat)) {
                                                foreach ($subcat->attributes as $attribute) {
                                                  $inname = $attribute->input_name;
                                                  $chFilters = $request["$inname"];
                                                  if (!empty($chFilters)) {
                                                    $flag = 1;
                                                    foreach ($chFilters as $key => $chFilter) {
                                                      if ($key == 0 && $flag == 0) {
                                                        $query->where('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                                                      } else {
                                                        $query->orWhere('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                                                      }

                                                    }
                                                  }

                                                }
                                              }


                                              if (!empty($childcat)) {
                                                foreach ($childcat->attributes as $attribute) {
                                                  $inname = $attribute->input_name;
                                                  $chFilters = $request["$inname"];
                                                  if (!empty($chFilters)) {
                                                    $flag = 1;
                                                    foreach ($chFilters as $key => $chFilter) {
                                                      if ($key == 0 && $flag == 0) {
                                                        $query->where('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                                                      } else {
                                                        $query->orWhere('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                                                      }

                                                    }
                                                  }

                                                }
                                              }
                                          });


                                  $prods = $prods->where('status', 1)->get();
                                  $prods = (new Collection(Product::filterProducts($prods)))->paginate(12);
                                 // dd($prods);

      $data['prods'] = $prods;
      // dd($data);

      if($request->ajax()) {

      $data['ajax_check'] = 1;

        return view('includes.product.filtered-user-products', $data);
      }
      return view('vendor.catalog.catalog-products', $data);
    }


    public function getsubs(Request $request) {
      $category = Category::where('slug', $request->category)->firstOrFail();
      $subcategories = Subcategory::where('category_id', $category->id)->get();
      return $subcategories;
    }

   public function product($slug)
    {
        $this->code_image();

        $productt = Product::where('slug','=',$slug)->firstOrFail();

        // dd($productt);
        if($productt->status == 0){
          return response()->view('errors.404')->setStatusCode(404); 
        }

        $productt->views+=1;
        
        $productt->update();
        
        if (Session::has('currency'))
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $product_click =  new ProductClick;
        $product_click->product_id = $productt->id;
        $product_click->date = Carbon::now()->format('Y-m-d');
        $product_click->save();

        if($productt->user_id != 0)
        {
            $vendors = Product::where('status','=',1)->where('user_id','=',$productt->user_id)->take(8)->get();
        }
        else
        {
            $vendors = Product::where('status','=',1)->where('user_id','=',0)->take(8)->get();
        }
        return view('vendor.catalog.single-product',compact('productt','curr','vendors'));

    }


 private function  code_image()
    {
        $actual_path = str_replace('project','',base_path());
        $image = imagecreatetruecolor(200, 50);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image,0,0,200,50,$background_color);

        $pixel = imagecolorallocate($image, 0,0,255);
        for($i=0;$i<500;$i++)
        {
            imagesetpixel($image,rand()%200,rand()%50,$pixel);
        }

        $font = $actual_path.'assets/front/fonts/NotoSans-Bold.ttf';
        $allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = strlen($allowed_letters);
        $letter = $allowed_letters[rand(0, $length-1)];
        $word='';
        //$text_color = imagecolorallocate($image, 8, 186, 239);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $cap_length=6;// No. of character in image
        for ($i = 0; $i< $cap_length;$i++)
        {
            $letter = $allowed_letters[rand(0, $length-1)];
            imagettftext($image, 25, 1, 35+($i*25), 35, $text_color, $font, $letter);
            $word.=$letter;
        }
        $pixels = imagecolorallocate($image, 8, 186, 239);
        for($i=0;$i<500;$i++)
        {
            imagesetpixel($image,rand()%200,rand()%50,$pixels);
        }
        session(['captcha_string' => $word]);
        imagepng($image, $actual_path."assets/images/capcha_code.png");
    }




    public function token()
    {
      return view('vendor.apitoken');
    }


    public function logs()
    {
      return view('vendor.logs');
    }




}
