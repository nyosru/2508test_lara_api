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
 *     name="Организации",
 *     description="работа с организациями"
 * )
 */
class OrganizationController extends Controller
{

    /**
     * Получить список организаций, связанных с видом деятельности по имени,
     * включая вложенные виды деятельности (рекурсивно).
     *
     *
     * @OA\Get(
     *      path="/api/organizations/by-activity-name/{name}",
     *      tags={"Организации"},
     *      summary="Список организаций по виду деятельности",
     *      description="Возвращает список организаций, связанных с указанным видом деятельности (по имени), включая вложенные до 3 уровней",
     *    security={{"ApiKeyAuth":{}}},
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
     *               @OA\Property(
     *     property="data",
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Organization")
     * )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Пользователь не авторизован",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        // Получить id деятельности и всех дочерних видов до 3 уровней вложенности
//        $activityIds = $this->getActivityWithChildrenIds($activity);

        // Получить организации, у которых есть связь с этими видами деятельности
        $organizations = Organization::whereHas('activities', function ($query)
//        use ($activityIds)
        use ($name) {
//            $query->whereIn('activities.id', $activityIds);
            $query->where('activities.name', $name);
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
     * Получить список организаций, связанных с видом деятельности по имени,
     * включая вложенные виды деятельности (рекурсивно).
     *
     *
     * @OA\Get(
     *      path="/api/organization/{id}",
     *      tags={"Организации"},
     *      summary="Получаем данные одной органиации по id",
     *      description="Возвращает данные",
     *    security={{"ApiKeyAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="id организации",
     *          required=true,
     *          @OA\Schema(type="number")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *     @OA\Property(
     *      property="data",
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Organization")
     *          )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Пользователь не авторизован",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
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
    public function show(string $id)
    {
        try {
            $organization = Organization::with(['building', 'phones', 'activities'])->whereId($id)->firstOrFail();
            return OrganizationResource::collection([$organization]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
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
