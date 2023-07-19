<?php

namespace Kodeas\Monitor\Transformers;

use Illuminate\Http\Request;

class ServerTransformer
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run(): array
    {
        $startCpuUsage = $this->request->attributes->get('start_cpu_usage');

        return [
            'peak_memory_usage' => $this->formatBytes(memory_get_peak_usage()),
            'environment' => config('monitor.environment'),
            'cpu_usage_in_ms' => $this->getCpuUsage($startCpuUsage),
        ];
    }

    private function getCpuUsage($startCpuUsage): float
    {
        $endUsage = getrusage();

        $userCpuTimeUsed =
            ($endUsage["ru_utime.tv_sec"] * 1e6 + $endUsage["ru_utime.tv_usec"]) -
            ($startCpuUsage["ru_utime.tv_sec"] * 1e6 + $startCpuUsage["ru_utime.tv_usec"]);

        $systemCpuTimeUsed =
            ($endUsage["ru_stime.tv_sec"] * 1e6 + $endUsage["ru_stime.tv_usec"]) -
            ($startCpuUsage["ru_stime.tv_sec"] * 1e6 + $startCpuUsage["ru_stime.tv_usec"]);

        return round(($userCpuTimeUsed + $systemCpuTimeUsed) / 1000, 2);
    }

    public function formatBytes($size, $precision = 2): string
    {
        if ($size === 0 || $size === null) {
            return "0B";
        }

        $sign = $size < 0 ? '-' : '';
        $size = abs($size);

        $base = log($size) / log(1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
        return $sign . round(pow(1024, $base - floor($base)), $precision) . $suffixes[(int) floor($base)];
    }

}