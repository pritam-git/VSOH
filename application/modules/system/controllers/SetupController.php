<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/SetupController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: SetupController.php 370 2015-06-22 16:33:52Z nm $
 */

/**
 *
 *
 * System_SetupController
 *
 *
 */
class System_SetupController extends L8M_Controller_Action
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A string representing the session namespace reserved for this controller.
	 *
	 * @var string
	 */
	protected $_sessionNamespace = 'System_SetupController';

	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function indexAction()
	{
		/**
		 * do only allow this in dev-mode
		 */
		if (L8M_Environment::ENVIRONMENT_DEVELOPMENT != L8M_Environment::getInstance()->getEnvironment()) {
			$this->_redirect($this->_helper->url('index', 'index', 'system'));
		}

		$form = new System_Form_Setup_Process();
		$form
			->addDecorators(array(
				new L8M_Form_Decorator(),
				new L8M_Form_Decorator_FormHasRequiredElements(),
			))
			->setAction($this->_helper->url('index', 'setup', 'system'))
		;

		if ($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getPost())) {

			/**
			 * store confirmation in session
			 */
			Zend_Session_Namespace::resetSingleInstance($this->_sessionNamespace);
			$session = new Zend_Session_Namespace($this->_sessionNamespace);
			$session->systemSetupProcessConfirmed = TRUE;

			if ($form->getValue('delete_temp_images')) {
				$session->deleteTemporaryImages = TRUE;
			} else {
				$session->deleteTemporaryImages = FALSE;
			}

			$this->_redirect($this->_helper->url('process'));
		}

		$this->view->form = $form;

	}

	/**
	 * Process action.
	 *
	 * @return void
	 */
	public function processAction()
	{
		/**
		 * unregister unusefull plugins
		 *
		 * @var Zend_Controller_Front
		 */
		if (L8M_Config::getOption('zfdebug.enabled')) {
			$frontController = Zend_Controller_Front::getInstance();
			$zfDebugPlugin = $frontController->getPlugin('L8M_Controller_Plugin_Debug');

			$zfDebugPlugin->unregisterPlugin('database');
			$zfDebugPlugin->unregisterPlugin('doctrine');
			$frontController->unregisterPlugin('L8M_Controller_Plugin_Debug');

			$doctrineManager = Doctrine_Manager::getInstance()->getConnection('default');
			$doctrineManager->setEventListener(new Doctrine_EventListener());
		}

		/**
		 * do only allow this in dev-mode
		 */
		if (L8M_Environment::ENVIRONMENT_DEVELOPMENT != L8M_Environment::getInstance()->getEnvironment()) {
			$this->_redirect($this->_helper->url('index', 'index', 'system'));
		}

		/**
		 * set time limit
		 */
		set_time_limit(0);

		/**
		 * check confirmation in session
		 */
		$session = new Zend_Session_Namespace($this->_sessionNamespace);
		if ($session->systemSetupProcessConfirmed == FALSE) {
			$this->_redirect($this->_helper->url('index'));
		} else {
			$this->view->layout()->systemSetupProcessConfirmed = TRUE;
		}

		/**
		 * check whether we have to kill / delete all temporary images or not
		 */
		if ($session->deleteTemporaryImages) {
			foreach (new DirectoryIterator(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media') as $fileInfo) {
				$file = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $fileInfo->getFilename();
				if (!$fileInfo->isDot() &&
					!is_dir($file) &&
					file_exists($file) &&
					is_writable($file)) {

					unlink($file);
				}
			}
			foreach (new DirectoryIterator(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile') as $fileInfo) {
				$file = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $fileInfo->getFilename();
				if (!$fileInfo->isDot() &&
					!is_dir($file) &&
					file_exists($file) &&
					is_writable($file)) {

					unlink($file);
				}
			}
			foreach (new DirectoryIterator(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'captcha') as $fileInfo) {
				$file = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'captcha' . DIRECTORY_SEPARATOR . $fileInfo->getFilename();
				if (!$fileInfo->isDot() &&
					!is_dir($file) &&
					file_exists($file) &&
					is_writable($file)) {

					unlink($file);
				}
			}
		}

		/**
		 * clear confirmation in session
		 */
//		$session->systemSetupProcessConfirmed = FALSE;
//		Zend_Session_Namespace::resetSingleInstance($this->_sessionNamespace);

		/**
		 * doctrine options
		 */
		$doctrineOptions = $this->getOption('doctrine');
		$doctrineOptions['connection'] = 'default';

		/**
		 * options
		 */
		$options = array(
			'moduleName'=>'default',
			'controllers'=>array(),
			'models'=>array(),
			'doctrine'=>$doctrineOptions,
		);

		/**
		 * check whether database exists or not
		 */
		$databaseExists = L8M_Doctrine_Database::databaseExists();
		if (!$databaseExists) {
			$this->view->layout()->setupWithoutDatabase = TRUE;
		} else {
			$this->view->layout()->setupWithoutDatabase = FALSE;
		}


		/**
		 * builder
		 */
		$builder = new L8M_Application_Module_Builder();
		$builder->build($options);

		$buildMessages = $builder->getMessages();

		/**
		 * import objects
		 */
		$importObjects = array(
			'Role',
			'Salutation',
			'MediaType',
			'EntityAdmin',
			'EntitySupervisor',
			'EntityUser',
			'ModelColumnName',
			'ModelListColumn',
			'Action',
			'Navigation',
			'Territory',
			'Country',
			'CountryZone',
			'Currency',
			'Language',
		);
		$importFirstObjects = L8M_Config::getOption('doctrine.options.importFirst.objects');
		if (is_array($importFirstObjects)) {
			$importObjects = $importFirstObjects;
		}

		/**
		 * retrieve imports from application.ini
		 */
		if (array_key_exists('import', $doctrineOptions['options']) &&
			array_key_exists('objects', $doctrineOptions['options']['import'])) {

			$appIniImportObjects = $doctrineOptions['options']['import']['objects'];
			if (is_array($appIniImportObjects) &&
				count($appIniImportObjects) > 0) {

				/**
				 * merge imports
				 */
				$importObjects = array_merge($importObjects, $appIniImportObjects);
			}
		}

		/**
		 * start import process
		 */
		$importMessages = array();

		if (!$builder->hasError() &&
			count($importObjects) > 0) {

			foreach ($importObjects as $importObject) {

				$import = L8M_Doctrine_Import::factory(
					$importObject,
					$doctrineOptions
				);

				if ($import instanceof L8M_Doctrine_Import_Abstract) {
					$import->process();
					$importMessages[] = array(
						'headline'=>'Importing ' . $importObject,
						'messages'=>$import->getMessages(),
					);
				}

			}
		}

		$this->view->listBuildMessages = $buildMessages;
		$this->view->listImportMessages = $importMessages;

		/**
		 * do we have to re-login?
		 */
		if (!$databaseExists &&
			L8M_Doctrine_Table::tableExists('action') &&
			L8M_Doctrine_Table::tableExists('image_config') &&
			L8M_Doctrine_Table::tableExists('role')) {

			/**
			 * sessionCount
			 */
			$sessionCount = 0;

			/**
			 * sessionNamespace
			 */
			$sessionNamespace = new Zend_Session_Namespace();
			if (isset($sessionNamespace->initialized) &&
				$sessionNamespace->initialized === TRUE) {
				/**
				 * sessionSaveHandler
				 */
				$sessionSaveHandler = Zend_Session::getSaveHandler();

				/**
				 * no save handler, sessions are stored in session save path
				 */
				if ($sessionSaveHandler === NULL) {
					$sessionSavePath = session_save_path();
					$directoryIterator = new DirectoryIterator($sessionSavePath);
					while($directoryIterator->valid()) {
						if (preg_match('/^sess_[0-9a-z]+$/i', $directoryIterator->getFilename()) &&
							$directoryIterator->isFile()) {
							$sessionCount++;
							if ($directoryIterator->isWritable()) {
								$sessionFilePath = $sessionSavePath
												 . DIRECTORY_SEPARATOR
												 . $directoryIterator->getFilename()
								;
								@unlink($sessionFilePath);
							}
						}
						$directoryIterator->next();
					}
				}
			}
		}
	}

}