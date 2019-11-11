<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_Abstract
 *
 *
 */
abstract class L8M_Doctrine_Import_Abstract
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A Zend_Cache instance.
	 *
	 * @var Zend_Cache
	 */
	protected $_cache = NULL;

	/**
	 * A string representing the name of the cache template in the
	 * Zend_Cache_Manager instance.
	 *
	 * @var string
	 */
	protected static $_cacheTemplate = 'default';

	/**
	 * A string representing the sql that has been used to retrieve the data.
	 *
	 * @var string
	 */
	protected $_sql = NULL;

	/**
	 * An array in which data is hard-coded.
	 *
	 * @var array
	 */
	protected $_array = NULL;

	/**
	 * A string representing the file from which data is retrieved.
	 *
	 * @var string
	 */
	protected $_file = NULL;
	/**
	 * An array with the retrieved data.
	 *
	 * @var array
	 */
	protected $_data = NULL;

	/**
	 * An integer representing the limit.
	 *
	 * @var int
	 */
	protected $_limit = NULL;

	/**
	 * An integer representing the offset.
	 *
	 * @var int
	 */
	protected $_offset = 0;

	/**
	 * An array representing the application options.
	 *
	 * @var array|Zend_Config $options
	 */
	protected $_options = NULL;

	/**
	 * A Zend_Db_Adapter_Abstract instance, representing the adapter with which
	 * a connection to dbtypo is established.
	 *
	 * @var Zend_Db_Adapter_Pdo_Mysql
	 */
	protected $_database = NULL;

	/**
	 * A Doctrine_Collection instance holding the generated Doctrine_Record
	 * instances.
	 *
	 * @var Doctrine_Collection
	 */
	protected $_dataCollection = NULL;

	/**
	 * An integere representing the total number of records.
	 *
	 * @var int
	 */
	protected $_totalRecords = NULL;

	/**
	 * An array of messages.
	 *
	 * @var array
	 */
	protected $_messages = array();

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Doctrine_Import_Abstract instance.
	 *
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($options = NULL)
	{
	    $this
	    	->setOptions($options)
	    	->_init()
	    ;
	}

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes L8M_Doctrine_Import_Abstract instance. This method should be
	 * overridden by child classes, while still making calls to this method
	 * using parent::_init();
	 *
	 * @return void
	 */
	protected function _init()
	{
		/**
		 * @todo this should be handled with more flexibility, i.e., database
		 *       adapter configuration should be retrieved and adapters
		 *       retrieved and stored in $this->_databases;
		 */

		/**
		 * databaseDefault
		 */
		if (!Zend_Registry::isRegistered('databaseDefault')) {
			throw new L8M_Doctrine_Import_Exception('Could not retrieve "database" from Zend_Registry.');
		}
		$this->_database = Zend_Registry::get('databaseDefault');

		/**
		 * cache
		 */
		if (Zend_Registry::isRegistered('Zend_Cache_Manager')) {
			$cacheManager = Zend_Registry::get('Zend_Cache_Manager');
			if ($cacheManager instanceof Zend_Cache_Manager &&
				$cacheManager->hasCacheTemplate(self::$_cacheTemplate)) {
				$this->_cache = $cacheManager->getCache(self::$_cacheTemplate);
			}
		}

	}

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Sets SQL to be used for retrieving data.
	 *
	 * @param string $sql
	 */
	public function setSql($sql = NULL)
	{
	    /**
	     * @todo adjust regular expression, as multiple statements can be
	     *       executed at once. The first could be a select, one of the
	     *       following could drop the database, delete, or whatever.
	     */
		if (!$sql ||
			!preg_match('/^SELECT/i', $sql)) {
		    throw new L8M_Doctrine_Import_Exception('For security reasons, only SELECT statements are allowed.');
		}

		$this->_sql = $sql;
		return $this;
	}

	/**
	 * Sets array in which data is stored.
	 *
	 * @param array $array
	 */
	public function setArray($array = NULL)
	{
		if (!is_array($array)) {
		    throw new L8M_Doctrine_Import_Exception('Array needs to be specified as an array.');
		}

		$this->_array = $array;
		return $this;
	}

	/**
	 * Sets file from which data is to be retrieved.
	 *
	 * @param unknown_type $file
	 */
	public function setFile($file = NULL)
	{
		if (!is_string($file) ||
			!file_exists($file) ||
			!is_file($file) ||
			!is_readable($file)) {
            throw new L8M_Doctrine_Import_Exception('File needs to be specified as a string and refer to an existing, readable file.');
		}

		if ($this->_cache) {
		    $cacheId = L8M_Cache::getCacheId('L8M_Doctrine_Import', array(
		    	'file',
		    	$file
		    ));
			if (!$this->_file = $this->_cache->load($cacheId)) {
				$this->_file = file($file);
				$this->_cache->save($this->_file);
			}
		} else {
			$this->_file = file($file);
		}

		return $this;
	}

	/**
	 * Sets limit.
	 *
	 * @param  int $limit
	 * @return L8M_Doctrine_Import_Abstract
	 */
	protected function _setLimit($limit = NULL)
	{
		if ($limit &&
		    !preg_match('/^[1-9]+[0-9]*$/', $limit)) {
			throw new L8M_Doctrine_Import_Exception('Limit needs to be specified needs to be specified as an integer or NULL.');
		}

		$this->_limit = $limit;
		return $this;
	}

	/**
	 * Sets options.
	 *
	 * @param  array|Zend_Config $options
	 * @return L8M_Doctrine_Import_Abstract
	 */
    public function setOptions($options = NULL)
    {

        if ($options instanceof Zend_Config) {
        	$options = $options->toArray();
        }

        if (!is_array($options) ||
        	count($options) == 0) {
        	throw new L8M_Doctrine_Import_Exception('Options need to be passed as an array or a Zend_Config instance.');
        }

    	/**
		 * classPrefix
		 */
   		if (!isset($options['options']['builder']['classPrefix'])) {
    		$options['options']['builder']['classPrefix'] = NULL;
		}

		$this->_options = $options;

    	return $this;
    }

	/**
	 * Sets offset.
	 *
	 * @param  int $offset
	 * @return L8M_Doctrine_Import_Abstract
	 */
	protected function _setOffset($offset = NULL)
	{
		if ($offset === NULL ||
		    !preg_match('/^[0-9]+$/', $offset)) {
			throw new L8M_Doctrine_Import_Exception('Offset needs to be specified needs to be specified as an integer.');
		}

        $this->_offset = $offset;
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
     * Returns class prefix used for generating model classes.
     *
     * @return string
     */
    public function getClassPrefix()
    {
    	if (isset($this->_options['options']['builder']['classPrefix'])) {
    		return $this->_options['options']['builder']['classPrefix'];
    	}

    	return NULL;

    }

	/**
	 * Returns name of the model class. Retrieves class prefix from options and
	 * prepends it to specified string. If no string is specified, model class
	 * name is retrieved from L8M_Doctrine_Import_Abstract instance.
	 *
	 * @param  string $className
	 * @return string
	 */
	public function getModelClassName($className = NULL)
	{

		if (!$className) {
			$className = $this->getName();
		}

		if (!is_string($className)) {
			throw new L8M_Doctrine_Import_Exception('Class name needs to be specified as a string.');
		}

		/**
		 * @todo filter class name
		 */

		$modelClassName = $this->getClassPrefix()
						. $className
		;

		return $modelClassName;

	}

	/**
	 * Returns name of imported object.
	 *
	 * @return string
	 */
	public function getName()
	{

		$className = get_class($this);

		/**
		 * model class prefix has been set in options, it is likely that we
		 * have a module specific import class
		 */
		if (isset($this->_options['options']['builder']['classPrefix'])) {

			$expression = '/^'
						. $this->_options['options']['builder']['classPrefix']
						. '(?P<modelName>.*)_Import$/'
			;

			if (preg_match($expression, $className, $match)) {
				return $match['modelName'];;
			}

		}

		/**
		 * if we have not detected the name yet, attempt to match against library
		 * import
		 */
		$expression = '/^L8M_Doctrine_Import_(?P<modelName>.*)$/';
		if (!preg_match($expression, $className, $match)) {
			throw new L8M_Doctrine_Import_Exception('Could not detect name from import class instance.');
		}

		return $match['modelName'];

	}

	/**
	 * Returns the number of records left, i.e., records that have not been
	 * imported yet.
	 *
	 * @return int
	 */
	public function getRecordsLeft()
	{
		$recordsLeft = $this->getTotalRecords()
					 - $this->_offset
					 - count($this->_data)
		;

		return $recordsLeft;
	}

	/**
	 * Returns total number of records.
	 *
	 * @return int
	 */
	public function getTotalRecords()
	{
		if ($this->_totalRecords == NULL) {
			if ($this->_sql) {
				$result = $this->_database->fetchAll('SELECT COUNT(*) AS count FROM (' . $this->_sql . ') AS dummy');
				$this->_totalRecords = $result[0]['count'];
			} else

			if ($this->_file) {
				$this->_totalRecords = count($this->_file);
			} else

			if ($this->_array) {
				$this->_totalRecords = count($this->_array);
			} else {
				$this->_totalRecords = count($this->_dataCollection);
			}
		}
		return $this->_totalRecords;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Processes import.
	 *
	 * @return L8M_Doctrine_Import_Abstract
	 */
	public function process($offset = 0, $limit = NULL)
	{
		$this
			->_setOffset($offset)
			->_setLimit($limit)
			->_retrieveData($offset, $limit)
		;

		if (is_array($this->_data)) {
			$this->_generateDataCollection();
			if (count($this->_dataCollection)>0) {
				$this->_saveDataCollection();
			}
		}
		if (method_exists($this, '_generateCustomizedDataCollection')) {
			$this->_generateCustomizedDataCollection();
		}

		return $this;
	}

	/**
	 * Converts a single file row into a data array.
	 *
	 * @param  string $row
	 * @return array
	 */
	protected function _convertFileRowIntoData($row = NULL)
	{
		return NULL;
	}

	/**
	 * Retrieves data and stores it in $this->_data
	 *
	 * @return L8M_Doctrine_Import_Abstract
	 */
	protected function _retrieveData()
	{
		/**
		 * sql has been specified
		 */
		if ($this->_sql !== NULL) {

			if ($this->_offset !== NULL &&
				$this->_limit !== NULL) {
				$this->_data = $this->_database->fetchAll($this->_sql . ' LIMIT ' . $this->_offset . ', ' . $this->_limit);
			} else {
				$this->_data = $this->_database->fetchAll($this->_sql);
			}

			$message = 'queried database with'
					 .  L8M_Geshi::parse(preg_replace('/\s+/', ' ', $this->_sql), 'mysql')
			;
			$this->addMessage($message, 'database');

			$message = 'retrieved <code>'
					 . $this->getTotalRecords()
					 . ' '
					 . $this->getModelClassName()
					 . '</code> records from <code>database</code>'
			;
			$this->addMessage($message, 'database');

		} else

		/**
		 * file has been specified
		 */
		if ($this->_file !== NULL) {
			if ($this->_offset !== NULL &&
				$this->_limit !== NULL) {

				$rows = array_slice(
					$this->_file,
					$this->_offset,
					$this->_limit
				);

			} else {

				$rows = $this->_file;

			}

			$this->_data = array();
			foreach($rows as $row) {
				$this->_data[] = $this->_convertFileRowIntoData($row);
			}

			$message = 'retrieved <code>'
					 . $this->getTotalRecords()
					 . ' '
					 . $this->getModelClassName()
					 . '</code> records from <code>file</code>'
			;
			$this->addMessage($message, 'file');

		} else

		/**
		 * array has been specified
		 */
		if ($this->_array !== NULL) {
			if ($this->_offset !== NULL &&
				$this->_limit !== NULL) {

				$this->_data = array_slice(
					$this->_array,
					$this->_offset,
					$this->_limit
				);

			} else {
				$this->_data = $this->_array;
			}

			$message = 'retrieved <code>'
					 . $this->getTotalRecords()
					 . ' '
					 . $this->getModelClassName()
					 . '</code> records from <code>array</code>'
			;
			$this->addMessage($message, 'page-code');

		}

		return $this;
	}

	/**
	 * Attempts to save Doctrine_Collection.
	 *
	 * @return L8M_Doctrine_Import_Abstract
	 */
	protected function _saveDataCollection()
	{
		if (count($this->_dataCollection)>0) {

			$message = 'added <code>'
					 . $this->getTotalRecords()
					 . ' '
					 . $this->getModelClassName()
					 . '</code> instances to a <code>Doctrine_Collection</code>'
			;
			$this->addMessage($message, 'doctrine');

			try {
				/**
				 * @todo replace this by "replace"
				 */
				$this->_dataCollection->save();

				$message = 'successfully saved <code>'
						 . $this->getTotalRecords()
						 . ' '
						 . $this->getModelClassName()
						 . '</code> records'
				;
				$this->addMessage($message, 'doctrine');

			} catch (Exception $exception) {
				/**
				 * @todo revise this
				 */
				L8M_Doctrine_Exception_Handler::handleException($exception);
			}
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
	 * @return L8M_Doctrine_Import_Abstract
	 */
	public function addMessage($message = NULL, $class = NULL)
	{
		if (!$message ||
			!is_string($message)) {
		    throw new L8M_Doctrine_Import_Exception('Message needs to be specified as a string.');
		}

		if ($class &&
			!is_string($class)) {
			throw new L8M_Doctrine_Import_Exception('If specified, class needs to be a string.');
		}

		$this->_messages[] = array(
			'class'=>$class,
			'value'=>$message,
		);

		return $this;
	}

	/**
	 * Adds messages.
	 *
	 * @param  array $messages
	 * @return L8M_Doctrine_Import_Abstract
	 */
	public function addMessages($messages = NULL)
	{
		if (is_array($messages) &&
			count($messages)>0) {

			$this->_messages = array_merge(
				$this->_messages,
				$messages
			);

		}

		return $this;
	}

	/**
	 * Returns messages.
	 *
	 * @return array
	 */
	public function getMessages()
	{
		return $this->_messages;
	}

	/**
	 *
	 *
	 * Abstract Methods
	 *
	 *
	 */

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	abstract protected function _generateDataCollection();
}