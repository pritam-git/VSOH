<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Acl.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Acl.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Acl
 *
 *
 */
class L8M_Acl extends Zend_Acl
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */
	const ROLE_GUEST_ID = 1;
	const ROLE_GUEST_SHORT = 'guest';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A name to be used for the Zend_Session_Namespace needed by this plugin
	 *
	 * @var string
	 * @todo consider name
	 */
	protected static $_sessionNamespace = 'L8M_Controller_Plugin_AuthControlled';

	/**
	 * An array with authentication data.
	 *
	 * @var Zend_Acl
	 */
	protected static $_acl = NULL;

	/**
	 * An L8M_Acl_Adapter_Abstract instance.
	 *
	 * @var L8M_Acl_Adapter_Abstract
	 */
	protected static $_adapter = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Acl instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		/**
		 * default roles
		 */
		$this->addRole(new L8M_Acl_Role(self::ROLE_GUEST_ID, self::ROLE_GUEST_SHORT));

		/**
		 * default resources
		 */
		$this
			->add(new L8M_Acl_Resource('default'))
			->add(new L8M_Acl_Resource('default', 'index'), new L8M_Acl_Resource('default'))
			->add(new L8M_Acl_Resource('default', 'index', 'index'), new L8M_Acl_Resource('default', 'index'))
			->add(new L8M_Acl_Resource('default', 'error'), new L8M_Acl_Resource('default'))
			->add(new L8M_Acl_Resource('default', 'error', 'error'), new L8M_Acl_Resource('default', 'error'))
			->add(new L8M_Acl_Resource('default', 'error', 'error403'), new L8M_Acl_Resource('default', 'error'))
			->add(new L8M_Acl_Resource('default', 'error', 'error404'), new L8M_Acl_Resource('default', 'error'))
			->add(new L8M_Acl_Resource('default', 'error', 'error503'), new L8M_Acl_Resource('default', 'error'))
			->add(new L8M_Acl_Resource('system'))
			->add(new L8M_Acl_Resource('system', 'cache'), new L8M_Acl_Resource('system'))
			->add(new L8M_Acl_Resource('system', 'cache', 'clear'), new L8M_Acl_Resource('system', 'cache'))
			->add(new L8M_Acl_Resource('system', 'configuration'), new L8M_Acl_Resource('system'))
			->add(new L8M_Acl_Resource('system', 'session'), new L8M_Acl_Resource('system'))
			->add(new L8M_Acl_Resource('system', 'session', 'clear'), new L8M_Acl_Resource('system', 'session'))
			->add(new L8M_Acl_Resource('system', 'setup'), new L8M_Acl_Resource('system'))
			->add(new L8M_Acl_Resource('system', 'setup', 'index'), new L8M_Acl_Resource('system','setup'))
			->add(new L8M_Acl_Resource('system', 'setup', 'process'), new L8M_Acl_Resource('system','setup'))
		;

		/**
		 * default permissions
		 *
		 * @todo reconsider and remove
		 */
		$this->allow(new L8M_Acl_Role(self::ROLE_GUEST_ID, self::ROLE_GUEST_SHORT));

	}

	/**
	 *
	 *
	 * Class Setters
	 *
	 *
	 */

	/**
	 * Sets L8M_Acl_Adapter_Abstract instance.
	 *
	 * @param  L8M_Acl_Adapter_Abstract $adapter
	 * @return L8M_Acl
	 */
	public static function setAdapter($adapter = NULL)
	{
		if (!($adapter instanceof L8M_Acl_Adapter_Abstract)) {
			throw new L8M_Acl_Exception('Adapter needs to be specified as an L8M_Acl_Adapter_Abstract instance.');
		}
		self::$_adapter = $adapter;
	}

	/**
	 * Returns L8M_Acl instance from L8M_Acl_Adapter_Abstract instance.
	 *
	 * @param  L8M_Acl_Adapter_Abstract $adaptert
	 * @return L8M_Acl
	 */
	public static function fromAdapter($adapter = NULL)
	{
		if ($adapter) {
			self::setAdapter($adapter);
		}
		if (!self::$_adapter) {
			throw new L8M_Acl_Exception('No adapter instance has been specified.');
		}
		return self::$_adapter->getAcl();
	}

	/**
	 * Returns acl.
	 *
	 * @return Zend_Acl
	 */
	public static function getAcl()
	{
		if (self::$_acl === NULL) {
			$session = new Zend_Session_Namespace(self::$_sessionNamespace);
			if (!isset($session->acl)) {

				/**
				 * load acl as Zend_Acl and try to load some more from database
				 */
				$session->acl = new Zend_Acl();
				$session->acl = $this->addRole($session->acl);
			}
			self::$_acl = $session->acl;
		}
		return self::$_acl;
	}
}