<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/views/helpers/OrderDetails.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: OrderDetails.php 408 2015-09-10 11:37:01Z nm $
 */

/**
 *
 *
 * Admin_View_Helper_OrderDetails
 *
 *
 */
class Admin_View_Helper_OrderDetails extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a orderDetails
	 *
	 * @return string
	 */
	public function orderDetails($productArray, $sumProducts, $shippingCosts, $orderSum, $taxes, $refund = NULL, $changeQuantity = TRUE)
	{
		ob_start();
		$isSmallBusiness = L8M_Config::getOption('shop.smallBusiness.enabled');

		$cart = PRJ_Shop_Cart::factory('adminShopCart');

?>
<div class="orderview">
	<div class="order-headline">
		<div class="headline-article"><?php echo $this->view->translate('Artikel', 'de'); ?></div>
		<div class="headline-count"><?php echo $this->view->translate('Anzahl', 'de'); ?></div>
		<div class="headline-price"><?php echo $this->view->translate('Einzelpreis', 'de'); ?></div>
		<div class="headline-refund">
<?php

		if (L8M_Config::getOption('shop.refund')) {
			echo $this->view->translate('Pfand', 'de');
			$cssClassSumPrice = NULL;
			$cssClassSumAll = NULL;
		} else {
			echo '&nbsp;';
			$cssClassSumPrice = ' headline-min';
			$cssClassSumAll = ' headline-plus';
		}

?>
		</div>
		<div class="headline-sum-price<?php echo $cssClassSumPrice; ?>">
<?php

		if (L8M_Config::getOption('shop.refund')) {
			echo $this->view->translate('Gesamtpreis', 'de');
		} else {
			echo '&nbsp;';
		}

?>
		</div>
		<div class="headline-sum-refund">
<?php

		if (L8M_Config::getOption('shop.refund')) {
			echo $this->view->translate('Pfand gesamt', 'de');
		} else {
			echo '&nbsp;';
		}

?>
		</div>
		<div class="headline-sum-of-refund-and-product<?php echo $cssClassSumAll; ?>">
<?php

		if (L8M_Config::getOption('shop.refund')) {
			echo $this->view->translate('Summe', 'de');
		} else {
			echo $this->view->translate('Gesamtpreis', 'de');
		}
?>
		</div>
	</div>
	<div class="products clear">
<?php

		foreach($productArray as $product) {
			if ($product['count'] > 0) {

			$productExtras = NULL;
			if ($product['product_extra_options']) {
				$productExtras = ' ' . $product['product_extra_options'];
			}

?>
		<div class="orderproduct">
<?php

$cssStyle = ' style="width: 221px;"';

if ($product['model']->media_image_id != NULL)
{

	$cssStyle = NULL;

?>
			<div class="picture"> <?php echo $product['model']->MediaImage->maxBox(30, 32); ?><br /></div>
<?php

}
			$fileLink = NULL;

			if (array_key_exists('order_item_id', $product) &&
				$product['order_item_id'] != NULL) {

				$productOrderItemModel = Doctrine_Query::create()
					->from('Default_Model_ProductOrderItem m')
					->addWhere('m.id = ?', array($product['order_item_id']))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($productOrderItemModel) {

					$mediaModel = Doctrine_Query::create()
						->from('Default_Model_Media m')
						->addWhere('m.id = ?', array($productOrderItemModel->media_id))
						->limit(1)
						->execute()
						->getFirst()
					;

					if ($mediaModel) {

						$fileLink = '<span><a href="' . $mediaModel->getLink() .'" class="external">' . $this->view->translate('Datei runterladen', 'de') . '</a></span>';

					}

				}

			}

?>
			<div class="name"<?php echo $cssStyle; ?>>
				<span class="product-name"><?php echo $product['model']->title . $productExtras; ?></span>
				<span class="product-number">(<?php echo $this->view->translate('Art.-Nr.:', 'de') . ' ' . $product['model']->product_number; ?>)</span>
				<?php echo $fileLink;?>
			</div>
<?php

			if ($changeQuantity) {
				echo $this->changeOrderProductQuantity($product['model']->short, $product['count'], $product['product_extra_short'], $product['order_item_id']);
			}

?>
			<div class="quantity"><?php echo $product['count']; ?></div>
			<div class="price"><span class="description"><?php echo $this->view->translate('Einzelpreis', 'de'); ?></span> <?php echo number_format($product['price'], 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');; ?></div>
<?php


?>
			<div class="refund">
<?php

				if (L8M_Config::getOption('shop.refund')) {

?>
				<span class="description">
<?php

					echo $this->view->translate('Pfand', 'de');
?>
				</span>
<?php

					echo ' ' . number_format($product['refund'], 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');;
				} else {
					echo '&nbsp;';
				}

?>
			</div>
<?php


?>
			<div class="sumprice">
<?php

				if (L8M_Config::getOption('shop.refund')) {

?>
				<span class="description">
<?php

					echo $this->view->translate('Gesamtpreis', 'de');

?>
				</span>
<?php

					echo ' ' . number_format(($product['price'] * $product['count']), 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');;
				} else {
					echo '&nbsp;';
				}

?>
			</div>
<?php


?>
			<div class="refundsum">
<?php

				if (L8M_Config::getOption('shop.refund')) {

?>
				<span class="description">
<?php

					echo $this->view->translate('Pfand gesamt', 'de');

?>
				</span>
<?php

					echo ' ' . number_format(($product['refund'] * $product['count']), 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');;
				} else {
					echo '&nbsp;';
				}

?>
			</div>
<?php


?>
			<div class="sum-of-refund-and-product">
				<span class="description">
<?php

				if (L8M_Config::getOption('shop.refund')) {
					echo $this->view->translate('Summe', 'de');
				} else {
					echo $this->view->translate('Gesamtpreis', 'de');
				}

?>
				</span>
<?php

				echo ' ' . number_format($product['sumprice'] + ($product['refund'] * $product['count']), 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');;

?>
			</div>
<?php

				if ($isSmallBusiness == FALSE) {

?>
			<div class="incl-tax"><span class="description"><?php echo $this->view->translate('inkl.', 'de'); ?></span> <?php echo $taxes[$product['taxes_id']]['name']; ?> <?php echo $this->view->translate('MwSt', 'de'); ?></div>
<?php

				}

?>
		</div>
		<br class="clear"/>
<?php
			}
		}
?>
	</div>
	<div class="mwst">
		<div class="subtotal">
			<div><?php echo $this->view->translate('Zwischensumme', 'de') . ':' ?></div>
			<div><?php echo number_format($sumProducts, 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');; ?></div>
		</div>

<?php
		if ($isSmallBusiness == FALSE) {

			foreach($taxes as $tax) {

				if ($tax['sum'] > 0) {

?>
		<div class="taxtotal">
			<div><?php echo $this->view->translate('inklusive', 'de') . ' ' . $tax['name'] . ' ' . $this->view->translate('MwSt', 'de') . ':' ?></div>
			<div><?php echo number_format($tax['sum'], 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');; ?></div>
		</div>
<?php

				}

			}

		}

		if ($refund) {

?>
		<div class="refundtotal">
			<div><?php echo $this->view->translate('Pfand', 'de') . ':' ?></div>
			<div><?php echo number_format($refund, 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');; ?></div>
		</div>
<?php

		}

?>
		<div class="shippingcost">
			<div><?php echo $this->view->translate('Versandkosten', 'de') . ':'; ?></div>
			<div><?php echo number_format($shippingCosts, 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');; ?></div>
		</div>
<?php

		if ($cart->getPaymentMethod()) {

?>
		<div class="paymentmethod">
			<div><?php echo $this->view->translate('Bezahlmethode', 'de') . ':'; ?> <span><?php echo $cart->getPaymentMethod()->title; ?></span></div>
		</div>
<?php

			if ($cart->getPaymentServiceCosts($cart->getPaymentMethod()->short) != 0) {
				if ($cart->getPaymentServiceCosts($cart->getPaymentMethod()->short) < 0) {
					$paymentMethodCostString = $this->view->translate('Gutschrift für Bezahlmethode', 'de');
				} else {
					$paymentMethodCostString = $this->view->translate('Zusätzliche Kosten', 'de');
				}
?>
		<div class="paymentmethod-costs">
			<div><?php echo $paymentMethodCostString; ?>:</div>
			<div><?php echo number_format(abs($cart->getPaymentServiceCosts($cart->getPaymentMethod()->short)), 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign'); ?></div>
		</div>
<?php
			}
		}

		if ($cart->getCoupon() != NULL) {

?>
		<div class="coupon-value">
			<div><?php echo $this->view->translate('Gutschein', 'de') . ':'; ?></div>
			<div><?php echo number_format($cart->getCoupon()->value, 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign'); ?></div>
		</div>
<?php

		}

?>
		<div class="sumtotal">
			<div><?php echo $this->view->translate('Rechnungssumme', 'de') . ':' ?></div>
			<div><?php echo number_format($cart->getCalculatedSumPrice(), 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');; ?></div>
		</div>
<?php

		if ($cart->getPaymentMethod() &&
			$cart->getCoupon()) {

			$sum = $orderSum + $cart->getPaymentServiceCosts($cart->getPaymentMethod()->short);

			if ($sum - $cart->getCoupon()->value < 0 ||
				$sum - $cart->getCoupon()->value == 0) {

				$var1 = $cart->getCoupon()->value - $sum;
			} else {
				$var1 = 0;
			}

?>
		<div class="coupon">
			<div><?php echo $this->view->translate('Der Gutschein beträgt nun noch', 'de') . ':' ?></div>
			<div><?php echo number_format($var1, 2, ',', ' ') . ' ' . PRJ_SiteConfig::getOption('currency_sign');; ?></div>
		</div>
<?php

		}

?>
	</div>
</div>
<?php

		return ob_get_clean();
	}

	public function changeOrderProductQuantity($productShort, $productCount, $extraShort = NULL, $orderItemID = NULL)
	{
		$linkPlusOne = NULL;
		$linkMinusOne = NULL;
		$linkRemove = NULL;
		foreach ($this->view->newProductItemQuantityParamArray as $key => $value) {
			if ($key == $orderItemID) {
				$linkPlusOne .= '&productItemQuantity[' . $key . ']=' . ($value + 1);

				$value = $value - 1;
				if ($value < 0) {
					$value = 0;
				}
				$linkMinusOne .= '&productItemQuantity[' . $key . ']=' . $value;
				$linkRemove .= '&productItemQuantity[' . $key . ']=0';
			} else {
				$linkPlusOne .= '&productItemQuantity[' . $key . ']=' . $value;
				$linkMinusOne .= '&productItemQuantity[' . $key . ']=' . $value;
				$linkRemove .= '&productItemQuantity[' . $key . ']=' . $value;
			}
		}

		ob_start();

?>
<div class="changequantity">
<?php

		if (!($this->view->orderModel->order_mail_send || $this->view->orderModel->ship_mail_send) &&
		$this->view->orderModel->state_wait_for_payment &&
		!$this->view->orderModel->cancelled) {

?>
	<a href="<?php echo $this->view->url(array('module'=>'admin', 'controller'=>'shop-order', 'action'=>'edit', 'modelListName'=>'Default_Model_ProductOrder'), NULL, TRUE) . '?' . implode('&', $this->view->passThoughViewVars) . $linkPlusOne; ?>" class="plus">+</a>
	<a href="<?php echo $this->view->url(array('module'=>'admin', 'controller'=>'shop-order', 'action'=>'edit', 'modelListName'=>'Default_Model_ProductOrder'), NULL, TRUE) . '?' . implode('&', $this->view->passThoughViewVars) . $linkMinusOne; ?>" class="minus">-</a>
	<a href="<?php echo $this->view->url(array('module'=>'admin', 'controller'=>'shop-order', 'action'=>'edit', 'modelListName'=>'Default_Model_ProductOrder'), NULL, TRUE) . '?' . implode('&', $this->view->passThoughViewVars) . $linkRemove; ?>" class="remove"><?php echo $this->view->translate('remove'); ?></a>
<?php

		} else {
			echo '&nbsp;';
		}

?>
</div>
<?php

		return ob_get_clean();
	}
}