<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/TranslationUpdater.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: TranslationUpdater.php 27 2019-05-29 12:10:00Z dp $
 */

/**
 *
 *
 * L8M_TranslationUpdater
 *
 *
 */
class L8M_TranslationUpdater
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_withVersionUpdate;
	private $_currentEnvironment;
	private $_getImportsAfter;
	private $_lastProductionImport;
	private $_lastProductionExport;
	private $_lastTranslatorImport;
	private $_lastTranslatorExport;
	private $_ftpServerConnection;
	private $_ftpBasePathToConnect;
	private $_localTranslationUpdaterInfoFile;

	/**
	 * Factory method to crate a translation updater instance
	 *
     * @return TranslationUpdater_Instance
	 */
	public static function factory($withVersionUpdate = FALSE)
	{
		return new L8M_TranslationUpdater($withVersionUpdate);
	}

    /**
     * __construct
     *
     * @return void
     *
     */
	public function __construct($withVersionUpdate)
	{
		// Set execution environment general settings
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 0);
		date_default_timezone_set('Europe/Berlin');

		$this->_withVersionUpdate = $withVersionUpdate;

		if(!$withVersionUpdate) {
			// Read Environment.ini file and set the current environment
			$environmentListFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'environment.ini';
			$currentEnvironment = L8M_Environment::getInstance($environmentListFile)->getEnvironment();

			// $currentEnvironment = 'production';

			if($currentEnvironment == 'production') {
				$this->_currentEnvironment = $currentEnvironment;
			} else {
				$this->_currentEnvironment = 'translator';
			}

			// Get the URLs for production
			$urls = parse_ini_file($environmentListFile);
			$productionUrls = $urls['production'];

			// Translator FTP connection
			$ftpServerName = 'transfer.tsd-int.com';
			$ftpUserName = 'HAHN_media';
			$ftpPassword = '26MQak03';
			$this->_ftpServerConnection = L8M_FtpTools::factory($ftpServerName, $ftpUserName, $ftpPassword);

			if(!$this->_ftpServerConnection) throw new L8M_Exception('Translator FTP could not be connected.');

			// Setup Translator FTP paths for import and export if not existing
			foreach($productionUrls as $url) {
				$ftpTransactionsInfoFile = DIRECTORY_SEPARATOR . $url . DIRECTORY_SEPARATOR . 'info.ini';

				$this->_ftpServerConnection->createFtpDirectory(DIRECTORY_SEPARATOR . $url);
				$this->_ftpServerConnection->createFtpDirectory(DIRECTORY_SEPARATOR . $url . DIRECTORY_SEPARATOR . 'import');
				$this->_ftpServerConnection->createFtpDirectory(DIRECTORY_SEPARATOR . $url . DIRECTORY_SEPARATOR . 'export');
				if(!$this->_ftpServerConnection->checkIfFtpFileExists($ftpTransactionsInfoFile)) {
					$defaultTranslationInfo = "[production]\n\nimport = " . date('YmdHis') . "\nexport = " . date('YmdHis') . "\n\n[translator]\n\nimport = " . date('YmdHis') . "\nexport = " . date('YmdHis');
					$this->_ftpServerConnection->createFtpFile($ftpTransactionsInfoFile, $defaultTranslationInfo);
				}
			}

			$this->_ftpBasePathToConnect = DIRECTORY_SEPARATOR . ((in_array($_SERVER['SERVER_NAME'], $productionUrls)) ? $_SERVER['SERVER_NAME'] : $productionUrls[0]) . DIRECTORY_SEPARATOR;

			// Copy translator info from FTP to local
			$localTranslationUpdaterPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'translationUpdater' . DIRECTORY_SEPARATOR;
			$this->_localTranslationUpdaterInfoFile = $localTranslationUpdaterPath . 'info.ini';

			$this->_ftpServerConnection->downloadFileFromFtp($ftpTransactionsInfoFile, $this->_localTranslationUpdaterInfoFile);
			$translationUpdaterInfo = parse_ini_file($this->_localTranslationUpdaterInfoFile, TRUE);

			$this->_lastProductionImport = $translationUpdaterInfo['production']['import'];
			$this->_lastProductionExport = $translationUpdaterInfo['production']['export'];
			$this->_lastTranslatorImport = $translationUpdaterInfo['translator']['import'];
			$this->_lastTranslatorExport = $translationUpdaterInfo['translator']['export'];

			if($currentEnvironment == 'production') {
				$this->_getImportsAfter = $translationUpdaterInfo['production']['export'];
			} else {
				$this->_getImportsAfter = $translationUpdaterInfo['translator']['export'];
			}
		}
    }

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Import Translations
	 */

	public function importTranslations() {
		if($this->_withVersionUpdate) {
			$lastImportTime = $this->importIntoDatabase();
		} else {
			// Set paths and file search
			$localImportPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'translationUpdater' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;
			$ftpPathToConnect = $this->_ftpBasePathToConnect;

			$currentEnvironment = $this->_currentEnvironment;

			if($currentEnvironment != 'production')  {
				$ftpPathToConnect .= 'export' . DIRECTORY_SEPARATOR;

				$fileGlobString = $localImportPath . 'liveExport_*.xml';
			} else {
				$ftpPathToConnect .= 'import' . DIRECTORY_SEPARATOR;

				$fileGlobString = $localImportPath . 'translatorExport_*.xml';
			}

			$oldFiles = glob($fileGlobString);
			foreach($oldFiles as $file) {
				unlink($file);
			}

			// Download available files from FTP
			$this->_ftpServerConnection->downloadDirectoryFromFtp($ftpPathToConnect, $localImportPath);
			$filesToImport = glob($fileGlobString);

			if(count($filesToImport)) {
				$fileToImport = $filesToImport[0];
				$importFileName = str_replace('.xml', '', basename($fileToImport));
				$importFileNameExplosion = explode('_', $importFileName);
				$timestamp = $importFileNameExplosion[count($importFileNameExplosion) - 1];

				$translationUpdaterInfo = parse_ini_file($this->_localTranslationUpdaterInfoFile, TRUE);
				$lastImportTime = $translationUpdaterInfo[$currentEnvironment]['import'];

				if($lastImportTime < $timestamp) {
					$lastImportTime = $this->importIntoDatabase();

					// Update local info.ini
					if($currentEnvironment == 'production') {
						$iniUpdate = "[production]\n\nimport = " . $lastImportTime . "\nexport = " . $this->_lastProductionExport . "\n\n[translator]\n\nimport = " . $this->_lastTranslatorImport . "\nexport = " . $this->_lastProductionExport;
					} else {
						$iniUpdate = "[production]\n\nimport = " . $this->_lastProductionImport . "\nexport = " . $this->_lastProductionExport . "\n\n[translator]\n\nimport = " . $lastImportTime . "\nexport = " . $this->_lastTranslatorExport;
					}

					$fp = fopen($this->_localTranslationUpdaterInfoFile, 'w');
					fwrite($fp, $iniUpdate);
				}
			}

			foreach($filesToImport as $file) {
				unlink($file);
			}

			// Upload local info.ini to FTP
			$this->_ftpServerConnection->reconnectToFtp();
			$this->_ftpServerConnection->uploadFileToFtp($this->_localTranslationUpdaterInfoFile, $this->_ftpBasePathToConnect . 'info.ini');
			$this->_ftpServerConnection->closeFtpConnection();
		}

		return TRUE;
	}

	public function exportTranslations() {
		$localExportPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'translationUpdater' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR;
		$ftpPathToConnect = $this->_ftpBasePathToConnect;

		$currentEnvironment = $this->_currentEnvironment;

		if($currentEnvironment != 'production') {
			$ftpPathToConnect .= 'import' . DIRECTORY_SEPARATOR;

			$fileGlobString = $localExportPath . 'translatorExport_*.xml';
		} else {
			$ftpPathToConnect .= 'export' . DIRECTORY_SEPARATOR;

			$fileGlobString = $localExportPath . 'liveExport_*.xml';
		}

		$oldFiles = glob($fileGlobString);
		foreach($oldFiles as $file) {
			unlink($file);
		}

		$this->_ftpServerConnection->deleteDirectoryFromFtp($ftpPathToConnect);

		$lastExportTime = $this->exportFromDatabase();

		// Update local info.ini
		if($currentEnvironment == 'production') {
			$iniUpdate = "[production]\n\nimport = " . $this->_lastProductionImport . "\nexport = " . $lastExportTime . "\n\n[translator]\n\nimport = " . $this->_lastTranslatorImport . "\nexport = " . $this->_lastProductionExport;
		} else {
			$iniUpdate = "[production]\n\nimport = " . $this->_lastProductionImport . "\nexport = " . $this->_lastProductionExport . "\n\n[translator]\n\nimport = " . $this->_lastTranslatorImport . "\nexport = " . $lastExportTime;
		}

		$fp = fopen($this->_localTranslationUpdaterInfoFile, 'w');
		fwrite($fp, $iniUpdate);

		$filesToUpload = glob($localExportPath . '*_' . $lastExportTime . '.xml');
		foreach($filesToUpload as $file) {
			$fileName = basename($file);

			$this->_ftpServerConnection->uploadFileToFtp($file, $ftpPathToConnect . $fileName);

			unlink($file);
		}

		// Upload local info.ini to FTP
		$this->_ftpServerConnection->reconnectToFtp();
		$this->_ftpServerConnection->uploadFileToFtp($this->_localTranslationUpdaterInfoFile, $this->_ftpBasePathToConnect . 'info.ini');
		$this->_ftpServerConnection->closeFtpConnection();

		return TRUE;
	}

	/**
	 * Reads the Backup file and creates array to generate XML file.
	 *
	 * @return array
	 */

	private function getFilteredData()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$path = BASE_PATH . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "translationUpdater" . DIRECTORY_SEPARATOR;

		if (!is_dir($path)) {
			mkdir($path, 0777, TRUE);
		}

		$exportedData = $this->createDataToParse();
		$timestamp = $this->_getImportsAfter;

		$filteredArray = array();
		$i = 0;

		foreach ($exportedData as $outerKey => $val) {
			$tableName = explode("_", $outerKey);
			foreach ($tableName as &$table) {
				$table = ucfirst($table);
			}
			if(substr_count($outerKey, "_") != 0) {
				array_pop($tableName);
				$modelName = "Default_Model_" . implode("", $tableName);

				if(!class_exists($modelName)) continue;

				foreach ($val as $innerKey => $innervalue) {
					if(strtotime($innervalue['created_at']) >= strtotime($timestamp)) {
						$status = 'add';
					} else {
						$status = 'edit';
					}

					$model = $modelName::getModelById($innervalue['id']);

					if($model) {
						$uniqueCols = array();
						if(isset($model->short)) {
							$uniqueCols['short'] = $model->short;
						}

						if(isset($model->resource)) {
							$uniqueCols['resource'] = $model->resource;
						}

						if(isset($model->file_name)) {
							$uniqueCols['file_name'] = $model->file_name;
						}

						if($modelName == 'Default_Model_Media') {
							$uniqueCols['link'] = $model->getLink();
						} else
						if($modelName == 'Default_Model_Action') {
							$uniqueCols['link'] = str_replace('.', '/', $model->resource);
						} else
						if($modelName == 'Default_Model_Translator') {
							$uniqueCols['link'] = str_replace('.', '/', $model->url);
						}

						foreach ($innervalue as $key => $value) {
							if($key != 'id' && $key != 'lang' && $key != 'created_at' && $key != 'updated_at' && $key != 'deleted_at') {
								$filteredArray[$i]['model_name'] = $modelName;
								$filteredArray[$i]['id'] = $innervalue['id'];
								$filteredArray[$i]['language'] = strtolower($innervalue['lang']);
								$filteredArray[$i]['status'] = $status;
								$filteredArray[$i]['column'] = $key;
								$filteredArray[$i]['value'] = $value;
								$filteredArray[$i]['resource'] = '';
								$filteredArray[$i]['short'] = '';
								$filteredArray[$i]['file_name'] = '';
								$filteredArray[$i]['link'] = '';

								$filteredArray[$i] = array_merge(
									$filteredArray[$i],
									$uniqueCols
								);

								$tableArray = $db->describeTable($outerKey);
								$filteredArray[$i]['length'] = $tableArray[$key]['LENGTH'];
								$i++;
							}
						}

					}
				}
			}
		}

		return $filteredArray;
	}

	/**
	 * Creates the Backup file.
	 *
	 * @return void
	 */

	private function createDataToParse()
	{
		//Get list of all the tables in DB
		$db = Zend_Db_Table::getDefaultAdapter();
		$allTables = $db->listTables();

		$dataToParse = array();

		foreach ($allTables as $tableName) {
			if(strpos($tableName, 'translation') !== FALSE) {
				$stmt = $db->query(
					'SELECT * FROM '.$tableName
				);
				$dataToParse[$tableName] = $stmt->fetchAll();
			}
		}

		return $dataToParse;
	}

	/**
	 * Reads the array and generates the XML file. If flag is TRUE, single file and if FALSE, language specific files
	 *
	 * @return void
	 */

	private function generateXml($array)
	{
		$timestamp = date('YmdHis');

		$exportPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'translationUpdater' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR;
		//For all changes, create one all.xml file
		if($this->_currentEnvironment == 'production') {
			if (!is_dir($exportPath)) {
				mkdir($exportPath, 0777, TRUE);
			}

			$fileName = $exportPath . 'liveExport_' . $timestamp . '.xml';
			$xmlElementAll = new SimpleXMLElement('<?xml version=\'1.0\'?><translator_xml_export generated_at="' . $timestamp . '"></translator_xml_export>');
			$this->arrayToXml($array,$xmlElementAll);
			$xml_file = $xmlElementAll->asXML($fileName);
		} else
		if($this->_currentEnvironment == 'translator') {
			//Generate language specific XML files
			if (!is_dir($exportPath)) {
				mkdir($exportPath, 0777, TRUE);
			}

			$languagesArray = array();
			$xmls = array();

			foreach ($array as $key => $value) {
				$language = strtolower($value['language']);
				if(!in_array($language, $languagesArray)) {
					$languagesArray[] = $language;
				}
				$xmls[$language][] = $value;
			}
			foreach ($languagesArray as $lang) {
				$fileName = $exportPath . 'translatorExport_' . $lang . '_' . $timestamp . '.xml';
				$xmlElement = new SimpleXMLElement('<?xml version=\'1.0\'?><translator_xml_export generated_at="' . $timestamp . '"></translator_xml_export>');
				$this->arrayToXml($xmls[$lang],$xmlElement);
				$xml_file = $xmlElement->asXML($fileName);
			}
		}

		return $timestamp;
	}

	/**
	 * Assigns properties to XML object.
	 *
	 * @return void
	 */
	function arrayToXml($array, &$xmlElement)
	{
		foreach($array as $key => $value) {
			if(is_array($value) && is_numeric($key)) {
				$subnode = $xmlElement->addChild("item");
				$subnode->addAttribute('model_name',$value['model_name']);
				$subnode->addAttribute('id',$value['id']);
				$subnode->addAttribute('short',$value['short']);
				$subnode->addAttribute('resource',$value['resource']);
				$subnode->addAttribute('file_name',$value['file_name']);
				$subnode->addAttribute('column',$value['column']);
				$subnode->addAttribute('length',$value['length']);
				$subnode->addAttribute('language',$value['language']);
				$subnode->addAttribute('status',$value['status']);
				$subnode->addAttribute('value',$value['value']);
				$subnode->addAttribute('link',$value['link']);

				$this->arrayToXml($value, $subnode);
			}
		}
	}

	/**
	 * Reads the XML file and adds/updates/deletes data into/from Database.
	 *
	 * @return void
	 */

	function updateDatabase($newArray)
	{
		$errorTranslations = array();
		foreach ($newArray as $key => $value) {
			$modelName = $value['model_name'];
			$language = $value['lang'];

			$db = Zend_Db_Table::getDefaultAdapter();

			$tableModelName = substr($modelName,14);
			$modelNameArray = preg_split('/(?=[A-Z])/', $tableModelName);
			foreach ($modelNameArray as $k => &$name) {
				if($name == '')
					unset($modelNameArray[$k]);
				else
					$name = strtolower($name);
			}
			$tableName = implode('_',$modelNameArray) . '_translation';

			unset($value['model_name']);
			$translationStatus = $value['status'];
			unset($value['status']);
			unset($value['link']);

			$uniqueColumn = '';

			//Get the unique identifier column
			if(isset($value['resource']) && $value['resource'] != '') {
				$uniqueColumn = 'resource';
			} else if(isset($value['short']) && $value['short'] != '') {
				$uniqueColumn = 'short';
			} else if(isset($value['file_name']) && $value['file_name'] != '') {
				$uniqueColumn = 'file_name';
			}

			//Get the id from the Base table
			if($uniqueColumn == '') {
				$errorTranslations[] = $modelName . ' : ' . $uniqueColumn . ' : ' . $value[$uniqueColumn]; continue;
			} else {
				$translationFor = $modelName::getModelByColumn($uniqueColumn, $value[$uniqueColumn]);
			}

			//If no entry is found in Base table, continue
			if(!$translationFor) {
				try {
					$newModelEntry = new $modelName();
					$newModelEntry->$uniqueColumn = $value[$uniqueColumn];
					$newModelEntry->save();

					$translationFor = $newModelEntry;
				} catch(Exception $e) {
					$errorTranslations[] = $modelName . ' : ' . $uniqueColumn . ' : ' . $value[$uniqueColumn]; continue;
				}
			}

			$translationId = $translationFor->id;
			unset($value['resource']);
			unset($value['short']);
			unset($value['file_name']);

			unset($value['id']);
			$value['updated_at'] = date('Y-m-d H:i:s');
			$whereArray = array(
				'id = ?' => $translationId,
				'lang = ?' => $language
			);
			$updatedRows = $db->update($tableName, $value, $whereArray);
			if(!$updatedRows) {
				$value['id'] = $translationId;
				$value['lang'] = $language;
				$value['created_at'] = $value['updated_at'] = date('Y-m-d H:i:s');
				$db->insert($tableName, $value);
			}
		}
	}

	/**
	 * Reads the array and merges the values to create single record
	 *
	 * @return array
	 */

	function mergeArray($xml)
	{
		$newArray = array();
		$i = 0;
		$j = 0;
		foreach ($xml as $key=>$value) {
			foreach ((array)$value as $k => $v) {
				if(!class_exists($v['model_name'])) continue;

				foreach($v as $k1 => &$v1) {
					if($v1 == '' && $v['model_name'] != 'Default_Model_Translator')
						$v1 = NULL;
					else
						$v1 = htmlspecialchars_decode($v1);
				}

				$model = $v['model_name'];
				$id = $v['id'];
				$language = $v['language'];
				$column = $v['column'];
				$columnValue = $v['value'];
				if(empty($newArray)) {
					$newArray[$i]['model_name'] = $model;
					$newArray[$i]['id'] = $id;
					$newArray[$i][$column] = $columnValue;
					$newArray[$i]['lang'] = $language;
					$newArray[$i]['status'] = $v['status'];
					$newArray[$i]['short'] = $v['short'];
					$newArray[$i]['file_name'] = $v['file_name'];
					$newArray[$i]['resource'] = $v['resource'];
					//$newArray[$i]['link'] = $v['link'];

				} else
				if($newArray[$j-1]['model_name'] == $model && $newArray[$j-1]['id'] == $id && $newArray[$j-1]['lang'] == $language) {
					$newArray[$i][$column] = $columnValue;
					$j--;
				} else {
					$newArray[$j]['model_name'] = $model;
					$newArray[$j]['id'] = $id;
					$newArray[$j][$column] = $columnValue;
					$newArray[$j]['lang'] = $language;
					$newArray[$j]['status'] = $v['status'];
					$newArray[$j]['short'] = $v['short'];
					$newArray[$j]['file_name'] = $v['file_name'];
					$newArray[$j]['resource'] = $v['resource'];
					//$newArray[$i]['link'] = $v['link'];
				}
			}
			$i = $j;
			$j++;
		}
		return $newArray;
	}

    /**
     * Export Databse
     *
     * @return void
     *
     */
	public function exportFromDatabase()
	{
		//Get the changes in Database
		$allDataArray = $this->getFilteredData();

		//To create single XML file for whole Database, set flag = TRUE.
		// To create language specific xml files, set flag = FALSE
		$timestamp = $this->generateXml($allDataArray);

		return $timestamp;
	}

	/**
	 * Reads the all.xml file and updates the Database.
	 *
	 * @return void
	 */

	public function importIntoDatabase()
	{
		if($this->_withVersionUpdate) {
			$fileToParse = glob(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'self.update' . DIRECTORY_SEPARATOR . 'translation' . DIRECTORY_SEPARATOR . 'updates.xml')[0];
			$xml = simplexml_load_file($fileToParse);

			$newArray = $this->mergeArray($xml);
			$this->updateDatabase($newArray);
		} else {
			$importPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'translationUpdater' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

			if($this->_currentEnvironment == 'production') {
				$filesToParse = glob(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'translationUpdater' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'translatorExport_*.xml');

				foreach($filesToParse as $file) {
					if(is_file($file)) {
						$xml = simplexml_load_file($file);

						$generated_time = $xml['generated_at'];
						unset($xml['generated_at']);

						$newArray = $this->mergeArray($xml);
						$this->updateDatabase($newArray);
					}
				}
			} else
			if($this->_currentEnvironment == 'translator') {
				$fileToParse = glob(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'translationUpdater' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'liveExport_*.xml')[0];
				$xml = simplexml_load_file($fileToParse);

				$generated_time = $xml['generated_at'];
				unset($xml['generated_at']);

				$newArray = $this->mergeArray($xml);
				$this->updateDatabase($newArray);
			}

			return date('YmdHis');
		}
	}

	/**
	 * Reads the language specific XML files and updates the Database.
	 *
	 * @return void
	 */

	public function importFromLanguageAction()
	{
		$importPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'translator' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR;

		foreach (new DirectoryIterator($importPath) as $file) {
			if ($file->isFile()) {
				$file = $file->__toString();
				$fileName = $importPath.$file;
				$xml = simplexml_load_file($fileName);
				$generated_time = $xml['generated_at'];
				unset($xml['generated_at']);
				$newArray = $this->mergeArray($xml);
				$this->updateDatabase($newArray);
			}
		}
	}
}



