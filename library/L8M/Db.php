<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Db.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Db.php 544 2017-08-23 18:28:23Z nm $
 */

/**
 *
 *
 * L8M_Db
 *
 *
 */
class L8M_Db
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
	protected static $_exception = FALSE;

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */


	/**
	 * getConnection
	 *
	 * @param string $name         name of multidb
	 * @return Doctrine_Connection
	 */
	public static function getConnection($name = 'default')
	{
		$returnValue = FALSE;

		$connection = Doctrine_Manager::getInstance()->getConnection($name);
		if ($connection instanceof Doctrine_Connection) {
			$returnValue = $connection;
		}

		/**
		 * return value
		 */
		return $returnValue;
	}


	/**
	 * fetchAll
	 *
	 * @param string $statement         sql query to be executed
	 * @param array $params             prepared statement params
	 * @return array
	 */
	public static function fetchAll($statement, array $params = array())
	{
		$returnValue = FALSE;

		$connection = self::getConnection();
		if ($connection instanceof Doctrine_Connection) {
			try {
				$returnValue = $connection->execute($statement, $params)->fetchAll(Doctrine_Core::FETCH_ASSOC);
			} catch (Doctrine_Exception $exception) {
				self::$_exception = $exception;
			}
		}

		/**
		 * return option
		 */
		return $returnValue;
	}


	/**
	 * fetchArray
	 *
	 * @param string $statement         sql query to be executed
	 * @param array $params             prepared statement params
	 * @return array
	 */
	public static function fetchArray($statement, array $params = array())
	{
		$returnValue = FALSE;

		$connection = self::getConnection();
		if ($connection instanceof Doctrine_Connection) {
			try {
				$returnValue = $connection->execute($statement, $params)->fetch(Doctrine_Core::FETCH_NUM);
			} catch (Doctrine_Exception $exception) {
				self::$_exception = $exception;
			}
		}

		/**
		 * return option
		 */
		return $returnValue;
	}


	/**
	 * fetchAssoc
	 *
	 * @param string $statement         sql query to be executed
	 * @param array $params             prepared statement params
	 * @return array
	 */
	public static function fetchAssoc($statement, array $params = array())
	{
		$returnValue = FALSE;

		$connection = self::getConnection();
		if ($connection instanceof Doctrine_Connection) {
			try {
				$returnValue = $connection->execute($statement, $params)->fetchAll(Doctrine_Core::FETCH_ASSOC);
			} catch (Doctrine_Exception $exception) {
				self::$_exception = $exception;
			}
		}

		/**
		 * return option
		 */
		return $returnValue;
	}


	/**
	 * fetchBoth
	 *
	 * @param string $statement         sql query to be executed
	 * @param array $params             prepared statement params
	 * @return array
	 */
	public static function fetchBoth($statement, array $params = array())
	{
		$returnValue = FALSE;

		$connection = self::getConnection();
		if ($connection instanceof Doctrine_Connection) {
			try {
				$returnValue = $connection->execute($statement, $params)->fetchAll(Doctrine_Core::FETCH_BOTH);
			} catch (Doctrine_Exception $exception) {
				self::$_exception = $exception;
			}
		}

		/**
		 * return option
		 */
		return $returnValue;
	}


	/**
	 * fetchRow
	 *
	 * @param string $statement         sql query to be executed
	 * @param array $params             prepared statement params
	 * @return array
	 */
	public static function fetchRow($statement, array $params = array())
	{
		$returnValue = FALSE;

		$connection = self::getConnection();
		if ($connection instanceof Doctrine_Connection) {
			try {
				$returnValue = $connection->execute($statement, $params)->fetch(Doctrine_Core::FETCH_ASSOC);
			} catch (Doctrine_Exception $exception) {
				self::$_exception = $exception;
			}
		}

		/**
		 * return option
		 */
		return $returnValue;
	}
	/**
	 * fetchColumn
	 *
	 * @param string $statement         sql query to be executed
	 * @param array $params             prepared statement params
	 * @param int $colnum               0-indexed column number to retrieve
	 * @return array
	 */
	public static function fetchColumn($statement, array $params = array(), $colnum = 0)
	{
		$returnValue = FALSE;

		$connection = self::getConnection();
		if ($connection instanceof Doctrine_Connection) {
			try {
				$returnValue = $connection->execute($statement, $params)->fetchAll(Doctrine_Core::FETCH_COLUMN, $colnum);;
			} catch (Doctrine_Exception $exception) {
				self::$_exception = $exception;
			}
		}

		/**
		 * return option
		 */
		return $returnValue;
	}


	/**
	 * fetchOne
	 *
	 * @param string $statement         sql query to be executed
	 * @param array $params             prepared statement params
	 * @param int $colnum               0-indexed column number to retrieve
	 * @return mixed
	 */
	public static function fetchOne($statement, array $params = array(), $colnum = 0)
	{
		$returnValue = FALSE;

		$connection = self::getConnection();
		if ($connection instanceof Doctrine_Connection) {
			try {
				$returnValue = $connection->execute($statement, $params)->fetchColumn($colnum);
			} catch (Doctrine_Exception $exception) {
				self::$_exception = $exception;
			}
		}

		/**
		 * return option
		 */
		return $returnValue;
	}

	/**
	 * delete
	 *
	 * @param string $modelName         model to be executed
	 * @param array $params             prepared statement params
	 * @return Ambigous <boolean, number>
	 */
	public static function delete($modelName, $params)
	{
		$returnValue = FALSE;

		if (substr($modelName, 0, strlen('Default_Model_')) == 'Default_Model_' &&
			class_exists($modelName, TRUE)) {

			$connection = self::getConnection();
			if ($connection instanceof Doctrine_Connection) {
				try {
					$tableName = substr($modelName,  strlen('Default_Model_'));
					$table = new Doctrine_Table($tableName, $connection);
					$returnValue = $connection->delete($table, $params);
				} catch (Doctrine_Exception $exception) {
					self::$_exception = $exception;
				}
			}
		} else {
			self::$_exception = new L8M_Exception('Class does not exist: ' . $modelName);
		}

		/**
		 * return option
		 */
		return $returnValue;
	}

	/**
	 * truncate
	 *
	 * @param string $modelName         model to be executed
	 * @return Ambigous <boolean, number>
	 */
	public static function truncate($modelName)
	{
		$returnValue = FALSE;

		if (substr($modelName, 0, strlen('Default_Model_')) == 'Default_Model_' &&
			class_exists($modelName, TRUE)) {

			$connection = self::getConnection();
			if ($connection instanceof Doctrine_Connection) {
				try {
					$tableName = substr($modelName,  strlen('Default_Model_'));
					$table = new Doctrine_Table($tableName, $connection);

					$model = new $modelName();
					if ($model->hasRelation('Translation')) {
						$tableNameTranslation = $table->getTableName() . '_translation';
						$returnValueTranslation = $connection->exec('DELETE FROM ' . $tableNameTranslation, array());
					}

					$returnValue = $connection->exec('DELETE FROM ' . $table->getTableName(), array());
				} catch (Doctrine_Exception $exception) {
					self::$_exception = $exception;
				}
			}
		} else {
			self::$_exception = new L8M_Exception('Class does not exist: ' . $modelName);
		}

		/**
		 * return option
		 */
		return $returnValue;
	}

	/**
	 * execute
	 * @param string $query     sql query
	 * @param array $params     query parameters
	 *
	 * @return PDOStatement|Doctrine_Adapter_Statement
	 */
	public static function execute($query, array $params = array())
	{
		$returnValue = FALSE;

		$connection = self::getConnection();
		if ($connection instanceof Doctrine_Connection) {
			try {
				$returnValue = $connection->exec($query, $params);
			} catch (Doctrine_Exception $exception) {
				self::$_exception = $exception;
			}
		}

		/**
		 * return option
		 */
		return $returnValue;
	}

	public static function hasException()
	{
		$returnValue = FALSE;

		if (self::$_exception) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	public static function getException()
	{
		$returnValue = self::$_exception;
		self::$_exception = FALSE;

		return $returnValue;
	}
}