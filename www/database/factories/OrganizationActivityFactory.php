<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Organization;
use App\Models\OrganizationActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganizationActivity>
 */
class OrganizationActivityFactory extends Factory
{
    protected $model = OrganizationActivity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'activity_id' => Activity::factory(),
        ];
    }
}
