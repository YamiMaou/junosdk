<?php

namespace YamiTec\JunoSDK;

use YamiTec\JunoSDK\Attributes\ClientAttributes;
use YamiTec\JunoSDK\Providers\RequestProvider;

class JunoSDK
{
    private $clientAttr;
    public function __construct(ClientAttributes $clientAttr){
        $this->clientAttr = $clientAttr;
    }

    public function Authorization(Array $data = null)
    {
        $reqProvider = new RequestProvider($this->clientAttr);
        $reqProvider->setEndpoint(ENDPOINT_SANDBOX['authorization-server']);
        $server_output = $reqProvider->exec();
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
