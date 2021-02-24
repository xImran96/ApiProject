<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealerOrder;
use App\Models\User;
use Illuminate\Support\Str;

class VendorOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function userToken()
    {
        $value = request()->header('authorization');
        $values = explode(" ", $value);
        return $values[1];
    }


    public function index()
    {

       

        try{

        $user = User::where('token', $this->userToken())->first();
           
             if(count($user->dealer_orders) != 0){
                 return response()->json(['status'=>'Success 200', 'orders'=>$user->dealer_orders]);
             }else{
                 return response()->json(['status'=>'Not Found 404', 'orders'=>`You Don't Have Any Orders`]);  
             }

     } catch (\Throwable $th) {
         return response()->json(['status'=>'Internal Server Error 500', 'Error'=>$th]);
     }
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try{
 
        $order  = new DealerOrder;
        $order->dealer_id = $request->dealer_id;
        $order->cart      =    $request->cart;
        $order->method    =  $request->method;
        $order->shipping  = $request->shipping;
        $order->pickup_location =  $request->pickup_location;
        $order->totalQty = $request->totalQty;
        $order->pay_amount =  $request->pay_amount; 
        $order->txnid = $request->txnid;
        $order->charge_id = $request->charge_id;
        $order->order_number = Str::random(10);
        $order->payment_status = $request->payment_status;
        $order->customer_email = $request->customer_email;
        $order->customer_name = $request->customer_name;
        $order->customer_country =  $request->customer_country;
        $order->customer_phone = $request->customer_phone;
        $order->customer_address = $request->customer_address;
        $order->customer_city = $request->customer_city;
        $order->customer_zip = $request->customer_zip;
        $order->shipping_name = $request->shipping_name;
        $order->shipping_country =$request->shipping_country;
        $order->shipping_email = $request->shipping_email;
        $order->shipping_phone =$request->shipping_phone;
        $order->shipping_address = $request->shipping_address;
        $order->shipping_city = $request->shipping_city;
        $order->shipping_zip = $request->shipping_zip;
        $order->order_note=$request->order_note;
        $order->coupon_code = $request->coupon_code;
        $order->coupon_discount = $request->coupon_discount ;
        $order->status = $request->status;
        $order->post_paid_confirm = $request->post_paid_confirm;
        $order->affilate_user = $request->affilate_user;
        $order->affilate_charge = $request->affilate_charge;
        $order->currency_sign = $request->currency_sign;
        $order->currency_value = $request->currency_value;
        $order->shipping_cost =  $request->shipping_cost;
        $order->packing_cost = $request->packing_cost;
        $order->tax =  $request->tax;
        $order->dp = $request->dp ;
        $order->pay_id =  $request->pay_id;
        $order->dealer_shipping_id =  $request->dealer_shipping_id;
        $order->dealer_packing_id = $request->dealer_packing_id;
        $order->save();

        $user = User::where('token', $this->userToken())->first();


                $log = new Log([
                        'topic'=>'Order',
                        'code'=>200,
                        'log_topic'=>'Order-Placed',
                        'log_message'=> $order->id.' '.$order->order_number.' is Placed Successfully.',
                        'log_level'=>'recieved_order',
                        ]);

             $user->logs()->save()


           if($user->logs()->save()){

             return response()->json(['status'=>'Success 200', 'message'=>'Order Has Been Placed Succesfully.']);
           }

        }catch(\Throwable $th){

            return response()->json(['status'=>'Internal Server Error 500', 'Error'=>$th]);
        }
      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
