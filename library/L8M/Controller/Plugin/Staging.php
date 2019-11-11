<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Staging.php
 * @author     Norbert Marks <nm@l8m.com>
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Staging.php 37 2014-04-10 13:19:03Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Staging
 *
 *
 */
class L8M_Controller_Plugin_Staging extends Zend_Controller_Plugin_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
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
	 * Called before Zend_Controller_Front exits its dispatch loop.
	 *
	 * @return void
	 */
	public function dispatchLoopShutdown()
	{

		if (Zend_Registry::get('environment') == L8M_Environment::ENVIRONMENT_STAGING) {
			$session = new Zend_Session_Namespace('L8M_Controller_Plugin_Staging');

			if (!$session->login) {
				$validatorUserAgent = L8M_Config::getOption('l8m.html.validator.user_agent') . ' ' . L8M_Config::getOption('l8m.html.validator.service_url');

				if ($this->getRequest()->getParam('l8mstaginglogin') &&
					$this->getRequest()->getParam('l8mstagingpassword')) {

					$dbError = FALSE;
					try {
						$entityModel = Doctrine_Query::create()
							->from('Default_Model_Entity m')
							->addWhere('m.login = ? ', array($this->getRequest()->getParam('l8mstaginglogin')))
							->limit(1)
							->execute()
							->getFirst()
						;
						$dbError = FALSE;
					} catch (Doctrine_Exception $exception) {
						$dbError = TRUE;
					}

					if (!$dbError &&
						$entityModel &&
						L8M_Library::checkPasswordHash($entityModel->password, $this->getRequest()->getParam('l8mstagingpassword'))) {

						$session->login = TRUE;
					} else {
						if ($dbError) {
							$this->_loginForm('An error has occurred during a process with the database.');
						} else {
							$this->_loginForm('An error has occurred.');
						}
					}
				} else
				if ($validatorUserAgent == $_SERVER['HTTP_USER_AGENT'] &&
					L8M_Library::isSubnetIP($_SERVER['REMOTE_ADDR'], L8M_Config::getOption('l8m.html.validator.traffic_subnet'))) {

				} else
				if(L8M_Acl_CalledFor::resource() == 'default.version-updater.index' || L8M_Acl_CalledFor::resource() == 'default.version-updater.test-updater') {

				} else {
					$this->_loginForm();
				}
			}
		}
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Staging Login
	 *
	 * @return void
	 */
	protected function _loginForm($errorMsg = NULL)
	{
		$response = $this->getResponse();
		$content = '<p>Please enter now your login and password.</p>' . L8M_Application_Check::getBox($this->_getForm($errorMsg), NULL, 'l8m-model-form-base');
		$response->setBody(L8M_Application_Check::getLayout($content, 'Staging', 'Login'));
	}

	protected function _getForm($errorMsg = NULL)
	{

		if ($errorMsg) {
			$errorMsg = '
				<div class="form-exception">
					<ul class="iconized">
						<li class="exclamation">' . $errorMsg . '</li>
					</ul>
				</div>
			';
		}

		ob_start();

?>
<?php echo $errorMsg; ?>
<form id="formUserLogin" enctype="application/x-www-form-urlencoded" method="post" action="">
	<dl>
		<dt id="login-label"><label for="l8mstaginglogin" class="required">Login</label></dt>
		<dd id="login-element">
			<input type="text" name="l8mstaginglogin" id="l8mstaginglogin" value="" />
		</dd>
		<dt id="password-label"><label for="l8mstagingpassword" class="required">Password</label></dt>
		<dd id="password-element">
			<input type="password" name="l8mstagingpassword" id="l8mstagingpassword" value="" />
		</dd>
		<dd>
			<input type="submit" name="formUserLoginSubmit" id="formUserLoginSubmit" value="Login" />
		</dd>
	</dl>
</form>
<?php

		return ob_get_clean();
	}
}