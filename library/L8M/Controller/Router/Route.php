<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Router/Route.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Route.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 *
 *
 * L8M_Controller_Router_Route
 *
 *
 */
class L8M_Controller_Router_Route extends Zend_Controller_Router_Route_Abstract
{
	/**
	 * Default translator
	 *
	 * @var Zend_Translate
	 */
	protected static $_defaultTranslator;

	/**
	 * Store generated urls.
	 *
	 * @var array
	 */
	protected static $_generatedLink = array();

	/**
	 * Translator
	 *
	 * @var Zend_Translate
	 */
	protected $_translator;

	/**
	 * Default locale
	 *
	 * @var mixed
	 */
	protected static $_defaultLocale;

	/**
	 * Locale
	 *
	 * @var mixed
	 */
	protected $_locale;

	/**
	 * Wether this is a translated route or not
	 *
	 * @var boolean
	 */
	protected $_isTranslated = FALSE;

	/**
	 * Translatable variables
	 *
	 * @var array
	 */
	protected $_translatable = array();

	protected $_urlVariable = ':';
	protected $_urlDelimiter = '/';
	protected $_regexDelimiter = '#';
	protected $_defaultRegex = null;

	/**
	 * Holds names of all route's pattern variable names. Array index holds a position in URL.
	 * @var array
	 */
	protected $_variables = array();

	/**
	 * Holds Route patterns for all URL parts. In case of a variable it stores it's regex
	 * requirement or null. In case of a static part, it holds only it's direct value.
	 * In case of a wildcard, it stores an asterisk (*)
	 * @var array
	 */
	protected $_parts = array();

	/**
	 * Holds user submitted default values for route's variables. Name and value pairs.
	 * @var array
	 */
	protected $_defaults = array();

	/**
	 * Holds user submitted regular expression patterns for route's variables' values.
	 * Name and value pairs.
	 * @var array
	 */
	protected $_requirements = array();

	/**
	 * Associative array filled on match() that holds matched path values
	 * for given variable names.
	 * @var array
	 */
	protected $_values = array();

	/**
	 * Associative array filled on match() that holds wildcard variable
	 * names and values.
	 * @var array
	 */
	protected $_wildcardData = array();

	/**
	 * Helper var that holds a count of route pattern's static parts
	 * for validation
	 * @var int
	 */
	protected $_staticCount = 0;

	public function getVersion() {
		return 1;
	}

	/**
	 * Instantiates route based on passed Zend_Config structure
	 *
	 * @param Zend_Config $config Configuration object
	 */
	public static function getInstance(Zend_Config $config)
	{
		$reqs = ($config->reqs instanceof Zend_Config) ? $config->reqs->toArray() : array();
		$defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();
		return new self($config->route, $defs, $reqs);
	}

	/**
	 * Prepares the route for mapping by splitting (exploding) it
	 * to a corresponding atomic parts. These parts are assigned
	 * a position which is later used for matching and preparing values.
	 *
	 * @param string $route Map used to match with later submitted URL path
	 * @param array $defaults Defaults for map variables with keys as variable names
	 * @param array $reqs Regular expression requirements for variables (keys as variable names)
	 * @param Zend_Translate $translator Translator to use for this instance
	 */
	public function __construct($route, $defaults = array(), $reqs = array(), Zend_Translate $translator = null, $locale = null)
	{
		$route               = trim($route, $this->_urlDelimiter);
		$this->_defaults     = (array) $defaults;
		$this->_requirements = (array) $reqs;
		$this->_translator   = $translator;
		$this->_locale       = $locale;

		if ($route !== '') {
			foreach (explode($this->_urlDelimiter, $route) as $pos => $part) {
				if (substr($part, 0, 1) == $this->_urlVariable && substr($part, 1, 1) != $this->_urlVariable) {
					$name = substr($part, 1);

					if (substr($name, 0, 1) === '@' && substr($name, 1, 1) !== '@') {
						$name                  = substr($name, 1);
						$this->_translatable[] = $name;
						$this->_isTranslated   = TRUE;
					}

					$this->_parts[$pos]  = (isset($reqs[$name]) ? $reqs[$name] : $this->_defaultRegex);
					$this->_variables[$pos] = $name;
				} else {
					if (substr($part, 0, 1) == $this->_urlVariable) {
						$part = substr($part, 1);
					}

					if (substr($part, 0, 1) === '@' && substr($part, 1, 1) !== '@') {
						$this->_isTranslated = TRUE;
					}

					$this->_parts[$pos] = $part;

					if ($part !== '*') {
						$this->_staticCount++;
					}
				}
			}
		}
	}

	/**
	 * Matches a user submitted path with parts defined by a map. Assigns and
	 * returns an array of variables on a successful match.
	 *
	 * @param string $path Path used to match against this routing map
	 * @return array|FALSE An array of assigned values or a FALSE on a mismatch
	 */
	public function match($path, $partial = FALSE)
	{
		if ($this->_isTranslated) {
			$translateMessages = $this->getTranslator()->getMessages();
		}

		$pathStaticCount = 0;
		$values       = array();
		$matchedPath  = '';

		if (!$partial) {
			$path = trim($path, $this->_urlDelimiter);
		}

		if ($path !== '') {
			$path = explode($this->_urlDelimiter, $path);

			foreach ($path as $pos => $pathPart) {
				// Path is longer than a route, it's not a match
				if (!array_key_exists($pos, $this->_parts)) {
					if ($partial) {
						break;
					} else {
						return FALSE;
					}
				}

				$matchedPath .= $pathPart . $this->_urlDelimiter;

				// If it's a wildcard, get the rest of URL as wildcard data and stop matching
				if ($this->_parts[$pos] == '*') {
					$count = count($path);
					for($i = $pos; $i < $count; $i+=2) {
						$var = urldecode($path[$i]);

						if (!isset($this->_wildcardData[$var]) &&
							!isset($this->_defaults[$var]) &&
							!isset($values[$var])) {

							$this->_wildcardData[$var] = (isset($path[$i+1])) ? urldecode($path[$i+1]) : null;
						}
					}

					$matchedPath = implode($this->_urlDelimiter, $path);
					break;
				}

				$name     = isset($this->_variables[$pos]) ? $this->_variables[$pos] : null;
				$pathPart = urldecode($pathPart);

				// Translate value if required
				$part = $this->_parts[$pos];
				if ($this->_isTranslated &&
					(substr($part, 0, 1) === '@' && substr($part, 1, 1) !== '@' && $name === null) ||
					$name !== null &&
					in_array($name, $this->_translatable)) {

					if (substr($part, 0, 1) === '@') {
						$part = substr($part, 1);
					}

					if (($originalPathPart = array_search($pathPart, $translateMessages)) !== FALSE) {
						$pathPart = $originalPathPart;
					}
				}

				if (substr($part, 0, 2) === '@@') {
					$part = substr($part, 1);
				}

				// If it's a static part, match directly
				if ($name === null && $part != $pathPart) {
					return FALSE;
				}

				// If it's a variable with requirement, match a regex. If not - everything matches
				if ($part !== null &&
					!preg_match($this->_regexDelimiter . '^' . $part . '$' . $this->_regexDelimiter . 'iu', $pathPart)) {

					return FALSE;
				}

				// If it's a variable store it's value for later
				if ($name !== null) {
					$values[$name] = $pathPart;
				} else {
					$pathStaticCount++;
				}
			}
		}

		// Check if all static mappings have been matched
		if ($this->_staticCount != $pathStaticCount) {
			return FALSE;
		}

		$return = $values + $this->_wildcardData + $this->_defaults;

		// Check if all map variables have been initialized
		foreach ($this->_variables as $var) {
			if (!array_key_exists($var, $return)) {
				return FALSE;
			} else
			if ($return[$var] == '' || $return[$var] === null) {
				// Empty variable? Replace with the default value.
				$return[$var] = $this->_defaults[$var];
			}
		}

		$this->setMatchedPath(rtrim($matchedPath, $this->_urlDelimiter));

		$this->_values = $values;

		return $return;

	}

	/**
	 * Assembles user submitted parameters forming a URL path defined by this route
	 *
	 * @param  array $data An array of variable and value pairs used as parameters
	 * @param  boolean $reset Whether or not to set route defaults with those provided in $data
	 * @param  bool $encode Tells to encode URL parts on output
	 * @param  bool $partial
	 * @param  bool $prefetchLink Tells to enable prefetching url in L8M_View_Helper_HeadLinkPrefetch
	 * @return string Route path with user submitted parameters
	 */
	public function assemble($data = array(), $reset = FALSE, $encode = FALSE, $partial = FALSE, $prefetchLink = FALSE)
	{
		/**
		 * default value
		 */
		$returnValue = '';

		/**
		 * options?
		 */
		if ($this->_isTranslated) {
			$translator = $this->getTranslator();

			if (isset($data['@locale'])) {
				$locale = $data['@locale'];
				unset($data['@locale']);
			} else {
				$locale = $this->getLocale();
			}
		}

		$url  = array();
		$flag = FALSE;

		$localeSupportedByModule = NULL;
		if (isset($data['module'])) {
			$localeSupportedByModule = $data['module'];
		}

		/**
		 * force variables and parts to be like in the original route for all components
		 * "lang" and "module" are options
		 */
		$this->_variables = array(
			'lang',
			'module',
			'controller',
			'action',
		);
		$this->_parts = array(
			'^(' . implode('|', L8M_Locale::getSupportedForLinks($localeSupportedByModule)) . ')$',
			NULL,
			NULL,
			NULL,
			'*',
		);

		/**
		 * prevent multiple link-generating
		 */
		$goOn = TRUE;

		$linkHash = NULL;
		if ($reset) {
			$linkSerialized = serialize($data);
			$linkLength = strlen($linkSerialized);
			$linkHash = $linkLength . '_' . md5($linkLength . '_' . $linkSerialized);

			if (array_key_exists($linkHash, self::$_generatedLink)) {
				$returnValue = self::$_generatedLink[$linkHash];
				$goOn = FALSE;
			}
		}

		if ($goOn) {

			/**
			 * build parts into url-array
			 */
			foreach ($this->_parts as $key => $part) {
				if (isset($this->_variables[$key])) {
					$name = $this->_variables[$key];
				} else {
					$name = NULL;
				}

				$useDefault = FALSE;
				if (isset($name) &&
					array_key_exists($name, $data) &&
					$data[$name] === null) {

					$useDefault = TRUE;
				}

				if (isset($name)) {
					if (isset($data[$name]) &&
						!$useDefault) {

						$value = $data[$name];
						unset($data[$name]);
					} else
					if (!$reset &&
						!$useDefault &&
						isset($this->_values[$name])) {

						$value = $this->_values[$name];
					} else
					if (!$reset &&
						!$useDefault &&
						isset($this->_wildcardData[$name])) {

						$value = $this->_wildcardData[$name];
					} else
					if (isset($this->_defaults[$name])) {
						$value = $this->_defaults[$name];
					} else {
						require_once 'Zend' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Router' . DIRECTORY_SEPARATOR . 'Exception.php';
						throw new Zend_Controller_Router_Exception($name . ' is not specified');
					}

					if ($this->_isTranslated &&
						in_array($name, $this->_translatable)) {

						$url[$key] = $translator->translate($value, $locale);
					} else {
						$url[$key] = $value;
					}
				} else
				if ($part != '*') {
					if ($this->_isTranslated &&
						substr($part, 0, 1) === '@') {

						if (substr($part, 1, 1) !== '@') {
							$url[$key] = $translator->translate(substr($part, 1), $locale);
						} else {
							$url[$key] = substr($part, 1);
						}
					} else {
						if (substr($part, 0, 2) === '@@') {
							$part = substr($part, 1);
						}

						$url[$key] = $part;
					}
				} else {
					if (!$reset) {
						$data += $this->_wildcardData;
					}
					$defaults = $this->getDefaults();
					foreach ($data as $var => $value) {
						if ($value !== null &&
							(!isset($defaults[$var]) || $value != $defaults[$var])) {

							/**
							 * check
							 */
							if (class_exists('Default_Model_ParamTranslator', TRUE)) {
								try {
									$paramTranslatorSqlCollection = L8M_Translate_Param::getUparamByParamWithLang($var, $url[0]);

									if ($paramTranslatorSqlCollection->count() == 1) {
										$var = $paramTranslatorSqlCollection->getFirst()->uparam;
									} else {

										/**
										 * do we have a reverse translation
										 */
										$paramTranslatorSqlCollection = L8M_Translate_Param::getIdByUparam($var);

										if ($paramTranslatorSqlCollection->count() == 1) {
											$transParamTranslatorSqlCollection = L8M_Translate_Param::getUparamByIdWithLang($paramTranslatorSqlCollection->getFirst()->id, $url[0]);

											if ($transParamTranslatorSqlCollection->count() == 1) {
												$var = $transParamTranslatorSqlCollection->getFirst()->uparam;
											}
										}
									}
								} catch (Doctrine_Connection_Exception $exception) {
									/**
									 * @todo maybe do something
									 */
								}
							}
							$url[$key++] = $var;
							$url[$key++] = $value;
							$flag = TRUE;
						}
					}
				}
			}

			/**
			 * create resource
			 */
			$resource = L8M_Acl_Resource::getResourceName($url[1], $url[2], $url[3]);

			/**
			 * prepare url-array concerning l8m-routing
			 */

			/**
			 * check param to action class
			 */
			$removeActionAndVarKey = FALSE;
			if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Action' . DIRECTORY_SEPARATOR . 'Param.php') &&
				class_exists('PRJ_Controller_Action_Param')) {

				$actionParam = new PRJ_Controller_Action_Param();
				$paramAction = $url[3];
				$paramController = $url[2];
				$paramModule = $url[1];
				if ($actionParam instanceof L8M_Controller_Action_Param &&
					$actionParam->checkControllerForUrlCreator($paramAction, $paramController, $paramModule, L8M_Locale::getDefault())) {

					$removeActionAndVarKey = TRUE;
				}
			}

			/**
			 * check for controller action var param
			 */
			$removeControllerActionVarParam = FALSE;
			if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Action' . DIRECTORY_SEPARATOR . 'Var.php') &&
				class_exists('PRJ_Controller_Action_Var')) {

				$paramVar = new PRJ_Controller_Action_Var();
				if ($paramVar instanceof L8M_Controller_Action_Var &&
					$paramVar->checkControllerByRealResource($resource)) {

					$removeControllerActionVarParam = TRUE;
//					$removeActionAndVarKey = FALSE;
				}
			}

			/**
			 * check ResourceTranslator
			 */
			if (class_exists('Default_Model_ResourceTranslator', TRUE)) {
				try {
					/**
					 * do we have a translation for possible original resource
					 */
					$resourceTranslatorSqlCollection = L8M_Translate_Resource::getUresourceByResourceWithLang($resource, $url[0]);
					if ($resourceTranslatorSqlCollection->count() == 1) {
						$tmpResource = $resourceTranslatorSqlCollection->getFirst()->uresource;
						$tmpResourceArray = explode('.', $tmpResource);

						if ($tmpResource != $resource &&
							count($tmpResourceArray) == 3) {

							if ($tmpResourceArray[0] != '%') {
								$url[1] = $tmpResourceArray[0];
								if ($tmpResourceArray[1] != '%') {
									$url[2] = $tmpResourceArray[1];
									if ($tmpResourceArray[2] != '%') {
										$url[3] = $tmpResourceArray[2];
									}
								}
							}
						}
					} else {

						/**
						 * do we have a reverse translation
						 */
						$resourceTranslatorSqlCollection = L8M_Translate_Resource::getIdByUresource($resource);
						if ($resourceTranslatorSqlCollection->count() == 1) {
							$transResourceTranslatorSqlCollection = L8M_Translate_Resource::getUresourceByIdWithLang($resourceTranslatorSqlCollection->getFirst()->id, $url[0]);
							if ($transResourceTranslatorSqlCollection->count() == 1) {
								$tmpResource = $transResourceTranslatorSqlCollection->getFirst()->uresource;
								$tmpResourceArray = explode('.', $tmpResource);

								if ($tmpResource != $resource &&
									count($tmpResourceArray) == 3) {

									if ($tmpResourceArray[0] != '%') {
										$url[1] = $tmpResourceArray[0];
										if ($tmpResourceArray[1] != '%') {
											$url[2] = $tmpResourceArray[1];
											if ($tmpResourceArray[2] != '%') {
												$url[3] = $tmpResourceArray[2];
											}
										}
									}
								}
							}
						}
					}
				} catch (Doctrine_Connection_Exception $exception) {
					/**
					 * @todo maybe do something
					 */
				}
			}

			/**
			 * is key 0 (lang) one of language-short standards remove it
			 */
			if ($url[0] == L8M_Locale::getTldDefaultLang() ){
				unset($url[0]);
			} else
			if (!L8M_Locale::getTldDefaultLang() &&
				$url[0] == L8M_Locale::getLang() &&
				$url[0] == L8M_Locale::getDefault()) {

				unset($url[0]);
			}

			/**
			 * is key 1 (module) the default menu remove it
			 */
			if ($url[1] == 'default') {
				unset($url[1]);
			}

			/**
			 * remove useless param and action-name
			 */
			if ($removeActionAndVarKey) {
				unset($url[3]);
				if (isset($url[4])) {
					unset($url[4]);
				}

				/**
				 * that case should never happen
				 */
				if ($removeControllerActionVarParam &&
					isset($url[6])) {

					unset($url[6]);
				}
			} else
			if ($removeControllerActionVarParam &&
				isset($url[4])) {

				unset($url[4]);
			}

			/**
			 * create link
			 */
			foreach (array_reverse($url, TRUE) as $key => $value) {
				$defaultValue = null;

				if (isset($this->_variables[$key])) {
					$defaultValue = $this->getDefault($this->_variables[$key]);

					if ($this->_isTranslated && $defaultValue !== null &&
						isset($this->_translatable[$this->_variables[$key]])) {

						$defaultValue = $translator->translate($defaultValue, $locale);
					}
				}

				if ($flag ||
					$value !== $defaultValue ||
					$partial) {

					if ($encode) {
						$value = urlencode($value);
					}
					$returnValue = $this->_urlDelimiter . $value . $returnValue;
					$flag = TRUE;
				}
			}

			$returnValue = trim($returnValue, $this->_urlDelimiter);

			/**
			 * prefetch link
			 */
			if ($prefetchLink) {
				L8M_View_Helper_HeadLinkPrefetch::addUrl($this->_urlDelimiter . $returnValue);
			}

			/**
			 * prevent multiple generating of link
			 */
			if ($linkHash) {
				self::$_generatedLink[$linkHash] = $returnValue;
			}
		}

		/**
		 * return url
		 */
		return $returnValue;

	}

	/**
	 * Return a single parameter of route's defaults
	 *
	 * @param string $name Array key of the parameter
	 * @return string Previously set default
	 */
	public function getDefault($name) {
		if (isset($this->_defaults[$name])) {
			return $this->_defaults[$name];
		}
		return null;
	}

	/**
	 * Return an array of defaults
	 *
	 * @return array Route defaults
	 */
	public function getDefaults() {
		return $this->_defaults;
	}

	/**
	 * Get all variables which are used by the route
	 *
	 * @return array
	 */
	public function getVariables()
	{
		return $this->_variables;
	}

	/**
	 * Set a default translator
	 *
	 * @param  Zend_Translate $translator
	 * @return void
	 */
	public static function setDefaultTranslator(Zend_Translate $translator = null)
	{
		self::$_defaultTranslator = $translator;
	}

	/**
	 * Get the default translator
	 *
	 * @return Zend_Translate
	 */
	public static function getDefaultTranslator()
	{
		return self::$_defaultTranslator;
	}

	/**
	 * Set a translator
	 *
	 * @param  Zend_Translate $translator
	 * @return void
	 */
	public function setTranslator(Zend_Translate $translator)
	{
		$this->_translator = $translator;
	}

	/**
	 * Get the translator
	 *
	 * @throws Zend_Controller_Router_Exception When no translator can be found
	 * @return Zend_Translate
	 */
	public function getTranslator()
	{
		if ($this->_translator !== null) {
			return $this->_translator;
		} else if (($translator = self::getDefaultTranslator()) !== null) {
			return $translator;
		} else {
			try {
				$translator = Zend_Registry::get('Zend_Translate');
			} catch (Zend_Exception $e) {
				$translator = null;
			}

			if ($translator instanceof Zend_Translate) {
				return $translator;
			}
		}

		require_once 'Zend' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Router' . DIRECTORY_SEPARATOR . 'Exception.php';
		throw new Zend_Controller_Router_Exception('Could not find a translator');
	}

	/**
	 * Set a default locale
	 *
	 * @param  mixed $locale
	 * @return void
	 */
	public static function setDefaultLocale($locale = null)
	{
		self::$_defaultLocale = $locale;
	}

	/**
	 * Get the default locale
	 *
	 * @return mixed
	 */
	public static function getDefaultLocale()
	{
		return self::$_defaultLocale;
	}

	/**
	 * Set a locale
	 *
	 * @param  mixed $locale
	 * @return void
	 */
	public function setLocale($locale)
	{
		$this->_locale = $locale;
	}

	/**
	 * Get the locale
	 *
	 * @return mixed
	 */
	public function getLocale()
	{
		if ($this->_locale !== null) {
			return $this->_locale;
		} else if (($locale = self::getDefaultLocale()) !== null) {
			return $locale;
		} else {
			try {
				$locale = Zend_Registry::get('Zend_Locale');
			} catch (Zend_Exception $e) {
				$locale = null;
			}

			if ($locale !== null) {
				return $locale;
			}
		}

		return null;
	}
}
