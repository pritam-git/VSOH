<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Translate/Resource.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Resource.php 364 2015-06-19 12:01:46Z nm $
 */

/**
 *
 *
 * L8M_Translate_Resource
 *
 *
 */
class L8M_Translate_Resource
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
	 * Main Cache
	 *
	 * @var array
	 */
	private static $_values = array();

	/**
	 * Cache Helper
	 *
	 * @var array
	 */
	private static $_uresource = array();
	private static $_resource = array();

	/**
	 * Not cacheable
	 *
	 * @var array
	 */
	private static $_noValueId = array();
	private static $_noValueU = array();
	private static $_noValueNormal = array();
	private static $_noValueNormalU = array();

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 *
	 * @param $id
	 * @param $lang
	 * @param $uresource
	 * @param $resource
	 */
	private static function _setValue($id, $lang, $uresource, $resource = NULL) {
		if (!array_key_exists($id, self::$_values)) {
			self::$_values[$id] = array(
				'resources'=>array(
					$lang=>$uresource,
				),
			);
			self::$_uresource[$uresource] = $id;

			if ($resource) {
				self::$_values[$id]['resource'] = $resource;
				self::$_resource[$resource] = $id;
			}
		} else {
			if ($resource &&
				!array_key_exists('resource', self::$_values[$id])) {

				self::$_values[$id]['resource'] = $resource;
				self::$_resource[$resource] = $id;
			}
			if (!array_key_exists($lang, self::$_values[$id]['resources'])) {
				self::$_values[$id]['resources'][$lang] = $uresource;
			}
		}
	}

	/**
	 * Retrieve SqlCollection by given values from cache.
	 *
	 * @param $id
	 * @param $lang
	 * @param $uresource
	 * @param $resource
	 * @return boolean|L8M_Sql_ObjectCollection
	 */
	private static function _getValue($id = NULL, $lang = NULL, $uresource = NULL, $resource = NULL) {
		$returnValue = FALSE;

		if ($resource &&
			$lang &&
			array_key_exists($resource, self::$_resource)) {

			$id = self::$_resource[$resource];
			if (array_key_exists($id, self::$_values) &&
				array_key_exists('resources', self::$_values[$id]) &&
				array_key_exists($lang, self::$_values[$id]['resources'])) {

				$returnValue = new L8M_Sql_ObjectCollection(array(0=>array('id'=>$id, 'resource'=>$resource, 'uresource'=>self::$_values[$id]['resources'][$lang], 'lang'=>$lang)), array('id', 'resource', 'uresource', 'lang'));
			}
		} else
		if ($uresource &&
			array_key_exists($uresource, self::$_uresource)) {

			$id = self::$_uresource[$uresource];
			if (array_key_exists($id, self::$_values) &&
				array_key_exists('resource', self::$_values[$id])) {

				$returnValue = new L8M_Sql_ObjectCollection(array(0=>array('id'=>$id, 'resource'=>self::$_values[$id]['resource'], 'uresource'=>$uresource)), array('id', 'resource', 'uresource'));
			}
		} else
		if ($id &&
			$lang &&
			array_key_exists($id, self::$_values) &&
			array_key_exists('resource', self::$_values[$id]) &&
			array_key_exists($lang, self::$_values[$id]['resources'])) {

			$returnValue = new L8M_Sql_ObjectCollection(array(0=>array('id'=>$id, 'resource'=>self::$_values[$id]['resource'], 'uresource'=>self::$_values[$id]['resources'][$lang], 'lang'=>$lang)), array('id', 'resource', 'uresource', 'lang'));
		}

		return $returnValue;
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $id
	 * @param $lang
	 */
	private static function _addNoValueId($id, $lang) {
		if (!array_key_exists($lang, self::$_noValueId)) {
			self::$_noValueId[$lang] = array($id);
		} else {
			self::$_noValueId[$lang][] = $id;
		}
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $id
	 * @param $lang
	 * @return boolean
	 */
	private static function _hasNoValueId($id, $lang) {
		$returnValue = FALSE;

		if (array_key_exists($lang, self::$_noValueId) &&
			in_array($id, self::$_noValueId[$lang])) {

			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $uresource
	 */
	private static function _addNoValueU($uresource) {
		self::$_noValueU[] = $uresource;
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $uresource
	 * @return boolean
	 */
	private static function _hasNoValueU($uresource) {
		$returnValue = FALSE;

		if (in_array($uresource, self::$_noValueU)) {
			$returnValue = TRUE;
		} else
		if (array_key_exists(L8M_Locale::getLang(), self::$_noValueNormal) &&
			in_array($uresource, self::$_noValueNormal[L8M_Locale::getLang()])) {

			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $lang
	 * @param $resource
	 */
	private static function _addNoValueNormal($lang, $resource) {
		if (!array_key_exists($lang, self::$_noValueNormal)) {
			self::$_noValueNormal[$lang] = array($resource);
		} else {
			self::$_noValueNormal[$lang][] = $resource;
		}
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $lang
	 * @param $resource
	 */
	private static function _hasNoValueNormal($lang, $resource) {
		$returnValue = FALSE;

		if (array_key_exists($lang, self::$_noValueNormal) &&
			in_array($resource, self::$_noValueNormal[$lang])) {

			$returnValue = TRUE;
		} else
		if (array_key_exists($lang, self::$_noValueNormalU) &&
			in_array($resource, self::$_noValueNormalU[$lang])) {

			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $lang
	 * @param $resource
	 */
	private static function _addNoValueNormalU($lang, $uresource) {
		if (!array_key_exists($lang, self::$_noValueNormalU)) {
			self::$_noValueNormalU[$lang] = array($uresource);
		} else {
			self::$_noValueNormalU[$lang][] = $uresource;
		}
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $lang
	 * @param $resource
	 * @return boolean
	 */
	private static function _hasNoValueNormalU($lang, $uresource) {
		$returnValue = FALSE;

		if (array_key_exists($lang, self::$_noValueNormalU) &&
			in_array($uresource, self::$_noValueNormalU[$lang])) {

			$returnValue = TRUE;
		} else
		if (array_key_exists($lang, self::$_noValueNormal) &&
			in_array($uresource, self::$_noValueNormal[$lang])) {

			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * Create and send back an empty result.
	 *
	 * @return L8M_Sql_ObjectCollection
	 */
	private static function _getEmptyResult() {
		$returnValue = new L8M_Sql_ObjectCollection(array(), array('id', 'resource', 'uresource', 'lang'));

		return $returnValue;
	}

	/**
	 * Retrieve SqlCollection with "resource" using resourceeters "uresource" and "lang".
	 *
	 * @param $resource
	 * @param $lang
	 * @param $doCache
	 * @return L8M_Sql_ObjectCollection
	 */
	public static function getResourceByUresourceWithLang($uresource = NULL, $lang = NULL, $doCache = TRUE) {
		$returnValue = self::_getValue(NULL, $lang, $uresource, NULL);

		if ($returnValue === FALSE) {
			if (self::_hasNoValueNormalU($lang, $uresource) === FALSE) {
				$returnValue = L8M_Sql::factory()
					->execute('SELECT m.id, m.resource FROM resource_translator AS m, resource_translator_translation AS mt WHERE m.id = mt.id AND mt.uresource LIKE ? AND mt.lang = ? LIMIT 1', array($uresource, $lang))
				;

				if ($returnValue instanceof L8M_Sql_ObjectCollection &&
					$returnValue->count() == 1) {

					$sqlObj = $returnValue->getFirst();
					if ($doCache) {
						self::_setValue($sqlObj->id, $lang, $uresource, $sqlObj->resource);
					}
				} else {
					if ($doCache) {
						self::_addNoValueNormalU($lang, $uresource);
					}
					$returnValue = self::_getEmptyResult();
				}
			} else {
				$returnValue = self::_getEmptyResult();
			}
		}

		return $returnValue;
	}

	/**
	 * Retrieve SqlCollection with "uresource" using resourceeters "resource" and "lang".
	 *
	 * @param $resource
	 * @param $lang
	 * @return L8M_Sql_ObjectCollection
	 */
	public static function getUresourceByResourceWithLang($resource = NULL, $lang = NULL) {
		$returnValue = self::_getValue(NULL, $lang, NULL, $resource);

		if ($returnValue === FALSE) {
			if (self::_hasNoValueNormal($lang, $resource) === FALSE) {
				$returnValue = L8M_Sql::factory()
					->execute('SELECT mt.id, mt.uresource FROM resource_translator AS m, resource_translator_translation AS mt WHERE m.id = mt.id AND m.resource = ? AND mt.lang = ? LIMIT 1', array($resource, $lang))
				;

				if ($returnValue instanceof L8M_Sql_ObjectCollection &&
					$returnValue->count() == 1) {

					$sqlObj = $returnValue->getFirst();
					self::_setValue($sqlObj->id, $lang, $sqlObj->uresource, $resource);
				} else {
					self::_addNoValueNormal($lang, $resource);
					$returnValue = self::_getEmptyResult();
				}
			} else {
				$returnValue = self::_getEmptyResult();
			}
		}

		return $returnValue;
	}

	/**
	 * Retrieve SqlCollection with "id" using resourceeters "uresource".
	 *
	 * @param $uresource
	 * @return L8M_Sql_ObjectCollection
	 */
	public static function getIdByUresource($uresource = NULL) {
		$returnValue = self::_getValue(NULL, NULL, $uresource, NULL);

		if ($returnValue === FALSE) {
			if (self::_hasNoValueU($uresource)) {
				$returnValue = L8M_Sql::factory()
					->execute('SELECT mt.id, mt.lang FROM resource_translator_translation AS mt WHERE mt.uresource = ? LIMIT 1', array($uresource))
				;

				if ($returnValue instanceof L8M_Sql_ObjectCollection &&
					$returnValue->count() == 1) {

					$sqlObj = $returnValue->getFirst();
					self::_setValue($sqlObj->id, $sqlObj->lang, $uresource);
				} else {
					self::_addNoValueU($uresource);
					$returnValue = self::_getEmptyResult();
				}
			} else {
				$returnValue = self::_getEmptyResult();
			}
		}

		return $returnValue;
	}

	/**
	 * Retrieve SqlCollection with "uresource" using resourceeters "id" and "lang".
	 *
	 * @param $id
	 * @param $lang
	 * @return L8M_Sql_ObjectCollection
	 */
	public static function getUresourceByIdWithLang($id = NULL, $lang = NULL) {
		$returnValue = self::_getValue($id, $lang, NULL, NULL);

		if ($returnValue === FALSE) {
			if (self::_hasNoValueId($id, $lang)) {
				$returnValue = L8M_Sql::factory()
					->execute('SELECT mt.id, mt.uresource FROM resource_translator_translation AS mt WHERE mt.id = ? AND mt.lang = ? LIMIT 1', array($id, $lang))
				;

				if ($returnValue instanceof L8M_Sql_ObjectCollection &&
					$returnValue->count() == 1) {

					$sqlObj = $returnValue->getFirst();
					self::_setValue($id, $lang, $sqlObj->uresource);
				} else {
					self::_addNoValueId($id, $lang);
					$returnValue = self::_getEmptyResult();
				}
			} else {
				$returnValue = self::_getEmptyResult();
			}
		}

		return $returnValue;
	}
}