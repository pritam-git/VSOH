<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/LanguageMenu.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: LanguageMenu.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_LanguageMenu
 *
 *
 */
class L8M_View_Helper_LanguageMenu extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Renders a language menu
	 *
	 * @return string
	 */
	public function languageMenu()
	{
		/**
		 * start html output
		 */
		ob_start();

		$cssClass = NULL;
		if (count(L8M_Locale::getSupported()) == 1) {
			$cssClass = ' only-language';
		}

?>
<ul class="lang-menu<?php echo $cssClass; ?>">
<?php

		foreach (L8M_Locale::getSupported() as $lang) {
			$cssClassActive = NULL;
			if ($lang == L8M_Locale::getLang()) {
				$cssClassActive = ' active';
			}

?>
	<li class="lang-<?php echo $lang . $cssClassActive; ?>"><a href="<?php echo $this->view->url(array('lang'=>$lang)); ?>"><?php echo $lang; ?></a></li>
<?php

		}

?>
</ul>
<?php

		return ob_get_clean();
	}

}