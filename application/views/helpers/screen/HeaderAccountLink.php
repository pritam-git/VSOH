<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/screen/HeaderAccountLink.php
 * @author	 Debopam Parua <debopam.parua@bcssarl.com>
 * @version	$Id: HeaderAccountLink.php 339 2018-12-12 17:49:50Z dp $
 */

/**
 *
 *
 * System_View_Helper_Screen_HeaderAccountLink
 *
 *
 */
class Default_View_Helper_Screen_HeaderAccountLink extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a header account link.
	 *
	 * @return string
	 */
	public function headerAccountLink()
	{
		$returnValue = '';

		if (Zend_Auth::getInstance()->hasIdentity()) {
			$loginUser = Zend_Auth::getInstance()->getIdentity();
			$loginUserName = $loginUser->firstname . ' ' . $loginUser->lastname;
			if ($loginUserName == ' ') {
				$loginUserName = $this->view->translate('Nutzer', 'de');
			}
			$returnValue .= '<li class="account-dropdown dropdown logout-dropdown">';
			$returnValue .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user" aria-hidden="true"></i><span class="caret"></a>';
			$returnValue .= '<ul class="dropdown-menu logged-in-acc-menu">';
			$returnValue .= '<li class="pagescroll hello-div">' . $this->view->translate('Hallo,', 'de') . '  ' . $loginUserName . '</li>';
			$returnValue .= '<li><a href="' . $this->view->url(array('module'=>'default', 'controller'=>'user', 'action'=>'settings'), NULL, TRUE) . '">' . $this->view->translate('Reset Password') . '</a></li>';
			$returnValue .= '<li><a href="' . $this->view->url(array('module'=>'default', 'controller'=>'logout', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Logout', 'de') . '</a></li>';
			$returnValue .= '</ul>';
			$returnValue .= '</li>';
		} else {
			$form = new Default_Form_User_Login2();
			$form->addDecorators(array(
		// 			new L8M_Form_Decorator_Form_Small(),
					new L8M_Form_Decorator_FormHasRequiredElements(),
				))
				->setAction($this->view->url(array('module'=>'default', 'controller'=>'login', 'action'=>'index'), NULL, TRUE))
			;

			$loginForm = (string) $form;
			$forgetPasswordLink = vsprintf(
				'<a href="%1s" title="%2s">%3s</a>',
				array(
					$this->view->url(
						array(
							'module'=>'default',
							'controller'=>'user',
							'action'=>'retrieve-password',
						),
						NULL,
						TRUE
					),
					$this->view->translate('forget password?'),
					$this->view->translate('forget password?'),
				)
			);

			$returnValue .= '<li class="account-dropdown dropdown login-dropdown">';
			$returnValue .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-sign-in" aria-hidden="true"></i><span class="caret"></a>';
			//$returnValue .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span>Login</span><span class="caret"></a>';
			$returnValue .= '<ul class="dropdown-menu logged-out-acc-menu">';
			$returnValue .= '<li>' . $loginForm . '</li>';
			$returnValue .= '<li>' . $forgetPasswordLink . '</li>';
			$returnValue .= '</ul>';
			$returnValue .= '</li>';
		}

		return $returnValue;
	}
}