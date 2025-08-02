<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


/**
 * @OA\Tag(
 *     name="API Keys",
 *     description="Управление API ключами"
 * )
 *
 * @OA\Schema(
 *     schema="ApiKeyCreateRequest",
 *     required={"name"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Описание для нового API ключа (например, 'Ключ для тестирования')",
 *         example="Ключ для тестирования"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ApiKeyCreateResponse",
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="API ключ успешно создан"
 *     ),
 *     @OA\Property(
 *         property="api_key",
 *         type="string",
 *         description="Сгенерированный API ключ",
 *         example="XyZ123ABcdEfG456HiJ789KlMnoPqRsTuvWxYz0123456789abcdefghijklmn"
 *     ),
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     )
 * )
 * @OA\Schema(
 *     schema="ApiKey",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="название",
 *         example="первый ключ"
 *     ),
 *     @OA\Property(
 *         property="api_key",
 *         type="string",
 *         description="API ключ",
 *         example="XyZ123ABcdEfG456HiJ789KlMnoPqRsTuvWxYz0123456789abcdefghijklmn"
 *     ),
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     )
 * )
 */

class ApiKeyController extends Controller
{
    /**
     * получить все ключи
     *
     * @OA\Get(
     *      path="/api/api-keys",
     *     tags={"API Keys"},
     *      summary="Получить список всех API-ключей",
     *      description="Возвращает все зарегистрированные API-ключи в системе",
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/ApiKey")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *          )
     *      )
     * )
     */
    public function index()
    {
        $apiKeys = ApiKey::select(['name','key','id'])->get();
        return response()->json($apiKeys);
    }

    /**
     * Создать новый API ключ.
     *
     * @OA\Post(
     *     path="/api/api-key/create",
     *     tags={"API Keys"},
     *     summary="Создание нового API ключа",
     *     description="Создаёт новый API ключ по заданному имени",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ApiKeyCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="API ключ успешно создан",
     *         @OA\JsonContent(ref="#/components/schemas/ApiKeyCreateResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"name": {"The name field is required."}}
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Генерируем уникальный ключ
        $key = Str::random(60);

        $apiKey = ApiKey::create([
            'name' => $request->name,
            'key' => $key,
        ]);

        return response()->json([
            'message' => 'API ключ успешно создан',
            'api_key' => $apiKey->key,
            'id' => $apiKey->id,
        ], 201);
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
