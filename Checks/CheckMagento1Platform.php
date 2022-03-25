<?php

class CheckMagento1Platform extends CheckPlatform
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
