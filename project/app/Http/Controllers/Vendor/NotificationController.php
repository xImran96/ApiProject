<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Log;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function order_notf_count($id)
    {
        $data = UserNotification::where('user_id','=',$id)->where('is_read','=',0)->get()->count();
        return response()->json($data);            
    } 

    public function order_notf_clear($id)
    {
        $data = UserNotification::where('user_id','=',$id);
        $log = Log::create([
            'user_id'=>auth()->user()->id,
            'topic'=>'notification',
            'code'=>200,
            'log_topic'=>'Vendor-notification-delete',
            'log_message'=>'Vendor notification deleted Successfully.',
            'log_level'=>'notificaiton deleted',
            ]);
        $data->delete();        
        
    } 

    public function order_notf_show($id)
    {
        $datas = UserNotification::where('user_id','=',$id)->get();
        if($datas->count() > 0){
          foreach($datas as $data){
            $data->is_read = 1;
            $data->update();
          }
          $log = Log::create([
            'user_id'=>auth()->user()->id,
            'topic'=>'notification',
            'code'=>200,
            'log_topic'=>'Vendor-notification-updated',
            'log_message'=>'Vendor notification updated Successfully.',
            'log_level'=>'notification updaterd',
]);
        }       
        return view('vendor.notification.order',compact('datas'));           
    } 
}
