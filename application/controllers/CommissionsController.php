<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/CommissionsController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: CommissionsController.php 549 2017-08-30 08:26:59Z nm $
 */

/**
 *
 *
 * CommissionsController
 *
 *
 */
class CommissionsController extends L8M_Controller_Action
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

		$collection = Doctrine_Query::create()
			->from('Default_Model_Commission m')
			->orderBy('m.position ASC')
			->execute()
		;

		$this->view->collection = $collection;

	}

	/**
	 * get protocol action.
	 *
	 * @return void
	 */
	public function protocolAction ()
	{
		$short = $this->_request->getParam('short');
		$searchForm = new Default_Form_Protocol_Search();
		$searchForm
//			->addDecorators(array(
//				new L8M_Form_Decorator_FormHasRequiredElements(),
//			))
			->setAction($this->view->url(array('module'=>'default', 'controller'=>'commissions', 'action'=>'protocol', 'short'=>$short), NULL, TRUE))
		;
		$page = $this->_request->getParam('page');
		$searchString = $this->_request->getParam('searchString');

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$regionID = $loginUser->region_id;
		$contractTypeID = $loginUser->contract_type_id;

		$commissionModel = Default_Model_Commission::getModelByShort($short);
		if(!$commissionModel) {
			$this->_redirect($this->_helper->url('index', 'commissions', 'default'));
		}

		$this->view->commissionName = $commissionModel->toArray()['Translation'][L8M_Locale::getLang()]['title'];

		$protocolQuery = Doctrine_Query::create()
			->from('Default_Model_Protocol m')
			->innerJoin('m.Commission c')
			->addWhere('c.short = ?', array($short))
		;

		if (($searchForm->isSubmitted() &&
			$searchForm->isValid($this->getRequest()->getPost())) ||
			($searchString != '')) {

			if(isset($searchForm->getValues()['searchProtocolInput']) && ($searchForm->getValues()['searchProtocolInput'])) {
				$searchString = $searchForm->getValues()['searchProtocolInput'];
			}
			$this->view->searchString = $searchString;

			$protocolQuery->addWhere('LOWER(m.name) LIKE ?', '%' . $searchString . '%');
		}
		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$protocolQuery->innerJoin('m.ProtocolM2nDepartment n')
				->innerJoin('m.ProtocolM2nRegion o')
				->innerJoin('m.ProtocolM2nContractType p')
				->innerJoin('m.ProtocolM2nBrand b')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('o.region_id = ?', array($regionID))
				->addWhere('p.contract_type_id = ?', array($contractTypeID))
				->addWhere(PRJ_Library::getPublishedWhereClause())
				->addWhere('m.publish_datetime <= NOW()')
			;

			if (L8M_Config::getOption('l8m.brandSwitch.enabled')) {
				$brandSession = new Zend_Session_Namespace('brand');
				$brandId = $brandSession->id;

				$protocolQuery->addWhere('b.brand_id = ?', array($brandId));
			} else {
				$brandsArray = array();

				foreach($loginUser->EntityM2nBrand as $connectedBrand) {
					$brandsArray[] = $connectedBrand->brand_id;
				}

				$protocolQuery->whereIn('b.brand_id', $brandsArray);
			}
		} else {
			$this->view->isAdmin = TRUE;
		}
		$protocolQuery->addWhere('YEAR(m.publish_datetime) > YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->orderBy('m.publish_datetime DESC')
		;

		$protocolPager = new Doctrine_Pager($protocolQuery, $page, L8M_Config::getOption('l8m.news.maxPerPage'));
		$this->view->protocolCollection = $protocolPager
			->execute()
		;
		$this->view->short = $short;
		$this->view->searchForm = $searchForm;
		$this->view->protocolPager = $protocolPager;
	}

	/**
	 * get protocol details action.
	 *
	 * @return void
	 */
	public function detailAction ()
	{
		$short = $this->_request->getParam('short');

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$regionID = $loginUser->region_id;
		$contractTypeID = $loginUser->contract_type_id;
		$lang = L8M_Locale::getLang();

		$protocolModel = Default_Model_Protocol::createQuery()
			->from('Default_Model_Protocol m')
			->innerJoin('m.Commission c')
			->addWhere('m.short = ?', array($short))
			->addWhere('YEAR(m.publish_datetime) > YEAR(CURDATE() - INTERVAL 2 YEAR)')
		;

		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$protocolModel->innerJoin('m.ProtocolM2nDepartment n')
				->innerJoin('m.ProtocolM2nRegion o')
				->innerJoin('m.ProtocolM2nContractType p')
				->innerJoin('m.ProtocolM2nBrand b')
				->leftJoin('m.ProtocolM2nMediaImage i')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('o.region_id = ?', array($regionID))
				->addWhere('p.contract_type_id = ?', array($contractTypeID))
				->addWhere(PRJ_Library::getPublishedWhereClause())
				->addWhere('m.publish_datetime <= NOW()')
			;

			if (L8M_Config::getOption('l8m.brandSwitch.enabled')) {
				$brandSession = new Zend_Session_Namespace('brand');
				$brandId = $brandSession->id;

				$protocolModel->addWhere('b.brand_id = ?', array($brandId));
			} else {
				$brandsArray = array();

				foreach($loginUser->EntityM2nBrand as $connectedBrand) {
					$brandsArray[] = $connectedBrand->brand_id;
				}

				$protocolModel->whereIn('b.brand_id', $brandsArray);
			}
		} else {
			$this->view->isAdmin = TRUE;
		}
		$protocolModel = $protocolModel->limit(1)
			->execute()
			->getFirst()
		;
		if (!$protocolModel) {
			$this->_redirect($this->_helper->url('index'));
		}

		$this->view->protocol = $protocolModel->Commission->short;
		$this->view->title = $protocolModel->Translation->$lang->title;
		$this->view->short = $protocolModel->short;
		$this->view->headline = $this->view->translate('Protokolle der Kommissionen', 'de') . ": " . $protocolModel->Commission->Translation->$lang->title;
		$this->view->protocolModel = $protocolModel;
	}

	/**
	 * set PDF file in HTML
	 *
	 * @return void
	 */
	public function setPdfAction ()
	{

		$mediaID = $this->getRequest()->getParam('media', NULL, FALSE);

		$mediaModel = Default_Model_Media::getModelByID($mediaID, 'Default_Model_Media');

		if(!($mediaModel instanceof Default_Model_Media)) {
			$this->_redirect($this->_helper->url('index'));
		}

		$fileName = $mediaModel->file_name;
		$filePath = $mediaModel->getStoredFilePath();

		//get file type
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$fileType = finfo_file($finfo, $filePath);
		finfo_close($finfo);

		/**
		 * disable layout
		 */
		Zend_Layout::getMvcInstance()->disableLayout();
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);

		$this->getResponse()
			->setHeader('Content-Type', $fileType)
			->setHeader('Content-Disposition',  'inline; filename="' . $fileName . '"')
			->setBody(file_get_contents($filePath))
		;
	}
}