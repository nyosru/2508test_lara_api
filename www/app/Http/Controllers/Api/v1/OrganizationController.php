<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


/**
 * @OA\SecurityScheme(
 *      securityScheme="ApiKeyAuth",
 *      type="apiKey",
 *      in="header",
 *      name="X-API-KEY"
 *  )
 *
 * @OA\Tag(
 *     name="Organizations",
 *     description="API для работы с организациями"
 * )
 */
class OrganizationController extends Controller
{


    /**
     * Получить список организаций, связанных с видом деятельности по имени,
     * включая вложенные виды деятельности (рекурсивно).
     *
     *     #security={{"ApiKeyAuth":{}}},
     *
     * @OA\Get(
     *      path="/api/organizations/by-activity-name/{name}",
     *      tags={"Organizations"},
     *      summary="Список организаций по виду деятельности",
     *      description="Возвращает список организаций, связанных с указанным видом деятельности (по имени), включая вложенные до 3 уровней",
     *      @OA\Parameter(
     *          name="name",
     *          in="path",
     *          description="Название вида деятельности",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Organization")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Вид деятельности не найден",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Activity not found")
     *          )
     *      )
     *  )
     */
    public function getByActivityName($name)
    {
        // Найти деятельность по имени
        try {
            $activity = Activity::where('name', $name)->firstOrFail();
        }catch ( \Exception $e){
            return response()->json(['message' => 'Activity not found'], 404);
        }

        // Получить id деятельности и всех дочерних видов до 3 уровней вложенности
        $activityIds = $this->getActivityWithChildrenIds($activity);

        // Получить организации, у которых есть связь с этими видами деятельности
        $organizations = Organization::whereHas('activities', function($query) use ($activityIds) {
            $query->whereIn('activities.id', $activityIds);
        })->with(['building', 'phones', 'activities'])->get();

        return OrganizationResource::collection($organizations);
//        return response()->json($organizations);
    }

    /**
     * Рекурсивный сбор id деятельности и вложенных видов (до 3 уровней)
     */
    protected function getActivityWithChildrenIds(Activity $activity, $level = 1)
    {
        $ids = [$activity->id];

        if ($level < 3) {
            foreach ($activity->children as $child) {
                $ids = array_merge($ids, $this->getActivityWithChildrenIds($child, $level + 1));
            }
        }

        return $ids;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
