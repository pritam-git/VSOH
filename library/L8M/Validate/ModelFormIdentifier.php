<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Form/Validator/ModelFormIdentifier.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ModelFormIdentifier.php 37 2014-04-10 13:19:03Z nm $
 */


/**
 *
 *
 * L8M_Validate_ModelFormIdentifier
 *
 *
 */
class L8M_Validate_ModelFormIdentifier extends Zend_Validate_Abstract
{
	const INVALID   = 'modelFormIdentifierInvalid';

	/**
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID   => 'It is currently not permitted to edit the record.',
	);

	/**
	 * @var array
	 */
	protected $_validateVars = array(
		'model'=>NULL,
		'id'=>NULL,
	);

	/**
	 * Sets validator options
	 *
	 * @param string $model
	 * @param integer $id
	 * @return void
	 */
	public function __construct($model, $id)
	{
		$this->_validateVars['model'] = $model;
		$this->_validateVars['id'] = $id;

		$view = Zend_Layout::getMvcInstance()->getView();
		$this->_messageTemplates[self::INVALID] = $view->translate('It is currently not permitted to edit the record.');
	}


	/**
	 * Defined by Zend_Validate_Interface
	 *
	 * Returns true if and only if the string length of $value is at least the min option and
	 * no greater than the max option (when the max option is not null).
	 *
	 * @param  string $value
	 * @return boolean
	 */
	public function isValid($value)
	{
		$returnValue = TRUE;

		if (!L8M_ModelForm_MarkedForEditor::isValid($this->_validateVars['model'], $this->_validateVars['id'], $value)) {
			$returnValue = FALSE;
			$this->_error(self::INVALID);
		}

		return $returnValue;
	}
}
