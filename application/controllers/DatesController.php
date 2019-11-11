<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/DatesController.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: DatesController.php 549 2018-12-03 05:02:59Z nm $
 */

/**
 *
 *
 * DatesController
 *
 *
 */
class DatesController extends L8M_Controller_Action
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
		$searchForm = new Default_Form_Protocol_Search();
		$searchForm
//			->addDecorators(array(
//				new L8M_Form_Decorator_FormHasRequiredElements(),
//			))
			->setAction($this->view->url(array('module'=>'default', 'controller'=>'dates', 'action'=>'index'), NULL, TRUE))
		;
		$page = $this->_request->getParam('page');
		$searchString = $this->_request->getParam('searchString');

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$regionID = $loginUser->region_id;
		$contractTypeID = $loginUser->contract_type_id;

		$datesQuery = Doctrine_Query::create()
			->from('Default_Model_Dates m');

		if (($searchForm->isSubmitted() &&
			$searchForm->isValid($this->getRequest()->getPost())) ||
			($searchString != '')) {

			if(isset($searchForm->getValues()['searchProtocolInput']) && ($searchForm->getValues()['searchProtocolInput'])) {
				$searchString = $searchForm->getValues()['searchProtocolInput'];
			}
			$this->view->searchString = $searchString;

			$datesQuery->innerJoin('m.Translation mt')
				->addWhere('LOWER(mt.title) LIKE ?', array('%' . $searchString . '%'));
		}

		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$datesQuery->innerJoin('m.DatesM2nDepartment n')
				->innerJoin('m.DatesM2nRegion o')
				->innerJoin('m.DatesM2nContractType p')
				->innerJoin('m.DatesM2nBrand b')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('o.region_id = ?', array($regionID))
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

		$datesQuery->addWhere('YEAR(m.publish_datetime) > YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->orderBy('m.publish_datetime DESC')
		;

		$datesPager = new Doctrine_Pager($datesQuery, $page, L8M_Config::getOption('l8m.news.maxPerPage'));
		$this->view->datesCollection = $datesPager
			->execute()
		;

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

		$loginUser = Zend_Auth::getInstance()->getIdentity();
		$departmentID = $loginUser->department_id;
		$regionID = $loginUser->region_id;
		$contractTypeID = $loginUser->contract_type_id;

		$datesModel = Doctrine_Query::create()
			->from('Default_Model_Dates m')
			->leftJoin('m.Translation mt')
		;
		if(!((Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntityAdmin) && (!(Zend_Auth::getInstance()->getIdentity()) instanceof Default_Model_EntitySupervisor)) {
			$datesModel->leftJoin('m.DatesM2nDepartment n')
				->leftJoin('m.DatesM2nRegion o')
				->leftJoin('m.DatesM2nMediaImage i')
				->leftJoin('m.DatesM2nContractType p')
				->leftJoin('m.DatesM2nBrand b')
				->addWhere('m.id = n.dates_id')
				->addWhere('n.department_id = ?', array($departmentID))
				->addWhere('o.region_id = ?', array($regionID))
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
		$datesModel=$datesModel
			->addWhere('YEAR(m.publish_datetime) > YEAR(CURDATE() - INTERVAL 2 YEAR)')
			->addWhere('m.short = ? AND mt.lang = ? ', array($this->_request->getParam('short'), L8M_Locale::getLang()))
			->limit(1)
			->execute()
			->getFirst()
		;

		if (!$datesModel) {
			$this->_redirect($this->_helper->url('index'));
		}
		$this->view->layout()->title = $datesModel->title;

		$userId = $loginUser->id;

		$registrationDetails = new stdClass();
		$registrationDetails->status = FALSE;

		$userAlreadyRegistered = Doctrine_Query::create()
			->from('Default_Model_DatesParticipants')
			->addWhere('user_id = ? AND date_id = ?', array($userId, $datesModel->id))
			->count()
		;

		if($userAlreadyRegistered) {
			$participantsCollection = Doctrine_Query::create()
				->from('Default_Model_DatesParticipants')
				->select('participant_name')
				->addWhere('user_id = ? AND date_id = ?', array($userId, $datesModel->id))
				->execute()
			;
			$answersCollection = Doctrine_Query::create()
				->from('Default_Model_DatesParticipantsAnswers')
				->addWhere('user_id = ? AND date_id = ?', array($userId, $datesModel->id))
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
			$eventModel = Default_Model_Dates::getModelByID($eventId);

			$loginUser = Zend_Auth::getInstance()->getIdentity();
			$userId = $loginUser->id;

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
				->from('Default_Model_DatesParticipants')
				->addWhere('user_id = ? AND date_id = ?', array($userId, $eventModel->id))
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
					$DatesParticipantsModel = new Default_Model_DatesParticipants();
					$DatesParticipantsModel['user_id'] = $userId;
					$DatesParticipantsModel['participant_name'] = $personValue;
					$DatesParticipantsModel['date_id'] = $eventId;
					$DatesParticipantsModel->save();

					$newParticipantsIds[$personIndex] = $DatesParticipantsModel->id;
				} else {
					++$existingParticipantsCount;
					$DatesParticipantsModel = Default_Model_DatesParticipants::getModelById($personIndex);
					if($DatesParticipantsModel->participant_name != $personValue) {
						$DatesParticipantsModel->participant_name = $personValue;
						$DatesParticipantsModel->save();
					}
				}
			}

			//delete participants
			$deletedParticipantsIds = array();
			if($existingParticipantsCount < $oldParticipantsCount) {
				$oldParticipantsCollection = Doctrine_Query::create()
					->from('Default_Model_DatesParticipants')
					->addWhere('user_id = ? AND date_id = ?', array($userId, $eventModel->id))
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
			$dateQuestions = $eventModel->DatesM2nQuestions->getIterator();

			//define ordering closure, using preferred comparison method/field
			$dateQuestions->uasort(function ($first, $second) {
				return (int) $first->position > (int) $second->position ? 1 : -1;
			});

			//get question ids from date questions
			$questionIds = array();
			foreach ($dateQuestions as $value){
				if(!in_array($value->question_id,$questionIds))
					array_push($questionIds,$value->question_id);
			}

			$questionsAndAnswers = array();
			foreach ($dateQuestions as $value) {
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
			foreach ($eventModel->DatesM2nQuestions as $questionValue){
				$DatesAnswerModel = Doctrine_Query::create()
					->from('Default_Model_DatesParticipantsAnswers')
					->addWhere('user_id = ?', array($userId))
					->addWhere('date_id = ?', array($eventId))
					->addWhere('question_id = ?', array($questionValue->question_id))
					->limit(1)
					->execute()
					->getFirst()
				;

				if (!$DatesAnswerModel){
					//Add new row for question answer
					$DatesAnswerModel = new Default_Model_DatesParticipantsAnswers();
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

				$DatesAnswerModel['user_id'] = $userId;
				$DatesAnswerModel['date_id'] = $eventId;
				$DatesAnswerModel['question_id'] = $questionValue->question_id;
				$DatesAnswerModel['answer'] = $answer;
				$DatesAnswerModel->save();

				$questionsAndAnswers[$DatesAnswerModel->Question->id]['answer'] = $answer;
			}

			/*
			 * send email to all supervisor about event details
			 */

			//prepare attachment (iCal file)
			$DateICal = 'Ymd\THis\Z';
			$eventPageURL = L8M_Library::getSchemeAndHttpHost() . $this->view->url(array('module'=>'default', 'controller'=>'dates', 'action'=>'detail', 'short'=>$eventModel->short), NULL, TRUE);
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
			$eventModel = Default_Model_Dates::getModelByID($eventId);

			$loginUser = Zend_Auth::getInstance()->getIdentity();
			$userId = $loginUser->id;

			//delete participants
			$participantsCollection = Doctrine_Query::create()
				->from('Default_Model_DatesParticipants')
				->addWhere('user_id = ?', array($userId))
				->addWhere('date_id = ?', array($eventId))
				->execute()
			;
			foreach($participantsCollection as $participantModel) {
				$participantModel->hardDelete();
			}

			//delete answers
			$answersCollection = Doctrine_Query::create()
				->from('Default_Model_DatesParticipantsAnswers')
				->addWhere('user_id = ?', array($userId))
				->addWhere('date_id = ?', array($eventId))
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