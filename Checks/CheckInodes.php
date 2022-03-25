<?php

class CheckInodes implements CheckRessourcesInterface
{
    static public function Run(int $threshold): bool
    {

        $percentUsage = Utils::GetInodesUsage();
        $result = $percentUsage < $threshold;

        return $result;
    }
}
