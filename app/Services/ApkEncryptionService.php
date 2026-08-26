<?php

namespace App\Services;

use RuntimeException;

class ApkEncryptionService
{
    private const CIPHER = 'aes-256-gcm';

    /**
     * Шифрует APK.
     *
     * Формат результата:
     *
     * [16 bytes IV]
     * [16 bytes authentication tag]
     * [encrypted data]
     */
    public function encrypt(
        string $inputPath,
        string $outputPath
    ): array {

        if (!is_file($inputPath)) {
            throw new RuntimeException(
                'Исходный APK-файл не найден.'
            );
        }

        $masterKey = config(
            'app.release_encryption_key'
        );

        if (empty($masterKey)) {
            throw new RuntimeException(
                'Ключ шифрования релизов не настроен.'
            );
        }

        /*
         * Приводим ключ к 32 байтам.
         */
        $key = hash(
            'sha256',
            $masterKey,
            true
        );

        /*
         * AES-256-GCM использует 12-байтовый IV.
         */
        $iv = random_bytes(12);

        $input = fopen(
            $inputPath,
            'rb'
        );

        if ($input === false) {
            throw new RuntimeException(
                'Не удалось открыть APK.'
            );
        }

        $output = fopen(
            $outputPath,
            'wb'
        );

        if ($output === false) {

            fclose($input);

            throw new RuntimeException(
                'Не удалось создать зашифрованный файл.'
            );
        }

        /*
         * Записываем IV в начало файла.
         */
        fwrite(
            $output,
            $iv
        );

        /*
         * Tag пока неизвестен.
         *
         * Зарезервируем 16 байт.
         */
        $tagPosition = ftell($output);

        fwrite(
            $output,
            str_repeat("\0", 16)
        );

        /*
         * Потоковое шифрование.
         *
         * Важно:
         * AES-GCM в PHP через openssl_encrypt
         * не является полноценным streaming API.
         *
         * Для MVP читаем APK целиком.
         */

        $data = stream_get_contents(
            $input
        );

        fclose($input);

        if ($data === false) {

            fclose($output);

            throw new RuntimeException(
                'Не удалось прочитать APK.'
            );
        }

        $tag = '';

        $encrypted = openssl_encrypt(
            $data,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            16
        );

        if ($encrypted === false) {

            fclose($output);

            throw new RuntimeException(
                'Ошибка AES-256-GCM шифрования.'
            );
        }

        fwrite(
            $output,
            $encrypted
        );

        /*
         * Возвращаемся к месту tag.
         */
        fseek(
            $output,
            $tagPosition
        );

        fwrite(
            $output,
            $tag
        );

        fclose($output);

        return [
            'iv' => base64_encode($iv),
            'auth_tag' => base64_encode($tag),
            'encrypted_size' => filesize($outputPath),
        ];
    }
}