<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/forms/ShopOrder/BillingNumber.php
 * @author     Robert Quint <rq@l8m.com>
 * @version    $Id: BillingNumber.php 201 2014-10-14 14:19:03Z nm $
 */

/**
 *
 *
 * Admin_Form_ShopOrder_BillingNumber
 *
 *
 */
class Admin_Form_ShopOrder_BillingNumber extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Admin_Form_ShopOrder_BillingNumber instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		$request = Zend_Controller_Front::getInstance()->getRequest();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formShopOrderBillingNumber');

		/**
		 * formLogin
		 */
		$formBillingNumber = new Zend_Form_Element_Text('billing_number');
		$formBillingNumber
			->setLabel('Billing Number')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Db_NoRecordExists(array(
					'table'=>'product_order',
					'field'=>'billing_number',
					'adapter'=>Zend_Registry::get('databaseDefault'),
				)),
			))
			->addDecorators(array(
				 array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class'=>'form-billing-number')))
			)
		;
		$formBillingNumber->setValue($request->getParam('id', NULL, FALSE));
		$this->addElement($formBillingNumber);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formShopOrderBillingNumberSubmit');
		$formSubmitButton
			->setLabel('Confirm')
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
		;
		$this->addElement($formSubmitButton);
	}
}