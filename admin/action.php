<?

use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Iplogic\Actions\ActionsTable;

$moduleID = 'iplogic.actions';
define("ADMIN_MODULE_NAME", $moduleID);
$baseFolder = realpath(__DIR__ . "/../../..");
include($baseFolder . "/modules/" . $moduleID . "/prolog.php");

if( $MODULE_ACCESS < "E" ) {
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
	CAdminMessage::ShowMessage(Loc::getMessage("ACCESS_DENIED"));
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
	die();
}

if( $request->get("ID") < 1 ) {
	$fatalErrors = Loc::getMessage("WRONG_ACTION_ID") . "<br>";
}
else {
	$ACTION_ACCESS = \Iplogic\Actions\Access::getGroupRight("action", $request->get("ID"));
	if( $ACTION_ACCESS < "R" ) {
		require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
		CAdminMessage::ShowMessage(Loc::getMessage("ACCESS_DENIED"));
		require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
		die();
	}
}

$adminControl = new \Iplogic\Actions\Admin\Info($moduleID);

/* get service data and preforms*/
$info = "";
if( $fatalErrors == "" ) {
	$arAction = ActionsTable::getRowById($request->get("ID"));
	if( !is_array($arAction) ) {
		$fatalErrors = Loc::getMessage("ACTION_NOT_FOUND") . "<br>";
	}
	elseif( $arAction["ACTIVE"] != "Y" ) {
		$fatalErrors = Loc::getMessage("ACTION_NOT_ACTIVE") . "<br>";
	}
	else {
		$PHP = Option::get($moduleID, "cli_php", "/usr/bin/php");
		if( $arAction["BACKGROUND"] == "Y" ) {
			if( $arAction["TYPE"] == "HTTP" ) {
				$MISS_CERT = Option::get($moduleID, "cli_wget_miss_cert", "Y");
				$comm = "wget ";
				if( $MISS_CERT == "Y" ) {
					$comm .= "--no-check-certificate ";
				}
				$comm .= "-b -q -O - " . $arAction["ACTION"];
			}
			if( $arAction["TYPE"] == "CLI" ) {
				$PHP = Option::get($moduleID, "cli_php", "/usr/bin/php");
				$comm = $PHP . " -f " . $arAction["ACTION"];
			}
			if( substr(php_uname(), 0, 7) == "Windows" ) {
				pclose(popen("start /B " . $comm, "r"));
			}
			else {
				exec($comm . " > /dev/null 2>&1 &");
			}
			LocalRedirect("/bitrix/admin/iplogic_actions_go.php?done=" . $arAction["ID"] . "lang=" . LANG);
		}
		else {
			if( $arAction["TYPE"] == "HTTP" ) {
				try {
					$info = "<pre>" . file_get_contents($arAction["ACTION"]) . "</pre>";
				} catch( Throwable $e ) {
					$info = $e->getMessage();
				}
			}
			if( $arAction["TYPE"] == "CLI" ) {
				$PHP = Option::get($moduleID, "cli_php", "/usr/bin/php");
				$comm = $PHP . " -f " . $arAction["ACTION"];
				$result = [];
				$state = -1;
				exec($comm, $result, $state);
				foreach( $result as $str ) {
					$info .= $str . "<br>";
				}
			}
		}
	}
}


/* tabs and opts */
$arTabs = [
	[
		"DIV"   => "edit1",
		"TAB"   => Loc::getMessage("IPL_ACT_ACTIONS"),
		"ICON"  => "main_user_edit",
		"TITLE" => Loc::getMessage("IPL_ACT_ACTIONS_TITLE"),
	],
];
$arOpts = [
	[
		"TAB"  => 0,
		"INFO" => $info,
	],
];


/* context menu */
$arContextMenu = [
	[
		"TEXT"  => Loc::getMessage("IPL_ACT_LIST"),
		"TITLE" => Loc::getMessage("IPL_ACT_LIST_TITLE"),
		"LINK"  => "iplogic_actions_go.php?lang=" . LANG,
		"ICON"  => "btn_list",
	],
	[
		"SEPARATOR" => "Y",
	],
];


/* lang messages in classes */
$Messages = [];


/* prepare control object */
$adminControl->arTabs = $arTabs;
$adminControl->arOpts = $arOpts;
$adminControl->Mess = $Messages;
$adminControl->arContextMenu = $arContextMenu;
$adminControl->initDetailPage();


/* executing */

/* starting output */
$APPLICATION->SetTitle(Loc::getMessage("IPL_ACT_PAGE_TITLE") . " " . $arAction["NAME"] . " #" . $arAction["ID"]);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");


/* fatal errors */
if( $fatalErrors != "" ) {
	CAdminMessage::ShowMessage($fatalErrors);
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
	die();
}


/* action errors */
if( $message ) {
	echo $message->Show();
}


/* content */
$adminControl->buildPage();

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
?>