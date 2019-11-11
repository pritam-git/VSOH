<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/BoxBottom.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BoxBottom.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_BoxBottom
 *
 *
 */
class L8M_View_Helper_BoxBottom extends Zend_View_Helper_Abstract
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
    public function boxBottom()
    {
    	ob_start();
?>
    <div class="box-bottom">
    	<div class="box-bottom-right"></div>
    </div>
</div>
<!-- box end -->
<?php
        return ob_get_clean();
    }
}