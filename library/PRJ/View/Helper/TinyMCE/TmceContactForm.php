<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/TmceContactForm.php
 * @author     Santino Lange <sl@l8m.com>
 * @version    $Id: TmceContactForm.php 16 2014-07-10 11:36:38Z sl $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceContactForm
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceContactForm extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a contentBoxes.
	 *
	 * @return string
	 */
	public function tmceContactForm()
	{

		$content = NULL;

		$form = new Default_Form_Contact_Form();
		$form
			->addDecorator(new L8M_Form_Decorator_FormHasRequiredElements())
			->setAction($this->view->url(array('module'=>'default', 'controller'=>'contact', 'action'=>'index')))
		;

		$content.= $form;

		$content = '<div class="content">' . $content . '</div>';

		return $content;

	}
	/* public function tmceContactForm()
	{

		$content = NULL;

		$form = new Default_Form_Contact_Form();
		$form
			->addDecorator(new L8M_Form_Decorator_FormHasRequiredElements())
			->setAction($this->view->url(array('module'=>'default', 'controller'=>'contact', 'action'=>'index')))
		;

		$content.= $form;

		$content = '<div class="content">' . $content . '</div>';

		return $content;

	} */

}