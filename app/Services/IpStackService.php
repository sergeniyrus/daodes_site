<?php

namespace App\Services;

use GuzzleHttp\Client;

class IpStackService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('IPSTACK_API_KEY');
    }

    /**
     * Получает информацию о местоположении по IP-адресу.
     *
     * @param string $ip
     * @return array
     */
    public function getLocation(string $ip): array
    {
        $response = $this->client->get("http://api.ipstack.com/{$ip}?access_key={$this->apiKey}");
        return json_decode($response->getBody(), true);
    }
}