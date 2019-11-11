<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/controllers/DatesController.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: DatesController.php 299 2018-12-11 18:50:38Z dp $
 */

/**
 *
 *
 * Admin_DatesController
 *
 *
 */
class Admin_DatesController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_Dates';
	private $_modelListShort = 'Dates';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Dates';

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
	 * Initializes Admin_DatesController.
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
			->setButton('Send PushMail', array('action'=>'update', 'controller'=>'dates', 'module'=>'admin'), 'update', TRUE)
			->setButton('Teilnehmerliste', array('action'=>'download-list', 'controller'=>'dates', 'module'=>'admin'), 'download-list', TRUE)
//			->disableSaveWhere()
//			->useDbWhere(FALSE)
//			->showAjax();
//			->doNotRedirect()
//			->setDeleteOldList()
		;

		$this->_modelListConfig = array(
			'order'=>array(
				'uname',
				'publish_datetime',
				'title',
				'description',
				'content',
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
				'fr_media_id'=>'/medias/dates/fr',
				'de_media_id'=>'/medias/dates/de',
				'fr_presentation_media_id'=>'/medias/dates/frPresentation',
				'de_presentation_media_id'=>'/medias/dates/dePresentation',
			),
			'mediaRole'=>array(
				'fr_media_id'=>'user',
				'de_media_id'=>'user',
				'fr_presentation_media_id'=>'user',
				'de_presentation_media_id'=>'user',
			),
			'columnLabels'=>array(
				'dates_media_image_id'=>'Image',
				'relation_m2n_datesm2nregion' => 'Region',
				'relation_m2n_datesm2ncontracttype' => 'Contract',
				'relation_m2n_datesm2nbrand' => 'Brand',
			),
			'buttonLabel'=>'Save',
			'columnTypes'=>array(
				'title'=>'text',
				'description'=>'textarea',
				'subject_of_negotiations'=>'textarea',
				'comment'=>'textarea',
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
			),
			'addStandardColumnValues'=>array(
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
				'PRJ_Sitemap_Create_AfterSave',
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
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
				'PRJ_Sitemap_Edit_AfterSave',
			),
		)));
	}

	/**
	 * PDF action.
	 *
	 * @return void
	 */
	public function exportAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Export');

		/**
		 * this can go on for 5 minutes
		 */
		set_time_limit(300);

		/**
		 * start model list
		 */
		$this->_modelList->exportModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
		)));
	}

	/**
	 * Send Push Mail action.
	 *
	 * @return void
	 */
	public function updateAction ()
	{
		$protocolId = $this->_request->getParam('id');
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
		$backUrl = $this->view->url(array('action'=>'list','controller'=>'dates','module'=>'admin','modelListName'=>'Default_Model_Dates') , NULL, TRUE) . $paramValues;
		$currentUrl = $this->view->url(array('action'=>'update','controller'=>'dates','module'=>'admin','modelListName'=>'Default_Model_Dates') , NULL, TRUE) . $paramValues;

		//redirect to index page if result not found.
		if(!PRJ_PushEmail::getSelectionLists('Default_Model_Dates','DatesM2nDepartment','Dates',$protocolId)){
			$this->_redirect($backUrl);
		}

		$this->view->protocolId = $protocolId;
		$this->view->redirectUrl = $backUrl;
		$this->view->currentUrl = $currentUrl;
		$this->view->isTested = $isTested === 'TRUE' ? TRUE : FALSE;
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
			$protocolId = $requestParams['id'];
			$protocolModel = Default_Model_Dates::getModelByID($protocolId);

			//get all users and email to them
			$result = PRJ_PushEmail::processPushEmail('DatesM2nDepartment', 'Dates', $requestParams, 'dates', $protocolModel->short);

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
	public function getSelectedUserAjaxAction(){
		if ($this->getRequest()->isXmlHttpRequest()) {
			$requestParams = $this->_request->getParams();

			//get all users and email to them
			$result = PRJ_PushEmail::getUserFromSelection('DatesM2nDepartment','Dates',$requestParams);

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
	 * download event list excel sheet action.
	 *
	 * @return void
	 */
	public function downloadListAction ()
	{
		$dateId = $this->_request->getParam('id');

		$dateModel = Default_Model_Dates::getModelByID($dateId);

		//get a new ArrayIterator for sorting the questions with `position` in DB
		$dateQuestions = $dateModel->DatesM2nQuestions->getIterator();

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

		$questions = array();
		foreach ($dateQuestions as $value) {
			if(empty($value->Question->parent_question_id)){
				//add question in list
				array_push($questions, array(
					'id' => $value->question_id,
					'title' => $value->Question->title
				));

				$childQuestions = Default_Model_DatesQuestions::createQuery()
					->addWhere('parent_question_id = ?', $value->question_id)
					->execute()
				;
				if($childQuestions->count() > 0) {
					foreach ($childQuestions as $childQuestion) {
						if (in_array($childQuestion->id, $questionIds)) {
							//add child questions if found
							array_push($questions, array(
								'id' => $childQuestion->id,
								'title' => $childQuestion->title
							));
						}
					}
				}
			}
		}

		$userIds = array();
		foreach ($dateModel->DatesParticipants as $participants){
			if (!in_array($participants->user_id,$userIds)){
				//add in user id in array
				$userIds[] = $participants->user_id;
			}
		}

		/**
		 * Create excel object
		 */
		require_once BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'IOFactory.php';
		$excelObj = new PHPExcel();

		$excelObj->getProperties()
			->setCreator("VSOH Admin")
			->setTitle("Event_" . $dateModel->title)
			->setDescription("Details of participants in the event - " . $dateModel->title . ".")
		;

		/**
		 * Define styles for border, header and content
		 */
		$defaultBorder = array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb'=>'000000')
		);
		$headerStyle = array(
			'borders' => array(
				'allborders' => $defaultBorder
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'b8cb00'),
			),
			'font' => array(
				'bold' => true,
				'size' => 12
			)
		);
		$contentStyle = array(
			'borders' => array(
				'allborders' => $defaultBorder
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'ffffff'),
			),
			'font' => array(
				'size' => 12
			)
		);

		$worksheet = $excelObj->setActiveSheetIndex(0);

		/*
		 * Display headings
		 */
		$currentRow = 1;
		$colStringIndex = 0;

		//event name
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'A'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('Veranstaltungsname'));
		$colStringIndex++;

		//CADC
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'B'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('Cadc'));
		$colStringIndex++;

		//User name
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'C'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('User'));
		$colStringIndex++;

		//Total participants
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'D'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('Begelitpersonen (engl. Accompaniment)'));
		$colStringIndex++;

		//Participants name
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'E'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('Begelitpersonen (Names)'));
		$colStringIndex++;

		//Email
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'F'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('Email'));
		$colStringIndex++;

		//Street
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'G'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('Street'));
		$colStringIndex++;

		//Zip
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'H'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('Zip'));
		$colStringIndex++;

		//City
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'I'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('City'));
		$colStringIndex++;

		//Phone
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'J'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('Phone'));
		$colStringIndex++;

		//Company
		$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'K'
		$worksheet->setCellValue($colString . $currentRow,$this->view->translate('Company'));
		$colStringIndex++;

		//Questions
		foreach ($questions as $question) {
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);
			$worksheet->setCellValue($colString . $currentRow, $question['title']);
			$colStringIndex++;
		}

		//apply heading style
		$excelObj->getActiveSheet()->getStyle('A'.$currentRow.':'.$colString.$currentRow)->applyFromArray($headerStyle);

		//set auto size column width to all columns
		foreach(range('A',$colString) as $columnID) {
			$excelObj->getActiveSheet()
				->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		/*
		 * Display event details
		 */

		//change row
		$currentRow++;

		//display Event name
		$colString = PHPExcel_Cell::stringFromColumnIndex(0);//Column 'A'
		$worksheet->setCellValue($colString . $currentRow, $dateModel->title);

		foreach ($userIds as $userId) {
			$colStringIndex = 1;

			//get entity model
			$entityModel = Default_Model_Entity::getModelByID($userId);

			//display user's CADC code
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'B'
			$worksheet->setCellValue($colString . $currentRow, $entityModel->cadc);
			$colStringIndex++;

			//display user name - firstname + lastname
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'C'
			$worksheet->setCellValue($colString . $currentRow, $entityModel->firstname . ' ' . $entityModel->lastname);
			$colStringIndex++;

			/*
			 * get participants details
			 */
			$datesParticipantsModel = Doctrine_Query::create()
				->from('Default_Model_DatesParticipants')
				->addWhere('user_id = ?', array($userId))
				->addWhere('date_id = ?', array($dateId))
			;

			$totalParticipants = $datesParticipantsModel->count();
			$participants = '';
			foreach ($datesParticipantsModel->execute() as $participantKey => $participantValue){
				if ($participantKey === ($totalParticipants-1)){
					//is last element
					$participants .= trim($participantValue->participant_name);
				} else{
					$participants .= trim($participantValue->participant_name) . ', ';
				}
			}

			//display total participants
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'D'
			$worksheet->setCellValue($colString . $currentRow, $totalParticipants);
			$colStringIndex++;

			//display participants
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'E'
			$worksheet->setCellValue($colString . $currentRow, $participants);
			$colStringIndex++;

			//display email
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'F'
			$worksheet->setCellValue($colString . $currentRow, $entityModel->email);
			$colStringIndex++;

			//display street
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'G'
			$worksheet->setCellValue($colString . $currentRow, $entityModel->street);
			$colStringIndex++;

			//display zip
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'H'
			$worksheet->setCellValue($colString . $currentRow, $entityModel->zip);
			$colStringIndex++;

			//display city
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'I'
			$worksheet->setCellValue($colString . $currentRow, $entityModel->city);
			$colStringIndex++;

			//display phone
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'J'
			$worksheet->setCellValue($colString . $currentRow, $entityModel->phone);
			$colStringIndex++;

			//display company
			$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);//Column 'K'
			$worksheet->setCellValue($colString . $currentRow, $entityModel->company);
			$colStringIndex++;

			//answers of the questions
			foreach ($questions as $value) {
				//find answer
				$answerModel = Doctrine_Query::create()
					->from('Default_Model_DatesParticipantsAnswers')
					->addWhere('user_id = ?', array($userId))
					->addWhere('date_id = ?', array($dateId))
					->addWhere('question_id = ?', array($value['id']))
					->limit(1)
					->execute()
					->getFirst()
				;

				if($answerModel)
					$answer = $answerModel->answer;
				else
					$answer = '';

				$colString = PHPExcel_Cell::stringFromColumnIndex($colStringIndex);
				$worksheet->setCellValue($colString . $currentRow, $answer);
				$colStringIndex++;
			}

			//add new row if entry not last
			if($userId != end($userIds))
				$currentRow++;
		}

		//apply content style
		$excelObj->getActiveSheet()->getStyle('A2:'.$colString.$currentRow)->applyFromArray($contentStyle);

		/**
		 * Produce output and force download at browser
		 */
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Event_' . $dateModel->title . '.xlsx"');

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, 'Excel2007');
		$objWriter->save('php://output');

		exit;
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

			//get protocol model.
			$protocolModel = Default_Model_Dates::getModelByID($requestParams['id']);

			//get all users and email to them
			$result = PRJ_PushEmail::sendTestMail($requestParams,'dates', $protocolModel->short);

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