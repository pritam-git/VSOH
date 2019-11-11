<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Form.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Form.php 27 2014-04-02 14:29:24Z nm $
 */

/**
 *
 *
 * L8M_Form
 *
 *
 */
class L8M_Form extends Zend_Form
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * The suffix that will be appended to the name of the form to arrive at a
	 * name for a form element used as a protection against cross site forgery
	 * requests. To every instance of L8M_Form upon rendering a hidden input
	 * field with this name will be added, containing a hash that will be used
	 * to check against manipulative requests.
	 *
	 * @var string
	 */
	protected $_formCSFRIdentifierSuffix = 'CSFR';

	/**
	 * The suffix that will be appended to the name of the form to arrive at a
	 * name for a form element used to check whether the form has been submitted
	 * or not.
	 *
	 * @var string
	 */
	protected static $_formSubmittedIdentifierSuffix = 'Submitted';

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Form instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->_view = Zend_Layout::getMvcInstance()->getView();

		/**
		 * set submit method and default decorators
		 */
		$this
			->setMethod(Zend_Form::METHOD_POST)
			->setDecorators(array(
				new Zend_Form_Decorator_FormElements(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dl',
				)),
				new Zend_Form_Decorator_Form(),
//				new L8M_Form_Decorator_FormHasRequiredElements(),
//				new L8M_Form_Decorator_Form_Small(),
			))
		;
	}

	/**
	 * This function is overridden as we want to automatically add CSFR and
	 * form submitted elements, whose name is generated from id attribute of
	 * which we do not know whether it is provided with options on construct.
	 * Therefore this functionality has been moved to here. Not so good, though.
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 * @return L8M_Form
	 */
	public function setAttrib($key, $value)
	{

		/**
		 * if id is set for the first time, set name too (so you wont' have to
		 * worry about it wherever you create forms)
		 */
		if ($key == 'id') {

			/**
			 * if id is already set and hidden form elements must have been
			 * added already
			 */
			if (array_key_exists($key, $this->_attribs)) {
				/**
				 * remove hidden form elements
				 */
				$this->removeElement($this->_getFormCSFRIdentifier());
				$this->removeElement($this->_getFormSubmittedIdentifier());
			}

			/**
			 * set id and name
			 */
			parent::setAttrib('id',$value);
			parent::setAttrib('name', $value);

			/**
			 * add hidden form elements
			 */
			$this->_addFormSubmittedElement()
				 ->_addFormCSFRElement();

		} else

		/**
		 * apart from id, allow only setting of attributes other than name
		 */
		if ($key!='name') {
			parent::setAttrib($key,$value);
		}

		return $this;

	}

	/**
	 * Returns TRUE if form has been submitted .
	 *
	 * @param  string $method
	 * @return bool
	 */
	public function isSubmitted($method = Zend_Form::METHOD_POST)
	{
		/**
		 * lower case method
		 */
		$method = strtolower($method);
		/**
		 * if request is either POST or GET and request method matches the one
		 * specified in calling this function, and if the value of the
		 * form element with the formSubmittedIdentifier matches the value it
		 * should have, the form has been submitted
		 */
		if (in_array($method, array(Zend_Form::METHOD_POST, Zend_Form::METHOD_GET)) &&
			$method == strtolower(Zend_Controller_Front::getInstance()->getRequest()->getMethod()) &&
			Zend_Controller_Front::getInstance()->getRequest()->getParam($this->_getFormSubmittedIdentifier()) == $this->_getFormSubmittedValue()) {

			return TRUE;
		}
		return FALSE;
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Returns the name of the hidden form element which will be used to check
	 * whether the form has been submitted.
	 *
	 * @return string
	 */
	protected function _getFormSubmittedIdentifier()
	{
		return L8M_Form::getFormSubmittedIdentifier($this->getId());
	}

	/**
	 * Returns the name of the hidden form element which will be used to check
	 * whether the form has been submitted.
	 *
	 * @return string
	 */
	public static function getFormSubmittedIdentifier($formID = NULL)
	{
		$formSubmittedIdentifier = md5($formID) . self::$_formSubmittedIdentifierSuffix;
		if (is_numeric(substr($formSubmittedIdentifier, 0, 1))) {
			$formSubmittedIdentifier = 'l8m' . $formSubmittedIdentifier;
		}

		return $formSubmittedIdentifier;
	}

	/**
	 * Returns the value of the hidden form element which will be used to check
	 * whether the form has been submitted.
	 *
	 * @return string
	 */
	protected function _getFormSubmittedValue()
	{
		return L8M_Form::getFormSubmittedValue($this->getId());
	}

	/**
	 * Returns the value of the hidden form element which will be used to check
	 * whether the form has been submitted.
	 *
	 * @return string
	 */
	public static function getFormSubmittedValue($formID = NULL)
	{
		$formSubmittedValue = md5(serialize(array(
			'formId'=>$formID,
			'formSender'=>Zend_Auth::getInstance()->getIdentity(),
		)));

		return $formSubmittedValue;
	}

	/**
	 * Adds a hidden form element to the form which will be used to check
	 * whether the form has been submitted.
	 *
	 * @return L8M_Form
	 */
	protected function _addFormSubmittedElement()
	{
		$formSubmitted = new Zend_Form_Element_Hidden($this->_getFormSubmittedIdentifier());
		$formSubmitted
			->setValue($this->_getFormSubmittedValue())
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
					'style'=>'display:none;',
				)),
			))
		;
		$this->addElement($formSubmitted);
		return $this;
	}

	/**
	 * Returns the name of the hidden form element which will be used to check
	 * against a server side stored hash - in order to protect agains CSFR.
	 *
	 * @return string
	 */
	protected function _getFormCSFRIdentifier()
	{
		$formCSFRIdentifier = $this->getId()
							. $this->_formCSFRIdentifierSuffix
		;

		return $formCSFRIdentifier;
	}

	/**
	 * Returns salt to use in creation of hash for formCSFR element
	 *
	 * @return string
	 */
	protected function _getFormCSFRSalt()
	{
		$formCSFRSalt = md5(serialize(array(
			'formId'=>$this->getId(),
			'formSender'=>Zend_Auth::getInstance()->getIdentity(),
			'formCreationTime'=>microtime(),
		)));

		return $formCSFRSalt;
	}

	/**
	 * Adds form CSFR element to form
	 *
	 * @todo   re-enable addition of CSFR element
	 * @return void
	 */
	protected function _addFormCSFRElement()
	{

		/**
		 * formCSFR
		 */
//		$formCSFR = new Zend_Form_Element_Hash($this->_getFormCSFRIdentifier());
//		$formCSFR
//			->setSalt($this->_getFormCSFRSalt())
//			->setDecorators(array(
//				new Zend_Form_Decorator_ViewHelper(),
//				new Zend_Form_Decorator_HtmlTag(array(
//					'tag'=>'dd',
//				)),
//			))
//		;
//		$this->addElement($formCSFR);

		return $this;

	}

}