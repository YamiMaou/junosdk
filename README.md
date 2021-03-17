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
a chamada deve-se da seguinte forma:

```
<?php
$client = new \YamiTec\JunoSDK\Attributes\ClientAttributes();
$client::exec();
$juno = new \YamiTec\JunoSDK\JunoSDK($client);
print_r($juno->Authorization());
```
# Retornos

(Em DEV)

# Agradecimento

Equipe www.yamitec.com