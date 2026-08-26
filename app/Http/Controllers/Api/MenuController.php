<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    /**
     * Получить меню организации.
     */
    public function index(Organization $organization): JsonResponse
    {
        $sections = $organization
            ->menuSections()
            ->where('is_active', true)
            ->with([
                'activeItems',
            ])
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
            ],

            'sections' => $sections->map(
                function ($section) {
                    return [
                        'id' => $section->id,
                        'name' => $section->name,
                        'sort_order' => $section->sort_order,

                        'items' => $section->activeItems->map(
                            function ($item) {
                                return [
                                    'id' => $item->id,
                                    'name' => $item->name,
                                    'weight' => $item->weight,
                                    'unit' => $item->unit,
                                    'price' => $item->price,
                                    'description' => $item->description,
                                    'sort_order' => $item->sort_order,
                                ];
                            }
                        )->values(),
                    ];
                }
            )->values(),
        ]);
    }
}
