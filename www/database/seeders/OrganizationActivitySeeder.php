<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = Activity::all();
        $organizations = Organization::all();

        foreach ($organizations as $organization) {

            // Каждая организация связана с 1-3 случайными деятельностями
            $randomActivityIds = $activities->random(rand(1,3))->pluck('id')->toArray();

            foreach ($randomActivityIds as $activityId) {
                try{
                DB::table('organization_activities')->insert([
                    'organization_id' => $organization->id,
                    'activity_id' => $activityId,
                ]);
                } catch (\Exception $e) {}
            }

        }
    }
}
