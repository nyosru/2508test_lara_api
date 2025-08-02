<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use OpenApi\Annotations as OA;


class ApiController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/example",
     *     summary="Пример получения данных",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ"
     *     )
     * )
     */
    public function example()
    {
        return response()->json(['message' => 'Hello Swagger']);
    }
}
