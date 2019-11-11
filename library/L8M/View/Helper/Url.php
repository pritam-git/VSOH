<?php

class L8M_View_Helper_Url extends Zend_View_Helper_Abstract
{
	/**
	 * Generates an url given the name of a route.
	 *
	 * @access public
	 *
	 * @param  array $urlOptions Options passed to the assemble method of the Route object.
	 * @param  mixed $name The name of a Route to use. If null it will use the current Route
	 * @param  bool $reset Whether or not to reset the route defaults with those provided
	 * @param  bool $encode Tells to encode URL parts on output
	 * @param bool $prefetchLink Tells to enable prefetching url in L8M_View_Helper_HeadLinkPrefetch
	 * @return string Url for the link href attribute.
	 */
	public function url(array $urlOptions = array(), $name = NULL, $reset = FALSE, $encode = TRUE, $prefetchLink = FALSE)
	{
		if (!array_key_exists('lang', $urlOptions)) {
			$supportedLangModule = NULL;
			if (isset($urlOptions['module'])) {
				$supportedLangModule = $urlOptions['module'];
			}
			$urlOptions['lang'] = L8M_Locale::getLangForLink($supportedLangModule);
		}

		$router = Zend_Controller_Front::getInstance()->getRouter();
		return $router->assemble($urlOptions, $name, $reset, $encode, $prefetchLink);
	}
}
