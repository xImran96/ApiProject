<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_products', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('product_type');
            $table->string('product_id');
            $table->string('affiliate_link')->nullabe();
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('subcategory_id')->nullable();
            $table->integer('childcategory_id')->nullable();
            $table->integer('attributes')->nullable();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('slug');
            $table->string('photo');
            $table->string('thumbnail')->nullable();
            $table->string('file')->nullable();
            $table->string('size')->nullable();
            $table->string('size_qty')->nullable();
            $table->string('size_price')->nullable();
            $table->text('color')->nullable();
            $table->double('price');
            $table->double('previous_price')->nullabe();
            $table->string('details_en')->nullabe();
            $table->string('details_ar')->nullabe();
            $table->integer('stock')->nullabe();
            $table->text('policy')->nullabe();
            $table->tinyinteger('status')->unsigned()->default(0);
            $table->integer('views')->unsigned()->nullabe();
            $table->string('tags')->nullabe();
            $table->text('features')->nullabe();
            $table->string('region')->nullabe();
            $table->text('colors')->nullabe();
            $table->tinyinteger('product_condition')->unsigned()->default(0);
            $table->string('ship')->nullabe();
            $table->tinyinteger('is_meta')->default(0);
            $table->text('meta_tag')->nullabe();
            $table->text('meta_description')->nullabe();
            $table->string('youtube')->nullabe();
            $table->enum('type', ['Physical', 'Digital', 'License']);
            $table->text('license')->nullabe();
            $table->text('license_qty')->nullabe();
            $table->text('link')->nullabe();
            $table->string('platform', 255)->nullabe();
            $table->string('license_type', 255)->nullabe();
            $table->string('measure', 191)->nullabe();
            $table->tinyinteger('featured')->default(0);
            $table->tinyinteger('best')->default(0);  
            $table->tinyinteger('top')->default(0);
            $table->integer('min_order_qty')->nullable();
            $table->tinyinteger('hot')->default(0);
            $table->tinyinteger('latest')->default(0);
            $table->tinyinteger('big')->default(0);
            $table->tinyinteger('trending')->default(0);
            $table->tinyinteger('sale')->default(0);
            $table->tinyinteger('is_discount')->default(0);
            $table->text('discount_date')->nullabe();        
            $table->text('whole_sell_qty')->nullabe();
            $table->text('whole_sell_discount')->nullabe();
            $table->tinyinteger('is_catalog')->default(0);
            $table->integer('catalog_id')->default(0);
            $table->integer('profit_percentage');
            $table->integer('import_price'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_products');
    }
}
