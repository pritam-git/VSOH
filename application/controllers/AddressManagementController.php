<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/AddressManagementController.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: AddressManagementController.php 549 2018-12-05 02:12:59Z nm $
 */

/**
 *
 *
 * AddressManagementController
 *
 *
 */
class AddressManagementController extends L8M_Controller_Action
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
			$this->_redirect($this->_helper->url('index', 'index', 'default'));
		}

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		if(!empty($loginUser->parent_user_id)){
			$this->_redirect($this->_helper->url('index', 'index', 'default'));
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
		$short = $this->_request->getParams();
		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$loginUserId = $loginUser->id;

		$subUserModel = Doctrine_Query::create()
			->from('Default_Model_Entity')
			->addWhere('parent_user_id = ?', array($loginUserId))
			->orderBy('lastname ASC')
			->execute()
		;

		$branchUserModel = Doctrine_Query::create()
			->from('Default_Model_Entity')
			->addWhere('super_parent_id = ?', array($loginUserId))
			->orderBy('lastname ASC')
			->execute()
		;

		$this->view->subUserCollection = $subUserModel;
		$this->view->branchUserCollection = $branchUserModel;
		$this->view->loginUser = $loginUser;
		$this->view->short = $short;
	}

	/**
	 * create user action.
	 *
	 * @return void
	 */
	public function createUserAction ()
	{
		$loginUser = Zend_Auth::getInstance()->getIdentity();

		if(!empty($loginUser->parent_user_id)){
			$this->_redirect($this->_helper->url('index'));
		}

		$createForm = new Default_Form_AddressManagement_CreateUser();
		$createForm->buildUp(array(), 'send');
		$createForm->setAction($this->view->url(array('module'=>'default', 'controller'=>'address-management', 'action'=>'create-user'), NULL, TRUE));
		$this->view->createForm = $createForm;

		/**
		 * form is submitted and is valid
		 */
		if ($createForm->isSubmitted() && $createForm->isValid($this->getRequest()->getPost())) {
			/**
			 * create new customer and merge with form values
			 */
			$entityModel = new Default_Model_EntityUser();

			/**
			 * merge with form values
			 */
			$formValues = $createForm->getValues();
			$formValues['login'] = $formValues['email'];
			$formValues['parent_user_id'] = $loginUser->id;

			$entityModel->merge($formValues);
			$entityModel->activated_at = date('Y-m-d H:i:s');

			//set new password
			$newPassword = L8M_Library::generatePassword(12);
			$entityModel->password = L8M_Library::generateDBPasswordHash($newPassword);

			/**
			 * add other data from parent user data.
			 */
            $entityModel->short = L8M_Library::createShort('Default_Model_Product', 'short', $formValues['email'], 120);
			$entityModel->ch_code_dara = '--';
			$entityModel->cadc = '--';
			$entityModel->company = $loginUser->company;
			$entityModel->street = $loginUser->street;
			$entityModel->zip = $loginUser->zip;
			$entityModel->city = $loginUser->city;
			$entityModel->address_line_1 = $loginUser->address_line_1;
			$entityModel->www = $loginUser->www;
			$entityModel->fax = $loginUser->fax;
			$entityModel->gl = $loginUser->gl;
			$entityModel->spoken_language = $loginUser->spoken_language;
			$entityModel->contract_type_id = $loginUser->contract_type_id;
			$entityModel->region_id = $loginUser->region_id;

			/**
			 * double-check
			 */
			if ($entityModel->isValid()) {
				/**
				 * save customer
				 */
				$entityModel->save();

				//store brands for sub-users
                $entity_id = $entityModel->id;
                $positionCount = 1;
				foreach ($loginUser->EntityM2nBrand as $brandEntityModel) {
					$entityM2nBrandModel = new Default_Model_EntityM2nBrand();
					$entityM2nBrandModel->entity_id = $entity_id;
					$entityM2nBrandModel->brand_id = $brandEntityModel->brand_id;
                    $entityM2nBrandModel->save();

                    $brandShort = $brandEntityModel->Brand->short;

                    $brandOptionModelInstance = Default_Model_BrandOptionModel::getModelByShort($brandShort);

                    if($brandOptionModelInstance) {
                        $brandOptionModelId = $brandOptionModelInstance->id;

                        $newEntityM2nBrandOptionModelInstance = new Default_Model_EntityM2nBrandOptionModel();
                        $newEntityM2nBrandOptionModelInstance->entity_id = $entity_id;
                        $newEntityM2nBrandOptionModelInstance->brand_option_model_id = $brandOptionModelId;
                        $newEntityM2nBrandOptionModelInstance->position = $positionCount;
                        $newEntityM2nBrandOptionModelInstance->save();
                        $positionCount++;

                        $entityM2nBrandOptionModelId = $newEntityM2nBrandOptionModelInstance->id;

                        $newEntityM2nBrandOptionModelValueInstance = new Default_Model_EntityM2nBrandOptionModelValues();
                        $newEntityM2nBrandOptionModelValueInstance->entity_m2n_brand_option_model_id = $entityM2nBrandOptionModelId;
                        $newEntityM2nBrandOptionModelValueInstance->is_member = 1;
                        $newEntityM2nBrandOptionModelValueInstance->save();
                    } else {
                        throw new L8M_Exception('address-management/create-user : BrandOptionModal with ' . $brandShort . ' Short was not found. Please Contect the admin.');
                    }
				}

				//send login data to the created sub-user via email.
				$dynamicVars = array(
					'BASE_URL' => L8M_Library::getSchemeAndHttpHost().'/'.$entityModel->spoken_language,
					'USER_EMAIL' => $entityModel->login,
					'PASSWORD' => $newPassword,
					'GARAGE' => $loginUser->company,
				);
				PRJ_Email::send('create_subuser', $entityModel, $dynamicVars);

				/**
				 * redirect on success
				 */
				$this->_redirect($this->_helper->url('index'));
			}
		}
	}

	/**
	 * edit user action.
	 *
	 * @return void
	 */
	public function editUserAction ()
	{
		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$entityId = $this->_request->getParam('id');
		$do = $this->_request->getParam('do');
		$entityModel = Default_Model_Entity::getModelByID($entityId);

		$flag = FALSE;
		if($loginUser->id === $entityId){
			$flag = TRUE;
		} else {
			//only allow edit with "save" option if login user is parent user of edited user.
			if($entityModel->parent_user_id == $loginUser->id && $do == 'save')
				$flag = TRUE;

			//only allow edit with "request" option if login user is super parent user of edited user.
			if($entityModel->super_parent_id == $loginUser->id && $do == 'request')
				$flag = TRUE;
		}

		if($flag){
			$editForm = new Default_Form_AddressManagement_CreateUser();
			$editForm->buildUp($entityModel->toArray(),$do);
			$editForm->setAction($this->view->url(array('module'=>'default', 'controller'=>'address-management', 'action'=>'edit-user', 'id'=>$entityId, 'do' => $do), NULL, TRUE));
			$this->view->editForm = $editForm;

			/**
			 * form is submitted and is valid
			 */
			if ($editForm->isSubmitted() && $editForm->isValid($this->getRequest()->getPost())) {
				$formData = $editForm->getValues();

				/**
				 * double-check
				 */
				if ($entityModel->isValid()) {
					if($do == 'save') {
					//save the details in `entity` table if user is sub-user (Unter-Nutzer).

						/**
						 * merge the post data with model
						 */
						$entityModel->merge($formData);
						$entityModel->login = $formData['email'];

						/**
						 * add other data from parent user data.
						 */
						$entityModel->company = $loginUser->company;
						$entityModel->street = $loginUser->street;
						$entityModel->zip = $loginUser->zip;
						$entityModel->city = $loginUser->city;
						$entityModel->address_line_1 = $loginUser->address_line_1;
						$entityModel->www = $loginUser->www;
						$entityModel->fax = $loginUser->fax;
						$entityModel->gl = $loginUser->gl;

						/**
						 * save customer
						 */
						$entityModel->save();
					} else
					if($do == 'request'){
					//send request email to the supervisor for parent-user(Stammdaten) and branch user(Filialen)

						//get salutation details
						$salutaionModel = Default_Model_Salutation::getModelByID($formData['salutation_id']);

						//prepare redirect link for email
						$urlParams = array(
							'module' => 'admin',
							'controller' => 'user',
							'action' => 'edit-request',
							'id' => $entityId
						);
						$redirectLink = L8M_Library::getSchemeAndHttpHost() . $this->_helper->url('index', 'login', 'admin', array('comingFrom'=>json_encode($urlParams)));

						//save the request dat ain serialized array
						$changedData = $formData;
						unset($changedData[$editForm->getFormSubmittedIdentifier('formUserCreate')]);
						$entityModel->edit_request_data = serialize($changedData);
						$entityModel->save();

						$formData['ch_code'] = $entityModel->ch_code;
						$formData['cadc'] = $entityModel->cadc;
						$formData['user_email'] = $formData['email'];
						$formData['salutation_name'] = $salutaionModel->name;
						$formData['redirect_link'] = $redirectLink;

						//send email to inform user that request is sent.
						PRJ_Email::send('request_sent', $entityModel, array());

						//get all supervisors.
						$supervisorModel = Doctrine_Query::create()
							->from('Default_Model_EntitySupervisor')
							->orderBy('id ASC')
						;

						if ($supervisorModel->count() > 0) {
							//send email to all supervisor.
							foreach ($supervisorModel->execute() as $supervisorValue) {
								//send change request to the supervisor.
								PRJ_Email::send('user_edit_request', $supervisorValue, $formData);
							}
						}
					} else {}

					/**
					 * redirect to address management index page
					 */
					$this->_redirect($this->_helper->url('index'));
				}
			}
		} else {
			$this->_redirect($this->_helper->url('index'));
		}
	}

	/**
	 * delete user action.
	 *
	 * @return void
	 */
	public function deleteUserAction ()
	{
		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$entityId = $this->_request->getParam('id');
		$entityModel = Default_Model_Entity::getModelByID($entityId);
		if (!empty($entityModel) && $entityModel->parent_user_id == $loginUser->id) {
			//delete all relation for the entity_id.
			$relationAction = new PRJ_Entity_Delete_BeforePreDelete();
			$relationAction->beforePreDelete($entityModel);

			//delete entity from DB.
			$entityModel->hardDelete();
			$this->_redirect($this->_helper->url('index'));
		} else {
			$this->_redirect($this->_helper->url('index'));
		}
	}
}