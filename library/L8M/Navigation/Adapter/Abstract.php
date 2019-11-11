<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Navigation/Adapter/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Navigation_Adapter_Abstract
 *
 *
 */
 abstract class L8M_Navigation_Adapter_Abstract
 {

    /**
     *
     *
     * Class Variables
     *
     *
     */

    /**
     * A Zend_Navigation instance.
     *
     * @var Zend_Navigation
     */
    protected $_navigation = NULL;

    /**
     * An array of options.
     *
     * @var array
     */
    protected $_options = NULL;

    /**
     *
     *
     * Class Constructor
     *
     *
     */

    /**
     * Constructs L8M_Navigation_Adapter_Abstract instance.
     *
     * @param  array|Zend_Config $options
     * @return void
     */
     public function __construct($options = NULL)
    {
        if ($options) {
            $this->setOptions($options);
        }
        $this->init();

        return $this;
    }

     /**
     *
     *
     * Setter Methods
     *
     *
     */

    /**
     * Sets options.
     *
     * @param  array|Zend_Config $options
     * @return L8M_Navigation_Adapter_Abstract
     */
    public function setOptions($options = NULL)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        if (!is_array($options)) {
            throw new L8M_Navigation_Adapter_Abstract_Exception('Options need to be specified as an array or a Zend_Config instance.');
        }
        if (!array_key_exists('roleShort', $options)) {
            throw new L8M_Navigation_Adapter_Abstract_Exception('Role short needs to be specified in options as to retrieve only role specific navigation.');
        }
        $this->_options = $options;
    }

    /**
     *
     *
     * Getter Methods
     *
     *
     */

     /**
     * Returns Zend_Navigation instance as provided by this L8M_Navigation_Adapter_Abstract
     * instance.
     *
     * @return Zend_Navigation
     */
    public function getNavigation()
    {
        return $this->_navigation;
    }

    /**
     * Returns a string representing the role held by the current user.
     *
     * @return string
     */
    public function getRoleShort()
    {
        return $this->_options['roleShort'];
    }

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Initializes L8M_Navigation_Adapter_Abstract instance and creates Zend_Navigation
     * instance..
     *
     * @return L8M_Navigation_Adapter_Abstract
     */
    abstract public function init();

 }