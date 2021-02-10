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

class MyFatoorahController extends Controller
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
     
        $return_url = action('Front\MyFatoorahController@payreturn').'com';
        $cancel_url = action('Front\MyFatoorahController@paycancle').'com';
      
  
        $item_name = $settings->title." Order";
        $item_number = Str::random(10);
        $item_amount = (double)$request->total;


        // Append amount& currency (£) to quersytring so it cannot be edited in html
        $InvoiceItems = null;
        foreach($cart->items as $prod)
        {
            $product = Product::findOrFail($prod['item']['id']);
         
            $x = (string)$prod['qty'];
           // dd((int)$x);
            //dd($product->price * (int)$x);
            $data = array(
                'ItemName' => $product->name,
                'Quantity' => (int)$x,
                'UnitPrice' => $product->price * (int)$x,
            );
        }
     
        $keys = explode(',', implode(",", array_keys($data))); // values strings

        $arr = array_map('strval', array_keys($data)); // values strings
        $arr = json_encode($data); // int strings
     
       
        //dd($arr);
        //The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
        $token =  "ytMSqvTCONA-yaRAxs-WeD8S4b-gRqZDbBk8tH9LWEOPCL9GLfMF-KBJXDKLWW6ye27t3c9Gv6wzNh5YpFIKqKv5OEIeDeSuwCysHaDhLy9Kh7v1oJSlAtORMnNCusqob9uEa1j1kp_1horXh131TEsK7lmqQaKF9s93iTFLWbtcq_EtqYbc29CsVqaJM-AfS9Gcqs9-zZFTL0chnFoU55nyUbvnZ68tH4GkVxLu48UwCF_JQQkblzMxTgjPqHUQJDl-1DSxYZJj_Cg3qZJWeSJkBMkzhRPzDWpelo4QcknOtWiOmkdhXAI-n4UCWE7L_vAEtc7b-BkNIz_1GSDxx51H5KY6YN1FmDEJiGX2XKPzqqlR-aQ92ur30utuQV8_qDriKLNIDoaPYcQHshEcqajQVo8EMF7W9LO_2b695oU2h5SOH-e3ol14iqKheF_nWEw749n1cDhgEBLR_yC81Uz3x3j-yMQLfurir_v9D7Wjv4PMS-e5mq2e3A_wXzoGjTg3yNABZsijH7OCJ7ulGToT6E2lhFEx-f1S_Ya4GJNREOV-1LfADWF3y_GE4xBGK1Il2lfv8cfeHqPzhBURloRZZ0OkC5nvXTNyKTRgUDAnRO17GzKQh4Kt8x7Ojmfw8AtZNJ7z3LkJ_BtS_jOqQxJgR474XvE7z1wLhth9-dmfDHnY"; #token value to be placed here;
        $basURL = "https://api.myfatoorah.com";   
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "$basURL/v2/SendPayment",
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"NotificationOption\":\"ALL\",\"CustomerName\": \"$request->name\",\"DisplayCurrencyIso\": \"SAR\",\"MobileCountryCode\":\"+965\",\"CustomerMobile\": \"$request->phone\",\"CustomerEmail\": \"$request->email\",\"InvoiceValue\": $item_amount, \"CallBackUrl\": \"$return_url\",\"ErrorUrl\": \"$cancel_url\",\"Language\": \"en\", \"CustomerReference\" :\"ref $request->user_id\",\"CustomerCivilId\":$request->user_id,\"UserDefinedField\": \"Custom field\", \"ExpireDate\": \"\",\"CustomerAddress\" :{\"Block\":\"\",\"Street\":\"\",\"HouseBuildingNo\":\"\",\"Address\":\"$request->address\",\"AddressInstructions\":\"\"},  \"InvoiceItems\": [$arr]}",
    CURLOPT_HTTPHEADER => array("Authorization: Bearer $token","Content-Type: application/json"),

));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
} else {
  echo "$response '<br />'";
}
$json  = json_decode((string)$response, true);
//echo "json  json: $json '<br />'";
$data=$json["IsSuccess"];
if(!$data)
{
    return redirect()->route('front.checkout')->with('unsuccess','Payment Cancelled.');

}
$payment_url = $json["Data"]["InvoiceURL"];

       // Redirect to paypal IPN
   
                       $order['user_id'] = $request->user_id;
                       $order['cart'] = utf8_encode(bzcompress(serialize($cart), 9));
                       $order['totalQty'] = $request->totalQty;
                       $order['pay_amount'] = round($item_amount / $curr->value, 2);
                       $order['method'] = $request->method;
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
   
   
   
     
   
        return redirect($payment_url);
   
    }
    public function paycancle(){
       
         return redirect()->route('front.checkout')->with('unsuccess','Payment Cancelled.');
     }

     public function payreturn(){
       
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
