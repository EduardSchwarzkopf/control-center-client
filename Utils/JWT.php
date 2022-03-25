<?php

class JWT
{

    public $payload = null;
    public $isValid = false;
    public $token = null;

    public function __construct(
        string $token
    ) {
        $this->validateToken($token);
    }


    public static function base64url_encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64url_decode(string $data): string
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    private function validateToken(string $token): JWT
    {

        if (empty($token)) {
            return $this;
        }

        $keyPublicPath = "storage/public.key";

        $part = explode(".", $token);

        $header = $part[0];
        $tokenPayload = $part[1];
        $signature = $part[2];

        $payload = JWT::base64url_decode($tokenPayload);
        $payload = json_decode($payload, true);

        // check if is expired here
        $dt = new DateTimeImmutable;
        $now = $dt->getTimestamp();

        $exp = $payload["exp"];

        if ($now > $exp) {
            // Token expired!
            return $this;
        }

        $encodedData = $signature;

        // Open public path and return this in string format
        $fp = fopen($keyPublicPath, "r");
        $chavePublicaString = fread($fp, 8192);
        fclose($fp);

        // Open public key string and return 'resourse'
        $resPublicKey = openssl_get_publickey($chavePublicaString);

        // Decode base64 to reaveal dots (Dots are used in JWT syntaxe)
        $encodedData = JWT::base64url_decode($encodedData);

        $rawEncodedData = $encodedData;

        $partialDecodedData = '';
        $decodedData = '';
        $split2 = explode('.', $rawEncodedData);
        foreach ($split2 as $part2) {
            $part2 = JWT::base64url_decode($part2);

            openssl_public_decrypt($part2, $partialDecodedData, $resPublicKey);
            $decodedData .= $partialDecodedData;
        }

        if ($header . "." . $tokenPayload === $decodedData) {
            // valid token
            $this->isValid = true;
            $this->payload = $payload;
            $this->token = $token;
        }

        return $this;
    }




    /**
     * get access token from header
     * */
    static public function getBearerToken(): string
    {

        function getAuthorizationHeader(): string
        {
            $headers = '';
            if (isset($_SERVER['Authorization'])) {
                $headers = trim($_SERVER["Authorization"]);
            } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            } elseif (function_exists('apache_request_headers')) {
                $requestHeaders = apache_request_headers();
                // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                //print_r($requestHeaders);
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
        }


        $headers = getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return '';
    }
}
