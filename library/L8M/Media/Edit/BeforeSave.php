<?php

/**
 * PRJ
 *
 *
 * @filesource /library/L8M/Media/Edit/BeforeSave.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: BeforeSave.php 561 2018-02-15 15:22:00Z nm $
 */

/**
 *
 *
 * L8M_Media_Edit_BeforeSave
 *
 *
 */
class L8M_Media_Edit_BeforeSave
{

	private $_goOn = FALSE;
	private $_formValues = array();

	/**
	 * BeforeSave
	 */
	public function beforeSave($modelID, $modelName, $formValues)
	{

		$modelFrom = $modelName . ' m';

		$model = Doctrine_Query::create()
			->from($modelFrom)
			->addWhere('m.id = ?', array($modelID))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * if model exists
		 */
		if ($model !== FALSE) {
			Zend_Registry::set('L8M_Media_Edit_OldMediaModelArray', $model->toArray());
			Zend_Registry::set('L8M_Media_Edit_OldRoleModelArray', $model->Role->toArray());

			$this->_formValues = $formValues;
			$this->_formValues['short'] = L8M_Library::getUsableUrlStringOnly($formValues['file_name'], '-', array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '-', '_', '.'));

			$this->_goOn = TRUE;
		}
	}

	public function replaceFormValues()
	{
		return $this->_formValues;
	}

	public function goOn()
	{
		return $this->_goOn;
	}

}