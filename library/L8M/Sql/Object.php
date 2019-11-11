<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Object/Object.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Object.php 424 2015-09-21 14:24:22Z nm $
 */

/**
 *
 *
 * L8M_Sql_Object
 *
 *
 */
class L8M_Sql_Object extends L8M_Sql_ObjectAbstract
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
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 * Cunstruct L8M_Sql_Object.
	 *
	 * @param array $valueArray
	 * @param array $allowedColumns
	 * @param String $modelClassName
	 * @return L8M_Sql_ObjectCollection
	 */
	public function __construct($valueArray, $allowedColumns, $modelClassName = FALSE) {
		$this->_valueArray = $valueArray;
		$this->_allowedColumns = $allowedColumns;

		if ($modelClassName &&
			class_exists($modelClassName)) {

			$this->_modelClassName = $modelClassName;
		}
	}

	/**
	 * Check whether columns exist or not.
	 *
	 * @param array $columns
	 * @return boolean
	 */
	public function hasColumns($columns) {
		$returnValue = TRUE;
		foreach ($columns as $column) {
			if (!array_key_exists($column, $this->_allowedColumns)) {
				$returnValue = FALSE;
			}
		}

		return $returnValue;
	}

	/**
	 * Check whether column exist or not.
	 *
	 * @param String $column
	 * @return boolean
	 */
	public function hasColumn($column) {
		$returnValue = FALSE;
		if (array_key_exists($column, $this->_allowedColumns)) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * Get specified column of L8M_Sql_Object.
	 *
	 * @param integer $property
	 * @return mixed
	 */
	public function __get($property) {
		$returnValue = NULL;

		if (array_key_exists($property, $this->_valueArray)) {
			$returnValue = $this->_valueArray[$property];
		} else
		if (array_key_exists($property, $this->_allowedColumns)) {
			$returnValue = NULL;
		} else {
			throw new L8M_Exception('Property does not exist: ' . $property);
		}

		return $returnValue;
	}

	/**
	 * Get specified column of L8M_Sql_Object.
	 *
	 * @param integer $property
	 * @param mixed $value
	 * @return mixed
	 */
	public function __set($property, $value) {
		if (array_key_exists($property, $this->_allowedColumns)) {
			$this->_valueArray[$property] = $value;
		} else {
			throw new L8M_Exception('Property does not exist: ' . $property);
		}
	}

	/**
	 * Returns value-array.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->_valueArray;
	}

	public function getModel() {
		$returnValue = FALSE;
		if ($this->_modelClassName &&
			$this->hasColumn('id') &&
			$this->id) {

			$modelName = $this->_modelClassName;
			$model = $modelName::getModelByID($this->id, $modelName);

			if ($model) {
				$returnValue = $model;
			}
		}

		return $returnValue;
	}
}