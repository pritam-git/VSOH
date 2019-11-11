<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/ArchiveAssemblyController.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: ArchiveAssemblyController.php 549 2019-01-07 04:48:59Z nm $
 */

/**
 *
 *
 * ArchiveAssemblyController
 *
 *
 */
class ArchiveAssemblyController extends L8M_Controller_Action
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
			->setAction($this->view->url(array('module'=>'default', 'controller'=>'archive-assembly', 'action'=>'index'), NULL, TRUE))
		;
		$page = $this->_request->getParam('page');
		$searchString = $this->_request->getParam('searchString');

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$regionID = $loginUser->region_id;

		$assemblyQuery = Doctrine_Query::create()
			->from('Default_Model_Assembly m')
		;

		if (($searchForm->isSubmitted() &&
				$searchForm->isValid($this->getRequest()->getPost())) ||
			($searchString != '')) {

			if(isset($searchForm->getValues()['searchProtocolInput']) && ($searchForm->getValues()['searchProtocolInput'])) {
				$searchString = $searchForm->getValues()['searchProtocolInput'];
			}
			$this->view->searchString = $searchString;

			$assemblyQuery->innerJoin('m.Translation mt')
				->addWhere('LOWER(mt.title) LIKE ?', '%' . $searchString . '%')
			;
		}

		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$assemblyQuery->innerJoin('m.AssemblyM2nDepartment n')
				->innerJoin('m.AssemblyM2nRegion o')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('o.region_id = ?', array($regionID))
				->addWhere(PRJ_Library::getPublishedWhereClause())
			;
		} else {
			$this->view->isAdmin = TRUE;
		}
		$assemblyQuery->addWhere('YEAR(m.publish_datetime) <= YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->orderBy('m.publish_datetime DESC')
		;

		$assemblyPager = new Doctrine_Pager($assemblyQuery, $page, L8M_Config::getOption('l8m.news.maxPerPage'));
		$this->view->assemblyCollection = $assemblyPager
			->execute()
		;

		$this->view->searchForm = $searchForm;
		$this->view->assemblyPager = $assemblyPager;
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
		$regionID = $loginUser->region_id;
		$lang = L8M_Locale::getLang();

		$assemblyModel = Doctrine_Query::create()
			->from('Default_Model_Assembly m')
			->leftJoin('m.Translation mt')
		;
		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$assemblyModel->leftJoin('m.AssemblyM2nDepartment n')
				->leftJoin('m.AssemblyM2nRegion o')
				->leftJoin('m.AssemblyM2nMediaImage i')
				->addWhere('m.id = n.assembly_id')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('o.region_id = ?', array($regionID))
				->addWhere(PRJ_Library::getPublishedWhereClause())
			;
		} else {
			$this->view->isAdmin = TRUE;
		}
		$assemblyModel = $assemblyModel->addWhere('m.short = ? AND mt.lang = ? ', array($this->_request->getParam('short'), L8M_Locale::getLang()))
			->addWhere('YEAR(m.publish_datetime) <= YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->limit(1)
			->execute()
			->getFirst()
		;

		if (!$assemblyModel) {
			$this->_redirect($this->_helper->url('index'));
		}

		$this->view->title = $assemblyModel->Translation->$lang->title;
		$this->view->assemblyModel = $assemblyModel;
	}
}