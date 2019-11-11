<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/RegionDatesController.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: RegionDatesController.php 549 2019-04-24 01:32:59Z nm $
 */

/**
 *
 *
 * RegionDatesController
 *
 *
 */
class RegionDatesController extends L8M_Controller_Action
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
		$page = $this->_request->getParam('page');
		$searchString = $this->_request->getParam('searchString');
		$regionId = $this->_request->getParam('region');

		if(!empty($regionId))
			$formAction = $this->view->url(array('module'=>'default', 'controller'=>'region-dates', 'action'=>'index', 'region'=>$regionId), NULL, TRUE);
		else
			$formAction = $this->view->url(array('module'=>'default', 'controller'=>'region-dates', 'action'=>'index'), NULL, TRUE);

		$searchForm = new Default_Form_Protocol_Search();
		$searchForm
//			->addDecorators(array(
//				new L8M_Form_Decorator_FormHasRequiredElements(),
//			))
			->setAction($formAction)
		;

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$regionID = $loginUser->region_id;
		$contractTypeID = $loginUser->contract_type_id;

		$datesQuery = Doctrine_Query::create()
			->from('Default_Model_RegionDates m')
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
			$datesQuery->innerJoin('m.RegionDatesM2nDepartment n')
				->innerJoin('m.RegionDatesM2nContractType p')
				->innerJoin('m.RegionDatesM2nBrand b')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('m.region_id = ?', array($regionID))
				->addWhere('p.contract_type_id = ?', array($contractTypeID))
				->addWhere('m.publish_'.L8M_Locale::getLang().' = ?', array(TRUE))
				->addWhere('m.publish_datetime <= NOW()')
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
		if(!empty($regionId)) {
			$datesQuery->addWhere('m.region_id = ?', array($regionId));
			$this->view->region = $regionId;
		}
		$datesQuery->addWhere('YEAR(m.publish_datetime) > YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->orderBy('m.publish_datetime DESC')
		;

		$datesPager = new Doctrine_Pager($datesQuery, $page, L8M_Config::getOption('l8m.news.maxPerPage'));
		$this->view->datesCollection = $datesPager
			->execute()
		;

		$regionOptions = Doctrine_Query::create()
			->from('Default_Model_Region s')
			->select('s.id, st.title')
			->leftJoin('s.Translation st')
			->addWhere('st.lang = ?', L8M_Locale::getLang())
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute(array())
		;
		$this->view->regionOptions = $regionOptions;

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
		$regionId = $this->_request->getParam('region');

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$contractTypeID = $loginUser->contract_type_id;

		$redirectUrl = $this->view->url(array('module'=>'default', 'controller'=>'region-dates', 'action'=>'index'), NULL, TRUE);

		$datesModel = Doctrine_Query::create()
			->from('Default_Model_RegionDates m')
			->leftJoin('m.Translation mt')
		;
			if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
				$datesModel->leftJoin('m.RegionDatesM2nDepartment n')
					->leftJoin('m.RegionDatesM2nContractType p')
					->leftJoin('m.RegionDatesM2nBrand b')
					->leftJoin('m.RegionDatesM2nMediaImage i')
					->addWhere('m.id = n.region_date_id')
					->addWhere('n.department_id = ?', array($departmentID))
					->addWhere('m.region_id = ?', array($regionId))
					->addWhere('p.contract_type_id = ?', array($contractTypeID))
					->addWhere('m.publish_'.L8M_Locale::getLang().' = ?', array(TRUE))
					->addWhere('m.publish_datetime <= NOW()')
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
			$datesModel->addWhere('m.short = ? AND mt.lang = ? ', array($this->_request->getParam('short'), L8M_Locale::getLang()))
				->addWhere('YEAR(m.publish_datetime) > YEAR(CURDATE() - INTERVAL 2 YEAR)')
			;
		if(!empty($regionId)) {
			$datesModel->addWhere('m.region_id = ?', array($regionId));
			$this->view->region = $regionId;
			$redirectUrl = $this->view->url(array('module'=>'default', 'controller'=>'region-dates', 'action'=>'index', 'region'=>$regionId), NULL, TRUE);
		}
		$datesModel = $datesModel->limit(1)
			->execute()
			->getFirst();

		if (!$datesModel) {
			$this->_redirect($redirectUrl);
		}
		$this->view->layout()->title = $datesModel->title;

		$userId = $loginUser->id;

		$registrationDetails = new stdClass();
		$registrationDetails->status = FALSE;

		$userAlreadyRegistered = Doctrine_Query::create()
			->from('Default_Model_RegionDatesParticipants')
			->addWhere('user_id = ? AND region_date_id = ?', array($userId, $datesModel->id))
			->count()
		;

		if($userAlreadyRegistered) {
			$participantsCollection = Doctrine_Query::create()
				->from('Default_Model_RegionDatesParticipants')
				->select('participant_name')
				->addWhere('user_id = ? AND region_date_id = ?', array($userId, $datesModel->id))
				->execute()
			;
			$answersCollection = Doctrine_Query::create()
				->from('Default_Model_RegionDatesParticipantsAnswers')
				->addWhere('user_id = ? AND region_date_id = ?', array($userId, $datesModel->id))
				->execute()
			;

			$participants = array();
			$answers = array();

			foreach($participantsCollection as $participantModel) {
				$participants[$participantModel->id] = $participantModel->participant_name;
			}
			foreach($answersCollection as $answerModel) {
				$answers[$answerModel->question_id] = $answerModel->answer;
			}

			$registrationDetails->status = TRUE;
			$registrationDetails->participants = $participants;
			$registrationDetails->answers = $answers;
		}

		$this->view->loginUser = $loginUser;
		$this->view->datesModel = $datesModel;
		$this->view->registrationDetails = $registrationDetails;
	}

	/**
	 * registration for event action.
	 *
	 * @return void
	 */
	public function registerEventAjaxAction()
	{
		if ($this->getRequest()->isXmlHttpRequest()) {
			//get form post data
			$postData = $this->_request->getPost();
			$eventId = $postData['eventId'];
			$totalPerson = $postData['totalPerson'];
			$personNames = array();
			$questions = array();

			//get event model from id
			$eventModel = Default_Model_RegionDates::getModelByID($eventId);

			$loginUser = Zend_Auth::getInstance()->getIdentity();
			$userId = $loginUser->id;

			if (((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) || ((!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && !empty($eventModel->is_bookable) && strtotime(date('Y-m-d')) <= strtotime($eventModel->closed_registration_date) && $eventModel->region_id == $loginUser->region_id)) {
				//get person names and answers of questions from form data.
				foreach($postData as $postKey => $postValue) {
					$explodeKey = explode('_', $postKey);

					$keyName = $explodeKey[0];
					array_shift($explodeKey);
					$keyIndex = implode('_', $explodeKey);

					//get person name
					if($keyName == 'personName'){
						$personNames[$keyIndex] = $postValue;
					} else
					if($keyName == 'question'){
						$questions[$postKey] = $postValue;
					}
				}

				$oldParticipantsCount = Doctrine_Query::create()
					->from('Default_Model_RegionDatesParticipants')
					->addWhere('user_id = ? AND region_date_id = ?', array($userId, $eventModel->id))
					->count()
				;

				if($oldParticipantsCount) {
					$emailTemplate = 'change_event_registration_details';
				} else {
					$emailTemplate = 'event_registration';
				}

				//save participants
				$newParticipantsIds = array();
				$existingParticipantsCount = 0;
				foreach ($personNames as $personIndex=>$personValue) {
					if(strpos($personIndex, 'new_') !== FAlSE) {
						$RegionDatesParticipantsModel = new Default_Model_RegionDatesParticipants();
						$RegionDatesParticipantsModel['user_id'] = $userId;
						$RegionDatesParticipantsModel['participant_name'] = $personValue;
						$RegionDatesParticipantsModel['region_date_id'] = $eventId;
						$RegionDatesParticipantsModel->save();

						$newParticipantsIds[$personIndex] = $RegionDatesParticipantsModel->id;
					} else {
						++$existingParticipantsCount;
						$RegionDatesParticipantsModel = Default_Model_RegionDatesParticipants::getModelById($personIndex);
						if($RegionDatesParticipantsModel->participant_name != $personValue) {
							$RegionDatesParticipantsModel->participant_name = $personValue;
							$RegionDatesParticipantsModel->save();
						}
					}
				}

				//delete participants
				$deletedParticipantsIds = array();
				if($existingParticipantsCount < $oldParticipantsCount) {
					$oldParticipantsCollection = Doctrine_Query::create()
						->from('Default_Model_RegionDatesParticipants')
						->addWhere('user_id = ? AND region_date_id = ?', array($userId, $eventModel->id))
						->execute()
					;

					foreach($oldParticipantsCollection as $oldParticipantModel) {
						if(!in_array($oldParticipantModel->id, array_keys($personNames))) {
							$deletedParticipantsIds[] = $oldParticipantModel->id;
							$oldParticipantModel->hardDelete();
						}
					}
				}

				//get a new ArrayIterator for sorting the questions with `position` in DB
				$regionDateQuestions = $eventModel->RegionDatesM2nQuestions->getIterator();

				//define ordering closure, using preferred comparison method/field
				$regionDateQuestions->uasort(function ($first, $second) {
					return (int) $first->position > (int) $second->position ? 1 : -1;
				});

				//get question ids from date questions
				$questionIds = array();
				foreach ($regionDateQuestions as $value){
					if(!in_array($value->question_id,$questionIds))
						array_push($questionIds,$value->question_id);
				}

				$questionsAndAnswers = array();
				foreach ($regionDateQuestions as $value) {
					if(empty($value->Question->parent_question_id)){
						//add question in list
						$questionsAndAnswers[$value->question_id] = array(
							'question' => $value->Question->title
						);

						$childQuestions = Default_Model_DatesQuestions::createQuery()
							->addWhere('parent_question_id = ?', $value->question_id)
							->execute()
						;
						if($childQuestions->count() > 0) {
							foreach ($childQuestions as $childQuestion) {
								if (in_array($childQuestion->id, $questionIds)) {
									//add child questions if found
									$questionsAndAnswers[$childQuestion->id] = array(
										'question' => $childQuestion->title
									);
								}
							}
						}
					}
				}

				//save question answers
				foreach ($eventModel->RegionDatesM2nQuestions as $questionValue) {
					$RegionDatesAnswerModel = Doctrine_Query::create()
						->from('Default_Model_RegionDatesParticipantsAnswers')
						->addWhere('user_id = ?', array($userId))
						->addWhere('region_date_id = ?', array($eventId))
						->addWhere('question_id = ?', array($questionValue->question_id))
						->limit(1)
						->execute()
						->getFirst()
					;

					if (!$RegionDatesAnswerModel) {
						//Add new row for question answer
						$RegionDatesAnswerModel = new Default_Model_RegionDatesParticipantsAnswers();
					}

					//get answers
					if (!empty($questionValue->Question->is_checkbox)) {
						//is checkbox
						$answer = 'Nein';
						if (array_key_exists('question_' . $questionValue->question_id, $questions))
							$answer = 'Ja';
					} else {
						//is text input
						$answer = NULL;
						if (array_key_exists('question_' . $questionValue->question_id, $questions))
							$answer = $questions['question_' . $questionValue->question_id];
					}

					$RegionDatesAnswerModel['user_id'] = $userId;
					$RegionDatesAnswerModel['region_date_id'] = $eventId;
					$RegionDatesAnswerModel['question_id'] = $questionValue->question_id;
					$RegionDatesAnswerModel['answer'] = $answer;
					$RegionDatesAnswerModel->save();

					$questionsAndAnswers[$RegionDatesAnswerModel->Question->id]['answer'] = $answer;
				}

				/*
				 * send email to all supervisor about event details
				 */

				//prepare attachment (iCal file)
				if (!empty($eventModel->region_id))
					$detailPageUrl = $this->view->url(array('module' => 'default', 'controller' => 'region-dates', 'action' => 'detail', 'short' => $eventModel->short), NULL, TRUE);
				else
					$detailPageUrl = $this->view->url(array('module' => 'default', 'controller' => 'region-dates', 'action' => 'detail', 'region' => $eventModel->region_id, 'short' => $eventModel->short), NULL, TRUE);

				$DateICal = 'Ymd\THis\Z';
				$eventPageURL = L8M_Library::getSchemeAndHttpHost() . $detailPageUrl;
				$iCal = 'BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0
CALSCALE:GREGORIAN
PRODID:-//VSOH//' . $eventModel->title . '//' . L8M_Locale::getLang() . '\n
BEGIN:VEVENT
CREATED:' . date($DateICal, strtotime($eventModel->created_at)) . '
UID:' . $eventId . '
URL;VALUE=URI:' . $eventPageURL . '
DTEND:' . date($DateICal, strtotime($eventModel->end_datetime)) . '
TRANSP:OPAQUE
SUMMARY:' . $eventModel->title . '
DTSTART:' . date($DateICal, strtotime($eventModel->start_datetime)) . '
DTSTAMP:' . date($DateICal) . '
LOCATION:' . $eventModel->place . '
SEQUENCE:0
DESCRIPTION:' . $eventModel->description . '
END:VEVENT
END:VCALENDAR';

				$fileName = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'ICS' . DIRECTORY_SEPARATOR . $eventModel->title . '.ics';
				$f = fopen($fileName, 'wb');
				if (!$f) {
					die('Unable to create output file: ' . $fileName);
				}
				fwrite($f, $iCal);
				fclose($f);

				//get supervisor details to send emails
				$supervisorModel = Doctrine_Query::create()
					->from('Default_Model_EntitySupervisor')
					->orderBy('id ASC')
				;

				if ($supervisorModel->count() > 0) {
					//send email to all supervisor.
					foreach ($supervisorModel->execute() as $supervisorValue) {
						//create dynamic variable array for email template.
						$dynamicVars = array(
							'CONTENT_HTML' => $this->view->prjEmailEventRegistration('html', $eventModel->title, $totalPerson, $personNames, $questionsAndAnswers, $supervisorValue->spoken_language),
							'CONTENT_PLAIN' => $this->view->prjEmailEventRegistration('plain', $eventModel->title, $totalPerson, $personNames, $questionsAndAnswers, $supervisorValue->spoken_language)
						);

						$returnValue = PRJ_Email::send($emailTemplate, $supervisorValue, $dynamicVars, array($fileName));
					}
				}

				/**
				 * send email to login user for event details
				 */
				$userDynamicVars = array(
					'CONTENT_HTML' => $this->view->prjEmailEventRegistration('html', $eventModel->title, $totalPerson, $personNames, $questionsAndAnswers, $loginUser->spoken_language),
					'CONTENT_PLAIN' => $this->view->prjEmailEventRegistration('plain', $eventModel->title, $totalPerson, $personNames, $questionsAndAnswers, $loginUser->spoken_language)
				);
				//send email to login user.
				$returnValue = PRJ_Email::send($emailTemplate, $loginUser, $userDynamicVars, array($fileName));

				$result = array('isSent' => $returnValue);
				if(count($newParticipantsIds)) {
					$result['newParticipants'] = $newParticipantsIds;
				}
				if(count($deletedParticipantsIds)) {
					$result['deletedParticipants'] = $deletedParticipantsIds;
				}

				/**
				 * json
				 */
				$bodyData = Zend_Json_Encoder::encode($result);

				//disable layout
				Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
				Zend_Layout::getMvcInstance()->disableLayout();

				/**
				 * header
				 */
				$bodyContentHeader = 'application/json';
				$this->getResponse()
					->setHeader('Content-Type', $bodyContentHeader)
					->setBody($bodyData);
			}
		}
	}

	/**
	 * unregistration from event action.
	 *
	 * @return void
	 */
	public function unregisterEventAjaxAction()
	{
		if ($this->getRequest()->isXmlHttpRequest()) {
			//get form post data
			$postData = $this->_request->getPost();
			$eventId = $postData['eventId'];
			$comment = $postData['comment'];

			//get event model from id
			$eventModel = Default_Model_RegionDates::getModelByID($eventId);

			$loginUser = Zend_Auth::getInstance()->getIdentity();
			$userId = $loginUser->id;

			//delete participants
			$participantsCollection = Doctrine_Query::create()
				->from('Default_Model_RegionDatesParticipants')
				->addWhere('user_id = ?', array($userId))
				->addWhere('region_date_id = ?', array($eventId))
				->execute()
			;
			foreach($participantsCollection as $participantModel) {
				$participantModel->hardDelete();
			}

			//delete answers
			$answersCollection = Doctrine_Query::create()
				->from('Default_Model_RegionDatesParticipantsAnswers')
				->addWhere('user_id = ?', array($userId))
				->addWhere('region_date_id = ?', array($eventId))
				->execute()
			;
			foreach($answersCollection as $answerModel) {
				$answerModel->hardDelete();
			}

			$emailTemplate = 'event_unregistration';

			//get supervisor details to send emails
			$supervisorModel = Doctrine_Query::create()
				->from('Default_Model_EntitySupervisor')
				->orderBy('id ASC')
			;

			if ($supervisorModel->count() > 0) {
				//send email to all supervisor.
				foreach ($supervisorModel->execute() as $supervisorValue) {
					//create dynamic variable array for email template.
					$dynamicVars = array(
						'CONTENT_HTML' => $this->view->prjEmailEventRegistration('html', $eventModel->title, 0, array(), array(), $supervisorValue->spoken_language, $comment),
						'CONTENT_PLAIN' => $this->view->prjEmailEventRegistration('plain', $eventModel->title, 0, array(), array(), $supervisorValue->spoken_language, $comment),
					);

					$returnValue = PRJ_Email::send($emailTemplate, $supervisorValue, $dynamicVars, array());
				}
			}

			/**
			 * send email to login user for event details
			 */
			$userDynamicVars = array(
				'CONTENT_HTML' => $this->view->prjEmailEventRegistration('html', $eventModel->title, 0, array(), array(), $loginUser->spoken_language, $comment),
				'CONTENT_PLAIN' => $this->view->prjEmailEventRegistration('plain', $eventModel->title, 0, array(), array(), $loginUser->spoken_language, $comment)
			);
			//send email to login user.
			$returnValue = PRJ_Email::send($emailTemplate, $loginUser, $userDynamicVars, array());

			$result = array('isSent' => $returnValue);

			/**
			 * json
			 */
			$bodyData = Zend_Json_Encoder::encode($result);

			//disable layout
			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			/**
			 * header
			 */
			$bodyContentHeader = 'application/json';
			$this->getResponse()
				->setHeader('Content-Type', $bodyContentHeader)
				->setBody($bodyData);
		}
	}
}