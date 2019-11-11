<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/controllers/SurveyController.php
 * @author     Pritam Parmar <pritam.parmar@bcssarl.com>
 * @version    $Id: SurveyController.php 299 2019-10-23 03:02:38Z pp $
 */

/**
 *
 *
 * Admin_AssemblyController
 *
 *
 */
class Admin_SurveyController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_Survey';
	private $_modelListShort = 'Survey';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Survey';

	/**
	 * Store modelList.
	 *
	 * @var L8M_ModelForm_List
	 */
	private $_modelList = NULL;

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Admin_SurveyController.
	 *
	 * @return void
	 */
	public function init ()
	{

		/**
		 * set headline
		 */
		$this->_helper->layout()->headline = $this->view->translate('Administration') . ' - ModelList';
		$this->_helper->layout()->headline .= ': ' . $this->view->translate($this->_modelListUntranslatedTitle);

		/**
		 * pass through parent to prevent errors
		 */
		parent::init();

		/**
		 * start model list
		 */
		$this->_modelList = new L8M_ModelForm_List($this->_modelListName, $this);
		$this->_modelList
			->setDefault('listTitle', $this->view->translate($this->_modelListUntranslatedTitle))
			->disableSubLinks()
//			->disableButtonAdd()
//			->disableButtonDelete()
//			->addWhere('short', 'guest', FALSE, 'aa', 'Role', 'r')
//			->addWhereDqlString('aa.is_action_method = ? AND aa.resource LIKE ? ', array(TRUE, 'default.%'))
//			->setButton('Update', array('action'=>'update', 'controller'=>'action', 'module'=>'system'), 'update', FALSE)
			->setButton('Send', array('action'=>'send', 'controller'=>'survey', 'module'=>'admin'), 'update', TRUE)
			->setButton('Duplicate', array('action'=>'duplicate', 'controller'=>'survey', 'module'=>'admin'), 'update', TRUE)
			->setButton('Analytics', array('action'=>'analytics', 'controller'=>'survey', 'module'=>'admin'), 'update', TRUE)

//			->disableSaveWhere()
//			->useDbWhere(FALSE)
//			->showAjax();
//			->doNotRedirect()
//			->setDeleteOldList()
		;

		$this->_modelListConfig = array(
			'order'=>array(
			),
			'addIgnoredColumns'=>array(
			),
			'addIgnoredM2nRelations'=>array(
			),
			'ignoreColumnRelation'=>array(
			),
			'ignoreColumnInMultiRelation'=>array(
			),
			'relationM2nValuesDefinition'=>array(
			),
			'mediaDirectory'=>array(
			),
			'mediaRole'=>array(
			),
			'columnLabels'=>array(
			),
			'buttonLabel'=>'Save',
			'columnTypes'=>array(
				'survey_data' => 'textarea'
			),
			'addStaticFormElements'=>array(
			),
			'M2NRelations'=>array(
			),
			'replaceColumnValuesInMultiRelation'=>array(
			),
			'relationColumnInMultiRelation'=>array(
			),
			'multiRelationCondition'=>array(
			),
			'tinyMCE'=>array(
			),
			'setFormLanguage'=>L8M_Locale::getDefaultSystem(),
			'action'=>$this->_request->getActionName(),
			//'debug'=>TRUE,
		);

		$this->view->modelFormListButtons = $this->_modelList->getButtons(NULL, $this->_modelListShort, $this->_modelListConfig);
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
		if ($this->_modelListName) {
			$this->_forward('list');
		}
	}

	/**
	 * List action.
	 *
	 * @return void
	 */
	public function listAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('List');

		/**
		 * start model list
		 */
		$this->_modelList->listCollection($this->_modelListShort);
	}

	/**
	 * Create action.
	 *
	 * @return void
	 */
	public function createAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Add');

		/**
		 * start model list
		 */
		$this->_modelList->createModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforeSave'=>array(
				'PRJ_Survey_Create_BeforeSave',
			),
			'addStandardColumnValues'=>array(
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
			),
		)));
	}

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function deleteAction ()
	{

		/**
		 * get id to delete
		 */
		$ids = $this->getRequest()->getParam('ids', NULL, FALSE);
		if (!is_array($ids)) {
			$ids = array();
		}
		if (count($ids) > 0) {
			$ids = array_unique($ids);
		}
		foreach($ids as $id) {
			$surveyAnswerCollection = Doctrine_Query::create()
				->from('Default_Model_SurveyAnswers sa')
				->addWhere('sa.survey_id = ?', $id)
				->execute()
			;
			foreach ($surveyAnswerCollection as $surveyAnswer) {
				$surveyAnswer->hardDelete();
			}
		}

		/**
		 * set subheadline
		 */

		$this->_helper->layout()->subheadline =  $this->view->translate('Delete');

		/**
		 * start model list
		 */
		$this->_modelList->deleteModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforePreDelete'=>array(
			),
			'doBefore'=>array(
			),
		)));
	}

	/**
	 * Edit action.
	 *
	 * @return void
	 */
	public function editAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Edit');

		/**
		 * start model list
		 */
		$this->_modelList->editModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforeFormOutput'=>array(
			),
			'doBeforeSave'=>array(
				'PRJ_Survey_Edit_BeforeSave',
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
			),
		)));
	}

	/**
	 * SEND action.
	 *
	 * @return void
	 */
	public function sendAction ()
	{
		$surveyId = $this->_request->getParam('id');
		$isTested = $this->_request->getParam('do');

		/**
		 * prepare back button url
		 */
		$paramArray = array(
			'page',
			'rp',
			'query',
			'qtype',
			'sortorder',
			'sortname',
		);
		$paramValues = '?';
		foreach ($paramArray as $param) {
			$paramValues .= $param . '=' . $this->getRequest()->getParam($param, NULL, FALSE) . '&';
		}
		$backUrl = $this->view->url(array('action'=>'list','controller'=>'survey','module'=>'admin','modelListName'=>'Default_Model_Survey') , NULL, TRUE) . $paramValues;
		$currentUrl = $this->view->url(array('action'=>'send','controller'=>'survey','module'=>'admin','modelListName'=>'Default_Model_Survey') , NULL, TRUE) . $paramValues;

		//get survey model.
		$surveyModel = Default_Model_Survey::getModelByID($surveyId);

		$this->view->sent = FALSE;
		if($surveyModel->sent_mail_count != 0) {
			$this->view->sent = TRUE;
		}

		//redirect to survey page if result not found.
		if(!PRJ_SurveyEmail::getSelectionLists('Default_Model_Survey','SurveyM2nDepartment','Survey',$surveyId)) {
			$this->_redirect($backUrl);
		}

		$this->view->surveyId = $surveyId;
		$this->view->redirectUrl = $backUrl;
		$this->view->currentUrl = $currentUrl;
		$this->view->isTested = $isTested === 'TRUE' ? TRUE : FALSE;
	}

	/**
	 * duplicate action.
	 *
	 * @return void
	 */
	public function duplicateAction ()
	{
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();

		$surveyId = $this->_request->getParam('id');
		$surveyModel = Default_Model_Survey::getModelByID($surveyId);

		$aiParams = array(L8M_Config::getOption('resources.multidb.default.dbname'), 'survey');
		$aiQuery = 'SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?';

		$statement = L8M_Db::getConnection()->prepare($aiQuery);
		$statement->execute($aiParams);
		$result = $statement->fetch();
		$pNextID = $result[0];

		$newSurveyModel = new Default_Model_Survey();
		$newSurveyModel->survey_data = $surveyModel->survey_data;
		$newSurveyModel->name = $surveyModel->name . '-copy';

		$surveyShort = L8M_Library::createShort('Default_Model_Product', 'short', $newSurveyModel->name . '-' . $pNextID, 120);

		$newSurveyModel->sent_mail_count = 0;
		$newSurveyModel->short = $surveyShort;
		$newSurveyModel->survey_language = $surveyModel->survey_language;
		$newSurveyModel->start_datetime = $surveyModel->start_datetime;
		$newSurveyModel->end_datetime = $surveyModel->end_datetime;

		$newSurveyModel->save();

		// For SurveyM2NBrand
		$surveyM2NBrandModel = Doctrine_Query::create()
			->from('Default_Model_SurveyM2nBrand b')
			->addWhere('survey_id = ?', array($surveyId))
			->execute()
		;

		foreach($surveyM2NBrandModel as $brand) {
			$newSurveyM2NBrandModel = new Default_Model_SurveyM2nBrand();
			$newSurveyM2NBrandModel->survey_id = $newSurveyModel->id;
			$newSurveyM2NBrandModel->brand_id = $brand['brand_id'];
			$newSurveyM2NBrandModel->save();
		}

		// For SurveyM2NContractType
		$surveyM2NContractTypeModel = Doctrine_Query::create()
			->from('Default_Model_SurveyM2nContractType c')
			->addWhere('survey_id = ?', array($surveyId))
			->execute()
		;

		foreach($surveyM2NContractTypeModel as $contractType) {
			$newSurveyM2NContractTypeModel = new Default_Model_SurveyM2nContractType();
			$newSurveyM2NContractTypeModel->survey_id = $newSurveyModel->id;
			$newSurveyM2NContractTypeModel->contract_type_id = $contractType['contract_type_id'];
			$newSurveyM2NContractTypeModel->save();
		}

		// For SurveyM2NDepartment
		$surveyM2NDepartmentModel = Doctrine_Query::create()
			->from('Default_Model_SurveyM2nDepartment d')
			->addWhere('survey_id = ?', array($surveyId))
			->execute()
		;

		foreach($surveyM2NDepartmentModel as $department) {
			$newSurveyM2NDepartmentModel = new Default_Model_SurveyM2nDepartment();
			$newSurveyM2NDepartmentModel->survey_id = $newSurveyModel->id;
			$newSurveyM2NDepartmentModel->department_id = $department['department_id'];
			$newSurveyM2NDepartmentModel->save();
		}

		// For SurveyM2NRegion
		$surveyM2NRegionModel = Doctrine_Query::create()
			->from('Default_Model_SurveyM2nRegion r')
			->addWhere('survey_id = ?', array($surveyId))
			->execute()
		;

		foreach($surveyM2NRegionModel as $region) {
			$newSurveyM2NRegionModel = new Default_Model_SurveyM2nRegion();
			$newSurveyM2NRegionModel->survey_id = $newSurveyModel->id;
			$newSurveyM2NRegionModel->region_id = $region['region_id'];
			$newSurveyM2NRegionModel->save();
		}

		$backUrl = $this->view->url(array('action'=>'index','controller'=>'survey','module'=>'admin') , NULL, TRUE);
		$this->_redirect($backUrl);
	}

	/**
	 * analyze action
	 *
	 * @return void
	 */
	public function analyticsAction() {
		$surveyId = $this->_request->getParam('id');
		$surveyModel = Default_Model_Survey::getModelByID($surveyId);

		$surveyAnswersModel = Doctrine_Query::create()
			->from('Default_Model_SurveyAnswers sa')
			->addWhere('survey_id = ?', array($surveyId))
			->leftJoin('sa.Entity e')
			->addWhere('e.id = sa.entity_id')
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute()
		;

		$this->view->totalAnswers = count($surveyAnswersModel);

		$surveyData = unserialize($surveyModel->survey_data);

		$survey = json_decode($surveyData, TRUE);
		$count = array();

		foreach($survey['pages'] as $page) {
			foreach($page['elements'] as $element) {
				$name = $element['name'];
				if($element['type'] == 'radiogroup' || $element['type'] == 'checkbox') {
					$c = 0;
					foreach($element['choices'] as $choice) {
						if(!is_array($choice)) {
							$option = $choice;
						} else {
							$option = $choice['value'];
						}
						$count[$name][$option] = 0;

						if(isset($element['hasOther']) &&  $element['hasOther'] == 1) {
							$count[$name]['otherCount'] = 0;
							$count[$name]['otherArray'] = array();
						}
						if(isset($element['hasNone']) &&  $element['hasNone'] == 1) {
							$count[$name]['noneCount'] = 0;
						}
						$count[$name]['count'] = 0;
						$count[$name]['type'] = $element['type'];
						foreach($surveyAnswersModel as $ans) {
							$surveyAnswers = unserialize($ans['sa_survey_answer_data']);
							$answers = json_decode($surveyAnswers, TRUE);
							if($element['type'] == 'checkbox') {
								if(isset($answers[$name])) {
									$count[$name]['count'] += 1;
									if(in_array($option, $answers[$name])) {
										$count[$name][$option] += 1;
									}
									if(in_array('other', $answers[$name])) {
										$count[$name]['otherCount'] += 1;
										$comment = $name . '-Comment';
										$otherArray = array();

										if(isset($answers[$comment])) {
											$otherArray['answer'] = $answers[$comment];
											$otherArray['email'] = $ans['e_email'];
											array_push($count[$name]['otherArray'], $otherArray);
										}
									}
									if(in_array('none', $answers[$name])) {
										$count[$name]['noneCount'] += 1;
									}
								}
							} else
							if($element['type'] == 'radiogroup') {
								if(isset($answers[$name])) {
									$count[$name]['count'] += 1;

									if($option == $answers[$name]) {
										$count[$name][$option] += 1;
									}
									if($answers[$name] == 'other') {
										$count[$name]['otherCount'] += 1;
										$comment = $name . '-Comment';
										$otherArray = array();

										if(isset($answers[$comment])) {
											$otherArray['answer'] = $answers[$comment];
											$otherArray['email'] = $ans['e_email'];
											array_push($count[$name]['otherArray'], $otherArray);
										}
									}
									if($answers[$name] == 'none') {
										$count[$name]['noneCount'] += 1;
									}
								}
							}
						}
						$old = $count[$name][$option];
						unset($count[$name][$option]);
						if(!is_array($choice)) {
							$option = 'option not defined_' . $c++;
						} else {
							if(is_array($choice['text'])) {
								if($surveyModel->survey_language == 'de') {
									if(isset($choice['text']['default'])) {
										$option = $choice['text']['default'];
									} else {
										$option = $choice['text']['fr'];
									}
								} else {
									$option = $choice['text'][$surveyModel->survey_language];
								}
							} else {
								$option = $choice['text'];
							}
						}
						$count[$name][$option] = $old;
					}
				} else
				if($element['type'] == 'dropdown') {
					$choiceMin = (isset($element['choicesMin'])) ? $element['choicesMin'] : 0;
					$choiceMax = (isset($element['choicesMax'])) ? $element['choicesMax'] : 0;
					$choiceStep = (isset($element['choiceStep'])) ? $element['choiceStep'] : 1;
					if(isset($element['choices'])) {
						$c = 0;
						foreach($element['choices'] as $choice) {
							$count[$name]['type'] = $element['type'];
							$count[$name]['count'] = 0;
							if(!is_array($choice)) {
								$option = $choice;
							} else {
								$option = $choice['value'];
							}
							$count[$name][$option] = 0;
							foreach($surveyAnswersModel as $ans) {
								$surveyAnswers = unserialize($ans['sa_survey_answer_data']);
								$answers = json_decode($surveyAnswers, TRUE);
								if(isset($answers[$name])) {
									$count[$name]['count'] += 1;
									if($option == $answers[$name]) {
										$count[$name][$option] += 1;
									}
								}
							}
							$old = $count[$name][$option];
							unset($count[$name][$option]);
							if(!is_array($choice)) {
								$option = 'option not defined_' . $c++;
							} else {
								if(is_array($choice['text'])) {
									if($surveyModel->survey_language == 'de') {
										if(isset($choice['text']['default'])) {
											$option = $choice['text']['default'];
										} else {
											$option = $choice['text']['fr'];
										}
									} else {
										$option = $choice['text'][$surveyModel->survey_language];
									}
								} else {
									$option = $choice['text'];
								}
							}
							$count[$name][$option] = $old;
						}
						if($choiceMax != 0 || $choiceMin != 0) {
							for($i = $choiceMin; $i <= $choiceMax; $i += $choiceStep) {
								$count[$name]['type'] = $element['type'];
								$count[$name]['count'] = 0;
								$count[$name][$i] = 0;
								foreach($surveyAnswersModel as $ans) {
									$surveyAnswers = unserialize($ans['sa_survey_answer_data']);
									$answers = json_decode($surveyAnswers, TRUE);
									if(isset($answers[$name])) {
										$count[$name]['count'] += 1;
										if($i == $answers[$name]) {
											$count[$name][$i] += 1;
										}
									}
								}
							}
						}
					} else {
						for($i = $choiceMin; $i <= $choiceMax; $i += $choiceStep) {
							$count[$name]['type'] = $element['type'];
							$count[$name]['count'] = 0;
							$count[$name][$i] = 0;
							foreach($surveyAnswersModel as $ans) {
								$surveyAnswers = unserialize($ans['sa_survey_answer_data']);
								$answers = json_decode($surveyAnswers, TRUE);
								if(isset($answers[$name])) {
									$count[$name]['count'] += 1;
									if($i == $answers[$name]) {
										$count[$name][$i] += 1;
									}
								}
							}
						}
					}
				} else
				if($element['type'] == 'rating') {
					if(isset($element['rateValues'])) {
						$c = 0;
						foreach($element['rateValues'] as $rateValue) {
							$count[$name]['type'] = $element['type'];
							$count[$name]['count'] = 0;
							if(!is_array($rateValue)) {
								$option = $rateValue;
							} else {
								$option = $rateValue['value'];
							}
							$count[$name][$option] = 0;
							foreach($surveyAnswersModel as $ans) {
								$surveyAnswers = unserialize($ans['sa_survey_answer_data']);
								$answers = json_decode($surveyAnswers, TRUE);
								if(isset($answers[$name])) {
									$count[$name]['count'] += 1;
									if($option == $answers[$name]) {
										$count[$name][$option] += 1;
									}
								}
							}
							$old = $count[$name][$option];
							unset($count[$name][$option]);
							if(!is_array($rateValue)) {
								$option = 'option not defined_' . $c++;
							} else {
								if(is_array($rateValue['text'])) {
									if($surveyModel->survey_language == 'de') {
										if(isset($rateValue['text']['default'])) {
											$option = $rateValue['text']['default'];
										} else {
											$option = $rateValue['text']['fr'];
										}
									} else {
										$option = $rateValue['text'][$surveyModel->survey_language];
									}
								} else {
									$option = $rateValue['text'];
								}
							}

							$count[$name][$option] = $old;
						}
					} else {
						$minRateValue = (isset($element['rateMin'])) ? $element['rateMin'] : 1;
						$maxRateValue = (isset($element['rateMax'])) ? $element['rateMax'] : 5;
						$rateStep = (isset($element['rateStep'])) ? $element['rateStep'] : 1;
						for($i = $minRateValue; $i <= $maxRateValue; $i += $rateStep) {
							$count[$name]['type'] = $element['type'];
							$count[$name]['count'] = 0;
							$count[$name][$i] = 0;
							$count[$name]['maxRateValue'] = $maxRateValue;
							foreach($surveyAnswersModel as $ans) {
								$surveyAnswers = unserialize($ans['sa_survey_answer_data']);
								$answers = json_decode($surveyAnswers, TRUE);
								if(isset($answers[$name])) {
									$count[$name]['count'] += 1;
									if($i == $answers[$name]) {
										$count[$name][$i] += 1;
									}
								}
							}
						}
					}
				} else
				if($element['type'] == 'text' || $element['type'] == 'comment') {
					$textAnswers = array();
					$count[$name] = array();
					if(!empty($surveyAnswersModel)) {
						foreach($surveyAnswersModel as $ans) {
							$surveyAnswers = unserialize($ans['sa_survey_answer_data']);
							$answers = json_decode($surveyAnswers, TRUE);

							if(isset($answers[$name])) {
								foreach($answers as $k => $v) {
								$textAns = array();
									if($k == $name) {
										$entityId = $ans['e_email'];
										$textAns['email'] = $entityId;
										$textAns['answer'] = $v;
										array_push($textAnswers, $textAns);
									}
								}
								$count[$name] = $textAnswers;
							}
							$count[$name]['type'] = $element['type'];
						}
					} else {
						$count[$name]['type'] = $element['type'];
					}

				}
			}
		}
		$this->view->count = $count;
	}


	/**
	 * Send Test Push Mail action.
	 *
	 * @return void
	 */
	public function sendTestPushmailsAjaxAction ()
	{
		if ($this->getRequest()->isXmlHttpRequest()) {
			$requestParams = $this->_request->getParams();

			//get survey model.
			$surveyModel = Default_Model_Survey::getModelByID($requestParams['id']);

			$this->view->surveyTitle = $surveyModel->name;

			//get all users and email to them
			$result = PRJ_SurveyEmail::sendTestMail($requestParams,'survey', base64_encode($surveyModel->short));

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
	 * Ajax for getting user details from selected contract type and region ids.
	 *
	 * @return void
	 */
	public function getSelectedUserAjaxAction() {
		if ($this->getRequest()->isXmlHttpRequest()) {
			$requestParams = $this->_request->getParams();

			//get all users and email to them
			$result = PRJ_SurveyEmail::getUserFromSelection('SurveyM2nDepartment','Survey',$requestParams);

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
	 * Ajax for sending emails in sets
	 *
	 * @return void
	 */
	public function mailProcessAjaxAction(){
		if ($this->getRequest()->isXmlHttpRequest()) {
			$requestParams = $this->_request->getParams();

			//get links for email.
			$surveyId = $requestParams['id'];
			$surveyModel = Default_Model_Survey::getModelByID($surveyId);

			$this->view->surveyTitle = $surveyModel->name;

			//get all users and email to them
			$result = PRJ_SurveyEmail::processSurveyEmail('SurveyM2nDepartment', 'Survey', $requestParams, 'survey', base64_encode($surveyModel->short));

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
