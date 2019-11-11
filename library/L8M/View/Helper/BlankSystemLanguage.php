<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/BlankSystemLanguage.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BlankSystemLanguage.php 204 2015-03-23 11:07:00Z rq $
 */

/**
 *
 *
 * L8M_View_Helper_BlankSystemLanguage
 *
 *
 */
class L8M_View_Helper_BlankSystemLanguage extends L8M_View_Helper
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
	public function blankSystemLanguage()
	{
		$display = NULL;

		if (count(L8M_Locale::getSupported()) > 1) {

			$display.= '<a><i class="fa fa-globe"></i> ' . $this->_getLanguageName(L8M_Locale::getLang()) . '</a>';

			$display.= $this->_languageMenu() . PHP_EOL;

			$display = '<li class="lang-container">' . $display . '</li>';

			$display = '<ul>' . $display . '</ul>';

			$display = '<div class="lang">' . $display . '</div>';

		}
		return $display;
	}

	private function _getLanguageName($value) {
		$returnValue = $value;
		$value = strtoupper($value);

		if (L8M_Doctrine_Database::databaseExists() &&
			L8M_Doctrine::isEnabled() &&
			L8M_Doctrine_Table::tableExists('language')) {

			$tmpLanguageModel = new Default_Model_Language();
			if ($tmpLanguageModel->getTable()->hasColumn('name_local')) {
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
			}
		}

		return $returnValue;
	}

	private function _languageMenu() {

		$display = NULL;

		$supportedLangs = L8M_Config::getOption('locale.backend.supported');

		$cssClass = NULL;
		if (count($supportedLangs) == 1) {
			$cssClass = 'only-language';
		}

		foreach ($supportedLangs as $lang) {
			$cssClassActive = NULL;
			if ($lang == L8M_Locale::getLang()) {
				$cssClassActive = ' active';
			}

			$display.= '<li class="lang-' . $lang . $cssClassActive . '"><a href="' . $this->view->url(array('module'=>L8M_Acl_CalledFor::module(), 'controller'=>'index', 'action'=>'index', 'lang'=>$lang), NULL, TRUE) . '"><i class="fa fa-angle-right"></i> ' . $this->_getLanguageName($lang) . '</a></li>';

		}

		$display = '<ul class="lang-menu ' . $cssClass . '">' . $display . '</ul>';

		return $display;
	}
}