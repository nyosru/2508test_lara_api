<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeoController extends Controller
{

  /**
      * Вычисляет 4 угловые точки квадрата вокруг заданной точки с радиусом в км
      *
      * @param float $latitude  Широта в градусах (-90 .. 90)
      * @param float $longitude Долгота в градусах (-180 .. 180)
      * @param float $distanceKm Расстояние до границ квадрата в километрах
      *
      * @return array Массив из 4 точек (каждая ['lat' => ..., 'lon' => ...])
      *               Порядок: верх-лево, верх-право, низ-право, низ-лево
      */
     public static function calculateSquareCorners(float $latitude, float $longitude, float $distanceKm): array
     {
         // 1 градус широты ≈ 111 км
         $deltaLat = $distanceKm / 111;

         // Для долготы учитываем сужение меридианов в зависимости от широты
         $latitudeRad = deg2rad($latitude);
         $deltaLon = $distanceKm / (111 * cos($latitudeRad));

         // Верхний левый угол (северо-запад)
         $topLeft = [
             'lat' => $latitude + $deltaLat,
             'lon' => $longitude - $deltaLon,
         ];

         // Верхний правый угол (северо-восток)
         $topRight = [
             'lat' => $latitude + $deltaLat,
             'lon' => $longitude + $deltaLon,
         ];

         // Нижний правый угол (юго-восток)
         $bottomRight = [
             'lat' => $latitude - $deltaLat,
             'lon' => $longitude + $deltaLon,
         ];

         // Нижний левый угол (юго-запад)
         $bottomLeft = [
         'lat' => $latitude - $deltaLat,
             'lon' => $longitude - $deltaLon,
         ];

         return [
             'tl' => $topLeft,
             'tr' => $topRight,
             'br' => $bottomRight,
             'bl' => $bottomLeft,
             'lat_min' => $latitude - $deltaLat,
             'lat_max' => $latitude + $deltaLat,
             'lng_min' => $longitude - $deltaLon,
             'lng_max' => $longitude + $deltaLon,
         ];
     }

}
