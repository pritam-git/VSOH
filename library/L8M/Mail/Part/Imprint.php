<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/Mail/Part/Imprint.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Imprint.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Mail_Part_Imprint
 *
 *  
 */
class L8M_Mail_Part_Imprint extends L8M_Mail_Part 
{
    
    /**
     * 
     * 
     * Class Constants
     * 
     * 
     */
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_FULL = 'full';
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
    /**
     * Adds item to the list of imprint items 
     *
     * @param  string               $item
     * @param  string               $position
     * @return L8M_Mail_Part_Imprint
     */
    public function addItem($item = NULL, $position = self::POSITION_LEFT)
    {
        if (!is_string($item)) {
            throw new L8M_Mail_Part_Imprint_Exception('Could not add item as it needs to be a string.');
        }
        $item = strip_tags($item);
        $item = trim($item);
        if ($item) {
            $this->_items[] = array('value'=>$item,
                                    'position'=>$position);
        }
        return $this;
    }
    
    /**
     * 
     * 
     * Render Methods
     * 
     * 
     */
    
	/**
     * Renders items of this mail part.
     * 
     * @todo   functionality
     * @param  string $mode
     * @return string
     */
    protected function _renderItems($mode = self::RENDER_TEXT)
    {
        if (count($this->_items)>0) {
	    	ob_start();
	        if ($mode == self::RENDER_HTML) {
	        	foreach($this->_items as $item) {
	        		?>
	        		<?php
	        	}
	        } else 
	        if ($mode == self::RENDER_TEXT) {
	        	foreach($this->_items as $item) {
	        		?>
	        		<?php
	        	}
	        }
	        return ob_get_clean();
        }
        return NULL;			       
    }    
    
}