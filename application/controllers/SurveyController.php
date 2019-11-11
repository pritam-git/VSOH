<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/SurveyController.php
 * @author     Unnati Visani <unnati.visani@bcssarl.com>
 * @version    $Id: SurveyController.php  2019-10-24 16:18:40Z nm $
 */

/**
 *
 *
 * SurveyController
 *
 *
 */
class SurveyController extends L8M_Controller_Action
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
		/**
		 * init parent
		 */
		parent::init();
	}
	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function indexAction ()
	{
		$surveyShort = base64_decode($this->_request->getParam('survey'));
		$entityShort = base64_decode($this->_request->getParam('entity'));

		if($surveyShort && $entityShort) {
			$surveyModel = Default_Model_Survey::getModelByShort($surveyShort);
			$entityModel = Default_Model_Entity::getModelByShort($entityShort);

			$surveyId = ($surveyModel) ? $surveyModel->id : NULL;
			$entityId = ($entityModel) ? $entityModel->id : NULL;

			if($surveyId != NULL && $entityId != NULL) {
				$this->view->surveyShort = $surveyShort;
				$this->view->entityShort = $entityShort;

				$surveyDataCheck = Doctrine_Query::create()
					->from('Default_Model_SurveyAnswers')
					->addWhere('survey_id = ?', array($surveyId))
					->addwhere('entity_id = ?', array($entityId))
					->execute()
					->getFirst()
				;

				if($entityModel instanceof Default_Model_EntityAdmin || !$surveyDataCheck) {
					$surveyModel = Default_Model_Survey::getModelByID($surveyId);
					$entityModel = Default_Model_Entity::getModelByID($entityId);

					$surveyData = unserialize($surveyModel->survey_data);

					if($surveyData != NULL &&
						$surveyModel != NULL &&
						$entityModel != NULL) {

						$this->view->surveyData = $surveyData;
					} else {
						// Error: Some error in the request
						$this->view->surveyData = 'error';
						$this->view->surveyErrorMsg = $this->view->translate('Survey not properly defined', 'en');
					}

				} else {
					//Error:
					$this->view->surveyData = 'error';
					$this->view->surveyErrorMsg = $this->view->translate('Survey has already been submitted by the user', 'en');
				}
			} else {
				//Error:
				$this->view->surveyData = 'error';
				$this->view->surveyErrorMsg = $this->view->translate('User or Survey not found', 'en');
			}
		} else {
			//Error:
			$this->view->surveyData = 'error';
			$this->view->surveyErrorMsg = $this->view->translate('The link is not proper', 'en');
		}
	}

	/**
	 * Save answers action.
	 *
	 * @return void
	 */
	public function saveAnswersAjaxAction() {
		$surveyShort = base64_decode($this->_request->getParam('survey'));
		$entityShort = base64_decode($this->_request->getParam('entity'));
		$surveyData = serialize($this->getRequest()->getParam('surveyData'));

		if($surveyShort && $entityShort) {
			$surveyModel = Default_Model_Survey::getModelByShort($surveyShort);
			$entityModel = Default_Model_Entity::getModelByShort($entityShort);

			$surveyId = ($surveyModel) ? $surveyModel->id : NULL;
			$entityId = ($entityModel) ? $entityModel->id : NULL;

			if($surveyId && $entityId && $surveyData) {
				$surveyDataCheck = Doctrine_Query::create()
					->from('Default_Model_SurveyAnswers')
					->addWhere('survey_id = ?', array($surveyId))
					->addwhere('entity_id = ?', array($entityId))
					->execute()
					->getFirst()
				;

				if(!$surveyDataCheck) {
					$surveyAnswersModel = new Default_Model_SurveyAnswers();

					$answersArray = array(
						'survey_id'=>$surveyId,
						'entity_id'=>$entityId,
						'survey_answer_data'=>$surveyData
					);

					$surveyAnswersModel->merge($answersArray);
					$surveyAnswersModel->save();
				} else
				if($surveyDataCheck && $entityModel instanceof Default_Model_EntityAdmin) {
					$surveyDataCheck->survey_answer_data = $surveyData;
					$surveyDataCheck->save();
				}
			}
		}

		Zend_Layout::getMvcInstance()->disableLayout();
        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
	}
}