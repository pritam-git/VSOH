<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/BlankSystemMenuStructureAuthor.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BlankSystemMenuStructureAuthor.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_BlankSystemMenuStructureAuthor
 *
 *
 */
class PRJ_View_Helper_BlankSystemMenuStructureAuthor extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private static $_generatedMenuStructure = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Return Menu Structure
	 *
	 * @return array
	 */
	public function blankSystemMenuStructureAuthor()
	{

		/**
		 * return value
		 */
		$returnValue = array();
		$rememberMenu = array();
		$menuCounter = 0;

		/**
		 * controllers
		 */
		$standardControllerNames = array(
			'Content',
			'Newsletter',
			'NewsletterUser',
			'NewsletterSubscriberType',
			'MetaConfiguration',
			'Translator',
		);
		$unusedControllerNames = array(
			'Index',
			'Login',
			'Old',
		);
		$specialStandardControllerNames = array(
			'Blog',
			'Faq',
			'News',
			'Team',
		);
		$markUsedAdminController = array();
		$standardControllerPrefixes = array(
		);

		/**
		 * view
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		/**
		 * merge array
		 */
		$standardControllerNames = array_merge($standardControllerNames, $unusedControllerNames);

		$returnValue[$menuCounter] = array(
			'short'=>'general',
			'name'=>$viewFromMVC->translate('General'),
			'title'=>$viewFromMVC->translate('Configurate your Application'),
			'description'=>'',
			'links'=>array(
				array(
					'css'=>'vcard-edit',
					'link'=>$this->view->url(array('module'=>'author', 'controller'=>'content'), NULL, TRUE),
					'controller'=>'content',
					'name'=>$viewFromMVC->translate('Content-Pages'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'application-view-list',
					'link'=>$this->view->url(array('module'=>'author', 'controller'=>'meta-configuration'), NULL, TRUE),
					'controller'=>'meta-configuration',
					'name'=>$viewFromMVC->translate('MetaConfiguration'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'images',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'media'), NULL, TRUE),
					'controller'=>'media',
					'name'=>$viewFromMVC->translate('Manage Media'),
					'showOnlyInEnvironment'=>NULL,
				),
			),
		);
		$rememberMenu['general'] = $menuCounter++;

		/**
		 * do we have something to translate
		 */
		if (count(L8M_Locale::getSupported()) > 0) {
			$returnValue[$rememberMenu['general']]['links'][] = array(
				'css'=>'comment-edit',
				'link'=>$this->view->url(array('module'=>'author', 'controller'=>'translator'), NULL, TRUE),
				'controller'=>'translator',
				'name'=>$viewFromMVC->translate('Translations'),
				'showOnlyInEnvironment'=>NULL,
			);
		}

		/**
		 * administrate newsletter
		 */
		if (L8M_Config::getOption('l8m.newsletter.enabled')) {
			$returnValue[$menuCounter] = array(
				'short'=>'newsletter',
				'name'=>$viewFromMVC->translate('Newsletter'),
				'title'=>$viewFromMVC->translate('Configurate the Newsletter'),
				'description'=>'',
				'links'=>array(
					array(
						'css'=>'report-user',
						'link'=>$this->view->url(array('module'=>'author', 'controller'=>'newsletter-user'), NULL, TRUE),
						'controller'=>'newsletter-user',
						'name'=>$viewFromMVC->translate('Newsletter Subscriber'),
						'showOnlyInEnvironment'=>NULL,
					),
					array(
						'css'=>'report-key',
						'link'=>$this->view->url(array('module'=>'author', 'controller'=>'newsletter-subscriber-type'), NULL, TRUE),
						'controller'=>'newsletter-subscriber-type',
						'name'=>$viewFromMVC->translate('NewsletterSubscriberType'),
						'showOnlyInEnvironment'=>NULL,
					),
					array(
						'css'=>'report',
						'link'=>$this->view->url(array('module'=>'author', 'controller'=>'newsletter'), NULL, TRUE),
						'controller'=>'newsletter',
						'name'=>$viewFromMVC->translate('Newsletter'),
						'showOnlyInEnvironment'=>NULL,
					),
				),
			);
			$rememberMenu['newsletter'] = $menuCounter++;
		}

		/**
		 * administrate your dynamic content
		 *
		 * walk through controller-directory
		 */
		$returnValue[$menuCounter] = array(
			'short'=>'dynamic-content',
			'name'=>$viewFromMVC->translate('Dynamic Content'),
			'title'=>$viewFromMVC->translate('Configurate your Dynamic Content'),
			'description'=>'',
			'links'=>array(),
		);
		$rememberMenu['dynamic-content'] = $menuCounter++;

		$walkTroughReturnArray = $this->_walkThroughModuleControllers($standardControllerNames, $markUsedAdminController, $standardControllerPrefixes, $specialStandardControllerNames, $returnValue, $rememberMenu, $viewFromMVC, 'author');

		$returnValue = $walkTroughReturnArray['returnValue'];
		$rememberMenu = $walkTroughReturnArray['rememberMenu'];
		$standardControllerNames = $walkTroughReturnArray['standardControllerNames'];


		return $returnValue;
	}

	private function _walkThroughModuleControllers($standardControllerNames, $markUsedAdminController, $standardControllerPrefixes, $specialStandardControllerNames, $menuBoxes, $rememberMenu, $viewFromMVC, $moduleName)
	{
		$returnValue = array(
			'returnValue'=>$menuBoxes,
			'rememberMenu'=>$rememberMenu,
			'standardControllerNames'=>array(),
		);

		$dynamicContentControllers = array();

		if (count($markUsedAdminController) > 0) {
			$standardControllerNames = array_merge($standardControllerNames, $markUsedAdminController);
		}

		$directoryIterator = new DirectoryIterator(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . 'controllers');
		foreach($directoryIterator as $file) {
			/* @var $file DirectoryIterator */
			if ($file->isFile() &&
				preg_match('/^(.+)Controller\.php$/', $file->getFilename(), $match)) {

				$hasStandardControllerPrefix = FALSE;
				foreach ($standardControllerPrefixes as $standardControllerPrefix) {
					if (substr($match[1], 0, strlen($standardControllerPrefix)) == $standardControllerPrefix) {
						$hasStandardControllerPrefix = TRUE;
					}
				}
				if (!in_array($match[1], $standardControllerNames) &&
					!$hasStandardControllerPrefix) {

					/**
					 * filter
					 */
					$filterController = new Zend_Filter_Word_CamelCaseToDash();
					$dynamicContentControllers[] = array(
						'shortName'=>strtolower($filterController->filter($match[1])),
						'longName'=>$match[1],
					);
				}
			}
		}

		if (count($dynamicContentControllers) > 0) {
			foreach ($dynamicContentControllers as $dynamicContentController) {
				if (in_array($dynamicContentController['longName'], $specialStandardControllerNames)) {

					/**
					 * do we have a Blog?
					 */
					if (L8M_Config::getOption('l8m.blog.enabled') &&
						$dynamicContentController['longName'] == 'Blog') {

						$menuBoxes[$rememberMenu['dynamic-content']]['links'][] = array(
							'css'=>'feed-edit',
							'link'=>$this->view->url(array('module'=>'author', 'controller'=>'blog'), NULL, TRUE),
							'controller'=>'blog',
							'name'=>$viewFromMVC->translate('Blog'),
							'showOnlyInEnvironment'=>NULL,
						);
					}

					/**
					 * do we have a FAQ?
					 */
					if (L8M_Config::getOption('l8m.faq.enabled') &&
						$dynamicContentController['longName'] == 'Faq') {

						$menuBoxes[$rememberMenu['dynamic-content']]['links'][] = array(
							'css'=>'help-edit',
							'link'=>$this->view->url(array('module'=>'author', 'controller'=>'faq'), NULL, TRUE),
							'controller'=>'faq',
							'name'=>$viewFromMVC->translate('FAQs'),
							'showOnlyInEnvironment'=>NULL,
						);
					}

					/**
					 * do we have News?
					 */
					if (L8M_Config::getOption('l8m.news.enabled') &&
						$dynamicContentController['longName'] == 'News') {

						$menuBoxes[$rememberMenu['dynamic-content']]['links'][] = array(
							'css'=>'calendar-edit',
							'link'=>$this->view->url(array('module'=>'author', 'controller'=>'news'), NULL, TRUE),
							'controller'=>'news',
							'name'=>$viewFromMVC->translate('News'),
							'showOnlyInEnvironment'=>NULL,
						);
					}

					/**
					 * do we have a team?
					 */
					if (L8M_Config::getOption('l8m.team.enabled') &&
						$dynamicContentController['longName'] == 'Team') {

						$menuBoxes[$rememberMenu['dynamic-content']]['links'][] = array(
							'css'=>'user',
							'link'=>$this->view->url(array('module'=>'author', 'controller'=>'team'), NULL, TRUE),
							'controller'=>'team',
							'name'=>$viewFromMVC->translate('Team'),
							'showOnlyInEnvironment'=>NULL,
						);
					}
				} else {

					$menuBoxes[$rememberMenu['dynamic-content']]['links'][] = array(
						'css'=>'application-form-edit',
						'link'=>$this->view->url(array('module'=>'author', 'controller'=>$dynamicContentController['shortName']), NULL, TRUE),
						'controller'=>$dynamicContentController['shortName'],
						'name'=>$viewFromMVC->translate($dynamicContentController['longName']),
						'showOnlyInEnvironment'=>NULL,
					);

				}
			}
		}


		$returnValue = array(
			'returnValue'=>$menuBoxes,
			'rememberMenu'=>$rememberMenu,
			'standardControllerNames'=>$standardControllerNames,
		);

		return $returnValue;
	}
}