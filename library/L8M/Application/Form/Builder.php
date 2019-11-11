<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/Form/Builder.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Application_Form_Builder
 *
 *
 */
class L8M_Application_Form_Builder extends L8M_Application_Builder_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Application_Form_Builder instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		if (!isset($this->_options['moduleName'])) {
			throw new L8M_Application_Form_Builder_Exception('Key "moduleName" needs to be present in options.');
		}

		/**
		 * @todo watch this as model name can be empty, too
		 */
		if (!isset($this->_options['modelName'])) {
			throw new L8M_Application_Form_Builder_Exception('Key "modelName" needs to be present in options.');
		}

		if (!isset($this->_options['actionName']) ||
			count($this->_options['actionName']) == 0) {
			throw new L8M_Application_Form_Builder_Exception('Key "actionName" needs to be present in options');
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
		$filter->addFilter(new Zend_Filter_Word_DashToCamelCase());

		/**
		 * formClassName
		 */
		$formClassName = $this->_getFormClassName(
			$this->_options['moduleName'],
			$this->_options['modelName'],
			$this->_options['actionName']
		);

		/**
		 * initializationMethodDocblock
		 */
		$initializationMethodDocblock = new L8M_CodeGenerator_Php_Docblock(array(
		    'shortDescription'=>'Initializes ' . $formClassName . ' instance.',
		    'tags'=>array(
		        array(
		            'name'=>'return',
		            'description'=>'void',
		        ),
		    ),
		));

		/**
		 * formId
		 */
		$formId = 'form'
				. $filter->filter($this->_options['moduleName'])
				. $filter->filter($this->_options['modelName'])
				. $filter->filter($this->_options['actionName'])
		;

		/**
		 * submitButtonId
		 */
		$submitButtonId = $formId
						. 'Submit'
		;

		/**
		 * submitButtonLabel
		 */
		$submitButtonLabel = $filter->filter($this->_options['actionName'])
				   		   . ' '
				   		   . $filter->filter($this->_options['modelName'])
		;

		/**
		 * initializationMethodBody
		 */
		$initializationMethodBody = implode(PHP_EOL, array(
			'parent::init();',
			'',
			'/**',
			' * form',
			' */',
			'$this',
			"\t" . '->setMethod(Zend_Form::METHOD_POST)',
			"\t" . '->setAttrib(\'id\', \'' . $formId . '\')',
			';',
			'',
			'/**',
			' * formSubmitButton',
			'*/',
			'$formSubmitButton = new Zend_Dojo_Form_Element_SubmitButton(\'' . $submitButtonId . '\');',
			'$formSubmitButton',
			"\t" . '->setLabel(\'' . $submitButtonLabel . '\')',
			"\t" . '->setDecorators(array(',
			"\t\t" . 'new Zend_Dojo_Form_Decorator_DijitElement(),',
			"\t\t" . 'new Zend_Form_Decorator_HtmlTag(array(',
			"\t\t\t" . '\'tag\'=>\'dd\',',
			"\t\t" . ')),',
			"\t" . '))',
			';',
		));

		/**
		 * initialization method
		 */
		$initializationMethod = new Zend_CodeGenerator_Php_Method();
		$initializationMethod
			->setName('init')
			->setDocblock($initializationMethodDocblock)
			->setBody($initializationMethodBody)
			->setVisibility(Zend_CodeGenerator_Php_Method::VISIBILITY_PUBLIC)
		;

		/**
		 * formClassFilePath
		 */
		$formClassFilePath = $this->_getFormClassFilePath(
			$this->_options['moduleName'],
			$this->_options['modelName'],
			$this->_options['actionName']
		);

		/**
		 * docBlock
		 */
		$formClassDocblock = new L8M_CodeGenerator_Php_Docblock();
		$formClassDocblock
			->setLongDescription('This form has been built with ' . get_class($this) . '.')
	 		->setTags(array(
	 			array(
			 		'name'=>'filesource',
			 		'description'=>$this->_getRelativePath($formClassFilePath),
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
		 * formClass
		 */
		$formClass = new Zend_CodeGenerator_Php_Class();
		$formClass
			->setName($formClassName)
			->setDocblock($formClassDocblock)
			->setExtendedClass('L8M_Dojo_Form')
			->setMethods(array($initializationMethod))
		;

		/**
		 * do not overwrite existing forms
		 */
		if (is_string($formClassFilePath) &&
			!file_exists($formClassFilePath)) {

			$this->_createDirectory(dirname($formClassFilePath));

			$formFile = new Zend_CodeGenerator_Php_File();
			$formFile
				->setClass($formClass)
				->setFilename($formClassFilePath)
				->write()
			;

			$this->addMessage('built class <code class="application-form">' . $formClassName . '</code>', 'add');

		} else {

			$this->addMessage('skipped building form <code class="application-form">' . $formClassName . '</code>', 'information semi');

		}

	}

}