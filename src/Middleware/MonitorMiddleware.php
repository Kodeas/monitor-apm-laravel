<?php

namespace Kodeas\Monitor\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Kodeas\Monitor\Observers;
use Kodeas\Monitor\Support\MonitorApi;
use Kodeas\Monitor\Transformers\GitTransformer;
use Kodeas\Monitor\Transformers\QueryTransformer;
use Kodeas\Monitor\Transformers\RequestTransformer;
use Kodeas\Monitor\Transformers\ResponseTransformer;
use Kodeas\Monitor\Transformers\ServerTransformer;
use Illuminate\Support\Str;

class MonitorMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        Observers::start($request);

        return $next($request);
    }

    public function terminate(Request $request, $response): void
    {
        if (!$request->route()) {
            return;
        }

        $notifyStages = config('monitor.notify_environments') ?: [];

        if (count($notifyStages)) {
            if (!in_array(app()->environment(), $notifyStages)) {
                return;
            }
        }

        $action = $request->route()->getActionName();
        if (!Str::startsWith($action, config('monitor.namespace'))) {
            return;
        }

        $data = [
            (new RequestTransformer($request))->run(),
            (new ResponseTransformer($request, $response))->run(),
            (new QueryTransformer())->run(),
            (new GitTransformer())->run(),
            (new ServerTransformer($request))->run()
        ];

        (new MonitorApi)->submit(array_merge(...$data));
    }
}
