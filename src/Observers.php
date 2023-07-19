<?php

namespace Kodeas\Monitor;

use Illuminate\Http\Request;

class Observers
{

    public static function start(Request $request): void
    {
        \DB::connection()->enableQueryLog();

        $request->attributes->set('start_time', microtime(true));
        $request->attributes->set('start_cpu_usage', getrusage());
    }
}