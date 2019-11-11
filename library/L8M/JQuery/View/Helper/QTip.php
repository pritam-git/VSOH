<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/View/Helper/QTip.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: QTip.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_JQuery_View_Helper_QTip
 *
 *
 */
class L8M_JQuery_View_Helper_QTip extends L8M_JQuery_View_Helper_Abstract
{

    /**
     *
     *
     * Class Variables
     *
     *
     */

    /**
     * Contains TRUE when head script has been added to initialize all qTip tool
     * tips on page.
     *
     * @var bool
     */
    protected static $_qTipInitialized = FALSE;

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Renders qTip.
     *
     * @param  string $selector
     * @param  string $content
     * @return string
     */
    public function qTip($selector = NULL, $content = NULL)
    {
        if ($selector &&
        	is_string($selector) &&
        	$content &&
        	is_string($content)) {
            $this->_renderHeadScript();
            $this->view->headScript()->captureStart();
?>
$(document).ready(function() {
	$("<?php echo $selector; ?>").qtip({
		content: '<?php echo $content;?>',
   		show: 'mouseover',
   		hide: 'mouseout',
   		style: {
   			name: 'light',
   			tip: true
   		},
   		position: {
      		corner: {
         		target: 'topRight',
         		tooltip: 'bottomLeft'
      		}
   		}
	});
});
<?php
            $this->view->headScript()->captureEnd();
        }
        return '';
    }

    /**
     *
     *
     * Helper Methods
     *
     *
     */

    /**
     * Renders head script.
     *
     * @return L8M_JQuery_View_Helper_QTip
     */
    protected function _renderHeadScript()
    {
        if (self::$_qTipInitialized === FALSE) {
            $this->view->jQuery()->addJavascriptFile(self::$_pluginPath . 'qtip/jquery.qtip-1.0.0-rc3.min.js');
            self::$_qTipInitialized = TRUE;
        }
        return $this;
    }
}