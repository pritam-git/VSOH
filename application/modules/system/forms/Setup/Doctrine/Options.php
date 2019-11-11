<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/form/Setup/Doctrine/Options.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Options.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_Form_Setup_Doctrine_Options
 *
 *
 */
class System_Form_Setup_Doctrine_Options extends L8M_Dojo_Form
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Customer_Register instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this->setMethod(Zend_Form::METHOD_POST)
			 ->setAttrib('id', 'formSystemSetupDoctrineOptions');

		/**
		 * formConnectionString
		 */
		$formConnectionString = new Zend_Dojo_Form_Element_ValidationTextBox('connectionString');
		$formConnectionString->setLabel('Connection String')
							 ->setDescription('Enter the connection string for the Doctrine connection.')
							 ->setPromptMessage($this->getTranslator()->translate('Please enter the connection string.'))
							 ->setInvalidMessage($this->getTranslator()->translate('The connection string you entered does not seem to be correct.'))
							 ->setRequired(TRUE)
//							 ->setFilters(array())
//							 ->setValidators()
		;
		$this->addElement($formConnectionString);

		/**
		 * formFixturesPath
		 */
		$formFixturesPath = new Zend_Dojo_Form_Element_ValidationTextBox('fixturesPath');
		$formFixturesPath->setLabel('Fixtures Path')
						 ->setDescription('Enter the fixtures path.')
						 ->setPromptMessage($this->getTranslator()->translate('Please enter the fixtures path .'))
						 ->setInvalidMessage($this->getTranslator()->translate('The fixtures path you entered does not seem to be correct.'))
						 ->setRequired(FALSE)
//						 ->setFilters(array())
//						 ->setValidators()
		;
		$this->addElement($formFixturesPath);

		/**
		 * formSqlPath
		 */
		$formSqlPath = new Zend_Dojo_Form_Element_ValidationTextBox('sqlPath');
		$formSqlPath->setLabel('Sql Path')
						 ->setDescription('Enter the sql path.')
						 ->setPromptMessage($this->getTranslator()->translate('Please enter the sql path .'))
						 ->setInvalidMessage($this->getTranslator()->translate('The sql path you entered does not seem to be correct.'))
						 ->setRequired(TRUE)
//						 ->setFilters(array())
//						 ->setValidators()
		;
		$this->addElement($formSqlPath);

		/**
		 * formMigrationsPath
		 */
		$formMigrationsPath = new Zend_Dojo_Form_Element_ValidationTextBox('migrationsPath');
		$formMigrationsPath->setLabel('Migrations Path')
						   ->setDescription('Enter the migrations path.')
						   ->setPromptMessage($this->getTranslator()->translate('Please enter the migrations path .'))
						   ->setInvalidMessage($this->getTranslator()->translate('The migrations path you entered does not seem to be correct.'))
						   ->setRequired(FALSE)
//						   ->setFilters(array())
//						   ->setValidators()
		;
		$this->addElement($formMigrationsPath);

		/**
		 * formModelsPath
		 */
		$formModelsPath = new Zend_Dojo_Form_Element_ValidationTextBox('modelsPath');
		$formModelsPath->setLabel('Models Path')
					   ->setDescription('Enter the models path.')
					   ->setPromptMessage($this->getTranslator()->translate('Please enter the models path .'))
					   ->setInvalidMessage($this->getTranslator()->translate('The models path you entered does not seem to be correct.'))
					   ->setRequired(TRUE)
//					   ->setFilters(array())
//					   ->setValidators()
		;
		$this->addElement($formModelsPath);

		/**
		 * formYamlPath
		 */
		$formYamlPath = new Zend_Dojo_Form_Element_ValidationTextBox('yamlPath');
		$formYamlPath->setLabel('Yaml Path')
					 ->setDescription('Enter the yaml path.')
					 ->setPromptMessage($this->getTranslator()->translate('Please enter the yaml path .'))
					 ->setInvalidMessage($this->getTranslator()->translate('The yaml path you entered does not seem to be correct.'))
					 ->setRequired(FALSE)
//					 ->setFilters(array())
//					 ->setValidators()
		;
		$this->addElement($formYamlPath);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Dojo_Form_Element_SubmitButton('formSystemSetupDoctrineOptionsSubmit');
		$formSubmitButton->setLabel('Save Doctrine Options')
						 ->setDecorators(array('DijitElement'));
		$this->addElement($formSubmitButton);
	}
}