<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/BoxContent.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BoxContent.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_BoxContent
 *
 *
 */
class L8M_View_Helper_BoxContent extends Zend_View_Helper_Abstract
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
    public function boxContent($content = NULL, $escape = FALSE)
    {
    	ob_start();
?>
    <div class="box-content">
        <div class="box-content-right">
            <?php echo ($escape ? $this->view->escape($content) : $content); ?>
        </div>
    </div>
<?php
        return ob_get_clean();
    }
}