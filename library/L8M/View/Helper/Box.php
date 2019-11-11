<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/Box.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Box.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_Box
 *
 *
 */
class L8M_View_Helper_Box extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Renders box.
     *
     * @param  string $content
     * @param  string $cssClasses
     * @param  string $cssStyles
     * @return string
     */
    public function box($content = NULL, $cssClasses = NULL, $cssStyles = NULL, $escape = FALSE)
    {
    	ob_start();

		$content = $escape == TRUE
				 ? $this->view->escape($content)
				 : $content
		;

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
<div class="box<?php echo $cssClasses; ?>"<?php echo $cssStyles; ?>>
<?php

		echo $content;

?>
</div>
<!-- box end -->
<?php
        return ob_get_clean();
    }
}