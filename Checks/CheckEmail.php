<?php

class CheckEmail implements CheckInterface
{
    static public function Run(string $email = null): bool
    {
        if ($email == null) {
            return false;
        }

        $subject = "Control-Center PHP Mailtest";
        $txt = date("Y-m-d H:i");
        $headers = "From: monitoring@" . Utils::GetDomain();

        $out = mail($email, $subject, $txt, $headers);

        return $out;
    }
}
