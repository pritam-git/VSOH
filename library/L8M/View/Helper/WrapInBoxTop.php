<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/BoxTop.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: WrapInBoxTop.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_WrapInBoxTop
 *
 *
 */
class L8M_View_Helper_WrapInBoxTop extends Zend_View_Helper_Abstract
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
    public function wrapInBoxTop($cssClasses = NULL, $cssStyles = NULL)
    {
    	ob_start();

    		$cssClasses = trim($cssClasses)
    					? ' ' . $this->view->escape($cssClasses)
    					: ''
    		;

    		$cssStyles = trim($cssStyles)
    				   ? ' style="' . $this->view->escape($cssStyles) . '"'
    				   : ''
			;

?>
<!-- box begin -->
<div class="wrap-in-box<?php echo $cssClasses; ?>"<?php echo $cssStyles; ?>>
    <div class="wrap-in-box-top">
    	<div class="wrap-in-box-top-right"></div>
    </div>
<?php
        return ob_get_clean();
    }
}