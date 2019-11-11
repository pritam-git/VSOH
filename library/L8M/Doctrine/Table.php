<?php


/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Table.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Table.php 370 2015-06-22 16:33:52Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Table
 *
 *
 */
class L8M_Doctrine_Table extends Doctrine_Table
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Checks whether database exists or not
	 *
	 * @param  string $tableName
	 * @param  string $connectionKey
	 * @return boolean
	 */
	public static function tableExists($tableName = NULL, $connectionKey = 'default')
	{
		$tableExists = FALSE;

		if ($tableName &&
			L8M_Doctrine_Database::databaseExists($connectionKey)) {

			$connection = Doctrine_Manager::getInstance()->getConnection($connectionKey);
			if ($connection instanceof Doctrine_Connection) {
				try {
					$connection->execute('SHOW COLUMNS FROM ' . $tableName);
					$tableExists = TRUE;
				} catch (Doctrine_Exception $exception) {
				}
			}
		}
		return $tableExists;
	}
}
