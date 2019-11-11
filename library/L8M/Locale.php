<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Locale.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Locale.php 543 2017-08-23 17:04:56Z nm $
 */

/**
 *
 *
 * L8M_Locale
 *
 *
 */
class L8M_Locale
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
	 * language short
	 *
	 * @var string
	 */
	protected static $_lang = NULL;

	/**
	 * old language short
	 *
	 * @var string
	 */
	protected static $_oldLang = NULL;

	/**
	 * region short
	 *
	 * @var string
	 */
	protected static $_region = NULL;

	/**
	 * language short for backend links as default
	 *
	 * @var string
	 */
	protected static $_langForLinkBackend = NULL;

	/**
	 * language default short
	 *
	 * @var string
	 */
	protected static $_default = NULL;

	/**
	 * language default short for TopLevelDomain
	 *
	 * @var string
	 */
	protected static $_tldDefaultLang = NULL;

	/**
	 * language supported
	 *
	 * @var array
	 */
	protected static $_supported = array();

	/**
	 * language supported with backend
	 *
	 * @var array
	 */
	protected static $_supportedWithBackend = array();

	/**
	 * language supported for links
	 *
	 * @var array
	 */
	protected static $_supportedLinks = array();

	/**
	 * language supported for backend links
	 *
	 * @var array
	 */
	protected static $_supportedLinksForBackend = array();

	/**
	 * Class wide Locale Constants
	 *
	 * @var array $_localeSupported
	 */
	protected static $_localeSupported = array(
		'aa_DJ', 'aa_ER', 'aa_ET',
		'af_NA', 'af_ZA',
		'ak_GH',
		'am_ET',
		'ar_AE', 'ar_BH', 'ar_DZ', 'ar_EG', 'ar_IQ', 'ar_JO', 'ar_KW', 'ar_LB', 'ar_LY', 'ar_MA', 'ar_OM', 'ar_QA', 'ar_SA', 'ar_SD', 'ar_SY', 'ar_TN', 'ar_YE',
		'as_IN',
		'az_AZ',
		'be_BY',
		'bg_BG',
		'bn_BD', 'bn_IN',
		'bo_CN', 'bo_IN',
		'bs_BA',
		'ca_ES',
		'cs_CZ',
		'cy_GB',
		'da_DK',
		'de_AT', 'de_BE', 'de_CH', 'de_DE', 'de_LI', 'de_LU',
		'dv_MV',
		'dz_BT',
		'ee_GH', 'ee_TG',
		'el_CY', 'el_GR',
		'en_AS', 'en_AU', 'en_BE', 'en_BW', 'en_BZ', 'en_CA', 'en_GB', 'en_GU', 'en_HK', 'en_IE', 'en_IN', 'en_JM', 'en_MH', 'en_MP', 'en_MT', 'en_NA', 'en_NZ', 'en_PH', 'en_PK', 'en_SG', 'en_TT', 'en_UM', 'en_US', 'en_VI', 'en_ZA', 'en_ZW',
		'es_AR', 'es_BO', 'es_CL', 'es_CO', 'es_CR', 'es_DO', 'es_EC', 'es_ES', 'es_GT', 'es_HN', 'es_MX', 'es_NI', 'es_PA', 'es_PE', 'es_PR', 'es_PY', 'es_SV', 'es_US', 'es_UY', 'es_VE',
		'et_EE',
		'eu_ES',
		'fa_AF', 'fa_IR',
		'fi_FI',
		'fo_FO',
		'fr_BE', 'fr_CA', 'fr_CH', 'fr_FR', 'fr_LU', 'fr_MC', 'fr_SN',
		'ga_IE',
		'gl_ES',
		'gu_IN',
		'gv_GB',
		'ha_GH', 'ha_NE', 'ha_NG', 'ha_SD',
		'he_IL',
		'hi_IN',
		'hr_HR',
		'hu_HU',
		'hy_AM',
		'id_ID',
		'ig_NG',
		'ii_CN',
		'is_IS',
		'it_CH', 'it_IT',
		'ja_JP',
		'ka_GE',
		'kk_KZ',
		'kl_GL',
		'km_KH',
		'kn_IN',
		'ko_KR',
		'ku_IQ', 'ku_IR', 'ku_SY', 'ku_TR',
		'kw_GB',
		'ky_KG',
		'ln_CD', 'ln_CG',
		'lo_LA',
		'lt_LT',
		'lv_LV',
		'mk_MK',
		'ml_IN',
		'mn_CN', 'mn_MN',
		'mr_IN',
		'ms_BN', 'ms_MY',
		'mt_MT',
		'my_MM',
		'nb_NO',
		'ne_IN', 'ne_NP',
		'nl_BE', 'nl_NL',
		'nn_NO',
		'nr_ZA',
		'ny_MW',
		'oc_FR',
		'om_ET', 'om_KE',
		'or_IN',
		'pa_IN', 'pa_PK',
		'pl_PL',
		'ps_AF',
		'pt_BR', 'pt_PT',
		'ro_MD', 'ro_RO',
		'ru_RU', 'ru_UA',
		'rw_RW',
		'sa_IN',
		'se_FI', 'se_NO',
		'sh_BA', 'sh_CS', 'sh_YU',
		'si_LK',
		'sk_SK',
		'sl_SI',
		'so_DJ', 'so_ET', 'so_KE', 'so_SO',
		'sq_AL',
		'sr_BA', 'sr_CS', 'sr_ME', 'sr_RS', 'sr_YU',
		'ss_SZ', 'ss_ZA',
		'st_LS', 'st_ZA',
		'sv_FI', 'sv_SE',
		'sw_KE', 'sw_TZ',
		'ta_IN',
		'te_IN',
		'tg_TJ',
		'th_TH',
		'ti_ER', 'ti_ET',
		'tn_ZA',
		'to_TO',
		'tr_TR',
		'ts_ZA',
		'tt_RU',
		'ug_CN',
		'uk_UA',
		'ur_IN', 'ur_PK',
		'uz_AF', 'uz_UZ',
		've_ZA',
		'vi_VN',
		'wo_SN',
		'xh_ZA',
		'yo_NG',
		'zh_CN', 'zh_HK', 'zh_MO', 'zh_SG', 'zh_TW',
		'zu_ZA',
	);

	/**
	 * Class wide Locale Constants
	 *
	 * @var array $_localeData
	 */
	protected static $_localeData = array(
		'aa'=>array('aa_DJ', 'aa_ER', 'aa_ET',),
		'af'=>array('af_NA', 'af_ZA',),
		'ak'=>array('ak_GH',),
		'am'=>array('am_ET',),
		'ar'=>array('ar_AE', 'ar_BH', 'ar_DZ', 'ar_EG', 'ar_IQ', 'ar_JO', 'ar_KW', 'ar_LB', 'ar_LY', 'ar_MA', 'ar_OM', 'ar_QA', 'ar_SA', 'ar_SD', 'ar_SY', 'ar_TN', 'ar_YE',),
		'as'=>array('as_IN',),
		'az'=>array('az_AZ',),
		'be'=>array('be_BY',),
		'bg'=>array('bg_BG',),
		'bn'=>array('bn_BD', 'bn_IN',),
		'bo'=>array('bo_CN', 'bo_IN',),
		'bs'=>array('bs_BA',),
		'ca'=>array('ca_ES',),
		'cs'=>array('cs_CZ',),
		'cy'=>array('cy_GB',),
		'da'=>array('da_DK',),
		'de'=>array('de_AT', 'de_BE', 'de_CH', 'de_DE', 'de_LI', 'de_LU',),
		'dv'=>array('dv_MV',),
		'dz'=>array('dz_BT',),
		'ee'=>array('ee_GH', 'ee_TG',),
		'el'=>array('el_CY', 'el_GR',),
		'en'=>array('en_GB', 'en_AS', 'en_AU', 'en_BE', 'en_BW', 'en_BZ', 'en_CA', 'en_GU', 'en_HK', 'en_IE', 'en_IN', 'en_JM', 'en_MH', 'en_MP', 'en_MT', 'en_NA', 'en_NZ', 'en_PH', 'en_PK', 'en_SG', 'en_TT', 'en_UM', 'en_US', 'en_VI', 'en_ZA', 'en_ZW',),
		'es'=>array('es_AR', 'es_BO', 'es_CL', 'es_CO', 'es_CR', 'es_DO', 'es_EC', 'es_ES', 'es_GT', 'es_HN', 'es_MX', 'es_NI', 'es_PA', 'es_PE', 'es_PR', 'es_PY', 'es_SV', 'es_US', 'es_UY', 'es_VE',),
		'et'=>array('et_EE',),
		'eu'=>array('eu_ES',),
		'fa'=>array('fa_AF', 'fa_IR',),
		'fi'=>array('fi_FI',),
		'fo'=>array('fo_FO',),
		'fr'=>array('fr_BE', 'fr_CA', 'fr_CH', 'fr_FR', 'fr_LU', 'fr_MC', 'fr_SN',),
		'ga'=>array('ga_IE',),
		'gl'=>array('gl_ES',),
		'gu'=>array('gu_IN',),
		'gv'=>array('gv_GB',),
		'ha'=>array('ha_GH', 'ha_NE', 'ha_NG', 'ha_SD',),
		'he'=>array('he_IL',),
		'hi'=>array('hi_IN',),
		'hr'=>array('hr_HR',),
		'hu'=>array('hu_HU',),
		'hy'=>array('hy_AM',),
		'id'=>array('id_ID',),
		'ig'=>array('ig_NG',),
		'ii'=>array('ii_CN',),
		'is'=>array('is_IS',),
		'it'=>array('it_CH', 'it_IT',),
		'ja'=>array('ja_JP',),
		'ka'=>array('ka_GE',),
		'kk'=>array('kk_KZ',),
		'kl'=>array('kl_GL',),
		'km'=>array('km_KH',),
		'kn'=>array('kn_IN',),
		'ko'=>array('ko_KR',),
		'ku'=>array('ku_IQ', 'ku_IR', 'ku_SY', 'ku_TR',),
		'kw'=>array('kw_GB',),
		'ky'=>array('ky_KG',),
		'ln'=>array('ln_CD', 'ln_CG',),
		'lo'=>array('lo_LA',),
		'lt'=>array('lt_LT',),
		'lv'=>array('lv_LV',),
		'mk'=>array('mk_MK',),
		'ml'=>array('ml_IN',),
		'mn'=>array('mn_CN', 'mn_MN',),
		'mr'=>array('mr_IN',),
		'ms'=>array('ms_BN', 'ms_MY',),
		'mt'=>array('mt_MT',),
		'my'=>array('my_MM',),
		'nb'=>array('nb_NO',),
		'ne'=>array('ne_IN', 'ne_NP',),
		'nl'=>array('nl_BE', 'nl_NL',),
		'nn'=>array('nn_NO',),
		'nr'=>array('nr_ZA',),
		'ny'=>array('ny_MW',),
		'oc'=>array('oc_FR',),
		'om'=>array('om_ET', 'om_KE',),
		'or'=>array('or_IN',),
		'pa'=>array('pa_IN', 'pa_PK',),
		'pl'=>array('pl_PL',),
		'ps'=>array('ps_AF',),
		'pt'=>array('pt_BR', 'pt_PT',),
		'ro'=>array('ro_MD', 'ro_RO',),
		'ru'=>array('ru_RU', 'ru_UA',),
		'rw'=>array('rw_RW',),
		'sa'=>array('sa_IN',),
		'se'=>array('se_FI', 'se_NO',),
		'sh'=>array('sh_BA', 'sh_CS', 'sh_YU',),
		'si'=>array('si_LK',),
		'sk'=>array('sk_SK',),
		'sl'=>array('sl_SI',),
		'so'=>array('so_DJ', 'so_ET', 'so_KE', 'so_SO',),
		'sq'=>array('sq_AL',),
		'sr'=>array('sr_BA', 'sr_CS', 'sr_ME', 'sr_RS', 'sr_YU',),
		'ss'=>array('ss_SZ', 'ss_ZA',),
		'st'=>array('st_LS', 'st_ZA',),
		'sv'=>array('sv_FI', 'sv_SE',),
		'sw'=>array('sw_KE', 'sw_TZ',),
		'ta'=>array('ta_IN',),
		'te'=>array('te_IN',),
		'tg'=>array('tg_TJ',),
		'th'=>array('th_TH',),
		'ti'=>array('ti_ER', 'ti_ET',),
		'tn'=>array('tn_ZA',),
		'to'=>array('to_TO',),
		'tr'=>array('tr_TR',),
		'ts'=>array('ts_ZA',),
		'tt'=>array('tt_RU',),
		'ug'=>array('ug_CN',),
		'uk'=>array('uk_UA',),
		'ur'=>array('ur_IN', 'ur_PK',),
		'uz'=>array('uz_AF', 'uz_UZ',),
		've'=>array('ve_ZA',),
		'vi'=>array('vi_VN',),
		'wo'=>array('wo_SN',),
		'xh'=>array('xh_ZA',),
		'yo'=>array('yo_NG',),
		'zh'=>array('zh_CN', 'zh_HK', 'zh_MO', 'zh_SG', 'zh_TW',),
		'zu'=>array('zu_ZA',),
	);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * set language
	 *
	 * @param string $lang
	 */
	public static function setLang($lang = NULL)
	{
		self::$_lang = $lang;
	}

	/**
	 * set old language
	 *
	 * @param string $lang
	 */
	public static function setOldLang($lang = NULL)
	{
		self::$_oldLang = $lang;
	}

	/**
	 * set region
	 *
	 * @param string $region
	 */
	public static function setRegion($region = NULL)
	{
		if (is_string($region) &&
			strlen($region) == 2) {

			self::$_region = $region;
		}
	}

	/**
	 * set language for backend link
	 *
	 * @param string $lang
	 */
	public static function setLangForLinkBackend($lang = NULL)
	{
		if ($lang == NULL ||
			!in_array($lang, L8M_Config::getOption('locale.backend.supported'))) {

			$lang = L8M_Config::getOption('locale.backend.default');
		}
		self::$_langForLinkBackend = $lang;
	}

	/**
	 * set supported languages array
	 *
	 * @param array $lang
	 */
	public static function setSupported($langs = NULL)
	{
		if (is_array($langs)) {
			self::$_supported = $langs;
		}
	}

	/**
	 * set default language
	 *
	 * @param string $lang
	 */
	public static function setDefault($lang = NULL)
	{
		if (is_string($lang) &&
			strlen($lang) == 2) {

			self::$_default = $lang;
		}
	}

	/**
	 * get TopLevelDomain default language-short
	 *
	 * @return string
	 */
	public static function setTldDefaultLang($lang = NULL)
	{
		if (is_string($lang) &&
			strlen($lang) == 2) {

			self::$_tldDefaultLang = $lang;
		}
	}

	/**
	 * get language
	 *
	 * @return string
	 */
	public static function getLang()
	{
		return self::$_lang;
	}

	/**
	 * get language for link
	 *
	 * @return string
	 */
	public static function getLangForLink($module = NULL)
	{
		$returnValue = self::$_lang;

		if (!$module) {
			$module = L8M_Acl_CalledFor::module();
		}
		if (in_array($module, L8M_Config::getOption('locale.backend.modules'))) {
// 			if (!in_array($returnValue, L8M_Config::getOption('locale.backend.supported'))) {
				$returnValue = self::$_langForLinkBackend;
// 			}
		} else {
			if (!in_array($returnValue, self::getSupportedForLinks($module))) {
				$returnValue = self::getDefault();
			}
		}
		return $returnValue;
	}

	/**
	 * get old language
	 *
	 * @return string
	 */
	public static function getOldLang()
	{
		return self::$_oldLang;
	}

	/**
	 * get region
	 *
	 * @return string
	 */
	public static function getRegion()
	{
		return self::$_region;
	}

	/**
	 * get locale consisting of locale and region
	 *
	 * @return string
	 */
	public static function getLocale()
	{
		return self::$_lang . '_' . self::$_region;
	}

	/**
	 * get TopLevelDomain default lang short
	 *
	 * @return string
	 */
	public static function getTldDefaultLang()
	{
		return self::$_tldDefaultLang;
	}

	/**
	 * get supported languages array
	 *
	 * @return array
	 */
	public static function getSupported($withBackendLang = FALSE)
	{
		if (count(self::$_supported) == 0 &&
			count(L8M_Config::getOption('locale.supported')) > 0) {

			self::$_supported = L8M_Config::getOption('locale.supported');

			/**
			 * do we have an identity entity model?
			 */
			if (L8M_Doctrine::isEnabled() &&
				Zend_Auth::getInstance()->hasIdentity()) {
				$roleShort = Zend_Auth::getInstance()->getIdentity()->Role->short;

				/**
				 * check special supported lang shorts for role short
				 */
				$supportedByRole = L8M_Config::getOption('locale.role.' . $roleShort . '.supported');
				if (is_array($supportedByRole)) {

					/**
					 * retrieve supported locale shorts
					 */
					self::$_supported = $supportedByRole;
				}
			}
		}
		$returnValue = self::$_supported;

		/**
		 * do we hav to add backend lang?
		 */
		if ($withBackendLang) {
			if (count(self::$_supportedWithBackend) == 0) {
				self::$_supportedWithBackend = self::$_supported;

				foreach (L8M_Config::getOption('locale.backend.supported') as $backendLang) {
					if (!in_array($backendLang, self::$_supportedWithBackend)) {
						self::$_supportedWithBackend[] = $backendLang;
					}
				}
			}
			$returnValue = self::$_supportedWithBackend;
		}

		return $returnValue;
	}

	/**
	 * get supported languages array for links
	 *
	 * @return array
	 */
	public static function getSupportedForLinks($module = NULL)
	{
		if (!$module) {
			$module = L8M_Acl_CalledFor::module();
		}
		if (in_array($module, L8M_Config::getOption('locale.backend.modules'))) {
			if (count(self::$_supportedLinksForBackend) == 0) {
				self::$_supportedLinksForBackend = self::$_supportedLinks;

				foreach (L8M_Config::getOption('locale.backend.supported') as $backendLang) {
					if (!in_array($backendLang, self::$_supportedLinksForBackend)) {
						self::$_supportedLinksForBackend[] = $backendLang;
					}
				}
			}
			$returnValue = self::$_supportedLinksForBackend;
		} else {
			if (count(self::$_supportedLinks) == 0 &&
				count(L8M_Config::getOption('locale.supported')) > 0) {

				self::$_supportedLinks = L8M_Config::getOption('locale.supported');

				/**
				 * do we have an identity entity model?
				 */
				if (L8M_Doctrine::isEnabled() &&
					Zend_Auth::getInstance()->hasIdentity()) {
					$roleShort = Zend_Auth::getInstance()->getIdentity()->Role->short;

					/**
					 * check special supported lang shorts for role short
					 */
					$supportedByRole = L8M_Config::getOption('locale.role.' . $roleShort . '.supported');
					if (is_array($supportedByRole)) {

						/**
						 * retrieve supported locale shorts
						 */
						self::$_supportedLinks = $supportedByRole;
					}
				}
			}
			$returnValue = self::$_supportedLinks;
		}

		return $returnValue;
	}

	/**
	 * is locale supported by languages array
	 *
	 * @param $locale String | Zend_Locale
	 * @return boolean
	 */
	public static function isAvailable($locale = NULL)
	{
		if ($locale != NULL) {
			if ($locale instanceof Zend_Locale) {
				$return = in_array($locale->getLanguage(), self::$_supported);
			} else {
				$return = in_array($locale, self::$_supported);
			}
		} else {
			$return = FALSE;
		}
		return $return;
	}

	/**
	 * is locale existing
	 *
	 * @param $locale String
	 * @return boolean
	 */
	public static function isLocaleExisting($locale = NULL)
	{
		if ($locale != NULL) {
			$return = in_array($locale, self::$_localeSupported);
		} else {
			$return = FALSE;
		}
		return $return;
	}

	/**
	 * Get first region of lang
	 *
	 * @param $lang String
	 * @return String
	 */
	public static function getRegionForLang($lang = NULL)
	{
		if ($lang != NULL &&
			array_key_exists($lang, self::$_localeData)) {

			$tmpReturn = explode('_', self::$_localeData[$lang][0]);
			$return = $tmpReturn[1];
		} else {
			$return = FALSE;
		}
		return $return;
	}

	/**
	 * get default language
	 *
	 * @return string
	 */
	public static function getDefault()
	{
		return self::$_default;
	}

	/**
	 * get default system language
	 *
	 * @return string
	 */
	public static function getDefaultSystem()
	{
		return L8M_Config::getOption('locale.defaultSystem');
	}

	/**
	 * add model translation
	 *
	 * @param $model Default_Model_Base_Abstract
	 * @param $column string
	 * @param $message string
	 * @return string
	 */
	public static function addModelTranslation($model, $column = NULL, $message = NULL, $defaultLang = NULL)
	{
		if ($model instanceof Default_Model_Base_Abstract) {
			if (!is_array($message)) {

				if ($defaultLang == NULL) {
					$defaultLang = L8M_Locale::getDefaultSystem();
				} else
				if (!array_key_exists($defaultLang, self::$_supported)) {
					$defaultLang = L8M_Locale::getDefaultSystem();
				}
				$messages = array($defaultLang=>$message);
			} else {
				$messages = $message;
			}
			if (self::$_supported) {
				foreach (self::$_supported as $lang) {
					if (array_key_exists($lang, $messages)) {
						$langMessage = $message;
					} else {
						$translatorModel = Doctrine_Query::create()
							->from('Default_Model_Translator t')
							->where('t.short = ? ', array($message))
							->execute()
							->getFirst()
						;
						if ($translatorModel &&
							isset($translatorModel->Translation[$lang]) &&
							$translatorModel->Translation[$lang]->text != NULL) {

							$langMessage = $translatorModel->Translation[$lang]->text;
						} else {
							$langMessage = $lang . '[' . $message . ']';
						}
					}
					$model->Translation[$lang][$column] = $langMessage;
				}
			}
		}
		return $model;
	}

	/**
	 * add model array translation
	 *
	 * @param $modelValues array
	 * @param $column string
	 * @param $message string
	 * @return string
	 */
	public static function addModelArrayTranslation($modelValues, $column = NULL, $message = NULL, $defaultLang = NULL)
	{
		if (is_array($modelValues) ||
			$modelValues instanceof Default_Model_Base_Abstract) {

			if (!is_array($message)) {
				if ($defaultLang == NULL) {
					$defaultLang = L8M_Locale::getDefaultSystem();
				} else
				if (!in_array($defaultLang, self::$_supported)) {
					$defaultLang = L8M_Locale::getDefaultSystem();
				}
				$messages = array($defaultLang=>$message);
			} else {
				$messages = $message;
			}
			foreach (self::$_supported as $lang) {
				if (array_key_exists($lang, $messages)) {
					$langMessage = $message;
				} else {
					$translatorModel = Doctrine_Query::create()
						->from('Default_Model_Translator t')
						->where('t.short = ? ', array($message))
						->execute()
						->getFirst()
					;
					if ($translatorModel &&
						isset($translatorModel->Translation[$lang]) &&
						$translatorModel->Translation[$lang]->text != NULL) {

						$langMessage = $translatorModel->Translation[$lang]->text;
					} else {
						$langMessage = $lang . '[' . $message . ']';
					}
				}
				if (is_array($modelValues)) {
					$modelValues['Translation'][$lang][$column] = $langMessage;
				} else
				if ($modelValues instanceof Default_Model_Base_Abstract &&
					$modelValues->hasRelation('Translation') &&
					in_array($column, $modelValues->getI18nFields())) {
					$modelValues->Translation[$lang][$column] = $langMessage;
				}
			}
		}
		return $modelValues;
	}

	public static function getPossibleSystemLanguages()
	{
		return array_keys(self::$_localeData);
	}
}