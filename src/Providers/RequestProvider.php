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
        //var_dump($this->headers);
    }
    public function setHeaders(array $headers){
        $this->headers = $headers;
        /*foreach($headers as $key=>$value){
            $this->headers .= "{$key}:{$value};";
        }*/
        return $this;
    }
    public function setMethod($method){
        $this->method = $method;
        return $this;
    }

    public function setData($data, $type = null){
        //print_r($data);
        $this->data = $type == null ? http_build_query($data) : $data; 
        return $this;
    }

    public function setEndpoint(string $uri){
        $this->endpoint = $uri;
        return $this;
    }

    public function exec(){
        $ch = curl_init();
        $url = (getenv('JUNO_ENVIRONMENT') != 'PRODUCTION' ? URL_SANDBOX : URL_PRODUCTION).($this->endpoint ?? ENDPOINT_SANDBOX['authorization-server']);
        //echo $url;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, METHOD_POST, 1);
        if($this->data) 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_HEADER  , true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return (object)["status" => $status, "data" => json_decode($response,true)];
    }
}