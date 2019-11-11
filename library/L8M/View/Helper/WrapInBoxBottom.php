<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/WrapInBoxBottom.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: WrapInBoxBottom.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_WrapInBoxBottom
 *
 *
 */
class L8M_View_Helper_WrapInBoxBottom extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Renders box bottom.
     *
     * @return string
     */
    public function wrapInBoxBottom()
    {
    	ob_start();

?>
    <div class="wrap-in-box-bottom">
    	<div class="wrap-in-box-bottom-right"></div>
    </div>
</div>
<!-- box end -->
<?php

        return ob_get_clean();
    }
}