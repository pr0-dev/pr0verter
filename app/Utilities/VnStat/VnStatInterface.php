<?php

namespace App\Utilities\VnStat;

use Carbon\Carbon;
use JetBrains\PhpStorm\Pure;
use stdClass;

class VnStatInterface {
    /** @var string  */
    private string $name;
    /** @var string  */
    private string $alias;
    /** @var Carbon  */
    private Carbon $created;
    /** @var Carbon  */
    private Carbon $updated;
    /** @var VnStatInterfaceTraffic  */
    private VnStatInterfaceTraffic $traffic;

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $alias
     * @return void
     */
    public function setAlias(string $alias)
    {
        $this->alias = $alias;
    }


    /**
     * @param stdClass $created
     * @return void
     */
    public function setCreated(stdClass $created)
    {
        $this->created = Carbon::createFromDate($created->date->year, $created->date->month, $created->date->day);
    }

    /**
     * @param stdClass $updated
     * @return void
     */
    public function setUpdated(stdClass $updated)
    {
        $this->updated = Carbon::createFromDate($updated->date->year, $updated->date->month, $updated->date->day)->setTime($updated->time->hour, $updated->time->minute);
    }

    /**
     * @param VnStatInterfaceTraffic $traffic
     * @return void
     */
    public function setTraffic(VnStatInterfaceTraffic $traffic)
    {
        $this->traffic = $traffic;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    #[Pure]
    public function getTotalTraffic(): int
    {
        return $this->traffic->getTotalTraffic();
    }
}
