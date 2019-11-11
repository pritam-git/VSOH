<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Role.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Role.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Model_Role
 *
 *
 */
class Default_Model_Role extends Default_Model_Base_Role
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	const ROLE_ADMIN = 'admin';
	const ROLE_USER  = 'user';
	const ROLE_CUSTOMER = 'customer';
	const ROLE_RESELLER = 'reseller';
	const ROLE_GUEST = 'guest';

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
    protected $_i18nFields = array('name',
    							   'description');

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
     * Sets this Default_Model_Role instance as disabled or enabled.
     *
     * @param  bool $disabled
     * @return Default_Model_Role
     */
    public function setDisabled($disabled = TRUE)
    {
        $this->_set('disabled', (bool) $disabled);
        return $this;
    }

	/**
     * Sets the parent Default_Model_Role instance of this Default_Model_Role
     * instance.
     *
     * @param  Default_Model_Role $role
     * @return Default_Model_Role
     */
    public function setRole($role = NULL)
    {
        $this->_set('Role', $role);
        return $this;
    }

	/**
     * Sets the id of the parent Default_Model_Role instance of this
     * Default_Model_Role instance.
     *
     * @param  int $id
     * @return Default_Model_Role
     */
    public function setRoleId($id = NULL)
    {
        $this->_set('role_id', $id);
        return $this;
    }

	/**
     * Sets short of this Default_Model_Role instance.
     *
     * @param  string $short
     * @return Default_Model_Role
     */
    public function setShort($short = NULL)
    {
        $this->_set('short', $short);
        return $this;
    }

	/**
     * Sets name of this Default_Model_Role instance.
     *
     * @param  string $name
     * @return Default_Model_Role
     */
    public function setName($name = NULL)
    {
        $this->__set('name', $name);
        return $this;
    }

	/**
     * Sets description of this Default_Model_Role instance.
     *
     * @param  string $description
     * @return Default_Model_Role
     */
    public function setDescription($description = NULL)
    {
        $this->__set('description', $description);
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
     * Returns the id of this Default_Model_Role instance.
     *
     * @return int
     */
    public function getId()
    {
        return $this->_get('id');
    }

	/**
     * Returns TRUE when this Default_Model_Role instance is disabled.
     *
     * @return bool
     */
    public function getDisabled()
    {
        return (bool) $this->_get('disabled');
    }

	/**
     * Returns the parent Default_Model_Role associated with this
     * Default_Model_Role instance.
     *
     * @return Default_Model_Role
     */
    public function getRole()
    {
        return $this->_get('Role');
    }

	/**
     * Returns the id of the parent Default_Model_Role associated with this
     * Default_Model_Role instance.
     *
     * @return int
     */
    public function getRoleId()
    {
        return $this->_get('role_id');
    }

	/**
     * Returns short of this Default_Model_Role instance.
     *
     * @return string
     */
    public function getShort()
    {
        return $this->_get('short');
    }

	/**
     * Returns name of this Default_Model_Role instance.
     *
     * @return string
     */
    public function getName()
    {
        return $this->__get('name');
    }

	/**
     * Returns description of this Default_Model_Role instance.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->__get('description');
    }

}