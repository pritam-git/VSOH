<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/ArchiveDatesController.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: ArchiveDatesController.php 549 2018-12-04 11:20:59Z nm $
 */

/**
 *
 *
 * ArchiveDatesController
 *
 *
 */
class ArchiveDatesController extends L8M_Controller_Action
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
			->setAction($this->view->url(array('module'=>'default', 'controller'=>'archive-dates', 'action'=>'index'), NULL, TRUE))
		;
		$page = $this->_request->getParam('page');
		$searchString = $this->_request->getParam('searchString');

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$regionID = $loginUser->region_id;
		$contractTypeID = $loginUser->contract_type_id;

		$datesQuery = Doctrine_Query::create()
			->from('Default_Model_Dates m')
		;

		if (($searchForm->isSubmitted() &&
			$searchForm->isValid($this->getRequest()->getPost())) ||
			($searchString != '')) {

			if(isset($searchForm->getValues()['searchProtocolInput']) && ($searchForm->getValues()['searchProtocolInput'])) {
				$searchString = $searchForm->getValues()['searchProtocolInput'];
			}
			$this->view->searchString = $searchString;

			$datesQuery->innerJoin('m.Translation mt')
				->addWhere('LOWER(mt.title) LIKE ?', array('%' . $searchString . '%'))
			;
		}
		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$datesQuery->innerJoin('m.DatesM2nDepartment n')
				->innerJoin('m.DatesM2nRegion o')
				->innerJoin('m.DatesM2nContractType p')
				->innerJoin('m.DatesM2nBrand b')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('o.region_id = ?', array($regionID))
				->addWhere('p.contract_type_id = ?', array($contractTypeID))
				->addWhere('m.publish_'.L8M_Locale::getLang().' = ?', array(TRUE))
			;

			if (L8M_Config::getOption('l8m.brandSwitch.enabled')) {
				$brandSession = new Zend_Session_Namespace('brand');
				$brandId = $brandSession->id;

				$datesQuery->addWhere('b.brand_id = ?', array($brandId));
			} else {
				$brandsArray = array();

				foreach($loginUser->EntityM2nBrand as $connectedBrand) {
					$brandsArray[] = $connectedBrand->brand_id;
				}

				$datesQuery->whereIn('b.brand_id', $brandsArray);
			}
		} else {
			$this->view->isAdmin = TRUE;
		}
		$datesQuery->addWhere('YEAR(m.publish_datetime) <= YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->orderBy('m.publish_datetime DESC')
		;

		$datesPager = new Doctrine_Pager($datesQuery, $page, L8M_Config::getOption('l8m.news.maxPerPage'));
		$this->view->datesCollection = $datesPager
		->execute()
		;

		$this->view->searchForm = $searchForm;
		$this->view->datesPager = $datesPager;
	}

	/**
	 * Details action.
	 *
	 * @return void
	 */
	public function detailAction ()
	{
		/**
		 * set model
		 */
		$this->_helper->layout()->model = TRUE;

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$regionID = $loginUser->region_id;
		$contractTypeID = $loginUser->contract_type_id;

		$datesModel = Doctrine_Query::create()
			->from('Default_Model_Dates m')
			->leftJoin('m.Translation mt')
		;
		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$datesModel->leftJoin('m.DatesM2nDepartment n')
				->leftJoin('m.DatesM2nRegion o')
				->leftJoin('m.DatesM2nContractType p')
				->leftJoin('m.DatesM2nBrand b')
				->leftJoin('m.DatesM2nMediaImage i')
				->addWhere('m.id = n.dates_id')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('o.region_id = ?', array($regionID))
				->addWhere('p.contract_type_id = ?', array($contractTypeID))
				->addWhere('m.publish_'.L8M_Locale::getLang().' = ?', array(TRUE))
			;

			if (L8M_Config::getOption('l8m.brandSwitch.enabled')) {
				$brandSession = new Zend_Session_Namespace('brand');
				$brandId = $brandSession->id;

				$datesModel->addWhere('b.brand_id = ?', array($brandId));
			} else {
				$brandsArray = array();

				foreach($loginUser->EntityM2nBrand as $connectedBrand) {
					$brandsArray[] = $connectedBrand->brand_id;
				}

				$datesModel->whereIn('b.brand_id', $brandsArray);
			}
		} else {
			$this->view->isAdmin = TRUE;
		}
		$datesModel = $datesModel->addWhere('m.short = ? AND mt.lang = ? ', array($this->_request->getParam('short'), L8M_Locale::getLang()))
			->addWhere('YEAR(m.publish_datetime) <= YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->limit(1)
			->execute()
			->getFirst()
		;

		if (!$datesModel) {
			$this->_redirect($this->_helper->url('index'));
		}

		$this->view->loginUser = $loginUser;
		$this->view->datesModel = $datesModel;
	}
}