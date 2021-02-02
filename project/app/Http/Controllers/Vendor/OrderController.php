<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\InstallmentDetails;
use Auth;
use App\Models\Order;
use App\Models\VendorOrder;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $orders = VendorOrder::where('user_id', '=', $user->id)->orderBy('id', 'desc')->get()->groupBy('order_number');

        return view('vendor.order.index', compact('user', 'orders'));
    }

    public function show($slug)
    {
        $user = Auth::user();
        $order = Order::where('order_number', '=', $slug)->first();
        $is_approved = false;
        $dateS = Carbon::now()->startOfMonth()->subMonth(3);
        $dateE = Carbon::now()->startOfMonth();
        $installments=null;
        $order_number = Order::where('customer_email', $order->customer_email)->whereBetween('created_at', [$dateS, $dateE])->count();
        $order_value = Order::where('customer_email', $order->customer_email)->whereBetween('created_at', [$dateS, $dateE])->sum('pay_amount');
        //customer  has paid before before and date and complete order 
        $last_post_paid = Order::where('customer_email', $order->customer_email)->where([['status', 'completed'], ['method', 'post_paid']])->latest('created_at')->first();
        if($order->post_paid_confirm>=1)
        {
            $installments=Installment::where('order_number',$order->order_number)->get();
        }
        $last_post_paid_date = null;
        if ($last_post_paid) {
            $last_post_paid_date = $last_post_paid->created_at;
        }
        $bronze = false;
        $sliver = false;
        $gold = false;
        $platinum = false;

        if ($order_number >= 0 && $order_number < 3 && $order_value > 49.0) {
            $bronze = true;
        } else if ($order_number > 3 && $order_number < 10 && $order_value > 49 && $order_value < 200) {
            $sliver = true;
        } else if ($order_number > 10 && $order_number < 30 && $order_value > 199 && $order_value < 2000) {
            $gold = true;
        } else if ($order_number > 15 && $order_value > 4999) {
            $platinum = true;
        }
     
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('vendor.order.details', compact('user','installments', 'order', 'cart', 'bronze', 'sliver', 'gold', 'platinum', 'order_number', 'order_value', 'last_post_paid_date'));
    }

    public function license(Request $request, $slug)
    {
        $order = Order::where('order_number', '=', $slug)->first();
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        $cart->items[$request->license_key]['license'] = $request->license;
        $order->cart = utf8_encode(bzcompress(serialize($cart), 9));
        $order->update();
        $msg = 'Successfully Changed The License Key.';
        return response()->json($msg);
    }

    public function confirm($id)
    {
        $user = Auth::user();
        $order = Order::where('order_number', '=', $id)->first();
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));



        return view('vendor.order.post_paid', compact('order', 'cart', 'user'));
    }

    public function invoice($slug)
    {
        $user = Auth::user();
        $order = Order::where('order_number', '=', $slug)->first();
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('vendor.order.invoice', compact('user', 'order', 'cart'));
    }

    public function printpage($slug)
    {
        $user = Auth::user();
        $order = Order::where('order_number', '=', $slug)->first();
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('vendor.order.print', compact('user', 'order', 'cart'));
    }

    public function checkproduct(Request $request)
    {

        $sum_of_values = array_sum($request->input('values'));


        $order = Order::where('order_number', '=', $request->order)->first();
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));

        $product_ids = [];
        $selected_product = $request->input('items');
        foreach ($selected_product as $product) {
            array_push($product_ids, $product['product_id']);
        }

        $cart_product_value = 0.0;
        foreach ($cart->items as $key => $product) {
            if (in_array($product['item']['id'], $product_ids)) {
                $cart_product_value += round($product['price'] * $order->currency_value, 2);
            }
        }
        if ($sum_of_values < $cart_product_value) {
            return ['success' => true, 'message' => 1, 'total' => $cart_product_value];
        } else if ($sum_of_values > $cart_product_value) {
            return ['success' => true, 'message' => 2, 'total' => $cart_product_value];
        } else {
            return ['success' => true, 'message' => 3, 'total' => $cart_product_value];
        }
        // dd(unserialize(bzdecompress(utf8_decode($request->input('items')))));


    }

    public function saveinstallment(Request $request)
    {
        $order = Order::where('order_number', '=', $request->order)->first();
      $values_of_installment=$request->input('values');
      $date_of_installment=$request->input('dates');
  
    for($i=0;$i<count($values_of_installment);$i++)
    {
        Installment::create([
            'order_number'=>$request->order,
            'date'=>$date_of_installment[$i],
            'value'=>$values_of_installment[$i]
        ]);
    }

    $product_ids = [];
    $selected_product = $request->input('items');
    foreach ($selected_product as $product) {
        InstallmentDetails::create([
            'order_number'=>$request->order,
            'product_id'=>$product['product_id'],
        ]);
    }

    $order->status='waiting_buyer';
    $order->post_paid_confirm=1;
    $order->save();
    $mainorder = VendorOrder::where('order_number', '=', $request->order)->first();
    $mainorder->status='waiting_buyer';
    
    $mainorder->save();
    return ['success' => true, 'message' => 1];
    }

    public function status($slug, $status)
    {
        $mainorder = VendorOrder::where('order_number', '=', $slug)->first();
        if ($mainorder->status == "completed") {
            return redirect()->back()->with('success', 'This Order is Already Completed');
        } else {

            $user = Auth::user();
            $order = VendorOrder::where('order_number', '=', $slug)->where('user_id', '=', $user->id)->update(['status' => $status]);
            return redirect()->route('vendor-order-index')->with('success', 'Order Status Updated Successfully');
        }
    }
}
