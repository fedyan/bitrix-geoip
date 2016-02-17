<?php
$inegrationModuleName = "itsfera.integration";
if ( !CModule::IncludeModule( $inegrationModuleName ) ){
    $GLOBALS['APPLICATION']->ThrowException('Установите модуль '.$inegrationModuleName);
}