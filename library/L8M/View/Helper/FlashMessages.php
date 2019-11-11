<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/View/Helper/FlashMessages.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: FlashMessages.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_FlashMessages
 * 
 * 
 */
class L8M_View_Helper_FlashMessages extends Zend_View_Helper_Abstract 
{
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
    /**
     * Renders a message or an array of messages.
     *
     * @param  string|array $messages
     * @param  string       $title
     * @return string
     */
    public function flashMessages($messages = NULL, $title = NULL)
    {
        if (!$messages) {
            return;
        }
        
        if ($title === NULL) {
            $title = $this->view->translate('Messages');
        }
        
        ob_start();
?>
<!--  flashMessages start -->
<h1><?php echo $this->view->escape($title); ?></h1>
<div class="separator"></div>
<?php echo $this->view->iconizedList($messages, L8M_View_Helper_IconizedList::STYLE_SMALL, 'info'); ?>
<!--  flashMessages end -->
<?php 
		
		return ob_get_clean();

    }
    
}