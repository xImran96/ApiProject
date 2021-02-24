<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Datatables;
use Auth;

class LogsController extends Controller
{
    


     public function datatables()
    {
    	 $user = Auth::user();
         $datas = $user->logs()->orderBy('id','desc')->get();

         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->toJson(); //--- Returning Json Data To Client Side
    }


}
