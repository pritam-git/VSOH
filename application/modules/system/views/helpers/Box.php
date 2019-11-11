<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/Box.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Box.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_View_Helper_Box
 *
 *
 */
class System_View_Helper_Box extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Wraps content in box, but does not escape it.
	 *
	 * @param  string $content
	 * @param  string $cssClasses
	 * @param  string $cssStyles
	 * @param  bool   $escape
	 * @return string
	 */
	public function box($content = NULL, $cssClasses = NULL, $cssStyles = NULL, $escape = FALSE)
	{
		if ($content !== NULL) {
			$cssSystem = NULL;
			if (!$this->view->layout()->isException) {
				$cssSystem = ' system';
			}
			ob_start();
?>
<!-- box begin -->
<div class="box<?php echo $cssSystem; ?><?php echo (trim($cssClasses) ? ' ' . $this->view->escape($cssClasses) : ''); ?>"<?php echo ($cssStyles ? ' style="' . $this->view->escape($cssStyles) . '"' : ''); ?>>
<?php
			echo ($escape ? $this->view->escape($content) : $content);
?>
</div>
<!-- box end -->
<?php
			return ob_get_clean();
		}
		return '';
	}

}