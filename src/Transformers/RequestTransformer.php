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
            'uri' => $this->request->route()->uri(),
            'path' => $this->request->path(),
            'request_data' => null,
            'request_headers' => $this->request->headers->all(),
            'query_params' => $this->request->query(),
            'action' => optional($this->request->route())->getActionName(),
            'is_json' => $this->request->isJson(),
            'user' => null,
            'user_id' => null,
        ];

        if (config('monitor.loggers.request_data')) {
            $data['request_data'] = $this->cleanObject($this->request->all());
        }

        if (config('monitor.loggers.user')) {
            $user = $this->request->user();

            if ($user) {
                $data['user'] = $user->only(config('monitor.logger_details.user_fields'));
                $data['user_id'] = $user->id;
            }
        }

        return $data;
    }

    public function cleanObject($obj)
    {
        if (is_null($obj)) {
            return null;
        }

        if (is_array($obj)) {
            $clean = [];

            foreach ($obj as $key => $value) {
                $clean[$key] = $this->isRedacted($key) ? '[FILTERED]' : $this->cleanObject($value);
            }

            return $clean;
        }

        return $obj;
    }

    private function isRedacted($key): bool
    {
        foreach (config('monitor.redacted_keys') as $redactedKey) {
            if (strtolower($redactedKey) == strtolower($key)) {
                return true;
            }
        }

        return false;
    }
}
