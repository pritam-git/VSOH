<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Form/Element/Email.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Email.php 73 2014-05-14 17:21:43Z nm $
 */

/**
 *
 *
 * L8M_Form_Element_Email
 *
 *
 */
class L8M_Form_Element_Email extends Zend_Form_Element_Text
{
	/**
	 * Default form view helper to use for rendering
	 * @var string
	 */
	public $helper = 'formEmail';
}
