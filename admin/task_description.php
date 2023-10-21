<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

return [
	"IPLOGIC_ACTIONS_DENIED"        => [
		"title"       => Loc::getMessage('TASK_NAME_IPLOGIC_ACTIONS_DENIED'),
		"description" => Loc::getMessage('TASK_DESC_IPLOGIC_ACTIONS_DENIED'),
	],
	"IPLOGIC_ACTIONS_EXECUTE"       => [
		"title"       => Loc::getMessage('TASK_NAME_IPLOGIC_ACTIONS_EXECUTE'),
		"description" => Loc::getMessage('TASK_DESC_IPLOGIC_ACTIONS_EXECUTE'),
	],
	"IPLOGIC_ACTIONS_MANAGE"        => [
		"title"       => Loc::getMessage('TASK_NAME_IPLOGIC_ACTIONS_MANAGE'),
		"description" => Loc::getMessage('TASK_DESC_IPLOGIC_ACTIONS_MANAGE'),
	],
	"IPLOGIC_ACTIONS_ACTION_DENIED" => [
		"title"       => Loc::getMessage('TASK_NAME_IPLOGIC_ACTIONS_ACTION_DENIED'),
		"description" => Loc::getMessage('TASK_DESC_IPLOGIC_ACTIONS_ACTION_DENIED'),
	],
	"IPLOGIC_ACTIONS_ACTION_RUN"    => [
		"title"       => Loc::getMessage('TASK_NAME_IPLOGIC_ACTIONS_ACTION_RUN'),
		"description" => Loc::getMessage('TASK_DESC_IPLOGIC_ACTIONS_ACTION_RUN'),
	],
	"MODULE"                        => [
		"title" => Loc::getMessage("TASK_BINDING_MODULE"),
	],
	"ACTION"                        => [
		"title" => Loc::getMessage("TASK_BINDING_ACTION"),
	],
];
