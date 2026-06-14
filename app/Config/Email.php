<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = '';
    public string $fromName   = 'Om Abonk LMS';

    public string $protocol    = 'smtp';
    public string $SMTPHost    = 'smtp.gmail.com';
    public int    $SMTPPort    = 587;
    public string $SMTPUser    = '';
    public string $SMTPPass    = '';
    public string $SMTPCrypto  = 'tls';
    public int    $SMTPTimeout = 30;
    public bool   $SMTPKeepAlive = false;
    public string $SMTPAuthMethod = 'login';

    public string $mailType  = 'html';
    public string $charset   = 'UTF-8';
    public bool   $wordWrap  = true;
    public int    $wrapChars = 76;
    public bool   $validate  = false;
    public int    $priority  = 3;

    public string $CRLF  = "\r\n";
    public string $newline = "\r\n";

    public bool   $BCCBatchMode = false;
    public int    $BCCBatchSize = 200;
    public bool   $DSN = false;

    public function __construct()
    {
        parent::__construct();

        // Load dari .env atau fallback ke placeholder
        $this->fromEmail = env('email.fromEmail', $this->fromEmail);
        $this->SMTPUser  = env('email.SMTPUser', $this->SMTPUser);
        $this->SMTPPass  = env('email.SMTPPass', $this->SMTPPass);
    }
}
