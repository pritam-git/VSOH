<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/View/Helper/Colorbox.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Colorbox.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_JQuery_View_Helper_Colorbox
 *
 *
 */
class L8M_JQuery_View_Helper_Colorbox extends L8M_JQuery_View_Helper_Abstract
{

    /**
     *
     *
     * Class Variables
     *
     *
     */

    /**
     * Contains TRUE when head script has been added for using colorboxes.
     *
     * @var bool
     */
    protected static $_colorboxInitialized = FALSE;

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Renders colorbox.
     *
     * $options = array(
     * @param  string $id
     * @param  string $action
     * @param  array  $options
     * @return string
     */
    public function colorbox($options = array())
    {
        ob_start();

        /**
         * render head script
         */
        $layout = Zend_Layout::getMvcInstance();
        if ($layout->isEnabled()) {
            $this->_renderHeadScript();
        } else {
        	$this->_renderInlineScript();
        }

        return ob_get_clean();

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
     * @return L8M_JQuery_View_Helper_Flexigrid
     */
    protected function _renderHeadScript()
    {
        if (self::$_colorboxInitialized === FALSE) {

        	/**
        	 * add javascript and css for colorbox
        	 */
        	$this->view->jQuery()->addJavascriptFile(self::$_pluginPath . 'colorbox/colorbox/jquery.colorbox.js')
        						 ->addJavascriptFile('/js/jquery/fix/colorbox.fix.js');
			$this->view->headLink()
				->appendStylesheet('/css/screen/js/colorbox.css', 'screen')
				->appendStylesheet('/css/screen/js/colorbox.fix.css', 'screen')
			;
            self::$_colorboxInitialized = TRUE;
        }
        return $this;
    }

	/**
     * Returns inline script.
     *
     * @return void
     */
    protected function _renderInlineScript()
    {

		if (self::$_colorboxInitialized === FALSE) {

?>
<script type="text/javascript" src="<?php echo self::$_pluginPath . 'colorbox/colorbox/jquery.colorbox.js'; ?>"></script>
<script type="text/javascript" src="/js/jquery/fix/colorbox.unbind.close.js"></script>"

<style type="text/css">
    @import url(/css/screen/js/colorbox.css);
	@import url(/css/screen/js/colorbox.fix.css);
</style>
<?php

            self::$_colorboxInitialized = TRUE;
        }
        return $this;

    }
}