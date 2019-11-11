<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Object/ObjectCollection.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ObjectCollection.php 425 2015-09-21 14:38:25Z nm $
 */

/**
 *
 *
 * L8M_Sql_ObjectCollection
 *
 *
 */
class L8M_Sql_ObjectCollection extends L8M_Sql_ObjectAbstract implements Iterator, Countable
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
	private $_position = 0;

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 * Get first L8M_Sql_Object of L8M_Sql_ObjectCollection.
	 *
	 * @return L8M_Sql_Object
	 */
	public function first() {
		return $this->getFirst();
	}

	/**
	 * Get first L8M_Sql_Object of L8M_Sql_ObjectCollection.
	 *
	 * @return L8M_Sql_Object
	 */
	public function getFirst() {
		$returnValue = FALSE;

		if (count($this->_valueArray) > 0 &&
			array_key_exists(0, $this->_valueArray)) {

			$returnValue = new L8M_Sql_Object($this->_valueArray[0], $this->_allowedColumns, $this->_modelClassName);
		}
		return $returnValue;
	}

	/**
	 * Get last L8M_Sql_Object of L8M_Sql_ObjectCollection.
	 *
	 * @return L8M_Sql_Object
	 */
	public function last() {
		return $this->getLast();
	}

	/**
	 * Get last L8M_Sql_Object of L8M_Sql_ObjectCollection.
	 *
	 * @return L8M_Sql_Object
	 */
	public function getLast() {
		$returnValue = FALSE;

		if (count($this->_valueArray) > 0 &&
			array_key_exists((count($this->_valueArray) - 1), $this->_valueArray)) {

			$returnValue = new L8M_Sql_Object($this->_valueArray[count($this->_valueArray) - 1], $this->_allowedColumns, $this->_modelClassName);
		}
		return $returnValue;
	}

	/**
	 * Get count of collection of L8M_Sql_Object.
	 *
	 * @return integer
	 */
	public function count() {
		$returnValue = count($this->_valueArray);
		return $returnValue;
	}

	/**
	 * Get specified L8M_Sql_Object of L8M_Sql_ObjectCollection.
	 *
	 * @param integer $property
	 * @return L8M_Sql_Object
	 */
	public function __get($property) {
		$returnValue = NULL;

		if (array_key_exists($property, $this->_valueArray)) {
			$returnValue = new L8M_Sql_Object($this->_valueArray[$property], $this->_allowedColumns, $this->_modelClassName);
		} else {
			throw new L8M_Exception('Property does not exist: ' . $property);
		}

		return $returnValue;
	}

	/**
	 * Set specified L8M_Sql_Object of L8M_Sql_ObjectCollection.
	 *
	 * @param integer $property
	 * @param L8M_Sql_Object $value
	 */
	public function __set($property, $value) {
		if (array_key_exists($property, $this->_valueArray)) {
			if ($value instanceof L8M_Sql_Object &&
				$value->hasColumns(array_keys($this->_allowedColumns))) {

				$this->_valueArray[$property] = $value;
			} else {
				throw new L8M_Exception('Value (L8M_Sql_Object) is not of same type (L8M_Sql_ObjectCollection).');
			}
		} else {
			throw new L8M_Exception('Property does not exist: ' . $property);
		}
	}

	/**
	 * Returns value-array.
	 *
	 * @param boolean $deep
	 * @return array
	 */
	public function toArray($deep = FALSE) {
		$returnValue = array();
		if ($deep) {
			$returnValue = $this->_valueArray;
		} else {
			foreach ($this->_valueArray as $key => $value) {
				$returnValue[$key] = new L8M_Sql_Object($value, $this->_allowedColumns, $this->_modelClassName);
			}
		}

		return $returnValue;
	}

	/**
	 * Cunstruct L8M_Sql_ObjectAbstract.
	 *
	 * @param array $valueArray
	 * @param array $allowedColumns
	 * @param String $modelClassName
	 * @return L8M_Sql_ObjectCollection
	 */
	public function __construct($valueArray, $allowedColumns, $modelClassName = NULL) {
		$this->_position = 0;
		parent::__construct($valueArray, $allowedColumns, $modelClassName);
	}

	public function rewind() {
		$this->_position = 0;
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::current()
	 * @return L8M_Sql_Object
	 */
	public function current() {
		return $this->__get($this->_position);
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::key()
	 * @return integer
	 */
	public function key() {
		return $this->_position;
	}

	public function next() {
		++$this->_position;
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::valid()
	 * @return boolean
	 */
	public function valid() {
		return isset($this->_valueArray[$this->_position]);
	}
}