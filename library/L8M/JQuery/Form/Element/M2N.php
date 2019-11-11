<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/M2N.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id:M2N.php 271 2012-02-27 13:56:59Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_M2N
 *
 *
 */
class L8M_JQuery_Form_Element_M2N extends Zend_Form_Element_Xhtml
{

 	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * contains relation
	 *
	 * @var Doctrine_Relation_ForeignKey
	 */
	protected $_relation = NULL;

	/**
	 * flags if relation is possible self-reflecting
	 *
	 * @var boolean
	 */
	protected $_isSelfRefelecting = FALSE;

	/**
	 * contains relation
	 *
	 * @var Doctrine_Relation_ForeignKey
	 */
	protected $_foreignRelation = NULL;

	/**
	 * contains relation
	 *
	 * @var Doctrine_Relation_ForeignKey
	 */
	protected $_m2nTranslation = NULL;

	/**
	 * contains relation column for input
	 *
	 * @var string
	 */
	protected $_translationColumn = NULL;

	/**
	 * contains m2n relations
	 *
	 * @var array of Doctrine_Relation_ForeignKey
	 */
	protected $_m2nRelations = array();

	/**
	 * contains relation m infos
	 *
	 * @var array
	 */
	protected $_relationM = NULL;

	/**
	 * contains relation n infos
	 *
	 * @var array
	 */
	protected $_relationN = NULL;

	/**
	 * contains relation wich is refering to 2Y relation
	 *
	 * @var array
	 */
	protected $_relation2y = NULL;

	/**
	 * contains relation values infos
	 *
	 * @var array
	 */
	protected $_relationValues = NULL;

	/**
	 * sortable
	 *
	 * @var boolean
	 */
	protected $_sortable = FALSE;

	/**
	 * has an extra Value
	 *
	 * @var boolean
	 */
	protected $_hasExtraValue = FALSE;

	/**
	 * sortable by relation
	 *
	 * @var boolean
	 */
	protected $_sortableByRelation = FALSE;

	/**
	 * contains model ID
	 *
	 * @var integer
	 */
	protected $_modelID = NULL;

	/**
	 * contains local class name
	 *
	 * @var string
	 */
	protected $_localClassName = NULL;

	/**
	 * contains foreign class name
	 *
	 * @var string
	 */
	protected $_foreignClassName = NULL;

	/**
	 * contains foreign column name
	 *
	 * @var string
	 */
	protected $_foreignColumn = NULL;

	/**
	 * contains foreign column to show
	 *
	 * @var string
	 */
	protected $_useForeignColumn = NULL;

	/**
	 * contains collection of values
	 *
	 * @var Doctrine_Collection
	 */
	protected $_foreignValuesCollection = NULL;

	/**
	 * contains count of collection values
	 *
	 * @var integer
	 */
	protected $_foreignValuesCount = 0;

	/**
	 * flags, whether multiple rows in relation m2n values are allowed or not
	 * @var boolean
	 */
	protected $_allowMultipleRelationM2nValuesRows = NULL;

	/**
	 * contains column labels for columns in relation m2n values
	 * @var array
	 */
	protected $_relationM2nValuesColumnLabels = array();

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

	public function getValues()
	{
		$returnValue = NULL;

		return $returnValue;
	}

	public function getTranslationColumn()
	{
		return $this->_translationColumn;
	}

	public function isTranslateable()
	{
		$returnValue = FALSE;

		if ($this->_m2nTranslation) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	public function hasRelation2y()
	{
		$returnValue = FALSE;

		if ($this->_relation2y) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	public function getRelation2yModelName()
	{
		$returnValue = NULL;

		if ($this->_relation2y) {
			$returnValue = $this->_relation2y['class'];
		}

		return $returnValue;
	}

	public function hasRealtionValues()
	{
		$returnValue = FALSE;

		if ($this->_relationValues) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	public function hasMultipleRelationM2nValuesRows()
	{
		return $this->_allowMultipleRelationM2nValuesRows;
	}

	public function getRealtionValueColumns()
	{
		$returnValue = array();

		if ($this->hasRealtionValues()) {
			$foreignColumnName = $this->_relationValues['relation']->getForeignColumnName();
			foreach ($this->_relationValues['relation']->getTable()->getColumns() as $columnName => $columnDefinition) {
				if ($columnName != 'id' &&
					$columnName != $this->_relationValues['relation']->getForeignColumnName() &&
					$columnName != 'created_at' &&
					$columnName != 'updated_at' &&
					$columnName != 'deleted_at') {

					if ($this->hasRelation2y()) {
						if ($columnName != 'referenced_id') {
							$returnValue[$columnName] = $columnDefinition;
						}
					} else {
						$returnValue[$columnName] = $columnDefinition;
					}
				}
			}
		}

		return $returnValue;
	}

	public static function prepareRelationValueInput($relationColumnValue, $relationColumnDefinition)
	{
		$relationColumnValue = strip_tags($relationColumnValue);
		$relationColumnValue = str_replace('"', '\'', $relationColumnValue);
		if (isset($relationColumnDefinition['type'])) {
			if ($relationColumnDefinition['type'] == 'integer' ||
				$relationColumnDefinition['type'] == 'decimal' ||
				$relationColumnDefinition['type'] == 'float' ||
				$relationColumnDefinition['type'] == 'double') {

				$relationColumnValue = L8M_Translate::numeric($relationColumnValue, L8M_Locale::getLang(), L8M_Locale::getDefaultSystem());
			}
		}
		return $relationColumnValue;
	}

	public static function prepareRelationValueOutput($relationColumnValue, $relationColumnDefinition)
	{
		if (isset($relationColumnDefinition['type'])) {
			if ($relationColumnDefinition['type'] == 'integer' ||
				$relationColumnDefinition['type'] == 'decimal' ||
				$relationColumnDefinition['type'] == 'float' ||
				$relationColumnDefinition['type'] == 'double') {

				$relationColumnValue = L8M_Translate::numeric($relationColumnValue);
			}
		}
		return $relationColumnValue;
	}

	public function getRealtionValuesRows($ID)
	{
		$returnValue = NULL;

		if ($this->hasRealtionValues()) {
			$modelClassName = $this->_relationValues['relation']->getClass();
			$foreignColumnName = $this->_relationValues['relation']->getForeignColumnName();

			$modelCollection = Doctrine_Query::create()
				->from($modelClassName . ' m')
				->addWhere('m.' . $foreignColumnName .' = ? ', array($ID))
				->execute()
			;

			if ($modelCollection->count() > 0) {
				$returnValue = $modelCollection;
			}
		}

		return $returnValue;
	}

	public function getRelationValueColumnLablel($columnName = NULL)
	{
		$returnValue = $columnName;

		if (array_key_exists($columnName, $this->_relationM2nValuesColumnLabels)) {
			$returnValue = $this->_relationM2nValuesColumnLabels[$columnName];
		}

		return $returnValue;
	}


	public function hasExtraValue()
	{
		$returnValue = FALSE;

		if ($this->_hasExtraValue) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	public function isSortable()
	{
		$returnValue = FALSE;

		if ($this->_sortable) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * returns collection of items
	 *
	 * @return Doctrine_Collection
	 */
	public function getOptionValuesForRender()
	{
		if ($this->_foreignValuesCollection == NULL) {
			$query = Doctrine_Query::create()
				->from($this->_foreignClassName . ' m')
			;


			if ($this->getForeignAlias() == 'Media' ||
				$this->getForeignAlias() == 'MediaImage' ||
				$this->getForeignAlias() == 'MediaShockwave') {

				$query = $query
					->addWhere('m.media_folder_id IS NULL', array())
				;
			}

			$this->_foreignValuesCollection = $query
				->orderBy('m.' . $this->_useForeignColumn . ' ASC')
				->limit(75)
				->execute()
			;
		}

		return $this->_foreignValuesCollection;
	}

	/**
	 * returns count of full collection items
	 *
	 * @return integer
	 */
	public function getOptionValuesCountForRender()
	{

		if ($this->_foreignValuesCount == NULL) {
			$query = Doctrine_Query::create()
				->from($this->_foreignClassName . ' m')
				->select('COUNT(id)')
			;


			if ($this->getForeignAlias() == 'Media' ||
				$this->getForeignAlias() == 'MediaImage' ||
				$this->getForeignAlias() == 'MediaShockwave') {

				$query = $query
					->addWhere('m.media_folder_id IS NULL', array())
				;
			}

			$countArray = $query
				->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
				->execute()
			;

			if (is_array($countArray) &&
				isset($countArray[0]) &&
				is_array($countArray[0]) &&
				isset($countArray[0]['m_COUNT'])) {

				$this->_foreignValuesCount = $countArray[0]['m_COUNT'];
			}
		}
		$returnValue = $this->_foreignValuesCount;

		return $returnValue;
	}

	/**
	 * returns collection of selected items
	 *
	 * @return Doctrine_Collection
	 */
	public function getSelectedOptionValuesForRender()
	{
		$selectedValuesQuery = Doctrine_Query::create()
			->from($this->_relation->getClass() . ' m')
			->leftJoin('m.' . $this->_foreignRelation->getAlias() . ' ma')
			->addWhere('m.' . $this->_relation->getForeignColumnName() . ' = ? ', array($this->_modelID))
		;

		$sortBy = $this->getSortBy();
		if ($sortBy) {
			$selectedValuesQuery = $selectedValuesQuery
				->orderBy('m.' . $sortBy . ' ASC')
			;
		}

		$selectedValuesCollection = $selectedValuesQuery
			->execute()
		;
		return $selectedValuesCollection;
	}

	public function getSortBy()
	{
		$returnValue = NULL;

		if ($this->_sortable) {
			if ($this->_sortableByRelation) {
				if ($this->_relationM['class'] == $this->_relation->getClass()) {
					$returnValue = 'position_m';
				} else {
					$returnValue = 'position_n';
				}
			} else {
				$returnValue = 'position';
			}
		}

		return $returnValue;
	}

	public function getUseForeignColumn()
	{
		return $this->_useForeignColumn;
	}

	public function getForeignAlias()
	{
		return $this->_foreignRelation->getAlias();
	}

	public function getAutoloadModelName()
	{
		$returnValue = $this->_foreignRelation->getAlias();

		if ($this->_relationM['class'] == $this->_relationN['class']) {
			$returnValue = $this->_relationM['class'];
		}

		return $returnValue;
	}

	public function getForeignColumn()
	{
		return $this->_foreignColumn;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * constructor
	 *
	 * @param name $spec
	 * @param Doctrine_Relation_ForeignKey $relation
	 * @param integer $modelID
	 * @param array $options
	 */
	public function __construct($spec = NULL, $relation = NULL, $localClassName, $modelID = NULL, $relationM2nValuesDefinition = NULL, $options = NULL)
	{

		/**
		 * set decorator path
		 */
		$this->addPrefixPath(
			'L8M_JQuery_Form_Decorator',
			'L8M'. DIRECTORY_SEPARATOR . 'JQuery'. DIRECTORY_SEPARATOR . 'Form'. DIRECTORY_SEPARATOR . 'Decorator',
			'decorator'
		);

		/**
		 * set model id
		 */
		$this->_modelID = $modelID;

		/**
		 * set local class name
		 */
		$this->_localClassName = $localClassName;

		/**
		 * set relation
		 */
		$this->_relation = $relation;

		/**
		 * m2n relations
		 */
		$this->_m2nRelations = $this->_relation->getTable()->getRelations();

		/**
		 * sortable or what?
		 */
		$localRelationColumnDefinitions = $this->_relation->getTable()->getColumns();
		if (isset($localRelationColumnDefinitions['position'])) {
			$this->_sortable = TRUE;
		}
		if (isset($localRelationColumnDefinitions['position_m']) &&
			isset($localRelationColumnDefinitions['position_n'])) {
			$this->_sortable = TRUE;
			$this->_sortableByRelation = TRUE;
		}

		/**
		 * self-reflecting M2N
		 */
		$tmpPossibleM2nRelations = explode('M2n', $this->_relation->getAlias());
		if (count($tmpPossibleM2nRelations) == 2 &&
			$tmpPossibleM2nRelations[0] == $tmpPossibleM2nRelations[1]) {

			$this->_isSelfRefelecting = TRUE;
		}

		/**
		 * has Value?
		 */
		if (isset($localRelationColumnDefinitions['value'])) {

			if (!isset($localRelationColumnDefinitions['notnull']) ||
				(isset($localRelationColumnDefinitions['notnull']) && $localRelationColumnDefinitions['notnull'] == FALSE)) {

				$this->_hasExtraValue = TRUE;
			} else {
				throw new L8M_Exception('Failure creating M2N-Form-Element. Value is required Column.');
			}
		}

		/**
		 * find relations
		 */
		$translationTable = NULL;
		if ($this->_hasExtraValue !== TRUE &&
			count($this->_m2nRelations) === 3 &&
			$this->_relation->getTable()->hasRelation('Translation')) {

			$hasTranslation = FALSE;
			foreach ($this->_m2nRelations as $m2nRelation) {
				if ($m2nRelation->getAlias() == 'Translation') {
					$hasTranslation = TRUE;
					$translationTable = $m2nRelation->getTable();
				} else {
					if ($this->_relationM == NULL) {
						$this->_relationM = array(
							'alias'=>$m2nRelation->getAlias(),
							'class'=>$m2nRelation->getClass(),
							'relation'=>$m2nRelation,
						);
					} else
					if ($this->_relationN == NULL) {
						$this->_relationN = array(
							'alias'=>$m2nRelation->getAlias(),
							'class'=>$m2nRelation->getClass(),
							'relation'=>$m2nRelation,
						);
					}
				}
			}

			if ($hasTranslation) {
				if ($translationTable) {
					$translationTableColumnDefinitions = $translationTable->getColumns();
					foreach ($translationTableColumnDefinitions as $translationTableColumnName => $translationTableColumnDefinition) {
						if ($translationTableColumnName !='id' &&
							$translationTableColumnName !='lang' &&
							$translationTableColumnName !='created_at' &&
							$translationTableColumnName !='updated_at' &&
							$translationTableColumnName !='deleted_at' &&
							isset($translationTableColumnDefinition['notnull']) &&
							$translationTableColumnDefinition['notnull'] == TRUE) {

							throw new L8M_Exception('Failure creating M2N-Form-Element. Relation Translation has required Columns.');
						}
					}
				}
			}
		} else
		if (count($this->_m2nRelations) === 2) {
			foreach ($this->_m2nRelations as $m2nRelation) {
				if ($this->_relationM == NULL) {
					$this->_relationM = array(
						'alias'=>$m2nRelation->getAlias(),
						'class'=>$m2nRelation->getClass(),
						'relation'=>$m2nRelation,
					);
				} else
				if ($this->_relationN == NULL) {
					$this->_relationN = array(
						'alias'=>$m2nRelation->getAlias(),
						'class'=>$m2nRelation->getClass(),
						'relation'=>$m2nRelation,
					);
				}
			}
		} else
		if (count($this->_m2nRelations) === 3 &&
			!$this->_relation->getTable()->hasRelation('Translation') &&
			$this->_relation->getTable()->hasRelation($this->_relation->getAlias() . 'Values')) {

			foreach ($this->_m2nRelations as $m2nRelation) {
				if ($m2nRelation->getType() == Doctrine_Relation::ONE) {
					if ($this->_relationM == NULL) {
						$this->_relationM = array(
							'alias'=>$m2nRelation->getAlias(),
							'class'=>$m2nRelation->getClass(),
							'relation'=>$m2nRelation,
						);
					} else
					if ($this->_relationN == NULL) {
						$this->_relationN = array(
							'alias'=>$m2nRelation->getAlias(),
							'class'=>$m2nRelation->getClass(),
							'relation'=>$m2nRelation,
						);
					}
				} else {
					$this->_relationValues = array(
						'alias'=>$m2nRelation->getAlias(),
						'class'=>$m2nRelation->getClass(),
						'relation'=>$m2nRelation,
					);
				}
			}

			if ($this->_relationM == NULL ||
				$this->_relationN == NULL ||
				$this->_relationValues == NULL) {

				throw new L8M_Exception('Failure creating M2N-Form-Element. Relations inside of M2N need to of count 2 having relationtype ONE and of count 1 having relationtype MANY.');
			}
		} else {
			throw new L8M_Exception('Failure creating M2N-Form-Element. Relations inside of M2N need to be of count 2 or 3 having Translation.');
		}

		foreach ($this->_m2nRelations as $m2nRelation) {
			if ($m2nRelation->getAlias() == 'Translation') {
				$this->_m2nTranslation = $m2nRelation;
				$transColumns = $this->_m2nTranslation->getTable()->getColumns();
				if (isset($transColumns['name'])) {
					$this->_translationColumn = 'name';
				} else
				if (isset($transColumns['title'])) {
					$this->_translationColumn = 'title';
				} else {
					throw new L8M_Exception('Failure creating M2N-Form-Element. Relation Translation need to have the Column "name" or "title".');
				}
			} else
			if ($m2nRelation->getClass() != $this->_localClassName &&
				$m2nRelation->getType() == Doctrine_Relation::ONE) {

				$this->_foreignRelation = $m2nRelation;
			} else
			if ($this->_isSelfRefelecting &&
				$m2nRelation->getClass() == $this->_localClassName &&
				$m2nRelation->getType() == Doctrine_Relation::ONE) {

				$this->_foreignRelation = $m2nRelation;
			}
		}

		if ($this->_foreignRelation == NULL &&
			$this->_relationM['class'] == $this->_relationN['class']) {

			$this->_foreignRelation = $this->_relationN['relation'];
		}

		$this->_foreignClassName = $this->_foreignRelation->getClass();
		$this->_foreignColumn = $this->_foreignRelation->getLocalColumnName();
		$foreignTableColumnDefinitions = $this->_foreignRelation->getTable()->getColumns();

		if (isset($foreignTableColumnDefinitions['name'])) {
			$this->_useForeignColumn = 'name';
		}
		if (isset($foreignTableColumnDefinitions['short'])) {
			$this->_useForeignColumn = 'short';
		}
		if (isset($foreignTableColumnDefinitions['resource'])) {
			$this->_useForeignColumn = 'resource';
		}
		if (isset($foreignTableColumnDefinitions['login'])) {
			$this->_useForeignColumn = 'login';
		}
		if ($this->_useForeignColumn == NULL) {
			throw new L8M_Exception('Failure creating M2N-Form-Element. Relation column musst have at least a short');
		}

		/**
		 * some definitions
		 */
		if ($this->hasRealtionValues()) {
			$this->_allowMultipleRelationM2nValuesRows = TRUE;
			if ($relationM2nValuesDefinition &&
				is_array($relationM2nValuesDefinition) &&
				array_key_exists($this->_relation->getAlias(), $relationM2nValuesDefinition)) {

				if (isset($relationM2nValuesDefinition[$this->_relation->getAlias()]['allowMultipleRows']) &&
					$relationM2nValuesDefinition[$this->_relation->getAlias()]['allowMultipleRows'] == FALSE) {

					$this->_allowMultipleRelationM2nValuesRows = FALSE;
				}

				if (isset($relationM2nValuesDefinition[$this->_relation->getAlias()]['columnLabels']) &&
					is_array($relationM2nValuesDefinition[$this->_relation->getAlias()]['columnLabels'])) {

					$this->_relationM2nValuesColumnLabels = $relationM2nValuesDefinition[$this->_relation->getAlias()]['columnLabels'];
				}
			}
		}

		/**
		 * check for 2Y in M2N
		 */
		if ($this->_relationValues) {
			$relationValuesColumnsDefinition = $this->_relationValues['relation']->getTable()->getColumns();
			if (array_key_exists('referenced_id', $relationValuesColumnsDefinition)) {
				if(substr($this->_relationM['alias'], -1 * strlen('OptionModel')) == 'OptionModel') {
					$this->_relation2y = $this->_relationM;
				} else
				if (substr($this->_relationN['alias'], -1 * strlen('OptionModel')) == 'OptionModel') {
					$this->_relation2y = $this->_relationN;
				}
			}
		}

		/**
		 * parent constructor
		 */
		parent::__construct($spec, $options);
	}

	/**
	 * Flags, whether M2N uses a media relation
	 *
	 */
	public function isMediaRealtion()
	{
		$returnValue = FALSE;

		if (substr($this->_foreignClassName, 0, strlen('Default_Model_Media')) !== FALSE) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	public function loadDefaultDecorators()
	{
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return;
		}

		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this
				->addDecorator('M2N')
				->addDecorator('Errors')
				->addDecorator('Description', array(
					'tag'   => 'p',
					'class' => 'description'
					))
				->addDecorator('HtmlTag', array(
					'tag' => 'dd',
					'id'  => $this->getName() . '-element'
					))
				->addDecorator('Label', array('tag' => 'dt'))
			;
		}
	}
}