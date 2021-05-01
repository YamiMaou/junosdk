<?php
    // URL PRODUCTION
    define('URL_PRODUCTION', "https://api.juno.com.br");
    // URL SANDBOX
    define('URL_SANDBOX', "https://sandbox.boletobancario.com");

    /**
     * Define Methods
     */
    define('METHOD_POST', CURLOPT_POST);
    /**
     * DEFINE ENDPOINTS
     */
    define('ENDPOINT_PRODUCTION', [
        "authorization-server" => "/authorization-server/oauth/token",
        "charges" => "/charges",
        "payment" => "/payments",
    ]);
    define('ENDPOINT_SANDBOX', [
        "authorization-server" => "/authorization-server/oauth/token",
        "charges" => "/api-integration/charges",
        "payment" => "/api-integration/payments",
    ]);
