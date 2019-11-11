<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Activation.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Activation.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Model_Activation
 *
 *
 */
class Default_Model_Activation extends Default_Model_Base_Activation
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * Default life time of a Default_Model_Activation instance in seconds,
	 * equal to one week (1 week x 7days/week x 24hrs/day x 60min/hr x 60s/min).
	 */
	const LIFETIME_DEFAULT = 604800;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Further constructs Default_Model_Activation instance.
	 *
	 * @return void
	 */
	public function construct()
	{
		parent::construct();
		$this->_set('remote_ip', $_SERVER['REMOTE_ADDR']);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Saves activation, but makes sure an activation code and an expiration
	 * date are set.
	 *
	 * @param  Doctrine_Connection $conn
	 * @return void
	 */
	public function save(Doctrine_Connection $conn = NULL)
	{
		if (!$this->_get('activation_code')) {
			$this->generateActivationCode();
		}

		if ($this->id &&
			$this->hasRelation('Translation')) {

			$this->_removeTranslationFromCache();
		}
		$this->_removeFieldNameFromCache();

		parent::save($conn);
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Generates activation code and updates expiration time. Use $enforce flag
	 * to enforce generation of a new activation code if a code is already
	 * present.
	 *
	 * @return Default_Model_Activation
	 */
	public function generateActivationCode($lifeTime = NULL)
	{
		if (!$this->_get('target') ||
			!$this->_get('target_id') ||
			!$this->_get('remote_ip')) {
			throw new Default_Model_Activation_Exception('In order to generate an activation code, the activation target and its remote ip need to be set.');
		}

		$activationCode = md5(serialize(array(
			$this->_get('target'),
			$this->_get('target_id'),
			$this->_get('remote_ip'),
			microtime(),
		)));

		$this->_set('activation_code', $activationCode);

		$lifeTime = $lifeTime
				  ? (int) $lifeTime
				  : self::LIFETIME_DEFAULT
		;

		$expiresAt = date('Y-m-d H:i:s', time() + $lifeTime);

		$this->_set('expires_at', $expiresAt);

		return $this;
	}

	/**
	 * Returns a URL  that can be used for processing this
	 * Default_Model_Activation instance.
	 *
	 * @return string
	 */
	public function getLink()
	{
		$urlHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('url');
		$activationLink = $urlHelper->url(
			array(
				'module'=>'default',
				'controller'=>'activation',
				'action'=>'activate',
				'code'=>$this->_get('activation_code'),
			),
			NULL,
			TRUE
		);
		return $activationLink;
	}
}