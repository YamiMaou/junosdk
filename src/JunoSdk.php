<?php

namespace YamiTec\JunoSDK;

use YamiTec\JunoSDK\Attributes\ClientAttributes;
use YamiTec\JunoSDK\Providers\RequestProvider;

class JunoSDK
{
    private $clientAttr;
    private $authData;
    private $reqProvider;
    public function __construct(ClientAttributes $clientAttr){
        $this->clientAttr = $clientAttr;
        $this->reqProvider = new RequestProvider($this->clientAttr);
    }

    public function Authorization(array $form_data)
    {
        $this->reqProvider->setEndpoint(ENDPOINT_SANDBOX['authorization-server']);
        $this->reqProvider->setData($form_data);
        $this->reqProvider->setHeaders([
            'Content-Type: application/x-www-form-urlencoded;',
            'Authorization: Basic ' . $this->clientAttr::$base64_credentials . '',
        ]);
        $server_output = $this->reqProvider->exec();
        $json = json_decode($server_output->data);
        $status = $server_output->status;
        if ($status == '401') {
            $errorMessage = $json->error;
            $ErroMensagem = $json->message;
            $time = date('d/m/Y H:i', strtotime($json->timestamp));
            echo json_encode([
                'success' => false,
                'status' => $status,
                'time' => $time,
                'message' => $errorMessage,
                'details' => $ErroMensagem
            ]);
        } else {
            if (isset($json->error)) {
                return json_encode([
                    'success' => false,
                    'status' => $status,
                    'time' => $json->error,
                    'message' => $json->error_description,
                    'details' => ''
                ]);
            } else {
                $this->authData = json_decode($server_output->data);
                return json_encode([
                    'success' => true,
                    'status' => $status,
                    'data' => $server_output->data,
                ]);
            }
        }
    }

    public function makeCharge()
    {
        $this->reqProvider->setEndpoint(ENDPOINT_SANDBOX['charges']);
        $this->reqProvider->setHeaders([
            'X-API-Version: 2',
            'Content-Type: application/json;charset=UTF-8',
            'X-Resource-Token:'. getenv('JUNO_PRIVATE_TOKEN').'',
            'Authorization: Bearer' . $this->authData->access_token. '',
        ]);
        $data = [
            "charge" => [
                "description" => "TESTE DE PAGAMENTO",
                "amount" => 29.99,
                "paymentTypes" => ["CREDIT_CARD"],
            ],
            "billing" =>[
                "name"=> "Cliente de teste",
                "document" => "44401919831",
                "email" => "ephyllus2@gmail.com",
                "address" => [
                    "street" => "R. serafim ponte grande",
                    "number" => 65,
                    "complement" => "",
                    "neighborhood" => "jd Amalia",
                    "city" => "São Paulo",
                    "state" => "SP",
                    "postCode" => "05890210"
                ]
            ]
        ];
        $this->reqProvider->setData(json_encode($data),true);
        $server_output = $this->reqProvider->exec();
        return $server_output;
    }


    public function makePayment($charge)
    {
        $this->reqProvider->setEndpoint(ENDPOINT_SANDBOX['payment']);
        $this->reqProvider->setHeaders([
            'X-API-Version: 2',
            'Content-Type: application/json;charset=UTF-8',
            'X-Resource-Token:'. getenv('JUNO_PRIVATE_TOKEN').'',
            'Authorization: Bearer' . $this->authData->access_token. '',
        ]);
        $data = [
            "chargeId" => $charge,
            "billing" =>[
                "email" => "ephyllus2@gmail.com",
                "address" => [
                    "street" => "R. serafim ponte grande",
                    "number" => 65,
                    "complement" => "",
                    "neighborhood" => "jd Amalia",
                    "city" => "São Paulo",
                    "state" => "SP",
                    "postCode" => "05890210"
                ]
            ],
            "creditCardDetails" => [
                //"creditCardId" => 1,
                "creditCardHash" => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiaFGrkm+Oo8ZJ4H0Y4VGztBVplcW8MNR
                ↵5vgWR7IvopYu1ARoSL7i/EwvzEx/YOMRy2o4uh80eXAem+PK52K1DhbGEkRCRz4LCvrhmtQzcVWe
                ↵KOlb0O53c4nyXFEfKwhpm/5TbTgXrrWr66K1XUyCvs825PXb+PUEYGn4i0pACwlbn+KuR058S54e
                ↵nzRVbIOaL1w+YoBxGB+j+LWZJhTQAy7LIJC98RYCKGi72su1JhK6zUlviDv+ZqPOLDmyTNyTj37h
                ↵BivG9q2cXthECxdf2A5FwHyQadXyb01sBnJIIWNLpZdzQ8xbL39yjxogZUIdv2rlKS3w9wh+GPlU
                ↵44NOmQIDAQAB",
                //"storeCreditCardData" => 1,
            ]
        ];
        $this->reqProvider->setData(json_encode($data),true);
        $server_output = $this->reqProvider->exec();
        return $server_output;
    }
}
