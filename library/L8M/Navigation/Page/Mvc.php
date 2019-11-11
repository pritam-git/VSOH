<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Navigation/Page/Mvc.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Mvc.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 *
 *
 * L8M_Navigation_Page_Mvc
 *
 *
 */
class L8M_Navigation_Page_Mvc extends Zend_Navigation_Page
{
	/**
	 * Action name to use when assembling URL
	 *
	 * @var string
	 */
	protected $_action;

	/**
	 * Controller name to use when assembling URL
	 *
	 * @var string
	 */
	protected $_controller;

	/**
	 * Module name to use when assembling URL
	 *
	 * @var string
	 */
	protected $_module;

	/**
	 * Params to use when assembling URL
	 *
	 * @see getHref()
	 * @var array
	 */
	protected $_params = array();

	/**
	 * Route name to use when assembling URL
	 *
	 * @see getHref()
	 * @var string
	 */
	protected $_route;

	/**
	 * Whether params should be reset when assembling URL
	 *
	 * @see getHref()
	 * @var bool
	 */
	protected $_resetParams = true;

	/**
	 * Cached href
	 *
	 * The use of this variable minimizes execution time when getHref() is
	 * called more than once during the lifetime of a request. If a property
	 * is updated, the cache is invalidated.
	 *
	 * @var string
	 */
	protected $_hrefCache;

	/**
	 * Action helper for assembling URLs
	 *
	 * @see getHref()
	 * @var Zend_Controller_Action_Helper_Url
	 */
	protected static $_urlHelper = null;

	// Accessors:

	/**
	 * Returns whether page should be considered active or not
	 *
	 * This method will compare the page properties against the request object
	 * that is found in the front controller.
	 *
	 * @param  bool $recursive  [optional] whether page should be considered
	 *                          active if any child pages are active. Default is
	 *                          false.
	 * @return bool             whether page should be considered active or not
	 */
	public function isActive($recursive = false)
	{
		$returnValue = FALSE;

		if (!$this->_active) {
			$front = Zend_Controller_Front::getInstance();
			$reqParams = $front->getRequest()->getParams();

			$resourceLang = L8M_Locale::getLang();
			if (isset($reqParams['lang'])) {
				$resourceLang = $reqParams['lang'];
				unset($reqParams['lang']);
			}

			if (!array_key_exists('module', $reqParams)) {
				$reqParams['module'] = $front->getDefaultModule();
			}

			$myParams = $this->_params;

			if (NULL !== $this->_module) {
				$myParams['module'] = $this->_module;
			} else {
				$myParams['module'] = $front->getDefaultModule();
			}

			if (NULL !== $this->_controller) {
				$myParams['controller'] = $this->_controller;
			} else {
				$myParams['controller'] = $front->getDefaultControllerName();
			}

			if (NULL !== $this->_action) {
				$myParams['action'] = $this->_action;
			} else {
				$myParams['action'] = $front->getDefaultAction();
			}

			if (class_exists('Default_Model_ResourceTranslator', TRUE)) {
				$resource = L8M_Acl_Resource::getResourceName($myParams['module'], $myParams['controller'], $myParams['action']);
				try {
					/**
					 * do we have a translation for possible original resource
					 */
					$resourceTranslatorModel = Doctrine_Query::create()
						->from('Default_Model_ResourceTranslator m')
						->where('m.resource = ? ', array($resource))
						->limit(1)
						->execute()
						->getFirst()
					;
					if ($resourceTranslatorModel) {
						$tmpResource = $resourceTranslatorModel->Translation[$resourceLang]['uresource'];
						$tmpResourceArray = explode('.', $tmpResource);

						if ($tmpResource != $resource &&
							count($tmpResourceArray) == 3) {

							$myParams['module'] = $tmpResourceArray[0];
							$myParams['controller'] = $tmpResourceArray[1];
							$myParams['action'] = $tmpResourceArray[2];
						}
					}
				} catch (Doctrine_Connection_Exception $exception) {
				/**
				 * @todo maybe do something
				 */

				}
			}

			if (count(array_intersect_assoc($reqParams, $myParams)) ==
				count($myParams)) {

				$this->_active = TRUE;
				$returnValue = TRUE;
			} else
			if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Action' . DIRECTORY_SEPARATOR . 'Param.php') &&
				class_exists('PRJ_Controller_Action_Param')) {

				$actionParam = new PRJ_Controller_Action_Param();
				if ($actionParam instanceof L8M_Controller_Action_Param &&
					$actionParam->checkController($myParams['action'], $myParams['controller'], $myParams['module'], $resourceLang)) {

					unset($myParams['action']);
					unset($reqParams['action']);


					if (count(array_intersect_assoc($reqParams, $myParams)) ==
						count($myParams)) {

						$this->_active = TRUE;
						$returnValue = TRUE;
					}
				}
			}
		}

		if (!$returnValue) {
			$returnValue = parent::isActive($recursive);
		}

		return $returnValue;
	}

	/**
	 * Returns href for this page
	 *
	 * This method uses {@link Zend_Controller_Action_Helper_Url} to assemble
	 * the href based on the page's properties.
	 *
	 * @param  bool $prefetchLink Tells to enable prefetching url in L8M_View_Helper_HeadLinkPrefetch
	 * @return string  page href
	 */
	public function getHref($prefetchLink = TRUE)
	{
		if ($this->_hrefCache) {
			return $this->_hrefCache;
		}

		if (null === self::$_urlHelper) {
			self::$_urlHelper =
				Zend_Controller_Action_HelperBroker::getStaticHelper('Url');
		}

		$params = $this->getParams();

		if ($param = $this->getModule()) {
			$params['module'] = $param;
		}

		if ($param = $this->getController()) {
			$params['controller'] = $param;
		}

		if ($param = $this->getAction()) {
			$params['action'] = $param;
		}

		$url = self::$_urlHelper->url($params,
									  $this->getRoute(),
									  $this->getResetParams(),
									  TRUE,
									  $prefetchLink);

		return $this->_hrefCache = $url;
	}

	/**
	 * Sets action name to use when assembling URL
	 *
	 * @see getHref()
	 *
	 * @param  string $action             action name
	 * @return Zend_Navigation_Page_Mvc   fluent interface, returns self
	 * @throws Zend_Navigation_Exception  if invalid $action is given
	 */
	public function setAction($action)
	{
		if (null !== $action && !is_string($action)) {
			require_once 'Zend' . DIRECTORY_SEPARATOR . 'Navigation' . DIRECTORY_SEPARATOR . 'Exception.php';
			throw new Zend_Navigation_Exception(
					'Invalid argument: $action must be a string or null');
		}

		$this->_action = $action;
		$this->_hrefCache = null;
		return $this;
	}

	/**
	 * Returns action name to use when assembling URL
	 *
	 * @see getHref()
	 *
	 * @return string|null  action name
	 */
	public function getAction()
	{
		return $this->_action;
	}

	/**
	 * Sets controller name to use when assembling URL
	 *
	 * @see getHref()
	 *
	 * @param  string|null $controller    controller name
	 * @return Zend_Navigation_Page_Mvc   fluent interface, returns self
	 * @throws Zend_Navigation_Exception  if invalid controller name is given
	 */
	public function setController($controller)
	{
		if (null !== $controller && !is_string($controller)) {
			require_once 'Zend' . DIRECTORY_SEPARATOR . 'Navigation' . DIRECTORY_SEPARATOR . 'Exception.php';
			throw new Zend_Navigation_Exception(
					'Invalid argument: $controller must be a string or null');
		}

		$this->_controller = $controller;
		$this->_hrefCache = null;
		return $this;
	}

	/**
	 * Returns controller name to use when assembling URL
	 *
	 * @see getHref()
	 *
	 * @return string|null  controller name or null
	 */
	public function getController()
	{
		return $this->_controller;
	}

	/**
	 * Sets module name to use when assembling URL
	 *
	 * @see getHref()
	 *
	 * @param  string|null $module	      module name
	 * @return Zend_Navigation_Page_Mvc   fluent interface, returns self
	 * @throws Zend_Navigation_Exception  if invalid module name is given
	 */
	public function setModule($module)
	{
		if (null !== $module && !is_string($module)) {
			require_once 'Zend' . DIRECTORY_SEPARATOR . 'Navigation' . DIRECTORY_SEPARATOR . 'Exception.php';
			throw new Zend_Navigation_Exception(
					'Invalid argument: $module must be a string or null');
		}

		$this->_module = $module;
		$this->_hrefCache = null;
		return $this;
	}

	/**
	 * Returns module name to use when assembling URL
	 *
	 * @see getHref()
	 *
	 * @return string|null  module name or null
	 */
	public function getModule()
	{
		return $this->_module;
	}

	/**
	 * Sets params to use when assembling URL
	 *
	 * @see getHref()
	 *
	 * @param  array|null $params       [optional] page params. Default is null
	 *	                                which sets no params.
	 * @return Zend_Navigation_Page_Mvc  fluent interface, returns self
	 */
	public function setParams(array $params = null)
	{
		if (null === $params) {
			$this->_params = array();
		} else {
			// TODO: do this more intelligently?
			$this->_params = $params;
		}

		$this->_hrefCache = null;
		return $this;
	}

	/**
	 * Returns params to use when assembling URL
	 *
	 * @see getHref()
	 *
	 * @return array  page params
	 */
	public function getParams()
	{
		return $this->_params;
	}

	/**
	 * Sets route name to use when assembling URL
	 *
	 * @see getHref()
	 *
	 * @param  string $route              route name to use when assembling URL
	 * @return Zend_Navigation_Page_Mvc   fluent interface, returns self
	 * @throws Zend_Navigation_Exception  if invalid $route is given
	 */
	public function setRoute($route)
	{
		if (null !== $route && (!is_string($route) || strlen($route) < 1)) {
			require_once 'Zend' . DIRECTORY_SEPARATOR . 'Navigation' . DIRECTORY_SEPARATOR . 'Exception.php';
			throw new Zend_Navigation_Exception(
				 'Invalid argument: $route must be a non-empty string or null');
		}

		$this->_route = $route;
		$this->_hrefCache = null;
		return $this;
	}

	/**
	 * Returns route name to use when assembling URL
	 *
	 * @see getHref()
	 *
	 * @return string  route name
	 */
	public function getRoute()
	{
		return $this->_route;
	}

	/**
	 * Sets whether params should be reset when assembling URL
	 *
	 * @see getHref()
	 *
	 * @param  bool $resetParams         whether params should be reset when
	 *                                   assembling URL
	 * @return Zend_Navigation_Page_Mvc  fluent interface, returns self
	 */
	public function setResetParams($resetParams)
	{
		$this->_resetParams = (bool) $resetParams;
		$this->_hrefCache = null;
		return $this;
	}

	/**
	 * Returns whether params should be reset when assembling URL
	 *
	 * @see getHref()
	 *
	 * @return bool  whether params should be reset when assembling URL
	 */
	public function getResetParams()
	{
		return $this->_resetParams;
	}

	/**
	 * Sets action helper for assembling URLs
	 *
	 * @see getHref()
	 *
	 * @param  Zend_Controller_Action_Helper_Url $uh  URL helper
	 * @return void
	 */
	public static function setUrlHelper(Zend_Controller_Action_Helper_Url $uh)
	{
		self::$_urlHelper = $uh;
	}

	// Public methods:

	/**
	 * Returns an array representation of the page
	 *
	 * @return array  associative array containing all page properties
	 */
	public function toArray()
	{
		return array_merge(
			parent::toArray(),
			array(
				'action'       => $this->getAction(),
				'controller'   => $this->getController(),
				'module'       => $this->getModule(),
				'params'       => $this->getParams(),
				'route'        => $this->getRoute(),
				'reset_params' => $this->getResetParams()
			));
	}
}
