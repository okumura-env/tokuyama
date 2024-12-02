<?php

namespace Database\Seeders;

use App\Models\DumpOrderCategoryTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DumpOrderCategoryTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 挿入するデータの配列
        $data = [
            ['title' => 'リデ', 'dump_order_category_id' => 1],
            ['title' => 'MM', 'dump_order_category_id' => 1],
            ['title' => 'ベン', 'dump_order_category_id' => 1],
            ['title' => 'MO', 'dump_order_category_id' => 1],
            ['title' => 'US', 'dump_order_category_id' => 1],
            ['title' => '石炭', 'dump_order_category_id' => 2],
            ['title' => 'CL', 'dump_order_category_id' => 2],
            ['title' => '汚泥', 'dump_order_category_id' => 3],
            ['title' => 'CL', 'dump_order_category_id' => 3],
            ['title' => '東見初', 'dump_order_category_id' => 4],
            ['title' => 'タイヤチップ', 'dump_order_category_id' => 4],
            ['title' => '転', 'dump_order_category_id' => 4],
            ['title' => '出光', 'dump_order_category_id' => 4],
            ['title' => 'ナイ', 'dump_order_category_id' => 4],
            ['title' => 'カレ', 'dump_order_category_id' => 4],
        ];

        foreach ($data as $d) {
            DumpOrderCategoryTitle::create($d);
        }
    }
}
