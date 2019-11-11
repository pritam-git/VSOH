<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/Module/Builder.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Application_Module_Builder
 *
 *
 */
class L8M_Application_Module_Builder extends L8M_Application_Builder_Abstract
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
		'api',
		'configs',
		'controllers',
		'doctrine',
		'doctrine/data',
		'doctrine/data/fixtures',
		'doctrine/data/sql',
		'doctrine/migrations',
		'doctrine/schema',
		'forms',
		'layouts',
		'layouts/filters',
		'layouts/helpers',
		'layouts/scripts',
		'models',
		'models/Base',
		'plugins',
		'services',
		'services/Base',
		'translations',
		'views',
		'views/filters',
		'views/helpers',
		'views/scripts',
	);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Application_Module_Builder instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		if (!isset($this->_options['moduleName']) ||
			$this->_options['moduleName'] == '') {
			throw new L8M_Application_Module_Builder_Exception('Key "moduleName" needs to be present in options.');
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
		 * controller options present?
		 */
		if (isset($this->_options['controllers']) &&
			count($this->_options['controllers']) > 0) {
			foreach($this->_options['controllers'] as $controllerOptions) {
				$controllerOptions = array_merge(
					array(
						'moduleName'=>$this->_options['moduleName']
					),
					$controllerOptions
				);
				$controllerBuilder = new L8M_Application_Controller_Builder();
				$controllerBuilder->build($controllerOptions);
				$this->addMessages($controllerBuilder->getMessages());
				unset($controllerBuilder);
			}
		}

		/**
		 * model options present?
		 */
		if (isset($this->_options['models']) &&
			is_array($this->_options['models'])) {

			/**
			 * retrieve module specific doctrine options
			 * @todo revise (prefixes and paths . . . !)
			 */
			$moduleDoctrineOptions = $this->_options['doctrine'];
			try {
				$moduleConfig = new Zend_Config_Ini($this->_getModuleConfigPath($this->_options['moduleName']));
				$moduleOptions = $moduleConfig->toArray();
				if (isset($moduleOptions['doctrine'])) {
					$moduleDoctrineOptions = array_merge(
						$moduleOptions['doctrine'],
						$moduleDoctrineOptions
					);
				}
			} catch (Zend_Config_Exception $exception) {
			}

			$modelOptions = array(
				'moduleName'=>$this->_options['moduleName'],
				'models'=>$this->_options['models'],
				'doctrine'=>$moduleDoctrineOptions,
			);

			/**
			 * @todo class?
			 */
			$modelBuilder = new L8M_Application_Model_Doctrine_Builder();
			$modelBuilder->build($modelOptions);
			$this->addMessages($modelBuilder->getMessages());

		}

		/**
		 * form options present?
		 */
		if (isset($this->_options['forms']) &&
			count($this->_options['forms']) > 0) {
			foreach($this->_options['forms'] as $modelOptions) {

				$modelOptions = array_merge(
					array(
						'moduleName'=>$this->_options['moduleName'],
					),
					$modelOptions
				);

				foreach($modelOptions['actions'] as $formOptions) {

					$formOptions = array_merge(
						array(
							'moduleName'=>$modelOptions['moduleName'],
							'modelName'=>isset($modelOptions['modelName']) ? $modelOptions['modelName'] : '',
						),
						$formOptions
					);
					$formBuilder = new L8M_Application_Form_Builder();
					$formBuilder->build($formOptions);
					$this->addMessages($formBuilder->getMessages());
					unset($formBuilder);
				}
			}
		}

		/**
		 * bootstrapClassName
		 */
		$bootstrapClassName = $this->_getBootstrapClassName(
			$this->_options['moduleName']
		);

		/**
		 * bootstrapClassExtendedClassName
		 */
		$bootstrapClassExtendedClassName = $this->_getBootstrapClassExtendedClassName(
			$this->_options['moduleName']
		);

		/**
		 * bootstrapClassFilePath
		 */
		$bootstrapClassFilePath = $this->_getBootstrapClassFilePath(
			$this->_options['moduleName']
		);

		/**
		 * bootstrapClassDocblock
		 */
		$bootstrapClassDocblock = new L8M_CodeGenerator_Php_Docblock();
		$bootstrapClassDocblock
			->setLongDescription('This bootstrap class has been built with ' . get_class($this) . '.')
	 		->setTags(array(
	 			array(
			 		'name'=>'filesource',
			 		'description'=>$this->_getRelativePath($bootstrapClassFilePath),
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
		 * bootstrapClass
		 */
		$bootstrapClass = new Zend_CodeGenerator_Php_Class();
		$bootstrapClass
			->setName($bootstrapClassName)
			->setDocblock($bootstrapClassDocblock)
			->setExtendedClass($bootstrapClassExtendedClassName)
		;

		/**
		 * bootstrapClassFilePath
		 */
		$bootstrapClassFilePath = $this->_getBootstrapClassFilePath(
			$this->_options['moduleName']
		);

		/**
		 * file
		 */
		if (is_string($bootstrapClassFilePath) &&
			!(file_exists($bootstrapClassFilePath) &&
			  is_file($bootstrapClassFilePath))) {

			$bootstrapClassFile = new Zend_CodeGenerator_Php_File();
			$bootstrapClassFile
				->setClass($bootstrapClass)
				->setFilename($bootstrapClassFilePath)
				->write()
			;

			$this->addMessage('built bootstrapper <code class="application-lightning">' . $bootstrapClassName . '</code>', 'add');
		} else {
			$this->addMessage('skipped building bootstrapper <code class="application-lightning">' . $bootstrapClassName . '</code>', 'information semi');
		}

		$this->addMessage('finished building module <code class="bricks">' . $this->_options['moduleName'] . '</code>', 'accept');

	}

}