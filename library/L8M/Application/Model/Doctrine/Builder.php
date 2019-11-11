<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/Model/Doctrine/Builder.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Builder.php 428 2015-09-24 10:08:50Z nm $
 */

/**
 *
 *
 * L8M_Application_Model_Doctrine_Builder
 *
 *
 */
class L8M_Application_Model_Doctrine_Builder extends L8M_Application_Builder_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of required directories.
	 *
	 * @var array
	 */
	protected $_requiredDirectories = array(
		'doctrine',
		'doctrine/data',
		'doctrine/data/fixtures',
		'doctrine/data/sql',
		'doctrine/migrations',
		'doctrine/schema',
		'models',
		'models/Base',
		'services',
		'services/Base',
	);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Application_Model_Builder instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		if (!isset($this->_options['moduleName'])) {
			throw new L8M_Application_Model_Doctrine_Builder_Exception('Key "moduleName" needs to be present in options.');
		}

		if (!isset($this->_options['models'])) {
			throw new L8M_Application_Model_Doctrine_Builder_Exception('Key "models" needs to be present in options.');
		}

		if (!isset($this->_options['doctrine'])) {
			throw new L8M_Application_Model_Doctrine_Builder_Exception('Key "doctrine" needs to be present in options.');
		}

		if (!isset($this->_options['doctrine']['connection'])) {
			throw new L8M_Application_Model_Doctrine_Builder_Exception('Key "connection" needs to be present in doctrine options.');
		}

		if (!isset($this->_options['doctrine']['options']['fixturesPath'])) {
			throw new L8M_Application_Model_Doctrine_Builder_Exception('Key "fixturesPath" needs to be present in doctrine options.');
		}

		if (!isset($this->_options['doctrine']['options']['sqlPath'])) {
			throw new L8M_Application_Model_Doctrine_Builder_Exception('Key "sqlPath" needs to be present in doctrine options.');
		}

		if (!isset($this->_options['doctrine']['options']['migrationsPath'])) {
			throw new L8M_Application_Model_Doctrine_Builder_Exception('Key "migrationsPath" needs to be present in doctrine options.');
		}

		if (!isset($this->_options['doctrine']['options']['modelsPath'])) {
			throw new L8M_Application_Model_Doctrine_Builder_Exception('Key "modelsPath" needs to be present in doctrine options.');
		}

		if (!isset($this->_options['doctrine']['options']['yamlPath'])) {
			throw new L8M_Application_Model_Doctrine_Builder_Exception('Key "yamlPath" needs to be present in doctrine options.');
		}

		if (!isset($this->_options['doctrine']['options']['builder'])) {
			throw new L8M_Application_Model_Doctrine_Builder_Exception('Key "builder" needs to be present in doctrine options.');
		}

	}

	/**
	 * Builds components.
	 *
	 * @return void
	 */
	protected function _buildComponents()
	{

		/**
		 * manager
		 */
		$manager = Doctrine_Manager::getInstance();

		/**
		 * enable export of all attributes
		 */
		$manager->setAttribute(
			Doctrine_Core::ATTR_EXPORT,
			Doctrine_Core::EXPORT_ALL
		);

		/**
		 * connection
		 */
		$connection = $manager->getConnection($this->_options['doctrine']['connection']);

		/**
		 * cache
		 */
		$cache = $connection->getAttribute(Doctrine_Core::ATTR_CACHE);

		if ($cache instanceof Doctrine_Cache_Db) {

			/**
			 * remove cache
			 */
			$connection->setAttribute(
				Doctrine_Core::ATTR_CACHE,
				NULL
			);

			/**
			 * reset cache lifespan
			 */
			$connection->setAttribute(
				Doctrine_Core::ATTR_CACHE_LIFESPAN,
				NULL
			);

			/**
			 * remove query cache
			 */
			$connection->setAttribute(
				Doctrine_Core::ATTR_QUERY_CACHE,
				NULL
			);

		}

		/**
		 * check writables for models
		 */
		$writeErrors = array();
		$di = new RecursiveDirectoryIterator($this->_options['doctrine']['options']['modelsPath'], FilesystemIterator::SKIP_DOTS);
		$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($ri as $file) {
			if ($file->isFile() &&
				!is_writable($file)) {

				$writeErrors[] = 'needs to be writable <code>' . $file . '</code>';
			}
		}
		if (count($writeErrors)) {
			$message = 'could not generate models in <code class="folder">'
					 . $this->_getRelativePath($this->_options['doctrine']['options']['modelsPath'])
					 . '</code>'
			;
			$this->addMessage($message, 'error');

			foreach ($writeErrors as $writeError) {
				$this->addMessage($writeError, 'exclamation');
			}

			return $this;
		}

		/**
		 * generate models in modelPath from YAML files found in yamlPath,
		 * using the specified options (works fine for now).
		 */
		try {

			/**
			 * models
			 */
			$models = isset($this->_options['models'])
					? $this->_options['models']
					: array()
			;

			/**
			 * importSchema
			 */
			$importSchema = new Doctrine_Import_Schema();

			/**
			 * set builder options
			 */
			$importSchema->setOptions($this->_options['doctrine']['options']['builder']);

			/**
			 * attempt to generate models in the specified model path from the
			 * schema files in the specified schema path
			 */
			$importSchema->importSchema(
				$this->_options['doctrine']['options']['yamlPath'],
				'yml',
				$this->_options['doctrine']['options']['modelsPath'],
				$models
			);

			$message = 'generated models in <code class="folder">'
					 . $this->_getRelativePath($this->_options['doctrine']['options']['modelsPath'])
					 . '</code> from YAML schema files located in <code class="folder">'
					 . $this->_getRelativePath($this->_options['doctrine']['options']['yamlPath'])
					 . '</code>'
			;

			$this->addMessage($message, 'accept');

		} catch (Doctrine_Exception $exception) {

			$message = 'could not generate models in <code class="folder">'
					 . $this->_getRelativePath($this->_options['doctrine']['options']['modelsPath'])
					 . '</code> from YAML schema files located in <code class="folder">'
					 . $this->_getRelativePath($this->_options['doctrine']['options']['yamlPath'])
					 . '</code>'
			;
			$this->addMessage($message, 'error');

			$message = 'an exception was thrown <code>'
					 . $exception->getMessage()
					 . '</code>'
			;
			$this->addMessage($message, 'exclamation');

			return $this;

		}

		/**
		 * if database exists at the specified connection, drop it
		 */
		if ($this->_databaseExists($this->_options['doctrine']['connection']) === TRUE) {
			try {

//				Doctrine_Core::dumpData($this->_options['doctrine']['options']['fixturesPath'], true);

				/**
				 * drop database
				 */
				$manager->dropDatabases(array(
					$this->_options['doctrine']['connection'],
				));

				$message = 'dropped existing database <code class="database">'
						 . $this->_options['doctrine']['connection']
						 . '</code>'
				;
				$this->addMessage($message, 'accept');

			} catch (Doctrine_Exception $exception) {

				$message = 'could not drop database <code class="database">'
						 . $this->_options['doctrine']['connection']
						 . '</code>'
				;
				$this->addMessage($message, 'error');

				$message = 'an exception was thrown <code>'
						 . $exception->getMessage()
						 . '</code>'
				;
				$this->addMessage($message, 'exclamation');

				return $this;

			}
		}

		/**
		 * if no database exists at the specified connection
		 */
		if ($this->_databaseExists($this->_options['doctrine']['connection']) === FALSE) {

			try {

				/**
				 * create database
				 */
				$connection->createDatabase();

				$message = 'created database <code class="database">'
						  . $this->_options['doctrine']['connection']
						  . '</code>'
				;

				$this->addMessage($message, 'accept');

			} catch (Doctrine_Exception $exception) {

				$message = 'could not create database <code class="database">'
						 . $this->_options['doctrine']['connection']
						 . '</code>'
				;
				$this->addMessage($message, 'error');

				$message = 'an exception was thrown <code>'
						 . $exception->getMessage()
						 . '</code>'
				;
				$this->addMessage($message, 'exclamation');

				return $this;

			}
		}

		/**
		 * if database exists at the specified connection, explicitly set
		 * collation and charset
		 */
		if ($this->_databaseExists($this->_options['doctrine']['connection'])) {

			try {

				/**
				 * set default to utf8
				 */
				$connection->execute('SET NAMES utf8');
				$connection->execute('SET CHARACTER SET utf8');

				/**
				 * database
				 */
				$database = $connection->getDatabaseName();

				/**
				 * query
				 */
				$query = 'ALTER DATABASE '
					   . $connection->quoteIdentifier($database, TRUE)
					   . ' DEFAULT CHARACTER SET utf8 COLLATE utf8_bin'
				;

				$connection->execute($query);

				$message = 'explicitly set collation and charset of database <code class="database">'
						 . $this->_options['doctrine']['connection']
						 . '</code> to <code>utf8/utf8_bin</code>'
				;

				$this->addMessage($message, 'accept');

			} catch (Doctrine_Exception $exception) {

				$message = 'could not set collation and charset of database <code class="database">'
						 . $this->_options['doctrine']['connection']
						 . '</code> to <code>utf8/utf8_bin</code>'
				;
				$this->addMessage($message, 'error');

				$message = 'an exception was thrown <code>'
						 . $exception->getMessage()
						 . '</code>'
				;
				$this->addMessage($message, 'exclamation');

				return $this;

			}
		}

		/**
		 * create tables in database using models found in modelPath
		 */
		try {
			/**
			 * @todo specify options?
			 */
			Doctrine_Core::createTablesFromModels();

			$message = 'created tables in database <code class="database">'
					 . $this->_options['doctrine']['connection']
					 . '</code> from models'
			;
			$this->addMessage($message, 'accept');

		} catch (Doctrine_Exception $exception) {

			$message = 'could not create tables in database <code class="database">'
					 . $this->_options['doctrine']['connection']
					 . '</code>'
			;
			$this->addMessage($message, 'error');

			$message = 'an exception was thrown <code>'
					 . $exception->getMessage()
					 . '</code>'
			;
			$this->addMessage($message, 'exclamation');

			return $this;

		}

		/**
		 * create sql from models found in modelPath
		 */
		$sql = NULL;
		try {

			$sql = Doctrine_Core::generateSqlFromModels($this->_options['doctrine']['options']['modelsPath']);

			$message = 'created sql in <code class="folder">'
					 . $this->_getRelativePath($this->_options['doctrine']['options']['sqlPath'])
					 . '</code> from models'
			;
			$this->addMessage($message, 'accept');

		} catch (Doctrine_Exception $exception) {

			$message = 'could not create sql in <code class="folder">'
					 . $this->_getRelativePath($this->_options['doctrine']['options']['sqlPath'])
					 . '</code>'
			;
			$this->addMessage($message, 'error');

			$message = 'an exception was thrown <code>'
					 . $exception->getMessage()
					 . '</code>'
			;
			$this->addMessage($message, 'exclamation');

			return $this;

		}

		/**
		 * save sql in sqlPath
		 */
		$sqlFilePath = $this->_options['doctrine']['options']['sqlPath']
					 . DIRECTORY_SEPARATOR
					 . 'tables.sql'
	 	;

		$sqlFile = fopen($sqlFilePath, 'w');
		fwrite($sqlFile, $sql);
		fclose($sqlFile);

		/**
		 * load fixtures from fixturesPath
		 */
		try {

			Doctrine_Core::loadData($this->_options['doctrine']['options']['fixturesPath']);

			$message = 'loaded fixtures located in <code>'
					 . $this->_getRelativePath($this->_options['doctrine']['options']['fixturesPath'])
					 . '</code> into database'
			;
			$this->addMessage($message, 'accept');

		} catch (Doctrine_Exception $exception) {

			$message = 'could not load fixtures located in <code class="folder">'
					 . $this->_getRelativePath($this->_options['doctrine']['options']['fixturesPath'])
					 . '</code> into database'
			;
			$this->addMessage($message, 'error');

			$message = 'an exception was thrown <code>'
					 . $exception->getMessage()
					 . '</code>'
			;
			$this->addMessage($message, 'exclamation');

			return $this;

		}

		return $this;

	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Returns TRUE if the database with the specified connection key exists.
	 *
	 * @param  string $connectionKey
	 * @return bool
	 */
	protected function _databaseExists($connectionKey = NULL)
	{
		$returnValue = FALSE;

		$connection = Doctrine_Manager::getInstance()->getConnection($connectionKey);
		if ($connection instanceof Doctrine_Connection) {
			try {
				$connection->execute('SHOW TABLES');
				$returnValue = TRUE;
			} catch (Doctrine_Exception $exception) {
				$returnValue = FALSE;
			}
		}
		return $returnValue;
	}

}