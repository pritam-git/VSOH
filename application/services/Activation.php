<?php

/**
 * L8M
 *
 *
 * @filesource /application/service/Activation.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Activation.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Service_Activation
 *
 *
 */
class Default_Service_Activation extends Default_Service_Base_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * A string representing the name of the class that serves as a template for
	 * the "Activatable" behaviour.
	 */
	const TEMPLATE_ACTIVATABLE = 'L8M_Doctrine_Template_Activatable';

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Returns TRUE if specified code is an MD5 hash.
     *
     * @param  string $code
     * @return bool
     */
    public static function isCode($code = NULL)
    {
        return preg_match('/^[0-9a-f]{32}$/i', $code);
    }

    /**
     * Attempts to create a Default_Model_Activation instance from the specified
     * model instance. A requirement for the model instance is that it contains
     * a column named "activated_at", as this column is used for indicating
     * whether a record has been activated or not.
     *
     * @param  Doctrine_Record $record
     * @return Default_Model_Activation
     */
    public static function fromDoctrineRecord($record = NULL)
    {
    	if (!($record instanceof Doctrine_Record)) {
    		throw new Default_Service_Activation_Exception('Record needs to be specified as a Doctrine_Record instance.');
    	}

    	if (!($record->getTable()->hasTemplate(self::TEMPLATE_ACTIVATABLE))) {
    		throw new Default_Service_Activation_Exception('The underlying Doctrine_Table instance needs to act as "' . self::TEMPLATE_ACTIVATABLE . '".');
    	}

    	$activation = new Default_Model_Activation();
    	$activation->target = get_class($record);
    	$activation->target_id = $record->id;

		return $activation;
    }

}