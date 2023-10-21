<?
/** @global CMain $APPLICATION */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;

$moduleID = 'iplogic.actions';

$APPLICATION->SetAdditionalCss("/bitrix/css/" . $moduleID . "/menu.css");

Loc::loadMessages(__FILE__);

if( !Loader::includeModule($moduleID) ) {
	return false;
}

$MODULE_ACCESS = \Iplogic\Actions\Access::getGroupRight("module");

if( $MODULE_ACCESS <= "D" ) {
	return false;
}

$arConMenu = [];

$arConMenu[] = [
	"text"     => Loc::getMessage("IPL_ACT_MENU_ACTIONS_GO"),
	"title"    => Loc::getMessage("IPL_ACT_MENU_ACTIONS_GO"),
	"url"      => "iplogic_actions_go.php?lang=" . LANGUAGE_ID,
	"more_url" => [
		"iplogic_actions_go.php",
		"iplogic_actions_action.php",
	],
];

if( $MODULE_ACCESS >= "W" ) {
	$arConMenu[] = [
		"text"     => Loc::getMessage("IPL_ACT_MENU_ACTIONS_SETUP"),
		"title"    => Loc::getMessage("IPL_ACT_MENU_ACTIONS_SETUP"),
		"items_id" => "menu_ipl_actions_setup",
		"items"    => [
			[
				"text"     => Loc::getMessage("IPL_ACT_MENU_ACTIONS_LIST"),
				"title"    => Loc::getMessage("IPL_ACT_MENU_ACTIONS_LIST"),
				"url"      => "iplogic_actions_list.php?lang=" . LANGUAGE_ID,
				"more_url" => [
					"iplogic_actions_list.php",
					"iplogic_actions_edit.php",
				],
			],
			[
				"text"     => Loc::getMessage("IPL_ACT_MENU_ACTIONS_GROUPS"),
				"title"    => Loc::getMessage("IPL_ACT_MENU_ACTIONS_GROUPS"),
				"url"      => "iplogic_actions_groups.php?lang=" . LANGUAGE_ID,
				"more_url" => [
					"iplogic_actions_groups.php",
					"iplogic_actions_group_edit.php",
				],
			],
		],
	];
}

$arConMenu[] = [
	"text" => Loc::getMessage("IPL_ACT_MENU_HELP"),
	"title" => Loc::getMessage("IPL_ACT_MENU_HELP"),
	"url" => "javascript:window.open('https://iplogic.ru/doc/course/2/', '_blank');void(0);",
];

$arModMenu = [
	"parent_menu" => "global_menu_services",
	"section"     => "ipl_actions",
	"sort"        => Option::get($moduleID, 'menu_sort_index', 500),
	"text"        => Loc::getMessage("IPL_ACT_MENU_ACTIONS"),
	"title"       => Loc::getMessage("IPL_ACT_MENU_ACTIONS_TITLE"),
	"icon"        => "iplogic_actions_menu_logo",
	"items_id"    => "menu_ipl_actions",
	"items"       => $arConMenu,
];
return $arModMenu;