<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/screen/HeaderMenu.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: HeaderMenu.php 339 2015-04-28 11:49:50Z nm $
 */

/**
 *
 *
 * System_View_Helper_Screen_HeaderMenu
 *
 *
 */
class Default_View_Helper_Screen_HeaderMenu extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a header menu.
	 *
	 * @return string
	 */
	public function headerMenu()
	{
		$returnValue = '';
		$returnValue2 = '';

		if (Zend_Auth::getInstance()->hasIdentity()) {
			$loginUser = Zend_Auth::getInstance()->getIdentity();
			$archiveDropDown = '';

			//get user regions
			$regionId = $loginUser->region_id;
			if (!empty($regionId)) {
				$regionUrl = $this->view->url(array('module' => 'default', 'controller' => 'region-dates', 'action' => 'index', 'region' => $regionId), NULL, TRUE);
				$archiveRegionUrl = $this->view->url(array('module' => 'default', 'controller' => 'archive-region-dates', 'action' => 'index', 'region' => $regionId), NULL, TRUE);
			} else {
				$regionUrl = $this->view->url(array('module' => 'default', 'controller' => 'region-dates', 'action' => 'index'), NULL, TRUE);
				$archiveRegionUrl = $this->view->url(array('module' => 'default', 'controller' => 'archive-region-dates', 'action' => 'index'), NULL, TRUE);
			}

			$archiveDropDown .= '<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . $this->view->translate('Archiv', 'de') . '<span class="caret"></a>';
			$archiveDropDown .= '<ul class="dropdown-menu">';
			$archiveDropDown .= '<li><a href="' . $this->view->url(array('module' => 'default', 'controller' => 'archive-news', 'action' => 'index'), NULL, TRUE) . '">' . $this->view->translate('News', 'de') . '</a></li>';
			$archiveDropDown .= '<li><a href="' . $this->view->url(array('module' => 'default', 'controller' => 'archive-dates', 'action' => 'index'), NULL, TRUE) . '">' . $this->view->translate('Termine', 'de') . '</a></li>';
			$archiveDropDown .= '<li><a href="' . $archiveRegionUrl . '">' . $this->view->translate('Region', 'de') . '</a></li>';
			$archiveDropDown .= '<li><a href="' . $this->view->url(array('module' => 'default', 'controller' => 'archive-otc', 'action' => 'index'), NULL, TRUE) . '">' . $this->view->translate('OTC', 'de') . '</a></li>';
			$archiveDropDown .= '<li><a href="' . $this->view->url(array('module' => 'default', 'controller' => 'archive-assembly', 'action' => 'index'), NULL, TRUE) . '">' . $this->view->translate('Mitgliederversammlung', 'de') . '</a></li>';
			$archiveDropDown .= '<li><a href="' . $this->view->url(array('module' => 'default', 'controller' => 'archive-commissions', 'action' => 'index'), NULL, TRUE) . '">' . $this->view->translate('Kommissionen', 'de') . '</a></li>';
			$archiveDropDown .= '</ul>';
		}

		if (Zend_Auth::getInstance()->hasIdentity()) {
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'index', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Home', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'about-us', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Über Uns', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'partner', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Partner', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'news', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('News', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'dates', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Termine', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $regionUrl . '">' . $this->view->translate('Region', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'otc', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('OTC', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'assembly', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Mitgliederversammlung', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'commissions', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Kommissionen', 'de') . '</a></li>';
			if(empty($loginUser->parent_user_id)) {
				$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module' => 'default', 'controller' => 'address-management', 'action' => 'index'), NULL, TRUE) . '">' . $this->view->translate('Adressverwaltung', 'de') . '</a></li>';
			}
			$returnValue .= '<li class="dropdown">'.$archiveDropDown.'</li>';

		} else {
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'index', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Home', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'about-us', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Über Uns', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'partner', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Partner', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'membership', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Mitgliedschaft', 'de') . '</a></li>';
			$returnValue .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'contact', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Kontakt', 'de') . '</a></li>';
		}

// 		if (Zend_Auth::getInstance()->hasIdentity()) {
// 			$returnValue .= '<li><a href="' . $this->view->url(array('module'=>'default', 'controller'=>'logout', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Logout') . '</a></li>';
// 		}

		if (Zend_Auth::getInstance()->hasIdentity()) {
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'index', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Home', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'about-us', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Über Uns', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'partner', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Partner', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'news', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('News', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'dates', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Termine', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $regionUrl . '">' . $this->view->translate('Region', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'otc', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('OTC', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'assembly', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Mitgliederversammlung', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'commissions', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Kommissionen', 'de') . '</a></li>';
			if(empty($loginUser->parent_user_id)) {
				$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module' => 'default', 'controller' => 'address-management', 'action' => 'index'), NULL, TRUE) . '">' . $this->view->translate('Adressverwaltung', 'de') . '</a></li>';
			}
			$returnValue2 .= '<li class="dropdown">'.$archiveDropDown.'</li>';
		} else {
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'index', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Home', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'about-us', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Über Uns', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'partner', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Partner', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'membership', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Mitgliedschaft', 'de') . '</a></li>';
			$returnValue2 .= '<li><a class="pageScroll" href="' . $this->view->url(array('module'=>'default', 'controller'=>'contact', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Kontakt', 'de') . '</a></li>';
		}

		$returnValue = '
			<div class="hidden-xs hidden-sm hidden-md hidden-lg menu navbar-collapse">
				<nav class="menu"><ul class="text-right navbar-nav">' . $returnValue . '</ul></nav>
			</div>
			<div class="col-xs-12 text-right menu">
				<nav id="mobile-menu"><ul>' . $returnValue2 . '</ul></nav>
			</div>
		';

		return $returnValue;
	}
}
