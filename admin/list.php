<?

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Iplogic\Actions\ActionsTable;
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


$rsData = GroupTable::getList(['order' => ['sort' => 'asc']]);
$arGroups = [];
$arGroupsFilter = [];
$arGroupsFilterID = [];
$arGroups[] = Loc::getMessage("IPL_ADMIN_NO_GROUP");
$arGroupsFilter[] = Loc::getMessage("IPL_ADMIN_NO_GROUP");
$arGroupsFilterID[] = 0;
while( $arGroup = $rsData->fetch() ) {
	$arGroups[$arGroup["ID"]] = $arGroup["NAME"];
	$arGroupsFilter[] = $arGroup["NAME"];
	$arGroupsFilterID[] = $arGroup["ID"];
}

$arOpts = [
	[
		"NAME"    => "name",
		"CAPTION" => Loc::getMessage("IPL_ADMIN_CAPTION_NAME"),
		"FILTER"  => [
			"COMPARE" => "?",
		],
		"VIEW"    => [
			"AddInputField" => [
				"PARAM" => ["size" => 20],
			],
			"AddViewField"  => [
				"PARAM" => "/bitrix/admin/iplogic_actions_edit.php?ID=##id##&lang=" . LANGUAGE_ID,
				"TYPE"  => "HREF",
			],
		],
	],
	[
		"NAME"    => "group",
		"CAPTION" => Loc::getMessage("IPL_ADMIN_CAPTION_GROUP"),
		"FILTER"  => [
			"VIEW"    => "select",
			"VALUES"  => [
				"reference"    => $arGroupsFilter,
				"reference_id" => $arGroupsFilterID,
			],
			"DEFAULT" => Loc::getMessage("IPL_ADMIN_ALL"),
		],
		"VIEW"    => [
			"AddSelectField" => [
				"PARAM" => $arGroups,
			],
		],
		"REPLACE" => $arGroups,
	],
	[
		"NAME"    => "action",
		"CAPTION" => Loc::getMessage("IPL_ADMIN_CAPTION_ACTION"),
		"FILTER"  => [
			"COMPARE" => "?",
		],
		"VIEW"    => [
			"AddInputField" => [
				"PARAM" => ["size" => 20],
			],
		],
	],
	[
		"NAME"    => "type",
		"CAPTION" => Loc::getMessage("IPL_ADMIN_CAPTION_TYPE"),
		"FILTER"  => [
			"VIEW"    => "select",
			"VALUES"  => [
				"reference"    => [
					Loc::getMessage("IPL_ADMIN_HTTP"),
					Loc::getMessage("IPL_ADMIN_CLI"),
				],
				"reference_id" => [
					"HTTP",
					"CLI",
				],
			],
			"DEFAULT" => Loc::getMessage("IPL_ADMIN_ALL"),
		],
		"VIEW"    => [
			"AddSelectField" => [
				"PARAM" => [
					"HTTP" => Loc::getMessage("IPL_ADMIN_HTTP"),
					"CLI"  => Loc::getMessage("IPL_ADMIN_CLI"),
				],
			],
		],
		"REPLACE" => [
			"HTTP" => Loc::getMessage("IPL_ADMIN_HTTP"),
			"CLI"  => Loc::getMessage("IPL_ADMIN_CLI"),
		],
	],
	[
		"NAME"    => "active",
		"CAPTION" => Loc::getMessage("IPL_ADMIN_CAPTION_ACTIVE"),
		"FILTER"  => [
			"VIEW"    => "select",
			"VALUES"  => [
				"reference"    => [
					Loc::getMessage("IPL_ADMIN_YES"),
					Loc::getMessage("IPL_ADMIN_NO"),
				],
				"reference_id" => [
					"Y",
					"N",
				],
			],
			"DEFAULT" => Loc::getMessage("IPL_ADMIN_ALL"),
		],
		"VIEW"    => [
			"AddCheckField" => [],
		],
		"REPLACE" => [
			"Y" => Loc::getMessage("IPL_ADMIN_YES"),
			"N" => Loc::getMessage("IPL_ADMIN_NO"),
		],
	],
	[
		"NAME"    => "sort",
		"CAPTION" => Loc::getMessage("IPL_ADMIN_CAPTION_SORT"),
		"FILTER"  => [],
		"VIEW"    => [
			"AddInputField" => [],
		],
	],
	[
		"NAME"    => "background",
		"CAPTION" => Loc::getMessage("IPL_ADMIN_CAPTION_BACKGROUND"),
		"FILTER"  => [
			"VIEW"    => "select",
			"VALUES"  => [
				"reference"    => [
					Loc::getMessage("IPL_ADMIN_YES"),
					Loc::getMessage("IPL_ADMIN_NO"),
				],
				"reference_id" => [
					"Y",
					"N",
				],
			],
			"DEFAULT" => Loc::getMessage("IPL_ADMIN_ALL"),
		],
		"VIEW"    => [
			"AddCheckField" => [],
		],
		"REPLACE" => [
			"Y" => Loc::getMessage("IPL_ADMIN_YES"),
			"N" => Loc::getMessage("IPL_ADMIN_NO"),
		],
	],
	[
		"NAME"    => "new_window",
		"CAPTION" => Loc::getMessage("IPL_ADMIN_CAPTION_NEW_WINDOW"),
		"FILTER"  => [
			"VIEW"    => "select",
			"VALUES"  => [
				"reference"    => [
					Loc::getMessage("IPL_ADMIN_YES"),
					Loc::getMessage("IPL_ADMIN_NO"),
				],
				"reference_id" => [
					"Y",
					"N",
				],
			],
			"DEFAULT" => Loc::getMessage("IPL_ADMIN_ALL"),
		],
		"VIEW"    => [
			"AddCheckField" => [],
		],
		"REPLACE" => [
			"Y" => Loc::getMessage("IPL_ADMIN_YES"),
			"N" => Loc::getMessage("IPL_ADMIN_NO"),
		],
	],
	[
		"NAME"       => "id",
		"CAPTION"    => "ID",
		"PROPERTY"   => "N",
		"UNIQ"       => "Y",
		"FILTER"     => [
			"VIEW" => "text",
		],
		"HEADER_KEY" => [
			"align"   => "right",
			"default" => false,
		],
	],
];


/* context menu */
$arContextMenu = [
	[
		"TEXT"  => Loc::getMessage("IPL_ADMIN_ADD"),
		"LINK"  => "iplogic_actions_edit.php?mode=new&lang=" . LANG,
		"TITLE" => Loc::getMessage("IPL_ADMIN_ADD_TITLE"),
		"ICON"  => "btn_new",
	],
];


/* context menu for each line */
$arItemContextMenu = [
	[
		"TEXT"    => Loc::getMessage("MAIN_ADMIN_LIST_EDIT"),
		"TITLE"   => Loc::getMessage("MAIN_ADMIN_LIST_EDIT"),
		"ICON"    => "edit",
		"ACTION"  => [
			"TYPE" => "REDIRECT",
			"HREF" => "iplogic_actions_edit.php?ID=##ID##&lang=" . LANG,
		],
		"DEFAULT" => true,
	],
	[
		"TEXT"   => Loc::getMessage("MAIN_ADMIN_LIST_DELETE"),
		"TITLE"  => Loc::getMessage("MAIN_ADMIN_LIST_DELETE"),
		"ICON"   => "delete",
		"ACTION" => [
			"TYPE" => "DELETE",
		],
	],
];


/* lang messages in classes */
$Messages = [
	"TITLE"              => Loc::getMessage("IPL_ADMIN_LIST_TITLE"),
	"COPY"               => Loc::getMessage("IPL_ADMIN_LIST_COPY"),
	"SELECTED"           => Loc::getMessage("MAIN_ADMIN_LIST_SELECTED"),
	"CHECKED"            => Loc::getMessage("MAIN_ADMIN_LIST_CHECKED"),
	"DELETE"             => Loc::getMessage("MAIN_ADMIN_LIST_DELETE"),
	"ACTIVATE"           => Loc::getMessage("MAIN_ADMIN_LIST_ACTIVATE"),
	"DEACTIVATE"         => Loc::getMessage("MAIN_ADMIN_LIST_DEACTIVATE"),
	"EDIT"               => Loc::getMessage("MAIN_ADMIN_LIST_EDIT"),
	"SAVE_ERROR_NO_ITEM" => Loc::getMessage("IPL_ADMIN_SAVE_ERROR_NO_ITEM"),
	"SAVE_ERROR_UPDATE"  => Loc::getMessage("IPL_ADMIN_SAVE_ERROR_UPDATE"),
	"SAVE_ERROR_DELETE"  => Loc::getMessage("IPL_ADMIN_SAVE_ERROR_DELETE"),
	"DELETE_CONF"        => Loc::getMessage("IPL_ADMIN_DELETE_CONF"),
];


/* prepare control object */
$adminControl = new Iplogic\Actions\Admin\TableList($moduleID);
$adminControl->POST_RIGHT = $MODULE_ACCESS;
$adminControl->arOpts = $arOpts;
$adminControl->Mess = $Messages;
$adminControl->arContextMenu = $arContextMenu;
$adminControl->arItemContextMenu = $arItemContextMenu;
$adminControl->sTableClass = "Iplogic\Actions\ActionsTable";


/* exec actions */
$adminControl->initList("tbl_iplogic_actions");
$adminControl->EditAction();
$adminControl->GroupAction();


/* get list and put it in control object */
$rsData = ActionsTable::getList(
	['order' => $adminControl->arSort, 'filter' => $adminControl->arFilter, 'select' => $adminControl->arSelect]
);
$adminControl->prepareData($rsData);


/* starting output */
$APPLICATION->SetTitle(Loc::getMessage('IPL_ADMIN_LIST_TITLE'));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

/* ok message */
if( $request->get("mess") === "ok" ) {
	CAdminMessage::ShowMessage(["MESSAGE" => Loc::getMessage("SAVED"), "TYPE" => "OK"]);
}


/* action errors */
if( count($adminControl->errors) ) {
	foreach( $adminControl->errors as $error ) {
		CAdminMessage::ShowMessage($error);
	}
}

$adminControl->renderList();

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
?>