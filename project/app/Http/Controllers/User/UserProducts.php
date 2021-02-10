<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\ImportProduct;

class UserProducts extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $prods = auth()->user()->myProducts;
    
       return view('vendor.products', compact('prods'));
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

        // dd($request->all());

        $product   = Product::find($request->product_id);
        $input     = $product;
        $profit    = $request->profit;
        $getProfit = ($profit/100)*$product->price;
        $newPrice  = $product->price + $getProfit;

        $input = [
                 'category_id' => $product->category_id,
                 'product_type'=> $product->product_type,
                 'product_id'=>$product->id,
                 'affiliate_link' => $product->affiliate_link,
                 'sku'=>$product->sku ,
                 'min_order_qty'=>$product->min_order_qty,
                 'subcategory_id'=>$product->subcategory_id,
                 'childcategory_id'=>$product->childcategory_id,
                 'attributes'=>$product->attributes,
                 'name_en'=>$product->name_en,
                 'name_ar'=>$product->name_ar,
                 'photo'=>$product->photo,
                 'size'=>$product->size,
                 'size_qty'=>$product->size_qty,
                 'size_price'=>$product->size_price,
                 'color'=>$product->color,
                 'details_en'=>$product->details_en,
                 'details_ar'=>$product->details_ar,
                 'price'=>$product->price,
                 'previous_price'=>$product->previous_price,
                 'stock'=>$product->stock,
                 'policy'=>$product->policy,
                 'status'=>$product->status,
                 'views'=>$product->views,
                 'tags'=>$product->tags,
                 'featured'=>$product->featured,
                 'best'=>$product->best,
                 'top'=>$product->top,
                 'hot'=>$product->hot,
                 'latest'=>$product->latest,
                 'big'=>$product->big,
                 'trending'=>$product->trending,
                 'sale'=>$product->sale,
                 'features'=>$product->features,
                 'colors'=>$product->colors,
                 'product_condition'=>$product->product_condition,
                 'ship'=>$product->ship,
                 'meta_tag'=>$product->meta_tag,
                 'meta_description'=>$product->meta_description,
                 'youtube'=>$product->youtube,
                 'type'=>$product->type,
                 'file'=>$product->file,
                 'license'=>$product->license,
                 'license_qty'=>$product->license_qty,
                 'link'=>$product->link,
                 'platform'=>$product->platform,
                 'region'=>$product->region,
                 'license_type'=>$product->license_type,
                 'measure'=>$product->measure,
                 'discount_date'=>$product->discount_date,
                 'is_discount'=>$product->is_discount,
                 'whole_sell_qty'=>$product->whole_sell_qty,
                 'whole_sell_discount'=>$product->whole_sell_discount,
                 'is_catalog'=>$product->is_catalog,   
                 'catalog_id'=>$product->catalog_id,
                 'slug'=>$product->slug,
                 'profit_percentage'=>$request->profit,
                 'import_price'=>$newPrice];    
                $import = new ImportProduct($input);
                auth()->user()->myProducts()->save($import);
                return back()->with('imported', 'Product Imported.');

        


    

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





public function userProducts()
    {
            $prods = auth()->user()->userProducts;
            // return response()->json($prods);
            return view('vendor.products', compact('prods'));
    }


    public function importProducts($product_id) {
    

        $check = DB::table('product_user')->where('user_id', auth()->user()->id)->where('product_id', $product_id)->get();


        if (count($check) == 0) {
            DB::table('product_user')
                ->insert(
                    [
                        'user_id'=>auth()->user()->id,
                         'product_id'=>$product_id
                ]
            );

        return back()->with('import', 'Product Imported Successfully.');
        }else{
            return back()->with('import', 'Product Already Imported.');
        }


        
    }
}
