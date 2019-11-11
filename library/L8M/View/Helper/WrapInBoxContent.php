<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/WrapInBoxContent.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: WrapInBoxContent.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_BoxContent
 *
 *
 */
class L8M_View_Helper_WrapInBoxContent extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Renders box content.
     *
	 * @param  string $content
	 * @return string
	 */
    public function wrapInBoxContent($content = NULL, $escape = FALSE)
    {

		ob_start();

		$content = $escape == TRUE
				 ? $this->view->escape($content)
				 : $content
		;
?>
    <div class="wrap-in-box-content">
        <div class="wrap-in-box-content-right">
<?php

			echo $content;

?>
        </div>
    </div>
<?php

        return ob_get_clean();
    }
}