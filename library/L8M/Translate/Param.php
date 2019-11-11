<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Translate/Param.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Param.php 364 2015-06-19 12:01:46Z nm $
 */

/**
 *
 *
 * L8M_Translate_Param
 *
 *
 */
class L8M_Translate_Param
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
	private static $_uparam = array();
	private static $_param = array();

	/**
	 * Not cacheable
	 *
	 * @var array
	 */
	private static $_noValueId = array();
	private static $_noValueU = array();
	private static $_noValueNormal = array();

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 * Set given values to cache.
	 *
	 * @param $id
	 * @param $lang
	 * @param $uparam
	 * @param $param
	 */
	private static function _setValue($id, $lang, $uparam, $param = NULL) {
		if (!array_key_exists($id, self::$_values)) {
			self::$_values[$id] = array(
				'params'=>array(
					$lang=>$uparam,
				),
			);
			self::$_uparam[$uparam] = $id;

			if ($param) {
				self::$_values[$id]['param'] = $param;
				self::$_param[$param] = $id;
			}
		} else {
			if ($param &&
				!array_key_exists('param', self::$_values[$id])) {

				self::$_values[$id]['param'] = $param;
				self::$_param[$param] = $id;
			}
			if (!array_key_exists($lang, self::$_values[$id]['params'])) {
				self::$_values[$id]['params'][$lang] = $uparam;
			}
		}
	}

	/**
	 * Retrieve SqlCollection by given values from cache.
	 *
	 * @param $id
	 * @param $lang
	 * @param $uparam
	 * @param $param
	 * @return boolean|L8M_Sql_ObjectCollection
	 */
	private static function _getValue($id = NULL, $lang = NULL, $uparam = NULL, $param = NULL) {
		$returnValue = FALSE;

		if ($param &&
			$lang &&
			array_key_exists($param, self::$_param)) {

			$id = self::$_param[$param];
			if (array_key_exists($id, self::$_values) &&
				array_key_exists('params', self::$_values[$id]) &&
				array_key_exists($lang, self::$_values[$id]['params'])) {

				$returnValue = new L8M_Sql_ObjectCollection(array(0=>array('id'=>$id, 'param'=>$param, 'uparam'=>self::$_values[$id]['params'][$lang], 'lang'=>$lang)), array('id', 'param', 'uparam', 'lang'));
			}
		} else
		if ($uparam &&
			array_key_exists($uparam, self::$_uparam)) {

			$id = self::$_uparam[$uparam];
			if (array_key_exists($id, self::$_values) &&
				array_key_exists('resource', self::$_values[$id])) {

				$returnValue = new L8M_Sql_ObjectCollection(array(0=>array('id'=>$id, 'param'=>self::$_values[$id]['param'], 'uparam'=>$uparam)), array('id', 'param', 'uparam'));
			}
		} else
		if ($id &&
			$lang &&
			array_key_exists($id, self::$_values) &&
			array_key_exists('param', self::$_values[$id]) &&
			array_key_exists($lang, self::$_values[$id]['params'])) {

			$returnValue = new L8M_Sql_ObjectCollection(array(0=>array('id'=>$id, 'param'=>self::$_values[$id]['param'], 'uparam'=>self::$_values[$id]['params'][$lang], 'lang'=>$lang)), array('id', 'param', 'uparam', 'lang'));
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
	 * @param $uparam
	 */
	private static function _addNoValueU($uparam) {
		self::$_noValueU[] = $uparam;
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $uparam
	 * @return boolean
	 */
	private static function _hasNoValueU($uparam) {
		$returnValue = FALSE;

		if (in_array($uparam, self::$_noValueU)) {
			$returnValue = TRUE;
		} else
		if (array_key_exists(L8M_Locale::getLang(), self::$_noValueNormal) &&
			in_array($uparam, self::$_noValueNormal[L8M_Locale::getLang()])) {

			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $lang
	 * @param $param
	 */
	private static function _addNoValueNormal($lang, $param) {
		if (!array_key_exists($lang, self::$_noValueNormal)) {
			self::$_noValueNormal[$lang] = array($param);
		} else {
			self::$_noValueNormal[$lang][] = $param;
		}
	}

	/**
	 * This is for caching reasons: do not call database again.
	 *
	 * @param $lang
	 * @param $param
	 * @return boolean
	 */
	private static function _hasNoValueNormal($lang, $param) {
		$returnValue = FALSE;

		if (array_key_exists($lang, self::$_noValueNormal) &&
			in_array($param, self::$_noValueNormal[$lang])) {

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
		$returnValue = new L8M_Sql_ObjectCollection(array(), array('id', 'param', 'uparam', 'lang'));

		return $returnValue;
	}

	/**
	 * Retrieve SqlCollection with "uparam" using parameters "param" and "lang".
	 *
	 * @param $param
	 * @param $lang
	 * @return L8M_Sql_ObjectCollection
	 */
	public static function getUparamByParamWithLang($param = NULL, $lang = NULL) {
		$returnValue = self::_getValue(NULL, $lang, NULL, $param);

		if ($returnValue === FALSE) {
			if (self::_hasNoValueNormal($lang, $param) === FALSE) {
				$returnValue = L8M_Sql::factory()
					->execute('SELECT mt.id, mt.uparam FROM param_translator AS m, param_translator_translation AS mt WHERE m.id = mt.id AND m.param = ? AND mt.lang = ? LIMIT 1', array($param, $lang))
				;

				if ($returnValue instanceof L8M_Sql_ObjectCollection &&
					$returnValue->count() == 1) {

					$sqlObj = $returnValue->getFirst();
					self::_setValue($sqlObj->id, $lang, $sqlObj->uparam, $param);
				} else {
					self::_addNoValueNormal($lang, $param);
					$returnValue = self::_getEmptyResult();
				}
			} else {
				$returnValue = self::_getEmptyResult();
			}
		}

		return $returnValue;
	}

	/**
	 * Retrieve SqlCollection with "id" using parameters "uparam".
	 *
	 * @param $uparam
	 * @return L8M_Sql_ObjectCollection
	 */
	public static function getIdByUparam($uparam = NULL) {
		$returnValue = self::_getValue(NULL, NULL, $uparam, NULL);

		if ($returnValue === FALSE) {
			if (self::_hasNoValueU($uparam) === FALSE) {
				$returnValue = L8M_Sql::factory()
					->execute('SELECT mt.id, mt.lang FROM param_translator_translation AS mt WHERE mt.uparam = ? LIMIT 1', array($uparam))
				;

				if ($returnValue instanceof L8M_Sql_ObjectCollection &&
					$returnValue->count() == 1) {

					$sqlObj = $returnValue->getFirst();
					self::_setValue($sqlObj->id, $sqlObj->lang, $uparam);
				} else {
					self::_addNoValueU($uparam);
					$returnValue = self::_getEmptyResult();
				}
			} else {
				$returnValue = self::_getEmptyResult();
			}
		}

		return $returnValue;
	}

	/**
	 * Retrieve SqlCollection with "uparam" using parameters "id" and "lang".
	 *
	 * @param $id
	 * @param $lang
	 * @return L8M_Sql_ObjectCollection
	 */
	public static function getUparamByIdWithLang($id = NULL, $lang = NULL) {
		$returnValue = self::_getValue($id, $lang, NULL, NULL);

		if ($returnValue === FALSE ) {
			if (self::_hasNoValueId($id, $lang) === FALSE) {
				$returnValue = L8M_Sql::factory()
					->execute('SELECT mt.uparam FROM param_translator_translation AS mt WHERE mt.id = ? AND mt.lang = ? LIMIT 1', array($id, $lang))
				;

				if ($returnValue instanceof L8M_Sql_ObjectCollection &&
					$returnValue->count() == 1) {

					$sqlObj = $returnValue->getFirst();
					self::_setValue($id, $lang, $sqlObj->uparam);
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