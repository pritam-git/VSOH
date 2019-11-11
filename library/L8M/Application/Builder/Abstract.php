<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/Builder/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 436 2015-09-29 09:02:07Z nm $
 */

/**
 *
 *
 * L8M_Application_Builder_Abstract
 *
 *
 */
abstract class L8M_Application_Builder_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of options.
	 *
	 * @var array
	 */
	protected $_options = NULL;

	/**
	 * An array of messages.
	 *
	 * @var array
	 */
	protected $_messages = array();

	/**
	 * An array of required directories.
	 *
	 * @var array
	 */
	protected $_requiredDirectories = NULL;

	/**
	 * Flags an error
	 *
	 * @var boolean
	 */
	static protected $_hasError = FALSE;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Application_Builder_Abstract instance.
	 *
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($options = NULL)
	{
		if ($options) {
			$this->setOptions($options);
		}
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Sets options.
	 *
	 * @param  array|Zend_Config $options
	 * @return L8M_Application_Builder_Abstract
	 */
	public function setOptions($options = NULL)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}

		if (!is_array($options)) {
			throw new L8M_Application_Builder_Exception('Options need to be specified as an array or a Zend_Config instance.');
		}

		$this->_options = $options;
		return $this;
	}

	/**
	 * Performs a build.
	 *
	 * @return L8M_Application_Builder_Abstract
	 */
	public function build($options = NULL)
	{
		if ($options) {
			$this->setOptions($options);
		}

		$this->_init();
		$this->_prepare();
		$this->_buildComponents();

		return $this;
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Adds message.
	 *
	 * @param  string $message
	 * @return L8M_Application_Builder_Abstract
	 */
	public function addMessage($message = NULL, $class = NULL)
	{
		if (!$message ||
			!is_string($message)) {
		    throw new L8M_Application_Builder_Exception('Message needs to be specified as a string.');
		}

		if ($class &&
			!is_string($class)) {
			throw new L8M_Application_Builder_Exception('If specified, class needs to be a string.');
		}

		$this->_messages[] = array(
			'class'=>$class,
			'value'=>$message,
		);

		if ($class == 'error') {
			self::$_hasError = TRUE;
		}

		return $this;
	}

	/**
	 * Adds messages.
	 *
	 * @param  array $messages
	 * @return L8M_Application_Builder_Abstract
	 */
	public function addMessages($messages = NULL)
	{
		if (is_array($messages) &&
			count($messages)>0) {

			$this->_messages = array_merge($this->_messages, $messages);

		}

		return $this;
	}

	/**
	 * Returns messages.
	 *
	 * @return array
	 */
	public function getMessages()
	{
		return $this->_messages;
	}

	/**
	 * Returns TRUE if error happened.
	 *
	 * @return boolean
	 */
	public function hasError()
	{
		return self::$_hasError;
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Creates required directories, if they do not exist, in the specified
	 * path.
	 *
	 * @param string $path
	 */
	protected function _createRequiredDirectories($path = NULL)
	{

		if (!is_string($path)) {
			throw new L8M_Application_Builder_Exception('Path needs to be specified as string.');
		}

		/**
		 * create directories required for a module
		 */
		if (count($this->_requiredDirectories)>0) {
			foreach($this->_requiredDirectories as $requiredDirectory) {
				$directory = $path
						   . DIRECTORY_SEPARATOR
						   . str_replace('/', DIRECTORY_SEPARATOR, $requiredDirectory)
				;
				$this->_createDirectory($directory);
			}
		}
	}

	/**
	 * Returns TRUE when the specified directory has been created.
	 *
	 * @param  string $directory
	 * @return bool
	 */
	protected function _createDirectory($directory = NULL, $mode = 755, $silent = FALSE)
	{
		if (!$directory ||
			!is_string($directory)) {
			throw new L8M_Application_Builder_Exception('Directory needs to be specified as string.');
		}

		$mode = (int) $mode;
		$silent = (bool) $silent;
		$created = FALSE;

		/**
		 * @todo sanity checks?
		 */
		if (!file_exists($directory) ||
			!is_dir($directory)) {
			$created = mkdir($directory, $mode, TRUE);
		}

		$short = $this->_getRelativePath($directory);

		if (!$silent) {
			if ($created) {
				$this->addMessage('created directory <code class="folder">' . $short . '</code>', 'add');
			} else {
				$this->addMessage('skipped creating directory <code class="folder">' . $short . '</code>', 'information semi');
			}
		}

		return $created;
	}

	/**
	 * Returns relative path.
	 *
	 * @param string $filePath
	 * @param string $basePath
	 */
	protected function _getRelativePath($filePath = NULL, $basePath = BASE_PATH)
	{
		if (!$filePath ||
			!is_string($filePath) ||
			!$basePath ||
			!is_string($basePath)) {
			throw new L8M_Application_Builder_Exception('File and base paths need to be specified as strings.');
		}

		$filePath = str_replace(
			$basePath,
			'',
			$filePath
		);

		$filePath = preg_replace('@\\\@', '/', $filePath);

		return $filePath;
	}

	/**
	 * Returns path to the specified module.
	 *
	 * @param  string $moduleName
	 * @return string
	 */
	protected function _getModulePath($moduleName = NULL)
	{
		if (!$moduleName ||
			!is_string($moduleName)) {
			throw new L8M_Application_Builder_Exception('Module name needs to be specified as string.');
		}

		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_CamelCaseToDash())
		;

		$moduleName = $filter->filter($moduleName);

		if ($moduleName == 'default') {
			$modulePath = APPLICATION_PATH;
		} else {
			$modulePath = APPLICATION_PATH
						. DIRECTORY_SEPARATOR
						. 'modules'
						. DIRECTORY_SEPARATOR
						. $moduleName
			;
		}

		return $modulePath;

	}

	/**
	 * Returns path to module specific configuration.
	 *
	 * @param  string $moduleName
	 * @return string
	 */
	protected function _getModuleConfigPath($moduleName = NULL)
	{
		if (!$moduleName ||
			!is_string($moduleName)) {
			throw new L8M_Application_Builder_Exception('Module name needs to be specified as string.');
		}

		$moduleConfigPath = $this->_getModulePath($moduleName)
						  . DIRECTORY_SEPARATOR
						  . 'configs'
						  . DIRECTORY_SEPARATOR
						  . 'module.ini'
	  	;

		return $moduleConfigPath;
	}

	/**
	 * Returns bootstrrap class name for the specified module.
	 *
	 * @param  string $moduleName
	 * @return string
	 */
	protected function _getBootstrapClassName($moduleName = NULL)
	{
		if (!$moduleName ||
			!is_string($moduleName)) {
			throw new L8M_Application_Builder_Exception('Module name needs to be specified as string.');
		}

		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_DashToCamelCase())
		;

		$moduleName = $filter->filter($moduleName);

		if ($this->_options['moduleName'] == 'default') {
			$bootstrapClassName = 'Bootstrap';
		} else {
			$bootstrapClassName = $moduleName
								 . '_'
								 . 'Bootstrap'
			;
		}

		return $bootstrapClassName;

	}

	/**
	 * Returns the name of the class the bootstrap class for the specified
	 * module extends.
	 *
	 * @param  string $moduleName
	 * @return string
	 */
	protected function _getBootstrapClassExtendedClassName($moduleName = NULL)
	{
		if (!$moduleName ||
			!is_string($moduleName)) {
			throw new L8M_Application_Builder_Exception('Module name needs to be specified as string.');
		}

		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_DashToCamelCase())
		;

		$moduleName = $filter->filter($moduleName);

		$bootstrapClassExtendedClassName = $filter->filter($moduleName) == 'default'
					   				 ? 'Zend_Application_Bootstrap_Bootstrap'
					   				 : 'Zend_Application_Module_Bootstrap'
		;

		return $bootstrapClassExtendedClassName;
	}

	/**
	 * Returns the name of the bootstrap class file.
	 *
	 * @return string
	 */
	protected function _getBootstrapClassFileName()
	{
		$bootstrapClassFileName = 'Bootstrap.php';
		return $bootstrapClassFileName;
	}

	/**
	 * Returns bootstrap class file path for the specified module.
	 *
	 * @param  string $moduleName
	 * @return string
	 */
	protected function _getBootstrapClassFilePath($moduleName = NULL)
	{
		if (!$moduleName ||
			!is_string($moduleName)) {
			throw new L8M_Application_Builder_Exception('Module name needs to be specified as string.');
		}

		$bootstrapClassFilePath = $this->_getModulePath($moduleName)
								. DIRECTORY_SEPARATOR
								. $this->_getBootstrapClassFileName()
		;

		return $bootstrapClassFilePath;
	}


	/**
	 * Returns controller class name for the specified controller in the
	 * specified module.
	 *
	 * @param  string $controllerName
	 * @param  string $moduleName
	 * @return string
	 */
	protected function _getControllerClassName($controllerName = NULL, $moduleName = NULL)
	{
		if (!$controllerName ||
			!is_string($controllerName) ||
			!$moduleName ||
			!is_string($moduleName)) {
			throw new L8M_Application_Builder_Exception('Controller and module names need to be specified as strings.');
		}

		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_DashToCamelCase())
		;

		$controllerName = $filter->filter($controllerName);
		$moduleName = $filter->filter($moduleName);


		if ($moduleName == 'default') {
			$controllerClassName = $controllerName
								 . 'Controller'
			;
		} else {
			$controllerClassName = $moduleName
								 . '_'
								 . $controllerName
								 . 'Controller'
			;
		}

		return $controllerClassName;

	}

	/**
	 * Returns the name of the controller class file for the specified
	 * controller name.
	 *
	 * @param  string $controllerName
	 * @return string
	 */
	protected function _getControllerClassFileName($controllerName = NULL)
	{
		if (!$controllerName ||
			!is_string($controllerName)) {
			throw new L8M_Application_Builder_Exception('Controller name needs to be specified as string.');
		}

		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_DashToCamelCase())
		;

		$controllerName = $filter->filter($controllerName);

		$controllerClassFileName = $controllerName
								 . 'Controller.php'
		;

		return $controllerClassFileName;

	}

	/**
	 * Returns controller path for the specified module.
	 *
	 * @param  string $moduleName
	 * @return string
	 */
	protected function _getControllerPath($moduleName = NULL)
	{
		if (!$moduleName ||
			!is_string($moduleName)) {
			throw new L8M_Application_Builder_Exception('Module name needs to be specified as string.');
		}

		$controllerPath = $this->_getModulePath($moduleName)
						. DIRECTORY_SEPARATOR
						. 'controllers'
		;

		return $controllerPath;
	}

	/**
	 * Returns controller file path for the specified module and controller.
	 *
	 * @param  string $controllerName
	 * @param  string $moduleName
	 * @return string
	 */
	protected function _getControllerClassFilePath($moduleName = NULL, $controllerName = NULL)
	{
		if (!$controllerName ||
			!is_string($controllerName) ||
			!$moduleName ||
			!is_string($moduleName)) {
			throw new L8M_Application_Builder_Exception('Controller and module names need to be specified as strings.');
		}

		$controllerClassFilePath = $this->_getControllerPath($moduleName)
								 . DIRECTORY_SEPARATOR
								 . $this->_getControllerClassFileName($controllerName)
		;

		return $controllerClassFilePath;
	}

	/**
	 * Returns path to view script directory for the specified module and
	 * controller.
	 *
	 * @param  string $moduleName
	 * @param  string $controllerName
	 * @return string
	 */
	protected function _getViewScriptPath($moduleName = NULL, $controllerName = NULL)
	{
		if (!$moduleName ||
			!is_string($moduleName) ||
			!$controllerName ||
			!is_string($controllerName)) {
			throw new L8M_Application_Builder_Exception('Controller and module names need to be specified as strings.');
		}

		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_CamelCaseToDash())
		;

		$controllerName = $filter->filter($controllerName);

		$viewScriptPath = $this->_getModulePath($moduleName)
						. DIRECTORY_SEPARATOR
						. 'views'
						. DIRECTORY_SEPARATOR
						. 'scripts'
						. DIRECTORY_SEPARATOR
						. $controllerName
		;

		return $viewScriptPath;
	}

	/**
	 * Returns name of view script file.
	 *
	 * @param  string $actionName
	 * @param  string $actionContext
	 * @return string
	 */
	protected function _getViewScriptFileName($actionName = NULL, $actionContext = NULL)
	{
		if (!$actionName ||
			!is_string($actionName)) {
			throw new L8M_Application_Builder_Exception('Action name needs to be specified as string.');
		}

		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_CamelCaseToDash())
		;

		$actionName = $filter->filter($actionName);
		$actionContext  = $actionContext == 'default'
						? ''
						: '.' . $actionContext
		;

		$viewScriptFileName = $actionName
							. $actionContext
							. '.phtml'
		;

		return $viewScriptFileName;
	}

	/**
	 * Returns full path to a view script for the specified module, controller,
	 * action and action context.
	 *
	 * @param  string $moduleName
	 * @param  string $controllerName
	 * @param  string $actionName
	 * @param  string $actionContext
	 * @return string
	 */
	protected function _getViewScriptFilePath($moduleName = NULL, $controllerName = NULL, $actionName = NULL, $actionContext = NULL)
	{
		if (!$moduleName ||
			!is_string($moduleName) ||
			!$controllerName ||
			!is_string($controllerName) ||
			!$actionName ||
			!is_string($actionName) ||
			!$actionContext ||
			!is_string($actionContext)) {
			throw new L8M_Application_Builder_Exception('Module, controller, action names and action context need to be specified as strings.');
		}

		$viewScriptFilePath = $this->_getViewScriptPath($moduleName, $controllerName)
							. DIRECTORY_SEPARATOR
							. $this->_getViewScriptFileName($actionName, $actionContext)
		;

		return $viewScriptFilePath;

	}

	/**
	 * Returns form class name for the specified module, model and action.
	 *
	 * @param  string $moduleName
	 * @param  string $modelName
	 * @param  string $actionName
	 * @return string
	 */
	protected function _getFormClassName($moduleName = NULL, $modelName = NULL, $actionName = NULL)
	{
		if (!$moduleName ||
			!is_string($moduleName) ||
			($modelName &&
			!is_string($modelName)) ||
			!$actionName ||
			!is_string($actionName)) {
			throw new L8M_Application_Builder_Exception('Module and action name need to be specified as string. If model name is specified, it needs to be a string, too.');
		}

		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_DashToCamelCase())
		;

		$moduleName = $filter->filter($moduleName);
		$modelName = $filter->filter($modelName);
		$actionName = $filter->filter($actionName);

		$formClassName = $moduleName
					   . '_Form_'
					   . ($modelName ? $modelName . '_' : '')
					   . $actionName
		;

		return $formClassName;

	}

	/**
	 * Returns name of form class file.
	 *
	 * @param  string $actionName
	 * @param  string $actionContext
	 * @return string
	 */
	protected function _getFormClassFileName($actionName = NULL)
	{
		if (!$actionName ||
			!is_string($actionName)) {
			throw new L8M_Application_Builder_Exception('Action name needs to be specified as string.');
		}

		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_DashToCamelCase())
		;

		$actionName = $filter->filter($actionName);

		$formClassFileName = $actionName
						   . '.php'
		;

		return $formClassFileName;
	}

	/**
	 * Returns full path to the form class for the specified module, model and action.
	 *
	 * @param  string $moduleName
	 * @param  string $modelName
	 * @param  string $actionName
	 * @return string
	 */
	protected function _getFormClassFilePath($moduleName = NULL, $modelName = NULL, $actionName = NULL)
	{
		if (!$moduleName ||
			!is_string($moduleName) ||
			($modelName &&
			!is_string($modelName)) ||
			!$actionName ||
			!is_string($actionName)) {
			throw new L8M_Application_Builder_Exception('Module and action name need to be specified as string. If model name is specified, it needs to be a string, too.');
		}

		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_DashToCamelCase())
		;

		$modelName = $modelName
				   ? $filter->filter($modelName)
				   : ''
		;

		$actionName = $filter->filter($actionName);

		$formClassFilePath = $this->_getModulePath($moduleName)
				   		   . DIRECTORY_SEPARATOR
						   . 'forms'
						   . DIRECTORY_SEPARATOR
						   . ($modelName ? $modelName . DIRECTORY_SEPARATOR : '')
						   . $this->_getFormClassFileName($actionName)
		;

		return $formClassFilePath;

	}

	/**
	 * Prepares build.
	 *
	 * Use this method in extending classes to prepare the build, i.e., to
	 * create directories, if necessary, etc.
	 *
	 * @return void
	 */
	protected function _prepare()
	{
		if (isset($this->_options['moduleName'])) {
			$modulePath = $this->_getModulePath($this->_options['moduleName']);
			$this->_createRequiredDirectories($modulePath);
		}
	}

	/**
	 *
	 *
	 * Abstract Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Application_Builder_Abstract instance.
	 *
	 * Use this method in extending classes  to perform a check on the options
	 * that have been set and to initialize the L8M_Application_Builder_Abstract
	 * instance.
	 *
	 * @return void
	 */
	abstract protected function _init();

	/**
	 * Builds components.
	 *
	 * @return void
	 */
	abstract protected function _buildComponents();

}