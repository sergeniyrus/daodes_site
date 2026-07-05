<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\JsonResponse;

class AppVersionController extends Controller
{
    public function check(): JsonResponse
    {
        $latest = AppVersion::where('is_active', true)
            ->orderByDesc('version_code')
            ->first();

        if (!$latest) {
            return response()->json([
                'error' => 'No version available'
            ], 404);
        }

        return response()->json([
            'version_code' => $latest->version_code,
            'version_name' => $latest->version_name,
            'force_update' => $latest->force_update,
            'changelog' => $latest->changelog ?? [],
            'apk_url' => $latest->apk_url,
        ]);
    }
}