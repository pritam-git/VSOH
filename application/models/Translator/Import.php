<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Translator/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 545 2017-08-24 20:24:35Z nm $
 */

/**
 *
 *
 * Default_Model_Translator_Import
 *
 *
 */
class Default_Model_Translator_Import extends L8M_Doctrine_Import_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	protected $_standsForClass = NULL;

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
		/**
		 * pass through to prevent failures
		 */
		parent::_init();

		/**
		 * retrieve class name
		 */
		$this->_retriveStandsForClassName();
		$modelName = $this->_standsForClass;

		/**
		 * retrieve last model
		 */
		$model = Doctrine_Query::create()
			->from($modelName . ' m')
			->limit(1)
			->orderBy('m.id DESC')
			->execute()
			->getFirst()
		;
		if ($model) {
			$i = $model->id + 1;
		} else {
			$i = 1;
		}

		/**
		 * project translations
		 */
		$w = array();

//		$w[] = array(
//			'id'=>$i++,
//			'short'=>'',
//			'text_en'=>'',
//			'text_de'=>'',
//		);

		/**
		 * standards
		 */
		$w = array_merge($w, self::getSystemStandardTranslationArray($i));

		$this->setArray($w);
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{
		/**
		 * retrieve class name
		 */
		$modelName = $this->_standsForClass;

		/**
		 * check whether translatable or not
		 */
		$model = new $modelName();
		$modelRelations = $model->getTable()->getRelations();
		if (array_key_exists('Translation', $modelRelations)) {
			$transCols = $model->Translation->getTable()->getColumns();
			$transLangs = L8M_Locale::getSupported(TRUE);
			$translateable = TRUE;
		} else {
			$translateable = FALSE;
		}

		/**
		 * add data to collection
		 */
		$this->_dataCollection = new Doctrine_Collection($modelName);
		foreach($this->_data as $data) {
			$model = new $modelName();
			$model->merge($data);

			/**
			 * add translatables
			 */
			if ($translateable) {
				foreach ($transCols as $transCol => $colDefinition) {
					if ($transCol != 'id' &&
						$transCol != 'lang' &&
						$transCol != 'created_at' &&
						$transCol != 'updated_at' &&
						$transCol != 'deleted_at') {

						foreach ($transLangs as $transLang) {
							if (array_key_exists($transCol . '_' . $transLang, $data)) {
								$model->Translation[$transLang]->$transCol = $data[$transCol . '_' . $transLang];
							}
						}
					}
				}
			}

			/**
			 * just add data
			 */
			$this->_dataCollection->add($model, $data['id']);
		}
	}

	/**
	 * Retrieve stands for class name.
	 *
	 * @return void
	 */
	protected function _retriveStandsForClassName()
	{
		$name = get_class($this);
		$this->_standsForClass = substr($name, 0, strlen($name) - strlen('_Import'));
	}

	/**
	 * this is containing the standard blank system translations
	 *
	 * @param integer $i
	 * @return array
	 */
	public static function getSystemStandardTranslationArray($i = 1)
	{

		/**
		 * error403
		 */
		$w[] = array(
			'id'=>$i++,
			'short'=>'HTTP/1.1 403 Forbidden',
			'text_en'=>'HTTP/1.1 403 Forbidden',
			'text_de'=>'HTTP/1.1 403 Verboten',
			'text_es'=>'HTTP/1.1 403 Prohibido',
			'text_fr'=>'HTTP/1.1 403 Interdit',
			'text_ru'=>'HTTP/1.1 403 Запрещено',
			'text_bg'=>'HTTP/1.1 403 Забранен',

		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'You are not allowed to access this document.',
			'text_en'=>'You are not allowed to access this document.',
			'text_de'=>'Es ist Ihnen nicht gestattet, auf das angeforderte Dokument zuzugreifen.',
			'text_es'=>'No se le permite tener acceso a este documento.',
			'text_fr'=>'Vous n\'êtes pas autorisé à accéder à ce document.',
			'text_ru'=>'Вы не можете открыть документ.',
			'text_bg'=>'Вие нямате право на достъп до този документ.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'You don\'t have permission to access the document you requested.',
			'text_en'=>'You don\'t have permission to access the document you requested.',
			'text_de'=>'Sie verfügen nicht über hinreichende Berechtigungen, um auf das Dokument zuzugreifen.',
			'text_es'=>'Usted no tiene permiso para acceder al documento que solicitó.',
			'text_fr'=>'Vous n\'êtes pas autorisé à accéder au document que vous avez demandé.',
			'text_ru'=>'Вы не имеете доступа к запрашиваемый документ.',
			'text_bg'=>'Вие нямате разрешение за достъп до документа, за който се кандидатства.',
		);

		/**
		 * error404
		 */
		$w[] = array(
			'id'=>$i++,
			'short'=>'HTTP/1.1 404 Not Found',
			'text_en'=>'HTTP/1.1 404 Not Found',
			'text_de'=>'HTTP/1.1 404 Nicht gefunden',
			'text_es'=>'HTTP/1.1 404 Extraviado',
			'text_fr'=>'HTTP/1.1 404 Pas Trouvé',
			'text_ru'=>'HTTP/1.1 404 Не Найдено',
			'text_bg'=>'HTTP/1.1 404 Ненамерен',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The document you requested could not be found.',
			'text_en'=>'The document you requested could not be found.',
			'text_de'=>'Das angeforderte Dokument konnte nicht gefunden werden.',
			'text_es'=>'El documento que ha solicitado no se pudo encontrar.',
			'text_fr'=>'Le document que vous avez demandé n\'a pas été trouvé.',
			'text_ru'=>'Запрашиваемый документ не может быть найдено.',
			'text_bg'=>'Документът, която търсите не може да бъде намерен.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The document associated with the URL you requested could not be found.',
			'text_en'=>'The document associated with the URL you requested could not be found.',
			'text_de'=>'Das Dokument, welches mit der von Ihnen angeforderten URL verknüpft ist, konnte nicht gefunden werden.',
			'text_es'=>'El documento asociado a la URL solicitada no se pudo encontrar.',
			'text_fr'=>'Le document associé à l\'URL que vous avez demandé n\'a pas été trouvé.',
			'text_ru'=>'Документ, связанный с URL запрошенной не может быть найдено.',
			'text_bg'=>'В документа, свързани с URL, която търсите не може да бъде намерен.',
		);

		/**
		 * error503
		 */
		$w[] = array(
			'id'=>$i++,
			'short'=>'HTTP/1.1 503 Service Unavailable',
			'text_en'=>'HTTP/1.1 503 Service Unavailable',
			'text_de'=>'HTTP/1.1 503 Dienst nicht verfügbar',
			'text_es'=>'HTTP/1.1 503 Servicio no disponible',
			'text_fr'=>'HTTP/1.1 503 Service Indisponible',
			'text_ru'=>'HTTP/1.1 503 Сервис Недоступен',
			'text_bg'=>'HTTP/1.1 503 Услугата не е Достъпна',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The service you requested caused an error.',
			'text_en'=>'The service you requested caused an error.',
			'text_de'=>'Ihre Anfrage hat einen Fehler verursacht.',
			'text_es'=>'El servicio que ha solicitado provocó un error.',
			'text_fr'=>'Le service que vous avez demandé a provoqué une erreur.',
			'text_ru'=>'Запрашиваемая служба вызвала ошибку.',
			'text_bg'=>'Услугата, която заявихте, причинена от грешка.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Error Message',
			'text_en'=>'Error Message',
			'text_de'=>'Fehlermeldung',
			'text_es'=>'Mensaje de Error',
			'text_fr'=>'Message d\'Erreur',
			'text_ru'=>'Сообщение Об Ошибке',
			'text_bg'=>'Съобщение за Грешка',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Error Stack Trace',
			'text_en'=>'Error Stack Trace',
			'text_de'=>'Fehler Ablaufverfolgung',
			'text_es'=>'Error Seguimiento de la Pila',
			'text_fr'=>'Erreur Stack Trace',
			'text_ru'=>'Ошибка Трассировки Стека',
			'text_bg'=>'Грешка Stack Trace',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Error Type',
			'text_en'=>'Error Type',
			'text_de'=>'Fehlertyp',
			'text_es'=>'Tipo de Error',
			'text_fr'=>'Type d\'Erreur',
			'text_ru'=>'Тип Ошибки',
			'text_bg'=>'Тип Error',
		);

		/**
		 * errors
		 */
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' is no valid email address in the basic format local-part@hostname',
			'text_en'=>'\'%value%\' is no valid email address in the basic format "local-part@hostname".',
			'text_de'=>'\'%value%\' ist keine valide Email-Adresse im Format "local-part@hostname".',
			'text_es'=>'\'%value%\' hay una dirección válida de correo electrónico en el formato básico "parte-local @ nombre de host".',
			'text_fr'=>'\'%value%\' a pas d\'adresse e-mail valide dans le format de base "partie locale @ hostname".',
			'text_ru'=>'\'%value%\' не правильный адрес электронной почты в базовом формате "локальная часть @ имя хоста".',
			'text_bg'=>'\'%value%\' не е валиден имейл адрес в основния формат "местно част @ хост".',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Value is required and can\'t be empty',
			'text_en'=>'Value is required and can\'t be empty.',
			'text_de'=>'Der Wert wird benötigt und darf nicht leer sein.',
			'text_es'=>'Se requiere valor y no puede estar vacío.',
			'text_fr'=>'La valeur est nécessaire et ne peut pas être vide.',
			'text_ru'=>'Значение является обязательным и не может быть пустым.',
			'text_bg'=>'Да се въведе стойност и не може да бъде празно.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' does not appear to be a valid IP address',
			'text_en'=>'\'%value%\' does not appear to be a valid IP address.',
			'text_de'=>'\'%value%\' scheint keine valide IP-Adresse zu sein.',
			'text_es'=>'\'%value%\' no parece ser una dirección IP válida.',
			'text_fr'=>'\'%value%\' ne semble pas être une adresse IP valide.',
			'text_ru'=>'\'%value%\' кажется, не быть действительным IP-адрес.',
			'text_bg'=>'\'%value%\' не се появи, за да бъде валиден IP адрес.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' does not appear to be a valid local network name',
			'text_en'=>'\'%value%\' does not appear to be a valid local network name.',
			'text_de'=>'\'%value%\' scheint kein valider Name für ein lokales Netzwerk zu sein.',
			'text_es'=>'\'%value%\' no parece ser un nombre de red local válida.',
			'text_fr'=>'\'%value%\' ne semble pas être un nom local valide réseau.',
			'text_ru'=>'\'%value%\' кажется, не быть действительным местное название сети.',
			'text_bg'=>'\'%value%\' не се появи, за да бъде валиден местното наименование мрежа.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' appears to be a DNS hostname but cannot match TLD against known list',
			'text_en'=>'\'%value%\' appears to be a DNS hostname but cannot match TLD against known list.',
			'text_de'=>'\'%value%\' scheint ein DNS-Hostname zu sein, jedoch ist die TLD nicht bekannt.',
			'text_es'=>'\'%value%\' parece ser un nombre de host DNS pero no puede igualar TLD contra la lista conocida.',
			'text_fr'=>'\'%value%\' semble être un nom d\'hôte DNS, mais ne peut pas correspondre TLD contre liste connue.',
			'text_ru'=>'\'%value%\' кажется, DNS-имя хоста, но не может сравниться TLD против известного списка.',
			'text_bg'=>'\'%value%\' изглежда е DNS име на хост, но не може да се сравнява TLD срещу известен списък.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' appears to be a DNS hostname but cannot extract TLD part',
			'text_en'=>'\'%value%\' appears to be a DNS hostname but cannot extract TLD part.',
			'text_de'=>'\'%value%\' scheint ein DNS-Hostname zu sein, jedoch kann der TLD-Teil nicht extrahiert werden.',
			'text_es'=>'\'%value%\' parece ser un nombre de host DNS, pero no puede extraer la parte TLD.',
			'text_fr'=>'\'%value%\' semble être un nom d\'hôte DNS mais ne peut pas extraire une partie de TLD.',
			'text_ru'=>'\'%value%\' кажется, DNS-имя хоста, но не может извлечь TLD часть.',
			'text_bg'=>'\'%value%\' изглежда е DNS име на хост, но не може да се извлече TLD част.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' appears to be a DNS hostname but contains a dash in an invalid position',
			'text_en'=>'\'%value%\' appears to be a DNS hostname but contains a dash in an invalid position.',
			'text_de'=>'\'%value%\'scheint ein DNS-Hostname zu sein, jedoch enthält er einen Unterstrich an einer falschen Position.',
			'text_es'=>'\'%value%\' parece ser un nombre de host DNS, pero contiene un guión en una posición válida.',
			'text_fr'=>'\'%value%\' semble être un nom d\'hôte DNS, mais contient un tiret dans une position non valide.',
			'text_ru'=>'\'%value%\' кажется, DNS-имя хоста, но содержит тире в неверном месте.',
			'text_bg'=>'\'%value%\' изглежда е DNS име на хост, но съдържа пробив в невалиден позиция.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' is no valid input. Please type in Digits',
			'text_en'=>'\'%value%\' is no valid input. Please type in Digits.',
			'text_de'=>'\'%value%\' ist keine valide Eingabe. Bitte geben Sie nur Zahlen ein.',
			'text_es'=>'\'%value%\' hay entrada válida. Por favor escriba los dígitos.',
			'text_fr'=>'\'%value%\' a pas d\'entrée valide. S\'il vous plaît taper chiffres.',
			'text_ru'=>'\'%value%\' не действует ввода. Пожалуйста, введите цифры.',
			'text_bg'=>'\'%value%\' не е валидна вход. Моля въведете в цифри.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' must contain only digits',
			'text_en'=>'\'%value%\' must contain only digits.',
			'text_de'=>'\'%value%\' darf nur Ziffern enthalten.',
			'text_es'=>'\'%value%\' debe contener sólo dígitos.',
			'text_fr'=>'\'%value%\' doit contenir que des chiffres.',
			'text_ru'=>'\'%value%\' должно содержать только цифры.',
			'text_bg'=>'\'%value%\' трябва да съдържа само цифри.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Media does not exists within that relation.',
			'text_en'=>'Media does not exists within that relation.',
			'text_de'=>'Das Medium existiert in dieser Relation nicht.',
			'text_es'=>'Media no existe dentro de esa relación.',
			'text_fr'=>'Media ne existe pas dans cette relation.',
			'text_ru'=>'Медиа не существует в этом отношении.',
			'text_bg'=>'Медиа не съществува в тази връзка.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' is less than %min% characters long',
			'text_en'=>'\'%value%\' is less than %min% characters long.',
			'text_de'=>'\'%value%\' ist kürzer als %min% Zeichen.',
			'text_es'=>'\'%value%\' es menos de %min% caracteres de longitud.',
			'text_fr'=>'\'%value%\' est inférieure à %min% caractères.',
			'text_ru'=>'\'%value%\' составляет менее %min% символов.',
			'text_bg'=>'\'%value%\' е по-къс от %min% символа.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'String is less than %min% characters long',
			'text_en'=>'String is less than %min% characters long.',
			'text_de'=>'Die Zeichenkette ist kürzer als %min% Zeichen.',
			'text_es'=>'La cadena es menor que %min% caracteres.',
			'text_fr'=>'La chaîne est plus courte de %min% caractères.',
			'text_ru'=>'Строка короче, чем %min% символов.',
			'text_bg'=>'Низът е по-кратък от %min% символа.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'String is more than %max% characters long',
			'text_en'=>'String is more than %max% characters long.',
			'text_de'=>'Die Zeichenkette ist länger als %max% Zeichen.',
			'text_es'=>'La cadena tiene más de %max% caracteres.',
			'text_fr'=>'La chaîne est plus de %max% caractères.',
			'text_ru'=>'Строка дольше, чем %max% символов.',
			'text_bg'=>'Низът е по-дълъг от %max% символа.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Invalid type given. String expected',
			'text_en'=>'Invalid type given. String expected.',
			'text_de'=>'Falscher Typ eingetragen, es wurde ein String erwartet.',
			'text_es'=>'Tipo no válido dado. Cadena esperado.',
			'text_fr'=>'Type valide donné. Chaîne attendue.',
			'text_ru'=>'Неверный тип дано. Строка ожидалось.',
			'text_bg'=>'Невалиден тип дал. Струнен очаква.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' is more than %max% characters long',
			'text_en'=>'\'%value%\' is more than %max% characters long.',
			'text_de'=>'\'%value%\' ist länger als %max% Zeichen.',
			'text_es'=>'\'%value%\' es más de %max% caracteres.',
			'text_fr'=>'\'%value%\' est plus que %max% caractères.',
			'text_ru'=>'\'%value%\' больше, чем %max% символов.',
			'text_bg'=>'\'%value%\' е повече от %max% знака.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' is an empty string',
			'text_en'=>'\'%value%\' is an empty string.',
			'text_de'=>'\'%value%\' ist eine leere Zeichenkette.',
			'text_es'=>'\'%value%\' es una cadena vacía.',
			'text_fr'=>'\'%value%\' est une chaîne vide.',
			'text_ru'=>'\'%value%\' пустая строка.',
			'text_bg'=>'\'%value%\' е празен низ.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The two given tokens do not match',
			'text_en'=>'The two given tokens do not match.',
			'text_de'=>'Die 2 Werte stimmen nicht überein.',
			'text_es'=>'Las dos fichas dadas no coinciden.',
			'text_fr'=>'Les deux jetons donnés ne correspondent pas.',
			'text_ru'=>'Два данные лексемы не совпадают.',
			'text_bg'=>'Двете дадени символи не съвпадат.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' contains non alphabetic characters',
			'text_en'=>'\'%value%\' contains non alphabetic characters.',
			'text_de'=>'\'%value%\' enthält Zeichen, die keine Buchstaben sind.',
			'text_es'=>'\'%value%\' contiene caracteres no alfabéticos.',
			'text_fr'=>'\'%value%\' contient des caractères non alphabétiques.',
			'text_ru'=>'\'%value%\' содержит, кроме букв символов.',
			'text_bg'=>'\'%value%\' съдържа символи, различни от букви.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Invalid type given. String, integer, float, boolean or array expected',
			'text_en'=>'Invalid type given. String, integer, float, boolean or array expected.',
			'text_de'=>'Invalider Typ angegeben. Cadena, Integer, Float, Boolean oder Array wurden erwartet.',
			'text_es'=>'Invalider tipo especificado. Se esperaba String, entero, flotante, booleano o matriz.',
			'text_fr'=>'Type invalider spécifié. Chaîne, integer, float, booléen ou un tableau était attendu.',
			'text_ru'=>'Invalider тип указан. Ожидалось, строка, целое число, вещественное, логическое или массив.',
			'text_bg'=>'Посочено Invalider тип. Очакваше струнен, число, флоат, булев или масив.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Resource "%1s" already exist. You can not name the Controller "%2s".',
			'text_en'=>'Resource "%1s" already exist. You can not name the Controller "%2s".',
			'text_de'=>'Die Resource "%1s" existiert bereits. Sie können den Controller nicht "%2s" nennen.',
			'text_es'=>'Recursos "%1s" ya existentes. No se puede nombrar el controlador "%2s".',
			'text_fr'=>'Resource "%1s" existent déjà. Vous ne pouvez pas nommer le contrôleur "%2s".',
			'text_ru'=>'Ресурс "%1s" уже существуют. Вы не можете назвать контроллер "%2s".',
			'text_bg'=>'Resource "%1s" вече съществува. Вие не можете да дадете име на контролера "%2s".',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'"%1s" is a value, that is not allowed.',
			'text_en'=>'"%1s" is a value, that is not allowed.',
			'text_de'=>'"%1s" ist ein Wert, der nicht erlaubt ist.',
			'text_es'=>'"%1s" es un valor, que no está permitido.',
			'text_fr'=>'"%1s" est une valeur, qui ne sont pas permis.',
			'text_ru'=>'"%1s" Значение, что не допускается.',
			'text_bg'=>'"%1s" е на стойност, която не е позволено.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'"%1s" is already a child of an element and therefore can not be a parent.',
			'text_en'=>'"%1s" is already a child of an element and therefore can not be a parent.',
			'text_de'=>'"%1s" ist bereits ein Kindelement eines Elementes und kann daher kein Elternelement sein.',
			'text_es'=>'"%1s" ya es un hijo de un elemento y por lo tanto no puede ser un padre.',
			'text_fr'=>'"%1s" est déjà un enfant d\'un élément et ne peut donc pas être un parent.',
			'text_ru'=>'"%1s" уже ребенок элемента и, следовательно, не может быть родителем.',
			'text_bg'=>'"%1s" вече е дете на един елемент и поради това не може да бъде родител.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'An error has occurred.',
			'text_en'=>'An error has occurred.',
			'text_de'=>'Ein Fehler ist aufgetreten.',
			'text_es'=>'Se ha producido un error.',
			'text_fr'=>'Une erreur est survenue.',
			'text_ru'=>'Произошла ошибка.',
			'text_bg'=>'Възникнала е грешка.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Captcha value is wrong',
			'text_en'=>'Captcha value is wrong.',
			'text_de'=>'Der Sicherheitscode ist falsch.',
			'text_es'=>'Valor Captcha es erróneo.',
			'text_fr'=>'Captcha valeur est erroné.',
			'text_ru'=>'Значение Защитный код неправильно.',
			'text_bg'=>'Captcha стойност не е наред.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Empty captcha value',
			'text_en'=>'Empty captcha value.',
			'text_de'=>'Der Sicherheitscode fehlt.',
			'text_es'=>'Valor de código de imagen vacía.',
			'text_fr'=>'Valeur de captcha vide.',
			'text_ru'=>'Пусто CAPTCHA на значение.',
			'text_bg'=>'Празен стойност Captcha.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' exceeds the allowed length',
			'text_en'=>'\'%value%\' exceeds the allowed length.',
			'text_de'=>'\'%value%\' überschreitet die zulässige Länge.',
			'text_es'=>'\'%value%\' supera la longitud permitida.',
			'text_fr'=>'\'%value%\' dépasse la longueur autorisée.',
			'text_ru'=>'\'%value%\' превышает допустимую длину.',
			'text_bg'=>'\'%value%\' превишава допустимата дължина.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%localPart%\' is no valid local part for email address \'%value%\'',
			'text_en'=>'\'%localPart%\' is no valid local part for email address \'%value%\'.',
			'text_de'=>'\'%localPart%\' ist kein gültiger lokaler Teil für die E-Mail-Adresse \'%value%\'.',
			'text_es'=>'\'%localPart%\' no es una parte local válida de la dirección de correo electrónico \'%value%\'.',
			'text_fr'=>'\'%localPart%\' ne fait pas partie locale valide de l\'adresse e-mail \'%value%\'.',
			'text_ru'=>'\'%localPart%\' не действует локальная часть адреса электронной почты для \'%value%\'.',
			'text_bg'=>'\'%localPart%\' не е валидна местната част на имейл адрес \'%value%\'.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%localPart%\' can not be matched against quoted-string format',
			'text_en'=>'\'%localPart%\' can not be matched against quoted-string format.',
			'text_de'=>'\'%localPart%\' cann nicht mit dem Quoted-String-Format abgestimmt werden.',
			'text_es'=>'\'%localPart%\' no puede ser igualada contra el formato de cadena entre comillas.',
			'text_fr'=>'\'%localPart%\' ne peut être égalé contre le format chaîne entre guillemets.',
			'text_ru'=>'\'%localPart%\' не могут быть сопоставлены с формата цитирует струн.',
			'text_bg'=>'\'%localPart%\' не може да се сравнява с цитиран низ формат.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%localPart%\' can not be matched against dot-atom format',
			'text_en'=>'\'%localPart%\' can not be matched against dot-atom format.',
			'text_de'=>'\'%localPart%\' kann nicht mit dem Dot-Atom-Format abgestimmt werden.',
			'text_es'=>'\'%localPart%\' no puede ser igualada contra el formato de punto-átomo.',
			'text_fr'=>'\'%localPart%\' ne peut être égalé contre le format dot-atom.',
			'text_ru'=>'\'%localPart%\' не може да се сравнява с дот-атом формат.',
			'text_bg'=>'\'%localPart%\' не могут быть сопоставлены с дот-атома.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%hostname%\' is not in a routable network segment. The email address \'%value%\' should not be resolved from public network',
			'text_en'=>'\'%hostname%\' is not in a routable network segment. The email address \'%value%\' should not be resolved from public network.',
			'text_de'=>'\'%hostname%\' ist nicht in einer routbaren Netzwerksegment. Die E-Mail-Adresse \'%value%\' sollte nicht aus dem öffentlichen Netz gelöst werden.',
			'text_es'=>'\'%hostname%\' no se encuentra en un segmento de red enrutable. El e-mail \'%value%\' no debe resolverse desde la red pública.',
			'text_fr'=>'\'%hostname%\' est pas dans un segment de réseau routable. Le adresse mail \'%value%\' ne doit pas être résolu à partir du réseau public.',
			'text_ru'=>'\'%hostname%\' не в маршрутизируемым сегменте сети. Адрес электронной почты \'%value%\' не должен быть решен с общественной сети.',
			'text_bg'=>'\'%hostname%\' не е в навигационна сегмент на мрежата. имейл адрес \'%value%\', не трябва да бъде решен от публична мрежа.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%hostname%\' does not appear to have a valid MX record for the email address \'%value%\'',
			'text_en'=>'\'%hostname%\' does not appear to have a valid MX record for the email address \'%value%\'.',
			'text_de'=>'\'%hostname%\' scheint keinen gültigen MX-Eintrag für die E-Mail-Adresse \'%value%\' zu haben.',
			'text_es'=>'\'%hostname%\' parece que no hay registro MX válido para la dirección de correo electrónico \'%value%\' a tener.',
			'text_fr'=>'\'%hostname%\' semble y avoir aucun enregistrement MX valide pour l\'adresse e-mail \'%value%\' à avoir.',
			'text_ru'=>'\'%hostname%\' кажется, не действует запись MX для адреса электронной почты \'%value%\' не имеют.',
			'text_bg'=>'\'%hostname%\' Изглежда, че няма валиден запис MX за имейл адреса \'%value%\' да има.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%hostname%\' is no valid hostname for email address \'%value%\'',
			'text_en'=>'\'%hostname%\' is no valid hostname for email address \'%value%\'.',
			'text_de'=>'\'%hostname%\' ist kein gültiger Hostname für die E-Mail-Adresse \'%value%\'.',
			'text_es'=>'\'%hostname%\' no es un nombre de host válida dirección de correo electrónico \'%value%\'.',
			'text_fr'=>'\'%hostname%\' est pas un nom d\'hôte valide pour l\'adresse e-mail \'%value%\'.',
			'text_ru'=>'\'%hostname%\' не действует хоста для адреса электронной почты \'%value%\'.',
			'text_bg'=>'\'%hostname%\' не е валиден за име на хост имейл адрес \'%value%\'.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' appears to be a local network name but local network names are not allowed',
			'text_en'=>'\'%value%\' appears to be a local network name but local network names are not allowed.',
			'text_de'=>'\'%value%\' scheint eine lokaler Netzwerkname zu sein. Lokale Netzwerknamen sind jedoch nicht erlaubt.',
			'text_es'=>'\'%value%\' parece ser un nombre de red local, pero los nombres de red locales no están permitidos.',
			'text_fr'=>'\'%value%\' semble être un nom de réseau local, mais les noms de réseaux locaux ne sont pas autorisés.',
			'text_ru'=>'\'%value%\' кажется, местное название сети, но имена локальной сети не допускается.',
			'text_bg'=>'\'%value%\' изглежда е име на локална мрежа, но местни имена на мрежата не са позволени.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'A record matching \'%value%\' was found',
			'text_en'=>'A record matching \'%value%\' was found.',
			'text_de'=>'Es wurde ein Datensatz gefunden, welcher bereits \'%value%\' enthält.',
			'text_es'=>'Se encontró un registro coincidente \'%value%\'.',
			'text_fr'=>'Un enregistrement correspondant à \'%value%\' a été trouvé.',
			'text_ru'=>'Был найден запись, соответствующая \'%value%\'.',
			'text_bg'=>'Е намерено Рекорден съвпадение \'%value%\'',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Invalid type given. String, integer or float expected',
			'text_en'=>'Invalid type given. String, integer or float expected.',
			'text_de'=>'Ungültiger Typ angegeben. String, Integer oder Float erwartet.',
			'text_es'=>'Tipo no válido dado. Cadena, entero o flotante espera.',
			'text_fr'=>'Type valide donné. Chaîne, integer ou float attendus.',
			'text_ru'=>'Неверный тип дано. Строка, число или с плавающей точкой ожидалось.',
			'text_bg'=>'Невалиден тип дал. Струнен, число или плувка очаква.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Das System hat Ihre Position für einen sicheren Login bestimmt.',
			'text_en'=>'The system has determined your position for a secure login.',
			'text_de'=>'Das System hat Ihre Position für einen sicheren Login bestimmt.',
			'text_es'=>'El sistema ha determinado su posición para un inicio de sesión seguro.',
			'text_fr'=>'Le système a déterminé votre position pour une connexion sécurisée.',
			'text_ru'=>'Система определит ваше положение для безопасного входа.',
			'text_bg'=>'Системата е определила позицията си за сигурно влизане.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Das System versucht Ihre Position für einen sicheren Login zu bestimmen.',
			'text_en'=>'The system attempts to determine your location for a secure login.',
			'text_de'=>'Das System versucht Ihre Position für einen sicheren Login zu bestimmen.',
			'text_es'=>'El sistema intenta determinar su ubicación para un inicio de sesión seguro.',
			'text_fr'=>'Le système tente de déterminer votre position pour une connexion sécurisée.',
			'text_ru'=>'Система пытается определить ваше местоположение для безопасного входа.',
			'text_bg'=>'Системата се опитва да определи местоположението ви за сигурно влизане.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The system could not determine your position.',
			'text_en'=>'The system could not determine your position.',
			'text_de'=>'Das System konnte Ihre Position nicht bestimmen.',
			'text_es'=>'El sistema no puede determinar su posición.',
			'text_fr'=>'Le système n\'a pas pu déterminer votre position.',
			'text_ru'=>'Система не может определить свою позицию.',
			'text_bg'=>'Системата не може да определи позицията си.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The system could not determine your position, because of a timeout.',
			'text_en'=>'The system could not determine your position, because of a timeout.',
			'text_de'=>'Das System konnte Ihre Position nicht bestimmen, da es zu einem Timeout kam.',
			'text_es'=>'El sistema no puede determinar su posición, debido a un tiempo de espera.',
			'text_fr'=>'Le système n\'a pas pu déterminer votre position, en raison d\'un délai d\'attente.',
			'text_ru'=>'Система не может определить свою позицию, из-за тайм-аута.',
			'text_bg'=>'Системата не може да определи позицията си, тъй като на изчакване.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The system could not determine your position, because permission was denied.',
			'text_en'=>'The system could not determine your position, because permission was denied.',
			'text_de'=>'Das System konnte Ihre Position nicht bestimmen, da der Zugriff verweigert wurde.',
			'text_es'=>'El sistema no puede determinar su posición, ya que el permiso fue negado.',
			'text_fr'=>'Le système n\'a pas pu déterminer votre position, car l\'autorisation a été refusée.',
			'text_ru'=>'Система не может определить свою позицию, потому что разрешение было отказано.',
			'text_bg'=>'Системата не може да определи позицията си, тъй като разрешение е бил отказан.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The system could not determine your position, because the position of the device could not be determined.',
			'text_en'=>'The system could not determine your position, because the position of the device could not be determined.',
			'text_de'=>'Das System konnte Ihre Position nicht bestimmen, da die Position des Gerätes nicht ermittelt werden konnte.',
			'text_es'=>'El sistema no puede determinar su posición, porque la posición del dispositivo no se pudo determinar.',
			'text_fr'=>'Le système n\'a pas pu déterminer votre position, parce que la position de l\'appareil n\'a pas pu être déterminée.',
			'text_ru'=>'Система не может определить свою позицию, потому что позиция устройства не может быть определен.',
			'text_bg'=>'Системата не може да определи позицията си, тъй като положението на устройството не може да бъде определена.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The account associated with the specified login has been disabled.',
			'text_en'=>'The account associated with the specified login has been disabled.',
			'text_de'=>'Das Benutzerkonto mit dem angegebenen Namen wurde deaktiviert.',
			'text_es'=>'La cuenta asociada con el inicio de sesión especificado se ha desactivado.',
			'text_fr'=>'Le compte associé à la connexion spécifiée a été désactivée.',
			'text_ru'=>'Счет, связанный с указанным входа была отключена.',
			'text_bg'=>'Профилът, свързан с определен Logon е деактивиран.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Your account has been disabled for security reasons. You\'ll receive an email with instructions on how to re-activate your account.',
			'text_en'=>'Your account has been disabled for security reasons. You\'ll receive an email with instructions on how to re-activate your account.',
			'text_de'=>'Ihr Benutzerkonto wurde aus Sicherheitsgründen deaktiviert. Sie erhalten eine E-Mail mit Anweisungen, wie Sie dieses wieder aktivieren können.',
			'text_es'=>'Su cuenta ha sido desactivada por razones de seguridad. Usted recibirá un correo electrónico con instrucciones sobre cómo volver a activar su cuenta.',
			'text_fr'=>'Votre compte a été désactivé pour des raisons de sécurité. Vous recevrez un email avec des instructions sur la façon de réactiver votre compte.',
			'text_ru'=>'Ваш аккаунт был отключен по соображениям безопасности. Вы получите письмо с инструкциями о том, как повторно активировать свой аккаунт.',
			'text_bg'=>'Вашият профил е деактивиран от съображения за сигурност. Вие ще получите имейл с инструкции как да активирате отново профила си.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Model Name "%1s" already used on "%2s".',
			'text_en'=>'Model Name "%1s" already used on "%2s".',
			'text_de'=>'Der Model Name "%1s" wurde bereits genutzt bei "%2s".',
			'text_es'=>'Nombre del modelo "%1s" ya se utiliza en "%2s".',
			'text_fr'=>'Nom du modèle "%1s" déjà utilisé sur "%2s".',
			'text_ru'=>'Название модели "%1s" уже используется на "%2s".',
			'text_bg'=>'Модел "%1s" вече се използва по "%2s".',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The only allowed chars are: "%1s".',
			'text_en'=>'The only allowed chars are: "%1s".',
			'text_de'=>'Die einzigen erlaubten Zeichen sind: "%1s".',
			'text_es'=>'Los caracteres permitidos son: "%1s".',
			'text_fr'=>'Les caractères autorisés sont: "%1s".',
			'text_ru'=>'Допустимые символы: "%1s".',
			'text_bg'=>'Допустимите героите са: "%1s".',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'An exception has been thrown',
			'text_en'=>'An exception has been thrown',
			'text_de'=>'Es wurde ein Fehler generiert',
			'text_es'=>'Una excepción ha sido lanzado',
			'text_fr'=>'Une exception a été levée',
			'text_ru'=>'Исключение было брошено',
			'text_bg'=>'Изключение е хвърлен',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The Model Name already exists: "%1s".',
			'text_en'=>'The Model Name already exists: "%1s".',
			'text_de'=>'Der Model Name existiert bereits: "%1s".',
			'text_es'=>'El nombre del modelo ya existe: "%1s".',
			'text_fr'=>'Le nom du modèle existe déjà.: "%1s".',
			'text_ru'=>'Название модели уже существует: "%1s".',
			'text_bg'=>'Вече съществува Името на модела: "%1s".',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Upload of data doesn\'t work! File-size must be greater then zero.',
			'text_en'=>'Upload of data doesn\'t work! File-size must be greater then zero.',
			'text_de'=>'Das Hochladen der Daten funktioniert nicht! Die Dateigröße muss größer als Null sein.',
			'text_es'=>'Subir de datos no funciona! Archivo de tamaño debe ser mayor que cero.',
			'text_fr'=>'Ajouter des données ne fonctionne pas! Taille du fichier doit être supérieure à zéro.',
			'text_ru'=>'Загрузить данные не работает! Размер файла должен быть больше нуля.',
			'text_bg'=>'Качване на данни не работи! File размер трябва да е по-голяма от нула.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Upload of data doesn\'t work! Could not match stream-size with file-size.',
			'text_en'=>'Upload of data doesn\'t work! Could not match stream-size with file-size.',
			'text_de'=>'Das Hochladen der Daten funktioniert nicht! Die Streamgröße stimmt nicht mit der Dateigröße überein.',
			'text_es'=>'Subir de datos no funciona! No se ha podido igualar la corriente grande con archivo de tamaño.',
			'text_fr'=>'Ajouter des données ne fonctionne pas! Pourrait ne pas correspondre à flux-size avec la taille de fichier.',
			'text_ru'=>'Загрузить данные не работает! Не удалось соответствовать поток-размер с размера файла.',
			'text_bg'=>'Качване на данни не работи! Не може да съвпада потока размер с файл-размер.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'All files in sum should have a maximum size of \'%max%\' but \'%size%\' were detected.',
			'text_en'=>'All files in sum should have a maximum size of \'%max%\' but \'%size%\' were detected.',
			'text_de'=>'Alle Dateien in der Summe sollte eine maximale Größe von \'%max%\' haben, es wurde jedoch eine Größe von \'%size%\' erkannt.',
			'text_es'=>'Todos los archivos de suma deben tener un tamaño máximo de \'%max%\', pero \'%size%\' fueron detectados.',
			'text_fr'=>'Tous les fichiers somme devraient avoir une taille maximale de \'%max%\', mais \'%size%\' ont été détectés.',
			'text_ru'=>'Все файлы в сумме должны иметь максимальный размер \'%max%\', но \'%size%\' были обнаружены.',
			'text_bg'=>'Всички файлове в сума следва да разполагат с максимален размер от \'%max%\', но са били открити \'%size%\'.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Upload of data doesn\'t work!',
			'text_en'=>'Upload of data doesn\'t work!',
			'text_de'=>'Das Hochladen der Daten funktioniert nicht!',
			'text_es'=>'Subir de datos no funciona!',
			'text_fr'=>'Ajouter des données ne fonctionne pas!',
			'text_ru'=>'Загрузить данные не работает!',
			'text_bg'=>'Качване на данни не работи!',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Could not find role!',
			'text_en'=>'Could not find role!',
			'text_de'=>'Benutzergruppe konnte nicht gefunden werden!',
			'text_es'=>'Grupo de usuarios no se ha encontrado!',
			'text_fr'=>'Groupe d\'utilisateurs n\'a pas pu être trouvé!',
			'text_ru'=>'Группа пользователей не может быть найден!',
			'text_bg'=>'Потребителят група, не може да бъде намерен!',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Submit of form doesn\'t work!',
			'text_en'=>'Submit of form doesn\'t work!',
			'text_de'=>'Das Absenden des Formulars war fehlerhaft!',
			'text_es'=>'La presentación del formulario era defectuoso!',
			'text_fr'=>'La soumission du formulaire était défectueux!',
			'text_ru'=>'Представление о форме был неисправен!',
			'text_bg'=>'Подаването на формата е повреден!',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Media is not type of %1s and was rejected.',
			'text_en'=>'Media is not type of %1s and was rejected.',
			'text_de'=>'Das Medium ist nicht vom Typ %1s und wurde abgelehnt.',
			'text_es'=>'El medio no es el tipo %1s y fue rechazado.',
			'text_fr'=>'Le milieu est pas le type %1s et a été rejetée.',
			'text_ru'=>'Среда не тип %1s и был отклонен.',
			'text_bg'=>'Средата не е от типа %1s и беше отхвърлено.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Media can not be replaced. "%1s" is not writable.',
			'text_en'=>'Media can not be replaced. "%1s" is not writable.',
			'text_de'=>'Das Medium kann nicht ersetzt werden. "%1s" ist nicht schreibbar.',
			'text_es'=>'El medio no puede ser sustituido. "%1s" no es modificable.',
			'text_fr'=>'Le milieu ne peut pas être remplacé. "%1s" est pas accessible en écriture.',
			'text_ru'=>'Среда не может быть заменен. "%1s" не для записи.',
			'text_bg'=>'Средата не може да бъде заменен. "%1s" не е достъпна за писане.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Upload failed',
			'text_en'=>'Upload failed',
			'text_de'=>'Hochladen fehlgeschlagen',
			'text_es'=>'No subir',
			'text_fr'=>'Échec de l\'envoi',
			'text_ru'=>'Загрузка не удалась',
			'text_bg'=>'Качването е неуспешно',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' ist keine valide Eingabe. Bitte geben Sie nur Zahlen ein',
			'text_en'=>'\'%value%\' is no valid input. Please type in a number.',
			'text_de'=>'\'%value%\' ist keine valide Eingabe. Bitte geben Sie nur Zahlen ein.',
			'text_es'=>'\'%value%\' hay una entrada válida. Por favor, escriba un número.',
			'text_fr'=>'\'%value%\' est aucune entrée valide. S\'il vous plaît taper un numéro.',
			'text_ru'=>'\'%value%\' не действует ввода. Пожалуйста, введите число.',
			'text_bg'=>'\'%value%\' не е валидна вход. Моля въведете в редица.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' does not appear to be a float',
			'text_en'=>'\'%value%\' does not appear to be a float.',
			'text_de'=>'\'%value%\' scheint keine Fließkommazahl zu sein.',
			'text_es'=>'\'%value%\' parece que no hay número de coma flotante.',
			'text_fr'=>'\'%value%\' semble y avoir aucun nombre à virgule flottante.',
			'text_ru'=>'\'%value%\' кажется, быть не число с плавающей точкой.',
			'text_bg'=>'\'%value%\' изглежда, че няма число с плаваща запетая.',
		);


		/**
		 * admin
		 */
		$w[] = array(
			'id'=>$i++,
			'short'=>'Administration',
			'text_en'=>'Administration',
			'text_de'=>'Verwaltung',
			'text_es'=>'Administración',
			'text_fr'=>'Administration',
			'text_ru'=>'Администрация',
			'text_bg'=>'Администрация',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Choose, what to administrate.',
			'text_en'=>'Choose, what to administrate.',
			'text_de'=>'Wählen Sie den zu verwaltenden Bereich.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Configurate your Application',
			'text_en'=>'Configurate your Application',
			'text_de'=>'Konfigurieren Sie die Anwendung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Content-Pages',
			'text_en'=>'Content-Pages',
			'text_de'=>'Inhalts-Seiten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Sitemap',
			'text_en'=>'Sitemap',
			'text_de'=>'Sitemap',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Create Sitemap',
			'text_en'=>'Create Sitemap',
			'text_de'=>'Sitemap Erstellen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Create Model Name',
			'text_en'=>'Create Model Name',
			'text_de'=>'Erstelle Model Name',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Translations',
			'text_en'=>'Translations',
			'text_de'=>'Übersetzungen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Configurate your Dynamic Content',
			'text_en'=>'Configurate your Dynamic Content',
			'text_de'=>'Konfigurieren Sie die Dynamischen-Inhalte',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Content',
			'text_en'=>'Content',
			'text_de'=>'Inhalt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Content plain',
			'text_en'=>'Content (Plain)',
			'text_de'=>'Inhalt (Plain)',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Content html',
			'text_en'=>'Content (HTML)',
			'text_de'=>'Inhalt (HTML)',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Translator',
			'text_en'=>'Translator',
			'text_de'=>'Übersetzer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'You are editing',
			'text_en'=>'You are editing',
			'text_de'=>'Sie editieren jetzt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Add',
			'text_en'=>'Add',
			'text_de'=>'Hinzufügen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Remove',
			'text_en'=>'Remove',
			'text_de'=>'Entfernen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'remove',
			'text_en'=>'remove',
			'text_de'=>'entfernen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Position',
			'text_en'=>'Position',
			'text_de'=>'Position',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Edit',
			'text_en'=>'Edit',
			'text_de'=>'Bearbeiten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Delete',
			'text_en'=>'Delete',
			'text_de'=>'Löschen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Save',
			'text_en'=>'Save',
			'text_de'=>'Speichern',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Back',
			'text_en'=>'Back',
			'text_de'=>'Zurück',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Blog',
			'text_en'=>'Blog',
			'text_de'=>'Blog',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Users',
			'text_en'=>'Users',
			'text_de'=>'Nutzer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'System',
			'text_en'=>'System',
			'text_de'=>'System',
			'text_es'=>'Sistema',
			'text_fr'=>'Système',
			'text_ru'=>'Система',
			'text_bg'=>'Система',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Admin',
			'text_en'=>'Admin',
			'text_de'=>'Admin',
			'text_es'=>'Administración',
			'text_fr'=>'Administrateur',
			'text_ru'=>'Админ',
			'text_bg'=>'Админ',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Media',
			'text_en'=>'Manage Media',
			'text_de'=>'Medien Verwalten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Warning during the deletion process',
			'text_en'=>'Warning during the deletion process',
			'text_de'=>'Warnung während des Löschvorgangs',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The record is in use. Make sure, you do not use it on another record. It seems to be required there and cannot be deleted recursiv.',
			'text_en'=>'The record is in use. Make sure, you do not use it on another record. It seems to be required there and cannot be deleted recursiv.',
			'text_de'=>'Der Datensatz wird benutzt. Stellen Sie sicher, dass Sie ihn nicht mit einem anderen Datensatz benutzen. Er scheint dort erforderlich zu sein und kann nicht rekursiv gelöscht werden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'It seems you have no access and cannot delete that record.',
			'text_en'=>'It seems you have no access and cannot delete that record.',
			'text_de'=>'Es scheint, dass Sie keinen Zugriff auf diesen Datensatz haben und diesen nicht löschen können.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'id',
			'text_en'=>'ID',
			'text_de'=>'ID',
			'text_es'=>'ID',
			'text_fr'=>'ID',
			'text_ru'=>'ID',
			'text_bg'=>'ID',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Id',
			'text_en'=>'ID',
			'text_de'=>'ID',
			'text_es'=>'ID',
			'text_fr'=>'ID',
			'text_ru'=>'ID',
			'text_bg'=>'ID',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ID',
			'text_en'=>'ID',
			'text_de'=>'ID',
			'text_es'=>'ID',
			'text_fr'=>'ID',
			'text_ru'=>'ID',
			'text_bg'=>'ID',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Short',
			'text_en'=>'Short',
			'text_de'=>'Kürzel',
			'text_es'=>'Corto',
			'text_fr'=>'Bref',
			'text_ru'=>'кратко',
			'text_bg'=>'кратко',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Name',
			'text_en'=>'Name',
			'text_de'=>'Name',
			'text_fr'=>'Nom',
			'text_ru'=>'Имя',
			'text_bg'=>'Име',
			'text_es'=>'Nombre',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Uname',
			'text_en'=>'Name',
			'text_de'=>'Name',
			'text_fr'=>'Nom',
			'text_ru'=>'Имя',
			'text_bg'=>'Име',
			'text_es'=>'Nombre',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Parent Record',
			'text_en'=>'Parent Record',
			'text_de'=>'Eltern-Element',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bild',
			'text_en'=>'Image',
			'text_de'=>'Bild',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Image',
			'text_en'=>'Image',
			'text_de'=>'Bild',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Images',
			'text_en'=>'Images',
			'text_de'=>'Bilder',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'MediaImage',
			'text_en'=>'Image',
			'text_de'=>'Bild',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Value',
			'text_en'=>'Value',
			'text_de'=>'Wert',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Code',
			'text_en'=>'Code',
			'text_de'=>'Code',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Original value',
			'text_en'=>'Original-Value',
			'text_de'=>'Original-Wert',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Is used',
			'text_en'=>'Is Used',
			'text_de'=>'Wurde Benutzt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Extra Price',
			'text_en'=>'Extra Price',
			'text_de'=>'Zusatz-Preis',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'EntityUser',
			'text_en'=>'User-Account',
			'text_de'=>'Benutzerkonto',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Entity User',
			'text_en'=>'User-Account',
			'text_de'=>'Benutzerkonto',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Headline',
			'text_en'=>'Headline',
			'text_de'=>'Überschrift',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Activation Code',
			'text_en'=>'Activation Code',
			'text_de'=>'Aktivierungs-Code',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Password',
			'text_en'=>'Password',
			'text_de'=>'Passwort',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Anrede',
			'text_en'=>'Salutation',
			'text_de'=>'Anrede',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Salutation',
			'text_en'=>'Salutation',
			'text_de'=>'Anrede',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Disabled',
			'text_en'=>'Disabled',
			'text_de'=>'Deaktiviert',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Enabled',
			'text_en'=>'Enabled',
			'text_de'=>'Aktiviert',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Lastname',
			'text_en'=>'Lastname',
			'text_de'=>'Nachname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Firstname',
			'text_en'=>'Firstname',
			'text_de'=>'Vorname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Customer firstname',
			'text_en'=>'Customer - Firstname',
			'text_de'=>'Kunden - Vorname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Customer lastname',
			'text_en'=>'Customer - Lastname',
			'text_de'=>'Kunden - Nachname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Customer phone',
			'text_en'=>'Customer - Phone',
			'text_de'=>'Kunden - Telefon',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Customer email',
			'text_en'=>'Customer - Email',
			'text_de'=>'Kunden - E-Mail',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Customer street',
			'text_en'=>'Customer - Street',
			'text_de'=>'Kunden - Straße',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Customer street number',
			'text_en'=>'Customer - Street Number',
			'text_de'=>'Kunden - Hausnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Customer zip',
			'text_en'=>'Customer - Zip',
			'text_de'=>'Kunden - PLZ',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Customer city',
			'text_en'=>'Customer - City',
			'text_de'=>'Kunden - Stadt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Customer country',
			'text_en'=>'Customer - Country',
			'text_de'=>'Kunden - Land',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Phone',
			'text_en'=>'Phone',
			'text_de'=>'Telefon',
			'text_fr'=>'Téléphone',
			'text_ru'=>'Телефон',
			'text_bg'=>'Телефон',
			'text_es'=>'Teléfono',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Mobile',
			'text_en'=>'Mobile',
			'text_de'=>'Mobil-Telefon',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Street',
			'text_en'=>'Street',
			'text_de'=>'Straße',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Street number',
			'text_en'=>'Street Number',
			'text_de'=>'Hausnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zip',
			'text_en'=>'Zip',
			'text_de'=>'PLZ',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'City',
			'text_en'=>'City',
			'text_de'=>'Stadt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Country',
			'text_en'=>'Country',
			'text_de'=>'Land',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Address line 1',
			'text_en'=>'Address Line 1',
			'text_de'=>'Adresszeile 1',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Address line 2',
			'text_en'=>'Address Line 2',
			'text_de'=>'Adresszeile 2',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Company name',
			'text_en'=>'Company Name',
			'text_de'=>'Firmenname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Commercial register id',
			'text_en'=>'Commercial Register ID',
			'text_de'=>'Handelsregisternummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Vat tax id',
			'text_en'=>'Vat Tax ID',
			'text_de'=>'Umsatzsteuer-ID',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Managing director',
			'text_en'=>'Managing Director',
			'text_de'=>'Geschäftsführer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Branch',
			'text_en'=>'Business',
			'text_de'=>'Branche',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Contact name',
			'text_en'=>'Contact Name',
			'text_de'=>'Kontakt Name',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Contact position',
			'text_en'=>'Contact Position',
			'text_de'=>'Kontakt Position',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing company',
			'text_en'=>'Billing - Company',
			'text_de'=>'Rechnungs - Firma',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing Country',
			'text_en'=>'Billing - Country',
			'text_de'=>'Rechnungs - Land',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing firstname',
			'text_en'=>'Billing - Firstname',
			'text_de'=>'Rechnungs - Vorname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing lastname',
			'text_en'=>'Billing - Lastname',
			'text_de'=>'Rechnungs - Nachname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing address line 1',
			'text_en'=>'Billing - Address Line 1',
			'text_de'=>'Rechnungs - Adresszeile 1',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing address line 2',
			'text_en'=>'Billing - Address Line 2',
			'text_de'=>'Rechnungs - Adresszeile 2',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing street',
			'text_en'=>'Billing - Street',
			'text_de'=>'Rechnungs - Straße',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing street number',
			'text_en'=>'Billing - Street Number',
			'text_de'=>'Rechnungs - Hausnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing zip',
			'text_en'=>'Billing - Zip',
			'text_de'=>'Rechnungs - PLZ',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing city',
			'text_en'=>'Billing - City',
			'text_de'=>'Rechnungs - Stadt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing country',
			'text_en'=>'Billing - Country',
			'text_de'=>'Rechnungs - Land',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Use all paymentservices',
			'text_en'=>'All payment services allowed',
			'text_de'=>'Alle Zahlungsdienste erlaubt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'End of validity',
			'text_en'=>'End of Validity',
			'text_de'=>'Ende der Gültigkeit',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'E-Mail',
			'text_en'=>'Email',
			'text_de'=>'E-Mail',
			'text_fr'=>'Émail',
			'text_ru'=>'Эмаль',
			'text_bg'=>'Емайл',
			'text_es'=>'Correo Electrónico',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Email',
			'text_en'=>'Email',
			'text_de'=>'E-Mail',
			'text_fr'=>'Émail',
			'text_ru'=>'Эмаль',
			'text_bg'=>'Емайл',
			'text_es'=>'Correo Electrónico',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Subheadline',
			'text_en'=>'Sub Headline',
			'text_de'=>'Unter-Überschrift',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ISO 2 Code',
			'text_en'=>'ISO 2 Code',
			'text_de'=>'ISO 2 Code',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ISO 3 Code',
			'text_en'=>'ISO 3 Code',
			'text_de'=>'ISO 3 Code',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ISO Number',
			'text_en'=>'ISO Number',
			'text_de'=>'ISO Nummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Local Name',
			'text_en'=>'Local Name',
			'text_de'=>'Lokal-Name',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Submit',
			'text_en'=>'Submit',
			'text_de'=>'Absenden',
			'text_fr'=>'Envoyer',
			'text_ru'=>'Послать',
			'text_bg'=>'Изпращам',
			'text_es'=>'Enviar',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ok',
			'text_en'=>'Ok',
			'text_de'=>'Ok',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Short-Name',
			'text_en'=>'Short-Name',
			'text_de'=>'Kurz-Name',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Www',
			'text_en'=>'WWW',
			'text_de'=>'WWW',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Fax',
			'text_en'=>'Fax',
			'text_de'=>'Fax',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Resource',
			'text_en'=>'Resource',
			'text_de'=>'Ressource',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zone Code',
			'text_en'=>'Zone Code',
			'text_de'=>'Zonen-Code',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Price',
			'text_en'=>'Price',
			'text_de'=>'Preis',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Media',
			'text_en'=>'Media',
			'text_de'=>'Medium',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'MediaType',
			'text_en'=>'Media Type',
			'text_de'=>'Medien-Typ',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'MediaFolder',
			'text_en'=>'Media Folder',
			'text_de'=>'Medien-Verzeichnis',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Role',
			'text_en'=>'Role',
			'text_de'=>'Gruppe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'List',
			'text_en'=>'List',
			'text_de'=>'Auflistung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Height',
			'text_en'=>'Height',
			'text_de'=>'Höhe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Width',
			'text_en'=>'Width',
			'text_de'=>'Breite',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Crop',
			'text_en'=>'Crop',
			'text_de'=>'Zuschneiden',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Delete Media',
			'text_en'=>'Delete Media',
			'text_de'=>'Medium Entfernen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Select Media',
			'text_en'=>'Select Media',
			'text_de'=>'Medium Auswählen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'No media selected.',
			'text_en'=>'No media selected.',
			'text_de'=>'Kein Medium ausgewählt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Select a file you would like to upload.',
			'text_en'=>'Select a file you would like to upload.',
			'text_de'=>'Wählen Sie eine Datei aus, die Sie hochladen möchten.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Upload media',
			'text_en'=>'Upload Media',
			'text_de'=>'Medium Hochladen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Upload Media',
			'text_en'=>'Upload Media',
			'text_de'=>'Medium Hochladen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Upload Medias',
			'text_en'=>'Upload Medias',
			'text_de'=>'Medien Hochladen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Select',
			'text_en'=>'Select',
			'text_de'=>'Auswählen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Select Files',
			'text_en'=>'Select Files',
			'text_de'=>'Dateien Auswählen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'X-Coordinate',
			'text_en'=>'X-Coordinate',
			'text_de'=>'X-Koordinate',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Y-Coordinate',
			'text_en'=>'Y-Coordinate',
			'text_de'=>'Y-Koordinate',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Action',
			'text_en'=>'Action',
			'text_de'=>'Aktion',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Controller',
			'text_en'=>'Controller',
			'text_de'=>'Controller',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Titel',
			'text_en'=>'Title',
			'text_de'=>'Titel',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Keywords',
			'text_en'=>'Keywords',
			'text_de'=>'Schlüsselwörter',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Description',
			'text_en'=>'Description',
			'text_de'=>'Beschreibung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'File name',
			'text_en'=>'Filename',
			'text_de'=>'Dateiname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'File size',
			'text_en'=>'Filesize',
			'text_de'=>'Dateigröße',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'File Size',
			'text_en'=>'Filesize',
			'text_de'=>'Dateigröße',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Text',
			'text_en'=>'Text',
			'text_de'=>'Text',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Untranslated',
			'text_en'=>'Untranslated',
			'text_de'=>'Unübersetzt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Comment',
			'text_en'=>'Comment',
			'text_de'=>'Kommentar',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Mime Type',
			'text_en'=>'MIME-Type',
			'text_de'=>'MIME-Typ',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Product',
			'text_en'=>'Product',
			'text_de'=>'Produkt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Product Unit',
			'text_en'=>'Product Unit',
			'text_de'=>'Produkteinheit',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Taxes',
			'text_en'=>'Taxes',
			'text_de'=>'Steuern',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop Coupon',
			'text_en'=>'Shop Coupon',
			'text_de'=>'Shop Gutschein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop Products',
			'text_en'=>'Shop Products',
			'text_de'=>'Shop Produkte',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop Productgroups',
			'text_en'=>'Shop Productgroups',
			'text_de'=>'Shop Produktgruppen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop Taxes',
			'text_en'=>'Shop Taxes',
			'text_de'=>'Shop Steuern',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop ProductOptions',
			'text_en'=>'Shop Product-Options',
			'text_de'=>'Shop Produkt-Optionen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop ProductOptionItems',
			'text_en'=>'Shop Product-Option Items',
			'text_de'=>'Shop Produkt-Options-Entitäten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop ShippingCost',
			'text_en'=>'Shop Shipping Cost',
			'text_de'=>'Shop Versandkosten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop TemporaryOffer',
			'text_en'=>'Shop Temporary Offer',
			'text_de'=>'Shop Zeitbegrenztes Angebot',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop ProductUnit',
			'text_en'=>'Shop Product Unit',
			'text_de'=>'Shop Produkteinheit',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Coupon',
			'text_en'=>'Coupon',
			'text_de'=>'Gutschein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'TemporaryOffer',
			'text_en'=>'Temporary Offer',
			'text_de'=>'Zeitbegrenztes Angebot',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ProductUnit',
			'text_en'=>'Product Unit',
			'text_de'=>'Produkteinheit',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ShippingCost',
			'text_en'=>'Shipping Cost',
			'text_de'=>'Versandkosten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Products',
			'text_en'=>'Products',
			'text_de'=>'Produkte',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ProductGroup',
			'text_en'=>'Productgroup',
			'text_de'=>'Produktgruppe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Product Group',
			'text_en'=>'Productgroup',
			'text_de'=>'Produktgruppe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ProductOptions',
			'text_en'=>'Product-Options',
			'text_de'=>'Produkt-Optionen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ProductOptionItems',
			'text_en'=>'Product-Option Items',
			'text_de'=>'Produkt-Options-Entitäten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Productgroups',
			'text_en'=>'Productgroups',
			'text_de'=>'Produktgruppen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Refund',
			'text_en'=>'Refund',
			'text_de'=>'Pfand',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'zzgl. %1s %2s Pfand',
			'text_en'=>'excl. %1s %2s Refund',
			'text_de'=>'zzgl. %1s %2s Pfand',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Producer',
			'text_en'=>'Producer',
			'text_de'=>'Hersteller',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Configurate your Shop',
			'text_en'=>'Configurate your Shop',
			'text_de'=>'Konfigurieren Sie Ihren Shop',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Title',
			'text_en'=>'Title',
			'text_de'=>'Titel',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Subtitle',
			'text_en'=>'Subtitle',
			'text_de'=>'Untertitel',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Sub title',
			'text_en'=>'Subtitle',
			'text_de'=>'Untertitel',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Product number',
			'text_en'=>'Product Number',
			'text_de'=>'Produkt-Nummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Offer',
			'text_en'=>'Offer',
			'text_de'=>'Angebot',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shipping cost factor',
			'text_en'=>'Shipping Cost Factor',
			'text_de'=>'Versandkosten-Faktor',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Meta keywords',
			'text_en'=>'Meta Keywords',
			'text_de'=>'Meta Keywords',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Meta title',
			'text_en'=>'Meta Title',
			'text_de'=>'Meta Title',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Meta description',
			'text_en'=>'Meta Description',
			'text_de'=>'Meta Description',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Sold',
			'text_en'=>'Sold',
			'text_de'=>'Ausverkauft',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Sold out',
			'text_en'=>'Sold Out',
			'text_de'=>'Ausverkauft',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Hidden',
			'text_en'=>'Hidden',
			'text_de'=>'Versteckt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Tipp',
			'text_en'=>'Tip',
			'text_de'=>'Tipp',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Tip',
			'text_en'=>'Tip',
			'text_de'=>'Tipp',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Neu',
			'text_en'=>'New',
			'text_de'=>'Neu',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'New',
			'text_en'=>'New',
			'text_de'=>'Neu',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'new',
			'text_en'=>'new',
			'text_de'=>'neu',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Top product',
			'text_en'=>'Top Product',
			'text_de'=>'Top Produkt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Menu name',
			'text_en'=>'Menu Name',
			'text_de'=>'Menü-Name',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Menu short',
			'text_en'=>'Menu Short',
			'text_de'=>'Menü-Short',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Show in shop',
			'text_en'=>'Show In Shop',
			'text_de'=>'Im Shop Zeigen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'From date',
			'text_en'=>'From Date',
			'text_de'=>'Von Datum',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Till date',
			'text_en'=>'Till Date',
			'text_de'=>'Bis Datum',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Cost',
			'text_en'=>'Cost',
			'text_de'=>'Kosten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Factor',
			'text_en'=>'Factor',
			'text_de'=>'Faktor',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'From factor',
			'text_en'=>'From Factor',
			'text_de'=>'Vom Faktor',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Till factor',
			'text_en'=>'Till Factor',
			'text_de'=>'Bis zum Faktor',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Equal factor',
			'text_en'=>'Equal Factor',
			'text_de'=>'Gleich dem Faktor',
		);

		/**
		 * forms
		 */
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihr Name',
			'text_en'=>'Your name',
			'text_de'=>'Ihr Name',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre Emailadresse',
			'text_en'=>'Your emailaddress',
			'text_de'=>'Ihre Emailadresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihr Feedback',
			'text_en'=>'Your feedback',
			'text_de'=>'Ihr Feedback',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Senden',
			'text_en'=>'Send',
			'text_de'=>'Senden',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre Nachricht',
			'text_en'=>'Your message',
			'text_de'=>'Ihre Nachricht',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Abschicken',
			'text_en'=>'Send',
			'text_de'=>'Abschicken',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'In den Newsletter eintragen',
			'text_en'=>'Subscribe to newsletter',
			'text_de'=>'In den Newsletter eintragen',
		);

		/**
		 * shop
		 */
		$w[] = array(
			'id'=>$i++,
			'short'=>'in die Einkaufstasche',
			'text_en'=>'add to cart',
			'text_de'=>'in die Einkaufstasche',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Welche Bezahlmethode wünschen Sie?',
			'text_en'=>'Which payment method to you prefer?',
			'text_de'=>'Welche Bezahlmethode wünschen Sie?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Weiter zur Bezahlmethode',
			'text_en'=>'Continue to payment',
			'text_de'=>'Weiter zur Bezahlmethode',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Jetzt bezahlen',
			'text_en'=>'Pay now',
			'text_de'=>'Jetzt bezahlen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Jetzt bezahlen mit PayPal',
			'text_en'=>'Pay now with PayPal',
			'text_de'=>'Jetzt bezahlen mit PayPal',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bitte wählen Sie',
			'text_en'=>'Please select',
			'text_de'=>'Bitte wählen Sie',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnungsadresse',
			'text_en'=>'Bill address',
			'text_de'=>'Rechnungsadresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Vorname',
			'text_en'=>'First name',
			'text_de'=>'Vorname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Nachname',
			'text_en'=>'Last name',
			'text_de'=>'Nachname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Straße',
			'text_en'=>'Street',
			'text_de'=>'Straße',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Hausnummer',
			'text_en'=>'Street number',
			'text_de'=>'Hausnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Adresszusatz 1',
			'text_en'=>'Additional address 1',
			'text_de'=>'Adresszusatz 1',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Adresszusatz 2',
			'text_en'=>'Additional address 2',
			'text_de'=>'Adresszusatz 2',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'PLZ',
			'text_en'=>'Zip',
			'text_de'=>'PLZ',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Stadt',
			'text_en'=>'City',
			'text_de'=>'Stadt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Land',
			'text_en'=>'Country',
			'text_de'=>'Land',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Lieferadresse',
			'text_en'=>'Delivery address',
			'text_de'=>'Lieferadresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnungs- und Lieferadresse sind gleich',
			'text_en'=>'Bill and delivery address are the same',
			'text_de'=>'Rechnungs- und Lieferadresse sind gleich',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Wie erreichen wir Sie?',
			'text_en'=>'How can we contact you?',
			'text_de'=>'Wie erreichen wir Sie?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Handy',
			'text_en'=>'Handy',
			'text_de'=>'Handy',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Telefon',
			'text_en'=>'Phone',
			'text_de'=>'Telefon',
			'text_fr'=>'Téléphone',
			'text_ru'=>'Телефон',
			'text_bg'=>'Телефон',
			'text_es'=>'Teléfono',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bemerkung',
			'text_en'=>'Comment',
			'text_de'=>'Bemerkung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ich habe meine Angaben sorgfältig geprüft.',
			'text_en'=>'I have checked my data carefully.',
			'text_de'=>'Ich habe meine Angaben sorgfältig geprüft.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Jetzt bestellen',
			'text_en'=>'Order now',
			'text_de'=>'Jetzt bestellen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Summe',
			'text_en'=>'Sum',
			'text_de'=>'Summe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'enthaltene Mehrwertsteuer',
			'text_en'=>'VAT included',
			'text_de'=>'enthaltene Mehrwertsteuer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnungssumme',
			'text_en'=>'Total bill',
			'text_de'=>'Rechnungssumme',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre Einkaufstasche ist leer, bitte suchen Sie sich etwas schönes in unserem Shop aus.',
			'text_en'=>'Your shopping bag is empty, please choose something beautiful out of our shop.',
			'text_de'=>'Ihre Einkaufstasche ist leer, bitte suchen Sie sich etwas schönes in unserem Shop aus.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Art. Nr.',
			'text_en'=>'Art. no.',
			'text_de'=>'Art. Nr.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bezahlmethode',
			'text_en'=>'Payment method',
			'text_de'=>'Bezahlmethode',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Gutschrift für Bezahlmethode',
			'text_en'=>'Credit for payment methode',
			'text_de'=>'Gutschrift für Bezahlmethode',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zusätzliche Kosten',
			'text_en'=>'Additional costs',
			'text_de'=>'Zusätzliche Kosten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Gesamte Rechnungssumme',
			'text_en'=>'Total amount invoiced',
			'text_de'=>'Gesamte Rechnungssumme',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bitte bestätigen Sie, dass Sie Ihre Angaben geprüft haben.',
			'text_en'=>'Please confirm that you have reviewed your information.',
			'text_de'=>'Bitte bestätigen Sie, dass Sie Ihre Angaben geprüft haben.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bitte bestätigen Sie, dass Sie die AGB gelesen haben und diese akzeptieren.',
			'text_en'=>'Please confirm that you have read the Terms and Conditions and accept it.',
			'text_de'=>'Bitte bestätigen Sie, dass Sie die AGB gelesen haben und diese akzeptieren.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ich habe die \'%value%\' gelesen und akzeptiere diese.',
			'text_en'=>'I have read and agree to \'%value%\'.',
			'text_de'=>'Ich habe die \'%value%\' gelesen und akzeptiere diese.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zahlungsart',
			'text_en'=>'Payment method',
			'text_de'=>'Zahlungsart',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kontakt',
			'text_en'=>'Contact',
			'text_de'=>'Kontakt',
			'text_fr'=>'Contact',
			'text_ru'=>'Контакт',
			'text_bg'=>'Контакт',
			'text_es'=>'Contácto',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'AGB',
			'text_en'=>'Terms and Conditions',
			'text_de'=>'AGB',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Versand',
			'text_en'=>'Shipping',
			'text_de'=>'Versand',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Store',
			'text_en'=>'Store',
			'text_de'=>'Store',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Webshop',
			'text_en'=>'Webshop',
			'text_de'=>'Webshop',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop',
			'text_en'=>'Shop',
			'text_de'=>'Shop',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Logout',
			'text_en'=>'Logout',
			'text_de'=>'Logout',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Login',
			'text_en'=>'Login',
			'text_de'=>'Login',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Impressum',
			'text_en'=>'Imprint',
			'text_de'=>'Impressum',
			'text_fr'=>'Empreinte',
			'text_ru'=>'Oтпечаток',
			'text_bg'=>'Oтпечатък',
			'text_es'=>'Pie de Imprenta',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shopping bag',
			'text_en'=>'Shopping bag',
			'text_de'=>'Shopping bag',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ich habe bereits ein Kundenkonto',
			'text_en'=>'I already have an account',
			'text_de'=>'Ich habe bereits ein Benutzerkonto',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Möchten Sie ein Kundenkonto anlegen?',
			'text_en'=>'Would you like to create a new account?',
			'text_de'=>'Möchten Sie ein Benutzerkonto anlegen?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ja',
			'text_en'=>'Yes',
			'text_de'=>'Ja',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Yes',
			'text_en'=>'Yes',
			'text_de'=>'Ja',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Nein',
			'text_en'=>'No',
			'text_de'=>'Nein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'No',
			'text_en'=>'No',
			'text_de'=>'Nein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Berlin',
			'text_en'=>'Berlin',
			'text_de'=>'Berlin',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Beautyservice',
			'text_en'=>'Beautyservice',
			'text_de'=>'Beautyservice',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Hairlounge',
			'text_en'=>'Hairlounge',
			'text_de'=>'Hairlounge',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kundenkonto',
			'text_en'=>'Account',
			'text_de'=>'Benutzerkonto',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Abbrechen',
			'text_en'=>'Abort',
			'text_de'=>'Abbrechen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Es ist ein Fehler aufgetreten',
			'text_en'=>'An error has occurred',
			'text_de'=>'Es ist ein Fehler aufgetreten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Die Bestellung wurde abgebrochen',
			'text_en'=>'The order has been canceled',
			'text_de'=>'Die Bestellung wurde abgebrochen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Vielen Dank für Ihren Einkauf',
			'text_en'=>'Thank you for your purchase',
			'text_de'=>'Vielen Dank für Ihren Einkauf',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Die Suche ergab folgendes Ergebnis für',
			'text_en'=>'The search yielded the following results for',
			'text_de'=>'Die Suche ergab folgendes Ergebnis für',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Die Suche ergab kein Ergebnis für',
			'text_en'=>'The search yielded no result for',
			'text_de'=>'Die Suche ergab kein Ergebnis für',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Activation',
			'text_en'=>'Activation',
			'text_de'=>'Aktivierung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Activation started',
			'text_en'=>'Activation started',
			'text_de'=>'Aktivierung gestartet',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Please hang on while the activation is ready.',
			'text_en'=>'Please hang on while the activation is ready.',
			'text_de'=>'Bitte warten Sie bis die Aktivierung abgeschlossen ist.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Expired',
			'text_en'=>'Expired',
			'text_de'=>'Abgelaufen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The activationcode you entered is expired already.',
			'text_en'=>'The activationcode you entered is expired already.',
			'text_de'=>'Der Aktivierungscode, den Sie eingegeben haben, ist abgelaufen.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Activationcode unknown.',
			'text_en'=>'Activationcode unknown.',
			'text_de'=>'Aktivierungscode unbekannt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The activationcode you entered is unknown.',
			'text_en'=>'The activationcode you entered is unknown.',
			'text_de'=>'Der Aktivierungscode, den Sie eingegeben haben, ist nicht bekannt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Activationcode used.',
			'text_en'=>'Activationcode used.',
			'text_de'=>'Aktivierungscode bereits genutzt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The activationcode you entered is used already.',
			'text_en'=>'The activationcode you entered is used already.',
			'text_de'=>'Der Aktivierungscode, den Sie eingegeben haben, ist bereits benutzt wurden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Vielen Dank für Ihre Anfrage.',
			'text_en'=>'Thank you for your inquiry.',
			'text_de'=>'Vielen Dank für Ihre Anfrage.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Vielen Dank für Ihr Feedback.',
			'text_en'=>'Thank you for your feedback.',
			'text_de'=>'Vielen Dank für Ihr Feedback.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'You are already logedin.',
			'text_en'=>'You are already logedin.',
			'text_de'=>'Sie sind bereits eingeloggt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'To access your personal space, you must register.',
			'text_en'=>'To access your personal space, you must register.',
			'text_de'=>'Um auf Ihren persönlichen Bereich zuzugreifen, müssen Sie sich registrieren.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Please enter now your login and password.',
			'text_en'=>'Please enter now your login and password.',
			'text_de'=>'Bitte geben Sie jetzt Ihren Login und das Passwort ein.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'To be able to log in, you must be registered. Do you want to %s now?',
			'text_en'=>'To be able to log in, you must be registered. Do you want to %s now?',
			'text_de'=>'Um sich einloggen zu können, müssen Sie registriert sein. Wollen Sie sich jetzt %s?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Forgotten your password? Do you want a %s?',
			'text_en'=>'Forgotten your password? Do you want a %s?',
			'text_de'=>'Haben Sie ihr Passwort vergessen? Möchten Sie sich ein %s zuschicken lassen?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Problem with activating your account? Do you want a %s?',
			'text_en'=>'Problem with activating your account? Do you want a %s?',
			'text_de'=>'Gab es Probleme mit der Aktivierung? Möchten Sie sich einen %s? zuschicken lassen?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'sign up',
			'text_en'=>'sign up',
			'text_de'=>'registrieren',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'new password',
			'text_en'=>'new password',
			'text_de'=>'neues Passwort',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'new activation code',
			'text_en'=>'new activation code',
			'text_de'=>'neuen Aktivierungscode',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Get activation code',
			'text_en'=>'Get activation code',
			'text_de'=>'Aktivierungscode zusenden',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'User',
			'text_en'=>'User',
			'text_de'=>'Nutzer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Entity',
			'text_en'=>'User',
			'text_de'=>'Nutzer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Account activated.',
			'text_en'=>'Account activated.',
			'text_de'=>'Benutzerkonto aktiviert.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'You have successfully activated your account and can %s now.',
			'text_en'=>'You have successfully activated your account and can %s now.',
			'text_de'=>'Sie haben Ihr Benutzerkonto erfolgreich aktiviert und können sich jetzt %s.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'log in',
			'text_en'=>'log in',
			'text_de'=>'einloggen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre Daten wurde geändert.',
			'text_en'=>'Your data has been changed.',
			'text_de'=>'Ihre Daten wurde geändert.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Passwort ändern',
			'text_en'=>'Change password',
			'text_de'=>'Passwort ändern',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bearbeiten',
			'text_en'=>'Edit',
			'text_de'=>'Bearbeiten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Konto Information',
			'text_en'=>'Account information',
			'text_de'=>'Konto Information',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kontaktdaten',
			'text_en'=>'Contact',
			'text_de'=>'Kontaktdaten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Hallo',
			'text_en'=>'Hello',
			'text_de'=>'Hallo',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Von Ihrer Konto-Übersicht aus haben Sie die Möglichkeit, Ihre letzten Vorgänge einzusehen und Ihre Benutzerkonto-Daten zu bearbeiten. Wählen Sie dazu einen der untensteheneden Links, um Information anzusehen oder zu bearbeiten.',
			'text_en'=>'From your account summary, you have the possibility to view your recent transactions and edit your account data. To do this, one of the links below are eden to view or edit information.',
			'text_de'=>'Von Ihrer Benutzerkonto-Übersicht aus haben Sie die Möglichkeit, Ihre letzten Vorgänge einzusehen und Ihre Benutzerkonto-Daten zu bearbeiten. Wählen Sie dazu einen der untensteheneden Links, um Information anzusehen oder zu bearbeiten.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Adressbuch',
			'text_en'=>'Addressbook',
			'text_de'=>'Adressbuch',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Primäre Rechnungsadresse',
			'text_en'=>'Primary billing address',
			'text_de'=>'Primäre Rechnungsadresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Es wurde keine primäre Rechnungsadresse angegeben',
			'text_en'=>'A primary billing address was not specified',
			'text_de'=>'Es wurde keine primäre Rechnungsadresse angegeben',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Primäre Versandadresse',
			'text_en'=>'Primary delivery address',
			'text_de'=>'Primäre Versandadresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Es wurden keine primäre Versandadresse angegeben',
			'text_en'=>'A primary delivery address was not specified',
			'text_de'=>'Es wurden keine primäre Versandadresse angegeben',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Adresse ändern',
			'text_en'=>'Change address',
			'text_de'=>'Adresse ändern',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre gewählte Bestellung beinhaltet',
			'text_en'=>'Your selected order includes',
			'text_de'=>'Ihre gewählte Bestellung beinhaltet',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Angegebene Rechnungsadresse',
			'text_en'=>'Billing address',
			'text_de'=>'Angegebene Rechnungsadresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Angegebene Lieferadresse',
			'text_en'=>'Delivery address',
			'text_de'=>'Angegebene Lieferadresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre Bestellungen auf einen Blick',
			'text_en'=>'Your orders at a glance',
			'text_de'=>'Ihre Bestellungen auf einen Blick',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Registration',
			'text_en'=>'Registration',
			'text_de'=>'Registrierung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Please fill out the form below now, to register with us.',
			'text_en'=>'Please fill out the form below now, to register with us.',
			'text_de'=>'Bitte füllen Sie das unten stehende Formular jetzt aus um sich bei uns zu registrieren.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Registration complete',
			'text_en'=>'Registration complete',
			'text_de'=>'Registrierung abgeschlossen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Hacking Attempt!',
			'text_en'=>'Hacking Attempt!',
			'text_de'=>'Hackerversuch!',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'You tried to hack an account.',
			'text_en'=>'You tried to hack an account.',
			'text_de'=>'Sie haben versucht ein Benutzerkonto zu hacken.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Error',
			'text_en'=>'Error',
			'text_de'=>'Fehler',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'An error has occured.',
			'text_en'=>'An error has occured.',
			'text_de'=>'Ein Fehler ist aufgetreten.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'HTTP/1.1 403 Nicht gestattet',
			'text_en'=>'HTTP/1.1 403 Not permitted',
			'text_de'=>'HTTP/1.1 403 Nicht gestattet',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Es ist Ihnen nicht gestattet, auf das angeforderte Dokument zuzugreifen.',
			'text_en'=>'You are not allowed to access this document.',
			'text_de'=>'Es ist Ihnen nicht gestattet, auf das angeforderte Dokument zuzugreifen.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'HTTP/1.1 404 Nicht gefunden',
			'text_en'=>'HTTP/1.1 404 Not found',
			'text_de'=>'HTTP/1.1 404 Nicht gefunden',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Das angeforderte Dokument konnte nicht gefunden werden.',
			'text_en'=>'The requested document could not be found.',
			'text_de'=>'Das angeforderte Dokument konnte nicht gefunden werden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Produkte',
			'text_en'=>'Products',
			'text_de'=>'Produkte',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihr Passwort',
			'text_en'=>'Your password',
			'text_de'=>'Ihr Passwort',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Einloggen',
			'text_en'=>'Login',
			'text_de'=>'Einloggen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zum Account',
			'text_en'=>'to account',
			'text_de'=>'Zum Benutzerkonto',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Konto Übersicht',
			'text_en'=>'Account overview',
			'text_de'=>'Benutzerkonto Übersicht',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Addressbuch',
			'text_en'=>'Addressbook',
			'text_de'=>'Addressbuch',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Meine Bestellungen',
			'text_en'=>'My orders',
			'text_de'=>'Meine Bestellungen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Gutschein Guthaben',
			'text_en'=>'Credit Voucher',
			'text_de'=>'Gutschein Guthaben',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bankdaten',
			'text_en'=>'Bank Information',
			'text_de'=>'Bankdaten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kontoinhaber',
			'text_en'=>'Account Holder',
			'text_de'=>'Kontoinhaber',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kontonummer',
			'text_en'=>'Account Number',
			'text_de'=>'Kontonummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bankleitzahl',
			'text_en'=>'Bankleitzahl',
			'text_de'=>'Bankleitzahl',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Name der Bank',
			'text_en'=>'Name of the Bank',
			'text_de'=>'Name der Bank',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Passwort',
			'text_en'=>'Password',
			'text_de'=>'Passwort',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Passwort wiederholen',
			'text_en'=>'Repeat Password',
			'text_de'=>'Passwort wiederholen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Speichern',
			'text_en'=>'Save',
			'text_de'=>'Speichern',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Sie haben bisher nichts bestellt',
			'text_en'=>'You have not ordered anything yet',
			'text_de'=>'Sie haben bisher nichts bestellt',
		);

		/**
		 * emails
		 */
		$w[] = array(
			'id'=>$i++,
			'short'=>'Einzelpreis',
			'text_en'=>'Single price',
			'text_de'=>'Einzelpreis',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Deine angegebene Handynummer',
			'text_en'=>'Your mobile phone number',
			'text_de'=>'Deine angegebene Handynummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Dein Kommentar',
			'text_en'=>'Your comment',
			'text_de'=>'Dein Kommentar',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Mehrwertsteuer',
			'text_en'=>'Value-Added Tax',
			'text_de'=>'Mehrwertsteuer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Die gesamte Rechnungssumme wird abgebucht von',
			'text_en'=>'The total invoice amount is debited from',
			'text_de'=>'Die gesamte Rechnungssumme wird abgebucht von',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'hier ist deine Bestellung auf einen Blick',
			'text_en'=>'here\'s your order at a glance',
			'text_de'=>'hier ist deine Bestellung auf einen Blick',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Als Rechnungsadresse hast du angegeben',
			'text_en'=>'As the billing address you provided',
			'text_de'=>'Als Rechnungsadresse hast du angegeben',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Die Lieferadresse ist folgende',
			'text_en'=>'The delivery address is the following',
			'text_de'=>'Die Lieferadresse ist folgende',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Deine angegebene Telefonnummer',
			'text_en'=>'Your telephone number',
			'text_de'=>'Deine angegebene Telefonnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Deine angegebene Emailadresse',
			'text_en'=>'Your specified e-mail address',
			'text_de'=>'Deine angegebene Emailadresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Deine Bestellung',
			'text_en'=>'Your order',
			'text_de'=>'Deine Bestellung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'herzlich willkommen in unserem Shop. Um sich bei Ihrem nächsten Besuch in unserem Shop einzuloggen, klicken sie einfach Login oder Mein Benutzerkonto im oberen Bereich jeder Seite und geben Sie ihr E-Mail-Adresse und Passwort ein.',
			'text_en'=>'welcome to our shop. To log on your next visit in our shop, just click Login or My Account at the top of every page and enter your e-mail address and password.',
			'text_de'=>'herzlich willkommen in unserem Shop. Um sich bei Ihrem nächsten Besuch in unserem Shop einzuloggen, klicken sie einfach Login oder Mein Benutzerkonto im oberen Bereich jeder Seite und geben Sie ihr E-Mail-Adresse und Passwort ein.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Mit folgenden Zugangsdaten können Sie sich anmelden:',
			'text_en'=>'With the following access data you can login:',
			'text_de'=>'Mit folgenden Zugangsdaten können Sie sich anmelden:',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Nach erfolgreichem Login auf unsere Seite haben Sie die folgenden Möglichkeiten:',
			'text_en'=>'After successful login in to our site you have the following options:',
			'text_de'=>'Nach erfolgreichem Login auf unsere Seite haben Sie die folgenden Möglichkeiten:',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'- Schnellerer Bezahlvorgang beim nächsten Einkauf',
			'text_en'=>'- Faster checkout process the next time you purchase',
			'text_de'=>'- Schnellerer Bezahlvorgang beim nächsten Einkauf',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'- Alle laufenden Bestellungen im Auge behalten',
			'text_en'=>'- Keep an eye on all current orders',
			'text_de'=>'- Alle laufenden Bestellungen im Auge behalten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'- Verlauf der vergangenen Bestellungen',
			'text_en'=>'- History of past orders',
			'text_de'=>'- Verlauf der vergangenen Bestellungen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'- Verwalten der Kundenkonto-Einstellungen',
			'text_en'=>'- Managing the Account Settings',
			'text_de'=>'- Verwalten der Benutzerkonto-Einstellungen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'- Ihr Passwort ändern',
			'text_en'=>'- Change your password',
			'text_de'=>'- Ihr Passwort ändern',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'- Verschiedene Adressen für den Versand an Familienmitglieder und Freunde sowie Rechnungsadressen speichern',
			'text_en'=>'- Store different addresses for sending to family members and friends as well as billing addresses',
			'text_de'=>'- Verschiedene Adressen für den Versand an Familienmitglieder und Freunde sowie Rechnungsadressen speichern',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zuvor müssen Sie lediglich die Registrierung bestätigen indem Sie auf folgenden Link klicken',
			'text_en'=>'Previously, you only need to confirm the registration by clicking on the following link',
			'text_de'=>'Zuvor müssen Sie lediglich die Registrierung bestätigen indem Sie auf folgenden Link klicken',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Vielen Dank!',
			'text_en'=>'Thank you!',
			'text_de'=>'Vielen Dank!',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'FrontLinks',
			'text_en'=>'FrontImage-Links',
			'text_de'=>'FrontImage-Links',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'FrontImages',
			'text_en'=>'FrontImages',
			'text_de'=>'FrontImages',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Team',
			'text_en'=>'Team',
			'text_de'=>'Team',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Newsletter',
			'text_en'=>'Newsletter',
			'text_de'=>'Newsletter',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'url',
			'text_en'=>'Url',
			'text_de'=>'Url',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Are you sure?',
			'text_en'=>'Are you sure?',
			'text_de'=>'Sind Sie sicher?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'This may take some more minutes. Do not refresh the page after you have clicked: "Yes, this is my wish!"',
			'text_en'=>'This may take some more minutes. Do not refresh the page after you have clicked: "Yes, this is my wish!"',
			'text_de'=>'Dies dauert eventuell etwas länger als nur ein paar Minuten. Bitte nicht mehr die Seite aktualisieren, wenn Sie: "Ja, das ist mein Wunsch!", gedrückt haben.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Yes, this is my wish!',
			'text_en'=>'Yes, this is my wish!',
			'text_de'=>'Ja, das ist mein Wunsch!',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Emailadresse',
			'text_en'=>'Email address',
			'text_de'=>'E-Mail-Adresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Produktsuche',
			'text_en'=>'Product-Search',
			'text_de'=>'Produktsuche',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Suche',
			'text_en'=>'Search',
			'text_de'=>'Suche',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Search',
			'text_en'=>'Search',
			'text_de'=>'Suche',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Einlösen',
			'text_de'=>'Einlösen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Gutschein',
			'text_en'=>'Coupon',
			'text_de'=>'Gutschein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Gutscheincode',
			'text_en'=>'Coupon-Code',
			'text_de'=>'Gutscheincode',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Preis von',
			'text_en'=>'Price from',
			'text_de'=>'Preis von',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'bis',
			'text_en'=>'till',
			'text_de'=>'bis',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Alle',
			'text_en'=>'All',
			'text_de'=>'Alle',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'FAQs',
			'text_en'=>'FAQs',
			'text_de'=>'FAQs',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Faq',
			'text_en'=>'FAQ',
			'text_de'=>'FAQ',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Question',
			'text_en'=>'Question',
			'text_de'=>'Frage',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Answer',
			'text_en'=>'Answer',
			'text_de'=>'Antwort',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Flyer',
			'text_en'=>'Flyer',
			'text_de'=>'Flyer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Create',
			'text_en'=>'Create',
			'text_de'=>'Erstellen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Created at',
			'text_en'=>'Created at',
			'text_de'=>'Erstellt am',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Wunschtermin und Besonderheiten',
			'text_en'=>'Desired date and special features',
			'text_de'=>'Wunschtermin und Besonderheiten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Retype Password',
			'text_en'=>'Retype Password',
			'text_de'=>'Passwort wiederholen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Frau',
			'text_en'=>'Mrs.',
			'text_de'=>'Frau',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Herr',
			'text_en'=>'Mr.',
			'text_de'=>'Herr',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'First Name',
			'text_en'=>'Firstname',
			'text_de'=>'Vorname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Last Name',
			'text_en'=>'Lastname',
			'text_de'=>'Nachname',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'House Number',
			'text_en'=>'House Number',
			'text_de'=>'Hausnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Register!',
			'text_en'=>'Register',
			'text_de'=>'Registrieren',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Login mit Facebook',
			'text_en'=>'Login with Facebook',
			'text_de'=>'Login mit Facebook',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Lost Password',
			'text_en'=>'Lost Password',
			'text_de'=>'Passwort vergessen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Passwort vergessen',
			'text_en'=>'Lost Password',
			'text_de'=>'Passwort vergessen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Passwort vergessen?',
			'text_en'=>'Frogot Password?',
			'text_de'=>'Passwort vergessen?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Retrieve Password',
			'text_en'=>'Retrieve Password',
			'text_de'=>'Passwort abrufen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Enter your email if you forgot your password and want to retrieve a new one.',
			'text_en'=>'Enter your email if you forgot your password and want to retrieve a new one.',
			'text_de'=>'Geben Sie Ihre E-Mail Adresse ein, wenn Sie Ihr Passwort vergessen haben und Sie bekommen ein Neues.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'An account with the specified login could not be found.',
			'text_en'=>'An account with the specified login could not be found.',
			'text_de'=>'Ein Benutzerkonto mit dem angegebenen Namen konnte nicht gefunden werden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The account associated with the specified login has not been activated.',
			'text_en'=>'The account associated with the specified login has not been activated.',
			'text_de'=>'Das Benutzerkonto mit dem angegebenen Namen wurde noch nicht aktiviert.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The account associated with the specified login can not be accessed that way.',
			'text_en'=>'The account associated with the specified login can not be accessed that way.',
			'text_de'=>'Das Benutzerkonto mit dem angegebenen Namen kann nicht auf diese Weise betreten werden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'We sent you an email.',
			'text_en'=>'We sent you an email.',
			'text_de'=>'Wir haben Ihnen eine E-Mail gesendet.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Reset Password',
			'text_en'=>'Reset Password',
			'text_de'=>'Passwort zurücksetzen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Feedback',
			'text_en'=>'Feedback',
			'text_de'=>'Feedback',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'You forgot your password?',
			'text_en'=>'You forgot your password?',
			'text_de'=>'Passwort vergessen?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ProductOrder',
			'text_en'=>'Product Orders',
			'text_de'=>'Bestellungen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bestellung vom',
			'text_en'=>'Order from',
			'text_de'=>'Bestellung vom',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Payed',
			'text_en'=>'Payed',
			'text_de'=>'Bezahlt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'State Shipped',
			'text_en'=>'Shipped',
			'text_de'=>'Verschickt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Storniert',
			'text_en'=>'Cancelled',
			'text_de'=>'Storniert',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'State Disposed',
			'text_en'=>'Compiled',
			'text_de'=>'Zusammengestellt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Order Checked',
			'text_en'=>'Order Checked',
			'text_de'=>'Bestätigt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'DeliveryCountry',
			'text_en'=>'Delivery Country',
			'text_de'=>'Versand-Land',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'BillingCountry',
			'text_en'=>'Billing Country',
			'text_de'=>'Rechnungs-Land',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop Order',
			'text_en'=>'Orders',
			'text_de'=>'Bestellungen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'ShopOrder',
			'text_en'=>'Orders',
			'text_de'=>'Bestellungen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'of',
			'text_en'=>'of',
			'text_de'=>'von',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' was not found in the haystack',
			'text_en'=>'\'%value%\' was not found in the haystack.',
			'text_de'=>'\'%value%\' wurde in der Datenmenge nicht wiedergefunden.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Here, you can setup the system in order to get it going.',
			'text_en'=>'Here, you can setup the system in order to get it going.',
			'text_de'=>'Hier können Sie das System einrichten, damit es funktioniert.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Here, you can setup the  system in order to get going.',
			'text_en'=>'Here, you can setup the system in order to get it going.',
			'text_de'=>'Hier können Sie das System einrichten, damit es funktioniert.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Here, you can setup the system in order to get going.',
			'text_en'=>'Here, you can setup the system in order to get it going.',
			'text_de'=>'Hier können Sie das System einrichten, damit es funktioniert.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Get a quick application overview.',
			'text_en'=>'Get a quick application overview.',
			'text_de'=>'Holen Sie sich einen schnellen Überblick über die Anwendung.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Setup and Configuration',
			'text_en'=>'Setup and Configuration',
			'text_de'=>'Setup und Konfiguration',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Set Up this application',
			'text_en'=>'Set Up this application',
			'text_de'=>'Einrichten dieser Anwendung',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Configure this application',
			'text_en'=>'Configure this application',
			'text_de'=>'Konfigurieren Sie diese Anwendung',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Roles and Users',
			'text_en'=>'Manage Roles and Users',
			'text_de'=>'Verwalten von Gruppen und Nutzern',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage roles',
			'text_en'=>'Manage Roles',
			'text_de'=>'Verwalten von Gruppen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage users',
			'text_en'=>'Manage Users',
			'text_de'=>'Verwalten von Nutzern',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Media and CSS',
			'text_en'=>'Manage Media and CSS',
			'text_de'=>'Verwalten von Medien und CSS',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Generate iconized list CSS',
			'text_en'=>'Generate iconized list CSS',
			'text_de'=>'Generiere Iconized-List CSS',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Resources',
			'text_en'=>'Manage Resources',
			'text_de'=>'Verwalten von Resourcen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage modules',
			'text_en'=>'Manage Modules',
			'text_de'=>'Verwalten von Modulen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Module',
			'text_en'=>'Module',
			'text_de'=>'Modul',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage controllers',
			'text_en'=>'Manage Controllers',
			'text_de'=>'Verwalten von Controllern',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage actions',
			'text_en'=>'Manage Actions',
			'text_de'=>'Verwalten von Aktionen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Plugins',
			'text_en'=>'Manage Plugins',
			'text_de'=>'Verwalten von Plugins',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage translations',
			'text_en'=>'Manage Translations',
			'text_de'=>'Verwalten von Übersetzungen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Translations',
			'text_en'=>'Manage Translations',
			'text_de'=>'Verwalten von Übersetzungen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage models',
			'text_en'=>'Manage Models',
			'text_de'=>'Verwalten von Modeln',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Models',
			'text_en'=>'Manage Models',
			'text_de'=>'Verwalten von Modeln',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'You should only tick this box if you have fully understood what\'s going to happen once you\'ve clicked the button below, you can\'t stop it.',
			'text_en'=>'You should only tick this box if you have fully understood what\'s going to happen once you\'ve clicked the button below, you can\'t stop it.',
			'text_de'=>'Sie sollten nur ankreuzen, wenn Sie voll und ganz verstanden haben, was passieren wird, denn sobald Sie auf die Schaltfläche geklickt haben werden, kann es nicht stoppen.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Yes, setup now!',
			'text_en'=>'Yes, setup now!',
			'text_de'=>'Ja jetzt einrichten!',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Using these options may lead to malfunctions. So make sure you really know what you are doing!',
			'text_en'=>'Using these options may lead to malfunctions. So make sure you really know what you are doing!',
			'text_de'=>'Das Benutzen dieser Optionen kann zu Fehlfunktionen führen. Seien Sie sich wirklich sicher bei dem was Sie tun.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Is real Action?',
			'text_en'=>'Is real Action?',
			'text_de'=>'Ist eine echte Aktion?',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Is new Action?',
			'text_en'=>'Is new Action?',
			'text_de'=>'Ist eine neue Aktion?',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'There is nothing to do.',
			'text_en'=>'There is nothing to do.',
			'text_de'=>'Es gibt nichts zu tun.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Update',
			'text_en'=>'Update',
			'text_de'=>'Update',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'ModelColumnName',
			'text_en'=>'ModelColumnName',
			'text_de'=>'ModelColumnName',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'ModelName',
			'text_en'=>'ModelName',
			'text_de'=>'ModelName',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'ModelColumnNameEditAs',
			'text_en'=>'ModelColumnNameEditAs',
			'text_de'=>'ModelColumnNameEditAs',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'ModelList',
			'text_en'=>'ModelList',
			'text_de'=>'ModelList',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Roles',
			'text_en'=>'Roles',
			'text_de'=>'Gruppen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'BackendAdminBoxes',
			'text_en'=>'Backend - AdminBoxes',
			'text_de'=>'Backend - AdminBoxes',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'BackendAdminBoxesAction',
			'text_en'=>'Backend - AdminBoxesAction',
			'text_de'=>'Backend - AdminBoxesAction',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Add All',
			'text_en'=>'Add All',
			'text_de'=>'Alle Hinzufügen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Imprint',
			'text_en'=>'Imprint',
			'text_de'=>'Impressum',
			'text_fr'=>'Empreinte',
			'text_ru'=>'Oтпечаток',
			'text_bg'=>'Oтпечатък',
			'text_es'=>'Pie de Imprenta',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Contact',
			'text_en'=>'Contact',
			'text_de'=>'Kontakt',
			'text_fr'=>'Contact',
			'text_ru'=>'Контакт',
			'text_bg'=>'Контакт',
			'text_es'=>'Contácto',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Emailaddress',
			'text_en'=>'E-Mail Address',
			'text_de'=>'E-Mail-Adresse',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Your Message',
			'text_en'=>'Your Message',
			'text_de'=>'Ihre Nachricht',
			'text_fr'=>'Votre Message',
			'text_ru'=>'Ваше Сообщение',
			'text_bg'=>'Вашето съобщение',
			'text_es'=>'Su mensaje',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Deutschland',
			'text_en'=>'Germany',
			'text_de'=>'Deutschland',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Über Uns',
			'text_en'=>'About Us',
			'text_de'=>'Über Uns',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Updates',
			'text_en'=>'Updates',
			'text_de'=>'Updates',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Models',
			'text_en'=>'Models',
			'text_de'=>'Models',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Actions',
			'text_en'=>'Actions',
			'text_de'=>'Aktionen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Info',
			'text_en'=>'Info',
			'text_de'=>'Info',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Infos',
			'text_en'=>'Infos',
			'text_de'=>'Infos',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Backend - AdminBoxes',
			'text_en'=>'Backend - AdminBoxes',
			'text_de'=>'Backend - AdminBoxes',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Backend - AdminBoxesAction',
			'text_en'=>'Backend - AdminBoxesAction',
			'text_de'=>'Backend - AdminBoxesAction',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Setup this application',
			'text_en'=>'Setup this application',
			'text_de'=>'Diese Anwendung einrichten',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Setup this application.',
			'text_en'=>'Setup this application.',
			'text_de'=>'Diese Anwendung einrichten.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Configure this application.',
			'text_en'=>'Configure this application.',
			'text_de'=>'Diese Anwendung konfigurieren.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage roles.',
			'text_en'=>'Manage Roles.',
			'text_de'=>'Verwalte Gruppen.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage users.',
			'text_en'=>'Manage Users.',
			'text_de'=>'Verwalte Benutzer.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Resources and Privileges',
			'text_en'=>'Manage Resources and Privileges',
			'text_de'=>'Verwalte Resourcen und Privileges',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage resources',
			'text_en'=>'Manage Resources',
			'text_de'=>'Verwalte Resources',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage resources.',
			'text_en'=>'Manage Resources.',
			'text_de'=>'Verwalte Resources.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage privileges',
			'text_en'=>'Manage Privileges',
			'text_de'=>'Verwalte Privileges',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage privileges.',
			'text_en'=>'Manage Privileges.',
			'text_de'=>'Verwalte Privileges.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage media.',
			'text_en'=>'Manage Media.',
			'text_de'=>'Verwalte Medien.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Generate sprite CSS from this application\'s icon files.',
			'text_en'=>'Generate sprite CSS from this application\'s icon files.',
			'text_de'=>'Genriere sprite CSS von den in der Anwendung installierten Icon-Dateien',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Modules, Controllers and Actions',
			'text_en'=>'Manage Modules, Controllers and Actions',
			'text_de'=>'Verwalte Module, Controller und Actions',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage modules.',
			'text_en'=>'Manage Modules.',
			'text_de'=>'Verwalte Module.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage controllers.',
			'text_en'=>'Manage Controllers.',
			'text_de'=>'Verwalte Controller.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage actions.',
			'text_en'=>'Manage Actions.',
			'text_de'=>'Verwalte Actions.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Models and Forms',
			'text_en'=>'Manage Models and Forms',
			'text_de'=>'Verwalte Modele und Formulare',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage models.',
			'text_en'=>'Manage Models.',
			'text_de'=>'Verwalte Modele.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage forms',
			'text_en'=>'Manage Forms',
			'text_de'=>'Verwalte Formulare',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage forms.',
			'text_en'=>'Manage Forms.',
			'text_de'=>'Verwalte Formulare.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'The specified password does not seem to be correct.',
			'text_en'=>'The specified password does not seem to be correct.',
			'text_de'=>'Das angegebene Passwort scheint nicht korrekt zu sein.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' does not match the expected structure for a DNS hostname',
			'text_en'=>'\'%value%\' does not match the expected structure for a DNS hostname.',
			'text_de'=>'\'%value%\' passt nicht in das erwartete Musster eines DNS-Hostnamens.',
			'text_es'=>'\'%value%\' no se corresponde con la estructura prevista para un nombre de host DNS.',
			'text_fr'=>'\'%value%\' ne correspond pas à la structure attendue pour un nom d\'hôte DNS.',
			'text_ru'=>'\'%value%\' не соответствует ожидаемому структуру для DNS имя хоста.',
			'text_bg'=>'\'%value%\' не съвпада с очакваната структура за DNS име на хост.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Copy',
			'text_en'=>'Copy',
			'text_de'=>'Kopieren',
			'text_fr'=>'Copie',
			'text_ru'=>'копия',
			'text_bg'=>'копие',
			'text_es'=>'Copiar',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'File \'%value%\' was not uploaded',
			'text_en'=>'File \'%value%\' was not uploaded.',
			'text_de'=>'Die Datei \'%value%\' wurde nicht hochgeladen.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage media',
			'text_en'=>'Manage Media',
			'text_de'=>'Verwalte Medien',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Model',
			'text_en'=>'Model',
			'text_de'=>'Model',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'The item is in use. Make sure, you do not use it on another record. It seems to be required there and cannot be deleted recursiv.',
			'text_en'=>'The item is in use. Make sure, you do not use it on another record. It seems to be required there and cannot be deleted recursiv.',
			'text_de'=>'Dieses Datentupel wird benutzt. Stellen Sie sicher, dass es nicht mit einem anderen Datensatz verwendet wird. Es scheint dort benötigt zu werden und kann nicht rekursiv gelöscht werden.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'It is currently not permitted to edit the record.',
			'text_en'=>'It is currently not permitted to edit the record.',
			'text_de'=>'Es ist derzeit nicht gestattet den Datensatz zu bearbeiten.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Kontakt-Informationen',
			'text_en'=>'Contact-Informations',
			'text_de'=>'Kontakt-Informationen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Neuer Termin',
			'text_en'=>'New Date',
			'text_de'=>'Neuer Termin',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Erledigt',
			'text_en'=>'Done',
			'text_de'=>'Erledigt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Offene Termine',
			'text_en'=>'Open Dates',
			'text_de'=>'Offene Termine',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Nicht Erledig',
			'text_en'=>'Not Done',
			'text_de'=>'Nicht Erledig',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Abgeschlossene Termine',
			'text_en'=>'Closed Dates',
			'text_de'=>'Abgeschlossene Termine',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Mehr Infos',
			'text_en'=>'More infos',
			'text_de'=>'Mehr Infos',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Mehr Termine',
			'text_en'=>'More Dates',
			'text_de'=>'Mehr Termine',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Angebots-Kunden erstellen',
			'text_en'=>'Create Offer-Customer',
			'text_de'=>'Angebots-Kunden erstellen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Kopieren',
			'text_en'=>'Copy',
			'text_de'=>'Kopieren',
			'text_fr'=>'Copie',
			'text_ru'=>'копия',
			'text_bg'=>'копие',
			'text_es'=>'Copiar',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Speichern & nächsten Datensatz hinzufügen',
			'text_en'=>'Save & add next Record',
			'text_de'=>'Speichern & nächsten Datensatz hinzufügen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Speichern & nächsten Datensatz bearbeiten',
			'text_en'=>'Save & edit next Record',
			'text_de'=>'Speichern & nächsten Datensatz bearbeiten',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Noch mehr Termine',
			'text_en'=>'Even more Dates',
			'text_de'=>'Noch mehr Termine',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'\'%value%\' contains characters which are non alphabetic and no digits',
			'text_en'=>'\'%value%\' contains characters which are non alphabetic and no digits.',
			'text_de'=>'\'%value%\' beinhaltet Zeichen, die keine Buchstaben oder Zahlen sind.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Change',
			'text_en'=>'Change',
			'text_de'=>'Ändern',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Change Directory',
			'text_en'=>'Change Directory',
			'text_de'=>'Verzteichnis Wechseln',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Rename',
			'text_en'=>'Rename',
			'text_de'=>'Umbenennen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'This directory should not contain any subdirectory. All medias will be moved into the parent directory.',
			'text_en'=>'This directory should not contain any subdirectory. All medias will be moved into the parent directory.',
			'text_de'=>'Dieses Verzeichnis sollte keine Unterverzeichnisse beinhalten. Alle Medien werden in das darüber liegende Verzeichnis bewegt.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Project',
			'text_en'=>'Project',
			'text_de'=>'Projekt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Projects',
			'text_en'=>'Projects',
			'text_de'=>'Projekte',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Url',
			'text_en'=>'Url',
			'text_de'=>'Url',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Leistungen',
			'text_en'=>'Services',
			'text_de'=>'Leistungen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Projekte',
			'text_en'=>'Projects',
			'text_de'=>'Projekte',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Date',
			'text_en'=>'Date',
			'text_de'=>'Datum',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'%1s - Blog',
			'text_en'=>'%1s - Blog',
			'text_de'=>'%1s - Blog',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'The latest news from %1s.',
			'text_en'=>'The latest news from %1s.',
			'text_de'=>'Die letzen Neuigkeiten auf %1s.',
		);


		$w[] = array(
			'id'=>$i++,
			'short'=>'Clear all Sessions',
			'text_en'=>'Clear all Sessions',
			'text_de'=>'Alle Sessions löschen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sessions have been cleared (%1s remaining).',
			'text_en'=>'Sessions have been cleared (%1s remaining).',
			'text_de'=>'Sessions wurden gelöscht (%1s blieben zurück).',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Session Clearing requested.',
			'text_en'=>'Session Clearing requested.',
			'text_de'=>'Session Löschvorgangs-Anfrage.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Clear all Caches',
			'text_en'=>'Clear all Caches',
			'text_de'=>'Alle Caches leeren',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Cleared %1s entries from cache.',
			'text_en'=>'Cleared %1s entries from cache.',
			'text_de'=>'%1s Einträge wurden vom Cache geleert.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Cache Clearing requested.',
			'text_en'=>'Cache Clearing requested.',
			'text_de'=>'Cache Löschvorgangs-Anfrage.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Temporary Datas',
			'text_en'=>'Manage Temporary Datas',
			'text_de'=>'Verwalten von temporären Daten.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Configurate the Newsletter',
			'text_en'=>'Configurate the Newsletter',
			'text_de'=>'Verwalten Sie den Newsletter',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'NewsletterSubscriber',
			'text_en'=>'Newsletter-Subscriber',
			'text_de'=>'Newsletter-Angemeldete',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Newsletter Subscriber',
			'text_en'=>'Newsletter-Subscriber',
			'text_de'=>'Newsletter-Angemeldete',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Vielen Dank für Ihre Anmeldung bei unserem Newsletter.',
			'text_en'=>'Thank you for subscribing our newsletter.',
			'text_de'=>'Vielen Dank für Ihre Anmeldung bei unserem Newsletter.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'MetaConfiguration',
			'text_en'=>'Meta-Tag Configuration',
			'text_de'=>'Meta-Tag Konfiguration',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Test',
			'text_en'=>'Test',
			'text_de'=>'Test',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sending Newsletter',
			'text_en'=>'Sending Newsletter',
			'text_de'=>'Versand des Newsletter',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Der Newsletter wurde versendet.',
			'text_en'=>'The newsletter was sent.',
			'text_de'=>'Der Newsletter wurde versendet.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Language',
			'text_en'=>'Language',
			'text_de'=>'Sprache',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'News',
			'text_en'=>'News',
			'text_de'=>'Aktuelles',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Aktuelles',
			'text_en'=>'Latest',
			'text_de'=>'Aktuelles',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Subject',
			'text_en'=>'Subject',
			'text_de'=>'Betreff',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'EmailTemplate',
			'text_en'=>'Email - Template',
			'text_de'=>'E-Mail - Vorlage',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'EmailTemplatePart',
			'text_en'=>'Email - Template-Part',
			'text_de'=>'E-Mail - Teil-Vorlage',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Configurate EmailTemplate',
			'text_en'=>'Configurate Email - Templates',
			'text_de'=>'Verwalten von E-Mail - Vorlagen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Html body css style',
			'text_en'=>'HTML Body CSS-Style',
			'text_de'=>'HTML Body CSS-Style',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Html paragraph css style',
			'text_en'=>'HTML paragraph CSS-Style',
			'text_de'=>'HTML Absatz CSS-Style',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Html headline css style',
			'text_en'=>'HTML Headline CSS-Style',
			'text_de'=>'HTML Überschrift CSS-Style',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Html data css style',
			'text_en'=>'HTML Data CSS-Style',
			'text_de'=>'HTML Daten CSS-Style',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Html dataline label css style',
			'text_en'=>'HTML Dataline Label CSS-Style',
			'text_de'=>'HTML Datenzeile Label CSS-Style',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Html dataline data css style',
			'text_en'=>'HTML Dataline Data CSS-Style',
			'text_de'=>'HTML Datenzeile Daten CSS-Style',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'All files in sum should have a maximum size of \'%max%\' but \'%size%\' were detected',
			'text_en'=>'All files in sum should have a maximum size of \'%max%\' but \'%size%\' were detected.',
			'text_de'=>'Alle Dateien sollten eine Größe von insgesamt \'%max%\' nicht überschreiten. Es wurden jedoch \'%size%\' erkannt.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Account Overview',
			'text_en'=>'Account Overview',
			'text_de'=>'Benutzerkonto - Übersicht',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Orders Overview',
			'text_en'=>'Orders Overview',
			'text_de'=>'Bestellübersicht',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'User-Account',
			'text_en'=>'My Account',
			'text_de'=>'Mein Benutzerkonto',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Backend AdminBox-Action',
			'text_en'=>'Backend AdminBox-Action',
			'text_de'=>'Backend AdminBox-Aktion',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Model Name',
			'text_en'=>'Model Name',
			'text_de'=>'Model Name',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Name short',
			'text_en'=>'Name Short',
			'text_de'=>'Namen-Kürzel',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Default sort',
			'text_en'=>'Default Sort',
			'text_de'=>'Standart-Sortierung',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Button edit',
			'text_en'=>'Button Edit',
			'text_de'=>'Button Bearbeiten',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Button add',
			'text_en'=>'Button Add',
			'text_de'=>'Button Hinzufügen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Button delete',
			'text_en'=>'Button Delete',
			'text_de'=>'Button Löschen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Is allowed',
			'text_en'=>'Is Allowed',
			'text_de'=>'Ist Erlaubt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Is action method',
			'text_en'=>'Is Action-Method',
			'text_de'=>'Ist virtuelle Aktion',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Layout',
			'text_en'=>'Layout',
			'text_de'=>'Layout',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Content partial',
			'text_en'=>'Content-Partial',
			'text_de'=>'Partieller Inhalt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Navigation',
			'text_en'=>'Navigation',
			'text_de'=>'Navigation',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Navigation',
			'text_en'=>'Manage Navigation',
			'text_de'=>'Verwalten der Navigation',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Action Resource',
			'text_en'=>'Action Resource',
			'text_de'=>'Aktions-Resource',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Default action resource',
			'text_en'=>'Action Resource',
			'text_de'=>'Aktions-Resource',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Role Short',
			'text_en'=>'Short of Role',
			'text_de'=>'Kürzel der Gruppe',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Show all',
			'text_en'=>'Show All',
			'text_de'=>'Zeige Allen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Show all loggedin',
			'text_en'=>'Show all Loggedin',
			'text_de'=>'Zeige allen Eingeloggten',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Target',
			'text_en'=>'Target',
			'text_de'=>'Ziel',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Uri',
			'text_en'=>'URI',
			'text_de'=>'URI',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Dynamic',
			'text_en'=>'Dynamic',
			'text_de'=>'Dynamisch',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Visible',
			'text_en'=>'Visible',
			'text_de'=>'Sichtbar',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Css class',
			'text_en'=>'CSS-Class',
			'text_de'=>'CSS-Klasse',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Default language',
			'text_en'=>'Default-Language',
			'text_de'=>'Standard-Sprache',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Do not translate',
			'text_en'=>'Do not translate',
			'text_de'=>'Nicht übersetzen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Parent Element',
			'text_en'=>'Parent Element',
			'text_de'=>'Eltern-Element',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Eltern-Element',
			'text_en'=>'Parent Element',
			'text_de'=>'Eltern-Element',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Medias, that can be seen by role: %1s',
			'text_en'=>'Medias, that can be seen by role: %1s',
			'text_de'=>'Medien, die von der Nutzergruppe "%1s" gesehen werden können.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Medias, that are not filtered by roles.',
			'text_en'=>'Medias, that are not filtered by roles.',
			'text_de'=>'Medien, die nicht nach Nutzergruppen gefiltert sind.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Done',
			'text_en'=>'Done',
			'text_de'=>'Erledigt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Time',
			'text_en'=>'Time',
			'text_de'=>'Zeit',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Hour',
			'text_en'=>'Hour',
			'text_de'=>'Stunde',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Minute',
			'text_en'=>'Minute',
			'text_de'=>'Minute',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Second',
			'text_en'=>'Second',
			'text_de'=>'Sekunde',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Now',
			'text_en'=>'Now',
			'text_de'=>'Jetzt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'You agree, that setup will delete all files in "/data/media", "/public/img/default/captcha" and "/public/mediafile".',
			'text_en'=>'You agree, that setup will delete all files in "/data/media", "/public/img/default/captcha" and "/public/mediafile".',
			'text_de'=>'Sie stimmen zu, dass Setup sämtliche Dateien in "/data/media", "/public/img/default/captcha" und "/public/mediafile" löschen wird.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Delete temporary Images',
			'text_en'=>'Delete temporary Images',
			'text_de'=>'Lösche temporäre Bilder',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Newsletter Gruppe',
			'text_en'=>'Newsletter Group',
			'text_de'=>'Newsletter Gruppe',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Bitte wählen Sie aus, an welchen Typ der Newsletter-Angemeldeten der Newsletter verschickt werden sollen.',
			'text_en'=>'Select the type of newsletter subscriber the newsletter should be send to, please.',
			'text_de'=>'Bitte wählen Sie aus, an welchen Typ der Newsletter-Angemeldeten der Newsletter verschickt werden sollen.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'ContentSiteImages',
			'text_en'=>'Content-Site Images',
			'text_de'=>'Inhalts-Seiten Bilder',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'BackgroundImage',
			'text_en'=>'Background Image',
			'text_de'=>'Hintergrundbild',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'ImageConfig',
			'text_en'=>'Image-Config',
			'text_de'=>'Bild-Config',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'InfoPage',
			'text_en'=>'Info-Page',
			'text_de'=>'Info-Seite',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'SiteConfig',
			'text_en'=>'Site-Config',
			'text_de'=>'Seiten-Config',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'MediaConfig',
			'text_en'=>'Media-Config',
			'text_de'=>'Medien-Config',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Lang',
			'text_en'=>'Language-Code',
			'text_de'=>'Sprach-Code',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Newsletter Subscriber Type',
			'text_en'=>'Type of Newsletter Subscriber',
			'text_de'=>'Typ des Newsletter-Angemeldeten',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'NewsletterSubscriberType',
			'text_en'=>'Newsletter Subscriber Types',
			'text_de'=>'Typen der Newsletter-Angemeldeten',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre Anfrage wurde erfolgreich an uns übermittelt.',
			'text_en'=>'Your request has been successfully sent to us.',
			'text_de'=>'Ihre Anfrage wurde erfolgreich an uns übermittelt.',
			'text_es'=>'Su solicitud ha sido enviado correctamente a nosotros.',
			'text_fr'=>'Votre demande a été soumis avec succès à nous.',
			'text_ru'=>'Ваш запрос был успешно представлен на нас.',
			'text_bg'=>'Вашата заявка беше изпратена успешно за нас.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Wir haben Sie nun aus dem Newsletter-Verteiler entfernt.',
			'text_en'=>'We removed you now from the newsletter mailing list.',
			'text_de'=>'Wir haben Sie nun aus dem Newsletter-Verteiler entfernt.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'weiterlesen',
			'text_en'=>'read more',
			'text_de'=>'weiterlesen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'ContentBox',
			'text_en'=>'Content-Box',
			'text_de'=>'Inhalts-Box',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Link',
			'text_en'=>'Link',
			'text_de'=>'Link',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Media Image M2n Action',
			'text_en'=>'Background Images for Action',
			'text_de'=>'Hintergrundbilder für Aktionen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Prev',
			'text_en'=>'Prev',
			'text_de'=>'Zurück',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Next',
			'text_en'=>'Next',
			'text_de'=>'Vor',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'January',
			'text_en'=>'January',
			'text_de'=>'Januar',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'February',
			'text_en'=>'February',
			'text_de'=>'Februar',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'March',
			'text_en'=>'March',
			'text_de'=>'März',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'April',
			'text_en'=>'April',
			'text_de'=>'April',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'May',
			'text_en'=>'May',
			'text_de'=>'Mai',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'June',
			'text_en'=>'June',
			'text_de'=>'Juni',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'July',
			'text_en'=>'July',
			'text_de'=>'Juli',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'August',
			'text_en'=>'August',
			'text_de'=>'August',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'September',
			'text_en'=>'September',
			'text_de'=>'September',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'October',
			'text_en'=>'October',
			'text_de'=>'Oktober',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'November',
			'text_en'=>'November',
			'text_de'=>'November',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'December',
			'text_en'=>'December',
			'text_de'=>'Dezember',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Jan',
			'text_en'=>'Jan',
			'text_de'=>'Jan',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Feb',
			'text_en'=>'Feb',
			'text_de'=>'Feb',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Mar',
			'text_en'=>'Mar',
			'text_de'=>'Mär',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Apr',
			'text_en'=>'Apr',
			'text_de'=>'Apr',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Jun',
			'text_en'=>'Jun',
			'text_de'=>'Jun',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Jul',
			'text_en'=>'Jul',
			'text_de'=>'Jul',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Aug',
			'text_en'=>'Aug',
			'text_de'=>'Aug',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sep',
			'text_en'=>'Sep',
			'text_de'=>'Sep',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Oct',
			'text_en'=>'Oct',
			'text_de'=>'Okt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Nov',
			'text_en'=>'Nov',
			'text_de'=>'Nov',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Dec',
			'text_en'=>'Dec',
			'text_de'=>'Dez',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sunday',
			'text_en'=>'Sunday',
			'text_de'=>'Sonntag',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Monday',
			'text_en'=>'Monday',
			'text_de'=>'Montag',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Tuesday',
			'text_en'=>'Tuesday',
			'text_de'=>'Dienstag',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Wednesday',
			'text_en'=>'Wednesday',
			'text_de'=>'Mittwoch',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Thursday',
			'text_en'=>'Thursday',
			'text_de'=>'Donnerstag',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Friday',
			'text_en'=>'Friday',
			'text_de'=>'Freitag',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Saturday',
			'text_en'=>'Saturday',
			'text_de'=>'Samstag',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sun',
			'text_en'=>'Sun',
			'text_de'=>'So',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Mon',
			'text_en'=>'Mon',
			'text_de'=>'Mo',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Tue',
			'text_en'=>'Tue',
			'text_de'=>'Di',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Wed',
			'text_en'=>'Wed',
			'text_de'=>'Mi',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Thu',
			'text_en'=>'Thu',
			'text_de'=>'Do',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Fri',
			'text_en'=>'Fri',
			'text_de'=>'Fr',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sat',
			'text_en'=>'Sat',
			'text_de'=>'Sa',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Su',
			'text_en'=>'Su',
			'text_de'=>'So',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Mo',
			'text_en'=>'Mo',
			'text_de'=>'Mo',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Tu',
			'text_en'=>'Tu',
			'text_de'=>'Di',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'We',
			'text_en'=>'We',
			'text_de'=>'Mi',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Th',
			'text_en'=>'Th',
			'text_de'=>'Do',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Fr',
			'text_en'=>'Fr',
			'text_de'=>'Fr',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sa',
			'text_en'=>'Sa',
			'text_de'=>'Sa',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Pflichtfeld',
			'text_en'=>'Required field',
			'text_de'=>'Pflichtfeld',
			'text_fr'=>'Champ Obligatoire',
			'text_ru'=>'Обязательное поле',
			'text_bg'=>'Задължително поле',
			'text_es'=>'Campos obligatorios',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Displaying {from} to {to} of {total} items',
			'text_en'=>'Displaying {from} to {to} of {total} items',
			'text_de'=>'Zeige {from} bis {to} von {total} Einträgen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'No items',
			'text_en'=>'No items',
			'text_de'=>'Keine Einträge',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Connection Error',
			'text_en'=>'Connection Error',
			'text_de'=>'Verbindungsfehler',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Processing, please wait ...',
			'text_en'=>'Processing, please wait ...',
			'text_de'=>'Verarbeitung, bitte warten ...',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Page {current} of {total}',
			'text_en'=>'Page {current} of {total}',
			'text_de'=>'Seite {current} von {total}',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'General',
			'text_en'=>'General',
			'text_de'=>'Allgemein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Dynamic Content',
			'text_en'=>'Dynamic Content',
			'text_de'=>'Dynamischer Inhalt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Homepage',
			'text_en'=>'Homepage',
			'text_de'=>'Homepage',
			'text_es'=>'Página Principal',
			'text_fr'=>'Page d\'Accueil',
			'text_ru'=>'Домашняя Страница',
			'text_bg'=>'Уебсайт',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Count the not new rows',
			'text_en'=>'Count the not new rows',
			'text_de'=>'Zähle die nicht neuen Zeilen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Count new rows of Model',
			'text_en'=>'Count new rows of Model',
			'text_de'=>'Zähle neue Zeilen im Model',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Die Sitemap konnte nicht manuell erneut erstellt werden.',
			'text_en'=>'The sitemap can not be recreated manually.',
			'text_de'=>'Die Sitemap konnte nicht manuell erneut erstellt werden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Die Sitemap wurde manuell erneut erstellt.',
			'text_en'=>'The sitemap was manually recreated.',
			'text_de'=>'Die Sitemap wurde manuell erneut erstellt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Configuration',
			'text_en'=>'Configuration',
			'text_de'=>'Konfiguration',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Temporary Datas',
			'text_en'=>'Temporary Datas',
			'text_de'=>'Temporäre Daten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Roles & Users',
			'text_en'=>'Roles & Users',
			'text_de'=>'Gruppen & Nutzer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Medias & CSS',
			'text_en'=>'Medias & CSS',
			'text_de'=>'Medien & CSS',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Resources & Navigation',
			'text_en'=>'Resources & Navigation',
			'text_de'=>'Resourcen & Navigation',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage Resources & Navigation',
			'text_en'=>'Manage Resources & Navigation',
			'text_de'=>'Manage Resources & Navigation',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ActionResource',
			'text_en'=>'Action Resource',
			'text_de'=>'Aktions-Resource',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'RoleShort',
			'text_en'=>'Short of Role',
			'text_de'=>'Kürzel der Gruppe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnung',
			'text_en'=>'Invoice',
			'text_de'=>'Rechnung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Lieferschein',
			'text_en'=>'Delivery Note',
			'text_de'=>'Lieferschein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Produkt-Bilder',
			'text_en'=>'Product-Images',
			'text_de'=>'Produkt-Bilder',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Produkt-Optionen',
			'text_en'=>'Product-Options',
			'text_de'=>'Produkt-Optionen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Product Status',
			'text_en'=>'Product-Status',
			'text_de'=>'Produkt-Status',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ProductStatus',
			'text_en'=>'Product-Status',
			'text_de'=>'Produkt-Status',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Color code',
			'text_en'=>'Color-Code',
			'text_de'=>'Farb-Code',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Datei',
			'text_en'=>'File',
			'text_de'=>'Datei',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shockwave',
			'text_en'=>'Shockwave',
			'text_de'=>'Shockwave',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Produkt-Tag',
			'text_en'=>'Product-Tag',
			'text_de'=>'Produkt-Tag',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Menge der Einheiten',
			'text_en'=>'Number of Units',
			'text_de'=>'Menge der Einheiten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'reverenced_id',
			'text_en'=>'Reverenzed',
			'text_de'=>'Referenziert',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Visit us on Facebook',
			'text_en'=>'Visit us on Facebook',
			'text_de'=>'Besuchen Sie uns in Facebook',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Visit us on Twitter',
			'text_en'=>'Visit us on Twitter',
			'text_de'=>'Besuchen Sie uns in Twitter',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Visit us on Pinterest',
			'text_en'=>'Visit us on Pinterest',
			'text_de'=>'Besuchen Sie uns in Pinterest',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Close',
			'text_en'=>'Close',
			'text_de'=>'Schließen',
		);

		/**
		 * shop stuff
		 */
		$w[] = array(
			'id'=>$i++,
			'short'=>'Herzlich Willkommen',
			'text_en'=>'Welcome',
			'text_de'=>'Herzlich Willkommen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Neue Produkte',
			'text_en'=>'New Products',
			'text_de'=>'Neue Produkte',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Das Produkt wurde in den Warenkorb gelegt.',
			'text_en'=>'The product has been added to your cart.',
			'text_de'=>'Das Produkt wurde in den Warenkorb gelegt.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'ab',
			'text_en'=>'from',
			'text_de'=>'ab',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Menge',
			'text_en'=>'Quantity',
			'text_de'=>'Menge',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Quantity',
			'text_en'=>'Quantity',
			'text_de'=>'Menge',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'in den Warenkorb',
			'text_en'=>'add to cart',
			'text_de'=>'in den Warenkorb',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Top-Produkte',
			'text_en'=>'Top Products',
			'text_de'=>'Top-Produkte',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sonderangebote',
			'text_en'=>'Temporary Offers',
			'text_de'=>'Sonderangebote',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Neuer Kunde',
			'text_en'=>'New Customer',
			'text_de'=>'Neuer Kunde',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Zum Warenkorb',
			'text_en'=>'To Cart',
			'text_de'=>'Zum Warenkorb',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihr Warenkorb ist leer.',
			'text_en'=>'Your cart is empty.',
			'text_de'=>'Ihr Warenkorb ist leer.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Copyright %1s alle Rechte vorbehalten.',
			'text_en'=>'Copyright %1s all rights reserved.',
			'text_de'=>'Copyright %1s alle Rechte vorbehalten.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre Registrierung war erfolgreich! Sie erhalten nun eine E-Mail mit dem Link zur Aktivierung ihres Accounts.',
			'text_en'=>'Your registration was successful! You will receive an email with a link to activate their account.',
			'text_de'=>'Ihre Registrierung war erfolgreich! Sie erhalten nun eine E-Mail mit dem Link zur Aktivierung ihres Benutzerkontos.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Enter your email if your account has been disabled for security reasons and you\'ll retrieve an email with instructions on how to re-activate your account.',
			'text_en'=>'Enter your email if your account has been disabled for security reasons and you\'ll retrieve an email with instructions on how to re-activate your account.',
			'text_de'=>'Wenn Ihr Benutzerkonto deaktiviert wurde, geben Sie bitte Ihre E-Mail an und Sie erhalten eine Nachricht mit Anweisung zur Reaktivierung an diese.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihr %1s Team',
			'text_en'=>'Your %1s Team',
			'text_de'=>'Ihr %1s Team',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Mein Profil',
			'text_en'=>'Profile',
			'text_de'=>'Mein Profil',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Bestellvorgänge',
			'text_en'=>'Order processes',
			'text_de'=>'Bestellvorgänge',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Persönliche Daten',
			'text_en'=>'Personal data',
			'text_de'=>'Persönliche Daten',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Persönliche Einstellungen',
			'text_en'=>'Personal settings',
			'text_de'=>'Persönliche Einstellungen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ausloggen',
			'text_en'=>'Logout',
			'text_de'=>'Ausloggen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihr Warenkorb',
			'text_en'=>'Your cart',
			'text_de'=>'Ihr Warenkorb',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Zur Kasse',
			'text_en'=>'Go to checkout',
			'text_de'=>'Zur Kasse',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ich möchte einen Gutschein einlösen',
			'text_en'=>'I want to redeem a coupon',
			'text_de'=>'Ich möchte einen Gutschein einlösen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Weiter zur Adressdateneingabe',
			'text_en'=>'Continue to address data entry',
			'text_de'=>'Weiter zur Adressdateneingabe',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Art.-Nr.',
			'text_en'=>'Art.-No.',
			'text_de'=>'Art.-Nr.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Grundpreis',
			'text_en'=>'Basic price',
			'text_de'=>'Grundpreis',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'inkl.',
			'text_en'=>'incl.',
			'text_de'=>'inkl.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'MwSt',
			'text_en'=>'VAT',
			'text_de'=>'MwSt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'zzgl.',
			'text_en'=>'plus',
			'text_de'=>'zzgl.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Zwischensumme',
			'text_en'=>'Subtotal',
			'text_de'=>'Zwischensumme',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'inklusive',
			'text_en'=>'included',
			'text_de'=>'inklusive',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Bezahlmethode wählen',
			'text_en'=>'Choose payment method',
			'text_de'=>'Bezahlmethode wählen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Adresse eingeben',
			'text_en'=>'Enter address',
			'text_de'=>'Adresse eingeben',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Kauf abgeschlossen',
			'text_en'=>'Purchase completed',
			'text_de'=>'Kauf abgeschlossen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Einkaufsschritte',
			'text_en'=>'Buy steps',
			'text_de'=>'Einkaufsschritte',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Kartennummer',
			'text_en'=>'Card number',
			'text_de'=>'Kartennummer',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Gültig bis',
			'text_en'=>'Valid to',
			'text_de'=>'Gültig bis',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sicherheitscode',
			'text_en'=>'Security code',
			'text_de'=>'Sicherheitscode',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ich habe die %s gelesen und akzeptiere diese.',
			'text_en'=>'I have read the %s and I accept them.',
			'text_de'=>'Ich habe die %s gelesen und akzeptiere diese.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Weiter zur Bestellübersicht',
			'text_en'=>'Proceed to order summary',
			'text_de'=>'Weiter zur Bestellübersicht',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Weiter zur Bestellung',
			'text_en'=>'Proceed to Order',
			'text_de'=>'Weiter zur Bestellung',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Bestellinformationen ändern',
			'text_en'=>'Change ordering information',
			'text_de'=>'Bestellinformationen ändern',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Bestellung abbrechen',
			'text_en'=>'Cancel order',
			'text_de'=>'Bestellung abbrechen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Bezahlmethode ändern',
			'text_en'=>'Change of payment method',
			'text_de'=>'Bezahlmethode ändern',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnungsinformationen',
			'text_en'=>'Billing information',
			'text_de'=>'Rechnungsinformationen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Versandinformationen',
			'text_en'=>'Shipping information',
			'text_de'=>'Versandinformationen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Artikel',
			'text_en'=>'Article',
			'text_de'=>'Artikel',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Artikelnummer',
			'text_en'=>'Article number',
			'text_de'=>'Artikelnummer',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Stk.',
			'text_en'=>'pcs',
			'text_de'=>'Stk.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Stk',
			'text_en'=>'pcs',
			'text_de'=>'Stk',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Versand & Bearbeitung',
			'text_en'=>'Shipping & handling',
			'text_de'=>'Versand & Bearbeitung',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Steuern',
			'text_en'=>'Taxes',
			'text_de'=>'Steuern',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Gesamtsumme',
			'text_en'=>'Total',
			'text_de'=>'Gesamtsumme',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnungs - Straße',
			'text_en'=>'Billing - street',
			'text_de'=>'Rechnungs - Straße',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnungs - Hausnummer',
			'text_en'=>'Billing - street number',
			'text_de'=>'Rechnungs - Hausnummer',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnungs - Adresszeile 1',
			'text_en'=>'Billing - address line 1',
			'text_de'=>'Rechnungs - Adresszeile 1',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnungs - Adresszeile 2',
			'text_en'=>'Billing - address line 2',
			'text_de'=>'Rechnungs - Adresszeile 2',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Please note the following data that you used to register with us.',
			'text_en'=>'Please note the following data that you used to register with us.',
			'text_de'=>'Bitte beachten Sie die nachfolgenden Daten, mit denen Sie sich bei uns registriert haben.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnungs - Postleitzahl',
			'text_en'=>'Billing - zip code',
			'text_de'=>'Rechnungs - Postleitzahl',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnungs - Stadt',
			'text_en'=>'Billing - city',
			'text_de'=>'Rechnungs - Stadt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Liefer - Vorname',
			'text_en'=>'Delivery - firstname',
			'text_de'=>'Liefer - Vorname',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Liefer - Nachname',
			'text_en'=>'Delivery - lastname',
			'text_de'=>'Liefer - Nachname',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Liefer - Straße',
			'text_en'=>'Delivery - street',
			'text_de'=>'Liefer - Straße',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Liefer - Hausnummer',
			'text_en'=>'Delivery - street number',
			'text_de'=>'Liefer - Hausnummer',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Liefer - Adresszeile 1',
			'text_en'=>'Delivery - address line 1',
			'text_de'=>'Liefer - Adresszeile 1',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Liefer - Adresszeile 2',
			'text_en'=>'Delivery - address line 2',
			'text_de'=>'Liefer - Adresszeile 2',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Liefer - Postleitzahl',
			'text_en'=>'Delivery - zip code',
			'text_de'=>'Liefer - Postleitzahl',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Liefer - Stadt',
			'text_en'=>'Delivery - city',
			'text_de'=>'Liefer - Stadt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Kommentar zur Bestellung',
			'text_en'=>'Comment the order',
			'text_de'=>'Kommentar zur Bestellung',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Interne Notizen',
			'text_en'=>'Internal notes',
			'text_de'=>'Interne Notizen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Anzahl',
			'text_en'=>'Quantity',
			'text_de'=>'Anzahl',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Art.-Nr.:',
			'text_en'=>'Art.-No.:',
			'text_de'=>'Art.-Nr.:',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Storno',
			'text_en'=>'Cancellation',
			'text_de'=>'Storno',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Angegebene Zahlungsinformationen',
			'text_en'=>'Specified payment information',
			'text_de'=>'Angegebene Zahlungsinformationen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Informationen',
			'text_en'=>'Information',
			'text_de'=>'Informationen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Aktionen für die laufende Bestellung',
			'text_en'=>'For the current work order',
			'text_de'=>'Aktionen für die laufende Bestellung',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Bestellbestätigungsemail verschicken',
			'text_en'=>'Send order confirmation email',
			'text_de'=>'Bestellbestätigungsemail verschicken',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Zahlungseingang bestätigen',
			'text_en'=>'Confirm receipt of payment',
			'text_de'=>'Zahlungseingang bestätigen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Versandbestätigungsemail verschicken',
			'text_en'=>'Send shipping confirmation email',
			'text_de'=>'Versandbestätigungsemail verschicken',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Lieferschein anzeigen',
			'text_en'=>'View Slip',
			'text_de'=>'Lieferschein anzeigen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Rechnung anzeigen',
			'text_en'=>'View Bill',
			'text_de'=>'Rechnung anzeigen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sind sie sicher?',
			'text_en'=>'Are you sure?',
			'text_de'=>'Sind sie sicher?',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Änderungen gehen verloren. Sind sie sicher?',
			'text_en'=>'Changes are lost. Are you sure?',
			'text_de'=>'Änderungen gehen verloren. Sind sie sicher?',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Dies ist nicht unumkehrbar. Sind sie sicher?',
			'text_en'=>'This is not irreversible. Are you sure?',
			'text_de'=>'Dies ist nicht unumkehrbar. Sind sie sicher?',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Zahlungsmethode',
			'text_en'=>'Payment method',
			'text_de'=>'Zahlungsmethode',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Kartenbesitzer',
			'text_en'=>'Card holder',
			'text_de'=>'Kartenbesitzer',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Kreditkartentyp',
			'text_en'=>'Credit card type',
			'text_de'=>'Kreditkartentyp',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Kreditkartennummer',
			'text_en'=>'Credit card number',
			'text_de'=>'Kreditkartennummer',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ablaufdatum',
			'text_en'=>'expiration date',
			'text_de'=>'Ablaufdatum',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Versandart',
			'text_en'=>'Shipping',
			'text_de'=>'Versandart',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Versandpauschale - Shipping & Handling',
			'text_en'=>'Freight charges - Shipping & Handling',
			'text_de'=>'Versandpauschale - Shipping & Handling',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Bezahldaten',
			'text_en'=>'payment data',
			'text_de'=>'Bezahldaten',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre bestellten Produkte',
			'text_en'=>'Your ordered products',
			'text_de'=>'Ihre bestellten Produkte',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Bestellbestätigungsemail wurde verschickt.',
			'text_en'=>'Order confirmation email has been sent.',
			'text_de'=>'Bestellbestätigungsemail wurde verschickt.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Es wurde bereits eine Bestellbestätigungsemail versendet',
			'text_en'=>'There an order confirmation email has been sent',
			'text_de'=>'Es wurde bereits eine Bestellbestätigungsemail versendet',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Von Ihrer Konto-Übersicht aus haben Sie die Möglichkeit, Ihre letzten Vorgänge einzusehen und Ihre Benutzerkonto-Daten zu bearbeiten. Wählen Sie dazu einen der untenstehenden Links, um Information anzusehen oder zu bearbeiten.',
			'text_en'=>'From your Account Overview, you have the possibility to view your recent transactions and edit your account information. To do this, one of the links to view or edit information below.',
			'text_de'=>'Von Ihrer Konto-Übersicht aus haben Sie die Möglichkeit, Ihre letzten Vorgänge einzusehen und Ihre Benutzerkonto-Daten zu bearbeiten. Wählen Sie dazu einen der untenstehenden Links, um Information anzusehen oder zu bearbeiten.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Übersicht Bestellungen',
			'text_en'=>'Overview orders',
			'text_de'=>'Übersicht Bestellungen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Daten Bearbeiten',
			'text_en'=>'Edit data',
			'text_de'=>'Daten Bearbeiten',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Zahlungseingangsbestätigungsemail wurde verschickt.',
			'text_en'=>'Receipt confirmation email has been sent.',
			'text_de'=>'Zahlungseingangsbestätigungsemail wurde verschickt.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Zahlungseingang wurde bestätigt',
			'text_en'=>'Payment was confirmed',
			'text_de'=>'Zahlungseingang wurde bestätigt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Versandbestätigungsemail wurde verschickt.',
			'text_en'=>'Shipping confirmation email has been sent.',
			'text_de'=>'Versandbestätigungsemail wurde verschickt.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Es wurde bereits eine Versandbestätigungsemail versendet',
			'text_en'=>'There a shipping confirmation email has been sent',
			'text_de'=>'Es wurde bereits eine Versandbestätigungsemail versendet',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Geld ist eingegangen',
			'text_en'=>'Payment is received',
			'text_de'=>'Geld ist eingegangen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Zusammengestellt',
			'text_en'=>'Package created',
			'text_de'=>'Zusammengestellt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Versendet',
			'text_en'=>'Shipped',
			'text_de'=>'Versendet',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'für',
			'text_en'=>'for',
			'text_de'=>'für',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Status',
			'text_en'=>'Status',
			'text_de'=>'Status',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Der Status Ihrer gewählten Bestellung',
			'text_en'=>'The status of your selected order',
			'text_de'=>'Der Status Ihrer gewählten Bestellung',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre gewählte Bestellung beinhaltet folgende Produkte',
			'text_en'=>'Your selected order includes the following products',
			'text_de'=>'Ihre gewählte Bestellung beinhaltet folgende Produkte',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Produktname',
			'text_en'=>'Product name',
			'text_de'=>'Produktname',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Anz.',
			'text_en'=>'Num.',
			'text_de'=>'Anz.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Einzelpr.',
			'text_en'=>'Price',
			'text_de'=>'Einzelpr.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Gesamtpreis',
			'text_en'=>'Total',
			'text_de'=>'Gesamtpreis',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Zurück zur Übersicht',
			'text_en'=>'Back to Overview',
			'text_de'=>'Zurück zur Übersicht',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ständige Informationen',
			'text_en'=>'Permanent information',
			'text_de'=>'Ständige Informationen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Persönliche Informationen ändern',
			'text_en'=>'Change personal data',
			'text_de'=>'Persönliche Informationen ändern',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihr Warenkorb ist leer, bitte suchen Sie sich etwas schönes in unserem Shop aus.',
			'text_en'=>'Your cart is empty. You may pick something nice from our shop.',
			'text_de'=>'Ihr Warenkorb ist leer, bitte suchen Sie sich etwas schönes in unserem Shop aus.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Details',
			'text_en'=>'Details',
			'text_de'=>'Details',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>' MwSt',
			'text_en'=>' VAT',
			'text_de'=>' MwSt',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Bewertungen',
			'text_en'=>'Reviews',
			'text_de'=>'Bewertungen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Sofort verfügbar.',
			'text_en'=>'Immediately available.',
			'text_de'=>'Sofort verfügbar.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Register',
			'text_en'=>'Register',
			'text_de'=>'Registrieren',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'-',
			'text_en'=>'-',
			'text_de'=>'-',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'WWW',
			'text_en'=>'WWW',
			'text_de'=>'WWW',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Your Registration Data',
			'text_en'=>'Your Registration Data',
			'text_de'=>'Ihre Registrierungsdaten',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Registration Complete',
			'text_en'=>'Registration Complete',
			'text_de'=>'Registrierung abgeschlossen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Account Activated',
			'text_en'=>'Account Activated',
			'text_de'=>'Benutzerkonto aktiviert',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Benutzerkonto reaktivieren?',
			'text_en'=>'Re-activate account?',
			'text_de'=>'Benutzerkonto reaktivieren?',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Activated at',
			'text_en'=>'Activated at',
			'text_de'=>'Aktiviert am',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Forgotten your password?',
			'text_en'=>'Forgotten your password?',
			'text_de'=>'Passwort vergessen?',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Jetzt zahlungspflichtig bestellen',
			'text_en'=>'Order with obligation to pay',
			'text_de'=>'Jetzt zahlungspflichtig bestellen',
		);


		$w[] = array(
			'id'=>$i++,
			'short'=>'We sent you an email with the link to reset your password.',
			'text_en'=>'We sent you an email with the link to reset your password.',
			'text_de'=>'Wir haben Ihnen eine E-Mail zum Zurücksetzen Ihres Passwortes geschickt.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'We sent you an email with your new password.',
			'text_en'=>'We sent you an email with your new password.',
			'text_de'=>'Wir haben Ihnen eine E-Mail mit Ihrem neuen Passwort geschickt.',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Old password',
			'text_en'=>'Old password',
			'text_de'=>'Altes Passwort',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'New password',
			'text_en'=>'New password',
			'text_de'=>'Neues Passwort',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Repeat new password',
			'text_en'=>'Repeat new password',
			'text_de'=>'Neues Passwort wiederholen',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Order Details',
			'text_en'=>'Order Details',
			'text_de'=>'Bestellung - Details',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'Please select',
			'text_en'=>'Please select',
			'text_de'=>'Bitte auswählen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Previous',
			'text_en'=>'Previous',
			'text_de'=>'Zurück',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Last',
			'text_en'=>'Last',
			'text_de'=>'Letzte',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'First',
			'text_en'=>'First',
			'text_de'=>'Erste',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Unit',
			'text_en'=>'Unit',
			'text_de'=>'Einheit',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Leider gibt es für die gewählte Kategorie keine Produkte.',
			'text_en'=>'Unfortunately, there are no products for the selected category.',
			'text_de'=>'Leider gibt es für die gewählte Kategorie keine Produkte.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Preis',
			'text_en'=>'Price',
			'text_de'=>'Preis',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kategorie',
			'text_en'=>'Category',
			'text_de'=>'Kategorie',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Hersteller',
			'text_en'=>'Producer',
			'text_de'=>'Hersteller',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kundenservice',
			'text_en'=>'Customer Service',
			'text_de'=>'Kundenservice',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Größentabelle',
			'text_en'=>'Sizing Chart',
			'text_de'=>'Größentabelle',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Rückgaberecht',
			'text_en'=>'Returns Policy',
			'text_de'=>'Rückgaberecht',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zahlung & Versand',
			'text_en'=>'Payment & Shipping',
			'text_de'=>'Zahlung & Versand',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Rollover Image',
			'text_en'=>'Rollover Image',
			'text_de'=>'Rollover Bild',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'RolloverMediaImage',
			'text_en'=>'Rollover Image',
			'text_de'=>'Rollover Bild',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shoes',
			'text_en'=>'Shoes',
			'text_de'=>'Schuhe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Clothes',
			'text_en'=>'Clothes',
			'text_de'=>'Kleidung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Measurements',
			'text_en'=>'Measurements',
			'text_de'=>'Messungen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Jetzt bei %1s anmelden',
			'text_en'=>'Login now at %1s',
			'text_de'=>'Jetzt bei %1s anmelden',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Jetzt mit %1s bezahlen',
			'text_en'=>'Pay now with %1s',
			'text_de'=>'Jetzt mit %1s bezahlen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zur Startseite',
			'text_en'=>'To the Home',
			'text_de'=>'Zur Startseite',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Beim Newsletter anmelden.',
			'text_en'=>'Signup for newsletter.',
			'text_de'=>'Beim Newsletter anmelden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'bisher',
			'text_en'=>'so far',
			'text_de'=>'bisher',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'jetzt',
			'text_en'=>'now',
			'text_de'=>'jetzt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Sie sparen %1s %2s (%3s%%)',
			'text_en'=>'You save %1s %2s (%3s%%)',
			'text_de'=>'Sie sparen %1s %2s (%3s%%)',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Leider ausverkauft!',
			'text_en'=>'Sold out!',
			'text_de'=>'Leider ausverkauft!',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Dieses Produkt ist leider ausverkauft!',
			'text_en'=>'This product is currently sold out!',
			'text_de'=>'Dieses Produkt ist leider ausverkauft!',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Pfand',
			'text_en'=>'Refund',
			'text_de'=>'Pfand',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Pfand gesamt',
			'text_en'=>'Refund total',
			'text_de'=>'Pfand gesamt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Der Artikel wurde dem Warenkorb hinzugefügt.',
			'text_en'=>'The item has been added to the cart.',
			'text_de'=>'Der Artikel wurde dem Warenkorb hinzugefügt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bitte spezifizieren Sie den Artikel genauer, bevor er dem Warenkorb hinzugefügt werden kann.',
			'text_en'=>'Specify the exact item, before adding it to the cart, please.',
			'text_de'=>'Bitte spezifizieren Sie den Artikel genauer, bevor er dem Warenkorb hinzugefügt werden kann.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kunden, die dieses Produkt gekauft haben, haben auch folgende Produkte gekauft',
			'text_en'=>'Customers, who bought this product, also bought the following products',
			'text_de'=>'Kunden, die dieses Produkt gekauft haben, haben auch folgende Produkte gekauft',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Street Number',
			'text_en'=>'Street Number',
			'text_de'=>'Hausnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Address Line 1',
			'text_en'=>'Address Line 1',
			'text_de'=>'Adresszusatz 1',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Address Line 2',
			'text_en'=>'Address Line 2',
			'text_de'=>'Adresszusatz 2',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zip Code',
			'text_en'=>'Zip Code',
			'text_de'=>'Postleitzahl',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Mobile Number',
			'text_en'=>'Mobile Number',
			'text_de'=>'Mobilrufnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Phone Number',
			'text_en'=>'Phone Number',
			'text_de'=>'Telefonnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Melde dich an, wenn du über die neuesten Waren, Angebote und Aktionen informiert werden möchtest.',
			'text_en'=>'Subscribe now, if you want to be informed about the latest products, offers and promotions.',
			'text_de'=>'Melde dich an, wenn du über die neuesten Waren, Angebote und Aktionen informiert werden möchtest.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Dieser News-Eintrag ist nicht vorhanden.',
			'text_en'=>'This news item is not available.',
			'text_de'=>'Dieser News-Eintrag ist nicht vorhanden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'more',
			'text_en'=>'more',
			'text_de'=>'mehr',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'mehr',
			'text_en'=>'more',
			'text_de'=>'mehr',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsAmountLiquid',
			'text_en'=>'Amount Liquid',
			'text_de'=>'Menge Flüssigkeit',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsAmountPiece',
			'text_en'=>'Amount Piece',
			'text_de'=>'Menge Stück',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsColor',
			'text_en'=>'Color',
			'text_de'=>'Farbe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsSizeClothesGirth',
			'text_en'=>'Size Clothes Girth',
			'text_de'=>'Größe Kleidung Umfang',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsForm',
			'text_en'=>'Form',
			'text_de'=>'Form',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsGeneral',
			'text_en'=>'General',
			'text_de'=>'Allgemein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsMeasureCircumference',
			'text_en'=>'Measure Circumference',
			'text_de'=>'Abmessung Umfang',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsMeasureDepth',
			'text_en'=>'Measure Depth',
			'text_de'=>'Abmessung Tiefe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsMeasureHeight',
			'text_en'=>'Measure Height',
			'text_de'=>'Abmessung Höhe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsMeasureDiameter',
			'text_en'=>'Measure Diameter',
			'text_de'=>'Abmessung Durchmesser',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsMeasureLength',
			'text_en'=>'Measure Length',
			'text_de'=>'Abmessung Länge',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsMeasureWidth',
			'text_en'=>'Measure Width',
			'text_de'=>'Abmessung Breite',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsSize',
			'text_en'=>'Size',
			'text_de'=>'Größe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsSizeClothesBraSize',
			'text_en'=>'Size Clothes Bra Cup',
			'text_de'=>'Größe Kleidung BH-Körpchen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsSizeClothesBraCup',
			'text_en'=>'Size Clothes Bra Cup',
			'text_de'=>'Größe Kleidung BH-Größe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsSizeClothesGeneral',
			'text_en'=>'Size Clothes General',
			'text_de'=>'Größe Kleidung Allgemein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsSizeShoes',
			'text_en'=>'Size Clothes Shoes',
			'text_de'=>'Größe Kleidung Schuhe',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Default_Model_ProductOptionsWeight',
			'text_en'=>'Weight',
			'text_de'=>'Gewicht',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Configurate your Product Options',
			'text_en'=>'Configurate your Product Options',
			'text_de'=>'Konfigurieren Sie die Produkt-Optionen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The password is forceable in',
			'text_en'=>'The password is forceable in',
			'text_de'=>'Das Passwort ist durchsetzbar in',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'1 year',
			'text_en'=>'1 year',
			'text_de'=>'ein Jahr',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'years',
			'text_en'=>'years',
			'text_de'=>'Jahre',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'1 month',
			'text_en'=>'1 month',
			'text_de'=>'ein Monat',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'months',
			'text_en'=>'months',
			'text_de'=>'Monate',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'1 day',
			'text_en'=>'1 day',
			'text_de'=>'ein Tag',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'days',
			'text_en'=>'days',
			'text_de'=>'Tage',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'1 hour',
			'text_en'=>'1 hour',
			'text_de'=>'eine Stunde',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'hours',
			'text_en'=>'hours',
			'text_de'=>'Stunden',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'1 minute',
			'text_en'=>'1 minute',
			'text_de'=>'eine Minute',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'minutes',
			'text_en'=>'minutes',
			'text_de'=>'Minuten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'1 second',
			'text_en'=>'1 second',
			'text_de'=>'eine Sekunde',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'seconds',
			'text_en'=>'seconds',
			'text_de'=>'Sekunden',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'less than one second',
			'text_en'=>'less than one second',
			'text_de'=>'weniger als eine Sekunde',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Wiederherstellen',
			'text_en'=>'Restore',
			'text_de'=>'Wiederherstellen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bank account iban',
			'text_en'=>'Bank Account IBAN',
			'text_de'=>'Bankkonto IBAN',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bank account bic',
			'text_en'=>'Bank Account BIC',
			'text_de'=>'Bankkonto BIC',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'So nicht anders deklariert sind alle Preise inklusive gesetzlicher Mehrwertsteuern und zzgl. evtl. %1s zu verstehen.',
			'text_en'=>'If not stated in another way, all prices are to be understood including value added tax and plus %1s.',
			'text_de'=>'So nicht anders deklariert sind alle Preise inklusive gesetzlicher Mehrwertsteuern und zzgl. evtl. %1s zu verstehen.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Versandkosten',
			'text_en'=>'shipping costs',
			'text_de'=>'Versandkosten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'TinymceTemplate',
			'text_en'=>'HTML-Template',
			'text_de'=>'HTML-Vorlage',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Einträge',
			'text_en'=>'Entries',
			'text_de'=>'Einträge',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Author',
			'text_en'=>'Author',
			'text_de'=>'Autor',
			'text_es'=>'Autor',
			'text_fr'=>'Auteur',
			'text_ru'=>'Автор',
			'text_bg'=>'Автор',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'A record matching \'%s\' was found',
			'text_en'=>'A record matching \'%s\' was found',
			'text_de'=>'Dieser Wert \'%s\' ist bereits vorhanden',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The request data',
			'text_en'=>'The request data',
			'text_de'=>'Die Anfragedaten',
			'text_es'=>'Los datos de la solicitud',
			'text_fr'=>'Les données de demande',
			'text_ru'=>'Данные запроса',
			'text_bg'=>'Данните за искане',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Please note the following dates that were entered in the contact form.',
			'text_en'=>'Please note the following dates that were entered in the contact form.',
			'text_de'=>'Bitte beachten Sie die folgenden Daten, die in das Kontaktformular eingegeben wurden.',
			'text_es'=>'Tenga en cuenta las siguientes fechas que se introdujeron en el formulario de contacto.',
			'text_fr'=>'S\'il vous plaît noter les dates suivantes qui ont été saisies dans le formulaire de contact.',
			'text_ru'=>'Пожалуйста, обратите внимание на следующие даты, которые были введены в контактной форме.',
			'text_bg'=>'Моля, обърнете внимание на следните дати, които са били вписани в контактната форма.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Den Gutschein gibt es leider nicht',
			'text_en'=>'This coupon does not exist',
			'text_de'=>'Den Gutschein gibt es leider nicht',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>"Login with Facebook",
			'text_en'=>"Login with Facebook",
			'text_de'=>"Einloggen über Facebook",
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ShippingCountry',
			'text_en'=>'ShippingCountry',
			'text_de'=>'Versandkosten Länder',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Costs',
			'text_en'=>'Costs',
			'text_de'=>'Kosten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Consistent costs',
			'text_en'=>'Consistent costs',
			'text_de'=>'Einheitliche Kosten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Choosen country already used for another model.',
			'text_en'=>'Choosen country already used for another model.',
			'text_de'=>'Ausgewähltes Land wird bereits in einem anderen Model verwendet.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Show %1s',
			'text_en'=>'Show %1s',
			'text_de'=>'Zeige %1s',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Width of %1s',
			'text_en'=>'Width of %1s',
			'text_de'=>'Breite von %1s',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Download',
			'text_en'=>'Download',
			'text_de'=>'Herunterladen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Type',
			'text_en'=>'Type',
			'text_de'=>'Typ',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Export as',
			'text_en'=>'Export as',
			'text_de'=>'Exportieren als',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Export',
			'text_en'=>'Export',
			'text_de'=>'Exportieren',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Exportieren',
			'text_en'=>'Export',
			'text_de'=>'Exportieren',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'As of: %1s',
			'text_en'=>'As of: %1s',
			'text_de'=>'Stand: %1s',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Enable Account',
			'text_en'=>'Enable Account',
			'text_de'=>'Benutzerkonto Reaktivieren',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Your account has been re-activated.',
			'text_en'=>'Your account has been re-activated.',
			'text_de'=>'Ihr Benutzerkonto wurde reaktiviert.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'If there is an account with that login existing, you\'ll retrive an email with instructions on how to re-activate your account.',
			'text_en'=>'If there is an account with that login existing, you\'ll retrive an email with instructions on how to re-activate your account.',
			'text_de'=>'Wenn es ein Benutzerkonto mit diesem Namen gibt, wird Ihnen eine E-Mail mit Anweisungen zur Reaktivierung zugesandt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Unfortunately, no icons could be found in the designated icon locations.',
			'text_en'=>'Unfortunately, no icons could be found in the designated icon locations.',
			'text_de'=>'Leider konnten keine Symbole in den dafür vorgesehenen Symbol-Orten gefunden werden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Successfully generated CSS for %1s %2s icons.',
			'text_en'=>'Successfully generated CSS for %1s %2s icons.',
			'text_de'=>'Erfolgreich generiertes CSS für %1s %2s Symbole.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Cancel',
			'text_en'=>'Cancel',
			'text_de'=>'Abbrechen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'This will affect one record. Are you sure?',
			'text_en'=>'This will affect one record. Are you sure?',
			'text_de'=>'Dies wird einen Datensatz beeinflussen. Sind Sie sicher?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'This will affect some records. Are you sure?',
			'text_en'=>'This will affect some records. Are you sure?',
			'text_de'=>'Dies wird mehere Datensätze beeinflussen. Sind Sie sicher?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'This record will be permanently deleted and cannot be recovered. Are you sure?',
			'text_en'=>'This record will be permanently deleted and cannot be recovered. Are you sure?',
			'text_de'=>'Dieser Datensatz wird unwiderruflich gelöscht und kann nicht wiederhergestellt werden. Sind Sie sicher?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'These records will be permanently deleted and cannot be recovered. Are you sure?',
			'text_en'=>'These records will be permanently deleted and cannot be recovered. Are you sure?',
			'text_de'=>'Diese Datensätze werden unwiderruflich gelöscht und können nicht wiederhergestellt werden. Sind Sie sicher?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Selection',
			'text_en'=>'Selection',
			'text_de'=>'Auswahl',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'You need to select one or more items, when using this function.',
			'text_en'=>'You need to select one or more items, when using this function.',
			'text_de'=>'Sie müssen ein oder mehrere Datensätze auswählen, um diese Funktion zu nutzen.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'It\'s not allowed to select an item, when using this function.',
			'text_en'=>'It\'s not allowed to select an item, when using this function.',
			'text_de'=>'Um diese Funktion zu nutzen, darf kein Datensatz ausgewählt sein.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'You need to select one item, when using this function.',
			'text_en'=>'You need to select one item, when using this function.',
			'text_de'=>'Sie müssen einen Datensatz auswählen, um diese Funktion nutzen zu können.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'EmailTemplateReplacement',
			'text_en'=>'Email - Template - Replacement',
			'text_de'=>'E-Mail - Vorlagen - Ersetzung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Before you proceed',
			'text_en'=>'Before you proceed',
			'text_de'=>'Bevor Sie fortfahren',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'In order to make this application work, the system needs to be set up.Make sure that you understand the following before you proceed:',
			'text_en'=>'In order to make this application work, the system needs to be set up.Make sure that you understand the following before you proceed:',
			'text_de'=>'Um diese Anwendung zum Funktionieren zu bekommen, muss das System einen Installations-Prozess durchlaufen. Stellen Sie sicher, dass Sie das Folgenden verstanden haben, bevor Sie fortfahren werden:',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Latitude',
			'text_en'=>'Latitude',
			'text_de'=>'Breitengrad',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Longitude',
			'text_en'=>'Longitude',
			'text_de'=>'Längengrad',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Copyright',
			'text_en'=>'Copyright',
			'text_de'=>'Urheberrecht',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Manage ProductOptions',
			'text_en'=>'Manage Product-Options',
			'text_de'=>'Verwalte Produkt-Optionen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'"%1s" has been generated.',
			'text_en'=>'"%1s" has been generated.',
			'text_de'=>'"%1s" wurde generiert.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Telephone',
			'text_en'=>'Telephone',
			'text_de'=>'Telefon',
			'text_fr'=>'Téléphone',
			'text_ru'=>'Телефон',
			'text_bg'=>'Телефон',
			'text_es'=>'Teléfono',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Firma',
			'text_en'=>'Company',
			'text_de'=>'Firma',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Company',
			'text_en'=>'Company',
			'text_de'=>'Firma',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Slide to the left',
			'text_en'=>'Slide to the left',
			'text_de'=>'Schiebe nach links',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Slide to the right',
			'text_en'=>'Slide to the right',
			'text_de'=>'Schiebe nach rechts',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Customer',
			'text_en'=>'Customer',
			'text_de'=>'Kunde',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Client',
			'text_en'=>'Client',
			'text_de'=>'Auftraggeber',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Counter',
			'text_en'=>'Counter',
			'text_de'=>'Zähler',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Examples',
			'text_en'=>'Examples',
			'text_de'=>'Beispiele',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Example',
			'text_en'=>'Example',
			'text_de'=>'Beispiel',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Address',
			'text_en'=>'Address',
			'text_de'=>'Adresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Adresse',
			'text_en'=>'Address',
			'text_de'=>'Adresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Show',
			'text_en'=>'Show',
			'text_de'=>'Anzeigen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Notizen',
			'text_en'=>'Notes',
			'text_de'=>'Notizen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Privacy Policy',
			'text_en'=>'Privacy Policy',
			'text_de'=>'Datenschutz',
			'text_fr'=>'Politique de Confidentialité',
			'text_ru'=>'Политика Конфиденциальности',
			'text_bg'=>'Декларация за Поверителност',
			'text_es'=>'Política de Privacidad',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Instructions of Cancellation',
			'text_en'=>'Instructions of Cancellation',
			'text_de'=>'Widerrufsbelehrung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Return Policy',
			'text_en'=>'Return Policy',
			'text_de'=>'Rückgaberecht',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Legal Issues',
			'text_en'=>'Legal Issues',
			'text_de'=>'Rechtliche Hinweise',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'PaymentServices',
			'text_en'=>'Payment Services',
			'text_de'=>'Bezahlmethoden',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Percent',
			'text_en'=>'Percent',
			'text_de'=>'Prozent',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Fixed',
			'text_en'=>'Fixed',
			'text_de'=>'Feststehend',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Cant be null',
			'text_en'=>'Cant be null',
			'text_de'=>'Kann nicht Null sein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Has internal review',
			'text_en'=>'Has internal review',
			'text_de'=>'Hat interne Überprüfung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Class name',
			'text_en'=>'Class-Name',
			'text_de'=>'Klassen-name',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Need bank account infos',
			'text_en'=>'Need bank account infos',
			'text_de'=>'Benötigt bankkontoinformationen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Need creditcard infos',
			'text_en'=>'Need creditcard infos',
			'text_de'=>'Benötigt Kreditenkarteninformationen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Has payment after order',
			'text_en'=>'Has payment after order',
			'text_de'=>'Hat Zahlung nach Bestellung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Publish datetime',
			'text_en'=>'Publish datetime',
			'text_de'=>'Veröffentlichungsdatum',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Leider haben wir aktuell keine News.',
			'text_en'=>'Sorry, there are no news at the moment.',
			'text_de'=>'Leider haben wir aktuell keine News.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'News nicht vorhanden oder nicht gefunden.',
			'text_en'=>'News not available or not found.',
			'text_de'=>'News nicht vorhanden oder nicht gefunden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'The media (ID: %1s) is not type of an image and can not be cropped.',
			'text_en'=>'The media (ID: %1s) is not type of an image and can not be cropped.',
			'text_de'=>'Das Medium (ID: %1s) ist nicht vom Typ Bild und kann nicht zugeschnitten werden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Media needs to be specified.',
			'text_en'=>'Media needs to be specified.',
			'text_de'=>'Das Medium muss spezifiziert werden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'empty',
			'text_en'=>'empty',
			'text_de'=>'leer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop PaymentServices',
			'text_en'=>'Shop PaymentServices',
			'text_de'=>'Shop Versandkosten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre gewählte stornierte Bestellung beinhaltet',
			'text_en'=>'Your chosen canceled order includes',
			'text_de'=>'Ihre gewählte stornierte Bestellung beinhaltet',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Aktionen für die stornierte Bestellung',
			'text_en'=>'Actions for the canceled order',
			'text_de'=>'Aktionen für die stornierte Bestellung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kunden, die diese Produkte gekauft haben, haben auch folgende Produkte gekauft',
			'text_en'=>'Customers who bought this, also bought the following products',
			'text_de'=>'Kunden, die diese Produkte gekauft haben, haben auch folgende Produkte gekauft',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihr Gutschein beträgt nun noch',
			'text_en'=>'Your coupon has now still a value of',
			'text_de'=>'Ihr Gutschein beträgt nun noch',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Eingelöster Gutschein',
			'text_en'=>'Used coupon',
			'text_de'=>'Eingelöster Gutschein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Der Gutschein beträgt nun noch',
			'text_en'=>'The coupon has now still a value of',
			'text_de'=>'Der Gutschein beträgt nun noch',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Versandadresse',
			'text_en'=>'Shipping address',
			'text_de'=>'Versandadresse',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Confirm',
			'text_en'=>'Confirm',
			'text_de'=>'Bestätigen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing Number',
			'text_en'=>'Billing Number',
			'text_de'=>'Rechnungsnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Billing number',
			'text_en'=>'Billing number',
			'text_de'=>'Rechnungsnummer',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Select File',
			'text_en'=>'Select File',
			'text_de'=>'Datei auswählen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'A media file, which is required for a product, has not been uploaded yet.',
			'text_en'=>'A media file, which is required for a product, has not been uploaded yet.',
			'text_de'=>'Eine Mediadatei, die für ein Produkt benötigt wird, wurde noch nicht hochgeladen.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shipping costs',
			'text_en'=>'Shipping costs',
			'text_de'=>'Versandkosten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Payment costs',
			'text_en'=>'Payment costs',
			'text_de'=>'Bezahlmethode - Gebühren',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Delivery company',
			'text_en'=>'Delivery company',
			'text_de'=>'Liefer - Firma',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Need media upload',
			'text_en'=>'Need media upload',
			'text_de'=>'Benötigt Dateiupload',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Datei auswählen',
			'text_en'=>'Select File',
			'text_de'=>'Datei auswählen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Weiter',
			'text_en'=>'Forward',
			'text_de'=>'Weiter',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Medium bearbeiten',
			'text_en'=>'Edit Media',
			'text_de'=>'Medium bearbeiten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Medium hochladen',
			'text_en'=>'Upload Media',
			'text_de'=>'Medium hochladen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ein Medium, welches für ein Produkt benötigt wird, wurde noch nicht hochgeladen.',
			'text_en'=>'A media file, which is required for a product, has not been uploaded yet.',
			'text_de'=>'Ein Medium, welches für ein Produkt benötigt wird, wurde noch nicht hochgeladen.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Medium angehangen',
			'text_en'=>'Media appended',
			'text_de'=>'Medium angehangen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Medium herunterladen',
			'text_en'=>'Download Media',
			'text_de'=>'Medium herunterladen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Warten auf Geldeingang',
			'text_en'=>'Wait for payment',
			'text_de'=>'Warten auf Geldeingang',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Gesamtpr.',
			'text_en'=>'Total price',
			'text_de'=>'Gesamtpr.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Den Gutschein gibt es leider nicht.',
			'text_en'=>'Sorry, this coupon doesn\'t exist.',
			'text_de'=>'Den Gutschein gibt es leider nicht.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Product M2n Tag',
			'text_en'=>'Tag to product',
			'text_de'=>'Tag zum Produkt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Lieferung bis',
			'text_en'=>'Delivery to',
			'text_de'=>'Lieferung bis',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ShopTag',
			'text_en'=>'Product-Tags',
			'text_de'=>'Produkt-Tags',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop Tag',
			'text_en'=>'Product-Tags',
			'text_de'=>'Produkt-Tags',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Holiday',
			'text_en'=>'Holiday',
			'text_de'=>'Feiertage',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ShopHoliday',
			'text_en'=>'Holiday',
			'text_de'=>'Feiertage',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop Holiday',
			'text_en'=>'Holiday',
			'text_de'=>'Feiertage',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Delivery days',
			'text_en'=>'Days until delivered',
			'text_de'=>'Tage bis geliefert',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'SmallMediaImage',
			'text_en'=>'Small Image',
			'text_de'=>'Kleines Bild',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Uparam',
			'text_en'=>'Translated Parameter',
			'text_de'=>'Übersetzter Parameter',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Param',
			'text_en'=>'Parameter',
			'text_de'=>'Parameter',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ParamTranslator',
			'text_en'=>'Parameter-Translation',
			'text_de'=>'Parameter-Übersetzung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'TranslatorParam',
			'text_en'=>'Parameter-Translation',
			'text_de'=>'Parameter-Übersetzung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Translate Param',
			'text_en'=>'Parameter-Translations',
			'text_de'=>'Parameter-Übersetzungen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Translated Param',
			'text_en'=>'Translated Parameter',
			'text_de'=>'Übersetzter Parameter',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Uresource',
			'text_en'=>'Translated Resource',
			'text_de'=>'Übersetzte Resource',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ResourceTranslator',
			'text_en'=>'Resource-Translation',
			'text_de'=>'Resourcen-Übersetzung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'TranslatorResource',
			'text_en'=>'Resource-Translation',
			'text_de'=>'Resourcen-Übersetzung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Translate Resource',
			'text_en'=>'Resource-Translations',
			'text_de'=>'Resourcen-Übersetzungen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Translated Resource',
			'text_en'=>'Translated Resource',
			'text_de'=>'Übersetzte Resource',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'What do you see?',
			'text_en'=>'What do you see?',
			'text_de'=>'Was sehen Sie?',
			'text_fr'=>'Que voyez-vous?',
			'text_ru'=>'Что вы видите?',
			'text_bg'=>'Какво виждаш?',
			'text_es'=>'Que ve usted?',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'In stock',
			'text_en'=>'In Stock',
			'text_de'=>'Im Lager',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Do not change stock on placed order.',
			'text_en'=>'Do not change stock on placed order.',
			'text_de'=>'Lagerbestand mit eingehender Bestellung nicht ändern.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'ShopProductInformation',
			'text_en'=>'Product-Information Pages',
			'text_de'=>'Produktinformations-Seiten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'CSS background-attachment',
			'text_en'=>'CSS background-attachment',
			'text_de'=>'CSS background-attachment',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'CSS background-size',
			'text_en'=>'CSS background-size',
			'text_de'=>'CSS background-size',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Detail',
			'text_en'=>'Detail',
			'text_de'=>'Detail',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Weiterlesen',
			'text_en'=>'Read more',
			'text_de'=>'Weiterlesen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Warenkorb',
			'text_en'=>'Cart',
			'text_de'=>'Warenkorb',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Home',
			'text_en'=>'Home',
			'text_de'=>'Home',
			'text_fr'=>'Home',
			'text_ru'=>'Home',
			'text_bg'=>'Home',
			'text_es'=>'Home',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Willkommen in unserem Shop',
			'text_en'=>'Welcome in our shop',
			'text_de'=>'Willkommen in unserem Shop',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kategorien',
			'text_en'=>'Categories',
			'text_de'=>'Kategorien',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Service Hotline',
			'text_en'=>'Service Hotline',
			'text_de'=>'Service Hotline',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Telefonische Unterstützung und Beratung unter:',
			'text_en'=>'Telephone assistance and inquiries:',
			'text_de'=>'Telefonische Unterstützung und Beratung unter:',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Shop Service',
			'text_en'=>'Shop Service',
			'text_de'=>'Shop Service',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Versand und Zahlungsbedingungen',
			'text_en'=>'Shipping and payment',
			'text_de'=>'Versand und Zahlungsbedingungen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Information',
			'text_en'=>'Information',
			'text_de'=>'Information',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Melden Sie sich an, wenn Sie über die neuesten Waren, Angebote und Aktionen informiert werden möchten.',
			'text_en'=>'Sign in if you want to be informed on the latest products, offers and promotions.',
			'text_de'=>'Melden Sie sich an, wenn Sie über die neuesten Waren, Angebote und Aktionen informiert werden möchten.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Datenschutz',
			'text_en'=>'Privacy Policy',
			'text_de'=>'Datenschutz',
			'text_fr'=>'Politique de Confidentialité',
			'text_ru'=>'Политика Конфиденциальности',
			'text_bg'=>'Декларация за Поверителност',
			'text_es'=>'Política de Privacidad',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Widerrufsbelehrung',
			'text_en'=>'Revocation instruction',
			'text_de'=>'Widerrufsbelehrung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Richtlinie akzeptieren',
			'text_en'=>'Accept policy',
			'text_de'=>'Richtlinie akzeptieren',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zahlungspflichtig bestellen',
			'text_en'=>'Order with obligation to pay',
			'text_de'=>'Zahlungspflichtig bestellen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zurück zum Gutschein',
			'text_en'=>'Back to coupon',
			'text_de'=>'Zurück zum Gutschein',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bitte wählen Sie eine Zahlungsmethode aus.',
			'text_en'=>'Select a payment method, please.',
			'text_de'=>'Bitte wählen Sie eine Zahlungsmethode aus.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Kauf abschließen',
			'text_en'=>'Confirm order',
			'text_de'=>'Kauf abschließen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Informationen zum Anbieter',
			'text_en'=>'Information of the provider',
			'text_de'=>'Informationen zum Anbieter',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Allgemeine Geschäftsbedingungen',
			'text_en'=>'Terms and conditions',
			'text_de'=>'Allgemeine Geschäftsbedingungen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Datenschutzerklärung',
			'text_en'=>'Privacy policy',
			'text_de'=>'Datenschutzerklärung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'privacy policy',
			'text_en'=>'privacy policy',
			'text_de'=>'Datenschutzerklärung',
			'text_fr'=>'Politique de Confidentialité',
			'text_ru'=>'Политика Конфиденциальности',
			'text_bg'=>'Декларация за Поверителност',
			'text_es'=>'Política de Privacidad',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ich bin über die %1s informiert und erkenne diese an.',
			'text_en'=>'I am aware of the %1s and accept them.',
			'text_de'=>'Ich bin über die %1s informiert und erkenne diese an.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bitte bestätigen Sie, dass Sie über die Datenschutzerklärung informiert sind und diese anerkennen.',
			'text_en'=>'Please confirm that you are aware of the privacy statements and such parties.',
			'text_de'=>'Bitte bestätigen Sie, dass Sie über die Datenschutzerklärung informiert sind und diese anerkennen.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Weiter zum Überblick Ihrer Bestellung',
			'text_en'=>'Continue to overview of your order',
			'text_de'=>'Weiter zum Überblick Ihrer Bestellung',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bezahlung vornehmen',
			'text_en'=>'Make payment',
			'text_de'=>'Bezahlung vornehmen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Um Ihren Einkauf abzuschließen, sollten Sie jetzt bezahlen',
			'text_en'=>'To complete your purchase, you should pay now',
			'text_de'=>'Um Ihren Einkauf abzuschließen, sollten Sie jetzt bezahlen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Benutzerkonto',
			'text_en'=>'user account',
			'text_de'=>'Benutzerkonto',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Vielen Dank für Ihre Bestellung!',
			'text_en'=>'Thank you for your order!',
			'text_de'=>'Vielen Dank für Ihre Bestellung!',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Grundpreis ist %1s %2s / %3s',
			'text_en'=>'Base price is %1s %2s / %3s',
			'text_de'=>'Grundpreis ist %1s %2s / %3s',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Produkt Information',
			'text_en'=>'Product information',
			'text_de'=>'Produkt Information',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Leider ergab die Suche keine Treffer.',
			'text_en'=>'Sorry, the search returned no results.',
			'text_de'=>'Leider ergab die Suche keine Treffer.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ihre Bewertung "%1s" für "%2s" wurde gespeichert. Danke für Ihre Bewertung.',
			'text_en'=>'Your rating "%1s" for "%2s" has been saved. Thank you for your rating.',
			'text_de'=>'Ihre Bewertung "%1s" für "%2s" wurde gespeichert. Danke für Ihre Bewertung.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Weitere Kategorien',
			'text_en'=>'Other Categories',
			'text_de'=>'Weitere Kategorien',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Zur Kategorie',
			'text_en'=>'To the Category',
			'text_de'=>'Zur Kategorie',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bezahlen',
			'text_en'=>'Pay',
			'text_de'=>'Bezahlen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Vielen Dank für Ihre Bestellung!',
			'text_en'=>'Thank you for your order!',
			'text_de'=>'Vielen Dank für Ihre Bestellung!',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Profil',
			'text_en'=>'Profile',
			'text_de'=>'Profil',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Datum',
			'text_en'=>'Date',
			'text_de'=>'Datum',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Jetzt Bezahlen',
			'text_en'=>'Pay Now',
			'text_de'=>'Jetzt Bezahlen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'In den Warenkorb hinzufügen',
			'text_en'=>'Add to cart',
			'text_de'=>'In den Warenkorb hinzufügen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Nochmal bestellen',
			'text_en'=>'Order again',
			'text_de'=>'Nochmal bestellen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Ansehen',
			'text_en'=>'Show',
			'text_de'=>'Ansehen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Bestellung ist bestätigt',
			'text_en'=>'Order is accepted',
			'text_de'=>'Bestellung ist bestätigt',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Die Summe ist inkl. gesetzlicher Mehrwertsteuern und evtl. Versandkosten zu verstehen.',
			'text_en'=>'The sum is to be understood incl. VAT and any delivery costs.',
			'text_de'=>'Die Summe ist inkl. gesetzlicher Mehrwertsteuern und evtl. Versandkosten zu verstehen.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Redirect back to that form after saveing.',
			'text_en'=>'Redirect back to that form after saveing.',
			'text_de'=>'Nach dem Speichervorgang zu diesem Formular zurück umleiten.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Upload',
			'text_en'=>'Upload',
			'text_de'=>'Hochladen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Upload & Install',
			'text_en'=>'Upload & Install',
			'text_de'=>'Hochladen & Installieren',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Wait',
			'text_en'=>'Wait',
			'text_de'=>'Warten',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Please wait. This takes a little while.',
			'text_en'=>'Please wait. This takes a little while.',
			'text_de'=>'Bitte warten. Dies dauert eine Weile.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Sorry, only letters (a-z) and numbers (0-9) are allowed.',
			'text_en'=>'Sorry, only letters (a-z) and numbers (0-9) are allowed.',
			'text_de'=>'Leider sind nur Buchstaben (a-z) und Zahlen (0-9) erlaubt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Sorry, only letters (a-z, A-Z and ä, Ä, ö, Ö, ü, Ü, é, É, è, È, ê, Ê, í, Í, ì, Ì, î, Î, ñ, Ñ, ó, Ó, ò, Ò, ô, Ô, ß, ú, Ù, ù, Ù, û, Û), numbers (0-9), signs (-, _, \') and whitespaces in combination with words are allowed.',
			'text_en'=>'Sorry, only letters (a-z, A-Z and ä, Ä, ö, Ö, ü, Ü, é, É, è, È, ê, Ê, í, Í, ì, Ì, î, Î, ñ, Ñ, ó, Ó, ò, Ò, ô, Ô, ß, ú, Ù, ù, Ù, û, Û), numbers (0-9), signs (-, _, \') and whitespaces in combination with words are allowed.',
			'text_de'=>'Leider sind nur Buchstaben (a-z, A-Z and ä, Ä, ö, Ö, ü, Ü, é, É, è, È, ê, Ê, í, Í, ì, Ì, î, Î, ñ, Ñ, ó, Ó, ò, Ò, ô, Ô, ß, ú, Ù, ù, Ù, û, Û), Zahlen (0-9), Zeichen (-, _, \') und Leerzeichen in Kombination mit Wörtern erlaubt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Subdirectories have to be deleted first.',
			'text_en'=>'Subdirectories have to be deleted first.',
			'text_de'=>'Unterordner müssen zuerst gelöscht werden.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Replace',
			'text_en'=>'Replace',
			'text_de'=>'Ersetzen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'FixMedias',
			'text_en'=>'Fix Medias',
			'text_de'=>'Fix Medien',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Delete All',
			'text_en'=>'Delete All',
			'text_de'=>'Alle Löschen',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Canonical',
			'text_en'=>'Canonical',
			'text_de'=>'Canonical',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Canonical for Language',
			'text_en'=>'Canonical for Language',
			'text_de'=>'Canonical für Sprache',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Material',
			'text_en'=>'Material',
			'text_de'=>'Material',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Als Verwendungszweck geben Sie bitte "%s" an.',
			'text_en'=>'Please use "%s" as reference.',
			'text_de'=>'Als Verwendungszweck geben Sie bitte "%s" an.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Basic Configuration',
			'text_en'=>'Basic Configuration',
			'text_de'=>'Grundkonfiguration',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Plugins',
			'text_en'=>'Plugins',
			'text_de'=>'Plugins',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Plugin',
			'text_en'=>'Plugin',
			'text_de'=>'Plugin',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Install Plugin',
			'text_en'=>'Install Plugin',
			'text_de'=>'Plugin installieren',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Plugin is faulty.',
			'text_en'=>'Plugin is faulty.',
			'text_de'=>'Plugin ist fehlerhaft.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Version',
			'text_en'=>'Version',
			'text_de'=>'Version',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Robots',
			'text_en'=>'Robots',
			'text_de'=>'Robots',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'SiteRights-Parameter "%1s" not yet set.',
			'text_en'=>'SiteRights-Parameter "%1s" not yet set.',
			'text_de'=>'SiteRights-Parameter "%1s" noch nicht gesetzt.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'SiteRights',
			'text_en'=>'SiteRights',
			'text_de'=>'SiteRights',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Email text plain',
			'text_en'=>'E-mail - Text (plain)',
			'text_de'=>'E-Mail - Text (plain)',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Email text html',
			'text_en'=>'E-mail - Text (HTML)',
			'text_de'=>'E-Mail - Text (HTML)',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Website text html',
			'text_en'=>'Website - Text (HTML)',
			'text_de'=>'Webseiten - Text (HTML)',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Robot verification failed, please try again.',
			'text_en'=>'Robot verification failed, please try again.',
			'text_de'=>'Roboterprüfung fehlgeschlagen, bitte versuchen Sie es erneut.',
			'text_es'=>'Error de la verificación del robot. Vuelve a intentarlo.',
			'text_fr'=>'La vérification du robot a échoué, réessayez.',
			'text_ru'=>'Не удалось выполнить проверку робота, повторите попытку.',
			'text_bg'=>'Проверката на роботите не бе успешна. Моля, опитайте отново.',
		);
		$w[] = array(
			'id'=>$i++,
			'short'=>'Please click on the reCAPTCHA box.',
			'text_en'=>'Please click on the reCAPTCHA box.',
			'text_de'=>'Bitte klicken Sie auf die reCAPTCHA Box.',
			'text_es'=>'Por favor, haga clic en la casilla reCAPTCHA.',
			'text_fr'=>'Cliquez sur la boîte reCAPTCHA.',
			'text_ru'=>'Нажмите на поле reCAPTCHA.',
			'text_bg'=>'Моля, кликнете върху полето reCAPTCHA.',
		);





//		$w[] = array(
//			'id'=>$i++,
//			'short'=>'',
//			'text_en'=>'',
//			'text_de'=>'',
//		);

		/**
		 * return
		 */
		return $w;
	}
}