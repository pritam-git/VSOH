<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/form/Setup/Doctrine/Builder/Options.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Options.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_Form_Setup_Doctrine_Builder_Options
 *
 *
 */
class System_Form_Setup_Doctrine_Builder_Options extends L8M_Dojo_Form
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
			 ->setAttrib('id', 'formSystemSetupDoctrineBuilderOptions');

		/**
		 * Formular-Elemente
		 * --
		 *
		 * generateBaseClasses (bool)
		 * generateTableClasses (bool)
		 * baseClassPrefix (string)
		 * baseClassesDirectory (string)
		 * baseTableClassName (string)
		 * baseClassName (string)
		 * classPrefix (string)
		 * classPrefixFiles (string)
		 * pearStyle (bool)
		 * packagesPrefix (string)
		 * packagesPath (string)
		 * packagesFolderName (string)
		 * suffix (string)
		 * phpDocPackage (string)
		 * phpDocSubpackage (string)
		 * phpDocName (string)
		 * phpDocEmail (string, email)
		 */


		/**
		 * formGenerateBaseClasses
		 */
//		$formGenerateBaseClasses = new Zend_Dojo_Form_Element_CheckBox('GenerateBaseClasses');
//		$formGenerateBaseClasses->setLabel('GenerateBaseClasses')
//				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
//				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
//						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
//						 ->setRegExp()
//						 ->setRequired(TRUE)
//						 ->setFilters(array())
//						 ->setValidators(array());
//		$this->addElement($formGenerateBaseClasses);



		/**
		 * formGenerateTableClasses
		 */
//		$formGenerateTableClasses = new Zend_Dojo_Form_Element_CheckBox('GenerateTableClasses');
//		$formGenerateTableClasses->setLabel('GenerateTableClasses')
//				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
//				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
//						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
//						 ->setRegExp()
//						 ->setRequired(TRUE)
//						 ->setFilters(array())
//						 ->setValidators(array());
//		$this->addElement($formGenerateTableClasses);

		/**
		 * formBaseClassesDirectory
		 */
		$formBaseClassesDirectory = new Zend_Dojo_Form_Element_ValidationTextBox('BaseClassesDirectory');
		$formBaseClassesDirectory->setLabel('BaseClassesDirectory')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formBaseClassesDirectory);

		/**
		 * formBaseTableClassName
		 */
		$formBaseTableClassName = new Zend_Dojo_Form_Element_ValidationTextBox('BaseTableClassName');
		$formBaseTableClassName->setLabel('BaseTableClassName')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formBaseTableClassName);


		/**
		 * formBaseClassName
		 */
		$formBaseClassName = new Zend_Dojo_Form_Element_ValidationTextBox('BaseClassName');
		$formBaseClassName->setLabel('BaseClassName')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formBaseClassName);


		/**
		 * formClassPrefix
		 */
		$formClassPrefix = new Zend_Dojo_Form_Element_ValidationTextBox('ClassPrefix');
		$formClassPrefix->setLabel('ClassPrefix')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formClassPrefix);

		/**
		 * formClassPrefixFiles
		 */
		$formClassPrefixFiles = new Zend_Dojo_Form_Element_ValidationTextBox('ClassPrefixFiles');
		$formClassPrefixFiles->setLabel('ClassPrefixFiles')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formClassPrefixFiles);

		/**
		 * formPearStyle
		 */
		$formPearStyle = new Zend_Dojo_Form_Element_CheckBox('PearStyle');
		$formPearStyle->setLabel('PearStyle')
					  ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
//					  ->setRegExp()
					  ->setRequired(TRUE)
//					  ->setFilters(array())
//					  ->setValidators(array())
		;
		$this->addElement($formPearStyle);

		/**
		 * formPackagesPrefix
		 */
		$formPackagesPrefix = new Zend_Dojo_Form_Element_ValidationTextBox('PackagesPrefix');
		$formPackagesPrefix->setLabel('PackagesPrefix')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formPackagesPrefix);

		/**
		 * formPackagesPath
		 */
		$formPackagesPath = new Zend_Dojo_Form_Element_ValidationTextBox('PackagesPath');
		$formPackagesPath->setLabel('PackagesPath')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formPackagesPath);


		/**
		 * formPackagesFolderName
		 */
		$formPackagesFolderName = new Zend_Dojo_Form_Element_ValidationTextBox('PackagesFolderName');
		$formPackagesFolderName->setLabel('PackagesFolderName')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formPackagesFolderName);

		/**
		 * formSuffix
		 */
		$formSuffix = new Zend_Dojo_Form_Element_ValidationTextBox('Suffix');
		$formSuffix->setLabel('Suffix')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formSuffix);


		/**
		 * formPhpDocPackage
		 */
		$formPhpDocPackage = new Zend_Dojo_Form_Element_ValidationTextBox('PhpDocPackage');
		$formPhpDocPackage->setLabel('PhpDocPackage')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formPhpDocPackage);

		/**
		 * formPhpDocSubpackage
		 */
		$formPhpDocSubpackage = new Zend_Dojo_Form_Element_ValidationTextBox('PhpDocSubpackage');
		$formPhpDocSubpackage->setLabel('PhpDocSubpackage')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formPhpDocSubpackage);

		/**
		 * formPhpDocName
		 */
		$formPhpDocName = new Zend_Dojo_Form_Element_ValidationTextBox('PhpDocName');
		$formPhpDocName->setLabel('PhpDocName')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formPhpDocName);

		/**
		 * formPhpDocEmail
		 */
		$formPhpDocEmail = new Zend_Dojo_Form_Element_ValidationTextBox('PhpDocEmail');
		$formPhpDocEmail->setLabel('PhpDocEmaile')
				 ->setDescription('This will be the name of the server on which the database is running. You can enter an address or an IP-adress.')
				 ->setPromptMessage($this->getTranslator()->translate('Please enter the database server.'))
						 ->setInvalidMessage($this->getTranslator()->translate('The database server you entered does not seem to be correct.'))
						 ->setRegExp()
						 ->setRequired(TRUE)
						 ->setFilters(array())
						 ->setValidators(array());
		$this->addElement($formPhpDocEmail);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Dojo_Form_Element_SubmitButton('formSystemSetupDoctrineBuilderOptionsSubmit');
		$formSubmitButton->setLabel('Save Doctrine Builder Options')
						 ->setDecorators(array('DijitElement'));
		$this->addElement($formSubmitButton);

	}
}