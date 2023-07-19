<?php

namespace Kodeas\Monitor\Transformers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ResponseTransformer
{
    private Response|JsonResponse $response;
    private Request $request;

    public function __construct(Request $request, Response|JsonResponse $response)
    {
        $this->response = $response;
        $this->request = $request;
    }

    public function run(): array
    {
        return [
            'response_status' => $this->response->status(),
            'response_time_in_ms' => $this->getResponseTimeInMs(),
        ];
    }

    private function getResponseTimeInMs(): int
    {
        $responseTimeInSeconds = microtime(true) - $this->request->attributes->get('start_time');
        return (int) ($responseTimeInSeconds * 1000);
    }
}