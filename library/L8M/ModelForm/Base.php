<?php

/**
 * L8M
 *
 * Class for autogenerating forms based on Doctrine models.
 *
 * @filesource /library/L8M/Base/Base.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Base.php 568 2018-06-21 08:02:25Z nm $
 */

/**
 *
 *
 * L8M_ModelForm_Base
 *
 *
 */
class L8M_ModelForm_Base extends L8M_JQuery_Form
{
	/**
	 *
	 */
	protected $_addModelFormDecorator = FALSE;

	/**
	 * Reference to the model's table class
	 * @var Doctrine_Table
	 */
	protected $_table;

	/**
	 * Dummy model.
	 * @var Default_Model_Base_Abstract
	 */
	protected $_dummyModel;

	/**
	 * Instance of the Zend_Form based form used
	 * @var Zend_Form
	 */
	protected $_form;

	/**
	 * Which Zend_Form element types are associated with which doctrine type?
	 *
	 * @todo add possibly missing types
	 * @var array
	 */
	protected $_columnTypes = array(
		'integer' => 'text',
		'decimal' => 'text',
		'float' => 'text',
		'double' => 'text',
		'string' => 'text',
		'clob' => 'text',
		'varchar' => 'text',
		'boolean' => 'checkbox',
		'timestamp' => 'datetime',
		'time' => 'text',
		'date' => 'date',
		'datetime' => 'datetime',
		'enum' => 'select'
	);

	/**
	 * Contains all numeric types
	 */
	protected $_numericColumnTypes = array(
		'integer',
		'decimal',
		'float',
		'double',
	);

	/**
	 * Array of hooks that are called before saving the column
	 * @var array
	 */
	protected $_columnHooks = array();

	/**
	 * Default validators for doctrine column types
	 * @var array
	 */
	protected $_columnValidators = array(
		'integer' => 'int',
		'float' => 'float',
		'double' => 'float'
	);

	/**
	 * Column that should be edited.
	 * @var string
	 */
	protected $_editColumnID = NULL;

	/**
	 * Prefix fields with this
	 * @var string
	 */
	protected $_fieldPrefix = '';

	/**
	 * Column names listed in this array will not be shown in the form
	 * @var array
	 */
	protected $_ignoreColumns = array(
		'deleted_at',
		'created_at',
		'updated_at',
	);

	/**
	 * Array of Relation names, that should be ignored
	 * @var array
	 */
	protected $_ignoredM2nRelations = array();

	/**
	 * save m2n relations
	 * @var array
	 */
	protected $_m2nRelations = array();

	/**
	 * save relation form element infos
	 * @var array
	 */
	protected $_m2nRelationFormElementInfos = array();

	/**
	 * save position form element infos
	 * @var array
	 */
	protected $_positionFormElementInfos = array();

	/**
	 * Whether or not to generate fields for many parts of m2o and m2m relations
	 * @var bool
	 */
	protected $_generateManyFields = TRUE;

	/**
	 * Use this to override field types for columns. key = column, value = field type
	 * @var array
	 */
	protected $_fieldTypes = array();

	/**
	 * Field labels. key = column name, value = label
	 * @var array
	 */
	protected $_fieldLabels = array();

	/**
	 * Button Label
	 * @var string
	 */
	protected $_buttonLabel = NULL;

	/**
	 * Labels to use with many to many relations.
	 * key = related class name, value = label
	 * @var array
	 */
	protected $_relationLabels = array();

	/**
	 * contains th order of all elements as wished to be added
	 * @var array
	 */
	protected $_orderOfFormElements = array();

	/**
	 * Name of the model class
	 * @var string
	 */
	protected $_modelName = '';

	/**
	 * Model instance for editing existing models
	 * @var Doctrine_Record
	 */
	protected $_instance = NULL;

	/**
	 * Stores form class names for many-relations
	 * @var array
	 */
	protected $_relationForms = array();

	/**
	 * Stores relations of multi relations concerning recursion
	 * ex. array('Action'=>array('role_id'=>'1')) shoud retrieve all Actions for multi relations with role_id = 1
	 * @var array
	 */
	protected $_relationColumnInMultiRelation = array();

	/**
	 * Stores display-groups to add
	 * ex. array('id_of_display_group'=>array('legend'=>'Some Text', 'elements'=>array('col1', 'col2'))) shoud add col1 and col2 to display group
	 * @var array
	 */
	protected $_reservedDisplayGroups = array();

	/**
	 * Stores definition for relation m2n values
	 * ex. array('BillM2nGoaeNumber'=>array('columnLabels'=>array('service_count'=>'Quantity','service_date'=>'Date'),'allowMultipleRows'=>FALSE,)) should define column labels and allow multiple rows
	 * @var array
	 */
	protected $_relationM2nValuesDefinition = array();

	/**
	 * Contains mediaID columns as array refering the directory for mediaFolderID
	 * ex. array('media_image_id'=>'/images/products')
	 * @var array
	 */
	protected $_mediaDirectoryArray = array();

	/**
	 * Contains mediaID columns as array refering the role used for the media
	 * ex. array('media_image_id'=>'user')
	 * @var array
	 */
	protected $_mediaRoleArray = array();

	/**
	 * Stores ignorence of multi relations concerning recursion
	 * ex. array('Action'=>array(Role)) ignores multi-relation Role in Action
	 * @var array
	 */
	protected $_ignoreColumnInMultiRelation = array();

	/**
	 * Stores ignorence of relations
	 * ex. array('Action', Controller) ignores relation Action and Controller in current model
	 * @var array
	 */
	protected $_ignoreColumnRelation = array();

	/**
	 * Stores replacements of multi relations concerning selections
	 * ex. array('Default_Model_Role'=>'short') replaces 'id' or 'name' with 'short' (only example, cause short is also an standard)
	 * @var array
	 */
	protected $_replaceColumnValuesInMultiRelation = array();

	/**
	 * condition for multi relation
	 * @var array
	 */
	protected $_multiRelationCondition = array();

	/**
	 * tinyMCE config
	 * @var array
	 */
	protected $_tinyMCE = array();

	/**
	 * position form element config
	 * @var array
	 */
	protected $_position = array(
		'useParentRelation'=>NULL,
	);

	/**
	 * Stores static form elements, that should be added to form
	 * @var array
	 */
	protected $_staticFormElementsArray = array();

	/**
	 * Stores boolean and decides, whether to show form-element for "Back after save." or not.
	 * @var array
	 */
	protected $_showBackAfterSave = TRUE;

	/**
	 * Is in Debug-Mode - add standard-submit-button no javascript
	 * @var boolean
	 */
	protected $_isInDebug = FALSE;

	/**
	 * Names of alle elments used in this form.
	 * @var array
	 */
	protected $_formElementNames;

	/**
	 * contains all column form element names, that labels should not be escaped
	 */
	protected $_doNotEscapeLabel = array();

	/**
	 * An array of languages supported by this application.
	 *
	 * @var array
	 */
	protected static $_supportedLanguages = NULL;

	/**
	 * translated form elements
	 * @var array
	 */
	protected $_translatedFormElements = array();

	/**
	 * unordered form elements
	 * @var array
	 */
	protected $_unorderedFormElements = array();

	/**
	 * unordered form elements having a child
	 * @var array
	 */
	protected $_unorderedFormElementsOfChild = array();

	/**
	 * save the names of all unordered static form elements
	 * @var array
	 */
	protected $_unorderedStaticFormElementsName = array();

	/**
	 * names of numeric elements
	 * @var array
	 */
	protected $_numericElements = array();

	/**
	 * default language code
	 * @var string
	 */
	protected $_formLanguage = NULL;

	/**
	 * default model list name
	 * @var string
	 */
	protected $_modelListName = NULL;

	/**
	 * zend view
	 *
	 * @var Zend_View_Interface
	 */
	protected $_view = NULL;

	/**
	 * @param array $options Options to pass to the Zend_Form constructor
	 */
	public function __construct($options = NULL, $model = NULL, $formOptions = NULL)
	{
		/**
		 * retrieve table from model
		 */
		if ($this->_modelName == '') {
			if ($model == NULL) {
				throw new Exception('No model defined');
			} else {
				$this->_modelName = $model;
			}
		}
		$this->_table = Doctrine_Core::getTable($this->_modelName);

		/**
		 * dummy model
		 */
		$modelName = $this->_modelName;
		$this->_dummyModel = new $modelName();

		/**
		 * parent construct
		 */
		parent::__construct($options);

		/**
		 * retrive view
		 */
		$this->_view = Zend_Layout::getMvcInstance()->getView();

		/**
		 * disable Translator because we are translating it
		 */
		$this->setDisableTranslator(TRUE);

		/**
		 * set prefix
		 */
		$this->_fieldPrefix = $this->_table->getTableName() . '_';

		/**
		 * set formOptions
		 */
		if (is_array($formOptions)) {

			/**
			 * for custom order
			 */
			if (isset($formOptions['order']) &&
				is_array($formOptions['order'])) {

				$this->_orderOfFormElements = $formOptions['order'];
			}

			/**
			 * for ignoring only these columns instead of the default ones
			 */
			if (isset($formOptions['ignoreColumns']) &&
				is_array($formOptions['ignoreColumns'])) {

				$this->_ignoreColumns = $formOptions['ignoreColumns'];
			}

			/**
			 * for ignoring some more columns then only the default ones
			 */
			if (isset($formOptions['addIgnoredColumns']) &&
				is_array($formOptions['addIgnoredColumns'])) {

				$this->_ignoreColumns = array_merge($this->_ignoreColumns, $formOptions['addIgnoredColumns']);
			}

			/**
			 * for ignoring some m2n relations
			 */
			if (isset($formOptions['addIgnoredM2nRelations']) &&
				is_array($formOptions['addIgnoredM2nRelations'])) {

				$this->_ignoredM2nRelations = array_merge($this->_ignoredM2nRelations, $formOptions['addIgnoredM2nRelations']);
			}

			/**
			 * for m2n relations
			 */
			if (isset($formOptions['M2NRelations']) &&
				is_array($formOptions['M2NRelations'])) {

				$this->_m2nRelations = array_merge($this->_m2nRelations, $formOptions['M2NRelations']);
			}

			/**
			 * do we have a new form language?
			 */
			if (isset($formOptions['setFormLanguage']) &&
				in_array($formOptions['setFormLanguage'], L8M_Locale::getSupported())) {

				$this->_formLanguage = $formOptions['setFormLanguage'];
			} else {
				$this->_formLanguage = L8M_Locale::getDefaultSystem();
			}

			/**
			 * do not escape labels of the following elements
			 */
			if (isset($formOptions['doNotEscapeLabel']) &&
				is_array($formOptions['doNotEscapeLabel'])) {

				foreach ($formOptions['doNotEscapeLabel'] as $columnFormElementName) {
					$this->_doNotEscapeLabel[] = $this->_fieldPrefix . $columnFormElementName;
				}
			}

			/**
			 * for ignoring some more columns then only the default ones
			 */
			if (isset($formOptions['editColumnID'])) {
				$this->_editColumnID = (int) $formOptions['editColumnID'];
			}

			/**
			 * setting into debug-mode no ajax and tinyMCE available
			 */
			if (isset($formOptions['debug']) &&
				is_bool($formOptions['debug'])) {

				$this->_isInDebug = $formOptions['debug'];
			}

			/**
			 * setting relation for multi-relation
			 */
			if (isset($formOptions['relationColumnInMultiRelation']) &&
				is_array($formOptions['relationColumnInMultiRelation'])) {

				$this->_relationColumnInMultiRelation = $formOptions['relationColumnInMultiRelation'];
			}

			/**
			 * setting add some display groups
			 */
			if (isset($formOptions['addDisplayGroups']) &&
				is_array($formOptions['addDisplayGroups'])) {

				$this->_reservedDisplayGroups = $formOptions['addDisplayGroups'];
			}

			/**
			 * setting definition for relation m2n values
			 */
			if (isset($formOptions['relationM2nValuesDefinition']) &&
				is_array($formOptions['relationM2nValuesDefinition'])) {

				$this->_relationM2nValuesDefinition = $formOptions['relationM2nValuesDefinition'];
			}

			/**
			 * setting ignore for multi-relation
			 */
			if (isset($formOptions['ignoreColumnInMultiRelation']) &&
				is_array($formOptions['ignoreColumnInMultiRelation'])) {

				$this->_ignoreColumnInMultiRelation = $formOptions['ignoreColumnInMultiRelation'];
			}

			/**
			 * setting media directory array
			 */
			if (isset($formOptions['mediaDirectory']) &&
				is_array($formOptions['mediaDirectory'])) {

				$this->_mediaDirectoryArray = $formOptions['mediaDirectory'];
			}

			/**
			 * setting media role array
			 */
			if (isset($formOptions['mediaRole']) &&
				is_array($formOptions['mediaRole'])) {

				$this->_mediaRoleArray = $formOptions['mediaRole'];
			}

			/**
			 * setting ignore for relation
			 */
			if (isset($formOptions['ignoreColumnRelation']) &&
				is_array($formOptions['ignoreColumnRelation'])) {

				$this->_ignoreColumnRelation = $formOptions['ignoreColumnRelation'];
			}

			/**
			 * replace column values in multi relation
			 */
			if (isset($formOptions['replaceColumnValuesInMultiRelation']) &&
				is_array($formOptions['replaceColumnValuesInMultiRelation'])) {

				$this->_replaceColumnValuesInMultiRelation = $formOptions['replaceColumnValuesInMultiRelation'];
			}

			/**
			 * multi relation condition
			 */
			if (isset($formOptions['multiRelationCondition']) &&
				is_array($formOptions['multiRelationCondition'])) {

				$this->_multiRelationCondition = $formOptions['multiRelationCondition'];
			}

			/**
			 * tinyMCE config
			 */
			if (isset($formOptions['tinyMCE']) &&
				is_array($formOptions['tinyMCE'])) {

				$this->_tinyMCE = $formOptions['tinyMCE'];
			}

			/**
			 * position form element config
			 */
			if (isset($formOptions['Position']) &&
				is_array($formOptions['Position'])) {

				if (array_key_exists('useParentRelation', $formOptions['Position']) &&
					is_string($formOptions['Position']['useParentRelation'])) {

					$this->_position['useParentRelation'] = $formOptions['Position']['useParentRelation'];
				}
			}

			/**
			 * setting column labels
			 */
			if (isset($formOptions['columnLabels']) &&
				is_array($formOptions['columnLabels'])) {

				$this->_fieldLabels = $formOptions['columnLabels'];
			}

			/**
			 * setting button label
			 */
			if (isset($formOptions['buttonLabel']) &&
				is_string($formOptions['buttonLabel']) &&
				trim($formOptions['buttonLabel']) !== '') {

				$this->_buttonLabel = $formOptions['buttonLabel'];
			}

			/**
			 * setting column types
			 */
			if (isset($formOptions['columnTypes']) &&
				is_array($formOptions['columnTypes'])) {

				$this->_fieldTypes = $formOptions['columnTypes'];
			}

			/**
			 * setting add model form decorator
			 */
			if (isset($formOptions['addModelFormDecorator']) &&
				is_bool($formOptions['addModelFormDecorator'])) {

				$this->_addModelFormDecorator = $formOptions['addModelFormDecorator'];
			}

			/**
			 * settings for static form elements
			 */
			if (isset($formOptions['addStaticFormElements']) &&
				is_array($formOptions['addStaticFormElements'])) {

				$this->_staticFormElementsArray = $formOptions['addStaticFormElements'];
			}

			/**
			 * settings for showing "back to form" element
			 */
			if (isset($formOptions['showBackAfterSave']) &&
				is_bool($formOptions['showBackAfterSave'])) {

				$this->_showBackAfterSave = $formOptions['showBackAfterSave'];
			}

			/**
			 * settings model list name
			 */
			if (isset($formOptions['modelListName']) &&
				is_string($formOptions['modelListName'])) {

				$this->_modelListName = $formOptions['modelListName'];
			}
		}

		/**
		 * do form construct
		 */
		$this->_preGenerate();
		$this->_generateForm();
		$this->_postGenerate();
	}

	protected function _addDisplayGroups()
	{
		/**
		 * do we have a display group to add?
		 */
		if (count($this->_reservedDisplayGroups) > 0) {

			/**
			 * walk through the display groups
			 */
			foreach ($this->_reservedDisplayGroups as $displayGroupID => $displayGroupSpecs) {

				/**
				 * set options to NULL
				 */
				$options = NULL;

				/**
				 * set elements-array of display-group to an empty one
				 */
				$elements = array();

				/**
				 * do we have a legend
				 */
				if (isset($displayGroupSpecs['legend'])) {

					/**
					 * prepare options array for legend
					 */
					$options = array(
						'legend'=>$displayGroupSpecs['legend'],
					);
				}

				/**
				 * do we have a legend
				 */
				if (array_key_exists('elements', $displayGroupSpecs) &&
					is_array($displayGroupSpecs['elements']) &&
					count($displayGroupSpecs['elements']) > 0) {

					/**
					 * walk through the elements and try adding them
					 */
					foreach ($displayGroupSpecs['elements'] as $elementName) {

						/**
						 * set element name
						 */
						$elementName = $this->_fieldPrefix . $elementName;

						/**
						 * do we have the element with that form initialized
						 */
						if (in_array($elementName, $this->_formElementNames)) {

							/**
							 * add to display-group elements
							 */
							$elements[] = $elementName;
						}
					}
				}

				/**
				 * add  display group to our form
				 */
				$this->addDisplayGroup($elements, $displayGroupID, $options);

				$tmpDisplayGroup = $this->getDisplayGroup($displayGroupID);
				$tmpDisplayGroup
					->addDecorator(
						'Fieldset',
						array(
							'escape'=>FALSE,
						)
					)
				;
			}
		}
	}

	/**
	 * Adds a form element from a Doctrine_Relation present in the model
	 * attached to this form.
	 *
	 * @param Doctrine_Relation $relation
	 * @param $alias
	 * @param $searchOption
	 * @return L8M_ModelForm_Base
	 */
	protected function _addFormElementFromRelation($relation = NULL, $alias = NULL, $searchOption = NULL, $allAlias = array())
	{
		/* @var $relation Doctrine_Relation */
		$relationType = $relation->getType();

		/**
		 * retrieve relation class
		 */
		$relationClass = $relation->getClass();

		/**
		 * one-to-one relation of model media
		 */
		if ($relationType === Doctrine_Relation::ONE &&
			preg_match('/Default_Model_Media(.*)$/', $relationClass)) {

			/**
			 * create element name
			 */
			$formElementName = $this->_fieldPrefix
							 . strtolower($relation->getLocal())
			;

			/**
			 * media role
			 */
			$defaultMediaRole = NULL;
			if (is_array($this->_mediaRoleArray) &&
				array_key_exists($relation->getLocal(), $this->_mediaRoleArray) &&
				$this->_mediaRoleArray[$relation->getLocal()]) {

				$roleModel = Doctrine_Query::create()
					->from('Default_Model_Role m')
					->addWhere('m.short = ? ', array($this->_mediaRoleArray[$relation->getLocal()]))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($roleModel) {
					$defaultMediaRole = $roleModel;
				}
			}

			/**
			 * media directory
			 */
			$defaultMediaFolder = Default_Service_MediaFolder::getMediaFolderModelFromPath();
			if (is_array($this->_mediaDirectoryArray) &&
				array_key_exists($relation->getLocal(), $this->_mediaDirectoryArray) &&
				trim($this->_mediaDirectoryArray[$relation->getLocal()])) {

				$defaultMediaFolder = Default_Service_MediaFolder::getMediaFolderModelFromPath(trim($this->_mediaDirectoryArray[$relation->getLocal()]));
			}

			$columnNameModel = Doctrine_Query::create()
				->from('Default_Model_ModelColumnName m')
				->leftJoin('m.ModelName mn')
				->addWhere('m.name = ?', array($relation->getLocal()))
				->addWhere('mn.name = ?', array($this->_modelName))
				->limit(1)
				->execute()
				->getFirst()
			;

			if (class_exists('Default_Model_RememberMediaFolder', TRUE)) {
				$rememberMediaFolderModel = Doctrine_Query::create()
					->from('Default_Model_RememberMediaFolder m')
					->leftJoin('m.ModelColumnName mcn')
					->leftJoin('mcn.ModelName mn')
					->addWhere('m.entity_id = ?', array(Zend_Auth::getInstance()->getIdentity()->id))
					->addWhere('mcn.name = ?', array($relation->getLocal()))
					->addWhere('mn.name = ?', array($this->_modelName))
					->limit(1)
					->execute()
					->getFirst()
				;
				if ($rememberMediaFolderModel) {
					$defaultMediaFolder = Default_Model_MediaFolder::getModelByID($rememberMediaFolderModel->media_folder_id, 'Default_Model_MediaFolder');
				}
			}

			/**
			 * create media element
			 */
			$columnDefinition = $this->_table->getColumnDefinition($relation->getLocal());
			if (isset($columnDefinition['notnull']) &&
				$columnDefinition['notnull'] == TRUE) {

				$columnRequired = TRUE;
			} else {
				$columnRequired = FALSE;
			}
			$formMedia = new L8M_JQuery_Form_Element_Media($formElementName, substr($relationClass, strlen('Default_Model_Media')), $this->_modelName, $relation->getLocal(), $columnRequired, $defaultMediaFolder, $defaultMediaRole, $columnNameModel);

			/**
			 * set paramValue
			 */
			$paramValue = $this->_getParamValue($formElementName);

			if ($this->_editColumnID !== NULL) {

				/**
				 * retrieve row to edit
				 */
				$_editRow = $this->_table->findOneBy('id', $this->_editColumnID);

				if ($_editRow !== FALSE) {

					/**
					 * set edited or default
					 */
					$editValue = $_editRow->get($relation->getLocal());
					if ($paramValue == NULL) {
						$paramValue = $editValue;
					}
				}
			}

			/**
			 * set media ID
			 */
			$formMedia->setMediaID($paramValue);

			/**
			 * disable translator
			 */
			$formMedia->setDisableTranslator(TRUE);

			/**
			 * create form element label
			 */
			if (isset($this->_fieldLabels[$formElementName])) {
				$formMedia
					->setLabel($this->_view->translate($this->_fieldLabels[$formElementName], $this->_formLanguage))
				;
			} else {
				$formMedia
					->setLabel($this->_view->translate($this->_getLabelFromColumnName($formElementName), $this->_formLanguage))
				;
			}

			/**
			 * add element as unordered element
			 */
			$this->_addUnorderedFormElement($formElementName, $formMedia);

		} else

		/**
		 * one-to-one relation
		 */
		if ($relationType === Doctrine_Relation::ONE) {

			/**
			 * retrieve table
			 */
			$table = $relation->getTable();

			/**
			 * create element name
			 */
			$formElementName = $this->_fieldPrefix
							 . strtolower($relation->getLocal())
			;

			/**
			 * get column identifier
			 */
			$idColumn = $table->getIdentifier();

			/**
			 * get row name identifier
			 */
			$rowNameKeys = explode('_', $relation->getLocal());
			$rowNameKey = array_pop($rowNameKeys);

			/**
			 * set options
			 */
			$options = array(
				''=>'-',
			);

			/**
			 * set validator
			 */
			$optionsValidator = array();

			/**
			 * set paramValue
			 */
			$paramValue = $this->_getParamValue($formElementName);

			/**
			 * look for options to add
			 */
			if ($searchOption == NULL) {

				if ($this->_editColumnID !== NULL) {

					/**
					 * retrieve row to edit
					 */
					$_editRow = $this->_table->findOneBy('id', $this->_editColumnID);

					if ($_editRow !== FALSE) {

						/**
						 * set edited or default
						 */
						$editValue = $_editRow->get($relation->getLocal());
						if ($paramValue == NULL) {
							$paramValue = $editValue;
						}
					}
				}
				/**
				 * find all
				 */
				$rows = $this->_getRowsFromTable($table);
			} else {

				/**
				 * find iterativ row to edit default value or customer value
				 */
				if ($this->_editColumnID !== NULL) {

					/**
					 * retrieve row to edit
					 */
					$_editRow = $this->_table->findOneBy('id', $this->_editColumnID);

					if (count($allAlias) > 0 &&
						$alias != $allAlias[0]['alias'] &&
						$_editRow !== FALSE) {

						$oneElementName = $this->_fieldPrefix .
							$allAlias[0]['relation']->getLocal()
						;

						if ($this->_getParamValue($oneElementName) == NULL) {
							$_oneEditRow = $this->_table->findOneBy('id', $this->_editColumnID);

							/**
							 * is column existing
							 */
							if ($_oneEditRow !== FALSE) {
								$oneEditRelation = $_oneEditRow->get($allAlias[0]['alias']);
								$oneEditValue = $oneEditRelation->get('id');

								/**
								 * do we have something to work with
								 */
								if ($oneEditValue !== NULL) {
									/**
									 * find value
									 */
									for ($i = 0; $i < count($allAlias) - 1; $i++) {

										/**
										 * create one name
										 */
										$oneElementName = $this->_fieldPrefix .
											$allAlias[$i]['relation']->getLocal()
										;

										/**
										 * one table
										 */
										$oneTable = $allAlias[$i]['relation']->getTable();

										/**
										 * one row
										 */
										$_oneEditRow = $oneTable->findOneBy('id', $oneEditValue);

										/**
										 * one edit value
										 */
										$oneEditRelation = $_oneEditRow->get($allAlias[$i + 1]['alias']);
										$oneEditValue = $oneEditRelation->get('id');
									}
									$paramValue = $oneEditValue;
								}
							}
						}
					} else {

						/**
						 * is column existing
						 */
						if ($_editRow !== FALSE) {
							/**
							 * set edited or default
							 */
							$editValue = $_editRow->get($relation->getLocal());
							if ($paramValue == NULL) {
								$paramValue = $editValue;
							}
						}
					}
				}

				/**
				 * do we have a parent and nothing above?
				 */
				if (is_array($searchOption) &&
					isset($searchOption['parent']) &&
					isset($searchOption['parentRelation']) &&
					substr($searchOption['parentRelation']->getClass(), strlen('Default_Model_'), strlen('Media')) != 'Media') {

					/**
					 * related column
					 */
					$relatedColumn = $this->_getCamelCaseToUnderscore($searchOption['parent']);
					if ($table->hasColumn($relatedColumn)) {

						/**
						 * do nothing
						 */
					} else
					if ($table->hasColumn($relatedColumn . '_id')) {

						/**
						 * end with '_id'
						 */
						$relatedColumn = $relatedColumn . '_id';
					} else {
						throw new L8M_Exception('Related Column: "' . $relatedColumn . '" does not exist');
					}

					/**
					 * related value
					 */
					$relatedValue = $this->_getParamValueSelect($this->_fieldPrefix . $relatedColumn);

					/**
					 * do we edit column?
					 */
					if ($this->_editColumnID !== NULL) {
						if ($this->_getParamValue($this->_fieldPrefix . $relatedColumn) === NULL) {
							if (count($allAlias) == 1 &&
								$alias == $allAlias[0]['alias']) {

								$allAlias[1]['alias'] = $searchOption['parent'];
								$allAlias[1]['relation'] = $searchOption['parentRelation'];
							}

							$_oneEditRow = $this->_table->findOneBy('id', $this->_editColumnID);

							/**
							 * is column existing
							 */
							if ($_editRow !== FALSE) {
								$oneEditRelation = $_oneEditRow->get($allAlias[0]['alias']);
								$oneEditValue = $oneEditRelation->get('id');

								if ($oneEditValue !== NULL) {
									/**
									 * find value
									 */
									for ($i = 0; $i < count($allAlias) - 1; $i++) {

										/**
										 * create one name
										 */
										$oneElementName = $this->_fieldPrefix .
											$allAlias[$i]['relation']->getLocal()
										;

										/**
										 * one table
										 */
										$oneTable = $allAlias[$i]['relation']->getTable();

										/**
										 * one row
										 */
										$_oneEditRow = $oneTable->findOneBy('id', $oneEditValue);

										/**
										 * one edit value
										 */
										$oneEditRelation = $_oneEditRow->get($allAlias[$i + 1]['alias']);
										$oneEditValue = $oneEditRelation->get('id');
									}
									$relatedValue = $oneEditValue;
								}
							}
						}
					}

					/**
					 * find only related rows
					 */
					$rows = $this->_getRowsFromTable($table, $relatedColumn, $relatedValue);
				} else

				/**
				 * is element a child
				 */
				if (is_array($searchOption) &&
					isset($searchOption['child'])) {

					/**
					 * find all
					 */
					$rows = $this->_getRowsFromTable($table);
				} else {

					/**
					 * find all
					 */
					$rows = $this->_getRowsFromTable($table);
				}
			}

			/**
			 * add options
			 */
			/* @var $row Default_Model_Base_Abstract */
			foreach ($rows as $row) {

				/**
				 * do we have a translation relation
				 */
				if ($row->contains('Translation')) {
					$rowTranslation = $row->Translation[$this->_getLanguage()];
				} else {
					$rowTranslation = FALSE;
				}

				/**
				 * does row exist?
				 */
				if ($row->contains($rowNameKey)) {

					/**
					 * use row name key as value
					 */
					$optionValue = $row[$rowNameKey];
				} else {

					/**
					 * use id as value
					 */
					$optionValue = $row->$idColumn;
				}

				/**
				 * do we have a named column to work with?
				 */
				if (array_key_exists($relationClass, $this->_replaceColumnValuesInMultiRelation) &&
					$row->contains($this->_replaceColumnValuesInMultiRelation[$relationClass])) {

					$optionKey = $row[$this->_replaceColumnValuesInMultiRelation[$relationClass]];
				} else

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
				} else

				/**
				 * maybe a login we could work with
				 */
				if ($row->contains('login')) {

					$optionKey = $row['login'];
				} else {

					if ($rowTranslation !== FALSE) {

						/**
						 * so let's try with some translation
						 */

						/**
						 * do we have a named column to work with?
						 */
						if (array_key_exists($relationClass, $this->_replaceColumnValuesInMultiRelation) &&
							$rowTranslation->contains($this->_replaceColumnValuesInMultiRelation[$relationClass])) {

							$optionKey = $rowTranslation[$this->_replaceColumnValuesInMultiRelation[$relationClass]];
						} else

						/**
						 * do we have a name to work with
						 */
						if ($rowTranslation->contains('name') &&
							$rowTranslation['name'] != NULL) {

							$optionKey = $rowTranslation['name'];
						} else

						/**
						 * do we have a name to work with
						 */
						if ($rowTranslation->contains('short') &&
							$rowTranslation['short'] != NULL) {

							$optionKey = $rowTranslation['short'];
						} else

						/**
						 * do we have a name to work with
						 */
						if ($rowTranslation->contains('value') &&
							$rowTranslation['value'] != NULL) {

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
				 * some corrections
				 */
				if (trim($optionKey) == '') {
					$optionKey = $optionValue;
				}
				$optionKey = L8M_Library::decodeHTMLentities($optionKey);

				/**
				 * build option
				 */
				$options[$optionValue] = $optionKey;
				$optionsValidator[$optionValue] = $optionKey;
			}

			/**
			 * create element
			 */
			$childColumn = NULL;
			if ($searchOption == NULL) {

				$useParentFormElement = FALSE;
				$modelParentRelation = $this->_dummyModel->getParentRelation();
				$className = $table->getClassnameToReturn();
				if ($this->_dummyModel->getTable()->hasColumn('position') &&
					!in_array('position', $this->_ignoreColumns)) {

					$hasPositionColumn = TRUE;
				} else {
					$hasPositionColumn = FALSE;
				}
				if (
					(
						$hasPositionColumn
					) && (
						(
							$modelParentRelation &&
							$modelParentRelation->getClass() == $className &&
							$modelParentRelation->getLocalColumnName() == $relation->getLocalColumnName()
						) || (
							$this->_position['useParentRelation'] == $relation->getAlias()
						)
					)
				) {
					$useParentFormElement = TRUE;
				}

				if ($rows->count() > 19 ||
					$useParentFormElement) {

					$relationName = str_replace('Default_Model_', '', $className);

					$one2NmultiRelationCondition = array();
					if (isset($this->_multiRelationCondition[$relationName])) {
						$one2NmultiRelationCondition = $this->_multiRelationCondition[$relationName];
					}

					$one2NreplaceColumnValueInMultiRelation = NULL;
					if (array_key_exists($relationClass, $this->_replaceColumnValuesInMultiRelation)) {
						$one2NreplaceColumnValueInMultiRelation = $this->_replaceColumnValuesInMultiRelation[$relationClass];
					}

					if ($useParentFormElement) {
						$formElement = new L8M_JQuery_Form_Element_Parent($formElementName, $className, $one2NmultiRelationCondition, $one2NreplaceColumnValueInMultiRelation, $options, $paramValue, $rowNameKey, $idColumn);
					} else {
						$formElement = new L8M_JQuery_Form_Element_One2N($formElementName, $className, $one2NmultiRelationCondition, $one2NreplaceColumnValueInMultiRelation, $options, $paramValue, $rowNameKey, $idColumn);
					}
				} else {

					/* @var $formElement Zend_Form_Element_Select */
					$formElement = $this->createElement('select', $formElementName);
				}
			} else {
				/* @var $formElement L8M_JQuery_Form_Element_Select */
				$formElement = new L8M_JQuery_Form_Element_Select($formElementName);

				if (is_array($searchOption)) {

					/**
					 * ChildName
					 */
					$childAliasName = NULL;

					/**
					 * are we a parent? so we have one child
					 */
					if (isset($searchOption['child'])) {
						$childAliasName = $searchOption['child'];
					} else

					/**
					 * we are not a parent but we have a child to refresh
					 */
					if (isset($searchOption['refreshChild'])) {
						$childAliasName = $searchOption['refreshChild'];
					}

					if ($childAliasName !== NULL) {
						/**
						 * child column
						 */
						$childColumn = $this->_fieldPrefix
							. $this->_getCamelCaseToUnderscore($childAliasName)
							. '_id'
						;

						//$childColumn = strtolower($childColumn);

						/**
						 * child name
						 */
						$childName = L8M_Library::lcFirst($childAliasName);

						/**
						 * maybe later more auto-functionality
						 */
//						if ($table->hasRelation($childAliasName)) {
//							$tableRelation = $table->getRelation($childAliasName);
//							$foreignTableColumn = $tableRelation->getForeignColumnName();
//						} else {
							$foreignTableColumn = $table->getTableName() . '_id';
//						}

						$formElement
							->updateOnChange($childColumn, $this->_view->url(array('module'=>'system', 'controller'=>'auto-complete', 'action'=>'auto'), NULL, TRUE) . '/')
							->setKey($childName)
							->setColumn($foreignTableColumn)
						;
					}
				}
			}

			/**
			 * setup element
			 */
			/**
			 * retrieve definition
			 */
			$definition = $this->_table->getColumnDefinition($relation->getLocal());

			/**
			 * is required?
			 */
			if (isset($definition['notnull']) &&
				$definition['notnull'] == TRUE) {

				/**
				 * element is required
				 */
				$formElement
					->setRequired(TRUE)
				;
				if (!($formElement instanceof L8M_JQuery_Form_Element_One2N) &&
					!($formElement instanceof L8M_JQuery_Form_Element_Parent)) {

					$formElement
						->addValidator('InArray', false, array(array_keys($optionsValidator)))
					;
				}
			} else {
				$definition['notnull'] = FALSE;
			}

			if ($formElement instanceof L8M_JQuery_Form_Element_One2N ||
				$formElement instanceof L8M_JQuery_Form_Element_Parent) {

				$formElement
					->addValidator(new L8M_Validate_One2N($className, $one2NmultiRelationCondition, $rowNameKey, $idColumn, $definition['notnull']))
				;
			}

			/**
			 * disable translator
			 */
			$formElement->setDisableTranslator(TRUE);

			/**
			 * set up label
			 */
			$formElementLabel = $alias;
			if (isset($this->_fieldLabels[$formElementName])) {
				$formElementLabel = $this->_fieldLabels[$formElementName];
			}
			$formElement->setLabel($this->_view->translate($this->_getLabelFromColumnName($formElementLabel), $this->_formLanguage));

			/**
			 * add options and set value
			 */
			if (!($formElement instanceof L8M_JQuery_Form_Element_One2N) &&
				!($formElement instanceof L8M_JQuery_Form_Element_Parent)) {

				$formElement
					->setMultiOptions($options)
					->setValue($paramValue)
				;
			}

			/**
			 * add select to form
			 */
			$this->_addUnorderedFormElement($formElementName, $formElement, $childColumn);
		} else

		/**
		 * one-to-many, translations
		 */
		if ($relationType === Doctrine_Relation::MANY &&
			preg_match('/Translation$/', $relationClass)) {

			/**
			 * table with translations
			 */
			$table = Doctrine_Core::getTable($relationClass);

			/**
			 * get row to edit
			 */
			if ($this->_editColumnID !== NULL) {
				$_editRow = $table->findBy('id', $this->_editColumnID);
			}

			/**
			 * retrieve columns
			 */
			$translationColumns = $table->getColumns();

			/**
			 * go through columns
			 */
			foreach ($translationColumns as $name=>$definition) {

				if (!isset($definition['primary']) &&
				 	isset($this->_columnTypes[$definition['type']]) &&
					!in_array($name, $this->_ignoreColumns)) {

					/**
					 * we could edit or create this column form element
					 */
					/**
					 * form element name
					 */
					$formElementsName = $this->_fieldPrefix .
						$name
					;

					/**
					 * retrieve column type
					 */
					$type = $this->_getColumnToElementType($definition);

					/**
					 * is there another type for the column
					 */
					if (isset($this->_fieldTypes[$name])) {
						$type = $this->_fieldTypes[$name];
					}

					/**
					 * build up tab elements
					 */
					foreach ($this->_getSupportedLanguages() as $supportedLanguage) {

						/**
						 * tab element name
						 */
						$formElementName = $this->_fieldPrefix .
							'Translation__' .
							$supportedLanguage .
							'__' .
							$name
						;

						/**
						 * default values
						 */
						$paramValue = $this->_getParamValue($formElementName);
						if ($this->_editColumnID !== NULL) {
							$editValue = $_editRow->get($supportedLanguage);
							if ($paramValue == NULL) {
								$paramValue = $editValue->get($name);
							}
						}

						/**
						 * build one tab
						 */
						$elements[$supportedLanguage] = array(
							'elementName'=>$formElementName,
							'elementValue'=>$paramValue,
						);
					}

					/**
					 * is required?
					 */
					$multiTabIsRequired = FALSE;
					if (isset($definition['notnull']) &&
						$definition['notnull'] == TRUE) {

						$multiTabIsRequired = TRUE;
					}

					/**
					 * create multitab element
					 */
					$tinyMCEFormElementConfig = array();
					if (array_key_exists($name, $this->_tinyMCE)) {
						$tinyMCEFormElementConfig = $this->_tinyMCE[$name];
					}
					$formMultiContent = new L8M_JQuery_Form_Element_MultiTab($formElementsName, $multiTabIsRequired, $tinyMCEFormElementConfig);

					/**
					 * disable translator
					 */
					$formMultiContent->setDisableTranslator(TRUE);

					/**
					 * create form element label
					 */
					if (isset($this->_fieldLabels[$name])) {
						$formMultiContent
							->setLabel($this->_view->translate($this->_fieldLabels[$name], $this->_formLanguage))
						;
					} else {
						$formMultiContent
							->setLabel($this->_view->translate($this->_getLabelFromColumnName($name), $this->_formLanguage))
						;
					}

					/**
					 * set up multitab element
					 */
					$formMultiContent
						->setElementType($type)
						->setElements($elements)
					;

					/**
					 * has special length?
					 */
					if (isset($definition['length']) &&
						$definition['length']) {

						$formMultiContent
							->setAttribs(array('data-max-length'=>$definition['length']))
						;
					}

					/**
					 * save element name to form
					 */
					$this->_translatedFormElements[] = $formElementsName;

					/**
					 * add element as unordered element
					 */
					$this->_addUnorderedFormElement($formElementsName, $formMultiContent);
				}
			}
		}
		return $this;
	}

	/**
	 * Controlls retrieving and building of parent relations via recursion.
	 *
	 * @param Doctrine_Relation $relation
	 * @param $alias
	 */
	protected function _addFormElementFromRelationRecursiv($relation = NULL, $alias = NULL, $childAlias = NULL, $allAlias = array())
	{

		$allAlias[] = array(
			'relation'=>$relation,
			'alias'=>$alias,
		);

		/**
		 * searchOptions for building the select element
		 */
		if ($childAlias !== NULL) {
			$searchOptions = array(
				'child'=>$childAlias,
			);
		} else {
			$searchOptions = NULL;
		}

		/**
		 * retrieve relation
		 */
		$relations = Doctrine_Core::getTable($relation->getClass())->getRelations();
		$relationsArray = array();

		/**
		 * build relationArray
		 */
		foreach ($relations as $name => $definition) {

			if (in_array($definition->getLocal(), $this->_ignoreColumns) ||
				($this->_generateManyFields == false &&
					$definition->getType() == Doctrine_Relation::MANY)
				) {
				continue;
			}

			$relationsArray[$name] = $definition;
		}

		/**
		 * retrieve parent relation
		 */
		$parentRelation = $this->_addFormElementsFromRelations($relationsArray, TRUE, $alias);

		/**
		 * does a parent exist?
		 */
		if ($parentRelation !== FALSE &&
			is_array($parentRelation) &&
			$parentRelation['relation'] !== $relation &&
			$parentRelation['alias'] !== $alias) {

			/**
			 * fall into recursion
			 */
			$this->_addFormElementFromRelationRecursiv($parentRelation['relation'], $parentRelation['alias'], $alias, $allAlias);

			/**
			 * adding options
			 */
			$searchOptions = array(
				'parent'=>$parentRelation['alias'],
				'parentRelation'=>$parentRelation['relation'],
				'refreshChild'=>$childAlias,
			);
		}

		/**
		 * build element
		 */
		return $this->_addFormElementFromRelation($relation, $alias, $searchOptions, $allAlias);
	}

	/**
	 * Parse static form elements
	 */
	protected function _addStaticFormElements()
	{
		/**
		 * do we have the possibility to add some static form elements,
		 * 'cause somthing is in here?
		 */
		if (count($this->_staticFormElementsArray) > 0) {

			/**
			 * walk through array and add if possible
			 */
			foreach ($this->_staticFormElementsArray as $formElementName => $elementArray) {

				$possibleColumnName = $formElementName;
				$formElementName = $this->_fieldPrefix . $formElementName;

				/**
				 * do we have a element-type
				 */
				if (is_array($elementArray) &&
					isset($elementArray['type'])) {

					if ($elementArray['type'] == 'tinyMCE') {
						$tinyMCEFormElementConfig = array();
						if (array_key_exists($possibleColumnName, $this->_tinyMCE)) {
							$tinyMCEFormElementConfig = $this->_tinyMCE[$possibleColumnName];
						}
						$formElement = new L8M_JQuery_Form_Element_TinyMCE($formElementName, $tinyMCEFormElementConfig);
					} else {
						$formElement = $this->createElement($elementArray['type'], $formElementName);
					}

					/**
					 * set required
					 */
					if (isset($elementArray['required']) &&
						$elementArray['required'] == TRUE) {

						$formElement
							->setRequired(TRUE)
							->addValidator(new Zend_Validate_NotEmpty())
						;

						if ($formElement instanceof Zend_Form_Element_Checkbox) {

							/**
							 * add Validator
							 */
							$checkboxValidator = new Zend_Validate_InArray(array(TRUE));
							$checkboxValidator->setMessage('You have to verify that Checkbox.');
							$formElement
								->addValidator($checkboxValidator);
							;
						}
					}

					/**
					 * possible values
					 */
					if ($formElement instanceof Zend_Form_Element_Select &&
						isset($elementArray['values']) &&
						is_array($elementArray['values'])) {

						$formElement->setDisableTranslator(TRUE);
						foreach ($elementArray['values'] as $valuesKey=>$valuesValue) {
							$formElement->addMultiOption(
								$valuesKey,
								$valuesValue
							);
						}
					}

					/**
					 * retrive value
					 */
					$paramValue = $this->_getParamValue($formElementName);
					if ($this->_editColumnID !== NULL) {
						$_editRow = $this->_table->findOneBy('id', $this->_editColumnID);
						if ($_editRow !== FALSE) {
							$possibleColumns = $this->_table->getColumns();
							if (array_key_exists($possibleColumnName, $possibleColumns)) {
								$editValue = $_editRow->get($possibleColumnName);
								if ($paramValue == NULL) {
									if (in_array($possibleColumns[$possibleColumnName]['type'], $this->_numericColumnTypes)) {
										$editValue = L8M_Translate::numeric($editValue);
									}
									$paramValue = $editValue;
								}
							}
						}
					}
					if ($formElement instanceof Zend_Form_Element_Checkbox) {

						/**
						 * set checked
						 */
						if (isset($elementArray['checked']) &&
							$elementArray['checked'] == TRUE) {

							if ($paramValue !== NULL &&
								trim($paramValue) == '1') {

								$formElement
									->setChecked(TRUE)
								;
							} else

							if ($paramValue === NULL) {

								$formElement
									->setChecked(TRUE)
								;
							}
						} else

						if ($paramValue !== NULL &&
							trim($paramValue) == '1') {

							$formElement
								->setChecked(TRUE)
							;
						}
					} else {

						/**
						 * set default
						 */
						if (isset($elementArray['default'])) {

							if ($paramValue == NULL) {
								$paramValue = $elementArray['default'];
							}
						}
						$formElement->setValue($paramValue);
					}

					/**
					 * disable translator
					 */
					$formElement->setDisableTranslator(TRUE);

					/**
					 * set label
					 */
					if (isset($elementArray['label'])) {
						$formElement->setLabel($this->_view->translate($elementArray['label'], $this->_formLanguage));
					}

					if (isset($elementArray['description'])) {
						$formElement->setDescription($this->_view->translate($elementArray['description'], $this->_formLanguage));
					}

					/**
					 * set escape in decorators
					 */
					$decorators = $formElement->getDecorators();

					$descriptionDecorator = $formElement->getDecorator('Description');
					$descriptionDecorator->setOption('escape', FALSE);

					$labelDecorator = $formElement->getDecorator('Label');
					$labelDecorator->setOption('escape', FALSE);

					/**
					 * add element to unordered ones
					 */
					$this->_addUnorderedFormElement($formElementName, $formElement);

					/**
					 * save element name to unorderedFormElementsName
					 */
					$this->_unorderedStaticFormElementsName[] = $formElementName;
				}
			}
		}

	}

	/**
	 * Parses columns to fields
	 */
	protected function _addFormElementsFromColumns()
	{

		/**
		 * retrieve row to edit
		 */
		$_editRow = FALSE;
		if ($this->_editColumnID !== NULL) {
			$_editRow = $this->_table->findOneBy('id', $this->_editColumnID);
		}

		/**
		 * go through all columns
		 */
		foreach ($this->_getColumns() as $name => $definition) {

			if ($name == 'position' &&
				$definition['type'] == 'integer') {

				$this->_addFormElementFromPostion();
			} else {

				/**
				 * get type of column
				 */
				$type = $this->_getColumnToElementType($definition);

				/**
				 * is there another type for the column
				 */
				if (isset($this->_fieldTypes[$name])) {
					$type = $this->_fieldTypes[$name];
	 			}

	 			/**
	 			 * element name
	 			 */
	 			$formElementName = $this->_fieldPrefix
	 							 . $name
				;

				/**
				 * do not overwrite existing elements
				 */
				if (!isset($this->_unorderedFormElements[$formElementName])) {

					/**
					 * create form element
					 */
					if ($type == 'tinyMCE') {
						$tinyMCEFormElementConfig = array();
						if (array_key_exists($name, $this->_tinyMCE)) {
							$tinyMCEFormElementConfig = $this->_tinyMCE[$name];
						}
						$formElement = new L8M_JQuery_Form_Element_TinyMCE($formElementName, $tinyMCEFormElementConfig);
					} else
					if ($type == 'date') {
						$formElement = new L8M_JQuery_Form_Element_Date($formElementName);
					} else
					if ($type == 'datetime') {
						$formElement = new L8M_JQuery_Form_Element_Datetime($formElementName);
					} else {
						if ($name == 'password') {
							$formElement = new L8M_JQuery_Form_Element_Password($formElementName);
						} else {
							$formElement = $this->createElement($type, $formElementName);
						}
					}

					/**
					 * disable translator
					 */
					$formElement->setDisableTranslator(TRUE);

					/**
					 * create form element label
					 */
					if (isset($this->_fieldLabels[$name])) {
						$formElement->setLabel($this->_view->translate($this->_fieldLabels[$name], $this->_formLanguage));
					} else {
						$formElement->setLabel($this->_view->translate($this->_getLabelFromColumnName($name), $this->_formLanguage));
					}

					/**
					 * add validator
					 */
					if (isset($this->_columnValidators[$definition['type']])) {
						$formElement->addValidator($this->_columnValidators[$definition['type']]);
					}

					/**
					 * may add some style class for numeric
					 */
					if (in_array($definition['type'], $this->_numericColumnTypes)) {
						$formElement->setAttrib('class', 'isNumeric');
					}

					/**
					 * is required?
					 */
					if (isset($definition['notnull']) &&
						$definition['notnull'] == TRUE) {

						$formElement
							->setRequired(TRUE)
							->addValidator(new Zend_Validate_NotEmpty())
						;

						if (isset($definition['type']) &&
							$definition['type'] == 'boolean' &&
							(
								(isset($definition['default']) && $definition['default'] == NULL) ||
								!isset($definition['default'])
							)) {

							/**
							 * add Validator
							 */
							$checkboxValidator = new Zend_Validate_InArray(array(TRUE));
							$checkboxValidator->setMessage('You have to verify that Checkbox.');
							$formElement
								->addValidator($checkboxValidator);
							;
						}
					}

					/**
					 * char and varchar length validator
					 */
					if (isset($definition['type']) &&
						($definition['type'] == 'string' || $definition['type'] == 'varchar' || $definition['type'] == 'char')) {

						/**
						 * has special length?
						 */
						if (isset($definition['length']) &&
							$definition['length']) {

							$formElement
								->setAttrib('data-max-length', $definition['length'])
							;
						}

						if (($definition['type'] == 'string' || $definition['type'] == 'varchar') &&
							isset($definition['length'])) {

							$formElement
								->setValidators(array(
									new Zend_Validate_StringLength(array(
										'min'=>0,
										'max'=>$definition['length'],
									))
								))
							;
						} else
						if ($definition['type'] == 'char' &&
							isset($definition['length']) &&
							$definition['length'] > 0 &&
							isset($definition['notnull']) &&
							$definition['notnull'] == TRUE) {

							$formElement
								->setValidators(array(
									new Zend_Validate_StringLength(array(
										'min'=>$definition['length'],
										'max'=>$definition['length'],
									))
								))
							;
						}
					}

					/**
					 * set multioptions if is required
					 */
					if ($type == 'select' && $definition['type'] == 'enum') {
						foreach ($definition['values'] as $text) {
							$formElement->addMultiOption($text, ucwords($text));
						}
					}

					/**
					 * default values
					 * @todo check that
					 */
					if (!$this->isSubmitted($this->getMethod()) &&
						isset($definition['default'])) {

						if (isset($definition['type']) &&
							$definition['type'] == 'boolean' &&
							$definition['default'] == TRUE) {

							$formElement->setChecked(TRUE);
						} else {
							$formElement->setValue($definition['default']);
						}
					}

					/**
					 * database default values
					 */
					if ($this->_editColumnID !== NULL ||
						($this->_editColumnID !== NULL && $this->isSubmitted($this->getMethod()))) {

						$editValue = NULL;
						$paramValue = $this->_getParamValue($formElementName);
						if ($this->_editColumnID !== NULL &&
							$_editRow !== FALSE) {

							$editValue = $_editRow->get($name);
							if ($paramValue == NULL) {
								if (in_array($definition['type'], $this->_numericColumnTypes)) {
									$editValue = L8M_Translate::numeric($editValue);
								}
								$paramValue = $editValue;
							}
						}
						$formElement->setValue($paramValue);

						if ($formElement instanceof L8M_JQuery_Form_Element_Password) {
							$formElement->setDefaultValue($editValue);
						}
					}

					/**
					 * add element to unordered ones
					 */
					$this->_addUnorderedFormElement($formElementName, $formElement);
					$this->_addNumericElement($formElementName, $definition['type']);
				}
			}
		}
	}

	/**
	 * Parses relations to fields
	 *
	 * @param $relations
	 * @param $isInRecursion
	 */
	protected function _addFormElementsFromRelations($relations = NULL, $isInRecursion = FALSE, $parentAlias = NULL)
	{
		$relationsOneForRecursion = array();

		foreach ($relations as $alias=>$relation) {

			/* @var $relation Doctrine_Relation */
			$relationType = $relation->getType();

			if ($relationType === Doctrine_Relation::ONE) {
				if (!$isInRecursion) {

					/**
					 * add relation to recursion
					 */
					$ignore = FALSE;

					/**
					 * should relation combination be ignored?
					 * sometimes it's not set, that's why we need to set a helper var $ignore
					 */
					if (is_array($this->_ignoreColumnRelation) &&
						in_array($alias, $this->_ignoreColumnRelation)) {

						/**
						 * yes - ignore it
						 */
						$ignore = TRUE;
					}

					/**
					 * should we ignore relation combination
					 */
					if (!$ignore) {
						$this->_addFormElementFromRelationRecursiv($relation, $alias);
					}
				} else {

					/**
					 * add relation to recursion
					 */
					$ignore = FALSE;

					/**
					 * should relation combination be ignored?
					 * sometimes it's not set, that's why we need to set a helper var $ignore
					 */
					if (is_array($this->_ignoreColumnInMultiRelation) &&
						$parentAlias !== NULL &&
						array_key_exists($parentAlias, $this->_ignoreColumnInMultiRelation) &&
						is_array($this->_ignoreColumnInMultiRelation[$parentAlias]) &&
						in_array($alias, $this->_ignoreColumnInMultiRelation[$parentAlias])) {

						/**
						 * yes - ignore it
						 */
						$ignore = TRUE;
					} else

					/**
					 * is it a Media Relation, that should be ignored?
					 */
					if (substr($relation->getClass(), 0, strlen('Default_Model_Media')) !== FALSE) {

						/**
						 * yes - ignore it
						 */
						$ignore = TRUE;
					}

					/**
					 * should we ignore relation combination
					 */
					if (!$ignore) {
						$relationsOneForRecursion[] = array('relation'=>$relation,
															'alias'=>$alias);
					}
				}
			} else {
				if (!$isInRecursion &&
					$this->_isM2NRelation($relation)) {

					$this->_addFormElementFromM2NRelation($relation);
				} else
				if (!$isInRecursion) {
					$this->_addFormElementFromRelation($relation, $alias);
				}
			}
		}

		if ($isInRecursion) {
			if (count($relationsOneForRecursion) == 1) {

				return array_pop($relationsOneForRecursion);
			} else {
				return FALSE;
			}
		} else {
			return $this;
		}
	}

	/**
	 * checks whether relation is m2n or not
	 *
	 * @param Doctrine_Relation_ForeignKey $relation
	 * @return boolean
	 */
	protected function _isM2NRelation($relation)
	{
		$returnValue = FALSE;

		if (strpos(strtolower($relation->getForeignColumnName()), 'm2n')) {
			$returnValue = TRUE;
		} else

		if (strpos(strtolower($relation->getAlias()), 'm2n')) {
			$returnValue = TRUE;
		} else

		if (in_array($relation->getAlias(), $this->_m2nRelations)) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * adds an m2n relation form-element
	 *
	 * @param Doctrine_Relation_ForeignKey $relation
	 */
	protected function _addFormElementFromM2NRelation($relation)
	{
		$goOn = FALSE;
		if (is_array($this->_ignoreColumnRelation) &&
			!in_array($relation->getForeignColumnName(), $this->_ignoreColumnRelation)) {

			$goOn = TRUE;
		}

		if (is_array($this->_ignoredM2nRelations) &&
			in_array($relation->getAlias(), $this->_ignoredM2nRelations)) {

			$goOn = FALSE;
		}

		if ($goOn &&
			$this->_dummyModel instanceof Default_Model_Media &&
			substr($relation->getAlias(), 0, strlen('MediaM2n')) == 'MediaM2n') {

			$goOn = FALSE;
		}

		if ($goOn) {

			$formElementShort = 'relation_m2n_' . strtolower($relation->getAlias());
			$formElementName = $this->_fieldPrefix . $formElementShort;

			if (array_key_exists($formElementShort, $this->_fieldLabels)) {
				$formElementLabel = $this->_fieldLabels[$formElementShort];
			} else {
				$formElementLabel = $this->_getLabelFromColumnName($relation->getAlias());
			}

			if (!in_array($formElementName, $this->_m2nRelationFormElementInfos)) {
				$formElementM2N = new L8M_JQuery_Form_Element_M2N($formElementName, $relation, $this->_modelName, $this->_editColumnID, $this->_relationM2nValuesDefinition);
				$formElementM2N
					->setLabel($this->_view->translate($formElementLabel, $this->_formLanguage))
					->setDisableTranslator(TRUE)
				;
				$this->_addUnorderedFormElement($formElementName, $formElementM2N);
				$this->_m2nRelationFormElementInfos[$formElementShort] = array(
					'alias'=>$relation->getAlias(),
					'relation'=>$relation,
					'foreignAlias'=>$formElementM2N->getForeignAlias(),
					'foreignColumn'=>$formElementM2N->getForeignColumn(),
					'orderBy'=>$formElementM2N->getSortBy(),
					'isTranslateable'=>$formElementM2N->isTranslateable(),
					'translationColumn'=>$formElementM2N->getTranslationColumn(),
					'fieldPrefix'=>$this->_fieldPrefix,
				);
			}
		}
	}

	public function getM2NRelationFormInfos()
	{
		return $this->_m2nRelationFormElementInfos;
	}

	/**
	 * adds a position form-element
	 *
	 * @param Doctrine_Relation_ForeignKey $relation
	 */
	protected function _addFormElementFromPostion()
	{
		if (is_array($this->_ignoreColumns) &&
			!in_array('position', $this->_ignoreColumns)) {

			$formElementShort = 'position_pos';
			$formElementName = $this->_fieldPrefix . $formElementShort;

			if (array_key_exists($formElementShort, $this->_fieldLabels)) {
				$formElementLabel = $this->_fieldLabels[$formElementShort];
			} else {
				$formElementLabel = $this->_getLabelFromColumnName('position');
			}

			if (!in_array($formElementName, $this->_positionFormElementInfos)) {
				$formElementPosition = new L8M_JQuery_Form_Element_Position($formElementName, $this->_modelName, $this->_editColumnID, $this->_position['useParentRelation']);
				$formElementPosition
					->setLabel($this->_view->translate($formElementLabel, $this->_formLanguage))
					->setDisableTranslator(TRUE)
				;
				$this->_addUnorderedFormElement($formElementName, $formElementPosition);
				$this->_positionFormElementInfos[$formElementShort] = array(
					'fieldPrefix'=>$this->_fieldPrefix,
				);
			}
		}
	}

	public function getPositionFormInfos()
	{
		return $this->_positionFormElementInfos;
	}

	protected function _addNumericElement($elementName, $type)
	{
		if (in_array($type, $this->_numericColumnTypes)) {
			$this->_numericElements[] = $elementName;
		}
	}

	/**
	 * adds all form elements in a ordered way
	 *
	 * @return void
	 */
	protected function _addOrderedFormElements()
	{
		/**
		 * ordered elements empty add start
		 */
		$ordElements = array();

		/**
		 * order elements as given in order of form elements in array
		 */
		foreach ($this->_orderOfFormElements as $name) {

			$name = $this->_fieldPrefix . $name;
			/**
			 * does element exist and is not added yet
			 */
			if (isset($this->_unorderedFormElements[$name]) &&
				$this->_unorderedFormElements[$name]['added'] == FALSE) {

				/**
				 * add parent elements
				 */
				$ordParentElements = $this->_getOrderedFormElementsOfChild($name);
				$ordElements = array_merge($ordElements, $ordParentElements);

				/**
				 * add element
				 */
				$ordElements[] = $this->_unorderedFormElements[$name]['element'];
				$this->_unorderedFormElements[$name]['added'] = TRUE;
			}
		}

		/**
		 * add all unordered elments to array
		 */
		foreach ($this->_unorderedFormElements as $name=>$formItem) {

			/**
			 * does element exist and is not added yet
			 */
			if ($formItem['added'] == FALSE) {

				/**
				 * add parent elements
				 */
				$ordParentElements = $this->_getOrderedFormElementsOfChild($name);
				$ordElements = array_merge($ordElements, $ordParentElements);

				/**
				 * add element
				 */
				$ordElements[] = $formItem['element'];
				$this->_unorderedFormElements[$name]['added'] = TRUE;
			}
		}

		/**
		 * add all ordered elements to form
		 */
		/**
		 * @var Zend_Form_Element
		 */
		foreach ($ordElements as $orderedFormElement) {

			if (in_array($orderedFormElement->getName(), $this->_unorderedStaticFormElementsName) ||
				in_array($orderedFormElement->getName(), $this->_doNotEscapeLabel)) {

				$labelDecoratorOptions = array(
					'escape'=>FALSE,
				);
			} else {
				$labelDecoratorOptions = array();
			}

			/**
			 * add modelFormDecoratorDecorator ;o)
			 */
			if ($this->_addModelFormDecorator) {

				$labelDecoratorOptions['tag'] = 'div';
				$labelDecoratorOptions['class'] = 'modelFormLabel';

				$orderedFormElement
					->addDecorator(
						array(
							'modelFormDecorator' => 'HtmlTag',
						),
						array(
							'tag' => 'div',
							'class'=>'modelForm',
						)
					)
					->addDecorator(
						'HtmlTag',
						array(
							'tag'=>'div',
							'class'=>'modelFormElement',
						)
					)
					->addDecorator(
						'Label',
						$labelDecoratorOptions
					)
				;
			} else {
				if (count($labelDecoratorOptions) > 0) {
					$orderedFormElement
						->addDecorator(
							'Label',
							$labelDecoratorOptions
						)
					;
				}
			}

			/**
			 * add to known form elements
			 */
			$this->_formElementNames[] = $orderedFormElement->getName();

			/**
			 * add to form
			 */
			$this->addElement($orderedFormElement);
		}
	}

	/**
	 * returns an ordered array of parent elements to a named child element
	 *
	 * @param string $name
	 * @return array
	 */
	protected function _getOrderedFormElementsOfChild($name) {
		if (isset($this->_unorderedFormElementsOfChild[$name]) &&
			$this->_unorderedFormElementsOfChild[$name]['added'] == FALSE) {

			$parentOrderedElements = $this->_getOrderedFormElementsOfChild($this->_unorderedFormElementsOfChild[$name]['name']);
			$this->_unorderedFormElementsOfChild[$name]['added'] = TRUE;
			return array_merge($parentOrderedElements, array($this->_unorderedFormElementsOfChild[$name]['element']));
		}
		return array();
	}

	/**
	 * Adds elements to an array
	 *
	 * @param $name
	 * @param $element
	 * @param $childOf
	 * @return void
	 */
	protected function _addUnorderedFormElement($name = NULL, $element = NULL, $childOf = NULL)
	{
		if ($childOf == NULL) {
			$this->_unorderedFormElements[$name] = array(
				'element'=>$element,
				'added'=>FALSE,
			);
		} else {
			$this->_unorderedFormElementsOfChild[$childOf] = array(
				'element'=>$element,
				'added'=>FALSE,
				'name'=>$name,
			);
		}
	}

	/**
	 * Returns L8M_ModelForm_Base.
	 *
	 * @param $model
	 * @param $formOptions
	 * @param $options
	 * @return L8M_ModelForm_Base
	 */
	public static function create($model = NULL, $formOptions = NULL, $options = NULL)
	{

		return new L8M_ModelForm_Base($options, $model, $formOptions);
	}

	/**
	 * Override to provide custom pre-form generation logic
	 */
	protected function _preGenerate()
	{
	}

	/**
	 * Override to provide custom post-form generation logic
	 */
	protected function _postGenerate()
	{
	}

	/**
	 * Override to provide custom post-save logic
	 */
	protected function _postSave($persist)
	{
	}

	/**
	 * Set the model instance for editing existing rows
	 * @param Doctrine_Record $instance
	 */
	public function setRecord($instance)
	{
		$this->_instance = $instance;
		foreach ($this->_getColumns() as $name => $definition)
		{
			$this->setDefault($this->_fieldPrefix . $name, $this->_instance->$name);
		}

		foreach ($this->_getRelations() as $name => $relation)
		{
			switch($relation->getType())
			{
			case Doctrine_Relation::ONE:
				$idColumn = $relation->getTable()->getIdentifier();
				$this->setDefault($this->_fieldPrefix . $relation->getLocal(), $this->_instance->$name->$idColumn);
				break;
			case Doctrine_Relation::MANY:
				$formClass = $this->_relationForms[$relation->getClass()];
				foreach ($this->_instance->$name as $num => $rec)
				{
					$form = new $formClass;
					$form->setRecord($rec);
					$form->setIsArray(true);
					$form->removeDecorator('Form');
					$form->addElement('submit', $this->_getDeleteButtonName($name, $rec), array(
						'label' => 'Delete'
					));
					$label = $relation->getClass();
					if (isset($this->_relationLabels[$relation->getClass()]))
						$label = $this->_relationLabels[$relation->getClass()];

					$form->setLegend($label . ' ' . ($num + 1))
						 ->addDecorator('Fieldset');
					$this->addSubForm($form, $this->_getFormName($name, $rec));
				}
				break;
			}
		}
	}

	/**
	 * Retrieve all form element values
	 *
	 * @param  bool $suppressArrayNotation
	 * @return array
	 */
	public function getValues($suppressArrayNotation = false)
	{
		$values = array();
		foreach ($this->_formElementNames as $key) {
			$element = $this->getElement($key);
			if (!$element->getIgnore()) {

				if (strpos($key, $this->_fieldPrefix) === FALSE) {
					$name = $key;
				} else {
					$name = substr($key, strlen($this->_fieldPrefix));
				}

				if (in_array($key, $this->_translatedFormElements)) {
					foreach ($this->_getSupportedLanguages() as $supportedLanguage) {
						$formElementName = 'Translation__' .
							$supportedLanguage .
							'__' .
							$name
						;
						$values['Translation'][$supportedLanguage][$name] = $this->_getValue($element, stripslashes($this->_getParamValue($this->_fieldPrefix . $formElementName)));
					}
				} else
				if (array_key_exists($name, $this->_m2nRelationFormElementInfos)) {
					$values[$name] = array();
					$m2nElementValues = $element->getValue();
					if ($m2nElementValues !== NULL &&
						is_array($m2nElementValues)) {

						foreach ($m2nElementValues as $m2nValue) {
							$values[$name][] = $this->_getValue($element, stripslashes($m2nValue));
						}
					}
				} else
				if (array_key_exists($name, $this->_positionFormElementInfos)) {
					$values[$name] = $this->_getValue($element, stripslashes($element->getValue()));
				} else {
					$values[$name] = $this->_getValue($element, stripslashes($element->getValue()));
				}
			}
		}

		if (!$suppressArrayNotation && $this->isArray()) {
			$values = $this->_attachToArray($values, $this->getElementsBelongTo());
		}

		if ($this->_editColumnID !== NULL) {
			//$values['id'] = $this->_editColumnID;
		}

		return $values;
	}

	/**
	 * retrieve model instance
	 *
	 * @return Doctrine_Model
	 */
	public function getRecord()
	{
		if ($this->_instance === NULL) {
			$this->_instance = new $this->_modelName;
		}
		return $this->_instance;
	}

	/**
	 * Returns the Label of a column.
	 *
	 * @param string $columnName
	 * @return string
	 */
	protected function _getLabelFromColumnName($columnName)
	{
		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_UnderscoreToSeparator())
			->addFilter(new Zend_Filter_Word_CamelCaseToSeparator())
			->addFilter(new Zend_Filter_Word_DashToSeparator())
		;

		return $filter->filter(ucfirst(trim($columnName)));
	}

	/**
	 * Returns the Label of a column.
	 *
	 * @param string $columnName
	 * @return string
	 */
	protected function _getCamelCaseToUnderscore($relation)
	{
		$filter = new Zend_Filter();
		$filter
			->addFilter(new Zend_Filter_Word_CamelCaseToUnderscore())
		;

		return strtolower($filter->filter($relation));
	}

	/**
	 * Returns the name of the new button field for relation alias
	 * @param string $relationAlias alias of the relation
	 * @return string name of the new button
	 */
	protected function _getNewButtonName($relationAlias)
	{
		return $relationAlias . '_new_button';
	}

	/**
	 * Returns the name of the delete button field for relation alias
	 * @param string $relationAlias alias of the relation
	 * @param Doctrine_Record $record if deleting existing records
	 * @return string name of the new button
	 */
	protected function _getDeleteButtonName($relationAlias, Doctrine_Record $record = NULL)
	{
		$val = 'new';
		$idColumn = $record->getTable()->getIdentifier();
		if ($record != NULL)
			$val = $record->$idColumn;

		return $relationAlias . '_' . $val . '_delete';
	}

	/**
	 * Returns the new form name for relation alias
	 * @param string $relationAlias alias of the relation
	 * @param Doctrine_Record $record if editing existing records
	 * @return string name of the new form
	 */
	protected function _getFormName($relationAlias, Doctrine_Record $record = NULL)
	{
		$returnValue = $relationAlias . '_new_form';

		if ($record != NULL) {
			$idColumn = $record->getTable()->getIdentifier();
			$returnValue = $relationAlias . '_' . $record->$idColumn;
		}

		return $returnValue;
	}

	/**
	 * Correct form value types for Doctrine
	 *
	 * @param  string $value value
	 * @param  string $type column type
	 * @return mixed
	 */
	protected function _doctrineizeValue($value, $type)
	{
		switch($type)
		{
			case 'boolean':
				return (boolean)$value;
				break;
			default:
				return $value;
				break;
		}
		trigger_error('This line should never run', E_USER_ERROR);
	}

	/**
	 * Generates the form
	 */
	protected function _generateForm()
	{
		/**
		 * set atributes
		 */
		$this
			->setAttrib('id', $this->_fieldPrefix . 'form')
		;

		/**
		 * generate modelForm
		 */
		$this->_addFormElementsFromRelations($this->_getRelations());
		$this->_addFormElementsFromColumns();
		$this->_addStaticFormElements();
		$this->_addOrderedFormElements();
		$this->_addDisplayGroups();

		/**
		 * set identifier element
		 */
		if ($this->_editColumnID) {
			$formElementHidden = new Zend_Form_Element_Hidden('l8m_model_form_base_element_identitfier');
			$formElementHidden
				->setValue(L8M_ModelForm_MarkedForEditor::getIdentifier($this->_modelName, $this->_editColumnID, TRUE))
				->setRequired(TRUE)
				->addValidator(new L8M_Validate_ModelFormIdentifier($this->_modelName, $this->_editColumnID))
				->setDecorators(array(
					new Zend_Form_Decorator_ViewHelper(),
					new Zend_Form_Decorator_HtmlTag(array(
						'tag'=>'div',
						'style'=>'display:none;',
						'class'=>'l8m_model_form_base_element_identitfier',
						'data-model'=>$this->_modelName,
						'data-id'=>$this->_editColumnID,
					)),
				))
			;
			$this->addElement($formElementHidden);
		}

		/**
		 * Get back to form after save
		 */
		if ($this->_showBackAfterSave) {
			$formElementBackAfterSave = new Zend_Form_Element_Checkbox('l8m_system_back_after_save');
			$formElementBackAfterSave
				->setLabel('Redirect back to that form after saveing.');
			;
			$this->addElement($formElementBackAfterSave);
		}

		/**
		 * set cutom button label if possible
		 */
		if ($this->_buttonLabel !== NULL) {
			$buttonLabel = $this->_buttonLabel;
		} else {
			$buttonLabel = 'Save ' . ucfirst($this->_table->getTableName());
		}

		/**
		 * formSubmitButton
		 */
		if ($this->_isInDebug) {

			/**
			 * add debug-submit
			 */
			$formSubmitButton = new Zend_Form_Element_Submit($this->_fieldPrefix . 'submit');
			$formSubmitButton
				->setDisableTranslator(TRUE)
				->setLabel($this->_view->translate($buttonLabel . ' (Debug-Mode)', $this->_formLanguage))
			;
		} else {

			/**
			 * add normal jquery-submit
			 */
			$formSubmitButton = new L8M_JQuery_Form_Element_Submit($this->_fieldPrefix . 'submit');
			$formSubmitButton
				->setParentForm($this)
				->setDisableTranslator(TRUE)
				->setLabel($this->_view->translate($buttonLabel, $this->_formLanguage))
			;
		}
		$formSubmitButton
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
		;
		$this->addElement($formSubmitButton);
	}

	/**
	 * Get unignored columns
	 * @return array
	 */
	protected function _getColumns()
	{
		$columns = array();

		foreach ($this->_table->getColumns() as $name => $definition) {
			if ((isset($definition['primary']) &&
				$definition['primary']) ||
				!isset($this->_columnTypes[$definition['type']]) ||
				in_array($name, $this->_ignoreColumns)) {
				continue;
			}
			$columns[$name] = $definition;
		}

		return $columns;
	}

	/**
	 * parse ColumnType to ElementType
	 *
	 * @param array $columnType
	 * @return string
	 */
	protected function _getColumnToElementType($columnDefinition)
	{
		if (isset($columnDefinition['type'])) {
			$type = $this->_columnTypes[$columnDefinition['type']];
			if ($columnDefinition['type'] == 'string' ||
				$columnDefinition['type'] == 'clob') {
				$type = 'tinyMCE';
				if (isset($columnDefinition['length'])) {
					if ($columnDefinition['length'] <= 128) {
						$type = 'text';
					} else
					if ($columnDefinition['length'] <= 255) {
						$type = 'textarea';
					}
				}
			}
		} else {
			throw new Exception('No column-definition defined.');
		}

		return $type;
	}

	/**
	 * Retrieves rows from table with relation for multi relations
	 * @param Doctrine_Table $table
	 */
	protected function _getRowsFromTable($table, $column = NULL, $value = NULL) {
		$className = $table->getClassnameToReturn();
		$relationName = str_replace('Default_Model_', '', $className);

		$rowsCollection = FALSE;

		$modelQuery = Doctrine_Query::create()
			->from($className . ' m')
		;
		if (isset($this->_relationColumnInMultiRelation[$relationName])) {

			foreach ($this->_relationColumnInMultiRelation[$relationName] as $key => $value) {
				if ($value === NULL) {
					$modelQuery = $modelQuery->addWhere('m.' .  $key . ' IS NULL', array());
				} else {
					$modelQuery = $modelQuery->addWhere('m.' .  $key . ' = ? ', array($key));
				}
			}
		} else {

			/**
			 * retrieve standard - translation is inside, so we do not need to filter with lang
			 */
			if ($column !== NULL) {

				$modelQuery = $modelQuery->addWhere('m.' . $column . ' = ? ', array($value));
			}
		}

		if (isset($this->_multiRelationCondition[$relationName])) {
			foreach ($this->_multiRelationCondition[$relationName] as $key => $value) {
				if ($value === NULL) {
					$modelQuery = $modelQuery->addWhere('m.' .  $key . ' IS NULL', array());
				} else
				if (is_array($value) &&
					array_key_exists('like', $value) &&
					$value['like'] &&
					array_key_exists('value', $value)) {

					$modelQuery = $modelQuery->addWhere('m.' .  $key . ' LIKE ? ', array($value['value']));
				} else
				if (is_array($value) &&
					array_key_exists('like', $value) &&
					!$value['like'] &&
					array_key_exists('value', $value)) {

					$modelQuery = $modelQuery->addWhere('m.' .  $key . ' NOT LIKE ? ', array($value['value']));
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'lt' &&
					array_key_exists('value', $value)) {

					$modelQuery = $modelQuery->addWhere('m.' .  $key . ' < ? ', array($value['value']));
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'lte' &&
					array_key_exists('value', $value)) {

					$modelQuery = $modelQuery->addWhere('m.' .  $key . ' <= ? ', array($value['value']));
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'gt' &&
					array_key_exists('value', $value)) {

					$modelQuery = $modelQuery->addWhere('m.' .  $key . ' > ? ', array($value['value']));
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'gte' &&
					array_key_exists('value', $value)) {

					$modelQuery = $modelQuery->addWhere('m.' .  $key . ' >= ? ', array($value['value']));
				} else {
					$modelQuery = $modelQuery->addWhere('m.' .  $key . ' = ? ', array($value));
				}
			}
		}

		$columnDefinitions = $table->getColumns();
		$orderBy = NULL;
		if (isset($this->_replaceColumnValuesInMultiRelation[$className]) &&
			array_key_exists($this->_replaceColumnValuesInMultiRelation[$className], $columnDefinitions)) {

			$orderBy = $this->_replaceColumnValuesInMultiRelation[$className];
		} else
		if (array_key_exists('name', $columnDefinitions)) {
			$orderBy = 'name';
		} else
		if (array_key_exists('short', $columnDefinitions)) {
			$orderBy = 'short';
		} else
		if (array_key_exists('value', $columnDefinitions)) {
			$orderBy = 'value';
		} else
		if (array_key_exists('login', $columnDefinitions)) {
			$orderBy = 'login';
		} else
		if (array_key_exists('id', $columnDefinitions)) {
			$orderBy = 'id';
		}

		if ($orderBy) {
			$modelQuery = $modelQuery
				->orderBy('m.' . $orderBy . ' ASC')
			;
		}

		$rowsCollection = $modelQuery
			->limit(75)
			->execute()
		;

		return $rowsCollection;
	}

	/**
	 * retrieve language from registry
	 */
	protected function _getLanguage()
	{
		/**
		 * language
		 */
		$language = Zend_Registry::isRegistered('Zend_Locale')
				  ? Zend_Registry::get('Zend_Locale')->getLanguage()
				  : NULL
		;
		return $language;
	}

	/**
	 * Retrieves the Value of a param post with the form.
	 *
	 * @param $paramName
	 */
	protected function _getParamValue($paramName)
	{
		return Zend_Controller_Front::getInstance()->getRequest()->getParam($paramName);
	}

	/**
	 * Retrieves the Value for a column to be used
	 *
	 * @param $element Zend_Form_Element
	 * @param $value String
	 *
	 * @return String
	 */
	protected function _getValue($element, $value) {
		/**
		 * make doctrinable
		 */
		if (in_array($element->getId(), $this->_numericElements)) {
			$value = L8M_Translate::numeric($value, L8M_Locale::getLang(), L8M_Locale::getDefaultSystem()); //str_replace(',', '.', $value);
		}

		/**
		 * maybe another return value if empty
		 */
		switch ($element->getType()) {
			case 'Zend_Form_Element_Text':
				if (in_array($element->getId(), $this->_numericElements)) {
					if (trim($value) === '') {
						return NULL;
					} else {
						return $value;
					}
				} else {
					if (trim($value) == '') {
						return NULL;
					} else {
						return $value;
					}
				}
				break;
			case 'L8M_JQuery_Form_Element_Select':
				if (trim($value) == '') {
					return NULL;
				} else {
					return $value;
				}
				break;
			case 'Zend_Form_Element_Select':
				if (trim($value) == '') {
					return NULL;
				} else {
					return $value;
				}
				break;
			case 'Zend_Form_Element_Checkbox':
				if ((boolean) $value == TRUE) {
					return TRUE;
				} else {
					return FALSE;
				}
				break;
			case 'L8M_JQuery_Form_Element_MultiTab':
				if (trim($value) == '<html />') {
					$value = NULL;
				}
				if (trim($value) == '') {
					return NULL;
				} else {
					return $value;
				}
				break;
			case 'L8M_JQuery_Form_Element_TinyMCE':
				if (trim($value) == '<html />') {
					$value = NULL;
				}
				if (trim($value) == '') {
					return NULL;
				} else {
					return $value;
				}
				break;
		}

		if (trim($value) == '') {
			return NULL;
		} else {
			return $value;
		}
	}

	/**
	 * Retrieves the Value of a select param post with the form,
	 * returning -1 if it is NULL.
	 *
	 * @param $paramName
	 */
	protected function _getParamValueSelect($paramName) {
		$value = Zend_Controller_Front::getInstance()->getRequest()->getParam($paramName);
		if ($value === NULL) {
			$value = '-1';
		}
		return $value;
	}

	/**
	 * Returns all un-ignored relations
	 * @return array
	 */
	protected function _getRelations()
	{
		$relations = array();

		foreach ($this->_table->getRelations() as $name => $definition) {

			if (in_array($definition->getLocal(), $this->_ignoreColumns) ||
				($this->_generateManyFields == false &&
				 $definition->getType() == Doctrine_Relation::MANY)) {
				continue;
			 }

			$relations[$name] = $definition;
		}

		return $relations;
	}

	/**
	 * Save the form data
	 * @param bool $persist Save to DB or not
	 * @return Doctrine_Record
	 */
	public function save($persist = true)
	{
		$inst = $this->getRecord();

		$inst->merge($this->getValues());

		if ($persist) {
			$inst->save();
		}

		$this->_postSave($persist);

		return $inst;
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Returns an array of supported languages.
	 *
	 * @return array
	 */
	protected function _getSupportedLanguages()
	{
		if (self::$_supportedLanguages === NULL) {
			$withBackendLang = FALSE;
			if (in_array(L8M_Acl_CalledFor::resource(), L8M_Config::getOption('locale.backend.allowMultiTabResource'))) {
				$withBackendLang = TRUE;
			}
			self::$_supportedLanguages = L8M_Locale::getSupported($withBackendLang);
		}
		return self::$_supportedLanguages;
	}
}
