<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');


CModule::IncludeModule("bit.geoip");
$geoip = new Bit\Geoip\Api();
echo '<pre>';
print_r($geoip->getCityByIp());
echo '</pre>';