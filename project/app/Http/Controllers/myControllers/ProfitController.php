<?php

namespace App\Http\Controllers\myControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\ImportProduct;

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
    	
       $importProducts = ImportProduct::all();
       $vendor = User::where('id',auth()->user()->id)->first();
       foreach ($importProducts as $importProduct) {
       	 $getPercentage = ($importProduct->price*$req->percentage)/100;

       	 $newPrice = $importProduct->price+$getPercentage;
       	 // echo $newPrice  . "</br>";
       	  
       	  $getSingleRecord = ImportProduct::find($importProduct->id);
            if($getSingleRecord->specific_percentage_status == 0){
               $getSingleRecord->new_price = $newPrice;
               $getSingleRecord->profit_percentage =  $req->percentage;
             $getSingleRecord->save();
            }
       	 
       	  
       }


        $log = Log::create([
                        'user_id'=>auth()->user()->id,
                        'topic'=>'Profit',
                        'code'=>200,
                        'log_topic'=>'Vendor-Set-Profit',
                        'log_message'=>'Profit added has place order Successfully.',
                        'log_level'=>'order-placed',
        ]);
       $vendor->profit_percentage = $req->percentage;
       $vendor->save();
       return back();
    }
}
