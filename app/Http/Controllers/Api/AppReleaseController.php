<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppRelease;
use App\Services\IPFSService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppReleaseController extends Controller
{
    protected IPFSService $ipfsService;

    public function __construct(
        IPFSService $ipfsService
    ) {
        $this->ipfsService = $ipfsService;
    }


    /**
     * Получить последний активный релиз категории.
     *
     * Например:
     *
     * /api/recipe-calculator/releases/latest?category=root
     * /api/recipe-calculator/releases/latest?category=cook
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
                ->where(
                    'is_active',
                    true
                )
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

                    /*
                     * Старый IPFS URL.
                     *
                     * Оставляем для совместимости.
                     */
                    'apk_url' =>
                        $release->apk_url,

                    /*
                     * Основной URL скачивания.
                     */
                    'download_url' =>
                        route(
                            'releases.download',
                            [
                                'release' =>
                                    $release->id,
                            ]
                        ),

                    /*
                     * SHA-256 APK.
                     */
                    'apk_sha256' =>
                        $release->apk_sha256,

                    /*
                     * Размер APK.
                     */
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


    /**
     * Скачать APK релиза.
     *
     * Публичный HTTPS endpoint:
     *
     * /api/app/releases/{release}/download
     *
     * APK физически хранится в IPFS.
     *
     * Пользователь не обращается напрямую
     * к IPFS gateway.
     */
    public function download(
        AppRelease $release
    ): Response {

        /*
         * ============================================================
         * ПРОВЕРКА РЕЛИЗА
         * ============================================================
         */

        if (!$release->is_active) {

            abort(
                404,
                'Релиз недоступен'
            );
        }


        /*
         * ============================================================
         * CID
         * ============================================================
         */

        $cid =
            $release->ipfs_cid;


        if (!$cid) {

            abort(
                404,
                'CID APK не найден'
            );
        }


        /*
         * ============================================================
         * ПОЛУЧАЕМ APK НАПРЯМУЮ ИЗ IPFS
         * ============================================================
         *
         * ВАЖНО:
         *
         * Здесь больше нет:
         *
         * Http::get($ipfsUrl)
         *
         * и нет обращения к:
         *
         * https://daodes.space/ipfs/...
         *
         *
         * Получаем содержимое напрямую через
         * IPFS API.
         */

        try {

            $content =
                $this->ipfsService->downloadFile(
                    $cid
                );

        } catch (\Throwable $e) {

            report($e);

            abort(
                502,
                'Не удалось получить APK из IPFS'
            );
        }


        /*
         * ============================================================
         * ПРОВЕРКА ПУСТОГО ФАЙЛА
         * ============================================================
         */

        if (
            $content === ''
        ) {

            abort(
                502,
                'IPFS вернул пустой APK'
            );
        }


        /*
         * ============================================================
         * ФАКТИЧЕСКИЙ РАЗМЕР
         * ============================================================
         */

        $actualSize =
            strlen(
                $content
            );


        /*
         * ============================================================
         * ФАКТИЧЕСКИЙ SHA-256
         * ============================================================
         */

        $actualSha256 =
            hash(
                'sha256',
                $content
            );


        /*
         * ============================================================
         * ПРОВЕРКА С SHA ИЗ БД
         * ============================================================
         *
         * Если SHA отличается, НЕ отдаём APK пользователю.
         *
         * Это принципиально важно.
         */

        $databaseSha256 =
            strtolower(
                trim(
                    (string) $release->apk_sha256
                )
            );


        if (
            $databaseSha256 !== ''
            && !hash_equals(
                $databaseSha256,
                strtolower(
                    $actualSha256
                )
            )
        ) {

            report(
                new \RuntimeException(
                    'SHA-256 APK не совпадает. '
                    . 'Release ID: '
                    . $release->id
                    . '. DB: '
                    . $databaseSha256
                    . '. IPFS: '
                    . $actualSha256
                )
            );


            abort(
                502,
                'Контрольная сумма APK не совпадает. Скачивание заблокировано.'
            );
        }


        /*
         * ============================================================
         * ПРОВЕРКА РАЗМЕРА
         * ============================================================
         */

        if (
            $release->apk_size !== null
            && (int) $release->apk_size !== $actualSize
        ) {

            report(
                new \RuntimeException(
                    'Размер APK не совпадает. '
                    . 'Release ID: '
                    . $release->id
                    . '. DB: '
                    . $release->apk_size
                    . '. IPFS: '
                    . $actualSize
                )
            );


            abort(
                502,
                'Размер APK не совпадает. Скачивание заблокировано.'
            );
        }


        /*
         * ============================================================
         * ИМЯ ФАЙЛА
         * ============================================================
         */

        $filename =
            'elk-i-igolki-'
            . $release->version
            . '.apk';


        /*
         * Защищаем имя файла.
         */
        $filename =
            preg_replace(
                '/[^a-zA-Z0-9._-]/',
                '_',
                $filename
            );


        /*
         * ============================================================
         * ОТДАЧА APK
         * ============================================================
         */

        return response(
            $content,
            200,
            [

                'Content-Type' =>
                    'application/vnd.android.package-archive',

                'Content-Disposition' =>
                    'attachment; filename="' . $filename . '"',

                'Content-Length' =>
                    $actualSize,

                'Cache-Control' =>
                    'public, max-age=3600',

                'X-Content-Type-Options' =>
                    'nosniff',

                /*
                 * Фактический SHA отданного APK.
                 */
                'X-APK-SHA256' =>
                    $actualSha256,

                /*
                 * Размер фактического APK.
                 */
                'X-APK-SIZE' =>
                    (string) $actualSize,

            ]
        );
    }
}
