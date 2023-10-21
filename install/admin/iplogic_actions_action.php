<?
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iplogic.actions/admin/action.php"))
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iplogic.actions/admin/action.php");
elseif (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/modules/iplogic.actions/admin/action.php"))
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/iplogic.actions/admin/action.php");
?>
