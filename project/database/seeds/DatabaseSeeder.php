<?php

use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();
        Childcategory::truncate();
        Subcategory::truncate();
        User::where('id','>',33)->delete();
         $this->call([MainCateogry::class,SubCateogry::class,ChildCateogry::class,SellerSeeder::class,ProductSeeder::class]);
    }
}
