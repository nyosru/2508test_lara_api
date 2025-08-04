<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // сделал всё это я -> php-cat.com

        // Создадим несколько зданий
        $buildings = Building::all();

        // Создадим организации, связывая их с существующими зданиями
        foreach ($buildings as $building) {
            Organization::factory()->count(rand(1,5))->create([
                'building_id' => $building->id,
            ]);
        }

    }
}
