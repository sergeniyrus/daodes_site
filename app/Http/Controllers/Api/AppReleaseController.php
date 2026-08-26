<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppRelease;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppReleaseController extends Controller
{
    /**
     * Получить последний активный релиз категории.
     *
     * Например:
     *
     * /api/recipe-calculator/releases/latest?category=root
     *
     * /api/recipe-calculator/releases/latest?category=cook
     *
     * /api/recipe-calculator/releases/latest?category=manager
     */
    public function latest(
        Request $request
    ): JsonResponse {

        $category =
            $request->query(
                'category',
                'root'
            );

        $release =
            AppRelease::query()
                ->where('is_active', true)
                ->whereHas(
                    'category',
                    function ($query) use ($category) {

                        $query
                            ->where(
                                'slug',
                                $category
                            )
                            ->where(
                                'is_active',
                                true
                            );
                    }
                )
                ->with('category')
                ->orderByDesc(
                    'version_code'
                )
                ->first();

        if (!$release) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Активный релиз категории не найден',
                ],
                404
            );
        }

        return response()->json(
            [
                'success' => true,

                'release' => [

                    'id' =>
                        $release->id,

                    'category' =>
                        $release->category->slug,

                    'category_name' =>
                        $release->category->name,

                    'version' =>
                        $release->version,

                    'version_code' =>
                        $release->version_code,

                    'title' =>
                        $release->title,

                    'description' =>
                        $release->description,

                    'apk_url' =>
                        $release->apk_url,

                    'apk_sha256' =>
                        $release->apk_sha256,

                    'apk_size' =>
                        $release->apk_size,

                    'is_required' =>
                        $release->is_required,

                    'is_active' =>
                        $release->is_active,

                    'released_at' =>
                        $release->released_at?->toISOString(),
                ],
            ]
        );
    }
}
