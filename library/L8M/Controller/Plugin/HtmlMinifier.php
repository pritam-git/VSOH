<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/HtmlMinifier.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: HtmlMinifier.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_HtmlMinifier
 *
 *
 */
class L8M_Controller_Plugin_HtmlMinifier extends Zend_Controller_Plugin_Abstract
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

		$calledForModuleName = $layout->calledForModuleName;
		$calledForControllerName = $layout->calledForControllerName;
		$calledForResource = $layout->calledForResource;
		$moduleExceptions = L8M_Config::getOption('HtmlMinifier.exceptions.module');
		if ($moduleExceptions === NULL) {
			$moduleExceptions = array();
		}
		$resourceExceptions = L8M_Config::getOption('HtmlMinifier.exceptions.resource');
		if ($resourceExceptions === NULL) {
			$resourceExceptions = array();
		}

		if ($calledForModuleName == 'admin' ||
			$calledForModuleName == 'system' ||
			$calledForModuleName == 'system-model-list' ||
			($calledForModuleName == 'default' && $calledForControllerName == 'error') ||
			in_array($calledForModuleName, $moduleExceptions) ||
			in_array($calledForResource, $resourceExceptions)) {

			if ($calledForControllerName != 'login') {
				return;
			}
		}

		if (L8M_Config::getOption('zfdebug.enabled')) {
			return;
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
	 * Minifies HTML
	 *
	 * @return void
	 */
	protected function _output()
	{
		$response = $this->getResponse();
//		$response->setBody(preg_replace('(\r|\n|\t)', '', $response->getBody()));

		/**
		 * Thanks to ridgerunnerless
		 * Website: http://www.jmrware.com/
		 * Code from http://stackoverflow.com/questions/5312349/minifying-final-html-output-using-regular-expressions-with-codeigniter#5324014
		 */
		/**
		 * 8MB stack. *nix
		 */
		ini_set('pcre.recursion_limit', '16777');
		$regEx = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';

		$htmlContent = $response->getBody();
		if (mb_strlen($htmlContent) <= 200000) {
			$responseText = preg_replace($regEx, ' ', $htmlContent);
		} else {
			$responseText = $htmlContent;
		}

		if ($responseText === null) {
			$infoBox = '<div class="l8m-pcr-error">PCRE Error! File too big at L8M_Controller_Plugin_HtmlMinifier.</div>' . PHP_EOL;
			$responseText = preg_replace('/(<\/body>)/i',$infoBox . '$1', $response->getBody());
		}

		$response->setBody($responseText);
	}
}