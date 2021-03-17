<?php
require 'vendor/autoload.php';
require 'src/Constants.php';
(new \YamiTec\DotENV\DotENV(__DIR__ . '/.env'))->load();
$client = new \YamiTec\JunoSDK\Attributes\ClientAttributes();
$client::setClientId('maouyami');
$client::exec();
$juno = new \YamiTec\JunoSDK\JunoSDK($client);
print_r($juno->Authorization());
