<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppRelease;
use App\Models\ReleaseCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Organization;

class ReleaseAdminController extends Controller
{
    /**
     * Список категорий и релизов.
     */
    public function index(): View
{
    $organizations = Organization::with([
        'releaseCategories' => function ($query) {
            $query->with([
                'releases' => function ($query) {
                    $query->orderByDesc('version_code');
                }
            ])->orderBy('id');
        }
    ])
        ->orderBy('id')
        ->get();

    return view(
        'admin.releases.index',
        compact('organizations')
    );
}


    /**
     * Форма создания релиза.
     */
    public function create(): View
    {
        $categories = ReleaseCategory::orderBy('id')->get();

        return view(
            'admin.releases.create',
            compact('categories')
        );
    }


    /**
     * Сохранение нового релиза.
     */
    public function store(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([

            'category_id' => [
                'required',
                'integer',
                'exists:release_categories,id',
            ],

            'version' => [
                'required',
                'string',
                'max:100',
            ],

            'version_code' => [
                'required',
                'integer',
                'min:1',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'apk_file' => [
                'required',
                'file',
                'mimes:apk',
                'max:512000',
            ],

            'is_required' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
         * ============================================================
         * APK
         * ============================================================
         */

        $file = $request->file('apk_file');

        $path = $file->store(
            'releases',
            'public'
        );

        $validated['apk_url'] =
            asset('storage/' . $path);

        $validated['apk_size'] =
            $file->getSize();

        $validated['apk_sha256'] =
            hash_file(
                'sha256',
                $file->getRealPath()
            );


        /*
         * ============================================================
         * ЧЕКБОКСЫ
         * ============================================================
         */

        $validated['is_required'] =
            $request->boolean('is_required');

        $validated['is_active'] =
            $request->boolean('is_active');


        /*
         * ============================================================
         * ДАТА РЕЛИЗА
         * ============================================================
         */

        $validated['released_at'] = now();


        /*
         * ============================================================
         * СОЗДАНИЕ
         * ============================================================
         */

        AppRelease::create($validated);


        return redirect()
            ->route('admin.releases.index')
            ->with(
                'success',
                'Релиз успешно создан.'
            );
    }


    /**
     * Форма редактирования релиза.
     */
    public function edit(
        AppRelease $release
    ): View {

        $categories =
            ReleaseCategory::orderBy('id')->get();

        return view(
            'admin.releases.edit',
            compact(
                'release',
                'categories'
            )
        );
    }


    /**
     * Обновление релиза.
     */
    public function update(
        Request $request,
        AppRelease $release
    ): RedirectResponse {

        $validated = $request->validate([

            'category_id' => [
                'required',
                'integer',
                'exists:release_categories,id',
            ],

            'version' => [
                'required',
                'string',
                'max:100',
            ],

            'version_code' => [
                'required',
                'integer',
                'min:1',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'apk_file' => [
                'nullable',
                'file',
                'mimes:apk',
                'max:512000',
            ],

            'is_required' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
         * ============================================================
         * APK
         * ============================================================
         */

        if ($request->hasFile('apk_file')) {

            /*
             * Удаляем старый APK.
             */

            if ($release->apk_url) {

                $oldPath = parse_url(
                    $release->apk_url,
                    PHP_URL_PATH
                );

                if ($oldPath) {

                    $oldPath = ltrim(
                        str_replace(
                            '/storage/',
                            '',
                            $oldPath
                        ),
                        '/'
                    );

                    Storage::disk('public')
                        ->delete($oldPath);
                }
            }


            /*
             * Сохраняем новый APK.
             */

            $file = $request->file('apk_file');

            $path = $file->store(
                'releases',
                'public'
            );


            /*
             * URL.
             */

            $validated['apk_url'] =
                asset('storage/' . $path);


            /*
             * Размер.
             */

            $validated['apk_size'] =
                $file->getSize();


            /*
             * SHA-256.
             */

            $validated['apk_sha256'] =
                hash_file(
                    'sha256',
                    $file->getRealPath()
                );
        }


        /*
         * ============================================================
         * ЧЕКБОКСЫ
         * ============================================================
         */

        $validated['is_required'] =
            $request->boolean('is_required');

        $validated['is_active'] =
            $request->boolean('is_active');


        /*
         * ============================================================
         * СОХРАНЕНИЕ
         * ============================================================
         */

        $release->update($validated);


        return redirect()
            ->route('admin.releases.index')
            ->with(
                'success',
                'Релиз успешно обновлён.'
            );
    }
}