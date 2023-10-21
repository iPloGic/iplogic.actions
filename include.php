<?
$moduleID = 'iplogic.actions';
$moduleNamespace = 'Iplogic\\Actions\\';

use \Bitrix\Main\Loader;

include(__DIR__ . "/lib/admin/autoload.php");

$arClasses = array_merge(
	[
		$moduleNamespace . "ActionsTable" => "lib/ORM/actionstable.php",
		$moduleNamespace . "GroupTable"   => "lib/ORM/grouptable.php",
		$moduleNamespace . "RightsTable"  => "lib/ORM/rightstable.php",

		$moduleNamespace . "Access"       => "lib/access.php",
	],
	$arAdminClasses
);

Loader::registerAutoLoadClasses(
	$moduleID,
	$arClasses
);