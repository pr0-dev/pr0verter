<?php

namespace App\Http\Controllers;

use App\Models\ConvertStatistic;
use App\Utilities\VnStat\VnStat;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * @throws \JsonMapper_Exception
     */
    public function systemStats(): JsonResponse
    {
        $spacePercentageUsed = 100 - disk_free_space('/') / disk_total_space('/') * 100;
        $absoluteMemory = array_merge(array_filter(explode(" ", explode("\n", trim(shell_exec('free')))[1])));
        $memoryPercentageUsed = $absoluteMemory[2] / $absoluteMemory[1] * 100;
        $cpuCount = substr_count((string)@file_get_contents('/proc/cpuinfo'),"\nprocessor")+1;
        $cpuPercentageUsed = sys_getloadavg()[0]/$cpuCount*100;

        $totalConvertCount = ConvertStatistic::get()->count();
        $totalConvertToday = ConvertStatistic::where('time', '>', Carbon::today())->count();
        $averageConvertTime = ConvertStatistic::whereSucceeded(true)->average('convertTime');

        $netWorkData = json_decode(shell_exec('vnstat --json'));
        $mapper = new \JsonMapper();
        $vnStat = $mapper->map($netWorkData, new VnStat());

        return response()->json([
            'memoryUsed' => $memoryPercentageUsed,
            'spaceUsed' => $spacePercentageUsed,
            'cpuUsed' => $cpuPercentageUsed,
            'totalConvertCount' => $totalConvertCount,
            'convertToday' => $totalConvertToday,
            'avgConvertTime' => $averageConvertTime,
            'dataTransferred' => $vnStat->interface(config('network.interface'))->getTotalTraffic()
        ]);
    }
}
