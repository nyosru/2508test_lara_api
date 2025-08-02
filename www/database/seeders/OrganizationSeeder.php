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
        // Создадим несколько зданий
        $buildings = Building::factory()->count(3)->create();

        // Создадим организации, связывая их с существующими зданиями
        foreach ($buildings as $building) {
            Organization::factory()->count(3)->create([
                'building_id' => $building->id,
            ]);
        }

    }

}
