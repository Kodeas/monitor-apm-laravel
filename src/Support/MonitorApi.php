<?php

namespace Kodeas\Monitor\Support;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Log;
use Throwable;

class MonitorApi
{

    const _BASE_URL = 'http://192.168.0.1';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_url' => self::_BASE_URL
        ]);
    }

    public function submit($data): bool
    {
        try {
            $this->client->post('/api/import', [
                'body' => json_encode($data)
            ]);
        } catch (ClientException|ServerException|Throwable $e) {
            Log::error($e);
            return false;
        }

        return true;
    }
}
