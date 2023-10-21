<?

use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Iplogic\Actions\ActionsTable;
use \Iplogic\Actions\GroupTable;

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


$adminControl = new \Iplogic\Actions\Admin\Info($moduleID);

/* get service data and preforms*/
$arGroups = [
	0 => [
		"ID"          => 0,
		"NAME"        => Loc::getMessage("IPL_ACT_ACTIONS"),
		"DESCRIPTION" => Loc::getMessage("IPL_ACT_ACTIONS_TITLE")
	]
];
$rsGroups = GroupTable::getList(
	["filter" => ["=ACTIVE" => "Y"], "order" => ["SORT" => "ASC"], "select" => ["ID", "NAME", "DESCRIPTION"]]
);
while( $arGroup = $rsGroups->Fetch() ) {
	$arGroups[$arGroup["ID"]] = $arGroup;
}

$list = [];
$rsActions = ActionsTable::getList(
	[
		"filter" => ["=ACTIVE" => "Y"],
		"order"  => ["SORT" => "ASC"],
		"select" => ["ID", "NAME", "NEW_WINDOW", "GROUP"]
	]
);
while( $arActions = $rsActions->Fetch() ) {
	if(\Iplogic\Actions\Access::getGroupRight("action", $arActions["ID"]) >= "R") {
		$list[$arActions["GROUP"]] .= "<tr><td>" . $arActions["NAME"] .
			"</td><td><a href='/bitrix/admin/iplogic_actions_action.php?ID=" . $arActions["ID"] . "&lang=" .
			LANGUAGE_ID . "'" . ($arActions["NEW_WINDOW"] == "Y" ? " target='_blank'" : "") . "><button>" .
			Loc::getMessage("IPL_GO") .
			"</button></a></td></tr>";
	}
}


$info = [];
if( count($list) == 0 ) {
	$info[0] = Loc::getMessage("IPL_EMPTY");
}
foreach($list as $group => $cont) {
	$info[$group] = "<tr><td><table class='act_lost_table'>" . $cont . "</table></td></tr>";
}


/* tabs and opts */
$tabIndex = 0;
foreach($arGroups as $group => $arGroup) {
	if(!isset($info[$group])) {
		continue;
	}
	$arTabs[$tabIndex] = [
		"DIV"   => "edit" . $group,
		"TAB"   => $arGroup["NAME"],
		"ICON"  => "main_user_edit",
		"TITLE" => $arGroup["DESCRIPTION"],
	];
	$arOpts[$tabIndex] = [
		"TAB"  => $tabIndex,
		"INFO" => $info[$group],
	];
	$tabIndex++;
}


/* context menu */
$arContextMenu = [];


/* lang messages in classes */
$Messages = [];


/* prepare control object */
$adminControl->arTabs = $arTabs;
$adminControl->arOpts = $arOpts;
$adminControl->Mess = $Messages;
$adminControl->arContextMenu = $arContextMenu;
$adminControl->initDetailPage();


/* starting output */
$APPLICATION->SetTitle(Loc::getMessage("IPL_ACT_PAGE_TITLE"));
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


/* ok message */
if( $request->get("done") > 0 ) {
	$arAction = ActionsTable::getRowById($request->get("done"));
	CAdminMessage::ShowMessage(
		[
			"MESSAGE" => $arAction["NAME"] . " [" . $arAction["ID"] . "] - " . Loc::getMessage("IPL_ACT_DONE"),
			"TYPE"    => "OK",
		]
	);
}


/* content */
echo "<style>
	.act_lost_table tr td {
		padding:7px;
	}
</style>";

$adminControl->buildPage();


require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
?>