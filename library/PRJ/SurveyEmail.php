<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/SurveyEmail.php
 * @author     Nimisha Vyas <nimisha.vyas@bcssarl.com>
 * @version    $Id: SurveyEmail.php 16 2019-10-24 15:49:56Z sl $
 */

/**
 *
 *
 * PRJ_SurveyEmail
 *
 *
 */

class PRJ_SurveyEmail
{
	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * get all lists of selection filters.
	 *
	 * @param $defaultModel
	 * @param $M2nModel
	 * @param $protocol
	 * @param $protocolId
	 * @return bool
	 */
	public static function getSelectionLists($defaultModel,$M2nModel,$protocol,$protocolId) {
		/**
		 * view from MVC
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		//get protocol model by id.
		$protocolModel = $defaultModel::getModelByID($protocolId);

		//change published field for "date" model
		// if($protocol === 'Dates' || $protocol === 'RegionDate'){
		// 	$published = 'p.publish_de = TRUE OR p.publish_fr = TRUE';
		// 	$isPublished = $protocolModel->publish_de || $protocolModel->publish_fr  ? TRUE : FALSE;
		// } else {
		// 	$published = 'p.published = TRUE';
		// 	$isPublished = $protocolModel->published ? TRUE : FALSE;
		// }

		//if protocol is published or not.
		//if($isPublished) {
			$protocolQuery = Doctrine_Query::create()
				->from('Default_Model_Entity m')
				->innerJoin('m.Department d')
				->innerJoin('d.'.$M2nModel.' pd')
				->innerJoin('pd.'.$protocol.' p')
				->addWhere('p.id = ?',array($protocolId))
				//->addWhere($published)
				->orderBy('m.id ASC')
			;

			if(isset($protocolModel->region_id))
				$protocolQuery->addWhere('m.region_id = ?',array($protocolModel->regio_id));

			//redirect to index page if department relation not found.
			$rowCount = $protocolQuery->count();
			if(empty($rowCount)){
				return FALSE;
			}

			$language = array();
			$contractTypeIds = array();
			$regionIds = array();
			$brandIds = array();
			$departmentIds = array();
			foreach ($protocolQuery->execute() as $protocolValue) {
				//prepare array for spoken language
				if (!empty($protocolValue->spoken_language) && !in_array(strtoupper($protocolValue->spoken_language), $language)) {
					array_push($language, strtoupper($protocolValue->spoken_language));
				}

				//prepare array for contract type
				if (!empty($protocolValue->contract_type_id) && !in_array($protocolValue->contract_type_id, $contractTypeIds)) {
					array_push($contractTypeIds, $protocolValue->contract_type_id);
				}

				//prepare array for regions
				if (!empty($protocolValue->region_id) && !in_array($protocolValue->region_id, $regionIds)) {
					array_push($regionIds, $protocolValue->region_id);
				}

				//prepare array for brands
				foreach ($protocolValue->EntityM2nBrand as $brandValue) {
					if (!empty($brandValue->brand_id) && !in_array($brandValue->brand_id, $brandIds)) {
						array_push($brandIds, $brandValue->brand_id);
					}
				}

				//prepare array for departments
				if (!empty($protocolValue->department_id) && !in_array($protocolValue->department_id, $departmentIds)) {
					array_push($departmentIds, $protocolValue->department_id);
				}
			}

			$viewFromMVC->language = $language;
			$viewFromMVC->contractType = Default_Model_ContractType::getContractType($contractTypeIds);
			$viewFromMVC->regions = Default_Model_Region::getRegions($regionIds);
			$viewFromMVC->brands = Default_Model_Brand::getBrands($brandIds);
			$viewFromMVC->department = Default_Model_Department::getDepartment($departmentIds);
			$viewFromMVC->published = true;
		// } else {
		// 	$viewFromMVC->published = false;
		// }

		return TRUE;
	}

	/**
	 * get all users form selection and send emails to them.
	 *
	 * @param $M2nModel
	 * @param $protocol
	 * @param $requestParams
	 * @param $controller
	 * @param $short
	 * @param null $regionId
	 * @return array
	 */
	public static function processSurveyEmail($M2nModel,$protocol,$requestParams,$controller,$short,$regionId = NULL) {
		//view from MVC
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		//change published field for "date" model
		// if($protocol === 'Dates' || $protocol === 'RegionDate'){
		// 	$published = 'p.publish_de = TRUE OR p.publish_fr = TRUE';
		// } else {
		// 	$published = 'p.published = TRUE';
		// }

		$protocolId = $requestParams['id'];
		$totalStep = (int)$requestParams['totalStep'];
		$rowCount = (int)$requestParams['rowCount'];
		$step = (int)$requestParams['step'];
		$lang = $requestParams['selectedLang'];
		$contractTypeIds = $requestParams['contractTypeIds'];
		$regionIds = $requestParams['regionIds'];
		$brandIds = $requestParams['brandIds'];
		$departmentIds = $requestParams['departmentIds'];
		$start = ($step - 1) * L8M_Config::getOption('l8m.news.maxPerPage');
		$regionM2NModelName = $protocol . 'M2nRegion';
		$protocolQuery = Doctrine_Query::create()
			->from('Default_Model_Entity m')
			->innerJoin('m.Department d')
			->innerJoin('m.EntityM2nBrand b')
			->innerJoin('d.'.$M2nModel.' pd')
			->innerJoin('pd.'.$protocol.' p')
//			->offset($start)
//			->limit(L8M_Config::getOption('l8m.news.maxPerPage'))
		;

		if (class_exists("Default_Model_" . $regionM2NModelName)) {
			$protocolQuery->innerJoin('p.' . $regionM2NModelName . ' ps')
				->addWhere('ps.region_id = m.region_id')
			;
		} else {
			if (class_exists("Default_Model_" . $protocol)) {
				$className = "Default_Model_" . $protocol;
				$dummyInstance = new $className;
				if(isset($dummyInstance->region_id)) {
					$protocolQuery->innerJoin('p.Region s')
						->addWhere('s.id = m.region_id')
					;
				}
			}
		}
		$protocolQuery->addWhere('p.id = ?', array($protocolId))
			// ->addWhere($published)
			->orderBy('m.id ASC')
		;

		//create where clause possible combinations
		$arrayCombinations = PRJ_Library::arrayCombinations(array($lang, $contractTypeIds, $regionIds, $brandIds, $departmentIds));
		foreach ($arrayCombinations as $combinationValue) {
			if ($combinationValue === reset($arrayCombinations)) {
				$protocolQuery->addWhere('UPPER(m.spoken_language) = ? AND m.contract_type_id = ? AND m.region_id = ? AND b.brand_id = ? AND m.department_id = ?', $combinationValue);
			} else {
				$protocolQuery->orWhere('UPPER(m.spoken_language) = ? AND m.contract_type_id = ? AND m.region_id = ? AND b.brand_id = ? AND m.department_id = ?', $combinationValue);
			}
		}

		$protocolPager = new Doctrine_Pager($protocolQuery, $step, L8M_Config::getOption('l8m.news.maxPerPage'));
		$resultCollection = $protocolPager->execute();
		$resultCount = $resultCollection->count();

		$surveyShort = base64_decode($short);
		$surveyModel = Default_Model_Survey::getModelByShort($surveyShort);

		$usersId = array();
		if ($resultCount > 0) {
			//send email to all users.
			$count = $surveyModel->sent_mail_count;
			foreach ($resultCollection as $entityModel) {
				if($entityModel->login == 'user-' . $entityModel->id . '@hahn-media.ch'){
					$entityModel->email = "testar@hahn-media.ch";
				}

				$usersId[] = $entityModel->id;

				$surveyUrl = L8M_Library::getSchemeAndHttpHost() . $viewFromMVC->url(array('module' => 'default', 'controller' => $controller, 'action' => 'index', 'survey' => $short, 'entity' => base64_encode($entityModel->short)), NULL, TRUE);
				$emailSubject = $viewFromMVC->translate('VSOH') . ' - ' . $viewFromMVC->surveyTitle;

				//send emails to user
				$sent = PRJ_Email::send(
					'survey_email',
					$entityModel,
					array(
						'SURVEY_URL' => $surveyUrl
					),
					FALSE,
					$emailSubject
				);
				if($sent) {
					$count++;
				}
			}
		}

		$surveyModel->sent_mail_count = $count;
		$surveyModel->save();

		// Get sub-users
		// $subUserModel = Doctrine_Query::create()
		// 	->from('Default_Model_Entity m')
		// 	->innerJoin('m.Department d')
		// 	->innerJoin('m.EntityM2nBrand b')
		// 	->innerJoin('d.' . $M2nModel . ' pd')
		// 	->innerJoin('pd.' . $protocol . ' p')
		// 	->whereIn('m.parent_user_id', $usersId)
		// ;

		// $subUsersCollection = $subUserModel->execute();

		// $resultCount += $subUsersCollection->count();
		// if ($resultCount > 0) {
		// 	//send email to all users.
		// 	foreach ($subUsersCollection as $subUserEntityModel) {
		// 		$usersId[] = $subUserEntityModel->id;
		// 		//redirect link for protocol page
		// 		$redirectTo = L8M_Library::getSchemeAndHttpHost() . $viewFromMVC->url(array('module' => 'default', 'controller' => $controller, 'action' => 'detail', 'lang'=> strtolower($subUserEntityModel->spoken_language), 'short' => $short), NULL, TRUE);

		// 		if(!empty($regionId))
		// 			$redirectTo = L8M_Library::getSchemeAndHttpHost() . $viewFromMVC->url(array('module' => 'default', 'controller' => $controller, 'action' => 'detail', 'lang'=> strtolower($subUserEntityModel->spoken_language), 'region'=>$regionId, 'short' => $short), NULL, TRUE);

		// 		//send emails to user
		// 		PRJ_Email::send('push_email', $subUserEntityModel, array('REDIRECT_URL' => $redirectTo));
		// 	}
		// }
		$completedStep = $start + $resultCount;

		$percentage = (int)(($completedStep / $rowCount) * 100);
		if ($step != $totalStep) {
			$step += 1;
			$result = array('step' => $step, 'percentage' => $percentage, 'completedStep' => $completedStep);
		} else {
			$result = array('step' => 'done', 'completedStep' => $completedStep);
		}

		return $result;
	}

	public static function getUserFromSelection($M2nModel,$protocol,$requestParams){
		/**
		 * view from MVC
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		//change published field for "date" model
		// if($protocol === 'Dates' || $protocol === 'RegionDate'){
		// 	$published = 'p.publish_de = TRUE OR p.publish_fr = TRUE';
		// } else {
		// 	$published = 'p.published = TRUE';
		// }

		$protocolId = $requestParams['id'];
		$lang = $requestParams['selectedLang'];
		$contractTypeIds = $requestParams['contractTypeIds'];
		$regionIds = $requestParams['regionIds'];
		$brandIds = $requestParams['brandIds'];
		$departmentIds = $requestParams['departmentIds'];
		$arrayCombinations = PRJ_Library::arrayCombinations(array($lang, $contractTypeIds, $regionIds, $brandIds, $departmentIds));
		$regionM2NModelName = $protocol.'M2nRegion';

		$protocolQuery = Doctrine_Query::create()
			->from('Default_Model_Entity m')
			->innerJoin('m.Department d')
			->innerJoin('m.EntityM2nBrand b')
			->innerJoin('d.'.$M2nModel.' pd')
			->innerJoin('pd.'.$protocol.' p')
		;
		if (class_exists("Default_Model_".$regionM2NModelName)) {
			$protocolQuery->innerJoin('p.'.$regionM2NModelName.' ps')
				->addWhere('ps.region_id = m.region_id')
			;
		} else {
			if (class_exists("Default_Model_".$protocol)) {
				$className = "Default_Model_".$protocol;
				$dummyInstance = new $className;
				if(isset($dummyInstance->region_id)) {
					$protocolQuery->innerJoin('p.Region s')
						->addWhere('s.id = m.region_id')
					;
				}
			}
		}
		$protocolQuery->addWhere('p.id = ?', array($protocolId))
			// ->addWhere($published)
			->orderBy('m.id ASC')
		;

		//create where clause possible combinations
		foreach ($arrayCombinations as $combinationValue) {
			if ($combinationValue === reset($arrayCombinations)) {
				$protocolQuery->addWhere('UPPER(m.spoken_language) = ? AND m.contract_type_id = ? AND m.region_id = ? AND b.brand_id = ? AND m.department_id = ?', $combinationValue);
			} else {
				$protocolQuery->orWhere('UPPER(m.spoken_language) = ? AND m.contract_type_id = ? AND m.region_id = ? AND b.brand_id = ? AND m.department_id = ?', $combinationValue);
			}
		}
		$usersCollection = $protocolQuery->execute();
		$usersList = array();
		//Get subusers

		if(!empty($usersCollection)) {
			// get sub-users
			/* foreach($usersCollection as $users) {
				$subUserModel = Doctrine_Query::create()
					->from('Default_Model_Entity m')
					->innerJoin('m.Department d')
					->innerJoin('m.EntityM2nBrand b')
					->innerJoin('d.'.$M2nModel.' pd')
					->innerJoin('pd.'.$protocol.' p')
					->addWhere('m.parent_user_id = ?', $users->id)
					;
					if (class_exists("Default_Model_".$regionM2NModelName)) {
						$subUserModel->innerJoin('p.'.$regionM2NModelName.' ps')
						->addWhere('ps.region_id = m.region_id');
					} else {
						if (class_exists("Default_Model_".$protocol)) {
							$className = "Default_Model_".$protocol;
							$dummyInstance = new $className;
							if(isset($dummyInstance->region_id)) {
								$subUserModel->innerJoin('p.Region s')
								->addWhere('s.id = m.region_id')
								;
							}
						}
					}

				$subUsersCollection = $subUserModel->execute();

				foreach($subUsersCollection as $subuserModel) {
					$userDetails = array(
						'CH_Code' => $subuserModel->ch_code,
						'Name' => $subuserModel->Salutation->name . ' ' . $subuserModel->firstname . ' ' . $subuserModel->lastname,
						'Company' => $subuserModel->company,
						'Login' => $subuserModel->login
					);

					$usersList[] = $userDetails;
				}
			} */

			foreach($usersCollection as $userModel) {
				$userDetails = array(
					'CH_Code' => $userModel->ch_code,
					'Name' => $userModel->Salutation->name . ' ' . $userModel->firstname . ' ' . $userModel->lastname,
					'Company' => $userModel->company,
					'Login' => $userModel->login
				);

				$usersList[] = $userDetails;
			}
		}

		$rowCount = count($usersList);
		$totalStep = ceil($rowCount / L8M_Config::getOption('l8m.news.maxPerPage'));
		$listHTML = $viewFromMVC->pushEmailUsersList($usersList);
		$processHTML = $viewFromMVC->pushEmailProcess($rowCount);

		$result = array('rowCount' => $rowCount, 'totalStep' => $totalStep, 'listHTML' => $listHTML, 'processHTML' => $processHTML);

		return $result;
	}

	public static function sendTestMail($requestParams, $controller, $short, $regionId = NULL){
		//view from MVC
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		$entityId = Zend_Auth::getInstance()->getIdentity()->id;
		$entityShort = Default_Model_Entity::getModelById($entityId)->short;

		foreach ($requestParams['selectedLang'] as $lang) {
			//redirect link for protocol page
			$surveyUrl = L8M_Library::getSchemeAndHttpHost() . $viewFromMVC->url(array('module' => 'default', 'controller' => $controller, 'action' => 'index', 'survey' => $short, 'entity' => base64_encode($entityShort)), NULL, TRUE);
			$emailSubject = $viewFromMVC->translate('VSOH') . ' - ' . $viewFromMVC->surveyTitle;

			$entityModel = array(
				'firstname' => $requestParams['firstname'],
				'lastname' => $requestParams['lastname'],
				'spoken_language' => $lang,
				'email' => $requestParams['email'],
				'salutation_id' => $requestParams['salutation'],
			);

			//send emails to user
			PRJ_Email::send(
				'survey_email',
				(object)$entityModel,
				array(
					'SURVEY_URL' => $surveyUrl
				),
				FALSE,
				$emailSubject
			);
		}

		$result = array('isSent' => TRUE);

		return $result;
	}
}