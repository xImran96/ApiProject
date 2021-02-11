<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\ImportProduct;
class ProductController extends Controller
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
                    if(count($user->myProducts)!=0){
                        return response()->json(['status'=>'Success 200', 'products'=>$user->myProducts]);
                    }else{
                        return response()->json(['status'=>'Not Found 404', 'products'=>`You Don't Have Any Imports`]);  
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    //       try {
    //                $user = User::where('token', $this->userToken())->first();
    //                 if(count($user->myProducts)!=0){
    //                     return response()->json(['status'=>'Success 200', 'products'=>$user->myProducts]);

    //                 }else{
    //                     return response()->json(['status'=>'Not Found 404', 'products'=>`You Don't Have Any Imports`]);  
    //                 }

    //         } catch (\Throwable $th) {
    //             return response()->json(['status'=>'Internal Server Error 500', 'Error'=>$th]);
    //         }
         try {
         if(!ImportProduct::where('id',$id)->exists())
        {
            return "Sorry the page does not exist.";
        }
        $roduct = ImportProduct::findOrFail($id);
       
        return response()->json(['success'=>$roduct]);
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
}
