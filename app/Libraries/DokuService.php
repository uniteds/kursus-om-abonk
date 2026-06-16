<?php

namespace App\Libraries;

class DokuService
{
    private string $clientId;
    private string $secretKey;
    private string $apiUrl;
    private string $apiPath = '/checkout/v1/payment';

    public function __construct()
    {
        $this->clientId  = env('DOKU_CLIENT_ID') ?: '';
        $this->secretKey = env('DOKU_SECRET_KEY') ?: '';
        $this->apiUrl    = (env('DOKU_ENV') === 'production')
            ? 'https://api.doku.com'
            : 'https://api-sandbox.doku.com';
    }

    public function isConfigured(): bool
    {
        return !empty($this->clientId) && !empty($this->secretKey);
    }

    public function getJsUrl(): string
    {
        return (env('DOKU_ENV') === 'production')
            ? 'https://jokul.doku.com/jokul-checkout-js/v1/jokul-checkout-1.0.0.js'
            : 'https://sandbox.doku.com/jokul-checkout-js/v1/jokul-checkout-1.0.0.js';
    }

    public function generateInvoiceNumber(string $prefix = 'INV'): string
    {
        return $prefix . '-' . date('Ymd') . '-' . uniqid();
    }

    public function generateSignature(
        string $requestId,
        string $requestTimestamp,
        string $requestTarget,
        ?string $digest = null
    ): string {
        $component  = "Client-Id:{$this->clientId}\n";
        $component .= "Request-Id:{$requestId}\n";
        $component .= "Request-Timestamp:{$requestTimestamp}\n";
        $component .= "Request-Target:{$requestTarget}";
        if ($digest) {
            $component .= "\nDigest:{$digest}";
        }

        $signature = base64_encode(
            hash_hmac('sha256', $component, $this->secretKey, true)
        );

        return "HMACSHA256={$signature}";
    }

    public function generateDigest(array $body): string
    {
        return base64_encode(
            hash('sha256', json_encode($body), true)
        );
    }

    public function generateRequestId(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function createPayment(array $orderData): array
    {
        $requestId       = $this->generateRequestId();
        $requestTimestamp = gmdate('Y-m-d\TH:i:s\Z');
        $digest          = $this->generateDigest($orderData);
        $signature       = $this->generateSignature($requestId, $requestTimestamp, $this->apiPath, $digest);

        $headers = [
            'Client-Id'       => $this->clientId,
            'Request-Id'      => $requestId,
            'Request-Timestamp' => $requestTimestamp,
            'Signature'       => $signature,
            'Content-Type'    => 'application/json',
        ];

        $url  = $this->apiUrl . $this->apiPath;
        $json = json_encode($orderData);

        log_message('info', "[DOKU] POST {$url}");
        log_message('info', "[DOKU] Request Body: {$json}");
        log_message('info', "[DOKU] Client-Id: {$this->clientId}");
        log_message('info', "[DOKU] Request-Id: {$requestId}");
        log_message('info', "[DOKU] Request-Timestamp: {$requestTimestamp}");

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $json,
            CURLOPT_HTTPHEADER     => array_map(
                fn($k, $v) => "{$k}: {$v}",
                array_keys($headers),
                array_values($headers)
            ),
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        log_message('info', "[DOKU] Response HTTP {$httpCode}: {$response}");

        if ($error) {
            return [
                'success' => false,
                'error'   => "cURL Error: {$error}",
            ];
        }

        $body = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success'    => true,
                'request_id' => $requestId,
                'response'   => $body['response'] ?? $body,
            ];
        }

        $errorMsg = $body['error_messages'] ?? $body['message'] ?? $body['errors'] ?? $response;
        if (is_array($errorMsg)) {
            $errorMsg = json_encode($errorMsg);
        }

        return [
            'success'   => false,
            'http_code' => $httpCode,
            'error'     => $errorMsg,
            'raw'       => $body,
        ];
    }

    public function verifyNotificationSignature(array $headers, string $requestBody): bool
    {
        $clientId      = $headers['Client-Id'] ?? '';
        $requestId     = $headers['Request-Id'] ?? '';
        $requestTimestamp = $headers['Request-Timestamp'] ?? '';
        $receivedSig   = str_replace('HMACSHA256=', '', $headers['Signature'] ?? '');

        if (empty($clientId) || empty($requestId) || empty($requestTimestamp) || empty($receivedSig)) {
            return false;
        }

        $requestTarget = '/notification';
        $digest = base64_encode(hash('sha256', $requestBody, true));

        $component  = "Client-Id:{$clientId}\n";
        $component .= "Request-Id:{$requestId}\n";
        $component .= "Request-Timestamp:{$requestTimestamp}\n";
        $component .= "Request-Target:{$requestTarget}\n";
        $component .= "Digest:{$digest}";

        $expectedSig = base64_encode(
            hash_hmac('sha256', $component, $this->secretKey, true)
        );

        return hash_equals($expectedSig, $receivedSig);
    }
}
