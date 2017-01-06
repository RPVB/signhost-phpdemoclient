<?php

namespace Evidos\SignHostAPIClientRest;

class AccessToken
{
    public $AccessToken;
    public $ExpiresIn;
    public $Username;

    function __construct($accessToken, $expiresIn, $username) {
        $this->AccessToken = $accessToken;
        $this->ExpiresIn = $expiresIn;
        $this->Username = $username;
    }
}