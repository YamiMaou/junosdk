<?php

namespace YamiTec\JunoSDK;

use YamiTec\JunoSDK\Attributes\ClientAttributes;
use YamiTec\JunoSDK\Models\Billing;
use YamiTec\JunoSDK\Providers\RequestProvider;

class JunoSDK
{
    private $clientAttr;
    private $authData;
    private $reqProvider;
    private $sandbox;
    public function __construct(ClientAttributes $clientAttr, $sandbox = false){
        $this->sandbox = $sandbox;
        $this->clientAttr = $clientAttr;
        $this->reqProvider = new RequestProvider($this->clientAttr);
    }

    public function Authorization(array $form_data)
    {
        $this->reqProvider->setEndpoint($this->sandbox ? ENDPOINT_SANDBOX['authorization-server'] : ENDPOINT_PRODUCTION['authorization-server']);
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

    public function makeCharge(array $charge, Billing $billing)
    {
        $this->reqProvider->setEndpoint($this->sandbox ? ENDPOINT_SANDBOX['charges'] : ENDPOINT_PRODUCTION['charges']);
        $this->reqProvider->setHeaders([
            'X-API-Version: 2',
            'Content-Type: application/json;charset=UTF-8',
            'X-Resource-Token:'. getenv('JUNO_PRIVATE_TOKEN').'',
            'Authorization: Bearer' . $this->authData->access_token. '',
        ]);
        $data = [
            "charge" => $charge,
            "billing" => $billing
        ];
        $this->reqProvider->setData(json_encode($data),true);
        $server_output = $this->reqProvider->exec();
        return $server_output;
    }


    public function makePayment($charge, Billing $billing, array $creditCardDetails)
    {
        $this->reqProvider->setEndpoint($this->sandbox ? ENDPOINT_SANDBOX['payment'] : ENDPOINT_PRODUCTION['payment']);
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
            "creditCardDetails" =>$creditCardDetails
        ];
        $this->reqProvider->setData(json_encode($data),true);
        $server_output = $this->reqProvider->exec();
        return $server_output;
    }
}
