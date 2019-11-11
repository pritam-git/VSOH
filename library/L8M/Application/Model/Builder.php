<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/Model/Builder.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Application_Model_Builder
 *
 *
 */
class L8M_Application_Model_Builder extends L8M_Application_Builder_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of required directories.
	 *
	 * @var array
	 */
	protected $_requiredDirectories = array(
		'models',
		'services',
	);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */


	/**
	 * Initializes L8M_Application_Model_Builder instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		if (!isset($this->_options['moduleName'])) {
			throw new L8M_Application_Model_Builder_Exception('Key "moduleName" needs to be present in options.');
		}

		if (!isset($this->_options['modelName'])) {
			throw new L8M_Application_Model_Builder_Exception('Key "modelName" needs to be present in options.');
		}

	}

	/**
	 * Builds components.
	 *
	 * @return void
	 */
	protected function _buildComponents()
	{
		throw new L8M_Application_Model_Builder_Exception(__METHOD__ . 'not implemented yet.');
	}

}