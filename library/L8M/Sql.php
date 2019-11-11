<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Sql.php
 * @author     Santino Lange <sl@l8m.com>
 * @version    $Id: Sql.php 424 2015-09-21 14:24:22Z nm $
 */

/**
 *
 *
 * L8M_Sql
 *
 *
 */
class L8M_Sql
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
	 * @var array of L8M_Exception
	 */
	protected $_exceptions = array();

	/**
	 * @var $_connection Doctrine_Connection
	 */
	private $_connection = FALSE;

	/**
	 * @var $_model Default_Model_Base_Abstract
	 */
	private $_model = FALSE;

	/**
	 * @var string
	 */
	private $_language = NULL;
	private $_modelName = NULL;
	private $_table = NULL;
	private $_tableAs = NULL;
	private $_translateTableAs = NULL;
	private $_select = '*';
	private $_limit = NULL;
	private $_orderBy = NULL;
	private $_groupBy = NULL;
	private $_sql = NULL;

	/**
	 * @var array
	 */
	private $_where = array();
	private $_params = array();

	/**
	 * @var boolean
	 */
	private $_hasTranslation = FALSE;
	private $_asObject = TRUE;
	private $_withTranslation = FALSE;
	private $_myOwnSql = FALSE;
	private $_resultAsSingleScalar = FALSE;


	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 *
	 * @param string|Defult_Model_Base_Abstract $model
	 * @param string $lang
	 * @param string $connectionName
	 * @return L8M_Sql
	 */
	public function __construct($model = FALSE, $withTranslation = FALSE, $lang = NULL, $connectionName = 'default') {

		/**
		 * retrieve database connection
		 */
		$this->_connection = L8M_Db::getConnection($connectionName);
		if (!$this->_connection) {
			$this->_addException('Could not get database connection, while constructing.');
		} else {

			/**
			 * handle model input
			 */
			if ($model instanceof Default_Model_Base_Abstract) {

				/**
				 * retrieve table from model
				 */
				$this->_modelName = $model->getName();
				$this->_model = $model;
				$this->_table = $this->_model->getTable()->getTableName();
			} else
			if (is_string($model) &&
				strlen($model) > 0) {

				/**
				 * we have to deal with a string
				 */
				if (substr($model, 0, strlen('Default_Model_')) == 'Default_Model_' &&
					class_exists($model)) {

					/**
					 * cerate dummy model and retrieve table
					 */
					$this->_modelName = $model;
					$this->_model = new $model();
					$this->_table = $this->_model->getTable()->getTableName();
				} else {

					/**
					 * try finding table
					 */
					if ($model != strtolower($model) &&
						class_exists('Default_Model_' . $model)) {

						/**
						 * cerate dummy model and retrieve table
						 */
						$model = 'Default_Model_' . $model;
						$this->_modelName = $model;
						$this->_model = new $model();
						$this->_table = $this->_model->getTable()->getTableName();
					} else {
						$filter = new Zend_Filter();
						$filter
							->addFilter(new Zend_Filter_Word_DashToCamelCase())
						;

						$model = 'Default_Model_' . $filter->filter($model);

						if (class_exists($model)) {

							/**
							 * cerate dummy model and retrieve table
							 */
							$this->_modelName = $model;
							$this->_model = new $model();
							$this->_table = $this->_model->getTable()->getTableName();
						} else {
							$this->_addException('Could not find table, while constructing.');
						}
					}
				}
			} else {
				if ($withTranslation == FALSE &&
					$lang == NULL) {

					$this->_myOwnSql = TRUE;
				} else {
					$this->_addException('Need to have table, while constructing.');
				}
			}

			/**
			 * initalize with translation?
			 */
			if ($withTranslation) {

				$this->_withTranslation = TRUE;

				/**
				 * set language
				 */
				if (!$lang) {
					$this->_language = L8M_Locale::getLang();
				}

				$this->_tableAs = 'm';
				$this->_translateTableAs = 'mt';

				/**
				 * retrieve translation-table
				 */
				if (!$this->hasExceptions()) {
					$this->_checkTranslation();
				}
			}
		}
	}

	/**
	 * Create an L8M_Sql-Object
	 *
	 * @param string|Defult_Model_Base_Abstract $model
	 * @param string $lang
	 * @param string $connectionName
	 * @return L8M_Sql
	 */
	public static function factory($model = FALSE, $withTranslation = FALSE, $lang = NULL, $connectionName = 'default') {
		$returnValue = new L8M_Sql($model, $withTranslation, $lang, $connectionName);
		return $returnValue;
	}

	/**
	 * Creates a sql-select statement.
	 *
	 * @param array|string $fields
	 * @param boolean $withoutID
	 * @return L8M_Sql
	 */
	public function select($fields = array(), $withoutID = FALSE) {

		if (!$this->_myOwnSql) {

			/**
			 * easy access for single selects
			 */
			if ($fields &&
				!is_array($fields)) {

				$fields = array($fields);
			}

			/**
			 * empty select if there is some shit inside
			 */
			if (!is_array($fields)) {
				$fields = array();
			}

			/**
			 * check whether to select ID by default or not
			 */
			if (!$withoutID &&
				!in_array('id', $fields)) {

				$fields = array_merge(array('id'), $fields);
			}

			/**
			 * create select
			 */
			if (count($fields) > 0) {
				$this->_select = implode(',', $fields);
			}
		} else {
			$this->_addException('Function "select" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Creates a sql-select-count statement.
	 *
	 * @param string $column
	 * @return L8M_Sql
	 */
	public function count($column = 'id') {
		if (!$this->_myOwnSql) {
			$this->_select = 'COUNT(' . $column . ') as count';
		} else {
			$this->_addException('Function "count" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Adds a sql-limit to statement.
	 *
	 * @param integer|boolean $length
	 * @param integer|boolean $start
	 * @return L8M_Sql
	 */
	public function limit($length = FALSE, $start = FALSE) {

		if (!$this->_myOwnSql) {

			/**
			 * set limit
			 */
			if ($length !== FALSE &&
				is_integer($length) &&
				$length >= 0) {

				$this->_limit = $length;

				if (is_integer($start) &&
					$start >= 0) {

					$this->_limit = $start . ',' . $this->_limit;
				}

				$this->_limit = 'LIMIT ' . $this->_limit;
			}

			/**
			 * reset limit
			 */
			if ($length === FALSE &&
				$start === FALSE) {

				$this->_limit = NULL;
			}
		} else {
			$this->_addException('Function "limit" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Adds a sql-orderBy to statement.
	 *
	 * @param string|boolean $value
	 * @return L8M_Sql
	 */
	public function orderBy($value = FALSE) {

		if (!$this->_myOwnSql) {
			if ($value === FALSE) {

				/**
				 * reset order by
				 */
				$this->_orderBy = NULL;
			} else {

				/**
				 * set value
				 */
				$this->_orderBy = 'ORDER BY ' . $value;
			}
		} else {
			$this->_addException('Function "orderBy" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Adds a sql-orderBy-asc to statement.
	 *
	 * @param string|boolean $column
	 * @return L8M_Sql
	 */
	public function orderByColumnAsc($column) {
		if (!$this->_myOwnSql) {
			$this->_orderBy = 'ORDER BY ' . $column . ' ASC';
		} else {
			$this->_addException('Function "orderByColumnAsc" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Adds a sql-orderBy-desc to statement.
	 *
	 * @param string|boolean $column
	 * @return L8M_Sql
	 */
	public function orderByColumnDesc($column) {
		if (!$this->_myOwnSql) {
			$this->_orderBy = 'ORDER BY ' . $column . ' DESC';
		} else {
			$this->_addException('Function "orderByColumnDesc" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Adds a sql-groupBy to statement.
	 *
	 * @param string|boolean $column
	 * @return L8M_Sql
	 */
	public function groupBy($column = FALSE) {

		if (!$this->_myOwnSql) {
			if ($column === FALSE) {

				/**
				 * reset column for group by
				 */
				$this->_groupBy = NULL;
			} else {

				/**
				 * set column for group by
				 */
				$this->_groupBy = 'GROUP BY ' . $column;
			}
		} else {
			$this->_addException('Function "groupBy" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Check whether a Translation exists or not.
	 *
	 * @return L8M_Sql
	 */
	private function _checkTranslation() {
		if ($this->_model->getTable()->hasRelation('Translation')) {
			$this->_hasTranslation = TRUE;
		} else {
			$this->_hasTranslation = FALSE;
		}

		return $this;
	}

	/**
	 * Create and-where-statement for one column.
	 *
	 * @param string $statement
	 * @param array $values
	 * @return L8M_Sql
	 */
	public function addWhere($statement = NULL, $values = array()) {
		if (!$this->_myOwnSql) {
			$this->where($statement, $values, 'AND');
		} else {
			$this->_addException('Function "addWhere" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Create or-where-statement for one column.
	 *
	 * @param string $statement
	 * @param array $values
	 * @return L8M_Sql
	 */
	public function orWhere($statement = NULL, $values = array()) {
		if (!$this->_myOwnSql) {
			$this->where($statement, $values, 'OR');
		} else {
			$this->_addException('Function "orWhere" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Create where-statement for one column.
	 *
	 * @param string $statement
	 * @param string $column
	 * @return L8M_Sql
	 */
	public function whereID($value, $column = 'id') {
		if (!$this->_myOwnSql) {
			$this->where($column . ' = ?', array($value), NULL, TRUE);
			$this->limit(1);
		} else {
			$this->_addException('Function "whereID" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Add where statement.
	 *
	 * @param string $statement
	 * @param array $values
	 * @param string $conjunction
	 * @param boolean $reset
	 * @return L8M_Sql
	 */
	public function where($statement = NULL, $values = array(), $conjunction = NULL, $reset = FALSE) {
		if (!$this->_myOwnSql) {
			if ($reset) {
				$this->_where = array();
			}

			if ($values !== NULL &&
				!is_array($values)) {

				$values = array($values);
			}

			if (is_string($statement) &&
				strlen($statement) > 0 &&
				is_array($values)) {

				$conjunction = strtoupper($conjunction);
				if (!in_array($conjunction, array('AND', 'OR'))) {
					$conjunction = 'AND';
				}

				if (count($this->_where) == 0) {
					$conjunction = NULL;
				}

				$this->_where[] = array(
					'statement'=>'(' . $statement . ')',
					'values'=>$values,
					'conjunction'=>$conjunction,
				);
			}
		} else {
			$this->_addException('Function "where" can not be used with "own SQL".');
		}

		return $this;
	}

	/**
	 * Enable return value of execute as object.
	 *
	 * @return L8M_Sql
	 */
	public function disableReturnAsObject() {
		$this->_asObject = FALSE;

		return $this;
	}

	public function setResultAsSingleScalar() {
		$this->_asObject = FALSE;
		$this->_resultAsSingleScalar = TRUE;

		return $this;
	}

	/**
	 * Executes L8M_Sql
	 *
	 * @param string $statement
	 * @param array params
	 * @return L8M_Sql_ObjectCollection|array|boolean
	 */
	public function execute($statement = NULL, $params = array()) {

		$returnValue = FALSE;

		$transSelectColumns = array();

		if ($statement !== NULL) {
			$sql = $statement;
		} else {

			/**
			 * generate where
			 */
			$where = NULL;
			$params = array();
			if (count($this->_where) > 0) {
				foreach ($this->_where as $whereArray) {
					$where .= $whereArray['conjunction'] . $whereArray['statement'];
					$params = array_merge($params, $whereArray['values']);
				}
				$where = 'WHERE ' . $where;
			}

			/**
			 * generate select
			 */
			$select = $this->_select;

			/**
			 * generate from
			 */
			if ($this->_tableAs) {
				$table = $this->_table . ' AS ' . $this->_tableAs;
			} else {
				$table = $this->_table;
			}

			if ($this->_withTranslation &&
				$this->_hasTranslation) {

				$transtable = $this->_table . '_translation AS ' . $this->_translateTableAs;
				$table .= ',' . $transtable;
				if ($where == NULL) {
					$where = 'WHERE ';
				} else {
					$where .= ' AND ';
				}
				$where .= '(' . $this->_tableAs . '.id = ' . $this->_translateTableAs . '.id AND ' . $this->_translateTableAs . '.lang = ? )';
				$params = array_merge($params, array($this->_language));

				if ($select == '*') {
					$select = $this->_tableAs . '.*';
					$transSelectColumns = $this->_model->getTable()->getRelation('Translation')->getTable()->getColumns();

					$delKeys = array(
						'id',
						'lang',
						'created_at',
						'updated_at',
						'deleted_at',
					);

					foreach ($delKeys as $delKey) {
						if (array_key_exists($delKey, $transSelectColumns)) {
							unset($transSelectColumns[$delKey]);
						}
					}

					$select .= ',' . implode(',', array_keys($transSelectColumns));
				}
			}
			$sql = 'SELECT ' . $select . ' FROM ' . $table . ' ' . $where . ' ' . $this->_groupBy . ' ' . $this->_orderBy . ' ' . $this->_limit;
		}

		/**
		 * save for later info
		 */
		$this->_sql = trim($sql);
		$this->_params = $params;

		/**
		 * execute in different ways
		 */
		if (strtoupper(substr($sql, 0, strlen('SELECT'))) == 'SELECT') {
			try {
				$result = L8M_Db::fetchAll($this->_sql, $this->_params);
				if (L8M_Db::hasException()) {
					$this->_addException(L8M_Db::getException(), TRUE);
				}
			} catch (Doctrine_Exception $e) {
				$this->_addException('Somthing went wrong with SQL: SELECT');
				$this->_addException($e, TRUE);
			}

			if (!$this->hasExceptions()) {
				if ($this->_asObject) {
					if ($this->_myOwnSql) {
						$allowedKeys = array();
						if (count($result) >= 1 &&
							is_array($result[0])) {

							$allowedKeys = array_keys($result[0]);
						}
					} else {
						$allowedKeys = array_merge($this->_model->getTable()->getColumns(), $transSelectColumns);
					}
					$returnValue = new L8M_Sql_ObjectCollection($result, $allowedKeys, $this->_modelName);
				} else {
					$returnValue = $result;
				}
			}
		} else {
			try {
				$result = L8M_Db::execute($this->_sql, $this->_params);
				if (L8M_Db::hasException()) {
					$this->_addException(L8M_Db::getException(), TRUE);
				}
			} catch (Doctrine_Exception $e) {

				$this->_addException('Somthing went wrong with SQL');
				$this->_addException($e, TRUE);
			}

			if (!$this->hasExceptions()) {
				$returnValue = $result;
			}
		}

		if ($this->_resultAsSingleScalar &&
			is_array($returnValue)) {

			if (count($returnValue) > 0) {
				$dummyArray = $returnValue[0];

				$arrayKeys = array_keys($dummyArray);
				if (count($arrayKeys) == 1) {
					$arrayKey = $arrayKeys[0];
					$tmpArray = array();
					foreach ($returnValue as $key => $value) {
						$tmpArray[] = $value[$arrayKey];
					}
					$returnValue = $tmpArray;
				} else {
					$this->_addException('Single Scalar not possible, because more then one or no field in list (' . implode(',', $arrayKeys) . ').');
					$returnValue = FALSE;
				}
			} else {
				$returnValue = array();
			}

		}

		return $returnValue;
	}

	/**
	 * Retrieve count as number.
	 *
	 * @return integer|boolean
	 */
	public function getCount() {
		$returnValue = FALSE;

		if (!$this->_myOwnSql) {
			$resultObjCollection = $this
				->count()
				->execute()
			;
			if ($resultObjCollection->count() == 1) {
				$resultObj = $resultObjCollection->getFirst();
				if (isset($resultObj->count)) {
					$returnValue = $resultObj->count;
				} else {
					$this->_addException('Could not retrieve count.');
				}
			} else {
				$this->_addException('Could not execute SQL.');
			}
		} else {
			$this->_addException('This function need to work with generated SQL due to speed and cache misconfiguration.');
		}

		return $returnValue;
	}

	/**
	 * Does L8M_Sql uses generated sql or just some own query?
	 *
	 * @return boolean
	 */
	public function usesOwnSql() {
		return $this->_myOwnSql;
	}

	/**
	 * Retrieve created sql and params.
	 *
	 * @param boolean $withParams
	 * @return array|string
	 */
	public function getCreatedSql($withParams = TRUE) {

		$returnValue = NULL;
		if ($withParams) {
			$returnValue = array(
				'sql'=>$this->_sql,
				'params'=>$this->_params,
			);
		} else {
			$returnValue = $this->_sql;
		}

		return $returnValue;
	}

	/**
	 * Adds Error to internal error handler.
	 *
	 * @param Exception|string $message
	 * @param boolean $isException
	 */
	private function _addException($message = NULL, $isException = FALSE) {
		if ($isException) {
			$this->_exceptions[] = $message;
		} else {
			$this->_exceptions[] = new L8M_Exception($message);
		}
	}

	/**
	 * Checks whether an error exists or not.
	 *
	 * @return boolean
	 */
	public function hasExceptions() {
		$returnValue = FALSE;

		if (count($this->_exceptions) > 0) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * Retuns stack of errors.
	 *
	 * @return array
	 */
	public function getExceptions() {
		$returnValue = $this->_exceptions;
		$this->_exceptions = array();

		return $returnValue;
	}
}