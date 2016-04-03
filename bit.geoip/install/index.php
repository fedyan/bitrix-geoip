<?php
IncludeModuleLangFile(__FILE__);
Class bit_geoip extends CModule
{
    const BASE_FILE_NAME = "geo_city_2013_09.sql";
    var $MODULE_ID = "bit.geoip";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    function __construct()
    {
        global $arModuleVersion;
        include("version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->PARTNER_NAME = GetMessage("PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("PARTNER_URI");
        $this->MODULE_NAME = GetMessage("GEOIP_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("MODULE_DESC");

        $this->sModuleLocation = strpos(__FILE__,"/local/modules/")!==false?"/local/modules/":"/bitrix/modules/";
        $this->sModuleInstallDir = $_SERVER["DOCUMENT_ROOT"].$this->sModuleLocation.$this->MODULE_ID."/install/";


    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        try{

            $this->installTables();
            
        } catch ( \Bitrix\Main\SystemException $e) {

            echo CAdminMessage::ShowMessage(Array(
                "TYPE" => "ERROR",
                "MESSAGE" => GetMessage("MOD_INST_ERR"),
                "DETAILS" => $e->getMessage(),
                "HTML" => true,
            ));

            //die();
        }
        echo CAdminMessage::ShowNote("Модуль установлен");
    }

    function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);
        echo CAdminMessage::ShowNote("Модуль успешно удален из системы");
    }

    function installTables()
    {

        // считываем файл
        $handle = fopen($this->sModuleInstallDir.self::BASE_FILE_NAME, "r");
        if ($handle) {
            $c = 0;
            $sql = "";
            while (($line = fgets($handle, 4096)) !== false) {

                $line = trim($line);
                //пропускаем пустые строки и комментарии
                $sLineStart = substr($line,0,2);
                if (empty($line) || in_array($sLineStart,array("--","/*")) ) continue;
                $sql.= $line;
                //формируем запросы из строк до точки с запятой
                if ( substr(trim($line),-1)==';'){
                    $res = $GLOBALS['DB']->Query($sql, false, __FILE__.__LINE__);
                    //echo ++$c.' - '.$res.'<br>';
                    $sql = "";
                }
            }
            //if (!feof($handle)) {
            //  echo "Error: unexpected fgets() fail\n";
            //}
            fclose($handle);
        }

    }

}

?>