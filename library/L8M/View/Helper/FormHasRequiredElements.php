<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/FormHasRequiredElements.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: FormHasRequiredElements.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_FormHasRequiredElements
 *
 *
 */
class L8M_View_Helper_FormHasRequiredElements extends Zend_View_Helper_Abstract
{

    /**
     *
     *
     * Class Methods
     *
     *
     */

	/**
     * Render form with corresponding message if it has required elements.
     *
     * @param  string            $content
     * @param  array|Zend_Config $options
     * @return string
     */
    public function formHasRequiredElements($content = NULL, $options = NULL)
    {
    	if (!$content) {
    		return '';
    	}

    	if ($options instanceof Zend_Config) {
    		$options = $options->toArray();
    	}

    	$message = isset($options['message'])
    			 ? $options['message']
    			 : '*Form contains required elements.'
    	;

    	$class = isset($options['class'])
    		   ? ' class="' . $options['class'] . '"'
    		   : ''
    	;

    	// enabled; display label
        $content = $content
        		 . '<p'
        		 . $class
        		 . '>'
                 . $this->view->translate($message)
                 . '</p>'
        ;

        return $content;

    }
}