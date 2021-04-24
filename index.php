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
require 'src/Constants.php';
(new \YamiTec\DotENV\DotENV(__DIR__ . '/.env'))->load();
$client = new \YamiTec\JunoSDK\Attributes\ClientAttributes();
//$client::setClientId('maouyami');
$client::exec();
$juno = new \YamiTec\JunoSDK\JunoSDK($client);
// 'grant_type=client_credentials&clientId=' . $client::$clientId . '&clientSecret=' . $client::$clientSecret . ''
$data = [
    'grant_type' => 'client_credentials',

];
$auth_data = json_decode($juno->Authorization($data));
//print_r(json_decode($auth_data->data));
$pay = $juno->makeCharge();
$pay = $juno->makePayment(json_decode($pay->data)->_embedded->charges[0]->id);

var_dump($pay);
//var_dump(json_decode($pay->data)->_embedded->charges[0]->id);