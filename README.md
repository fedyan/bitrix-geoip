# bitrix free geoip 
Простой Bitrix модуль для определение страны и города по IP адресу

При установке модуля в базу добавляются таблицы соответствия IP-адресов городам. (База взята здесь https://habrahabr.ru/post/108541/)
База весит 53 Мб, так что установка длится около минуты.

###Установка
Скопируйте модуль в папку /local/modules или /bitrix/modules
Чтобы получилось /local/modules/bit.geoip
После установки, никаких кнопок или настроек в битриксе не появится,
но вам станет доступен API модуля.

###Пример использования:

```
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

CModule::IncludeModule("bit.geoip");
$geoip = new Bit\Geoip\Api();
echo '<pre>';
print_r($geoip->getCityByIp());
echo '</pre>';
?>
```

Результат:
```
Array
(
    [city_name] => Пушкино
    [country_name] => Российская Федерация
    [ip] => 89.222.161.79
)
```

По полученному имени города можно определить ID местоположения из модуля магазина.
