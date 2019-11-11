<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Doctrine.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine
 *
 *
 */
class L8M_Doctrine
{

    /**
     *
     *
     * Class Variables
     *
     *
     */

    /**
     * Contains TRUE when Doctrine is enabled, FALSE when Doctrine is disabled,
     * and NULL when it has not been disabled or enabled yet.
     *
     * @var bool
     */
    protected static $_enabled = NULL;

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Disables Doctrine. Note: this does not have any effect on whether
     * Doctrine gets bootstrapped or not, this class serves merely as a
     * container as it is less cost intensive to
     *
     * @return TRUE
     */
    public static function disable()
    {
        return self::enable(FALSE);
    }

    /**
     * Enables Doctrine.
     *
     * @param  bool $enable.
     * @return void
     */
    public static function enable($enable = TRUE)
    {
        if (self::$_enabled !== NULL) {
            throw new L8M_Doctrine_Exception('Doctrine can only be enabled or disabled once.');
        }
        self::$_enabled = (bool) $enable;
    }

    /**
     * Returns TRUE if Doctrine is enabled.
     *
     * @return bool
     */
    public static function isEnabled()
    {
        return self::$_enabled === TRUE;
    }

    /**
     * Returns TRUE if Doctrine is disabled.
     *
     * @return bool
     */
    public static function isDisabled()
    {
    	$isDisabled = in_array(self::$_enabled, array(NULL, FALSE));
        return $isDisabled;
    }

}