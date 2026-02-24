<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodeService
{
    /**
     * Geocode an address using Nominatim (OpenStreetMap). Returns [lat, lng] or null.
     * Usage policy: max 1 request per second.
     */
    public function geocode(string $address): ?array
    {
        $address = trim($address);
        if ($address === '') {
            return null;
        }

        $response = Http::withHeaders([
            'User-Agent' => config('app.name', 'LandogzPOS') . '/1.0',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $address,
            'format' => 'json',
            'limit' => 1,
        ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();
        if (empty($data) || ! isset($data[0]['lat'], $data[0]['lon'])) {
            return null;
        }

        return [
            (float) $data[0]['lat'],
            (float) $data[0]['lon'],
        ];
    }

    /**
     * Reverse geocode lat/lng to a display address using Nominatim. Returns address string or null.
     * Usage policy: max 1 request per second.
     */
    public function reverseGeocode(float $lat, float $lng): ?string
    {
        $response = Http::withHeaders([
            'User-Agent' => config('app.name', 'LandogzPOS') . '/1.0',
        ])->get('https://nominatim.openstreetmap.org/reverse', [
            'lat' => $lat,
            'lon' => $lng,
            'format' => 'json',
        ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();
        return isset($data['display_name']) ? (string) $data['display_name'] : null;
    }
}
