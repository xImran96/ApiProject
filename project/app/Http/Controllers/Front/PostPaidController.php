<?php

namespace app\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\VendorOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PostPaidController extends Controller
{
    //

    public function store(Request $request){
        if (!Session::has('cart')) {
           return redirect()->route('front.cart')->with('success',"You don't have any product to checkout.");
        }
   
           if($request->pass_check) {
               $users = User::where('email','=',$request->personal_email)->get();
               if(count($users) == 0) {
                   if ($request->personal_pass == $request->personal_confirm){
                       $user = new User();
                       $user->name = $request->personal_name; 
                       $user->email = $request->personal_email;   
                       $user->password = bcrypt($request->personal_pass);
                       $token = md5(time().$request->personal_name.$request->personal_email);
                       $user->verification_link = $token;
                       $user->affilate_code = md5($request->name.$request->email);
                       $user->email_verified = 'Yes';
                       $user->save();
                       Auth::guard('web')->login($user);                     
                   }else{
                       return redirect()->back()->with('unsuccess',"Confirm Password Doesn't Match.");     
                   }
               }
               else {
                   return redirect()->back()->with('unsuccess',"This Email Already Exist.");  
               }
           }
   
   
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
               if (Session::has('currency')) 
               {
                 $curr = Currency::find(Session::get('currency'));
               }
               else
               {
                   $curr = Currency::where('is_default','=',1)->first();
               }
           foreach($cart->items as $key => $prod)
           {
           if(!empty($prod['item']['license']) && !empty($prod['item']['license_qty']))
           {
                   foreach($prod['item']['license_qty']as $ttl => $dtl)
                   {
                       if($dtl != 0)
                       {
                           $dtl--;
                           $produc = Product::findOrFail($prod['item']['id']);
                           $temp = $produc->license_qty;
                           $temp[$ttl] = $dtl;
                           $final = implode(',', $temp);
                           $produc->license_qty = $final;
                           $produc->update();
                           $temp =  $produc->license;
                           $license = $temp[$ttl];
                            $oldCart = Session::has('cart') ? Session::get('cart') : null;
                            $cart = new Cart($oldCart);
                            $cart->updateLicense($prod['item']['id'],$license);  
                            Session::put('cart',$cart);
                           break;
                       }                    
                   }
           }
           }
        $settings = Generalsetting::findOrFail(1);
        $order = new Order();
        $paypal_email = $settings->paypal_business;
     
      
  
        $item_name = $settings->title." Order";
        $item_number = Str::random(10);
        $item_amount = (double)$request->total;


        // Append amount& currency (£) to quersytring so it cannot be edited in html
    
   
     
       
        //dd($arr);
    

       // Redirect to paypal IPN
   
                       $order['user_id'] = $request->user_id;
                       $order['cart'] = utf8_encode(bzcompress(serialize($cart), 9));
                       $order['totalQty'] = $request->totalQty;
                       $order['pay_amount'] = round($item_amount / $curr->value, 2);
                       $order['method'] = 'post_paid';
                       $order['customer_email'] = $request->email;
                       $order['customer_name'] = $request->name;
                       $order['customer_phone'] = $request->phone;
                       $order['order_number'] = $item_number;
                       $order['shipping'] = $request->shipping;
                       $order['pickup_location'] = $request->pickup_location;
                       $order['customer_address'] = $request->address;
                       $order['customer_country'] = $request->customer_country;
                       $order['customer_city'] = $request->city;
                       $order['customer_zip'] = $request->zip;
                       $order['shipping_email'] = $request->shipping_email;
                       $order['shipping_name'] = $request->shipping_name;
                       $order['shipping_phone'] = $request->shipping_phone;
                       $order['shipping_address'] = $request->shipping_address;
                       $order['shipping_country'] = $request->shipping_country;
                       $order['shipping_city'] = $request->shipping_city;
                       $order['shipping_zip'] = $request->shipping_zip;
                       $order['order_note'] = $request->order_notes;
                       $order['coupon_code'] = $request->coupon_code;
                       $order['coupon_discount'] = $request->coupon_discount;
                       $order['payment_status'] = "Pending";
                       $order['currency_sign'] = $curr->sign;
                       $order['currency_value'] = $curr->value;
                       $order['shipping_cost'] = $request->shipping_cost;
                       $order['packing_cost'] = $request->packing_cost;
                       $order['tax'] = $request->tax;
                       $order['dp'] = $request->dp;
   
           $order['vendor_shipping_id'] = $request->vendor_shipping_id;
           $order['vendor_packing_id'] = $request->vendor_packing_id;
   
           if (Session::has('affilate')) 
           {
               $val = $request->total / $curr->value;
               $val = $val / 100;
               $sub = $val * $gs->affilate_charge;
               $order['affilate_user'] = Session::get('affilate');
               $order['affilate_charge'] = $sub;
           }
                       $order->save();
                       if($request->coupon_id != "")
                       {
                          $coupon = Coupon::findOrFail($request->coupon_id);
                          $coupon->used++;
                          if($coupon->times != null)
                          {
                               $i = (int)$coupon->times;
                               $i--;
                               $coupon->times = (string)$i;
                          }
                          $coupon->update();
   
                       }
                       foreach($cart->items as $prod)
                       {
                   $x = (string)$prod['stock'];
                   if($x != null)
                   {
                               $product = Product::findOrFail($prod['item']['id']);
                               $product->stock =  $prod['stock'];
                               $product->update();                
                           }
                       }
           foreach($cart->items as $prod)
           {
               $x = (string)$prod['size_qty'];
               if(!empty($x))
               {
                   $product = Product::findOrFail($prod['item']['id']);
                   $x = (int)$x;
                   $x = $x - $prod['qty'];
                   $temp = $product->size_qty;
                   $temp[$prod['size_key']] = $x;
                   $temp1 = implode(',', $temp);
                   $product->size_qty =  $temp1;
                   $product->update();               
               }
           }
   
   
   
   
   
           foreach($cart->items as $prod)
           {
               $x = (string)$prod['stock'];
               if($x != null)
               {
   
                   $product = Product::findOrFail($prod['item']['id']);
                   $product->stock =  $prod['stock'];
                   $product->update();  
                   if($product->stock <= 5)
                   {
                       $notification = new Notification();
                       $notification->product_id = $product->id;
                       $notification->save();                    
                   }              
               }
           }
   
   
           $notf = null;
   
           foreach($cart->items as $prod)
           {
               if($prod['item']['user_id'] != 0)
               {
                   $vorder =  new VendorOrder();
                   $vorder->order_id = $order->id;
                   $vorder->user_id = $prod['item']['user_id'];
                   $notf[] = $prod['item']['user_id'];
                   $vorder->qty = $prod['qty'];
                   $vorder->price = $prod['price'];
                   $vorder->order_number = $order->order_number;             
                   $vorder->save();
               }
   
           }
   
           if(!empty($notf))
           {
               $users = array_unique($notf);
               foreach ($users as $user) {
                   $notification = new UserNotification();
                   $notification->user_id = $user;
                   $notification->order_number = $order->order_number;
                   $notification->save();    
               }
           }
                       Session::put('temporder_id',$order->id);
                       Session::put('tempcart',$cart);
   
   
   
     
   
                       if(Session::has('temporder_id')){
                        $order_id = Session::get('temporder_id');
                        $order = Order::find($order_id);
                        $tempcart = unserialize(bzdecompress(utf8_decode($order['cart'])));
                    }
                    else{
                        $tempcart = '';
                        return redirect()->back();
                    }
            
                     return view('front.success',compact('tempcart','order'));
   
    }
  
  
}
