<?php

namespace App\Services;

use Cloutier\PhpIpfsApi\IPFS;

class IPFSService
{
    protected $ipfs;

    public function __construct()
    {
        // Инициализация IPFS-клиента
        $this->ipfs = new IPFS(config('ipfs.host', 'localhost'), config('ipfs.port', 5001));
    }

    /**
     * Загружает файл на IPFS и возвращает его CID.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    public function uploadFile($file)
    {
        // Загружаем файл на IPFS
        $response = $this->ipfs->add($file->get());
        return $response['Hash']; // Возвращаем CID файла
    }
}