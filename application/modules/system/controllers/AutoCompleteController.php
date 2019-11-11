<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/AutoCompleteController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: AutoCompleteController.php 338 2015-04-28 09:59:47Z nm $
 */

/**
 *
 *
 * System_AutoCompleteController
 *
 *
 */
class System_AutoCompleteController extends L8M_Controller_Action
{

	/**
	 *
	 *
	 * Initialization Methods
	 *
	 *
	 */

	/**
	 * Initializes System_AutoCompleteController.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * if Dojo is disabled, redirect to ErrorController
		 */
//		if (!Zend_Registry::isRegistered('dojoEnabled') ||
//			Zend_Registry::get('dojoEnabled') !== TRUE ||
//			!$this->getRequest()->isXmlHttpRequest()) {
//			$this->_redirect($this->_helper->url('error404', 'error', 'default'));
//		}

	}

	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Application action.
	 *
	 * @return void.
	 */
	public function applicationAction()
	{

		/**
		 * select
		 */
		$select = new Zend_Db_Select($this->getDatabaseAdapter('database'));

		/**
		 * client id
		 */
		if ($this->getRequest()->getParam('client_id', NULL, FALSE)) {
			$select->from('application',
					  array(
					  		'id'=>'application.id',
					  		'name'=>'application.name',
					  ))
				   ->where('application.client_id = ?',
				   		   $this->getRequest()->getParam('client_id', NULL, FALSE))
				   ->order('application.name ASC');
		} else {

			/**
			 * default
			 */
			$select->from('application',
					  array(
					  		'id'=>'application.id',
					  		'name'=>'CONCAT(client.name, " / ", application.name)',
					  ))
				   ->joinLeft('client',
				   			  'client.id = application.client_id')
				   ->order('CONCAT(client.name, " / ", application.name) ASC');
		}

		/**
		 * items
		 */
		$items = $this->getDatabaseAdapter('default')->fetchAll($select);

		/**
		 * data
		 */
		$data = array();
		$optional = $this->getRequest()->getParam('optional', NULL, FALSE);
		if ($optional) {
			$data[] = array(
							'id'=>NULL,
							'name'=>NULL,
			);
		}

		if (count($items)>0) {
			foreach($items as $item) {
				$data[] = array(
								'id'=>$item['id'],
								'name'=>$item['name'],
				);
			}
		}

		$this->_helper->autoCompleteDojo(new Zend_Dojo_Data('id', $data));

	}

	/**
	 * Module action.
	 *
	 * @return void.
	 */
	public function moduleAction()
	{
		/**
		 * select
		 */
		$select = new Zend_Db_Select($this->getDatabaseAdapter('default'));

		/**
		 * default
		 */
		$select->from('module',
					  array(
					  		'id'=>'module.id',
					  		'name'=>'module.name',
					  ))
			   ->order('module.name ASC');

		/**
		 * items
		 */
		$items = $this->getDatabaseAdapter('default')->fetchAll($select);
		$items = array_merge(array(array('id'=>'-1', 'name'=>'-')), $items);

		/**
		 * data
		 */
		$data = array();
		$optional = $this->getRequest()->getParam('optional', NULL, FALSE);
		if ($optional) {
			$data[] = array(
							'id'=>NULL,
							'name'=>NULL,
			);
		}

		if (count($items)>0) {
			foreach($items as $item) {
				$data[] = array(
								'id'=>$item['id'],
								'name'=>$item['name'],
				);
			}
		}

		$this->_helper->autoCompleteDojo(new Zend_Dojo_Data('id', $data));

	}

	/**
	 * Controller action.
	 *
	 * @return void.
	 */
	public function controllerAction()
	{
		/**
		 * select
		 */
		$select = new Zend_Db_Select($this->getDatabaseAdapter('default'));

		/**
		 * module id
		 */
		if ($this->getRequest()->getParam('module_id', NULL, FALSE)) {
			$select->from('controller',
						  array(
						  		'id'=>'controller.id',
						  		'name'=>'controller.name',
						  ))
				   ->where('controller.module_id = ?',
				   		   $this->getRequest()->getParam('module_id', NULL, FALSE))
				   ->order('controller.name ASC');
			/**
			 * items
			 */
			$items = $this->getDatabaseAdapter('default')->fetchAll($select);
		} else {
			$items = array();
		}
		$items = array_merge(array(array('id'=>'-1', 'name'=>'-')), $items);



		/**
		 * data
		 */
		$data = array();
		$optional = $this->getRequest()->getParam('optional', NULL, FALSE);
		if ($optional) {
			$data[] = array(
							'id'=>NULL,
							'name'=>NULL,
			);
		}

		if (count($items)>0) {
			foreach($items as $item) {
				$data[] = array(
								'id'=>$item['id'],
								'name'=>$item['name'],
				);
			}
		}

		$this->_helper->autoCompleteDojo(new Zend_Dojo_Data('id', $data));
	}

	/**
	 * Action action.
	 *
	 * @return void.
	 */
	public function actionAction()
	{
		/**
		 * select
		 */
		$select = new Zend_Db_Select($this->getDatabaseAdapter('default'));

		/**
		 * module id
		 */
		if ($this->getRequest()->getParam('module_id', NULL, FALSE)) {
			$select->from('action',
						array(
								'id'=>'action.id',
								'name'=>'CONCAT(controller.name, " / ",
												action.name)',
						))
					->joinLeft('controller',
							'controller.id = action.controller_id')
					->where('controller.module_id = ?',
							$this->getRequest()->getParam('module_id', NULL, FALSE))
					->order('CONCAT(controller.name, " / ",
									action.name) ASC');
		} else

		/**
		 * controller id
		 */
		if ($this->getRequest()->getParam('controller_id', NULL, FALSE)) {
			$select->from('action',
						array(
							'id'=>'action.id',
							'name'=>'action.name',
						))
					->where('action.controller_id = ?',
						$this->getRequest()->getParam('controller_id', NULL, FALSE))
					->order('action.name ASC');
		} else {

			/**
			 * default
			 */
			$select->from('action',
						  array(
						  		'id'=>'action.id',
						  		'name'=>'CONCAT(module.name, " / ",
						  						controller.name, " / ",
						  						action.name)',
						  ))
				   ->joinLeft('controller',
							  'controller.id = controller.module_id')
				   ->joinLeft('module',
							  'module.id = controller.module_id')
				   ->order('CONCAT(module.name, " / ",
				   				   controller.name, " / ",
			  					   action.name) ASC');
		}

		/**
		 * items
		 */
		$items = $this->getDatabaseAdapter('default')->fetchAll($select);

		/**
		 * data
		 */
		$data = array();
		$optional = $this->getRequest()->getParam('optional', NULL, FALSE);
		if ($optional) {
			$data[] = array(
							'id'=>NULL,
							'name'=>NULL,
			);
		}

		if (count($items)>0) {
			foreach($items as $item) {
				$data[] = array(
								'id'=>$item['id'],
								'name'=>$item['name'],
				);
			}
		}

		$this->_helper->autoCompleteDojo(new Zend_Dojo_Data('id', $data));
	}

	/**
	 * Action action.
	 *
	 * @return void.
	 */
	public function actAction()
	{
		/**
		 * select
		 */
		$select = new Zend_Db_Select($this->getDatabaseAdapter('default'));

		/**
		 * client id
		 */
		if ($this->getRequest()->getParam('client_id', NULL, FALSE)) {
			$select->from('action',
						  array(
						  		'id'=>'action.id',
						  		'name'=>'CONCAT(application.name, " / ",
						  						module.name, " / ",
						  						controller.name, " / ",
						  						action.name)',
						  ))
				   ->joinLeft('controller',
							  'controller.id = action.controller_id')
				   ->joinLeft('module',
							  'module.id = controller.module_id')
				   ->joinLeft('application',
							  'application.id = module.application_id')
				   ->where('application.client_id = ?',
				   		   $this->getRequest()->getParam('client_id', NULL, FALSE))
				   ->order('CONCAT(application.name, " / ",
				   				   module.name, " / ",
				   				   controller.name, " / ",
				   				   action.name) ASC');
		} else

		/**
		 * application id
		 */
		if ($this->getRequest()->getParam('application_id', NULL, FALSE)) {
			$select->from('action',
						  array(
						  		'id'=>'action.id',
						  		'name'=>'CONCAT(module.name, " / ",
						  						controller.name, " / ",
						  						action.name)',
						  ))
				   ->joinLeft('controller',
							  'controller.id = action.controller_id')
				   ->joinLeft('module',
							  'module.id = controller.module_id')
				   ->where('module.application_id = ?',
				   		   $this->getRequest()->getParam('application_id', NULL, FALSE))
				   ->order('CONCAT(module.name, " / ",
				   				   controller.name, " / ",
				   				   action.name) ASC');
		} else

		/**
		 * module id
		 */
		if ($this->getRequest()->getParam('module_id', NULL, FALSE)) {
			$select->from('action',
						  array(
						  		'id'=>'action.id',
						  		'name'=>'CONCAT(controller.name, " / ",
						  						action.name)',
						  ))
				   ->joinLeft('controller',
							  'controller.id = action.controller_id')
				   ->where('controller.module_id = ?',
				   		   $this->getRequest()->getParam('module_id', NULL, FALSE))
				   ->order('CONCAT(controller.name, " / ",
				   				   action.name) ASC');
		} else

		/**
		 * controller id
		 */
		if ($this->getRequest()->getParam('controller_id', NULL, FALSE)) {
			$select->from('action',
						  array(
						  		'id'=>'action.id',
						  		'name'=>'action.name',
						  ))
				   ->where('action.controller_id = ?',
				   		   $this->getRequest()->getParam('controller_id', NULL, FALSE))
				   ->order('action.name ASC');
		} else {

			/**
			 * default
			 */
			$select->from('action',
						  array(
						  		'id'=>'action.id',
						  		'name'=>'CONCAT(client.name, " / ",
						  						application.name, " / ",
						  						module.name, " / ",
						  						controller.name, " / ",
						  						action.name)',
						  ))
				   ->joinLeft('controller',
							  'controller.id = controller.module_id')
				   ->joinLeft('module',
							  'module.id = controller.module_id')
				   ->joinLeft('application',
							  'application.id = module.application_id')
				   ->joinLeft('client',
							  'client.id = application.client_id')
				   ->order('CONCAT(client.name, " / ",
				   				   application.name, " / ",
				   				   module.name, " / ",
				   				   controller.name, " / ",
			  					   action.name) ASC');
		}

		/**
		 * items
		 */
		$items = $this->getDatabaseAdapter('default')->fetchAll($select);

		/**
		 * data
		 */
		$data = array();
		$optional = $this->getRequest()->getParam('optional', NULL, FALSE);
		if ($optional) {
			$data[] = array(
							'id'=>NULL,
							'name'=>NULL,
			);
		}

		if (count($items)>0) {
			foreach($items as $item) {
				$data[] = array(
								'id'=>$item['id'],
								'name'=>$item['name'],
				);
			}
		}

		$this->_helper->autoCompleteDojo(new Zend_Dojo_Data('id', $data));
	}

	/**
	 * AutoComplete LoadAction
	 *
	 * used by L8M_JQuery_Form_Element_Position
	 *
	 */
	public function positionAction() {
		$this->view->modelCollection = NULL;

		if ($this->getRequest()->isXmlHttpRequest()) {
			$paramFunction = strtolower($this->getRequest()->getParam('function', NULL, FALSE));
			if ($paramFunction !== 'update') {
				$paramFunction = 'load';
			}

			$paramPosDirection = strtolower($this->getRequest()->getParam('posDirection', NULL, FALSE));
			if ($paramPosDirection !== 'append') {
				$paramPosDirection = 'prepend';
			}

			$paramParentID = $this->getRequest()->getParam('parentID', NULL, FALSE);
			$paramParentRelation = $this->getRequest()->getParam('parentRelation', NULL, FALSE);

			$paramModel = $this->getRequest()->getParam('model', NULL, FALSE);
			$paramModelID = $this->getRequest()->getParam('modelID', NULL, FALSE);
			$paramOffset = $this->getRequest()->getParam('offset', NULL, FALSE);

			$positionModel = new L8M_Model_Position($paramModelID, $paramModel, $paramParentRelation);
			$positionModel->setParent($paramParentID);
			if ($paramOffset !== NULL) {
				$paramOffset = (int) $paramOffset;
			}
			$data = $positionModel->load($paramOffset);
			$data['function'] = $paramFunction;
			$data['posDirection'] = $paramPosDirection;

			$bodyData = Zend_Json_Encoder::encode($data);

			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			$this->getResponse()
				->setHeader('Content-Type', 'application/json')
				->setBody($bodyData)
			;
		}
	}

	/**
	 * AutoComplete LoadAction
	 *
	 * used by L8M_JQuery_Form_Element_M2N
	 *
	 */
	public function loadAction() {

		$this->view->modelCollection = array();

		if ($this->getRequest()->isXmlHttpRequest()) {
			$paramModel = $this->getRequest()->getParam('model', NULL, FALSE);
			$paramUseSortColumn = $this->getRequest()->getParam('useSortColumn', NULL, FALSE);
			$paramOffset = $this->getRequest()->getParam('offset', NULL, FALSE);
			$paramElementName = $this->getRequest()->getParam('element', NULL, FALSE);
			$paramUseValueColumn = $this->getRequest()->getParam('useValueColumn', NULL, FALSE);
			$paramUseIDColumn = $this->getRequest()->getParam('useIDColumn', NULL, FALSE);
			$paramSearch = $this->getRequest()->getParam('search', NULL, FALSE);
			$paramFullView = $this->getRequest()->getParam('fullView', NULL, FALSE);
			$paramMediaFolderID = $this->getRequest()->getParam('mediaFolderID', NULL, FALSE);

			$paramConditionLikes = $this->getRequest()->getParam('like', NULL, FALSE);
			$paramConditionDifferences = $this->getRequest()->getParam('difference', NULL, FALSE);
			$paramConditionIsNulls = $this->getRequest()->getParam('isnull', NULL, FALSE);
			$paramConditionValues = $this->getRequest()->getParam('value', NULL, FALSE);
			$paramConditionKeys = $this->getRequest()->getParam('key', NULL, FALSE);

			if (strpos($paramModel, 'Default_Model_') === FALSE) {
				$paramModel = 'Default_Model_' . $paramModel;
			}
			if (class_exists($paramModel)) {
				$query = Doctrine_Query::create()
					->from($paramModel . ' m')
				;

				$model = new $paramModel();
				$columnDefinitions = $model->getTable()->getColumns();
				$modelRelations = $model->getTable()->getRelations();
				$transColumnDefinitions = array();
				if (array_key_exists('Translation', $modelRelations)) {
					$transColumnDefinitions = $model['Translation']->getTable()->getColumns();
					$query = $query
						->leftJoin('m.Translation mt')
					;
				}

				/**
				 * search
				 */
				if ($paramSearch &&
					(array_key_exists($paramUseValueColumn, $columnDefinitions) || array_key_exists($paramUseValueColumn, $transColumnDefinitions))) {

					$referencedModel = 'm';
					if (array_key_exists($paramUseValueColumn, $transColumnDefinitions)) {
						$referencedModel = 'mt';
						$query = $query
							->addWhere('mt.lang = ? ', array(L8M_Locale::getLang()))
						;
					}
					$query = $query
						->addWhere($referencedModel . '.' . $paramUseValueColumn . ' LIKE ? ', array('%' . $paramSearch . '%'))
					;
				}

				/**
				 * media
				 */
				if ($model instanceof Default_Model_Media) {

					if ($paramMediaFolderID) {
						$query = $query
							->addWhere('m.media_folder_id = ? ', array($paramMediaFolderID))
						;
					} else {
						$query = $query
							->addWhere('m.media_folder_id IS NULL ', array())
						;
					}
				}

				/**
				 * condition
				 */
				$countConditions = count($paramConditionKeys);
				if ($countConditions == count($paramConditionLikes) &&
					$countConditions == count($paramConditionDifferences) &&
					$countConditions == count($paramConditionIsNulls) &&
					$countConditions == count($paramConditionValues)) {

					$sConditionsCounter = 0;
					for ($sConditionsCounter = 0; $sConditionsCounter < $countConditions; $sConditionsCounter++) {
						if (array_key_exists($sConditionsCounter, $paramConditionKeys) &&
							array_key_exists($sConditionsCounter, $paramConditionLikes) &&
							array_key_exists($sConditionsCounter, $paramConditionDifferences) &&
							array_key_exists($sConditionsCounter, $paramConditionIsNulls) &&
							array_key_exists($sConditionsCounter, $paramConditionValues) &&
							array_key_exists($paramConditionKeys[$sConditionsCounter], $columnDefinitions)) {

							if ($paramConditionIsNulls[$sConditionsCounter] === '1') {
								$query = $query->addWhere('m.' .  $paramConditionKeys[$sConditionsCounter] . ' IS NULL', array());
							} else
							if ($paramConditionLikes[$sConditionsCounter] === '1') {
								$query = $query->addWhere('m.' .  $paramConditionKeys[$sConditionsCounter] . ' LIKE ? ', array($paramConditionValues[$sConditionsCounter]));
							} else
							if ($paramConditionLikes[$sConditionsCounter] === '0') {
								$query = $query->addWhere('m.' .  $paramConditionKeys[$sConditionsCounter] . ' NOT LIKE ? ', array($paramConditionValues[$sConditionsCounter]));
							} else
							if ($paramConditionDifferences[$sConditionsCounter] === 'lt') {
								$query = $query->addWhere('m.' .  $paramConditionKeys[$sConditionsCounter] . ' < ? ', array($paramConditionValues[$sConditionsCounter]));
							} else
							if ($paramConditionDifferences[$sConditionsCounter] === 'lte') {
								$query = $query->addWhere('m.' .  $paramConditionKeys[$sConditionsCounter] . ' <= ? ', array($paramConditionValues[$sConditionsCounter]));
							} else
							if ($paramConditionDifferences[$sConditionsCounter] === 'gt') {
								$query = $query->addWhere('m.' .  $paramConditionKeys[$sConditionsCounter] . ' > ? ', array($paramConditionValues[$sConditionsCounter]));
							} else
							if ($paramConditionDifferences[$sConditionsCounter] === 'gte') {
								$query = $query->addWhere('m.' .  $paramConditionKeys[$sConditionsCounter] . ' >= ? ', array($paramConditionValues[$sConditionsCounter]));
							} else {
								$query = $query->addWhere('m.' .  $paramConditionKeys[$sConditionsCounter] . ' = ? ', array($paramConditionValues[$sConditionsCounter]));
							}
						}
					}
				}

				if (!$paramUseSortColumn) {
					if ($paramUseValueColumn &&
						array_key_exists($paramUseValueColumn, $columnDefinitions)) {

						$paramUseSortColumn = $paramUseValueColumn;
					} else
					if (array_key_exists('name', $columnDefinitions)) {
						$paramUseSortColumn = 'name';
					} else
					if (array_key_exists('short', $columnDefinitions)) {
						$paramUseSortColumn = 'short';
					} else
					if (array_key_exists('value', $columnDefinitions)) {
						$paramUseSortColumn = 'value';
					} else
					if (array_key_exists('login', $columnDefinitions)) {
						$paramUseSortColumn = 'login';
					} else
					if (array_key_exists('id', $columnDefinitions)) {
						$paramUseSortColumn = 'id';
					}
				}
				if ($paramUseSortColumn) {
					if (array_key_exists($paramUseSortColumn, $columnDefinitions)) {
						$query = $query
							->orderBy('m.' . $paramUseSortColumn . ' ASC')
						;
					}
				}

				if ($paramUseValueColumn) {
					if (!array_key_exists($paramUseValueColumn, $columnDefinitions)) {
						if (!array_key_exists($paramUseValueColumn, $transColumnDefinitions)) {
							$paramUseValueColumn = 'id';
						}
					}
				} else {
					$paramUseValueColumn = 'id';
				}

				if ($paramUseIDColumn) {
					if (!array_key_exists($paramUseIDColumn, $columnDefinitions)) {
						$paramUseIDColumn = 'id';
					}
				} else {
					$paramUseIDColumn = 'id';
				}

				if ($paramFullView) {
					$this->view->fullView = TRUE;

					$queryCopy = $query->copy();

					$this->view->fullCount = $queryCopy
						->select('COUNT(m.id)')
						->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR)
						->execute()
					;
				} else {
					$this->view->fullView = FALSE;
				}

				$this->view->modelCollection = $query
					->offset($paramOffset)
					->limit(75)
					->execute()
				;

				$this->view->elementName = $paramElementName;
				$this->view->useIDColumn = $paramUseIDColumn;
				$this->view->useValueColumn = $paramUseValueColumn;
			}
			Zend_Layout::getMvcInstance()->disableLayout();
		}
	}

	/**
	 * AutoComplete AutoAction
	 *
	 * used by L8M_JQuery_Form_Element_Select
	 *
	 */
	public function autoAction() {

		/**
		 * retrieve params
		 */
		$paramKey = $this->getRequest()->getParam('key', NULL, FALSE);
		$paramColumn = strtolower($this->getRequest()->getParam('column', NULL, FALSE));
		$paramValue = $this->getRequest()->getParam('value', NULL, FALSE);

		/**
		 * empty items
		 */
		$items = array();

		/**
		 * do we have a request to work with?
		 */
		if ($paramKey &&
			$paramColumn &&
			$paramValue) {

			/**
			 * filter camelCase
			 */

			/**
			 * build model name
			 */
			$modelName = 'Default_Model_' . ucfirst($paramKey);

			/**
			 * does model exist
			 */
			if (class_exists($modelName)) {

				/**
				 * try and look for constructed model 'cause of translation
				 */
				$model = new $modelName;

				/**
				 * start model request for rows
				 */
				$modelQuery = Doctrine_Query::create()
					->from($modelName . ' m')
					->addWhere('m.' . $paramColumn . ' = ? ', array($paramValue))
				;

				/**
				 * do we have a translation for that model
				 */
				if ($model->hasRelation('Translation')) {

					$modelQuery
						->leftJoin('m.Translation mt')
						->addWhere('mt.lang = ? ', array($this->_getLanguage()))
					;
				}

				/**
				 * retrieve models
				 */
				$modelCollection = $modelQuery->execute();

				/**
				 * build up items
				 */
				if ($modelCollection !== FALSE) {
					foreach ($modelCollection as $row) {

						/**
						 * do we have a translation relation
						 */
						if ($row->contains('Translation')) {
							$rowTranslation = $row->Translation[$this->_getLanguage()];
						} else {
							$rowTranslation = FALSE;
						}

						/**
						 * use id as value
						 */
						$optionValue = $row['id'];

						/**
						 * do we have a name to work with
						 */
						if ($row->contains('name')) {

							$optionKey = $row['name'];
						} else

						/**
						 * maybe a short we could work with
						 */
						if ($row->contains('short')) {

							$optionKey = $row['short'];
						} else

						/**
						 * maybe a value we could work with
						 */
						if ($row->contains('value')) {

							$optionKey = $row['value'];
						} else {

							if ($rowTranslation !== FALSE) {

								/**
								 * so let's try with some translation
								 */

								/**
								 * do we have a name to work with
								 */
								if ($rowTranslation->contains('name')) {

									$optionKey = $rowTranslation['name'];
								} else

								/**
								 * do we have a name to work with
								 */
								if ($rowTranslation->contains('short')) {

									$optionKey = $rowTranslation['short'];
								} else

								/**
								 * do we have a name to work with
								 */
								if ($rowTranslation->contains('value')) {

									$optionKey = $rowTranslation['value'];
								} else {

									/**
									 * so we should have an id
									 */
									$optionKey = $row->$idColumn;
								}

							} else {

								/**
								 * so we should have an id
								 */
								$optionKey = $row->$idColumn;
							}
						}

						/**
						 * build option
						 */
						if (trim($optionKey) == '') {
							$optionKey = $optionValue;
						}
						$items[] = array('id'=>$optionValue, 'name'=>$optionKey);
					}
				}
			}
		}
		$items = array_merge(array(array('id'=>'-1', 'name'=>'-')), $items);

		/**
		 * data
		 */
		$data = array();
		$optional = $this->getRequest()->getParam('optional', NULL, FALSE);
		if ($optional) {
			$data[] = array(
							'id'=>NULL,
							'name'=>NULL,
			);
		}

		if (count($items)>0) {
			foreach($items as $item) {
				$data[] = array(
								'id'=>$item['id'],
								'name'=>$item['name'],
				);
			}
		}

		$this->_helper->autoCompleteDojo(new Zend_Dojo_Data('id', $data));
	}



	/**
	 * AutoComplete ModelCollectionAction
	 *
	 * used by L8M_JQuery_Form_Element_M2N
	 *
	 */
	public function modelCollectionAction() {

		$this->view->modelCollection = array();

		if ($this->getRequest()->isXmlHttpRequest()) {
			$paramModel = $this->getRequest()->getParam('model', NULL, FALSE);
			$paramID = $this->getRequest()->getParam('id', NULL, FALSE);



			/**
			 * load model
			 */
			if (class_exists($paramModel, TRUE)) {
				try {
					$helperModel = Doctrine_Query::create()
						->from($paramModel . ' m')
						->addWhere('m.id = ? ', array($paramID))
						->limit(1)
						->execute()
						->getFirst()
					;

					if ($helperModel &&
						$helperModel->getTable()->hasRelation('ModelName')) {

						$useModelName = $helperModel->ModelName->name;

						/**
						 * load model
						 */
						if (class_exists($useModelName, TRUE)) {

							$testModel = new $useModelName();
							$columnDefinitions = $testModel->getTable()->getColumns();

							$orderBy = NULL;
							if (array_key_exists('id', $columnDefinitions) &&
								(array_key_exists('short', $columnDefinitions) || array_key_exists('resource', $columnDefinitions))) {

								if (array_key_exists('position', $columnDefinitions)) {
									$orderBy = 'm.position';
								} else
								if (array_key_exists('short', $columnDefinitions)) {
									$orderBy = 'm.short';
								} else
								if (array_key_exists('resource', $columnDefinitions)) {
									$orderBy = 'm.resource';
								}

								try {
									$modelCollection = Doctrine_Query::create()
										->from($useModelName . ' m')
										->orderBy($orderBy . ' ASC')
										->execute()
									;
									$this->view->modelCollection = $modelCollection;
								} catch (Doctrine_Connection_Exception $exception) {
									/**
									 * @todo maybe do something
									 */
								}
							}
						}
					}
				} catch (Doctrine_Connection_Exception $exception) {
					/**
					 * @todo maybe do something
					 */
				}
			}

			Zend_Layout::getMvcInstance()->disableLayout();
		}
	}

	/**
	 * Role action.
	 *
	 * @return void.
	 */
	public function roleAction()
	{
		/**
		 * select
		 */
		$select = new Zend_Db_Select($this->getDatabaseAdapter('default'));

		/**
		 * default
		 */
		$select->from('role',
					  array(
					  		'id'=>'role.id',
					  		'name'=>'role_translation.name',
					  ))
				   ->joinLeft('role_translation',
							  'role_translation.id = role.id')
				   ->where('role_translation.lang = ?', $this->_getLanguage())
				   ->order('role_translation.name ASC');

		/**
		 * items
		 */
		$items = $this->getDatabaseAdapter('default')->fetchAll($select);

		/**
		 * data
		 */
		$data = array();
		$optional = $this->getRequest()->getParam('optional', NULL, FALSE);
		if ($optional) {
			$data[] = array(
							'id'=>NULL,
							'name'=>NULL,
			);
		}

		if (count($items)>0) {
			foreach($items as $item) {
				$data[] = array(
								'id'=>$item['id'],
								'name'=>$item['name'],
				);
			}
		}

		$this->_helper->autoCompleteDojo(new Zend_Dojo_Data('id', $data));
	}

	/**
	 * User action.
	 *
	 * @return void.
	 */
	public function userAction()
	{
		/**
		 * select
		 */
		$select = new Zend_Db_Select($this->getDatabaseAdapter('default'));

		/**
		 * role id
		 */
		if ($this->getRequest()->getParam('role_id', NULL, FALSE)) {
			$select->from('user',
						  array(
						  		'id'=>'user.id',
						  		'name'=>'user.login',
						  ))
				   ->where('user.role_id = ?',
				   		   $this->getRequest()->getParam('role_id', NULL, FALSE))
				   ->order('user.login ASC');

		} else {

			/**
			 * default
			 */
			$select->from('user',
						  array(
						  		'id'=>'user.id',
						  		'name'=>'CONCAT(role.name, " / ",
						  						user.login)',
						  ))
				   ->joinLeft('role',
							  'role.id = user.role_id')
				   ->order('CONCAT(role.name, " / ",
			  					   user.login) ASC');
		}

		/**
		 * items
		 */
		$items = $this->getDatabaseAdapter('default')->fetchAll($select);

		/**
		 * data
		 */
		$data = array();
		$optional = $this->getRequest()->getParam('optional', NULL, FALSE);
		if ($optional) {
			$data[] = array(
							'id'=>NULL,
							'name'=>NULL,
			);
		}

		if (count($items)>0) {
			foreach($items as $item) {
				$data[] = array(
								'id'=>$item['id'],
								'name'=>$item['name'],
				);
			}
		}

		$this->_helper->autoCompleteDojo(new Zend_Dojo_Data('id', $data));
	}

}