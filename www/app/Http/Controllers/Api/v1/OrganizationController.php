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
     *      path="/api/organization/by-activity-name/{name}",
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
     * Получить список организаций, связанных с видом деятельности по имени,
     * включая вложенные виды деятельности (рекурсивно).
     *
     *
     * @OA\Post(
     *      path="/api/organization/by-name",
     *      tags={"Организации"},
     *      summary="поиск организации по названию",
     *      description="Возвращает список организаций",
     *    security={{"ApiKeyAuth":{}}},
*     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     description="Название"
 *                 ),
 *                 required={"address"}
 *             )
 *         )
 *     ),
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
    public function getByName( request $request )
    {

       $request->validate([
           'name' => 'required|string|max:255',
       ]);

        $name = $request->input('name');
        $organizations = Organization::where('name', 'LIKE', "%{$name}%")->with(['building', 'phones', 'activities'])->get();

        return OrganizationResource::collection($organizations);

    }


    /**
     * Получить список организаций
     *
     *
     * @OA\Get(
     *      path="/api/organization",
     *      tags={"Организации"},
     *      summary="Получить список всех организаций",
     *      description="Возвращает список организаций",
     *    security={{"ApiKeyAuth":{}}},
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
     *  )
     */
    public function index()
    {
        $organizations = Organization::all();
        return OrganizationResource::collection($organizations);
    }




    /**
     * Получить список организаций, связанных с видом деятельности по имени,
     * включая вложенные виды деятельности (рекурсивно).
     *
     *
     * @OA\Post(
     *      path="/api/organization/by-address",
     *      tags={"Организации"},
     *      summary="Список организаций по виду деятельности",
     *      description="Возвращает список организаций, связанных с указанным видом деятельности (по имени), включая вложенные до 3 уровней",
     *    security={{"ApiKeyAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="address",
 *                     type="string",
 *                     description="Адрес здания"
 *                 ),
 *                 required={"address"}
 *             )
 *         )
 *     ),
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
public function getByAddress(Request $request)
    {

    $address = $request->input('address');

//         // Найти организациии с адресом
//         try {
//             $activity = Activity::where('name', $name)->firstOrFail();
//         } catch (\Exception $e) {
//             return response()->json(['message' => 'Activity not found'], 404);
//         }
//
//         // Получить id деятельности и всех дочерних видов до 3 уровней вложенности
// //        $activityIds = $this->getActivityWithChildrenIds($activity);

        // Получить организации, у которых есть связь с этими видами деятельности
        $organizations = Organization::whereHas('building', function ($query)
//        use ($activityIds)
        use ($address)
        {
//            $query->whereIn('activities.id', $activityIds);
            $query->where('address', $address);
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
 * @OA\Post(
 *      path="/api/organization/by-location",
 *      tags={"Организации"},
 *      summary="Получить организации по местоположению здания в квадрате вокруг точки",
 *      description="Возвращает организации, чьи здания находятся в квадрате вокруг указанной гео-точки с заданным радиусом (в километрах)",
 *      security={{"ApiKeyAuth":{}}},
 *      @OA\RequestBody(
 *          required=true,
 *          description="Координаты точки и радиус",
 *          @OA\JsonContent(
 *              required={"latitude","longitude","radiusKm"},
 *              @OA\Property(property="latitude", type="number", format="float", example=55.751244, description="Широта в градусах"),
 *              @OA\Property(property="longitude", type="number", format="float", example=37.618423, description="Долгота в градусах"),
 *              @OA\Property(property="radiusKm", type="number", format="float", example=5, description="Радиус квадрата в километрах")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Список организаций в указанном квадрате",
 *          @OA\JsonContent(
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  @OA\Items(ref="#/components/schemas/Organization")
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Ошибка валидации",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="The given data was invalid."),
 *              @OA\Property(
 *                  property="errors",
 *                  type="object",
 *                  example={
 *                      "latitude": {"The latitude field is required."},
 *                      "longitude": {"The longitude field is required."},
 *                      "radiusKm": {"The radiusKm field is required."}
 *                  }
 *              )
 *          )
 *      )
 * )
 */
public function getByLocation(Request $request)
{
    $validated = $request->validate([
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'radiusKm' => 'required|numeric|min:0',
    ]);

    $lat = $validated['latitude'];
    $lon = $validated['longitude'];
    $radiusKm = $validated['radiusKm'];

// достаём координаты границы квадрата
    $calc_la_lo = \App\Http\Controllers\Services\GeoController::calculateSquareCorners( $lat, $lon, $radiusKm);
    $minLat = $calc_la_lo['lat_min'];
    $maxLat = $calc_la_lo['lat_max'];
    $minLon = $calc_la_lo['lng_min'];
    $maxLon = $calc_la_lo['lng_max'];

    // Найти организации, у которых здание входит в квадрат
    $organizations = Organization::whereHas('building', function ($query) use ($minLat, $maxLat, $minLon, $maxLon) {
        $query->whereBetween('latitude', [$minLat, $maxLat])
              ->whereBetween('longitude', [$minLon, $maxLon]);
    })->with(['building', 'phones', 'activities'])->get();

    return OrganizationResource::collection($organizations);
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



