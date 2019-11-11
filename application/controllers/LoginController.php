<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/LoginController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: LoginController.php 398 2015-09-02 09:15:08Z sl $
 */

/**
 *
 *
 * LoginController
 *
 *
 */
class LoginController extends L8M_Controller_Action
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
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$loginUser = Zend_Auth::getInstance()->getIdentity();
			$this->_redirect($this->_helper->url('index', 'index', 'default',array('lang'=>$loginUser->spoken_language)));
		}

		/**
		 * retrieve params
		 */
		$paramComingFrom = $this->getRequest()->getParam('comingFrom');

		/**
		 * so we make sure that authResult is NULL
		 */
		$authResult = NULL;
		$isRegisteredInFacebook = FALSE;

		/**
		 * ALL FACEBOOK START
		 */
		if (L8M_Config::getOption('facebook.enabled')) {
			$loginFacebookUrl = PRJ_Facebook::getLoginUrl('email', L8M_Library::getSchemeAndHttpHost() . $this->_helper->url('index', 'login', 'default', array('comingFrom'=>$paramComingFrom)));
			if (PRJ_Facebook::factory()->getUser()){
				$isRegisteredInFacebook = PRJ_Facebook::isRegistered();
				if ($isRegisteredInFacebook === TRUE){

					/**
					 * login automaticly
					 */
					$authResult = PRJ_Facebook::login();
				} else {

					/**
					 * redirect to registration
					 */
					$this->_redirect($this->_helper->url('register', 'user', 'default'));
				}
			}
		}

		/**
		 * login form
		 */
		$form = new Default_Form_User_Login();
		$form
			->addDecorators(array(
				new L8M_Form_Decorator_Form_Small(),
				new L8M_Form_Decorator_FormHasRequiredElements(),
			))
			->setAction($this->_helper->url('index', 'login', 'default', array('comingFrom'=>$paramComingFrom)))
		;
		$form2 = new Default_Form_User_Login2();

		if (($form->isSubmitted() && $form->isValid($this->getRequest()->getParams())) ||
			($form2->isSubmitted() && $form2->isValid($this->getRequest()->getParams())) ||
			(L8M_Config::getOption('facebook.enabled') && $isRegisteredInFacebook)) {

			if ($form2->isSubmitted() && $form2->isValid($this->getRequest()->getParams())) {
				$formValues = $form2->getValues();
				$formValues['login'] = $formValues['login2'];
				$formValues['password'] = $formValues['password2'];
			} else {
				$formValues = $form->getValues();
			}

			/**
			 * authResult
			 */
			if ($authResult !== NULL &&
				$authResult instanceof Zend_Auth_Result &&
				L8M_Config::getOption('facebook.enabled') &&
				$isRegisteredInFacebook) {

				/**
				 * already tried to login via PRJ_Facebook
				 */
			} else {
				$authResult = L8M_Controller_Plugin_AuthControlled::login($formValues['login'], $formValues['password']);
			}

			/**
			 * user could be logged in
			 */
			if ($authResult->isValid()) {

				$user = Zend_Auth::getInstance()->getIdentity();
				$brandSession = new Zend_Session_Namespace('brand');
				if (isset($brandSession->id)) {
					unset($brandSession->id);
				}

				/**
				 * redirect to the change password page if user will login first time.
				 */
				if ($user->is_first_login) {
					$this->_redirect($this->_helper->url('settings', 'user', 'default', array('lang'=>$user->spoken_language,'comingFrom'=>$paramComingFrom)));
				}

				/**
				 * redirect if coming from
				 */
				if ($paramComingFrom) {
					$this->_redirect($this->view->url(json_decode($paramComingFrom,TRUE),NULL,TRUE));
				}

				/**
				 * query the default action
				 */
				$defaultAction = Doctrine_Query::create()
					->from('Default_Model_Action a')
					->select('a.name AS action_name, c.name AS controller_name, m.name AS module_name')
					->leftJoin('a.Controller AS c ON a.controller_id=c.id')
					->leftJoin('c.Module AS m ON c.module_id=m.id')
					->where('a.resource = ?', $user->Role->default_action_resource)
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
						$this->_redirect($this->_helper->url($defaultAction[0]['a_action_name'], $defaultAction[0]['c_controller_name'], $defaultAction[0]['m_module_name'],array('lang'=>$user->spoken_language)));
					} else {

						/**
						 * redirect to default index
						 */
						$this->_redirect($this->_helper->url('index', 'index', 'default',array('lang'=>$user->spoken_language)));
					}
				} else {

					/**
					 * redirect to default index
					 */
					$this->_redirect($this->_helper->url('index', 'index', 'default',array('lang'=>$user->spoken_language)));
				}
		 	} else {

				if ($authResult->getCode() == Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID) {
					$form->getElement('password')->addErrors($authResult->getMessages());
				} else {
					$form->getElement('login')->addErrors($authResult->getMessages());
				}
			}
		}

		/**
		 * set view vars
		 */
		if (L8M_Config::getOption('facebook.enabled')) {
			$this->view->loginFacebookUrl = $loginFacebookUrl;
		}
		$this->view->form = $form;
		$this->view->comingFrom = $paramComingFrom;
		$this->view->registrationEnabled = $this->getOption('authentication.registration.enabled');
	}
}