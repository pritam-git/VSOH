<?php


/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import
 *
 *
 */
class L8M_Doctrine_Import
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * The namespace for L8M_Doctrine_Import_Abstract descendants.
	 *
	 * @var string
	 */
	protected static $_namespace = 'L8M_Doctrine_Import_';

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Creates an L8M_Doctrine_Import instance from the specified options.
	 *
	 * @param  string            $model
	 * @param  array|Zend_Config $options
	 * @return L8M_Doctrine_Import_Abstract
	 */
	public static function factory($model = NULL, $options = NULL)
	{
		if (!is_string($model)) {
			throw new L8M_Doctrine_Import_Exception('Model needs to be specified as string.');
		}

		/**
		 * we don't have an import class yet
		 */
		$importClass = NULL;

		/**
		 * if a model class prefix is present in options, let's try to retrieve an import
		 * class from the corresponding module
		 */
		if (isset($options['options']['builder']['classPrefix'])) {
			$importClass = $options['options']['builder']['classPrefix']
						 . $model
						 . '_Import'
			;
			if (!class_exists($importClass)) {
	        	try {
	            	@Zend_Loader::loadClass($importClass);
	        	} catch (Zend_Exception $exception) {
	        	}
			}
        }

        /**
         * if no model class prefix is present or no module specific import
         * class could be found, attempt to use an import class from the library
         * or the model is always importable model then import
         */
        if (!$importClass ||
        	!class_exists($importClass) ||
        	(
        		isset($options['options']['builder']['alwaysImport']) &&
        		is_array($options['options']['builder']['alwaysImport']) &&
        		in_array($model, $options['options']['builder']['alwaysImport'])
        	) ) {

			$importClass = self::$_namespace
						 . $model
			;

			if (!class_exists($importClass)) {
				try {
	            	@Zend_Loader::loadClass($importClass);
	        	} catch (Zend_Exception $exception) {
	        		throw new L8M_Doctrine_Import_Exception($exception->getMessage());
	        	}
	        }

		}

		/**
		 * check whether the import class actually extends L8M_Doctrine_Import_Abstract
		 */
        $reflectionClass = new ReflectionClass($importClass);
        if (!$reflectionClass->isSubclassOf('L8M_Doctrine_Import_Abstract')) {
        	throw new L8M_Doctrine_Import_Exception($importClass . ' does not extend L8M_Doctrine_Import_Abstract.');
        }

        return new $importClass($options);

	}

}