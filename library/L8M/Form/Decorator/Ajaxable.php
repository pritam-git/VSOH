<?php

/**
 * L8M
 *
 * @filesource /library/L8M/Form/Decorator/Ajaxable.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Ajaxable.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_Ajaxable
 *
 *
 */
class L8M_Form_Decorator_Ajaxable extends Zend_Form_Decorator_Abstract
{

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

		ob_start();
?>
<img class="ajax-load" src="/img/system/ajax.facebook.bg.gif" alt="Ajax Loader"/>
<?php

		return ob_get_clean() . $content;
	}

}