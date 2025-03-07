<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\EncryptionService;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'sender_id',
        'message', // Основное сообщение (может быть null)
        'ipfs_cid', // CID из IPFS
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Загружает сообщение в IPFS и возвращает CID.
     */
    public function uploadMessageToIPFS($message)
    {
        Log::info('Uploading message to IPFS', ['messageLength' => strlen($message)]);

        try {
            // Шифруем сообщение
            $encryptionService = new EncryptionService();
            $encryptedMessage = $encryptionService->encrypt($message);

            // Отправляем файл в IPFS через ipfs.io
            $client = new Client([
                'base_uri' => 'https://daodes.space'
            ]);

            $response = $client->request('POST', '/api/v0/add', [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => $encryptedMessage,
                        'filename' => 'message.txt', // Имя файла для IPFS
                    ]
                ]
            ]);

            // Парсим ответ и извлекаем CID
            $data = json_decode($response->getBody(), true);

            if (isset($data['Hash'])) {
                $cid = $data['Hash'];
                Log::info('Message uploaded to IPFS successfully', ['cid' => $cid]);
                return $cid;
            } else {
                Log::error('IPFS error: No Hash in response', ['response' => $data]);
                throw new Exception('No valid response from IPFS');
            }
        } catch (Exception $e) {
            Log::error('IPFS upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Получает сообщение из IPFS и дешифрует его.
     */
    public function getMessageFromIPFS($cid)
    {
        try {
            // Логируем начало процесса получения данных из IPFS
            Log::info('Начало получения данных из IPFS', [
                'cid' => $cid,
            ]);

            // Получаем файл из IPFS через ipfs.io
            $client = new Client([
                'base_uri' => 'https://daodes.space'
            ]);

            $response = $client->request('GET', "/ipfs/{$cid}");
            $fileContent = $response->getBody()->getContents();

            // Логируем полученные данные из IPFS
            Log::info('Данные получены из IPFS', [
                'cid' => $cid,
                'fileContentLength' => strlen($fileContent),
                'fileContentSample' => substr($fileContent, 0, 50), // Логируем первые 50 символов
            ]);

            // Проверяем, что данные не пустые
            if (empty($fileContent)) {
                throw new Exception('Ошибка: данные из IPFS пустые');
            }

            // Дешифруем сообщение
            $encryptionService = new EncryptionService();
            return $encryptionService->decrypt($fileContent);
        } catch (Exception $e) {
            Log::error('Ошибка при получении данных из IPFS', [
                'cid' => $cid,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Дешифрует сообщение при получении.
     */
    public function getMessageAttribute($value)
    {
        if ($this->ipfs_cid) {
            try {
                return $this->getMessageFromIPFS($this->ipfs_cid);
            } catch (Exception $e) {
                Log::error('Ошибка при дешифровании сообщения', [
                    'cid' => $this->ipfs_cid,
                    'error' => $e->getMessage(),
                ]);
                return 'Ошибка: не удалось расшифровать сообщение';
            }
        }
        return null;
    }
}