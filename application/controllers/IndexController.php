<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/IndexController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: IndexController.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * IndexController
 *
 *
 */
class IndexController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Empty action.
	 *
	 * @return void
	 */
	public function emptyAction ()
	{

	}

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function indexAction ()
	{
		//initial checks for the Default module.

		PRJ_DefaultInitChecks::check();

		if (Zend_Auth::getInstance()->hasIdentity()) {
			$loginUser = Zend_Auth::getInstance()->getIdentity();
			if ($loginUser) {
				if ($loginUser->department_id) {
					$newsCollection = Doctrine_Query::create()
						->from('Default_Model_News m')
						->innerJoin('m.NewsM2nDepartment n')
						->addWhere('n.department_id = ?', array($loginUser->department_id))
						->addWhere('YEAR(m.publish_datetime) >= YEAR(CURDATE() - INTERVAL 2 YEAR)')
						->addWhere('m.publish_datetime <= CURDATE()')
						->orderBy('m.publish_datetime DESC')
						->addWhere(PRJ_Library::getPublishedWhereClause())
						->limit(5)
						->execute();

					$this->view->departmentName = $loginUser->Department->title;
					$this->view->newsCollection = $newsCollection;
				}
			}
		}
	}

	/**
	 * Set selected brand action.
	 *
	 * @return void
	 */
	public function setBrandAction() {
		$brandId = $this->_request->getParam('brand');
		$brandSession = new Zend_Session_Namespace('brand');

		if(!empty($brandId)) {
			//set the brand session if not found
			if (!isset($brandSession->id)) {
				$brandSession->id = $brandId;
			}
		} else {
			//unset the brand session if found
			if (isset($brandSession->id)) {
				unset($brandSession->id);
			}
		}

		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();
		$this->getResponse()
			->setBody($brandSession->id)
		;
	}
}