<?php

namespace Database\Seeders;

use App\Models\DumpOrderCategory;
use Illuminate\Database\Seeder;

class DumpOrderCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dumpOrderCategories = [
            ['name' => 'HES'],
            ['name' => 'MCM'],
            ['name' => 'H'],   
            ['name' => 'その他'],
        ];

        foreach ($dumpOrderCategories as $dumpOrderCategory) {
            DumpOrderCategory::create($dumpOrderCategory);
        }
    }
}
