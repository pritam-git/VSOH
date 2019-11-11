<?php

/**
 * L8M
 *
 *  
 * @filesource /library/L8M/View/Helper/Note.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Note.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_Note
 * 
 *
 */
class L8M_View_Helper_Note extends Zend_View_Helper_Abstract
{
	
	/**
	 * 
	 * 
	 * Class Methods
	 * 
	 * 
	 */

    /**
     * Renders content and preprends a span.note. Both are translated. 
     * No escaping.
     *
     * @param  string $note
     * @param  string $title
     * @return string
     */
    public function note($note = NULL, $title = 'Note')
    {
        if ($note!==NULL) {
            ob_start();
?>
<span class="note"><?php echo $this->view->escape($this->view->translate($title)); ?>:</span>&nbsp;
<?php

            echo $note;
            return ob_get_clean();
        }
        return NULL;
    }
}