<?php

/**
 * L8M
 *
 * @filesource /library/PRJ/Form/Decorator/Button.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Button.php 6 2014-06-25 11:15:10Z nm $
 */

/**
 *
 *
 * PRJ_Form_Decorator_Button
 *
 *
 */
class PRJ_Form_Decorator_Button extends Zend_Form_Decorator_Abstract
{

	/**
	 * contains some string for button
	 *
	 * @var string
	 */
	private $_value = NULL;
	private $_url = NULL;

	/**
	 * Constructor
	 *
	 * @param  string $options
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($value = NULL, $url = NULL)
	{

		if ($value !== NULL &&
			is_string($value)) {

			$this->_value = $value;
		}

		if ($url !== NULL &&
			is_string($url)) {

			$this->_url = $url;
		}

	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Renders ajaxable form.
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{

		return $content . '<a class="button back" href="' . $this->_url . '">' . $this->_value . '</a>';

	}

}