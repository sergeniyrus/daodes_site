<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ProductImportService;
use Illuminate\Http\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

final class ImportProductController extends Controller
{
    public function __invoke(
        Request $request,
        ProductImportService $productImportService
    ): RedirectResponse {
        $validated = $request->validate([
            'csv' => 'required|string',
        ]);

        // Копируем файл из временной локации в постоянную.
        $fileLocation = Storage::putFile(
            path: 'imports',
            file: new File(Storage::path($validated['csv']))
        );

        $productImportService->import(
            csvLocation: $fileLocation
        );

        return redirect()
            ->route('products.import')
            ->with('success', 'Products imported successfully');
    }
}