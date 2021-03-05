<?php

namespace App\Http\Controllers\myControllers;

use App\Models\Log;
use App\Models\User;
use App\ImportProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfitController extends Controller
{
	// profit_percentage;
	// new_price;
    public function showProfitForm()
    {
       return view('layouts.My-product.profit');
    }
    public function profitApply(Request $req)
    {
    	
       
       $vendor = User::find(auth()->user()->id);
       
       $importProducts = $vendor->myProducts;
       foreach ($importProducts as $importProduct) {
       	 $getPercentage = ($importProduct->price*$req->percentage)/100;

       	 $newPrice = $importProduct->price+$getPercentage;
            if($importProduct->specific_percentage_status == 0){
               $importProduct->new_price = $newPrice;
               $importProduct->profit_percentage =  $req->percentage;
               $importProduct->save();
            }
       	 
       	  
       }


        $log = Log::create([
                        'user_id'=>auth()->user()->id,
                        'topic'=>'Profit',
                        'code'=>200,
                        'log_topic'=>'Vendor-Set-Profit',
                        'log_message'=>'Profit added has place order Successfully.',
                        'log_level'=>'profit change',
        ]);
       $vendor->profit = $req->percentage;
       $vendor->save();
      //  dd($vendor);
       return redirect()->back()->with('message','Profit Added Successfully');
    }
}
