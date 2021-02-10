<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCateogry extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        $sub_cat=[
            ['id'=>'61','name_en'=>'Skin Care','name_ar'=>'العناية بالبشرة','slug'=>'Skin-Care','category_id'=>'71','status'=>'0'],
            ['id'=>'84','name_en'=>'Bath & Body','name_ar'=>' حمام والجسم ','slug'=>'Bath-Body','category_id'=>'71','status'=>'0'],
            ['id'=>'93','name_en'=>'Hair Care','name_ar'=>'العناية بالشعر','slug'=>'Hair-Care','category_id'=>'71','status'=>'0'],
            ['id'=>'147','name_en'=>'Oral Care','name_ar'=>'العناية بالفم','slug'=>'Oral-Care','category_id'=>'71','status'=>'0'],
            ['id'=>'159','name_en'=>'Make Up','name_ar'=>'ميك أب','slug'=>'Make-Up','category_id'=>'71','status'=>'0'],
            ['id'=>'184','name_en'=>'Nails Care','name_ar'=>'العناية بالاظافر','slug'=>'Nails-Care','category_id'=>'71','status'=>'0'],
            ['id'=>'190','name_en'=>'Men Care','name_ar'=>'العناية بالرجل','slug'=>'Men-Care','category_id'=>'71','status'=>'0'],
            ['id'=>'203','name_en'=>'Others','name_ar'=>'اخرى','slug'=>'Others','category_id'=>'71','status'=>'0'],
            ['id'=>'434','name_en'=>'Contact Lens','name_ar'=>'عدسات لاصقة','slug'=>'Contact-Lens','category_id'=>'71','status'=>'0'],
            ['id'=>'440','name_en'=>'Men Perfume','name_ar'=>'عطر رجالي','slug'=>'Men-Perfume','category_id'=>'71','status'=>'0'],
            ['id'=>'59','name_en'=>'Home Decor','name_ar'=>'ديكور المنزل','slug'=>'Home-Decor','category_id'=>'72','status'=>'0'],
            ['id'=>'64','name_en'=>'stationary','name_ar'=>'ثابت','slug'=>'stationary','category_id'=>'72','status'=>'0'],
            ['id'=>'70','name_en'=>'Pets','name_ar'=>'الحيوانات الأليفة','slug'=>'Pets','category_id'=>'72','status'=>'0'],
            ['id'=>'410','name_en'=>'Home Products','name_ar'=>'منتجات منزلية','slug'=>'Home-Products','category_id'=>'72','status'=>'0'],
            ['id'=>'411','name_en'=>'Gifts','name_ar'=>'الهدايا','slug'=>'Gifts','category_id'=>'72','status'=>'0'],
            ['id'=>'417','name_en'=>'Party Supplies','name_ar'=>'لوازم الحفلات','slug'=>'Party-Supplies','category_id'=>'72','status'=>'0'],
            ['id'=>'419','name_en'=>'Office Product','name_ar'=>'منتج المكتب','slug'=>'Office-Product','category_id'=>'72','status'=>'0'],
            ['id'=>'420','name_en'=>'Games & Puzzles','name_ar'=>'الألعاب والألغاز','slug'=>'Games-Puzzles','category_id'=>'72','status'=>'0'],
            ['id'=>'251','name_en'=>'Clothes','name_ar'=>'ملابس','slug'=>'Clothes','category_id'=>'73','status'=>'0'],
            ['id'=>'252','name_en'=>'Shoes','name_ar'=>'أحذية','slug'=>'Shoes','category_id'=>'73','status'=>'0'],
            ['id'=>'253','name_en'=>'Jewellery','name_ar'=>'مجوهرات','slug'=>'Jewellery','category_id'=>'73','status'=>'0'],
            ['id'=>'254','name_en'=>'Accessories','name_ar'=>'مستلزمات','slug'=>'Accessories','category_id'=>'73','status'=>'0'],
            ['id'=>'409','name_en'=>'Shop By Brand','name_ar'=>'تسوق  الماركة','slug'=>'Shop-By-Brand','category_id'=>'73','status'=>'0'],
            ['id'=>'429','name_en'=>'Lenses','name_ar'=>'العدسات','slug'=>'Lenses','category_id'=>'73','status'=>'0'],
            ['id'=>'207','name_en'=>'Mobiles & Accessories','name_ar'=>'موبايلات واكسسواراتها','slug'=>'Mobiles-Accessories','category_id'=>'75','status'=>'0'],
            ['id'=>'208','name_en'=>'Computer & Networking','name_ar'=>'','slug'=>'Computer-Networking','category_id'=>'75','status'=>'0'],
            ['id'=>'209','name_en'=>'Video Games','name_ar'=>'','slug'=>'Video-Games','category_id'=>'75','status'=>'0'],
            ['id'=>'210','name_en'=>'Televisions','name_ar'=>'','slug'=>'Televisions','category_id'=>'75','status'=>'0'],
            ['id'=>'211','name_en'=>'Home Appliances','name_ar'=>'','slug'=>'Home-Appliances','category_id'=>'75','status'=>'0'],
            ['id'=>'212','name_en'=>'Speakers','name_ar'=>'','slug'=>'Speakers','category_id'=>'75','status'=>'0'],
            ['id'=>'213','name_en'=>'Headphones & Earphones','name_ar'=>'','slug'=>'Headphones-Earphones','category_id'=>'75','status'=>'0'],
            ['id'=>'214','name_en'=>'Power Banks','name_ar'=>'','slug'=>'Power-Banks','category_id'=>'75','status'=>'0'],
            ['id'=>'215','name_en'=>'Wearable Devices','name_ar'=>'','slug'=>'Wearable-Devices','category_id'=>'75','status'=>'0'],
            ['id'=>'216','name_en'=>'Camera,Photos & Video','name_ar'=>'','slug'=>'Camera-Photos-Video','category_id'=>'75','status'=>'0'],
            ['id'=>'218','name_en'=>'Shop By Brands','name_ar'=>'','slug'=>'Shop-Brands','category_id'=>'75','status'=>'0'],
        ];

        DB::table('subcategories')->insert($sub_cat);

    }
}
