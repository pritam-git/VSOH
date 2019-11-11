<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/Sitemap.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Sitemap.php 399 2015-09-02 09:23:38Z nm $
 */

/**
 *
 *
 * PRJ_Sitemap
 *
 *
 */
class PRJ_Sitemap
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
	 * add the special models and controllers that are used in the project
	 *
	 *
	 * 'Default_Model_Services'=>array(
	 *		'indexUrlArray'=>array('module'=>'default', 'controller'=>'services', 'action'=>'index'),
	 * 		'detailUrlArray'=>array('module'=>'default', 'controller'=>'services', 'action'=>'detail'),
	 * 		'param'=>'short',
	 * 		'column'=>'ushort',
	 * ),
	 *
	 * @var Array
	 */
	private static $_myModel2Url = array(
//		'Default_Model_Team'=>array(
//			'indexUrlArray'=>array('module'=>'default', 'controller'=>'team', 'action'=>'index'),
//			'detailUrlArray'=>array('module'=>'default', 'controller'=>'team', 'action'=>'detail'),
//			'param'=>'name',
//			'column'=>'short',
//		),
	);

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 * writes an xml file according to search engine standards
	 *
	 * @return boolean
	 */
	public static function writeXML()
	{
		$returnValue = FALSE;

		if (L8M_Acl_CalledFor::resource() !== 'system.setup.process') {
			$sitemapFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'sitemap.xml';
			if (is_writable($sitemapFile)) {

				/**
				 * load the given xml file if it exists
				 */
				$xml = new DOMDocument('1.0', 'UTF-8');

				$node = $xml->createElement('urlset');
				$node->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
				$node->setAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');

				if ($xml) {

					foreach (self::getSitesArray() as $siteUrl) {

						/**
						 * for every supported url append an url tag
						 */
						foreach ($siteUrl as $lang=>$translatedUrl) {

							$url = $xml->createElement('url');

							/**
							 * appands to the url node a loc child
							 */
							$loc = $xml->createElement('loc', L8M_Library::getSchemeAndHttpHost() . $translatedUrl);
							$url->appendChild($loc);

							foreach ($siteUrl as $lang=>$translatedUrl) {

								/**
								 * add xhtml:link child
								 */
								$xhtmlLink = $xml->createElement('xhtml:link');
								$xhtmlLink->setAttribute('rel', 'alternate');
								$xhtmlLink->setAttribute('hrefLang', $lang);
								$xhtmlLink->setAttribute('href', L8M_Library::getSchemeAndHttpHost() . $translatedUrl);
								$url->appendChild($xhtmlLink);
							}
						$node->appendChild($url);
						}


					}
				}

				/**
				 * append all
				 */
				$xml->appendChild($node);
				$xml->formatOutput = TRUE;

				/**
				 * save xml in given xml file
				 */
				$returnValue = $xml->save($sitemapFile);
			}
		}

		return $returnValue;
	}

	/**
	 * Return all possible sites.
	 *
	 * $returnValue = array(
	 * 		array(
	 * 			'de'=>'/example-site',
	 * 			'en'=>'/en/example-site',
	 * 		),
	 * );
	 *
	 * @return array
	 */
	private static function getSitesArray()
	{
		$returnValue = array();

		/**
		 * retrieve the view
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		/**
		 * start
		 */
		$tmpLinksArray = array();
		foreach (L8M_Locale::getSupported() as $lang) {
			$tmpLinksArray[$lang] = $viewFromMVC->url(array('module'=>'default', 'controller'=>'index', 'action'=>'index', 'lang'=>$lang), NULL, TRUE);
		}
		$returnValue[] = $tmpLinksArray;

		if (L8M_Config::getOption('l8m.blog.enabled')) {
			$tmpLinksArray = array();
			foreach (L8M_Locale::getSupported() as $lang) {
				$tmpLinksArray[$lang] = $viewFromMVC->url(array('module'=>'default', 'controller'=>'blog', 'action'=>'index', 'lang'=>$lang), NULL, TRUE);
			}
			$returnValue[] = $tmpLinksArray;
			$returnValue = array_merge($returnValue, self::_addSpecialModelToUrl(array('module'=>'default', 'controller'=>'blog', 'action'=>'detail'), 'short', 'Default_Model_Blog', 'ushort'));
		}
		if (L8M_Config::getOption('l8m.faq.enabled')) {
			$tmpLinksArray = array();
			foreach (L8M_Locale::getSupported() as $lang) {
				$tmpLinksArray[$lang] = $viewFromMVC->url(array('module'=>'default', 'controller'=>'faq', 'action'=>'index', 'lang'=>$lang), NULL, TRUE);
			}
			$returnValue[] = $tmpLinksArray;
			$returnValue = array_merge($returnValue, self::_addSpecialModelToUrl(array('module'=>'default', 'controller'=>'faq', 'action'=>'detail'), 'short', 'Default_Model_Faq', 'ushort'));
		}
		if (L8M_Config::getOption('l8m.news.enabled')) {
			$tmpLinksArray = array();
			foreach (L8M_Locale::getSupported() as $lang) {
				$tmpLinksArray[$lang] = $viewFromMVC->url(array('module'=>'default', 'controller'=>'news', 'action'=>'index', 'lang'=>$lang), NULL, TRUE);
			}
			$returnValue[] = $tmpLinksArray;
			$returnValue = array_merge($returnValue, self::_addSpecialModelToUrl(array('module'=>'default', 'controller'=>'news', 'action'=>'detail'), 'short', 'Default_Model_News', 'ushort'));
		}
		if (L8M_Config::getOption('l8m.team.enabled')) {
			$tmpLinksArray = array();
			foreach (L8M_Locale::getSupported() as $lang) {
				$tmpLinksArray[$lang] = $viewFromMVC->url(array('module'=>'default', 'controller'=>'team', 'action'=>'index', 'lang'=>$lang), NULL, TRUE);
			}
			$returnValue[] = $tmpLinksArray;
			if (L8M_Config::getOption('l8m.team.detail.enabled')) {
				$returnValue = array_merge($returnValue, self::_addSpecialModelToUrl(array('module'=>'default', 'controller'=>'team', 'action'=>'detail'), 'name', 'Default_Model_Team', 'short'));
			}
		}
		if (L8M_Config::getOption('l8m.newsletter.enabled')) {
			$tmpLinksArray = array();
			foreach (L8M_Locale::getSupported() as $lang) {
				$tmpLinksArray[$lang] = $viewFromMVC->url(array('module'=>'default', 'controller'=>'newsletter', 'action'=>'subscribe', 'lang'=>$lang), NULL, TRUE);
				$tmpLinksArray[$lang] = $viewFromMVC->url(array('module'=>'default', 'controller'=>'newsletter', 'action'=>'unsubscribe', 'lang'=>$lang), NULL, TRUE);
			}
			$returnValue[] = $tmpLinksArray;
		}

		/**
		 * retrieve shop pages or pages for the shop
		 *
		 * cart
		 * article
		 * group
		 *
		 * shipping-costs
		 * terms-and-conditions
		 *
		 */
		if (!L8M_Config::getOption('shop.disabled')) {
			$tmpLinksArray = array();
			foreach (L8M_Locale::getSupported() as $lang) {
				$tmpLinksArray[$lang] = $viewFromMVC->url(array('module'=>'shop', 'controller'=>'cart', 'action'=>'index', 'lang'=>$lang), NULL, TRUE);
			}
			$returnValue[] = $tmpLinksArray;

			$returnValue = array_merge($returnValue, self::_addSpecialModelToUrl(array('module'=>'shop', 'controller'=>'article', 'action'=>'index'), 'short', 'Default_Model_Product', 'ushort'));
			$returnValue = array_merge($returnValue, self::_addSpecialModelToUrl(array('module'=>'shop', 'controller'=>'group', 'action'=>'index'), 'short', 'Default_Model_ProductGroup', 'ushort'));
		}


		/**
		 * retrieve action methods
		 */
		$returnValue = array_merge($returnValue, self::_actionMethodsToUrl());


		/**
		 * retrieve special cases
		 */
		$returnValue = array_merge($returnValue, self::_specialUrls());

		return $returnValue;
	}

	/**
	 * handle models with urls
	 *
	 * @param $urlArray
	 * @param $paramName
	 * @param $modelName
	 * @param $columName
	 * @return Array $returnValue
	 */
	private static function _addSpecialModelToUrl($urlArray, $paramName, $modelName, $columnName) {

		$returnValue = array();

		/**
		 * start
		 */
		if (is_array($urlArray) &&
			$paramName &&
			class_exists($modelName) &&
			$columnName) {

			/**
			 * retrieve the view
			 */
			$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

			/**
			 * dummy
			 */
			$dummyModel = new $modelName();
			$dummyModelTableColumnDefinition = $dummyModel->getTable()->getColumns();

			/**
			 * column in translation?
			 */
			if (array_key_exists($columnName, $dummyModelTableColumnDefinition)) {

				/**
				 * retrieve array
				 */
				$modelArray = Doctrine_Query::create()
					->from($modelName . ' m')
					->select('m.id, m.' . $columnName)
					->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
					->execute()
				;

				/**
				 * build up urls
				 */
				foreach ($modelArray as $tupelArray) {
					$tmpLinksArray = array();
					foreach (L8M_Locale::getSupported() as $lang) {
						$tmpLinksArray[$lang] = $viewFromMVC->url(array_merge($urlArray, array('lang'=>$lang, $paramName=>$tupelArray[$columnName])), NULL, TRUE);
					}
					$returnValue[] = $tmpLinksArray;
				}
			} else
			if ($dummyModel->hasRelation('Translation')) {
				$dummyTranslationTableColumnDefinition = $dummyModel->Translation->getTable()->getColumns();
				if (array_key_exists($columnName, $dummyTranslationTableColumnDefinition)) {

					$tmpLinksArray = array();
					foreach (L8M_Locale::getSupported() as $lang) {

						/**
						 * retrieve array
						 */
						$modelArray = Doctrine_Query::create()
							->from($modelName . ' m')
							->leftJoin('m.Translation mt')
							->select('m.id, mt.' . $columnName)
							->addWhere('mt.lang = ? ', array($lang))
							->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
							->execute()
						;

						/**
						 * build up urls
						 */
						foreach ($modelArray as $tupelArray) {
							if (isset($tmpLinksArray[$tupelArray['id']]) &&
								is_array($tmpLinksArray[$tupelArray['id']])) {

								$tmpLinksArray[$tupelArray['id']][$lang] = $viewFromMVC->url(array_merge($urlArray, array('lang'=>$lang, $paramName=>$tupelArray['Translation'][$lang][$columnName])), NULL, TRUE);
							} else {
								$tmpLinksArray[$tupelArray['id']] = array(
									$lang=>$viewFromMVC->url(array_merge($urlArray, array('lang'=>$lang, $paramName=>$tupelArray['Translation'][$lang][$columnName])), NULL, TRUE),
								);
							}
						}
					}
					$returnValue = $tmpLinksArray;
				}
			}
		}

		return $returnValue;
	}

	/**
	 * add all action methods to the sitemap
	 *
	 * @param Array $returnValue
	 * @return Array $returnValue
	 */
	private static function _actionMethodsToUrl()
	{

		/**
		 * retrieve action methods
		 */
		$methodArray = Doctrine_Query::create()
			->from('Default_Model_Action m')
			->addWhere('m.is_action_method = ? ', TRUE)
			->select('m.id, m.resource')
			->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
			->execute()
		;

		if (count($methodArray) > 0) {

			$urlArray = array();

			/**
			 * retrieve the view
			 */
			$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

			/**
			 * build up urls
			 */
			$isNotInArray = TRUE;
			foreach ($methodArray as $method) {

				if (in_array('default.contact.index', $method)) {

					$isNotInArray = FALSE;
				}


				$tmpLinksArray = array();

				/**
				 * split the action resource to retrieve the different components
				 *
				 * @var Array
				 */
				$urlArrayTemp = explode('.', $method['resource']);

				if (is_array($urlArrayTemp)) {

					/**
					 * for every suproted language set the url from the aciton method
					 */
					foreach (L8M_Locale::getSupported() as $lang) {

						$urlArray = array(
							'module'=>$urlArrayTemp[0],
							'controller'=>$urlArrayTemp[1],
							'action'=>$urlArrayTemp[2],
							'lang'=>$lang,
						);

						$tmpLinksArray[$lang] = $viewFromMVC->url($urlArray, NULL, TRUE);
					}

					$returnValue[] = $tmpLinksArray;
				}
			}
			if ($isNotInArray) {
				/**
				 * add contact
				 */
				$tmpLinksArray = array();
				foreach (L8M_Locale::getSupported() as $lang) {
					$tmpLinksArray[$lang] = $viewFromMVC->url(array('module'=>'default', 'controller'=>'contact', 'action'=>'index', 'lang'=>$lang), NULL, TRUE);
				}
				$returnValue[] = $tmpLinksArray;
			}
		}

		return $returnValue;
	}

	/**
	 * handle special cases for example prj models and their controller
	 *
	 * @return Array $returnValue
	 */
	private static function _specialUrls()
	{
		$returnValue = array();

		/**
		 * retrieve the view
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		if (isset(self::$_myModel2Url) &&
			is_array(self::$_myModel2Url) &&
			count(self::$_myModel2Url) > 0) {

			foreach (self::$_myModel2Url as $modelName=>$model) {

				/**
				 * handle detail urls
				 */
				if (array_key_exists('detailUrlArray', $model)) {
					$returnValue = array_merge($returnValue, self::_addSpecialModelToUrl(array('module'=>$model['detailUrlArray']['module'], 'controller'=>$model['detailUrlArray']['controller'], 'action'=>$model['detailUrlArray']['action']), $model['param'], $modelName, $model['column']));
				}

				/**
				 * handle index url
				 */
				if (array_key_exists('indexUrlArray', $model)) {

					$tmpLinksArray = array();
					foreach (L8M_Locale::getSupported() as $lang) {
						$tmpLinksArray[$lang] = $viewFromMVC->url(array('module'=>$model['indexUrlArray']['module'], 'controller'=>$model['indexUrlArray']['controller'], 'action'=>$model['indexUrlArray']['action'], 'lang'=>$lang), NULL, TRUE);
					}
					$returnValue[] = $tmpLinksArray;
				}
			}
		}

		return $returnValue;
	}
}