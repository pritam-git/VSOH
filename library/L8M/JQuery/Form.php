<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Form.php 27 2014-04-02 14:29:24Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form
 *
 *
 */
class L8M_JQuery_Form extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_JQuery_Form instance
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();
		ZendX_JQuery::enableForm($this);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Adds client side form validation so you won't have to take care of it in
	 * the view, and renders form
	 *
	 * @param  Zend_View_Interface $view
	 * @return string
	 */
	public function render(Zend_View_Interface $view = null)
	{
		/**
		 * set jQueryEnabled flag
		 */
		Zend_Registry::set('jQueryEnabled',TRUE);
		return parent::render($view);
	}

}