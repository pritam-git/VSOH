<?php 

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/Mail/Part/Contact.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Contact.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Mail_Part_Contact
 * 
 * 
 */
class L8M_Mail_Part_Contact extends L8M_Mail_Part 
{
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
    /**
     * Adds contact item to item list
     *
     * @param  string $label
     * @param  string $value
     * @return L8M_Mail_Part_Contact
     */
    public function addItem($label = NULL, $value = NULL)
    {
        if ($value===NULL) {
            $value = $label;
            $label = NULL; 
        }
        $this->_items[] = array('label'=>$label, 'value'=>$value);
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
	        if ($mode==self::RENDER_HTML) {
?>
<!-- contact list begin -->
<ul style="margin:0px; padding:0px">
<?php 
	        	foreach($this->_items as $item) {
	        	    
	        	    if (!$item['label'] &&
	        	        !$item['value']) {
?>
	<li style="font-size:12px; list-style-type:none; margin:0px; padding:0px;">&nbsp;</li>		
<?php
        	        } else	        	            
	        		/**
	        		 * without label
	        		 */
	        		if (!$item['label']) {
?>
	<li style="font-size:12px; list-style-type:none; margin:0px; padding:0px;"><span style="margin:0px; padding:0px;"><?php echo $this->escape($this->utf8Decode($item['value'])); ?></span></li>		
<?php 
	        		} else 
	        		
	        		/**
	        		 * with label
	        		 */
	        		if ($item['value']) {
?>
	<li style="font-size:12px; list-style-type:none; margin:0px; padding:0px; margin-left:60px; color:#005AAA;"><span style="color:#000000; position:absolute; margin-left:-60px;"><?php echo $this->escape($this->utf8Decode($this->translate($item['label']))); ?></span>&nbsp;<?php echo $this->escape($this->utf8Decode($item['value'])); ?></li>
<?php 	        			
	        		}  
	        		
	        	}

?>
</ul>
<!-- contact list end -->
<?php 
       		} else 
	        if ($mode==self::RENDER_TEXT) {
	        	$padLength = 0;
	        	foreach($this->_items as $item) {
	        	    $labelLength = strlen($item['label']);
	        		$padLength = max(array($padLength, $labelLength + 1));
	        	} 
	        	foreach($this->_items as $item) {
	        		if ($item['label']) {
		        		echo str_pad($this->utf8Decode($item['label']), $padLength, ' ', STR_PAD_RIGHT);
	        		}
	        		echo $this->utf8Decode($item['value']) . PHP_EOL;
	        	}
	        }
        	return ob_get_clean();
        }
        return NULL;
    }
}