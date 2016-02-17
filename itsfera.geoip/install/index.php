<?php
IncludeModuleLangFile(__FILE__);
Class itsfera_geoip extends CModule
{
    var $MODULE_ID = "itsfera.geoip";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    function __construct()
    {
        include("version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->PARTNER_NAME = GetMessage("PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("PARTNER_URI");
        $this->MODULE_NAME = GetMessage("GEOIP_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("MODULE_DESC");

    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        echo CAdminMessage::ShowNote("Модуль установлен");
    }

    function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);
        echo CAdminMessage::ShowNote("Модуль успешно удален из системы");
    }

}

?>