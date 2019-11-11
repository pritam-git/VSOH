<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/JEditable.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: JEditable.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_JEditable
 *
 *
 */
 class L8M_JQuery_Form_Element_JEditable extends Zend_Form_Element
 {

 	/**
 	 *
 	 *
 	 * Class Variables
 	 *
 	 *
 	 */

 	/**
 	 * Contains TRUE when head script has been added to initialize all tiny mce
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
     * Render form element
     *
     * @param  Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        if (null !== $view) {
            $this->setView($view);
        }

        /**
         * render head script
         */
        if (!self::$_jEditableInitialized) {
            $this->_renderHeadScript();
        }

        $this->getView()->headScript()->captureStart();

?>
$(document).ready(function() {
     $('.editable').editable('<?php echo $this->_getAction(); ?>');
});
<?php

        $this->getView()->headScript()->captureEnd();


        /**
         * html for form element
         */
        ob_start();

?>
<span class="editable" id="<?php echo $this->getName(); ?>"><?php echo $this->getValue(); ?></span>
<?php

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
     * @return L8M_Form_Element_TinyMCE
     */
    protected function _renderHeadScript()
    {

    	$view = $this->getView();
    	if ($view) {
    		$view->jQuery()->addJavascriptFile('/js/jquery.plugins/jeditable/jquery.jeditable.js');
    	}
    	self::$_jEditableInitialized = TRUE;
    	return $this;

    }

    /**
     * Returns the parent form.
     *
     * @return Zend_Form
     */
    protected function _getAction()
    {
    	return '/test/j-editable/format/html';
    }

 }