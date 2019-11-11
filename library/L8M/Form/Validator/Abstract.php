<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Form/Validator/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */


/**
 *
 *
 * L8M_Form_Validator_Abstract
 *
 *
 */
abstract class L8M_Form_Validator_Abstract implements Zend_Validate_Interface
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A Zend_Form instance.
	 *
	 * @var Zend_Form
	 */
	protected $_form = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Form_Validator_Abstract instance.
	 *
	 * @param Zend_Form $form
	 */
	public function __construct($form = NULL)
	{
		$this->_form = $form;
	}

	/**
	 *
	 *
	 * Abstract Methods
	 *
	 *
	 */

	/**
	 * Returns TRUE when the validation is ok.
	 *
	 * @return bool
	 */
	abstract public function isValid();

}