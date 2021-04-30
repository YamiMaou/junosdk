<html>
<head>
<script type="text/javascript" src="https://sandbox.boletobancario.com/boletofacil/wro/direct-checkout.min.js"></script>
</head>
<body>
<script type="text/javascript">
  var checkout = new DirectCheckout('1E3CB56969F1986E2B2F960963111E530166BF8E3CBCC9F7D52A0BF22E475D34',false);
  /* Em sandbox utilizar o construtor new DirectCheckout('PUBLIC_TOKEN', false); */            
  //generateHash();
  function generateHash() {
    var cardData = {
        cardNumber: '5500497511776173',
        holderName: 'teste de nome',
        securityCode: '123',
        expirationMonth: '12',
        expirationYear: '2045'
      };


  /* isValidSecurityCode: Valida número do cartão de crédito (retorna true se for válido) */
  checkout.isValidCardNumber(cardData.cardNumber);

  /* isValidSecurityCode: Valida código de segurança do cartão de crédito (retorna true se for válido) */
  checkout.isValidSecurityCode(cardData.cardNumber, cardData.securityCode);

  /* isValidExpireDate: Valida data de expiração do cartão de crédito (retorna true se for válido) */
  checkout.isValidExpireDate(cardData.expirationMonth, cardData.expirationYear);

  /* isValidCardData: Validação dos dados do cartão de crédito(retorna true se for válido) */
  checkout.isValidCardData(cardData, function(error) {
      /* Erro - A variável error conterá o erro ocorrido durante a validação dos dados do cartão de crédito */
  });
  /* getCardType: Obtem o tipo de cartão de crédito (bandeira) */
  console.log(checkout.getCardType(cardData.cardNumber));
}
  
</script>
</body>
</html>

<?php
require 'vendor/autoload.php';
//require 'src/Constants.php';
(new \YamiTec\DotENV\DotENV(__DIR__ . '/.env'))->load();
$address = new \YamiTec\JunoSDK\Models\Address();
$address->street = "R. Serafim Ponte grande 50A";
$address->number = 56;
$address->complement = "Fundos";
$address->neighborhood = "Jd Amália";
$address->city = "São Paulo";
$address->state = "SP";
$address->postCode = "05890210";

$billing = new \YamiTec\JunoSDK\Models\Billing();
$billing->name = "TESTE 2904";
$billing->document = "44401919831";
$billing->email = "ephyllus2@gmail.com";
$billing->address = $address;

$client = new \YamiTec\JunoSDK\Attributes\ClientAttributes();
//$client::setClientId('maouyami');
$client::exec();
$juno = new \YamiTec\JunoSDK\JunoSDK($client, true);
// 'grant_type=client_credentials&clientId=' . $client::$clientId . '&clientSecret=' . $client::$clientSecret . ''
$data = [
    'grant_type' => 'client_credentials',

];
$auth_data = $juno->Authorization($data);
//var_dump($auth_data);
$charge = [
  "description" => "TESTE DE PAGAMENTO",
  "amount" => 29.99,
  "paymentTypes" => ["CREDIT_CARD"],
];
$pay = $juno->makeCharge($charge,$billing);
$creditCardDetails = [
  //"creditCardId" => 1,
  "creditCardHash" => "ca29edec-fe0d-4f29-b484-bfb38bc012e5",
  //"storeCreditCardData" => 1,
];
$pay = $juno->makePayment($pay->data['_embedded']['charges'][0]['id'], $billing, $creditCardDetails);

var_dump($pay);
//var_dump(json_decode($pay->data)->_embedded->charges[0]->id);