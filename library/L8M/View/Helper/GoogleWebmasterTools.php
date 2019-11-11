<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/GoogleWebmasterTools.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: GoogleWebmasterTools.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_GoogleWebmasterTools
 *
 *
 */
class L8M_View_Helper_GoogleWebmasterTools extends Zend_View_Helper_HeadMeta
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array with GoogleWebmasterTools options.
	 *
	 * @var string
	 */
	protected $_options = NULL;

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Sets headMeta according to specified Google Webmaster Tools code.
     *
     * @param  string|array|Zend_Config $options
     * @return L8M_View_Helper_GoogleWebmasterTools
     */
    public function googleWebmasterTools($options = NULL)
    {
        $this->setOptions($options);
        return $this;
	}

	/**
	 *
	 *
	 * Class Setters
	 *
	 *
	 */

    /**
     * Sets options.
     *
     * @param array $options
     * @return L8M_View_Helper_GoogleWebmasterTools
     */
    public function setOptions($options = NULL)
    {
        if (is_string($options)) {
    		$options = array('code'=>$options);
        } else

        if ($options instanceof Zend_Config) {
        	$options = $options->toArray();
        }

        if (is_array($options) &&
            array_key_exists('code', $options)) {
            $this->setCode($options['code']);
        }
    	return $this;
    }

	/**
	 * Sets GoogleWebmasterTools code.
	 *
	 * @param  string $code
	 * @return L8M_View_Helper_GoogleWebmasterTools
	 */
	public function setCode($code = NULL)
	{
		$this->_options['code'] = (string) $code;
		parent::headMeta($this->_options['code'], 'google-site-verification');
		return $this;
	}

	/**
	 *
	 *
	 * Class Getters
	 *
	 *
	 */

	/**
	 * Returns GoogleWebmasterTools code.
	 *
	 * @return string
	 */
	public function getCode()
	{
		return $this->_options['code'];
	}

}