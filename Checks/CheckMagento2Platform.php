<?php

class CheckMagento2Platform extends CheckPlatformBase
{
    static public function Run(Platform $platform): bool
    {

        $parentCheck = parent::Run($platform);

        // TODO:
        // - Sending E-Mail
        // - Password Reset E-Mail

        $result = $parentCheck;

        return $result;
    }
}
