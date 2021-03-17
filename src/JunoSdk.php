<?php

namespace YamiTec\JunoSDK;

use YamiTec\JunoSDK\Attributes\ClientAttributes;

class JunoSDK
{
    private $clientAttr;
    public function __construct(ClientAttributes $clientAttr){
        $this->clientAttr = $clientAttr;
    }

    public function Authorization(Array $data )
    {
        //$base64 = base64_encode($this->clientAttr->base64_credentials);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.juno.com.br/authorization-server/oauth/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = [
            'Content-Type: application/x-www-form-urlencoded;',
            'Authorization: Basic ' . $this->clientAttr->base64_credentials . '',
            'Host: api.fullprog.dev',
            'grant_type=client_credentials&clientId=' . $this->clientAttr->clientId . '&clientSecret=' . $this->clientAttr->clientSecret . ''
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($server_output);
        $status = $json->status;
        if ($status == '401') {
            $errorMessage = $json->error;
            $ErroMensagem = $json->message;
            $time = date('d/m/Y H:i', strtotime($json->timestamp));
            echo 'Em ' . $time . ' ocorreu um erro. <br>Detalhes: ' . $errorMessage . ' Complemento: ' . $ErroMensagem;
        } else {
            if (isset($json->error)) {
                echo $json->error . ' informa: ' . $json->error_description;
            } else {
                print  $server_output;
            }
        }
    }
}
