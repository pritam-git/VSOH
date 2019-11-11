<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/Privilege.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Privilege.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_Privilege
 *
 *
 */
class L8M_Doctrine_Import_Privilege extends L8M_Doctrine_Import_Abstract
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
		$this->_data = array();
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{
		$this->_dataCollection = new Doctrine_Collection($this->getModelClassName());
	}

}