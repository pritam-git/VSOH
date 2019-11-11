<?php


/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Generator.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Generator.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Generator
 *
 *
 */
class L8M_Doctrine_Generator
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An L8M_Doctrine_Generator instance.
	 *
	 * @var L8M_Doctrine_Generator
	 */
	protected static $_generatorInstance = NULL;

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
	 * A string representing the connection key.
	 *
	 * @var string
	 */
	protected $_connectionKey = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Doctrine_Generator instance.
	 *
	 * @return void
	 */
	protected function __construct()
	{

	}

	/**
	 * Returns L8M_Doctrine_Generator instance.
	 *
	 * @param  array|Zend_Config $options
	 * @return L8M_Doctrine_Generator
	 */
	public static function getInstance($options = NULL)
	{
		if (self::$_generatorInstance === NULL) {
			self::$_generatorInstance = new self;
			self::$_generatorInstance->setOptions($options);
		}
		return self::$_generatorInstance;
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
        	throw new L8M_Doctrine_Generator_Exception('Options need to be passed as an array or a Zend_Config instance.');
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
	 * Sets connection string to be used.
	 *
	 * @param  string $connectionString
	 * @return L8M_Doctrine_Generator
	 */
	public function setConnectionKey($connectionKey = NULL)
	{
	    if ($connectionKey &&
	        !is_string($connectionKey)) {
            throw new L8M_Doctrine_Generator_Exception('Connection string needs to be specified as a string.');
        }
        if (!Zend_Registry::isRegistered('Zend_Config')) {
            throw new L8M_Doctrine_Generator_Exception('Could not retrieve Zend_Config instance from Zend_Registry.');
        }
        $databaseConfig = Zend_Registry::get('Zend_Config')->database;
	    if (!$databaseConfig ||
            !$databaseConfig->get('connection') ||
            !$databaseConfig->connection->get($connectionKey) ||
            !$databaseConfig->connection->get($connectionKey)->get('options') ||
            !$databaseConfig->connection->get($connectionKey)->options->get('params')) {
            throw new L8M_Doctrine_Generator_Exception('Could not retrieve database connection params from from Zend_Registry.');
        }
        $connectionString = 'mysql://' .
							$databaseConfig->connection->get($connectionKey)->options->params->username .
							':' .
							$databaseConfig->connection->get($connectionKey)->options->params->password .
							'@' .
							$databaseConfig->connection->get($connectionKey)->options->params->host .
							'/' .
							$databaseConfig->connection->get($connectionKey)->options->params->dbname;
        $this->_connectionKey = $connectionKey;
        $this->_options['connection'][$this->_connectionKey]['string'] = $connectionString;
        return $this;
	}

	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

    /**
     * Returns options passed to Doctrine_Import_Schema.
     *
     * @return array
     */
    public function getGeneratorOptions()
    {
		return $this->_options['options']['builder'];
    }

    /**
     * Returns a string representing the connection key.
     *
     * @return string
     */
    public function getConnectionKey()
    {
        return $this->_connectionKey;
    }

    /**
     * Returns connection string used to connect to database.
     *
     * @return string
     */
    public function getConnectionString()
    {
    	return $this->_options['connection'][$this->getConnectionKey()]['string'];
    }

    /**
     * Returns class prefix used for generating model classes.
     *
     * @return string
     */
    public function getClassPrefix()
    {
    	return $this->_options['options']['builder']['classPrefix'];
    }

    /**
     * Returns path to Yaml fixture files.
     *
     * @return string
     */
    public function getFixturesPath()
    {
    	return $this->_options['options']['data']['fixturesPath'];
    }

	/**
	 * Returns messages as an array.
	 *
	 * @return array
	 */
	public function getMessages()
	{
		return $this->_messages;
	}

	/**
	 * Returns path in which models should be generated.
	 *
	 *  @return string
	 */
	public function getModelsPath()
	{
		return $this->_options['options']['modelsPath'];
	}

	/**
	 * Returns options.
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->_options;
	}

	/**
	 * Returns path to Yaml schema files.
	 *
	 * @return void
	 */
	public function getSchemaPath()
	{
		return $this->_options['options']['yamlPath'];
	}

	/**
	 * Returns path to folder in which sql file should be generated.
	 *
	 * @return string
	 */
	public function getSqlPath()
	{
		return $this->_options['options']['data']['sqlPath'];
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

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
		    throw new L8M_Doctrine_Generator_Exception('Doctrine is disabled.');
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
		 * generate models in modelPath from YAML files found in schemaPath,
		 * using the specified options (works fine for now).
		 */
		try {
			/**
			 * importOptions
			 */
			$importOptions = $this->getGeneratorOptions();
			/**
			 * importSchema
			 */
	        $importSchema = new Doctrine_Import_Schema();
	        $importSchema->setOptions($importOptions);
	        $importSchema->importSchema($this->getSchemaPath(), 'yml', $this->getModelsPath());
			$this->_addMessage('generated models in <code>' . $this->getModelsPath() . '</code> from YAML schema files located in <code>' . $this->getSchemaPath() . '</code>', 'doctrine');
		} catch (Doctrine_Exception $exception) {
			L8M_Doctrine_Exception_Handler::handleException($exception);
		}

		/**
		 * drop database identified by connection string
		 */
		if ($this->databaseExists() === TRUE) {
    		try {
    			Doctrine_Manager::getInstance()->dropDatabases(array($this->getConnectionKey()));
    			$this->_addMessage('dropped existing database', 'database');
    		} catch (Doctrine_Exception $exception) {
    			L8M_Doctrine_Exception_Handler::handleException($exception);
    		}
		}

		/**
		 * create database identified by connection string
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
	 * Adds message.
	 *
	 * @param  string $message
	 * @return L8M_Doctrine_Generator
	 */
	protected function _addMessage($message = NULL, $class = NULL)
	{
		if (!$message ||
			!is_string($message)) {
		    throw new L8M_Doctrine_Generator_Exception('Message needs to be specified as a string.');
		}

		$this->_messages[] = array('class'=>$class, 'value'=>$message);
		return $this;
	}

	/**
	 * Returns TRUE if the database specified with the connection string exists.
	 *
	 * @param  string $connectionKey
	 * @return bool
	 */
	public function databaseExists($connectionKey = NULL)
	{
		if (!$connectionKey) {
			$connectionKey = $this->getConnectionKey();
		}
		if (!$connectionKey) {
			throw new L8M_Doctrine_Generator_Exception('Could not retrieve connection key.');
		}
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
