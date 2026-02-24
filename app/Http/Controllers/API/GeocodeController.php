<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\GeocodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeocodeController extends Controller
{
    /**
     * GET /api/v1/geocode?address=... — geocode an address server-side (avoids CORS / User-Agent issues).
     */
    public function __invoke(Request $request): JsonResponse
    {
        $address = $request->query('address');
        if (! is_string($address) || trim($address) === '') {
            return response()->json([
                'status' => false,
                'message' => 'Missing or empty address.',
            ], 422);
        }

        $result = app(GeocodeService::class)->geocode($address);
        if ($result === null) {
            return response()->json([
                'status' => false,
                'message' => 'Could not geocode address.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'latitude' => $result[0],
                'longitude' => $result[1],
            ],
        ]);
    }

    /**
     * GET /api/v1/geocode/reverse?latitude=...&longitude=... — reverse geocode to address string.
     */
    public function reverse(Request $request): JsonResponse
    {
        $lat = $request->query('latitude') ?? $request->query('lat');
        $lng = $request->query('longitude') ?? $request->query('lng');
        if ($lat === null || $lng === null || ! is_numeric($lat) || ! is_numeric($lng)) {
            return response()->json([
                'status' => false,
                'message' => 'Missing or invalid latitude/longitude.',
            ], 422);
        }

        $lat = (float) $lat;
        $lng = (float) $lng;
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return response()->json([
                'status' => false,
                'message' => 'Latitude or longitude out of range.',
            ], 422);
        }

        $address = app(GeocodeService::class)->reverseGeocode($lat, $lng);
        if ($address === null) {
            return response()->json([
                'status' => false,
                'message' => 'Could not reverse geocode.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'address' => $address,
            ],
        ]);
    }
}
