<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealerOrder;
use App\Models\PaymentGateway;
use App\Models\VendorCart;
use App\Models\Generalsetting;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserNotification;
use App\Models\VendorOrder;
use App\Models\OrderTrack;
use Session;
use App\Models\Notification;
use Str;
use Carbon\Carbon;
use App\Models\Pagesetting;
use App\Classes\GeniusMailer;
use Auth;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;

class DealerHareerController extends Controller
{
    
    public function makeOrder(Request $request)
    {
    	

    	 $vOrder = DealerOrder::where('order_number', $request->order_number)->first();
       // dd($vOrder);
       $subs = auth()->user()->subscribes()->where('status',1)->orderBy('id','desc')->first();
 // dd($subs);
    	 $gt = PaymentGateway::where('title', $request->method)->first();
    	 $currency = Currency::where('name', $vOrder->currency_sign)->first();
       $user = Auth::user();
       // dd($user);

    	switch ($request->method){
    			case 'My fatoora':
    				//my fatoora


               $myItems=[];
              $totalQty=0;
              $totalPrice=0;

                $cart_items = explode(',', $vOrder->cart);
                foreach ($cart_items as $key => $item) {

                    $myCartItem = unserialize($item);

                    // ($myCartItem);
                    $prod = Product::find($myCartItem['item_id']);

                    // ($prod);
                    unset($myCartItem['item_id']);
                        
                    $myCartItem['item'] = $prod;


                    $myCartItem['item_price'] = $prod->price;
                    $myCartItem['price'] = $myCartItem['item_price']*$myCartItem['qty'];


                    $myItems[] =  $myCartItem;
                    $totalQty++;
                    $totalPrice +=$myCartItem['price'];
               


               
           }

              $serviceCharges =  ($subs->per_delivery_charges + $subs->per_order_charges + $subs->preparation);            
              $cart = new VendorCart($myItems, $vOrder->totalQty, $totalPrice);


        // dd($cart);
        $settings = Generalsetting::findOrFail(1);
        $order = new Order();
        $paypal_email = $settings->paypal_business;
     
        $return_url = action('Front\MyFatoorahController@payreturn');
         // $return_url = 'https://www.youtube.com/';
        $cancel_url = action('Front\MyFatoorahController@paycancle');
         // $cancel_url = 'http://www.trustechsol.com/';

        // dd($return_url);
      
  
        $item_name = $settings->title." Order";
        $item_number = Str::random(10);
        $item_amount = (double) $cart->totalPrice;

        // dd($item_amount);


        // Append amount& currency (£) to quersytring so it cannot be edited in html
        $InvoiceItems = null;

        // dd($cart->items);

        foreach($cart->items as $prod)
        {
          // dd($prod);
            $product = Product::findOrFail($prod['item']['id']);
         // dd($product);
            $x = (string)$prod['qty'];
           // dd((int)$x);
            //dd($product->price * (int)$x);
            $data = array(
                'ItemName' => $product->name_en,
                'Quantity' => (int)$x,
                'UnitPrice' => $product->price * (int)$x,
                ''
            );
        }
     
        $keys = explode(',', implode(",", array_keys($data))); // values strings

        $arr = array_map('strval', array_keys($data)); // values strings
        $arr = json_encode($data); // int strings
     

        
        // dd($arr);
           //The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
        $token = "ytMSqvTCONA-yaRAxs-WeD8S4b-gRqZDbBk8tH9LWEOPCL9GLfMF-KBJXDKLWW6ye27t3c9Gv6wzNh5YpFIKqKv5OEIeDeSuwCysHaDhLy9Kh7v1oJSlAtORMnNCusqob9uEa1j1kp_1horXh131TEsK7lmqQaKF9s93iTFLWbtcq_EtqYbc29CsVqaJM-AfS9Gcqs9-zZFTL0chnFoU55nyUbvnZ68tH4GkVxLu48UwCF_JQQkblzMxTgjPqHUQJDl-1DSxYZJj_Cg3qZJWeSJkBMkzhRPzDWpelo4QcknOtWiOmkdhXAI-n4UCWE7L_vAEtc7b-BkNIz_1GSDxx51H5KY6YN1FmDEJiGX2XKPzqqlR-aQ92ur30utuQV8_qDriKLNIDoaPYcQHshEcqajQVo8EMF7W9LO_2b695oU2h5SOH-e3ol14iqKheF_nWEw749n1cDhgEBLR_yC81Uz3x3j-yMQLfurir_v9D7Wjv4PMS-e5mq2e3A_wXzoGjTg3yNABZsijH7OCJ7ulGToT6E2lhFEx-f1S_Ya4GJNREOV-1LfADWF3y_GE4xBGK1Il2lfv8cfeHqPzhBURloRZZ0OkC5nvXTNyKTRgUDAnRO17GzKQh4Kt8x7Ojmfw8AtZNJ7z3LkJ_BtS_jOqQxJgR474XvE7z1wLhth9-dmfDHnY"; #token value to be placed here;
        $basURL = "https://api.myfatoorah.com";   
        
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
  
        CURLOPT_URL => "$basURL/v2/SendPayment",
  
        CURLOPT_CUSTOMREQUEST => "POST",
        
        CURLOPT_POSTFIELDS => "{\"NotificationOption\":\"ALL\",\"CustomerName\": \"$user->name\",\"DisplayCurrencyIso\": \"SAR\",\"MobileCountryCode\":\"+965\",\"CustomerMobile\": \"$user->phone\",\"CustomerEmail\": \"$user->email\",\"InvoiceValue\": $item_amount, \"CallBackUrl\": \"$return_url\",\"ErrorUrl\": \"$cancel_url\",\"Language\": \"en\", \"CustomerReference\" :\"ref $user->id\",\"CustomerCivilId\":$user->id,\"UserDefinedField\": \"Custom field\", \"ExpireDate\": \"\",\"CustomerAddress\" :{\"Block\":\"\",\"Street\":\"\",\"HouseBuildingNo\":\"\",\"Address\":\"$user->address\",\"AddressInstructions\":\"\"},  \"InvoiceItems\": [$arr]}",
        
        CURLOPT_HTTPHEADER => array("Authorization: Bearer $token","Content-Type: application/json"),

        ));
  
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err){


  }else {
    echo "$response<br/>'";
  }

$json  = json_decode((string)$response, true);
// dd($json);
//echo "json  json: $json '<br />'";
$data=$json["IsSuccess"];



// {
//     "dealer_id": "13",
//     "cart" : {
        
//             "items": [
//                        { 
//                     "qty":"1",
//                     "size_key": "",
//                     "size_qty": "3",
//                     "size_price":"medium",
//                      "size":"12",
//                      "color":"pink",
//                      "stock":"",
//                      "price":"220.8",
//                      "item_id":"103",
//                      "license":"",
//                      "dp":"",
//                      "keys":"",
//                      "value":"",
//                      "item_price":"77.8"
//                 }
//             ],
//         "totalQty":"1",
//         "totalPrice":"122"    
        
//     },
//     "method"    : "Paypal",
//     "shipping"  : "shipping",
//     "shipping_details": {
//         "name": "Muhammad Imran",
//         "email": "x.imran96@gmail.com",
//         "country":"Pakistan",
//         "phone":"03152471996",
//         "address":"peshawar",
//         "city":"peshawar",
//         "zip":"25000"
//     },
//     "customer":{
//         "email": "x.imran96@gmail.com",
//         "name": "Muhammad Imran",
//         "country": "UAE",
//         "phone":"03152471993",
//         "address":"Jamaira Islands",
//         "city":"Dubai",
//         "zip":"25000"
//     },
//     "pickup_location" :"Peshawar",
//     "totalQty" : "3",
//     "pay_amount" : "132.6",
//     "txnid" : "sadasdas",
//     "charge_id": "charge_id",
//     "order_number": "Str::random(10)",
//     "payment_status": "Done",
//    "order_note": "It was a great Order",
//    "coupon_code": "asdasd",
//    "coupon_discount": "25%",
//    "status":"Pending",
//     "currency":{
//         "sign": "SAR",
//         "value":"72"
//     },
//     "shipping_cost":"12",
//     "packing_cost":"12",
//     "tax": "20",
//      "dp": "1",
//      "pay_id": "1"

// }





          $payment_url = $json["Data"]["InvoiceURL"];
// dd($payment_url);
       // Redirect to paypal IPN
   
                       $order['user_id'] = auth()->user()->id;
                       $order['cart'] = utf8_encode(bzcompress(serialize($cart), 9));
                       $order['totalQty'] = $vOrder->totalQty;
                       $order['pay_amount'] = round(($item_amount + $subs->per_delivery_charges + $subs->per_order_charges + $subs->preparation) / $currency->value, 2);
                       $order['method'] = $request->method;
                       $order['customer_email'] = auth()->user()->email;
                       $order['customer_name'] = auth()->user()->name;
                       $order['customer_phone'] = auth()->user()->phone;
                       $order['order_number'] = $vOrder->order_number;
                       $order['shipping'] = $vOrder->shipping;
                       $order['pickup_location'] = $vOrder->pickup_location;
                       $order['customer_address'] = auth()->user()->address;
                       $order['customer_country'] = auth()->user()->country;
                       $order['customer_city'] = auth()->user()->city;
                       $order['customer_zip'] = auth()->user()->zip;
                       $order['shipping_email'] = $vOrder->shipping_email;
                       $order['shipping_name'] = $vOrder->shipping_name;
                       $order['shipping_phone'] = $vOrder->shipping_phone;
                       $order['shipping_address'] = $vOrder->shipping_address;
                       $order['shipping_country'] = $vOrder->shipping_country;
                       $order['shipping_city'] = $vOrder->shipping_city;
                       $order['shipping_zip'] = $vOrder->shipping_zip;
                       $order['order_note'] = $vOrder->order_notes;
                       $order['payment_status'] = "Pending";
                       $order['currency_sign'] = $vOrder->currency_sign;
                       $order['currency_value'] = $vOrder->currency_value;
                       $order['shipping_cost'] = $vOrder->shipping_cost;
                       $order['packing_cost'] = $vOrder->packing_cost;
                       $order['tax'] = $vOrder->tax;
                       $order['dp'] = $vOrder->dp;   
                       $order['vendor_shipping_id'] = 0;
                       $order['vendor_packing_id'] = 0;
   

                       $order->save();
                      
                       
                      foreach($cart->items as $prod)
                        {   
                            $x = (string)$prod['qty'];

                            $y = (string)$prod['size_qty']; 
                            if(!empty($x) & empty($y))
                            {   
                                $x = (int)$x;
                                $product = Product::findOrFail($prod['item']['id']);
                                $updateStock = $product->stock - $x; 
                                $product->stock = $updateStock;
                                $product->update();
                                // dd($product);               
                            }
                            else if(!empty($y) && !empty($x)) {
                                
                                $product = Product::findOrFail($prod['item']['id']);
                                $x = (int)$x;
                                $y = (int)$y;
                                $y = $y - $x;
                                $temp = $product->size_qty;
                                $temp[$prod['size_key']] = $y;
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
            if(auth()->user())
            {
                $vorder =  new VendorOrder;
                $vorder->order_id = $order->id;
                $vorder->user_id = auth()->user()->id;
                $notf[] = auth()->user()->id;
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
                $notification = new UserNotification;
                $notification->user_id = $user;
                $notification->order_number = $order->order_number;
                $notification->save();    
            }
        }

   
    
      
        return redirect($payment_url);
    	
    			break;


    			case 'cashOnDelivery':
    				//Cash On Delivery


              $myItems=[];
              $totalQty=0;
              $totalPrice=0;

              $cart_items = explode(',', $vOrder->cart);
                foreach ($cart_items as $key => $item) {



                    $myCartItem = unserialize($item);
                    // ($myCartItem);
                    $prod = Product::find($myCartItem['item_id']);
                    // ($prod);
                    unset($myCartItem['item_id']);
                    $myCartItem['item'] = $prod;

                    $myCartItem['item_price'] = $prod->price;
                    $myCartItem['price'] = $myCartItem['item_price']*$myCartItem['qty'];


                    $myItems[] =  $myCartItem;
                    $totalQty++;
                    $totalPrice +=$myCartItem['price'];
               
                   }

                   $cart = new VendorCart($myItems, $vOrder->totalQty, $myCartItem['price']);
                   $gs = Generalsetting::findOrFail(1);



                        $order = new Order;
                       
                        $success_url = action('Front\PaymentController@payreturn');
                        
                        $item_name = $gs->title." Order";
                        $item_number = Str::random(10);
                        
                        $order['user_id'] = auth()->user()->id;
                        $order['cart'] = utf8_encode(bzcompress(serialize($cart), 9)); 
                        $order['totalQty'] = $vOrder->totalQty;
                        $order['pay_amount'] = round(($cart->totalPrice + $subs->per_delivery_charges + $subs->per_order_charges + $subs->preparation) / $currency->value, 2);
                        $order['method'] = 'Cash-On-Delivery';
                        $order['shipping'] = $vOrder->shipping;
                        $order['pickup_location'] = $vOrder->pickup_location;
                        $order['customer_email'] = auth()->user()->email;
                        $order['customer_name'] = auth()->user()->name;
                        $order['shipping_cost'] = $vOrder->shipping_cost;
                        $order['packing_cost'] = $vOrder->packing_cost;
                        $order['tax'] = $vOrder->tax;
                        $order['customer_phone'] = auth()->user()->phone;
                        $order['order_number'] = $vOrder->order_number;
                        $order['customer_address'] = auth()->user()->address;
                        $order['customer_country'] = auth()->user()->country;
                        $order['customer_city'] = auth()->user()->city;
                        $order['customer_zip'] = auth()->user()->zip;
                        $order['shipping_email'] = $vOrder->shipping_email;
                        $order['shipping_name'] = $vOrder->shipping_name;
                        $order['shipping_phone'] = $vOrder->shipping_phone;
                        $order['shipping_address'] = $vOrder->shipping_address;
                        $order['shipping_country'] = $vOrder->shipping_country;
                        $order['shipping_city'] = $vOrder->shipping_city;
                        $order['shipping_zip'] = $vOrder->shipping_zip;
                        $order['order_note'] = $vOrder->order_notes;
                        $order['coupon_code'] = $vOrder->coupon_code;
                        $order['coupon_discount'] = $vOrder->coupon_discount;
                        $order['dp'] = $vOrder->dp;
                        $order['payment_status'] = "Pending";
                        $order['currency_sign'] = $currency->sign;
                        $order['currency_value'] = $currency->value;
                        $order['vendor_shipping_id'] = 0;
                        $order['vendor_packing_id'] = 0;

                        // dd($order);
                        $order->save();

                        $track = new OrderTrack;
                        $track->title = 'Pending';
                        $track->text = 'You have successfully placed your order.';
                        $track->order_id = $order->id;
                        $track->save();

                        $notification = new Notification;
                        $notification->order_id = $order->id;
                        $notification->save();

                        foreach($cart->items as $prod)
                        {   
                            $x = (string)$prod['qty'];

                            $y = (string)$prod['size_qty']; 
                            if(!empty($x) & empty($y))
                            {   
                                $x = (int)$x;
                                $product = Product::findOrFail($prod['item']['id']);
                                ;
                                $updateStock = $product->stock - $x; 
                                $product->stock = $updateStock;
                                $product->update();
                                $importproduct = auth()->user()->myProducts()->where('product_id', $product->id)->first();
                                $importproduct->stock = $updateStock;
                                $importproduct->update();



                                // dd($importproduct);               
                            }
                            else if(!empty($y) && !empty($x)) {
                                
                                $product = Product::findOrFail($prod['item']['id']);
                                $x = (int)$x;
                                $y = (int)$y;
                                $y = $y - $x;
                                $temp = $product->size_qty;
                                $temp[$prod['size_key']] = $y;
                                $temp1 = implode(',', $temp);
                                $product->size_qty =  $temp1;
                                $updateStock = $product->stock - $x; 
                                $product->update(); 

                                $importproduct = auth()->user()->myProducts()->where('product_id', $product->id)->first();

                                $tempImport = $importproduct->size_qty;
                                $tempImport[$prod['size_key']] = $y;
                                $temp1Import = implode(',', $tempImport);
                                $importproduct->size_qty =  $temp1Import;
                                $importproduct->stock =  $updateStock;
                                $importproduct->update();


                            }
                        }


                  foreach($cart->items as $prod)
                  {
                      
                          $product = Product::findOrFail($prod['item']['id']);
                          if($product->stock <= 5)
                          {
                              $notification = new Notification;
                              $notification->product_id = $product->id;
                              $notification->save();                    
                          }              
                      
                  }

        $notf = null;

        foreach($cart->items as $prod)
        {
            if(auth()->user())
            {
                $vorder =  new VendorOrder;
                $vorder->order_id = $order->id;
                $vorder->user_id = auth()->user()->id;
                $notf[] = auth()->user()->id;
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
                $notification = new UserNotification;
                $notification->user_id = $user;
                $notification->order_number = $order->order_number;
                $notification->save();    
            }
        }



        //Sending Email To Buyer

        if($gs->is_smtp == 1)
        {
        $data = [
            'to' => auth()->user()->email,
            'type' => "new_order",
            'cname' => auth()->user()->name,
            'oamount' => "",
            'aname' => "",
            'aemail' => "",
            'wtitle' => "",
            'onumber' => $order->order_number,
        ];

        $mailer = new GeniusMailer();
        $mailer->sendAutoOrderMail($data,$order->id);            
        }
        else
        {
           $to = $request->email;
           $subject = "Your Order Placed!!";
           $msg = "Hello ".$request->name."!\nYou have placed a new order.\nYour order number is ".$order->order_number.".Please wait for your delivery. \nThank you.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
           mail($to,$subject,$msg,$headers);            
        }
        //Sending Email To Admin
        if($gs->is_smtp == 1)
        {
            $data = [
                'to' => Pagesetting::find(1)->contact_email,
                'subject' => "New Order Recieved!!",
                'body' => "Hello Admin!<br>Your store has received a new order.<br>Order Number is ".$order->order_number.".Please login to your panel to check. <br>Thank you.",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);            
        }
        else
        {
           $to = Pagesetting::find(1)->contact_email;
           $subject = "New Order Recieved!!";
           $msg = "Hello Admin!\nYour store has recieved a new order.\nOrder Number is ".$order->order_number.".Please login to your panel to check. \nThank you.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
           mail($to,$subject,$msg,$headers);
        }



        return redirect()->route('vendor-dashboard');
























    			break;
    		
    		// default:
    		// 	# code...
    		// 	break;
    	}





    }
}
