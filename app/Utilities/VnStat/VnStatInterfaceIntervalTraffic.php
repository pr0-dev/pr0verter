<?php

namespace App\Utilities\VnStat;

use Carbon\Carbon;
use stdClass;

class VnStatInterfaceIntervalTraffic
{
    private int $id;
    private Carbon $date;
    private Carbon $time;
    private int $rx;
    private int $tx;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setDate(stdClass $date) {
            $this->date = Carbon::createFromDate($date->year, property_exists($date, 'month') ? $date->month : null, property_exists($date, 'day') ? $date->day : null);
    }

    public function setTime(stdClass $time)
    {
        $this->time = Carbon::createFromTime($time->hour, $time->minute);
    }

    public function setRx(int $rx)
    {
        $this->rx = $rx;
    }

    public function setTx(int $tx)
    {
        $this->tx = $tx;
    }
}
