<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Country/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 7 2014-03-11 16:18:40Z nm $
 */
 
/**
 *
 *
 * Default_Model_Country_Import
 *
 *
 */
class Default_Model_Country_Import extends L8M_Doctrine_Import_Abstract
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
			array('id'=>1,'iso_2'=>'AD','iso_3'=>'AND','name_local'=>'Andorra','name_en'=>'Andorra','name_de'=>'Andorra','territory_iso_nr'=>39),
			array('id'=>2,'iso_2'=>'AE','iso_3'=>'ARE','name_local'=>'الإمارات العربيّة المتّحدة','name_en'=>'United Arab Emirates','name_de'=>'Vereinigte Arabische Emirate','territory_iso_nr'=>null),
			array('id'=>3,'iso_2'=>'AF','iso_3'=>'AFG','name_local'=>'افغانستان','name_en'=>'Afghanistan','name_de'=>'Afghanistan','territory_iso_nr'=>34),
			array('id'=>4,'iso_2'=>'AG','iso_3'=>'ATG','name_local'=>'Antigua and Barbuda','name_en'=>'Antigua and Barbuda','name_de'=>'Antigua und Barbuda','territory_iso_nr'=>29),
			array('id'=>5,'iso_2'=>'AI','iso_3'=>'AIA','name_local'=>'Anguilla','name_en'=>'Anguilla','name_de'=>'Anguilla','territory_iso_nr'=>29),
			array('id'=>6,'iso_2'=>'AL','iso_3'=>'ALB','name_local'=>'Shqipëria','name_en'=>'Albania','name_de'=>'Albanien','territory_iso_nr'=>39),
			array('id'=>7,'iso_2'=>'AM','iso_3'=>'ARM','name_local'=>'Հայաստան','name_en'=>'Armenia','name_de'=>'Armenien','territory_iso_nr'=>null),
			array('id'=>8,'iso_2'=>'AN','iso_3'=>'ANT','name_local'=>'Nederlandse Antillen','name_en'=>'Netherlands Antilles','name_de'=>'Niederländische Antillen','territory_iso_nr'=>29),
			array('id'=>9,'iso_2'=>'AO','iso_3'=>'AGO','name_local'=>'Angola','name_en'=>'Angola','name_de'=>'Angola','territory_iso_nr'=>17),
			array('id'=>10,'iso_2'=>'AQ','iso_3'=>'ATA','name_local'=>'Antarctica','name_en'=>'Antarctica','name_de'=>'Antarktis','territory_iso_nr'=>null),
			array('id'=>11,'iso_2'=>'AR','iso_3'=>'ARG','name_local'=>'Argentina','name_en'=>'Argentina','name_de'=>'Argentinien','territory_iso_nr'=>5),
			array('id'=>12,'iso_2'=>'AS','iso_3'=>'ASM','name_local'=>'Amerika Samoa','name_en'=>'American Samoa','name_de'=>'Amerikanisch-Samoa','territory_iso_nr'=>61),
			array('id'=>13,'iso_2'=>'AT','iso_3'=>'AUT','name_local'=>'Österreich','name_en'=>'Austria','name_de'=>'Österreich','territory_iso_nr'=>155),
			array('id'=>14,'iso_2'=>'AU','iso_3'=>'AUS','name_local'=>'Australia','name_en'=>'Australia','name_de'=>'Australien','territory_iso_nr'=>53),
			array('id'=>15,'iso_2'=>'AW','iso_3'=>'ABW','name_local'=>'Aruba','name_en'=>'Aruba','name_de'=>'Aruba','territory_iso_nr'=>29),
			array('id'=>16,'iso_2'=>'AZ','iso_3'=>'AZE','name_local'=>'Azərbaycan','name_en'=>'Azerbaijan','name_de'=>'Aserbaidschan','territory_iso_nr'=>null),
			array('id'=>17,'iso_2'=>'BA','iso_3'=>'BIH','name_local'=>'BiH/БиХ','name_en'=>'Bosnia and Herzegovina','name_de'=>'Bosnien und Herzegowina','territory_iso_nr'=>39),
			array('id'=>18,'iso_2'=>'BB','iso_3'=>'BRB','name_local'=>'Barbados','name_en'=>'Barbados','name_de'=>'Barbados','territory_iso_nr'=>29),
			array('id'=>19,'iso_2'=>'BD','iso_3'=>'BGD','name_local'=>'বাংলাদেশ','name_en'=>'Bangladesh','name_de'=>'Bangladesch','territory_iso_nr'=>34),
			array('id'=>20,'iso_2'=>'BE','iso_3'=>'BEL','name_local'=>'Belgique','name_en'=>'Belgium','name_de'=>'Belgien','territory_iso_nr'=>155),
			array('id'=>21,'iso_2'=>'BF','iso_3'=>'BFA','name_local'=>'Burkina','name_en'=>'Burkina Faso','name_de'=>'Burkina Faso','territory_iso_nr'=>11),
			array('id'=>22,'iso_2'=>'BG','iso_3'=>'BGR','name_local'=>'България','name_en'=>'Bulgaria','name_de'=>'Bulgarien','territory_iso_nr'=>151),
			array('id'=>23,'iso_2'=>'BH','iso_3'=>'BHR','name_local'=>'البحري','name_en'=>'Bahrain','name_de'=>'Bahrain','territory_iso_nr'=>145),
			array('id'=>24,'iso_2'=>'BI','iso_3'=>'BDI','name_local'=>'Burundi','name_en'=>'Burundi','name_de'=>'Burundi','territory_iso_nr'=>14),
			array('id'=>25,'iso_2'=>'BJ','iso_3'=>'BEN','name_local'=>'Bénin','name_en'=>'Benin','name_de'=>'Benin','territory_iso_nr'=>11),
			array('id'=>26,'iso_2'=>'BM','iso_3'=>'BMU','name_local'=>'Bermuda','name_en'=>'Bermuda','name_de'=>'Bermuda','territory_iso_nr'=>21),
			array('id'=>27,'iso_2'=>'BN','iso_3'=>'BRN','name_local'=>'دارالسلام','name_en'=>'Brunei','name_de'=>'Brunei Darussalam','territory_iso_nr'=>35),
			array('id'=>28,'iso_2'=>'BO','iso_3'=>'BOL','name_local'=>'Bolivia','name_en'=>'Bolivia','name_de'=>'Bolivien','territory_iso_nr'=>5),
			array('id'=>29,'iso_2'=>'BR','iso_3'=>'BRA','name_local'=>'Brasil','name_en'=>'Brazil','name_de'=>'Brasilien','territory_iso_nr'=>5),
			array('id'=>30,'iso_2'=>'BS','iso_3'=>'BHS','name_local'=>'The Bahamas','name_en'=>'The Bahamas','name_de'=>'Bahamas','territory_iso_nr'=>29),
			array('id'=>31,'iso_2'=>'BT','iso_3'=>'BTN','name_local'=>'Druk-Yul','name_en'=>'Bhutan','name_de'=>'Bhutan','territory_iso_nr'=>34),
			array('id'=>32,'iso_2'=>'BV','iso_3'=>'BVT','name_local'=>'Bouvet Island','name_en'=>'Bouvet Island','name_de'=>'Bouvetinsel','territory_iso_nr'=>null),
			array('id'=>33,'iso_2'=>'BW','iso_3'=>'BWA','name_local'=>'Botswana','name_en'=>'Botswana','name_de'=>'Botsuana','territory_iso_nr'=>18),
			array('id'=>34,'iso_2'=>'BY','iso_3'=>'BLR','name_local'=>'Беларусь','name_en'=>'Belarus','name_de'=>'Belarus','territory_iso_nr'=>null),
			array('id'=>35,'iso_2'=>'BZ','iso_3'=>'BLZ','name_local'=>'Belize','name_en'=>'Belize','name_de'=>'Belize','territory_iso_nr'=>13),
			array('id'=>36,'iso_2'=>'CA','iso_3'=>'CAN','name_local'=>'Canada','name_en'=>'Canada','name_de'=>'Kanada','territory_iso_nr'=>21),
			array('id'=>37,'iso_2'=>'CC','iso_3'=>'CCK','name_local'=>'Cocos (Keeling) Islands','name_en'=>'Cocos (Keeling) Islands','name_de'=>'Kokosinseln (Keeling)','territory_iso_nr'=>53),
			array('id'=>38,'iso_2'=>'CD','iso_3'=>'COD','name_local'=>'Congo','name_en'=>'Congo','name_de'=>'Demokratische Republik Kongo','territory_iso_nr'=>17),
			array('id'=>39,'iso_2'=>'CF','iso_3'=>'CAF','name_local'=>'Centrafrique','name_en'=>'Central African Republic','name_de'=>'Zentralafrikanische Republik','territory_iso_nr'=>17),
			array('id'=>40,'iso_2'=>'CG','iso_3'=>'COG','name_local'=>'Congo-Brazzaville','name_en'=>'Congo-Brazzaville','name_de'=>'Kongo','territory_iso_nr'=>17),
			array('id'=>41,'iso_2'=>'CH','iso_3'=>'CHE','name_local'=>'Schweiz','name_en'=>'Switzerland','name_de'=>'Schweiz','territory_iso_nr'=>155),
			array('id'=>42,'iso_2'=>'CI','iso_3'=>'CIV','name_local'=>'Côte d’Ivoire','name_en'=>'Côte d’Ivoire','name_de'=>'Elfenbeinküste','territory_iso_nr'=>11),
			array('id'=>43,'iso_2'=>'CK','iso_3'=>'COK','name_local'=>'Cook Islands','name_en'=>'Cook Islands','name_de'=>'Cookinseln','territory_iso_nr'=>61),
			array('id'=>44,'iso_2'=>'CL','iso_3'=>'CHL','name_local'=>'Chile','name_en'=>'Chile','name_de'=>'Chile','territory_iso_nr'=>5),
			array('id'=>45,'iso_2'=>'CM','iso_3'=>'CMR','name_local'=>'Cameroun','name_en'=>'Cameroon','name_de'=>'Kamerun','territory_iso_nr'=>17),
			array('id'=>46,'iso_2'=>'CN','iso_3'=>'CHN','name_local'=>'中华','name_en'=>'China','name_de'=>'China','territory_iso_nr'=>30),
			array('id'=>47,'iso_2'=>'CO','iso_3'=>'COL','name_local'=>'Colombia','name_en'=>'Colombia','name_de'=>'Kolumbien','territory_iso_nr'=>5),
			array('id'=>48,'iso_2'=>'CR','iso_3'=>'CRI','name_local'=>'Costa Rica','name_en'=>'Costa Rica','name_de'=>'Costa Rica','territory_iso_nr'=>13),
			array('id'=>49,'iso_2'=>'CU','iso_3'=>'CUB','name_local'=>'Cuba','name_en'=>'Cuba','name_de'=>'Kuba','territory_iso_nr'=>29),
			array('id'=>50,'iso_2'=>'CV','iso_3'=>'CPV','name_local'=>'Cabo Verde','name_en'=>'Cape Verde','name_de'=>'Kap Verde','territory_iso_nr'=>11),
			array('id'=>51,'iso_2'=>'CX','iso_3'=>'CXR','name_local'=>'Christmas Island','name_en'=>'Christmas Island','name_de'=>'Weihnachtsinsel','territory_iso_nr'=>null),
			array('id'=>52,'iso_2'=>'CY','iso_3'=>'CYP','name_local'=>'Κύπρος / Kıbrıs','name_en'=>'Cyprus','name_de'=>'Zypern','territory_iso_nr'=>145),
			array('id'=>53,'iso_2'=>'CZ','iso_3'=>'CZE','name_local'=>'Cesko','name_en'=>'Czech Republic','name_de'=>'Tschechische Republik','territory_iso_nr'=>151),
			array('id'=>54,'iso_2'=>'DE','iso_3'=>'DEU','name_local'=>'Deutschland','name_en'=>'Germany','name_de'=>'Deutschland','territory_iso_nr'=>155),
			array('id'=>55,'iso_2'=>'DJ','iso_3'=>'DJI','name_local'=>'جيبوتي /Djibouti','name_en'=>'Djibouti','name_de'=>'Dschibuti','territory_iso_nr'=>14),
			array('id'=>56,'iso_2'=>'DK','iso_3'=>'DNK','name_local'=>'Danmark','name_en'=>'Denmark','name_de'=>'Dänemark','territory_iso_nr'=>154),
			array('id'=>57,'iso_2'=>'DM','iso_3'=>'DMA','name_local'=>'Dominica','name_en'=>'Dominica','name_de'=>'Dominica','territory_iso_nr'=>29),
			array('id'=>58,'iso_2'=>'DO','iso_3'=>'DOM','name_local'=>'Quisqueya','name_en'=>'Dominican Republic','name_de'=>'Dominikanische Republik','territory_iso_nr'=>29),
			array('id'=>59,'iso_2'=>'DZ','iso_3'=>'DZA','name_local'=>'الجزائ','name_en'=>'Algeria','name_de'=>'Algerien','territory_iso_nr'=>15),
			array('id'=>60,'iso_2'=>'EC','iso_3'=>'ECU','name_local'=>'Ecuador','name_en'=>'Ecuador','name_de'=>'Ecuador','territory_iso_nr'=>5),
			array('id'=>61,'iso_2'=>'EE','iso_3'=>'EST','name_local'=>'Eesti','name_en'=>'Estonia','name_de'=>'Estland','territory_iso_nr'=>154),
			array('id'=>62,'iso_2'=>'EG','iso_3'=>'EGY','name_local'=>'مصر','name_en'=>'Egypt','name_de'=>'Ägypten','territory_iso_nr'=>15),
			array('id'=>63,'iso_2'=>'EH','iso_3'=>'ESH','name_local'=>'الصحراء الغربي','name_en'=>'Western Sahara','name_de'=>'Westsahara','territory_iso_nr'=>15),
			array('id'=>64,'iso_2'=>'ER','iso_3'=>'ERI','name_local'=>'ኤርትራ','name_en'=>'Eritrea','name_de'=>'Eritrea','territory_iso_nr'=>14),
			array('id'=>65,'iso_2'=>'ES','iso_3'=>'ESP','name_local'=>'España','name_en'=>'Spain','name_de'=>'Spanien','territory_iso_nr'=>39),
			array('id'=>66,'iso_2'=>'ET','iso_3'=>'ETH','name_local'=>'ኢትዮጵያ','name_en'=>'Ethiopia','name_de'=>'Äthiopien','territory_iso_nr'=>14),
			array('id'=>67,'iso_2'=>'FI','iso_3'=>'FIN','name_local'=>'Suomi','name_en'=>'Finland','name_de'=>'Finnland','territory_iso_nr'=>154),
			array('id'=>68,'iso_2'=>'FJ','iso_3'=>'FJI','name_local'=>'Viti','name_en'=>'Fiji','name_de'=>'Fidschi','territory_iso_nr'=>54),
			array('id'=>69,'iso_2'=>'FK','iso_3'=>'FLK','name_local'=>'Falkland Islands','name_en'=>'Falkland Islands','name_de'=>'Falklandinseln','territory_iso_nr'=>5),
			array('id'=>70,'iso_2'=>'FM','iso_3'=>'FSM','name_local'=>'Micronesia','name_en'=>'Micronesia','name_de'=>'Mikronesien','territory_iso_nr'=>57),
			array('id'=>71,'iso_2'=>'FO','iso_3'=>'FRO','name_local'=>'Føroyar / Færøerne','name_en'=>'Faroes','name_de'=>'Färöer','territory_iso_nr'=>154),
			array('id'=>72,'iso_2'=>'FR','iso_3'=>'FRA','name_local'=>'France','name_en'=>'France','name_de'=>'Frankreich','territory_iso_nr'=>155),
			array('id'=>73,'iso_2'=>'GA','iso_3'=>'GAB','name_local'=>'Gabon','name_en'=>'Gabon','name_de'=>'Gabun','territory_iso_nr'=>17),
			array('id'=>74,'iso_2'=>'GB','iso_3'=>'GBR','name_local'=>'United Kingdom','name_en'=>'United Kingdom','name_de'=>'Vereinigtes Königreich','territory_iso_nr'=>154),
			array('id'=>75,'iso_2'=>'GD','iso_3'=>'GRD','name_local'=>'Grenada','name_en'=>'Grenada','name_de'=>'Grenada','territory_iso_nr'=>29),
			array('id'=>76,'iso_2'=>'GE','iso_3'=>'GEO','name_local'=>'საქართველო','name_en'=>'Georgia','name_de'=>'Georgien','territory_iso_nr'=>null),
			array('id'=>77,'iso_2'=>'GF','iso_3'=>'GUF','name_local'=>'Guyane française','name_en'=>'French Guiana','name_de'=>'Französisch-Guayana','territory_iso_nr'=>5),
			array('id'=>78,'iso_2'=>'GH','iso_3'=>'GHA','name_local'=>'Ghana','name_en'=>'Ghana','name_de'=>'Ghana','territory_iso_nr'=>11),
			array('id'=>79,'iso_2'=>'GI','iso_3'=>'GIB','name_local'=>'Gibraltar','name_en'=>'Gibraltar','name_de'=>'Gibraltar','territory_iso_nr'=>39),
			array('id'=>80,'iso_2'=>'GL','iso_3'=>'GRL','name_local'=>'Grønland','name_en'=>'Greenland','name_de'=>'Grönland','territory_iso_nr'=>21),
			array('id'=>81,'iso_2'=>'GM','iso_3'=>'GMB','name_local'=>'Gambia','name_en'=>'Gambia','name_de'=>'Gambia','territory_iso_nr'=>11),
			array('id'=>82,'iso_2'=>'GN','iso_3'=>'GIN','name_local'=>'Guinée','name_en'=>'Guinea','name_de'=>'Guinea','territory_iso_nr'=>11),
			array('id'=>83,'iso_2'=>'GP','iso_3'=>'GLP','name_local'=>'Guadeloupe','name_en'=>'Guadeloupe','name_de'=>'Guadeloupe','territory_iso_nr'=>29),
			array('id'=>84,'iso_2'=>'GQ','iso_3'=>'GNQ','name_local'=>'Guinea Ecuatorial','name_en'=>'Equatorial Guinea','name_de'=>'Äquatorialguinea','territory_iso_nr'=>17),
			array('id'=>85,'iso_2'=>'GR','iso_3'=>'GRC','name_local'=>'Ελλάδα','name_en'=>'Greece','name_de'=>'Griechenland','territory_iso_nr'=>39),
			array('id'=>86,'iso_2'=>'GS','iso_3'=>'SGS','name_local'=>'South Georgia and the South Sandwich Islands','name_en'=>'South Georgia and the South Sandwich Islands','name_de'=>'Südgeorgien und die Südlichen Sandwichinseln','territory_iso_nr'=>null),
			array('id'=>87,'iso_2'=>'GT','iso_3'=>'GTM','name_local'=>'Guatemala','name_en'=>'Guatemala','name_de'=>'Guatemala','territory_iso_nr'=>13),
			array('id'=>88,'iso_2'=>'GU','iso_3'=>'GUM','name_local'=>'Guåhån','name_en'=>'Guam','name_de'=>'Guam','territory_iso_nr'=>57),
			array('id'=>89,'iso_2'=>'GW','iso_3'=>'GNB','name_local'=>'Guiné-Bissau','name_en'=>'Guinea-Bissau','name_de'=>'Guinea-Bissau','territory_iso_nr'=>11),
			array('id'=>90,'iso_2'=>'GY','iso_3'=>'GUY','name_local'=>'Guyana','name_en'=>'Guyana','name_de'=>'Guyana','territory_iso_nr'=>5),
			array('id'=>91,'iso_2'=>'HK','iso_3'=>'HKG','name_local'=>'香港','name_en'=>'Hong Kong SAR of China','name_de'=>'Hong Kong S.A.R., China','territory_iso_nr'=>30),
			array('id'=>92,'iso_2'=>'HN','iso_3'=>'HND','name_local'=>'Honduras','name_en'=>'Honduras','name_de'=>'Honduras','territory_iso_nr'=>13),
			array('id'=>93,'iso_2'=>'HR','iso_3'=>'HRV','name_local'=>'Hrvatska','name_en'=>'Croatia','name_de'=>'Kroatien','territory_iso_nr'=>39),
			array('id'=>94,'iso_2'=>'HT','iso_3'=>'HTI','name_local'=>'Ayiti','name_en'=>'Haiti','name_de'=>'Haiti','territory_iso_nr'=>29),
			array('id'=>95,'iso_2'=>'HU','iso_3'=>'HUN','name_local'=>'Magyarország','name_en'=>'Hungary','name_de'=>'Ungarn','territory_iso_nr'=>151),
			array('id'=>96,'iso_2'=>'ID','iso_3'=>'IDN','name_local'=>'Indonesia','name_en'=>'Indonesia','name_de'=>'Indonesien','territory_iso_nr'=>35),
			array('id'=>97,'iso_2'=>'IE','iso_3'=>'IRL','name_local'=>'Éire','name_en'=>'Ireland','name_de'=>'Irland','territory_iso_nr'=>154),
			array('id'=>98,'iso_2'=>'IL','iso_3'=>'ISR','name_local'=>'ישראל','name_en'=>'Israel','name_de'=>'Israel','territory_iso_nr'=>145),
			array('id'=>99,'iso_2'=>'IN','iso_3'=>'IND','name_local'=>'India','name_en'=>'India','name_de'=>'Indien','territory_iso_nr'=>34),
			array('id'=>100,'iso_2'=>'IO','iso_3'=>'IOT','name_local'=>'British Indian Ocean Territory','name_en'=>'British Indian Ocean Territory','name_de'=>'Britisches Territorium im Indischen Ozean','territory_iso_nr'=>null),
			array('id'=>101,'iso_2'=>'IQ','iso_3'=>'IRQ','name_local'=>'العراق / عيَراق','name_en'=>'Iraq','name_de'=>'Irak','territory_iso_nr'=>145),
			array('id'=>102,'iso_2'=>'IR','iso_3'=>'IRN','name_local'=>'ايران','name_en'=>'Iran','name_de'=>'Iran','territory_iso_nr'=>34),
			array('id'=>103,'iso_2'=>'IS','iso_3'=>'ISL','name_local'=>'Ísland','name_en'=>'Iceland','name_de'=>'Island','territory_iso_nr'=>154),
			array('id'=>104,'iso_2'=>'IT','iso_3'=>'ITA','name_local'=>'Italia','name_en'=>'Italy','name_de'=>'Italien','territory_iso_nr'=>39),
			array('id'=>105,'iso_2'=>'JM','iso_3'=>'JAM','name_local'=>'Jamaica','name_en'=>'Jamaica','name_de'=>'Jamaika','territory_iso_nr'=>29),
			array('id'=>106,'iso_2'=>'JO','iso_3'=>'JOR','name_local'=>'أردنّ','name_en'=>'Jordan','name_de'=>'Jordanien','territory_iso_nr'=>145),
			array('id'=>107,'iso_2'=>'JP','iso_3'=>'JPN','name_local'=>'日本','name_en'=>'Japan','name_de'=>'Japan','territory_iso_nr'=>30),
			array('id'=>108,'iso_2'=>'KE','iso_3'=>'KEN','name_local'=>'Kenya','name_en'=>'Kenya','name_de'=>'Kenia','territory_iso_nr'=>14),
			array('id'=>109,'iso_2'=>'KG','iso_3'=>'KGZ','name_local'=>'Кыргызстан','name_en'=>'Kyrgyzstan','name_de'=>'Kirgisistan','territory_iso_nr'=>143),
			array('id'=>110,'iso_2'=>'KH','iso_3'=>'KHM','name_local'=>'Kâmpŭchea','name_en'=>'Cambodia','name_de'=>'Kambodscha','territory_iso_nr'=>35),
			array('id'=>111,'iso_2'=>'KI','iso_3'=>'KIR','name_local'=>'Kiribati','name_en'=>'Kiribati','name_de'=>'Kiribati','territory_iso_nr'=>57),
			array('id'=>112,'iso_2'=>'KM','iso_3'=>'COM','name_local'=>'اتحاد القمر','name_en'=>'Comoros','name_de'=>'Komoren','territory_iso_nr'=>14),
			array('id'=>113,'iso_2'=>'KN','iso_3'=>'KNA','name_local'=>'Saint Kitts and Nevis','name_en'=>'Saint Kitts and Nevis','name_de'=>'St. Kitts und Nevis','territory_iso_nr'=>29),
			array('id'=>114,'iso_2'=>'KP','iso_3'=>'PRK','name_local'=>'북조선','name_en'=>'North Korea','name_de'=>'Demokratische Volksrepublik Korea','territory_iso_nr'=>30),
			array('id'=>115,'iso_2'=>'KR','iso_3'=>'KOR','name_local'=>'한국','name_en'=>'South Korea','name_de'=>'Republik Korea','territory_iso_nr'=>30),
			array('id'=>116,'iso_2'=>'KW','iso_3'=>'KWT','name_local'=>'الكويت','name_en'=>'Kuwait','name_de'=>'Kuwait','territory_iso_nr'=>145),
			array('id'=>117,'iso_2'=>'KY','iso_3'=>'CYM','name_local'=>'Cayman Islands','name_en'=>'Cayman Islands','name_de'=>'Kaimaninseln','territory_iso_nr'=>29),
			array('id'=>118,'iso_2'=>'KZ','iso_3'=>'KAZ','name_local'=>'Қазақстан /Казахстан','name_en'=>'Kazakhstan','name_de'=>'Kasachstan','territory_iso_nr'=>143),
			array('id'=>119,'iso_2'=>'LA','iso_3'=>'LAO','name_local'=>'ເມືອງລາວ','name_en'=>'Laos','name_de'=>'Laos','territory_iso_nr'=>35),
			array('id'=>120,'iso_2'=>'LB','iso_3'=>'LBN','name_local'=>'لبنان','name_en'=>'Lebanon','name_de'=>'Libanon','territory_iso_nr'=>145),
			array('id'=>121,'iso_2'=>'LC','iso_3'=>'LCA','name_local'=>'Saint Lucia','name_en'=>'Saint Lucia','name_de'=>'St. Lucia','territory_iso_nr'=>29),
			array('id'=>122,'iso_2'=>'LI','iso_3'=>'LIE','name_local'=>'Liechtenstein','name_en'=>'Liechtenstein','name_de'=>'Liechtenstein','territory_iso_nr'=>155),
			array('id'=>123,'iso_2'=>'LK','iso_3'=>'LKA','name_local'=>'ශ්‍රී ලංකා / இலங்கை','name_en'=>'Sri Lanka','name_de'=>'Sri Lanka','territory_iso_nr'=>34),
			array('id'=>124,'iso_2'=>'LR','iso_3'=>'LBR','name_local'=>'Liberia','name_en'=>'Liberia','name_de'=>'Liberia','territory_iso_nr'=>11),
			array('id'=>125,'iso_2'=>'LS','iso_3'=>'LSO','name_local'=>'Lesotho','name_en'=>'Lesotho','name_de'=>'Lesotho','territory_iso_nr'=>18),
			array('id'=>126,'iso_2'=>'LT','iso_3'=>'LTU','name_local'=>'Lietuva','name_en'=>'Lithuania','name_de'=>'Litauen','territory_iso_nr'=>154),
			array('id'=>127,'iso_2'=>'LU','iso_3'=>'LUX','name_local'=>'Luxemburg','name_en'=>'Luxembourg','name_de'=>'Luxemburg','territory_iso_nr'=>155),
			array('id'=>128,'iso_2'=>'LV','iso_3'=>'LVA','name_local'=>'Latvija','name_en'=>'Latvia','name_de'=>'Lettland','territory_iso_nr'=>154),
			array('id'=>129,'iso_2'=>'LY','iso_3'=>'LBY','name_local'=>'الليبية','name_en'=>'Libya','name_de'=>'Libyen','territory_iso_nr'=>15),
			array('id'=>130,'iso_2'=>'MA','iso_3'=>'MAR','name_local'=>'المغربية','name_en'=>'Morocco','name_de'=>'Marokko','territory_iso_nr'=>15),
			array('id'=>131,'iso_2'=>'MC','iso_3'=>'MCO','name_local'=>'Monaco','name_en'=>'Monaco','name_de'=>'Monaco','territory_iso_nr'=>155),
			array('id'=>132,'iso_2'=>'MD','iso_3'=>'MDA','name_local'=>'Moldova','name_en'=>'Moldova','name_de'=>'Republik Moldau','territory_iso_nr'=>null),
			array('id'=>133,'iso_2'=>'MG','iso_3'=>'MDG','name_local'=>'Madagascar','name_en'=>'Madagascar','name_de'=>'Madagaskar','territory_iso_nr'=>14),
			array('id'=>134,'iso_2'=>'MH','iso_3'=>'MHL','name_local'=>'Marshall Islands','name_en'=>'Marshall Islands','name_de'=>'Marschallinseln','territory_iso_nr'=>57),
			array('id'=>135,'iso_2'=>'MK','iso_3'=>'MKD','name_local'=>'Македонија','name_en'=>'Macedonia','name_de'=>'Mazedonien','territory_iso_nr'=>39),
			array('id'=>136,'iso_2'=>'ML','iso_3'=>'MLI','name_local'=>'Mali','name_en'=>'Mali','name_de'=>'Mali','territory_iso_nr'=>11),
			array('id'=>137,'iso_2'=>'MM','iso_3'=>'MMR','name_local'=>'Myanmar','name_en'=>'Myanmar','name_de'=>'Myanmar','territory_iso_nr'=>35),
			array('id'=>138,'iso_2'=>'MN','iso_3'=>'MNG','name_local'=>'Монгол Улс','name_en'=>'Mongolia','name_de'=>'Mongolei','territory_iso_nr'=>30),
			array('id'=>139,'iso_2'=>'MO','iso_3'=>'MAC','name_local'=>'澳門 / Macau','name_en'=>'Macao SAR of China','name_de'=>'Macau S.A.R., China','territory_iso_nr'=>30),
			array('id'=>140,'iso_2'=>'MP','iso_3'=>'MNP','name_local'=>'Northern Marianas','name_en'=>'Northern Marianas','name_de'=>'Nördliche Marianen','territory_iso_nr'=>57),
			array('id'=>141,'iso_2'=>'MQ','iso_3'=>'MTQ','name_local'=>'Martinique','name_en'=>'Martinique','name_de'=>'Martinique','territory_iso_nr'=>29),
			array('id'=>142,'iso_2'=>'MR','iso_3'=>'MRT','name_local'=>'الموريتانية','name_en'=>'Mauritania','name_de'=>'Mauretanien','territory_iso_nr'=>11),
			array('id'=>143,'iso_2'=>'MS','iso_3'=>'MSR','name_local'=>'Montserrat','name_en'=>'Montserrat','name_de'=>'Montserrat','territory_iso_nr'=>29),
			array('id'=>144,'iso_2'=>'MT','iso_3'=>'MLT','name_local'=>'Malta','name_en'=>'Malta','name_de'=>'Malta','territory_iso_nr'=>39),
			array('id'=>145,'iso_2'=>'MU','iso_3'=>'MUS','name_local'=>'Mauritius','name_en'=>'Mauritius','name_de'=>'Mauritius','territory_iso_nr'=>14),
			array('id'=>146,'iso_2'=>'MV','iso_3'=>'MDV','name_local'=>'ޖުމުހޫރިއްޔ','name_en'=>'Maldives','name_de'=>'Malediven','territory_iso_nr'=>34),
			array('id'=>147,'iso_2'=>'MW','iso_3'=>'MWI','name_local'=>'Malawi','name_en'=>'Malawi','name_de'=>'Malawi','territory_iso_nr'=>14),
			array('id'=>148,'iso_2'=>'MX','iso_3'=>'MEX','name_local'=>'México','name_en'=>'Mexico','name_de'=>'Mexiko','territory_iso_nr'=>13),
			array('id'=>149,'iso_2'=>'MY','iso_3'=>'MYS','name_local'=>'مليسيا','name_en'=>'Malaysia','name_de'=>'Malaysia','territory_iso_nr'=>35),
			array('id'=>150,'iso_2'=>'MZ','iso_3'=>'MOZ','name_local'=>'Moçambique','name_en'=>'Mozambique','name_de'=>'Mosambik','territory_iso_nr'=>14),
			array('id'=>151,'iso_2'=>'NA','iso_3'=>'NAM','name_local'=>'Namibia','name_en'=>'Namibia','name_de'=>'Namibia','territory_iso_nr'=>18),
			array('id'=>152,'iso_2'=>'NC','iso_3'=>'NCL','name_local'=>'Nouvelle-Calédonie','name_en'=>'New Caledonia','name_de'=>'Neukaledonien','territory_iso_nr'=>54),
			array('id'=>153,'iso_2'=>'NE','iso_3'=>'NER','name_local'=>'Niger','name_en'=>'Niger','name_de'=>'Niger','territory_iso_nr'=>11),
			array('id'=>154,'iso_2'=>'NF','iso_3'=>'NFK','name_local'=>'Norfolk Island','name_en'=>'Norfolk Island','name_de'=>'Norfolkinsel','territory_iso_nr'=>53),
			array('id'=>155,'iso_2'=>'NG','iso_3'=>'NGA','name_local'=>'Nigeria','name_en'=>'Nigeria','name_de'=>'Nigeria','territory_iso_nr'=>11),
			array('id'=>156,'iso_2'=>'NI','iso_3'=>'NIC','name_local'=>'Nicaragua','name_en'=>'Nicaragua','name_de'=>'Nicaragua','territory_iso_nr'=>13),
			array('id'=>157,'iso_2'=>'NL','iso_3'=>'NLD','name_local'=>'Nederland','name_en'=>'Netherlands','name_de'=>'Niederlande','territory_iso_nr'=>155),
			array('id'=>158,'iso_2'=>'NO','iso_3'=>'NOR','name_local'=>'Norge','name_en'=>'Norway','name_de'=>'Norwegen','territory_iso_nr'=>154),
			array('id'=>159,'iso_2'=>'NP','iso_3'=>'NPL','name_local'=>'नेपाल','name_en'=>'Nepal','name_de'=>'Nepal','territory_iso_nr'=>34),
			array('id'=>160,'iso_2'=>'NR','iso_3'=>'NRU','name_local'=>'Naoero','name_en'=>'Nauru','name_de'=>'Nauru','territory_iso_nr'=>57),
			array('id'=>161,'iso_2'=>'NU','iso_3'=>'NIU','name_local'=>'Niue','name_en'=>'Niue','name_de'=>'Niue','territory_iso_nr'=>61),
			array('id'=>162,'iso_2'=>'NZ','iso_3'=>'NZL','name_local'=>'New Zealand / Aotearoa','name_en'=>'New Zealand','name_de'=>'Neuseeland','territory_iso_nr'=>53),
			array('id'=>163,'iso_2'=>'OM','iso_3'=>'OMN','name_local'=>'عُمان','name_en'=>'Oman','name_de'=>'Oman','territory_iso_nr'=>145),
			array('id'=>164,'iso_2'=>'PA','iso_3'=>'PAN','name_local'=>'Panamá','name_en'=>'Panama','name_de'=>'Panama','territory_iso_nr'=>13),
			array('id'=>165,'iso_2'=>'PE','iso_3'=>'PER','name_local'=>'Perú','name_en'=>'Peru','name_de'=>'Peru','territory_iso_nr'=>5),
			array('id'=>166,'iso_2'=>'PF','iso_3'=>'PYF','name_local'=>'Polynésie française','name_en'=>'French Polynesia','name_de'=>'Französisch-Polynesien','territory_iso_nr'=>61),
			array('id'=>167,'iso_2'=>'PG','iso_3'=>'PNG','name_local'=>'Papua New Guinea  / Papua Niugini','name_en'=>'Papua New Guinea','name_de'=>'Papua-Neuguinea','territory_iso_nr'=>54),
			array('id'=>168,'iso_2'=>'PH','iso_3'=>'PHL','name_local'=>'Philippines','name_en'=>'Philippines','name_de'=>'Philippinen','territory_iso_nr'=>35),
			array('id'=>169,'iso_2'=>'PK','iso_3'=>'PAK','name_local'=>'پاکستان','name_en'=>'Pakistan','name_de'=>'Pakistan','territory_iso_nr'=>34),
			array('id'=>170,'iso_2'=>'PL','iso_3'=>'POL','name_local'=>'Polska','name_en'=>'Poland','name_de'=>'Polen','territory_iso_nr'=>151),
			array('id'=>171,'iso_2'=>'PM','iso_3'=>'SPM','name_local'=>'Saint-Pierre-et-Miquelon','name_en'=>'Saint Pierre and Miquelon','name_de'=>'St. Pierre und Miquelon','territory_iso_nr'=>21),
			array('id'=>172,'iso_2'=>'PN','iso_3'=>'PCN','name_local'=>'Pitcairn Islands','name_en'=>'Pitcairn Islands','name_de'=>'Pitcairn','territory_iso_nr'=>61),
			array('id'=>173,'iso_2'=>'PR','iso_3'=>'PRI','name_local'=>'Puerto Rico','name_en'=>'Puerto Rico','name_de'=>'Puerto Rico','territory_iso_nr'=>29),
			array('id'=>174,'iso_2'=>'PT','iso_3'=>'PRT','name_local'=>'Portugal','name_en'=>'Portugal','name_de'=>'Portugal','territory_iso_nr'=>39),
			array('id'=>175,'iso_2'=>'PW','iso_3'=>'PLW','name_local'=>'Belau / Palau','name_en'=>'Palau','name_de'=>'Palau','territory_iso_nr'=>57),
			array('id'=>176,'iso_2'=>'PY','iso_3'=>'PRY','name_local'=>'Paraguay','name_en'=>'Paraguay','name_de'=>'Paraguay','territory_iso_nr'=>5),
			array('id'=>177,'iso_2'=>'QA','iso_3'=>'QAT','name_local'=>'قطر','name_en'=>'Qatar','name_de'=>'Katar','territory_iso_nr'=>145),
			array('id'=>178,'iso_2'=>'RE','iso_3'=>'REU','name_local'=>'Réunion','name_en'=>'Reunion','name_de'=>'Réunion','territory_iso_nr'=>14),
			array('id'=>179,'iso_2'=>'RO','iso_3'=>'ROU','name_local'=>'România','name_en'=>'Romania','name_de'=>'Rumänien','territory_iso_nr'=>151),
			array('id'=>180,'iso_2'=>'RU','iso_3'=>'RUS','name_local'=>'Росси́я','name_en'=>'Russia','name_de'=>'Russische Föderation','territory_iso_nr'=>null),
			array('id'=>181,'iso_2'=>'RW','iso_3'=>'RWA','name_local'=>'Rwanda','name_en'=>'Rwanda','name_de'=>'Ruanda','territory_iso_nr'=>14),
			array('id'=>182,'iso_2'=>'SA','iso_3'=>'SAU','name_local'=>'السعودية','name_en'=>'Saudi Arabia','name_de'=>'Saudi-Arabien','territory_iso_nr'=>145),
			array('id'=>183,'iso_2'=>'SB','iso_3'=>'SLB','name_local'=>'Solomon Islands','name_en'=>'Solomon Islands','name_de'=>'Salomonen','territory_iso_nr'=>54),
			array('id'=>184,'iso_2'=>'SC','iso_3'=>'SYC','name_local'=>'Seychelles','name_en'=>'Seychelles','name_de'=>'Seychellen','territory_iso_nr'=>14),
			array('id'=>185,'iso_2'=>'SD','iso_3'=>'SDN','name_local'=>'السودان','name_en'=>'Sudan','name_de'=>'Sudan','territory_iso_nr'=>15),
			array('id'=>186,'iso_2'=>'SE','iso_3'=>'SWE','name_local'=>'Sverige','name_en'=>'Sweden','name_de'=>'Schweden','territory_iso_nr'=>154),
			array('id'=>187,'iso_2'=>'SG','iso_3'=>'SGP','name_local'=>'Singapore','name_en'=>'Singapore','name_de'=>'Singapur','territory_iso_nr'=>35),
			array('id'=>188,'iso_2'=>'SH','iso_3'=>'SHN','name_local'=>'Saint Helena','name_en'=>'Saint Helena','name_de'=>'St. Helena','territory_iso_nr'=>11),
			array('id'=>189,'iso_2'=>'SI','iso_3'=>'SVN','name_local'=>'Slovenija','name_en'=>'Slovenia','name_de'=>'Slowenien','territory_iso_nr'=>39),
			array('id'=>190,'iso_2'=>'SJ','iso_3'=>'SJM','name_local'=>'Svalbard','name_en'=>'Svalbard','name_de'=>'Svalbard und Jan Mayen','territory_iso_nr'=>154),
			array('id'=>191,'iso_2'=>'SK','iso_3'=>'SVK','name_local'=>'Slovensko','name_en'=>'Slovakia','name_de'=>'Slowakei','territory_iso_nr'=>151),
			array('id'=>192,'iso_2'=>'SL','iso_3'=>'SLE','name_local'=>'Sierra Leone','name_en'=>'Sierra Leone','name_de'=>'Sierra Leone','territory_iso_nr'=>11),
			array('id'=>193,'iso_2'=>'SM','iso_3'=>'SMR','name_local'=>'San Marino','name_en'=>'San Marino','name_de'=>'San Marino','territory_iso_nr'=>39),
			array('id'=>194,'iso_2'=>'SN','iso_3'=>'SEN','name_local'=>'Sénégal','name_en'=>'Senegal','name_de'=>'Senegal','territory_iso_nr'=>11),
			array('id'=>195,'iso_2'=>'SO','iso_3'=>'SOM','name_local'=>'Soomaaliya','name_en'=>'Somalia','name_de'=>'Somalia','territory_iso_nr'=>14),
			array('id'=>196,'iso_2'=>'SR','iso_3'=>'SUR','name_local'=>'Suriname','name_en'=>'Suriname','name_de'=>'Suriname','territory_iso_nr'=>5),
			array('id'=>197,'iso_2'=>'ST','iso_3'=>'STP','name_local'=>'São Tomé e Príncipe','name_en'=>'São Tomé e Príncipe','name_de'=>'São Tomé und Príncipe','territory_iso_nr'=>17),
			array('id'=>198,'iso_2'=>'SV','iso_3'=>'SLV','name_local'=>'El Salvador','name_en'=>'El Salvador','name_de'=>'El Salvador','territory_iso_nr'=>13),
			array('id'=>199,'iso_2'=>'SY','iso_3'=>'SYR','name_local'=>'سوري','name_en'=>'Syria','name_de'=>'Syrien','territory_iso_nr'=>145),
			array('id'=>200,'iso_2'=>'SZ','iso_3'=>'SWZ','name_local'=>'weSwatini','name_en'=>'Swaziland','name_de'=>'Swasiland','territory_iso_nr'=>18),
			array('id'=>201,'iso_2'=>'TC','iso_3'=>'TCA','name_local'=>'Turks and Caicos Islands','name_en'=>'Turks and Caicos Islands','name_de'=>'Turks- und Caicosinseln','territory_iso_nr'=>29),
			array('id'=>202,'iso_2'=>'TD','iso_3'=>'TCD','name_local'=>'تشاد / Tchad','name_en'=>'Chad','name_de'=>'Tschad','territory_iso_nr'=>17),
			array('id'=>203,'iso_2'=>'TF','iso_3'=>'ATF','name_local'=>'Terres australes françaises','name_en'=>'French Southern Territories','name_de'=>'Französische Süd- und Antarktisgebiete','territory_iso_nr'=>null),
			array('id'=>204,'iso_2'=>'TG','iso_3'=>'TGO','name_local'=>'Togo','name_en'=>'Togo','name_de'=>'Togo','territory_iso_nr'=>11),
			array('id'=>205,'iso_2'=>'TH','iso_3'=>'THA','name_local'=>'ไทย','name_en'=>'Thailand','name_de'=>'Thailand','territory_iso_nr'=>35),
			array('id'=>206,'iso_2'=>'TJ','iso_3'=>'TJK','name_local'=>'Тоҷикистон','name_en'=>'Tajikistan','name_de'=>'Tadschikistan','territory_iso_nr'=>143),
			array('id'=>207,'iso_2'=>'TK','iso_3'=>'TKL','name_local'=>'Tokelau','name_en'=>'Tokelau','name_de'=>'Tokelau','territory_iso_nr'=>61),
			array('id'=>208,'iso_2'=>'TM','iso_3'=>'TKM','name_local'=>'Türkmenistan','name_en'=>'Turkmenistan','name_de'=>'Turkmenistan','territory_iso_nr'=>143),
			array('id'=>209,'iso_2'=>'TN','iso_3'=>'TUN','name_local'=>'التونسية','name_en'=>'Tunisia','name_de'=>'Tunesien','territory_iso_nr'=>15),
			array('id'=>210,'iso_2'=>'TO','iso_3'=>'TON','name_local'=>'Tonga','name_en'=>'Tonga','name_de'=>'Tonga','territory_iso_nr'=>61),
			array('id'=>211,'iso_2'=>'TL','iso_3'=>'TLS','name_local'=>'Timor Lorosa\'e','name_en'=>'Timor-Leste','name_de'=>'Osttimor','territory_iso_nr'=>35),
			array('id'=>212,'iso_2'=>'TR','iso_3'=>'TUR','name_local'=>'Türkiye','name_en'=>'Turkey','name_de'=>'Türkei','territory_iso_nr'=>145),
			array('id'=>213,'iso_2'=>'TT','iso_3'=>'TTO','name_local'=>'Trinidad and Tobago','name_en'=>'Trinidad and Tobago','name_de'=>'Trinidad und Tobago','territory_iso_nr'=>29),
			array('id'=>214,'iso_2'=>'TV','iso_3'=>'TUV','name_local'=>'Tuvalu','name_en'=>'Tuvalu','name_de'=>'Tuvalu','territory_iso_nr'=>61),
			array('id'=>215,'iso_2'=>'TW','iso_3'=>'TWN','name_local'=>'中華','name_en'=>'Taiwan','name_de'=>'Taiwan','territory_iso_nr'=>30),
			array('id'=>216,'iso_2'=>'TZ','iso_3'=>'TZA','name_local'=>'Tanzania','name_en'=>'Tanzania','name_de'=>'Tansania','territory_iso_nr'=>14),
			array('id'=>217,'iso_2'=>'UA','iso_3'=>'UKR','name_local'=>'Україна','name_en'=>'Ukraine','name_de'=>'Ukraine','territory_iso_nr'=>null),
			array('id'=>218,'iso_2'=>'UG','iso_3'=>'UGA','name_local'=>'Uganda','name_en'=>'Uganda','name_de'=>'Uganda','territory_iso_nr'=>14),
			array('id'=>219,'iso_2'=>'UM','iso_3'=>'UMI','name_local'=>'United States Minor Outlying Islands','name_en'=>'United States Minor Outlying Islands','name_de'=>'Amerikanisch-Ozeanien','territory_iso_nr'=>null),
			array('id'=>220,'iso_2'=>'US','iso_3'=>'USA','name_local'=>'United States','name_en'=>'United States','name_de'=>'Vereinigte Staaten','territory_iso_nr'=>21),
			array('id'=>221,'iso_2'=>'UY','iso_3'=>'URY','name_local'=>'Uruguay','name_en'=>'Uruguay','name_de'=>'Uruguay','territory_iso_nr'=>5),
			array('id'=>222,'iso_2'=>'UZ','iso_3'=>'UZB','name_local'=>'O‘zbekiston','name_en'=>'Uzbekistan','name_de'=>'Usbekistan','territory_iso_nr'=>143),
			array('id'=>223,'iso_2'=>'VA','iso_3'=>'VAT','name_local'=>'Vaticano','name_en'=>'Vatican City','name_de'=>'Vatikanstaat','territory_iso_nr'=>39),
			array('id'=>224,'iso_2'=>'VC','iso_3'=>'VCT','name_local'=>'Saint Vincent and the Grenadines','name_en'=>'Saint Vincent and the Grenadines','name_de'=>'St. Vinzent und die Grenadinen','territory_iso_nr'=>29),
			array('id'=>225,'iso_2'=>'VE','iso_3'=>'VEN','name_local'=>'Venezuela','name_en'=>'Venezuela','name_de'=>'Venezuela','territory_iso_nr'=>5),
			array('id'=>226,'iso_2'=>'VG','iso_3'=>'VGB','name_local'=>'British Virgin Islands','name_en'=>'British Virgin Islands','name_de'=>'Britische Jungferninseln','territory_iso_nr'=>29),
			array('id'=>227,'iso_2'=>'VI','iso_3'=>'VIR','name_local'=>'US Virgin Islands','name_en'=>'US Virgin Islands','name_de'=>'Amerikanische Jungferninseln','territory_iso_nr'=>29),
			array('id'=>228,'iso_2'=>'VN','iso_3'=>'VNM','name_local'=>'Việt Nam','name_en'=>'Vietnam','name_de'=>'Vietnam','territory_iso_nr'=>35),
			array('id'=>229,'iso_2'=>'VU','iso_3'=>'VUT','name_local'=>'Vanuatu','name_en'=>'Vanuatu','name_de'=>'Vanuatu','territory_iso_nr'=>54),
			array('id'=>230,'iso_2'=>'WF','iso_3'=>'WLF','name_local'=>'Wallis and Futuna','name_en'=>'Wallis and Futuna','name_de'=>'Wallis und Futuna','territory_iso_nr'=>61),
			array('id'=>231,'iso_2'=>'WS','iso_3'=>'WSM','name_local'=>'Samoa','name_en'=>'Samoa','name_de'=>'Samoa','territory_iso_nr'=>61),
			array('id'=>232,'iso_2'=>'YE','iso_3'=>'YEM','name_local'=>'اليمنية','name_en'=>'Yemen','name_de'=>'Jemen','territory_iso_nr'=>145),
			array('id'=>233,'iso_2'=>'YT','iso_3'=>'MYT','name_local'=>'Mayotte','name_en'=>'Mayotte','name_de'=>'Mayotte','territory_iso_nr'=>14),
			array('id'=>235,'iso_2'=>'ZA','iso_3'=>'ZAF','name_local'=>'Afrika-Borwa','name_en'=>'South Africa','name_de'=>'Südafrika','territory_iso_nr'=>18),
			array('id'=>236,'iso_2'=>'ZM','iso_3'=>'ZMB','name_local'=>'Zambia','name_en'=>'Zambia','name_de'=>'Sambia','territory_iso_nr'=>14),
			array('id'=>237,'iso_2'=>'ZW','iso_3'=>'ZWE','name_local'=>'Zimbabwe','name_en'=>'Zimbabwe','name_de'=>'Simbabwe','territory_iso_nr'=>14),
			array('id'=>238,'iso_2'=>'PS','iso_3'=>'PSE','name_local'=>'Palestine','name_en'=>'Palestine','name_de'=>'Palästinensische Gebiete','territory_iso_nr'=>145),
			array('id'=>239,'iso_2'=>'CS','iso_3'=>'CSG','name_local'=>'Србија и Црна Гора','name_en'=>'Serbia and Montenegro','name_de'=>'Serbien und Montenegro','territory_iso_nr'=>39),
			array('id'=>240,'iso_2'=>'AX','iso_3'=>'ALA','name_local'=>'Åland Islands','name_en'=>'Åland Islands','name_de'=>'Alandinseln','territory_iso_nr'=>154),
			array('id'=>241,'iso_2'=>'HM','iso_3'=>'HMD','name_local'=>'Heard Island and McDonald Islands','name_en'=>'Heard Island and McDonald Islands','name_de'=>'Heard und McDonaldinseln','territory_iso_nr'=>53),
			array('id'=>242,'iso_2'=>'ME','iso_3'=>'MNE','name_local'=>'Crna Gora','name_en'=>'Montenegro','name_de'=>'Montenegro','territory_iso_nr'=>39),
			array('id'=>243,'iso_2'=>'RS','iso_3'=>'SRB','name_local'=>'Srbija','name_en'=>'Serbia','name_de'=>'Serbien','territory_iso_nr'=>39)
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

			$country = L8M_Doctrine_Record::factory($this->getModelClassName());

			$country->merge($data);
			$country->Translation['en']->name = $data['name_en'];
			$country->Translation['de']->name = $data['name_de'];

			$this->_dataCollection->add($country, $data['id']);

		}

	}

}