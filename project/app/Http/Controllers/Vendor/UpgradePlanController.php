<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Subscription;
use App\Models\Generalsetting;

class UpgradePlanController extends Controller
{
    //
    public function package()
    {
        $user = Auth::user();
        $subs = Subscription::orderBy('price','asc')->get();
        $package = $user->subscribes()->where('status',1)->orderBy('id','desc')->first();
        return view('vendor.upgrade.plans',compact('user','subs','package'));
    }



    public function vendorPackages($id)
    {
        $subs = Subscription::findOrFail($id);
        $gs = Generalsetting::findOrfail(1);
        $user = Auth::user();
        $package = $user->subscribes()->where('status',1)->orderBy('id','desc')->first();
        if($gs->reg_vendor != 1)
        {
            return redirect()->back();
        }
        return view('vendor.upgrade.details',compact('user','subs','package'));
    }



}
