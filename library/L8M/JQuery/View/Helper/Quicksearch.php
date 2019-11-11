<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/View/Helper/Quicksearch.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Quicksearch.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_JQuery_View_Helper_Quicksearch
 *
 *
 */
class L8M_JQuery_View_Helper_Quicksearch extends L8M_JQuery_View_Helper_Abstract
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
    protected static $_quicksearchInitialized = FALSE;

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
    public function quicksearch($id = NULL, $idAttached = NULL, $labelText = 'Search the table', $action = NULL)
    {
        if ($id) {
            $this->_renderHeadScript();
            $this->view->headScript()->captureStart();
?>
$(document).ready(function () {
	$('<?php echo $id; ?>').quicksearch({
		position: 'before',
		attached: '<?php echo $idAttached; ?>',
		stripeRowClass: ['odd', 'even'],
		labelText: '<?php echo $labelText; ?>',
		fixWidths: true
	});
});
<?php
            $this->view->headScript()->captureEnd();
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
        if (self::$_quicksearchInitialized === FALSE) {
            $this->view->jQuery()->addJavascriptFile(self::$_pluginPath . 'quicksearch/jquery.quicksearch.js');
            self::$_quicksearchInitialized = TRUE;
        }
        return $this;
    }
}