<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealerOrderDetail;
use Auth;
use App\Models\DealerOrder;
use Carbon\Carbon;
use App\Models\Installment;

class DealerOrdersController extends Controller
{
        //*** GET Request
    public function index()
    {
        $user = Auth::user();
        $orders = DealerOrderDetail::where('dealer_id', '=', $user->id)->orderBy('id', 'desc')->get()->groupBy('order_number');
      
        return view('vendor.dealer.index', compact('user', 'orders'));

    }


      public function show($slug)
    {
        $user = Auth::user();
        $order = DealerOrder::where('order_number', '=', $slug)->first();
        // dd($order);
        $is_approved = false;
        $dateS = Carbon::now()->startOfMonth()->subMonth(3);
        $dateE = Carbon::now()->startOfMonth();
        
        $order_number = DealerOrder::where('customer_email', $order->customer_email)->whereBetween('created_at', [$dateS, $dateE])->count();
        $order_value = DealerOrder::where('customer_email', $order->customer_email)->whereBetween('created_at', [$dateS, $dateE])->sum('pay_amount');
        //customer  has paid before before and date and complete order 

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
        
        $getcart =  explode(',', $order->cart);
        // dd(unserialize($cart[1]));
        foreach ($getcart as $item) {
            $cart[] = unserialize($item);
        }
        // dd($cart);
         

        // dd($cart);
        return view('vendor.dealer.details', compact('user','order', 'cart', 'bronze', 'sliver', 'gold', 'platinum', 'order_number', 'order_value'));
    }

}
