<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Session/Locale.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Locale.php 378 2015-07-08 10:16:41Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Session_Locale
 *
 *
 */
class L8M_Controller_Plugin_Session_Locale extends Zend_Controller_Plugin_Abstract
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
	protected $_localeOptions = array();
	protected $_supportedLocale = array();

	/**
	 *
	 *
	 * Class Constructors
	 *
	 *
	 */

	/**
	 * Constructs L8M_Controller_Plugin_Session_Locale instance.
	 *
	 * @return void
	 */
	public function __construct($localeOptions)
	{
		if (is_array($localeOptions)) {
			$this->_localeOptions = $localeOptions;
		}

		$this->_supportedLocale = L8M_Locale::getSupportedForLinks();
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */


	/**
	 * Sets the application locale and translation based on the locale param, if
	 * one is not provided it defaults to english
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{
		if (!Zend_Registry::isRegistered('Zend_Locale')) {
			/**
			 * requested locale
			 */
			/**
			 * need session for locale
			 */
			$session = new Zend_Session_Namespace('L8M_Controller_Plugin_Session_Locale');

			/**
			 * retrieve locale from request
			 */
			$lang = $request->getParam('lang');

			/**
			 * do we have a lang-short supported by the system?
			 * if not try auto detect
			 */
			if ($lang != NULL &&
				!in_array($lang, $this->_supportedLocale) &&
				$session->lang == NULL) {

				$lang = 'auto';
			} else

			/**
			 * do we have no lang via request but a session defined lang-short, use it.
			 */
			if ($lang == NULL &&
				$session->lang != NULL)	{

				$lang = $session->lang . '_' . $session->region;
			} else

			/**
			 * do we have a lang-short, that is supported by system, use it.
			 */
			if ($lang != NULL &&
				in_array($lang, $this->_supportedLocale)) {

				/**
				 * lang is lang
				 */
			} else

			/**
			 * do we have a lang-short, that is not supported by system, use default.
			 */
			if ($lang != NULL &&
				!in_array($lang, $this->_supportedLocale)) {

				$lang = $this->_localeOptions['default'] . '_' . $this->_localeOptions['region']['default'];
			} else

			/**
			 * autosetup locale if not set yet
			 */
			if ($lang == NULL &&
				$session->lang == NULL) {

				$lang = 'auto';
			}

			$locale = new Zend_Locale($lang);

			/**
			 * only the language part of the locale is important and not the localized language name
			 */
			if ($lang == 'auto' &&
				!in_array($locale->getLanguage(), $this->_supportedLocale)) {

				$localeLanguage = $this->_localeOptions['default'];
				$localeRegion = $this->_localeOptions['region']['default'];
			} else {
				$localeLanguage = $locale->getLanguage();
				$localeRegion = $locale->getRegion();
				if (!$localeRegion) {
					$localeRegion = strtoupper($localeLanguage);
				}
				if (!L8M_Locale::isLocaleExisting($localeLanguage . '_' . $localeRegion)) {
					$localeRegion = L8M_Locale::getRegionForLang($localeLanguage);
				}
			}

			/**
			 * save old language
			 */
			if ($localeLanguage != $session->lang) {
				$session->oldLang = $session->lang;
				L8M_Locale::setOldLang($session->lang);
			}

			/**
			 * backend language to session
			 */
			$actBackendLang = NULL;
			if (in_array(L8M_Acl_CalledFor::module(), L8M_Config::getOption('locale.backend.modules'))) {
				$actBackendLang = $localeLanguage;
				if (!in_array($actBackendLang, L8M_Config::getOption('locale.backend.supported'))) {
					$actBackendLang = L8M_Config::getOption('locale.backend.default');
				}
			}
			if ($actBackendLang) {
				$session->actualBackendLang = $actBackendLang;
			}

			/**
			 * init lang
			 */
			$locale->setLocale($localeLanguage);
			$session->lang = $localeLanguage;
			$session->region = $localeRegion;
			L8M_Locale::setLang($localeLanguage);
			L8M_Locale::setRegion($localeRegion);
// 			L8M_Locale::setSupported($this->_supportedLocale);
			L8M_Locale::setDefault($this->_localeOptions['default']);
			L8M_Locale::setOldLang($session->oldLang);
			L8M_Locale::setLangForLinkBackend($session->actualBackendLang);

			/**
			 * set translate with locale
			 */
			if (Zend_Registry::isRegistered('Zend_Translate')) {

				/**
				 * retrieve translate
				 */
				$translate = Zend_Registry::get('Zend_Translate');

				/**
				 * check
				 */
				if (($translate instanceof Zend_Translate) &&
					$translate->isAvailable($locale) &&
					in_array($locale->getLanguage(), $this->_supportedLocale)) {

					/**
					 * available locale
					 */
					$locale->setDefault($locale);
				} else
				if (isset($this->_localeOptions['default']) &&
					$this->_localeOptions['default']) {

					/**
					 * default locale
					 */
					$locale = new Zend_Locale($this->_localeOptions['default']);
				} else {
					$locale = new Zend_Locale('en');
				}

				/**
				 * set locale in translate adapter
				 */
				if ($translate instanceof Zend_Translate) {
					$translate->getAdapter()->setLocale($locale);
				}
			}

			/**
			 * register
			 */
			Zend_Registry::set('Zend_Locale', $locale);
		}
	}
}