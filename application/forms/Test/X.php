<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Test/X.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: X.php 28 2014-04-02 14:50:33Z nm $
 */


/**
 *
 *
 * Default_Form_Test_X
 *
 *
 */
class Default_Form_Test_X extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Test_X instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formTestX');

		/**
		 * formElement
		 */
		$formElement = new Zend_Form_Element_Radio('test1');
		$formElement
			->setLabel('Radio-Options')
			->addMultiOptions(array(
				'male' => 'Male',
				'female' => 'Female'
			))
			->setSeparator('')
		;
		$this->addElement($formElement);

		/**
		 * formElement
		 */
		$formElement = new Zend_Form_Element_Text('test2');
		$formElement
			->setLabel('Surrounding Decorator')
			->addDecorators(array(
				array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class'=>'some-class-name')))
			)
		;
		$this->addElement($formElement);


		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formUserLoginSubmit');
		$formSubmitButton
			->setLabel('Login')
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'class'=>'submit',
				)),
			))
		;
		$this->addElement($formSubmitButton);
	}
}