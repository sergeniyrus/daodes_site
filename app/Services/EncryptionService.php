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
        try {
            return Crypto::encrypt($data, $this->key);
        } catch (Exception $e) {
            Log::error('Ошибка шифрования: ' . $e->getMessage());
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
            Log::error('Ошибка дешифрования: ' . $e->getMessage());
            throw new Exception("Ошибка дешифрования: " . $e->getMessage());
        }
    }
} 