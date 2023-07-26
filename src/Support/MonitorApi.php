<?php

namespace Kodeas\Monitor\Support;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Log;
use Throwable;

class MonitorApi
{

    const _BASE_URL = 'https://apm.kodeas.com/';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::_BASE_URL,
            'headers' => [
                'Authorization' => config('monitor.key')
            ]
        ]);
    }

    public function submit($data): bool
    {
        try {
            $this->client->post('/api/import', [
                'json' => $data
            ]);
        } catch (ClientException|ServerException|Throwable $e) {
            Log::error($e);
            return false;
        }

        return true;
    }
}
