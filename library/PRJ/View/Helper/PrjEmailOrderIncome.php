<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/PrjEmailOrderIncome.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: PrjEmailOrderIncome.php 563 2018-03-27 10:13:07Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_PrjEmailOrderIncome
 *
 *
 */
class PRJ_View_Helper_PrjEmailOrderIncome extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Renders mail content
	 *
	 * @param string $mode
	 * @param array $products - e.g. $product['count'], $product['model']->title
	 * @param array $orderInfos - e.g. $orderInfos['delivery_firstname']
	 * @param boolean $order
	 * @return string
	 */
	public function PrjEmailOrderIncome($mode, $products, $orderInfos, $order = TRUE)
	{

		$shopName = PRJ_SiteConfig::getOption('company_name');
		$shopLogo = PRJ_SiteConfig::getMediaImage('website_logo');
		$shopInfo = PRJ_SiteConfig::getOption('footer_text');
		$serviceHotline = PRJ_SiteConfig::getOption('service_hotline');
		$email = PRJ_Siteconfig::getOption('email');
		$accountHolder = PRJ_SiteConfig::getOption('kontoinhaber');
		$bankCodeNumber = PRJ_SiteConfig::getOption('blz');
		$accountNumber = PRJ_SiteConfig::getOption('konto');
		$nameOfBank = PRJ_SiteConfig::getOption('bankname');
		$iban = PRJ_SiteConfig::getOption('iban');
		$bic = PRJ_SiteConfig::getOption('bic');
		$taxNumber = PRJ_SiteConfig::getOption('tax_number');

		/**
		 * get name of billing country
		 */
		$billingCountryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.id = ?', array($orderInfos['billing_country_id']))
			->limit(1)
			->execute()
			->getFirst()
		;

		$orderInfos['billing_country'] = $billingCountryModel->name;

		/**
		 * get name of delivery country
		 */
		$deliveryCountryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.id = ?', array($orderInfos['billing_country_id']))
			->limit(1)
			->execute()
			->getFirst()
		;

		$orderInfos['delivery_country'] = $deliveryCountryModel->name;

		$sumOfAllProducts = 0;
		$taxOfAllProducts = 0;
		$sumPrice = $orderInfos['sum_price'];

		if ($mode == 'html') {

			ob_start();

?>
	<table style="border: 0; padding:0; margin:0; width:648px;" border="0" cellspacing="0" cellpadding="0" width="648">
		<tr>
			<td style="background-color:#ffffff; border-color:#000000; border-style:solid; border-width:1px; width:312px; padding:0; margin:0;">

		<p style="margin:0px; font-weight:bold; padding:8px; padding-left:10px; font-weight:normal; text-transform:uppercase; background-color:#000000; color:#ffffff;"><?php echo $this->view->translate('Rechnungsinformationen','de'); ?></p>
<?php

			$minHeight = 95;

?>
		<div style="padding:8px; padding-left:10px; height:<?php echo $minHeight; ?>px;">
<?php

			if (L8M_Config::getOption('shop.company.enabled') &&
				$orderInfos['company'] != NULL &&
				!empty($orderInfos['company'])) {

				echo '<p style="margin:0px;">' . $orderInfos['company'] . '</p>';
			}

			echo '<p style="margin:0px;">' . $orderInfos['firstname'] . ' ' . $orderInfos['lastname'] . '</p>';
			echo '<p style="margin:0px;">' . $orderInfos['billing_street'] . ' ' . $orderInfos['billing_street_number'] . '</p>';
			if ($orderInfos['billing_address_line_1']) {
				echo '<p style="margin:0px;">' . $orderInfos['billing_address_line_1'] . '</p>';
			}
			if ($orderInfos['billing_address_line_2']) {
				echo '<p style="margin:0px;">' . $orderInfos['billing_address_line_2'] . '</p>';
			}
			echo '<p style="margin:0px;">' . $orderInfos['billing_zip'] . ' ' . $orderInfos['billing_city'] . '</p>';
			echo '<p style="margin:0px;">' . $orderInfos['billing_country'] . '</p>';
			echo '<p style="margin:0px;">' . $this->view->translate('Phone') . ': ' . $orderInfos['phone'] . '</p>';
			if (isset($orderInfos['mobile']) &&
				$orderInfos['mobile']) {

				echo '<p style="margin:0px;">' . $this->view->translate('Mobile') . ': ' . $orderInfos['mobile'] . '</p>';
			}

?>
		</div>

			</td>
		</tr>
		<tr>
			<td colspan="3" style="">
				&nbsp;
			</td>
		</tr>
		<tr>
			<td colspan="3" style="background-color:#ffffff; border-color:#000000; border-style:solid; border-width:1px; width:648px; padding:0; margin:0;">

		<p style="margin:0px; padding:8px; padding-left:10px; background-color:#000000; color:#FFFFFF; text-transform:uppercase; font-weight:normal;"><?php echo $this->view->translate('Zahlungsmethode','de'); ?></p>
		<div style="padding:8px; padding-left:10px; height:<?php echo $minHeight; ?>px;">
<?php

			if (L8M_Config::getOption('shop.company.enabled') &&
				$orderInfos['delivery_company'] != NULL &&
				!empty($orderInfos['delivery_company'])) {

				echo '<p style="margin:0px;">' . $orderInfos['delivery_company'] . '</p>';
			}

			echo '<p style="margin:0px;">' . $orderInfos['delivery_firstname'] . ' ' . $orderInfos['delivery_lastname'] . '</p>';
			echo '<p style="margin:0px;">' . $orderInfos['delivery_street'] . ' ' . $orderInfos['delivery_street_number'] . '</p>';
			if ($orderInfos['delivery_address_line_1']) {
				echo '<p style="margin:0px;">' . $orderInfos['delivery_address_line_1'] . '</p>';
			}
			if ($orderInfos['delivery_address_line_2']) {
				echo '<p style="margin:0px;">' . $orderInfos['delivery_address_line_2'] . '</p>';
			}
			echo '<p style="margin:0px;">' . $orderInfos['delivery_zip'] . ' ' . $orderInfos['delivery_city'] . '</p>';
			echo '<p style="margin:0px;">' . $orderInfos['delivery_country'] . '</p>';
			echo '<p style="margin:0px;">' . $this->view->translate('Phone') . ': ' . $orderInfos['phone'] . '</p>';
			if (isset($orderInfos['mobile']) &&
				$orderInfos['mobile']) {

				echo '<p style="margin:0px;">' . $this->view->translate('Mobile') . ': ' . $orderInfos['mobile'] . '</p>';
			}

?>
		</div>

<?php

			if ($order) {
				$width = 295;
				$paddingBottom = "";
			} else {
				$width = 438;
				$paddingBottom = "padding-bottom:19px;";
			}

?>

			</td>
		</tr>
		<tr>
			<td colspan="3" style="">
				&nbsp;
			</td>
		</tr>
		<tr>
			<td colspan="3" style="background-color:#ffffff; border-color:#000000; border-style:solid; border-width:1px; width:648px; padding:0; margin:0;">

		<div>
			<table style="border: 0; padding:0; margin:0; width:648px;" border="0" cellspacing="0" cellpadding="0" width="648">
				<tr>
			<td style="margin:0px; width:<?php echo $width; ?>px; padding:8px; padding-left:10px; background-color:#000000; color:#FFFFFF; text-transform:uppercase; font-weight:normal;">
				<?php echo $this->view->translate('Artikel','de'); ?>
			</td>
			<td style="margin:0px; width:140px; text-align:center; padding:8px; padding-left:10px; background-color:#000000; color:#FFFFFF; text-transform:uppercase; font-weight:normal;">
				<?php echo $this->view->translate('Artikelnummer','de'); ?>
			</td>
			<td style="margin:0px; width:50px; text-align:center; padding:8px; padding-left:10px; background-color:#000000; color:#FFFFFF; text-transform:uppercase; font-weight:normal;">
				<?php echo $this->view->translate('Stk.','de'); ?>
			</td>
<?php

			if ($order) {

?>
			<td style="margin:0px; text-align:right; padding:8px; padding-left:10px; background-color:#000000; color:#FFFFFF; text-transform:uppercase; font-weight:normal;">
				<?php echo $this->view->translate('Zwischensumme','de'); ?>
			</td>
<?php

			}

?>
				</tr>
			</table>
		</div>
		<div style="background-color:#ffffff; padding-top:8px; margin:0px; <?php echo $paddingBottom; ?>">
			<div>
				<table style="background-color:#ffffff; border:0; padding:0; margin:0; width:648px;" border="0" cellspacing="0" cellpadding="0" width="648">
<?php

			foreach($products as $productElement) {

				$deliveryDays = Default_Model_Product::calcShipmentReadyDays($productElement['model']->delivery_days);
				$deliveryDate = date('d.m.Y', strtotime('+' . $deliveryDays . ' days'));


				if ($productElement['count'] > 0) {
					$ecolspan = 3;
					echo '<tr>';
					echo '<td style="margin:0px; width:' . $width . 'px; padding-left:10px;">' . $productElement['model']->title. ' ' . $productElement['product_extra_options'] . '</td>';
					echo '<td style="margin:0px; width:140px; text-align:center;">' . $productElement['model']->product_number . '</td>';
					echo '<td style="margin:0px; width:50px; text-align:center;">' . $productElement['count'] . '</td>';
					if ($order) {
					$ecolspan = 4;
						echo '<td style="margin:0px; text-align:right; padding-right:10px;">' . number_format($productElement['price'] * $productElement['count'], 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign') . '</td>';
					}
					echo '</tr>';
					echo '<tr>';
					echo '<td colspan="' . $ecolspan . '" style="margin:0px; text-left; padding-left: 10px;">' . $this->view->translate('Material') . ': ' . strip_tags($productElement['model']->material, '<br><br/><br />'). '</td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td colspan="' . $ecolspan . '" style="margin:0px; text-left; padding-left: 10px;">' . $this->view->translate('Lieferung bis', 'de') . ': ' . $deliveryDate . '</td>';
					echo '</tr>';

					$sumOfAllProducts = $sumOfAllProducts + ($productElement['price'] * $productElement['count']);

					for($i = 1; $i <= $productElement['count']; $i++) {

						$taxOfAllProducts = $taxOfAllProducts + round(($productElement['price'] / (100 + $productElement['model']->Taxes->value) * $productElement['model']->Taxes->value), 2);

					}
				}
			}

?>
				</table>
			</div>
<?php

			if ($order) {

?>
			<table style="border:0; padding:0; margin:0; width:648px;" border="0" cellspacing="0" cellpadding="0" width="648">
			<tr>
				<td style="margin:0px; padding-top:12px; text-align:right; width:485px;"><?php echo $this->view->translate('Zwischensumme','de'); ?></td>
				<td style="margin:0px; padding-top:12px; text-align:right; padding-right:10px; width:153px;"><?php echo number_format($sumOfAllProducts, 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign'); ?></td>
			</tr>
			<tr>
				<td style="margin:0px; padding-top:12px; text-align:right; width:485px;"><?php echo $this->view->translate('Versand & Bearbeitung','de'); ?></td>
				<td style="margin:0px; padding-top:12px; text-align:right; padding-right:10px; width:153px;"><?php echo number_format(($orderInfos['sum_price'] - $sumOfAllProducts), 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign'); ?></td>
			</tr>
<?php

				if ($orderInfos['coupon']) {

					$sumPrice -= $orderInfos['coupon_value'];

?>
			<tr>
				<td style="margin:0px; padding-top:9px; text-align:right; width:485px; font-style:italic;"><?php echo $this->view->translate('Eingelöster Gutschein','de'); ?></td>
				<td style="margin:0px; padding-top:9px; text-align:right; padding-right:10px; width:153px;">-<?php echo number_format(($orderInfos['coupon_value']), 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign'); ?></td>
			</tr>
<?php

				}

				if (L8M_Config::getOption('shop.smallBusiness.enabled') == FALSE) {

?>
			<tr>
				<td style="margin:0px; padding-top:9px; padding-bottom:18px; text-align:right; width:485px;"><?php echo $this->view->translate('Steuern','de'); ?></td>
				<td style="margin:0px; padding-top:9px; padding-bottom:18px; text-align:right; padding-right:10px; width:153px;"><?php echo number_format($taxOfAllProducts, 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign'); ?></td>
			</tr>
<?php

				}

			}

?>
			</table>
		</div>
<?php

			if ($order) {

?>
		<table style="height:11px; border:0; padding:0; margin:0; width:648px;" border="0" cellspacing="0" cellpadding="0" width="648">
			<tr>
				<td style="margin:0px; text-align:right; width:478px; padding:8px; padding-left:10px; background-color:#000000; color:#FFFFFF; text-transform:uppercase; font-weight:normal;"><?php echo $this->view->translate('Gesamtsumme','de'); ?></td>
				<td style="margin:0px; text-align:right; width:152px; padding:8px; padding-left:10px; background-color:#000000; color:#FFFFFF; text-transform:uppercase; font-weight:normal;"><?php echo number_format($sumPrice, 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign'); ?></td>
			</tr>
		</table>
<?php

			}

?>

			</td>
		</tr>
		<tr>
			<td colspan="3" style="">
				&nbsp;
			</td>
		</tr>
<?php

			if (isset($orderInfos['note'])) {

?>
		<tr>
			<td colspan="3" style="background-color:#ffffff; border-color:#000000; border-style:solid; border-width:1px; width:648px; padding:0; margin:0;">

		<table style="border:0; padding:0; margin:0; margin-top:20px; width:648px;" border="0" cellspacing="0" cellpadding="0" width="648">
			<tr>
				<td style="margin:0px; font-weight:bold; padding:8px; padding-left:10px; background-color:#000000; color:#FFFFFF; text-transform:uppercase; font-weight:normal; width:630px;"><?php echo $this->view->translate('Kommentar','de'); ?></td>
			</tr>
			<tr>
				<td style="padding:8px; padding-left:10px; height:130px;">
					<p style="margin:0px;"><?php echo $orderInfos['note']; ?></p>
				</td>
			<tr>
		</table>
			</td>
		</tr>
<?php

			}
?>
	</table>
<?php

			return ob_get_clean();

		} else
		if ($mode == 'plain') {

			$returnValue = NULL;

			foreach ($products as $productElement) {

				$deliveryDays = Default_Model_Product::calcShipmentReadyDays($productElement['model']->delivery_days);
				$deliveryDate = date('d.m.Y', strtotime('+' . $deliveryDays . ' days'));

				if ($productElement['count'] > 0) {
					$returnValue .= $this->view->translate('Artikel') . ':       ' . $productElement['model']->title . ' ' . $productElement['product_extra_options'] . PHP_EOL;
					$returnValue .= $this->view->translate('Artikelnummer') . ': ' . $productElement['model']->product_number . PHP_EOL;
					$returnValue .= $this->view->translate('Material') . ': ' . strip_tags($productElement['model']->material) . PHP_EOL;
					$returnValue .= $this->view->translate('Lieferung bis', 'de') . ': ' . $deliveryDate . PHP_EOL;
					$returnValue .= $this->view->translate('Stk') . ':           ' . $productElement['count'] . 'x'. PHP_EOL;
					$returnValue .= $this->view->translate('Einzelpreis') . ':   ' . number_format($productElement['price'], 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_iso3_code') . PHP_EOL;
					$returnValue .= $this->view->translate('Gesamtpreis') . ':   ' . number_format($productElement['count'] * $productElement['price'], 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_iso3_code') . PHP_EOL . PHP_EOL;

					$sumOfAllProducts = $sumOfAllProducts + ($productElement['price'] * $productElement['count']);

					for($i = 1; $i <= $productElement['count']; $i++) {

						$taxOfAllProducts = $taxOfAllProducts + round(($productElement['price'] / (100 + $productElement['model']->Taxes->value) * $productElement['model']->Taxes->value), 2);

					}
				}
			}

			$returnValue .= PHP_EOL . $this->view->translate('Zwischensumme') . ':        ' . number_format($sumOfAllProducts, 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_iso3_code') . PHP_EOL;
			$returnValue .= $this->view->translate('Versand & Bearbeitung','de') . ':     ' . number_format(($orderInfos['sum_price'] - $sumOfAllProducts), 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_iso3_code') . PHP_EOL;
			if ($orderInfos['coupon']) {
				$returnValue .= $this->view->translate('Eingelöster Gutschein','de') . ': ' . $orderInfos['coupon_code'] . PHP_EOL;
			}

			if (L8M_Config::getOption('shop.smallBusiness.enabled') == FALSE) {
				$returnValue .= $this->view->translate('Steuern','de') . ':                   ' . number_format($taxOfAllProducts, 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_iso3_code') . PHP_EOL;
			}
			$returnValue .= '--------------------------------------------------------------------------------------------'. PHP_EOL;
			$returnValue .= $this->view->translate('Gesamtsumme','de') . ':               ' . number_format($orderInfos['sum_price'], 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_iso3_code') . PHP_EOL . PHP_EOL;

			return $returnValue;

		}

	}
}