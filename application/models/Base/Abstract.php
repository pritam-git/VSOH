<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Base/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 422 2015-09-21 09:04:39Z nm $
 */

/**
 *
 *
 * Default_Model_Base_Abstract
 *
 *
 */
abstract class Default_Model_Base_Abstract extends Doctrine_Record
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A string representing the current language in ISO2.
	 * @var unknown_type
	 */
	protected static $_i18nIndex = NULL;

	/**
	 * An array of field names that are internationalized.
	 *
	 * @var array
	 */
	protected $_i18nFields = array();

	/**
	 * A Zend_Cache instance.
	 *
	 * @var Zend_Cache
	 */
	protected static $_cache = NULL;

	/**
	 * A Zend_Translate instance
	 *
	 * @var Zend_Translate
	 */
	protected static $_translator;

	/**
	 * Hold parent relation.
	 *
	 * @var Doctrine_Relation_LocalKey
	 */
	protected static $_parentRelations = array();

	/**
	 * easy access class name
	 *
	 * @var string
	 */
	protected $_modelClassName = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs BaseAbstract instance.
	 *
	 * @return void
	 */
	public function construct()
	{

		/**
		 * parent
		 */
		parent::construct();

		/**
		 * className
		 */
		$this->_modelClassName = $this->getTable()->getClassnameToReturn();
		$this->_initParent();

		/**
		 * relations
		 */
		$modelRelations = $this->getTable()->getRelations();

		/**
		 * language
		 */
		self::$_i18nIndex = self::getLanguage();
		if ($this->getTable()->hasRelation('Translation')) {
			$transCols = $this->Translation->getTable()->getColumns();
			unset($transCols['id']);
			unset($transCols['lang']);
			unset($transCols['created_at']);
			unset($transCols['updated_at']);
			unset($transCols['deleted_at']);
			$this->_i18nFields = array_keys($transCols);
		}
	}

	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

	/**
	 * Returns a string representing the index currently used for
	 * internationalized fields.
	 *
	 * @return string
	 */
	public function getI18nIndex()
	{
		return self::$_i18nIndex;
	}

	/**
	 * Returns an array of field names that are internationalized.
	 *
	 * @return array
	 */
	public function getI18nFields()
	{
		return $this->_i18nFields;
	}

	/**
	 *
	 *
	 * Helper  Methods
	 *
	 *
	 */

	/**
	 * Returns modelForm with specifiede options options.
	 *
	 * @param string $name
	 * @param array $options
	 * @param array|Zend_Config $extraOptions
	 *
	 * @return L8M_ModelForm_Base
	 */
	public function getForm($name = NULL, $options = NULL, $extraOptions = NULL)
	{
		$extraOptions = array(
			'model'=>get_class($this),
			'name'=>$name,
			'options'=>$extraOptions,
		);
		return L8M_ModelForm_Base::create(get_class($this), $options, $extraOptions);
	}

	/**
	 * Returns Zend_Translate instance.
	 *
	 * @return Zend_Translate
	 */
	public function getTranslator()
	{
		if (self::$_translator === NULL) {
			if (Zend_Registry::isRegistered('Zend_Translate') &&
				(NULL != $translator = Zend_Registry::get('Zend_Translate')) &&
				($translator instanceof Zend_Translate)) {
				self::$_translator = $translator;
			} else {
				self::$_translator = FALSE;
			}
		}
		return self::$_translator;
	}

	/**
	 *
	 *
	 * Magic Methods
	 *
	 *
	 */

	/**
	 * Sets the value of a field, obeying the current locale.
	 *
	 * @param  string $name
	 * @param  mixed  $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		if (is_array($this->getI18nFields()) &&
			in_array($name, $this->getI18nFields())) {

			$this->Translation[$this->getI18nIndex()]->$name = $value;
			$this->_removeTranslationFromCache();
		} else {
			parent::__set($name, $value);
			$this->_removeFieldNameFromCache();
		}
	}

	/**
	 * Gets the value of a field, obeying the current locale.
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		$returnValue = NULL;
		if ($name == 'id' ||
			$this->hasRelation($name)) {

			$returnValue = parent::__get($name);
		} else
		if (is_array($this->getI18nFields()) &&
			in_array($name, $this->getI18nFields())) {

			$returnValue = $this->_getTranslationFromCache($name, $this->getI18nIndex());

			if ($returnValue === FALSE) {
				$returnValue = $this->Translation[$this->getI18nIndex()]->$name;
			}
			$this->_setTranslationToCache($returnValue, $name, $this->getI18nIndex());
		} else
		if ($name == 'Referenced' &&
			substr(get_class($this), -1 * strlen('OptionModelValues')) == 'OptionModelValues' &&
			$this->getTable()->hasColumn('referenced_id')) {

			$meAlias = substr(get_class($this), strlen('Default_Model_'));
			$backToM2nRelationName = substr($meAlias, 0, strlen($meAlias) - strlen('Values'));

			if ($this->getTable()->hasRelation($backToM2nRelationName)) {
				$backToM2nModel = $this->$backToM2nRelationName;

				$tmpM2nPos = strpos($backToM2nRelationName, 'M2n');

				if ($backToM2nModel &&
					$tmpM2nPos !== FALSE) {

					$optionModelRelationName = substr($backToM2nRelationName, $tmpM2nPos + 3);
					$optionModel = $backToM2nModel->$optionModelRelationName;

					if ($optionModel) {
						$nameModel = $optionModel->ModelName;
						if ($nameModel) {
							$theModelName = $nameModel->name;

							/**
							 * load meta tags from meta configuration
							 */
							if (class_exists($theModelName, TRUE)) {
								try {
									$returnValue = Doctrine_Query::create()
										->from($theModelName . ' m')
										->addWhere('m.id = ? ', array($this->referenced_id))
										->limit(1)
										->execute()
										->getFirst()
									;

								} catch (Doctrine_Connection_Exception $exception) {
									/**
									 * @todo maybe do something
									 */
								}
							}
						}
					}
				}
			}

			if ($returnValue === NULL) {
				throw new L8M_Exception('Something went wrong processing referenced Model.');
			}
		} else {
			$returnValue = $this->_getFieldNameFromCache($name);
			if ($returnValue === FALSE) {
				$returnValue = parent::__get($name);
			}
			$this->_setFieldNameToCache($returnValue, $name);
		}
		return $returnValue;
	}

	/**
	 * Returns current language.
	 *
	 * @return string
	 */
	public static function getLanguage()
	{
		if (self::$_i18nIndex === NULL) {
			self::$_i18nIndex = L8M_Locale::getLang();
		}
		return self::$_i18nIndex;
	}

	/**
	 * Returns a Doctrine_Query
	 *
	 * @param String $className
	 * @param String $queryClassKey
	 * @return Doctrine_Query
	 */
	public static function createQuery($className = NULL, $queryClassKey = 'm')
	{
		if (!$className &&
			version_compare(PHP_VERSION, '5.3.0') >= 0) {

			$className = get_called_class();
			if (substr($className, 0, strlen('Default_Model_')) != 'Default_Model_') {
				throw new L8M_Exception('Can not determine the right class for the doctrine query. Class name need to start with "Default_Model_".');
			}
		} else
		if (!class_exists($className)) {
			throw new L8M_Exception('Can not determine the right class for the doctrine query.');
		}

		$doctrineQuery = Doctrine_Query::create()
			->from($className . ' ' . $queryClassKey)
		;

		return $doctrineQuery;
	}

	/**
	 * Returns a L8M_Sql
	 *
	 * @param string|Defult_Model_Base_Abstract $model
	 * @param string $lang
	 * @param string $connectionName
	 * @return L8M_Sql
	 */
	public static function createSqlQuery($className = NULL, $withTranslation = FALSE, $lang = NULL, $connectionName = 'default')
	{
		if (!$className &&
			version_compare(PHP_VERSION, '5.3.0') >= 0) {

			$className = get_called_class();
			if (substr($className, 0, strlen('Default_Model_')) != 'Default_Model_') {
				throw new L8M_Exception('Can not determine the right class for the doctrine query. Class name need to start with "Default_Model_".');
			}
		} else
		if (!class_exists($className)) {
			throw new L8M_Exception('Can not determine the right class for the doctrine query.');
		}

		$sqlQuery = L8M_Sql::factory($className, $withTranslation, $lang, $connectionName);

		return $sqlQuery;
	}

	/**
	 * Returns Model by column and value concerning Class
	 *
	 * @param String $columnName
	 * @param mixed $value
	 * @param String $className
	 * @return Default_Model_Base_Abstract
	 */
	public static function getModelByColumn($columnName = NULL, $value = NULL, $className = NULL)
	{
		$doctrineQuery = self::createQuery($className);
		$model = $doctrineQuery
			->addWhere('m.' . $columnName . ' = ? ', array($value))
			->execute()
			->getFirst()
		;
		return $model;
	}

	/**
	 * Returns SqlObject by column and value concerning Class
	 *
	 * @param String $columnName
	 * @param mixed $value
	 * @param String $className
	 * @return L8M_Sql_Object
	 */
	public static function getSqlObjectByColumn($columnName = NULL, $value = NULL, $className = NULL)
	{
		$sqlQuery = self::createSqlQuery($className);
		$sqlObject = $sqlQuery
			->addWhere($columnName . ' = ? ', array($value))
			->execute()
			->getFirst()
		;
		return $sqlObject;
	}

	/**
	 * Returns Model by id concerning Class
	 *
	 * @param integer $id
	 * @param String $className
	 * @return Default_Model_Base_Abstract
	 */
	public static function getModelByID($id = NULL, $className = NULL)
	{
		$model = self::getModelByColumn('id', $id, $className);
		return $model;
	}

	/**
	 * Returns SqlObject by id concerning Class
	 *
	 * @param integer $id
	 * @param String $className
	 * @return L8M_Sql_Object
	 */
	public static function getSqlObjectByID($id = NULL, $className = NULL)
	{
		$sqlObject = self::getSqlObjectByColumn('id', $id, $className);
		return $sqlObject;
	}

	/**
	 * Returns Model by short concerning Class
	 *
	 * @param String $short
	 * @param String $className
	 * @return Default_Model_Base_Abstract
	 */
	public static function getModelByShort($short = NULL, $className = NULL)
	{
		$model = self::getModelByColumn('short', $short, $className);
		return $model;
	}

	/**
	 * Returns SqlObject by short concerning Class
	 *
	 * @param String $short
	 * @param String $className
	 * @return L8M_Sql_Object
	 */
	public static function getSqlObjectByShort($short = NULL, $className = NULL)
	{
		$sqlObject = self::getSqlObjectByColumn('short', $short, $className);
		return $sqlObject;
	}

	/**
	 * Returns Model by resource concerning Class
	 *
	 * @param String $resource
	 * @param String $className
	 * @return Default_Model_Base_Abstract
	 */
	public static function getModelByResource($resource = NULL, $className = NULL)
	{
		$model = self::getModelByColumn('resource', $resource, $className);
		return $model;
	}

	/**
	 * Returns SqlObject by resource concerning Class
	 *
	 * @param String $resource
	 * @param String $className
	 * @return L8M_Sql_Object
	 */
	public static function getSqlObjectByResource($resource = NULL, $className = NULL)
	{
		$sqlObject = self::getSqlObjectByColumn('resource', $resource, $className);
		return $sqlObject;
	}

	/**
	 * init parent
	 *
	 * @throws L8M_Exception
	 */
	protected function _initParent()
	{
		if (!array_key_exists($this->_modelClassName, self::$_parentRelations)) {

			/**
			 * need to filter irrelevant models cause of memmory limits
			 */
			$selfAlias = str_replace('Default_Model_', '', $this->_modelClassName);
			if (strpos($selfAlias, 'M2n') === FALSE &&
				$this->getTable()->hasRelation($selfAlias)) {

				$parentRelation = $this->getTable()->getRelation($selfAlias, FALSE);
				if ($parentRelation instanceof Doctrine_Relation_LocalKey) {
					self::$_parentRelations[$this->_modelClassName] = $selfAlias;
				}
			}
			if (!array_key_exists($this->_modelClassName, self::$_parentRelations)) {
				self::$_parentRelations[$this->_modelClassName] = FALSE;
			}
		}
	}

	/**
	 * Returns parent relation.
	 *
	 * @return Ambigous <NULL, Doctrine_Relation_LocalKey>
	 */
	public function getParentRelation()
	{
		$returnValue = NULL;
		if (array_key_exists($this->_modelClassName, self::$_parentRelations) &&
			self::$_parentRelations[$this->_modelClassName]) {

			$returnValue = $this->getTable()->getRelation(self::$_parentRelations[$this->_modelClassName], FALSE);
		}

		return $returnValue;
	}

	/**
	 * Retrieve parent model.
	 *
	 * @return Ambigous <NULL, boolean, Default_Model_Base_Abstract>
	 */
	public function getParentModel()
	{
		$returnValue = NULL;
		$parentRelation = $this->getParentRelation();

		if ($parentRelation) {
			$returnValue = FALSE;
			$parentColumn = $parentRelation->getLocalColumnName();
			$parentAlias = $parentRelation->getAlias();

			if ($this->$parentColumn) {
				$returnValue = $this->$parentAlias;
			}
		}

		return $returnValue;
	}

	/**
	 * Retrieve collection of children.
	 *
	 * @return Ambigous <NULL, Doctrine_Collection>
	 */
	public function getChildrenCollection()
	{
		$returnValue = NULL;
		$parentRelation = $this->getParentRelation();

		if ($parentRelation) {
			$parentColumn = $parentRelation->getLocalColumnName();
			$ownColumn = $parentRelation->getForeignColumnName();
			$className = $parentRelation->getClass();

			$childrenCollection = Doctrine_Query::create()
				->from($className .' m')
				->addWhere('m.' . $parentColumn . ' = ?', array($this->$ownColumn))
				->execute()
			;
			$returnValue = $childrenCollection;
		}

		return $returnValue;
	}

	/**
	 * Returns Zend_Cache instance.
	 *
	 * @return Zend_Cache
	 */
	protected static function _getCache()
	{
		if (self::$_cache === NULL) {
			/* @var $locale Zend_Locale */
			if (Zend_Registry::isRegistered('cache') &&
				(FALSE != $config = Zend_Registry::get('Zend_Config')) &&
				$config->get('doctrine') &&
				$config->doctrine->get('cache') &&
				$config->doctrine->cache->get('enabled') &&
				(NULL != $cache = Zend_Registry::get('cache')) &&
				substr(get_class($cache), 0, 10) == 'Zend_Cache') {

				self::$_cache = $cache;
			} else {
				self::$_cache = FALSE;
			}
		}
		return self::$_cache;
	}


	/**
	 * Returns name of the model instance.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_get('name');
	}

	protected function _getTranslationFromCache($fieldName = NULL, $langShort = NULL)
	{
		$returnValue = FALSE;

		if ($fieldName &&
			$langShort &&
			$cache = L8M_Cache::getCache('Default_Model_Abstract_Translation')) {

			$cacheValue = $cache->load($this->_getCacheName());

			if ($cacheValue &&
				is_array($cacheValue) &&
				isset($cacheValue[$fieldName]) &&
				is_array($cacheValue[$fieldName]) &&
				array_key_exists($langShort, $cacheValue[$fieldName])) {

				$returnValue = $cacheValue[$fieldName][$langShort];
			}
		}
		return $returnValue;
	}

	protected function _getFieldNameFromCache($fieldName = NULL)
	{
		$returnValue = FALSE;

		if ($fieldName &&
			$cache = L8M_Cache::getCache('Default_Model_Abstract')) {

			$cacheValue = $cache->load($this->_getCacheName());

			if ($cacheValue &&
				is_array($cacheValue) &&
				array_key_exists($fieldName, $cacheValue)) {

				$returnValue = $cacheValue[$fieldName];
			}
		}
		return $returnValue;
	}

	protected function _setTranslationToCache($modelTranslation = NULL, $fieldName = NULL, $langShort = NULL)
	{
		$returnValue = FALSE;

		if ($modelTranslation !== FALSE &&
			$fieldName &&
			$langShort &&
			$cache = L8M_Cache::getCache('Default_Model_Abstract_Translation')) {

			$cacheValue = $cache->load($this->_getCacheName());

			if ($cacheValue &&
				is_array($cacheValue)) {

				if (isset($cacheValue[$fieldName]) &&
					is_array($cacheValue[$fieldName])) {

					$cacheValue[$fieldName][$langShort] = $modelTranslation;
				} else {
					$cacheValue[$fieldName] = array($langShort=>$modelTranslation);
				}
			} else {
				$cacheValue = array();
				$cacheValue[$fieldName] = array($langShort=>$modelTranslation);
			}
			$cache->save($cacheValue, $this->_getCacheName());
		}
	}

	protected function _setFieldNameToCache($modelFieldContent = NULL, $fieldName = NULL)
	{
		$returnValue = FALSE;

		if ($modelFieldContent !== FALSE &&
			$fieldName &&
			$cache = L8M_Cache::getCache('Default_Model_Abstract')) {

			$cacheValue = $cache->load($this->_getCacheName());

			if ($cacheValue &&
				is_array($cacheValue)) {

				$cacheValue[$fieldName] = $modelFieldContent;
			} else {
				$cacheValue = array();
				$cacheValue[$fieldName] = $modelFieldContent;
			}
			$cache->save($cacheValue, $this->_getCacheName());
		}
	}

	/**
	 * remove from cache
	 */
	public function _removeTranslationFromCache()
	{
		if ($this->id &&
			$cache = L8M_Cache::getCache('Default_Model_Abstract_Translation')) {

			$cacheValue = $cache->remove($this->_getCacheName());
		}
	}

	/**
	 * remove from cache
	 */
	public function _removeFieldNameFromCache()
	{
		if ($this->id &&
			$cache = L8M_Cache::getCache('Default_Model_Abstract')) {

			$cacheValue = $cache->remove($this->_getCacheName());
		}
	}

	/**
	 * get cache name
	 *
	 * @return string
	 */
	protected function _getCacheName()
	{
		$returnValue = NULL;
		if (array_key_exists('id', $this->_id)) {
			$returnValue = get_class($this) . '_' . $this->_id['id'];
		} else
		if ($this->id) {
			$returnValue = get_class($this) . '_' . $this->id;
		}

		return $returnValue;
	}

	/**
	 * deletes this data access object and all the related composites
	 * this operation is isolated by a transaction
	 *
	 * this event can be listened by the onPreDelete and onDelete listeners
	 *
	 * @return boolean	  true if successful
	 */
	public function delete(Doctrine_Connection $conn = null)
	{
		if ($this->id &&
			$this->hasRelation('Translation')) {

			$this->_removeTranslationFromCache();
		}
		$this->_removeFieldNameFromCache();

		return parent::delete($conn);
	}

	/**
	 * applies the changes made to this object into database
	 * this method is smart enough to know if any changes are made
	 * and whether to use INSERT or UPDATE statement
	 *
	 * this method also saves the related components
	 *
	 * @param Doctrine_Connection $conn     optional connection parameter
	 * @throws Exception                    if record is not valid and validation is active
	 * @return void
	 */
	public function save(Doctrine_Connection $conn = null)
	{
		if ($this->id &&
			$this->hasRelation('Translation')) {

			$this->_removeTranslationFromCache();
		}
		$this->_removeFieldNameFromCache();

		parent::save($conn);
	}
}