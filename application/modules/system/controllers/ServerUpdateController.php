<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/ServerUpdateController.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: ServerUpdateController.php 359 2019-05-27 12:09:00Z dp $
 */


/**
 *
 *
 * System_ServerUpdateController
 *
 *
 */
class System_ServerUpdateController extends L8M_Controller_Action
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
	protected $_sessionNamespace = 'System_ServerUpdateController';

	private $_serverUrl;
	private $_environmentListFile = APPLICATION_PATH . '/configs/environment.ini';
	private $_currentEnvironment;
	private $_remoteDataDirectory;

	/**
	 * Store modelList.
	 *
	 * @var L8M_ModelForm_List
	 */
	// private $_modelList = NULL;

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes System_ServerUpdateController
	 *
	 * @return void
	 */
	public function init ()
	{
		/**
		 * pass through parent to prevent errors
		 */
		parent::init();

		if(L8M_Config::getOption('l8m.server_updater.disabled')) {
			throw new L8M_Exception('Version Updater is disabled. Admin or supervisor needs to enable Version Updater support.');
		}

		ini_set('max_execution_time', 0);
		ini_set('memory_limit', -1);

		$this->_serverUrl = $_SERVER['SERVER_NAME'];
		$this->_currentEnvironment = L8M_Environment::getInstance($this->_environmentListFile)->getEnvironment();

		/* if($this->_currentEnvironment === L8M_Environment::ENVIRONMENT_PRODUCTION) {
			$this->_redirect($this->_helper->url('index', 'index'));
		} */

		if($this->_currentEnvironment === L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
			$this->_remoteDataDirectory = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'lastVersions');
		} else
		if($this->_currentEnvironment === L8M_Environment::ENVIRONMENT_STAGING) {
			$this->_remoteDataDirectory = realpath(BASE_PATH . DIRECTORY_SEPARATOR . '..');
		}

		$this->_serverCredentialsArray = array(
            'gamma.vsoh.ch' => array(
                'username' => 'admin_vsoh_gamma',
                'password' => 'Sve~717aa!y7tA99'
            ),
            'beta.vsoh.ch' => array(
                'username' => 'admin_vsoh_beta',
                'password' => '99#uhl8TZh6*mp05'
            ),
            'www.vsoh.ch' => array(
                'username' => '',
                'password' => ''
            )
        );
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
		$environments = parse_ini_file($this->_environmentListFile);
		if(isset($environments['development'])) {
			unset($environments['development']);
		}

		if(isset($environments['production'])) {
			unset($environments['production']);
		}

		foreach($environments as $environment=>$urls) {
			foreach($urls as $index=>$url) {
				if($this->_serverUrl == $url) {
					unset($environments[$environment][$index]);
				}

				if(($this->_currentEnvironment === L8M_Environment::ENVIRONMENT_DEVELOPMENT) && !file_exists($this->_remoteDataDirectory .DIRECTORY_SEPARATOR . $url . '.zip')) {
					unset($environments[$environment][$index]);
				} else
				if(($this->_currentEnvironment === L8M_Environment::ENVIRONMENT_STAGING) && !file_exists($this->_remoteDataDirectory .DIRECTORY_SEPARATOR . $url)) {
					unset($environments[$environment][$index]);
				}
			}

			if(count($environments[$environment]) == 0) {
				unset($environments[$environment]);
			}
		}

		if(file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'self.update.zip')) {
			$this->view->manualUpdate = TRUE;
		}

		$this->view->selfUrl = $this->_serverUrl;
		$this->view->allServers = $environments;
	}

	/**
	 * Compare Versions action.
	 *
	 * @return void
	 */
	public function compareVersionsAction ()
	{
		$postedData = $this->getRequest()->getPost();

		$updatesList = FALSE;

		if(isset($postedData['fromAutoUpdate']) && (bool)$postedData['fromAutoUpdate'] == TRUE) {
			if(!isset($postedData['serverToUpdate']) || (bool)$postedData['serverToUpdate'] == '') {
				$this->_redirect($this->_helper->url('index'));
			}

			$serverToUpdate = $postedData['serverToUpdate'];

			if(isset($this->_serverCredentialsArray[$serverToUpdate]) &&
				isset($this->_serverCredentialsArray[$serverToUpdate]['username']) &&
				$this->_serverCredentialsArray[$serverToUpdate]['username'] != '' &&
				isset($this->_serverCredentialsArray[$serverToUpdate]['password']) &&
				$this->_serverCredentialsArray[$serverToUpdate]['password'] != '') {

				$versionUpdater = L8M_VersionUpdater::factory($this->_currentEnvironment, $this->_remoteDataDirectory . DIRECTORY_SEPARATOR . $serverToUpdate);

				$versionUpdater->checkVersionUpdaterSupportOnRemote($this->_serverCredentialsArray[$serverToUpdate]['username'], $this->_serverCredentialsArray[$serverToUpdate]['password']);

				if($versionUpdater->remoteDataExists()) {
					$updatesList = $versionUpdater->createUpdatePackage();

					$this->view->updateServerUrl = $serverToUpdate;
				} else {
					$this->_redirect($this->_helper->url('index'));
				}
			} else {
				throw new L8M_Exception('server FTP details not available for ' . $serverToUpdate . '. Please contact the admin.');
			}
		} else {
			if(isset($_FILES['update-package']) &&
				isset($_FILES['update-package']['type']) &&
				($_FILES['update-package']['type'] == "application/zip")){

				$za = new ZipArchive();
				$za->open($_FILES['update-package']['tmp_name']);
				for( $i = 0; $i < $za->numFiles; $i++ ){
					$stat = $za->statIndex( $i );
					$zFiles[] = $stat['name'];
				}
				if(in_array('changes.ini', $zFiles)){
					$uploadsDir = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
					$tmp_name = $_FILES["update-package"]["tmp_name"];
					$name = 'self.update.zip';
					move_uploaded_file($tmp_name, $uploadsDir . DIRECTORY_SEPARATOR . $name);
					if(!file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'self.update.zip')) {
						$this->_redirect($this->_helper->url('index'));
					}

					$versionUpdater = L8M_VersionUpdater::factory($this->_currentEnvironment, FALSE);

					$updatesList = $versionUpdater->getUpdatesList();
				}else{
					$this->view->err = "Invalid ZIP file Uploaded";
				}
			}else{
				$this->view->err = "Invalid file Uploaded";
			}
		}

		$this->view->selfUrl = $this->_serverUrl;

		if($updatesList) {
			$this->view->updates = $updatesList;
		}
	}

	/**
     * Download the update package created for the selected server
     *
     * @return bool
     * @throws Doctrine_Query_Exception
     * @throws L8M_Exception
     */
	public function downloadUpdatePackageAction()
	{
        $postedData = $this->getRequest()->getPost();
        $tempPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;

        if(isset($postedData['fromAutoUpdate']) && (bool)$postedData['fromAutoUpdate'] == TRUE) {
            if(!isset($postedData['serverToUpdate']) || (bool)$postedData['serverToUpdate'] == '') {
                $this->_redirect($this->_helper->url('index'));
            }

            $serverToUpdate = $postedData['serverToUpdate'];
            $fileName = $serverToUpdate . '.update.zip';
            $downloadFilePath = $tempPath . $serverToUpdate . '.update.zip';

            if(file_exists($downloadFilePath)) {
                header("Content-type: application/zip");
                header("Content-Disposition: attachment; filename=$fileName");
                header("Content-length: " . filesize($downloadFilePath));
                header("Pragma: no-cache");
                header("Expires: 0");
                readfile("$downloadFilePath");
            }
            Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
            Zend_Layout::getMvcInstance()->disableLayout();

            $this->getResponse()
                ->setHeader('Content-Type', 'application/zip');
        } else {
            $this->_redirect($this->_helper->url('index'));
        }
    }

	/**
     * Replace or Add new files which have changes and remove extra files
     *
     * @return bool
     * @throws Doctrine_Query_Exception
     * @throws L8M_Exception
     */
    public function updateServerAction ()
    {
		$postedData = $this->getRequest()->getPost();

		if(isset($postedData['fromAutoUpdate']) && (bool)$postedData['fromAutoUpdate'] == TRUE) {
			if(!isset($postedData['serverToUpdate']) || $postedData['serverToUpdate'] == '') {
				$this->_redirect($this->_helper->url('index'));
			}

			$serverToUpdate = $postedData['serverToUpdate'];

			if(isset($this->_serverCredentialsArray[$serverToUpdate]) &&
				isset($this->_serverCredentialsArray[$serverToUpdate]['username']) &&
				$this->_serverCredentialsArray[$serverToUpdate]['username'] != '' &&
				isset($this->_serverCredentialsArray[$serverToUpdate]['password']) &&
				$this->_serverCredentialsArray[$serverToUpdate]['password'] != '') {

				$upOne = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp');
				$zipFile = $upOne . DIRECTORY_SEPARATOR . $serverToUpdate . '.update.zip';
				if(!file_exists($zipFile)) {
					$this->_redirect($this->_helper->url('index'));
				}

				$versionUpdater = L8M_VersionUpdater::factory($this->_currentEnvironment, $this->_remoteDataDirectory . DIRECTORY_SEPARATOR . $serverToUpdate);

				$updateFileUploaded = $versionUpdater->uploadUpdateZip($this->_serverCredentialsArray[$serverToUpdate]['username'], $this->_serverCredentialsArray[$serverToUpdate]['password']);

				if($updateFileUploaded) {
					$output = $versionUpdater->updateRemotePath($this->_serverCredentialsArray[$serverToUpdate]['username'], $this->_serverCredentialsArray[$serverToUpdate]['password']);

					if($this->_currentEnvironment === L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
						$update = $versionUpdater->updateLocalPath('dump-update');
						$this->view->uploaded = $update;
					}
				}
			} else {
				throw new L8M_Exception('server FTP details not available for ' . $serverToUpdate . '. Please contact the admin.');
			}
		} else {
			if(!file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'self.update.zip')) {
				$this->_redirect($this->_helper->url('index'));
			}

			$versionUpdater = L8M_VersionUpdater::factory($this->_currentEnvironment, FALSE);

			$update = $versionUpdater->updateLocalPath('self-update');
			$this->view->uploaded = $update;
		}
	}

    public function testAction () {

	}

}