<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppRelease;
use App\Models\Organization;
use App\Models\ReleaseCategory;
use App\Services\IPFSService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <--- 1. ДОБАВЛЕНО
use Illuminate\View\View;
use Throwable;

class ReleaseAdminController extends Controller
{
    protected IPFSService $ipfsService;

    public function __construct(IPFSService $ipfsService)
    {
        $this->ipfsService = $ipfsService;
    }


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
                ])
                ->orderBy('id');

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
    // <--- 2. ДОБАВЛЕН ПАРАМЕТР Organization $organization
    public function create(Organization $organization): View
    {
        // <--- 3. ПОЛУЧАЕМ КАТЕГОРИИ ТОЛЬКО ЭТОЙ ОРГАНИЗАЦИИ
        $categories = $organization->releaseCategories()->orderBy('id')->get();

        return view(
            'admin.releases.create',
            compact('categories', 'organization') // <--- 4. ПЕРЕДАЕМ organization в вид
        );
    }


    /**
     * Сохранение нового релиза.
     */
    // <--- 5. ДОБАВЛЕН ПАРАМЕТР Organization $organization
    public function store(
        Request $request,
        Organization $organization
    ): RedirectResponse {

        $validated = $request->validate([

            'category_id' => [
                'required',
                'integer',
                // <--- 6. СТРОГАЯ ПРОВЕРКА: категория должна принадлежать этой организации
                Rule::exists('release_categories', 'id')->where('organization_id', $organization->id),
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
                // <--- 7. ПРОВЕРКА УНИКАЛЬНОСТИ: перехватит дубликат до SQL-ошибки
                Rule::unique('app_releases')->where(fn ($query) => $query->where('category_id', $request->category_id)),
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
         * APK → IPFS → ПРОВЕРКА ЦЕЛОСТНОСТИ
         * ============================================================
         */

        $file =
            $request->file('apk_file');


        try {

            $ipfsResult =
                $this->ipfsService->uploadFileAndVerify(
                    $file
                );


            /*
             * CID.
             */
            $cid =
                $ipfsResult['cid'];


            /*
             * Публичный URL оставляем для совместимости.
             *
             * Пользовательское скачивание APK через него
             * больше не выполняется.
             */
            $validated['apk_url'] =
                $this->ipfsService->gatewayUrl(
                    $cid,
                    'app.apk'
                );


            /*
             * Фактический размер APK.
             */
            $validated['apk_size'] =
                $ipfsResult['size'];


            /*
             * SHA-256 APK.
             */
            $validated['apk_sha256'] =
                $ipfsResult['sha256'];


            /*
             * CID сохраняется через существующую модель/поле,
             * если оно поддерживается accessor/mutator модели.
             */
            if (
                isset($validated['ipfs_cid'])
            ) {

                $validated['ipfs_cid'] =
                    $cid;
            }

        } catch (Throwable $e) {

            report($e);


            return back()
                ->withInput()
                ->with(
                    'error',
                    'Не удалось загрузить APK в IPFS: '
                    . $e->getMessage()
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
         * ДАТА РЕЛИЗА
         * ============================================================
         */

        $validated['released_at'] =
            now();


        /*
         * ============================================================
         * СОЗДАНИЕ
         * ============================================================
         */

        AppRelease::create(
            $validated
        );


        return redirect()
            ->route('admin.releases.index')
            ->with(
                'success',
                'Релиз успешно создан. APK загружен в IPFS и проверен по SHA-256.'
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
                // <--- 8. ПРОВЕРКА УНИКАЛЬНОСТИ ПРИ ОБНОВЛЕНИИ (игнорируем текущую запись)
                Rule::unique('app_releases')->where(fn ($query) => $query->where('category_id', $request->category_id))->ignore($release->id),
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
         * НОВЫЙ APK
         * ============================================================
         */

        if ($request->hasFile('apk_file')) {

            $file =
                $request->file('apk_file');


            try {

                /*
                 * Загружаем APK в IPFS и сразу проверяем,
                 * что IPFS вернул абсолютно те же байты.
                 */
                $ipfsResult =
                    $this->ipfsService->uploadFileAndVerify(
                        $file
                    );


                /*
                 * CID.
                 */
                $cid =
                    $ipfsResult['cid'];


                /*
                 * URL для совместимости.
                 */
                $validated['apk_url'] =
                    $this->ipfsService->gatewayUrl(
                        $cid,
                        'app.apk'
                    );


                /*
                 * Размер.
                 */
                $validated['apk_size'] =
                    $ipfsResult['size'];


                /*
                 * SHA-256.
                 */
                $validated['apk_sha256'] =
                    $ipfsResult['sha256'];


                /*
                 * Если поле ipfs_cid присутствует
                 * среди разрешённых атрибутов формы,
                 * сохраняем новый CID.
                 */
                if (
                    isset($validated['ipfs_cid'])
                ) {

                    $validated['ipfs_cid'] =
                        $cid;
                }

            } catch (Throwable $e) {

                report($e);


                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Не удалось загрузить новый APK в IPFS: '
                        . $e->getMessage()
                    );
            }
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

        $release->update(
            $validated
        );


        return redirect()
            ->route('admin.releases.index')
            ->with(
                'success',
                'Релиз успешно обновлён.'
            );
    }
}