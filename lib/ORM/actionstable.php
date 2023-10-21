<?php
namespace Iplogic\Actions;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\BooleanField,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class ActionsTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(255) mandatory
 * <li> ACTION string(255) mandatory
 * <li> TYPE string(255) mandatory
 * <li> ACTIVE bool ('N', 'Y') optional default 'Y'
 * <li> SORT int optional default 100
 * <li> BACKGROUND bool ('N', 'Y') optional default 'N'
 * <li> NEW_WINDOW bool ('N', 'Y') optional default 'Y'
 * <li> GROUP int optional default 0
 * </ul>
 *
 * @package Iplogic\Actions
 **/

class ActionsTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'ipl_actions';
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
					'title' => Loc::getMessage('ACTIONS_ENTITY_ID_FIELD')
				]
			),
			new StringField(
				'NAME',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateName'],
					'title' => Loc::getMessage('ACTIONS_ENTITY_NAME_FIELD')
				]
			),
			new StringField(
				'ACTION',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateAction'],
					'title' => Loc::getMessage('ACTIONS_ENTITY_ACTION_FIELD')
				]
			),
			new StringField(
				'TYPE',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateType'],
					'title' => Loc::getMessage('ACTIONS_ENTITY_TYPE_FIELD')
				]
			),
			new BooleanField(
				'ACTIVE',
				[
					'values' => array('N', 'Y'),
					'default' => 'Y',
					'title' => Loc::getMessage('ACTIONS_ENTITY_ACTIVE_FIELD')
				]
			),
			new IntegerField(
				'SORT',
				[
					'default' => 100,
					'title' => Loc::getMessage('ACTIONS_ENTITY_SORT_FIELD')
				]
			),
			new BooleanField(
				'BACKGROUND',
				[
					'values' => array('N', 'Y'),
					'default' => 'N',
					'title' => Loc::getMessage('ACTIONS_ENTITY_BACKGROUND_FIELD')
				]
			),
			new BooleanField(
				'NEW_WINDOW',
				[
					'values' => array('N', 'Y'),
					'default' => 'Y',
					'title' => Loc::getMessage('ACTIONS_ENTITY_NEW_WINDOW_FIELD')
				]
			),
			new IntegerField(
				'GROUP',
				[
					'default' => 0,
					'title' => Loc::getMessage('ACTIONS_ENTITY_GROUP_FIELD'),
				]
			),
		];
	}

	/**
	 * Returns validators for NAME field.
	 *
	 * @return array
	 */
	public static function validateName()
	{
		return [
			new LengthValidator(null, 255),
		];
	}

	/**
	 * Returns validators for ACTION field.
	 *
	 * @return array
	 */
	public static function validateAction()
	{
		return [
			new LengthValidator(null, 255),
		];
	}

	/**
	 * Returns validators for TYPE field.
	 *
	 * @return array
	 */
	public static function validateType()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}