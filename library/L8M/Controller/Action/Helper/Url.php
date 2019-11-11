<?php
/**
 * L8M
 *
 *
 * @filesource library/L8M/Controller/Action/Helper/Url.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Url.php 378 2015-07-08 10:16:41Z nm $
 */


/**
 *
 *
 * L8M_Controller_Action_Helper_Url
 *
 *
 */
class L8M_Controller_Action_Helper_Url extends Zend_Controller_Action_Helper_Url
{
	/**
	 * Create URL based on default route
	 *
	 * @param  string $action
	 * @param  string $controller
	 * @param  string $module
	 * @param  array  $params
	 * @param  bool   $prefetchLink Tells to enable prefetching url in L8M_View_Helper_HeadLinkPrefetch
	 * @return string
	 */
	public function simple($action, $controller = NULL, $module = NULL, array $params = NULL, $prefetchLink = FALSE)
	{
		$request = $this->getRequest();

		$params['action'] = $action;

		if ($controller === NULL) {
			$controller = $request->getControllerName();
		}
		$params['controller'] = $controller;

		if ($module === NULL) {
			$module = $request->getModuleName();
		}
		$params['module'] = $module;

		$router = Zend_Controller_Front::getInstance()->getRouter();
		$returnValue = $router->assemble($params, FALSE, TRUE, TRUE, $prefetchLink);

		/**
		 * return url
		 */
		return $returnValue;
	}

	/**
	 * Assembles a URL based on a given route
	 *
	 * This method will typically be used for more complex operations, as it
	 * ties into the route objects registered with the router.
	 *
	 * @param  array   $urlOptions Options passed to the assemble method of the Route object.
	 * @param  mixed   $name	   The name of a Route to use. If null it will use the current Route
	 * @param  boolean $reset
	 * @param  boolean $encode
	 * @param  bool    $prefetchLink Tells to enable prefetching url in L8M_View_Helper_HeadLinkPrefetch
	 * @return string Url for the link href attribute.
	 */
	public function url($urlOptions = array(), $name = NULL, $reset = FALSE, $encode = TRUE, $prefetchLink = FALSE)
	{
		$router = $this->getFrontController()->getRouter();
		return $router->assemble($this->_prepareParamLang($urlOptions), $name, $reset, $encode, $prefetchLink);
	}

	/**
	 * Perform helper when called as $this->_helper->url() from an action controller
	 *
	 * Proxies to {@link simple()}
	 *
	 * @param  string $action
	 * @param  string $controller
	 * @param  string $module
	 * @param  array  $params
	 * @param  bool   $prefetchLink Tells to enable prefetching url in L8M_View_Helper_HeadLinkPrefetch
	 * @return string
	 */
	public function direct($action, $controller = NULL, $module = NULL, array $params = NULL, $prefetchLink = FALSE)
	{
		return $this->simple($action, $controller, $module, $this->_prepareParamLang($params), $prefetchLink);
	}

	/**
	 * Prepend Language-Short in front of the url.
	 *
	 * @param $params
	 * @return array
	 */
	private function _prepareParamLang($params = array())
	{

		if (!is_array($params)) {
			$params = array();
		}

		if (!array_key_exists('lang', $params)) {
			$supportedLangModule = NULL;
			if (isset($params['module'])) {
				$supportedLangModule = $params['module'];
			}
			$params['lang'] = L8M_Locale::getLangForLink($supportedLangModule);
		}

		return $params;
	}
}
