<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubchildcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subchildcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('childcategory_id');
            $table->text('name_en')->nullable();
            $table->text('name_ar')->nullable();
            $table->text('slug')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('subchildcategories');
    }
}
