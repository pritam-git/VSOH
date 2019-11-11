<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Google/Api.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Api.php 530 2017-05-29 20:12:16Z nm $
 */

/**
 *
 *
 * L8M_Google_Api
 *
 *
 */
class L8M_Google_Api
{

    /**
     *
     *
     * Class Variables
     *
     *
     */

    /**
	 * A string representing the GoogleMaps API key.
	 *
	 * @var string
	 */
	protected static $_apiKey = NULL;

	/**
     * An L8M_Google_Api instance.
     *
     * @var L8M_Google_Api
     */
	protected static $_instance = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Google_Api instance.
	 *
	 * @return void
	 */
	protected function __construct()
	{

	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns L8M_Google_Api instance.
	 *
	 * @return L8M_Google_Api
	 */
	public static function getInstance()
	{
		if (self::$_instance===NULL) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Sets API key for GoogleMaps.
	 *
	 * @param  string $apiKey
	 * @return L8M_Google_Maps_Api
	 */
	public static function setApiKey($apiKey = NULL)
	{
		if (is_array($apiKey) &&
			array_key_exists('browser', $apiKey)) {

			$apiKey = $apiKey['browser'];
		}
		self::$_apiKey = (string) $apiKey;
	}

	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

	/**
	 * Returns Google Ajax API key.
	 *
	 * @return string
	 */
	public static function getApiKey()
	{
		return self::$_apiKey ;
	}

}