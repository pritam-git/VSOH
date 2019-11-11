<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Navigation/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 41 2014-04-16 12:45:14Z nm $
 */

/**
 *
 *
 * L8M_Navigation_Abstract
 *
 *
 */
abstract class L8M_Navigation_Abstract extends Zend_Navigation
{
	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */
	const ROLE_GUEST_ID = 1;
	const ROLE_GUEST_SHORT = 'guest';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * navigation array
	 *
	 * @var array
	 */
	private $_navigation = array();

	/**
	 * roleID
	 *
	 * @var integer
	 */
	private $_roleID = NULL;

	/**
	 * show in module
	 *
	 * @var boolean
	 */
	private $_showInModule = NULL;

	/**
	 * use new module navigation
	 *
	 * @var boolean
	 */
	private $_useModuleNavigation = NULL;


	/**
	 * use new module navigation
	 *
	 * @var boolean
	 */
	private $_useTranslateablleNavigation = NULL;

	/**
	 * A string representing the current language ISO2 code.
	 *
	 * @var string
	 */
	protected static $_language = NULL;

	/**
	 * An L8M_Navigation_Adapter_Abstract instance.
	 *
	 * @var L8M_Navigation_Adapter_Abstract
	 */
	protected static $_adapter = NULL;

	/**
	 *
	 *
	 * Abstract Methods
	 *
	 *
	 */

	/**
	 * Creates a dynamic menu.
	 * Returns a navigation array.
	 *
	 * @param integer $navigationID
	 * @param string $dynamicShort
	 * @param array $urlOptions
	 * @return array
	 */
	abstract protected function createDynamicNavigation($navigationID, $dynamicShort, $urlOptions);

	/**
	 * Creates menu at beginning
	 * Returns a navigation array.
	 *
	 * @return array
	 */
	abstract protected function createNavigationStart();

	/**
	 * Creates menu at the end
	 * Returns a navigation array.
	 *
	 * @return array
	 */
	abstract protected function createNavigationEnd();

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Constructor
	 */
	public function __construct($navigationArray = array())
	{
		if (is_array($navigationArray) &&
			count($navigationArray) > 0 &&
			isset($navigationArray[0]) &&
			array_key_exists('label', $navigationArray[0]) &&
			array_key_exists('title', $navigationArray[0]) &&
			array_key_exists('visible', $navigationArray[0]) &&
			array_key_exists('id', $navigationArray[0])) {

			$this->_navigation = $navigationArray;
		} else {

			/**
			 * call navigation-creation
			 */
			$this->createNavigationArray();
		}
		parent::__construct($this->_navigation);
	}

	/**
	 * Creates the Navigation Array
	 *
	 */
	public function createNavigationArray()
	{
		/**
		 * check cache
		 */
		$navigationArray = $this->_getFromCache();

		if (!$navigationArray) {
			/**
			 * create empty navigation array
			 */
			$navigationArray = $this->createNavigationStart();

			/**
			 * retrieve first level navigation items
			 */
			if (class_exists('Default_Model_Action', TRUE)) {
				try {

					/**
					 * check for newer navigation version
					 */
					$navigationModel = new Default_Model_Navigation();
					$navigationModelColumns = $navigationModel->getTable()->getColumns();
					if (array_key_exists('show_in_module', $navigationModelColumns)) {
						$this->_useModuleNavigation = TRUE;
					} else {
						$this->_useModuleNavigation = FALSE;
					}

					if (array_key_exists('do_not_translate', $navigationModelColumns)) {
						$this->_useTranslateablleNavigation = TRUE;
					} else {
						$this->_useTranslateablleNavigation = FALSE;
					}

					/**
					 * selects
					 */
					$selects = array(
						'n.action_resource',
						'n.css_class',
						'n.dynamic',
						'n.id',
						'n.navigation_id',
						'n.position',
						'n.target',
						'n.uri',
						'n.visible',
						'n.name',
						'n.title',
						'n.show_all',
						'n.show_all_loggedin',
					);

					if ($this->_useTranslateablleNavigation) {
						$selects[] = 'n.default_language';
						$selects[] = 'n.do_not_translate';
					}

					/**
					 * let's execute query
					 * @var Doctrine_Query
					 */
					$navigationItemsQuery = Doctrine_Query::create()
						->from('Default_Model_Navigation n')
						->select(implode(',', $selects))
						->leftJoin('n.RoleShort r')
						->addWhere('n.navigation_id IS NULL')
					;

					/**
					 * show module navigation
					 */
					if ($this->_useModuleNavigation) {

						/**
						 * retrieve layout
						 */
						$layout = Zend_Layout::getMvcInstance();

						/**
						 * retrieve module name
						 */
						$moduleName = $layout->getView()->layout()->calledForModuleName;

						/**
						 * test if module navigation exists
						 */
						$navigationModuleTest = Doctrine_Query::create()
							->from('Default_Model_Navigation n')
							->addWhere('n.show_in_module = ? ', array($moduleName))
							->execute()
							->getFirst()
						;
						if ($navigationModuleTest) {

							/**
							 * add condition
							 */
							$navigationItemsQuery = $navigationItemsQuery
								->addWhere('n.show_in_module = ? ', array($moduleName))
							;

							/**
							 * save for later use
							 */
							$this->_showInModule = $moduleName;
						} else {

							/**
							 * add condition
							 */
							$navigationItemsQuery = $navigationItemsQuery
								->addWhere('n.show_in_module IS NULL', array())
							;
						}
					}

					/**
					 * show loggedin
					 */
					if ($this->_showLoggedin()) {
						$navigationItemsQuery->addWhere('r.id = ? OR n.show_all = ? OR n.show_all_loggedin = ? ', array($this->_getRoleID(), TRUE, TRUE));
					} else {
						$navigationItemsQuery->addWhere('r.id = ? OR n.show_all = ? ', array($this->_getRoleID(), TRUE));
					}
					$navigationItems = $navigationItemsQuery
						->orderBy('n.position ASC')
						->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
						->execute()
					;

					/**
					 * do we have some items in retrieved array
					 */
					if(is_array($navigationItems)) {

						/**
						 * go through navigation array
						 */
						foreach($navigationItems as $navigationItem) {

							/**
							 * prepare translation
							 */
							if (!$this->_useTranslateablleNavigation) {
								$navigationItem['default_language'] = NULL;
								$navigationItem['do_not_translate'] = NULL;
							}

							if ($navigationItem['n_action_resource'] !== NULL) {

								$actionResources = FALSE;
								$cache = L8M_Cache::getCache('Default_Model_Action');

								if ($cache) {
									$actionResources = $cache->load(L8M_Cache::getCacheId('resource', $navigationItem['n_action_resource']));
								}

								if ($actionResources === FALSE) {

									/**
									 * let's execute query
									 * @var Doctrine_Query
									 */
									$actionResources = Doctrine_Query::create()
										->from('Default_Model_Action a')
										->where('a.resource = ? ', array($navigationItem['n_action_resource']))
										->execute()
										->getFirst()
									;

									if ($cache) {
										$cache->save($actionResources, L8M_Cache::getCacheId('resource', $navigationItem['n_action_resource']));
									}
								}

								if ($actionResources) {

									$resourceArray = explode('.', $navigationItem['n_action_resource']);

									if (count($resourceArray) == 3) {

										/**
										 * internal site
										 */
										$navigationArray[] = array(
											'label'=>$navigationItem['n_name'],
											'style'=>NULL,
											'class'=>$navigationItem['n_css_class'],
											'title'=>$navigationItem['n_title'],
											'target'=>$navigationItem['n_target'],
											'visible'=>$navigationItem['n_visible'],
											'module'=>$resourceArray[0],
											'controller'=>$resourceArray[1],
											'action'=>$resourceArray[2],
											'id'=>$navigationItem['n_id'],
											'pages'=>$this->createSubNavigationArray(
												$navigationItem['n_id'],
												$navigationItem['n_dynamic'],
												array(
													'module'=>$resourceArray[0],
													'controller'=>$resourceArray[1],
													'action'=>$resourceArray[2],
													'label'=>$navigationItem['n_name'],
													'class'=>$navigationItem['n_css_class'],
													'title'=>$navigationItem['n_title'],
													'target'=>$navigationItem['n_target'],
													'visible'=>$navigationItem['n_visible'],
												)
											),
											'doNotTranslate'=>$navigationItem['n_do_not_translate'],
											'defaultLanguage'=>$navigationItem['n_default_language'],
										);
									}
								}
							} else
							if ($navigationItem['n_uri'] !== NULL){

								/**
								 * external site
								 */
								$navigationArray[] = array(
									'label'=>$navigationItem['n_name'],
									'style'=>NULL,
									'class'=>$navigationItem['n_css_class'],
									'title'=>$navigationItem['n_title'],
									'target'=>$navigationItem['n_target'],
									'uri'=>$navigationItem['n_uri'],
									'visible'=>$navigationItem['n_visible'],
									'id'=>$navigationItem['n_id'],
									'doNotTranslate'=>$navigationItem['n_do_not_translate'],
									'defaultLanguage'=>$navigationItem['n_default_language'],
								);
							}
						}
					}
				} catch (Doctrine_Connection_Exception $exception) {
					/**
					 * @todo maybe do something
					 */
				}
			}

			$endNavigationArray = $this->createNavigationEnd();
			if (is_array($endNavigationArray) &&
				count($endNavigationArray) > 0) {

				$navigationArray = array_merge($navigationArray, $endNavigationArray);
			}
		}

		/**
		 * save navigation-array inside class
		 */
		$this->_navigation = $navigationArray;
		$this->_setToCache($navigationArray);
	}

	/**
	 * Create sub navigation
	 *
	 * @param integer $navigationID
	 * @param string $dynamicShort
	 * @param array $urlOptions
	 * @return array
	 */
	private function createSubNavigationArray($navigationID = NULL, $dynamicShort = NULL, $urlOptions = NULL)
	{
		/**
		 * create empty navigation array
		 */
		$navigationArray = array();

		if ($navigationID !== NULL) {

			/**
			 * selects
			 */
			$selects = array(
				'n.action_resource',
				'n.css_class',
				'n.dynamic',
				'n.id',
				'n.navigation_id',
				'n.position',
				'n.target',
				'n.uri',
				'n.visible',
				'n.name',
				'n.title',
			);

			if ($this->_useTranslateablleNavigation) {
				$selects[] = 'n.default_language';
				$selects[] = 'n.do_not_translate';
			}

			/**
			 * retrieve sub level items
			 */
			$subNavigationItemsQuery = Doctrine_Query::create()
				->from('Default_Model_Navigation n')
				->select(implode(',', $selects))
				->leftJoin('n.RoleShort r')
				->addWhere('n.navigation_id = ? ', array($navigationID))
			;

			/**
			 * show only module navigation
			 */
			if ($this->_useModuleNavigation) {
				if ($this->_showInModule) {

					/**
					 * add condition
					 */
					$subNavigationItemsQuery->addWhere('n.show_in_module = ? ', array($this->_showInModule));
				} else {

					/**
					 * add condition
					 */
					$subNavigationItemsQuery->addWhere('n.show_in_module IS NULL', array());
				}
			}

			/**
			 * show loggedin
			 */
			if ($this->_showLoggedin()) {
				$subNavigationItemsQuery->addWhere('r.id = ? OR n.show_all = ? OR n.show_all_loggedin = ? ', array($this->_getRoleID(), TRUE, TRUE));
			} else {
				$subNavigationItemsQuery->addWhere('r.id = ? OR n.show_all = ? ', array($this->_getRoleID(), TRUE));
			}
			$subNavigationItems = $subNavigationItemsQuery
				->orderBy('n.position ASC')
				->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
				->execute()
			;

			/**
			 * do we have some items in retrieved array
			 */
			if(is_array($subNavigationItems) &&
				count($subNavigationItems) > 0) {

				/**
				 * create sub navigation
				 */
				/**
				 * go through navigation array
				 */
				foreach($subNavigationItems as $subNavigationItem) {

					/**
					 * prepare translation
					 */
					if (!$this->_useTranslateablleNavigation) {
						$subNavigationItem['n_default_language'] = NULL;
						$subNavigationItem['n_do_not_translate'] = NULL;
					}

					if ($subNavigationItem['n_action_resource'] !== NULL) {

						$actionResources = FALSE;
						$cache = L8M_Cache::getCache('Default_Model_Action');

						if ($cache) {
							$actionResources = $cache->load(L8M_Cache::getCacheId('resource', $subNavigationItem['n_action_resource']));
						}

						if ($actionResources === FALSE) {

							/**
							 * let's execute query
							 * @var Doctrine_Query
							 */
							$actionResources = Doctrine_Query::create()
								->from('Default_Model_Action a')
								->where('a.resource = ? ', array($subNavigationItem['n_action_resource']))
								->execute()
								->getFirst()
							;

							if ($cache) {
								$cache->save($actionResources, L8M_Cache::getCacheId('resource', $subNavigationItem['n_action_resource']));
							}
						}

						if ($actionResources) {

							$resourceArray = explode('.', $subNavigationItem['n_action_resource']);

							if (count($resourceArray) == 3) {

								/**
								 * internal site
								 */
								$navigationArray[] = array(
									'label'=>$subNavigationItem['n_name'],
									'style'=>NULL,
									'class'=>$subNavigationItem['n_css_class'],
									'title'=>$subNavigationItem['n_title'],
									'target'=>$subNavigationItem['n_target'],
									'visible'=>$subNavigationItem['n_visible'],
									'module'=>$resourceArray[0],
									'controller'=>$resourceArray[1],
									'action'=>$resourceArray[2],
									'id'=>$subNavigationItem['n_id'],
									'navigation_id'=>$subNavigationItem['n_navigation_id'],
									'pages'=>$this->createSubNavigationArray(
										$subNavigationItem['n_id'],
										$subNavigationItem['n_dynamic'],
										array(
											'module'=>$resourceArray[0],
											'controller'=>$resourceArray[1],
											'action'=>$resourceArray[2],
											'label'=>$subNavigationItem['n_name'],
											'class'=>$subNavigationItem['n_css_class'],
											'title'=>$subNavigationItem['n_title'],
											'target'=>$subNavigationItem['n_target'],
											'visible'=>$subNavigationItem['n_visible'],
										)
									),
									'doNotTranslate'=>$subNavigationItem['n_do_not_translate'],
									'defaultLanguage'=>$subNavigationItem['n_default_language'],
								);
							}
						}
					} else
					if ($subNavigationItem['n_uri'] !== NULL){

						/**
						 * external site
						 */
						$navigationArray[] = array(
							'label'=>$subNavigationItem['n_name'],
							'style'=>NULL,
							'class'=>$subNavigationItem['n_css_class'],
							'title'=>$subNavigationItem['n_title'],
							'target'=>$subNavigationItem['n_target'],
							'visible'=>$subNavigationItem['n_visible'],
							'uri'=>$subNavigationItem['n_uri'],
							'navigation_id'=>$subNavigationItem['n_navigation_id'],
							'doNotTranslate'=>$subNavigationItem['n_do_not_translate'],
							'defaultLanguage'=>$subNavigationItem['n_default_language'],
						);
					}
				}
			}
		}

		/**
		 * do we have a dynamic navigation request?
		 */
		if ($dynamicShort !== NULL) {

			/**
			 * retrive dynamic menu
			 */
			$navigationArray = $navigationArray + $dynamicNavigationArray = $this->createDynamicNavigation($navigationID, $dynamicShort, $urlOptions);
		}

		/**
		 * return array back to recursion start
		 */
		return $navigationArray;
	}

	/**
	 *
	 *
	 * Class Setters
	 *
	 *
	 */

	/**
	 * Sets L8M_Navigation_Adapter_Abstract instance.
	 *
	 * @param  L8M_Navigation_Adapter_Abstract $adapter
	 * @return L8M_Navigation
	 */
	public static function setAdapter($adapter = NULL)
	{
		if (!($adapter instanceof L8M_Navigation_Adapter_Abstract)) {
			throw new L8M_Navigation_Exception('Adapter needs to be specified as an L8M_Acl_Adapter_Abstract instance.');
		}
		self::$_adapter = $adapter;
	}

	/**
	 * Returns L8M_Navigation instance from L8M_Acl_Adapter_Abstract instance.
	 *
	 * @param  L8M_Navigation_Adapter_Abstract $adaptert
	 * @return L8M_Navigation
	 */
	public static function fromAdapter($adapter = NULL)
	{
		if ($adapter) {
			self::setAdapter($adapter);
		}
		if (!self::$_adapter) {
			throw new L8M_Navigation_Exception('No adapter instance has been specified.');
		}
		return self::$_adapter->getNavigation();
	}


	/**
	 * Returns language used by this application.
	 *
	 * @return void
	 */
	protected function _getLanguage()
	{
		if (self::$_language === NULL) {
			if (Zend_Registry::isRegistered('Zend_Locale')) {
				self::$_language = Zend_Registry::get('Zend_Locale')->getLanguage();
			} else {
				self::$_language = 'en';
			}
		}
		return self::$_language;
	}

	/**
	 * returns whether user is loggedin or not
	 *
	 * @return boolean
	 */
	protected function _showLoggedin()
	{
		/**
		 * retrieve user instance
		 */
		$user = Zend_Auth::getInstance()->getIdentity();
		if ($user !== NULL &&
			$user instanceof Default_Model_Entity) {

			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * returns role ID of user or guest-user-role
	 *
	 * @return integer
	 */
	protected function _getRoleID()
	{
		/**
		 * do we have a roleID
		 */
		if ($this->_roleID !== NULL) {
			return $this->_roleID;
		}

		/**
		 * retrieve user instance
		 */
		$user = Zend_Auth::getInstance()->getIdentity();
		if ($user !== NULL &&
			$user instanceof Default_Model_Entity) {

			/**
			 * user has identity, so get roleID
			 */
			$roleID = $user->Role['id'];
		} else {

			/**
			 * get guest role id, but first:
			 * do we have a database connection?
			 */
			if (class_exists('Default_Model_Base_Role', TRUE)) {
				try {
					/**
					 * let's execute query
					 * @var Doctrine_Query
					 */
					$guestRoles = Doctrine_Query::create()
						->from('Default_Model_Role r')
						->where('r.disabled = ?', 0)
						->addWhere('r.short = ?', 'guest')
						->select('r.id')
						->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
						->execute()
					;
					if (is_array($guestRoles) &&
						count($guestRoles) > 0 &&
						isset($guestRoles[0]['id'])) {

						/**
						 * database guest role id
						 */
						$roleID = $guestRoles[0]['id'];
					} else {

						/**
						 * @todo add an exception, 'cause there should be one guest-role!
						 */
					}
				} catch (Doctrine_Connection_Exception $exception) {

					/**
					 * set guest userID
					 */
					$roleID = self::ROLE_GUEST_ID;
				}
			} else {

				/**
				 * set guest userID
				 */
				$roleID = self::ROLE_GUEST_ID;
			}
		}

		if (!isset($roleID)) {
			$viewFromMVC = Zend_Layout::getMvcInstance()->getView();
			if ($viewFromMVC &&
				isset($viewFromMVC->layout()->systemSetupProcessConfirmed) &&
				$viewFromMVC->layout()->systemSetupProcessConfirmed) {

				if (isset($viewFromMVC->layout()->setupWithoutDatabase) &&
					$viewFromMVC->layout()->setupWithoutDatabase) {

					/**
					 * set guest userID
					 */
					$roleID = self::ROLE_GUEST_ID;
				}
			}

			if (!isset($roleID)) {
				throw new L8M_Exception('Could not find Role within Navigation.');
			}
		}

		/**
		 * save to class
		 */
		$this->_roleID = $roleID;

		/**
		 * return roleID
		 */
		return $roleID;
	}

	/**
	 * There is no parent.
	 */
	public function getParent()
	{
		return NULL;
	}

	/**
	 * Retrieve menu array from cache
	 *
	 * @return array
	 */
	protected function _getFromCache()
	{
		$returnValue = FALSE;

		$cache = L8M_Cache::getCache('L8M_Navigation');
		if ($cache) {
			if (Zend_Auth::getInstance()->hasIdentity()) {
				$roleShort = Zend_Auth::getInstance()->getIdentity()->Role->short;
				$calledForModuleName = Zend_Layout::getMvcInstance()->getView()->layout()->calledForModuleName;
				if (($roleShort != 'admin' || $roleShort != 'supervisor') &&
					($calledForModuleName != 'admin' || $calledForModuleName != 'system')) {

					$returnValue = $cache->load('navigation_' . $roleShort . '_lang_' . L8M_Locale::getLang());
				}
			} else {
				$returnValue = $cache->load('navigation_lang_' . L8M_Locale::getLang());
			}

		}

		return $returnValue;
	}

	/**
	 * Set menu array to cache
	 *
	 * @param array $navigation
	 */
	protected function _setToCache($navigation = array())
	{
		$cache = L8M_Cache::getCache('L8M_Navigation');
		if ($cache) {
			if (Zend_Auth::getInstance()->hasIdentity()) {
				$roleShort = Zend_Auth::getInstance()->getIdentity()->Role->short;
				$calledForModuleName = Zend_Layout::getMvcInstance()->getView()->layout()->calledForModuleName;
				if (($roleShort != 'admin' || $roleShort != 'supervisor') &&
					($calledForModuleName != 'admin' || $calledForModuleName != 'system')) {

					$cache->save($navigation, 'navigation_' . $roleShort . '_lang_' . L8M_Locale::getLang());
				}
			} else {
				$cache->save($navigation, 'navigation_lang_' . L8M_Locale::getLang());
			}
		}
	}
}