<?php

namespace App\Utilities\VnStat;

use JetBrains\PhpStorm\Pure;

class VnStatInterfaceTraffic {
    private VnStatInterfaceTotalTraffic $total;
    private array $fiveMinute;
    private array $hour;
    private array $day;
    private array $month;
    private array $year;
    private array $top;

    public function setTotal(VnStatInterfaceTotalTraffic $total)
    {
        $this->total = $total;
    }

    /**
     * @param VnStatInterfaceIntervalTraffic[] $fiveMinute
     * @return void
     */
    public function setFiveMinute(array $fiveMinute)
    {
        $this->fiveMinute = $fiveMinute;
    }

    /**
     * @param VnStatInterfaceIntervalTraffic[] $hour
     * @return void
     */
    public function setHour(array $hour)
    {
        $this->hour = $hour;
    }

    /**
     * @param VnStatInterfaceIntervalTraffic[] $day
     * @return void
     */
    public function setDay(array $day)
    {
        $this->day = $day;
    }

    /**
     * @param VnStatInterfaceIntervalTraffic[] $month
     * @return void
     */
    public function setMonth(array $month)
    {
        $this->month = $month;
    }

    /**
     * @param VnStatInterfaceIntervalTraffic[] $year
     * @return void
     */
    public function setYear(array $year)
    {
        $this->year = $year;
    }

    /**
     * @param VnStatInterfaceIntervalTraffic[] $top
     * @return void
     */
    public function setTop(array $top)
    {
        $this->top = $top;
    }

    #[Pure]
    public function getTotalTraffic(): int
    {
        return $this->total->sumTraffic();
    }
}
