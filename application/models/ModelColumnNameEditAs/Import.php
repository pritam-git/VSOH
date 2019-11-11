<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Role/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Model_ModelColumnNameEditAs_Import
 *
 *
 */
class Default_Model_ModelColumnNameEditAs_Import extends L8M_Doctrine_Import_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		parent::_init();
		$this->setArray(array(
			array(
				'id'=>1,
				'short'=>'text',
			),
			array(
				'id'=>2,
				'short'=>'textarea',
			),
			array(
				'id'=>3,
				'short'=>'tinyMCE',
			),
		));
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{

		/**
		 * Collection
		 */
		$this->_dataCollection = new Doctrine_Collection('Default_Model_ModelColumnNameEditAs');
		foreach($this->_data as $data) {
			$model = new Default_Model_ModelColumnNameEditAs();
			$model->merge($data);
			$this->_dataCollection->add($model, $data['id']);
		}
	}

}