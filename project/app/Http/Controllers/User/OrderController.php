<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Installment;
use Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\PaymentGateway;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function orders()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::where('user_id','=',$user->id)->orderBy('id','desc')->get();
        return view('user.order.index',compact('user','orders'));
    }

    public function ordertrack()
    {
        $user = Auth::guard('web')->user();
        return view('user.order-track',compact('user'));
    }

    public function trackload($id)
    {
        $order = Order::where('order_number','=',$id)->first();
        $datas = array('Pending','Processing','On Delivery','Completed');
        return view('load.track-load',compact('order','datas'));

    }

    public function accept(Request $request)
    {
        $order = Order::where('order_number','=',$request->order)->first();

        $order->status='accept_buyer';
        $order->post_paid_confirm=2;
        if($order->save())
        {
            return ['success' => true, 'message' => 1];
        }

    }

    public function order($id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::findOrfail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        $installments=null;
        if($order->post_paid_confirm>=1)
              {
                  $installments=Installment::where('order_number',$order->order_number)->get();
              }
        return view('user.order.details',compact('user','order','cart','installments'));
    }

    public function orderdownload($slug,$id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::where('order_number','=',$slug)->first();
        $prod = Product::findOrFail($id);
        if(!isset($order) || $prod->type == 'Physical' || $order->user_id != $user->id)
        {
            return redirect()->back();
        }
        return response()->download(public_path('assets/files/'.$prod->file));
    }

    public function orderprint($id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::findOrfail($id);
        $cart = unserialize(bzdecompress(utf8_decode($order->cart)));
        return view('user.order.print',compact('user','order','cart'));
    }

    public function trans()
    {
        $id = $_GET['id'];
        $trans = $_GET['tin'];
        $order = Order::findOrFail($id);
        $order->txnid = $trans;
        $order->update();
        $data = $order->txnid;
        return response()->json($data);            
    }  

}
