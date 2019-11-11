<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/Template/Parser.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Parser.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Template_Parser
 * 
 * 
 */
class L8M_Template_Parser 
{
    
    /**
     * 
     * 
     * Class Variables
     * 
     * 
     */
    
    /**
     * An L8M_Template_Parser instance (Singleton)
     *
     * @var L8M_Template_Parser
     */
    protected static $_instance = NULL;
    
    /**
     * A Zend_Translate instance
     *
     * @var Zend_Translate
     */
    protected static $_translate = NULL;
    
    /**
     * An array of cached templates
     *
     * @var array
     */
    protected $_templateCache = array();
    
    /**
     * A string representing a directory which will be accessed for retrieving
     * templates
     *
     * @var string
     */
    protected $_templateDirectory = NULL;
    
    /**
     * 
     * 
     * Class Constructor
     * 
     * 
     */    
    
    /**
     * Constructs L8M_Template_Parser instance.
     * 
     * @return void
     */
    protected function __construct()
    {
        
    }
    
    /**
     * 
     * 
     * Setter Methods
     * 
     * 
     */
    
    /**
     * Sets template directory.
     *
     * @param  string $path
     * @return L8M_Template_Parser
     */
    public function setTemplateDirectory($directory)
    {
        if (!file_exists($directory)) {
            throw new L8M_Template_Parser_Exception('Could not set template directory as it does not exist.');
        }
        if (!is_dir($directory)) {
            throw new L8M_Template_Parser_Exception('Could not set template directory as it does not exist.');
        }
        if (!is_readable($directory)) {
            throw new L8M_Template_Parser_Exception('Could not set template directory as it is not readable.');
        }
        $this->_templatePath = $directory;
        return $this;
    }

    /**
     * 
     * 
     * Getter Methods
     * 
     * 
     */
    
	/**
     * Returns L8M_Template_Parser instance (Singleton).
     *
     * @return L8M_Template_Parser
     */
    public static function getInstance()
    {
        if (self::$_instance===NULL) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }    
    
    /**
     * Returns path in which templates reside.
     *
     * @return string
     */
    public function getTemplateDirectory()
    {
        if (!$this->_templateDirectory &&
            defined('APPLICATION_PATH')) {
            return APPLICATION_PATH . 'views' . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR;
        }
        return $this->_templateDirectory;
    }    
    
	/**
     * Returns template from cache or file system.
     *
     * @param  string $template
     * @return string
     */
    public function getTemplate($template = NULL)
    {
        
        $template = (string) $template;
        if (array_key_exists($template, $this->_templateCache)) {
            return $this->_templateCache[$template];
        }
        $templatePath = $this->getTemplateDirectory() . $template;
        if (!file_exists($templatePath)) {
            throw new L8M_Template_Parser_Exception('Could not retrieve template "' . $template . '" from file system as it does not exist.');        
        }
        if (!is_file($templatePath)) {
            throw new L8M_Template_Parser_Exception('Could not retrieve template "' . $template . '" from file system as it is not a file.');
        }
        if (!is_readable($templatePath)) {
            throw new L8M_Template_Parser_Exception('Could not retrieve template "' . $template . '" from file system as it is not readable.');
        }
        $templateContent = file_get_contents($templatePath);
        $this->_templateCache[$template] = $templateContent;
        return $templateContent;
    }    
    
	/**
     * 
     * 
     * Class Methods
     * 
     * 
     */    
    
    /**
     * Renders the specified template with the specified variables.
     *
     * @param  string $name
     * @param  array  $variables
     * @return string
     */
    public function render($template = NULL, $variables = array())
    {
        /**
         * template is provided as a string
         */
		if (preg_match('/^</', trim($template))) {
			$content = $template;
		} else
	    /**
	     * template is provided as name of a template
	     * 
	     * @todo enhance regular expression to match view scripts
	     */
		if (preg_match('/^[a-z0-9_\.-]*$/i', $template)) {
		    $content = $this->getTemplate($template);
		}
		/**
		 * no content
		 */
		if ($content===NULL) {
		    throw new L8M_Template_Parser_Exception('Content of retrieved template is empty.');
		}
        /**
         * make variables locally available
         */
		if (is_array($variables)) {
			foreach ($variables as $name=>$value) {
				if (preg_match('/^[a-z_]+[a-z_0-9]*$/i', $name) &&
				    !in_array($name, get_class_vars(get_class($this)))) {
					$this->{$name} = $value;
				}
			}
		}
        /**
         * render
         */
        ob_start();
        /**
         * @todo fix parse errors, possibly due to missing closing PHP tag 
         * 		 (Zend Code Conventions). Zend_View_Helper_Partial does not eval,
         *       but includes the partials/templates 
         */
        eval('?>' . $content . '<?');
        return ob_get_clean();
    }
    
    /**
     * Mimicks Zend_View_Helper_Escape.
     *
     * @param  string $content
     * @param  string $quotestyle
     * @param  string $charset
     * @return string
     */
    public function escape($content = NULL, $quotestyle = NULL, $charset = NULL) 
    {
        return htmlentities($content, $quotestyle, $charset);
    }
    
    /**
     * Mimicks Zend_View_Helper_Partial.
     *
     * @todo  account for modules
     * @param string $name
     * @param string $module
     * @param string $model
     */
    public function partial($name = NULL, $module = NULL, $model = NULL)
    {
        if (func_num_args()==2) {
            $model = $module;
            $module = NULL;
        }
        return $this->render($name, $model);
    }
    
    /**
     * Mimicks Zend_View_Helper_Translate.
     *
     * @param  string             $message
     * @param  string|Zend_Locale $locale
     * @return string
     */
    public function translate($message = NULL, $locale = NULL)
    {
        if (self::$_translate===NULL) {
            if (Zend_Registry::isRegistered('Zend_Translate') &&
                (NULL!=$translate = Zend_Registry::get('Zend_Translate')) &&
                $translate instanceof Zend_Translate) {
                self::$_translate = $translate;
            } else {
                self::$_translate = FALSE;
            }
        }
        if (self::$_translate!==FALSE) {
            return self::$_translate->getAdapter()->translate($message, $locale);
        }
        return $message;
    }
    
}