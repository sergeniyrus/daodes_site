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
        // Загружаем ключ шифрования из .env
        $this->key = Key::loadFromAsciiSafeString(env('ENCRYPTION_KEY'));
    }

    /**
     * Шифрует данные.
     */
    public function encrypt($data)
    {
        //Log::info('Данные для шифрования:', ['data' => $data]);

        if (empty($data)) {
            throw new Exception("Данные для шифрования не могут быть пустыми.");
        }

        if (!mb_check_encoding($data, 'UTF-8')) {
            throw new Exception("Данные должны быть в кодировке UTF-8.");
        }

        try {
            $encryptedData = Crypto::encrypt($data, $this->key);

            if (strlen($encryptedData) < 16) {
                throw new Exception("Зашифрованные данные слишком короткие.");
            }

            return $encryptedData;
        } catch (Exception $e) {
            Log::error('Ошибка шифрования 1: ' . $e->getMessage());
            throw new Exception("Ошибка шифрования: " . $e->getMessage());
        }
    }

    /**
     * Дешифрует данные.
     */
    public function decrypt($encryptedData)
    {
        try {
            return Crypto::decrypt($encryptedData, $this->key);
        } catch (Exception $e) {
            Log::error('Ошибка дешифрования 2: ' . $e->getMessage());
            throw new Exception("Ошибка дешифрования: " . $e->getMessage());
        }
    }
}