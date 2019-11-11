<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/PrjEmailHeader.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: PrjEmailHeader.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_PrjEmailHeader
 *
 *
 */
class PRJ_View_Helper_PrjEmailHeader extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a PrjEmailHeader.
	 *
	 * @return string
	 */
	public function prjEmailHeader($content = NULL)
	{

		$shopLogo = PRJ_SiteConfig::getMediaImage('website_logo');
		$shopName = PRJ_SiteConfig::getOption('company_name');

		ob_start();

?>

<img style="margin-bottom: 30px;" src="http://<?php echo $_SERVER['SERVER_NAME'] . $shopLogo->getLink(); ?>" alt="<?php echo $shopName; ?>" /><br />

<?php

		$returnValue = ob_get_clean();
		return $returnValue;
	}

}