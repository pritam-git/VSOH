<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/View/Helper/JEditable.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: JEditable.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_JQuery_View_Helper_JEditable
 *
 *
 */
class L8M_JQuery_View_Helper_JEditable extends L8M_JQuery_View_Helper_Abstract
{

    /**
     *
     *
     * Class Variables
     *
     *
     */

    /**
     * Contains TRUE when head script has been added to initialize all jEditable
     * editors on page.
     *
     * @var bool
     */
    protected static $_jEditableInitialized = FALSE;

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Renders jEditable content.
     *
     * @param  string $id
     * @param  string $content
     * @param  string $action
     * @return string
     */
    public function jEditable($id = NULL, $content = NULL, $action = NULL)
    {
        if ($id &&
        	is_string($id)) {
            $this->_renderHeadScript();
            $this->view->headScript()->captureStart();
?>
$(document).ready(function() {
    $("#<?php echo $id; ?>").editable("<?php echo $action; ?>", {
        indicator: "<img src='/img/ajax.load.facebook.blue.bg.gif'>",
        tooltip: "<?php echo $this->view->translate('Click to edit.'); ?>",
        event: "click",
        style: "inherit"
    });
});
<?php
            $this->view->headScript()->captureEnd();
            ob_start();
?>
<span class="jEditable" id="<?php echo $id; ?>"><?php echo $this->view->escape($content); ?></span>
<?php
            return ob_get_clean();
        }
        return NULL;
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
     * @return L8M_JQuery_View_Helper_JEditable
     */
    protected function _renderHeadScript()
    {
        if (self::$_jEditableInitialized === FALSE) {
            $this->view->jQuery()->addJavascriptFile(self::$_pluginPath . 'jeditable/jquery.jeditable.mini.js');
            self::$_jEditableInitialized = TRUE;
        }
        return $this;
    }
}