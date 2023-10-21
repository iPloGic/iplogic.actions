<?
/* //////////////////////////////////////////////////////////////////////////////
*
*   Clssses for bitrix administrative section 
*   iPloGic solutions
*
*   V 3.0.0   30.10.19
*
*////////////////////////////////////////////////////////////////////////////////

namespace Iplogic\Actions\Admin;

use \Bitrix\Main\Config\Option,
	\Bitrix\Main\Application;


class Info extends Detail {

	public function buildPage() {
		global $APPLICATION;
		if (count($this->arContextMenu)) {
			$context = new \CAdminContextMenu($this->getContextMenu());
			$context->Show();
		}
		?>
		<?echo bitrix_sessid_post();
		$this->tabControl->Begin();
		$this->buildContent();
		$this->addButtons();
		$this->tabControl->End();
	}

	protected function buildContent() {
		foreach ( $this->arTabs as $num => $a ) {
			$this->tabControl->BeginNextTab();
			foreach ( $this->arOpts as $arProps ) {
				if ( $arProps["TAB"] == $num ) {
					echo $arProps["INFO"];
				}
			}
		}
	}

}