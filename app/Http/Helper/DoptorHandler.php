<?php

namespace App\Http\Helper;

class DoptorHandler
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }
    public function getBearerToken() {

        $token = $this->token;

        return $token;
    }


}

