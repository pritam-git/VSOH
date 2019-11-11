<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/AuthControlled/ContentInjector.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ContentInjector.php 519 2016-10-25 08:29:12Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_AuthControlled_ContentInjector
 *
 *
 */
class L8M_Controller_Plugin_AuthControlled_ContentInjector extends L8M_Controller_Plugin_AuthControlled
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A string representing the current language ISO2 code.
	 *
	 * @var string
	 */
	protected static $_language = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Called after an action is dispatched by Zend_Controller_Dispatcher.
	 *
	 * We need to make sure that this plugin is registered last before the
	 * Zend_Controller_Plugin_Layout, so that this method gets called before
	 * Zend_Controller_Plugin_Layout::postDispatch().
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request)
	{

		/**
		 * return early on redirect
		 */
		if (!$request->isDispatched() ||
			$this->getResponse()->isRedirect() ||
			L8M_Doctrine::isDisabled()) {
			return;
		}

		/**
		 * retrieve layout
		 */
		$layout = Zend_Layout::getMvcInstance();

		/**
		 * layout enabled?
		 */
		if (!($layout instanceof Zend_Layout) ||
			!$layout->isEnabled()) {
			return;
		}

		/**
		 * build navigation
		 */
		if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'Navigation.php') &&
			class_exists('PRJ_Navigation')) {

			Zend_Registry::set('Zend_Navigation', new PRJ_Navigation());
		} else {
			Zend_Registry::set('Zend_Navigation', new L8M_Navigation());
		}

		/**
		 * resource
		 */
		$resource = L8M_Acl_Resource::getResourceName(
			$request->getModuleName(),
			$request->getControllerName(),
			$request->getActionName()
		);
		$resource = $request->getParam('formerResource', $resource);

		/**
		 * languages
		 */
		$language = $this->_getLanguage();

		if (class_exists('Default_Model_Action', TRUE)) {
			try {
				/**
				 * let's execute query
				 * @var Doctrine_Query
				 */

				/**
				 * content
				 */
				$content = $this->getResponse()->getBody(TRUE);

				if (isset($content['default'])) {
					$content = $content['default'];

					$actionModel = FALSE;
					$cache = L8M_Cache::getCache('Default_Model_Action');

					if ($cache) {
						$actionModel = $cache->load(L8M_Cache::getCacheId('resource', $resource));
					}

					if ($actionModel === FALSE) {

						/**
						 * retrieve action
						 */
						$actionModel = Doctrine_Query::create()
							->from('Default_Model_Action a')
							->addWhere('a.resource = ? ', array($resource))
							->execute()
							->getFirst()
						;

						if ($cache) {
							$cache->save($actionModel, L8M_Cache::getCacheId('resource', $resource));
						}
					}

					if ($actionModel) {

						/**
						 * prevent Warnings add vars
						 */
						$contentTitle = '';
						$contentHeadline = '';
						$contentSubheadline = '';
						$contentKeywords = '';
						$contentDescription = '';
						$contentRobots = 'index, follow';

						if ($actionModel->getTable()->hasColumn('canonical_lang') &&
							$actionModel->canonical_lang &&
							in_array($actionModel->canonical_lang, L8M_Locale::getSupported()) &&
							$actionModel->canonical_lang != L8M_Locale::getLang()) {

							$layout->getView()->layout()->canonical = L8M_Library::getSchemeAndHttpHost() . $layout->getView()->url(array('module'=>$actionModel->Controller->Module->name, 'controller'=>$actionModel->Controller->name, 'action'=>$actionModel->name, 'lang'=>$actionModel->canonical_lang), NULL, TRUE);
						}

						/**
						 * maybe there is no translation
						 */
						if (isset($actionModel->Translation[$language])) {

							/**
							 * append content from database to aggregated content
							 */
							$content = $actionModel->Translation[$language]['content']
									 . $content
							;

							/**
							 * if header and subheader were retrieved from database,
							 * inject them into layout
							 */
							$contentTitle = $actionModel->Translation[$language]['title'];
							if ($contentTitle) {
								$layout->getView()->layout()->title = $contentTitle;
							}
							$contentHeadline = $actionModel->Translation[$language]['headline'];
							if ($contentHeadline) {
								$layout->getView()->layout()->headline = $contentHeadline;
							}
							$contentSubheadline = $actionModel->Translation[$language]['subheadline'];
							if ($contentSubheadline) {
								$layout->getView()->layout()->subheadline = $contentSubheadline;
							}
							$contentKeywords = $actionModel->Translation[$language]['keywords'];
							if ($contentKeywords) {
								if ($layout->getView()->layout()->keywords == '') {
									$layout->getView()->layout()->keywords = $contentKeywords;
								}
							}
							$contentDescription = $actionModel->Translation[$language]['description'];
							if ($contentDescription) {
								if ($layout->getView()->layout()->description == '') {
									$layout->getView()->layout()->description = $contentDescription;
								}
							}
						}

						/**
						 * load meta tags from meta configuration
						 */
						if (class_exists('Default_Model_MetaConfiguration', TRUE)) {
							try {
								$metaCollection = Doctrine_Query::create()
									->from('Default_Model_MetaConfiguration')
									->execute()
								;

								foreach ($metaCollection as $metaModel) {
									$tmpVar = 'meta_' . $metaModel->short;
									if (!isset($layout->getView()->layout()->$tmpVar)) {
										$layout->getView()->layout()->$tmpVar = $metaModel->value;
									}
								}

							} catch (Doctrine_Connection_Exception $exception) {
								/**
								 * @todo maybe do something
								 */
							}
						}

						/**
						 * set meta-robots definition
						 */
						$columnDefinitions = $actionModel->getTable()->getColumns();
						if (array_key_exists('robots', $columnDefinitions) &&
							$actionModel->robots) {

							$contentRobots = $actionModel->robots;
						}
						$layout->getView()->layout()->robots = $contentRobots;

						/**
						 * if a layout script has been set, assign it to layout
						 * instance
						 */
						$layoutScript = $actionModel->layout;
						if ($layoutScript) {
							$layout->setLayout($layoutScript);
						}

						if (!$layout->getView()->layout()->isContentFromCache) {
							/**
							 * if a content partial has been set, render content with
							 * content partial
							 */
							$layout->getView()->layout()->usedContentPartial = NULL;
							$partialScript = $actionModel->content_partial;
							if ($partialScript) {

								if ($partialScript != '') {
									if (!preg_match('/\.phtml$/', $partialScript)) {
										$partialScript .= '.phtml';
									}

									$module = $request->getModuleName();

									$model = array(
										'content'=>$content,
										'title'=>$contentTitle,
										'headline'=>$contentHeadline,
										'subheadline'=>$contentSubheadline,
									);

									$session = new Zend_Session_Namespace('L8M_Controller_Plugin_Mobile_Detector');
									if (isset($session->isMobileDevice) &&
										$session->isMobileDevice == TRUE) {

										if ($module == 'default') {
											$partialScriptFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . str_replace('.phtml', '-mobile.phtml', $partialScript);
										} else {
											$partialScriptFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . str_replace('.phtml', '-mobile.phtml', $partialScript);
										}
										if (file_exists($partialScriptFile)) {
											$partialScript = str_replace('.phtml', '-mobile.phtml', $partialScript);
										}
									}

									/**
									 * passthrough layout - used content partial (maybe useful for view render-scripts to know)
									 */
									$layout->getView()->layout()->usedContentPartial = $actionModel->content_partial;

									/**
									 * render content partial
									 */
									$content = $layout->getView()->partial(
										$partialScript,
										$module,
										$model
									);
								}
							}
						}

						if (preg_match_all('|<div class="sysviewhelper l8m-object"[^>]+>###:(.*?):###</div>|is', $content, $match)) {
							$matchingPatterns = $match[0];
							$matchingStrings = $match[1];

							for ($i = 0; $i < count($matchingStrings); $i++) {
								$outputClassName = $matchingStrings[$i];
								if (substr($outputClassName, 0, strlen('PRJ_View_Helper_TinyMCE_Tmce')) == 'PRJ_View_Helper_TinyMCE_Tmce') {

									/**
									 * replacement pattern
									 */
									$replacePattern = $matchingPatterns[$i];

									/**
									 * clear data-rel
									 */
									if (preg_match('| data-rel="(.*?)"|is', $replacePattern, $partMatch)) {
										$replacePattern = str_replace($partMatch[0], '', $replacePattern);
									}

									/**
									 * clear style
									 */
									if (preg_match('| data-ignoredimension="(.*?)"|is', $replacePattern, $partMatch)) {
										$replacePattern = str_replace($partMatch[0], '', $replacePattern);
										if ($partMatch[1] == 'true') {
											if (preg_match('| style="(.*?)"|is', $replacePattern, $partMatch)) {
												$completeStyle = $partMatch[0];
												$newCompleteStyle = $completeStyle;
												$onlyStyle = $partMatch[1];
												if (preg_match('|height:(.*?);|is', $onlyStyle, $partMatch)) {
													$newCompleteStyle = str_replace($partMatch[0], '', $newCompleteStyle);
												}
												if (preg_match('|width:(.*?);|is', $onlyStyle, $partMatch)) {
													$newCompleteStyle = str_replace($partMatch[0], '', $newCompleteStyle);
												}
												$newCompleteStyle = str_replace('" "', '""', $newCompleteStyle);
												if ($newCompleteStyle == ' style=""') {
													$newCompleteStyle = '';
												}
												$replacePattern = str_replace($completeStyle, $newCompleteStyle, $replacePattern);
											}
										}
									}

									/**
									 * parmeters
									 */
									$parameter = NULL;
									if (preg_match('| data-parameters="(.*?)"|is', $replacePattern, $partMatch)) {
										$parameter = $partMatch[1];
										$replacePattern = str_replace($partMatch[0], '', $replacePattern);
									}

									/**
									 * remove sys view helper container
									 */
									$removeSysViewHelperContainer = FALSE;
									if (preg_match('| data-removesysview="(.*?)"|is', $replacePattern, $partMatch)) {
										$replacePattern = str_replace($partMatch[0], '', $replacePattern);
										if ($partMatch[1] == 'true') {
											$removeSysViewHelperContainer = TRUE;
										}
									}

									/**
									 * function view-helper
									 */
									$tmp = explode('_', $outputClassName);
									$outputFunction = L8M_Library::lcFirst($tmp[count($tmp) - 1]);
									$output = $layout->getView()->$outputFunction($parameter);

									/**
									 * replace content with content
									 */
									if ($removeSysViewHelperContainer) {
										$replaceWith = $output;
									} else {
										$replaceWith = str_replace('###:' . $outputClassName . ':###', $output, $replacePattern);
									}
									$content = str_replace($matchingPatterns[$i], $replaceWith, $content);
								}
							}
						}

						/**
						 * doing the stuff intern - no debug needed anymore
						 */
						L8M_Controller_Plugin_AuthControlled_Application_Functions::postDispatch($request, $this->getResponse(), $content);
					}
				}
			} catch (Doctrine_Connection_Exception $exception) {
				/**
				 * @todo maybe do something
				 */
			}
		}
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

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

}