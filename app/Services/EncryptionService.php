<?php

namespace App\Services;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Exception;
use Illuminate\Support\Facades\Log;

class EncryptionService
{
    protected $key;

    public function __construct()
    {
        $keyString = env('ENCRYPTION_KEY');

        if (empty($keyString)) {
            throw new Exception('Отсутствует ключ шифрования (ENCRYPTION_KEY) в .env.');
        }

        try {
            $this->key = Key::loadFromAsciiSafeString($keyString);
        } catch (Exception $e) {
            Log::error('Ошибка загрузки ключа шифрования: ' . $e->getMessage());
            throw new Exception('Неверный формат ENCRYPTION_KEY в .env');
        }
    }

    /**
     * Шифрует данные.
     */
    public function encrypt($data)
    {
        if (empty($data)) {
            throw new Exception("Данные для шифрования не могут быть пустыми.");
        }

        if (!mb_check_encoding($data, 'UTF-8')) {
            throw new Exception("Данные должны быть в кодировке UTF-8.");
        }

        try {
            // Шифруем и кодируем в base64, чтобы избежать потери бинарных символов при передаче
            $encryptedData = Crypto::encrypt($data, $this->key);
            $encoded = base64_encode($encryptedData);

            if (strlen($encoded) < 16) {
                throw new Exception("Зашифрованные данные слишком короткие (возможно, ошибка шифрования).");
            }

            return $encoded;
        } catch (Exception $e) {
            Log::error('Ошибка шифрования: ' . $e->getMessage());
            throw new Exception("Ошибка шифрования: " . $e->getMessage());
        }
    }

    /**
     * Дешифрует данные.
     * Поддерживает как новые (base64), так и старые бинарные данные.
     */
    public function decrypt($encryptedData)
    {
        if (empty($encryptedData)) {
            throw new Exception("Пустые данные для дешифрования.");
        }

        try {
            // Пробуем декодировать base64
            $decoded = base64_decode($encryptedData, true);

            // Если base64-декодирование не удалось, возможно, старый формат — пробуем как есть
            if ($decoded === false || strlen($decoded) < 16) {
                Log::warning('Данные не в base64, пробуем старый формат дешифрования.');
                $decoded = $encryptedData;
            }

            // Попытка дешифровки
            return Crypto::decrypt($decoded, $this->key);

        } catch (Exception $e) {
            Log::error('Ошибка дешифрования: ' . $e->getMessage(), [
                'data_length' => strlen($encryptedData),
                'preview' => substr($encryptedData, 0, 40)
            ]);

            throw new Exception("Ошибка дешифрования: " . $e->getMessage());
        }
    }
}
