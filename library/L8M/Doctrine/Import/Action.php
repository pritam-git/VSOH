<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/Action.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Action.php 37 2014-04-10 13:19:03Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_Action
 *
 *
 */
class L8M_Doctrine_Import_Action extends L8M_Doctrine_Import_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		parent::_init();
		$this->_data = array();
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{

		$this->_dataCollection = new Doctrine_Collection($this->getModelClassName());

		/**
		 * roleGuest
		 */
		$roleGuest = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->where('r.short = ?')
			->execute(array('guest'))
			->getFirst()
		;

		/**
		 * roleAfterGuestUser
		 */
		$roleAfterGuestUser = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->where('r.id = ?', array($roleGuest->role_id))
			->execute()
			->getFirst()
		;

		/**
		 * roleAdmin
		 */
		$roleAdmin = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->where('r.short = ?')
			->execute(array('admin'))
			->getFirst()
		;

		/**
		 * roleSupervisor
		 */
		$roleSupervisor = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->where('r.short = ?')
			->execute(array('supervisor'))
			->getFirst()
		;

		/**
		 * roleAuthor
		 */
		$roleAuthor = FALSE;
		if (class_exists('Default_Model_EntityAuthor')) {
			$roleAuthor = Doctrine_Query::create()
				->from('Default_Model_Role r')
				->where('r.short = ?')
				->execute(array('author'))
				->getFirst()
			;
		}

		/**
		 * modules
		 */
		$modules = Zend_Controller_Front::getInstance()->getControllerDirectory();

		/**
		 * filter
		 */
		$filterController = new Zend_Filter_Word_CamelCaseToDash();
		$filterModule = new Zend_Filter_Word_DashToCamelCase();

		foreach($modules as $moduleShort=>$moduleControllerPath) {

			/**
			 * module
			 */
			$module = L8M_Doctrine_Record::factory($this->getModelClassName('Module'));
			$module->name = strtolower($moduleShort);

			$directoryIterator = new DirectoryIterator($moduleControllerPath);
			foreach($directoryIterator as $file) {
				/* @var $file DirectoryIterator */
				if ($file->isFile() &&
					preg_match('/^(.+)Controller\.php$/', $file->getFilename(), $match)) {

					$controllerName = $match[1];

					/**
					 * controller
					 */
					$controllerModel = L8M_Doctrine_Record::factory($this->getModelClassName('Controller'));
					$controllerModel->name = strtolower($filterController->filter($controllerName));
					$controllerModel->Module = $module;

					$controllerFile = $controllerName . 'Controller';
					$controllerClass = $controllerName . 'Controller';

					if ($moduleShort != 'default') {
						$controllerClass = ucfirst($filterModule->filter($moduleShort)) . '_' . $controllerClass;
					}
					if (!class_exists($controllerClass)) {
						try {
							Zend_Loader::loadFile($controllerFile . '.php', $moduleControllerPath);
						} catch (Exception $exception) {
							L8M_Library::arrayShow(array('c'=>$controllerClass, 'p'=>$moduleControllerPath)); die();
						}
					}

					$reflectionClass = new Zend_Reflection_Class($controllerClass);
					if ($reflectionClass->isSubclassOf('Zend_Controller_Action')) {
						$methods = $reflectionClass->getMethods();

						foreach($methods as $method) {
							/* @var $method Zend_Reflection_Method */
							if ($method->isPublic() &&
								preg_match('/^(.+)Action$/', $method->getName(), $match)) {

								/**
								 * action
								 */
								$actionModel = L8M_Doctrine_Record::factory($this->getModelClassName('Action'));
								$actionName = strtolower($filterController->filter($match[1]));
								$actionModel->name = $actionName;
								$actionModel->Controller = $controllerModel;
								$actionModel->is_allowed = TRUE;

								/**
								 * default and other user controller actions
								 */
								if ($moduleShort == 'default' &&
									$controllerModel->name == 'user' &&
									$actionName != 'index' &&
									$actionName != 'register' &&
									$actionName != 'registration-complete' &&
									$actionName != 'account-activated' &&
									$actionName != 'retrieve-password' &&
									$actionName != 'reset-password' &&
									$actionName != 'enable-account') {

									$actionModel->Role = $roleAfterGuestUser;
								} else

								/**
								 * default or facebook module
								 */
								if ($moduleShort == 'default' ||
									$moduleShort == 'shop') {

									$actionModel->Role = $roleGuest;
								} else

								/**
								 * controller login
								 */
								if ($controllerModel->name == 'login' &&
									$actionName == 'index') {

									$actionModel->Role = $roleGuest;
								} else

								/**
								 * admin module
								 */
								if ($moduleShort == 'admin') {
									$actionModel->Role = $roleSupervisor;
								} else

								/**
								 * author module and role author
								 */
								if ($moduleShort == 'author' &&
									$roleAuthor) {

									$actionModel->Role = $roleAuthor;
								} else

								/**
								 * system module and controller media
								 */
								if ($moduleShort == 'system' &&
									$controllerModel->name == 'media') {

									if ($roleAuthor) {
										$actionModel->Role = $roleAuthor;
									} else {
										$actionModel->Role = $roleSupervisor;
									}
								} else

								/**
								 * system module and controller auto-complete
								 */
								if ($moduleShort == 'system' &&
									$controllerModel->name == 'auto-complete') {

									if ($roleAuthor) {
										$actionModel->Role = $roleAuthor;
									} else {
										$actionModel->Role = $roleSupervisor;
									}
								} else

								/**
								 * system module and controller mark-for-editor
								 */
								if ($moduleShort == 'system' &&
									$controllerModel->name == 'mark-for-editor') {

									if ($roleAuthor) {
										$actionModel->Role = $roleAuthor;
									} else {
										$actionModel->Role = $roleSupervisor;
									}
								} else

								/**
								 * all other modules
								 */
								{
									$actionModel->Role = $roleAdmin;
								}

								$this->_dataCollection->add($actionModel);

							}
						}
					}
				}
			}

		}
	}

	/**
	 * try loading Default_Model_Action_Import for some customized actions
	 *
	 * @return void
	 */
	protected function _generateCustomizedDataCollection()
	{
		/**
		 * model name
		 *
		 * @var string
		 */
		$model = 'Action';

		/**
		 * import class
		 */
		$importClass = NULL;

		/**
		 * if a model class prefix is present in options, let's try to retrieve an import
		 * class from the corresponding module
		 */
		if (isset($this->_options['options']['builder']['classPrefix'])) {
			$importClass = $this->_options['options']['builder']['classPrefix']
						 . $model
						 . '_Import'
			;
			if (!class_exists($importClass)) {
				try {
					@Zend_Loader::loadClass($importClass);
				} catch (Zend_Exception $exception) {
				}
			}
		}

		if ($importClass !== NULL &&
			class_exists($importClass)) {

			/**
			 * check whether the import class actually extends L8M_Doctrine_Import_Abstract
			 */
			$reflectionClass = new ReflectionClass($importClass);
			if (!$reflectionClass->isSubclassOf('L8M_Doctrine_Import_Abstract')) {
				throw new L8M_Doctrine_Import_Exception($importClass . ' does not extend L8M_Doctrine_Import_Abstract.');
			}

			/**
			 * construct $import of $model
			 */
			$import = new $importClass($this->_options);

			if ($import instanceof L8M_Doctrine_Import_Abstract) {
				$import->process();
				$this->addMessages($import->getMessages());
			}
		}
	}

}