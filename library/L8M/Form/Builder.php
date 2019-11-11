<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Form/Builder.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Form_Builder
 *
 *
 */
class L8M_Form_Builder
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An L8M_Form_Builder instance.
	 *
	 * @var L8M_Form_Builder
	 */
	protected static $_builderInstance = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Form_Builder instance.
	 *
	 * @return void
	 */
	protected function __construct()
	{

	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns L8M_Form_Builder instance.
	 *
	 * @return void
	 */
	public static function getInstance()
	{
		if (self::$_builderInstance === NULL) {
			self::$_builderInstance = new self;
		}
		return self::$_builderInstance;
	}

	/**
	 * Builds with options.
	 *
	 * $options = array(
			'modules'=>array(
				array(
					'moduleName'=>'system',
					'models'=>array(
						array(
							'modelName'=>'User',
							'actions'=>array(
								array(
									'actionName'=>'edit',
								),
								array(
									'actionName'=>'create',
								),
								array(
									'actionName'=>'delete',
								),
							),
						),
					),
				),
			),
		);
	 *
	 *
	 * @param array|Zend_Config $options
	 */
	public function build($options = NULL)
	{

		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}

		if (!is_array($options)) {
			throw new L8M_Form_Builder_Exception('Options need to be specified as an array or a Zend_Config instance.');
		}

		if (!isset($options['modules']) ||
			count($options['modules']) == 0) {
			throw new L8M_Form_Builder_Exception('Key "modules" needs to be present in options');
		}

		foreach($options['modules'] as $moduleOptions) {
			$this->buildModule($moduleOptions);
		}

		$this->_addMessage('finished building modules', 'bricks');

	}

	/**
	 * Builds module form from options.
	 *
	 * @param array|Zend_Config $options
	 */
	public function buildModule($options = NULL)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}

		if (!is_array($options)) {
			throw new L8M_Form_Builder_Exception('Options need to be specified as an array or a Zend_Config instance.');
		}

		if (!isset($options['moduleName']) ||
			$options['moduleName'] == '') {
			throw new L8M_Form_Builder_Exception('Key "moduleName" needs to be present in options.');
		}

		if (isset($options['models']) &&
			count($options['models']) > 0) {

			foreach($options['models'] as $modelOptions) {

				$modelOptions = array_merge(array('moduleName'=>$options['moduleName']), $modelOptions);

				foreach($modelOptions['actions'] as $action) {
					$formOptions = array(
						'moduleName'=>$modelOptions['moduleName'],
						'modelName'=>$modelOptions['modelName'],
						'actionName'=>$action['actionName'],
					);
					$this->buildForm($formOptions);
				}

			}
		}

		$this->_addMessage('finished building forms for module <code>' . $options['moduleName'] . '</code>', 'bricks');

	}

	/**
	 * Builds form from options.
	 *
	 * @param array|Zend_Config $options
	 */
	public function buildForm($options = NULL)
	{

		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}

		if (!is_array($options)) {
			throw new L8M_Application_Form_Exception('Options need to be specified as an array or a Zend_Config instance.');
		}

		if (!isset($options['moduleName'])) {
			throw new L8M_Application_Form_Exception('Key "moduleName" needs to be present in options.');
		}

		if (!isset($options['modelName'])) {
			throw new L8M_Application_Form_Exception('Key "modelName" needs to be present in options.');
		}

		if (!isset($options['actionName']) ||
			count($options['actionName']) == 0) {
			throw new L8M_Application_Form_Exception('Key "actionName" needs to be present in options');
		}

		/**
		 * filter
		 */
		$filter = new Zend_Filter();
		$filter->addFilter(new Zend_Filter_Word_DashToCamelCase());

		/**
		 * className
		 */
		$className = $filter->filter($options['moduleName']) . '_' . 'Form_' . $filter->filter($options['modelName']) . '_' . $filter->filter($options['actionName']);

		/**
		 * classFilename
		 */
		$classFileName = $filter->filter($options['actionName']) . '.php';

		/**
		 * classPath
		 */
		$classPath = APPLICATION_PATH . ($options['moduleName'] != 'default' ? 'modules' . DIRECTORY_SEPARATOR . $options['moduleName'] . DIRECTORY_SEPARATOR : '') . 'forms' . DIRECTORY_SEPARATOR . $options['modelName'] . DIRECTORY_SEPARATOR;

		/**
		 * docBlock
		 */
		$docBlock = new L8M_CodeGenerator_Php_Docblock(array(
		    'shortDescription' => 'Initializes ' . $className . ' instance.',
		    'tags'             => array(
		        array(
		            'name'        => 'return',
		            'description' => 'void',
		        ),
		    ),
		));

		/**
		 * initialization method
		 */
		$initializationMethod = new Zend_CodeGenerator_Php_Method();
		$initializationMethod
			->setName('init')
			->setDocblock($docBlock)
			->setBody('parent::init();')
			->setVisibility(Zend_CodeGenerator_Php_Method::VISIBILITY_PUBLIC)
		;

		/**
		 * docBlock
		 */
		$docBlock = new L8M_CodeGenerator_Php_Docblock();
		$docBlock
			->setLongDescription('This form has been built with L8M_Form_Builder.')
	 		->setTags(array(
	 			array(
			 		'name'=>'filesource',
			 		'description'=>'',
			 	),
			 	array(
			 		'name'=>'author',
			 		'description'=>'Norbert Marks <nm@l8m.com>',
			 	),
			 	array(
			 		'name'=>'since',
			 		'description'=>date('Y-m-d H:i:s'),
			 	),
			 	array(
			 		'name'=>'version',
			 		'description'=>'$Id' . '$',
			 	),
			 ))
		;

		/**
		 * class
		 */
		$class = new Zend_CodeGenerator_Php_Class();
		$class
			->setName($className)
			->setDocblock($docBlock)
			->setExtendedClass('L8M_Form')
			->setMethods(array($initializationMethod))
		;

		/**
		 * filePath
		 */
		$filePath = $classPath . $classFileName;

		/**
		 * file
		 */
		if (is_string($classPath) &&
			L8M_Library::directoryExists($classPath, TRUE) &&
			!file_exists($filePath)) {

			$file = new Zend_CodeGenerator_Php_File();
			$file
				->setClass($class)
				->setFilename($filePath)
				->write()
			;

			$this->_addMessage('finished building form <code>' . $className . '</code>', 'form');

		} else {

			$this->_addMessage('skipped building form <code>' . $className . '</code>', 'form disabled');

		}

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
	 * @return L8M_Application_Builder
	 */
	protected function _addMessage($message = NULL, $class = NULL)
	{
		if (!$message ||
			!is_string($message)) {
		    throw new L8M_Application_Builder_Exception('Message needs to be specified as a string.');
		}

		$this->_messages[] = array(
			'class'=>$class,
			'value'=>$message,
		);

		return $this;
	}

	/**
	 * Returns messages as an array.
	 *
	 * @return array
	 */
	public function getMessages()
	{
		return $this->_messages;
	}

}