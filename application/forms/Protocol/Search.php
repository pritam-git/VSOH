<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Protocol/Search.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: Search.php 126 2018-11-30 12:50:56Z nm $
 */

/**
 *
 *
 * Default_Form_Protocol_Search
 *
 *
 */
class Default_Form_Protocol_Search extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Protocol_Search instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formSearchProtocol');

		/**
		 * formElement
		 */
		$formElement = new Zend_Form_Element_Text('searchProtocolInput');
		$formElement
			->setRequired(TRUE)
			->setAttrib('placeholder', $this->_view->translate('Suche', 'de') . '...')
			->setAttrib('class', 'form-control')
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formElement);

		/**
		 * formSubmitButton
		 */
		$spFormSubmitButton = new Zend_Form_Element_Submit('formSearchSubmit');
		$spFormSubmitButton
			->setLabel('Search')
			->setAttrib('class', 'btn btn-warning')
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'class'=>'submit',
				)),
			))
		;
		$this->addElement($spFormSubmitButton);
	}
}