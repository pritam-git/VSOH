<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Form/Element/Number.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Number.php 178 2014-09-05 10:29:44Z nm $
 */

/**
 *
 *
 * L8M_Form_Element_Number
 *
 *
 */
class L8M_Form_Element_Number extends Zend_Form_Element_Text
{
	/**
	 * Default form view helper to use for rendering
	 * @var string
	 */
	public $helper = 'formNumber';

	/**
	 * Retrieve filtered element value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		$returnValue = L8M_Translate::numeric(parent::getValue(), L8M_Locale::getLang(), L8M_Locale::getDefaultSystem());

		return $returnValue;
	}
}
