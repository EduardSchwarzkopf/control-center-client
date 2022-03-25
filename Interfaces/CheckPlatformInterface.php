<?php

interface CheckPlatformInterface
{

    static public function Run(Platform $platform): bool;
}
