<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/OtcController.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: OtcController.php 549 2019-01-07 04:33:59Z nm $
 */

/**
 *
 *
 * OtcController
 *
 *
 */
class OtcController extends L8M_Controller_Action
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
			->setAction($this->view->url(array('module'=>'default', 'controller'=>'otc', 'action'=>'index'), NULL, TRUE))
		;
		$page = $this->_request->getParam('page');
		$searchString = $this->_request->getParam('searchString');

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$regionID = $loginUser->region_id;

		$otcQuery = Doctrine_Query::create()
			->from('Default_Model_Otc m')
		;

		if (($searchForm->isSubmitted() &&
			$searchForm->isValid($this->getRequest()->getPost())) ||
			($searchString != '')) {

			if(isset($searchForm->getValues()['searchProtocolInput']) && ($searchForm->getValues()['searchProtocolInput'])) {
				$searchString = $searchForm->getValues()['searchProtocolInput'];
			}
			$this->view->searchString = $searchString;

			$otcQuery->innerJoin('m.Translation mt')
				->addWhere('LOWER(mt.title) LIKE ?', '%' . $searchString . '%')
			;
		}

		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$otcQuery->innerJoin('m.OtcM2nDepartment n')
				->innerJoin('m.OtcM2nRegion o')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('o.region_id = ?', array($regionID))
				->addWhere(PRJ_Library::getPublishedWhereClause())
				->addWhere('m.publish_datetime <= NOW()')
			;
		} else {
			$this->view->isAdmin = TRUE;
		}
		$otcQuery->addWhere('YEAR(m.publish_datetime) > YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->orderBy('m.publish_datetime DESC')
		;


		$otcPager = new Doctrine_Pager($otcQuery, $page, L8M_Config::getOption('l8m.news.maxPerPage'));
		$this->view->otcCollection = $otcPager
			->execute()
		;

		$this->view->searchForm = $searchForm;
		$this->view->otcPager = $otcPager;
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

		$otcModel = Doctrine_Query::create()
			->from('Default_Model_Otc m')
			->leftJoin('m.Translation mt')
		;
		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$otcModel->leftJoin('m.OtcM2nDepartment n')
				->leftJoin('m.OtcM2nRegion o')
				->leftJoin('m.OtcM2nMediaImage i')
				->addWhere('m.id = i.otc_id')
				->addWhere('m.id = n.otc_id')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('o.region_id = ?', array($regionID))
				->addWhere(PRJ_Library::getPublishedWhereClause())
				->addWhere('m.publish_datetime <= NOW()')
			;
		} else {
			$this->view->isAdmin = TRUE;
		}
		$otcModel=$otcModel->addWhere('m.short = ? AND mt.lang = ? ', array($this->_request->getParam('short'), L8M_Locale::getLang()))
			->addWhere('YEAR(m.publish_datetime) > YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->limit(1)
			->execute()
			->getFirst();

		if (!$otcModel) {
			$this->_redirect($this->_helper->url('index'));
		}

		$this->view->title = $otcModel->Translation->$lang->title;
		$this->view->otcModel = $otcModel;
	}
}