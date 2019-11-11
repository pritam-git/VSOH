<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Language/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Model_Language_Import
 *
 *
 */
class Default_Model_Language_Import extends L8M_Doctrine_Import_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

		/**
	 * Initializes instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		parent::_init();

		$array = array(
			array('id'=>1,'iso_2'=>'AB','name_en'=>'Abkhazian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Аҧсуа бызшәа','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Abchasisch'),
			array('id'=>2,'iso_2'=>'AA','name_en'=>'Afar','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Afaraf','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Afar'),
			array('id'=>3,'iso_2'=>'AF','name_en'=>'Afrikaans','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Afrikaans','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Afrikaans'),
			array('id'=>4,'iso_2'=>'SQ','name_en'=>'Albanian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Gjuha shqipe','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Albanisch'),
			array('id'=>5,'iso_2'=>'AM','name_en'=>'Amharic','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'አማርኛ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Amharisch'),
			array('id'=>6,'iso_2'=>'AR','name_en'=>'Arabic','country_iso_2'=>null,'collate_locale'=>'ar_SA','name_local'=>'العربية','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Arabisch'),
			array('id'=>7,'iso_2'=>'HY','name_en'=>'Armenian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Հայերեն','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Armenisch'),
			array('id'=>8,'iso_2'=>'AS','name_en'=>'Assamese','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'অসমীয়া','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Assamesisch'),
			array('id'=>9,'iso_2'=>'AY','name_en'=>'Aymara','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Aymar aru','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Aymará-Sprache'),
			array('id'=>10,'iso_2'=>'AZ','name_en'=>'Azerbaijani','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Azərbaycan dili','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Aserbaidschanisch'),
			array('id'=>11,'iso_2'=>'BA','name_en'=>'Bashkir','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Башҡорт','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Baschkirisch'),
			array('id'=>12,'iso_2'=>'EU','name_en'=>'Basque','country_iso_2'=>null,'collate_locale'=>'eu_ES','name_local'=>'Euskara','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Baskisch'),
			array('id'=>13,'iso_2'=>'BN','name_en'=>'Bengali','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'বাংলা','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Bengalisch'),
			array('id'=>14,'iso_2'=>'DZ','name_en'=>'Dzongkha','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ཇོང་ཁ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Bhutanisch'),
			array('id'=>15,'iso_2'=>'BH','name_en'=>'Bihari','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'भोजपुरी','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Biharisch'),
			array('id'=>16,'iso_2'=>'BI','name_en'=>'Bislama','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Bislama','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Bislama'),
			array('id'=>17,'iso_2'=>'BR','name_en'=>'Breton','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Brezhoneg','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Bretonisch'),
			array('id'=>18,'iso_2'=>'BG','name_en'=>'Bulgarian','country_iso_2'=>null,'collate_locale'=>'bg_BG','name_local'=>'Български','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Bulgarisch'),
			array('id'=>19,'iso_2'=>'MY','name_en'=>'Burmese','country_iso_2'=>null,'collate_locale'=>'my_MM','name_local'=>'မ္ရန္‌မာစာ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Birmanisch'),
			array('id'=>20,'iso_2'=>'BE','name_en'=>'Belarusian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Беларуская','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Weißrussisch'),
			array('id'=>21,'iso_2'=>'KM','name_en'=>'Khmer','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ភាសាខ្មែរ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kambodschanisch'),
			array('id'=>22,'iso_2'=>'CA','name_en'=>'Catalan','country_iso_2'=>null,'collate_locale'=>'ca_ES','name_local'=>'Català','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Katalanisch'),
			array('id'=>23,'iso_2'=>'ZA','name_en'=>'Zhuang','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Sawcuengh','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Zhuang'),
			array('id'=>24,'iso_2'=>'ZH','name_en'=>'Chinese (Traditional)','country_iso_2'=>'HK','collate_locale'=>'zh_HK','name_local'=>'漢語','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Chinesisch'),
			array('id'=>25,'iso_2'=>'CO','name_en'=>'Corsican','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Corsu','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Korsisch'),
			array('id'=>26,'iso_2'=>'HR','name_en'=>'Croatian','country_iso_2'=>null,'collate_locale'=>'hr_HR','name_local'=>'Hrvatski','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kroatisch'),
			array('id'=>27,'iso_2'=>'CS','name_en'=>'Czech','country_iso_2'=>null,'collate_locale'=>'cs_CZ','name_local'=>'Čeština','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tschechisch'),
			array('id'=>28,'iso_2'=>'DA','name_en'=>'Danish','country_iso_2'=>null,'collate_locale'=>'da_DK','name_local'=>'Dansk','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Dänisch'),
			array('id'=>29,'iso_2'=>'NL','name_en'=>'Dutch','country_iso_2'=>null,'collate_locale'=>'nl_NL','name_local'=>'Nederlands','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Niederländisch'),
			array('id'=>30,'iso_2'=>'EN','name_en'=>'English','country_iso_2'=>null,'collate_locale'=>'en_GB','name_local'=>'English','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Englisch'),
			array('id'=>31,'iso_2'=>'EO','name_en'=>'Esperanto','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Esperanto','is_sacred'=>0,'is_constructed'=>1,'name_de'=>'Esperanto'),
			array('id'=>32,'iso_2'=>'ET','name_en'=>'Estonian','country_iso_2'=>null,'collate_locale'=>'et_EE','name_local'=>'Eesti','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Estnisch'),
			array('id'=>33,'iso_2'=>'FO','name_en'=>'Faeroese','country_iso_2'=>null,'collate_locale'=>'fo_FO','name_local'=>'Føroyskt','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Färöisch'),
			array('id'=>34,'iso_2'=>'FA','name_en'=>'Persian','country_iso_2'=>null,'collate_locale'=>'fa_IR','name_local'=>'فارسی','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Persisch'),
			array('id'=>35,'iso_2'=>'FJ','name_en'=>'Fijian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Na Vosa Vakaviti','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Fidschianisch'),
			array('id'=>36,'iso_2'=>'FI','name_en'=>'Finnish','country_iso_2'=>null,'collate_locale'=>'fi_FI','name_local'=>'Suomi','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Finnisch'),
			array('id'=>37,'iso_2'=>'FR','name_en'=>'French','country_iso_2'=>null,'collate_locale'=>'fr_FR','name_local'=>'Français','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Französisch'),
			array('id'=>38,'iso_2'=>'FY','name_en'=>'Frisian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Frysk','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Friesisch'),
			array('id'=>39,'iso_2'=>'GL','name_en'=>'Galician','country_iso_2'=>null,'collate_locale'=>'gl_ES','name_local'=>'Galego','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Galizisch'),
			array('id'=>40,'iso_2'=>'GD','name_en'=>'Scottish Gaelic','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Gàidhlig','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Schottisch-Gälisch'),
			array('id'=>41,'iso_2'=>'GV','name_en'=>'Manx','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Gaelg','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Manx'),
			array('id'=>42,'iso_2'=>'KA','name_en'=>'Georgian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ქართული','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Georgisch'),
			array('id'=>43,'iso_2'=>'DE','name_en'=>'German','country_iso_2'=>null,'collate_locale'=>'de_DE','name_local'=>'Deutsch','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Deutsch'),
			array('id'=>44,'iso_2'=>'EL','name_en'=>'Greek','country_iso_2'=>null,'collate_locale'=>'el_GR','name_local'=>'Ελληνικά','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Griechisch'),
			array('id'=>45,'iso_2'=>'KL','name_en'=>'Greenlandic','country_iso_2'=>null,'collate_locale'=>'kl_DK','name_local'=>'Kalaallisut','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Grönländisch'),
			array('id'=>46,'iso_2'=>'GN','name_en'=>'Guaraní','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Avañe\'ẽ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Guarani'),
			array('id'=>47,'iso_2'=>'GU','name_en'=>'Gujarati','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ગુજરાતી','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Gujarati'),
			array('id'=>48,'iso_2'=>'HA','name_en'=>'Hausa','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Hausa','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Hausa'),
			array('id'=>49,'iso_2'=>'HE','name_en'=>'Hebrew','country_iso_2'=>null,'collate_locale'=>'he_IL','name_local'=>'עברית','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Hebräisch'),
			array('id'=>50,'iso_2'=>'HI','name_en'=>'Hindi','country_iso_2'=>null,'collate_locale'=>'hi_IN','name_local'=>'हिन्दी','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Hindi'),
			array('id'=>51,'iso_2'=>'HU','name_en'=>'Hungarian','country_iso_2'=>null,'collate_locale'=>'hu_HU','name_local'=>'Magyar','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ungarisch'),
			array('id'=>52,'iso_2'=>'IS','name_en'=>'Icelandic','country_iso_2'=>null,'collate_locale'=>'is_IS','name_local'=>'Íslenska','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Isländisch'),
			array('id'=>53,'iso_2'=>'ID','name_en'=>'Indonesian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Bahasa Indonesia','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Indonesisch'),
			array('id'=>54,'iso_2'=>'IA','name_en'=>'Interlingua','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Interlingua','is_sacred'=>0,'is_constructed'=>1,'name_de'=>'Interlingua'),
			array('id'=>55,'iso_2'=>'IE','name_en'=>'Interlingue','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Interlingue','is_sacred'=>0,'is_constructed'=>1,'name_de'=>'Interlingue'),
			array('id'=>56,'iso_2'=>'IU','name_en'=>'Inuktitut','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ᐃᓄᒃᑎᑐᑦ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Inukitut'),
			array('id'=>57,'iso_2'=>'IK','name_en'=>'Inupiaq','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Iñupiak','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Inupiak'),
			array('id'=>58,'iso_2'=>'GA','name_en'=>'Irish','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Gaeilge','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Irisch'),
			array('id'=>59,'iso_2'=>'IT','name_en'=>'Italian','country_iso_2'=>null,'collate_locale'=>'it_IT','name_local'=>'Italiano','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Italienisch'),
			array('id'=>60,'iso_2'=>'JA','name_en'=>'Japanese','country_iso_2'=>null,'collate_locale'=>'ja_JP','name_local'=>'日本語','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Japanisch'),
			array('id'=>62,'iso_2'=>'KN','name_en'=>'Kannada','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ಕನ್ನಡ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kannada'),
			array('id'=>63,'iso_2'=>'KS','name_en'=>'Kashmiri','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'कॉशुर','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kaschmirisch'),
			array('id'=>64,'iso_2'=>'KK','name_en'=>'Kazakh','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Қазақ тілі','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kasachisch'),
			array('id'=>65,'iso_2'=>'RW','name_en'=>'Kinyarwanda','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Kinyarwanda','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ruandisch'),
			array('id'=>66,'iso_2'=>'KY','name_en'=>'Kirghiz','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Кыргыз тили','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kirgisisch'),
			array('id'=>67,'iso_2'=>'RN','name_en'=>'Kirundi','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'kiRundi','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Rundi-Sprache'),
			array('id'=>68,'iso_2'=>'KO','name_en'=>'Korean','country_iso_2'=>null,'collate_locale'=>'ko_KR','name_local'=>'한국말','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Koreanisch'),
			array('id'=>69,'iso_2'=>'KU','name_en'=>'Kurdish','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Kurdî','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kurdisch'),
			array('id'=>70,'iso_2'=>'LO','name_en'=>'Lao','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ພາສາລາວ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Laotisch'),
			array('id'=>71,'iso_2'=>'LA','name_en'=>'Latin','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Lingua latina','is_sacred'=>1,'is_constructed'=>0,'name_de'=>'Latein'),
			array('id'=>72,'iso_2'=>'LV','name_en'=>'Latvian','country_iso_2'=>null,'collate_locale'=>'lv_LV','name_local'=>'Latviešu','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Lettisch'),
			array('id'=>73,'iso_2'=>'LN','name_en'=>'Lingala','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Lingála','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Lingala'),
			array('id'=>74,'iso_2'=>'LT','name_en'=>'Lithuanian','country_iso_2'=>null,'collate_locale'=>'lt_LT','name_local'=>'Lietuvių','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Litauisch'),
			array('id'=>75,'iso_2'=>'MK','name_en'=>'Macedonian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Македонски','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Mazedonisch'),
			array('id'=>76,'iso_2'=>'MG','name_en'=>'Malagasy','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Merina','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Madagassisch'),
			array('id'=>77,'iso_2'=>'MS','name_en'=>'Malay','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Bahasa Melayu','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Malaiisch'),
			array('id'=>78,'iso_2'=>'ML','name_en'=>'Malayalam','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'മലയാളം','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Malayalam'),
			array('id'=>79,'iso_2'=>'MT','name_en'=>'Maltese','country_iso_2'=>null,'collate_locale'=>'mt_MT','name_local'=>'Malti','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Maltesisch'),
			array('id'=>80,'iso_2'=>'MI','name_en'=>'Māori','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Māori','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Maori'),
			array('id'=>81,'iso_2'=>'MR','name_en'=>'Marathi','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'मराठी','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Marathi'),
			array('id'=>82,'iso_2'=>'MO','name_en'=>'Moldavian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'молдовеняскэ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Moldauisch'),
			array('id'=>83,'iso_2'=>'MN','name_en'=>'Mongolian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Монгол','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Mongolisch'),
			array('id'=>84,'iso_2'=>'NA','name_en'=>'Nauru','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Ekakairũ Naoero','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Nauruisch'),
			array('id'=>85,'iso_2'=>'NE','name_en'=>'Nepali','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'नेपाली','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Nepalesisch'),
			array('id'=>86,'iso_2'=>'NO','name_en'=>'Norwegian','country_iso_2'=>null,'collate_locale'=>'no_NO','name_local'=>'Norsk','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Norwegisch'),
			array('id'=>87,'iso_2'=>'OC','name_en'=>'Occitan','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Occitan','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Okzitanisch'),
			array('id'=>88,'iso_2'=>'OR','name_en'=>'Oriya','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ଓଡ଼ିଆ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Orija'),
			array('id'=>89,'iso_2'=>'OM','name_en'=>'Oromo','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Afaan Oromoo','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Oromo'),
			array('id'=>90,'iso_2'=>'PS','name_en'=>'Pashto','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'پښت','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Afghanisch (Paschtu)'),
			array('id'=>91,'iso_2'=>'PL','name_en'=>'Polish','country_iso_2'=>null,'collate_locale'=>'pl_PL','name_local'=>'Polski','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Polnisch'),
			array('id'=>92,'iso_2'=>'PT','name_en'=>'Portuguese','country_iso_2'=>null,'collate_locale'=>'pt_PT','name_local'=>'Português','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Portugiesisch'),
			array('id'=>93,'iso_2'=>'PA','name_en'=>'Punjabi','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ਪੰਜਾਬੀ / پنجابی','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Pandschabisch'),
			array('id'=>94,'iso_2'=>'QU','name_en'=>'Quechua','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Runa Simi','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Quechua'),
			array('id'=>95,'iso_2'=>'RM','name_en'=>'Rhaeto-Romance','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Rumantsch','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Rätoromanisch'),
			array('id'=>96,'iso_2'=>'RO','name_en'=>'Romanian','country_iso_2'=>null,'collate_locale'=>'ro_RO','name_local'=>'Română','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Rumänisch'),
			array('id'=>97,'iso_2'=>'RU','name_en'=>'Russian','country_iso_2'=>null,'collate_locale'=>'ru_RU','name_local'=>'Русский','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Russisch'),
			array('id'=>98,'iso_2'=>'SM','name_en'=>'Samoan','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Gagana faʼa Samoa','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Samoanisch'),
			array('id'=>99,'iso_2'=>'SG','name_en'=>'Sango','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Sängö','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Sango'),
			array('id'=>100,'iso_2'=>'SA','name_en'=>'Sanskrit','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'संस्कृतम्','is_sacred'=>1,'is_constructed'=>0,'name_de'=>'Sanskrit'),
			array('id'=>101,'iso_2'=>'SR','name_en'=>'Serbian','country_iso_2'=>null,'collate_locale'=>'sr_YU','name_local'=>'Српски / Srpski','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Serbisch'),
			array('id'=>103,'iso_2'=>'ST','name_en'=>'Sesotho','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'seSotho','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Süd-Sotho-Sprache'),
			array('id'=>104,'iso_2'=>'TN','name_en'=>'Setswana','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Setswana','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tswana-Sprache'),
			array('id'=>105,'iso_2'=>'SN','name_en'=>'Shona','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'chiShona','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Shona'),
			array('id'=>106,'iso_2'=>'SD','name_en'=>'Sindhi','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'سنڌي، سندھی','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Sindhi'),
			array('id'=>107,'iso_2'=>'SI','name_en'=>'Sinhala','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'සිංහල','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Singhalesisch'),
			array('id'=>108,'iso_2'=>'SS','name_en'=>'Swati','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'siSwati','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Swazi'),
			array('id'=>109,'iso_2'=>'SK','name_en'=>'Slovak','country_iso_2'=>null,'collate_locale'=>'sk_SK','name_local'=>'Slovenčina','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Slowakisch'),
			array('id'=>110,'iso_2'=>'SL','name_en'=>'Slovenian','country_iso_2'=>null,'collate_locale'=>'sl_SI','name_local'=>'Slovenščina','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Slowenisch'),
			array('id'=>111,'iso_2'=>'SO','name_en'=>'Somali','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'af Soomaali','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Somali'),
			array('id'=>112,'iso_2'=>'ES','name_en'=>'Spanish','country_iso_2'=>null,'collate_locale'=>'es_ES','name_local'=>'Español','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Spanisch'),
			array('id'=>113,'iso_2'=>'SU','name_en'=>'Sundanese','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Basa Sunda','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Sudanesisch'),
			array('id'=>114,'iso_2'=>'SW','name_en'=>'Swahili','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Kiswahili','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Suaheli'),
			array('id'=>115,'iso_2'=>'SV','name_en'=>'Swedish','country_iso_2'=>null,'collate_locale'=>'sv_SE','name_local'=>'Svenska','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Schwedisch'),
			array('id'=>116,'iso_2'=>'TL','name_en'=>'Tagalog','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Tagalog','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tagalog'),
			array('id'=>117,'iso_2'=>'TG','name_en'=>'Tajik','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'тоҷикӣ / تاجیکی','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tadschikisch'),
			array('id'=>118,'iso_2'=>'TA','name_en'=>'Tamil','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'தமிழ்','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tamilisch'),
			array('id'=>119,'iso_2'=>'TT','name_en'=>'Tatar','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'татарча / tatarça / تاتارچ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tatarisch'),
			array('id'=>120,'iso_2'=>'TE','name_en'=>'Telugu','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'తెలుగు','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Telugu'),
			array('id'=>121,'iso_2'=>'TH','name_en'=>'Thai','country_iso_2'=>null,'collate_locale'=>'th_TH','name_local'=>'ภาษาไทย','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Thai'),
			array('id'=>122,'iso_2'=>'BO','name_en'=>'Tibetan','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'བོད་ཡིག','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tibetisch'),
			array('id'=>123,'iso_2'=>'TI','name_en'=>'Tigrinya','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ትግርኛ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tigrinja'),
			array('id'=>124,'iso_2'=>'TO','name_en'=>'Tongan','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'faka-Tonga','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tongaisch'),
			array('id'=>125,'iso_2'=>'TS','name_en'=>'Tsonga','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Tsonga','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tsonga'),
			array('id'=>126,'iso_2'=>'TR','name_en'=>'Turkish','country_iso_2'=>null,'collate_locale'=>'tr_TR','name_local'=>'Türkçe','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Türkisch'),
			array('id'=>127,'iso_2'=>'TK','name_en'=>'Turkmen','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Türkmen dili','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Turkmenisch'),
			array('id'=>128,'iso_2'=>'TW','name_en'=>'Twi','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Twi','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Twi'),
			array('id'=>129,'iso_2'=>'UG','name_en'=>'Uyghur','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ئۇيغۇرچه','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Uigurisch'),
			array('id'=>130,'iso_2'=>'UK','name_en'=>'Ukrainian','country_iso_2'=>null,'collate_locale'=>'uk_UA','name_local'=>'Українська','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ukrainisch'),
			array('id'=>131,'iso_2'=>'UR','name_en'=>'Urdu','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'اردو','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Urdu'),
			array('id'=>132,'iso_2'=>'UZ','name_en'=>'Uzbek','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Ўзбек / O\'zbek','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Usbekisch'),
			array('id'=>133,'iso_2'=>'VI','name_en'=>'Vietnamese','country_iso_2'=>null,'collate_locale'=>'vi_VN','name_local'=>'Tiếng Việt','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Vietnamesisch'),
			array('id'=>134,'iso_2'=>'VO','name_en'=>'Volapük','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Volapük','is_sacred'=>0,'is_constructed'=>1,'name_de'=>'Volapük'),
			array('id'=>135,'iso_2'=>'CY','name_en'=>'Welsh','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Cymraeg','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kymrisch'),
			array('id'=>136,'iso_2'=>'WO','name_en'=>'Wolof','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Wolof','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Wolof'),
			array('id'=>137,'iso_2'=>'XH','name_en'=>'Xhosa','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'isiXhosa','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Xhosa'),
			array('id'=>138,'iso_2'=>'YI','name_en'=>'Yiddish','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ייִדיש','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Jiddisch'),
			array('id'=>139,'iso_2'=>'YO','name_en'=>'Yoruba','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Yorùbá','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Joruba'),
			array('id'=>140,'iso_2'=>'ZU','name_en'=>'Zulu','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'isiZulu','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Zulu'),
			array('id'=>141,'iso_2'=>'BS','name_en'=>'Bosnian','country_iso_2'=>null,'collate_locale'=>'bs_BA','name_local'=>'Bosanski','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Bosnisch'),
			array('id'=>142,'iso_2'=>'AE','name_en'=>'Avestan','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Avestan','is_sacred'=>1,'is_constructed'=>0,'name_de'=>'Avestisch'),
			array('id'=>143,'iso_2'=>'AK','name_en'=>'Akan','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Akan','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Akan'),
			array('id'=>144,'iso_2'=>'AN','name_en'=>'Aragonese','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Aragonés','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Aragonesisch'),
			array('id'=>145,'iso_2'=>'AV','name_en'=>'Avar','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'магӀарул мацӀ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Awarisch'),
			array('id'=>146,'iso_2'=>'BM','name_en'=>'Bambara','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Bamanankan','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Bambara-Sprache'),
			array('id'=>147,'iso_2'=>'CE','name_en'=>'Chechen','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Нохчийн','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tschetschenisch'),
			array('id'=>148,'iso_2'=>'CH','name_en'=>'Chamorro','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Chamoru','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Chamorro-Sprache'),
			array('id'=>149,'iso_2'=>'CR','name_en'=>'Cree','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ᓀᐦᐃᔭᐤ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Cree'),
			array('id'=>150,'iso_2'=>'CU','name_en'=>'Church Slavonic','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'церковнославя́нский язы́к','is_sacred'=>1,'is_constructed'=>0,'name_de'=>'Kirchenslawisch'),
			array('id'=>151,'iso_2'=>'CV','name_en'=>'Chuvash','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Чăваш чěлхи','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tschuwaschisch'),
			array('id'=>152,'iso_2'=>'DV','name_en'=>'Dhivehi','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ދިވެހި','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Maledivisch'),
			array('id'=>153,'iso_2'=>'EE','name_en'=>'Ewe','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Ɛʋɛgbɛ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ewe-Sprache'),
			array('id'=>154,'iso_2'=>'FF','name_en'=>'Fula','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Fulfulde / Pulaar','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ful'),
			array('id'=>155,'iso_2'=>'HO','name_en'=>'Hiri motu','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Hiri motu','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Hiri-Motu'),
			array('id'=>156,'iso_2'=>'HT','name_en'=>'Haïtian Creole','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Krèyol ayisyen','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kreolisch'),
			array('id'=>157,'iso_2'=>'HZ','name_en'=>'Herero','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'otsiHerero','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Herero-Sprache'),
			array('id'=>158,'iso_2'=>'IG','name_en'=>'Igbo','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Igbo','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Igbo-Sprache'),
			array('id'=>159,'iso_2'=>'II','name_en'=>'Yi','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ꆇꉙ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Sichuan Yi'),
			array('id'=>160,'iso_2'=>'IO','name_en'=>'Ido','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Ido','is_sacred'=>0,'is_constructed'=>1,'name_de'=>'Ido-Sprache'),
			array('id'=>161,'iso_2'=>'JV','name_en'=>'Javanese','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Basa Jawa','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Javanisch'),
			array('id'=>162,'iso_2'=>'KG','name_en'=>'Kongo','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Kikongo','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kongo'),
			array('id'=>163,'iso_2'=>'KI','name_en'=>'Kikuyu','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Gĩkũyũ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kikuyu-Sprache'),
			array('id'=>164,'iso_2'=>'KJ','name_en'=>'Kuanyama','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Kuanyama','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kwanyama'),
			array('id'=>165,'iso_2'=>'KR','name_en'=>'Kanuri','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Kanuri','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kanuri-Sprache'),
			array('id'=>166,'iso_2'=>'KV','name_en'=>'Komi','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'коми кыв','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Komi-Sprache'),
			array('id'=>167,'iso_2'=>'KW','name_en'=>'Cornish','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Kernewek','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Kornisch'),
			array('id'=>168,'iso_2'=>'LB','name_en'=>'Luxembourgish','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Lëtzebuergesch','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Luxemburgisch'),
			array('id'=>169,'iso_2'=>'LG','name_en'=>'Luganda','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Luganda','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ganda-Sprache'),
			array('id'=>170,'iso_2'=>'LI','name_en'=>'Limburgish','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Limburgs','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Limburgisch'),
			array('id'=>171,'iso_2'=>'LU','name_en'=>'Luba-Katanga','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Luba-Katanga','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Luba'),
			array('id'=>172,'iso_2'=>'MH','name_en'=>'Marshallese','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Kajin M̧ajeļ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Marschallesisch'),
			array('id'=>173,'iso_2'=>'NB','name_en'=>'Norwegian Bokmål','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Norsk bokmål','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Norwegisch Bokmål'),
			array('id'=>174,'iso_2'=>'ND','name_en'=>'North Ndebele','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'isiNdebele','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ndebele-Sprache (Nord)'),
			array('id'=>175,'iso_2'=>'NG','name_en'=>'Ndonga','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Owambo','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ndonga'),
			array('id'=>176,'iso_2'=>'NN','name_en'=>'Norwegian Nynorsk','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Norsk nynorsk','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Norwegisch Nynorsk'),
			array('id'=>177,'iso_2'=>'NR','name_en'=>'South Ndebele','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Ndébélé','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ndebele-Sprache (Süd)'),
			array('id'=>178,'iso_2'=>'NV','name_en'=>'Navajo','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Dinékʼehǰí','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Navajo-Sprache'),
			array('id'=>179,'iso_2'=>'NY','name_en'=>'Chichewa','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'chiCheŵa','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Chewa-Sprache'),
			array('id'=>180,'iso_2'=>'OJ','name_en'=>'Ojibwa','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'ᐊᓂᔑᓈᐯᒧᐎᓐ','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ojibwa-Sprache'),
			array('id'=>181,'iso_2'=>'OS','name_en'=>'Ossetic','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Ирон æвзаг','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Ossetisch'),
			array('id'=>182,'iso_2'=>'PI','name_en'=>'Pali','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Pāli','is_sacred'=>1,'is_constructed'=>0,'name_de'=>'Pali'),
			array('id'=>183,'iso_2'=>'SC','name_en'=>'Sardinian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Sardu','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Sardisch'),
			array('id'=>184,'iso_2'=>'SE','name_en'=>'Northern Sami','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>' Sámegiella','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Nord-Samisch'),
			array('id'=>186,'iso_2'=>'TY','name_en'=>'Tahitian','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Reo Tahiti','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Tahitisch'),
			array('id'=>187,'iso_2'=>'VE','name_en'=>'Venda','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'tshiVenḓa','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Venda-Sprache'),
			array('id'=>188,'iso_2'=>'WA','name_en'=>'Walloon','country_iso_2'=>null,'collate_locale'=>null,'name_local'=>'Walon','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Wallonisch'),
			array('id'=>189,'iso_2'=>'PT','name_en'=>'Brazilian Portuguese','country_iso_2'=>'BR','collate_locale'=>'pt_BR','name_local'=>'Português brasileiro','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Portugiesisch'),
			array('id'=>190,'iso_2'=>'ZH','name_en'=>'Chinese (Simplified)','country_iso_2'=>'CN','collate_locale'=>'zh_CN','name_local'=>'汉语','is_sacred'=>0,'is_constructed'=>0,'name_de'=>'Chinesisch')
		);

		$this->setArray($array);

	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{
		$this->_dataCollection = new Doctrine_Collection($this->getModelClassName());

		foreach($this->_data as $data) {

			$language = L8M_Doctrine_Record::factory($this->getModelClassName());

			$language->merge($data);
			$language->Translation['en']->name = $data['name_en'];
			$language->Translation['de']->name = $data['name_de'];

			$this->_dataCollection->add($language, $data['id']);
		}

	}

}