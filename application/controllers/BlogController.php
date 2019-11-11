<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/BlogController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BlogController.php 10 2014-03-12 15:03:22Z nm $
 */

/**
 *
 *
 * BlogController
 *
 *
 */
class BlogController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Initialization Methods
	 *
	 *
	 */
	public function init()
	{
		if ($this->getOption('l8m.blog.enabled') == FALSE) {
			$this->_redirect($this->_helper->url('index', 'index', 'default'));
		}

		/**
		 * init parent
		 */
		parent::init();
	}

	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function indexAction ()
	{
		$page = $this->_request->getParam('page');

		$blogQuery = Doctrine_Query::create()
			->from('Default_Model_Blog m')
			->addWhere('m.publish_datetime <= NOW()', array())
			->orderBy('m.publish_datetime DESC')
		;

		$blogPager = new Doctrine_Pager($blogQuery, $page, L8M_Config::getOption('l8m.blog.maxPerPage'));
		$this->view->blogCollection = $blogPager
			->execute()
		;

		$this->view->blogPager = $blogPager;

	}

	/**
	 * RSS action.
	 *
	 * @return void
	 */
	public function rssAction ()
	{
		$this->view->blogCollection = Doctrine_Query::create()
			->from('Default_Model_Blog m')
			->addWhere('m.publish_datetime <= NOW()', array())
			->orderBy('m.publish_datetime DESC')
			->limit(L8M_Config::getOption('l8m.blog.rss.limit'))
			->execute()
		;

		Zend_Layout::getMvcInstance()->disableLayout();
	}

	/**
	 * Details action.
	 *
	 * @return void
	 */
	public function detailAction ()
	{
		$blogModel = Doctrine_Query::create()
			->from('Default_Model_Blog m')
			->leftJoin('m.Translation mt')
			->addWhere('mt.ushort = ? AND mt.lang = ? ', array($this->_request->getParam('short'), L8M_Locale::getLang()))
			->addWhere('m.publish_datetime <= NOW()', array())
			->limit(1)
			->execute()
			->getFirst()
		;

		if (!$blogModel) {
			$this->_redirect($this->_helper->url('index'));
		}

		$this->view->blogModel = $blogModel;
		$this->view->layout()->subheadline = $blogModel->title;
	}
}