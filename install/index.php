<?

use \Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Loader,
	\Bitrix\Main\Config\Option,
	\Bitrix\Main\IO\Directory,
	\Bitrix\Main\Application,
	\Bitrix\Main\EventManager,
	\Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);
if( !class_exists("iplogic_actions") ) {
	class iplogic_actions extends CModule
	{
		const MODULE_ID = 'iplogic.actions';
		var $MODULE_ID = 'iplogic.actions';
		var $MODULE_VERSION;
		var $MODULE_VERSION_DATE;
		var $MODULE_NAME;
		var $MODULE_DESCRIPTION;
		var $MODULE_CSS;
		var $errors = '';

		function __construct()
		{
			$arModuleVersion = [];
			include(__DIR__ . "/version.php");
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
			$this->MODULE_NAME = Loc::getMessage("IPLOGIC_ACT_MODULE_NAME");
			$this->MODULE_DESCRIPTION = Loc::getMessage("IPLOGIC_ACT_MODULE_DESC");

			$this->PARTNER_NAME = Loc::getMessage("IPLOGIC_ACT_PARTNER_NAME");
			$this->PARTNER_URI = Loc::getMessage("IPLOGIC_ACT_PARTNER_URI");
		}

		function InstallDB($arParams = [])
		{

			global $DB, $APPLICATION;

			$this->errors = false;
			$this->errors = $DB->RunSQLBatch(__DIR__ . "/db/" . strtolower($DB->type) . "/install.sql");

			if( $this->errors !== false ) {
				$APPLICATION->ThrowException(implode("", $this->errors));
				return false;
			}
			Loader::includeModule($this->MODULE_ID);

			$this->InstallTasks();

			Option::set(self::MODULE_ID, "GROUP_DEFAULT_RIGHT", 'R');
			Option::set(self::MODULE_ID, "cli_php", '/usr/bin/php');
			Option::set(self::MODULE_ID, "cli_wget_miss_cert", 'Y');
			Option::set(self::MODULE_ID, "menu_sort_index", '500');

			return true;
		}

		function UnInstallDB($arParams = [])
		{
			global $APPLICATION, $DB;

			$this->errors = false;

			if( !$arParams['savedata'] ) {
				$this->errors = $DB->RunSQLBatch(__DIR__ . "/db/" . strtolower($DB->type) . "/uninstall.sql");
			}

			if( !empty($this->errors) ) {
				$APPLICATION->ThrowException(implode("", $this->errors));
				return false;
			}

			$this->UnInstallTasks();

			Option::delete($this->MODULE_ID);
			return true;
		}

		function InstallFiles($arParams = [])
		{
			CopyDirFiles(__DIR__ . '/admin/', Application::getDocumentRoot() . '/bitrix/admin', true);
			CopyDirFiles(
				__DIR__ . '/css/',
				Application::getDocumentRoot() . '/bitrix/css/' . $this->MODULE_ID,
				true,
				true
			);
			CopyDirFiles(
				__DIR__ . '/images/',
				Application::getDocumentRoot() . '/bitrix/images/' . $this->MODULE_ID,
				true,
				true
			);
			return true;
		}

		function UnInstallFiles()
		{
			DeleteDirFiles(__DIR__ . '/admin/', Application::getDocumentRoot() . '/bitrix/admin');
			DeleteDirFilesEx('/bitrix/css/' . $this->MODULE_ID . '/');
			DeleteDirFilesEx('/bitrix/images/' . $this->MODULE_ID . '/');
			return true;
		}

		function GetModuleTasks()
		{
			$t = [
				"iplogic_actions_denied"        => [
					"LETTER"     => "D",
					"BINDING"    => "module",
					"OPERATIONS" => [],
				],
				"iplogic_actions_execute"       => [
					"LETTER"     => "E",
					"BINDING"    => "module",
					"OPERATIONS" => [
						"actions_run",
					],
				],
				"iplogic_actions_manage"        => [
					"LETTER"     => "W",
					"BINDING"    => "module",
					"OPERATIONS" => [
						"actions_run",
						"actions_manage",
					],
				],
				"iplogic_actions_action_denied" => [
					"LETTER"     => "D",
					"BINDING"    => "action",
					"OPERATIONS" => [],
				],
				"iplogic_actions_action_run"    => [
					"LETTER"     => "R",
					"BINDING"    => "action",
					"OPERATIONS" => [
						"action_run",
					],
				],
			];
			return $t;
		}

		function DoInstall()
		{
			global $APPLICATION;
			$this->InstallFiles();
			ModuleManager::registerModule($this->MODULE_ID);
			$this->InstallDB();
			$APPLICATION->IncludeAdminFile(
				Loc::getMessage("IPLOGIC_ACT_MODULE_INSTALLED_TITLE"),
				__DIR__ . "/step.php"
			);
			return true;
		}

		function DoUninstall()
		{
			global $APPLICATION, $step;
			$step = IntVal($step);
			if( $step < 2 ) {
				$APPLICATION->IncludeAdminFile(
					Loc::getMessage("IPLOGIC_ACT_MODULE_UNINSTALLED_TITLE"),
					__DIR__ . "/unstep1.php"
				);
			}
			elseif( $step == 2 ) {
				$this->UnInstallDB(
					[
						"savedata" => $_REQUEST["savedata"],
					]
				);
				$this->UnInstallFiles();
				ModuleManager::unRegisterModule(self::MODULE_ID);
				$APPLICATION->IncludeAdminFile(
					Loc::getMessage("IPLOGIC_ACT_MODULE_UNINSTALLED_TITLE"),
					__DIR__ . "/unstep2.php"
				);
			}
			return true;
		}
	}
}
?>
