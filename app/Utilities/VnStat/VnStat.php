<?php

namespace App\Utilities\VnStat;

use Exception;
use JetBrains\PhpStorm\Pure;

class VnStat {

    private VnStatInterface $currentInterface;

    private float $vnStatVersion;

    private int $jsonVersion;

    private array $interfaces;

    /**
     * @param float $vnstatversion
     * @return void
     */
    public function setVnstatversion(float $vnstatversion)
    {
        $this->vnStatVersion =  $vnstatversion;
    }

    public function setJsonversion(int $jsonversion)
    {
        $this->jsonVersion = $jsonversion;
    }

    /**
     * @param VnStatInterface[] $interfaces
     * @return void
     */
    public function setInterfaces(array $interfaces)
    {
        $this->interfaces =  $interfaces;
    }

    /**
     * @throws Exception
     */
    public function interface(string $interface): static
    {
        foreach ($this->interfaces as $interfaceIterator) {
            if($interfaceIterator->getName() === $interface) {
                $this->currentInterface = $interfaceIterator;
                return $this;
            }
        }
        throw new Exception('No Interface with that name found');
    }

    #[Pure]
    public function getTotalTraffic($formatOption = 'hr'): float
    {
        $traffic = $this->currentInterface->getTotalTraffic();
        if($formatOption == 'hr')
            return (float)$traffic / 1073741824;

        return $traffic;
    }
}
