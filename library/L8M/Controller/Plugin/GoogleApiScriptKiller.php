<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/GoogleApiScriptKiller.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: GoogleApiScriptKiller.php 565 2018-05-23 11:24:56Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_GoogleApiScriptKiller
 *
 *
 */
class L8M_Controller_Plugin_GoogleApiScriptKiller extends Zend_Controller_Plugin_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Called before Zend_Controller_Front exits its dispatch loop.
	 *
	 * @return void
	 */
	public function dispatchLoopShutdown()
	{
		if ($this->getRequest()->isXmlHttpRequest()) {
			return;
		}

		$layout = Zend_Layout::getMvcInstance();
		if (!$layout) {
			return;
		}

		$headers = $this->_response->getHeaders();
		$contentTypeExceptions = array(
			'text/html',
		);
		foreach ($headers as $key => $header) {
			if ($header['name'] == 'Content-Type' &&
				$header['value'] != 'text/html') {

				return;
			}
		}

		$this->_output();
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Kill Google Api Script
	 *
	 * @return void
	 */
	protected function _output()
	{
		$goOn = FALSE;

		if (L8M_Config::getOption('google.ApiScriptKiller.enabled') === TRUE) {
			$goOn = TRUE;
		} else
		if (L8M_Config::getOption('google.ApiScriptKiller.enabled') == 'auto' &&
			L8M_Library::getPing('google.de') === FALSE) {

			$goOn = TRUE;
		}

		if ($goOn)  {
			$responseBody = $this->getResponse()->getBody();

			$httpScheme = 'http';
			if (L8M_Library::isHttpHostSecure()) {
				$httpScheme = 'https';
			}
			$responseBody = str_replace($httpScheme . '://ajax.googleapis.com/ajax/libs/jquery/' . L8M_Config::getOption('resources.jquery.version') . '/jquery.min.js', '/js/jquery/jquery-' . L8M_Config::getOption('resources.jquery.version') . '.min.js', $responseBody);
			$responseBody = str_replace($httpScheme . '://' . L8M_Config::getOption('resources.jquery.migration.cdn_url') . 'jquery-migrate-' . L8M_Config::getOption('resources.jquery.migration.version') . '.min.js', '/js/jquery/jquery-migrate-' . L8M_Config::getOption('resources.jquery.migration.version') . '.min.js', $responseBody);
			$responseBody = str_replace($httpScheme . '://ajax.googleapis.com/ajax/libs/jqueryui/' . L8M_Config::getOption('resources.jquery.ui_version') . '/jquery-ui.min.js', '/js/jquery/jquery-ui-' . L8M_Config::getOption('resources.jquery.ui_version') . '.min.js', $responseBody);
			$responseBody = str_replace('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js', '/js/jquery/popper.min.js', $responseBody);
			$responseBody = str_replace('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/i18n/jquery-ui-i18n.min.js', '/js/jquery/jquery-ui-i18n.min.js', $responseBody);

			$this->getResponse()->setBody($responseBody);
		}
	}
}