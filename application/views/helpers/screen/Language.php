<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/Screen/Language.php
 * @author     Santino Lange <sl@l8m.com>
 * @version    $Id: Language.php 204 2015-03-23 11:07:00Z rq $
 */

/**
 *
 *
 * Default_View_Helper_Screen_Language
 *
 *
 */
class Default_View_Helper_Screen_Language extends L8M_View_Helper
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
	public function language()
	{
		$display = NULL;

		if (count(L8M_Locale::getSupported()) > 1) {

			$display.= $this->_languageMenu() . PHP_EOL;

//			$display = '<div class="lang col-xs-6 col-lg-12 col-md-12 col-sm-12">' . $display . '</div>';

		}
		return $display;
	}

	private function _getLanguageName($value) {
		$returnValue = $value;
		$value = strtoupper($value);

		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Language m')
			->addWhere('m.iso_2 = ? ', array($value))
			->limit(1)
			->execute()
			->getFirst()
		;
		if ($countryModel) {
			$returnValue = $countryModel->name_local;
		}

		return $returnValue;
	}

	private function _languageMenu() {

		$display = NULL;

		$cssClass = NULL;
		if (count(L8M_Locale::getSupported()) == 1) {
			$cssClass = 'only-language';
		}

		foreach (array_reverse(L8M_Locale::getSupported()) as $lang) {
			$cssClassActive = NULL;
			if ($lang == L8M_Locale::getLang()) {
				$cssClassActive = ' active';
			}

			$display.= '<li class="lang-' . $lang . $cssClassActive . '"><a href="' . $this->view->url(array('module'=>L8M_Acl_CalledFor::module(), 'controller'=>L8M_Acl_CalledFor::controller(), 'action'=>L8M_Acl_CalledFor::action(), 'lang'=>$lang), NULL, TRUE) . '" title="' . $this->_getLanguageName($lang) . '">' . $lang . '</a></li>';

		}

		$display = '<ul class="lang-menu ' . $cssClass . '">' . $display . '</ul>';

		return $display;
	}
}