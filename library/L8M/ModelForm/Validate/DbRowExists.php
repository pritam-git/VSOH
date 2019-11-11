<?php

/**
 * L8M
 *
 * @filesource /library/L8M/ModelForm/Validate/DbRowExists.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: DbRowExists.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_ModelForm_Validate_DbRowExists
 *
 *
 */
class L8M_ModelForm_Validate_DbRowExists extends Zend_Validate_Abstract
{
	const NOT_FOUND = 'notFound';

	protected $_table;

	protected $_messageTemplates = array(
		self::NOT_FOUND => 'Value was not found'
	);

	public function __construct($table)
	{
		$this->_table = $table;
	}

	public function isValid($value)
	{
		$this->_setValue($value);
		$row = $this->_table->find($value);	

		if($row == false)
		{
			$this->_error();
			return false;
		}

		return true;
	}
}