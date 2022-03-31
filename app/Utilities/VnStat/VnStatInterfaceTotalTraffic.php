<?php

namespace App\Utilities\VnStat;

class VnStatInterfaceTotalTraffic {
    private int $rx;
    private int $tx;

    public function setRx(int $rx)
    {
        $this->rx = $rx;
    }

    public function setTx(int $tx)
    {
        $this->tx = $tx;
    }

    /**
     * @return int
     */
    public function sumTraffic(): int
    {
        return $this->rx + $this->tx;
    }
}
