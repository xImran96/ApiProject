<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealerOrder;
use App\Models\DealerOrderDetail;
use App\Models\User;
use DB;
use App\Models\Log;
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

             // dd($request->cart['items']);

            $myArray =[];

            for ($i=0; $i < count($request->cart['items']) ; $i++) { 
                    
                    $myArray[] = serialize($request->cart['items'][$i]);
                     

             }

            $myArray2 = implode(",", $myArray);

            // dd($myArray2);
            $user = User::where('token', $this->userToken())->first();

    
        try{


        DB::beginTransaction();

        
        $order  = new DealerOrder;
        $order->dealer_id = $user->id;
        $order->cart      = $myArray2;
        $order->method    =  $request->method;
        $order->shipping  = $request->shipping;
        $order->pickup_location =  $request->pickup_location;
        $order->totalQty = $request->totalQty;
        $order->pay_amount =  $request->pay_amount; 
        $order->txnid = $request->txnid;
        $order->charge_id = $request->charge_id;
        $order->order_number = Str::random(10);
        $order->payment_status = $request->payment_status;
        $order->customer_email = $request->customer['email'];
        $order->customer_name = $request->customer['name'];
        $order->customer_country =  $request->customer['country'];
        $order->customer_phone = $request->customer['phone'];
        $order->customer_address = $request->customer['address'];
        $order->customer_city = $request->customer['city'];
        $order->customer_zip = $request->customer['zip'];
        $order->shipping_name = $request->shipping_details['name'];
        $order->shipping_country =$request->shipping_details['country'];
        $order->shipping_email = $request->shipping_details['email'];
        $order->shipping_phone =$request->shipping_details['phone'];
        $order->shipping_address = $request->shipping_details['address'];
        $order->shipping_city = $request->shipping_details['city'];
        $order->shipping_zip = $request->shipping_details['zip'];
        $order->order_note=$request->order_note;
        $order->coupon_code = $request->coupon_code;
        $order->coupon_discount = $request->coupon_discount ;
        $order->status = $request->status;
        $order->currency_sign = $request->currency['sign'];
        $order->currency_value = $request->currency['value'];
        $order->shipping_cost =  $request->shipping_cost;
        $order->packing_cost = $request->packing_cost;
        $order->tax = 0;
        $order->dp = $request->dp;
        $order->pay_id =  $request->pay_id;

        $order->save();


        $details = DealerOrderDetail::create([
                                'dealer_id'=>$user->id,
                                'dealer_order_id'=>$order->id,
                                'qty'=> count($request->cart['items']),
                                'price'=>$order->pay_amount,
                                'dealer_order_number'=>$order->order_number,
                                'status'=>'pending'
                                ]);
        

        $log = Log::create([
                        'user_id'=>$user->id,
                        'topic'=>'Vendor',
                        'code'=>200,
                        'log_topic'=>'Vendor-Order',
                        'log_message'=> $order->order_number.' '.$order->customer_name.' has place order Successfully.',
                        'log_level'=>'order-placed',
        ]);

        if ($order && $details && $log) {
                DB::commit();
                 return response()->json(['status'=>'Success 200', 'message'=>'Order Has Been Placed Succesfully.']);
        }else{
            DB::rollback();
             return response()->json(['status'=>'Success 404', 'message'=>'Check Your Daata.']);
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

        $user = User::where('token', $this->userToken())->first();



        if(!$user->dealer_orders()->where('id', $id)->first())
        {

            return "Sorry the order does not exist.";

        }
        // $order = Order::findOrFail($id);
        $order = $user->dealer_orders()->where('id', $id)->first();
       
          return response()->json(['status'=>'200', 'Order'=>$order]);
        // return response()->json(['success'=>$order]);
      
         }catch(\Throwable $th){
     
            return response()->json(['status'=>'Internal Server Error 500', 'Error'=>$th]);
        
        }
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

    public function cancel($id){
       
        try{
            $user = User::where('token', $this->userToken())->first();
            if(!$user){
                return response()->json(['status'=>'Access token is missing or invalid, request new one 401']);  
            }
         if(!DealerOrder::where('id',$id)->where('dealer_id',$user->id)->exists())
        {
            return response()->json(['status'=>'Not Found 404', 'VendorOrder'=>`This Order Does Not Exist`]);  
        }
        $vendororder = DealerOrder::findOrFail($id);
        $vendororder->status = 'declined';
        $vendororder->update();
        $vendororderDetail = DealerOrderDetail::where('dealer_id',$user->id)->where('dealer_order_id',$id)->first();
        if($vendororderDetail){
          $vendororderDetail->status = 'declined';
          $vendororderDetail->update();
        }
         
        
       
        return response()->json(['success'=>'Order Declined']);
    }catch(\Throwable $th){
     return response()->json(['status'=>'Internal Server Error 500', 'Error'=>$th]);
    }
    }
}
