<?php
namespace Iplogic\Actions;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Iplogic\Actions\ActionsTable;

/**
 * Class GroupTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(255) mandatory
 * <li> DESCRIPTION string(255) optional
 * <li> ACTIVE bool ('N', 'Y') optional default 'Y'
 * <li> SORT int optional default 100
 * </ul>
 *
 * @package Iplogic\Actions
 **/

class GroupTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'ipl_actions_group';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			new IntegerField(
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('GROUP_ENTITY_ID_FIELD'),
				]
			),
			new StringField(
				'NAME',
				[
					'required' => true,
					'validation' => function()
					{
						return[
							new LengthValidator(null, 255),
						];
					},
					'title' => Loc::getMessage('GROUP_ENTITY_NAME_FIELD'),
				]
			),
			new StringField(
				'DESCRIPTION',
				[
					'validation' => function()
					{
						return[
							new LengthValidator(null, 255),
						];
					},
					'title' => Loc::getMessage('GROUP_ENTITY_DESCRIPTION_FIELD'),
				]
			),
			new BooleanField(
				'ACTIVE',
				[
					'values' => array('N', 'Y'),
					'default' => 'Y',
					'title' => Loc::getMessage('GROUP_ENTITY_ACTIVE_FIELD'),
				]
			),
			new IntegerField(
				'SORT',
				[
					'default' => 100,
					'title' => Loc::getMessage('GROUP_ENTITY_SORT_FIELD'),
				]
			),
		];
	}

	public static function delete($ID) {
		$arFields = ["GROUP" => 0];
		$rsActions = ActionsTable::getList(["filter" => ["=GROUP" => $ID], "select" => ["ID"]]);
		while($arAction = $rsActions->fetch()) {
			ActionsTable::update($arAction["ID"], $arFields);
		}
		return parent::delete($ID);
	}
}