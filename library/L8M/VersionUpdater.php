<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/VersionUpdater.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: VersionUpdater.php 27 2019-05-29 12:10:00Z dp $
 */

/**
 *
 *
 * L8M_VersionUpdater
 *
 *
 */
class L8M_VersionUpdater
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_versionUpdateInfo;
	private $_environment;
	private $_remotePath;
	private $_updateDirectories = array('application', 'data', 'library', 'public');
	private $_updatesList = FALSE;
	private $_remoteVersionUpdaterLink = "/version-updater";
	private $_remoteVersionUpdaterToken = "c94f1bab826d8c554e8ae622d8471dbb";

	/**
	 * Factory method to crate a version updater instance
	 *
     * @return VersionUpdater_Instance
	 */
	public static function factory($environment, $remotePath = FALSE)
	{
		return new L8M_VersionUpdater($environment, $remotePath);
	}

    /**
     * __construct
     *
     * @return void
     *
     */
	public function __construct($environment, $remotePath)
	{
		$this->_environment = $environment;
		$this->_remotePath = $remotePath;
    }

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Check valid update request
	 *
	 * @return bool
	 */
	public function remoteDataExists()
	{
		if(!$this->_remotePath) return $this->_remotePath;

		if($this->_environment === L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
			return file_exists($this->_remotePath . '.zip');
		} else {
			return file_exists($this->_remotePath);
		}
	}

	/**
	 * Check and create update package
	 *
	 * @return bool||array
	 */
	public function createUpdatePackage()
	{
		if($this->_environment === L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
			$pathToDelete = $this->unpackRemotePackage();
		}

		//Compare files
		$fileComparisonStatus = $this->compareFiles();

		if(isset($pathToDelete) && ($pathToDelete !== FALSE) && file_exists($pathToDelete)) {
			$this->recursiveDelete($pathToDelete, TRUE);
		}

		// $this->updateLocalVersionInfo();

		return $fileComparisonStatus;
	}

	/**
	 * Get updates list
	 *
	 * @return bool||array
	 */
	public function getUpdatesList()
	{
		if(!$this->_updatesList && $this->_remotePath == FALSE) {
			$upOne = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp');

			$updatePath = $upOne . DIRECTORY_SEPARATOR . 'self.update' . DIRECTORY_SEPARATOR;
			$zipFile = $upOne . DIRECTORY_SEPARATOR . 'self.update.zip';

			if(!file_exists($zipFile)) {
				return FALSE;
			}

			if (!is_dir($updatePath)) {
				mkdir($updatePath, 0777, TRUE);
			} else {
				$this->recursiveDelete($updatePath);
			}

			if($this->extractZip($zipFile, $updatePath)) {
				//Parse changes file
				$fileChanges = parse_ini_file($updatePath . 'changes.ini', TRUE);

				$this->_updatesList = $fileChanges;

				$sqlDataArray = array();

				$sqlPath = $updatePath . 'sql' . DIRECTORY_SEPARATOR . 'updates.sql';

				//If there is an SQL file
				if(file_exists($sqlPath)) {
					$sqlContents = file_get_contents($sqlPath);
					if(strlen($sqlContents)) {
						$sqlContentsExplosion = explode(');', $sqlContents);

						//Get list of tables to add/update
						foreach($sqlContentsExplosion as $sqlContentPart) {
							$possibleTableName = $this->getStringBetween($sqlContentPart, 'INSERT INTO `', '` (');

							if(strlen(trim($possibleTableName))) {
								$sqlDataArray[] = $possibleTableName;
							}
						}
					}
				}

				if(count($sqlDataArray) > 0) {
					$this->_updatesList = array_merge(
						$this->_updatesList,
						array(
							'data' => $sqlDataArray
						)
					);
				}

				$this->recursiveDelete($updatePath, TRUE);
			} else {
				throw new L8M_Exception('L8M_VersionUpdater::getUpdatesList : Could not extract update package.');
			}
		}
		return $this->_updatesList;
	}

	/**
	 * Check and create update package
	 *
	 * @return bool
	 * @throws L8M_Exception
	 */
	public function updateLocalPath($action = 'self-update', $calledFromRemote = FALSE)
	{
		$upOne = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp');

		if($calledFromRemote) {
			$returnData = array(
				'success' => FALSE
			);
		}

		if($action == 'self-update') {
			$pathToUpdate = BASE_PATH;

			$updatePackage = $upOne . DIRECTORY_SEPARATOR . 'self.update.zip';

			$unzipPath = $upOne . DIRECTORY_SEPARATOR . 'self.update';
		} else
		if($action == 'dump-update') {
			$pathToUpdate = $this->unpackRemotePackage();
			$updateFor = $this->getRemoteServerName($pathToUpdate);

			$updatePackage = $pathToUpdate . '.update.zip';

			$unzipPath = $pathToUpdate . '.update';
		} else
		if($action == 'roll-back') {
			$pathToUpdate = BASE_PATH;

			$updatePackage = $upOne . DIRECTORY_SEPARATOR . 'backup.zip';

			$unzipPath = $upOne . DIRECTORY_SEPARATOR . 'backup';
		} else {
			throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Unknown action ' . $action . '. Cannot execute');
		}

		if(!is_dir($unzipPath)) {
			mkdir($unzipPath, 0777, TRUE);
		} else {
			$this->recursiveDelete($unzipPath);
		}

		if($this->extractZip($updatePackage, $unzipPath)) {
			$changesIniFile = $unzipPath .DIRECTORY_SEPARATOR . 'changes.ini';

			if(file_exists($changesIniFile)) {
				$messages = array();

				$changes = parse_ini_file($changesIniFile, TRUE);

				if(isset($changes['file']['add']) && count($changes['file']['add']) > 0) {
					$fileAddArray = $changes['file']['add'];
				} else {
					$fileAddArray = array();
				}
				if(isset($changes['file']['replace']) && count($changes['file']['replace']) > 0) {
					$fileReplaceArray = $changes['file']['replace'];
				} else {
					$fileReplaceArray = array();
				}
				$filesToCopy = array_reverse(array_merge($fileAddArray, $fileReplaceArray));
				sort($filesToCopy);

				if(isset($changes['file']['remove']) && count($changes['file']['remove']) > 0) {
					$fileToRemove = $changes['file']['remove'];
				} else {
					$fileToRemove = array();
				}

				//If there are new files
				if(count($filesToCopy) > 0) {
					$updateSource = $unzipPath . DIRECTORY_SEPARATOR . 'code';
					try {
						foreach($filesToCopy as $file) {
							$sourcePath = $updateSource . $file;
							$destinationPath = $pathToUpdate . $file;
							chmod($sourcePath, 0777);
							if(is_dir($sourcePath)) {
								if(!file_exists($destinationPath)) {
									$oldmask = umask(0);
									mkdir($destinationPath, 0777, true);
									umask($oldmask);
								}
							} else {
								if(file_exists($sourcePath)) {
									$content = file_get_contents($sourcePath);
									$this->fileWriter($destinationPath, $content);
								}
							}
						}
					} catch(Exception $exception) {
						if($calledFromRemote) {
							if($action == 'self-update') {
								$messages[] = 'L8M_VersionUpdater::updateLocalPath : Could not add new files. Could not update version. ' . $exception->getMessage();
							} else
							if($action == 'roll-back') {
								$messages[] = 'L8M_VersionUpdater::updateLocalPath : Could not roll back file changes. Added files could not be removed. ' . $exception->getMessage();
							}
						} else {
							if($action == 'self-update') throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Could not add new files. Could not update version. ' . $exception->getMessage()); else
							if($action == 'dump-update') throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Could not add new files. "' . $updateFor . '" dump could not be updated. ' . $exception->getMessage()); else
							if($action == 'roll-back') throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Could not roll back file changes. Added files could not be removed. ' . $exception->getMessage());
						}
					}
				}

				//If there are files to remove
				if(count($fileToRemove) > 0) {
					try {
						$lastPath = '';
						foreach($fileToRemove as $file) {
							if(($lastPath != '') && (strpos($file, $lastPath) === 0)) continue;

							$pathToRemove = $pathToUpdate . $file;
							if (file_exists($pathToRemove)) {
								if (is_dir($pathToRemove)) {
									$this->recursiveDelete($pathToRemove, TRUE);
								} else {
									unlink($pathToRemove);
								}
							}
							$lastPath = $file;
						}
					} catch(Exception $exception) {
						if($calledFromRemote) {
							if($action == 'self-update') {
								$messages[] = 'L8M_VersionUpdater::updateLocalPath : Could not remove redundant files. Could not update version. ' . $exception->getMessage();
							} else
							if($action == 'roll-back') {
								$messages[] = 'L8M_VersionUpdater::updateLocalPath : Could not roll back file changes. Removed files could not be reverted. ' . $exception->getMessage();
							}
						} else {
							if($action == 'self-update') throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Could not remove redundant files. Could not update version. ' . $exception->getMessage()); else
							if($action == 'dump-update') throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Could not remove redundant files. "' . $updateFor . '" dump could not be updated. ' . $exception->getMessage()); else
							if($action == 'roll-back') throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Could not roll back file changes. Removed files could not be reverted. ' . $exception->getMessage());
						}
					}
				}

				if($action == 'self-update') {
					if(file_exists($unzipPath . DIRECTORY_SEPARATOR . 'sql') &&
						file_exists($unzipPath . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'updates.sql')) {

						try {
							$sqlPath = $unzipPath . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'updates.sql';
							$content = file_get_contents($sqlPath);

							//Execute the SQL
							$db = Zend_Db_Table::getDefaultAdapter();
							$stmt = $db->prepare($content);
							$stmt->execute(array());
							$stmt->closeCursor();
						} catch(Exception $exception) {
							if($calledFromRemote) {
								$messages[] = 'L8M_VersionUpdater::updateLocalPath : Error updating Database. ' . $exception->getMessage();
							} else {
								throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Error updating Database. ' . $exception->getMessage());
							}
						}
					}

					//If Translation file exists

					if(file_exists($unzipPath . DIRECTORY_SEPARATOR . 'translation') && file_exists($unzipPath . DIRECTORY_SEPARATOR . 'translation' . DIRECTORY_SEPARATOR . 'updates.xml')) {
						$xmlPath = $unzipPath . DIRECTORY_SEPARATOR . 'translation' . DIRECTORY_SEPARATOR . 'updates.xml';
						if(is_file($xmlPath)) {
							$translationUpdater = L8M_TranslationUpdater::factory($withVersionUpdate = TRUE);
							$translationUpdater->importTranslations();
						}
					}

					//If change in action

					if(isset($changes['action']['add']) && count($changes['action']['add']) > 0) {
						$actionsToAdd = $changes['action']['add'];
					} else {
						$actionsToAdd = array();
					}

					if(isset($changes['action']['remove']) && count($changes['action']['remove']) > 0) {
						$actionsToRemove = $changes['action']['remove'];
					} else {
						$actionsToRemove = array();
					}

					foreach ($actionsToAdd as $resource) {
						$newAction = Default_Model_Action::getModelByColumn('resource', $resource);

						if(!$newAction) {
							$actionArray = explode('.', $resource);

							$module = $actionArray[0];
							$controller = $actionArray[1];
							$action = $actionArray[2];

							$controllerResource = $module . '.' . $controller;

							$controllerModel = Default_Model_Controller::getModelByColumn('resource', $controllerResource);

							//If there is a new controller, insert into database
							if(!isset($controllerModel->id) || $controllerModel->id == '') {
								$newController = new Default_Model_Controller();
								$newController->name = $controller;
								$newController->resource = $module . '.' . $controller;

								$moduleModel = Default_Model_Module::getModelByColumn('name', $module);

								//If there is a new module, insert into database
								if(!isset($moduleModel->id) || $moduleModel->id == '') {
									$newModule = new Default_Model_Module();
									$newModule->name = $module;
									$newModule->save();
									$moduleId = $newModule->id;
								} else {
									$moduleId = $moduleModel->id;
								}
								$newController->module_id = $moduleId;
								$newController->save();
								$controllerId = $newController->id;
							} else {
								$controllerId = $controllerModel->id;
							}

							//Insert action into database
							$newAction = new Default_Model_Action();
							$newAction->name = $action;
							$newAction->resource = $module . '.' . $controller . '.' . $action;
							$newAction->controller_id = $controllerId;
							$newAction->is_allowed = 1;

							if($module == 'default') {
								$roleShort = 'guest';
							} else
							if($module == "system") {
								$roleShort = 'admin';
							} else
							if($module == "admin") {
								$roleShort = 'supervisor';
							} else {
								$roleShort = 'guest';
							}

							$roleModel = Default_Model_Role::getModelByColumn('short', $roleShort);
							if($roleModel) {
								$roleId = $roleModel->id;
							} else {
								throw new L8M_Exception('Required role not found in records for adding a new action. Aborted.');
							}

							$newAction->role_id = $roleId;

							$newAction->save();
						}
					}

					foreach ($actionsToRemove as $remove) {
						$actionArray = explode('.', $remove);

						$module = $actionArray[0];
						$controller = $actionArray[1];
						$action = $actionArray[2];
						$controllerResource = $module . '.' . $controller;

						//Remove action from database
						$removeAction = Default_Model_Action::getModelByColumn('resource', $remove);
						if(isset($removeAction->id) && $removeAction->id != '') {
							$controllerId = $removeAction->controller_id;

							$removeAction->hardDelete();

							$oldController = Default_Model_Action::getModelByColumn('controller_id', $controllerId);

							//If there is no action in a controller, remove it from database
							if(!isset($oldController->controller_id) || $oldController->id == '') {
								$removeController = Default_Model_Controller::getModelByColumn('resource', $controllerResource);
								$moduleId = $removeController->module_id;
								$removeController->hardDelete();

								$oldModule = Default_Model_Controller::getModelByColumn('module_id', $moduleId);

								//If there is no controller in a module, remove it from database
								if(!isset($oldModule->module_id) || $oldModule->module_id == '') {
									$removeModule = Default_Model_Module::getModelByID($moduleId);
									$removeModule->hardDelete();
								}
							}
						}
					}

					//If change in models
					if(isset($changes['model']['add']) && count($changes['model']['add']) > 0) {
						$modelAddArray = $changes['model']['add'];
					} else {
						$modelAddArray = array();
					}
					if(isset($changes['model']['edit']) && count($changes['model']['edit']) > 0) {
						$modelEditArray = $changes['model']['edit'];
					} else {
						$modelEditArray = array();
					}
					$modelChangesArray = array_merge($modelAddArray,$modelEditArray);
					sort($modelChangesArray);

					if(isset($changes['model']['remove']) && count($changes['model']['remove']) > 0) {
						$modelRemoveArray = $changes['model']['remove'];
					} else {
						$modelRemoveArray = array();
					}

					if(count($modelChangesArray) > 0 || count($modelRemoveArray) > 0) {

						//If there is a change in any model, truncate the model_list table and all the related tables
						$db = Zend_Db_Table::getDefaultAdapter();

						$sql = "SET FOREIGN_KEY_CHECKS = 0;
								TRUNCATE TABLE `model_list_column_export`;
								TRUNCATE TABLE `model_list_column`;
								TRUNCATE TABLE `model_list_connection`;
								TRUNCATE TABLE `model_list_edit_ignore`;
								TRUNCATE TABLE `model_list_export`;
								TRUNCATE TABLE `model_list_translation`;
								TRUNCATE TABLE `model_list_where`;
								TRUNCATE TABLE `entity_model_list_config`;
								TRUNCATE TABLE `model_list`;
								SET FOREIGN_KEY_CHECKS = 1;
								";

						$stmt = $db->prepare($sql);
						$stmt->execute(array());
						$stmt->closeCursor();
					}

					if(count($modelChangesArray) > 0) {
						foreach ($modelChangesArray as $model) {
							$modelName = 'Default_Model_' . $model;

							//Load the model
							$loadedModel = new $modelName();

							$modelNameModel = Doctrine_Query::create()
								->from('Default_Model_ModelName m')
								->addWhere('m.name = ? ', array($modelName))
								->execute()
								->getFirst()
							;

							if ($modelNameModel) {
								$idArray = array($modelNameModel->id);

								if($modelName == 'Default_Model_Entity') {
									$entityArray = array('Default_Model_EntityAdmin', 'Default_Model_EntitySupervisor','Default_Model_EntityUser','Default_Model_EntityTranslator','Default_Model_EntityAuthor','Default_Model_Customer');
									$entityModel = Doctrine_Query::create()
										->from('Default_Model_ModelName m')
										->select('id')
										->whereIn('m.name', $entityArray)
										->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
										->execute()
									;
									//Get id of each record
									foreach($entityModel as $mid => $id) {
										$idArray[] = $id['m_id'];
									}
								}

								$modelColumnNameModel = Doctrine_Query::create()
									->from('Default_Model_ModelColumnName m')
									->whereIn('m.model_name_id', $idArray)
									->execute()
								;

								//Column names for the model
								foreach($modelColumnNameModel as $modelColumnName) {
									if(isset($modelColumnName->id) && $modelColumnName->id != '') {
										$modelColumnName->hardDelete();
									}
								}
							} else {
								$modelNameModel = new Default_Model_ModelName();
								$modelNameModel->name = $modelName;
								$modelNameModel->save();
							}
							/**
							 * retrieve columns
							 */
							$modelColumns = $loadedModel->getTable()->getColumns();

							/**
							 * create model column name
							 */
							foreach ($modelColumns as $columnName => $columnDefinition) {
								$modelColumnNameModel = Doctrine_Query::create()
									->from('Default_Model_ModelColumnName m')
									->addWhere('m.name = ? ', array($columnName))
									->addWhere('m.model_name_id = ? ', array($modelNameModel->id))
									->execute()
									->getFirst()
								;
								if (!$modelColumnNameModel) {
									$modelColumnNameModel = new Default_Model_ModelColumnName();
									$modelColumnNameModel->name = $columnName;
									$modelColumnNameModel->model_name_id = $modelNameModel->id;
									$modelColumnNameModel->save();
								}

								if($modelName == 'Default_Model_Entity') {
									foreach ($idArray as $mid) {
										$modelColumnNameModel = new Default_Model_ModelColumnName();
										$modelColumnNameModel->name = $columnName;
										$modelColumnNameModel->model_name_id = $mid;
										$modelColumnNameModel->save();
									}
								}
							}
						}
					}

					if(count($modelRemoveArray) > 0) {
						foreach($modelRemoveArray as $model) {
							$modelName = 'Default_Model_' . $model;

							$modelNameModel = Default_Model_ModelName::getModelByColumn('name', $modelName);
							if(isset($modelNameModel->id) && $modelNameModel->id != '') {
								$idArray = array($modelNameModel->id);
								if($modelName == 'Default_Model_Entity') {
									$entityArray = array('Default_Model_EntityAdmin', 'Default_Model_EntitySupervisor','Default_Model_EntityUser','Default_Model_EntityTranslator','Default_Model_EntityAuthor','Default_Model_Customer');
									$entityModel = Doctrine_Query::create()
										->from('Default_Model_ModelName m')
										->select('id')
										->whereIn('m.name', $entityArray)
										->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
										->execute()
									;
									foreach($entityModel as $mid => $id) {
										$idArray[] = $id['m_id'];
									}
								}

								/**
								 * retrieve columns
								 */

								$modelColumnNameModel = Doctrine_Query::create()
									->from('Default_Model_ModelColumnName m')
									->whereIn('m.model_name_id', $idArray)
									->execute()
								;
								foreach($modelColumnNameModel as $modelColumnName) {
									if(isset($modelColumnName->model_name_id) && $modelColumnName->model_name_id != '') {
										$modelColumnName->hardDelete();
									}
								}
								$modelNameModel->hardDelete();
							}
						}
					}

					if(isset($changes['media image connection']) && count($changes['media image connection'])) {
						$mediaImageConnections = $changes['media image connection'];

						foreach($mediaImageConnections as $model => $connections) {
							if(!class_exists('Default_Model_' . $model)) continue;
							$modelName = 'Default_Model_' . $model;
							$tempModelInstance = new $modelName();
							if(!isset($tempModelInstance->media_image_id)) continue;

							foreach($connections as $connection) {
								$connectionDetails = explode(',', $connection);
								if(count($connectionDetails) !== 2) continue;

								$detailForShort = explode('=', $connectionDetails[0]);
								$detailForPath = explode('=', $connectionDetails[1]);
								if((count($detailForShort) !== 2) || (count($detailForPath) !== 2)) continue;

								$shortKey = trim($detailForShort[0]);
								$shortValue = trim($detailForShort[1]);

								if(($shortKey != 'short') || ($shortValue == '')) continue;

								$pathKey = trim($detailForPath[0]);
								$pathValue = trim($detailForPath[1]);

								if(($pathKey != 'image_path') || strpos($pathValue, DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $model . '_' . $shortValue . '.') !== 0) continue;

								if(!file_exists(BASE_PATH . $pathValue)) continue;
								$pathValue = BASE_PATH . $pathValue;

								$modelValue = $modelName::getModelByShort($shortValue);

								if(!$modelValue) continue;

								$mediaIdForConnection = Default_Service_Media::fromFileToMediaID($pathValue);

								if(!$mediaIdForConnection) continue;

								$modelValue->media_image_id = $mediaIdForConnection;
								$modelValue->save();
							}
						}
					}
				} else
				if($action == 'dump-update') {
					$oldDumpPath = $this->_remotePath . '.zip';
					unlink($oldDumpPath);

					$newDumpPath = $oldDumpPath;
					$newDump = new ZipArchive();
					$newDump->open($newDumpPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

					$newDumpFiles = new RecursiveIteratorIterator(
						new RecursiveDirectoryIterator($pathToUpdate),
						RecursiveIteratorIterator::SELF_FIRST
					);

					foreach ($newDumpFiles as $name => $file) {
						if(!$file->isDir()) {
							$filePath = str_replace($upOne, '', $file->getRealPath());

							$newDump->addFile($file, $filePath);
						} else {
							$filePath = str_replace($upOne, '', $file->getRealPath());

							$newDump->addEmptyDir($filePath);
						}
					}
					$newDump->close();
					chmod($newDumpPath, 0777);

					$this->recursiveDelete($pathToUpdate, TRUE);
				} else
				if($action == 'roll-back') {
					if(file_exists($unzipPath . DIRECTORY_SEPARATOR . 'sql') &&
						file_exists($unzipPath . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'backup.sql')){
						try {
							$mysqlUserName = L8M_Config::getOption('resources.multidb.default.username');
							$mysqlPassword = L8M_Config::getOption('resources.multidb.default.password');
							$mysqlHostName = L8M_Config::getOption('resources.multidb.default.host');
							$DbName = L8M_Config::getOption('resources.multidb.default.dbname');
							$backupFile = $unzipPath . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . "backup.sql";
							$tables = array();
							$this->importBackupDatabase($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName, $backupFile);
						} catch(Exception $exception) {
							if($calledFromRemote) {
								$messages[] = 'L8M_VersionUpdater::updateLocalPath : Error rolling-back Database. ' . $exception->getMessage();
							} else {
								throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Error rolling-back Database. ' . $exception->getMessage());
							}
						}
					}
				}

				$this->recursiveDelete($unzipPath, TRUE);
				unlink($updatePackage);

				$returnData['message'] = $messages;
			} else {
				if($calledFromRemote) {
					$returnData['message'] = array();
					$returnData['message'][] = 'L8M_VersionUpdater::updateLocalPath : Changes could not be tracked. Could not update version.';
				} else {
					if($action == 'self-update') throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Changes could not be tracked. Could not update version.'); else
					if($action == 'dump-update') throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Changes could not be tracked. "' . $updateFor . '" dump could not be updated.');
				}
			}
		} else {
			if($calledFromRemote) {
				$returnData['message'] = array();
				$returnData['message'][] = 'L8M_VersionUpdater::updateLocalPath : Could not unpack update package.';
			} else {
				throw new L8M_Exception('L8M_VersionUpdater::updateLocalPath : Could not unpack update package.');
			}
		}

		if($calledFromRemote) {
			if(!isset($returnData['message']) || (isset($returnData['message']) && (count($returnData['message']) == 0))) {
				$returnData['success'] = TRUE;
			}
			return $returnData;
		} else {
			return TRUE;
		}
	}

	/**
     * Backup the data of code and database before update
     *
     * @return bool
     * @throws Doctrine_Query_Exception
     * @throws L8M_Exception
     */
	public function backupData()
	{
		//Database Backup
		$mysqlUserName = L8M_Config::getOption('resources.multidb.default.username');
        $mysqlPassword = L8M_Config::getOption('resources.multidb.default.password');
        $mysqlHostName = L8M_Config::getOption('resources.multidb.default.host');
        $DbName = L8M_Config::getOption('resources.multidb.default.dbname');
		$backupName = "backup.sql";
		$tables = array();
		$this->Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName, $tables, $backupName);

		//code Backup
		$tempPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
		$pathFor = explode(DIRECTORY_SEPARATOR, $this->_remotePath);
		$extractPath = $tempPath . $pathFor[count($pathFor) - 1] . '.update';
		$pathFor[count($pathFor) - 2] = "temp";
		$pathFor[count($pathFor) - 1] = $pathFor[count($pathFor) - 1] . ".update.zip";
		$uploadPath = implode(DIRECTORY_SEPARATOR, $pathFor);
		$this->extractZip($uploadPath, $extractPath);
		$changesContent = parse_ini_file($extractPath . DIRECTORY_SEPARATOR . 'changes.ini', TRUE);

		$iniCodeChanges = "[backup]\n\n";
		$files = array_merge($changesContent['file']['replace'], $changesContent['file']['remove']);

		foreach($changesContent['file']['remove'] as $removeFiles)
		{
			$iniCodeChanges .= 'add[] = "' . $removeFiles . '"' . "\n";
		}
		$iniCodeChanges .= "\n";
		foreach($changesContent['file']['replace'] as $replaceFiles)
		{
			$iniCodeChanges .= 'replace[] = "' . $replaceFiles . '"' . "\n";
		}
		$iniCodeChanges .= "\n";
		foreach($changesContent['file']['add'] as $addFiles)
		{
			$iniCodeChanges .= 'remove[] = "' . $addFiles . '"' . "\n";
		}
		$this->fileWriter(BASE_PATH . DIRECTORY_SEPARATOR . 'changes.ini', $iniCodeChanges);

		//create the archive
		$zip = new ZipArchive();
		$zip->open($tempPath.'backup.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
		$iniFilePath = BASE_PATH . DIRECTORY_SEPARATOR . 'changes.ini';
		$sqlFilePath = BASE_PATH . DIRECTORY_SEPARATOR . 'backup.sql';
		//Add ini file to zip
		if(file_exists($iniFilePath))
		{
			$zip->addFile($iniFilePath, "changes.ini");
			//unlink($iniFilePath);
		}
		//Add sql file to zip
		if(file_exists($sqlFilePath)){
			$zip->addFile($sqlFilePath, 'sql' . DIRECTORY_SEPARATOR . 'backup.sql');
			//unlink($sqlFilePath);
		}

		foreach($files as $file) {
			$path = BASE_PATH . $file;
			if(is_file($path)) {
				$filepath = 'code' . $file;
				$zip->addFile($path, $filepath);
			} else {
				$filepath = 'code' . $file;
				$zip->addEmptyDir($filepath);
			}
		}
		//close the zip -- done!
		$zip->close();
		//remove ini file from base path
		if(file_exists($iniFilePath))
		{
			unlink($iniFilePath);
		}
		//remove sql file from base path
		if(file_exists($sqlFilePath)){
			unlink($sqlFilePath);
		}
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();
	}

	/**
     * Upload zip to Remote address
     *
     * @return bool
     */
	public function uploadUpdateZip($remoteServerUser = '', $remoteServerPassword = '')
	{
		$updateFor = $this->getRemoteServerName();

		$upOne = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp');

		$uploadPackage = $upOne . DIRECTORY_SEPARATOR . $updateFor . '.update.zip';
        $remotePath = DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'self.update.zip';

		$ftpServer = L8M_FtpTools::factory($updateFor, $remoteServerUser, $remoteServerPassword);

		$uploadStatus = $ftpServer->uploadFileToFtp($uploadPackage, $remotePath);

		$ftpServer->closeFtpConnection();

		return $uploadStatus;
	}

	/**
	 * Check remote version updater support
	 *
	 * @return bool
	 * @throws L8M_Exception
	 */
	public function checkVersionUpdaterSupportOnRemote($remoteServerUser = '', $remoteServerPassword = '')
	{
		$updateFor = $this->getRemoteServerName();

		$ftpServer = L8M_FtpTools::factory($updateFor, $remoteServerUser, $remoteServerPassword);

		$remoteVersionUpdaterScriptPath = DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'VersionUpdaterController.php';
		$remoteStagingAccessForUpdater = DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'L8M' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Plugin' . DIRECTORY_SEPARATOR . 'Staging.php';
		$remoteVersionUpdaterCore = DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'L8M' . DIRECTORY_SEPARATOR . 'VersionUpdater.php';
		$remoteFtpToolsCore = DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'L8M' . DIRECTORY_SEPARATOR . 'FtpTools.php';

		$updaterSupport = $ftpServer->checkIfFtpFileExists($remoteVersionUpdaterScriptPath) &&
							$ftpServer->checkIfFtpFileExists($remoteStagingAccessForUpdater) &&
							$ftpServer->checkIfFtpFileExists($remoteVersionUpdaterCore) &&
							$ftpServer->checkIfFtpFileExists($remoteFtpToolsCore);

		if(!$updaterSupport) {
			$defaultVersionInfo = "[versionInfo]\n\ncurrentVersion = 000\nlastVersion = 000";
			$ftpServer->uploadFileToFtp($remoteVersionUpdaterScriptPath);
			$ftpServer->uploadFileToFtp($remoteStagingAccessForUpdater);
			$ftpServer->uploadFileToFtp($remoteVersionUpdaterCore);
			$ftpServer->uploadFileToFtp($remoteFtpToolsCore);
			$ftpServer->createFtpDirectory(DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater');
			$ftpServer->createFtpDirectory(DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp');
			$ftpServer->createFtpFile(DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'info.ini', $defaultVersionInfo);

			$ftpServer->closeFtpConnection();

			throw new L8M_Exception('L8M_VersionUpdater::checkVersionUpdaterSupportOnRemote : Version Updater is not supported/authorised on ' . $updateFor . '. ' . $updateFor . ' admin or supervisor needs to validate Version Updater support and add the update validation token.');
		} else {
			$ftpServer->closeFtpConnection();

			$ch = curl_init();

			/* curl_setopt($ch, CURLOPT_URL,"http://www.example.com/tester.phtml");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
						"postvar1=value1&postvar2=value2&postvar3=value3"); */

			curl_setopt($ch, CURLOPT_URL, "https://" . $updateFor . $this->_remoteVersionUpdaterLink . "/test-updater?token=" . $this->_remoteVersionUpdaterToken);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

			$headers = array();
			// $headers[] = "Authorization: Bearer APIKEY";
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);

			/* if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			} */
			curl_close($ch);

			try {
				$updaterSupportTestResult = json_decode($result, TRUE);
				if(!isset($updaterSupportTestResult['success']) || $updaterSupportTestResult['success'] == FALSE) {
					throw new L8M_Exception('L8M_VersionUpdater::checkVersionUpdaterSupportOnRemote : Version Updater is not supported/authorised on ' . $updateFor . '. ' . $updateFor . ' admin or supervisor needs to validate Version Updater support and add the update validation token.');
				}
			} catch(Exception $e) {
				throw new L8M_Exception('L8M_VersionUpdater::checkVersionUpdaterSupportOnRemote : Version Updater is not supported/authorised on ' . $updateFor . '. ' . $updateFor . ' admin or supervisor needs to validate Version Updater support and add the update validation token.');
			}
		}

		return $updaterSupport;
	}

	/**
	 * Update remote server and parse response
	 *
	 * @return bool
     * @throws L8M_Exception
	 */
	public function updateRemotePath($remoteServerUser, $remoteServerPassword)
	{
		$updateFor = $this->getRemoteServerName();
		$ftpServer = L8M_FtpTools::factory($updateFor, $remoteServerUser, $remoteServerPassword);

		$remoteVersionUpdaterScriptPath = DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'VersionUpdaterController.php';
		$remoteVersionUpdaterCore = DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'L8M' . DIRECTORY_SEPARATOR . 'VersionUpdater.php';

		$ftpServer->uploadFileToFtp($remoteVersionUpdaterScriptPath);
		$ftpServer->uploadFileToFtp($remoteVersionUpdaterCore);
		$ftpServer->closeFtpConnection();

		$remoteUpdateResponse = $this->callRemoteUpdater();

		try {
			$remoteUpdaterResponse = json_decode($remoteUpdateResponse, TRUE);

			if($remoteUpdaterResponse['success'] == TRUE) {
				return TRUE;
			} else {
				$errors = '';
				$errorCounter = 1;

				do {
					$errors .= $errorCounter . '. ' . $remoteUpdaterResponse['message'][$errorCounter - 1];

					++$errorCounter;
				} while($errorCounter <= count($remoteUpdaterResponse['message']));

				throw new L8M_Exception('L8M_VersionUpdater::updateRemotePath : There was some error updating the remote server. ' . $errors);
			}
		} catch(Exception $e) {
			echo(nl2br("L8M_VersionUpdater::updateRemotePath : Remote server threw some error.\n\n"));
		}
	}

	/**
	 * Get remote server name from the remote path set for version updater
	 *
	 * @return string
	 */
	private function getRemoteServerName($path = '')
	{
		if($path == '') {
			$path = $this->_remotePath;
		}
		$updateFor = explode('/', $path);
		$updateFor = $updateFor[count($updateFor) - 1];

		return $updateFor;
	}

	/**
	 * Call updater of remote
	 *
	 * @return bool
	 */
	private function callRemoteUpdater()
	{
		$updateFor = $this->getRemoteServerName();

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $updateFor . $this->_remoteVersionUpdaterLink . "?token=" . $this->_remoteVersionUpdaterToken);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

		$headers = array();
		// $headers[] = "Authorization: Bearer APIKEY";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		curl_close ($ch);

		return $result;
	}

	/**
	 * Unpack remote package for development mode
	 *
	 * @return bool||string
     * @throws L8M_Exception
	 */
	private function unpackRemotePackage()
	{
		$unpacked = TRUE;

		$remotePath = $this->_remotePath;
		$remotePackage = $remotePath . '.zip';

		$unpackPath = explode(DIRECTORY_SEPARATOR, $remotePath);

		$remoteServer = $unpackPath[count($unpackPath) - 1];
		unset($unpackPath[count($unpackPath) - 1]);

		$unpackPath[count($unpackPath) - 1] = 'temp';
		$unpackPath = implode(DIRECTORY_SEPARATOR, $unpackPath);

		if(is_dir($unpackPath . DIRECTORY_SEPARATOR . $remoteServer)) {
			$this->recursiveDelete($unpackPath . DIRECTORY_SEPARATOR . $remoteServer, TRUE);
		}

		if($this->extractZip($remotePackage, $unpackPath)) {
			return $unpackPath . DIRECTORY_SEPARATOR . $remoteServer;
		} else {
			$unpacked = FALSE;
			throw new L8M_Exception('L8M_VersionUpdater::updatePackager : Could not unpack local dump for ' . $remoteServer);
		}

		return $unpacked;
	}

	/**
	 * Compare local files with remote files
	 *
	 * @return bool
	 */
	private function compareFiles()
	{
        // Get real path for main folder
		$localBasePath = realpath(BASE_PATH);

        // Get all files and directories of main folder
        $localContentList = $this->getDirectoryContentList($localBasePath);
        $localDetailsList = $localContentList['contentDetails'];
        $localNamesList = $localContentList['contentNames'];

		// Get real path for old version folder
		$remoteBasePath = $this->_remotePath;
		if($this->_environment === L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
			$remoteBasePath = explode(DIRECTORY_SEPARATOR, $remoteBasePath);
			$remoteBasePath[count($remoteBasePath) - 2] = 'temp';
			$remoteBasePath = implode(DIRECTORY_SEPARATOR, $remoteBasePath);
		}

        // Get all files and directories of old version folder
        $remoteContentList = $this->getDirectoryContentList($remoteBasePath);
        $remoteDetailsList = $remoteContentList['contentDetails'];
        $remoteNamesList = $remoteContentList['contentNames'];

        //check differences in commonfiles
		$changesInLocal = $this->findChangedFiles($localDetailsList, $remoteDetailsList);

        $filesToRemove = array_diff($remoteNamesList, $localNamesList);
		$filesToAdd = array_diff($localNamesList, $remoteNamesList);
		$filesToReplace = $changesInLocal['files'];

		$contentChanges = $changesInLocal['contents'];

		$files = array();
		if(count($filesToAdd)) {
			$files['add'] = $filesToAdd;
		}
		if(count($filesToReplace)) {
			$files['replace'] = $filesToReplace;
		}
		if(count($filesToRemove)) {
			$files['remove'] = $filesToRemove;
		}

        if(count($files) > 0) {
			return $this->prepareUpdatePackage($files, $contentChanges);
        } else {
			return FALSE;
		}
	}

	/**
	 * Get details and names lists of directory contents
	 *
	 * @return bool
	 */
	private function getDirectoryContentList($path)
	{
        $upOne = realpath($path . DIRECTORY_SEPARATOR . '..');
		$directoriesToCheck = $this->_updateDirectories;

		$dirContentDetails = array();
		$dirContentNames = array();

        foreach($directoriesToCheck as $dir) {
			$dirContents = new RecursiveDirectoryIterator($path . DIRECTORY_SEPARATOR . $dir);
			$dirContents = new RecursiveIteratorIterator($dirContents, RecursiveIteratorIterator::LEAVES_ONLY);

            foreach($dirContents as $name=>$item) {
				// If folder or file name starts with a '.' skip it
				if((substr(basename($item), 0, 1) === '.') && (basename($item) != '.') && (basename($item) != '..')) continue;


				if($item->getRealPath() !== $path &&																																			// skip '.' for root of $path
					$item->getRealPath() !== $upOne &&																																			// skip '..' for root of $path
					strpos($item->getRealPath(), '.svn') === FALSE &&																															// skip '.svn'
					// $item->getRealPath() !== $path . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'VersionUpdaterController.php' &&		// skip '/data/temp'
					$item->getRealPath() !== $path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' &&																				// skip '/data/temp'
					strpos($item->getRealPath(), $path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp') !== 0 &&																	// skip contents of '/data/temp/'
					$item->getRealPath() !== $path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' &&																			// skip '/data/media'
					strpos($item->getRealPath(), $path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media') !== 0 &&																	// skip contents of '/data/media/'
					strpos($item->getRealPath(), $path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'lastVersions') !== 0 &&					// skip '/data/versionUpdater/lastVersions'
					strpos($item->getRealPath(), $path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp') !== 0 &&							// skip contents of '/data/versionUpdater/temp/'
					strpos($item->getRealPath(), 'application.ini') === FALSE &&																												// skip 'application.ini'
					strpos($item->getRealPath(), 'domain.ini') === FALSE &&																														// skip 'domain.ini'
					strpos($item->getRealPath(), 'writables.ini') === FALSE &&																													// skip 'writables.ini'
					strpos($item->getRealPath(), 'tables.sql') === FALSE &&																														// skip 'tables.sql'
					strpos($item->getRealPath(), 'sitemap.xml') === FALSE &&																													// skip 'sitemap.xml'
					preg_match('(_\d+x\d+)', $item->getRealPath()) === 0) {																														// skip media files generated dynamically by size

					$dirContentDetails[str_replace($path . DIRECTORY_SEPARATOR , '', $item->getRealPath())] = array(
						'content' => ($item->isDir()) ? '' : file_get_contents($item->getRealPath()),
						'size' => ($item->isDir()) ? 0 : filesize($item->getRealPath()),
						'path' => str_replace($path . DIRECTORY_SEPARATOR , '', $item->getRealPath()),
						'name' => basename($item->getRealPath()),
						'type' => ($item->isDir()) ? 'dir' : 'file'
					);
					$dirContentNames[] = str_replace($path. DIRECTORY_SEPARATOR, '', $item->getRealPath());
				}
            }
		}
        $dirContentDetails = array_unique($dirContentDetails, SORT_REGULAR);
		$dirContentNames = array_unique($dirContentNames, SORT_REGULAR);
		sort($dirContentNames);

        $list = array(
                "contentDetails" => $dirContentDetails,
                "contentNames" => $dirContentNames
		);

        return $list;
	}

	/**
	 * Find changed files from file details
	 *
	 * @return array
	 */
	private function findChangedFiles($localDetailsList, $remoteDetailsList)
	{
		$filesToReplace = array();
		$updatedBaseModelContents = array();

        foreach($localDetailsList as $index => $itemDetails) {
            if(!in_array($itemDetails, $remoteDetailsList) && isset($remoteDetailsList[$index])) {
				$filesToReplace[] = $itemDetails['path'];

				if(strpos($itemDetails['path'], 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR) !== FALSE ||
					strpos($itemDetails['path'],  DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR) !== FALSE) {
					$updatedBaseModelContents[$itemDetails['path']] = array(
						'newContent' => $itemDetails['content'],
						'oldContent' => $remoteDetailsList[$index]['content']
					);
				}
            }
		}
		sort($filesToReplace);

		$fileChangeResults = array(
			'files' => $filesToReplace,
			'contents' => $updatedBaseModelContents
		);

        return $fileChangeResults;
	}

	/**
	 * Prepare the update package
	 *
	 * @return bool
     * @throws L8M_Exception
	 */
	private function prepareUpdatePackage($files, $contentOfChangedFiles = array())
	{
		$pathFor = explode(DIRECTORY_SEPARATOR, $this->_remotePath);
		$pathFor[count($pathFor) - 2] = "temp";
		$oldPath = implode(DIRECTORY_SEPARATOR, $pathFor);
		$remoteServer = $pathFor[count($pathFor) - 1];

		$filesToAdd = isset($files['add']) ? $files['add'] : array();
		$filesToReplace = isset($files['replace']) ? $files['replace'] : array();
		$filesToRemove = isset($files['remove']) ? $files['remove'] : array();

		$filesForUpdatePackage = array();
		$updateSql = "SET FOREIGN_KEY_CHECKS=0;\n\n";

		$iniCodeChanges = "[file]\n\n";
		$iniActionAdd = '';
		$iniActionRemove = '';
		$iniTableAdd = '';
		$iniTableEdit = '';
		$iniTableRemove = '';
		$iniModelAdd = '';
		$iniModelEdit = '';
		$iniModelRemove = '';
		$editTableArray = array();

        if(count($filesToAdd) > 0) {
            foreach($filesToAdd as $path) {
				$iniCodeChanges .= 'add[] = "' . DIRECTORY_SEPARATOR . $path . '"' . "\n";
				$filesForUpdatePackage[] = BASE_PATH . DIRECTORY_SEPARATOR . $path;

				//If new model
				if(strpos($path, 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR) !== FALSE) {
					$modelName = $this->getStringBetween($path, 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR, '.php');
					$newModels[] = 'Default_Model_' . $modelName;
					$iniModelAdd .= 'add[] = "' . $modelName . '"' . "\n";
				} else
				//if new action
				if(strpos($path, 'Controller.php') !== FALSE) {
					if(strpos($path, 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR) !== FALSE) {
						$moduleName = 'default';
					} else {
						$moduleName = $this->getStringBetween($path, 'modules' . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . 'controllers');
					}

					$className = $this->getStringBetween($path, 'controllers' . DIRECTORY_SEPARATOR, '.php');
					$className = trim(preg_replace('/\s+/', '', $className));
					$classNameArray = preg_split('/(?=[A-Z])/', $className);

					foreach($classNameArray as $key => &$ca) {
						if($ca === '')
							unset($classNameArray[$key]);
						else
							$ca = strtolower($ca);
					}
					array_pop($classNameArray);
					$controllerName = implode('-', $classNameArray);

					$lines1 = file_get_contents(BASE_PATH . DIRECTORY_SEPARATOR . $path);
					$lines1 = preg_split('/\n/', $lines1);

					foreach ($lines1 as $new) {
						if(strpos($new, 'function') !== FALSE) {
							$functionName = $this->getStringBetween($new, 'function', '()');
							if(strpos($functionName, 'Action')) {
								$functionName = trim(preg_replace('/\s+/', '', $functionName));
								$functionNameArray = preg_split('/(?=[A-Z])/', $functionName);

								foreach ($functionNameArray as $key => &$fa) {
									if($fa === '')
										unset($functionNameArray[$key]);
									else
										$fa = strtolower($fa);
								}
								array_pop($functionNameArray);
								$actionName = implode('-', $functionNameArray);

								$resourceName = $moduleName . '.' . $controllerName . '.' . $actionName;
								$iniActionAdd .= 'add[] = "' . $resourceName . '"' . "\n";
							}
						}
					}
				}
			}

			if(isset($newModels) && is_array($newModels)) {
				try {
					$updateSql .= implode(";\n", Doctrine_Core::generateSqlFromArray($newModels)) . ";\n\n";
				} catch(Doctrine_Exception $exception) {
					$message = 'an exception was thrown <code>'
						. $exception->getMessage()
						. '</code>'
					;
					throw new L8M_Exception('L8M_VersionUpdater::prepareUpdatePackage : Error while generating SQL update, ' . $message);
				}

				$newModelList = Doctrine_Core::filterInvalidModels($newModels);
				foreach ($newModelList as $model) {
					$record = new $model();
					$table = $record->getTable();

					$tbl = explode(" ", trim($table));
					$tableName = strip_tags(end($tbl));
					$tableName = trim(preg_replace('/\s+/', '',$tableName));
					$iniTableAdd .= 'add[] = "' . $tableName . '"' . "\n";
				}
			}
		}

		$iniCodeChanges .= "\n";

        if(count($filesToReplace) > 0) {
            foreach($filesToReplace as $path) {
                $iniCodeChanges .= 'replace[] = "' . DIRECTORY_SEPARATOR . $path . '"' . "\n";
				$filesForUpdatePackage[] = BASE_PATH . DIRECTORY_SEPARATOR . $path;

				//Change in Model
				if(strpos($path, 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR) !== FALSE) {
					$modelName = $this->getStringBetween($path, 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR, '.php');
					$changedModel = 'Default_Model_' . $modelName;
					$iniModelEdit .= 'edit[] = "' . $modelName . '"' . "\n";

					if(!isset($contentOfChangedFiles[$path])) {
						throw new L8M_Exception('L8M_VersionUpdater::prepareUpdatePackage : Error while generating SQL update, could not read file contents.');
					}

					$lines1 = $contentOfChangedFiles[$path]['newContent'];
					$lines1 = explode('$this', $lines1);
					$lines2 = $contentOfChangedFiles[$path]['oldContent'];
					$lines2 = explode('$this', $lines2);

					$len1 = sizeof($lines1);
                    $len2 = sizeof($lines2);

                    $maxLength = ($len1 > $len2) ? $len1 : $len2;

                    for($i = 0; $i < $maxLength; $i++) {
                        if(isset($lines1[$i])) {
                            $lines1[$i] = $this->getStringBetween($lines1[$i], "->", ";");
                            $lines1[$i] = preg_replace('/\s+/', ' ', $lines1[$i]);
                        }
                        if(isset($lines2[$i])) {
                            $lines2[$i] = $this->getStringBetween($lines2[$i], "->", ";");
                            $lines2[$i] = preg_replace('/\s+/', ' ', $lines2[$i]);
                        }
                    }

					$diff = array_diff($lines1, $lines2);

					$models = array($changedModel);
					try {
						//Get the table name from model name
						$model = Doctrine_Core::filterInvalidModels($models);

						$record = new $model[0]();
						$table = $record->getTable();

						$tbl = explode(' ', trim($table));
						$tableName = strip_tags(end($tbl));
						$tableName = trim(preg_replace('/\s+/', '', $tableName));

					} catch(Doctrine_Exception $exception) {
						$message = 'an exception was thrown <code>'
							. $exception->getMessage()
							. '</code>'
						;
						throw new L8M_Exception('L8M_VersionUpdater::prepareUpdatePackage : Error while generating SQL update, ' . $message);
					}

					$iniTableEdit .= 'edit[] = "' . $tableName . '"' . "\n";

					foreach($diff as $d) {
						//New column
						if(strpos($d, 'hasColumn') !== FALSE) {
							$updateSql .= 'ALTER TABLE ' . $tableName;

							//get column name
							$colName = $this->getStringBetween($d, "hasColumn('", "',");
							//get filelds details
							$fields = $this->getStringBetween($d, "array(", "))");
							$fieldArray = explode(" ", $fields);

							//remove other data from string
							foreach ($fieldArray as $k => $v) {
								if($v == '' || $v == ' ' || $v == '=>') {
									unset($fieldArray[$k]);
								}
								else {
									$fieldArray[$k] = str_replace("'", '', $fieldArray[$k]);
									$fieldArray[$k] = str_replace(',', '', $fieldArray[$k]);
									$fieldArray[$k] = trim(preg_replace('/\s+/', '', $fieldArray[$k]));
								}
							}

							//Generate array of field details from string
							$fieldArray = array_values($fieldArray);

							$temp = array();
							$i = 0;
							$max = count($fieldArray);
							while($i < $max) {
								$temp[$fieldArray[0]] = $fieldArray[1];
								array_shift($fieldArray);
								array_shift($fieldArray);
								$i += 2;
							}

							$finalFieldArray[$colName] = $temp;
							// Get array keys
							$arrayKeys = array_keys($finalFieldArray);
							// Fetch last array key
							$lastArrayKey = array_pop($arrayKeys);

							$sql2 = NULL;
							foreach ($finalFieldArray as $fieldName => $field) {
								//create query from the array
								$query = Doctrine_Manager::connection()->export->getDeclaration($fieldName, $field);
								$queryFields[] = $query;
								$sql2 = "\nADD COLUMN " . $query;
								if(isset($field['primary'])) {
									$sql2 .= ' PRIMARY KEY';
								}

								foreach ($lines1 as $l) {
									if($l === $d) {
										$currentKey = key($lines1);
										$preCol = $lines1[$currentKey - 2];
									}
								}

								if (strpos($preCol, 'hasColumn') !== FALSE) {
									$preColName = $this->getStringBetween($preCol, "hasColumn('", "',");
									$sql2 .= ' AFTER ' . $preColName;
								}
								if($fieldName == $lastArrayKey)
									$sql2 .= ";\n\n";
								else
									$sql2 .= ",\n\n";
							}
							$updateSql .= $sql2;
						} else
						//If there is a new index
						if(strpos($d, 'index') !== FALSE) {
							$indexName = $this->getStringBetween($d, "index('", "',");
							$temp = trim(preg_replace('/\s+/', '', $d));

							$fieldName = $this->getStringBetween($temp, "=>array(0=>'", "',");
							$updateSql .= 'CREATE INDEX ' . $indexName . "\nON " . $tableName . ' (' . $fieldName . ");\n\n";
						} else
						//If there is a new foreign key constraint
						if(strpos($d, 'hasOne') !== FALSE || strpos($d, 'hasMany') !== FALSE) {
							$fList = array();

							if(strpos($d, 'hasOne') !== FALSE)
								$fModelName = $this->getStringBetween($d, "hasOne('", ' ');
							else
								if(strpos($d, 'hasMany') !== FALSE)
									$fModelName = $this->getStringBetween($d, "hasMany('", ' ');

							//get local column name
							$local = trim($this->getStringBetween($d, "local' => '", "',"));
							//get referenced column name
							$foreign = $this->getStringBetween($d, "foreign' => '", "'))");
							$fList[] = $fModelName;

							try {
								$models = Doctrine_Core::filterInvalidModels($fList);
								//get referenced table name
								$tableName = trim(preg_replace('/\s+/', '', $tableName));
								foreach ($models as $ft) {
									$record = new $ft();
									$table = $record->getTable();
									$tbl = explode(" ", trim($table));
									$fTableName = strip_tags(end($tbl));
									$fTableName = trim(preg_replace('/\s+/', '', $fTableName));
									if ($local != "id"){
										$updateSql .= 'ALTER TABLE ' . $tableName . "\nADD CONSTRAINT " . $tableName . '_' . $local . '_' . $fTableName . '_' . $foreign . ' FOREIGN KEY (' . $local . ') REFERENCES ' . $fTableName . '(' . $foreign . ");\n\n";
									}
								}
							} catch(Doctrine_Exception $exception) {
								$message = 'an exception was thrown <code>'
									. $exception->getMessage()
									. '</code>'
								;
								throw new L8M_Exception('L8M_VersionUpdater::prepareUpdatePackage : Error while generating SQL update, ' . $message);
							}
						}
					}

					$diff = array_diff($lines2, $lines1);

					$models = array($changedModel);
					try {
						//Get the table name from model name
						$model = Doctrine_Core::filterInvalidModels($models);

						$record = new $model[0]();
						$table = $record->getTable();

						$tbl = explode(' ', trim($table));
						$tableName = strip_tags(end($tbl));
						$tableName = trim(preg_replace('/\s+/', '', $tableName));
					} catch(Doctrine_Exception $exception) {
						$message = 'an exception was thrown <code>'
							. $exception->getMessage()
							. '</code>'
						;
						throw new L8M_Exception('L8M_VersionUpdater::prepareUpdatePackage : Error while generating SQL update, ' . $message);
					}
					$frDrop = '';
					$idxDrop = '';
					$colDrop = '';

					foreach ($diff as $d) {
						//If there is a changed column
						if (strpos($d, 'hasColumn') !== FALSE) {
							//get column name
							$colName = $this->getStringBetween($d, "hasColumn('", "',");
							if(strpos($updateSql,"ADD COLUMN $colName ") !== FALSE)	{
								$updateSql = str_replace("ADD COLUMN $colName ", "CHANGE `$colName` $colName ", $updateSql);
								continue;
							}
						}

						//If there is a redundant foreign key constraint
						if (strpos($d, 'hasOne') !== FALSE || strpos($d, 'hasMany') !== FALSE) {
							$fModel = 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR . $this->getStringBetween($d, 'Default_Model_', ' ') . '.php';
							$result = file_get_contents($oldPath . DIRECTORY_SEPARATOR . $fModel);
							$fTableName = $this->getStringBetween($result, "setTableName('", "')");

							//get local column name
							$local = trim($this->getStringBetween($d, "local' => '", "',"));
							//get referenced column name
							$foreign = $this->getStringBetween($d, "foreign' => '", "'))");
							if ($foreign == "id"){
								$frDrop .= 'ALTER TABLE ' . $tableName . "\nDROP FOREIGN KEY " . $tableName . '_' . $local . '_' . $fTableName . '_' . $foreign . ";\n\n";
							}
						} else
						//If there is a redundant index
						if (strpos($d, 'index') !== FALSE) {
							$indexName = $this->getStringBetween($d, "index('", "',");
							$idxDrop .= 'ALTER TABLE ' . $tableName . "\nDROP INDEX " . $indexName . ";\n\n";
						}

						//If there is a redundant column
						if (strpos($d, 'hasColumn') !== FALSE) {
							//get column name
							$colName = $this->getStringBetween($d, "hasColumn('", "',");
							$colDrop .= 'ALTER TABLE ' . $tableName . "\nDROP COLUMN " . $colName . ";\n\n";
						}
					}
					$updateSql .= $frDrop . $idxDrop . $colDrop;
				} else
				//Change in Controller
                if(strpos($path, 'Controller.php') !== FALSE) {
					if(!isset($contentOfChangedFiles[$path])) {
						throw new L8M_Exception('L8M_VersionUpdater::prepareUpdatePackage : Error while generating list of actions.');
					}

                    if(strpos($path, 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR) !== FALSE) {
                        $moduleName = "default";
                    } else {
                        $moduleName = $this->getStringBetween($path, 'modules' . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . 'controllers');
                    }

					$className = $this->getStringBetween($path, 'controllers' . DIRECTORY_SEPARATOR, '.php');
					$className = trim(preg_replace('/\s+/', '', $className));
					$classNameArray = preg_split('/(?=[A-Z])/', $className);

					foreach($classNameArray as $key => &$ca) {
						if($ca === '')
							unset($classNameArray[$key]);
						else
							$ca = strtolower($ca);
					}
					array_pop($classNameArray);
					$controllerName = implode('-', $classNameArray);

					$lines1 = $contentOfChangedFiles[$path]['newContent'];
					$lines1 = preg_split('/\n/', $lines1);
					$lines2 = $contentOfChangedFiles[$path]['oldContent'];
					$lines2 = preg_split('/\n/', $lines2);

					$newArray = array();
					$oldArray = array();

					foreach ($lines1 as $new) {
						if((strpos($new, 'function') !== FALSE) && (strpos($new, 'Action') !== FALSE)) {
							$newArray[] = trim($this->getStringBetween($new, 'function', '()'));
						}
					}

					foreach ($lines2 as $old) {
						if((strpos($old, 'function') !== FALSE && (strpos($old, 'Action') !== FALSE))) {
							$oldArray[] = trim($this->getStringBetween($old, 'function', '()'));
						}
					}

					//if an action is added
					$diff = array_diff($newArray, $oldArray);
					foreach ($diff as $d) {
						$functionName = trim(preg_replace('/\s+/', '', $d));
						$functionNameArray = preg_split('/(?=[A-Z])/', $functionName);

						foreach($functionNameArray as &$fa) {
							if($fa === '')
								unset($functionNameArray[$key]);
							else
								$fa = strtolower($fa);
						}
						array_pop($functionNameArray);
						$actionName = implode('-', $functionNameArray);

						$resourceName = $moduleName . '.' . $controllerName . '.' . $actionName;
						$iniActionAdd .= 'add[] = "' . $resourceName . '"' . "\n";
					}

					//if an action is removed
					$diff = array_diff($oldArray, $newArray);
					foreach ($diff as $d) {
						$functionName = trim(preg_replace('/\s+/', '', $d));
						$functionNameArray = preg_split('/(?=[A-Z])/', $functionName);

						foreach($functionNameArray as &$fa) {
							if($fa === '')
								unset($functionNameArray[$key]);
							else
								$fa = strtolower($fa);
						}
						array_pop($functionNameArray);
						$actionName = implode('-', $functionNameArray);

						$resourceName = $moduleName . '.' . $controllerName . '.' . $actionName;
						$iniActionRemove .= 'remove[] = "' . $resourceName . '"' . "\n";
					}
                }
			}
        }

		if(strpos($iniCodeChanges, DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'info.ini') === FALSE) {
			$iniCodeChanges .= 'replace[] = "' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'info.ini' . '"';
			$iniCodeChanges .= "\n\n";
		} else {
			$iniCodeChanges .= "\n";
		}

        if(count($filesToRemove) > 0) {
			foreach($filesToRemove as $path) {
				$iniCodeChanges .= 'remove[] = "' . DIRECTORY_SEPARATOR . $path . '"' . "\n";

				//Model to remove
				if(strpos($path, 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR) !== FALSE) {
					$oldPath1 = $oldPath . DIRECTORY_SEPARATOR . $path;
					$result = file_get_contents($oldPath1);
					$tableName = trim($this->getStringBetween($result, "setTableName('", "')"));
					$translationTablesearchString = "'tableName' => '" . $tableName . "_translation'";
					$updateSql .= 'DROP TABLE ' . $tableName . ";\n\n";
					$iniTableRemove .= 'remove[] = "' . $tableName . '"' . "\n";

					$modelName = $this->getStringBetween($oldPath1, 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR, '.php');
					$iniModelRemove .= 'remove[] = "' . $modelName . '"' . "\n";

					if(strpos($result, $translationTablesearchString) !== FALSE) {
						$updateSql .= 'DROP TABLE ' . $tableName . "_translation;\n\n";
						$iniTableRemove .= 'remove[] = "' . $tableName . '_translation"' . "\n";
					}
				} else
				//Action to remove
				if(strpos($path, 'Controller.php') !== FALSE) {
					if(strpos($path, 'application' . DIRECTORY_SEPARATOR .  'controllers' . DIRECTORY_SEPARATOR) !== FALSE) {
						$moduleName = 'default';
					} else {
						$moduleName = $this->getStringBetween($path, 'modules' . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . 'controllers');
					}

					$className = $this->getStringBetween($path, 'controllers' . DIRECTORY_SEPARATOR, '.php');
					$className = trim(preg_replace('/\s+/', '', $className));
					$classNameArray = preg_split('/(?=[A-Z])/', $className);

					foreach($classNameArray as $key => &$ca) {
						if($ca === '')
							unset($classNameArray[$key]);
						else
							$ca = strtolower($ca);
					}
					array_pop($classNameArray);
					$controllerName = implode('-', $classNameArray);

					$lines1 = file_get_contents($oldPath . DIRECTORY_SEPARATOR . $path);
					$lines1 = preg_split ('/\n/', $lines1);

					foreach ($lines1 as $old) {
						if(strpos($old, 'function') !== FALSE) {
							$functionName = $this->getStringBetween($old, 'function', '()');
							if(strpos($functionName, 'Action')) {
								$functionName = trim(preg_replace('/\s+/', '', $functionName));
								$functionNameArray = preg_split('/(?=[A-Z])/', $functionName);

								foreach ($functionNameArray as &$fa) {
									if($fa === '')
										unset($functionNameArray[$key]);
									else
										$fa = strtolower($fa);
								}
								array_pop($functionNameArray);
								$actionName = implode('-', $functionNameArray);

								$resourceName = $moduleName . '.' . $controllerName . '.' . $actionName;
								$iniActionRemove .= 'remove[] = "' . $resourceName . '"' . "\n";
							}
						}
					}
				}
			}
		}

		$iniCodeChanges .= 'remove[] = "' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'Zend_Loader_PluginLoader_Cache.php' . '"';

		//Changes in tables
		$iniTableChanges = '';
		if(strlen($iniTableAdd) || strlen($iniTableEdit) || strlen($iniTableRemove)) {
			$iniTableChanges = "\n\n[table]\n\n";
			if(strlen($iniTableAdd)) $iniTableChanges .= $iniTableAdd . "\n";
			if(strlen($iniTableEdit)) $iniTableChanges .= $iniTableEdit . "\n";
			if(strlen($iniTableRemove)) $iniTableChanges .= $iniTableRemove . "\n";
		}

		//Changes in actions
		$iniActionChanges = '';
		if(strlen($iniActionAdd) || strlen($iniActionRemove)) {
			$iniActionChanges = "\n\n[action]\n\n";
			if(strlen($iniActionAdd)) $iniActionChanges .= $iniActionAdd . "\n";
			if(strlen($iniActionRemove)) $iniActionChanges .= $iniActionRemove . "\n";
		}

		//Changes in models
		$iniModelChanges = '';
		if(strlen($iniModelAdd) || strlen($iniModelEdit) || strlen($iniModelRemove)) {
			$iniModelChanges = "\n\n[model]\n\n";
			if(strlen($iniModelAdd)) $iniModelChanges .= $iniModelAdd . "\n";
			if(strlen($iniModelEdit)) $iniModelChanges .= $iniModelEdit . "\n";
			if(strlen($iniModelRemove)) $iniModelChanges .= $iniModelRemove . "\n";
		}

		$iniChanges = $iniCodeChanges . $iniTableChanges . $iniActionChanges . $iniModelChanges;

		$upOne = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp');

		$this->updateLocalVersionInfo();
        $filesForUpdatePackage[] = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'info.ini');

		$this->fileWriter($upOne . DIRECTORY_SEPARATOR . 'changes.ini', $iniChanges);
        $filesForUpdatePackage[] = $upOne . DIRECTORY_SEPARATOR . 'changes.ini';

		if(strlen($iniTableAdd) || strlen($iniTableEdit) || strlen($iniTableRemove)) {
			$this->fileWriter($upOne . DIRECTORY_SEPARATOR . 'updates.sql', $updateSql);
			$filesForUpdatePackage[] = $upOne . DIRECTORY_SEPARATOR . 'updates.sql';
		}

		$changesContent = parse_ini_file($upOne . DIRECTORY_SEPARATOR . 'changes.ini', TRUE);
		$this->_updatesList = $changesContent;

		$updatePackagerSuccess = $this->createUpdateZip($filesForUpdatePackage);

		if(!$updatePackagerSuccess) {
			if($this->_environment === L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
				if(file_exists($oldPath)) {
					$this->recursiveDelete($oldPath, TRUE);
				}
			}

			throw new L8M_Exception('L8M_VersionUpdater::compareFiles : Update package could not be created for ' . $remoteServer);
		} else {
			return $this->getUpdatesList();
		}
	}

	/**
     * Gets the string between two character(s)
     *
     * @return string
     */

	private function getStringBetween($string, $start, $end)
	{
		$startStringPosition = strpos($string, $start);

		//If empty string
		if($startStringPosition === FALSE) return '';

		$endStringOffsetPosition = $startStringPosition + strlen($start);
		$endStringPosition = strpos($string, $end, $endStringOffsetPosition);
		if($endStringPosition === FALSE) return '';

		$lengthOfStringPart = $endStringPosition - $endStringOffsetPosition;

        return substr($string, $endStringOffsetPosition, $lengthOfStringPart);
	}

	/**
	 * Write files to file_system
	 *
	 * @return void
	 */
    private function fileWriter($fileName, $dataToSave)
    {
        if($fp = fopen($fileName, 'w')) {
            $startTime = microtime(TRUE);
            do {
				$canWrite = flock($fp, LOCK_EX);

            	// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
            	if(!$canWrite) usleep(round(rand(0, 100) * 1000));
			} while ((!$canWrite) && ((microtime(TRUE) - $startTime) < 5));

            //file was locked so now we can store information
            if ($canWrite) {
				fwrite($fp, $dataToSave);
            	// flock($fp, LOCK_UN);
            }
            fclose($fp);
        }
	}

	/**
	 * Creates a zip file with changes
	 *
	 * @return bool
	 */
	private function createUpdateZip($files = array(), $overwrite = FALSE)
	{
        //if we have good files...
        if(count($files)) {
			$pathPartToReplace = BASE_PATH . DIRECTORY_SEPARATOR;
			$updateFor = $this->getRemoteServerName();

			$upOne = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp');
			$destination = $upOne . DIRECTORY_SEPARATOR . $updateFor . '.update.zip';

            //create the archive
            $zip = new ZipArchive();
            $zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            //add the files
            foreach($files as $file) {
                if(is_file($file)) {
                    if($file == $upOne . DIRECTORY_SEPARATOR . 'changes.ini') {
                        $filepath = str_replace($upOne . DIRECTORY_SEPARATOR , '', $file);
                        $zip->addFile($file, $filepath);
					} else
					if($file == $upOne . DIRECTORY_SEPARATOR . 'updates.sql') {
                        $filepath = str_replace($upOne . DIRECTORY_SEPARATOR, 'sql' . DIRECTORY_SEPARATOR, $file);
                        $zip->addFile($file, $filepath);
                    } else {
						$filepath = 'code' . DIRECTORY_SEPARATOR . str_replace($pathPartToReplace , '', $file);
                        $zip->addFile($file, $filepath);
					}
                } else {
                    $filepath = 'code' . DIRECTORY_SEPARATOR . str_replace($pathPartToReplace , '', $file);
                    $zip->addEmptyDir(str_replace($pathPartToReplace, '', $filepath));
                }
            }
            //close the zip -- done!
			$zip->close();
			$changesIniFile = $upOne . DIRECTORY_SEPARATOR . 'changes.ini';
			if(file_exists($changesIniFile)) unlink($upOne . DIRECTORY_SEPARATOR . 'changes.ini');

			$updatesSqlFile = $upOne . DIRECTORY_SEPARATOR . 'updates.sql';
			if(file_exists($updatesSqlFile)) unlink($upOne . DIRECTORY_SEPARATOR . 'updates.sql');

            //check to make sure the file exists
            return file_exists($destination);
        } else {
            return FALSE;
        }
	}

	/**
	 * Recursively delete a directory and its contents
	 *
	 * @return void
	 */
	private function recursiveDelete($path, $deleteParent = FALSE)
	{
		$directoryIterator = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
		$directoryIterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::CHILD_FIRST);
		foreach($directoryIterator as $item) {
			if ($item->isDir()) rmdir($item->getPathname());
			else unlink($item->getPathname());
		}

		if($deleteParent) {
			rmdir($path);
		}
	}

	/**
	 * Update local version info
	 *
	 * @return void
     * @throws L8M_Exception
	 */
	private function updateLocalVersionInfo()
	{
		$versionInfoFile = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'info.ini';
		$updatePackageFile = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'Update.zip';

		if(!file_exists($versionInfoFile)) {
			throw new L8M_Exception('L8M_VersionUpdater::updateLocalVersionInfo : Local version info not available. Cannot update.');
		} else {
			$localVersionInfo = parse_ini_file($versionInfoFile, TRUE);

			$oldVersion = $localVersionInfo['versionInfo']['lastVersion'];

			if(file_exists($updatePackageFile)) {
				$newVersion = date('YmdHisu');
			} else {
				//If no old file is found
				$newVersion = $oldVersion;
			}
			$versionInfoIniContent = "[versionInfo]\n\n";
			$versionInfoIniContent .= 'currentVersion = ' . $newVersion . "\n";
			$versionInfoIniContent .= 'lastVersion = ' . $oldVersion . "\n";

			$this->fileWriter($versionInfoFile, $versionInfoIniContent);
		}
	}

	/**
     * Extract Zip to destination path
     *
     * @return bool
     */
	private function extractZip($file, $destination)
	{
        $zip = new ZipArchive;
        $res = $zip->open($file);
        if ($res === TRUE) {
            // extract it to the path we determined above
            $zip->extractTo($destination);
			$zip->close();

			$directoryIterator = new RecursiveDirectoryIterator($destination, FilesystemIterator::SKIP_DOTS);
			$directoryIterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::CHILD_FIRST);
			/* foreach($directoryIterator as $item) {
				chmod($item, 0777);
			} */
        }

		return $res;
	}

	/**
	 * Get version action
	 *
	 * @return json
	 */
	public function getRemoteVersionInfo($path)
	{
		$returnArray = array(
			'version' => 'unknown'
		);

		$versionInfoFile = $path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'versionUpdater' . DIRECTORY_SEPARATOR . 'info.ini';
		$versionInfo = parse_ini_file($versionInfoFile);

		if(isset($versionInfo['versionInfo']['currentVersion'])) {
			$returnArray['version'] = $versionInfo['versionInfo']['currentVersion'];
		}

		/**
		 * json
		 */
		$bodyData = Zend_Json_Encoder::encode($returnArray);

		//disable layout
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();

		/**
		 * header
		 */
		$bodyContentHeader = 'application/json';
		$this->getResponse()
			->setHeader('Content-Type', $bodyContentHeader)
			->setBody($bodyData);
	}
	/**
	 * Export Database
	 *
	 * @return void
	 */
    public function Export_Database($host, $user, $pass, $name, $tables, $backupName)
    {
		$content = '';
        $mysqli = new mysqli($host, $user, $pass, $name);
        $mysqli->select_db($name);
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables = $mysqli->query('SHOW TABLES');
        while($row = $queryTables->fetch_row())
        {
            $targetTables[] = $row[0];
        }
        if(count($tables)>0)
        {
            $targetTables = array_intersect($targetTables, $tables);
        }
        foreach($targetTables as $table)
        {
            $result = $mysqli->query('SELECT * FROM '.$table);
            $fields_amount = $result->field_count;
            $rows_num = $mysqli->affected_rows;
            $res = $mysqli->query('SHOW CREATE TABLE '.$table);
            $TableMLine = $res->fetch_row();
            $content = (!isset($content) ?  '' : $content) . "\n\n" . $TableMLine[1] . ";\n\n";

            for($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter=0)
            {
                while($row = $result->fetch_row())
                {
					//when started (and every after 100 command cycle):
                    if($st_counter%100 == 0 || $st_counter == 0)
                    {
                        $content .= "\nINSERT INTO " . $table . " VALUES";
                    }
                    $content .= "\n(";
                    for($j=0; $j<$fields_amount; $j++)
                    {
                        $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                        if(isset($row[$j]))
                        {
                            $content .= '"' . $row[$j] . '"' ;
                        }
                        else
                        {
                            $content .= '""';
                        }
                        if($j < ($fields_amount - 1))
                        {
                            $content .= ',';
                        }
                    }
                    $content .= ")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num)
                    {
                        $content .= ";";
                    }
                    else
                    {
                        $content .= ",";
                    }
                    $st_counter = $st_counter + 1;
                }
            } $content .="\n\n\n";
        }
		$backupName = $backupName ? $backupName : $name . ".sql";
		$fp = fopen(BASE_PATH . DIRECTORY_SEPARATOR . $backupName, 'w');
		fwrite($fp, $content);
		fclose($fp);

		//disable layout
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();
    }

	/**
	 * Drop existing tables from database if exist and import backup database
	 *
	 * @return void
	 */
	public function importBackupDatabase($host, $user, $password, $db, $backupFile)
	{
		// Name of the file
		$filename = $backupFile;
		// MySQL host
		$mysql_host = $host;
		// MySQL username
		$mysql_username = $user;
		// MySQL password
		$mysql_password = $password;
		// Database name
		$mysql_database = $db;

		// Connect to MySQL server
		$con = @new mysqli($mysql_host,$mysql_username,$mysql_password,$mysql_database);

		// Check connection
		if ($con->connect_errno) {
			echo "Failed to connect to MySQL: " . $con->connect_errno;
			echo "<br/>Error: " . $con->connect_error;
		}

		$con->query('SET foreign_key_checks = 0');
		if ($result = $con->query("SHOW TABLES"))
		{
			while($row = $result->fetch_array(MYSQLI_NUM))
			{
				$con->query('DROP TABLE IF EXISTS '.$row[0]);
			}
		}


		// Temporary variable, used to store current query
		$templine = '';
		// Read in entire file
		$lines = file($filename);
		// Loop through each line
		foreach ($lines as $line) {
		// Skip it if it's a comment
			if (substr($line, 0, 2) == '--' || $line == '')
				continue;

		// Add this line to the current segment
			$templine .= $line;
		// If it has a semicolon at the end, it's the end of the query
			if (substr(trim($line), -1, 1) == ';') {
				// Perform the query
				$con->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $con->error . '<br /><br />');
				// Reset temp variable to empty
				$templine = '';
			}
		}

		$con->query('SET foreign_key_checks = 1');
		$con->close();

		//disable layout
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();
	}
}

