<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('primary_categories')->insert([
            [
                'name' => 'パソコン関連',
                'sort_order' => 1,
            ],
            [
                'name' => 'ギフト',
                'sort_order' => 2,
            ],
            [
                'name' => 'サプリメント',
                'sort_order' => 3,
            ],
            ]);

        DB::table('secondary_categories')->insert([
            [
                'name' => 'ゲーミングPC',
                'sort_order' => 1,
                'primary_category_id' => 1
            ],
            [
                'name' => 'ビジネスノートパソコン',
                'sort_order' => 2,
                'primary_category_id' => 1
            ],
            [
                'name' => 'クリエイターパソコン',
                'sort_order' => 3,
                'primary_category_id' => 1
            ],
            [
                'name' => 'ギフトセット',
                'sort_order' => 4,
                'primary_category_id' => 2
            ],
            [
                'name' => 'リンゴ',
                'sort_order' => 5,
                'primary_category_id' => 2
            ],
            [
                'name' => 'プロテイン',
                'sort_order' => 6,
                'primary_category_id' => 3
            ],
            [
                'name' => 'BCAA',
                'sort_order' => 7,
                'primary_category_id' => 3
            ],
    
        ]);

    }
}
