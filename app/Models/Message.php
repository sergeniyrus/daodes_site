<?php // app/Models/Message.php

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
        'edited_at',
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
     * Загружает строку вида "nonce|ciphertext" в IPFS.
     */
    public function uploadMessageToIPFS(string $fullPayload): string
    {
        try {
            $client = new Client([
                'base_uri' => rtrim(config('services.ipfs.gateway', 'https://daodes.space'), '/'),
                'timeout' => 60.0,
            ]);

            $response = $client->request('POST', '/api/v0/add', [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => $fullPayload,
                        'filename' => 'message.txt',
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            if (!isset($data['Hash'])) {
                throw new Exception('IPFS did not return CID');
            }
            return $data['Hash'];
        } catch (Exception $e) {
            Log::error('IPFS upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Получает полный payload из IPFS: "nonce|ciphertext"
     */
    public function getMessageFromIPFS(string $cid): string
    {
        if (!$cid) {
            return '';
        }

        try {
            $client = new Client([
                'base_uri' => rtrim(config('services.ipfs.gateway', 'https://daodes.space'), '/'),
            ]);

            $response = $client->request('GET', "/ipfs/{$cid}");
            return $response->getBody()->getContents();
        } catch (Exception $e) {
            Log::error('IPFS fetch error', ['cid' => $cid, 'error' => $e->getMessage()]);
            return '[Ошибка загрузки]';
        }
    }

    /**
     * Обновляет содержимое сообщения: загружает новый payload в IPFS и обновляет CID.
     */
    public function updateMessageContent(string $newFullPayload): string
    {
        $newCid = $this->uploadMessageToIPFS($newFullPayload);
        $this->ipfs_cid = $newCid;
        $this->save();
        return $newCid;
    }
}