<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

if( !Loader::includeModule($moduleID) ) {
	$fatalErrors = Loc::getMessage("MODULE_INCLUDE_ERROR") . "<br>";
}

$MODULE_ACCESS = \Iplogic\Actions\Access::getGroupRight("module");

$fatalErrors = "";

$request = Application::getInstance()->getContext()->getRequest();

CJSCore::Init(array("jquery"));

