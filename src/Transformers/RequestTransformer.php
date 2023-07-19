<?php

namespace Kodeas\Monitor\Transformers;

use Illuminate\Http\Request;

class RequestTransformer
{

    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run(): array
    {
        $data = [
            'method' => $this->request->method(),
            'uri' => $this->request->getUri(),
            'path' => $this->request->path(),
            'request_data' => null,
            'query_params' => $this->request->query(),
            'action' => optional($this->request->route())->getActionName(),
            'headers' => $this->request->headers->all(),
            'json' => $this->request->isJson(),
            'user' => null,
        ];

        if (config('monitor.loggers.request_data')) {
            $data['request_data'] = $this->request->all();
        }

        if (config('monitor.loggers.user')) {
            $user = $this->request->user();

            if ($user) {
                $data['user'] = $user->only(config('monitor.logger_details.user_fields'));
            }
        }

        return $data;
    }
}