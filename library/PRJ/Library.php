<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/Library.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Library.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * PRJ_Library
 *
 *
 */
class PRJ_Library
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
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	static public function getRightsEmailText($plain = FALSE) {

		$returnValue = '';

		$rightsArray = array(
			'business-information',
			'instructions-of-cancellation',
			'terms-and-conditions',
			'privacy',
		);

		foreach ($rightsArray as $rightsElement) {

			$siteRightsModel = Default_Model_SiteRights::getModelByShort($rightsElement, 'Default_Model_SiteRights');

			if ($siteRightsModel instanceof Default_Model_SiteRights) {

				if ($plain == TRUE) {

					$returnValue .= $siteRightsModel->email_text_plain;

				} else {

					$returnValue .= $siteRightsModel->email_text_html;
				}


			}


		}

		return $returnValue;

	}

	public static function float_rand($min, $max, $round = 0) {

		//validate input
		if ($min > $max) {

			list($min, $max) = array($max, $min);
		}

		$returnValue = $min + mt_rand() / mt_getrandmax() * ($max - $min);

		if ($round > 0) {

			$returnValue = round($returnValue,$round);
		}

		return $returnValue;

	}

	public static function isAssoc(array $arr)
	{
		if (array() === $arr) return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	/**
	 * check if product is valid, returns warning messages, if its not
	 * @param  $productElement
	 * @return array
	 */
	public static function isProductValid($productElement) {

		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();
		$warningMessages = array();

		if ($productElement['product_number'] == '0') {
			$warningMessages[] = $viewFromMVC->translate('Es ist keine Produktnummer angegeben', 'de');
		}

		if ($productElement['buying_price'] < 0.01) {
			$warningMessages[] = $viewFromMVC->translate('Es ist kein Einkaufspreis angegeben', 'de');
		}

		if ($productElement['ps_profit'] < 1) {
			$warningMessages[] = $viewFromMVC->translate('Für Preis Standard ist kein Profitsatz angegeben', 'de');
		}

		if ($productElement['pe_profit'] < 1) {
			$warningMessages[] = $viewFromMVC->translate('Für Preis Express ist kein Profitsatz angegeben', 'de');
		}

		if ($productElement['po_profit'] < 1) {
			$warningMessages[] = $viewFromMVC->translate('Für Preis Overnight ist kein Profitsatz angegeben', 'de');
		}

		if ($productElement['ps_delivery_days'] < 1) {
			$warningMessages[] = $viewFromMVC->translate('Für Preis Standard sind keine Liefertage angegeben', 'de');
		}

// 		if ($productElement['pe_delivery_days'] < 1) {
// 			$warningMessages[] = $viewFromMVC->translate('Für Preis Express sind keine Liefertage angegeben', 'de');
// 		}

// 		if ($productElement['po_delivery_days'] < 1) {
// 			$warningMessages[] = $viewFromMVC->translate('Für Preis Overnight sind keine Liefertagen angegeben', 'de');
// 		}

		if ($productElement['ps_work_days'] < 1) {
			$warningMessages[] = $viewFromMVC->translate('Für Preis Standard sind keine Arbeitstage angegeben', 'de');
		}

// 		if ($productElement['pe_work_days'] < 1) {
// 			$warningMessages[] = $viewFromMVC->translate('Für Preis Express sind keine Arbeitstage angegeben', 'de');
// 		}

// 		if ($productElement['po_work_days'] < 1) {
// 			$warningMessages[] = $viewFromMVC->translate('Für Preis Overnight sind keine Arbeitstage angegeben', 'de');
// 		}

		if ($productElement['packaging_weight'] < 0.0001) {
			$warningMessages[] = $viewFromMVC->translate('Kein Verpackungsgewicht angegeben', 'de');
		}

		$systemWeightInvalid = ($productElement['system_weight'] < 0.0001);
		$productWeightInvalid = ($productElement['length'] < 0.0001 || $productElement['width'] < 0.0001 || $productElement['area_density'] < 0.0001);

		if ($systemWeightInvalid && $productWeightInvalid) {
			$warningMessages[] = $viewFromMVC->translate('Es existieren weder Systemgewicht noch produktspezifische Werte (Maße & Flächengewicht)', 'de');
		}

		return $warningMessages;

	}

	public static function round($numberString) {
		$numberString = round($numberString, 2);
		$numberArray = explode('.', $numberString);
		if (count($numberArray) == 2 &&
			strlen($numberArray[1]) < 2) {
			$numberArray[1] = str_pad($numberArray[1], 2, '0');
		} else {
			if (count($numberArray) == 1) {
				$numberArray[1] = '00';
			}
		}
		$returnValue = implode('.', $numberArray);
		return $returnValue;
	}

	public static function getPublishedWhereClause ($relationShort = 'm') {
		$returnValue = $relationShort . '.published = 1';

		if (Zend_Auth::getInstance()->hasIdentity()) {
			if (Zend_Auth::getInstance()->getIdentity()->Role->short == 'supervisor' ||
				Zend_Auth::getInstance()->getIdentity()->Role->short == 'admin') {

				$returnValue = '1';
			}
		}

		return $returnValue;
	}

	public static function arrayCombinations($arrays, $i = 0) {
		if (!isset($arrays[$i])) {
			return array();
		}
		if ($i == count($arrays) - 1) {
			return $arrays[$i];
		}

		// get combinations from subsequent arrays
		$tmp = self::arrayCombinations($arrays, $i + 1);

		$result = array();

		// concat each array from tmp with each element from $arrays[$i]
		foreach ($arrays[$i] as $v) {
			foreach ($tmp as $t) {
				$result[] = is_array($t) ?
					array_merge(array($v), $t) :
					array($v, $t);
			}
		}

		return $result;
	}
}