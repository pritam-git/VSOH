<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Translate/Adapter/Doctrine .php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Doctrine.php 111 2014-06-16 13:39:45Z nm $
 */

/**
 *
 *
 * L8M_Translate_Adapter_Doctrine
 *
 *
 */
class L8M_Translate_Adapter_Doctrine extends Zend_Translate_Adapter
{
	private $_data = array();

	/**
	 * Load translation data
	 *
	 * @param  string|array  $data
	 * @param  string		$locale  Locale/Language to add data for, identical with locale identifier,
	 *								see Zend_Locale for more information
	 * @param  array		 $options OPTIONAL Options to use
	 * @return array
	 */
	protected function _loadTranslationData($data, $locale, array $options = array())
	{
		$data = array();
		if (L8M_Locale::getDefault() != NULL) {
			if (L8M_Doctrine::isEnabled() == TRUE &&
				class_exists('Default_Model_Translator', TRUE)) {


				try {
					/**
					 * let's execute query
					 * @var Doctrine_Query
					 */
					$transaltionCollection = Doctrine_Query::create()
						->from('Default_Model_Translator t')
						->limit(1)
						->execute()
					;

					foreach ($transaltionCollection as $translation) {
						if (isset($translation->Translation[$locale])) {
							$data[$translation['short']] = $translation->Translation[$locale]['text'];
						} else {

							/**
							 * not translated yet
							 */
							$this->_log($translation['short'], $locale);

							$dataMessage = $this->_getMessageWithLocale($translation['short'], L8M_Config::getOption('locale.defaultSystem'), $locale, $options);
							$data[$translation['short']] = $dataMessage;

							$translation
								->merge(array(
									'Translation' => array(
										$locale => array(
											'text' => $dataMessage,
										),
									),
									'untranslated' => TRUE,
								))
							;
							$translation->save();
						}
					}
				} catch (Doctrine_Connection_Exception $exception) {
					/**
					 * @todo maybe do something
					 */
				}
			}
		}

		if (!isset($this->_data[$locale])) {
			$this->_data[$locale] = array();
		}

		$this->_data[$locale] = $data + $this->_data[$locale];

		return $this->_data;
	}


	/**
	 * Translates the given string
	 * returns the translation
	 *
	 * @see Zend_Locale
	 * @param  string|array	   $messageId Translation string, or Array for plural translations
	 * @param  string|Zend_Locale $locale	(optional) Locale/Language to use, identical with
	 *									   locale identifier, @see Zend_Locale for more information
	 * @return string
	 */
	public function translate($messageId, $defaultLocale = NULL, $locale = NULL)
	{
		if ($defaultLocale === NULL) {
			if (L8M_Config::getOption('locale.defaultSystem')) {
				$defaultLocale = L8M_Config::getOption('locale.defaultSystem');
			} else {
				$defaultLocale = L8M_Locale::getDefault();
			}
		}

		if ($locale === NULL) {
			$locale = $this->_options['locale'];
		}

		$plural = NULL;
		if (is_array($messageId)) {
			if (count($messageId) > 2) {
				$number = array_pop($messageId);
				if (!is_numeric($number)) {
					$plocale = $number;
					$number  = array_pop($messageId);
				} else {
					$plocale = L8M_Locale::getDefault();
				}

				$plural	= $messageId;
				$messageId = $messageId[0];
			} else {
				$messageId = $messageId[0];
			}
		}

		if (!Zend_Locale::isLocale($locale, TRUE, FALSE)) {
			if (!Zend_Locale::isLocale($locale, FALSE, FALSE)) {
				// language does not exist, return original string
				$this->_log($messageId, $locale);
				// use rerouting when enabled
				if (!empty($this->_options['route'])) {
					if (array_key_exists($locale, $this->_options['route']) &&
						!array_key_exists($locale, $this->_routed)) {
						$this->_routed[$locale] = TRUE;
						return $this->translate($messageId, $this->_options['route'][$locale]);
					}

					$this->_routed = array();
				}

				if ($plural === NULL) {
					return $messageId;
				}

				$rule = Zend_Translate_Plural::getPlural($number, $plocale);
				if (!isset($plural[$rule])) {
					$rule = 0;
				}

				return $plural[$rule];
			}

			$locale = new Zend_Locale($locale);
		}

		$locale = (string) $locale;
		if ((is_string($messageId) || is_int($messageId)) && isset($this->_translate[$locale][$messageId])) {
			// return original translation
			if ($plural === NULL) {
				return $this->_translate[$locale][$messageId];
			}

			$rule = Zend_Translate_Plural::getPlural($number, $locale);
			if (isset($this->_translate[$locale][$plural[0]][$rule])) {
				return $this->_translate[$locale][$plural[0]][$rule];
			}
		} else if (strlen($locale) != 2) {
			// faster than creating a new locale and separate the leading part
			$locale = substr($locale, 0, -strlen(strrchr($locale, '_')));

			if ((is_string($messageId) || is_int($messageId)) && isset($this->_translate[$locale][$messageId])) {
				// return regionless translation (en_US -> en)
				if ($plural === NULL) {
					return $this->_translate[$locale][$messageId];
				}

				$rule = Zend_Translate_Plural::getPlural($number, $locale);
				if (isset($this->_translate[$locale][$plural[0]][$rule])) {
					return $this->_translate[$locale][$plural[0]][$rule];
				}
			}
		}

		$this->_log($messageId, $locale);
		// use rerouting when enabled
		if (!empty($this->_options['route'])) {
			if (array_key_exists($locale, $this->_options['route']) &&
				!array_key_exists($locale, $this->_routed)) {
				$this->_routed[$locale] = TRUE;
				return $this->translate($messageId, $this->_options['route'][$locale]);
			}

			$this->_routed = array();
		}

		if ($plural === NULL) {

			/**
			 * check in db
			 */
			$transaltionModel = FALSE;
			$transaltionModelTranslateMessage = $this->_getFromCache($messageId, $locale);

			if (!$transaltionModelTranslateMessage &&
				L8M_Doctrine::isEnabled() == TRUE &&
				class_exists('Default_Model_Translator', TRUE)) {

				$translateByModelImport = FALSE;
				try {
					/**
					 * let's execute query
					 * @var Doctrine_Query
					 */

					$transaltionModel = Doctrine_Query::create()
						->from('Default_Model_Translator t')
						->addWhere('t.short = ? ', array(mb_substr($messageId, 0, 256)))
						->limit(1)
						->execute()
						->getFirst()
					;

					/**
					 * check wether we have somthing in there already
					 */
					if (!$transaltionModel) {
						$transaltionTestModel = Doctrine_Query::create()
							->from('Default_Model_Translator t')
							->limit(1)
							->execute()
							->getFirst()
						;
						if (!$transaltionTestModel) {
							$translateByModelImport = TRUE;
						}
					}
				} catch (Doctrine_Connection_Exception $exception) {
					/**
					 * @todo maybe do something
					 */
					$transaltionModel = FALSE;
					$translateByModelImport = TRUE;
				}

				/**
				 * translate by model import
				 */
				if ($translateByModelImport) {

					$transAllArray = Default_Model_Translator_Import::getSystemStandardTranslationArray();

					$langList = L8M_Locale::getSupported();

					if (is_array($langList)) {
						foreach ($transAllArray as $transArray) {
							if (isset($transArray['short'])) {
								foreach ($langList as $lang) {
									if (isset($transArray['text_' . $lang])) {
										$this->_translate[$lang][$transArray['short']] = $transArray['text_' . $lang];
									}
								}
							}
						}
					}

					if (isset($this->_translate[$locale]) &&
						isset($this->_translate[$locale][$messageId])) {

						return $this->_translate[$locale][$messageId];
					} else {
						return $messageId;
					}
				}
			}

			if ($transaltionModel &&
				isset($transaltionModel->Translation[$locale]) &&
				$transaltionModel->Translation[$locale]->text != NULL) {

				$this->_setToCache($messageId, $locale, $transaltionModel->Translation[$locale]->text);
				return $transaltionModel->Translation[$locale]->text;
			}

			if ($transaltionModelTranslateMessage) {
				return $transaltionModelTranslateMessage;
			}

			if ($transaltionModel) {

				/**
				 * messageId seems to be registered, but locale is missing, so add to database
				 */
				$values = array(
					'untranslated' => TRUE,
				);

				$values['Translation'][$locale]['text'] = $this->_getMessageWithLocale($messageId, $defaultLocale, $locale);
				$this->_translate[$locale][$messageId] = $this->_getMessageWithLocale($messageId, $defaultLocale, $locale);

				if (L8M_Doctrine::isEnabled() == TRUE &&
					class_exists('Default_Model_Translator', TRUE)) {

					try {
						/**
						 * let's execute query
						 * @var Doctrine_Query
						 */
						$transaltionModel
							->merge($values)
						;
						$transaltionModel->save();
					} catch (Doctrine_Connection_Exception $exception) {
						/**
						 * @todo maybe do something
						 */
					}
				}
				$this->_setToCache($messageId, $locale, $this->_translate[$locale][$messageId]);
				return $this->_translate[$locale][$messageId];
			} else {

				/**
				 * messageId seems not to be registered try searching in locale
				 */
				$transaltionTestModel = Doctrine_Query::create()
					->from('Default_Model_Translator m')
					->leftJoin('m.Translation mt')
					->addWhere('mt.lang = ? ', array($locale))
					->addWhere('mt.text = ? ', array($messageId))
					->limit(1)
					->execute()
					->getFirst()
				;
				if ($transaltionTestModel) {
					$this->_translate[$locale][$messageId] = $messageId;
					return $messageId;
				}

				/**
				 * messageId seems not to be registered yet so add to database
				 */

				$resource = new L8M_Acl_CalledFor;
				$url = $resource->resource();

				$values = array(
					'short'=>mb_substr($messageId, 0, 256),
					'untranslated'=>TRUE,
					'url'=>$url,
				);

				$langList = L8M_Locale::getSupported();

				if ($langList) {
					foreach ($langList as $lang) {
						$values['Translation'][$lang]['text'] = $this->_getMessageWithLocale($messageId, $defaultLocale, $lang);
						$this->_translate[$lang][$messageId] = $this->_getMessageWithLocale($messageId, $defaultLocale, $lang);
					}
				} else {
					$values['Translation'][$locale]['text'] = $this->_getMessageWithLocale($messageId, $defaultLocale, $locale);
					$this->_translate[$locale][$messageId] = $this->_getMessageWithLocale($messageId, $defaultLocale, $locale);
				}

				if (!isset($this->_translate[$locale][$messageId])) {
					$this->_translate[$locale][$messageId] = $this->_getMessageWithLocale($messageId, $defaultLocale, $lang);
				}

				if (L8M_Doctrine::isEnabled() == TRUE &&
					class_exists('Default_Model_Translator', TRUE)) {

					try {
						/**
						 * let's execute query
						 * @var Doctrine_Query
						 */
						$translation = new Default_Model_Translator();
						$translation
							->merge($values)
						;
						$translation->save();
					} catch (Doctrine_Connection_Exception $exception) {
						/**
						 * @todo maybe do something
						 */
					}
				}
				$this->_setToCache($messageId, $locale, $this->_translate[$locale][$messageId]);
				return $this->_getMessageWithLocale($messageId, $defaultLocale, $locale);
			}
		}

		$rule = Zend_Translate_Plural::getPlural($number, $plocale);
		if (!isset($plural[$rule])) {
			$rule = 0;
		}

		return $plural[$rule];
	}

	/**
	 * Creates a string with message and locale in front of it.
	 *
	 * @param $messageId
	 * @param $locale
	 * @param $options
	 */
	private function _getMessageWithLocale($messageId = NULL, $defaultLocale = NULL, $locale = NULL, $options = array()) {

		if ($defaultLocale === NULL) {
			if (L8M_Config::getOption('locale.defaultSystem')) {
				$defaultLocale = L8M_Config::getOption('locale.defaultSystem');
			} else
			if (is_array($options) &&
				array_key_exists('defaultLocale', $options) &&
				$options['defaultLocale'] !== NULL) {

				$defaultLocale = $options['defaultLocale'];
			} else
			if(is_array($this->_options) &&
				array_key_exists('defaultLocale', $this->_options)) {

				if ($this->_options['defaultLocale'] == NULL) {
					$this->_options['defaultLocale'] = L8M_Locale::getDefault();
				}
				$defaultLocale = $this->_options['defaultLocale'];
			}
		}

		if ($locale === NULL) {
			$locale = L8M_Locale::getLang();
		}

		if ($defaultLocale) {
			if ($locale == $defaultLocale) {
				$dataMessage = $messageId;
			} else {
				$googleTranslatedMessage = $this->_translateWithGoogle($messageId, $locale, $defaultLocale);
				if ($googleTranslatedMessage) {
					$dataMessage = $googleTranslatedMessage;
				} else {
					$dataMessage = $locale . '[' . $messageId . ']';
				}
			}
		} else {
			$dataMessage = $messageId;
		}
		return $dataMessage;
	}

	/**
	 * Translate Message with Google
	 *
	 * @param $message
	 * @param $locale
	 * @param $defaultLocale
	 */
	private function _translateWithGoogle($message, $locale, $defaultLocale) {
		$returnValue = NULL;
		if (L8M_Config::getOption('google.apis.translation.enabled')) {
			$client = new Zend_Http_Client('https://www.googleapis.com/language/translate/v2', array(
				'maxredirects' => 0,
				'timeout'      => 30,
			));

			$client->setParameterGet(array(
				'key'=>L8M_Config::getOption('google.apis.translation.key'),
				'source'=>$defaultLocale,
				'target'=>$locale,
				'q'=>$message,
			));

			$response = $client->request();

			if ($response->isSuccessful() &&
				$response->getStatus() == 200) {

				$data = $response->getBody();

				$serverResult = json_decode($data);

//				$status = $serverResult->responseStatus; // should be 200
//				$details = $serverResult->responseDetails;

				$returnValue = $serverResult->responseData->translatedText;
			}
		}
		return $returnValue;
	}


	/**
	 * returns the adapters name
	 *
	 * @return string
	 */
	public function toString()
	{
		return get_class($this);
	}

	/**
	 * Returns the available languages from this adapter
	 *
	 * @return array|null
	 */
	public function getList()
	{
		return L8M_Locale::getSupported();
	}

	/**
	 * Is the wished language available ?
	 *
	 * @see	Zend_Locale
	 * @param  string|Zend_Locale $locale Language to search for, identical with locale identifier,
	 * @see Zend_Locale for more information
	 * @return boolean
	 */
	public function isAvailable($locale)
	{
		$return = L8M_Locale::isAvailable($locale);
		return $return;
	}

	/**
	 * Retrieve translation from cache
	 *
	 * @param string $messageID
	 * @param string $locale
	 */
	protected function _getFromCache($messageID = NULL, $locale = NULL)
	{
		$returnValue = FALSE;

		if ($messageID &&
			$locale &&
			$cache = L8M_Cache::getCache('L8M_Translate_Adapter_Doctrine')) {

			$cacheValue = $cache->load(self::getCacheName($messageID));
			if (is_array($cacheValue) &&
				isset($cacheValue[$locale])) {

				$returnValue = $cacheValue[$locale];
			}
		}

		return $returnValue;
	}

	/**
	 * Set translation to cache
	 *
	 * @param string $messageID
	 * @param string $locale
	 * @param string $translatedMessage
	 */
	protected function _setToCache($messageID = NULL, $locale = NULL, $translatedMessage)
	{
		if ($messageID &&
			$locale &&
			$cache = L8M_Cache::getCache('L8M_Translate_Adapter_Doctrine')) {


			$cacheValue = $cache->load(strlen($messageID) . '_' . md5($messageID));
			if (is_array($cacheValue)) {
				$cacheValue[$locale] = $translatedMessage;
			} else {
				$cacheValue = array();
				$cacheValue[$locale] = $translatedMessage;
			}

			$cache->save($cacheValue, self::getCacheName($messageID));
		}
	}


	/**
	 * Creates an cach short
	 *
	 * @param string $short
	 * @return string
	 */
	public static function getCacheName($short = NULL)
	{
		$returnValue = NULL;

		if ($short) {
			$returnValue = strlen($short) . '_' . md5($short);
		}

		return $returnValue;
	}
}
