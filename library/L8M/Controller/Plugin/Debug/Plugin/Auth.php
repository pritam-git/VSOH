<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Auth.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Auth.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Auth
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Auth extends ZFDebug_Controller_Plugin_Debug_Plugin_Auth
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of icons assigned to different roles.
	 *
	 * @var array
	 */
	protected $_roleIcons = array(
		'guest'=>'',
		'user'=>'',
		'admin'=>''
	);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Gets menu tab for the Debugbar
	 *
	 * @return string
	 */
	public function getTab()
	{
		if (!$this->_auth->hasIdentity()) {
			return 'Not authorized';
		}

		$identity = $this->_auth->getIdentity();

		if ($identity instanceof Default_Model_Entity) {
			$login = $identity->login;
			$role = $identity->Role->name;
		} else

		if (is_object($identity) &&
			isset($identity->{$this->_login}) &&
			isset($identity->{$this->_role})) {
			$login = $identiy->{$this->_login};
			$role = $identiy->{$this->_role};
		} else

		if (is_array($identity) &&
			isset($identity[$this->_login]) &&
			isset($identity[$this->_role])) {
			$login = $identity[$this->_login];
			$role = $identity[$this->_login];
		} else {
			$login = $this->_auth->getIdentity();
			$role = 'n/a';
		}

		return $login . ' (' . $role . ')';
	}

	/**
	 * Gets content panel for the Debugbar.
	 *
	 * @return string
	 */
	public function getPanel()
	{
		ob_start();

?>
<h4>Authentication</h4>
<?php
		/**
		 * login
		 */
		if(!$this->_auth->hasIdentity()) {
?>
<p><a class="iconized user" href="/login/" title="Login">Login</a></p>
<?php
		} else {

			$identity = $this->_auth->getIdentity();

			if ($identity instanceof Default_Model_Entity) {
				$login = $identity->login;
				$role = $identity->Role->name;
			} else

			if (is_object($identity) &&
				isset($identity->{$this->_login}) &&
				isset($identity->{$this->_role})) {
				$login = $identiy->{$this->_login};
				$role = $identiy->{$this->_role};
			} else

			if (is_array($identity) &&
				isset($identity[$this->_login]) &&
				isset($identity[$this->_role])) {
				$login = $identity[$this->_login];
				$role = $identity[$this->_login];
			} else {
				$login = $this->_auth->getIdentity();
				$role = 'n/a';
			}

?>
<h5>Logged in</h5>
<?php

			L8M_Library::dataShow(array(
				'Login'=>$login,
				'Role'=>$role,
			));

?>
<p><a class="iconized user" href="/logout/" title="Logout">Logout</a></p>
<?php
		}

		return ob_get_clean();
	}
}