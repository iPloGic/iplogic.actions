<?
$module_id = "iplogic.actions";
$groupRightsBind = "module";

use \Bitrix\Main\Config\Option,
	\Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Application,
	\Bitrix\Main\Loader;

Loader::includeModule($module_id);

$docRoot = $_SERVER['DOCUMENT_ROOT'];
$RIGHT = $APPLICATION->GetGroupRight($module_id);

IncludeModuleLangFile($docRoot.BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$request = Application::getInstance()->getContext()->getRequest();

if($RIGHT >= "R") {

	$arMainOptions = [
		["menu_sort_index", Loc::getMessage("IPL_MENU_SORT_INDEX"), 500, ["text", 5]],
		["cli_php", Loc::getMessage("IPL_PHP_FILE"), "/usr/bin/php", ["text", 15]],
		["cli_wget_miss_cert", Loc::getMessage("IPL_MISS_CERT"), "Y", ["checkbox"]],
	];

	$aTabs = [
		["DIV" => "edit1", "TAB" => Loc::getMessage("MAIN_TAB_SET"), "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET"), "OPTIONS" => $arMainOptions],
		["DIV" => "edit10", "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"), "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")],
	];
	$tabControl = new CAdminTabControl("tabControl", $aTabs);

	if($request->isPost() && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT=="W" && check_bitrix_sessid())
	{
		if(strlen($RestoreDefaults)>0) {
			Option::delete($module_id);
			Option::getDefaults($module_id);
		}
		else
		{
			foreach($aTabs as $aTab){
				if($aTab["OPTIONS"]){
					foreach($aTab["OPTIONS"] as $arOption) {
						__AdmSettingsSaveOption($module_id, $arOption);
					}
				}
			}
		}
		$groupRightsAction = "POST";
		require_once(__DIR__ . "/admin/group_rights.php");
		$groupRightsAction = false;

		if ($request->get("back_url_settings") != "")
		{
			if( $request->get("Apply") != "" || $request->get("RestoreDefaults") != "" )
				LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($request->get("back_url_settings"))."&".$tabControl->ActiveTabParam());
			else
				LocalRedirect($request->get("back_url_settings"));
		}
		else
		{
			LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
		}
	}
	?>
	<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
		<?
		$tabControl->Begin();
		$arNotes = array();
		foreach($aTabs as $aTab){
			if($aTab["OPTIONS"]){
				$tabControl->BeginNextTab();
				__AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
			}
		}
		$tabControl->BeginNextTab();
		require_once(__DIR__ . "/admin/group_rights.php");
		$tabControl->Buttons();
		?>
		<input <?if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?=Loc::getMessage("MAIN_SAVE")?>" title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
		<?if(strlen($request->get("back_url_settings"))>0):?>
			<input <?if ($RIGHT<"W") echo "disabled" ?> type="button" name="Cancel" value="<?=Loc::getMessage("MAIN_OPT_CANCEL")?>" title="<?=Loc::getMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($request->get("back_url_settings")))?>'">
			<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($request->get("back_url_settings"))?>">
		<?endif?>
		<input type="submit" name="RestoreDefaults" title="<?echo Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="confirm('<?echo AddSlashes(Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo Loc::getMessage("MAIN_RESTORE_DEFAULTS")?>">
		<?=bitrix_sessid_post();?>
		<?$tabControl->End();?>
	</form>
	 <?/*
		CJSCore::Init(array("jquery"));
	 ?>
	 <script>
		$(document).ready(function(){
		});
	</script>
	<?*/
	if(!empty($arNotes))
	{
		echo BeginNote();
		foreach($arNotes as $i => $str)
		{
			?><span class="required"><sup><?echo $i+1?></sup></span><?echo $str?><br><?
		}
		echo EndNote();
	}
}
else {
	echo Loc::getMessage("MODULE_OPTIONS_DENIED");
}
?>
