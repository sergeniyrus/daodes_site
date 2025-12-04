<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'sender_id',
        'ipfs_cid',
    ];

    protected $appends = ['message'];

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
     * Загружает зашифрованное сообщение в IPFS и возвращает CID.
     */
    public function uploadMessageToIPFS($encryptedPayload)
    {
        try {
            $client = new Client([
                'base_uri' => 'https://daodes.space',
                'timeout' => 60.0,
            ]);

            $response = $client->request('POST', '/api/v0/add', [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => $encryptedPayload,
                        'filename' => 'message.txt',
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if (isset($data['Hash'])) {
                return $data['Hash'];
            }
            throw new Exception('No CID from IPFS');
        } catch (Exception $e) {
            Log::error('IPFS upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Получает зашифрованное сообщение из IPFS.
     */
    public function getMessageFromIPFS($cid)
    {
        try {
            $client = new Client([
                'base_uri' => 'https://daodes.space'
            ]);

            $response = $client->request('GET', "/ipfs/{$cid}");
            $fileContent = $response->getBody()->getContents();

            if (empty($fileContent)) {
                throw new Exception('IPFS returned empty data');
            }

            return $fileContent; // Возвращаем как есть — расшифровка на клиенте
        } catch (Exception $e) {
            Log::error('Ошибка при получении данных из IPFS', [
                'cid' => $cid,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Возвращает зашифрованное сообщение из IPFS (без расшифровки).
     */
    public function getMessageAttribute($value)
    {
        if ($this->ipfs_cid) {
            try {
                return $this->getMessageFromIPFS($this->ipfs_cid);
            } catch (Exception $e) {
                Log::error('Ошибка при загрузке сообщения из IPFS', [
                    'cid' => $this->ipfs_cid,
                    'error' => $e->getMessage(),
                ]);
                return 'Ошибка загрузки сообщения';
            }
        }
        return null;
    }
}