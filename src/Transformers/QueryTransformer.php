<?php

namespace Kodeas\Monitor\Transformers;

use DB;

class QueryTransformer
{

    public function run(): array
    {
        $queryLog = DB::getQueryLog();

        $queries = [];

        foreach ($queryLog as $queryInfo) {
            $query = $queryInfo['query'];
            $bindings = $queryInfo['bindings'];

            foreach ($bindings as $binding) {
                $query = preg_replace('/\?/', $binding, $query, 1);
            }

            $queries[] = [
                'executed_sql' => config('monitor.loggers.executed_sql') ? $query : null,
                'prepared_sql' => $queryInfo['query'],
                'execution_time' => $queryInfo['time'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        return [
            'queries' => $queries
        ];
    }
}
