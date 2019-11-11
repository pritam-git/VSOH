<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/ProductOptionModel/CreateModelName/Generate.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: Generate.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_ProductOptionModel_CreateModelName_Generate
 *
 *
 */
class PRJ_ProductOptionModel_CreateModelName_Generate
{


	/**
	 * Generate
	 *
	 * @param String $modelName
	 * @param String $YmlModelName
	 * @param String $tableName
	 * @return void
	 */
	public static function start($modelName, $YmlModelName, $tableName)
	{
		$returnValue = NULL;

		/**
		 * importSchemaOptions
		 */
		$importSchemaOptions = L8M_Config::getOption('doctrine.options.builder');

		/**
		 * modelsPath
		 */
		$modelsPath = L8M_Config::getOption('doctrine.options.modelsPath');

		/**
		 * schemaPath
		 */
		$schemaFile = L8M_Config::getOption('doctrine.options.yamlPath') . DIRECTORY_SEPARATOR . 'system-generated.yml';

		/**
		 * add model to system-generated YML file
		 */
		if (file_exists($schemaFile) &&
			is_readable($schemaFile) &&
			is_writable($schemaFile)) {

			if (filesize($schemaFile) === 0) {
				$data = self::_generateYML($YmlModelName, $tableName, TRUE);
			} else {
				$data = self::_generateYML($YmlModelName, $tableName);
			}
			file_put_contents($schemaFile, $data, FILE_APPEND);
		} else {
			if (!file_exists($schemaFile)) {
				file_put_contents($schemaFile, self::_generateYML($YmlModelName, $tableName));
			} else {
				$returnValue = self::_handleFielNotWritable($schemaFile);
			}
		}

		/**
		 * generate model if there is no error
		 */
		if ($returnValue == NULL) {

			/**
			 * importSchema
			 */
			$importSchema = new Doctrine_Import_Schema();
			$importSchema->setOptions($importSchemaOptions);

			try {
				$importSchema->importSchema($schemaFile, 'yml', $modelsPath);
			} catch (Doctrine_Exception $exception) {
				$returnValue = self::_handleException($exception);
			}

			/**
			 * generate tables if there is no error
			 */
			if ($returnValue == NULL) {
				/**
				 * create tables in database using models found in modelPath
				 */
				try {
					Doctrine_Core::createTablesFromArray(array($modelName));
				} catch (Doctrine_Exception $exception) {
					$returnValue = self::_handleException($exception);
				}

				/**
				 * add model name and stuff to database
				 */
				if ($returnValue == NULL) {
					$modelNameModel = new Default_Model_ModelName();
					$modelNameModel->name = $modelName;
					$modelNameModel->save();

					$columns = array(
						'id',
						'short',
						'name',
						'position',
					);
					foreach ($columns as $column) {
						$modelColumnNameModel = new Default_Model_ModelColumnName();
						$modelColumnNameModel->merge(array(
							'name'=>$column,
							'model_name_id'=>$modelNameModel->id,
						));
						$modelColumnNameModel->save();
					}

					$msg = vsprintf(L8M_Translate::string('"%1s" has been generated.'),
						array(
							$modelName,
						)
					);
					$returnValue = '<h3>' . L8M_Translate::string('Done') . '</h3><p>' . $msg . '</p>';
				}
			}
		}

		return $returnValue;
	}

	/**
	 * Handles Doctrine Exception, i.e., renders it.
	 *
	 * @param  Doctrine_Exception $exception
	 * @param  string             $prepend
	 * @return void
	 */
	private static function _handleException($exception = NULL)
	{
		if (!$exception instanceof Doctrine_Exception) {
			throw $exception;
		}

		ob_start();

?>
<h3><?php echo L8M_Translate::string('An exception has been thrown'); ?></h3>
<p><code><?php echo $exception->getMessage(); ?></code></p>
<?php

		$trace = explode('#', $exception->getTraceAsString());
		array_shift($trace);

		if (count($trace)>0) {
?>
<h2>Trace</h2>
<ul class="iconized">
<?php
			foreach($trace as $traceStep) {
?>
	<li class="exclamation"><?php echo $traceStep; ?></li>
<?php
			}

?>
</ul>
<?php
		}

		return ob_get_clean();
	}

	/**
	 * Handles File not writable, renders it.
	 *
	 * @param String $filename
	 * @return void
	 */
	private static function _handleFielNotWritable($filename)
	{

		$errorMsg = vsprintf(L8M_Translate::string('"%1s" is not read and / or writable.'),
			array(
				$filename,
			)
		);

		ob_start();

?>
<h3><?php echo L8M_Translate::string('An exception has been thrown'); ?></h3>
<p><code><?php echo $errorMsg; ?></code></p>
<?php

		return ob_get_clean();
	}

	/**
	 * Generate YML schema.
	 *
	 * @param unknown_type $YmlModelName
	 * @param unknown_type $tableName
	 * @param unknown_type $addHead
	 * @return void
	 */
	private static function _generateYML($YmlModelName, $tableName, $addHead = FALSE)
	{

		ob_start();
		if ($addHead) {
?>
################################################################################
#
# l8m_blank
#
#
# @filesource /application/doctrine/schema/system-generated.yml
# @author     Norbert Marks <nm@l8m.com>
# @version    $Id: Generate.php 7 2014-03-11 16:18:40Z nm $
#
################################################################################

detect_relations: true
options:
  collate: utf8_bin
  charset: utf8
  type: InnoDB
<?php echo PHP_EOL; ?>
<?php

		}

?>
<?php echo $YmlModelName; ?>:
  tableName: <?php echo $tableName . PHP_EOL; ?>
  actAs:
    Timestampable:
    SoftDelete:
  columns:
    id:
      type: integer(11)
      primary: true
      unsigned: true
      notnull: true
      autoincrement: true
    short:
      type: string(120)
      unique: true
      notnull: true
    name:
      type: string(120)
      notnull: true
    position:
      type: integer(11)
      unsigned: true
<?php echo PHP_EOL; ?>
<?php

		return ob_get_clean();
	}
}