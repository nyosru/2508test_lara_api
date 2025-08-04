<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $food = Activity::factory()->create(['name' => 'Еда', 'parent_id' => null]);
            $meat = Activity::factory()->create(['name' => 'Мясная продукция', 'parent_id' => $food->id]);
            Activity::factory()->create(['name' => 'Мясо', 'parent_id' => $meat->id]);
            Activity::factory()->create(['name' => 'Шея', 'parent_id' => $meat->id]);
            Activity::factory()->create(['name' => 'Свинина', 'parent_id' => $meat->id]);
            Activity::factory()->create(['name' => 'Баранина', 'parent_id' => $meat->id]);
            $milk = Activity::factory()->create(['name' => 'Молочная продукция', 'parent_id' => $food->id]);
            Activity::factory()->create(['name' => 'Молоко', 'parent_id' => $milk->id]);
            Activity::factory()->create(['name' => 'Творог', 'parent_id' => $milk->id]);
            Activity::factory()->create(['name' => 'Сметана', 'parent_id' => $milk->id]);
            Activity::factory()->create(['name' => 'Сыр', 'parent_id' => $milk->id]);
            Activity::factory()->create(['name' => 'Кефир', 'parent_id' => $milk->id]);
            Activity::factory()->create(['name' => 'Ряженка', 'parent_id' => $milk->id]);
        $cars = Activity::factory()->create(['name' => 'Автомобили', 'parent_id' => null]);
        $truck = Activity::factory()->create(['name' => 'Грузовые', 'parent_id' => $cars->id]);
            $passenger = Activity::factory()->create(['name' => 'Легковые', 'parent_id' => $cars->id]);
            $spareParts = Activity::factory()->create(['name' => 'Запчасти', 'parent_id' => $cars->id]);
            $accessories = Activity::factory()->create(['name' => 'Аксессуары', 'parent_id' => $cars->id]);
    }
}
