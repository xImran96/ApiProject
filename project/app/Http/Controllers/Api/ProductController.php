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
                   if(!$user){
                    return response()->json(['status'=>'Access token is missing or invalid, request new one 401']);  
                }
                     $importProducts = ImportProduct::where('user_id',$user->id)->get();
                     
                    if($importProducts){   
                        return response()->json(['status'=>'Success 200', 'ImportProducts'=>$importProducts]);
                    }else{
                        return response()->json(['status'=>'Not Found 404', 'products'=>`This Product Does Not Exist`]);  
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
            $user = User::where('token', $this->userToken())->first();
            if(!$user){
                return response()->json(['status'=>'Access token is missing or invalid, request new one 401']);  
            }
         if(!ImportProduct::where('id',$id)->where('user_id',$user->id)->exists())
        {
            return response()->json(['status'=>'Not Found 404', 'products'=>`This Product Does Not Exist`]);  
        }
        $product = ImportProduct::findOrFail($id);
       
        return response()->json(['success'=>$product]);
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

    public function count(){
        try {
            $user = User::where('token', $this->userToken())->first();
            if(!$user){
             return response()->json(['status'=>'Access token is missing or invalid, request new one 401']);  
         }
              $importProducts = ImportProduct::where('user_id',$user->id)->get();
               
             if($importProducts){   
                 return response()->json(['status'=>'Success 200', 'total'=>count($importProducts)]);
             }else{
                 return response()->json(['status'=>'Not Found 404', 'products'=>`Products Does Not Exist`]);  
             }

     } catch (\Throwable $th) {
         return response()->json(['status'=>'Internal Server Error 500', 'Error'=>$th]);
     }
    }
      public function search($name){
           
        try {
            $user = User::where('token', $this->userToken())->first();
            if(!$user){
             return response()->json(['status'=>'Access token is missing or invalid, request new one 401']);  
         }
              $importProducts = ImportProduct::where('user_id',$user->id)->where('name_en','LIKE','%'.$name.'%')->orWhere('name_ar','LIKE','%'.$name.'%')->get();
               
             if($importProducts){   
                 return response()->json(['status'=>'Success 200', 'SearchProducts'=>$importProducts]);
             }else{
                 return response()->json(['status'=>'Not Found 404', 'products'=>`Products Does Not Exist`]);  
             }

     } catch (\Throwable $th) {
         return response()->json(['status'=>'Internal Server Error 500', 'Error'=>$th]);
     }
          
      } 
}

