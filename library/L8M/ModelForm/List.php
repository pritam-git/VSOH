<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/ModelForm/List.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: List.php 542 2017-08-23 17:03:31Z nm $
 */

/**
 *
 *
 * L8M_ModelForm_List
 *
 *
 */
class L8M_ModelForm_List
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
	 * save model name
	 */
	private $_modelName = NULL;

	/**
	 * save redirector object
	 * @var Zend_Helper
	 */
	private $_redirector = NULL;

	/**
	 * save controller object
	 * @var Zend_Controller_Action
	 */
	private $_controller = NULL;

	/**
	 * where clauses for model-list
	 * @var array
	 */
	private $_addWhere = array();

	/**
	 * where clauses as dql-string for model-list
	 * @var array
	 */
	private $_addWhereDql = array();

	/**
	 * join clause
	 * @var array
	 */
	private $_addJoin = array();

	/**
	 * delete old list
	 * @var array
	 */
	private $_deleteOldList = FALSE;

	/**
	 * hide column in list
	 * @var array
	 */
	private $_listColumnHide = array();

	/**
	 * contains url variables for lead through
	 * @var array
	 */
	private $_leadThroughUrl = array();

	/**
	 * contains url variables for lead through for buttons
	 * @var array
	 */
	private $_leadThroughButton = array();

	/**
	 * contains alle numeric datatypes
	 * @var array
	 */
	private $_numericColumnTypes = array(
		'integer',
		'decimal',
		'float',
		'double',
		'timestamp',
		'time',
		'date',
	);

	/**
	 * Store genereated buttons in an array.
	 */
	private $_generatedButtons = array();

	/**
	 * save defaults
	 * @var array
	 */
	private $_default = array(
		'listTitle'=>NULL,
		'subLinks'=>TRUE,
		'specificColumnSubLinks'=>array(),
		'hideShort'=>TRUE,
		'showAjax'=>FALSE,
		'doRedirect'=>TRUE,
		'showBackAfterSave'=>TRUE,
		'saveWhere'=>TRUE,
		'useDbWhere'=>TRUE,
		'controllerParams'=>array(),
		'resultPerPage'=>15,
		'sortorder'=>'asc',
		'sortname'=>NULL,
		'searchQType'=>'',
		'searchQuery'=>'',
		'page'=>1,
		'loadDefaultButtonsFormDefault'=>FALSE,
		'button_edit'=>TRUE,
		'button_add'=>TRUE,
		'button_copy'=>TRUE,
		'button_export'=>TRUE,
		'button_delete'=>TRUE,
		'buttons'=>array(),
		'width'=>860,
		'height'=>385,
		'cssClassName'=>'',
		'showTitle'=>FALSE,
		'rightAlignColumns'=>array(),
		'relation'=>array(),
		'formLanguage'=>NULL,
		'translateColumns'=>array(),
		'reverseBoolean'=>array(),
		'deactivateBooleanLink'=>FALSE,
		'deactivateBooleanLinkIfTrue'=>array(),
		'deactivateBooleanLinkIfFalse'=>array(),
		'hideColumns'=>array(),
		'multiSameRelationName'=>array(),
		'columns'=>array(
			'id'=>array(
				'width'=>30,
				'display'=>'ID',
				'search_like'=>FALSE,
				'use_in_edit_view'=>TRUE,
			),
			'position'=>array(
				'width'=>55,
				'display'=>'Position',
				'search_like'=>FALSE,
				'use_in_edit_view'=>FALSE,
			),
			'resource'=>array(
				'width'=>180,
				'display'=>'Resource',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'name'=>array(
				'width'=>180,
				'display'=>'Name',
				'search_like'=>TRUE,
				'use_in_edit_view'=>TRUE,
			),
			'short'=>array(
				'width'=>100,
				'display'=>'Short',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'code'=>array(
				'width'=>180,
				'display'=>'Code',
				'search_like'=>TRUE,
				'use_in_edit_view'=>TRUE,
			),
			'value'=>array(
				'width'=>180,
				'display'=>'Value',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>FALSE,
			),
			'headline'=>array(
				'width'=>180,
				'display'=>'Headline',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'activation_code'=>array(
				'width'=>180,
				'display'=>'Activation Code',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'login'=>array(
				'width'=>180,
				'display'=>'Login',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'lastname'=>array(
				'width'=>180,
				'display'=>'Lastname',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'firstname'=>array(
				'width'=>180,
				'display'=>'Firstname',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'email'=>array(
				'width'=>180,
				'display'=>'Email',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'iso_2'=>array(
				'width'=>50,
				'display'=>'ISO 2 Code',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'iso_3'=>array(
				'width'=>50,
				'display'=>'ISO 3 Code',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'iso_nr'=>array(
				'width'=>50,
				'display'=>'ISO Number',
				'search_like'=>FALSE,
				'use_in_edit_view'=>TRUE,
			),
			'name_local'=>array(
				'width'=>180,
				'display'=>'Local Name',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'name_short'=>array(
				'width'=>100,
				'display'=>'Short-Name',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'zone_code'=>array(
				'width'=>180,
				'display'=>'Zone Code',
				'search_like'=>TRUE,
				'search_like_mode'=>3,
				'use_in_edit_view'=>TRUE,
			),
			'price'=>array(
				'width'=>100,
				'display'=>'Price',
				'search_like'=>FALSE,
				'use_in_edit_view'=>TRUE,
			),
		),
	);

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */
	public function __construct($modelName = NULL, &$controller = NULL)
	{
		if ($modelName == NULL) {
			throw new L8M_Exception('No model selected for ModelList');
		}

		/**
		 * retrieve Model
		 */
		$modelNameModel = Doctrine_Query::create()
			->from('Default_Model_ModelName mn')
			->where('mn.name = ? ', array($modelName))
			->execute()
			->getFirst()
		;
		if ($modelNameModel) {
			$this->_modelName = $modelNameModel->name;
			if ($controller instanceof Zend_Controller_Action) {
				$this->_controller = &$controller;
				$this->_redirector = $this->_controller->getHelper('redirector');
				set_time_limit(0);
				return TRUE;
			} else {
				throw new L8M_Exception('ModelList need to have a controller object.');
			}
		} else {
			throw new L8M_Exception('Model does not exists: ' . $modelName);
		}
		return FALSE;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * add a where claus as dql string to model-list
	 *
	 * @param string $dqlString
	 * @param array $values
	 *
	 * @return L8M_ModelForm_List
	 */
	public function addWhereDqlString($dqlString = NULL, $values = NULL, $joinOnShort = NULL, $modelAliasName = NULL, $as = NULL)
	{
		if ($values === NULL) {
			$values = array();
		}
		$this->_addWhereDql[] = array(
			'dql'=>$dqlString,
			'values'=>$values,
		);

		/**
		 * create and add join
		 */
		if ($joinOnShort &&
			$modelAliasName &&
			$as) {

			if (array_key_exists($as, $this->_addJoin)) {
				if ($this->_addJoin[$as]['joinOnShort'] <> $joinOnShort ||
					$this->_addJoin[$as]['modelAliasName'] <> $modelAliasName) {

					throw new L8M_Exception('Somthing went wrong processing the where-statement.');
				}
			} else {
				$this->_addJoin[$as] = array(
					'joinOnShort'=>$joinOnShort,
					'modelAliasName'=>$modelAliasName,
				);
			}
		}

		return $this;
	}

	/**
	 * add a where claus to model-list
	 *
	 * @param array $options
	 * @return L8M_ModelForm_List
	 */
	public function addWhere($column = NULL, $option = NULL, $useLike = FALSE, $joinOnShort = NULL, $modelAliasName = NULL, $as = NULL)
	{
		/**
		 * add option as array
		 */
		if ($option === NULL) {
			$option = 'NULL';
		}

		/**
		 * prepare model
		 */
		if (substr($modelAliasName, 0, strlen('Default_Model_')) == 'Default_Model_') {
			$modelAliasName = substr($modelAliasName, strlen('Default_Model_'));
		}

		/**
		 * create and add join
		 */
		$join = NULL;
		if ($joinOnShort &&
			$modelAliasName &&
			$as) {

			if (array_key_exists($as, $this->_addJoin)) {
				if ($this->_addJoin[$as]['joinOnShort'] <> $joinOnShort ||
					$this->_addJoin[$as]['modelAliasName'] <> $modelAliasName) {

					throw new L8M_Exception('Somthing went wrong processing the where-statement.');
				}
			} else {
				$this->_addJoin[$as] = array(
					'joinOnShort'=>$joinOnShort,
					'modelAliasName'=>$modelAliasName,
				);
			}
		}

		/**
		 * add where
		 */
		$this->_addWhere[] = array(
			'column'=>$column,
			'option'=>$option,
			'joinOnShort'=>$joinOnShort,
			'modelAliasName'=>$modelAliasName,
			'as'=>$as,
			'useLike'=>$useLike,
		);

		/**
		 * add join
		 */
		if ($join) {
			$this->_addJoin[] = $join;
		}

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * show this column in list
	 * @param String $relation
	 * @param String $name
	 *
	 * @return L8M_ModelForm_List
	 */
	public function setListRelationName($relation = NULL, $name = NULL, $width = NULL, $replace = NULL, $searchLike = FALSE)
	{
		if (is_string($relation) &&
			$relation != NULL &&
			is_string($name) &&
			$name != NULL) {

			$this->_default['relation'][$relation]['name'] = $name;
			if ($width) {
				$this->_default['relation'][$relation]['width'] = $width;
			}

			if ($replace) {
				$this->_default['relation'][$relation]['replace'] = $replace;
			}

			if ($searchLike) {
				$this->_default['relation'][$relation]['searchLike'] = $searchLike;
			}
		}
		return $this;
	}

	/**
	 * show this column (Translation) in list
	 * @param String $column
	 * @param String $displayAs
	 * @param integer $width
	 * @param boolean $searchLike
	 * @param boolean $deleteOldList
	 *
	 * @return L8M_ModelForm_List
	 */
	public function showListTranslateColumn($column = NULL, $displayAs = NULL, $width = NULL, $searchLike = NULL, $searchLikeMode = NULL)
	{
		if (is_string($column)) {
			if (!is_bool($searchLike)) {
				$searchLike = FALSE;
			}

			if ($width == NULL ||
				!is_numeric($width)) {

				$width = $this->_default['columns']['name']['width'];
			}

			if ($displayAs == NULL ||
				!is_string($displayAs)) {

				$displayAs = $column;
			}

			$this->_default['translateColumns'][$column] = array(
				'width'=>$width,
				'display'=>$displayAs,
				'search_like'=>$searchLike,
				'search_like_mode'=>$searchLikeMode,
			);
		}
		return $this;
	}

	/**
	 * change the relation-name for multi-same relations
	 * @param string $relationAlias
	 * @param string $name
	 *
	 * @return L8M_ModelForm_List
	 */
	public function setListColumnMultiSameRelationName($relationAlias = NULL, $name = NULL, $lang = NULL) {
		if (!in_array($lang, L8M_Locale::getSupported(TRUE))) {
			$lang = L8M_Locale::getDefaultSystem();
		}
		$this->_default['multiSameRelationName'][$relationAlias]['display'] = $name;
		$this->_default['multiSameRelationName'][$relationAlias]['lang'] = $lang;
		return $this;
	}

	/**
	 * show this column in list
	 * @param String $column
	 * @param String $displayAs
	 * @param integer $width
	 * @param boolean $searchLike
	 * @param boolean $deleteOldList
	 *
	 * @return L8M_ModelForm_List
	 */
	public function showListColumn($column = NULL, $displayAs = NULL, $width = NULL, $searchLike = FALSE, $deleteOldList = FALSE, $searchLikeMode = NULL, $useInEditView = FALSE)
	{
		if (is_string($column)) {
			if (!is_bool($searchLike)) {
				$searchLike = FALSE;
			}

			if ($width == NULL ||
				!is_numeric($width)) {

				$width = $this->_default['columns']['name']['width'];
			}

			if (is_bool($deleteOldList) &&
				$this->_deleteOldList == FALSE) {

				$this->_deleteOldList = $deleteOldList;
			}

			if ($displayAs == NULL ||
				!is_string($displayAs)) {

				$displayAs = $column;
			}

			if (!is_bool($useInEditView)) {
				$useInEditView = FALSE;
			}

			$this->_default['columns'][$column] = array(
				'width'=>$width,
				'display'=>$displayAs,
				'search_like'=>$searchLike,
				'search_like_mode'=>$searchLikeMode,
				'use_in_edit_view'=>$useInEditView
			);
		}
		return $this;
	}

	/**
	 * make column align right
	 *
	 * @param string $columnName
	 * @return L8M_ModelForm_List
	 */
	public function showListColumnAsAlignRight($columnName)
	{
		if (!in_array($columnName, $this->_default['rightAlignColumns'])) {
			$this->_default['rightAlignColumns'][] = $columnName;
		}

		return $this;
	}

	/**
	 * creates column, but hides column
	 *
	 * @param string $columnName
	 * @return L8M_ModelForm_List
	 */
	public function hideColumnInList($columnName)
	{
		$this->_default['hideColumns'][] = $columnName;

		return $this;
	}

	/**
	 * reverses boolean
	 *
	 * @param string $columnName
	 * @return L8M_ModelForm_List
	 */
	public function booleanReverseColumn($columnName)
	{
		$this->_default['reverseBoolean'][] = $columnName;

		return $this;
	}

	/**
	 * boolean not clickable
	 *
	 * @return L8M_ModelForm_List
	 */
	public function booleanNotClickable()
	{
		$this->_default['deactivateBooleanLink'] = TRUE;

		return $this;
	}

	/**
	 * boolean not clickable if true
	 *
	 * @param string $columnName
	 * @return L8M_ModelForm_List
	 */
	public function booleanNotClickableIfTrue($column)
	{
		$this->_default['deactivateBooleanLinkIfTrue'][] = $column;

		return $this;
	}

	/**
	 * boolean not clickable if false
	 *
	 * @param string $columnName
	 * @return L8M_ModelForm_List
	 */
	public function booleanNotClickableIfFalse($column)
	{
		$this->_default['deactivateBooleanLinkIfFalse'][] = $column;

		return $this;
	}

	/**
	 * hide this column in the list
	 * @param String $column
	 * @param boolean $deleteOldList
	 *
	 * @return L8M_ModelForm_List
	 */
	public function hideListColumn($column = NULL, $deleteOldList = FALSE)
	{
		if ($column) {
			$this->hideListColumns(array($column), $deleteOldList);
		}
		return $this;
	}

	/**
	 * hide these columns in the list
	 * @param array $columns
	 * @param boolean $deleteOldList
	 *
	 * @return L8M_ModelForm_List
	 */
	public function hideListColumns($columns = NULL, $deleteOldList = FALSE)
	{
		if (is_array($columns)) {
			foreach ($columns as $column) {
				if (is_string($column)) {
					if (!in_array($column, $this->_listColumnHide)) {
						$this->_listColumnHide[] = $column;
					}
				}
			}
			if (is_bool($deleteOldList)) {
				$this->_deleteOldList = $deleteOldList;
			}
		}
		return $this;
	}

	/**
	 * Disable sublinks in flexigrid.
	 *
	 * @return L8M_ModelForm_List
	 */
	public function disableSublinks()
	{
		/**
		 * disable sublinks
		 */
		$this->_default['subLinks'] = FALSE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Add Link to specified Column
	 *
	 * @return L8M_ModelForm_List
	 */
	public function addSublinkToColumn($columnName = NULL, $linkArray = NULL)
	{
		if ($columnName &&
			is_array($linkArray) &&
			count($linkArray) > 0) {

			/**
			 * add sublink for column
			 */
			$this->_default['specificColumnSubLinks'][$columnName] = $linkArray;
		}

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Disable button edit in flexigrid.
	 *
	 * @return L8M_ModelForm_List
	 */
	public function disableButtonEdit()
	{
		/**
		 * disable button
		 */
		$this->_default['button_edit'] = FALSE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Enable button edit in flexigrid.
	 *
	 * @return L8M_ModelForm_List
	 */
	public function enableButtonEdit()
	{
		/**
		 * enable button
		 */
		$this->_default['button_edit'] = TRUE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Enable default buttons from default
	 *
	 * @return L8M_ModelForm_List
	 */
	public function loadDefaultButtonsFormDefault($value = TRUE)
	{

		if ($value) {
			$value = TRUE;
		} else {
			$value = FALSE;
		}

		/**
		 * enable defaults
		 */
		$this->_default['loadDefaultButtonsFormDefault'] = $value;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Disable button add in flexigrid.
	 *
	 * @return L8M_ModelForm_List
	 */
	public function disableButtonAdd()
	{
		/**
		 * disable button
		 */
		$this->_default['button_add'] = FALSE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Disable button copy in flexigrid.
	 *
	 * @return L8M_ModelForm_List
	 */
	public function disableButtonCopy()
	{
		/**
		 * disable button
		 */
		$this->_default['button_copy'] = FALSE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Disable button edit in flexigrid.
	 *
	 * @return L8M_ModelForm_List
	 */
	public function disableButtonDelete()
	{
		/**
		 * disable button
		 */
		$this->_default['button_delete'] = FALSE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * sets default value
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return L8M_ModelForm_List
	 */
	public function setDefault($key, $value)
	{
		/**
		 * is key ekisting
		 */
		if (array_key_exists($key, $this->_default)) {
			$this->_default[$key] = $value;
		}

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * setFormLanguage
	 *
	 * @return L8M_ModelForm_List
	 */
	public function setFormLanguage($lang)
	{
		/**
		 * check language
		 */
		if (in_array($lang, L8M_Locale::getSupported())) {
			$this->_default['formLanguage'] = $lang;
		}

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * shows AJAX only interesting for list action
	 *
	 * @return L8M_ModelForm_List
	 */
	public function showAjax()
	{
		/**
		 * change ajax view
		 */
		$this->_default['showAjax'] = TRUE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * does not enable redirects after edit or create
	 *
	 * @return L8M_ModelForm_List
	 */
	public function doNotRedirect()
	{
		/**
		 * change ajax view
		 */
		$this->_default['doRedirect'] = FALSE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * shows short in edit and edit
	 *
	 * @return L8M_ModelForm_List
	 */
	public function showShort()
	{
		/**
		 * change hide short
		 */
		$this->_default['hideShort'] = FALSE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * force use where from db
	 *
	 * @return L8M_ModelForm_List
	 */
	public function useDbWhere($useDbWhere = TRUE)
	{
		/**
		 * change usage of db where
		 */
		if (is_bool($useDbWhere)) {
			$this->_default['useDbWhere'] = $useDbWhere;
		}

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Disable save where into db function
	 *
	 * @return L8M_ModelForm_List
	 */
	public function disableSaveWhere()
	{
		/**
		 * change save where
		 */
		$this->_default['saveWhere'] = FALSE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Disable the option for not redirecting in form
	 *
	 * @return L8M_ModelForm_List
	 */
	public function disableShowBackAfterSave()
	{
		/**
		 * change save where
		 */
		$this->_default['showBackAfterSave'] = FALSE;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Set CSS-ClassName
	 *
	 * @return L8M_ModelForm_List
	 */
	public function setCssClassName($cssClassName = NULL)
	{
		/**
		 * change css class name
		 */
		$this->_default['cssClassName'] = (String) $cssClassName;

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Return Buttons array.
	 *
	 * @return array
	 */
	public function getButtons($modelListModel = NULL, $listName = NULL, $options = array())
	{
		/**
		 * flexigrid buttons array
		 */
		$flexigridButtons = array();

		if (count($this->_generatedButtons) == 0) {

			/**
			 * retrieve modelList Model
			 */
			if (!$modelListModel &&
				$listName) {

				$modelListModel = $this->_retrieveList($listName, $options);
				if (!$modelListModel) {
					throw new L8M_Exception('Could not retrieve ModelList: ' . $listName);
				}
			}

			/**
			 * build buttons for flexigrid view if modelList exists
			 */
			if ($modelListModel) {

				/**
				 * add / create button
				 */
				if ($modelListModel->button_add) {
					$addButtonName = 'Add';
					if (preg_match('/Default_Model_Media(.*)$/', $this->_modelName) &&
						strpos($this->_modelName, 'M2n') === FALSE) {

						$addButtonName = 'Upload';
					}
					$flexigridButtons[] = array(
						'name'=>$this->_controller->view->translate($addButtonName),
						'bclass'=>'add',
						'onpress'=>'function:flexAdd',
					);
				}

				/**
				 * edit button
				 */
				if ($modelListModel->button_edit) {
					$flexigridButtons[] = array(
						'name'=>$this->_controller->view->translate('Edit'),
						'bclass'=>'edit',
						'onpress'=>'function:flexEdit',
					);
				}

				/**
				 * copy button
				 */
				if ($this->_default['button_copy'] &&
					method_exists($this->_controller, 'copyAction')) {

					$controllerClassName = get_class($this->_controller);
					$controllerClassNameArray = explode('_', substr($controllerClassName, 0, strlen($controllerClassName) - strlen('Controller')));
					if (count($controllerClassNameArray) == 2) {
						$filter = new Zend_Filter_Word_CamelCaseToDash();
						$urlModule = strtolower($filter->filter($controllerClassNameArray[0]));
						$urlController = strtolower($filter->filter($controllerClassNameArray[1]));
						$this->setButton($this->_controller->view->translate('Kopieren', 'de'), array('action'=>'copy', 'controller'=>$urlController, 'module'=>$urlModule), 'copy', TRUE, TRUE, FALSE);
					}
				}

				/**
				 * export button
				 */
				if ($this->_default['button_export'] &&
					method_exists($this->_controller, 'exportAction')) {

					$controllerClassName = get_class($this->_controller);
					$controllerClassNameArray = explode('_', substr($controllerClassName, 0, strlen($controllerClassName) - strlen('Controller')));
					if (count($controllerClassNameArray) == 2) {
						$filter = new Zend_Filter_Word_CamelCaseToDash();
						$urlModule = strtolower($filter->filter($controllerClassNameArray[0]));
						$urlController = strtolower($filter->filter($controllerClassNameArray[1]));
						$this->setButton($this->_controller->view->translate('Exportieren', 'de'), array('action'=>'export', 'controller'=>$urlController, 'module'=>$urlModule), 'export', FALSE, TRUE, FALSE);
					}
				}

				/**
				 * some other buttons
				 */
				if (count($this->_default['buttons']) > 0) {
					foreach ($this->_default['buttons'] as $buttonKey => $buttonArray) {
						$flexigridButtons[] = $buttonArray;
					}
				}

				/**
				 * maybe a separator
				 */
				if (($modelListModel->button_edit ||
					$modelListModel->button_add ||
					count($this->_default['buttons']) > 0) &&
					$modelListModel->button_delete) {

					$flexigridButtons[] = array(
						'separator'=>'true',
					);
				}

				/**
				 * delete button
				 */
				if ($modelListModel->button_delete) {
					$flexigridButtons[] = array(
						'name'=>$this->_controller->view->translate('Delete'),
						'bclass'=>'delete',
						'onpress'=>'function:flexDelete',
					);
				}

				/**
				 * cache buttons
				 */
				$this->_generatedButtons = $flexigridButtons;
			}
		} else {
			$flexigridButtons = $this->_generatedButtons;
		}

		return $flexigridButtons;
	}

	/**
	 * set button
	 *
	 * @return L8M_ModelForm_List
	 */
	public function setButtonSeperator()
	{
		/**
		 * add seperator
		 */
		$this->_default['buttons'][] = array(
			'separator'=>'true',
		);

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * set button
	 *
	 * @return L8M_ModelForm_List
	 */
	public function setButton($name = NULL, $urlArray = array(), $buttonClass = NULL, $needSelectedRow = TRUE, $addInFrontOfArray = FALSE, $useMultiSelect = FALSE)
	{
		/**
		 * change
		 */
		if ($name) {
			if (!$needSelectedRow) {
				$useMultiSelect = FALSE;
			}

			if ($buttonClass == 'download-media') {
				$onPress = 'flexPressDownloadMedia';
			} else {
				$onPress = 'flexPress' . count($this->_default['buttons']);
			}

			$addButton = array(
				'name'=>$name,
				'url'=>$urlArray,
				'bclass'=>$buttonClass,
				'onpress'=>$onPress,
				'needSelectedRow'=>$needSelectedRow,
				'useMultiSelect'=>$useMultiSelect,
			);
			if ($addInFrontOfArray) {
				$buttons = $this->_default['buttons'];
				$this->_default['buttons'] = array();
				$this->_default['buttons'][] = $addButton;
				foreach ($buttons as $button) {
					$this->_default['buttons'][] = $button;
				}
			} else {
				$this->_default['buttons'][] = $addButton;
			}
		}

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * set default results per page
	 *
	 * @return L8M_ModelForm_List
	 */
	public function setResultsPerPage($resultsPerpage)
	{
		/**
		 * change
		 */
		if (is_numeric($resultsPerpage)) {
			$this->_default['resultPerPage'] = $resultsPerpage;
		}

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * set default results per page
	 *
	 * @return L8M_ModelForm_List
	 */
	public function setStartOrder($sortname = NULL, $sortorder = NULL)
	{
		/**
		 * change
		 */
		if ($sortname) {
			$this->_default['sortname'] = $sortname;
		}
		if ($sortorder == 'asc' ||
			$sortorder == 'desc') {

			$this->_default['sortorder'] = $sortorder;
		}

		/**
		 * return this
		 */
		return $this;
	}

	/**
	 * Handling export of ModelList item
	 *
	 * @param array $options
	 */
	public function exportModel($listName = NULL, $options = array())
	{

		/**
		 * retrieve model-list
		 */
		$modelListModel = $this->_retrieveList($listName, $options);

		/**
		 * retrieve real short
		 */
		$listShort = $modelListModel->name_short;

		/**
		 * retrieve columns of model
		 */
		$loadModel = $this->_modelName;
		$loadedModel = new $loadModel();
		$availableModelColumns = $loadedModel->getTable()->getColumns();

		/**
		 * check for filter
		 */
		$session = new Zend_Session_Namespace($this->_modelName . '_' . $listShort. '_FilterParam');
		if ($session->filterValue &&
			$session->filterKey &&
			$session->filterFromM2N == NULL) {

			if (array_key_exists($session->filterKey, $availableModelColumns)) {
				$this->addWhereDqlString($listShort . '.' . $session->filterKey . ' = ? ', array($session->filterValue));
			} else {
				$failureFilterKey = $session->filterKey;
				$session->filterValue = NULL;
				$session->filterKey = NULL;
				$session->filterFromM2N = NULL;
				$session->filterAlias = NULL;
				throw new L8M_Exception('Session saved filterKey "' . $failureFilterKey . '" does not match Model "' . $this->_modelName . '"');
			}
		} else
		if ($session->filterValue &&
			$session->filterKey &&
			$session->filterFromM2N) {

			$fromM2NName = $session->filterFromM2N;
			$loadedM2NModel = new $fromM2NName();

			$loadedM2NModelColumns = $loadedM2NModel->getTable()->getColumns();
			if (array_key_exists($session->filterKey, $loadedM2NModelColumns)) {
				$loadedM2NModelRelations = $loadedM2NModel->getTable()->getRelations();
				$session->filterAlias = NULL;
				$foreignFromM2NModelValue = NULL;
				foreach ($loadedM2NModelRelations as $loadedM2NModelRelation) {
					if ($loadedM2NModelRelation->getType() === Doctrine_Relation::ONE &&
						$loadedM2NModelRelation->getClass() == $this->_modelName) {

						$session->filterAlias = $loadedM2NModelRelation->getAlias();
					} else
					if ($loadedM2NModelRelation->getType() === Doctrine_Relation::ONE &&
						$loadedM2NModelRelation->getLocalColumnName() == $session->filterKey) {

						$foreignFromM2NModelName = $loadedM2NModelRelation->getClass();
						$foreignFromM2NModelAlias = $loadedM2NModelRelation->getAlias();
						try {
							$foreignFromM2NModel = Doctrine_Query::create()
								->from($foreignFromM2NModelName . ' ffm')
								->where('ffm.id = ? ', array($session->filterValue))
								->limit(1)
								->execute()
								->getFirst()
							;
						} catch (Doctrine_Exception $e) {
							$failureFilterFromM2N = $session->filterFromM2N;
							$session->filterValue = NULL;
							$session->filterKey = NULL;
							$session->filterFromM2N = NULL;
							$session->filterAlias = NULL;
							throw new L8M_Exception('Something went wrong with M2N-Model "' . $failureFilterFromM2N . '".');
						}

						$foreignFromM2NModelColumns = $foreignFromM2NModel->getTable()->getColumns();
						if (array_key_exists('name', $foreignFromM2NModelColumns)) {
							$foreignFromM2NModelValue = $foreignFromM2NModel->name;
						} else
						if (array_key_exists('short', $foreignFromM2NModelColumns)) {
							$foreignFromM2NModelValue = $foreignFromM2NModel->short;
						} else {
							$failureFilterFromM2N = $session->filterFromM2N;
							$session->filterValue = NULL;
							$session->filterKey = NULL;
							$session->filterFromM2N = NULL;
							$session->filterAlias = NULL;
							throw new L8M_Exception('M2N-Model "' . $failureFilterFromM2N . '" need to have at least a short.');
						}
					}
				}
				if ($session->filterAlias) {
					$this->_controller->view->layout()->subheadline = $this->_controller->view->translate('List') . ': ' . $this->_controller->view->translate($foreignFromM2NModelAlias) . ' (' . $foreignFromM2NModelValue . ')';
					$this->addWhereDqlString('m2njoin.' . $session->filterKey . ' = ? ', array($session->filterValue));
				} else {
					$failureFilterFromM2N = $session->filterFromM2N;
					$session->filterValue = NULL;
					$session->filterKey = NULL;
					$session->filterFromM2N = NULL;
					$session->filterAlias = NULL;
					throw new L8M_Exception('Session saved M2N-Model "' . $failureFilterFromM2N . '" does not match Model "' . $this->_modelName . '"');
				}
			} else {
				$failureFilterKey = $session->filterKey;
				$session->filterValue = NULL;
				$session->filterKey = NULL;
				$session->filterFromM2N = NULL;
				$session->filterAlias = NULL;
				throw new L8M_Exception('Session saved filterKey "' . $failureFilterKey . '" does not match M2N-Model "' . $this->_modelName . '"');
			}

		}

		/**
		 * Standards
		 */

		/**
		 * do we have to deactivate website to show ajax request?
		 */
		if (is_array($options) &&
			array_key_exists('showAjax', $options) &&
			is_bool($options['showAjax'])) {

			$showAjax = $options['showAjax'];
		} else {
			$showAjax = $this->_default['showAjax'];
		}

		/**
		 * default from
		 */
		$defaultModelFrom = $this->_modelName . ' ' . $modelListModel->name_short;

		/**
		 * all joins
		 */
		$availableLeftJoins = array();

		/**
		 * prepare flexigrid for images
		 */
		$useFlexigridForImages = FALSE;

		/**
		 * available columns
		 */
		$modelListColumnCollection = Doctrine_Query::create()
			->from('Default_Model_ModelListColumn mlc')
			->where('mlc.model_list_id = ? ', array($modelListModel->id))
			->orderBy('mlc.position ASC')
			->execute()
		;
		$availableColumnNames = array();
		foreach ($modelListColumnCollection as $modelListColumnModel) {

			/**
			 * prepare vars
			 */
			$columnName = $modelListColumnModel->name;
			$replaceWithColumn = NULL;
			$modelListConnectionModel = $modelListColumnModel->ModelListConnection;
			$displayAs = $modelListColumnModel->ModelColumnName->Translation[L8M_Library::getLanguage()]['display'];
			$foreignModelName = NULL;
			if ($modelListConnectionModel->name_alias) {
				$foreignModelName = 'Default_Model_' . $modelListConnectionModel->name_alias;

				if (!class_exists($foreignModelName)) {
					$tryFindingMediaStringPos = strpos($modelListConnectionModel->name_alias, 'Media');
					if ($tryFindingMediaStringPos !== FALSE) {
						$foreignModelName = 'Default_Model_' . substr($modelListConnectionModel->name_alias, $tryFindingMediaStringPos);
					}
				}
			}

			/**
			 * show column as image
			 */
			$showAsImage = FALSE;

			/**
			 * show column as boolean
			 */
			$showAsBoolean = FALSE;

			/**
			 * align right
			 */
			$alignRight = FALSE;

			/**
			 * is numeric
			 */
			$isNumeric = FALSE;

			/**
			 * search like
			 */
			$searchLike = $modelListColumnModel->search_like;
			if (isset($modelListColumnModel['search_like_mode'])) {
				$searchLikeMode = $modelListColumnModel->search_like_mode;
			} else {
				$searchLikeMode = NULL;
				unset($searchLikeMode);
			}

			if ($modelListColumnModel->model_list_connection_id) {
				$filterAlias = new Zend_Filter_Word_CamelCaseToUnderscore();
				$tmpModelListColumnShort = strtolower($modelListConnectionModel->name_alias);
				if ($modelListConnectionModel->replace_with_column) {
					$columnName = $modelListConnectionModel->replace_with_column;
					if ($modelListConnectionModel->is_foreign == FALSE) {
						$replaceWithColumn = array(
							'replacedColumnName'=>$tmpModelListColumnShort . '_' . $columnName,//$ModelListConnectionModel->foreign_key,
							'columnName'=>$columnName,//$ModelListConnectionModel->foreign_key,
							'onModel'=>$modelListConnectionModel->ModelName->name,
						);
						if (array_key_exists($modelListConnectionModel->name_alias, $this->_default['relation']) &&
							array_key_exists('replace', $this->_default['relation'][$modelListConnectionModel->name_alias]) &&
							$this->_default['relation'][$modelListConnectionModel->name_alias]['replace'] != $columnName) {

							$replaceWithColumn['replacedColumnName'] = $tmpModelListColumnShort . '_' . $this->_default['relation'][$modelListConnectionModel->name_alias]['name'];
							$replaceWithColumn['columnName'] = $this->_default['relation'][$modelListConnectionModel->name_alias]['name'];
							$columnName = $this->_default['relation'][$modelListConnectionModel->name_alias]['replace'];
						}
						if (array_key_exists($modelListConnectionModel->name_alias, $this->_default['relation']) &&
							array_key_exists('searchLike', $this->_default['relation'][$modelListConnectionModel->name_alias])) {

							$searchLike = $this->_default['relation'][$modelListConnectionModel->name_alias]['searchLike'];
						}
					} else {
						throw new L8M_Exception('Foreign-Key not implemented yet into Model-List.');
					}
				}
				if ($modelListConnectionModel->join_on_short) {
					$availableLeftJoins[$modelListConnectionModel->short] = array(
						'joinOnShort'=>$modelListConnectionModel->join_on_short,
						'modelAliasName'=>$modelListConnectionModel->name_alias,
						'replaceWithColumn'=>$columnName,
					);
				} else {
					$availableLeftJoins[$modelListConnectionModel->short] = array(
						'joinOnShort'=>$listShort,
						'modelAliasName'=>$modelListConnectionModel->name_alias,
						'replaceWithColumn'=>$columnName,
					);
				}

				/**
				 * prepare column for image preview
				 */
				if (preg_match('/Default_Model_Media(.*)$/', $foreignModelName)) {
					$showAsImage = TRUE;
				}

				/**
				 * display as
				 */
				if ($foreignModelName == $this->_modelName) {
					$displayAs = $this->_controller->view->translate('Parent Record');
				} else {
					if (array_key_exists($modelListConnectionModel->name_alias, $this->_default['relation'])) {
						$displayAs = $this->_controller->view->translate($this->_default['relation'][$modelListConnectionModel->name_alias]['name']);
						if (array_key_exists('width',$this->_default['relation'][$modelListConnectionModel->name_alias])) {
							$modelListColumnModel->width = $this->_default['relation'][$modelListConnectionModel->name_alias]['width'];
						}
					}
					if ($displayAs == NULL ||
						$displayAs == 'ID') {

						$displayAs = $this->_controller->view->translate($modelListConnectionModel->name_alias);
					}
				}
			} else {

				if (array_key_exists($columnName, $availableModelColumns) &&
					$availableModelColumns[$columnName]['type'] == 'boolean') {

					$showAsBoolean = TRUE;
				}
				if (in_array($availableModelColumns[$columnName]['type'], $this->_numericColumnTypes)) {
					$alignRight = TRUE;
					$isNumeric = TRUE;
				} else
				if (in_array($columnName, $this->_default['rightAlignColumns'])) {
					$alignRight = TRUE;
				}

				$tmpModelListColumnShort = $listShort;
			}

			/**
			 * build up available columns
			 */
			$availableColumnNames[$tmpModelListColumnShort . '_' . $columnName] = array(
				'querySelect'=>$tmpModelListColumnShort . '.' . $columnName,
				'columnName'=>$columnName,
				'columnRelationShort'=>$tmpModelListColumnShort,
				'searchLike'=>$searchLike,
				'width'=>$modelListColumnModel->width,
				'display'=>$displayAs,
				'replaceAsConnection'=>$replaceWithColumn,
				'showAsImage'=>$showAsImage,
				'showAsBoolean'=>$showAsBoolean,
				'alignRight'=>$alignRight,
				'isNumeric'=>$isNumeric,
				'isTranslation'=>FALSE,
			);
			if (isset($searchLikeMode)) {
				$availableColumnNames[$tmpModelListColumnShort . '_' . $columnName]['searchLikeMode'] = $searchLikeMode;
			}

			/**
			 * should we prepare flexigrid for images?
			 */
			if ($showAsImage) {
				$useFlexigridForImages = TRUE;
			}
		}

		/**
		 * Translate Columns
		 */
		$modelQueryTranslationSelect = NULL;
		$loadedModelRelations = $loadedModel->getTable()->getRelations();
		if (array_key_exists('Translation', $loadedModelRelations)) {

			$translationColumns = $loadedModelRelations['Translation']->getTable()->getColumns();
			foreach ($this->_default['translateColumns'] as $translateColumnName => $translateColumnOptions) {

				if (array_key_exists($translateColumnName, $translationColumns)) {

					$modelQueryTranslationSelect = 'DISTINCT ';
					foreach (L8M_Locale::getSupported() as $translateLang) {

						/**
						 * build up available columns
						 */
						$availableColumnNames['translation_' . $translateLang . '_' . $translateColumnName] = array(
							'querySelect'=>'translation.' . $translateColumnName,
							'columnName'=>$translateColumnName,
							'columnRelationShort'=>'translation',
							'searchLike'=>$translateColumnOptions['search_like'],
							'width'=>$translateColumnOptions['width'],
							'display'=>$translateColumnOptions['display'] . ' (' . $translateLang . ')',
							'replaceAsConnection'=>FALSE,
							'showAsImage'=>FALSE,
							'showAsBoolean'=>FALSE,
							'alignRight'=>FALSE,
							'isNumeric'=>FALSE,
							'isTranslation'=>TRUE,
						);
						if (isset($translateColumnOptions['search_like_mode'])) {
							$availableColumnNames['translation_' . $translateLang . '_' . $translateColumnName]['searchLikeMode'] = $translateColumnOptions['search_like_mode'];
						}
					}
				}
			}
		}


		/**
		 * flexigrid standards
		 */
		$flexigridID = $listShort;
		if ($this->_default['showTitle']) {
			$flexigridTitle = $modelListModel->Translation[L8M_Library::getLanguage()]['title'];
		} else {
			$flexigridTitle = '';
		}
		$flexigridWidth = $modelListModel->width;
		$flexigridHeight = $modelListModel->height;

		/**
		 * overload some default by database ;o)
		 */
		$this->_loadDefaultsFormDatabase($modelListModel->id);

		/**
		 * page
		 */
		$page = $this->_controller->getRequest()->getParam('page', NULL, FALSE);
		$page = $page
			  ? $page
			  : $this->_default['page']
		;
		if (!is_numeric($page)) {
			$page = $this->_default['page'];
		}

		/**
		 * results per page
		 */
		$resultPerPage = $this->_controller->getRequest()->getParam('rp', NULL, FALSE);
		$resultPerPage = $resultPerPage
			  ? $resultPerPage
			  : $this->_default['resultPerPage']
		;
		if (!is_numeric($resultPerPage)) {
			$resultPerPage = $this->_default['resultPerPage'];
		}

		/**
		 * default order by
		 */
		$defaultSortName = $modelListModel->default_sort;
		if ($this->_default['sortname']) {
			$defaultSortName = $this->_default['sortname'];
		}

		/**
		 * sort order
		 */
		$sortOrder = $this->_controller->getRequest()->getParam('sortorder', NULL, FALSE);
		$sortOrder = $sortOrder
				   ? $sortOrder
				   : $this->_default['sortorder']
		;
		if ($sortOrder != 'asc') {
			$sortOrder = 'desc';
		}

		/**
		 * sort by name
		 */
		$sortName = $this->_controller->getRequest()->getParam('sortname', NULL, FALSE);
		$sortName = $sortName ? $sortName : $defaultSortName;
		$sortNameForDefaultSave = $sortName;
		if (array_key_exists($sortName, $availableColumnNames)) {
			$flexSortName = $sortName;
			$sortName = $this->_replaceFormColToQueryCol($sortName);
		} else {
			$flexSortName = $defaultSortName;
			$sortName = $this->_replaceFormColToQueryCol($defaultSortName);
		}

		/**
		 * search for query
		 */
		$searchQuery = $this->_controller->getRequest()->getParam('query', NULL, FALSE);
		if ($searchQuery == ' ') {
			$searchQuery = '';
		} else {
			$searchQuery = $searchQuery ? trim($searchQuery) : $this->_default['searchQuery'];
		}

		/**
		 * search by type
		 */
		$searchType = $this->_controller->getRequest()->getParam('qtype', NULL, FALSE);
		$searchType = $searchType ? $searchType : $this->_default['searchQType'];
		$searchTypeForDefaultSave = $searchType;
		$searchOrSortTranslationWhere = FALSE;
		$searchTranslationLang = NULL;
		if (array_key_exists($searchType, $availableColumnNames)) {
			$searchLike = $availableColumnNames[$searchType]['searchLike'];
			$flexSearchType = $searchType;
			if (isset($availableColumnNames[$searchType]['isTranslation']) &&
				$availableColumnNames[$searchType]['isTranslation']) {

				$searchTranslationWhere = TRUE;
				foreach (L8M_Locale::getSupported() as $tmpLang) {
					if ($searchType == 'translation_' . $tmpLang . '_' . $availableColumnNames[$searchType]['columnName']) {
						$searchTranslationLang = $tmpLang;
					}
				}
				if ($tmpLang === NULL) {
					$flexSearchType = '';
					$searchType = '';
					$searchLike = TRUE;
				}
				$searchType = 'translation.' . $availableColumnNames[$searchType]['columnName'];
			} else {
				$searchType = $this->_replaceFormColToQueryCol($searchType);
			}
		} else {
			$flexSearchType = '';
			$searchType = '';
			$searchLike = TRUE;
		}

		/**
		 * save some defaults to database ;o)
		 */
		$this->_saveDefaultsToDatabase($modelListModel->id, $listShort, array(
			'resultPerPage'=>$resultPerPage,
			'sortname'=>$sortNameForDefaultSave,
			'sortorder'=>$sortOrder,
			'searchQType'=>$searchTypeForDefaultSave,
			'searchQuery'=>$searchQuery,
		));

		/**
		 * order by
		 */
		$tmpDoctrineOrderBy['front'] = array();
		$tmpDoctrineOrderBy['end'] = array();
		foreach ($availableColumnNames as $columnName => $columnOptions) {
			if ($flexSortName == $columnName ||
				count($tmpDoctrineOrderBy['front']) != 0) {

				if (count($tmpDoctrineOrderBy['front']) == 0) {
					$tmpDoctrineOrderBy['front'][] = $columnOptions['querySelect'] . ' ' . strtoupper($sortOrder);
				} else {
					$tmpDoctrineOrderBy['front'][] = $columnOptions['querySelect'] . ' ASC';
				}
			} else {

				$tmpDoctrineOrderBy['end'][] = $columnOptions['querySelect'] . ' ASC';
			}
		}
		$tmp2DoctrineOrderBy = array();
		if (count($tmpDoctrineOrderBy['front']) != 0) {
			$tmp2DoctrineOrderBy[] = implode(', ', $tmpDoctrineOrderBy['front']);
		}
		if (count($tmpDoctrineOrderBy['end']) != 0) {
			$tmp2DoctrineOrderBy[] = implode(', ', $tmpDoctrineOrderBy['end']);
		}
		$doctrineOrderBy = implode(', ', $tmp2DoctrineOrderBy);

		/**
		 * create select
		 */
		$querySelects = array();
		foreach ($availableColumnNames as $columnName => $columnOptions) {
			if (!$columnOptions['isTranslation']) {
				$querySelects[] = $columnOptions['querySelect'];
			}
		}

		/**
		 * include left join primery id into selects
		 */
		$tmpAvailableLeftJoins = array();
		foreach ($availableLeftJoins as $leftJoinShort => $leftJoinArray) {

			/**
			 * otherwise causes query-error
			 */
			if (!in_array($leftJoinShort . '.id', $querySelects) ||
				(
					isset($availableColumnNames[$leftJoinShort . '_id']) &&
					$availableColumnNames[$leftJoinShort . '_id']['querySelect'] == $leftJoinShort . '.id' &&
					!$availableColumnNames[$leftJoinShort . '_id']['isTranslation']
				)) {

				$querySelects[] = $leftJoinShort . '.id';
				$tmpAvailableLeftJoins[$leftJoinShort] = $leftJoinArray;
			}
		}
		$availableLeftJoins = $tmpAvailableLeftJoins;

		/**
		 * query the actions
		 */
		$modelQuery = Doctrine_Query::create();

		if ($session->filterValue &&
			$session->filterKey &&
			$session->filterFromM2N &&
			$session->filterAlias) {

			$modelQuery = $modelQuery
				->from($session->filterFromM2N . ' m2njoin')
				->leftJoin('m2njoin.' . $session->filterAlias . ' ' . $listShort)
				->select($modelQueryTranslationSelect . 'm2njoin.id,' . implode(', ', $querySelects))
			;
		} else {
			$modelQuery = $modelQuery
				->from($defaultModelFrom)
				->select($modelQueryTranslationSelect . implode(', ', $querySelects))
			;
		}

		if (array_key_exists('Translation', $loadedModelRelations) &&
			$modelQueryTranslationSelect) {

			$modelQuery = $modelQuery->leftJoin($listShort . '.Translation translation');
		}

		$modelQuery = $modelQuery
			->orderBy($doctrineOrderBy)
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
		;

		/**
		 * add where
		 */
		$modelQuery = $this->_addWhereToQuery($modelQuery, $modelListModel, $availableLeftJoins);

		/**
		 * do we have a search query
		 */
		if ($searchQuery != '' &&
			$searchType != '') {

			if ($searchLike) {
				$doctrineSearchQuery = 'LOWER(' . $searchType . ') LIKE ?';
				$searchQuery = '%' . strtolower($searchQuery) . '%';
			} else {
				$doctrineSearchQuery = $searchType . ' = ?';
			}
			$modelQuery->addWhere($doctrineSearchQuery, array($searchQuery));

			if ($searchOrSortTranslationWhere &&
				$searchTranslationLang) {

				$modelQuery->addWhere('translation.lang = ? ', array($searchTranslationLang));
			}
		}

		/**
		 * export form
		 */
		$form = new L8M_ModelForm_Export_Form();
		$form->buildMeUp($availableColumnNames, $modelListModel->id);
		/**
		 * set decorators to form
		 */
		$form->setDecorators(
			array(
				new Zend_Form_Decorator_FormElements(),
				new Zend_Form_Decorator_HtmlTag(),
				//new L8M_Form_Decorator_Ajaxable(),
				new Zend_Form_Decorator_Form(),
				new L8M_Form_Decorator_ModelListFormBack(),
				new L8M_Form_Decorator_FormHasRequiredElements(),
				new L8M_Form_Decorator(array(
					'boxClass'=>'small l8m-model-form-base',
					'appendJsFile'=>'/js/jquery/system/model-form-base.js',
				)),
			)
		);
		$this->_controller->view->exportForm = $form;

		if ($form->isSubmitted() &&
			$form->isValid($this->_controller->getRequest()->getParams())) {

			/**
			 * start pagination
			 */
			$listScalar = $modelQuery->execute();

			/**
			 * data
			 */
			$data = array(
				'total'=>count($listScalar),
			);

			$entityID = Zend_Auth::getInstance()->getIdentity()->id;

			$modelListExportModel = Doctrine_Query::create()
				->from('Default_Model_ModelListExport m')
				->where('m.model_list_id = ? AND m.entity_id = ? ', array($modelListModel->id, $entityID))
				->limit(1)
				->execute()
				->getFirst()
			;
			if (!$modelListExportModel) {
				$modelListExportModel = new Default_Model_ModelListExport();
			}
			$modelListExportModel->merge(array(
				'entity_id'=>$entityID,
				'model_list_id'=>$modelListModel->id,
				'export_type'=>$form->getValue('export_type'),
			));
			$modelListExportModel->save();

			foreach ($availableColumnNames as $columnName => $columnOptions) {
				if (isset($columnOptions['alignRight']) &&
					$columnOptions['alignRight']) {

					$align = 'R';
				} else {
					$align = 'L';
				}

				$data['rows-title'][] = array(
					'name'=>$columnName,
					'title'=>$columnOptions['display'],
					'width'=>$form->getValue('num_' . $columnName),
					'align'=>$align,
					'show'=>$form->getValue('show_' . $columnName),
				);

				$modelListColumnExportModel = Doctrine_Query::create()
					->from('Default_Model_ModelListColumnExport m')
					->where('m.model_list_export_id = ? AND m.column_name = ? ', array($modelListExportModel->id, $columnName))
					->limit(1)
					->execute()
					->getFirst()
				;
				if (!$modelListColumnExportModel) {
					$modelListColumnExportModel = new Default_Model_ModelListColumnExport();
				}
				$modelListColumnExportModel->merge(array(
					'model_list_export_id'=>$modelListExportModel->id,
					'column_name'=>$columnName,
					'width'=>$form->getValue('num_' . $columnName),
					'show_column'=>$form->getValue('show_' . $columnName),
				));
				$modelListColumnExportModel->save();
			}

			foreach ($listScalar as $record) {
				$cells = array();
				$firstCell = NULL;
				$isFirstCell = TRUE;
				$i = 0;
				foreach ($availableColumnNames as $columnName => $columnOptions) {
					if ($isFirstCell) {
						$firstCell = $record[$columnName];
						$isFirstCell = FALSE;
					}

					if ($columnOptions['replaceAsConnection'] == NULL) {
						if ($this->_modelName == 'Default_Model_Media' &&
							$i == 1) {

							$cells[] = $this->_replaceRecordColumn($record, 'media_short', array('showAsImage'=>TRUE, 'replaceAsConnection'=>array('onModel'=>'Default_Model_Media' . ucfirst($record['mediatype_short']))), TRUE);
						}

						if ($columnOptions['showAsBoolean'] == TRUE &&
							isset($record[$columnOptions['columnRelationShort'] . '_id'])) {

							$editRecordID = $record[$columnOptions['columnRelationShort'] . '_id'];

							if (in_array($columnOptions['columnName'], $this->_default['reverseBoolean'])) {
								if ($record[$columnName]) {
									$recordValue = 'FALSE';
								} else {
									$recordValue = 'TRUE';
								}
							} else {
								if ($record[$columnName]) {
									$recordValue = 'TRUE';
								} else {
									$recordValue = 'FALSE';
								}
							}
							$cells[] = $recordValue;
						} else {
							if ($columnOptions['isNumeric']) {
								$record[$columnName] = L8M_Translate::numeric($record[$columnName]);
							}

							if (array_key_exists($columnOptions['columnName'], $this->_default['specificColumnSubLinks']) &&
								isset($record[$columnOptions['columnRelationShort'] . '_id'])) {

								$recordValue = $record[$columnName];
							} else {
								if (array_key_exists($columnName, $record)) {
									$recordValue = $record[$columnName];
								} else {
									$recordValue = NULL;
								}
							}

							if (array_key_exists('isTranslation', $columnOptions) &&
								$columnOptions['isTranslation']) {

								$translateModel = Doctrine_Query::create()
									->from($defaultModelFrom)
									->where($listShort . '.id = ?', array($record[$listShort . '_id']))
									->execute()
									->getFirst()
								;
								foreach (L8M_Locale::getSupported() as $tmpSupportedLang) {
									$recordValue = NULL;
									if ($translateModel &&
										isset($translateModel->Translation[$tmpSupportedLang]) &&
										isset($translateModel->Translation[$tmpSupportedLang][$columnOptions['columnName']])) {

										$recordValue = $translateModel->Translation[$tmpSupportedLang][$columnOptions['columnName']];
									}
									$cells[] = $recordValue;
								}
							} else {
								$cells[] = $recordValue;
							}
						}
					} else {

						/**
						 * replace record column content with its media
						 */
						$record[$columnName] = $this->_replaceRecordColumn($record, $columnName, $columnOptions, TRUE);

						/**
						 * do we have sublinks enabled?
						 */
						if ($this->_default['subLinks']) {
							$cells[] = $record[$columnName];
						} else {
							$cells[] = $record[$columnName];
						}
					}
					$i++;
				}
				$data['rows'][] = array(
					'id'=>$firstCell,
					'cell'=>$cells,
				);
			}

			/**
			 * disabel renderer und zend layout to view the pdf
			 */
			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			/**
			 * create pdf object, configure it and send to client
			 */
			$className = 'L8M_ModelForm_Export_' . $form->getValue('export_type');
			$functionName = 'create' . $form->getValue('export_type');
			$export = new $className();
			$export->setData(array(
				'headline'=>$this->_default['listTitle'],
				'subheadline'=>vsprintf($this->_controller->view->translate('As of: %1s'), array(date('d.m.Y'))),
				'filename'=>L8M_Library::getUsableUrlStringOnly($this->_default['listTitle'] . '-' . date('Y-m-d')),
				'definition'=>$data['rows-title'],
				'datas'=>$data['rows'],
			));
			$export->$functionName();
		} else {
			$this->_controller->view->modelFormListLeadThroughUrl = $this->_leadThroughUrl;
		}
	}

	/**
	 * Handling copy of ModelList item
	 *
	 * @param string $listName
	 * @param array $options
	 */
	public function copyModel($listName = NULL, $options = array())
	{
		/**
		 * retrieve model-list
		 */
		$modelListModel = $this->_retrieveList($listName);

		/**
		 * get id to delete
		 */
		$id = $this->_controller->getRequest()->getParam('id', NULL, FALSE);

		/**
		 * page
		 */
		$page = $this->_controller->getRequest()->getParam('page', NULL, FALSE);
		$page = $page
			  ? $page
			  : $this->_default['page']
		;
		if (!is_numeric($page)) {
			$page = $this->_default['page'];
		}

		/**
		 * results per page
		 */
		$resultPerPage = $this->_controller->getRequest()->getParam('rp', NULL, FALSE);
		$resultPerPage = $resultPerPage
			  ? $resultPerPage
			  : $this->_default['resultPerPage']
		;
		if (!is_numeric($resultPerPage)) {
			$resultPerPage = $this->_default['resultPerPage'];
		}

		/**
		 * search link infos
		 */
		$searchQuery = $this->_controller->getRequest()->getParam('query', NULL, FALSE);
		$searchColumn = $this->_controller->getRequest()->getParam('qtype', NULL, FALSE);

		/**
		 * sort order
		 */
		$sortOrder = $this->_controller->getRequest()->getParam('sortorder', NULL, FALSE);
		$sortOrder = $sortOrder
				   ? $sortOrder
				   : 'asc'
		;
		if ($sortOrder != 'asc') {
			$sortOrder = 'desc';
		}

		/**
		 * sort by name
		 */
		$sortName = $this->_controller->getRequest()->getParam('sortname', NULL, FALSE);
		$sortName = $sortName ? $sortName : $modelListModel->default_sort;

		/**
		 * retrive tupel
		 */
		$modelQuery = Doctrine_Query::create()
			->from($this->_modelName . ' ' . $modelListModel->name_short)
			->where($modelListModel->name_short . '.id = ? ', array($id))
		;

		/**
		 * add where
		 */
		$modelQuery = $this->_addWhereToQuery($modelQuery, $modelListModel);

		/**
		 * retrive
		 */
		$originalModel = $modelQuery
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * go on at the end
		 */
		$goOn = TRUE;

		/**
		 * do we have somthing to delete
		 */
		if ($originalModel !== FALSE) {
			$loadModel = $this->_modelName;
			$copiedModel = new $loadModel();
			$copiedModel->merge($originalModel->getData());

			$columnDefinitions = $copiedModel->getTable()->getColumns();
			if (array_key_exists('short', $columnDefinitions)) {
				if (isset($columnDefinitions['short']['length'])) {
					$shortLength = $columnDefinitions['short']['length'];
				} else{
					$shortLength = 45;
				}
			}

			$copiedModel->id = NULL;

			/**
			 * copy Text
			 */
			$copyText = 'CopyOf-';
			if (array_key_exists('name', $columnDefinitions)) {
				$copiedModel->name = $copyText . $copiedModel->name;
			}
			if (array_key_exists('header', $columnDefinitions)) {
				$copiedModel->header = $copyText . $copiedModel->header;
			}
			if (array_key_exists('headline', $columnDefinitions)) {
				$copiedModel->headline = $copyText . $copiedModel->headline;
			}
			if (array_key_exists('title', $columnDefinitions)) {
				$copiedModel->title = $copyText . $copiedModel->title;
			}
			if (array_key_exists('titel', $columnDefinitions)) {
				$copiedModel->titel = $copyText . $copiedModel->titel;
			}
			if (isset($copiedModel['Translation'])) {
				$transColumnDefinitions = $copiedModel->Translation->getTable()->getColumns();
				unset($transColumnDefinitions['id']);
				unset($transColumnDefinitions['lang']);
				unset($transColumnDefinitions['created_at']);
				unset($transColumnDefinitions['updated_at']);
				unset($transColumnDefinitions['deleted_at']);

				foreach(L8M_Locale::getSupported() as $lang) {
					foreach($transColumnDefinitions as $column => $tmpDef) {
						$copiedModel->Translation[$lang]->$column = $originalModel->Translation[$lang]->$column;
					}
				}
				if (array_key_exists('name', $transColumnDefinitions)) {
					$copiedModel->name = $copyText . $copiedModel->Translation[L8M_Locale::getDefault()]->name;
				}
				if (array_key_exists('header', $transColumnDefinitions)) {
					$copiedModel->header = $copyText . $copiedModel->Translation[L8M_Locale::getDefault()]->header;
				}
				if (array_key_exists('headline', $transColumnDefinitions)) {
					$copiedModel->headline = $copyText . $copiedModel->Translation[L8M_Locale::getDefault()]->headline;
				}
				if (array_key_exists('title', $transColumnDefinitions)) {
					$copiedModel->title = $copyText . $copiedModel->Translation[L8M_Locale::getDefault()]->title;
				}
				if (array_key_exists('titel', $transColumnDefinitions)) {
					$copiedModel->titel = $copyText . $copiedModel->Translation[L8M_Locale::getDefault()]->titel;
				}
			}
			$copiedModel->short = L8M_Library::createShort($copiedModel, 'short', $copyText . $copiedModel->short, $shortLength);


			/**
			 * do before save
			 */
			if (isset($options['doBeforeSave']) &&
				is_array($options['doBeforeSave']) &&
				count($options['doBeforeSave']) > 0) {

				foreach ($options['doBeforeSave'] as $autoClass) {
					$autoClassInstance = new $autoClass;
					$tmp = explode('_', $autoClass);
					$autoFunction = strtolower($tmp[count($tmp) - 1]);
					$autoClassInstance->$autoFunction($id, $this->_modelName, $originalModel, $copiedModel);
					if (method_exists($autoClassInstance, 'goOn')) {
						if (!$autoClassInstance->goOn()) {
							$goOn = FALSE;
						}
					}
				}
			}


			/**
			 * save copy
			 */
			$copiedModel->save();


			/**
			 * do after save
			 */
			if (isset($options['doAfterCopy']) &&
				is_array($options['doAfterCopy']) &&
				count($options['doAfterCopy']) > 0) {

				foreach ($options['doAfterCopy'] as $autoClass) {
					$autoClassInstance = new $autoClass;
					$tmp = explode('_', $autoClass);
					$autoFunction = strtolower($tmp[count($tmp) - 1]);
					$autoClassInstance->$autoFunction($id, $this->_modelName, $originalModel, $copiedModel);
					if (method_exists($autoClassInstance, 'goOn')) {
						if (!$autoClassInstance->goOn()) {
							$goOn = FALSE;
						}
					}
				}
			}
		}

		/**
		 * redirect
		 */
		$url = array_merge(
			array(
				'action'=>'list',
				'controller'=>$this->_controller->getRequest()->getControllerName(),
				'module'=>$this->_controller->getRequest()->getModuleName(),
				'modelListName'=>$this->_modelName,
				'rp'=>$resultPerPage,
				'page'=>$page,
				'query'=>$searchQuery,
				'qtype'=>$searchColumn,
				'sortorder'=>$sortOrder,
				'sortname'=>$sortName,
			),
			$this->_leadThroughUrl
		);

		if ($goOn) {
			$this->_redirector->gotoUrl($this->_controller->view->url($url, NULL, TRUE));
		} else {
			$this->_controller->view->modelFormListLeadThroughUrl = $this->_leadThroughUrl;
		}
	}

	/**
	 * Handling delete of ModelList item
	 *
	 * @param array $options
	 */
	public function deleteModel($listName = NULL, $options = array())
	{
		/**
		 * retrieve model-list
		 */
		$modelListModel = $this->_retrieveList($listName);

		/**
		 * check whether is allowed or not
		 */
		if (!$modelListModel->button_delete) {
			throw new L8M_Exception('You are not allowed to delete an item.');
		}

		/**
		 * get id to delete
		 */
		$ids = $this->_controller->getRequest()->getParam('ids', NULL, FALSE);
		if (!is_array($ids)) {
			$ids = array();
		}
		$id = $this->_controller->getRequest()->getParam('id', NULL, FALSE);
		if ($id) {
			$ids[] = $id;
		}
		if (count($ids) > 0) {
			$ids = array_unique($ids);
		}

		/**
		 * page
		 */
		$page = $this->_controller->getRequest()->getParam('page', NULL, FALSE);
		$page = $page
			  ? $page
			  : $this->_controller->getRequest()->getParam('seite', NULL, FALSE);
		$page = $page
			  ? $page
			  : $this->_default['page']
		;
		if (!is_numeric($page)) {
			$page = $this->_default['page'];
		}

		/**
		 * results per page
		 */
		$resultPerPage = $this->_controller->getRequest()->getParam('rp', NULL, FALSE);
		$resultPerPage = $resultPerPage
			  ? $resultPerPage
			  : $this->_default['resultPerPage']
		;
		if (!is_numeric($resultPerPage)) {
			$resultPerPage = $this->_default['resultPerPage'];
		}

		/**
		 * search link infos
		 */
		$searchQuery = $this->_controller->getRequest()->getParam('query', NULL, FALSE);
		$searchColumn = $this->_controller->getRequest()->getParam('qtype', NULL, FALSE);

		/**
		 * sort order
		 */
		$sortOrder = $this->_controller->getRequest()->getParam('sortorder', NULL, FALSE);
		$sortOrder = $sortOrder
				   ? $sortOrder
				   : 'asc'
		;
		if ($sortOrder != 'asc') {
			$sortOrder = 'desc';
		}

		/**
		 * sort by name
		 */
		$sortName = $this->_controller->getRequest()->getParam('sortname', NULL, FALSE);
		$sortName = $sortName ? $sortName : $modelListModel->default_sort;

		/**
		 * store exception
		 */
		$exceptions = array();

		/**
		 * delete all ids
		 */
		$doDeleteOk = TRUE;
		foreach ($ids as $singleId) {
			if (count($exceptions) == 0 &&
				$doDeleteOk) {

				/**
				 * retrive tupel
				 */
				$modelQuery = Doctrine_Query::create()
					->from($this->_modelName . ' ' . $modelListModel->name_short)
					->where($modelListModel->name_short . '.id = ? ', array($singleId))
				;

				/**
				 * add where
				 */
				$availableLeftJoins = $this->_addJoin;
				$modelQuery = $this->_addWhereToQuery($modelQuery, $modelListModel, $availableLeftJoins);

				/**
				 * retrive
				 */
				$modelTupel = $modelQuery
					->limit(1)
					->execute()
					->getFirst();
				;

				/**
				 * do we have somthing to delete
				 */
				if ($modelTupel !== FALSE) {

					/**
					 * by now we could delete modelTupel
					 */
					$doDeleteOk = TRUE;
					$userHasAccess = TRUE;

					/**
					 * check out auto generated values
					 */
					if (isset($options['doBeforePreDelete']) &&
						is_array($options['doBeforePreDelete']) &&
						count($options['doBeforePreDelete']) > 0) {

						foreach ($options['doBeforePreDelete'] as $autoClass) {
							$autoClassInstance = new $autoClass;
							$tmp = explode('_', $autoClass);
							$autoFunction = strtolower($tmp[count($tmp) - 1]);
							$autoClassInstance->$autoFunction($modelTupel);
							if (method_exists($autoClassInstance, 'goOn')) {
								if (!$autoClassInstance->goOn()) {
									$doDeleteOk = FALSE;
									if (method_exists($autoClassInstance, 'getException')) {
										$exceptions[] = $autoClassInstance->getException();
									}
								}
							}
						}
					}

					if ($doDeleteOk) {

						/**
						 * retrieve relations
						 */
						$relations = $modelTupel->getTable()->getRelations();
						$preDeleteReferencedArray = array();
						$relationDeletesOk = array();
						$relationDeleteCount = 0;

						/**
						 * walk through relations
						 *
						 * @var $relation Doctrine_Relation
						 */
						foreach ($relations as $relation) {

							/**
							 * do we have a foreign key relation?
							 */
							if ($relation->getType() === Doctrine_Relation::MANY) {

								/**
								 * retrieve definitions
								 */
								$foreignAlias = $relation->getAlias();
								$foreignClass = $relation->getClass();
								$foreignColumnName = $relation->getForeignColumnName();
								$localColumnName = $relation->getLocalColumnName();
								$foreignTableColumns = $relation->getTable()->getColumns();
								$foreignColumnDefinition = $foreignTableColumns[$foreignColumnName];

								/**
								 * do we have to handle a media
								 */
								if (preg_match('/Default_Model_Media(.*)$/', $foreignClass) ||
									preg_match('/Default_Model_Entity(.*)$/', $foreignClass)) {

									/**
									 * do nothing
									 */
								} else

								/**
								 * do we have to handle a translation
								 */
								if (substr($foreignClass, strlen('Translation') * -1) == 'Translation') {

									/**
									 * do nothing
									 */
								} else {

									/**
									 * check for referenced Model
									 */
									$relationIsReferencedModelConstruct = FALSE;
									$possibleReferencedModelValues = $foreignAlias . 'Values';
									$possibleReferencedModelValuesClass = 'Default_Model_' . $possibleReferencedModelValues;
									$referencedRelations = $modelTupel->$foreignAlias->getTable()->getRelations();
									if (count($referencedRelations) == 3 &&
										array_key_exists($possibleReferencedModelValues, $referencedRelations) &&
										class_exists($possibleReferencedModelValuesClass)) {

										$tmpTryReferenced = new $possibleReferencedModelValuesClass();
										$tmpTryReferencedRelations = $tmpTryReferenced->getTable()->getRelations();
										$tmpTryReferencedColumnDefinition = $tmpTryReferenced->getTable()->getColumns();

										if (count($tmpTryReferencedRelations) == 1 &&
											array_key_exists('referenced_id', $tmpTryReferencedColumnDefinition)) {

											$tmpTryReferencedRelation = array_shift($tmpTryReferencedRelations);
											$possibleReferencedColumnName = $tmpTryReferencedRelation->getLocalColumnName();

											$preDeleteReferencedArray[] = array(
												'foreignClass'=>$foreignClass,
												'foreignColumnName'=>$foreignColumnName,
												'referencedClass'=>$possibleReferencedModelValuesClass,
												'referencedColumnName'=>$possibleReferencedColumnName,
											);
											$relationIsReferencedModelConstruct = TRUE;
										}
									}

									if (!$relationIsReferencedModelConstruct) {

										/**
										 * we could not delete that model tupel as easy as we thought
										 */
										$relationDeleteCount++;
										$relationDeletesOk[$relationDeleteCount] = FALSE;

										/**
										 * retrive collection, that has to be deleted before
										 */
										$tryToDeleteCollection = Doctrine_Query::create()
											->from($foreignClass . ' m')
											->where('m.' . $foreignColumnName . ' = ? ', array($modelTupel[$localColumnName]))
											->execute()
										;

										/**
										 * columns to delete:
										 * greater or equal then 0
										 * not required
										 */
										if (!array_key_exists('notnull', $foreignColumnDefinition)) {
											$foreignColumnDefinition['notnull'] = FALSE;
										}

										/**
										 * test for deleting column
										 */
										if ($tryToDeleteCollection->count() == 0) {

											/**
											 * we could delete column
											 */
											$relationDeletesOk[$relationDeleteCount] = TRUE;
										} else
										if ($tryToDeleteCollection->count() > 0 &&
											$foreignColumnDefinition['notnull'] === FALSE) {

											/**
											 * temp value
											 */
											$relationDeletesOkTryNullSave = TRUE;

											/**
											 * walk through collection and set column-value NULL and save
											 */
											foreach ($tryToDeleteCollection as $tryToDeleteModel) {
												$tryToDeleteModel->$foreignColumnName = NULL;
												try {
													$tryToDeleteModel->save();
												} catch (Exception $e) {
													$exceptions[] = $e;
													$relationDeletesOkTryNullSave = FALSE;
												}
											}

											/**
											 * we could delete column
											 */
											$relationDeletesOk[$relationDeleteCount] = $relationDeletesOkTryNullSave;
										} else

										if ($tryToDeleteCollection->count() > 0 &&
											$foreignColumnDefinition['notnull'] === TRUE) {

											/**
											 * maybe an m2n
											 */
											$tTTDCIsMayByM2N = FALSE;

											/**
											 * relations have to be not null = true
											 */
											$tTTDCRAreNotNull = FALSE;

											/**
											 * relations have column position
											 */
											$tTTDCHasPosition = FALSE;

											/**
											 * relations have translation
											 */
											$tTTDCHasTranslation = FALSE;

											/**
											 * try to delete collection relations
											 */
											$tTTDCR = $tryToDeleteCollection->getTable()->getRelations();

											/**
											 * try to delete collection columns
											 */
											$tTTDCColumns = $tryToDeleteCollection->getTable()->getColumns();

											unset($tTTDCColumns['id']);
											unset($tTTDCColumns['created_at']);
											unset($tTTDCColumns['updated_at']);
											unset($tTTDCColumns['deleted_at']);

											if (count($tTTDCR) == 2) {
												if (count($tTTDCColumns) == 2) {
													foreach ($tTTDCColumns as $tTTDCColumn => $tTTDCColumnDefinition) {
														if (array_key_exists('notnull', $tTTDCColumnDefinition) &&
															$tTTDCColumnDefinition['notnull'] == TRUE) {

															$tTTDCRAreNotNull = TRUE;
														} else {
															$tTTDCRAreNotNull = FALSE;
														}
													}
													if ($tTTDCRAreNotNull) {
														$tTTDCIsMayByM2N = TRUE;
													}
												} else
												if (count($tTTDCColumns) == 3) {
													foreach ($tTTDCColumns as $tTTDCColumn => $tTTDCColumnDefinition) {
														if ($tTTDCColumn != 'position') {
															if (array_key_exists('notnull', $tTTDCColumnDefinition) &&
																$tTTDCColumnDefinition['notnull'] == TRUE) {

																$tTTDCRAreNotNull = TRUE;
															} else {
																$tTTDCRAreNotNull = FALSE;
															}
														} else
														if ($tTTDCColumn == 'position') {
															$tTTDCHasPosition = TRUE;
														}
													}
													if ($tTTDCRAreNotNull &&
														$tTTDCHasPosition) {

														$tTTDCIsMayByM2N = TRUE;
													}
												} else
												if (count($tTTDCColumns) == 4) {
													if (array_key_exists('position', $tTTDCColumns) &&
														array_key_exists('value', $tTTDCColumns) &&
														count($tTTDCR) == 2) {

														$tTTDCIsMayByM2N = TRUE;
													}
												}
											} else
											if (count($tTTDCR) == 3) {
												foreach ($tTTDCR as $tTTDCROne) {
													if (substr($tTTDCROne->getClass(), strlen('Translation') * -1) == 'Translation') {
														$tTTDCHasTranslation = TRUE;
													}
												}
												if ($tTTDCHasTranslation) {
													if (count($tTTDCColumns) == 2) {
														foreach ($tTTDCColumns as $tTTDCColumn => $tTTDCColumnDefinition) {
															if (array_key_exists('notnull', $tTTDCColumnDefinition) &&
																$tTTDCColumnDefinition['notnull'] == TRUE) {

																$tTTDCRAreNotNull = TRUE;
															} else {
																$tTTDCRAreNotNull = FALSE;
															}
														}
														if ($tTTDCRAreNotNull) {
															$tTTDCIsMayByM2N = TRUE;
														}
													} else
													if (count($tTTDCColumns) == 3) {
														foreach ($tTTDCColumns as $tTTDCColumn => $tTTDCColumnDefinition) {
															if ($tTTDCColumn != 'position') {
																if (array_key_exists('notnull', $tTTDCColumnDefinition) &&
																	$tTTDCColumnDefinition['notnull'] == TRUE) {

																	$tTTDCRAreNotNull = TRUE;
																} else {
																	$tTTDCRAreNotNull = FALSE;
																}
															} else
															if ($tTTDCColumn == 'position') {
																$tTTDCHasPosition = TRUE;
															}
														}
														if ($tTTDCRAreNotNull &&
															$tTTDCHasPosition) {

															$tTTDCIsMayByM2N = TRUE;
														}
													}
												}
											}

											/**
											 * this is maybe a m2n try to delete
											 */
											if ($tTTDCIsMayByM2N) {
												$relationDeletesOk[$relationDeleteCount] = TRUE;
												foreach ($tryToDeleteCollection as $tryToDeleteModel) {
													try {
														$tryToDeleteModel->hardDelete();
													} catch (Exception $e) {
														$exceptions[] = $e;
														$relationDeletesOk[$relationDeleteCount] = FALSE;
													}
												}
											}

										} else

										if ($tryToDeleteCollection->count() == 0 &&
											$foreignColumnDefinition['notnull'] === TRUE) {

											/**
											 * we could delete column
											 */
											$relationDeletesOk[$relationDeleteCount] = TRUE;
										}
									}
								}
							}
						}

						/**
						 * check okay for relation deletes
						 */
						foreach ($relationDeletesOk as $relationOneDeleteOk) {
							if ($relationOneDeleteOk == FALSE) {
								$doDeleteOk = FALSE;
							}
						}

						/**
						 * check out do before
						 */
						if (isset($options['doBefore']) &&
							is_array($options['doBefore']) &&
							count($options['doBefore']) > 0) {

							foreach ($options['doBefore'] as $autoClass) {
								$autoClassInstance = new $autoClass;
								$tmp = explode('_', $autoClass);
								$autoFunction = strtolower($tmp[count($tmp) - 1]);
								$autoClassInstance->$autoFunction($modelTupel);
								if (method_exists($autoClassInstance, 'goOn')) {
									if (!$autoClassInstance->goOn()) {
										$doDeleteOk = FALSE;
										if (method_exists($autoClassInstance, 'getException')) {
											$exceptions[] = $autoClassInstance->getException();
										}
									}
								}
							}
						}
					}

					/**
					 * delete banner
					 */
					if ($doDeleteOk) {

						/**
						 * do we have to handle a media
						 */
						if ($modelTupel instanceof Default_Model_Media) {
							$mediaAcl = new L8M_Acl_Media();
							if (!$mediaAcl->checkMedia($modelTupel)) {
								$userHasAccess = FALSE;
								$doDeleteOk = FALSE;
							} else {
								$mediaTypeShort = $modelTupel->MediaType->short;
								if ($mediaTypeShort == 'file' ||
									$mediaTypeShort == 'image' ||
									$mediaTypeShort == 'shockwave') {

										/**
										 * delete media file and its instances or
										 * just only delete the media instance and its file
										 */
										try {
											if (!$modelTupel->hardDelete()) {
												$doDeleteOk = FALSE;
											}
										} catch (Exception $e) {
											$exceptions[] = $e;
											$doDeleteOk = FALSE;
										}
								} else {
									$userHasAccess = FALSE;
									$doDeleteOk = FALSE;
								}
							}
						} else {

							/**
							 * delete possible referenced models
							 */
							$noError = TRUE;
							foreach ($preDeleteReferencedArray as $preDeleteReferenced) {
								$referencedCollection = Doctrine_Query::create()
									->from($preDeleteReferenced['foreignClass'] . ' m')
									->addWhere('m.' . $preDeleteReferenced['foreignColumnName'] . ' = ?', array($modelTupel->id))
									->execute()
								;

								foreach ($referencedCollection as $referencedModel) {
									$referencedValuesCollection = Doctrine_Query::create()
										->from($preDeleteReferenced['referencedClass'] . ' m')
										->addWhere('m.' . $preDeleteReferenced['referencedColumnName'] . ' = ?', array($referencedModel->id))
										->execute()
									;

									foreach ($referencedValuesCollection as $referencedValuesModel) {
										if ($noError) {
											try {
												$referencedValuesModel->hardDelete();
											} catch (Exception $e) {
												$exceptions[] = $e;
												$noError = FALSE;
											}
										}
									}

									if ($noError) {
										try {
											$referencedModel->hardDelete();
										} catch (Exception $e) {
											$exceptions[] = $e;
											$noError = FALSE;
										}
									}
								}
							}

							if ($noError) {
								try {
									$modelTupel->hardDelete();
								} catch (Exception $e) {
									$exceptions[] = $e;
									$doDeleteOk = FALSE;
								}
							}
						}
					}
				} else {

					/**
					 * model tupel does not exist, so why a warning - redirecting is the best way
					 * so we set all variables as if we had deleted the model tupel
					 */
					$doDeleteOk = TRUE;
					$userHasAccess = TRUE;
				}
			}
		}

		/**
		 * redirect
		 */
		$url = array_merge(
			array(
				'action'=>'list',
				'controller'=>$this->_controller->getRequest()->getControllerName(),
				'module'=>$this->_controller->getRequest()->getModuleName(),
				'modelListName'=>$this->_modelName,
				'rp'=>$resultPerPage,
				'page'=>$page,
				'query'=>$searchQuery,
				'qtype'=>$searchColumn,
				'sortorder'=>$sortOrder,
				'sortname'=>$sortName,
			),
			$this->_leadThroughUrl
		);

		$redirectParams = $this->_controller->getRequest()->getParam('redirect', NULL, FALSE);
		if($redirectParams != NULL) {
			$redirectParams = json_decode($redirectParams, TRUE);
			foreach($redirectParams as $key=>$value) {
				if(!in_array($key, array('controller', 'module', 'modelListName', 'action')))
					unset($redirectParams[$key]);
			}
			$url = array_merge(
				$url,
				$redirectParams
			);
		}

		if ($doDeleteOk) {
			$this->_redirector->gotoUrl($this->_controller->view->url($url, NULL, TRUE));
		} else {
			$backUrl = $this->_controller->view->url($url, NULL, TRUE);
			$this->_controller->view->backUrl = $backUrl;
			$this->_controller->view->userHasAccess = $userHasAccess;
			$this->_controller->view->deleteExceptions = $exceptions;
			$this->_controller->view->modelFormListLeadThroughUrl = $this->_leadThroughUrl;
		}
	}

	/**
	 * Handling creator of ModelList item
	 *
	 * @param array $options
	 */
	public function createModel($listName = NULL, $options = array())
	{
		/**
		 * retrieve model-list
		 */
		$modelListModel = $this->_retrieveList($listName);

		/**
		 * check whether is allowed or not
		 */
		if (!$modelListModel->button_add) {
			throw new L8M_Exception('You are not allowed to create an item.');
		}

		/**
		 * save and add next
		 */
		$saveAndAddNext = $this->_controller->getRequest()->getParam('backToForm', NULL, FALSE);
		if ($saveAndAddNext == 'true') {
			$backToList = FALSE;
		} else {
			$backToList = TRUE;
		}

		/**
		 * page
		 */
		$page = $this->_controller->getRequest()->getParam('page', NULL, FALSE);
		$page = $page
			  ? $page
			  : $this->_controller->getRequest()->getParam('seite', NULL, FALSE);
		$page = $page
			  ? $page
			  : $this->_default['page']
		;
		if (!is_numeric($page)) {
			$page = $this->_default['page'];
		}

		/**
		 * results per page
		 */
		$resultPerPage = $this->_controller->getRequest()->getParam('rp', NULL, FALSE);
		$resultPerPage = $resultPerPage
			  ? $resultPerPage
			  : $this->_default['resultPerPage']
		;
		if (!is_numeric($resultPerPage)) {
			$resultPerPage = $this->_default['resultPerPage'];
		}

		/**
		 * search link infos
		 */
		$searchQuery = $this->_controller->getRequest()->getParam('query', NULL, FALSE);
		$searchColumn = $this->_controller->getRequest()->getParam('qtype', NULL, FALSE);

		/**
		 * sort order
		 */
		$sortOrder = $this->_controller->getRequest()->getParam('sortorder', NULL, FALSE);
		$sortOrder = $sortOrder
				   ? $sortOrder
				   : 'asc'
		;
		if ($sortOrder != 'asc') {
			$sortOrder = 'desc';
		}

		/**
		 * sort by name
		 */
		$sortName = $this->_controller->getRequest()->getParam('sortname', NULL, FALSE);
		$sortName = $sortName ? $sortName : $modelListModel->default_sort;

		/**
		 * prepare back button
		 */
		$paramArray = array(
			'rp',
			'query',
			'qtype',
			'sortname',
			'sortorder',
			'page',
		);

		$paramValues = '?';
		foreach ($paramArray as $param) {
			$paramValues .= $param . '=' . $this->_controller->getRequest()->getParam($param, NULL, FALSE) . '&';
		}

		if((count($this->_default['controllerParams']) > 0) && isset($this->_default['controllerParams']['controllerParamName'])) {
			$paramValues .= $this->_default['controllerParams']['controllerParamName'] . '=' . $this->_default['controllerParams']['controllerParamValue'] . '&';
		}

		/**
		 * form param values for back button
		 */
		$this->_controller->view->formParamValues = $paramValues;

		/**
		 * form back button url if redirect to other controller
		 */
		$formBackUrl = NULL;
		$redirectParams = $this->_controller->getRequest()->getParam('redirect', NULL, FALSE);
		if($redirectParams != NULL) {
			$redirectParams = json_decode($redirectParams, TRUE);
			$formBackUrl = $this->_controller->view->url(
				array_merge(
					$redirectParams,
					array('action'=>'list')
				),
				NULL,
				TRUE
			);

			$formBackUrl .= $paramValues;
		}

		/**
		 * retrieve options for ignoring columns
		 */
		$optionsModel['addIgnoredColumns'] = array();
		$modelListEditIgnoreCollection = Doctrine_Query::create()
			->from('Default_Model_ModelListEditIgnore mlei')
			->leftJoin('mlei.ModelList ml')
			->where('ml.name = ? ', array($this->_modelName))
			->execute()
		;
		foreach ($modelListEditIgnoreCollection as $modelListEditIgnoreModel) {
			$optionsModel['addIgnoredColumns'][] = $modelListEditIgnoreModel->ModelColumnName->name;
		}

		/**
		 * retrieve options for column labels
		 */
		$optionsModel['modelListName'] = $listName;
		$optionsModel['columnLabels'] = array();
		$optionsModel['columnTypes'] = array();
		$optionsModel['order'] = array();
		$optionsModel['addStaticFormElements'] = array();
		if ($this->_default['hideShort']) {
			$optionsModel['addIgnoredColumns'] = array(
				'short',
				'ushort',
			);
		} else {
			$optionsModel['addIgnoredColumns'] = array();
		}
		$optionsModel['addIgnoredM2nRelations'] = array();
		$optionsModel['ignoreColumnRelation'] = array();
		$optionsModel['ignoreColumnInMultiRelation'] = array();
		$optionsModel['relationM2nValuesDefinition'] = array();
		$optionsModel['mediaDirectory'] = array();
		$optionsModel['mediaRole'] = array();
		$optionsModel['Position'] = array();

		$modelListColumnCollection = Doctrine_Query::create()
			->from('Default_Model_ModelListColumn mlc')
			->leftJoin('mlc.ModelList ml')
			->where('ml.name = ? ', array($this->_modelName))
			->orderBy('mlc.position ASC')
			->execute()
		;
		foreach ($modelListColumnCollection as $modelListColumnModel) {
			$columnDisplay = $modelListColumnModel->ModelColumnName->Translation[L8M_Locale::getLang()]['display'];
			if ($columnDisplay) {
				$optionsModel['columnLabels'][] = $modelListColumnModel->ModelColumnName->Translation[L8M_Locale::getLang()]['display'];
			}

			$columnEditAs = $modelListColumnModel->ModelColumnName->ModelColumnNameEditAs->short;
			if ($columnEditAs) {
				$optionsModel['columnTypes'][$modelListColumnModel->ModelColumnName->name] = $modelListColumnModel->ModelColumnName->ModelColumnNameEditAs->short;
			}

//			$optionsModel['order'][] = $modelListColumnModel->ModelColumnName->name;
		}

		/**
		 * options
		 */
		if (isset($options['order'])) {
			$optionsModel['order'] = array_merge($options['order'], $optionsModel['order']);
		}
		if (isset($options['addIgnoredColumns'])) {
			$optionsModel['addIgnoredColumns'] = array_merge($optionsModel['addIgnoredColumns'], $options['addIgnoredColumns']);
		}
		if (isset($options['addIgnoredM2nRelations'])) {
			$optionsModel['addIgnoredM2nRelations'] = array_merge($optionsModel['addIgnoredM2nRelations'], $options['addIgnoredM2nRelations']);
		}
		if (isset($options['ignoreColumnRelation'])) {
			$optionsModel['ignoreColumnRelation'] = array_merge($optionsModel['ignoreColumnRelation'], $options['ignoreColumnRelation']);
		}
		if (isset($options['ignoreColumnInMultiRelation'])) {
			$optionsModel['ignoreColumnInMultiRelation'] = array_merge($optionsModel['ignoreColumnInMultiRelation'], $options['ignoreColumnInMultiRelation']);
		}
		if (isset($options['relationM2nValuesDefinition'])) {
			$optionsModel['relationM2nValuesDefinition'] = array_merge($optionsModel['relationM2nValuesDefinition'], $options['relationM2nValuesDefinition']);
		}
		if (isset($options['mediaDirectory'])) {
			$optionsModel['mediaDirectory'] = array_merge($optionsModel['mediaDirectory'], $options['mediaDirectory']);
		}
		if (isset($options['mediaRole'])) {
			$optionsModel['mediaRole'] = array_merge($optionsModel['mediaRole'], $options['mediaRole']);
		}
		if (isset($options['columnLabels'])) {
			$optionsModel['columnLabels'] = array_merge($optionsModel['columnLabels'], $options['columnLabels']);
		}
		if (isset($options['buttonLabel'])) {
			$optionsModel['buttonLabel'] = $options['buttonLabel'];
		}
		if (isset($options['columnTypes'])) {
			$optionsModel['columnTypes'] = array_merge($optionsModel['columnTypes'], $options['columnTypes']);
		}
		if (isset($options['addStaticFormElements'])) {
			$optionsModel['addStaticFormElements'] = array_merge($optionsModel['addStaticFormElements'], $options['addStaticFormElements']);
		}
		if (isset($options['M2NRelations'])) {
			$optionsModel['M2NRelations'] = $options['M2NRelations'];
		}
		if (isset($options['replaceColumnValuesInMultiRelation'])) {
			$optionsModel['replaceColumnValuesInMultiRelation'] = $options['replaceColumnValuesInMultiRelation'];
		}
		if (isset($options['relationColumnInMultiRelation'])) {
			$optionsModel['relationColumnInMultiRelation'] = $options['relationColumnInMultiRelation'];
		}
		if (isset($options['multiRelationCondition'])) {
			$optionsModel['multiRelationCondition'] = $options['multiRelationCondition'];
		}
		if (isset($options['tinyMCE'])) {
			$optionsModel['tinyMCE'] = $options['tinyMCE'];
		}
		if (isset($options['Position'])) {
			$optionsModel['Position'] = $options['Position'];
		}
		if (isset($options['setFormLanguage'])) {
			$optionsModel['setFormLanguage'] = $options['setFormLanguage'];
		} else {
			$optionsModel['setFormLanguage'] = $this->_default['formLanguage'];
		}
		if (isset($options['action'])) {
			$urlAction = $options['action'];
		} else {
			$urlAction = 'create';
		}
		$optionsModel['showBackAfterSave'] = $this->_default['showBackAfterSave'];

		/**
		 * set url
		 */
		$url = array_merge(
			array(
				'action'=>$urlAction,
				'controller'=>$this->_controller->getRequest()->getControllerName(),
				'module'=>$this->_controller->getRequest()->getModuleName(),
				'modelListName'=>$this->_modelName,
				'rp'=>$resultPerPage,
				'page'=>$page,
				'query'=>$searchQuery,
				'qtype'=>$searchColumn,
				'sortorder'=>$sortOrder,
				'sortname'=>$sortName,
			),
			$this->_leadThroughUrl
		);

		$redirectParams = $this->_controller->getRequest()->getParam('redirect', NULL, FALSE);
		if($redirectParams != NULL) {
			$url = array_merge(
				$url,
				array('redirect'=>$redirectParams)
			);
		}

		/**
		 * create form
		 *
		 * @var L8M_ModelForm_Base
		 */
		$form = L8M_ModelForm_Base::create($this->_modelName, $optionsModel);
		$form->setAction($this->_controller->view->url($url, NULL, TRUE));

		/**
		 * store exceptions
		 */
		$exception = NULL;

		/**
		 * start form actions
		 */
		if ($form->isSubmitted() &&
			$form->isValid($this->_controller->getRequest()->getParams())) {

			$loadModel = $this->_modelName;
			$loadedModel = new $loadModel();

			$formValues = $form->getValues();
			if ($this->_default['hideShort']) {
				$columnDefinitions = $loadedModel->getTable()->getColumns();
				if (array_key_exists('short', $columnDefinitions)) {
					if (isset($columnDefinitions['short']['length'])) {
						$shortLength = $columnDefinitions['short']['length'];
					} else{
						$shortLength = 45;
					}
					if ($loadedModel instanceof Default_Model_Media) {
						$isMediaModel = TRUE;
					} else {
						$isMediaModel = FALSE;
					}
					if ((!$isMediaModel && array_key_exists('name', $columnDefinitions) && isset($formValues['name']) && $formValues['name'] != '') ||
						(!$isMediaModel && array_key_exists('name_addition', $columnDefinitions) && $formValues['name_addition'] != '')) {

						if (array_key_exists('name_addition', $columnDefinitions) &&
							$formValues['name_addition'] != '') {

							$shortText = $formValues['name'] . '-' . $formValues['name_addition'];
						} else {
							$shortText = $formValues['name'];
						}
						$formValues['short'] = L8M_Library::createShort($loadedModel, 'short', $shortText, $shortLength);
					} else
					if ((!$isMediaModel && array_key_exists('title', $columnDefinitions) && $formValues['title'] != '') ||
						(!$isMediaModel && array_key_exists('subtitle', $columnDefinitions) && $formValues['subtitle'] != '') ||
						(!$isMediaModel && array_key_exists('sub_title', $columnDefinitions) && $formValues['sub_title'] != '')) {

						if (array_key_exists('subtitle', $columnDefinitions) &&
							$formValues['subtitle'] != '') {

							$shortText = $formValues['title'] . '-' . $formValues['subtitle'];
						} else
						if (array_key_exists('sub_title', $columnDefinitions) &&
							$formValues['sub_title'] != '') {

							$shortText = $formValues['title'] . '-' . $formValues['sub_title'];
						} else {
							$shortText = $formValues['title'];
						}
						$formValues['short'] = L8M_Library::createShort($loadedModel, 'short', $shortText, $shortLength);
					} else
					if ((!$isMediaModel && array_key_exists('headline', $columnDefinitions) && $formValues['headline'] != '') ||
						(!$isMediaModel && array_key_exists('subheadline', $columnDefinitions) && $formValues['subheadline'] != '') ||
						(!$isMediaModel && array_key_exists('sub_headline', $columnDefinitions) && $formValues['sub_headline'] != '')) {

						if (array_key_exists('subheadline', $columnDefinitions) &&
							$formValues['subheadline'] != '') {

							$shortText = $formValues['headline'] . '-' . $formValues['subheadline'];
						} else
						if (array_key_exists('sub_headline', $columnDefinitions) &&
							$formValues['sub_headline'] != '') {

							$shortText = $formValues['headline'] . '-' . $formValues['sub_headline'];
						} else {
							$shortText = $formValues['headline'];
						}
						$formValues['short'] = L8M_Library::createShort($loadedModel, 'short', $shortText, $shortLength);
					} else
					if (!$isMediaModel &&
						array_key_exists('header', $columnDefinitions) &&
						$formValues['header'] != '') {

						$formValues['short'] = L8M_Library::createShort($loadedModel, 'short', $formValues['header'], $shortLength);
					} else
					if (array_key_exists('Translation', $formValues) &&
						array_key_exists(L8M_Locale::getDefault(), $formValues['Translation'])) {

						$transColumnDefinitions = $loadedModel->Translation->getTable()->getColumns();
						$transFormValues = $formValues['Translation'][L8M_Locale::getDefault()];

						if (!$isMediaModel &&
							array_key_exists('uname', $transColumnDefinitions)) {

							if ($transFormValues['uname'] != $loadedModel['Translation'][L8M_Locale::getDefault()]['uname']) {
								$formValues['short'] = L8M_Library::createShort($loadedModel, 'short', $transFormValues['uname'], $shortLength);
							} else {
								$formValues['short'] = $loadedModel['short'];
							}
						} else
						if ((!$isMediaModel && array_key_exists('name', $transColumnDefinitions) && $transFormValues['name'] != '') ||
							(!$isMediaModel && array_key_exists('name_addition', $transColumnDefinitions) && $transFormValues['name_addition'] != '')) {

							if (array_key_exists('name_addition', $transColumnDefinitions) &&
								$transFormValues['name_addition'] != '') {

								$shortText = $transFormValues['name'] . '-' . $transFormValues['name_addition'];
							} else {
								$shortText = $transFormValues['name'];
							}
							$formValues['short'] = L8M_Library::createShort($loadedModel, 'short', $shortText, $shortLength);
						} else
						if ((!$isMediaModel && array_key_exists('title', $transColumnDefinitions) && $transFormValues['title'] != '') ||
							(!$isMediaModel && array_key_exists('subtitle', $transColumnDefinitions) && $transFormValues['subtitle'] != '') ||
							(!$isMediaModel && array_key_exists('sub_title', $transColumnDefinitions) && $transFormValues['sub_title'] != '')) {

							if (array_key_exists('subtitle', $transColumnDefinitions) &&
								$transFormValues['subtitle'] != '') {

								$shortText = $transFormValues['title'] . '-' . $transFormValues['subtitle'];
							} else
							if (array_key_exists('sub_title', $transColumnDefinitions) &&
								$transFormValues['sub_title'] != '') {

								$shortText = $transFormValues['title'] . '-' . $transFormValues['sub_title'];
							} else {
								$shortText = $transFormValues['title'];
							}
							$formValues['short'] = L8M_Library::createShort($loadedModel, 'short', $shortText, $shortLength);
						} else
						if ((!$isMediaModel && array_key_exists('headline', $transColumnDefinitions) && $transFormValues['headline'] != '') ||
							(!$isMediaModel && array_key_exists('subheadline', $transColumnDefinitions) && $transFormValues['subheadline'] != '') ||
							(!$isMediaModel && array_key_exists('sub_headline', $transColumnDefinitions) && $transFormValues['sub_headline'] != '')) {

							if (array_key_exists('subheadline', $transColumnDefinitions) &&
								$transFormValues['subheadline'] != '') {

								$shortText = $transFormValues['headline'] . '-' . $transFormValues['subheadline'];
							} else
							if (array_key_exists('sub_headline', $transColumnDefinitions) &&
								$transFormValues['sub_headline'] != '') {

								$shortText = $transFormValues['headline'] . '-' . $transFormValues['sub_headline'];
							} else {
								$shortText = $transFormValues['headline'];
							}
							$formValues['short'] = L8M_Library::createShort($loadedModel, 'short', $shortText, $shortLength);
						} else
						if (!$isMediaModel &&
							array_key_exists('header', $transColumnDefinitions) &&
							$transFormValues['header'] != '') {

							$formValues['short'] = L8M_Library::createShort($loadedModel, 'short', $transFormValues['header'], $shortLength);
						}
					}

					/**
					 * uhort
					 */
					if (!$isMediaModel &&
						array_key_exists('Translation', $formValues) &&
						L8M_Library::arrayKeysExists(L8M_Locale::getSupported(), $formValues['Translation'])) {

						$transColumnDefinitions = $loadedModel->Translation->getTable()->getColumns();
						if (array_key_exists('ushort', $transColumnDefinitions) &&
							isset($transColumnDefinitions['ushort']['notnull']) &&
							$transColumnDefinitions['ushort']['notnull'] == TRUE) {

							$uShortOriginal = NULL;
							if (array_key_exists('uname', $transColumnDefinitions) &&
								isset($transColumnDefinitions['uname']['notnull']) &&
								$transColumnDefinitions['uname']['notnull'] == TRUE) {

								$uShortOriginal = L8M_Library::arrayCopySubKeyToMajorKey('uname', $formValues['Translation']);
							} else
							if (array_key_exists('name', $transColumnDefinitions) &&
								isset($transColumnDefinitions['name']['notnull']) &&
								$transColumnDefinitions['name']['notnull'] == TRUE) {

								$uShortOriginal = L8M_Library::arrayCopySubKeyToMajorKey('name', $formValues['Translation']);
							} else
							if (array_key_exists('title', $transColumnDefinitions) &&
								isset($transColumnDefinitions['title']['notnull']) &&
								$transColumnDefinitions['title']['notnull'] == TRUE) {

								$uShortOriginal = L8M_Library::arrayCopySubKeyToMajorKey('title', $formValues['Translation']);
							}

							if ($uShortOriginal) {
								foreach (L8M_Locale::getSupported() as $tmpLang) {
									$formValues['Translation'][$tmpLang]['ushort'] = L8M_Library::createUShort($loadedModel, $tmpLang, $uShortOriginal[$tmpLang], $transColumnDefinitions['ushort']['length']);
								}
							}
						}
					}

					/**
					 * check short
					 */
					if (isset($columnDefinitions['short']['notnull']) &&
						$columnDefinitions['short']['notnull'] == TRUE &&
						!isset($formValues['short']) &&
						$loadedModel['short'] == NULL) {

						$formValues['short'] = NULL;

						foreach ($columnDefinitions as $columnDefinitionName => $columnDefinition) {
							if (strpos($columnDefinitionName, 'name') !== FALSE &&
								($columnDefinition['type'] == 'string' || $columnDefinition['type'] == 'clob' ||  $columnDefinition['type'] == 'clob') &&
								isset($formValues[$columnDefinitionName]) &&
								strlen(trim($formValues[$columnDefinitionName])) > 0) {

								$formValues['short'] = trim($formValues[$columnDefinitionName]);
							}
						}

						if (!$formValues['short']) {
							$formValues['short'] = md5(time());
						}
						$formValues['short'] = L8M_Library::createShort($loadedModel, 'short', $formValues['short'], $shortLength);
					}
				}
			}

			$goOn = TRUE;

			/**
			 * check out auto generated values
			 */
			if (isset($options['doBeforeSave']) &&
				is_array($options['doBeforeSave']) &&
				count($options['doBeforeSave']) > 0) {

				foreach ($options['doBeforeSave'] as $autoClass) {
					if ($goOn) {
						$autoClassInstance = new $autoClass;
						$tmp = explode('_', $autoClass);
						$autoFunction = strtolower($tmp[count($tmp) - 1]);
						$autoClassInstance->$autoFunction($this->_modelName, $formValues, $form);
						if (method_exists($autoClassInstance, 'goOn')) {
							if (!$autoClassInstance->goOn()) {
								$goOn = FALSE;
								if (method_exists($autoClassInstance, 'getException')) {
									$exception = $autoClassInstance->getException();
								}
							} else {
								if (method_exists($autoClassInstance, 'replaceFormValues')) {
									$formValues = $autoClassInstance->replaceFormValues();
								}
							}
						}
					}
				}
			}

			if ($goOn) {
				$loadedModel->merge($formValues);

				/**
				 * merge static / standard values
				 */
				if (isset($options['addStandardColumnValues']) &&
					is_array($options['addStandardColumnValues']) &&
					count($options['addStandardColumnValues']) > 0) {

					foreach ($options['addStandardColumnValues'] as $standardColumn => $standardValue) {
						$loadedModel->merge(array($standardColumn=>$standardValue));
					}
				}

				/**
				 * save model without auto genereated values
				 */
				try {
					$loadedModel->save();
				} catch (Exception $exception) {
					$goOn = FALSE;
				}

				if ($goOn) {

					/**
					 * check out auto generated column values
					 */
					if (isset($options['addGeneratedColumnValues']) &&
						is_array($options['addGeneratedColumnValues']) &&
						count($options['addGeneratedColumnValues']) > 0) {

						foreach ($options['addGeneratedColumnValues'] as $autoColumn => $autoClass) {
							$autoClassInstance = new $autoClass;
							$loadedModel->merge(array($autoColumn=>$autoClassInstance->$autoColumn($loadedModel->id)));
						}

						/**
						 * try saving new datas
						 */
						try {
							$loadedModel->save();
						} catch (Exception $exception) {
							$goOn = FALSE;
						}
					}

					if ($goOn) {

						/**
						 * check m2n relations
						 */
						$this->_handleM2N($form->getM2NRelationFormInfos(), $loadedModel, $formValues);

						/**
						 * check positions
						 */
						$this->_handlePosition($form->getPositionFormInfos(), $loadedModel, $formValues);

						/**
						 * check out auto generated values
						 */
						if (isset($options['addGeneratedValues']) &&
							is_array($options['addGeneratedValues']) &&
							count($options['addGeneratedValues']) > 0) {

							foreach ($options['addGeneratedValues'] as $autoClass) {
								if ($goOn) {
									$autoClassInstance = new $autoClass;
									$tmp = explode('_', $autoClass);
									$autoFunction = strtolower($tmp[count($tmp) - 1]);
									$autoClassInstance->$autoFunction($loadedModel, $form);
									if (method_exists($autoClassInstance, 'goOn')) {
										if (!$autoClassInstance->goOn()) {
											$goOn = FALSE;
											if (method_exists($autoClassInstance, 'getException')) {
												$exception = $autoClassInstance->getException();
											}
										}
									}
								}
							}
						}

						/**
						 * do after save
						 */
						if (isset($options['doAfterSave']) &&
							is_array($options['doAfterSave']) &&
							count($options['doAfterSave']) > 0) {

							foreach ($options['doAfterSave'] as $autoClass) {
								if ($goOn) {
									$autoClassInstance = new $autoClass;
									$tmp = explode('_', $autoClass);
									$autoFunction = strtolower($tmp[count($tmp) - 1]);
									$autoClassInstance->$autoFunction($loadedModel->id, $this->_modelName, $formValues);
									if (method_exists($autoClassInstance, 'goOn')) {
										if (!$autoClassInstance->goOn()) {
											$goOn = FALSE;
											if (method_exists($autoClassInstance, 'getException')) {
												$exception = $autoClassInstance->getException();
											}
										}
									}
								}
							}
						}
					}
				}
			}

			/**
			 * redirect
			 */
			$url = array_merge(
				array(
					'action'=>'list',
					'controller'=>$this->_controller->getRequest()->getControllerName(),
					'module'=>$this->_controller->getRequest()->getModuleName(),
					'modelListName'=>$this->_modelName,
					'rp'=>$resultPerPage,
					'page'=>$page,
					'query'=>$searchQuery,
					'qtype'=>$searchColumn,
					'sortorder'=>$sortOrder,
					'sortname'=>$sortName,
					'createdModel'=>$this->_modelName,
					'modelID'=>$loadedModel->id
				),
				$this->_leadThroughUrl
			);

			$redirectParams = $this->_controller->getRequest()->getParam('redirect', NULL, FALSE);
			if($redirectParams != NULL) {
				$redirectParams = json_decode($redirectParams, TRUE);

				$url = array_merge(
					$url,
					$redirectParams
				);
			}

			/**
			 * redirect decision
			 */
			if ($goOn &&
				$this->_default['doRedirect'] &&
				$backToList) {

				$goOnRedirect = TRUE;
			} else {
				$goOnRedirect = FALSE;
			}
			if ($this->_default['showBackAfterSave'] &&
				$this->_controller->getRequest()->getParam('l8m_system_back_after_save')) {

				$goOnRedirect = FALSE;
			}
			if ($goOnRedirect) {
				$urlArray = array(
					'action'=>$url['action'],
					'controller'=>$url['controller'],
					'module'=>$url['module'],
					'modelListName'=>$url['modelListName'],
				);
				unset($url['action']);
				unset($url['controller']);
				unset($url['module']);
				unset($url['modelListName']);

				$urlStringArray = array();
				foreach ($url as $urlKey => $urlValue) {
					$urlStringArray[] = $urlKey . '=' .  $urlValue;
				}
				$urlString = implode('&', $urlStringArray);
				if ($urlString) {
					$urlString = '?' . $urlString;
				}

				$this->_redirector->gotoUrl($this->_controller->view->url($urlArray, NULL, TRUE) . $urlString);
			}
		} else {
			/**
			 * form not submitted or not valid
			 */

		}

		/**
		 * set decorators to form
		 */
		$form->setDecorators(
			array(
				new Zend_Form_Decorator_FormElements(),
				new Zend_Form_Decorator_HtmlTag(),
				//new L8M_Form_Decorator_Ajaxable(),
				new Zend_Form_Decorator_Form(),
				new L8M_Form_Decorator_ModelListFormBack($formBackUrl),
				new L8M_Form_Decorator_ModelListFormSaveNext($form->getId()),
				new L8M_Form_Decorator_FormHasRequiredElements(),
				new L8M_Form_Decorator_HasException($exception),
				new L8M_Form_Decorator(array(
					'boxClass'=>'small l8m-model-form-base',
					'appendJsFile'=>array(
						'/js/jquery/system/model-form-base.js',
						'/js/jquery/system/model-form-change-window-unload.js'
					),
				)),
			)
		);

		$this->_controller->view->modelForm = $form;
		$this->_controller->view->modelFormListLeadThroughUrl = $this->_leadThroughUrl;
	}

	/**
	 * Handling edit of ModelList item
	 *
	 * @param array $options
	 */
	public function editModel($listName = NULL, $options = array())
	{
		/**
		 * retrieve model-list
		 */
		$modelListModel = $this->_retrieveList($listName);

		/**
		 * check whether is allowed or not
		 */
		if (!$modelListModel->button_edit) {
			throw new L8M_Exception('You are not allowed to edit an item.');
		}

		/**
		 * get id to edit
		 */
		$id = $this->_controller->getRequest()->getParam('id', NULL, FALSE);

		/**
		 * save and add next
		 */
		$saveAndAddNext = $this->_controller->getRequest()->getParam('backToForm', NULL, FALSE);
		if ($saveAndAddNext == 'true') {
			$backToList = FALSE;
		} else {
			$backToList = TRUE;
		}

		/**
		 * page
		 */
		$page = $this->_controller->getRequest()->getParam('page', NULL, FALSE);
		$page = $page
			  ? $page
			  : $this->_controller->getRequest()->getParam('seite', NULL, FALSE);
		$page = $page
			  ? $page
			  : $this->_default['page']
		;
		if (!is_numeric($page)) {
			$page = $this->_default['page'];
		}

		/**
		 * results per page
		 */
		$resultPerPage = $this->_controller->getRequest()->getParam('rp', NULL, FALSE);
		$resultPerPage = $resultPerPage
			  ? $resultPerPage
			  : $this->_default['resultPerPage']
		;
		if (!is_numeric($resultPerPage)) {
			$resultPerPage = $this->_default['resultPerPage'];
		}

		/**
		 * search link infos
		 */
		$searchQuery = $this->_controller->getRequest()->getParam('query', NULL, FALSE);
		$searchColumn = $this->_controller->getRequest()->getParam('qtype', NULL, FALSE);

		/**
		 * sort order
		 */
		$sortOrder = $this->_controller->getRequest()->getParam('sortorder', NULL, FALSE);
		$sortOrder = $sortOrder
				   ? $sortOrder
				   : 'asc'
		;
		if ($sortOrder != 'asc') {
			$sortOrder = 'desc';
		}

		/**
		 * sort by name
		 */
		$sortName = $this->_controller->getRequest()->getParam('sortname', NULL, FALSE);
		$sortName = $sortName ? $sortName : $modelListModel->default_sort;

		/**
		 * prepare back button
		 */
		$paramArray = array(
			'rp',
			'query',
			'qtype',
			'sortname',
			'sortorder',
			'page',
		);

		$paramValues = '?';
		foreach ($paramArray as $param) {
			$paramValues .= $param . '=' . $this->_controller->getRequest()->getParam($param, NULL, FALSE) . '&';
		}

		if((count($this->_default['controllerParams']) > 0) && isset($this->_default['controllerParams']['controllerParamName'])) {
			$paramValues .= $this->_default['controllerParams']['controllerParamName'] . '=' . $this->_default['controllerParams']['controllerParamValue'] . '&';
		}
		$redirectParams = $this->_controller->getRequest()->getParam('redirect', NULL, FALSE);
		if($redirectParams != NULL) {
			$paramValues .= 'editedModel=' . $this->_modelName . '&';
		}

		/**
		 * form param values for back button
		 */
		$this->_controller->view->formParamValues = $paramValues;

		/**
		 * form back button url if redirect to other controller
		 */
		$formBackUrl = NULL;
		$redirectParams = $this->_controller->getRequest()->getParam('redirect', NULL, FALSE);
		if($redirectParams != NULL) {
			$redirectParams = json_decode($redirectParams, TRUE);
			$formBackUrl = $this->_controller->view->url($redirectParams,NULL,TRUE);
			$formBackUrl .= $paramValues;
		}

		/**
		 * do we have something to edit?
		 */
		$modelQuery = Doctrine_Query::create()
			->from($this->_modelName . ' ' . $modelListModel->name_short)
			->where($modelListModel->name_short . '.id = ? ', array($id))
		;

		/**
		 * add where
		 */
		$availableLeftJoins = $this->_addJoin;
		$modelQuery = $this->_addWhereToQuery($modelQuery, $modelListModel, $availableLeftJoins);

		/**
		 *
		 */
		$modelModel = $modelQuery
			->execute()
			->getFirst()
		;

		if (!$modelModel) {
			$url = array_merge(
				array(
					'action'=>'list',
					'controller'=>$this->_controller->getRequest()->getControllerName(),
					'module'=>$this->_controller->getRequest()->getModuleName(),
					'modelListName'=>$this->_modelName,
					'rp'=>$resultPerPage,
					'page'=>$page,
					'query'=>$searchQuery,
					'qtype'=>$searchColumn,
					'sortorder'=>$sortOrder,
					'sortname'=>$sortName,
				),
				$this->_leadThroughUrl
			);

			$redirectParams = $this->_controller->getRequest()->getParam('redirect', NULL, FALSE);
			if($redirectParams != NULL) {
				$redirectParams = json_decode($redirectParams, TRUE);
				$url = array_merge(
					$url,
					$redirectParams
				);
			}

			$this->_redirector->gotoUrl($this->_controller->view->url($url, NULL, TRUE));
		}

		/**
		 * do we have to render edit next with form
		 */
		$collectionCountQuery = Doctrine_Query::create()
			->from($this->_modelName . ' ' . $modelListModel->name_short)
			->select('COUNT(' . $modelListModel->name_short . '.id)')
		;

		/**
		 * add where
		 */
		$collectionCountQuery = $this->_addWhereToQuery($collectionCountQuery, $modelListModel, $availableLeftJoins);

		/**
		 *
		 */
		$collectionCount = $collectionCountQuery
			->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR)
			->execute()
		;

		if ($collectionCount > 1) {
			$renderEditNext = TRUE;
		} else {
			$renderEditNext = FALSE;
		}

		/**
		 * options model array
		 */
		$optionsModel = array(
			'editColumnID'=>$id,
			'buttonLabel'=>'Save',
		);

		/**
		 * retrieve options for ignoring columns
		 */
		$optionsModel['addIgnoredColumns'] = array();
		$modelListEditIgnoreCollection = Doctrine_Query::create()
			->from('Default_Model_ModelListEditIgnore mlei')
			->leftJoin('mlei.ModelList ml')
			->where('ml.name = ? ', array($this->_modelName))
			->execute()
		;
		foreach ($modelListEditIgnoreCollection as $modelListEditIgnoreModel) {
			$optionsModel['addIgnoredColumns'][] = $modelListEditIgnoreModel->ModelColumnName->name;
		}

		/**
		 * retrieve options for column labels
		 */
		$optionsModel['modelListName'] = $listName;
		$optionsModel['columnLabels'] = array();
		$optionsModel['columnTypes'] = array();
		$optionsModel['order'] = array();
		$optionsModel['addStaticFormElements'] = array();
		if ($this->_default['hideShort']) {
			$optionsModel['addIgnoredColumns'] = array(
				'short',
				'ushort',
			);
		} else {
			$optionsModel['addIgnoredColumns'] = array();
		}
		$optionsModel['addIgnoredM2nRelations'] = array();
		$optionsModel['ignoreColumnRelation'] = array();
		$optionsModel['ignoreColumnInMultiRelation'] = array();
		$optionsModel['relationM2nValuesDefinition'] = array();
		$optionsModel['mediaDirectory'] = array();
		$optionsModel['mediaRole'] = array();
		$optionsModel['Position'] = array();

		$modelListColumnCollection = Doctrine_Query::create()
			->from('Default_Model_ModelListColumn mlc')
			->leftJoin('mlc.ModelList ml')
			->where('ml.name = ? ', array($this->_modelName))
			->orderBy('mlc.position ASC')
			->execute()
		;
		foreach ($modelListColumnCollection as $modelListColumnModel) {
			$columnDisplay = $modelListColumnModel->ModelColumnName->Translation[L8M_Locale::getLang()]['display'];
			if ($columnDisplay) {
				$optionsModel['columnLabels'][] = $modelListColumnModel->ModelColumnName->Translation[L8M_Locale::getLang()]['display'];
			}

			$columnEditAs = $modelListColumnModel->ModelColumnName->ModelColumnNameEditAs->short;
			if ($columnEditAs) {
				$optionsModel['columnTypes'][$modelListColumnModel->ModelColumnName->name] = $modelListColumnModel->ModelColumnName->ModelColumnNameEditAs->short;
			}

//			$optionsModel['order'][] = $modelListColumnModel->ModelColumnName->name;
		}

		/**
		 * options
		 */
		if (isset($options['order'])) {
			$optionsModel['order'] = array_merge($options['order'], $optionsModel['order']);
		}
		if (isset($options['addIgnoredColumns'])) {
			$optionsModel['addIgnoredColumns'] = array_merge($optionsModel['addIgnoredColumns'], $options['addIgnoredColumns']);
		}
		if (isset($options['addIgnoredM2nRelations'])) {
			$optionsModel['addIgnoredM2nRelations'] = array_merge($optionsModel['addIgnoredM2nRelations'], $options['addIgnoredM2nRelations']);
		}
		if (isset($options['ignoreColumnRelation'])) {
			$optionsModel['ignoreColumnRelation'] = array_merge($optionsModel['ignoreColumnRelation'], $options['ignoreColumnRelation']);
		}
		if (isset($options['ignoreColumnInMultiRelation'])) {
			$optionsModel['ignoreColumnInMultiRelation'] = array_merge($optionsModel['ignoreColumnInMultiRelation'], $options['ignoreColumnInMultiRelation']);
		}
		if (isset($options['relationM2nValuesDefinition'])) {
			$optionsModel['relationM2nValuesDefinition'] = array_merge($optionsModel['relationM2nValuesDefinition'], $options['relationM2nValuesDefinition']);
		}
		if (isset($options['mediaDirectory'])) {
			$optionsModel['mediaDirectory'] = array_merge($optionsModel['mediaDirectory'], $options['mediaDirectory']);
		}
		if (isset($options['mediaRole'])) {
			$optionsModel['mediaRole'] = array_merge($optionsModel['mediaRole'], $options['mediaRole']);
		}
		if (isset($options['columnLabels'])) {
			$optionsModel['columnLabels'] = array_merge($optionsModel['columnLabels'], $options['columnLabels']);
		}
		if (isset($options['buttonLabel'])) {
			$optionsModel['buttonLabel'] = $options['buttonLabel'];
		}
		if (isset($options['columnTypes'])) {
			$optionsModel['columnTypes'] = array_merge($optionsModel['columnTypes'], $options['columnTypes']);
		}
		if (isset($options['addStaticFormElements'])) {
			$optionsModel['addStaticFormElements'] = array_merge($optionsModel['addStaticFormElements'], $options['addStaticFormElements']);
		}
		if (isset($options['M2NRelations'])) {
			$optionsModel['M2NRelations'] = $options['M2NRelations'];
		}
		if (isset($options['replaceColumnValuesInMultiRelation'])) {
			$optionsModel['replaceColumnValuesInMultiRelation'] = $options['replaceColumnValuesInMultiRelation'];
		}
		if (isset($options['relationColumnInMultiRelation'])) {
			$optionsModel['relationColumnInMultiRelation'] = $options['relationColumnInMultiRelation'];
		}
		if (isset($options['multiRelationCondition'])) {
			$optionsModel['multiRelationCondition'] = $options['multiRelationCondition'];
		}
		if (isset($options['tinyMCE'])) {
			$optionsModel['tinyMCE'] = $options['tinyMCE'];
		}
		if (isset($options['Position'])) {
			$optionsModel['Position'] = $options['Position'];
		}
		if (isset($options['setFormLanguage'])) {
			$optionsModel['setFormLanguage'] = $options['setFormLanguage'];
		} else {
			$optionsModel['setFormLanguage'] = $this->_default['formLanguage'];
		}
		if (isset($options['action'])) {
			$urlAction = $options['action'];
		} else {
			$urlAction = 'edit';
		}
		$optionsModel['showBackAfterSave'] = $this->_default['showBackAfterSave'];

		/**
		 * set url
		 */
		$url = array_merge(
			array(
				'action'=>$urlAction,
				'controller'=>$this->_controller->getRequest()->getControllerName(),
				'module'=>$this->_controller->getRequest()->getModuleName(),
				'modelListName'=>$this->_modelName,
				'id'=>$id,
				'rp'=>$resultPerPage,
				'page'=>$page,
				'query'=>$searchQuery,
				'qtype'=>$searchColumn,
				'sortorder'=>$sortOrder,
				'sortname'=>$sortName,
			),
			$this->_leadThroughUrl
		);

		$redirectParams = $this->_controller->getRequest()->getParam('redirect', NULL, FALSE);
		if($redirectParams != NULL) {
			$url = array_merge(
				$url,
				array('redirect'=>$redirectParams)
			);
		}

		/**
		 * store exceptions
		 */
		$exception = array();

		/**
		 * create form
		 *
		 * @var L8M_ModelForm_Base
		 */
		$form = L8M_ModelForm_Base::create($this->_modelName, $optionsModel);
		$form->setAction($this->_controller->view->url($url, NULL, TRUE));

		/**
		 * check Marked For Editor
		 */
		if (!L8M_ModelForm_MarkedForEditor::isEditable($this->_modelName, $id, FALSE)) {
			$exception[] = new L8M_ModelForm_MarkedForEditor_Exception(L8M_Translate::string('It is currently not permitted to edit the record.'));
		}

		/**
		 * check do before form output
		 */
		if (isset($options['doBeforeFormOutput']) &&
			is_array($options['doBeforeFormOutput']) &&
			count($options['doBeforeFormOutput']) > 0) {

			foreach ($options['doBeforeFormOutput'] as $autoClass) {
				$autoClassInstance = new $autoClass;
				$tmp = explode('_', $autoClass);
				$autoFunction = strtolower($tmp[count($tmp) - 1]);
				$autoClassInstance->$autoFunction($id, $this->_modelName, $form);
			}
		}


		if ($form->isSubmitted() &&
			$form->isValid($this->_controller->getRequest()->getParams())) {

			$model = Doctrine_Query::create()
				->from($this->_modelName . ' e')
				->where('e.id = ? ',array($id))
				->limit(1)
				->execute()
				->getFirst()
			;

			$formValues = $form->getValues();
			if ($this->_default['hideShort']) {
				$columnDefinitions = $model->getTable()->getColumns();
				if (array_key_exists('short', $columnDefinitions)) {
					if (isset($columnDefinitions['short']['length'])) {
						$shortLength = $columnDefinitions['short']['length'];
					} else{
						$shortLength = 45;
					}
					if ($model instanceof Default_Model_Media) {
						$isMediaModel = TRUE;
					} else {
						$isMediaModel = FALSE;
					}
					if ((!$isMediaModel && array_key_exists('name', $columnDefinitions) && isset($formValues['name'])) ||
						(!$isMediaModel && array_key_exists('name_addition', $columnDefinitions))) {

						if ($formValues['name'] != $model['name'] ||
							(isset($formValues['name_addition']) && $formValues['name_addition'] != $model['name_addition'])) {

							if (array_key_exists('name_addition', $columnDefinitions) &&
								$formValues['name_addition'] != $model['name_addition']) {

								$shortText = $formValues['name'] . '-' . $formValues['name_addition'];
							} else {
								$shortText = $formValues['name'];
							}
							$formValues['short'] = L8M_Library::createShort($model, 'short', $shortText, $shortLength);
						} else {
							$formValues['short'] = $model['short'];
						}
					} else
					if ((!$isMediaModel && array_key_exists('title', $columnDefinitions)) ||
						(!$isMediaModel && array_key_exists('subtitle', $columnDefinitions)) ||
						(!$isMediaModel && array_key_exists('sub_title', $columnDefinitions))) {

						if ($formValues['title'] != $model['title'] ||
							(isset($formValues['subtitle']) && $formValues['subtitle'] != $model['subtitle']) ||
							(isset($formValues['sub_title']) && $formValues['sub_title'] != $model['sub_title'])) {

							if (array_key_exists('subtitle', $columnDefinitions) &&
								$formValues['subtitle'] != $model['subtitle']) {

								$shortText = $formValues['title'] . '-' . $formValues['subtitle'];
							} else
							if (array_key_exists('sub_title', $columnDefinitions) &&
								$formValues['sub_title'] != $model['sub_title']) {

								$shortText = $formValues['title'] . '-' . $formValues['sub_title'];
							} else {
								$shortText = $formValues['title'];
							}
							$formValues['short'] = L8M_Library::createShort($model, 'short', $shortText, $shortLength);
						} else {
							$formValues['short'] = $model['short'];
						}
					} else
					if ((!$isMediaModel && array_key_exists('headline', $columnDefinitions)) ||
						(!$isMediaModel && array_key_exists('subheadline', $columnDefinitions)) ||
						(!$isMediaModel && array_key_exists('sub_headline', $columnDefinitions))) {

						if ($formValues['headline'] != $model['headline'] ||
							(isset($formValues['subheadline']) && $formValues['subheadline'] != $model['subheadline']) ||
							(isset($formValues['sub_headline']) && $formValues['sub_headline'] != $model['sub_headline'])) {

							if (array_key_exists('subheadline', $columnDefinitions) &&
								$formValues['subheadline'] != $model['subheadline']) {

								$shortText = $formValues['headline'] . '-' . $formValues['subheadline'];
							} else
							if (array_key_exists('sub_headline', $columnDefinitions) &&
								$formValues['sub_headline'] != $model['sub_headline']) {

								$shortText = $formValues['headline'] . '-' . $formValues['sub_headline'];
							} else {
								$shortText = $formValues['headline'];
							}
							$formValues['short'] = L8M_Library::createShort($model, 'short', $shortText, $shortLength);
						} else {
							$formValues['short'] = $model['short'];
						}
					} else
					if (!$isMediaModel &&
						array_key_exists('header', $columnDefinitions)) {

						if ($formValues['header'] != $model['header']) {
							$formValues['short'] = L8M_Library::createShort($model, 'short', $formValues['header'], $shortLength);
						} else {
							$formValues['short'] = $model['short'];
						}
					} else
					if ($isMediaModel &&
						array_key_exists('file_name', $columnDefinitions) &&
						$formValues['file_name'] != $model['file_name']) {

						$tmpFileNameArray = explode('.', $model['file_name']);
						if (count($tmpFileNameArray) > 2) {
							if (isset($columnDefinitions['file_name']['length'])) {
								$fileNameLength = $columnDefinitions['file_name']['length'];
							} else {
								$fileNameLength = 45;
							}

							$tmpModelFileNameArray = explode('.', $formValues['file_name']);
							if (count($tmpModelFileNameArray) > 2) {
								unset($tmpModelFileNameArray[count($tmpModelFileNameArray) - 1]);
								$formValues['file_name'] = implode('.', $tmpModelFileNameArray);
							}

							if (strlen($formValues['file_name'] .'.' . $tmpFileNameArray[count($tmpFileNameArray) - 1]) > $fileNameLength) {
								$formValues['file_name'] = substr($formValues['file_name'], 0, $fileNameLength - strlen('.' . $tmpFileNameArray[count($tmpFileNameArray) - 1])) . '.' . $tmpFileNameArray[count($tmpFileNameArray) - 1];
							} else {
								$formValues['file_name'] = $formValues['file_name'] .'.' . $tmpFileNameArray[count($tmpFileNameArray) - 1];
							}
						}
						$formValues['short'] = L8M_Library::createShort($model, 'short', $formValues['file_name'], $shortLength, TRUE);
					} else
					if (array_key_exists('Translation', $formValues) &&
						array_key_exists(L8M_Locale::getDefault(), $formValues['Translation']) &&
						isset($model['Translation']) &&
						isset($model['Translation'][L8M_Locale::getDefault()])) {

						$transColumnDefinitions = $model->Translation->getTable()->getColumns();
						$transFormValues = $formValues['Translation'][L8M_Locale::getDefault()];

						if (!$isMediaModel &&
							array_key_exists('uname', $transColumnDefinitions)) {

							if ($transFormValues['uname'] != $model['Translation'][L8M_Locale::getDefault()]['uname']) {
								$formValues['short'] = L8M_Library::createShort($model, 'short', $transFormValues['uname'], $shortLength);
							} else {
								$formValues['short'] = $model['short'];
							}
						} else
						if ((!$isMediaModel && array_key_exists('name', $transColumnDefinitions)) ||
							(!$isMediaModel && array_key_exists('name_addition', $transColumnDefinitions))) {

							if ($transFormValues['name'] != $model['Translation'][L8M_Locale::getDefault()]['name'] ||
								(isset($transFormValues['name_addition']) && $transFormValues['name_addition'] != $model['Translation'][L8M_Locale::getDefault()]['name_addition'])) {

								if (array_key_exists('name_addition', $transColumnDefinitions) &&
									$transFormValues['name_addition'] != $model['Translation'][L8M_Locale::getDefault()]['name_addition']) {

									$shortText = $transFormValues['name'] . '-' . $transFormValues['name_addition'];
								} else {
									$shortText = $transFormValues['name'];
								}
								$formValues['short'] = L8M_Library::createShort($model, 'short', $shortText, $shortLength);
							} else {
								$formValues['short'] = $model['short'];
							}
						} else
						if ((!$isMediaModel && array_key_exists('title', $transColumnDefinitions)) ||
							(!$isMediaModel && array_key_exists('subtitle', $transColumnDefinitions)) ||
							(!$isMediaModel && array_key_exists('sub_title', $transColumnDefinitions))) {

							if ($transFormValues['title'] != $model['Translation'][L8M_Locale::getDefault()]['title'] ||
								(isset($transFormValues['subtitle']) && $transFormValues['subtitle'] != $model['Translation'][L8M_Locale::getDefault()]['subtitle']) ||
								(isset($transFormValues['sub_title']) && $transFormValues['sub_title'] != $model['Translation'][L8M_Locale::getDefault()]['sub_title'])) {

								if (array_key_exists('subtitle', $transColumnDefinitions) &&
									$transFormValues['subtitle'] != $model['Translation'][L8M_Locale::getDefault()]['subtitle']) {

									$shortText = $transFormValues['title'] . '-' . $transFormValues['subtitle'];
								} else
								if (array_key_exists('sub_title', $transColumnDefinitions) &&
									$transFormValues['sub_title'] != $model['Translation'][L8M_Locale::getDefault()]['sub_title']) {

									$shortText = $transFormValues['title'] . '-' . $transFormValues['sub_title'];
								} else {
									$shortText = $transFormValues['title'];
								}
								$formValues['short'] = L8M_Library::createShort($model, 'short', $shortText, $shortLength);
							} else {
								$formValues['short'] = $model['short'];
							}
						} else
						if ((!$isMediaModel && array_key_exists('headline', $transColumnDefinitions)) ||
							(!$isMediaModel && array_key_exists('subheadline', $transColumnDefinitions)) ||
							(!$isMediaModel && array_key_exists('sub_headline', $transColumnDefinitions))) {

							if ($transFormValues['headline'] != $model['Translation'][L8M_Locale::getDefault()]['headline'] ||
								(isset($transFormValues['subheadline']) && $transFormValues['subheadline'] != $model['Translation'][L8M_Locale::getDefault()]['subheadline']) ||
								(isset($transFormValues['sub_headline']) && $transFormValues['sub_headline'] != $model['Translation'][L8M_Locale::getDefault()]['sub_headline'])) {

								if (array_key_exists('subheadline', $transColumnDefinitions) &&
									$transFormValues['subheadline'] != $model['Translation'][L8M_Locale::getDefault()]['subheadline']) {

									$shortText = $transFormValues['headline'] . '-' . $transFormValues['subheadline'];
								} else
								if (array_key_exists('sub_headline', $transColumnDefinitions) &&
									$transFormValues['sub_headline'] != $model['Translation'][L8M_Locale::getDefault()]['sub_headline']) {

									$shortText = $transFormValues['headline'] . '-' . $transFormValues['sub_headline'];
								} else {
									$shortText = $transFormValues['headline'];
								}
								$formValues['short'] = L8M_Library::createShort($model, 'short', $shortText, $shortLength);
							} else {
								$formValues['short'] = $model['short'];
							}
						} else
						if (!$isMediaModel &&
							array_key_exists('header', $transColumnDefinitions)) {

							if ($transFormValues['header'] != $model['Translation'][L8M_Locale::getDefault()]['header']) {
								$formValues['short'] = L8M_Library::createShort($model, 'short', $transFormValues['header'], $shortLength);
							} else {
								$formValues['short'] = $model['short'];
							}
						}
					}

					/**
					 * uhort
					 */
					if (!$isMediaModel &&
						array_key_exists('Translation', $formValues) &&
						L8M_Library::arrayKeysExists(L8M_Locale::getSupported(), $formValues['Translation'])) {

						$transColumnDefinitions = $model->Translation->getTable()->getColumns();
						if (array_key_exists('ushort', $transColumnDefinitions) &&
							isset($transColumnDefinitions['ushort']['notnull']) &&
							$transColumnDefinitions['ushort']['notnull'] == TRUE) {

							$uShortOriginal = NULL;
							if (array_key_exists('uname', $transColumnDefinitions) &&
								isset($transColumnDefinitions['uname']['notnull']) &&
								$transColumnDefinitions['uname']['notnull'] == TRUE) {

								$uShortOriginal = L8M_Library::arrayCopySubKeyToMajorKey('uname', $formValues['Translation']);
							} else
							if (array_key_exists('name', $transColumnDefinitions) &&
								isset($transColumnDefinitions['name']['notnull']) &&
								$transColumnDefinitions['name']['notnull'] == TRUE) {

								$uShortOriginal = L8M_Library::arrayCopySubKeyToMajorKey('name', $formValues['Translation']);
							} else
							if (array_key_exists('title', $transColumnDefinitions) &&
								isset($transColumnDefinitions['title']['notnull']) &&
								$transColumnDefinitions['title']['notnull'] == TRUE) {

								$uShortOriginal = L8M_Library::arrayCopySubKeyToMajorKey('title', $formValues['Translation']);
							}

							if ($uShortOriginal) {
								foreach (L8M_Locale::getSupported() as $tmpLang) {
									$possibleUShort = L8M_Library::createUShort($model, $tmpLang, $uShortOriginal[$tmpLang], $transColumnDefinitions['ushort']['length']);
									if ($possibleUShort != $model['Translation'][$tmpLang]['ushort']) {
										$formValues['Translation'][$tmpLang]['ushort'] = $possibleUShort;
									}
								}
							}
						}
					}

					/**
					 * check short
					 */
					if (isset($columnDefinitions['short']['notnull']) &&
						$columnDefinitions['short']['notnull'] == TRUE &&
						!isset($formValues['short'])) {

						if ($model['short'] == NULL) {
							$formValues['short'] = NULL;

							foreach ($columnDefinitions as $columnDefinitionName => $columnDefinition) {
								if (strpos($columnDefinitionName, 'name') !== FALSE &&
									($columnDefinition['type'] == 'string' || $columnDefinition['type'] == 'clob' ||  $columnDefinition['type'] == 'clob') &&
									isset($formValues[$columnDefinitionName]) &&
									strlen(trim($formValues[$columnDefinitionName])) > 0) {

									$formValues['short'] = trim($formValues[$columnDefinitionName]);
								}
							}

							if (!$formValues['short']) {
								$formValues['short'] = md5(time());
							}
							$formValues['short'] = L8M_Library::createShort($model, 'short', $formValues['short'], $shortLength);
						} else {
							$formValues['short'] = $model['short'];
						}
					}
				}
			}

			$goOn = TRUE;
			$RemoveUnsedMedia = new L8M_Media_Edit_RemoveUnusedMedia;
			$RemoveUnsedMedia->deleteMedia($id, $this->_modelName, $formValues);


			/**
			 * do before save
			 */
			if (isset($options['doBeforeSave']) &&
				is_array($options['doBeforeSave']) &&
				count($options['doBeforeSave']) > 0) {

				foreach ($options['doBeforeSave'] as $autoClass) {
					if ($goOn) {
						$autoClassInstance = new $autoClass;
						$tmp = explode('_', $autoClass);
						$autoFunction = strtolower($tmp[count($tmp) - 1]);
						$autoClassInstance->$autoFunction($id, $this->_modelName, $formValues, $form);
						if (method_exists($autoClassInstance, 'goOn')) {
							if (!$autoClassInstance->goOn()) {
								$goOn = FALSE;
								if (method_exists($autoClassInstance, 'getException')) {
									$exception[] = $autoClassInstance->getException();
								}
							} else {
								if (method_exists($autoClassInstance, 'replaceFormValues')) {
									$formValues = $autoClassInstance->replaceFormValues();
								}
							}
						}
					}
				}
			}

			if ($goOn) {
				$model->merge($formValues);

				/**
				 * save model without auto genereated values
				 */
				try {
					$model->save();
				} catch (Exception $exceptionSave) {
					$exception[] = $exceptionSave;
					$goOn = FALSE;
				}

				if ($goOn) {

					/**
					 * check out auto generated column values
					 */
					if (isset($options['addGeneratedColumnValues']) &&
						is_array($options['addGeneratedColumnValues']) &&
						count($options['addGeneratedColumnValues']) > 0) {

						foreach ($options['addGeneratedColumnValues'] as $autoColumn => $autoClass) {
							$autoClassInstance = new $autoClass;
							$model->merge(array($autoColumn=>$autoClassInstance->$autoColumn($model->id)));
						}

						/**
						 * try saving new datas
						 */
						try {
							$model->save();
						} catch (Exception $exceptionSaveAuto) {
							$exception[] = $exceptionSaveAuto;
							$goOn = FALSE;
						}
					}

					if ($goOn) {

						/**
						 * check m2n relations
						 */
						$this->_handleM2N($form->getM2NRelationFormInfos(), $model, $formValues);

						/**
						 * check positions
						 */
						$this->_handlePosition($form->getPositionFormInfos(), $model, $formValues);

						/**
						 * check out auto generated values
						 */
						if (isset($options['addGeneratedValues']) &&
							is_array($options['addGeneratedValues']) &&
							count($options['addGeneratedValues']) > 0) {

							foreach ($options['addGeneratedValues'] as $autoClass) {
								if ($goOn) {
									$autoClassInstance = new $autoClass;
									$tmp = explode('_', $autoClass);
									$autoFunction = strtolower($tmp[count($tmp) - 1]);
									$autoClassInstance->$autoFunction($model, $form);
									if (method_exists($autoClassInstance, 'goOn')) {
										if (!$autoClassInstance->goOn()) {
											$goOn = FALSE;
											if (method_exists($autoClassInstance, 'getException')) {
												$exception[] = $autoClassInstance->getException();
											}
										}
									}
								}
							}
						}

						/**
						 * do after save
						 */
						if (isset($options['doAfterSave']) &&
							is_array($options['doAfterSave']) &&
							count($options['doAfterSave']) > 0) {

							foreach ($options['doAfterSave'] as $autoClass) {
								if ($goOn) {
									$autoClassInstance = new $autoClass;
									$tmp = explode('_', $autoClass);
									$autoFunction = strtolower($tmp[count($tmp) - 1]);
									$autoClassInstance->$autoFunction($id, $this->_modelName, $formValues);
									if (method_exists($autoClassInstance, 'goOn')) {
										if (!$autoClassInstance->goOn()) {
											$goOn = FALSE;
											if (method_exists($autoClassInstance, 'getException')) {
												$exception[] = $autoClassInstance->getException();
											}
										}
									}
								}
							}
						}

						/**
						 * may deaktivate marked for editor
						 */
						if ($goOn) {

							L8M_ModelForm_MarkedForEditor::deactivate($this->_modelName, $id, L8M_ModelForm_MarkedForEditor::getIdentifier($this->_modelName, $id, FALSE));
						}
					}
				}
			}

			/**
			 * redirect
			 */
			$url = array_merge(
				array(
					'action'=>'list',
					'controller'=>$this->_controller->getRequest()->getControllerName(),
					'module'=>$this->_controller->getRequest()->getModuleName(),
					'modelListName'=>$this->_modelName,
					'rp'=>$resultPerPage,
					'page'=>$page,
					'query'=>$searchQuery,
					'qtype'=>$searchColumn,
					'sortorder'=>$sortOrder,
					'sortname'=>$sortName,
					'editedModel'=>$this->_modelName,
					'modelID'=>$id
				),
				$this->_leadThroughUrl
			);

			if (!$backToList &&
				$this->_sortOrderNextPossible($modelListModel->name_short, $sortName, $searchColumn)) {

				$modelQuery = Doctrine_Query::create()
					->from($this->_modelName . ' ' . $modelListModel->name_short)
				;

				/**
				 * check for next
				 */
				$modelQuery = $this->_sortOrderNextWhere($modelQuery, $modelListModel->name_short, $sortName, $searchColumn, $sortOrder, $searchQuery, $model, TRUE);

				/**
				 * add where
				 */
				$modelQuery = $this->_addWhereToQuery($modelQuery, $modelListModel);

				/**
				 * retrieve model
				 */
				$modelModel = $modelQuery
					->limit(1)
					->execute()
					->getFirst()
				;

				if (!$modelModel) {
					$modelQuery = Doctrine_Query::create()
						->from($this->_modelName . ' ' . $modelListModel->name_short)
					;

					/**
					 * check for next
					 */
					$modelQuery = $this->_sortOrderNextWhere($modelQuery, $modelListModel->name_short, $sortName, $searchColumn, $sortOrder, $searchQuery, $model);

					/**
					 * add where
					 */
					$modelQuery = $this->_addWhereToQuery($modelQuery, $modelListModel);

					/**
					 *
					 */
					$modelModel = $modelQuery
						->limit(1)
						->execute()
						->getFirst()
					;
				}

				if ($modelModel) {
					$url['action'] = $urlAction;
					$url['id'] = $modelModel->id;
				}
			}

			/**
			 * redirect decision
			 */
			if ($goOn &&
				$this->_default['doRedirect']) {

				$goOnRedirect = TRUE;
			} else {
				$goOnRedirect = FALSE;
			}
			$redirectParams = $this->_controller->getRequest()->getParam('redirect', NULL, FALSE);
			if ($this->_default['showBackAfterSave'] &&
				$this->_controller->getRequest()->getParam('l8m_system_back_after_save')) {

				if($redirectParams != NULL) {
					$url = array_merge(
						$url,
						array('redirect'=>$redirectParams)
					);
				}

				$goOnRedirect = TRUE;
				$url['action'] = $urlAction;

				if (isset($modelModel) &&
					$modelModel instanceof $this->_modelName &&
					$modelModel->id) {

					$url['id'] = $modelModel->id;
				}

				$formElementIdentifier = $form->getElement('l8m_model_form_base_element_identitfier');
				if ($formElementIdentifier) {
					$formElementIdentifier->setValue(L8M_ModelForm_MarkedForEditor::renewIdentifier($this->_modelName, $id, L8M_ModelForm_MarkedForEditor::getIdentifier($this->_modelName, $id, TRUE)));
				}
			} else {
				if($redirectParams != NULL) {
					$redirectParams = json_decode($redirectParams, TRUE);
					$url = array_merge(
						$url,
						$redirectParams
					);
				}
			}
			if ($goOnRedirect) {
				$urlArray = array(
					'action'=>$url['action'],
					'controller'=>$url['controller'],
					'module'=>$url['module'],
					'modelListName'=>$url['modelListName'],
				);
				unset($url['action']);
				unset($url['controller']);
				unset($url['module']);
				unset($url['modelListName']);

				$urlStringArray = array();
				foreach ($url as $urlKey => $urlValue) {
					$urlStringArray[] = $urlKey . '=' .  $urlValue;
				}
				$urlString = implode('&', $urlStringArray);
				if ($urlString) {
					$urlString = '?' . $urlString;
				}

				$this->_redirector->gotoUrl($this->_controller->view->url($urlArray, NULL, TRUE) . $urlString);
			}
		} else {

			/**
			 * form errors
			 */
			$formElementIdentifier = $form->getElement('l8m_model_form_base_element_identitfier');
			if ($formElementIdentifier) {

				$formElementIdentifier->setValue(L8M_ModelForm_MarkedForEditor::renewIdentifier($this->_modelName, $id, L8M_ModelForm_MarkedForEditor::getIdentifier($this->_modelName, $id, FALSE)));

				if ($formElementIdentifier->hasErrors()) {
					$exception[] = new L8M_ModelForm_MarkedForEditor_Exception(L8M_Translate::string('It is currently not permitted to edit the record.'));
				}
			}
		}

		/**
		 * set decorators to form
		 */
		$formDecorators = array(
			new Zend_Form_Decorator_FormElements(),
			new Zend_Form_Decorator_HtmlTag(),
			new Zend_Form_Decorator_Form(),
			new L8M_Form_Decorator_ModelListFormBack($formBackUrl),
		);
		if ($this->_sortOrderNextPossible($modelListModel->name_short, $sortName, $searchColumn)) {
			$formDecorators[] =new L8M_Form_Decorator_ModelListFormSaveNext($form->getid(), $renderEditNext, TRUE);
		}
		$formDecorators[] = new L8M_Form_Decorator_FormHasRequiredElements();
		$formDecorators[] = new L8M_Form_Decorator_HasException($exception);
		$formDecorators[] = new L8M_Form_Decorator(array(
			'boxClass'=>'small l8m-model-form-base',
			'appendJsFile'=>array(
					'/js/jquery/system/model-form-base.js',
					'/js/jquery/system/mark-for-editor.js',
					'/js/jquery/system/model-form-change-window-unload.js'
				),
		));
		$form->setDecorators($formDecorators);

		/**
		 * retrieve columns
		 */
		$viewModelID = NULL;
		if ($modelModel) {
			$modelColumns = $modelModel->getTable()->getColumns();
			$walkThrough = TRUE;
			foreach ($this->_default['columns'] as $column => $columnOptions) {
				if ($column != 'id' &&
					array_key_exists($column, $modelColumns) &&
					is_array($columnOptions) &&
					array_key_exists('use_in_edit_view', $columnOptions) &&
					$columnOptions['use_in_edit_view'] &&
					$walkThrough == TRUE) {

					$this->_controller->view->modelModelColumn = $column;
					$this->_controller->view->modelModelName = $modelModel[$column];
					$walkThrough = FALSE;
				}
			}

			$viewModelID = $modelModel->id;
		}

		$this->_controller->view->modelForm = $form;
		$this->_controller->view->modelModelId = $viewModelID;
		$this->_controller->view->modelFormListLeadThroughUrl = $this->_leadThroughUrl;
	}

	/**
	 * leeads some url variables through
	 *
	 * @param array $url
	 * @return L8M_ModelForm_List
	 */
	public function leadThroughUrl($url = NULL)
	{
		if (is_array($url)) {
			$this->_leadThroughUrl = array_merge($this->_leadThroughUrl, $url);
		}

		return $this;
	}

	/**
	 * leeads some url variables through to buttons
	 *
	 * @param array $url
	 * @return L8M_ModelForm_List
	 */
	public function leadThroughButton($url = NULL)
	{
		if (is_array($url)) {
			$this->_leadThroughButton = array_merge($this->_leadThroughButton, $url);
		}

		return $this;
	}

	/**
	 * Handle list of ModelLists
	 * Doing the Ajax and normal View
	 *
	 * @param string $listName
	 * @param array $options
	 */
	public function listCollection($listName = NULL, $options = NULL)
	{

		/**
		 * retrieve model-list
		 */
		$modelListModel = $this->_retrieveList($listName, $options);

		/**
		 * retrieve real short
		 */
		$listShort = $modelListModel->name_short;

		/**
		 * retrieve columns of model
		 */
		$loadModel = $this->_modelName;
		$loadedModel = new $loadModel();
		$availableModelColumns = $loadedModel->getTable()->getColumns();

		/**
		 * check for filter
		 */
		$session = new Zend_Session_Namespace($this->_modelName . '_' . $listShort. '_FilterParam');
		if ($session->filterValue &&
			$session->filterKey &&
			$session->filterFromM2N == NULL) {

			if (array_key_exists($session->filterKey, $availableModelColumns)) {
				$this->addWhereDqlString($listShort . '.' . $session->filterKey . ' = ? ', array($session->filterValue));
			} else {
				$failureFilterKey = $session->filterKey;
				$session->filterValue = NULL;
				$session->filterKey = NULL;
				$session->filterFromM2N = NULL;
				$session->filterAlias = NULL;
				throw new L8M_Exception('Session saved filterKey "' . $failureFilterKey . '" does not match Model "' . $this->_modelName . '"');
			}
		} else
		if ($session->filterValue &&
			$session->filterKey &&
			$session->filterFromM2N) {

			$fromM2NName = $session->filterFromM2N;
			$loadedM2NModel = new $fromM2NName();

			$loadedM2NModelColumns = $loadedM2NModel->getTable()->getColumns();
			if (array_key_exists($session->filterKey, $loadedM2NModelColumns)) {
				$loadedM2NModelRelations = $loadedM2NModel->getTable()->getRelations();
				$session->filterAlias = NULL;
				$foreignFromM2NModelValue = NULL;
				foreach ($loadedM2NModelRelations as $loadedM2NModelRelation) {
					if ($loadedM2NModelRelation->getType() === Doctrine_Relation::ONE &&
						$loadedM2NModelRelation->getClass() == $this->_modelName) {

						$session->filterAlias = $loadedM2NModelRelation->getAlias();
					} else
					if ($loadedM2NModelRelation->getType() === Doctrine_Relation::ONE &&
						$loadedM2NModelRelation->getLocalColumnName() == $session->filterKey) {

						$foreignFromM2NModelName = $loadedM2NModelRelation->getClass();
						$foreignFromM2NModelAlias = $loadedM2NModelRelation->getAlias();
						try {
							$foreignFromM2NModel = Doctrine_Query::create()
								->from($foreignFromM2NModelName . ' ffm')
								->where('ffm.id = ? ', array($session->filterValue))
								->limit(1)
								->execute()
								->getFirst()
							;
						} catch (Doctrine_Exception $e) {
							$failureFilterFromM2N = $session->filterFromM2N;
							$session->filterValue = NULL;
							$session->filterKey = NULL;
							$session->filterFromM2N = NULL;
							$session->filterAlias = NULL;
							throw new L8M_Exception('Something went wrong with M2N-Model "' . $failureFilterFromM2N . '".');
						}

						$foreignFromM2NModelColumns = $foreignFromM2NModel->getTable()->getColumns();
						if (array_key_exists('name', $foreignFromM2NModelColumns)) {
							$foreignFromM2NModelValue = $foreignFromM2NModel->name;
						} else
						if (array_key_exists('short', $foreignFromM2NModelColumns)) {
							$foreignFromM2NModelValue = $foreignFromM2NModel->short;
						} else {
							$failureFilterFromM2N = $session->filterFromM2N;
							$session->filterValue = NULL;
							$session->filterKey = NULL;
							$session->filterFromM2N = NULL;
							$session->filterAlias = NULL;
							throw new L8M_Exception('M2N-Model "' . $failureFilterFromM2N . '" need to have at least a short.');
						}
					}
				}
				if ($session->filterAlias) {
					$this->_controller->view->layout()->subheadline = $this->_controller->view->translate('List') . ': ' . $this->_controller->view->translate($foreignFromM2NModelAlias) . ' (' . $foreignFromM2NModelValue . ')';
					$this->addWhereDqlString('m2njoin.' . $session->filterKey . ' = ? ', array($session->filterValue));
				} else {
					$failureFilterFromM2N = $session->filterFromM2N;
					$session->filterValue = NULL;
					$session->filterKey = NULL;
					$session->filterFromM2N = NULL;
					$session->filterAlias = NULL;
					throw new L8M_Exception('Session saved M2N-Model "' . $failureFilterFromM2N . '" does not match Model "' . $this->_modelName . '"');
				}
			} else {
				$failureFilterKey = $session->filterKey;
				$session->filterValue = NULL;
				$session->filterKey = NULL;
				$session->filterFromM2N = NULL;
				$session->filterAlias = NULL;
				throw new L8M_Exception('Session saved filterKey "' . $failureFilterKey . '" does not match M2N-Model "' . $this->_modelName . '"');
			}

		}

		/**
		 * Standards
		 */

		/**
		 * do we have to deactivate website to show ajax request?
		 */
		if (is_array($options) &&
			array_key_exists('showAjax', $options) &&
			is_bool($options['showAjax'])) {

			$showAjax = $options['showAjax'];
		} else {
			$showAjax = $this->_default['showAjax'];
		}

		/**
		 * default from
		 */
		$defaultModelFrom = $this->_modelName . ' ' . $modelListModel->name_short;

		/**
		 * all joins
		 */
		$availableLeftJoins = array();

		/**
		 * prepare flexigrid for images
		 */
		$useFlexigridForImages = FALSE;

		/**
		 * available columns
		 */
		$modelListColumnCollection = Doctrine_Query::create()
			->from('Default_Model_ModelListColumn mlc')
			->where('mlc.model_list_id = ? ', array($modelListModel->id))
			->orderBy('mlc.position ASC')
			->execute()
		;
		$availableColumnNames = array();
		foreach ($modelListColumnCollection as $modelListColumnModel) {

			/**
			 * prepare vars
			 */
			$columnName = $modelListColumnModel->name;
			$replaceWithColumn = NULL;
			$modelListConnectionModel = $modelListColumnModel->ModelListConnection;
			$displayAs = $modelListColumnModel->ModelColumnName->Translation[L8M_Library::getLanguage()]['display'];
			$foreignModelName = NULL;
			if ($modelListConnectionModel->name_alias) {
				$foreignModelName = 'Default_Model_' . $modelListConnectionModel->name_alias;

				if (!class_exists($foreignModelName)) {
					$tryFindingMediaStringPos = strpos($modelListConnectionModel->name_alias, 'Media');
					if ($tryFindingMediaStringPos !== FALSE) {
						$foreignModelName = 'Default_Model_' . substr($modelListConnectionModel->name_alias, $tryFindingMediaStringPos);
					}
				}
			}

			/**
			 * show column as image
			 */
			$showAsImage = FALSE;

			/**
			 * show column as boolean
			 */
			$showAsBoolean = FALSE;

			/**
			 * align right
			 */
			$alignRight = FALSE;

			/**
			 * is numeric
			 */
			$isNumeric = FALSE;

			/**
			 * search like
			 */
			$searchLike = $modelListColumnModel->search_like;

			if (isset($modelListColumnModel['search_like_mode'])) {
				$searchLikeMode = $modelListColumnModel->search_like_mode;
			} else {
				$searchLikeMode = NULL;
				unset($searchLikeMode);
			}

			if ($modelListColumnModel->model_list_connection_id) {
				$filterAlias = new Zend_Filter_Word_CamelCaseToUnderscore();
				$tmpModelListColumnShort = strtolower($modelListConnectionModel->name_alias);
				if ($modelListConnectionModel->replace_with_column) {
					$columnName = $modelListConnectionModel->replace_with_column;
					if ($modelListConnectionModel->is_foreign == FALSE) {
						$replaceWithColumn = array(
							'replacedColumnName'=>$tmpModelListColumnShort . '_' . $columnName,//$ModelListConnectionModel->foreign_key,
							'columnName'=>$columnName,//$ModelListConnectionModel->foreign_key,
							'onModel'=>$modelListConnectionModel->ModelName->name,
						);
						if (array_key_exists($modelListConnectionModel->name_alias, $this->_default['relation']) &&
							array_key_exists('replace', $this->_default['relation'][$modelListConnectionModel->name_alias]) &&
							$this->_default['relation'][$modelListConnectionModel->name_alias]['replace'] != $columnName) {

							$replaceWithColumn['replacedColumnName'] = $tmpModelListColumnShort . '_' . $this->_default['relation'][$modelListConnectionModel->name_alias]['name'];
							$replaceWithColumn['columnName'] = $this->_default['relation'][$modelListConnectionModel->name_alias]['name'];
							$columnName = $this->_default['relation'][$modelListConnectionModel->name_alias]['replace'];
						}
						if (array_key_exists($modelListConnectionModel->name_alias, $this->_default['relation']) &&
							array_key_exists('searchLike', $this->_default['relation'][$modelListConnectionModel->name_alias])) {

							$searchLike = $this->_default['relation'][$modelListConnectionModel->name_alias]['searchLike'];
						}
					} else {
						throw new L8M_Exception('Foreign-Key not implemented yet into Model-List.');
//						$tmpModelListColumnShort = $listShort;
//						$replaceWithColumn = array(
//							'replacedColumnName'=>$tmpModelListColumnShort . '_' . $ModelListConnectionModel->local_key,
//							'columnName'=>$ModelListConnectionModel->foreign_key,
//							'onModel'=>$ModelListConnectionModel->ModelName->name,
//						);
					}
				}
				if ($modelListConnectionModel->join_on_short) {
					$availableLeftJoins[$modelListConnectionModel->short] = array(
						'joinOnShort'=>$modelListConnectionModel->join_on_short,
						'modelAliasName'=>$modelListConnectionModel->name_alias,
						'replaceWithColumn'=>$columnName,
					);
				} else {
					$availableLeftJoins[$modelListConnectionModel->short] = array(
						'joinOnShort'=>$listShort,
						'modelAliasName'=>$modelListConnectionModel->name_alias,
						'replaceWithColumn'=>$columnName,
					);
				}

				/**
				 * prepare column for image preview
				 */
				if (preg_match('/Default_Model_Media(.*)$/', $foreignModelName)) {
					$showAsImage = TRUE;
				}

				/**
				 * display as
				 */
				if ($foreignModelName == $this->_modelName) {
					$displayAs = $this->_controller->view->translate('Parent Record');
				} else {
					if (array_key_exists($modelListConnectionModel->name_alias, $this->_default['relation'])) {
						$displayAs = $this->_controller->view->translate($this->_default['relation'][$modelListConnectionModel->name_alias]['name']);
						if (array_key_exists('width',$this->_default['relation'][$modelListConnectionModel->name_alias])) {
							$modelListColumnModel->width = $this->_default['relation'][$modelListConnectionModel->name_alias]['width'];
						}
					}
					if ($displayAs == NULL ||
						$displayAs == 'ID') {

						$displayAs = $this->_controller->view->translate($modelListConnectionModel->name_alias);
					}
				}
			} else {

				if (array_key_exists($columnName, $availableModelColumns) &&
					$availableModelColumns[$columnName]['type'] == 'boolean') {

					$showAsBoolean = TRUE;
				}

				if (array_key_exists($columnName, $availableModelColumns) &&
					in_array($availableModelColumns[$columnName]['type'], $this->_numericColumnTypes)) {

					$alignRight = TRUE;
					$isNumeric = TRUE;
				} else
				if (in_array($columnName, $this->_default['rightAlignColumns'])) {
					$alignRight = TRUE;
				}

				$tmpModelListColumnShort = $listShort;
			}

			/**
			 * build up available columns
			 */
			$availableColumnNames[$tmpModelListColumnShort . '_' . $columnName] = array(
				'querySelect'=>$tmpModelListColumnShort . '.' . $columnName,
				'columnName'=>$columnName,
				'columnRelationShort'=>$tmpModelListColumnShort,
				'searchLike'=>$searchLike,
				'width'=>$modelListColumnModel->width,
				'display'=>$displayAs,
				'replaceAsConnection'=>$replaceWithColumn,
				'showAsImage'=>$showAsImage,
				'showAsBoolean'=>$showAsBoolean,
				'alignRight'=>$alignRight,
				'isNumeric'=>$isNumeric,
				'isTranslation'=>FALSE,
			);
			if (isset($searchLikeMode)) {
				$availableColumnNames[$tmpModelListColumnShort . '_' . $columnName]['searchLikeMode'] = $searchLikeMode;
			}

			/**
			 * should we prepare flexigrid for images?
			 */
			if ($showAsImage) {
				$useFlexigridForImages = TRUE;
			}
		}

		/**
		 * Translate Columns
		 */
		$modelQueryTranslationSelect = NULL;
		$loadedModelRelations = $loadedModel->getTable()->getRelations();
		if (array_key_exists('Translation', $loadedModelRelations)) {

			$translationColumns = $loadedModelRelations['Translation']->getTable()->getColumns();
			foreach ($this->_default['translateColumns'] as $translateColumnName => $translateColumnOptions) {

				if (array_key_exists($translateColumnName, $translationColumns)) {

					$modelQueryTranslationSelect = 'DISTINCT ';
					foreach (L8M_Locale::getSupported() as $translateLang) {

						/**
						 * build up available columns
						 */
						$availableColumnNames['translation_' . $translateLang . '_' . $translateColumnName] = array(
							'querySelect'=>'translation.' . $translateColumnName,
							'columnName'=>$translateColumnName,
							'columnRelationShort'=>'translation',
							'searchLike'=>$translateColumnOptions['search_like'],
							'width'=>$translateColumnOptions['width'],
							'display'=>$translateColumnOptions['display'] . ' (' . $translateLang . ')',
							'replaceAsConnection'=>FALSE,
							'showAsImage'=>FALSE,
							'showAsBoolean'=>FALSE,
							'alignRight'=>FALSE,
							'isNumeric'=>FALSE,
							'isTranslation'=>TRUE,
						);
						if (isset($translateColumnOptions['search_like_mode'])) {
							$availableColumnNames['translation_' . $translateLang . '_' . $translateColumnName]['searchLikeMode'] = $translateColumnOptions['search_like_mode'];
						}
					}
				}
			}
		}


		/**
		 * flexigrid standards
		 */
		$flexigridID = $listShort;
		if ($this->_default['showTitle']) {
			$flexigridTitle = $modelListModel->Translation[L8M_Library::getLanguage()]['title'];
		} else {
			$flexigridTitle = '';
		}
		$flexigridWidth = $modelListModel->width;
		$flexigridHeight = $modelListModel->height;

		/**
		 * overload some default by database ;o)
		 */
		$this->_loadDefaultsFormDatabase($modelListModel->id);

		/**
		 * page
		 */
		$page = $this->_controller->getRequest()->getParam('page', NULL, FALSE);
		$page = $page
			  ? $page
			  : $this->_default['page']
		;
		if (!is_numeric($page)) {
			$page = $this->_default['page'];
		}

		/**
		 * results per page
		 */
		$resultPerPage = $this->_controller->getRequest()->getParam('rp', NULL, FALSE);
		$resultPerPage = $resultPerPage
			  ? $resultPerPage
			  : $this->_default['resultPerPage']
		;
		if (!is_numeric($resultPerPage)) {
			$resultPerPage = $this->_default['resultPerPage'];
		}

		/**
		 * default order by
		 */
		$defaultSortName = $modelListModel->default_sort;
		if ($this->_default['sortname']) {
			$defaultSortName = $this->_default['sortname'];
		}

		/**
		 * sort order
		 */
		$sortOrder = $this->_controller->getRequest()->getParam('sortorder', NULL, FALSE);
		$sortOrder = $sortOrder
				   ? $sortOrder
				   : $this->_default['sortorder']
		;
		if ($sortOrder != 'asc') {
			$sortOrder = 'desc';
		}

		/**
		 * sort by name
		 */
		$sortName = $this->_controller->getRequest()->getParam('sortname', NULL, FALSE);
		$sortName = $sortName ? $sortName : $defaultSortName;
		$sortNameForDefaultSave = $sortName;
		if (array_key_exists($sortName, $availableColumnNames)) {
			$flexSortName = $sortName;
			$sortName = $this->_replaceFormColToQueryCol($sortName);
		} else {
			$flexSortName = $defaultSortName;
			$sortName = $this->_replaceFormColToQueryCol($defaultSortName);
		}

		/**
		 * search for query
		 */
		$searchQuery = $this->_controller->getRequest()->getParam('query', NULL, FALSE);
		if ($searchQuery == ' ') {
			$searchQuery = '';
		} else {
			$searchQuery = $searchQuery ? trim($searchQuery) : $this->_default['searchQuery'];
		}

		/**
		 * search by type
		 */
		$searchType = $this->_controller->getRequest()->getParam('qtype', NULL, FALSE);
		$searchType = $searchType ? $searchType : $this->_default['searchQType'];
		$searchTypeForDefaultSave = $searchType;
		$searchOrSortTranslationWhere = FALSE;
		$searchTranslationLang = NULL;
		if (array_key_exists($searchType, $availableColumnNames)) {
			$searchLike = $availableColumnNames[$searchType]['searchLike'];
			if (isset($availableColumnNames[$searchType]['searchLikeMode'])) {
				$searchLikeMode = $availableColumnNames[$searchType]['searchLikeMode'];
			}
			$flexSearchType = $searchType;
			if (isset($availableColumnNames[$searchType]['isTranslation']) &&
				$availableColumnNames[$searchType]['isTranslation']) {

				$searchTranslationWhere = TRUE;
				foreach (L8M_Locale::getSupported() as $tmpLang) {
					if ($searchType == 'translation_' . $tmpLang . '_' . $availableColumnNames[$searchType]['columnName']) {
						$searchTranslationLang = $tmpLang;
					}
				}
				if ($tmpLang === NULL) {
					$flexSearchType = '';
					$searchType = '';
					$searchLike = TRUE;
					$searchLikeMode = 3;
				}
				$searchType = 'translation.' . $availableColumnNames[$searchType]['columnName'];
			} else {
				$searchType = $this->_replaceFormColToQueryCol($searchType);
			}
		} else {
			$flexSearchType = '';
			$searchType = '';
			$searchLike = TRUE;
			$searchLikeMode = 3;
		}

		/**
		 * save some defaults to database ;o)
		 */
		$this->_saveDefaultsToDatabase($modelListModel->id, $listShort, array(
			'resultPerPage'=>$resultPerPage,
			'sortname'=>$sortNameForDefaultSave,
			'sortorder'=>$sortOrder,
			'searchQType'=>$searchTypeForDefaultSave,
			'searchQuery'=>$searchQuery,
		));

		/**
		 * order by
		 */
		$tmpDoctrineOrderBy['front'] = array();
		$tmpDoctrineOrderBy['end'] = array();
		foreach ($availableColumnNames as $columnName => $columnOptions) {
			if ($flexSortName == $columnName ||
				count($tmpDoctrineOrderBy['front']) != 0) {

				if (count($tmpDoctrineOrderBy['front']) == 0) {
					$tmpDoctrineOrderBy['front'][] = $columnOptions['querySelect'] . ' ' . strtoupper($sortOrder);
				} else {
					$tmpDoctrineOrderBy['front'][] = $columnOptions['querySelect'] . ' ASC';
				}
			} else {

				$tmpDoctrineOrderBy['end'][] = $columnOptions['querySelect'] . ' ASC';
			}
		}
		$tmp2DoctrineOrderBy = array();
		if (count($tmpDoctrineOrderBy['front']) != 0) {
			$tmp2DoctrineOrderBy[] = implode(', ', $tmpDoctrineOrderBy['front']);
		}
		if (count($tmpDoctrineOrderBy['end']) != 0) {
			$tmp2DoctrineOrderBy[] = implode(', ', $tmpDoctrineOrderBy['end']);
		}
		$doctrineOrderBy = implode(', ', $tmp2DoctrineOrderBy);

		/**
		 * do we have an AJAX request?
		 */
		if ($this->_controller->getRequest()->isXmlHttpRequest() ||
			$showAjax) {

			$recordEditID = $this->_controller->getRequest()->getParam('editID', NULL, FALSE);
			$recordColumnName = $this->_controller->getRequest()->getParam('editRecord', NULL, FALSE);
			$recordSetBoolean = $this->_controller->getRequest()->getParam('setBoolean', NULL, FALSE);
			$lodedEditModelColumns = array();
			$loadedEditModel = FALSE;

			if ($recordEditID &&
				$recordColumnName &&
				$recordSetBoolean) {

				$loadedEditModel = Doctrine_Query::create()
					->from($this->_modelName . ' e')
					->where('e.id = ? ', array($recordEditID))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($loadedEditModel) {
					$lodedEditModelColumns = $loadedEditModel->getTable()->getColumns();
				}
			}

			if ($recordEditID &&
				$recordColumnName &&
				$recordSetBoolean &&
				$loadedEditModel &&
				array_key_exists($recordColumnName, $lodedEditModelColumns)) {

				if ($recordSetBoolean == 'true' ||
					$recordSetBoolean == 'false') {

					if ($this->_default['deactivateBooleanLink']) {
						$javaScriptLinkAction = NULL;
						$cssLinkAction = ' flexigrid-column-deactivated';
					} else {
						$javaScriptLinkAction = 'flexigridEditRecordColumn(this); ';
						$cssLinkAction = NULL;
					}

					if (in_array($recordColumnName, $this->_default['deactivateBooleanLinkIfTrue']) &&
						$recordSetBoolean == 'true') {

						$javaScriptLinkAction = NULL;
						$cssLinkAction = ' flexigrid-column-deactivated';
					}

					if (in_array($recordColumnName, $this->_default['deactivateBooleanLinkIfFalse']) &&
						$recordSetBoolean == 'false') {

						$javaScriptLinkAction = NULL;
						$cssLinkAction = ' flexigrid-column-deactivated';
					}

					if ($recordSetBoolean == 'true') {
						$loadedEditModel->$recordColumnName = TRUE;
						if (in_array($recordColumnName, $this->_default['reverseBoolean'])) {
							$bodyData = '<a href="' . $this->_controller->view->url(array('editID'=>$recordEditID, 'editRecord'=>$recordColumnName, 'setBoolean'=>'false')) . '" onclick="' . $javaScriptLinkAction . 'return false;" class="flexigrid-column-on' . $cssLinkAction . '" id="flexigrid-column-on_' . $recordColumnName . '_' . $recordEditID . '"></a> <span class="flexigrid-column-off">false</span>';
						} else {
							$bodyData = '<span class="flexigrid-column-on">true</span> <a href="' . $this->_controller->view->url(array('editID'=>$recordEditID, 'editRecord'=>$recordColumnName, 'setBoolean'=>'false')) . '" onclick="' . $javaScriptLinkAction . 'return false;" class="flexigrid-column-off' . $cssLinkAction . '" id="flexigrid-column-off_' . $recordColumnName . '_' . $recordEditID . '"></a>';
						}
					} else
					if ($recordSetBoolean == 'false') {
						$loadedEditModel->$recordColumnName = FALSE;
						if (in_array($recordColumnName, $this->_default['reverseBoolean'])) {
							$bodyData = '<span class="flexigrid-column-on">true</span> <a href="' . $this->_controller->view->url(array('editID'=>$recordEditID, 'editRecord'=>$recordColumnName, 'setBoolean'=>'true')) . '" onclick="' . $javaScriptLinkAction . 'return false;" class="flexigrid-column-off' . $cssLinkAction . '" id="flexigrid-column-off_' . $recordColumnName . '_' . $recordEditID . '"></a>';
						} else {
							$bodyData = '<a href="' . $this->_controller->view->url(array('editID'=>$recordEditID, 'editRecord'=>$recordColumnName, 'setBoolean'=>'true')) . '" onclick="' . $javaScriptLinkAction . 'return false;" class="flexigrid-column-on' . $cssLinkAction . '" id="flexigrid-column-on_' . $recordColumnName . '_' . $recordEditID . '"></a> <span class="flexigrid-column-off">false</span>';
						}
					}
				}
				$loadedEditModel->save();
			} else {

				/**
				 * create select
				 */
				$querySelects = array();
				foreach ($availableColumnNames as $columnName => $columnOptions) {
					if (!$columnOptions['isTranslation']) {
						$querySelects[] = $columnOptions['querySelect'];
					}
				}

				/**
				 * include left join primery id into selects
				 */
				$tmpAvailableLeftJoins = array();
				foreach ($availableLeftJoins as $leftJoinShort => $leftJoinArray) {

					/**
					 * otherwise causes query-error
					 */
					if (!in_array($leftJoinShort . '.id', $querySelects) ||
						(
							isset($availableColumnNames[$leftJoinShort . '_id']) &&
							$availableColumnNames[$leftJoinShort . '_id']['querySelect'] == $leftJoinShort . '.id' &&
							!$availableColumnNames[$leftJoinShort . '_id']['isTranslation']
						)) {

						$querySelects[] = $leftJoinShort . '.id';
						$tmpAvailableLeftJoins[$leftJoinShort] = $leftJoinArray;
					}
				}
				$availableLeftJoins = $tmpAvailableLeftJoins;

				/**
				 * query the actions
				 */
				$modelQuery = Doctrine_Query::create();

				if ($session->filterValue &&
					$session->filterKey &&
					$session->filterFromM2N &&
					$session->filterAlias) {

					$modelQuery = $modelQuery
						->from($session->filterFromM2N . ' m2njoin')
						->leftJoin('m2njoin.' . $session->filterAlias . ' ' . $listShort)
						->select($modelQueryTranslationSelect . 'm2njoin.id,' . implode(', ', $querySelects))
						->distinct(TRUE)
					;
				} else {
					$modelQuery = $modelQuery
						->from($defaultModelFrom)
						->select($modelQueryTranslationSelect . implode(', ', $querySelects))
						->distinct(TRUE)
					;
				}

				if (array_key_exists('Translation', $loadedModelRelations) &&
					$modelQueryTranslationSelect) {

					$modelQuery = $modelQuery->leftJoin($listShort . '.Translation translation');
				}

				$modelQuery = $modelQuery
					->orderBy($doctrineOrderBy)
					->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
				;

				/**
				 * add where
				 */
				$modelQuery = $this->_addWhereToQuery($modelQuery, $modelListModel, $availableLeftJoins);

				/**
				 * do we have a search query
				 */
				if ($searchQuery != '' &&
					$searchType != '') {

					if ($searchLike) {
						$doctrineSearchQuery = 'LOWER(' . $searchType . ') LIKE ?';
						if (isset($searchLikeMode)) {
							if ($searchLikeMode == 1) {
								$searchQuery = strtolower($searchQuery) . '%';
							} else
							if ($searchLikeMode == 2) {
								$searchQuery = '%' . strtolower($searchQuery);
							} else {
								$searchQuery = '%' . strtolower($searchQuery) . '%';
							}
						} else {
							$searchQuery = '%' . strtolower($searchQuery) . '%';
						}
					} else {
						$doctrineSearchQuery = $searchType . ' = ?';
					}
					$modelQuery->addWhere($doctrineSearchQuery, array($searchQuery));

					if ($searchOrSortTranslationWhere &&
						$searchTranslationLang) {

						$modelQuery->addWhere('translation.lang = ? ', array($searchTranslationLang));
					}
				}

				/**
				 * start pagination
				 */
				$pager = new Doctrine_Pager($modelQuery, $page, $resultPerPage);

				/**
				 * retrieve records as array
				 */
				$records = $pager->execute();

				/**
				 * data
				 */
				$data = array(
					'page'=>$pager->getPage(),
					'total'=>$pager->getNumResults()
				);

				foreach ($records as $record) {
					$cells = array();
					$firstCell = NULL;
					$isFirstCell = TRUE;
					$i = 0;
					foreach ($availableColumnNames as $columnName => $columnOptions) {
						if ($isFirstCell) {
							$firstCell = $record[$columnName];
							$isFirstCell = FALSE;
						}
						if ($columnOptions['replaceAsConnection'] == NULL) {
							if ($this->_modelName == 'Default_Model_Media' &&
								$i == 1) {

								$cells[] = $this->_replaceRecordColumn($record, 'media_short', array('showAsImage'=>TRUE, 'replaceAsConnection'=>array('onModel'=>'Default_Model_Media' . ucfirst($record['mediatype_short']))));
							}

							if ($columnOptions['showAsBoolean'] == TRUE &&
								isset($record[$columnOptions['columnRelationShort'] . '_id'])) {

								$editRecordID = $record[$columnOptions['columnRelationShort'] . '_id'];

								if ($this->_default['deactivateBooleanLink']) {
									$javaScriptLinkAction = NULL;
									$cssLinkAction = ' flexigrid-column-deactivated';
								} else {
									$javaScriptLinkAction = 'flexigridEditRecordColumn(this); ';
									$cssLinkAction = NULL;
								}

								if (in_array($columnOptions['columnName'], $this->_default['deactivateBooleanLinkIfTrue']) &&
									$record[$columnName]) {

									$javaScriptLinkAction = NULL;
									$cssLinkAction = ' flexigrid-column-deactivated';
								}

								if (in_array($columnOptions['columnName'], $this->_default['deactivateBooleanLinkIfFalse']) &&
									!$record[$columnName]) {

									$javaScriptLinkAction = NULL;
									$cssLinkAction = ' flexigrid-column-deactivated';
								}

								if (in_array($columnOptions['columnName'], $this->_default['reverseBoolean'])) {
									if ($record[$columnName]) {
										$recordValue = '<a href="' . $this->_controller->view->url(array('editID'=>$editRecordID, 'editRecord'=>$columnOptions['columnName'], 'setBoolean'=>'false')) . '" onclick="' . $javaScriptLinkAction . 'return false;" class="flexigrid-column-on' . $cssLinkAction . '" id="flexigrid-column-on_' . $columnOptions['columnName'] . '_' . $editRecordID . '"></a> <span class="flexigrid-column-off">false</span>';
									} else {
										$recordValue = '<span class="flexigrid-column-on">true</span> <a href="' . $this->_controller->view->url(array('editID'=>$editRecordID, 'editRecord'=>$columnOptions['columnName'], 'setBoolean'=>'true')) . '" onclick="' . $javaScriptLinkAction . 'return false;" class="flexigrid-column-off' . $cssLinkAction . '" id="flexigrid-column-off_' . $columnOptions['columnName'] . '_' . $editRecordID . '"></a>';
									}
								} else {
									if ($record[$columnName]) {
										$recordValue = '<span class="flexigrid-column-on">true</span> <a href="' . $this->_controller->view->url(array('editID'=>$editRecordID, 'editRecord'=>$columnOptions['columnName'], 'setBoolean'=>'false')) . '" onclick="' . $javaScriptLinkAction . 'return false;" class="flexigrid-column-off' . $cssLinkAction . '" id="flexigrid-column-off_' . $columnOptions['columnName'] . '_' . $editRecordID . '"></a>';
									} else {
										$recordValue = '<a href="' . $this->_controller->view->url(array('editID'=>$editRecordID, 'editRecord'=>$columnOptions['columnName'], 'setBoolean'=>'true')) . '" onclick="' . $javaScriptLinkAction . 'return false;" class="flexigrid-column-on' . $cssLinkAction . '" id="flexigrid-column-on_' . $columnOptions['columnName'] . '_' . $editRecordID . '"></a> <span class="flexigrid-column-off">false</span>';
									}
								}
								$cells[] = '<span class="flexigrid-switch flexigrid-switch_' . $columnOptions['columnName'] . '_' . $editRecordID . '">' . $recordValue . '</span>';
							} else {
								if ($columnOptions['isNumeric']) {
									$record[$columnName] = L8M_Translate::numeric($record[$columnName]);
								}

								if (array_key_exists($columnOptions['columnName'], $this->_default['specificColumnSubLinks']) &&
									isset($record[$columnOptions['columnRelationShort'] . '_id'])) {

									$url = $this->_controller->view->url($this->_default['specificColumnSubLinks'][$columnOptions['columnName']] + array('filterValue'=>$record[$columnOptions['columnRelationShort'] . '_id']), NULL, TRUE);
									$recordValue = '<a href="' . $url . '">' . $record[$columnName] . '</a>';
								} else {
									if (array_key_exists($columnName, $record)) {
										$recordValue = $record[$columnName];
									} else {
										$recordValue = NULL;
									}
								}

								if ($columnOptions['alignRight']) {
									$recordValue = '<span class="right">' . $recordValue . '</span>';
								}

								if (array_key_exists('isTranslation', $columnOptions) &&
									$columnOptions['isTranslation']) {

									$translateModel = Doctrine_Query::create();
									$translateModel = $translateModel
										->from($defaultModelFrom)
										->where($listShort . '.id = ?', array($record[$listShort . '_id']))
										->execute()
										->getFirst()
									;
									foreach (L8M_Locale::getSupported() as $tmpSupportedLang) {
										$recordValue = NULL;
										if ($translateModel &&
											isset($translateModel->Translation[$tmpSupportedLang]) &&
											isset($translateModel->Translation[$tmpSupportedLang][$columnOptions['columnName']])) {

											$recordValue = $translateModel->Translation[$tmpSupportedLang][$columnOptions['columnName']];
										}
										$cells[] = $recordValue;
									}
								} else {
									$cells[] = $recordValue;
								}
							}
						} else {

							/**
							 * replace record column content with its media
							 */
							$record[$columnName] = $this->_replaceRecordColumn($record, $columnName, $columnOptions);

							/**
							 * do we have sublinks enabled?
							 */
							if ($this->_default['subLinks']) {
								$urlArray = array(
									'action'=>'list',
									'controller'=>$this->_controller->getRequest()->getControllerName(),
									'module'=>$this->_controller->getRequest()->getModuleName(),
									'modelListName'=>$columnOptions['replaceAsConnection']['onModel'],
									'query'=>$record[$columnOptions['replaceAsConnection']['replacedColumnName']],
									'qtype'=>$columnOptions['replaceAsConnection']['columnName'],
								);
								$url = $this->_controller->view->url($urlArray, NULL, TRUE);
								$cells[] = '<a href="' . $url . '">' . $record[$columnName] . '</a>';
							} else {
								$cells[] = $record[$columnName];
							}
						}
						$i++;
					}
					$data['rows'][] = array(
						'id'=>$firstCell,
						'cell'=>$cells,
					);
				}

				/**
				 * json
				 */
				$bodyData = Zend_Json_Encoder::encode($data);

				/**
				 * header
				 */
				$bodyContentHeader = 'application/json';
			}

			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			if (!$showAjax &&
				isset($bodyContentHeader)) {

				$this->_controller->getResponse()
					->setHeader('Content-Type', $bodyContentHeader)
				;
			}
			$this->_controller->getResponse()
				->setBody($bodyData)
			;
		} else {

			/**
			 * no AJAX request, so do view Gedöns!
			 */

			/**
			 * build coulumn array for flexigrid view
			 */
			$flexigridColumns = array();
			$i = 0;
			foreach ($availableColumnNames as $columnName => $columnOptions) {

				/**
				 * check display mode hide or not
				 */
				$hideColumnTmp = false;
				if (in_array($columnName, $this->_default['hideColumns'])) {
					$hideColumnTmp = true;
				}

				if ($this->_modelName == 'Default_Model_Media' &&
					$i == 1) {

					$flexigridColumns[] = array(
						'display'=>$this->_controller->view->translate('Media'),
						'name'=>'media',
						'sortable'=>false,
						'searchable'=>false,
						'width'=>100,
						'hide'=>$hideColumnTmp,
					);
				}
				if ($columnOptions['isTranslation']) {
					$columnSortable = false;
				} else {
					$columnSortable = true;
				}
				$flexigridColumns[] = array(
					'display'=>$columnOptions['display'],
					'name'=>$columnName,
					'sortable'=>$columnSortable,
					'searchable'=>true,
					'width'=>$columnOptions['width'],
					'hide'=>$hideColumnTmp,
				);
				$i++;
			}

			/**
			 * result per page options
			 */
			$doOnlySmallResultsPerpage = FALSE;
			if ($this->_modelName == 'Default_Model_Media') {
				$doOnlySmallResultsPerpage = TRUE;
			} else {

				foreach ($availableLeftJoins as $availableLeftJoin) {
					if ($availableLeftJoin['modelAliasName'] == 'MediaImage') {
						$doOnlySmallResultsPerpage = TRUE;
					}
				}
			}
			if ($doOnlySmallResultsPerpage) {
				$resultPerPageOptions = array(10);
			} else {
				$resultPerPageOptions = array(10, 15, 20, 25, 50, 100, 250, 500, 1000);
			}

			if (!in_array($resultPerPage, $resultPerPageOptions) &&
				count($resultPerPageOptions) > 0) {

				$resultPerPage = $resultPerPageOptions[0];
			}

			/**
			 * build buttons for flexigrid view
			 */
			$flexigridButtons = $this->getButtons($modelListModel);
			$activateStandardButtonsFunctions = FALSE;
			if ($modelListModel->button_add) {
				$activateStandardButtonsFunctions = TRUE;
			}
			if ($modelListModel->button_edit) {
				$activateStandardButtonsFunctions = TRUE;
			}
			if ($modelListModel->button_delete) {
				$activateStandardButtonsFunctions = TRUE;
			}

			/**
			 * flexigrid url
			 */
			$url = array_merge(
				array(
					'action'=>'list',
					'controller'=>$this->_controller->getRequest()->getControllerName(),
					'module'=>$this->_controller->getRequest()->getModuleName(),
					'modelListName'=>$this->_modelName,
				),
				$this->_leadThroughUrl
			);

			/**
			 * flexigrid button url
			 */
			$buttonUrl = array_merge(
				array(
					'action'=>'list',
					'controller'=>$this->_controller->getRequest()->getControllerName(),
					'module'=>$this->_controller->getRequest()->getModuleName(),
					'modelListName'=>$this->_modelName,
				),
				$this->_leadThroughButton
			);

			/**
			 * flexigrid options
			 */
			$flexigridOptions = array(
				'title'=>$flexigridTitle,
				'url'=>$this->_controller->view->url($url, NULL, TRUE),
				'urlArray'=>$url,
				'sortname'=>$flexSortName,
				'sortorder'=>$sortOrder,
				'query'=>$searchQuery,
				'qtype'=>$flexSearchType,
				'cssClassName'=>$this->_default['cssClassName'],
				'width'=>$flexigridWidth,
				'height'=>$flexigridHeight,
				'resizable'=>false,
				'buttons'=>$flexigridButtons,
				'activateStandardButtonsFunctions'=>$activateStandardButtonsFunctions,
				'leadThroughUrl'=>$buttonUrl,
				'columns'=>$flexigridColumns,
				'rp'=>$resultPerPage,
				'rpOptions'=>$resultPerPageOptions,
				'page'=>$page,
			);

			$this->_controller->view->modelList = $this->_controller->view->flexigrid('flexiGrid' . $flexigridID, $flexigridOptions, $useFlexigridForImages);
			$this->_controller->view->modelFormListLeadThroughUrl = $this->_leadThroughUrl;
		}
	}

	/**
	 * replace a record column value with its media part and return that html string
	 *
	 * @param array $record
	 * @param string $columnName
	 * @param array $columnOptions
	 *
	 * @return String|Default_Model_Media
	 */
	private function _replaceRecordColumn($record = NULL, $columnName = NULL, $columnOptions = NULL, $returnMediaModel = FALSE)
	{
		if ($record != NULL &&
			$columnName != NULL &&
			$columnOptions != NULL) {

			$returnValue = $record[$columnName];

			/**
			 * prepare preview image of media
			 */
			if ($record[$columnName] &&
				isset($columnOptions['showAsImage']) &&
				$columnOptions['showAsImage'] &&
				isset($columnOptions['replaceAsConnection']) &&
				isset($columnOptions['replaceAsConnection']['onModel'])) {

				/**
				 * do we have an image?
				 */
				if ($columnOptions['replaceAsConnection']['onModel'] == 'Default_Model_MediaImage') {
					$mediaImageModel = Doctrine_Query::create()
						->from('Default_Model_MediaImage m')
						->where('m.short = ? ', $record[$columnName])
						->execute()
						->getFirst()
					;
					if ($mediaImageModel) {
						$previewMediaImageModel = $mediaImageModel->maxBox(100, 32);
						$previewMediaImageModel->setHtmlAttribute('class', 'flexigrid-preview-pic');
					}

					if ($returnMediaModel) {
						$returnValue = $previewMediaImageModel;
					} else {
						$returnValue = (string) $previewMediaImageModel;
					}
				} else

				/**
				 * do we have a file
				 */
				if (preg_match('/Default_Model_Media(.*)$/', $columnOptions['replaceAsConnection']['onModel']) &&
					$columnOptions['replaceAsConnection']['onModel'] != 'Default_Model_MediaImageInstance') {

					if ($returnMediaModel) {
						$mediaModel = Doctrine_Query::create()
							->from('Default_Model_Media m')
							->where('m.short = ? ', $record[$columnName])
							->execute()
							->getFirst()
						;
						$returnValue = $mediaModel;
					} else {
						$cssClassAddon = 'file';
						$fileTypes = array(
							'word'=>array('doc', 'docx', 'dot', 'dotx'),
							'excel'=>array('xlc', 'xls', 'xlsx'),
							'acrobat'=>array('pdf'),
							'flash'=>array('swf'),
							'compressed'=>array('zip', 'tar', 'rar', 'gzip'),
							'powerpoint'=>array('ppt', 'pptx'),
							'movie'=>array('mp4', 'mpeg', 'flv', 'fl4', 'avi'),
						);
						$tmpFileArray = explode('.', $record[$columnName]);
						$fileExtension = strtolower($tmpFileArray[count($tmpFileArray) - 1]);

						foreach ($fileTypes as $cssType => $fileTypeArray) {
							if (in_array($fileExtension, $fileTypeArray)) {
								$cssClassAddon = $cssType;
							}
						}
						$returnValue = '<span class="flexigrid-preview-background flexigrid-preview-' . $cssClassAddon . '">' . $record[$columnName] . '</span>';
					}
				}
			}
		} else {
			$returnValue = NULL;
		}

		return $returnValue;
	}

	/**
	 * replace form column name to query name
	 *
	 * @param string $name
	 * @return string
	 */
	private function _replaceFormColToQueryCol($name = NULL)
	{
		$pos = strpos($name, '_');
		if ($pos !== FALSE) {
			$name = substr_replace($name, '.', $pos, 1);
		}
		return $name;
	}

	/**
	 * replace form column name to query name
	 *
	 * @param Default_Model_ModelList $query
	 * @return boolean
	 */
	private function _loadWhere($modelListModel = NULL)
	{
		if ($this->_default['useDbWhere']) {
			$this->_addJoin = array();
			$this->_addWhere = array();
			$modelListWhereCollection = Doctrine_Query::create()
				->from('Default_Model_ModelListWhere mlw')
				->where('mlw.model_list_id = ? ', array($modelListModel->id))
				->execute()
			;
			foreach ($modelListWhereCollection as $modelListWhereModel) {

				/**
				 * add where
				 */
				if ($modelListWhereModel->model_list_connection_id) {
					$this->addWhere(
						$modelListWhereModel->ModelColumnName->name,
						$modelListWhereModel->value,
						$modelListWhereModel->use_like,
						$modelListWhereModel->ModelListConnection->join_on_short,
						$modelListWhereModel->ModelListConnection->ModelName->name,
						$modelListWhereModel->ModelListConnection->short
					);
				} else {
					$this->addWhere(
						$modelListWhereModel->ModelColumnName->name,
						$modelListWhereModel->value,
						$modelListWhereModel->use_like
					);
				}
			}

			/**
			 * return
			 */
			if (!$modelListWhereCollection) {
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * replace form column name to query name
	 *
	 * @param Doctrine_Query $modelQuery
	 * @param Default_Model_ModelList $modelListModel
	 * @param array $availableLeftJoins
	 * @return Doctrine_Query
	 */
	private function _addWhereToQuery($modelQuery = NULL, $modelListModel = NULL, $availableLeftJoins = NULL)
	{
		/**
		 * check
		 */
		$doSave = TRUE;
		$loadWhere = TRUE;
		$deleteOld = TRUE;
		if (!$this->_default['saveWhere'] &&
			count($this->_addWhere) == 0) {

			if ($this->_loadWhere($modelListModel)) {
				$doSave = FALSE;
				$deleteOld = TRUE;
				$loadWhere = FALSE;
			}
		}

		if ($this->_default['saveWhere'] &&
			count($this->_addWhere) == 0 &&
			$loadWhere) {

			if ($this->_default['useDbWhere']) {
				$deleteOld = FALSE;
			}

			if ($this->_loadWhere($modelListModel)) {
				$doSave = FALSE;
			}
		}

		if ($modelQuery instanceof Doctrine_Query &&
			$modelListModel instanceof Default_Model_ModelList) {

			/**
			 * create left joins
			 */
			if ($availableLeftJoins) {
				foreach ($availableLeftJoins as $leftJoinShort => $leftJoinArray) {
					$modelQuery->leftJoin($leftJoinArray['joinOnShort'] . '.' . $leftJoinArray['modelAliasName'] . ' ' . $leftJoinShort);
				}
				foreach ($this->_addJoin as $leftJoinShort => $leftJoinArray) {
					if (!array_key_exists($leftJoinShort, $availableLeftJoins)) {
						$modelQuery->leftJoin($leftJoinArray['joinOnShort'] . '.' . $leftJoinArray['modelAliasName'] . ' ' . $leftJoinShort);
					}
				}
			} else {
				foreach ($this->_addJoin as $leftJoinShort => $leftJoinArray) {
					$modelQuery->leftJoin($leftJoinArray['joinOnShort'] . '.' . $leftJoinArray['modelAliasName'] . ' ' . $leftJoinShort);
				}
			}

			/**
			 * create where
			 */
			$whereColumn = '';
			foreach ($this->_addWhere as $where) {
				$whereColumn = trim($where['column']);
				$whereFront = '';
				if ($where['as'] == NULL) {
					$whereFront = $modelListModel->name_short;
				} else {
					$whereFront = $where['as'];
				}
				if (substr($whereColumn, 0, strlen($whereFront)) != $whereFront) {
					$whereColumn = $whereFront . '.' . $whereColumn;
				}
				if ($where['option'] == 'NULL') {
					$modelQuery->addWhere($whereColumn . ' IS ? ', array($where['option']));
				} else
				if ($where['useLike']) {
					$modelQuery->addWhere($whereColumn . ' LIKE ? ', array($where['option']));
				} else {
					$modelQuery->addWhere($whereColumn . ' = ? ', array($where['option']));
				}
			}
			foreach ($this->_addWhereDql as $whereDql) {
				$modelQuery->addWhere($whereDql['dql'], $whereDql['values']);
			}
			$this->_saveWhere($modelListModel, $deleteOld, $doSave);
		}

		/**
		 * return
		 */
		return $modelQuery;
	}

	/**
	 * retrieve model-list
	 */
	private function _retrieveList($listName = NULL, $options = NULL)
	{
		/**
		 * retrieve list short
		 */
		$listShort = '';
		$listNames = explode(' ', $listName);
		$modelListModel = NULL;
		if (count($listNames) == 2) {
			if ($listNames[0] == $this->_modelName) {
				$listShort = $listNames[1];
			} else {
				throw new L8M_Exception('Unexpected ListName for list.');
			}
		} else {
			if (strpos($this->_modelName, $listName) === FALSE) {
				$listShort = $listName;
			} else {
				/**
				 * is there only one modelList listed
				 */
				$modelListCollection = Doctrine_Query::create()
					->from('Default_Model_ModelList ml')
					->where('ml.name = ? ', array($this->_modelName))
					->addWhere('ml.name_short = ? ', array($listName))
					->execute()
				;
				if ($modelListCollection->count() == 1) {
					$modelListModel = $modelListCollection->getFirst();
					$listShort = $listName;
				} else
				if ($modelListCollection->count() > 1) {
					$this->_deleteOldList($listName);
				}
			}
		}

		if ($listName == NULL) {

			/**
			 * is there only one modelList listed
			 */
			$modelListCollection = Doctrine_Query::create()
				->from('Default_Model_ModelList ml')
				->where('ml.name = ? ', array($this->_modelName))
				->execute()
			;
			if ($modelListCollection->count() == 1) {
				$modelListModel = $modelListCollection->getFirst();
			} else
			if ($modelListCollection->count() == 0) {
				$modelListModel = FALSE;
			} else {
				throw new L8M_Exception('Too many ListNames. You have to choose the right one.');
			}
		} else {

			/**
			 * retrieve model list
			 */
			if (!$modelListModel) {
				$modelListModel = Doctrine_Query::create()
					->from('Default_Model_ModelList ml')
					->where('ml.name = ? ', array($this->_modelName))
					->addWhere('ml.name_short = ? ', array($listShort))
					->execute()
					->getFirst()
				;
			}
		}

		/**
		 * do we have a model?
		 */
		if (!$modelListModel ||
			$this->_deleteOldList == TRUE) {

			$modelListModel = $this->_createList($listShort, $options);
		}

		if ($this->_default['loadDefaultButtonsFormDefault']) {
			$modelListModel->button_add = $this->_default['button_add'];
			$modelListModel->button_edit = $this->_default['button_edit'];
			$modelListModel->button_delete = $this->_default['button_delete'];
		}

		/**
		 * return
		 */
		return $modelListModel;
	}

	/**
	 * set flag for deleting old model list
	 *
	 * @return L8M_ModelForm_List
	 */
	public function setDeleteOldList()
	{
		$this->_deleteOldList = TRUE;
		return $this;
	}

	/**
	 * handle all opsitions
	 *
	 * @param $m2nRelationFormInfos
	 * @param $loadedModel
	 * @param $formValues
	 */
	private function _handlePosition($m2nRelationFormInfos, $loadedModel, $formValues)
	{
		if (is_array($m2nRelationFormInfos)) {
			foreach ($m2nRelationFormInfos as $key => $value) {
				if (array_key_exists($key, $formValues)) {

					/**
					 * values
					 */
					$newPosition = $formValues[$key];
					$oldPosition = $loadedModel->position;

					if ($oldPosition === NULL) {
						$sql = 'UPDATE ' . $loadedModel->getTable()->getTableName() . ' SET `position` = position + 1 WHERE position > ' . $newPosition . ';';
						$result = L8M_Db::execute($sql);

						$newPosition = $newPosition + 1;
						$loadedModel->merge(array(
							'position'=>$newPosition,
						));
						$loadedModel->save();

						L8M_Cache::cleanAll();
					} else {
						if ($oldPosition != $newPosition) {

							if ($oldPosition > $newPosition) {
								$sql = 'UPDATE ' . $loadedModel->getTable()->getTableName() . ' SET `position` = position + 1 WHERE position > ' . $newPosition . ' AND position <= ' . $oldPosition . ';';
								$newPosition = $newPosition + 1;
							} else {
								$sql = 'UPDATE ' . $loadedModel->getTable()->getTableName() . ' SET `position` = position - 1 WHERE position > ' . $oldPosition . ' AND position <= ' . $newPosition . ';';
							}
							$result = L8M_Db::execute($sql);

							$loadedModel->merge(array(
								'position'=>$newPosition,
							));
							$loadedModel->save();

							L8M_Cache::cleanAll();
						}
					}
				}
			}
		}
	}

	/**
	 * handle all m2n relations
	 *
	 * @param $m2nRelationFormInfos
	 * @param $loadedModel
	 * @param $formValues
	 */
	private function _handleM2N($m2nRelationFormInfos, $loadedModel, $formValues)
	{
		if (is_array($m2nRelationFormInfos)) {
			foreach ($m2nRelationFormInfos as $key => $value) {
				if (array_key_exists($key, $formValues)) {

					/**
					 * @var $relation Doctrine_Relation_ForeignKey
					 */
					$relation = $value['relation'];
					$relationAlias = $value['alias'];
					$relationValuesRelationName = $relationAlias . 'Values';
					$relationValuesModelName = 'Default_Model_' . $relationValuesRelationName;
					$hasRelationValues = FALSE;
					$relationValuesColumnDefinitions = array();
					$relationValuesIdColumnName = NULL;
					$className = $relation->getClass();
					$foreignColumn = $value['foreignColumn'];
					$letsSaveValues = $formValues[$key];
					$orderByColumn = $value['orderBy'];
					$isTranslateable = $value['isTranslateable'];
					$translationColumn = $value['translationColumn'];
					$hasExtraValue = FALSE;
					$extraValueParamName = $value['fieldPrefix'] . $key . '_tabs_';

					/**
					 * check extra value
					 */
					$m2nRelation = new $className();
					$relationColumDefinition = $m2nRelation->getTable()->getColumns();
					if (array_key_exists('value', $relationColumDefinition)) {
						$hasExtraValue = TRUE;
					}

					/**
					 * check relation-values relation
					 */
					if (class_exists($relationValuesModelName, TRUE)) {
						$relationValuesFilter = new Zend_Filter_Word_CamelCaseToUnderscore();
						$relationValuesIdColumnName = strtolower($relationValuesFilter->filter($relationAlias)) . '_id';

						try {
							/**
							 * test 'cause of possible error
							 */
							$relationValuesCollection = Doctrine_Query::create()
								->from($relationValuesModelName . ' m')
								->limit(1)
								->execute()
								->getFirst()
							;

							/**
							 * passed test
							 */
							$relationValuesModel = new $relationValuesModelName();
							$relationValuesColumnDefinitions = $relationValuesModel->getTable()->getColumns();
							unset($relationValuesColumnDefinitions['id']);
							unset($relationValuesColumnDefinitions[$relationValuesIdColumnName]);
							unset($relationValuesColumnDefinitions['created_at']);
							unset($relationValuesColumnDefinitions['updated_at']);
							unset($relationValuesColumnDefinitions['deleted_at']);
							if (count($relationValuesColumnDefinitions) > 0) {
								$hasRelationValues = TRUE;
							}
						} catch (Doctrine_Connection_Exception $exception) {
							/**
							 * @todo maybe do something
							 */
						}
					}

					/**
					 * load save collction and start
					 */
					$savedCollection = Doctrine_Query::create()
						->from($className . ' m')
						->addWhere('m.' . $relation->getForeignColumnName() . ' = ?', array($loadedModel->id))
						->execute()
					;
					foreach ($savedCollection as $savedModel) {
						if (!in_array($savedModel[$foreignColumn], $letsSaveValues)) {
							if ($hasRelationValues) {
								$relationValuesCollection = Doctrine_Query::create()
									->from($relationValuesModelName . ' m')
									->addWhere('m.' . $relationValuesIdColumnName . ' = ? ', array($savedModel->id))
									->execute()
								;
								foreach ($relationValuesCollection as $relationValuesModel) {
									$relationValuesModel->hardDelete();
								}
							}
							$savedModel->hardDelete();
						}
					}
					$orderCounter = 1;
					foreach ($letsSaveValues as $letsSaveValue) {
						$existingModel = Doctrine_Query::create()
							->from($className . ' m')
							->addWhere('m.' . $relation->getForeignColumnName() . ' = ?', array($loadedModel->id))
							->addWhere('m.' . $foreignColumn . ' = ?', array($letsSaveValue))
							->execute()
							->getFirst()
						;

						/**
						 * check and publish translation values to form
						 */
						if ($isTranslateable) {
							$transVal = array();
							foreach (L8M_Locale::getSupported() as $lang) {
								$transKey = $value['fieldPrefix'] . $key . '_tabs_' . $lang . '_' . $letsSaveValue;
								$transVal[$lang] = strip_tags($this->_controller->getRequest()->getParam($transKey, NULL, FALSE));
							}
						}

						/**
						 * check and publish relation-values to form
						 */
						if ($hasRelationValues) {
							$relationValueParams = $this->_controller->getRequest()->getParam($value['fieldPrefix'] . $key . '_tabs_' . $letsSaveValue . '_rv', NULL, FALSE);
							$relationValueParamsOrdered = array();
							$relationValueParamsOrderedByIdentifier = array();
							foreach ($relationValuesColumnDefinitions as $relationValuesColumnDefColName => $relationValuesColumnDefColDefinition) {

								/**
								 * relies on server configuration, cause they manage assoc-arrays in a different ways
								 */
								$relationValuesColumnDefColNameTmp = NULL;
								if (isset($relationValueParams['\\\'' . $relationValuesColumnDefColName . '\\\''])) {
									$relationValuesColumnDefColNameTmp = '\\\'' . $relationValuesColumnDefColName . '\\\'';
								} else
								if (isset($relationValueParams['\'' . $relationValuesColumnDefColName . '\''])) {
									$relationValuesColumnDefColNameTmp = '\'' . $relationValuesColumnDefColName . '\'';
								} else
								if (isset($relationValueParams[$relationValuesColumnDefColName])) {
									$relationValuesColumnDefColNameTmp = $relationValuesColumnDefColName;
								}

								/**
								 * do we have a possible name for that column?
								 */
								if ($relationValuesColumnDefColNameTmp) {
									foreach ($relationValueParams[$relationValuesColumnDefColNameTmp] as $relationValueTempCounter => $relationValueTempParams) {
										if (!array_key_exists($relationValueTempCounter, $relationValueParamsOrdered)) {
											$relationValueParamsOrdered[$relationValueTempCounter] = array();
										}
										$relationValueParamsOrdered[$relationValueTempCounter][$relationValuesColumnDefColName] = L8M_JQuery_Form_Element_M2N::prepareRelationValueInput($relationValueTempParams, $relationValuesColumnDefColDefinition);
									}
								} else {
									throw new L8M_Exception('There is no name found for that column-reference. Check: handleM2N() in ModelFormList or your Server-Configuration.');
								}
							}
						} else {
							$relationValueParamsOrdered = array();
						}
						if (!$existingModel) {
							$existingModel = new $className();
							$existingModel->merge(array(
								$relation->getForeignColumnName()=>$loadedModel->id,
								$foreignColumn=>$letsSaveValue,
							));
						} else {
							if ($hasRelationValues) {
								$relationValuesCollection = Doctrine_Query::create()
									->from($relationValuesModelName . ' m')
									->addWhere('m.' . $relationValuesIdColumnName . ' = ? ', array($existingModel->id))
									->execute()
								;
								foreach ($relationValuesCollection as $relationValuesModel) {
									$relationValuesModel->hardDelete();
								}
							}
						}

						if ($orderByColumn) {
							$existingModel->merge(array(
								$orderByColumn=>$orderCounter,
							));
						}

						if ($hasExtraValue) {
							$extraValue = strip_tags($this->_controller->getRequest()->getParam($extraValueParamName . $letsSaveValue, NULL, FALSE));
							$existingModel->merge(array(
								'value'=>$extraValue,
							));
						}

						if ($isTranslateable) {
							foreach (L8M_Locale::getSupported() as $lang) {
								$existingModel->Translation[$lang][$translationColumn] = $transVal[$lang];
							}
						}
						$existingModel->save();

						if ($hasRelationValues) {
							foreach ($relationValueParamsOrdered as $relationValueParamsOrderedItem) {
								$relationValuesModel = new $relationValuesModelName();
								$relationValuesModel->merge($relationValueParamsOrderedItem);
								$relationValuesModel->$relationValuesIdColumnName = $existingModel->id;
								$relationValuesModel->save();
							}
						}

						$orderCounter++;
					}
				}
			}
		}
	}

	/**
	 * create model list item
	 *
	 * @param string $listShort
	 * @return Default_Model_Base_Abstract
	 */
	private function _saveWhere($modelListModel = NULL, $deleteOld = FALSE, $doSave = TRUE)
	{

		/**
		 * delete old where
		 */
		if ($deleteOld) {
			$modelListWhereCollection = Doctrine_Query::create()
				->from('Default_Model_ModelListWhere mlw')
				->where('mlw.model_list_id = ? ', array($modelListModel->id))
				->execute()
			;
			foreach ($modelListWhereCollection as $modelListWhereModel) {

				/**
				 * delete
				 */
				$modelListWhereModel->hardDelete();
			}
		}

		/**
		 * check
		 */
		if ($this->_default['saveWhere'] &&
			$doSave) {

			/**
			 * save where
			 */
			if ($modelListModel instanceof Default_Model_ModelList) {
				foreach ($this->_addWhere as $where) {
					$modelColumnNameModel = Doctrine_Query::create()
						->from('Default_Model_ModelColumnName mcn')
						->leftJoin('mcn.ModelName mn')
						->where('mn.name = ? ', array('Default_Model_' . $where['modelAliasName']))
						->addWhere('mcn.name = ? ', array(trim($where['column'])))
						->execute()
						->getFirst()
					;

					$modelListConnection = Doctrine_Query::create()
						->from('Default_Model_ModelListConnection mlc')
						->leftJoin('mlc.ModelName mn')
						->where('mn.name = ? ', array('Default_Model_' . $where['modelAliasName']))
						->addWhere('mlc.join_on_short = ? ', array(trim($where['joinOnShort'])))
						->addWhere('mlc.short = ? ', array(trim($where['as'])))
						->execute()
						->getFirst()
					;
					if (!$modelListConnection) {
						$modelName = Doctrine_Query::create()
							->from('Default_Model_ModelName mn')
							->where('mn.name = ? ', array('Default_Model_' . $where['modelAliasName']))
							->execute()
							->getFirst()
						;
						$modelListConnection = new Default_Model_ModelListConnection();
						$modelListConnection->join_on_short = $where['joinOnShort'];
						$modelListConnection->short = $where['as'];
						$modelListConnection->ModelName = $modelName;
						$modelListConnection->save();
					}

					$modelListWhereModel = new Default_Model_ModelListWhere();
					$modelListWhereModel->value = $where['option'];
					$modelListWhereModel->use_like = $where['useLike'];
					$modelListWhereModel->ModelColumnName = $modelColumnNameModel;
					$modelListWhereModel->ModelListConnection = $modelListConnection;
					$modelListWhereModel->ModelList = $modelListModel;
					$modelListWhereModel->save();
				}
			}
		}
	}

	/**
	 * Load some defaults from database in connection with user.
	 *
	 * @param integer $modelListID
	 * @return void
	 */
	private function _loadDefaultsFormDatabase($modelListID = NULL)
	{
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$entityModelListConfigModel = Doctrine_Query::create()
				->from('Default_Model_EntityModelListConfig m')
				->where('m.model_list_id = ? AND m.entity_id = ? ', array($modelListID, Zend_Auth::getInstance()->getIdentity()->id))
				->execute()
				->getFirst()
			;

			if ($entityModelListConfigModel) {
				if ($entityModelListConfigModel->rp !== NULL) {
					$this->_default['resultPerPage'] = $entityModelListConfigModel->rp;
				}

				if ($entityModelListConfigModel->sortname !== NULL) {
					$this->_default['sortname'] = $entityModelListConfigModel->sortname;
				}

				if ($entityModelListConfigModel->sortorder !== NULL) {
					$this->_default['sortorder'] = $entityModelListConfigModel->sortorder;
				}

				if ($entityModelListConfigModel->qtype !== NULL) {
					$this->_default['searchQType'] = $entityModelListConfigModel->qtype;
				}

				if ($entityModelListConfigModel->query !== NULL) {
					$this->_default['searchQuery'] = $entityModelListConfigModel->query;
				}
			}
		}
	}


	/**
	 * Save some defaults to database in connection with user.
	 *
	 * @param integer $modelListID
	 * @return void
	 */
	private function _saveDefaultsToDatabase($modelListID = NULL, $modelListShort = NULL, $settings = array())
	{
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$entityModelListConfigModel = Doctrine_Query::create()
				->from('Default_Model_EntityModelListConfig m')
				->where('m.model_list_id = ? AND m.entity_id = ? ', array($modelListID, Zend_Auth::getInstance()->getIdentity()->id))
				->execute()
				->getFirst()
			;

			if (!$entityModelListConfigModel) {
				$short = $modelListShort . '_' . Zend_Auth::getInstance()->getIdentity()->login;
				$entityModelListConfigModel = Doctrine_Query::create()
					->from('Default_Model_EntityModelListConfig m')
					->where('m.short = ? ', array($short))
					->execute()
					->getFirst()
				;
				if (!$entityModelListConfigModel) {
					$entityModelListConfigModel = new Default_Model_EntityModelListConfig();
					$entityModelListConfigModel->short = $short;
					$entityModelListConfigModel->entity_id = Zend_Auth::getInstance()->getIdentity()->id;
				}
				$entityModelListConfigModel->model_list_id = $modelListID;
			}

			if (isset($settings['resultPerPage']) &&
				$entityModelListConfigModel->rp != $settings['resultPerPage']) {

				$entityModelListConfigModel->rp = $settings['resultPerPage'];
			}

			if (isset($settings['sortname']) &&
				$entityModelListConfigModel->sortname != $settings['sortname']) {

				$entityModelListConfigModel->sortname = $settings['sortname'];
			}

			if (isset($settings['sortorder']) &&
				$entityModelListConfigModel->sortorder != $settings['sortorder']) {

				$entityModelListConfigModel->sortorder = $settings['sortorder'];
			}

			if (isset($settings['searchQType']) &&
				$entityModelListConfigModel->qtype != $settings['searchQType']) {

				$entityModelListConfigModel->qtype = $settings['searchQType'];
			}

			if (isset($settings['searchQuery']) &&
				$entityModelListConfigModel->query != $settings['searchQuery']) {

				$entityModelListConfigModel->query = $settings['searchQuery'];
			}

			$entityModelListConfigModel->save();
		}
	}

	/**
	 * delete model list item
	 *
	 * @param string $listShort
	 * @return void
	 */
	private function _deleteOldList($listShort)
	{
		$modelListCollection = Doctrine_Query::create()
			->from('Default_Model_ModelList ml')
			->where('ml.name_short = ? ', array($listShort))
			->execute()
		;

		foreach ($modelListCollection as $modelListModel) {
			if ($modelListModel) {

				/**
				 * delete relation to edit ignores
				 */
				$modelListEditIgnoreCollection = Doctrine_Query::create()
					->from('Default_Model_ModelListEditIgnore mi')
					->where('mi.model_list_id = ? ', array($modelListModel->id))
					->execute()
				;
				if ($modelListEditIgnoreCollection->count() > 0) {
					foreach ($modelListEditIgnoreCollection as $modelListEditIgnoreModel) {
						$modelListEditIgnoreModel->hardDelete();
					}
				}

				/**
				 * delete relation to list columns
				 */
				$modelListColumnCollection = Doctrine_Query::create()
					->from('Default_Model_ModelListColumn mc')
					->where('mc.model_list_id = ? ', array($modelListModel->id))
					->execute()
				;
				if ($modelListColumnCollection->count() > 0) {
					foreach ($modelListColumnCollection as $modelListColumnModel) {
						$modelListColumnModel->hardDelete();
					}
				}

				/**
				 * delete relation to model list wheres
				 */
				$modelListWhereCollection = Doctrine_Query::create()
					->from('Default_Model_ModelListWhere mw')
					->where('mw.model_list_id = ? ', array($modelListModel->id))
					->execute()
				;
				if ($modelListWhereCollection->count() > 0) {
					foreach ($modelListWhereCollection as $modelListWhereModel) {
						$modelListWhereModel->hardDelete();
					}
				}

				/**
				 * delete relation to entity model list configs
				 */
				$entityModelListConfigCollection = Doctrine_Query::create()
					->from('Default_Model_EntityModelListConfig m')
					->where('m.model_list_id = ? ', array($modelListModel->id))
					->execute()
				;
				if ($entityModelListConfigCollection->count() > 0) {
					foreach ($entityModelListConfigCollection as $entityModelListConfigModel) {
						$entityModelListConfigModel->hardDelete();
					}
				}

				/**
				 * delete relation to export configs
				 */
				$modelListExportCollection = Doctrine_Query::create()
					->from('Default_Model_ModelListExport me')
					->where('me.model_list_id = ? ', array($modelListModel->id))
					->execute()
				;
				if ($modelListExportCollection->count() > 0) {
					foreach ($modelListExportCollection as $modelListExportModel) {
						$modelListColumnExportCollection = Doctrine_Query::create()
							->from('Default_Model_ModelListColumnExport mce')
							->where('mce.model_list_export_id = ? ', array($modelListExportModel->id))
							->execute()
						;
						if ($modelListColumnExportCollection->count() > 0) {
							foreach ($modelListColumnExportCollection as $modelListColumnExportModel) {
								$modelListColumnExportModel->hardDelete();
							}
						}
						$modelListExportModel->hardDelete();
					}
				}

				/**
				 * delete model list
				 */
				$modelListModel->hardDelete();
			}
		}
	}

	/**
	 * create model list item
	 *
	 * @param string $listShort
	 * @return Default_Model_Base_Abstract
	 */
	private function _createList($listShort = NULL, $options = NULL)
	{
		/**
		 * retrive list short
		 */
		if ($listShort == NULL) {
			$listNames = explode('_', $this->_modelName);
			$listName = $listNames[count($listNames) - 1];
			$listShort = strtolower(substr($listName, 0, 1));
		} else {
			$listNames = explode('_', $this->_modelName);
			$listName = $listNames[count($listNames) - 1];
		}

		/**
		 * do we have to delete the list ?
		 */
		if ($this->_deleteOldList) {
			$this->_deleteOldList($listShort);
		}

		/**
		 * create model list record
		 */
		$modelListValues['name'] = $this->_modelName;
		$modelListValues['name_short'] = $listShort;
		$modelListValues['default_sort'] = $listShort . '_id';
		$modelListValues['button_edit'] = $this->_default['button_edit'];
		$modelListValues['button_add'] = $this->_default['button_add'];
		$modelListValues['button_delete'] = $this->_default['button_delete'];
		$modelListValues['width'] = $this->_default['width'];;
		$modelListValues['height'] = $this->_default['height'];;
		$modelListValues = L8M_Locale::addModelArrayTranslation($modelListValues, 'title', $listName);
		$modelListModel = new Default_Model_ModelList();
		$modelListModel->merge($modelListValues);

		/**
		 * create model list column record
		 */
		$loadModel = $this->_modelName;
		$loadedModel = new $loadModel();
		$modelColumns = $loadedModel->getTable()->getColumns();

		/**
		 * columns counter
		 */
		$positionCounter = 0;

		/**
		 * standard columns
		 */
		foreach ($this->_default['columns'] as $column => $columnOptions) {
			if (!in_array($column, $this->_listColumnHide) &&
				array_key_exists($column, $modelColumns)) {

				$modelColumnNameModel = Doctrine_Query::create()
					->from('Default_Model_ModelColumnName mcn')
					->leftJoin('mcn.ModelName mn')
					->where('mn.name = ? ', array($loadModel))
					->addWhere('mcn.name = ? ', array($column))
					->execute()
					->getFirst()
				;
				if ($modelColumnNameModel) {
					$modelColumnNameValues = array();
					$modelColumnNameValues = L8M_Locale::addModelArrayTranslation($modelColumnNameValues, 'display', $columnOptions['display']);
					$modelColumnNameModel->merge($modelColumnNameValues);
					$modelColumnNameModel->save();

					$modelListColumnValues['name'] = $column;
					$modelListColumnValues['search_like'] = $columnOptions['search_like'];
					if (isset($columnOptions['search_like_mode'])) {
						$modelListColumnValues['search_like_mode'] = $columnOptions['search_like_mode'];
					}
					$modelListColumnValues['width'] = $columnOptions['width'];
					$modelListColumnValues['position'] = $positionCounter++;
					$modelListColumnModel = new Default_Model_ModelListColumn();
					$modelListColumnModel->ModelList = $modelListModel;
					$modelListColumnModel->ModelColumnName = $modelColumnNameModel;
					$modelListColumnModel->merge($modelListColumnValues);
					$modelListColumnModel->save();
				}
			}
		}

		/**
		 * relation columns
		 */
		$modelRelations = $loadedModel->getTable()->getRelations();
		foreach ($modelRelations as $name => $definition) {

			if ($name != 'Translation' &&
				$definition instanceof Doctrine_Relation_LocalKey &&
				!in_array($definition->getLocalColumnName(), $this->_listColumnHide)) {

				/**
				 * does relation exists multiple times?
				 */
				$relationExistsMultipleTimes = FALSE;
				foreach ($modelRelations as $subDefinition) {
					if ($definition->getClass() == $subDefinition->getClass()) {
						$relationExistsMultipleTimes = TRUE;
					}
				}

				/**
				 * looking for definition
				 */
				$definitionKey = $definition->getForeign();
				$isForeign = FALSE;
				$displayLang = L8M_Locale::getDefaultSystem();

				if (!$relationExistsMultipleTimes) {
					$modelColumnNameModel = Doctrine_Query::create()
						->from('Default_Model_ModelColumnName mcn')
						->leftJoin('mcn.ModelName mn')
						->where('mn.name = ? ', array($definition->getClass()))
						->addWhere('mcn.name = ? ', array($definitionKey))
						->execute()
						->getFirst()
					;
					$displayName = $definition->getAlias();
				} else {
					$modelColumnNameModel = Doctrine_Query::create()
						->from('Default_Model_ModelColumnName mcn')
						->leftJoin('mcn.ModelName mn')
						->where('mn.name = ? ', array($definition->getClass()))
						->addWhere('mcn.name = ? ', array($definition->getLocal()))
						->execute()
						->getFirst()
					;
					if (!$modelColumnNameModel) {
						$modelColumnNameModel = new Default_Model_ModelColumnName();
						$modelColumnNameModel->merge(array(
							'name'=>$definition->getLocal(),
							'model_name_id'=>Default_Model_ModelName::getModelByColumn('name', $definition->getClass())->id,
						));
						$modelColumnNameModel->save();
					}
					if (array_key_exists($definition->getAlias(), $this->_default['multiSameRelationName'])) {
						$displayName = $this->_default['multiSameRelationName'][$definition->getAlias()]['display'];
						$displayLang = $this->_default['multiSameRelationName'][$definition->getAlias()]['lang'];
					} else {
						$displayName = $definition->getAlias();
					}
				}

				$modelListConnectionShort = strtolower($definition->getAlias());

				$modelListConnectionModel = Doctrine_Query::create()
					->from('Default_Model_ModelListConnection mlc')
					->leftJoin('mlc.ModelName mn')
					->where('mn.name = ? ', array($definition->getClass()))
					->addWhere('mlc.short = ? ', array($modelListConnectionShort))
					->addWhere('mlc.join_on_short = ? ', array($modelListModel->name_short))
					->execute()
					->getFirst()
				;
				$replaceWithColumnSearchLike = FALSE;
				$replaceWithColumnWidth = $this->_default['columns']['name']['width'];
				if (!$modelListConnectionModel) {
					$modelNameModel = Doctrine_Query::create()
						->from('Default_Model_ModelName mn')
						->where('mn.name = ? ', array($definition->getClass()))
						->execute()
						->getFirst()
					;
					$tmpLoadModelName = $definition->getClass();
					$tmpLoadedModelName = new $tmpLoadModelName();
					$tmpLoadedModelNameColumns = $tmpLoadedModelName->getTable()->getColumns();
					$replaceWithColumn = NULL;
					foreach ($this->_default['columns'] as $column => $columnOptions) {
						if (!in_array($column, $this->_listColumnHide) &&
							array_key_exists($column, $tmpLoadedModelNameColumns) &&
							$column != 'id' &&
							is_array($columnOptions) &&
							array_key_exists('use_in_edit_view', $columnOptions) &&
							$columnOptions['use_in_edit_view'] &&
							$replaceWithColumn == NULL) {

							$replaceWithColumn = $column;
							$replaceWithColumnSearchLike = $this->_default['columns'][$column]['search_like'];
							if (isset($this->_default['columns'][$column]['search_like_mode'])) {
								$replaceWithColumnSearchLikeMode = $this->_default['columns'][$column]['search_like_mode'];
							}
							$replaceWithColumnWidth = $this->_default['columns'][$column]['width'];
						}
					}
					$modelListConnectionModel = new Default_Model_ModelListConnection();
					$modelListConnectionModel->ModelName = $modelNameModel;
					$modelListConnectionModel->short = $modelListConnectionShort;
					$modelListConnectionModel->join_on_short = $modelListModel->name_short;
					$modelListConnectionModel->name_alias = $definition->getAlias();
					$modelListConnectionModel->replace_with_column = $replaceWithColumn;
					$modelListConnectionModel->is_foreign = $isForeign;
					$modelListConnectionModel->foreign_key = $definition->getForeign();
					$modelListConnectionModel->local_key = $definition->getLocal();
					$modelListConnectionModel->save();
				}

				$modelColumnNameValues = array();
				$modelColumnNameModel = L8M_Locale::addModelArrayTranslation($modelColumnNameModel, 'display', $displayName, $displayLang);
				$modelColumnNameModel->save();

				$modelListColumnValues['name'] = $definitionKey;
				$modelListColumnValues['search_like'] = $replaceWithColumnSearchLike;
				if (isset($replaceWithColumnSearchLikeMode)) {
					$modelListColumnValues['search_like_mode'] = $replaceWithColumnSearchLikeMode;
				}
				$modelListColumnValues['width'] = $replaceWithColumnWidth;
				$modelListColumnValues['position'] = $positionCounter++;
				$modelListColumnModel = new Default_Model_ModelListColumn();
				$modelListColumnModel->ModelList = $modelListModel;
				$modelListColumnModel->ModelListConnection = $modelListConnectionModel;
				$modelListColumnModel->ModelColumnName = $modelColumnNameModel;
				$modelListColumnModel->merge($modelListColumnValues);
				$modelListColumnModel->save();
			}
		}

		/**
		 * add where
		 */
		$this->_saveWhere($modelListModel);

		/**
		 * retrieve model-list
		 */
		$modelListModel = Doctrine_Query::create()
			->from('Default_Model_ModelList ml')
			->where('ml.name = ? ', array($this->_modelName))
			->addWhere('ml.name_short = ? ', array($listShort))
			->execute()
			->getFirst()
		;

		if (!$modelListModel) {
			throw new L8M_Exception('Something went wrong during auto setup of ModelList.');
		}
		return $modelListModel;
	}

	private function _sortOrderNextPossible($modelListShort, $sortName, $searchColumn)
	{
		$returnValue = FALSE;

		$pos = strpos($sortName, $modelListShort . '_');
		$pos2 = strpos($searchColumn, $modelListShort . '_');

		if ($pos === 0 &&
			$pos2 === 0) {

			$column = substr($sortName, strlen($modelListShort . '_'));
			$column2 = substr($searchColumn, strlen($modelListShort . '_'));

			$modelName = $this->_modelName;
			$loadedModel = new $modelName();

			$columnDefinitions = $loadedModel->getTable()->getColumns();

			if (array_key_exists($column, $columnDefinitions) &&
				array_key_exists($column2, $columnDefinitions) &&
				array_key_exists($column, $this->_default['columns']) &&
				array_key_exists($column2, $this->_default['columns']) &&
				$column == 'id') {

				$returnValue = TRUE;
			}
		}

		return $returnValue;
	}

	private function _sortOrderNextWhere($modelQuery, $modelListShort, $sortName, $searchColumn, $sortOrder, $searchQuery, $model, $getNotFirst = TRUE)
	{
		$pos = strpos($sortName, $modelListShort . '_');
		$pos2 = strpos($searchColumn, $modelListShort . '_');

		if ($pos === 0 &&
			$pos2 === 0) {

			$column = substr($sortName, strlen($modelListShort . '_'));
			$column2 = substr($searchColumn, strlen($modelListShort . '_'));

			$modelName = $this->_modelName;
			$loadedModel = new $modelName();

			$columnDefinitions = $loadedModel->getTable()->getColumns();

			if (array_key_exists($column, $columnDefinitions) &&
				array_key_exists($column2, $columnDefinitions) &&
				array_key_exists($column, $this->_default['columns']) &&
				array_key_exists($column2, $this->_default['columns'])) {

				/**
				 * sort
				 */
				if ($getNotFirst) {
					if (strtolower($sortOrder) == 'asc') {
						$sortOrder = 'ASC';
						$modelQuery = $modelQuery->addWhere($modelListShort . '.' . $column . ' > ? ', array($model->$column));
					} else {
						$sortOrder = 'DESC';
						$modelQuery = $modelQuery->addWhere($modelListShort . '.' . $column . ' <= ? ', array($model->$column));
					}
				}
				$modelQuery = $modelQuery->orderBy($modelListShort . '.' . $column . ' ' . $sortOrder . ', ' . $modelListShort . '.id ASC');

				/**
				 * search
				 */
				if ($searchQuery) {
					if ($this->_default['columns'][$column2]['search_like']) {
						if (isset($this->_default['columns'][$column2]['search_like_mode'])) {
							if ($this->_default['columns'][$column2]['search_like_mode'] == 1) {
								$modelQuery = $modelQuery->addWhere($modelListShort . '.' . $column2 . ' LIKE ? ', array($searchQuery . '%'));
							} else
							if ($this->_default['columns'][$column2]['search_like_mode'] == 2) {
								$modelQuery = $modelQuery->addWhere($modelListShort . '.' . $column2 . ' LIKE ? ', array('%' . $searchQuery));
							} else {
								$modelQuery = $modelQuery->addWhere($modelListShort . '.' . $column2 . ' LIKE ? ', array('%' . $searchQuery . '%'));
							}
						} else {
							$modelQuery = $modelQuery->addWhere($modelListShort . '.' . $column2 . ' LIKE ? ', array('%' . $searchQuery . '%'));
						}
					} else {
						$modelQuery = $modelQuery->addWhere($modelListShort . '.' . $column2 . ' = ? ', array($searchQuery));
					}
				}
			}
		}

		return $modelQuery;
	}
}
