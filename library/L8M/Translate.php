<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Translate.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Translate.php 192 2014-10-02 08:30:28Z nm $
 */

/**
 *
 *
 * L8M_Translate
 *
 *
 */
class L8M_Translate
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
	 * contains cache of numeric transaltions
	 * @var array
	 */
	protected static $_arrayNumericTranslation = array();

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Translate an numeric string into country specific one
	 *
	 * @param  string $numericValue
	 * @param  string $defaultLocale Translate into
	 * @param  string $locale Translate from
	 * @return string
	 */
	public static function numeric($numericValue = NULL, $defaultLocale = NULL, $locale = NULL)
	{
		$returnValue = NULL;
		if ($numericValue !== NULL) {
			if ($defaultLocale === NULL) {
				if (L8M_Config::getOption('locale.defaultSystem')) {
					$defaultLocale = L8M_Config::getOption('locale.defaultSystem');
				} else {
					$defaultLocale = L8M_Locale::getDefault();
				}
			}

			if ($locale === NULL) {
				$locale = L8M_Locale::getLang();
			}

			if (!array_key_exists($defaultLocale . '-2-' . $locale, self::$_arrayNumericTranslation)) {

				if ($defaultLocale == 'en' &&
					$locale != 'en') {

					self::$_arrayNumericTranslation[$defaultLocale . '-2-' . $locale] = array(
						'toThousendPoint'=>'.',
						'toDecimalPoint'=>',',
						'fromThousendPoint'=>',',
						'fromDecimalPoint'=>'.',
					);
				} else
				if ($defaultLocale != 'en' &&
					$locale == 'en') {

					self::$_arrayNumericTranslation[$defaultLocale . '-2-' . $locale] = array(
						'toThousendPoint'=>',',
						'toDecimalPoint'=>'.',
						'fromThousendPoint'=>'.',
						'fromDecimalPoint'=>',',
					);
				}
			}

			if (array_key_exists($defaultLocale . '-2-' . $locale, self::$_arrayNumericTranslation)) {
				$toThousendPoint = self::$_arrayNumericTranslation[$defaultLocale . '-2-' . $locale]['toThousendPoint'];
				$toDecimalPoint = self::$_arrayNumericTranslation[$defaultLocale . '-2-' . $locale]['toDecimalPoint'];
				$fromThousendPoint = self::$_arrayNumericTranslation[$defaultLocale . '-2-' . $locale]['fromThousendPoint'];
				$fromDecimalPoint = self::$_arrayNumericTranslation[$defaultLocale . '-2-' . $locale]['fromDecimalPoint'];

				$numericValue = str_replace($fromThousendPoint, 'L8M_TranslateFromThousendPoint', $numericValue);
				$numericValue = str_replace($fromDecimalPoint, 'L8M_TranslateFromDecimalPoint', $numericValue);
				$numericValue = str_replace('L8M_TranslateFromThousendPoint', $toThousendPoint, $numericValue);
				$numericValue = str_replace('L8M_TranslateFromDecimalPoint', $toDecimalPoint, $numericValue);
			}
			$returnValue = $numericValue;
		}
		return $returnValue;
	}

	/**
	 * Translate string using view translate
	 *
	 * @param string $messageId
	 * @param string $defaultLocale
	 * @param string $locale
	 * @return string
	 */
	public static function string($messageId = NULL, $defaultLocale = NULL, $locale = NULL)
	{
		$returnValue = NULL;
		if ($messageId) {
			$viewFromMVC = Zend_Layout::getMvcInstance()->getView();
			if ($viewFromMVC) {
				if ($defaultLocale) {
					if ($locale) {
						$returnValue = $viewFromMVC->translate($messageId, $defaultLocale, $locale);
					} else {
						$returnValue = $viewFromMVC->translate($messageId, $defaultLocale);
					}
				} else {
					$returnValue = $viewFromMVC->translate($messageId);
				}
			} else {
				$returnValue = $messageId;
			}
		}
		return $returnValue;
	}
}