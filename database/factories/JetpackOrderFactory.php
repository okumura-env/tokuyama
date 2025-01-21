<?php

namespace Database\Factories;

use App\Models\JetpackOrder;
use App\Models\JetpackSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class JetpackOrderFactory extends Factory
{
    protected $model = JetpackOrder::class;

    public function definition()
    {
        // ランダムに1～12のdate_idを生成
        $date_id = $this->faker->numberBetween(1, 12);
        // dd($date_id);

        // 指定されたdate_idに基づくjetpack_schedule_idを取得
        $schedule = JetpackSchedule::where('date_id', $date_id)->inRandomOrder()->first();

        return [
            'date_id' => $date_id,
            'vehicle_id' => null,
            'jetpack_schedule_id' => $schedule ? $schedule->id : null,
            'jetpack_destination_id' => $this->faker->numberBetween(1, 15),
            'quantity' => null,
            'status' => 0,
            'note' => null,
        ];
    }
}
