<?php

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainCateogry extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $main_cat=[
            ['id'=>'73','name_en'=>'Apparel, Accessories','name_ar'=>'الملابس والإكسسسوارات','slug'=>'apparel-accessories','photo'=>'','status'=>0],
            ['id'=>'74','name_en'=>'Bags, shoes and accessories','name_ar'=>'الحقائب والأحذية والاكسسوارات','slug'=>'Bags-shoes-accessories','photo'=>'','status'=>0],
            ['id'=>'71','name_en'=>'Beauty and personal care','name_ar'=>'الجمال والعناية الشخصية','slug'=>'Beauty-personal-care','photo'=>'','status'=>0],
            ['id'=>'72','name_en'=>'Consumer Goods','name_ar'=>'مواد استهلاكية','slug'=>'consumer-goods','photo'=>'','status'=>0],
            ['id'=>'75','name_en'=>'Electronics & Telecoms','name_ar'=>'الالكترونيات والاتصالات','slug'=>'electronics-telecoms','photo'=>'','status'=>0],
            ['id'=>'77','name_en'=>'Packaging, Advertising & Office','name_ar'=>'التعبئة والتغليف والإعلان والمكتب','slug'=>'packaging-advertising-office','photo'=>'','status'=>0],
            ['id'=>'76','name_en'=>'Gifts, Sports & Toys','name_ar'=>'الهدايا والرياضة والألعاب','slug'=>'gifts-sports-toys','photo'=>'','status'=>0],
            ['id'=>'66','name_en'=>'Health & Beauty','name_ar'=>'تجميل وعناية شخصية','slug'=>'health-beauty','photo'=>'','status'=>0],
            ['id'=>'78','name_en'=>'Jewelry','name_ar'=>'مجوهرات','slug'=>'jewelry','photo'=>'','status'=>0]
        ];

        DB::table('categories')->insert($main_cat);

    }
}
