<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/Mail/Part/Attachment.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Attachment.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Mail_Part_Attachment
 * 
 * 
 */
class L8M_Mail_Part_Attachment extends L8M_Mail_Part 
{
    
    
    /**
     * 
     * 
     * 
     * Class Constructor
     * 
     * 
     * 
     */
    
    /**
     * Constructs L8M_Mail_Part_Attachment instance
     * 
     * @return L8M_Mail_Part
     *
     */
    public function __construct($options = NULL)
    {
        if (!$options instanceof L8M_Mail) {
            throw new L8M_Mail_Part_Attachment_Exception('When constructing an L8M_Mail_Part_Attachment instance, the parent L8M_Mail instance needs to be passed as an argument.');
        }
        $this->setParent($options);
    }    
    
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
    /**
     * Adds the file at the specified source with the specified name to the list 
     * of items, if it exists.
     *
     * @param  string $name
     * @param  string $source
     * @return L8M_Mail_Part_Attachment
     */
    public function addItem($name = NULL, $source = NULL)
    {
        /**
         * name
         */
        $name = trim($name);
        /**
         * source
         */
        if ($source===NULL) {
            $source = $name;
            $name = NULL;
        }
        if (!file_exists($source) ||
            !is_file($source) ||
            !is_readable($source)) {
            throw new L8M_Mail_Part_Attachment_Exception('Could not add attachment as source does not exist, is not readable or is not a file.');                
        }
        /**
         * name
         */
        if ($name===NULL) {
            $name = basename($source); 
        }
        /**
         * attachment
         */ 
        $attachment = new Zend_Mime_Part(file_get_contents($source));
        $attachment->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
        $attachment->encoding = Zend_Mime::ENCODING_BASE64;
        $attachment->filename = $name;
        $attachment->type = L8M_Mime::getMimeType($source);
        $this->_items[] = array('name'=>$name,
        						'size'=>L8M_Library::getSizeString(filesize($source)),
        						'extension'=>strtoupper(substr($name, strrpos($name, '.') + 1)));
        /**
         * add to parent's Zend_Mail instance
         */
        $this->getParent()->getMail()->addAttachment($attachment);
        return $this;
    }
    
    /**
     * Adds files in the specified directory as items.
     *
     * @todo   make sure last character in directory is the directory separator
     * @param  string $directory
     * @return L8M_Mail_Part_Attachment
     */
    public function addItems($directory = NULL)
    {
        $unAttachableFiles = array('.', '..');
        $directory = (string) $directory;
        if (is_string($directory) &&
            file_exists($directory) &&
            is_dir($directory)) {
            $handle = opendir($directory);
            while (FALSE!==$file = readdir($handle)) {
                $filePath = $directory . $file;
                if (!in_array($file, $unAttachableFiles) &&
                    file_exists($filePath) &&
                    is_file($filePath) &&
                    is_readable($filePath)) {
                    $this->addItem($filePath);
                }                                
            }
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
     * @param  string $mode
     * @return string
     */
    protected function _renderItems($mode = self::RENDER_TEXT)
    {
    	if (count($this->_items)>0) {
	        ob_start();
	        if ($mode==self::RENDER_HTML) {
				?>
				<!-- attachment list begin -->
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<?php
				foreach ($this->_items as $item) {
					?>
					<!-- attachment list item begin -->
					<tr>
						<td style="font-size:12px;"><?php echo $this->escape($this->utf8Decode($item['name'])); ?></td>
						<td style="font-size:12px; text-align:right;"><?php echo $this->escape($item['size']); ?>&nbsp;|&nbsp;<?php echo $this->escape($this->utf8Decode($item['extension'])); ?></td>
					</tr>
					<!-- attachment list item end -->
					<?php
				}
				?>
				</table>                                    
                <!-- attachment list end -->
				<?php	        	
	        } else 
	        if ($mode==self::RENDER_TEXT) {
                foreach ($this->_items as $item) {
    	        	echo $this->utf8Decode($item['name']) . ' ' . '(' . $item['size'] . ' | ' . $this->utf8Decode($item['extension']) . ')' . PHP_EOL;
				}	            
	        }
	        return ob_get_clean();
    	}
    	return NULL;
    }
    
}