<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/BoxTop.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BoxTop.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_BoxTop
 *
 *
 */
class L8M_View_Helper_BoxTop extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Renders box top.
     *
     * @param  string $cssClasses
     * @param  string $cssStyles
     * @return string
     */
    public function boxTop($cssClasses = NULL, $cssStyles = NULL)
    {
    	ob_start();
?>
<!-- box begin -->
<div class="box<?php echo (trim($cssClasses) ? ' ' . $this->view->escape($cssClasses) : ''); ?>"<?php echo ($cssStyles ? ' style="' . $this->view->escape($cssStyles) . '"' : ''); ?>>
    <div class="box-top">
    	<div class="box-top-right"></div>
    </div>
<?php
        return ob_get_clean();
    }
}