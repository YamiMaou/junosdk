# YamiTec JunoSDK

# Instalação

para iniciar o projeto basta instalar apartir do composer  com o comando ```composer require yamitec/juno-sdk```

# Exemplo 



Para efetuar pagamentos com a JunoSDK basta instanciar a classe ```\YamiTec\JunoSDK```.
o seu arquivo .env deve ter os seguintes parametros:
```
JUNO_ENVIRONMENT=[SANDBOX OR PRODUCTION]
JUNO_CLIENT_ID=[CLIENT-ID]
JUNO_CLIENT_SECRET=[CLIENT-SECRET]
JUNO_PRIVATE_TOKEN=[PRIVATE-TOKEN]
```
## Authenticação

a chamada deve-se da seguinte forma:

```
<?php

$client = new \YamiTec\JunoSDK\Attributes\ClientAttributes();
$client::exec();
$juno = new \YamiTec\JunoSDK\JunoSDK($client);
$auth_data = json_decode($juno->Authorization($data));
if($auth_data->success == false){
  return json_encode(['success' => false, 'message' => $auth_data->message]);
}
```
## Criar Pagamento

```
$chargeData = [
    "description" => "Novo Lucro Plano: {$plan->name}",
    "amount" => $plan->price,
    "paymentTypes" => ["CREDIT_CARD"],
    ];
$charge = $juno->makeCharge($chargeData, $billing);
print_r($charge);

```

## Gerar Token de Cartão

```
[JS]
if(env('JUNO_ENVIRONMENT') == "SANDBOX") // verificar se é sandbox ou production
    script type="text/javascript" src="https://sandbox.boletobancario.com/boletofacil/wro/direct-checkout.min.js"></script>
else
    <script type="text/javascript" src="https://www.boletobancario.com/boletofacil/wro/direct-checkout.min.js"></script>
endif
<script>
 var checkout = new DirectCheckout("{{env('JUNO_PUBLIC_TOKEN')}}", false);
    function generateHash() {
        var cardData = {
            cardNumber: $('input[name="card_number"]').val().replace(/ /g, ""),
            holderName: $('input[name="card_name"]').val(),
            securityCode: $('input[name="card_cvv"]').val(),
            expirationMonth: $('input[name="card_vality"]').val().split('/')[0],
            expirationYear: $('input[name="card_vality"]').val().split('/')[1]
        };
        /* isValidCardNumber: Valida número do cartão de crédito (retorna true se for válido) */
        if (!checkout.isValidCardNumber(cardData.cardNumber)) {
            return {
                success: false,
                error: "Número de Cartão Inválido!",
                data: "Número de Cartão Inválido!",
            }
        }

        /* isValidSecurityCode: Valida código de segurança do cartão de crédito (retorna true se for válido) */
        if (!checkout.isValidSecurityCode(cardData.cardNumber, cardData.securityCode)) {
            return {
                success: false,
                error: "CVV Inválido!",
                data: "CVV Inválido!",
            }
        }
        /* isValidExpireDate: Valida data de expiração do cartão de crédito (retorna true se for válido) */
        if (!checkout.isValidExpireDate(cardData.expirationMonth, cardData.expirationYear)) {
            return {
                success: false,
                error: "Data de validade Inválida!",
                data: "Data de validade Inválida!",
            }
        }
        /* isValidCardData: Validação dos dados do cartão de crédito(retorna true se for válido) */
        checkout.isValidCardData(cardData, function(error) {
            return {
                success: false,
                error: error,
                data: error,
            }
            /* Erro - A variável error conterá o erro ocorrido durante a validação dos dados do cartão de crédito */
        });
        checkout.getCardHash(cardData, function(cardHash) {
                /* Sucesso - A variável cardHash conterá o hash do cartão de crédito */
                $('input[name="creditCardHash"]').val(cardHash); // PREENCHE O INPUT com nome creditCardHash com o token do cartão
            }, function(error) {
                /* Erro - A variável error conterá o erro ocorrido ao obter o hash */
                console.error(error)
        });
        return {
            success: true,
            data: checkout,
        }
    }
    </script>

```
## Efetuar Pagamento

```
$creditCardDetails = [
    "creditCardHash" => $_POST['creditCardHash'],
];
$pay = $juno->makePayment($pay->data['_embedded']['charges'][0]['id'], $billing, $creditCardDetails);
print_r($pay);
```
# Retornos

(Em DEV)

# Agradecimento

Equipe www.yamitec.com