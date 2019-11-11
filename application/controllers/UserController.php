<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/UserController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: UserController.php 451 2015-11-04 10:34:40Z nm $
 */

/**
 *
 *
 * UserController
 *
 *
 */
class UserController extends L8M_Controller_Action
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
		$calledForResource = L8M_Acl_CalledFor::resource();
		if ($this->getOption('authentication.registration.enabled') == FALSE &&
			($calledForResource != 'default.user.enable-account' && $calledForResource != 'default.user.reset-password' && $calledForResource != 'default.user.retrieve-password')) {

			$this->_redirect($this->_helper->url('index', 'index', 'default'));
		}

		$this->view->layout()->headline = $this->view->translate('User-Account');

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
	 * Default action. Redirects to login if not logged in.
	 *
	 * @return void
	 */
	public function indexAction()
	{
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			$this->_redirect($this->_helper->url('index', 'login', 'default'));
		} else {
			$this->_redirect($this->_helper->url('accountoverview', 'user', 'default'));
		}
	}

	/**
	 * AccountOverview Action
	 */
	public function accountoverviewAction()
	{
		$this->_redirect($this->_helper->url('index', 'address-management', 'default'));

// 		$this->view->layout()->robots = 'noindex, nofollow';
// 		$this->view->layout()->subheadline = $this->view->translate('Account Overview');

// 		if (Zend_Auth::getInstance()->hasIdentity()) {
// 			$this->view->entity = Zend_Auth::getInstance()->getIdentity();
// 		} else {
// 			throw new L8M_Exception('This should never happen. No identity.');
// 		}

	}

	/**
	 * addressbook Action
	 */
	public function addressbookAction()
	{
		$this->view->layout()->robots = 'noindex, nofollow';
		$this->view->layout()->subheadline = $this->view->translate('Addressbook');

		$user = Zend_Auth::getInstance()->getIdentity();

		$userModel = Doctrine_Query::create()
			->from('Default_Model_EntityUser r')
			->addWhere('r.id = ? ', array($user->id))
			->execute()
			->getFirst()
		;

		if ($this->_request->getParam('do')) {
			$do = $this->_request->getParam('do');

			$this->view->setting = $do;

			$formChangeAddress = new Default_Form_Customer_ChangeAddress();
			$formChangeAddress->addDecorator(new L8M_Form_Decorator_FormHasRequiredElements());

			$formChangeAddress
				->setAction($this->_helper->url('addressbook', 'user', 'default', array('do'=>$do)))
			;

			if ($formChangeAddress->isSubmitted()) {
				if ($formChangeAddress->isValid($this->getRequest()->getParams())) {
					if ($do == 'changeBillingAddress') {
						$dataArray['billing_firstname'] = $formChangeAddress->getValue('firstname');
						$dataArray['billing_lastname'] = $formChangeAddress->getValue('lastname');
						$dataArray['billing_street'] = $formChangeAddress->getValue('street');
						$dataArray['billing_street_number'] = $formChangeAddress->getValue('street_number');
						$dataArray['billing_address_line_1'] = $formChangeAddress->getValue('address_line_1');
						$dataArray['billing_address_line_2'] = $formChangeAddress->getValue('address_line_2');
						$dataArray['billing_zip'] = $formChangeAddress->getValue('zip');
						$dataArray['billing_city'] = $formChangeAddress->getValue('city');

						$countryModel = Doctrine_Query::create()
							->from('Default_Model_Country c')
							->addWhere('c.id = ? ', array($formChangeAddress->getValue('country_id')))
							->execute()
							->getFirst()
						;

						$dataArray['billing_country'] = $countryModel->name;

						$userModel->merge($dataArray);

						/**
						 * save model
						 */
						$userModel->save();

						$this->_redirect($this->_helper->url('addressbook', 'user', 'default', array('do'=>'changed')));
					} else
					if ($do == 'changeDeliveryAddress') {
						$dataArray['firstname'] = $formChangeAddress->getValue('firstname');
						$dataArray['lastname'] = $formChangeAddress->getValue('lastname');
						$dataArray['street'] = $formChangeAddress->getValue('street');
						$dataArray['street_number'] = $formChangeAddress->getValue('street_number');
						$dataArray['address_line_1'] = $formChangeAddress->getValue('address_line_1');
						$dataArray['address_line_2'] = $formChangeAddress->getValue('address_line_2');
						$dataArray['zip'] = $formChangeAddress->getValue('zip');
						$dataArray['city'] = $formChangeAddress->getValue('city');

						$countryModel = Doctrine_Query::create()
							->from('Default_Model_Country c')
							->addWhere('c.id = ? ', array($formChangeAddress->getValue('country_id')))
							->execute()
							->getFirst()
						;

						$dataArray['country'] = $countryModel->name;
					}

					if ($userModel &&
						$dataArray) {

						$userModel->merge($dataArray);

						/**
						 * save model
						 */
						$userModel->save();

						$this->_redirect($this->_helper->url('addressbook', 'user', 'default', array('do'=>'changed')));
					}
				}
			}
			$this->view->changeAddressForm = $formChangeAddress;
		}

		$this->view->userModel = $userModel;
		$this->view->billing_country_name = $this->view->userModel['billing_country'];
		$this->view->country_name = $this->view->userModel['country'];

		$billingCountryModel = Doctrine_Query::create()
			->from('Default_Model_Country r')
			->addWhere('r.name_local = ? ', array($this->view->userModel['billing_country']))
			->execute()
			->getFirst()
		;

		$deliveryCountryModel = Doctrine_Query::create()
			->from('Default_Model_Country r')
			->addWhere('r.name_local = ? ', array($this->view->userModel['country']))
			->execute()
			->getFirst()
		;

		if ($billingCountryModel) {
			$this->view->billing_country_id = $billingCountryModel->id;
		}
		if ($deliveryCountryModel) {
			$this->view->country_id = $deliveryCountryModel->id;
		}
	}

	/**
	 * orders Action
	 */
	public function ordersAction()
	{
		$this->_redirect($this->_helper->url('index', 'address-management', 'default'));

// 		$this->view->layout()->robots = 'noindex, nofollow';

// 		if ($this->_request->getParam('id')) {

// 			$this->view->layout()->subheadline = $this->view->translate('Order Details');

// 			$id = $this->_request->getParam('id');
// 			$this->view->orderDetail = TRUE;

// 			$user = Zend_Auth::getInstance()->getIdentity();
// 			$userId = $user->id;

// 			$userOrderItemsModel = Doctrine_Query::create()
// 				->from('Default_Model_ProductOrder p')
// 				->addWhere('p.entity_user_id = ? ', array($userId))
// 				->addWhere('p.id = ? ', array($id))
// 				->execute()
// 				->getFirst()
// 			;

// 			if (!$userOrderItemsModel) {
// 				$this->_redirect($this->_helper->url('order', 'user', 'default'));
// 			}

// 			$orderItemsCollection = Doctrine_Query::create()
// 				->from('Default_Model_ProductOrderItem r')
// 				->addWhere('r.product_order_id = ? ', array($id))
// 				->execute()
// 			;

// 			$orderModel = Doctrine_Query::create()
// 				->from('Default_Model_ProductOrder r')
// 				->addWhere('r.id = ? ', array($id))
// 				->execute()
// 				->getFirst()
// 			;

// 			$paymentMethodModel = Doctrine_Query::create()
// 				->from('Default_Model_PaymentService r')
// 				->addWhere('r.id = ? ', array($orderModel->payment_service_id))
// 				->execute()
// 				->getFirst()
// 			;

// 			$billingCountryModel = Doctrine_Query::create()
// 				->from('Default_Model_Country r')
// 				->addWhere('r.id = ? ', array($orderModel->billing_country_id))
// 				->execute()
// 				->getFirst()
// 			;

// 			$deliveryCountry = Doctrine_Query::create()
// 				->from('Default_Model_Country r')
// 				->addWhere('r.id = ? ', array($orderModel->delivery_country_id))
// 				->execute()
// 				->getFirst()
// 			;

// 			$this->view->orderModel = $orderModel;
// 			$this->view->orderItemsCollection = $orderItemsCollection;
// 			$this->view->paymentMethod = $paymentMethodModel->name;
// 			$this->view->billingCountry = $billingCountryModel->name_local;
// 			$this->view->deliveryCountry = $deliveryCountry->name_local;
// 			$this->view->userId = $id;

// 		} else {

// 			$this->view->layout()->subheadline = $this->view->translate('Orders Overview');

// 			$this->view->orderDetail = FALSE;

// 			$user = Zend_Auth::getInstance()->getIdentity();
// 			$userId = $user->id;

// 			$userOrderItemsCollection = Doctrine_Query::create()
// 				->from('Default_Model_ProductOrder p')
// 				->addWhere('p.entity_user_id = ?', array($userId))
// 				->orderBy('p.created_at DESC')
// 				->execute()
// 			;

// 			$this->view->userOrderItemsCollection = $userOrderItemsCollection;
// 			$this->view->userId = NULL;

// 		}
	}

	/**
	 * coupons Action
	 */
	public function couponsAction()
	{
		$this->_redirect($this->_helper->url('index', 'address-management', 'default'));

// 		$this->view->layout()->robots = 'noindex, nofollow';
// 		$this->view->layout()->subheadline = $this->view->translate('Coupon Overview');

// 		$userModel = Zend_Auth::getInstance()->getIdentity();

// 		$couponCollection = Doctrine_Query::create()
// 			->from('Default_Model_Coupon p')
// 			->addWhere('p.entity_user_id = ? ', array($userModel->id))
// 			->orderBy('p.created_at ASC')
// 			->execute()
// 		;
// 		$this->view->couponCollection = $couponCollection;
	}

	/**
	 * Retrieve Password Action.
	 *
	 * @return void
	 */
	public function retrievePasswordAction()
	{
		$this->view->layout()->robots = 'noindex, nofollow';
		$this->view->layout()->subheadline = $this->view->translate('Retrieve Password');

		$form = new Default_Form_User_RetrievePassword();
		$form
			->addDecorators(array(
				new L8M_Form_Decorator_Form_Small(),
				new L8M_Form_Decorator_FormHasRequiredElements(),
			))
		;

		if ($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getPost())) {

			$userModel = Doctrine_Query::create()
				->from('Default_Model_Entity m')
				->addWhere('m.login = ? ', array($form->getValue('email')))
				->execute()
				->getFirst()
			;
			if ($userModel) {
				$userModel->password_reset_hash = md5(L8M_Library::generatePassword(12));
				$userModel->save();

				//create dynamic variable array for email template.
				$dynamicVars = array(
					'LINK' => L8M_Library::getSchemeAndHttpHost() . $this->_helper->url('reset-password', 'user', 'default', array('lang'=>$userModel->spoken_language, 'login'=>$userModel->login, 'hash'=>$userModel->password_reset_hash))
				);

				//send email.
				PRJ_Email::send('retrieve_password', $userModel, $dynamicVars);
			}
		} else {
			$this->view->form = $form;
		}
	}

	/**
	 * Enable a disabled account Action-
	 * @return void
	 */
	public function enableAccountAction()
	{
		$this->view->layout()->robots = 'noindex, nofollow';
		$this->view->layout()->subheadline = $this->view->translate('Enable Account');

		$paramHash = $this->_request->getParam('hash');
		$paramLogin = $this->_request->getParam('login');

		$entityModel = Doctrine_Query::create()
			->from('Default_Model_Entity m')
			->addWhere('m.login = ? ', array($paramLogin))
			->execute()
			->getFirst()
		;

		if ($entityModel instanceof Default_Model_Entity &&
			$paramHash) {

			if ($entityModel->disabled_reset_hash == $paramHash) {
				$entityModel->merge(array(
					'password_attempt'=>0,
					'disabled_reset_hash'=>NULL,
					'disabled'=>FALSE,
				));
				$entityModel->save();
			} else
			if ($entityModel->disabled &&
				$entityModel->disabled_reset_hash) {

				$entityModel->disableBecauseOfSecurityReasons();
				$this->_helper->viewRenderer('enable-account-email');
			}
		} else {
			$form = new Default_Form_User_Enable();
			$form->addDecorator(new L8M_Form_Decorator_FormHasRequiredElements());

			$form
				->setAction($this->_helper->url('enable-account'))
			;

			$this->_helper->viewRenderer('enable-account-form');
			$this->view->form = $form;

			/**
			 * form is submitted
			 */
			if ($form->isSubmitted() &&
				$form->isValid($this->getRequest()->getPost())) {

				$formValues = $form->getValues();

				$entityModel = Doctrine_Query::create()
					->from('Default_Model_Entity m')
					->addWhere('m.login = ? ', array($formValues['email']))
					->execute()
					->getFirst()
				;

				if ($entityModel &&
					$entityModel->disabled &&
					$entityModel->disabled_reset_hash) {

					$entityModel->disableBecauseOfSecurityReasons();
				}

				$this->_helper->viewRenderer('enable-account-email');
			}
		}
	}

	/**
	 * Retrieve Password Action.
	 *
	 * @return void
	 */
	public function resetPasswordAction()
	{

		$this->view->layout()->subheadline = $this->view->translate('Reset Password');

		$passwordResetHash = $this->_request->getParam('hash');
		$login = $this->_request->getParam('login');

		$userModel = Doctrine_Query::create()
			->from('Default_Model_Entity m')
			->addWhere('m.login = ? ', array($login))
			->addWhere('m.password_reset_hash = ? ', array($passwordResetHash))
			->execute()
			->getFirst()
		;
		if ($userModel) {
			$newPassword = L8M_Library::generatePassword(12);
			$userModel->password = L8M_Library::generateDBPasswordHash($newPassword);
			$userModel->password_reset_hash = NULL;
			$userModel->save();

			//create dynamic variable array for email template.
			$dynamicVars = array(
				'PASSWORD' => $newPassword
			);

			//send email.
			PRJ_Email::send('reset_password', $userModel, $dynamicVars);
		}
	}

	/**
	 * RegistrierungAction.
	 *
	 * @return void
	 */
	public function registerAction()
	{
		$this->_redirect($this->_helper->url('index', 'index', 'default'));

		$this->view->layout()->subheadline = $this->view->translate('Register');

		/**
		 * If Facebook enabled and a Facbook-Entity detected start register with facebook
		 */
		if (L8M_Config::getOption('facebook.enabled') &&
			PRJ_Facebook::factory()->getUser()) {

			$form = new Default_Form_User_FacebookRegister();
		} else {
			$form = new Default_Form_User_Register();
		}

		/**
		 * default form decorator-set
		 */
		$form->addDecorator(new L8M_Form_Decorator_FormHasRequiredElements());

		$form
			->setAction($this->_helper->url('register'))
		;

		/**
		 * form is submitted
		 */
		if ($form->isSubmitted()) {

			/**
			 * add validator for password_repeat, set token to value of password
			 * field
			 */
			$form->getElement('password_repeated')
				->addValidator(new Zend_Validate_Identical(array(
					'token'=>$this->getRequest()->getParam('password'),
				)))
			;

			/**
			 * form is valid
			 */
			if ($form->isValid($this->getRequest()->getPost())) {

				/**
				 * create new customer and merge with form values
				 */
				$customer = new Default_Model_EntityUser();

				/**
				 * merge with form values
				 */
				$formValues = $form->getValues();
				$formValues['login'] = $formValues['email'];
				if ($form instanceof Default_Form_User_FacebookRegister) {
					$formValues['activated_at'] = date('Y-m-d H:i:s');
				}
				$customer->merge($formValues);
				$customer->password = L8M_Library::generateDBPasswordHash($form->getValue('password'));

				$mergeMoreDatas = array(
					'firstname',
					'lastname',
					'street',
					'street_number',
					'adress_line_1',
					'adress_line_2',
					'zip',
					'city',
					'country_id',
				);

				if (L8M_Config::getOption('shop.company.enabled') == TRUE) {
					$mergeMoreDatas[] = 'company';
				}

				foreach ($mergeMoreDatas as $dataItem) {
					$entityDataItem = 'billing_' . $dataItem;
					$customer->merge(array(
							$entityDataItem=>$formValues[$dataItem],
					));
				}

				/**
				 * double-check
				 */
				if ($customer->isValid()) {

					/**
					 * save customer
					 */
					$customer->save();

					/**
					 * create activation
					 */
					$activation = Default_Service_Activation::fromDoctrineRecord($customer);
					$activation->redirect_url = $this->_helper->url(
						'account-activated',
						'user',
						'default',
						array(
							'login'=>$customer->login,
						)
					);

					/**
					 * save activation
					 */
					$activation->save();

					/**
					 * email recipient
					 */
					$customerEmail = $customer->email;
					$customerName = $customer->firstname
								  . ' '
								  . $customer->lastname
					;
					$customerName = trim($customerName);

					/**
					 * email
					 */
					$email = L8M_MailV2::factory('register');
					$email
						->setFrom(L8M_Config::getOption('resources.mail.defaultFrom.email'), L8M_Config::getOption('resources.mail.defaultFrom.name'))
						->addTo($customerEmail, $customerName)
					;

					/**
					 * header
					 */
					$content = L8M_MailV2_Part::factory('header', $email);
					$content
						->setDynamicVar('HEADER', $this->view->prjEmailHeader())
					;
					$email->addPart($content);

					/**
					 * content
					 */
					$content = L8M_MailV2_Part::factory('register', $email);
					$content
						->setDynamicVar('FIRSTNAME', $customer->firstname)
						->setDynamicVar('LASTNAME', $customer->lastname)
						->setDynamicVar('ACTIVATIONCODE', L8M_Library::getSchemeAndHttpHost() . $activation->getLink())
					;
					$email->addPart($content);

					/**
					 * data
					 */
					$data = L8M_MailV2_Part_Data::fromForm(
						$form,
						array(
							'exclude'=>array(
								'password',
								'password_repeated',
							),
						)
					);

					$data->setEmailTemplatePartShort('register_data');

					$data
						->setHeadline($this->view->translate('Your Registration Data'))
						->setContent($this->view->translate('Please note the following data that you used to register with us.'))
					;
					$email->addPart($data);

					/**
					 * footer
					 */
					$content = L8M_MailV2_Part::factory('footer', $email);
					$email->addPart($content);

					/**
					 * send email
					 */
					try {
						$email->send();
					} catch (L8M_Mail_Exception $exception) {

					}

					/**
					 * redirect on success
					 */
					$this->_redirect($this->_helper->url('registration-complete'));
				}
			}

		}

		$this->view->form = $form;
	}

	/**
	 * RegistrationComplete action.
	 *
	 * @return void
	 */
	public function registrationCompleteAction()
	{
		$this->view->layout()->robots = 'noindex, nofollow';
		$this->view->layout()->subheadline = $this->view->translate('Registration Complete');
	}

	/**
	 * AccountActivated action.
	 *
	 * @return void
	 */
	public function accountActivatedAction()
	{
		$this->view->layout()->robots = 'noindex, nofollow';
		$this->view->layout()->subheadline = $this->view->translate('Account Activated');
	}

	/**
	 * personalData action.
	 *
	 * @return void
	 */
	public function personalDataAction()
	{
		$this->_redirect($this->_helper->url('index', 'address-management', 'default'));

// 		$this->view->layout()->robots = 'noindex, nofollow';
// 		$this->view->layout()->subheadline = $this->view->translate('Persönliche Daten', 'de');

// 		$this->view->change = NULL;

// 		$form = new Default_Form_User_PersonalData();
// 		$form->addDecorators(array(
// 			new PRJ_Form_Decorator_PersonalData(),
// 			new L8M_Form_Decorator_FormHasRequiredElements(),
// 		));

// 		$form->setAction($this->_helper->url('personal-data'));

// 		$this->view->form = $form;

// 		if ($form->isSubmitted() &&
// 			$form->isValid($this->getRequest()->getPost())) {

// 			$formValues = $form->getValues();
// 			if (Zend_Auth::getInstance()->hasIdentity()) {
// 				$entity = Zend_Auth::getInstance()->getIdentity();
// 			} else {
// 				throw new L8M_Exception('This should never happen. No identity.');
// 			}

// 			$entity->street = $formValues['street'];
// 			$entity->street_number = $formValues['street_number'];
// 			$entity->zip = $formValues['zip'];
// 			$entity->city = $formValues['city'];
// 			$entity->country_id = $formValues['country_id'];
// 			$entity->billing_street = $formValues['street'];
// 			$entity->billing_street_number = $formValues['street_number'];
// 			$entity->billing_zip = $formValues['zip'];
// 			$entity->billing_city = $formValues['city'];
// 			$entity->billing_country_id = $formValues['country_id'];
// 			$entity->phone = $formValues['phone'];
// 			$entity->fax = $formValues['fax'];
// 			$entity->www = $formValues['www'];

// 			$entity->save();
// 			$this->view->change = TRUE;

// 		}

	}

	/**
	 * settings action.
	 * @return void
	 * @throws L8M_Exception
	 */
	public function settingsAction()
	{
 		$this->view->layout()->robots = 'noindex, nofollow';
 		$this->view->layout()->subheadline = $this->view->translate('Persönliche Einstellungen', 'de');

 		$paramComingFrom = $this->getRequest()->getParam('comingFrom');

 		$form = new Default_Form_Customer_ChangePassword();
 		$form
 			->addDecorators(array(
 				new PRJ_Form_Decorator_AddHeadline($this->view->translate('Passwort ändern', 'de')),
 				new L8M_Form_Decorator_FormHasRequiredElements(),
 			))
 			->setAction($this->_helper->url('settings', 'user', 'default', array('comingFrom'=>$paramComingFrom)))
 		;

 		$this->view->form = $form;

 		if ($form->isSubmitted()) {

 			if(Zend_Auth::getInstance()->hasIdentity()) {
 				$entity = Zend_Auth::getInstance()->getIdentity();
 			} else {
 				throw new L8M_Exception('This should never happen. No Identity.');
 			}

 			/**
 			 * add validator for password_repeat, set token to value of password
 			 * field
 			 */
 			if ($form->getElement('password_repeat')) {
 				$form->getElement('password_repeat')
 					->addValidator(new Zend_Validate_Identical(array(
 						'token'=>$this->getRequest()->getParam('password'),
 				)))
 				;
 			}

 			/**
 			 * add validator for old_password
 			 */
 			if ($form->getElement('old_password')) {
 				$form->getElement('old_password')
 					->addValidator(new L8M_Validate_IdenticalPassword(array(
 						'token'=>$entity->password,
 				)))
 				;
 			}

 			if ($form->isValid($this->getRequest()->getParams())) {

 				if (L8M_Library::checkPasswordHash($entity->password, $form->getValue('old_password'))) {

 					$dataArray['password'] = md5($form->getValue('password'));
 					$entity->merge($dataArray);

 					/*
 					 * set is_first_login value to false
 					 * to indicate that user is already done reset password process.
 					 */
 					$entity->is_first_login = FALSE;

 					/**
 					 * save model
 					*/
 					$entity->save();

 					/**
 					 * redirect if coming from
 					 */
 					if ($paramComingFrom) {
 						$this->_redirect($this->view->url(json_decode($paramComingFrom,TRUE),NULL,TRUE));
 					}

 					$this->_redirect($this->_helper->url('index', 'index', 'default'));
 				}
 			}
 		}
	}
}