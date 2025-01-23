<?php

// database/seeders/WorkerSeeder.php
namespace Database\Seeders;

use App\Models\Worker;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    public function run()
    {
        $workers = [
            ['name' => '平中 郁史'],
            ['name' => '山田 昭博'],
            ['name' => '安藤 智司'],
            ['name' => '林 憲治'],
            ['name' => '長谷川 和彦'],
            ['name' => '中川 弘行'],
            ['name' => '廣野 勝'],
            ['name' => '田中 至'],
            ['name' => '高原 幸治'],
            ['name' => '青木 勝紀'],
            ['name' => '森脇 秀二'],
            ['name' => '馬田 竜也'],
            ['name' => '下村 太志'],
            ['name' => '藤本 剛'],
            ['name' => '津野 祥宏'],
            ['name' => '藤尾 典昭'],
            ['name' => '田中 正照'],
            ['name' => '谷岡 斉'],
            ['name' => '平中 保彦'],
            ['name' => '藤井 晋太郎'],
            ['name' => '谷村 博和'],
            ['name' => '河崎 なぎさ'],
            ['name' => '原田 智昭'],
            ['name' => '長尾 徹'],
            ['name' => '塚間 諒'],
            ['name' => '河村 宗'],
            ['name' => '杉中 啓二'],
            ['name' => '須賀 裕導'],
            ['name' => '東風浦 道男'],
            ['name' => '藤原 直樹'],
            ['name' => '末次 博典'],
            ['name' => '福田 健治'],
            ['name' => '林 邦昭'],
            ['name' => '岩井 卓也'],
            ['name' => '藤岡 宏史'],
            ['name' => '山﨑 健'],
            ['name' => '西川 勉'],
            ['name' => '古木 好昌'],
            ['name' => '松永 倫祥'],
        ];

        foreach ($workers as $worker) {
            Worker::create($worker);
        }
    }
}
