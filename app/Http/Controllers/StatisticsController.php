<?php

namespace App\Http\Controllers;

use App\Utilities\VnStat\VnStat;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function systemStats()
    {
        $spacePercentageUsed = 100 - disk_free_space('/') / disk_total_space('/') * 100;
        $absoluteMemory = array_merge(array_filter(explode(" ", explode("\n", trim(shell_exec('free')))[1])));
        $memoryPercentageUsed = $absoluteMemory[2] / $absoluteMemory[1] * 100;
        var_dump($spacePercentageUsed);
        var_dump($memoryPercentageUsed);
        $cpuCount = substr_count((string)@file_get_contents('/proc/cpuinfo'),"\nprocessor")+1;
        $cpuPercentageUsed = sys_getloadavg()[0]/$cpuCount*100;
        var_dump($cpuPercentageUsed);
        $data = json_decode(shell_exec('vnstat --json'));
        //var_dump($data);
        $mapper = new \JsonMapper();
        $vnStat = $mapper->map($data, new VnStat());
        //return response()->json($data);
        var_dump($vnStat->interface('wlp0s20f3')->getTotalTraffic());
        die();
    }
}
