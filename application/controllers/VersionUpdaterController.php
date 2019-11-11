<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/VersionUpdaterController.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: VersionUpdaterController.php 98 2019-06-04 13:22:08Z dp $
 */

/**
 *
 *
 * VersionUpdaterController
 *
 *
 */
class VersionUpdaterController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A string representing the session namespace reserved for this controller.
	 *
	 * @var string
	 */
	private $_environmentListFile = APPLICATION_PATH . '/configs/environment.ini';
	private $_currentEnvironment;

    /**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes System_ServerUpdateController.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();

		if (md5(L8M_Config::getOption('l8m.version_update.token')) != $this->_request->getParam('token')) {
			$this->_redirect($this->_helper->url('index', 'index', 'default'));
		}

		if(L8M_Config::getOption('l8m.server_updater.disabled')) {
			throw new L8M_Exception('Version Updater is disabled. Admin or supervisor needs to enable Version Updater support.');
		}

		$this->_currentEnvironment = L8M_Environment::getInstance($this->_environmentListFile)->getEnvironment();
	}

	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
     * Check and create update package
     *
     * @return bool/array
     */
    public function indexAction()
    {
		$versionUpdater = L8M_VersionUpdater::factory($this->_currentEnvironment, FALSE);
		$update = $versionUpdater->updateLocalPath('self-update', TRUE);

		if(isset($update['success'])) {
			if($update['success'] != TRUE) {
				$update = $versionUpdater->updateLocalPath('roll-back', TRUE);
			}
		} else {
			$update = array();
			$update['success'] = FALSE;
		}

        /**
         * json
         */
        $bodyData = Zend_Json_Encoder::encode($update);

        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
        Zend_Layout::getMvcInstance()->disableLayout();

        $this->getResponse()
            ->setHeader('Content-Type', 'application/json')
        ;

        $this->getResponse()
            ->setBody($bodyData)
        ;
    }

    /**
     * Updater test action
     * Check if updater is validated
     *
     * @return json
     */

    public function testUpdaterAction() {
        $returnData = array(
            'success' => TRUE
        );

        /**
         * json
         */
        $bodyData = Zend_Json_Encoder::encode($returnData);

        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
        Zend_Layout::getMvcInstance()->disableLayout();

        $this->getResponse()
            ->setHeader('Content-Type', 'application/json')
        ;

        $this->getResponse()
            ->setBody($bodyData)
        ;
    }
}
