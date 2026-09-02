<?php

namespace App\Services;

use Cloutier\PhpIpfsApi\IPFS;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class IPFSService
{
    protected IPFS $ipfs;


    public function __construct()
    {
        $this->ipfs = new IPFS(
            env('IPFS_HOST', 'localhost'),
            env('IPFS_PORT', 5001)
        );
    }


    /**
     * ============================================================
     * ЗАГРУЗКА ФАЙЛА В IPFS
     * ============================================================
     *
     * Загружает файл непосредственно через Kubo HTTP API:
     *
     * POST /api/v0/add
     *
     * Это важно для APK, потому что мы не используем
     * старую multipart-реализацию cloutier/php-ipfs-api.
     *
     * Возвращает CID.
     */
    public function uploadFile(
        UploadedFile $file
    ): string {

        if (!$file->isValid()) {

            throw new RuntimeException(
                'Загружаемый файл недействителен.'
            );
        }


        /*
         * Получаем реальный временный путь файла.
         */
        $path =
            $file->getRealPath();


        if (
            !$path
            || !is_file($path)
            || !is_readable($path)
        ) {

            throw new RuntimeException(
                'Не удалось получить временный файл APK.'
            );
        }


        /*
         * ========================================================
         * IPFS API
         * ========================================================
         */

        $host =
            env(
                'IPFS_HOST',
                'localhost'
            );


        $port =
            env(
                'IPFS_PORT',
                5001
            );


        $url =
            'http://'
            . $host
            . ':'
            . $port
            . '/api/v0/add'
            . '?pin=true';


        /*
         * ========================================================
         * CURL
         * ========================================================
         */

        $ch =
            curl_init(
                $url
            );


        if ($ch === false) {

            throw new RuntimeException(
                'Не удалось инициализировать CURL для IPFS.'
            );
        }


        /*
         * Передаём непосредственно файл.
         *
         * Важно:
         *
         * CURLFile заставляет PHP сформировать
         * корректный multipart/form-data запрос.
         */
        $curlFile =
            new \CURLFile(
                $path,
                $file->getMimeType()
                    ?: 'application/vnd.android.package-archive',
                $file->getClientOriginalName()
                    ?: 'app.apk'
            );


        curl_setopt_array(
            $ch,
            [

                CURLOPT_RETURNTRANSFER =>
                    true,

                CURLOPT_POST =>
                    true,

                CURLOPT_POSTFIELDS =>
                    [
                        'file' =>
                            $curlFile,
                    ],

                CURLOPT_CONNECTTIMEOUT =>
                    20,

                CURLOPT_TIMEOUT =>
                    300,

                CURLOPT_FOLLOWLOCATION =>
                    true,

            ]
        );


        /*
         * Выполняем загрузку.
         */
        $response =
            curl_exec(
                $ch
            );


        $httpCode =
            curl_getinfo(
                $ch,
                CURLINFO_HTTP_CODE
            );


        $curlError =
            curl_error(
                $ch
            );


        curl_close(
            $ch
        );


        /*
         * ========================================================
         * CURL ERROR
         * ========================================================
         */

        if (
            $response === false
        ) {

            throw new RuntimeException(
                'Ошибка загрузки файла в IPFS: '
                . $curlError
            );
        }


        /*
         * ========================================================
         * HTTP ERROR
         * ========================================================
         */

        if (
            $httpCode < 200
            || $httpCode >= 300
        ) {

            throw new RuntimeException(
                'IPFS вернул HTTP '
                . $httpCode
                . '. Ответ: '
                . $response
            );
        }


        /*
         * ========================================================
         * РАЗБОР ОТВЕТА
         * ========================================================
         *
         * Kubo может вернуть JSON:
         *
         * {
         *   "Name": "...",
         *   "Hash": "...",
         *   "Size": "..."
         * }
         *
         * Также возможен NDJSON.
         */

        $lines =
            preg_split(
                '/\r\n|\r|\n/',
                trim($response)
            );


        $cid =
            null;


        foreach (
            $lines as $line
        ) {

            if (
                trim($line) === ''
            ) {
                continue;
            }


            $data =
                json_decode(
                    $line,
                    true
                );


            if (
                !is_array($data)
            ) {
                continue;
            }


            if (
                !empty($data['Hash'])
            ) {

                $cid =
                    trim(
                        (string) $data['Hash']
                    );

                break;
            }


            if (
                !empty($data['hash'])
            ) {

                $cid =
                    trim(
                        (string) $data['hash']
                    );

                break;
            }
        }


        if (
            !$cid
        ) {

            /*
             * Попытка разобрать единый JSON.
             */
            $data =
                json_decode(
                    trim($response),
                    true
                );


            if (
                is_array($data)
                && !empty($data['Hash'])
            ) {

                $cid =
                    trim(
                        (string) $data['Hash']
                    );
            }
        }


        if (
            !$cid
        ) {

            throw new RuntimeException(
                'IPFS не вернул CID. Ответ: '
                . $response
            );
        }


        return $cid;
    }


    /**
     * ============================================================
     * ПРОВЕРКА ФАЙЛА В IPFS
     * ============================================================
     *
     * Загружает файл, получает CID, затем читает его обратно
     * непосредственно через IPFS API и сравнивает SHA-256.
     *
     * Возвращает:
     *
     * [
     *     'cid' => string,
     *     'size' => int,
     *     'sha256' => string,
     *     'source_sha256' => string,
     * ]
     */
    public function uploadFileAndVerify(
        UploadedFile $file
    ): array {

        if (!$file->isValid()) {

            throw new RuntimeException(
                'APK файл недействителен.'
            );
        }


        /*
         * Получаем исходные байты.
         */
        $sourceContent =
            $file->get();


        if (
            $sourceContent === ''
        ) {

            throw new RuntimeException(
                'Исходный APK пустой.'
            );
        }


        /*
         * SHA-256 исходного APK.
         */
        $sourceSha256 =
            hash(
                'sha256',
                $sourceContent
            );


        /*
         * Размер исходного APK.
         */
        $sourceSize =
            strlen(
                $sourceContent
            );


        /*
         * Загружаем файл в IPFS.
         */
        $cid =
            $this->uploadFile(
                $file
            );


        /*
         * Сразу читаем его обратно непосредственно
         * из IPFS API.
         */
        $ipfsContent =
            $this->downloadFile(
                $cid
            );


        if (
            $ipfsContent === ''
        ) {

            throw new RuntimeException(
                'IPFS вернул пустой APK после загрузки.'
            );
        }


        /*
         * Размер файла из IPFS.
         */
        $ipfsSize =
            strlen(
                $ipfsContent
            );


        /*
         * SHA-256 файла из IPFS.
         */
        $ipfsSha256 =
            hash(
                'sha256',
                $ipfsContent
            );


        /*
         * ========================================================
         * САМАЯ ВАЖНАЯ ПРОВЕРКА
         * ========================================================
         */

        if (
            $sourceSize !== $ipfsSize
            || !hash_equals(
                strtolower($sourceSha256),
                strtolower($ipfsSha256)
            )
        ) {

            throw new RuntimeException(
                'КРИТИЧЕСКАЯ ОШИБКА: APK после загрузки в IPFS '
                . 'не совпадает с исходным файлом. '
                . 'Исходный размер: '
                . $sourceSize
                . ', IPFS размер: '
                . $ipfsSize
                . '. Исходный SHA-256: '
                . $sourceSha256
                . ', IPFS SHA-256: '
                . $ipfsSha256
            );
        }


        return [

            'cid' =>
                $cid,

            'size' =>
                $ipfsSize,

            'sha256' =>
                $ipfsSha256,

            'source_sha256' =>
                $sourceSha256,

        ];
    }


    /**
     * ============================================================
     * IPFS GATEWAY URL
     * ============================================================
     */
    public function gatewayUrl(
        string $cid,
        ?string $filename = null
    ): string {

        $gateway =
            rtrim(
                env(
                    'IPFS_GATEWAY',
                    'https://daodes.space'
                ),
                '/'
            );


        $url =
            $gateway
            . '/ipfs/'
            . $cid;


        /*
         * filename остаётся только для обычного
         * gateway-доступа.
         *
         * Критическое скачивание APK через него
         * больше не выполняем.
         */
        if (
            $filename !== null
            && $filename !== ''
        ) {

            $url .=
                '?filename='
                . rawurlencode(
                    $filename
                );
        }


        return $url;
    }


    /**
     * ============================================================
     * ЗАГРУЗКА ФАЙЛА + URL
     * ============================================================
     */
    public function uploadFileAndGetUrl(
        UploadedFile $file
    ): string {

        $cid =
            $this->uploadFile(
                $file
            );


        return $this->gatewayUrl(
            $cid
        );
    }


    /**
     * ============================================================
     * МЕТАДАННЫЕ ФАЙЛА В IPFS
     * ============================================================
     */
    public function getFileMetadata(
        string $cid
    ): array {

        $content =
            $this->downloadFile(
                $cid
            );


        if (
            $content === ''
        ) {

            throw new RuntimeException(
                'IPFS вернул пустой файл.'
            );
        }


        return [

            'size' =>
                strlen(
                    $content
                ),

            'sha256' =>
                hash(
                    'sha256',
                    $content
                ),

        ];
    }


    /**
     * ============================================================
     * ПОЛУЧЕНИЕ ФАЙЛА ИЗ IPFS
     * ============================================================
     *
     * Используется непосредственно IPFS API:
     *
     * POST /api/v0/cat?arg=CID
     *
     * Здесь мы НЕ используем gateway.
     */
    public function downloadFile(
        string $cid
    ): string {

        if (
            trim($cid) === ''
        ) {

            throw new RuntimeException(
                'IPFS CID не указан.'
            );
        }


        $host =
            env(
                'IPFS_HOST',
                'localhost'
            );


        $port =
            env(
                'IPFS_PORT',
                5001
            );


        $url =
            'http://'
            . $host
            . ':'
            . $port
            . '/api/v0/cat?arg='
            . rawurlencode(
                $cid
            );


        $ch =
            curl_init(
                $url
            );


        if (
            $ch === false
        ) {

            throw new RuntimeException(
                'Не удалось инициализировать CURL для IPFS.'
            );
        }


        curl_setopt_array(
            $ch,
            [

                CURLOPT_RETURNTRANSFER =>
                    true,

                CURLOPT_FOLLOWLOCATION =>
                    true,

                CURLOPT_CONNECTTIMEOUT =>
                    20,

                CURLOPT_TIMEOUT =>
                    300,

                CURLOPT_POST =>
                    true,

                CURLOPT_POSTFIELDS =>
                    [],

            ]
        );


        $content =
            curl_exec(
                $ch
            );


        $httpCode =
            curl_getinfo(
                $ch,
                CURLINFO_HTTP_CODE
            );


        $curlError =
            curl_error(
                $ch
            );


        curl_close(
            $ch
        );


        if (
            $content === false
        ) {

            throw new RuntimeException(
                'Ошибка получения файла из IPFS: '
                . $curlError
            );
        }


        if (
            $httpCode < 200
            || $httpCode >= 300
        ) {

            throw new RuntimeException(
                'IPFS вернул HTTP '
                . $httpCode
            );
        }


        if (
            $content === ''
        ) {

            throw new RuntimeException(
                'IPFS вернул пустой файл.'
            );
        }


        return $content;
    }
}