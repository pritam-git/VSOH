<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/LoginController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: LoginController.php 548 2017-08-24 22:52:22Z nm $
 */

/**
 *
 *
 * System_LoginController
 *
 *
 */
class System_LoginController extends L8M_Controller_Action
{
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
		$this->view->layout()->robots = 'noindex, nofollow';
		$this->view->headScript()
			->appendFile('/js/jquery/system/captcha.js', 'text/javascript')
		;
		if (L8M_Config::getOption('authentication.backEnd.geoPositioning.enabled')) {
			$this->view->headScript()
				->appendFile('/js/jquery/system/geo.js', 'text/javascript')
			;
		}

		if (Zend_Auth::getInstance()->hasIdentity()) {
			$defaultActionResource = Zend_Auth::getInstance()->getIdentity()->Role->default_action_resource;
			if ($defaultActionResource) {
				$this->_redirect($this->_helper->url(L8M_Acl_Resource::getActionNameFromResource($defaultActionResource), L8M_Acl_Resource::getControllerNameFromResource($defaultActionResource), L8M_Acl_Resource::getModuleNameFromResource($defaultActionResource)));
			} else {
				$this->_redirect($this->_helper->url('index', 'index', 'default'));
			}
		}

		/**
		 * retrieve params
		 */
		$paramComingFrom = $this->getRequest()->getParam('comingFrom', NULL, FALSE);
		$paramDo = $this->getRequest()->getParam('do', NULL, FALSE);
		$paramHasGeo = $this->getRequest()->getParam('hasGeo', NULL, FALSE);
		$paramGeoCapId = $this->getRequest()->getParam('geoCapId', NULL, FALSE);
		$paramGeoError = $this->getRequest()->getParam('geoError', NULL, FALSE);

		/**
		 * error
		 */
		$geoErrorArray = array(
			'UNKNOWN_ERROR'=>'The system could not determine your position.',
			'TIMEOUT'=>'The system could not determine your position, because of a timeout.',
			'PERMISSION_DENIED'=>'The system could not determine your position, because permission was denied.',
			'POSITION_UNAVAILABLE'=>'The system could not determine your position, because the position of the device could not be determined.',
		);
		$geoErrorDefault = 'UNKNOWN_ERROR';

		/**
		 * has geo location API
		 */
		if ($paramHasGeo) {
			$paramHasGeo = TRUE;
		} else {
			$paramHasGeo = FALSE;
		}

		/**
		 * session for controller
		 */
		$session = new Zend_Session_Namespace(get_class($this));
		$geoCaptchaBindEnabled =& $session->geoCaptchaBindEnabled;
		if (!is_array($geoCaptchaBindEnabled)) {
			$geoCaptchaBindEnabled = array();
		}
		$geoCaptchaBindError =& $session->geoCaptchaBindError;
		if (!is_array($geoCaptchaBindError)) {
			$geoCaptchaBindError = array();
		}

		/**
		 * form exception
		 */
		$formException = NULL;

		/**
		 * login form
		 */
		$form = new System_Form_User_Login();
		$form
			->setAction($this->_helper->url('index', 'login', 'system', array('comingFrom'=>$paramComingFrom)))
		;

		if ($this->getRequest()->isXmlHttpRequest()) {
			$data = array();
			if ($paramDo == 'geo') {
				if ($paramHasGeo &&
					$paramGeoCapId &&
					array_key_exists($paramGeoCapId, $geoCaptchaBindEnabled) &&
					$geoCaptchaBindEnabled[$paramGeoCapId] === NULL) {

					$geoCaptchaBindEnabled[$paramGeoCapId] = TRUE;
					$data = $geoCaptchaBindEnabled;
				} else
				if ($paramGeoCapId &&
					array_key_exists($paramGeoCapId, $geoCaptchaBindEnabled)) {

					$geoCaptchaBindEnabled[$paramGeoCapId] = FALSE;
					$geoCaptchaBindError[$paramGeoCapId] = $geoErrorDefault;

					if (!$paramHasGeo &&
						array_key_exists($paramGeoError, $geoErrorArray)) {

						$geoCaptchaBindError[$paramGeoCapId] = $geoErrorArray[$paramGeoError];
					}
				}
			} else
			if ($paramDo == 're-captcha') {
				if ($form->getElement('captcha') instanceof Zend_Form_Element_Captcha) {
					$captcha = $form->getElement('captcha')->getCaptcha();

					$data['id']  = $captcha->generate();
					$data['src'] = $captcha->getImgUrl() . $captcha->getId() . $captcha->getSuffix();

					if ($paramGeoCapId &&
						array_key_exists($paramGeoCapId, $geoCaptchaBindEnabled)) {

						$geoCaptchaBindEnabled[$captcha->getId()] = $geoCaptchaBindEnabled[$paramGeoCapId];
						$geoCaptchaBindError[$captcha->getId()] = $geoCaptchaBindError[$paramGeoCapId];
					}
				}
			}

			/**
			 * json
			 */
			$bodyData = Zend_Json_Encoder::encode($data);

			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			$this->getResponse()
				->setHeader('Content-Type', 'application/json')
			;

			$this->getResponse()
				->setBody($bodyData)
			;
		} else {
			if ($form->isSubmitted() &&
				$form->isValid($this->getRequest()->getParams())) {

				/**
				 * don't try login
				 */
				$goOnTryLogin = FALSE;

				/**
				 * formValues
				 */
				$formValues = $form->getValues();

				if (!L8M_Config::getOption('authentication.backEnd.captcha.enabled') &&
					!L8M_Config::getOption('authentication.backEnd.geoPositioning.enabled')) {

					$goOnTryLogin = TRUE;
				} else
				if (L8M_Config::getOption('authentication.backEnd.captcha.enabled') &&
					!L8M_Config::getOption('authentication.backEnd.geoPositioning.enabled')) {

					$goOnTryLogin = TRUE;
				} else
				if (!L8M_Config::getOption('authentication.backEnd.captcha.enabled') &&
					L8M_Config::getOption('authentication.backEnd.geoPositioning.enabled')) {

					$goOnTryLogin = TRUE;
				} else
				if (L8M_Config::getOption('authentication.backEnd.captcha.enabled') &&
					L8M_Config::getOption('authentication.backEnd.geoPositioning.enabled')) {

					/**
					 * captcha id
					 */
					if ($form->getElement('captcha') instanceof Zend_Form_Element_Captcha) {
						$captchaId = $formValues['captcha']['id'];
					} else
					if ($form->getElement('captcha') instanceof L8M_Form_Element_GoogleReCaptcha) {
						$captchaId = $this->getRequest()->getParam('captcha-id');
					}

					$goOn = TRUE;
					if (L8M_Config::getOption('authentication.backEnd.geoPositioning.enabled')) {
						$goOn = FALSE;

						/**
						 * check for geo position
						 */
						if (array_key_exists($captchaId, $geoCaptchaBindEnabled) &&
							$geoCaptchaBindEnabled[$captchaId] === TRUE &&
							$geoCaptchaBindError[$captchaId] === NULL) {

							$goOn = TRUE;
						}
					}

					/**
					 * check for goOn | geo position
					 */
					if ($goOn) {
						$goOnTryLogin = TRUE;
					} else {

						/**
						 * geo position
						 */
						if ((array_key_exists($captchaId, $geoCaptchaBindError) && $geoCaptchaBindError[$captchaId] === NULL) ||
							!array_key_exists($captchaId, $geoCaptchaBindError)) {

							$geoCaptchaBindError[$captchaId] = $geoErrorDefault;
						}
						$formException = new L8M_Exception($this->view->translate($geoErrorArray[$geoCaptchaBindError[$captchaId]]));
					}
				}

				/**
				 * try login
				 */
				if ($goOnTryLogin) {

					/**
					 * authResult
					 */
					if (L8M_Environment::ENVIRONMENT_DEVELOPMENT != L8M_Environment::getInstance()->getEnvironment()) {
						$authResult = L8M_Controller_Plugin_AuthControlled::login($formValues['login'], $formValues['password'], $formValues['lat'], $formValues['lon'], $formValues['acc'], $formValues['alt'], $formValues['altacc'], $formValues['hea'], $formValues['spe']);
					} else {
						$authResult = L8M_Controller_Plugin_AuthControlled::loginWithoutPassword($formValues['login'], $formValues['lat'], $formValues['lon'], $formValues['acc'], $formValues['alt'], $formValues['altacc'], $formValues['hea'], $formValues['spe']);
					}

					/**
					 * user could be logged in
					 */
					if ($authResult->isValid()) {

						/**
						 * empty geo session bind
						 */
						$geoCaptchaBindEnabled = array();
						$geoCaptchaBindError = array();

						/**
						 * redirect if coming from
						 */
						if ($paramComingFrom) {
							$paramComingFromArray = explode('.', $paramComingFrom);
							if (count($paramComingFromArray) == 3) {
								$this->_redirect($this->_helper->url($paramComingFromArray[2], $paramComingFromArray[1], $paramComingFromArray[0]));
							}
						}

						$entityModel = Zend_Auth::getInstance()->getIdentity();

						/**
						 * query the default action
						 */
						$defaultAction = Doctrine_Query::create()
							->from('Default_Model_Action a')
							->select('a.name AS action_name, c.name AS controller_name, m.name AS module_name')
							->leftJoin('a.Controller AS c ON a.controller_id=c.id')
							->leftJoin('c.Module AS m ON c.module_id=m.id')
							->where('a.resource = ?', $entityModel->Role->default_action_resource)
							->limit(1)
							->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
							->execute()
						;


						if (is_array($defaultAction) &&
							count($defaultAction) == 1 &&
							isset($defaultAction[0])) {

							/**
							 * test, whether database content is empty
							 */
							if ($defaultAction[0]['m_module_name'] != '' &&
								$defaultAction[0]['c_controller_name'] != '' &&
								$defaultAction[0]['a_action_name'] != '') {

								/**
								 * redirect to default action
								 */
								$this->_redirect($this->_helper->url($defaultAction[0]['a_action_name'], $defaultAction[0]['c_controller_name'], $defaultAction[0]['m_module_name']));
							} else {

								/**
								 * redirect to default index
								 */
								$this->_redirect($this->_helper->url('index', 'index', 'default'));
							}
						} else {

							/**
							 * redirect to default index
							 */
							$this->_redirect($this->_helper->url('index', 'index', 'default'));
						}
					} else {

						/**
						 * add errors to form elements
						 */
						if ($authResult->getCode() == Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID) {
							$form->getElement('password')->addErrors($authResult->getMessages());

							$entityModel = Doctrine_Query::create()
								->from('Default_Model_Entity m')
								->where('m.login = ? ', array($authResult->getIdentity()))
								->limit(1)
								->execute()
								->getFirst()
							;
							if ($entityModel instanceof Default_Model_Entity &&
								$entityModel->disabled) {

								$formException = new L8M_Exception($this->view->translate('Your account has been disabled for security reasons. You\'ll receive an email with instructions on how to re-activate your account.'));
							}
						} else {
							$form->getElement('login')->addErrors($authResult->getMessages());
						}
					}
				}
			}

			/**
			 * add form decorators
			 */
			$form
				->addDecorators(array(
					new L8M_Form_Decorator_FormHasRequiredElements(),
					new L8M_Form_Decorator_HasException($formException),
					new L8M_Form_Decorator(array(
						'boxClass'=>'small l8m-model-form-base',
						'appendJsFile'=>'/js/jquery/system/model-form-base.js',
						'appendContent'=>'<ul class="iconized marginTop25"><li class="group-key"><a href="' . $this->_helper->url('retrieve-password', 'user', 'default') . '">' . $this->view->translate('Passwort vergessen?', 'de') . '</a></li><li class="group-lock"><a href="' . $this->_helper->url('enable-account', 'user', 'default') . '">' . $this->view->translate('Benutzerkonto reaktivieren?', 'de') . '</a></li></ul>'
					)),
				))
			;

			/**
			 * reder form
			 */
			$formString = $form->render();

			/**
			 * set geo position infos
			 */
			$captchaElement = $form->getElement('captcha');
			if ($captchaElement) {
				if ($form->getElement('captcha') instanceof Zend_Form_Element_Captcha) {
					$captchaId = $captchaElement->getCaptcha()->getId();
				} else
				if ($form->getElement('captcha') instanceof L8M_Form_Element_GoogleReCaptcha) {
					$captchaId = 'reCAPTCHA';
				}
				$geoCaptchaBindEnabled[$captchaId] = NULL;
				$geoCaptchaBindError[$captchaId] = NULL;
			}

			/**
			 * set view vars
			 */
			$this->view->form = $formString;
			$this->view->comingFrom = $paramComingFrom;
		}
	}
}