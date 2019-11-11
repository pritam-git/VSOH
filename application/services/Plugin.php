<?php

/**
 * L8M
 *
 *
 * @filesource /application/services/Plugin.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Plugin.php 356 2015-05-18 12:57:50Z nm $
 */

/**
 *
 *
 * Default_Service_Plugin
 *
 *
 */
class Default_Service_Plugin
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
	 * @var array of L8M_Exception
	 */
	protected static $_exceptions = array();

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 * Create an Default_Service_Plugin-Object
	 *
	 * @param Zend_Form_Element_File $formElement
	 * @return Default_Service_Plugin
	 */
	public static function fromFormElementFile($element) {
		if (!($element instanceof Zend_Form_Element_File)) {
			throw new L8M_Exception('Element needs to be an instance of Zend_Form_Element_File.');
		}

		/**
		 * an error has occured
		 */
		if (!$element->isUploaded()) {
			return NULL;
		}

		/**
		 * perform upload to temporary directory
		 */
		$element->receive();

		return Default_Service_Plugin::fromFileTransferAdapter(
			$element->getTransferAdapter(),
			$element->getName()
		);
	}



	/**
	 * Attempts to create a Default_Model_Plugin instance from the specified
	 * Zend_File_Transfer_Adapter_Abstract instance and the specified
	 * identifier.
	 *
	 * @param  Zend_File_Transfer_Adapter_Abstract $adapter
	 * @param  string $identifier
	 * @return Default_Model_Plugin
	 */
	public static function fromFileTransferAdapter($adapter = NULL, $identifier = NULL)
	{
		$returnValue = FALSE;

		if (!$adapter ||
			!($adapter instanceof Zend_File_Transfer_Adapter_Abstract)) {

			throw new L8M_Exception('Adapter needs to be specified as a Zend_File_Transfer_Adapter_Abstract instance.');
		}
		if (!$identifier ||
			!is_string($identifier)) {

			throw new L8M_Exception('Identifier needs to be specified as a string.');
		}

		/**
		 * retrieve file info from transfer adapter
		 */
		$fileInfo = $adapter->getFileInfo($identifier);
		$fileInfo = $fileInfo[$identifier];

		if (!L8M_Mime::isZip($fileInfo['type'])) {
			@unlink($fileInfo['tmp_name']);
			self::_addException(new L8M_Exception('Plugin needs to be a ZIP-file.'), TRUE);
		}

		if (!self::hasExceptions()) {
			if (substr($fileInfo['name'], -4) != '.zip') {
				@unlink($fileInfo['tmp_name']);
				self::_addException(new L8M_Exception('Plugin needs to be a ZIP-file.'), TRUE);
			}

			if (!self::hasExceptions()) {
				/**
				 * prepare unzip
				 */
				$pluginName = substr($fileInfo['name'], 0, strlen($fileInfo['name']) - 4);
				$pluginPath = Default_Model_Plugin::getUploadPath() . DIRECTORY_SEPARATOR . $pluginName;

				if (file_exists($pluginPath)) {
					self::_deleteDirectoryRecursive($pluginPath);
				}
				@mkdir($pluginPath);

				/**
				 * unzip
				 */
				$zip = new ZipArchive;
				if ($zip->open($fileInfo['tmp_name']) !== TRUE) {
					@unlink($fileInfo['tmp_name']);
					self::_addException(new L8M_Exception('An error occured handling the ZIP-file.'), TRUE);
				}

				if (!self::hasExceptions()) {
					$zip->extractTo($pluginPath);
					$zip->close();
					@unlink($fileInfo['tmp_name']);

					/**
					 * check plugin
					 */
					$pluginIni = $pluginPath . DIRECTORY_SEPARATOR . 'plugin.ini';
					if (!file_exists($pluginIni)) {
						if (!file_exists($pluginPath . DIRECTORY_SEPARATOR . $pluginName . DIRECTORY_SEPARATOR . 'plugin.ini')) {
							self::_deleteDirectoryRecursive($pluginPath);
							self::_addException(new L8M_Exception('Plugin definition is missing.'), TRUE);
						} else {
							$usedPluginPath = $pluginPath . DIRECTORY_SEPARATOR . $pluginName;
							$pluginIni = $usedPluginPath . DIRECTORY_SEPARATOR . 'plugin.ini';
						}
					} else {
						$usedPluginPath = $pluginPath;
					}

					if (!self::hasExceptions()) {
						try {
							require_once('Zend' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'Ini.php');
							$pluginConfigIni = new Zend_Config_Ini($pluginIni, 'plugin');
						} catch (Zend_Config_Exception $e) {
							self::_deleteDirectoryRecursive($pluginPath);
							self::_addException($e, TRUE);
						}

						if (!self::hasExceptions()) {
							$pluginConfigArray = $pluginConfigIni->toArray();

							if (!array_key_exists('name', $pluginConfigArray) ||
								$pluginName != $pluginConfigArray['name']) {

								self::_deleteDirectoryRecursive($pluginPath);
								self::_addException(new L8M_Exception('Plugin definition failure.'), TRUE);
							}

							if (!self::hasExceptions()) {
								if (!self::_checkConfigArray($pluginConfigArray)) {
									self::_deleteDirectoryRecursive($pluginPath);
									self::_addException(new L8M_Exception('There are errors in the plugin definition.'), TRUE);
								}

								if (!self::hasExceptions()) {
									if ($pluginConfigArray['l8m']['system']['type'] != L8M_Config::getOption('l8m.system.type')) {
										self::_deleteDirectoryRecursive($pluginPath);
										self::_addException(new L8M_Exception('Plugin not suitable for that system type.'), TRUE);
									}

									if (!self::hasExceptions()) {
										if ($pluginConfigArray['l8m']['system']['version'] != L8M_Config::getOption('l8m.system.version')) {
											self::_deleteDirectoryRecursive($pluginPath);
											self::_addException(new L8M_Exception('Plugin not suitable for that system version.'), TRUE);
										}

										/**
										 * prepare data
										 */
										$data = array(
											'short'=>L8M_Library::createShort('Default_Model_Plugin', 'short', $pluginConfigArray['name'], 45),
											'name'=>$pluginConfigArray['name'],
											'version'=>$pluginConfigArray['version'],
											'description'=>$pluginConfigArray['description'],
											'author'=>$pluginConfigArray['author'],
										);

										/**
										 * check dependencies
										 */
										if (!array_key_exists('needPlugins', $pluginConfigArray)) {
											$pluginConfigArray['needPlugins'] = array();
										}

										foreach ($pluginConfigArray['needPlugins'] as $neededPlugin) {
											if (!$neededPlugin) {
												self::_deleteDirectoryRecursive($pluginPath);
												self::_addException(new L8M_Exception('Failure while handling plugin dependencies.'), TRUE);
											}

											if (!self::hasExceptions()) {
												$checkPluginModel = Default_Model_Plugin::createQuery('Default_Model_Plugin', 'm')
													->addWhere('m.name = ? ', $neededPlugin)
													->limit(1)
													->execute()
													->getFirst()
												;

												if (!$checkPluginModel) {
													self::_deleteDirectoryRecursive($pluginPath);
													self::_addException(new L8M_Exception('Plugin dependence: "' . $neededPlugin . '" is missing.'), TRUE);
												} else {
													$checkPluginModel->free(TRUE);
												}
											}
										}

										if (!self::hasExceptions()) {

											/**
											 * search for plugin
											 */
											$pluginModel = Default_Model_Plugin::createQuery('Default_Model_Plugin', 'm')
												->addWhere('m.name = ? ', $data['name'])
												->addWhere('m.version = ? ', $data['version'])
												->addWhere('m.author = ? ', $data['author'])
												->limit(1)
												->execute()
												->getFirst()
											;

											if ($pluginModel) {
												self::_deleteDirectoryRecursive($pluginPath);
												$pluginModel->free(TRUE);
												self::_addException('Plugin already installed.');
											} else {

												/**
												 * install plugin
												 */
												$pluginModel = FALSE;
												if (self::_install($usedPluginPath)) {

													/**
													 * save plugin infos
													 */
													$pluginModel = new Default_Model_Plugin();
													$pluginModel->merge($data);
													$pluginModel->save();
													$returnValue = $pluginModel;
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $returnValue;
	}

	/**
	 * Delete directory recursive.
	 *
	 * @param string $dir
	 * @param boolean $includingDir
	 */
	private static function _deleteDirectoryRecursive($dir, $includingDir = TRUE) {
		$di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
		$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($ri as $file) {
			if ($file->isDir()) {
				@rmdir($file);
			} else {
				@unlink($file);
			}
		}

		if ($includingDir) {
			rmdir($dir);
		}
	}

	/**
	 *
	 * @return boolean
	 */
	private static function _install($usedPluginPath) {
		$returnValue = FALSE;

		if (self::_checkDirectories($usedPluginPath)) {

			$addModels = FALSE;
			$schemaFiles = array();

			$di = new RecursiveDirectoryIterator($usedPluginPath, FilesystemIterator::SKIP_DOTS);
			$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::SELF_FIRST);
			foreach ($ri as $file) {
				$pluginFile = $file->getPathname();
				$systemFile = BASE_PATH . substr($pluginFile, strlen($usedPluginPath));

				if ($file->isDir()) {
					if (!file_exists($systemFile) &&
						substr($file->getFilename(), 0, 1) != '.') {

						@mkdir($systemFile);
					}
				} else {
					if (substr($file->getFilename(), 0, 1) != '.') {
						@copy($pluginFile, $systemFile);
					}

					if (L8M_Config::getOption('doctrine.options.yamlPath') == dirname($systemFile)) {
						$addModels = TRUE;
						$schemaFiles[] = $systemFile;
					}
				}
			}

			if ($addModels) {

				$oldModelNames = array();

				$directoryIterator = new DirectoryIterator(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'models');
				foreach($directoryIterator as $file) {
					/* @var $file DirectoryIterator */
					if ($file->isFile() &&
						preg_match('/^(.+)\.php$/', $file->getFilename(), $match)) {

						/**
						 * retrieve model name
						 */
						$oldModelNames[] = 'Default_Model_' . $match[1];
					}
				}

				/**
				 * importSchemaOptions
				 */
				$importSchemaOptions = L8M_Config::getOption('doctrine.options.builder');

				/**
				 * modelsPath
				*/
				$modelsPath = L8M_Config::getOption('doctrine.options.modelsPath');

				foreach ($schemaFiles as $schemaFile) {

					/**
					 * importSchema
					 */
					$importSchema = new Doctrine_Import_Schema();
					$importSchema->setOptions($importSchemaOptions);

					try {
						$importSchema->importSchema($schemaFile, 'yml', $modelsPath);
					} catch (Doctrine_Exception $exception) {
						throw $exception;
					}

				}

				$newModelNames = array();

				$directoryIterator = new DirectoryIterator(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'models');
				foreach($directoryIterator as $file) {
					/* @var $file DirectoryIterator */
					if ($file->isFile() &&
						preg_match('/^(.+)\.php$/', $file->getFilename(), $match) &&
						!in_array('Default_Model_' . $match[1], $oldModelNames)) {

						/**
						 * retrieve model name
						 */
						$newModelNames[] = 'Default_Model_' . $match[1];
					}
				}

				foreach ($newModelNames as $newModelName) {
					/**
					 * create tables in database using models found in modelPath
					 */
					try {
						Doctrine_Core::createTablesFromArray(array($newModelName));
					} catch (Doctrine_Exception $exception) {
						throw $exception;
					}
				}
			}

			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 *
	 * @return boolean
	 */
	private static function _checkDirectories($usedPluginPath) {
		$returnValue = TRUE;

		$di = new RecursiveDirectoryIterator($usedPluginPath, FilesystemIterator::SKIP_DOTS);
		$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::SELF_FIRST);
		foreach ($ri as $file) {
			$pluginFile = $file->getPathname();
			$systemFile = BASE_PATH . substr($pluginFile, strlen($usedPluginPath));

			if ($file->isDir()) {
				if (!file_exists($systemFile)) {
					$testDir = dirname($systemFile . DIRECTORY_SEPARATOR . '..');
					if (file_exists($testDir) &&
						!is_writeable($testDir)) {

						self::_addException('Not writable: "' . $testDir . '"');
						$returnValue = FALSE;
					}
				}
			} else {
				if (file_exists($systemFile) &&
					!is_writeable($systemFile)) {

					self::_addException('Not writable: "' . $systemFile . '"');
					$returnValue = FALSE;
				} else {
					$testDir = dirname($systemFile);
					if (file_exists($testDir) &&
						!is_writeable($testDir)) {

						$this->_addException('Not writable: "' . $testDir . '"');
						$returnValue = FALSE;
					}
				}
			}
		}

		return $returnValue;
	}

	/**
	 *
	 * @param array $pluginConfigArray
	 * @return boolean
	 */
	private static function _checkConfigArray($pluginConfigArray) {
		$returnValue = FALSE;

		if (array_key_exists('l8m', $pluginConfigArray) &&
			array_key_exists('system', $pluginConfigArray['l8m']) &&
			array_key_exists('type', $pluginConfigArray['l8m']['system']) &&
			array_key_exists('version', $pluginConfigArray['l8m']['system'])) {

			if (array_key_exists('name', $pluginConfigArray) &&
				$pluginConfigArray['name'] &&
				array_key_exists('version', $pluginConfigArray) &&
				$pluginConfigArray['version'] &&
				array_key_exists('author', $pluginConfigArray) &&
				$pluginConfigArray['author'] &&
				array_key_exists('description', $pluginConfigArray)) {

				$returnValue = TRUE;
			}
		}

		return $returnValue;
	}

	/**
	 * Adds Error to internal error handler.
	 *
	 * @param Exception|string $message
	 * @param boolean $isException
	 */
	private static function _addException($message = NULL, $isException = FALSE) {
		if ($isException) {
			self::$_exceptions[] = $message;
		} else {
			self::$_exceptions[] = new L8M_Exception($message);
		}
	}

	/**
	 * Retuns boolean of error state.
	 *
	 * @return boolean
	 */
	public static function hasExceptions() {
		$returnValue = FALSE;
		if (count(self::$_exceptions)) {
			$returnValue = TRUE;
		}
		return $returnValue;
	}

	/**
	 * Retuns stack of errors.
	 *
	 * @return array
	 */
	public static function getExceptions() {
		$returnValue = self::$_exceptions;
		self::$_exceptions = array();

		return $returnValue;
	}
}