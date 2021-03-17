<?php
namespace YamiTec\JunoSDK\Attributes;

class ClientAttributes {
    public static $clientId;
    public static $clientSecret;
    public static $base64_credentials;

    public static function setClientId($clientId){
        self::$clientId = $clientId;
        return self::class;
    }

    public static function setClientSecret($clientSecret){
        self::$clientSecret = $clientSecret;
        return self::class;
    }
    
    public static function exec(){
        self::$clientId = self::$clientId ?? getenv('JUNO_CLIENT_ID');
        self::$clientSecret = self::$clientSecret ?? getenv('JUNO_CLIENT_SECRET');
        self::$base64_credentials = base64_encode(self::$clientId.":".self::$clientSecret);
        return self::class;
    }
}