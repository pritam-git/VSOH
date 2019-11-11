<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Salutation.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Salutation.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Model_Salutation
 *
 *
 */
class Default_Model_Salutation extends Default_Model_Base_Salutation
{
	/**
     *
     *
     * Class Variables
     *
     *
     */

	/**
     * An array of field names which are internationalized.
     *
     * @var array
     */
    protected $_i18nFields = array('name');

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
     * Sets this Default_Model_Salutation instance as disabled or enabled.
     *
     * @param  bool $disabled
     * @return Default_Model_Salutation
     */
    public function setDisabled($disabled = TRUE)
    {
        $this->_set('disabled', (bool) $disabled);
        return $this;
    }

	/**
     * Sets the isMale flag of this Default_Model_Salutation instance.
     *
     * @param  bool $isMale
     * @return Default_Model_Salutation
     */
    public function setIsMale($isMale = TRUE)
    {
        $this->_set('is_male', (bool) $isMale);
        return $this;
    }

	/**
     * Sets name of this Default_Model_Salutation instance.
     *
     * @param  string $name
     * @return Default_Model_Salutation
     */
    public function setName($name = NULL)
    {
        $this->__set('name', $name);
        return $this;
    }

   	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

	/**
     * Returns the id of this Default_Model_Salutation instance.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_get('id');
    }

	/**
     * Returns TRUE when this Default_Model_Salutation instance is disabled.
     *
     * @return bool
     */
    public function getDisabled()
    {
        return (bool) $this->_get('disabled');
    }

	/**
     * Returns TRUE when the isMale flag of this Default_Model_Salutation
     * instance is raised.
     *
     * @return bool
     */
    public function getIsMale()
    {
        return (bool) $this->_get('is_male');
    }

	/**
     * Returns name of this Default_Model_Salutation instance.
     *
     * @return string
     */
    public function getName()
    {
        return $this->__get('name');
    }

}