<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/screen/Footer.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Footer.php 339 2015-04-28 11:49:50Z nm $
 */

/**
 *
 *
 * System_View_Helper_Screen_Footer
 *
 *
 */
class Default_View_Helper_Screen_Footer extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a footer.
	 *
	 * @return string
	 */
	public function footer($content = NULL)
	{
		//brand change link display
		$displayBrandLink = '';
		$brandSession = new Zend_Session_Namespace('brand');
		if (L8M_Config::getOption('l8m.brandSwitch.enabled') && isset($brandSession->id)) {
			$displayBrandLink .= '<li><a href="' . $this->view->url(array('module'=>L8M_Acl_CalledFor::module(), 'controller'=>L8M_Acl_CalledFor::controller(), 'action'=>L8M_Acl_CalledFor::action()), NULL, TRUE) . '" id="changeBrandLink">' . $this->view->translate('Marke wechseln', 'de') . '</a></li>';
		}

		$content  = '<div class="footer-top">';

		$content .= '<div class="row">';
		$content .= '<div class="col-md-12 text-center footer-top-text">';
		$content .= '<br><br><a href="https://www.facebook.com" class="facebook"></a><br>';
		$content .= '<a href="' . $this->view->url(array('module'=>'default', 'controller'=>'index', 'action'=>'index'), NULL, TRUE) . '"><img class="footer-logo" src="/img/default/prj/logo_black.png" alt=""></a><br><br>';
		$content .= 'Verband Schweizer Opel Händler<br>';
		$content .= 'Union Suisse Distributeur Opel<br>';
		$content .= 'Unione Svizzera Distributori Opel<br><br>';
		$content .= $this->view->translate('Hohlenweg 11b . CH - 2564 Bellmund', 'de') . '<br><br>';
		$content .= '<a href="tel:0041792084426" class="tel">079 208 44 26</a>';
		$content .= '<a href="mailto:info@vsoh.ch" class="mail">info@vsoh.ch</a>';
		$content .= '</div>';
		$content .= '</div>';   
		$content .= '<div class="row">';
		$content .= '<div class="col-md-12">';
		$content .= '<hr>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '<div class="row">';
			$content .= '<div class="col-md-6 footerlinks">';
			$content .= '&copy;' . $this->view->translate('Verband Schweizer Opel-Händler. All Rights Reserved', 'de');
			$content .= '</div>';
			$content .= '<div class="col-md-6">';
			$content .= '<div class="pull-right">';
			$content .= '<a title="' . $this->view->translate('Top', 'de') . '" id="back-to-top" href="#">↑</a>';
			$content .= '<ul class="footerlinks pull-left">
							'. $displayBrandLink . '
							<li><a href="' . $this->view->url(array('module'=>'default', 'controller'=>'imprint', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Impressum', 'de') . '</a></li>
							<li><a href="' . $this->view->url(array('module'=>'default', 'controller'=>'contact', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Kontakt', 'de') . '</a></li>
							<li><a href="' . $this->view->url(array('module'=>'default', 'controller'=>'privacy-policy', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Datenschutz', 'de') . '</a></li>
							<li><a href="' . $this->view->url(array('module'=>'default', 'controller'=>'terms-and-condition', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Nutzungsbedingungen', 'de') . '</a></li>
							<li><a href="' . $this->view->url(array('module'=>'default', 'controller'=>'search', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Suchen', 'de') . '</a></li>
							<li><a href="' . $this->view->url(array('module'=>'default', 'controller'=>'links', 'action'=>'index'), NULL, TRUE) . '">' . $this->view->translate('Links', 'de') . '</a></li>
						</ul>';
			$content .= '</div>';
			$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '<div class="footer-bottom">';
		$content .= '</div>';






		$display = '<footer>' . $content . '</footer>';

		return $display;
	}

}