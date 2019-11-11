<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/One2N.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id:M2N.php 271 2012-02-27 13:56:59Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_One2N
 *
 *
 */
class L8M_JQuery_Form_Element_One2N extends Zend_Form_Element_Xhtml
{

 	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * contains one2n relation condition
	 *
	 * @var array
	 */
	protected $_one2NmultiRelationCondition = NULL;

	/**
	 * contains edit ID
	 *
	 * @var integer
	 */
	protected $_editID = NULL;

	/**
	 * contains local class name
	 *
	 * @var string
	 */
	protected $_localClassName = NULL;

	/**
	 * contains foreign class name
	 *
	 * @var array
	 */
	protected $_elementValues = NULL;

	/**
	 * contains id column name
	 *
	 * @var string
	 */
	protected $_useIDColumn = NULL;

	/**
	 * contains value column name
	 *
	 * @var string
	 */
	protected $_useValueColumn = NULL;

	/**
	 * contains count
	 *
	 * @var string
	 */
	protected $_foreignValuesCount = NULL;

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

	/**
	 * returns collection of items
	 *
	 * @return Doctrine_Collection
	 */
	public function getOptionValuesForRender()
	{
		return $this->_elementValues;
	}

	/**
	 * returns count of full collection items
	 *
	 * @return integer
	 */
	public function getOptionValuesCountForRender()
	{

		if ($this->_foreignValuesCount == NULL) {
			$countQuery = Doctrine_Query::create()
				->from($this->_localClassName . ' m')
				->select('COUNT(id)')
			;

			foreach($this->_one2NmultiRelationCondition as $key => $value) {

				if ($value === NULL) {
					$countQuery = $countQuery->addWhere('m.' .  $key . ' IS NULL', array());
				} else
				if (is_array($value) &&
					array_key_exists('like', $value) &&
					$value['like'] &&
					array_key_exists('value', $value)) {

					$countQuery = $countQuery->addWhere('m.' .  $key . ' LIKE ? ', array($value['value']));
				} else
				if (is_array($value) &&
					array_key_exists('like', $value) &&
					!$value['like'] &&
					array_key_exists('value', $value)) {

					$countQuery = $countQuery->addWhere('m.' .  $key . ' NOT LIKE ? ', array($value['value']));
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'lt' &&
					array_key_exists('value', $value)) {

					$countQuery = $countQuery->addWhere('m.' .  $key . ' < ? ', array($value['value']));
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'lte' &&
					array_key_exists('value', $value)) {

					$countQuery = $countQuery->addWhere('m.' .  $key . ' <= ? ', array($value['value']));
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'gt' &&
					array_key_exists('value', $value)) {

					$countQuery = $countQuery->addWhere('m.' .  $key . ' > ? ', array($value['value']));
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'gte' &&
					array_key_exists('value', $value)) {

					$countQuery = $countQuery->addWhere('m.' .  $key . ' >= ? ', array($value['value']));
				} else {
					$countQuery = $countQuery->addWhere('m.' .  $key . ' = ? ', array($value));
				}
			}

			$countArray = $countQuery
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
	public function getSelectedOptionValueForRender()
	{
		$modelQuery = Doctrine_Query::create()
			->from($this->_localClassName . ' m')
			->addWhere('m.' . $this->_useIDColumn . ' = ? ', array($this->_editID))
		;

		foreach ($this->_one2NmultiRelationCondition as $key => $value) {
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

				$modelQuery = $modelQuery->addWhere('m.' .  $key . ' = ? ', array($value['value']));
			} else {
				$modelQuery = $modelQuery->addWhere('m.' .  $key . ' = ? ', array($value));
			}
		}

		$selectedValueModel = $modelQuery
			->execute()
			->getFirst()
		;

		return $selectedValueModel;
	}

	public function getOne2NmultiRelationCondition()
	{
		return $this->_one2NmultiRelationCondition;
	}

	public function getClassName()
	{
		return $this->_localClassName;
	}

	public function getUseValueColumn()
	{
		return $this->_useValueColumn;
	}

	public function getUseIDColumn()
	{
		return $this->_useIDColumn;
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
	public function __construct($spec = NULL, $localClassName = NULL, $one2NmultiRelationCondition = NULL, $one2NreplaceColumnValueInMultiRelation = NULL, $values = NULL, $editID = NULL, $rowNameKey = NULL, $idColumn = NULL, $options = NULL)
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
		 * set edit id
		 */
		$this->_editID = $editID;

		/**
		 * set local class name
		 */
		if (!class_exists($localClassName)) {
			throw new L8M_Exception('Failure creationg One2N-Form-Element. Class does not exist.');
		} else {
			$this->_localClassName = $localClassName;
		}

		/**
		 * set relation conditions
		 */
		if (!is_array($one2NmultiRelationCondition)) {
			throw new L8M_Exception('Failure creationg One2N-Form-Element. Conditions have to be set in an array.');
		} else {
			$this->_one2NmultiRelationCondition = $one2NmultiRelationCondition;
		}

		/**
		 * set relation conditions
		 */
		if (!is_array($values)) {
			throw new L8M_Exception('Failure creationg One2N-Form-Element. Vales have to be set in an array.');
		} else {
			$this->_elementValues = $values;
		}

		$model = new $this->_localClassName();
		$columnDefinitions = $model->getTable()->getColumns();
		if ($rowNameKey &&
			array_key_exists($rowNameKey, $columnDefinitions)) {

			$this->_useIDColumn = $rowNameKey;
		} else
		if ($idColumn &&
			array_key_exists($idColumn, $columnDefinitions)) {

			$this->_useIDColumn = $idColumn;
		} else {
			throw new L8M_Exception('Failure creationg One2N-Form-Element. ID-Column does not exist.');
		}

		if ($one2NreplaceColumnValueInMultiRelation &&
			array_key_exists($one2NreplaceColumnValueInMultiRelation, $columnDefinitions)) {

			$this->_useValueColumn = $one2NreplaceColumnValueInMultiRelation;
		} else
		if (array_key_exists('name', $columnDefinitions)) {
			$this->_useValueColumn = 'name';
		} else
		if (array_key_exists('short', $columnDefinitions)) {
			$this->_useValueColumn = 'short';
		} else
		if (array_key_exists('value', $columnDefinitions)) {
			$this->_useValueColumn = 'value';
		} else
		if (array_key_exists('login', $columnDefinitions)) {
			$this->_useValueColumn = 'login';
		} else {

			$modelRelations = $model->getTable()->getRelations();
			if (array_key_exists('Translation', $modelRelations)) {
				$transColumnDefinitions = $model['Translation']->getTable()->getColumns();

				if ($one2NreplaceColumnValueInMultiRelation &&
					array_key_exists($one2NreplaceColumnValueInMultiRelation, $transColumnDefinitions)) {

					$this->_useValueColumn = $one2NreplaceColumnValueInMultiRelation;
				} else
				if (array_key_exists('name', $transColumnDefinitions)) {
					$this->_useValueColumn = 'name';
				} else
				if (array_key_exists('short', $transColumnDefinitions)) {
					$this->_useValueColumn = 'short';
				} else
				if (array_key_exists('value', $transColumnDefinitions)) {
					$this->_useValueColumn = 'value';
				}
			}
		}
		if ($this->_useValueColumn == NULL) {
			$this->_useValueColumn = $this->_useIDColumn;
		}

		/**
		 * parent constructor
		 */
		parent::__construct($spec, $options);
	}

	public function loadDefaultDecorators()
	{
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return;
		}

		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this
				->addDecorator('One2N')
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