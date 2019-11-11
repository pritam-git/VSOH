<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/Dojo/View/Helper/ContextHelp.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ContextHelp.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Dojo_View_Helper_ContextHelp
 * 
 * 
 */
class L8M_Dojo_View_Helper_ContextHelp extends Zend_Dojo_View_Helper_CustomDijit
{
    
    /**
     * 
     * 
     * Class Variables
     * 
     * 
     */
    
    /**
     * An array of rendered context help items.  
     *
     * @var array
     */
    protected static $_items = array();
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */

    /**
     * Renders a context help style icon to which a Dojo Tooltip is attached, 
     * which is displayed on hover
     *
     * @param  string $title
     * @param  string $content
     * @return string
     */
    public function contextHelp($title = NULL, $content = NULL)
    {
        if (!$title &&
            !$content) {
            return NULL;                
        } else 
        
        if ($content==NULL) {
            $content = $title;
            $title = NULL;
        }
        
        $id = 'contextHelp' . md5(serialize(array('count'=>count(self::$_items) + 1, 
        										  'title'=>$title, 
    										  	  'content'=>$content)));
        
        self::$_items[] = array('id'=>$id,
                                'title'=>$title,
                                'content'=>$content);
        
        ob_start();
        
        ?>
        <a class="contextHelp" title="<?php echo $this->view->translate('Help'); ?>"><?php echo $this->view->translate('Help'); ?></a>
        <?php
        
        return ob_get_clean();
        
//        $value = $title . $content;
//        $params = array('dojoType'=>'dijit.Tooltip');
//        $attribs = array();
        
//        return parent::customDijit($id, $value, $params, $attribs);

    }
    
}