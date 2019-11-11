<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Builder.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Builder.php 55 2014-05-05 12:13:34Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Builder
 *
 *
 */
class L8M_Doctrine_Builder
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An L8M_Doctrine_Builder instance.
	 *
	 * @var L8M_Doctrine_Builder
	 */
	protected static $_builderInstance = NULL;

	/**
	 * An array of messages.
	 *
	 * @var array
	 */
	protected $_messages = array();

	/**
	 * An array of options.
	 *
	 * @var array
	 */
	protected $_options = array();

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Doctrine_Builder instance.
	 *
	 * @return void
	 */
	protected function __construct($options = NULL)
	{
		$this->setOptions($options);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns L8M_Doctrine_Generator instance.
	 *
	 * @param  array|Zend_Config $options
	 * @return L8M_Doctrine_Builder
	 */
	public static function getInstance($options = NULL)
	{
		if (self::$_builderInstance === NULL ||
			$options != NULL) {
			self::$_builderInstance = new self($options);
		}
		return self::$_builderInstance;
	}

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Sets options.
	 *
	 * @param  array|Zend_Config $options
	 * @return L8M_Doctrine_Generator
	 */
	public function setOptions($options = NULL)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}

		if (!is_array($options) ||
			count($options) == 0) {
			throw new L8M_Doctrine_Builder_Exception('Options need to be passed as an array or a Zend_Config instance.');
		}

		/**
		 * fixturesPath
		 */
		if (!isset($options['options']['data']['fixturesPath']) ||
			!$options['options']['data']['fixturesPath']) {
			$options['options']['data']['fixturesPath'] = APPLICATION_PATH . 'doctrine/data/fixtures';
		}

		/**
		 * sqlPath
		 */
		if (!isset($options['options']['data']['sqlPath']) ||
			!$options['options']['data']['sqlPath']) {
			$options['options']['data']['sqlPath'] = APPLICATION_PATH . 'doctrine/data/sql';
		}

		/**
		 * migrationsPath
		 */
		if (!isset($options['options']['migrationsPath']) ||
			!$options['options']['migrationsPath']) {
			$options['options']['migrationsPath'] = APPLICATION_PATH . 'migrations';
		}

		/**
		 * modelsPath
		 */
		if (!isset($options['options']['modelsPath']) ||
			!$options['options']['modelsPath']) {
			$options['options']['modelsPath'] = APPLICATION_PATH . 'models';
		}

		/**
		 * schemaPath
		 */
		if (!isset($options['options']['yamlPath']) ||
			!$options['options']['yamlPath']) {
			$options['options']['yamlPath'] = APPLICATION_PATH . 'doctrine/schema';
		}

		$this->_options = $options;
		return $this;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Backs up database.
	 *
	 * @return void
	 */
	public function backupDatabase()
	{
		throw new L8M_Doctrine_Builder_Exception(__METHOD__ . ' not implemented yet.');
	}

	/**
	 * Drops database.
	 *
	 * @return void
	 */
	public function dropDatabase($connectionKey = NULL)
	{

		/**
		 * connection key
		 */
		if (!$connectionKey ||
			!is_string($connectionKey)) {
			throw new L8M_Doctrine_Builder_Exception('Connection key must be specified as a string.');
		}

		/**
		 * drop database identified by connection string
		 */
		if ($this->databaseExists($connectionKey) === TRUE) {

			try {
				Doctrine_Manager::getInstance()->dropDatabases($connectionKey);
				$this->_addMessage('dropped database with key <code>' . $connectionKey . '</code>', 'database');
			} catch (Doctrine_Exception $exception) {
				L8M_Doctrine_Exception_Handler::handleException($exception);
			}
		}

	}

	/**
	 * Creates database.
	 *
	 * @return void
	 */
	public function createDatabase()
	{
		/**
		 * connection key
		 */
		$connectionKey = 'default';

		if ($this->_databaseExists($connectionKey) === FALSE) {
			try {
				Doctrine_Manager::getInstance()->createDatabases(array($connectionKey()));
				$this->_addMessage('Created database <code>' . $connectionKey . '</code>', 'database');
			} catch (Doctrine_Exception $exception) {
				L8M_Doctrine_Exception_Handler::handleException($exception);
			}
		} else {
			$this->_addMessage('Could not create database <code>' . $connectionKey . '</code, because it already exists.', 'database');
		}
	}

	/**
	 * Generates models from Yaml.
	 *
	 * @return void
	 */
	public function generateModelsFromYaml()
	{

		/**
		 * builder options present?
		 */
		if (!isset($this->_setupOptions['doctrine']['options']['builder'])) {
			throw new L8M_Doctrine_Builder_Exception('Can not generate models from yaml if no builder options have been specified.');
		}

		/**
		 * yaml path option present?
		 */
		if (!isset($this->_setupOptions['doctrine']['options']['yamlPath'])) {
			throw new L8M_Doctrine_Builder_Exception('Can not generate models from yaml if no yaml path has been specified.');
		}

		/**
		 * models path option present?
		 */
		if (!isset($this->_setupOptions['doctrine']['options']['modelsPath'])) {
			throw new L8M_Doctrine_Builder_Exception('Can not generate models from yaml if no model path has been specified.');
		}

		/**
		 * importSchemaOptions
		 */
		$importSchemaOptions = $this->_setupOptions['doctrine']['options']['builder'];

		/**
		 * modelsPath
		 */
		$modelsPath = $this->_setupOptions['doctrine']['options']['modelsPath'];

		/**
		 * schemaPath
		 */
		$schemaPath = $this->_setupOptions['doctrine']['options']['yamlPath'];

		/**
		 * importSchema
		 */
		$importSchema = new Doctrine_Import_Schema();
		$importSchema->setOptions($importSchemaOptions);

		try {
			$importSchema->importSchema($schemaPath, 'yml', $modelsPath);
			$this->_addMessage('Generated models in <code>' . $modelsPath . '</code> from YAML schema files located in <code>' . $schemaPath . '</code>', 'doctrine');
		} catch (Doctrine_Exception $exception) {
			L8M_Doctrine_Exception_Handler::handleException($exception);
		}

	}

	/**
	 * Generates tables from models.
	 *
	 * @return void
	 */
	public function generateTablesFromModels()
	{
		/**
		 * models path option present?
		 */
		if (!isset($this->_setupOptions['doctrine']['options']['modelsPath'])) {
			throw new L8M_Doctrine_Builder_Exception('Can not generate tables from models if no model path has been specified.');
		}

		/**
		 * modelsPath
		 */
		$modelsPath = $this->_setupOptions['doctrine']['options']['modelsPath'];

		/**
		 * create tables in database using models found in modelPath
		 */
		try {
			Doctrine_Core::createTablesFromModels();
			$this->_addMessage('created tables in database from models located in <code>' . $modelsPath . '</code>', 'table');
		} catch (Doctrine_Exception $exception) {
			L8M_Doctrine_Exception_Handler::handleException($exception);
		}
	}

	/**
	 * Generates SQL from models.
	 *
	 * @return void
	 */
	public function generateSqlFromModels()
	{
		/**
		 * models path option present?
		 */
		if (!isset($this->_setupOptions['doctrine']['options']['modelsPath'])) {
			throw new L8M_Doctrine_Builder_Exception('Can not generate tables from models if no model path has been specified.');
		}

		/**
		 * sql path option present?
		 */
		if (!isset($this->_setupOptions['doctrine']['options']['sqlPath'])) {
			throw new L8M_Doctrine_Builder_Exception('Can not generate tables from models if no sql path has been specified.');
		}

		/**
		 * modelsPath
		 */
		$modelsPath = $this->_options['options']['data']['modelsPath'];

		/**
		 * sqlPath
		 */
		$sqlPath = $this->_options['options']['data']['sqlPath'];

		try {

			/**
			 * create sql from models found in modelPath
			 */
			$sql = Doctrine_Core::generateSqlFromModels($modelsPath);

			/**
			 * save sql in sqlPath
			 */
			$sqlFileHandle = fopen($sqlPath . DIRECTORY_SEPARATOR . 'tables.sql', 'w');
			fwrite($sqlFileHandle, $sql);
			fclose($sqlFileHandle);

			$this->_addMessage('Generated sql in <code>' . htmlentities($sqlPath) . '</code>from models located in <code>' . htmlentities($modelsPath) . '</code>', 'file');

		} catch (Doctrine_Exception $exception) {
			L8M_Doctrine_Exception_Handler::handleException($exception);
		}

	}

	/**
	 * Loads fixtures.
	 *
	 * @return void
	 */
	public function loadFixtures()
	{
		throw new L8M_Doctrine_Builder_Exception(__METHOD__ . ' not implemented yet.');
	}

	/**
	 * Imports data.
	 *
	 * @return void
	 */
	public function importData()
	{
		throw new L8M_Doctrine_Builder_Exception(__METHOD__ . ' not implemented yet.');
	}

	/**
	 * Builds Doctrine.
	 *
	 * @return L8M_Doctrine_Generator
	 */
	public function build()
	{

		/**
		 * options
		 */
		$options = $this->getOptions();

		/**
		 * Doctrine enabled?
		 */
		if (!isset($options['enabled']) ||
			!$options['enabled']) {
			throw new L8M_Doctrine_Builder_Exception('Doctrine is disabled.');
		}

		/**
		 * disable caching when we are working with a db cache
		 */
		$connection = Doctrine_Manager::getInstance()->getConnection($this->getConnectionKey());
		$cache = $connection->getAttribute(Doctrine_Core::ATTR_CACHE);
		if ($cache instanceof Doctrine_Cache_Db) {
			$connection->setAttribute(Doctrine_Core::ATTR_CACHE, NULL);
			$connection->setAttribute(Doctrine_Core::ATTR_CACHE_LIFESPAN, NULL);
			$connection->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, NULL);
		}

		/**
		 * create database identified by connection string
		 *
		 * @todo fix (database is not created)
		 */
		if ($this->databaseExists() === FALSE) {
			try {
				Doctrine_Manager::getInstance()->createDatabases(array($this->getConnectionKey()));
				$this->_addMessage('created database', 'database');
			} catch (Doctrine_Exception $exception) {
				L8M_Doctrine_Exception_Handler::handleException($exception);
			}
		}

		/**
		 * create tables in database using models found in modelPath
		 */
		try {
			Doctrine_Core::createTablesFromModels();
			$this->_addMessage('created tables in database from models located in <code>' . $this->getModelsPath() . '</code>', 'table');
		} catch (Doctrine_Exception $exception) {
			L8M_Doctrine_Exception_Handler::handleException($exception);
		}

		/**
		 * create sql from models found in modelPath
		 */
		$sql = NULL;
		try {
			$sql = Doctrine_Core::generateSqlFromModels($this->getModelsPath());
			$this->_addMessage('generated sql from models located in <code>' . $this->getModelsPath() . '</code>', 'file');
		} catch (Doctrine_Exception $exception) {
			L8M_Doctrine_Exception_Handler::handleException($exception);
		}

		/**
		 * save sql in sqlPath
		 */
		$sqlFile = fopen($this->getSqlPath() . DIRECTORY_SEPARATOR . 'tables.sql', 'w');
		fwrite($sqlFile, $sql);
		fclose($sqlFile);

		/**
		 * load fixtures from fixturesPath
		 */
		try {
			Doctrine_Core::loadData($this->getFixturesPath());
			$this->_addMessage('loaded fixtures located in <code>' . $this->getFixturesPath()  . '</code> into database', 'doctrine');
		} catch (Doctrine_Exception $exception) {
			L8M_Doctrine_Exception_Handler::handleException($exception);
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
	 * Adds message.
	 *
	 * @param  string $message
	 * @return L8M_Doctrine_Builder
	 */
	protected function _addMessage($message = NULL, $class = NULL)
	{
		if (!$message ||
			!is_string($message)) {
			throw new L8M_Doctrine_Builder_Exception('Message needs to be specified as a string.');
		}

		$this->_messages[] = array(
								   'class'=>$class,
								   'value'=>$message,
								  );
		return $this;
	}

	protected function _getConnectionString($connectionKey = NULL)
	{

		/**
		 * loop over all specified connections
		 */
		foreach($doctrineOptions['connection'] as $connectionName=>$connectionOptions) {

			/**
			 * connection enabled
			 */
			if ($connectionOptions['enabled']) {

				/**
				 * connection string specified
				 */
				if (isset($connectionOptions['string']) &&
					is_string($connectionOptions['string'])) {
					$connectionString = $connectionOptions['string'];
				} else {

					/**
					 * retrieve database options
					 */
					$databaseOptions = $this->getOption('database');

					if (isset($databaseOptions['connection']) &&
						isset($databaseOptions['connection'][$connectionName]) &&
						isset($databaseOptions['connection'][$connectionName]['options']) &&
						isset($databaseOptions['connection'][$connectionName]['options']['params']) &&
						isset($databaseOptions['connection'][$connectionName]['options']['params']['username']) &&
						isset($databaseOptions['connection'][$connectionName]['options']['params']['password']) &&
						isset($databaseOptions['connection'][$connectionName]['options']['params']['host']) &&
						isset($databaseOptions['connection'][$connectionName]['options']['params']['dbname'])) {
						$connectionString = 'mysql://' .
											$databaseOptions['connection'][$connectionName]['options']['params']['username']  .
											':' .
											$databaseOptions['connection'][$connectionName]['options']['params']['password'] .
											'@' .
											$databaseOptions['connection'][$connectionName]['options']['params']['host'] .
											'/' .
											$databaseOptions['connection'][$connectionName]['options']['params']['dbname'];
					}
				}

				try {
					/**
					 * connection
					 */
					$doctrineConnection = Doctrine_Manager::connection($connectionString, $connectionName);
					/**
					 * utf8
					 */
					$doctrineConnection->setCharset('utf8');
					$doctrineConnection->setCollate('utf8_bin');
					/**
					 * table name format
					 */
					$doctrineConnection->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, '%s');
					/**
					 * index name format
					 */
					$doctrineConnection->setAttribute(Doctrine_Core::ATTR_IDXNAME_FORMAT, '%s');
					/**
					 * doctrine cache
					 */
					if (isset($connectionOptions['cache']) &&
						isset($connectionOptions['cache']['enabled']) &&
						$connectionOptions['cache']['enabled'] &&
						isset($connectionOptions['cache']['class']) &&
						isset($connectionOptions['cache']['options']) &&
						isset($connectionOptions['cache']['options']['lifetime'])) {
						/**
						 * cache class
						 */
						$connectionCacheClass = $connectionOptions['cache']['class'];
						/**
						 * doctrineCache
						 */
						$doctrineCache = new $connectionCacheClass($connectionOptions['cache']['options']);
						$doctrineConnection->setAttribute(Doctrine_Core::ATTR_CACHE, $doctrineCache);
						$doctrineConnection->setAttribute(Doctrine_Core::ATTR_CACHE_LIFESPAN, $connectionOptions['cache']['options']['lifetime']); //$connectionConfig->get('cache')->get('lifetime'));
						$doctrineConnection->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $doctrineCache);

						$this->_log->info('Bootstrap: Doctrine cache bootstrapped.');
					}
					$this->_log->info('Bootstrap: Database "' . $connectionName . '" bootstrapped.');
				} catch (Doctrine_Exception $exception) {

				}
			}
		}

	}

	/**
	 * Returns TRUE if the database associated with the specified connection key
	 * exists.
	 *
	 * @param  string $connectionKey
	 * @return bool
	 */
	protected function _databaseExists($connectionKey = NULL)
	{
		if (!$connectionKey ||
			!is_string($connectionKey)) {
			throw new L8M_Doctrine_Builder_Exception('Connection key needs to be specified as a string.');
		}
		if ($this->_doctrineConnectionExists($connectionKey))

		$connection = Doctrine_Manager::getInstance()->getConnection($connectionKey);
		if ($connection) {
			try {
				$connection->execute('SHOW TABLES');
				return TRUE;
			} catch (Doctrine_Exception $exception) {
				return FALSE;
			}
		}
		return FALSE;
	}

}