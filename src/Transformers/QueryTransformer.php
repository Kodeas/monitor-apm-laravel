<?php

namespace Kodeas\Monitor\Transformers;

class QueryTransformer
{

    public function run(): array
    {
        $queryLog = \DB::getQueryLog();

        $queries = [];

        foreach ($queryLog as $queryInfo) {
            $query = $queryInfo['query'];
            $bindings = $queryInfo['bindings'];

            foreach ($bindings as $binding) {
                $query = preg_replace('/\?/', $binding, $query, 1);
            }

            $queries[] = [
                'executed_sql' => config('monitor.executed_sql') ? $query : null,
                'prepared_sql' => $queryInfo['query'],
                'execution_time' => $queryInfo['time']
            ];
        }

        return [
            'queries' => $queries
        ];
    }
}