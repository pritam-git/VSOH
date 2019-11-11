<?php

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Validate' . DIRECTORY_SEPARATOR . 'Abstract.php';

/**
 * @category   L8M
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd	 New BSD License
 */
class L8M_Validate_One2N extends Zend_Validate_Abstract
{
	const NOT_IN_ARRAY = 'notInArray';

	/**
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_IN_ARRAY => "'%value%' was not found in the haystack",
	);

	/**
	 * Haystack of possible values
	 *
	 * @var array
	 */
	protected $_haystack;

	/**
	 * contains one2n relation condition
	 *
	 * @var array
	 */
	protected $_one2NmultiRelationCondition = NULL;

	/**
	 * contains local class name
	 *
	 * @var string
	 */
	protected $_localClassName = NULL;

	/**
	 * contains id column name
	 *
	 * @var string
	 */
	protected $_useIDColumn = NULL;

	/**
	 * flags, whether can be null or not
	 *
	 * @var boolean
	 */
	protected $_notNull = TRUE;

	/**
	 * Sets validator options
	 *
	 * @param  array|Zend_Config $haystack
	 * @return void
	 */
	public function __construct($localClassName = NULL, $one2NmultiRelationCondition = NULL, $rowNameKey = NULL, $idColumn = NULL, $notNull = TRUE)
	{
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

		if (is_bool($notNull)) {
			$this->_notNull = $notNull;
		}

		$view = Zend_Layout::getMvcInstance()->getView();

		$this->_messageTemplates[self::NOT_IN_ARRAY] = $view->translate($this->_messageTemplates[self::NOT_IN_ARRAY]);
	}

	/**
	 * Defined by Zend_Validate_Interface
	 *
	 * Returns true if and only if $value is contained in the haystack option. If the strict
	 * option is true, then the type of $value is also checked.
	 *
	 * @param  mixed $value
	 * @return boolean
	 */
	public function isValid($value)
	{
		$returnValue = FALSE;

		$this->_setValue($value);

		if (!$this->_notNull &&
			$value == '') {

			$returnValue = TRUE;
		} else {
			$modelQuery = Doctrine_Query::create()
				->from($this->_localClassName . ' m')
				->addWhere('m.' . $this->_useIDColumn . ' = ? ', array($value))
			;

			foreach ($this->_one2NmultiRelationCondition as $conditionKey => $conditionValue) {
				if ($conditionValue === NULL) {
					$modelQuery = $modelQuery->addWhere('m.' .  $conditionKey . ' IS NULL', array());
				} else
				if (is_array($conditionValue) &&
					array_key_exists('like', $conditionValue) &&
					$conditionValue['like'] &&
					array_key_exists('value', $conditionValue)) {

					$modelQuery = $modelQuery->addWhere('m.' .  $conditionKey . ' LIKE ? ', array($conditionValue['value']));
				} else
				if (is_array($conditionValue) &&
					array_key_exists('like', $conditionValue) &&
					!$conditionValue['like'] &&
					array_key_exists('value', $conditionValue)) {

					$modelQuery = $modelQuery->addWhere('m.' .  $conditionKey . ' = ? ', array($conditionValue['value']));
				} else {
					$modelQuery = $modelQuery->addWhere('m.' .  $conditionKey . ' = ? ', array($conditionValue));
				}
			}

			$selectedValueModel = $modelQuery
				->execute()
				->getFirst()
			;

			if ($selectedValueModel) {
				$returnValue = TRUE;
			} else {
				$this->_error(self::NOT_IN_ARRAY);
			}
		}
		return $returnValue;
	}
}
