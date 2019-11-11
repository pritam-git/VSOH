<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/ViewScript/Builder.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Application_ViewScript_Builder
 *
 *
 */
class L8M_Application_ViewScript_Builder extends L8M_Application_Builder_Abstract
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
	);

	/**
	 *
	 *
	 * Abstract Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Application_ViewScript_Builder instance.
	 *
	 * @return void
	 */
	protected function _init()
	{

		if (!isset($this->_options['moduleName'])) {
			throw new L8M_Application_ViewScript_Builder_Exception('Key "moduleName" needs to be present in options.');
		}

		if (!isset($this->_options['controllerName'])) {
			throw new L8M_Application_ViewScript_Builder_Exception('Key "controllerName" needs to be present in options.');
		}

		if (!isset($this->_options['actionName']) ||
			count($this->_options['actionName']) == 0) {
			throw new L8M_Application_ViewScript_Builder_Exception('Key "actionName" needs to be present in options');
		}

		if (!isset($this->_options['actionContext']) ||
			count($this->_options['actionContext']) == 0) {
			throw new L8M_Application_ViewScript_Builder_Exception('Key "actionContext" needs to be present in options');
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
		 * modulePath
		 */
		$modulePath = $this->_getModulePath($this->_options['moduleName']);

		/**
		 * viewScriptPath
		 */
		$viewScriptPath = $this->_getViewScriptPath(
			$this->_options['moduleName'],
			$this->_options['controllerName']
		);

		/**
		 * viewScriptFileName
		 */
		$viewScriptFileName = $this->_getViewScriptFileName(
			$this->_options['actionName'],
			$this->_options['actionContext']
		);

		/**
		 * viewScriptFilePath
		 */
		$viewScriptFilePath = $this->_getViewScriptFilePath(
			$this->_options['moduleName'],
			$this->_options['controllerName'],
			$this->_options['actionName'],
			$this->_options['actionContext']
		);

		/**
		 * if view script does not exist yet, go ahead and create it
		 */
		if (is_string($viewScriptFilePath) &&
			!file_exists($viewScriptFilePath)) {

			/**
			 * create view script directory, if it does not exist yet
			 */
			$this->_createDirectory($viewScriptPath, 755, TRUE);

			/**
			 * viewScriptDocblock
			 */
			$viewScriptDocblock = new L8M_CodeGenerator_Php_Docblock();
			$viewScriptDocblock
				->setLongDescription('This view script has been built with ' . get_class($this) . '.')
		 		->setTags(array(
		 			array(
				 		'name'=>'filesource',
				 		'description'=>$this->_getRelativePath($viewScriptFilePath),
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
			 * viewScriptBody
			 */
			$viewScriptBody = implode(PHP_EOL, array(
				'$this->layout()->headline = $this->escape($this->translate(\'' . $this->_options['moduleName'] . '\'));',
				'$this->layout()->subheadline = $this->escape($this->translate(\'' . $this->_options['actionName'] . '\'));',
			));

			/**
			 * viewScriptFile
			 */
			$viewScriptFile = new Zend_CodeGenerator_Php_File();
			$viewScriptFile
				->setDocblock($viewScriptDocblock)
				->setBody($viewScriptBody)
				->setFilename($viewScriptFilePath)
				->write()
			;

			$this->addMessage('created view script <code class="eye">' . $this->_options['actionName'] . ' (' .$this->_options['actionContext'] . ')</code>', 'add');

		} else {
			$this->addMessage('skipped creation of view script <code class="eye">' . $this->_options['actionName'] . ' (' .$this->_options['actionContext'] . ')</code>', 'information semi');
		}
	}

}