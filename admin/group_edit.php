<?

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Iplogic\Actions\GroupTable;

$moduleID = 'iplogic.actions';
define("ADMIN_MODULE_NAME", $moduleID);
$baseFolder = realpath(__DIR__ . "/../../..");
include($baseFolder . "/modules/" . $moduleID . "/prolog.php");

if( $MODULE_ACCESS < "W" ) {
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
	CAdminMessage::ShowMessage(Loc::getMessage("ACCESS_DENIED"));
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
	die();
}

$ID = $request->get("ID");
if( $ID > 0 ) {
	$arFields = GroupTable::getRowById($ID, true);
	if( !$arFields ) {
		$fatalErrors = Loc::getMessage("WRONG_PARAMETERS") . "<br>";
	}
}

CJSCore::Init(["jquery"]);

$LID = Iplogic\Actions\Admin\TableForm::getLID();

$POST_REQUEST = false;
if(
	$request->isPost()
	&& ($request->get("save") != "" || $request->get("apply") != "")
	&& check_bitrix_sessid()
	&& $fatalErrors == ""
) {
	$POST_REQUEST = true;
}

$aTabs = [
	["DIV"   => "edit1",
	 "TAB"   => Loc::getMessage("IPL_ADMIN_GENERAL"),
	 "ICON"  => "main_user_edit",
	 "TITLE" => Loc::getMessage("IPL_ADMIN_GENERAL_TITLE"),
	],
];
$arOpts = [

	/* GENERAL */
	"ACTIVE"      => [
		"TAB"     => "edit1",
		"TYPE"    => "checkbox",
		"DEFAULT" => 'Y',
		"NAME"    => Loc::getMessage("IPL_ADMIN_ACTIVE"),
	],
	"NAME"        => [
		"TAB"      => "edit1",
		"TYPE"     => "text",
		"DEFAULT"  => "",
		"NAME"     => Loc::getMessage("IPL_ADMIN_NAME"),
		"REQURIED" => "Y",
	],
	"DESCRIPTION" => [
		"TAB"      => "edit1",
		"TYPE"     => "text",
		"DEFAULT"  => "",
		"NAME"     => Loc::getMessage("IPL_ADMIN_DESCRIPTION"),
		"REQURIED" => "N",
	],
	"SORT"        => [
		"TAB"     => "edit1",
		"TYPE"    => "text",
		"DEFAULT" => 100,
		"NAME"    => Loc::getMessage("IPL_ADMIN_SORT"),
	],

];

$aMenu = [
	[
		"TEXT"  => Loc::getMessage("IPL_ADMIN_LIST"),
		"TITLE" => Loc::getMessage("IPL_ADMIN_LIST_TITLE"),
		"LINK"  => "iplogic_actions_groups.php?lang=" . LANG,
		"ICON"  => "btn_list",
	],
];


$Messages = [
	"NOT_CHOSEN" => Loc::getMessage("NOT_CHOSEN"),
	"ALL"        => Loc::getMessage("ALL"),
];


$adminControl = new \Iplogic\Actions\Admin\TableForm($moduleID);
$adminControl->POST_RIGHT = $MODULE_ACCESS;
$adminControl->arTabs = $aTabs;
$adminControl->arOpts = $arOpts;
$adminControl->Mess = $Messages;
$adminControl->arContextMenu = $aMenu;
$adminControl->initDetailPage();

$adminControl->setFields($arFields);


// save data
if( $POST_REQUEST ) {

	$adminControl->getRequestData();

	if( !count($adminControl->errors) ) {
		$arFields = $adminControl->extractQueryValues();
		if( $ID > 0 ) {
			$res = GroupTable::update($ID, $arFields);
			if( !$res->isSuccess() ) {
				$adminControl->errors[] = implode("<br>", $res->getErrorMessages());
				$res = false;
			}
		}
		else {
			$res = GroupTable::add($arFields);
			if( !$res->isSuccess() ) {
				$adminControl->errors[] = implode("<br>", $res->getErrorMessages());
				$res = false;
			}
			else {
				$ID = $res->getId();
			}
		}
		if( $res ) {
			if( $request->get("apply") != "" ) {
				LocalRedirect(
					"/bitrix/admin/iplogic_actions_group_edit.php?ID=" . $ID . "&mess=ok&lang=" . LANG . "&" .
					$adminControl->ActiveTabParam()
				);
			}
			else {
				LocalRedirect("/bitrix/admin/iplogic_actions_groups.php?lang=" . LANG);
			}
		}
	}
}

// /save data

$APPLICATION->SetTitle(
	(
	$ID > 0 ?
		Loc::getMessage("IPL_ADMIN_ACTION_EDIT_TITLE") . " " . $arFields["NAME"] . " #" . $ID :
		Loc::getMessage("IPL_ADMIN_ACTION_NEW_TITLE")
	)
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

if( $fatalErrors != "" ) {
	CAdminMessage::ShowMessage($fatalErrors);
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
	die();
}

if( $request->get("mess") === "ok" ) {
	CAdminMessage::ShowMessage(["MESSAGE" => Loc::getMessage("SAVED"), "TYPE" => "OK"]);
}

elseif( count($adminControl->errors) ) {
	foreach( $adminControl->errors as $error ) {
		CAdminMessage::ShowMessage($error);
	}
}

$adminControl->buildPage();

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
?>