<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/Controller/Builder.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Application_Controller_Builder
 *
 *
 */
class L8M_Application_Controller_Builder extends L8M_Application_Builder_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of required directories.
	 *
	 * @var array
	 */
	protected $_requiredDirectories = array(
		'controllers',
		'views',
		'views/scripts',
	);

	/**
	 *
	 *
	 * Abstract Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Application_Controller_Builder instance.
	 *
	 * @return void
	 */
	protected function _init()
	{

		if (!isset($this->_options['moduleName'])) {
			throw new L8M_Application_Controller_Builder_Exception('Key "moduleName" needs to be present in options.');
		}

		if (!isset($this->_options['controllerName'])) {
			throw new L8M_Application_Controller_Builder_Exception('Key "controllerName" needs to be present in options.');
		}

		if (!isset($this->_options['actions']) ||
			count($this->_options['actions']) == 0) {
			throw new L8M_Application_Controller_Builder_Exception('Key "actions" needs to be present in options');
		}

	}

	/**
	 * Builds components.
	 *
	 * @return void
	 */
	protected function _buildComponents()
	{
		/**
		 * filter
		 */
		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_DashToCamelCase())
		;

		/**
		 * controller contexts
		 */
		$controllerAjaxContexts = array();
		$controllerContexts = array();

		/**
		 * controllerProperties
		 */
		$controllerProperties = array();

		/**
		 * controllerMethods
		 */
		$controllerMethods = array();

		/**
		 * if there are actions present, iterate over them
		 */
		if (count($this->_options['actions'])>0) {

			foreach($this->_options['actions'] as $action) {

				/**
				 * actionMethodDocblock
				 */
				$actionMethodDocblock = new L8M_CodeGenerator_Php_Docblock(array(
				    'shortDescription'=>$filter->filter($action['actionName']) . ' action.',
				    'tags'=>array(
				        array(
				            'name'=>'return',
				            'description'=>'void',
				        ),
				    ),
				));

				/**
				 * actionMethodName
				 */
				$actionMethodName = $filter->filter($action['actionName'])  . 'Action';
				$actionMethodName[0] = strtolower($actionMethodName[0]);

				/**
				 * actionMethod
				 */
				$actionMethod = new Zend_CodeGenerator_Php_Method();
				$actionMethod
					->setName($actionMethodName)
					->setDocblock($actionMethodDocblock)
					->setVisibility(Zend_CodeGenerator_Php_Method::VISIBILITY_PUBLIC)
				;

				/**
				 * add to controllerMethods
				 */
				$controllerMethods[] = $actionMethod;

				/**
				 * actionContexts
				 */
				$actionContexts = array();
				if (!isset($action['viewDisabled']) ||
					$action['viewDisabled'] === FALSE) {
					$actionContexts[] = 'default';
				}
				if (isset($action['actionContexts'])) {
					$actionContexts = array_merge($actionContexts, $action['actionContexts']);
				}

				/**
				 * action contexts available?
				 */
				if (count($actionContexts)>0) {

					foreach($actionContexts as $actionContext) {

						$viewScriptOptions = array_merge(
							$this->_options,
							array(
								'actionName'=>$action['actionName'],
								'actionContext'=>$actionContext == 'html' ? 'ajax' : $actionContext,
							)
						);

						$viewScriptBuilder = new L8M_Application_ViewScript_Builder();
						$viewScriptBuilder->build($viewScriptOptions);

						$this->addMessages($viewScriptBuilder->getMessages());

						unset($viewScriptBuilder);

						/**
						 * controllerAjaxContexts
						 */
						if (in_array($actionContext, array('html', 'json'))) {
							$controllerAjaxContexts[] = array(
								$action['actionName']=>$actionContext,
							);
						}
					}
				}
			}
		}

		/**
		 * controllerClassName
		 */
		$controllerClassName = $this->_getControllerClassName(
			$this->_options['controllerName'],
			$this->_options['moduleName']
		);

		/**
		 * controllerFilePath
		 */
		$controllerClassFilePath = $this->_getControllerClassFilePath(
			$this->_options['moduleName'],
			$this->_options['controllerName']
		);

		/**
		 * controllerClassDocblock
		 */
		$controllerClassDocblock = new L8M_CodeGenerator_Php_Docblock();
		$controllerClassDocblock
			->setLongDescription('This controller has been built with ' . get_class($this) . '.')
	 		->setTags(array(
	 			array(
			 		'name'=>'filesource',
			 		'description'=>$this->_getRelativePath($controllerClassFilePath),
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
		 * controllerAjaxContexts
		 */
		if (count($controllerAjaxContexts)>0) {

			/**
			 * initializationMethodDocblock
			 */
			$initializationMethodDocblock = new L8M_CodeGenerator_Php_Docblock(array(
			    'shortDescription'=>'Initializes ' . $controllerClassName . ' instance.',
			    'tags'=>array(
			        array(
			            'name'=>'return',
			            'description'=>'void',
			        ),
			    ),
			));

			/**
			 * initializationMethodBody
			 */
			$initializationMethodBody = implode(PHP_EOL, array(
				'parent::init();',
				'$this->_helper->ajaxContext->initContext();',
			));

			/**
			 * initializationMethod
			 */
			$initializationMethod = new Zend_CodeGenerator_Php_Method();
			$initializationMethod
				->setName('init')
				->setDocblock($actionMethodDocblock)
				->setBody($initializationMethodBody)
				->setVisibility(Zend_CodeGenerator_Php_Method::VISIBILITY_PUBLIC)
			;

			array_unshift($controllerMethods, $initializationMethod);

			/**
			 * ajaxablePropertyDocblock
			 */
//			$ajaxablePropertyDocblock = new L8M_CodeGenerator_Php_Docblock(array(
//			    'shortDescription'=>'An array with names of actions for which the AjaxContext switch will automatically be enabled.',
//			    'tags'=>array(
//			        array(
//			            'name'=>'var',
//			            'description'=>'array',
//			        ),
//			    ),
//			));

			/**
			 * ajaxablePropertyDefaultValue
			 */
//			$ajaxablePropertyDefaultValue = new Zend_CodeGenerator_Php_Property_DefaultValue();
//			$ajaxablePropertyDefaultValue
//				->setType(Zend_CodeGenerator_Php_Property_DefaultValue::TYPE_ARRAY)
//				->setValue($controllerAjaxContexts)
//				->setArrayDepth()
//			;

			/**
			 * ajaxableProperty
			 */
//			$ajaxableProperty = new Zend_CodeGenerator_Php_Property();
//			$ajaxableProperty
//				->setName('ajaxable')
//				->setDocblock($ajaxablePropertyDocblock)
//				->setDefaultValue($ajaxablePropertyDefaultValue)
//				->setVisibility(Zend_CodeGenerator_Php_Property::VISIBILITY_PUBLIC)
//			;
//
//			array_unshift($controllerProperties, $ajaxableProperty);

		}

		/**
		 * controllerClassName
		 */
		$controllerClassName = $this->_getControllerClassName(
			$this->_options['controllerName'],
			$this->_options['moduleName']
		);

		/**
		 * controllerClass
		 */
		$controllerClass = new Zend_CodeGenerator_Php_Class();
		$controllerClass
			->setName($controllerClassName)
			->setDocblock($controllerClassDocblock)
			->setExtendedClass('L8M_Controller_Action')
			->setProperties($controllerProperties)
			->setMethods($controllerMethods)
		;

		/**
		 * file
		 */
		if (is_string($controllerClassFilePath) &&
			!(file_exists($controllerClassFilePath) &&
			  is_file($controllerClassFilePath))) {

			$controllerFile = new Zend_CodeGenerator_Php_File();
			$controllerFile
				->setClass($controllerClass)
				->setFilename($controllerClassFilePath)
				->write()
			;

			$this->addMessage('built controller <code class="control-play">' . $controllerClassName . '</code>', 'add');
		} else {
			$this->addMessage('skipped building controller <code class="control-play">' . $controllerClassName . '</code>', 'information semi');
		}

	}

}