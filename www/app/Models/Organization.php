<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @OA\Schema(
 *     schema="Organization",
 *     type="object",
 *     title="Организация",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="ООО Рога и Копыта"),
 *     @OA\Property(
 *         property="building",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="address", type="string", example="г. Москва, ул. Ленина 1, офис 3"),
 *         @OA\Property(property="latitude", type="number", format="float", example=55.7558),
 *         @OA\Property(property="longitude", type="number", format="float", example=37.6173)
 *     ),
 *     @OA\Property(
 *         property="phones",
 *         type="array",
 *         @OA\Items(type="string", example="8-923-666-13-13")
 *     ),
 *     @OA\Property(
 *         property="activities",
 *         type="array",
 *         @OA\Items(type="string", example="Молочная продукция")
 *     )
 * )
 */


class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'building_id'];

    /**
     * @return BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(OrganizationPhone::class);
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'organization_activities');
    }

}
