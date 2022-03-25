<?php

class CheckDiskUsage implements CheckRessourcesInterface
{
    static public function Run(int $threshold): bool
    {

        $percentUsage = Utils::GetDiskUsage();
        $result = $percentUsage < $threshold;

        return $result;
    }
}
