<?php

/**
 * L8M a
 *
 * @filesource /library/L8M/Form/Decorator/Form/Wider.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Wider.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_Form_Wider
 *
 *
 */
class L8M_Form_Decorator_Form_Wider extends L8M_Form_Decorator
{

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Initializes L8M_Form_Decorator_Form_Wider instance.
	 *
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($options = NULL)
	{
		parent::__construct($options);
		$this->setOption('boxClass', 'wider');
	}

}