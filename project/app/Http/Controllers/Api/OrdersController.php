<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Auth;
use App\Models\Invoice;


class OrdersController extends Controller
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
        try {
            $user = User::where('token', $this->userToken())->first();
             if(count($user->orders)!=0){
                 return response()->json(['status'=>'Success 200', 'orders'=>$user->orders]);
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
        dd($request->all());
        try{
        $order  = new Order;
        $order->user_id =  $request->user_id;
        $order->cart =    $request->cart;
        $order->method =  $request->method;
        $order->shipping = $request->shipping;
        $order->pickup_location =  $request->pickup_location;
        $order->totalQty = $request->totalQty;
        $order->pay_amount =  $request->pay_amount; 
        $order->txnid = $request->txnid;
        $order->charge_id = $request->charge_id;
        $order->order_number = $request->order_number;
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
        $order->vendor_shipping_id =  $request->vendor_shipping_id;
        $order->vendor_packing_id = $request->vendor_packing_id;
        



       if($order->save()){
         
        $invoice  = new Invoice;
        $invoice->user_id =  $request->user_id;
        $invoice->order_id = $order->id;
        $invoice->cart =    $request->cart;
        $invoice->method =  $request->method;
        $invoice->shipping = $request->shipping;
        $invoice->pickup_location =  $request->pickup_location;
        $invoice->totalQty = $request->totalQty;
        $invoice->pay_amount =  $request->pay_amount; 
        $invoice->txnid = $request->txnid;
        $invoice->charge_id = $request->charge_id;
        $invoice->order_number = $request->order_number;
        $invoice->payment_status = $request->payment_status;
        $invoice->customer_email = $request->customer_email;
        $invoice->customer_name = $request->customer_name;
        $invoice->customer_country =  $request->customer_country;
        $invoice->customer_phone = $request->customer_phone;
        $invoice->customer_address = $request->customer_address;
        $invoice->customer_city = $request->customer_city;
        $invoice->customer_zip = $request->customer_zip;
        $invoice->shipping_name = $request->shipping_name;
        $invoice->shipping_country =$request->shipping_country;
        $invoice->shipping_email = $request->shipping_email;
        $invoice->shipping_phone =$request->shipping_phone;
        $invoice->shipping_address = $request->shipping_address;
        $invoice->shipping_city = $request->shipping_city;
        $invoice->shipping_zip = $request->shipping_zip;
        $invoice->order_note=$request->order_note;
        $invoice->coupon_code = $request->coupon_code;
        $invoice->coupon_discount = $request->coupon_discount ;
        $invoice->status = $request->status;
        $invoice->post_paid_confirm = $request->post_paid_confirm;
        $invoice->affilate_user = $request->affilate_user;
        $invoice->affilate_charge = $request->affilate_charge;
        $invoice->currency_sign = $request->currency_sign;
        $invoice->currency_value = $request->currency_value;
        $invoice->shipping_cost =  $request->shipping_cost;
        $invoice->packing_cost = $request->packing_cost;
        $invoice->tax =  $request->tax;
        $invoice->dp = $request->dp ;
        $invoice->pay_id =  $request->pay_id;
        $invoice->vendor_shipping_id =  $request->vendor_shipping_id;
        $invoice->vendor_packing_id = $request->vendor_packing_id;
        if($invoice->save()){
            return response()->json(['success'=>'200']);
        }
        return response()->json(['orderCreated'=>'But Invoice not']);
        
       }else{
        return response()->json(['error'=>'something went wrong']);
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
        try{
        if(!Order::where('id',$id)->exists())
        {
            return "Sorry the page does not exist.";
        }
        $order = Order::findOrFail($id);
       
        return response()->json(['success'=>$order]);
         }catch(\Throwable $th){
     return response()->json(['status'=>'Internal Server Error 500', 'Error'=>$th]);
    }
    //    return $order;
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
