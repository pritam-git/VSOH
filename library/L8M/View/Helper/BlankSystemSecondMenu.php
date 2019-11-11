<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/BlankSystemSecondMenu.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BlankSystemSecondMenu.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_BlankSystemSecondMenu
 *
 *
 */
class L8M_View_Helper_BlankSystemSecondMenu extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private static $_activeControllerMenuName = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Create Second-Menu
	 *
	 * @return string
	 */
	public function blankSystemSecondMenu($getActiveControllerMenuName = FALSE)
	{
		$returnValue = NULL;
		$requestObj = Zend_Controller_Front::getInstance()->getRequest();

		if ($getActiveControllerMenuName) {
			$returnValue = self::$_activeControllerMenuName;
		} else {
			if ($this->view->layout()->isException) {
				$rememberOldCalledForControllerName = $this->view->layout()->rememberOldCalledForControllerName;
				if (count($rememberOldCalledForControllerName) > 0) {
					$activeController = array_shift($rememberOldCalledForControllerName);
				} else {
					$activeController = $this->view->layout()->rememberCalledForControllerName;
				}
			} else {
				$activeController = $this->view->layout()->calledForControllerName;
			}
			$menuContent = NULL;
			foreach ($this->view->blankSystemMenuStructure() as $menuBox) {
				$subMenu = NULL;
				$activeCss = NULL;

				foreach ($menuBox['links'] as $menuBoxLink) {
					$subActiveCss = NULL;
					if ($menuBoxLink['controller'] == $activeController) {
						$activeGoOn = TRUE;
						if (array_key_exists('hasToHaveParam', $menuBoxLink) &&
							is_array($menuBoxLink['hasToHaveParam']) &&
							count($menuBoxLink['hasToHaveParam']) > 0) {

							foreach ($menuBoxLink['hasToHaveParam'] as $hasToHaveParam => $hasToHaveParamValue) {
								if ($hasToHaveParamValue != $requestObj->getParam($hasToHaveParam)) {
									$activeGoOn = FALSE;
								}
							}
						}
						if ($activeGoOn) {
							$subActiveCss = 'active ';
							$activeCss = ' active';
							self::$_activeControllerMenuName = $menuBoxLink['name'];
						}
					}

					if (!isset($menuBoxLink['showOnlyInEnvironment'])) {
						$subMenu .= '<li class="' . $subActiveCss . $menuBoxLink['css'] . '"><a href="' . $menuBoxLink['link'] . '">' . $menuBoxLink['name'] . '</a></li>';
					} else
					if (isset($menuBoxLink['showOnlyInEnvironment']) &&
						$menuBoxLink['showOnlyInEnvironment'] == L8M_Environment::getInstance()->getEnvironment()) {

						$subMenu .= '<li class="' . $subActiveCss . $menuBoxLink['css'] . '"><a href="' . $menuBoxLink['link'] . '">' . $menuBoxLink['name'] . '</a></li>';
					}
				}

				if ($subMenu) {
					$subMenu = '<ul class="sub iconized">' . $subMenu . '</ul>';
					$menuContent .= '<li class="main' . $activeCss . '"><a href="" class="second-menu">' . $menuBox['name'] . '</a>' . $subMenu . '</li>';
				}
			}
			if ($menuContent) {
				$returnValue = '<ul class="second-menu">' . $menuContent . '</ul>';
			}
		}

		return $returnValue;
	}
}