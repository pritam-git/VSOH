<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Database.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Database.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Database
 *
 *
 */
class L8M_Doctrine_Database
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
	 * @param  string $connectionKey
	 * @return boolean
	 */
	public static function databaseExists($connectionKey = 'default')
	{
		$databaseExists = FALSE;

		$connection = Doctrine_Manager::getInstance()->getConnection($connectionKey);
		if ($connection instanceof Doctrine_Connection) {
			try {
				$connection->execute('SHOW TABLES');
				$databaseExists = TRUE;
			} catch (Doctrine_Exception $exception) {
			}
		}
		return $databaseExists;
	}
}