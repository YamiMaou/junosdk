<?php
namespace YamiTec\JunoSDK\Providers;
class RequestProvider {
    private $headers = "";
    private $method = METHOD_POST;
    private $clientAttr;
    private $endpoint;
    private $data;
    public function __construct(\YamiTec\JunoSDK\Attributes\ClientAttributes $clientAttr)
    {
        $this->clientAttr = $clientAttr;
        $this->headers = [
            'Content-Type: application/x-www-form-urlencoded;',
            'Authorization: Basic ' . $this->clientAttr->base64_credentials . '',
            //'Host: api.fullprog.dev',
            'grant_type=client_credentials&clientId=' . $this->clientAttr->clientId . '&clientSecret=' . $this->clientAttr->clientSecret . ''
        ];
    }
    public function setHeaders(object $headers){
        foreach($headers as $key=>$value){
            $this->headers .= "{$key}:{$value};";
        }
        return $this;
    }
    public function setMethod($method){
        $this->method = $method;
        return $this;
    }

    public function setData(array $data){
        $this->data = $data;
        return $this;
    }

    public function setEndpoint(string $uri){
        $this->endpoint = $uri;
        return $this;
    }

    public function exec(){
        $ch = curl_init();
        $url = (getenv('JUNO_ENVIRONMENT') != 'PRODUCTION' ? URL_SANDBOX : URL_PRODUCTION).($this->endpoint ?? ENDPOINT_SANDBOX['authorization-server']);
        echo $url;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, METHOD_POST, 1);
        if($this->data) 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}