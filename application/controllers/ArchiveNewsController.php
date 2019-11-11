<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/ArchiveNewsController.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: ArchiveNewsController.php 549 2018-12-04 12:48:59Z nm $
 */

/**
 *
 *
 * ArchiveNewsController
 *
 *
 */
class ArchiveNewsController extends L8M_Controller_Action
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
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			$short = $this->_request->getParams();
//			$redirectTo = $this->view->url($short);
//			$this->_redirect($this->_helper->url('index', 'login', 'default').'?sendTo='.$redirectTo);
			$this->_redirect($this->_helper->url('index', 'login', 'default', array('comingFrom'=>json_encode($short))));
		}

		//initial checks for the Default module.
		PRJ_DefaultInitChecks::check();

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
		$searchForm = new Default_Form_Protocol_Search();
		$searchForm
//			->addDecorators(array(
//				new L8M_Form_Decorator_FormHasRequiredElements(),
//			))
			->setAction($this->view->url(array('module'=>'default', 'controller'=>'archive-news', 'action'=>'index'), NULL, TRUE))
		;
		$page = $this->_request->getParam('page');
		$searchString = $this->_request->getParam('searchString');

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;

		$newsQuery = Doctrine_Query::create()
			->from('Default_Model_News m')
		;

		if (($searchForm->isSubmitted() &&
				$searchForm->isValid($this->getRequest()->getPost())) ||
			($searchString != '')) {
			if(isset($searchForm->getValues()['searchProtocolInput']) && ($searchForm->getValues()['searchProtocolInput'])) {
				$searchString = $searchForm->getValues()['searchProtocolInput'];
			}
			$this->view->searchString = $searchString;

			$newsQuery->innerJoin('m.Translation mt')
				->addWhere('LOWER(mt.title) LIKE ?', '%' . $searchString . '%')
			;
		}
		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$newsQuery->innerJoin('m.NewsM2nDepartment n')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere(PRJ_Library::getPublishedWhereClause())
			;
		} else {
			$this->view->isAdmin = TRUE;
		}
		$newsQuery->addWhere('YEAR(m.publish_datetime) <= YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->orderBy('m.publish_datetime DESC')
		;

		$newsPager = new Doctrine_Pager($newsQuery, $page, L8M_Config::getOption('l8m.news.maxPerPage'));
		$this->view->newsCollection = $newsPager
			->execute()
		;

		$this->view->searchForm = $searchForm;
		$this->view->newsPager = $newsPager;
	}

	/**
	 * Details action.
	 *
	 * @return void
	 */
	public function detailAction ()
	{
		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$lang = L8M_Locale::getLang();

		$newsModel = Doctrine_Query::create()
			->from('Default_Model_News m')
			->leftJoin('m.Translation mt')
		;
		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$newsModel->leftJoin('m.NewsM2nDepartment n')
				->leftJoin('m.NewsM2nMediaImage i')
				->addWhere('m.id = n.news_id')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere(PRJ_Library::getPublishedWhereClause())
			;
		} else {
			$this->view->isAdmin = TRUE;
		}
		$newsModel = $newsModel->addWhere('m.short = ? AND mt.lang = ? ', array($this->_request->getParam('short'), L8M_Locale::getLang()))
			->addWhere('YEAR(m.publish_datetime) <= YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->limit(1)
			->execute()
			->getFirst()
		;

		if (!$newsModel) {
			$this->_redirect($this->_helper->url('index'));
		}

		$this->view->title = $newsModel->Translation->$lang->title;
		$this->view->newsModel = $newsModel;
	}
}