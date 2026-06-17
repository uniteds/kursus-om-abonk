<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class GoogleOAuth extends BaseConfig
{
    public string $clientId     = '';
    public string $clientSecret = '';
    public string $redirectUri  = '';

    public function __construct()
    {
        parent::__construct();

        $this->clientId     = env('google.client_id', $this->clientId);
        $this->clientSecret = env('google.client_secret', $this->clientSecret);
        $this->redirectUri  = env('google.redirect_uri', $this->redirectUri);
    }
}
