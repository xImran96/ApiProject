<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\ImportProduct;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CategoriesController extends Controller
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
       
        try{
            $category = [];
            $user = User::where('token', $this->userToken())->first();
            if(!$user){
                return response()->json(['status'=>'Access token is missing or invalid, request new one 401']);  
            }
            $products = $user->myProducts;
            if(!$products)
            {
                return response()->json(['status'=>'Not Found 404', 'Categories'=>`Does not exist`]);  
            }
            foreach($products as $product){
                $check = in_array($product->category, $category);
                if($check != true){
                    array_push($category,$product->category);
                }
                
                
                
                 
            }
            
           
           
            return response()->json(['success'=>$category]);
             }catch(\Throwable $th){
         return response()->json(['status'=>'Internal Server Error 500', 'Error'=>$th]);
        }
        
        return response()->json($datas);
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
        //
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
