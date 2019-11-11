<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/form/Setup/Database/Options.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Options.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_Form_Setup_Database_Options
 *
 *
 */
class System_Form_Setup_Database_Options extends L8M_Dojo_Form
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
		$this
			->setMethod(Zend_Form::METHOD_POST)
			->setAttrib('id', 'formSystemSetupDatabaseOptions')
		;

		/**
		 * formDatabaseHost
		 */
		$formHost = new Zend_Dojo_Form_Element_ValidationTextBox('host');
		$formHost
			->setLabel('Database Host')
			->setDescription('Enter the name of the server on which this application\'s database is or should be running.')
			->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
			->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
//			->setRequired(TRUE)
//			->setFilters(array())
//			->setValidators()
		;
		$this->addElement($formHost);

		/**
		 * formName
		 */
		$formName = new Zend_Dojo_Form_Element_ValidationTextBox('dbname');
		$formName
			->setLabel('Database Name')
			->setDescription($this->getTranslator()->translate('Enter the name of this application\'s default database.'))
			->setPromptMessage($this->getTranslator()->translate('Please enter the database name.'))
			->setInvalidMessage($this->getTranslator()->translate('The database name you entered does not seem to be correct.'))
//			->setRegExp()
//			->setRequired(TRUE)
//			->setFilters(array())
//			->setValidators(array())
		;
		$this->addElement($formName);

		/**
		 * formDatabaseLogin
		 */
		$formLogin = new Zend_Dojo_Form_Element_ValidationTextBox('username');
		$formLogin
			->setLabel('Database Login')
			->setDescription($this->getTranslator()->translate('Enter the name of the login needed to access this application\'s default database.'))
			->setPromptMessage($this->getTranslator()->translate('Please enter the database login.'))
			->setInvalidMessage($this->getTranslator()->translate('The database login you entered does not seem to be correct.'))
//			->setRegExp()
			->setRequired(FALSE)
//			->setFilters(array())
//			->setValidators(array())
		;
		$this->addElement($formLogin);

		/**
		 * formDatabasePassword
		 */
		$formPassword = new Zend_Dojo_Form_Element_PasswordTextBox('password');
		$formPassword
			->setLabel('Database Password')
			->setDescription($this->getTranslator()->translate('Enter the password needed to access this application\'s default database.'))
			->setPromptMessage($this->getTranslator()->translate('Please enter the database password.'))
			->setInvalidMessage($this->getTranslator()->translate('The database password you entered does not seem to be correct.'))
//			->setRegExp()
//			->setRequired(TRUE)
//			->setFilters(array())
//			->setValidators(array())
		;
		$this->addElement($formPassword);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Dojo_Form_Element_SubmitButton('formSystemSetupDatabaseOptionsSubmit');
		$formSubmitButton
			->setLabel('Save Database Options')
			->setDecorators(array('DijitElement'))
		;
		$this->addElement($formSubmitButton);
	}
}