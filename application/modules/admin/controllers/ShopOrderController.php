<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/controllers/ShopOrderController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ShopOrderController.php 408 2015-09-10 11:37:01Z nm $
 */

/**
 *
 *
 * Admin_ShopOrderController
 *
 *
 */
class Admin_ShopOrderController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_ProductOrder';
	private $_modelListShort = 'spor';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Shop Order';

	/**
	 * Store modelList.
	 *
	 * @var L8M_ModelForm_List
	 */
	private $_modelList = NULL;

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Admin_ShopOrderController.
	 *
	 * @return void
	 */
	public function init ()
	{

		/**
		 * set headline
		 */
		$this->_helper->layout()->headline = $this->view->translate('Administration') . ' - ModelList';
		$this->_helper->layout()->headline .= ': ' . $this->view->translate($this->_modelListUntranslatedTitle);

		/**
		 * pass through parent to prevent errors
		 */
		parent::init();

		/**
		 * start model list
		 */
		$this->_modelList = new L8M_ModelForm_List($this->_modelListName, $this);
		$this->_modelList
			->setDefault('listTitle', $this->view->translate($this->_modelListUntranslatedTitle))
			->disableSubLinks()
			->disableButtonAdd()
			->disableButtonDelete()
//			->addWhere('short', 'guest', FALSE, 'aa', 'Role', 'r')
//			->addWhereDqlString('aa.is_action_method = ? AND aa.resource LIKE ? ', array(TRUE, 'default.%'))
// 			->setButton('Rechnung', array('action'=>'rechnung', 'controller'=>'shop-order', 'module'=>'admin'), 'pdf', TRUE)
			->setButton('Lieferschein', array('action'=>'lieferschein', 'controller'=>'shop-order', 'module'=>'admin'), 'pdf', TRUE)
//			->setButton('Update', array('action'=>'update', 'controller'=>'action', 'module'=>'system'), 'update', FALSE)
//			->disableSaveWhere()
//			->useDbWhere(FALSE)
			->showListColumn('created_at', 'Bestellung vom', 110, FALSE, FALSE)
			->showListColumn('order_mail_send', 'Order Checked', 70, FALSE, FALSE)
			->showListColumn('state_wait_for_payment', 'Payed', 90, FALSE, FALSE)
			->showListColumn('state_dispose', 'State Disposed', 70, FALSE, FALSE)
			->showListColumn('state_shipped', 'State Shipped', 70, FALSE, FALSE)
			->showListColumn('cancelled', 'Storniert', 70, FALSE, FALSE)
			->showListColumn('note', 'Notizen', 110, FALSE, FALSE)
			->hideColumnInList('spor_firstname')
			->hideColumnInList('spor_email')
			->hideColumnInList('spor_note')
			->hideColumnInList('entityuser_login')
			->hideColumnInList('billingcountry_iso_2')
			->hideColumnInList('deliverycountry_iso_2')
			->hideColumnInList('coupon_value')
			->hideColumnInList('spor_state_dispose')
			->booleanReverseColumn('state_wait_for_payment')
		;

		if (L8M_Config::getOption('shop.admin.order.generateBill') == TRUE) {

			$this->_modelList
				->setButton('Rechnung', array('action'=>'rechnung', 'controller'=>'shop-order', 'module'=>'admin'), 'pdf', TRUE)
			;
		}

		if (L8M_Config::getOption('shop.admin.order.boolNotClickable')) {
			$this->_modelList->booleanNotClickable();
		}

		$this->_modelList
			->booleanNotClickableIfTrue('order_mail_send')
			->booleanNotClickableIfTrue('state_shipped')
			->booleanNotClickableIfTrue('cancelled')
			->booleanNotClickableIfFalse('order_mail_send')
			->booleanNotClickableIfFalse('state_wait_for_payment')
			->booleanNotClickableIfFalse('state_shipped')
			->booleanNotClickableIfFalse('cancelled')
//			->showAjax();
//			->doNotRedirect()
//			->setDeleteOldList()
		;

		$this->_modelListConfig = array(
			'order'=>array(
				'company',
				'firstname',
				'lastname',
				'billing_street',
				'billing_street_number',
				'billing_address_line_1',
				'billing_address_line_2',
				'billing_zip',
				'billing_city',
				'delivery_company',
				'delivery_firstname',
				'delivery_lastname',
				'delivery_street',
				'delivery_street_number',
				'delivery_address_line_1',
				'delivery_address_line_2',
				'delivery_zip',
				'delivery_city',
			),
			'addIgnoredColumns'=>array(
				'sum_price',
				'billing_country_id',
				'email',
				'comment',
				'delivery_country_id',
				'payment_service_id',
				'coupon_id',
				'state_wait_for_payment',
				'state_dispose',
				'state_shipped',
				'delivery_country_id',
				'bank_account_name',
				'bank_account_number',
				'bank_identification_code',
				'bank_name',
				'card_number',
				'valid_to',
				'verification_code',
				'entity_user_id',
				'cancelled',
				'order_mail_send',
				'ship_mail_send',
			),
			'addIgnoredM2nRelations'=>array(
			),
			'ignoreColumnRelation'=>array(
			),
			'ignoreColumnInMultiRelation'=>array(
				'PaymentService'=>array(
					'MediaImage',
				),
			),
			'relationM2nValuesDefinition'=>array(
			),
			'mediaDirectory'=>array(
			),
			'mediaRole'=>array(
			),
			'columnLabels'=>array(
				'note'=>'Kommentar zur Bestellung',
				'intern_comment'=>'Interne Notizen',
				'billing_street'=>'Rechnungs - Straße',
				'billing_street_number'=>'Rechnungs - Hausnummer',
				'billing_address_line_1'=>'Rechnungs - Adresszeile 1',
				'billing_address_line_2'=>'Rechnungs - Adresszeile 2',
				'billing_zip'=>'Rechnungs - Postleitzahl',
				'billing_city'=>'Rechnungs - Stadt',
				'delivery_company'=>'Liefer - Firma',
				'delivery_firstname'=>'Liefer - Vorname',
				'delivery_lastname'=>'Liefer - Nachname',
				'delivery_street'=>'Liefer - Straße',
				'delivery_street_number'=>'Liefer - Hausnummer',
				'delivery_address_line_1'=>'Liefer - Adresszeile 1',
				'delivery_address_line_2'=>'Liefer - Adresszeile 2',
				'delivery_zip'=>'Liefer - Postleitzahl',
				'delivery_city'=>'Liefer - Stadt',
				'mobile'=>'Handy',
			),
			'buttonLabel'=>'Save',
			'columnTypes'=>array(
				'billing_street'=>'text',
				'delivery_street'=>'text',
				'note'=>'textarea',
				'intern_comment'=>'textarea',
			),
			'addStaticFormElements'=>array(
			),
			'M2NRelations'=>array(
			),
			'replaceColumnValuesInMultiRelation'=>array(
			),
			'relationColumnInMultiRelation'=>array(
			),
			'multiRelationCondition'=>array(
			),
			'tinyMCE'=>array(
			),
			'setFormLanguage'=>L8M_Locale::getDefaultSystem(),
			'action'=>$this->_request->getActionName(),
			//'debug'=>TRUE,
		);

		$this->view->modelFormListButtons = $this->_modelList->getButtons(NULL, $this->_modelListShort, $this->_modelListConfig);
	}

	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function indexAction ()
	{
		if ($this->_modelListName) {
			$this->_forward('list');
		}
	}

	/**
	 * List action.
	 *
	 * @return void
	 */
	public function listAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('List');

		/**
		 * start model list
		 */
		$this->_modelList->listCollection($this->_modelListShort);
	}

	/**
	 * Create action.
	 *
	 * @return void
	 */
	public function createAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Add');

		/**
		 * start model list
		 */
		$this->_modelList->createModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforeSave'=>array(
			),
			'addStandardColumnValues'=>array(
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
			),
		)));
	}

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function deleteAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Delete');

		/**
		 * start model list
		 */
		$this->_modelList->deleteModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforePreDelete'=>array(
			),
			'doBefore'=>array(
			),
		)));
	}

	/**
	 * Edit action.
	 *
	 * @return void
	 */
	public function editAction ()
	{
// 		die();
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Edit');

		/**
		 * some view params
		 */
		if ($this->_request->getParam('id', NULL, FALSE)) {

			$id = $this->_request->getParam('id', NULL, FALSE);
			$do = $this->_request->getParam('do', NULL, FALSE);

			$report = $this->_request->getParam('report', NULL, FALSE);

			if ($report != NULL) {
				$this->view->report = $report;
			}

			$passThoughViewVars = array();
			$passThoughViewVarsWithoutID = array();
			$paramVars = array(
				'id',
				'page',
				'rp',
				'query',
				'qtype',
				'sortorder',
				'sortname',
			);
			foreach ($paramVars as $paramVar) {
				$passThoughViewVars[] = $paramVar . '=' . $this->_request->getParam($paramVar, NULL, FALSE);
				if ($paramVar != 'id') {
					$passThoughViewVarsWithoutID[] = $paramVar . '=' . $this->_request->getParam($paramVar, NULL, FALSE);
				}
			}

			$productItemQuantityParam = $this->_request->getParam('productItemQuantity', NULL, FALSE);
			if (!is_array($productItemQuantityParam)) {
				$productItemQuantityParam = array();
			}

			$cart = PRJ_Shop_Cart_FromOrderForEdit::factory($id);
			$orderModel = $cart->getOrderModel();
			$couponHistoryModel = $cart->getCouponHistoryModel();

			// DO NOT EDIT IBAN AND BIC IF OTHER PAY METHOD
			if ($orderModel->PaymentService->short != 'bankeinzug') {

				$this->_modelListConfig['addIgnoredColumns'][] = 'bank_account_bic';
				$this->_modelListConfig['addIgnoredColumns'][] = 'bank_account_iban';

			}

			/**
			 * add products
			 */
			$cart = PRJ_Shop_Cart_FromOrderForEdit::addProducts($cart, $productItemQuantityParam);
			$needToBeSaved = PRJ_Shop_Cart_FromOrderForEdit::getNeedToBeSaved();
			$newProductItemQuantityParamArray = PRJ_Shop_Cart_FromOrderForEdit::getNewProductItemQuantityParamArray();

			/**
			 * order infos
			 */
			$orderInfos = $cart->getOrderInfos();
			$billingCountry = NULL;
			$deliveryCountry = NULL;

			if ($do == 'orderCancel') {
				if (!$orderModel->state_shipped &&
					!$orderModel->cancelled) {

					$orderModel['cancelled'] = TRUE;
					$orderModel->save();
					if ($couponHistoryModel) {
						$couponHistoryModel->Coupon['value'] = $couponHistoryModel->Coupon['value'] + $couponHistoryModel['value'];
						$couponHistoryModel['value'] = 0;
						$couponHistoryModel->Coupon['is_used'] = NULL;
						$couponHistoryModel->save();
					}

					/**
					 * html for email
					 */
					$view = Zend_Layout::getMvcInstance()->getView();


					/**
					 * create products array
					 */
					$products = $cart->getAllProducts();

					/**
					 * email recipient
					 */
					$customerEmail = $orderModel->email;
					$customerName = $orderModel->firstname . ' ' . $orderModel->lastname;
					$customerName = trim($customerName);

//					die(L8M_Config::getOption('resources.masil.defaultFrom.email'));

					/**
					 * create email
					 */
					$email = L8M_MailV2::factory('order_cancel');
					$email
						->setFrom(L8M_Config::getOption('resources.mail.defaultFrom.email'), L8M_Config::getOption('resources.mail.defaultFrom.name'))
						->addTo($customerEmail, $customerName)
						->addBcc(L8M_Config::getOption('resources.mail.defaultToStore.email'))
					;

					/**
					 * content
					 */
					$content = L8M_MailV2_Part::factory('header', $email);
					$content
						->setDynamicVar('HEADER', $this->view->prjEmailHeader())
					;
					$email->addPart($content);

					$orderDate = date('Y-m-d', strtotime($orderModel->created_at));

					/**
					 * order
					 */
					$content = L8M_MailV2_Part::factory('order_cancel', $email);
					$content
						->setDynamicVar('FIRSTNAME',$orderInfos['firstname'])
						->setDynamicVar('LASTNAME', $orderInfos['lastname'])
						->setDynamicVar('ORDER_CONTENT_HTML', $view->prjEmailOrder('html', $products, $orderInfos, TRUE, $orderDate))
						->setDynamicVar('ORDER_CONTENT_PLAIN', $view->prjEmailOrder('plain', $products, $orderInfos, TRUE, $orderDate))
						->setDynamicVar('ORDER_ID', $orderInfos['id'])
						->setDynamicVar('ORDERDATE', date('d.m.Y', strtotime($orderInfos['created_at'])))
						->setDynamicVar('ORDERTIME', date('H:i:s', strtotime($orderInfos['created_at'])))
						->setDynamicVar('ACCOUNT_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'user', 'action'=>'index'), NULL, TRUE))
					;
					$email->addPart($content);

					/**
					 * order end
					*/
					$content = L8M_MailV2_Part::factory('order_end', $email);
					$content
					->setDynamicVar('AGB_LINK', L8M_Library::getSchemeAndHttpHost() .  $view->url(array('module'=>'default', 'controller'=>'terms-and-conditions', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('CANCELLATION_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'instructions-of-cancellation', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('PRIVACY_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'privacy-policy', 'action'=>'index'), NULL, TRUE))
						->setDynamicVar('EMAIL', PRJ_SiteConfig::getOption('email'))
					;
					$email->addPart($content);

					/**
					 * footer
					*/
					$content = L8M_MailV2_Part::factory('footer', $email);
					$email->addPart($content);

					/**
					 * send email
					 */
					try {
						$email->send();
						$this->view->mailmessage = $this->view->translate('Storno wurde per E-Mail verschickt.','de');
					} catch (L8M_Mail_Exception $exception) {
						$this->view->mailmessage = $this->view->translate('Fehler beim Verschicken der Storno an Kunde','de');
					}

				}

				$this->_redirect($this->_helper->url('edit', 'shop-order', 'admin', array('modelListName'=>'Default_Model_ProductOrder'), NULL, TRUE) . '?' . implode('&', $passThoughViewVars));
			}

			$countryModel = Doctrine_Query::create()
				->from('Default_Model_Country c')
				->addWhere('c.id = ? ', array($orderInfos['billing_country_id']))
				->execute()
				->getFirst()
			;
			if ($countryModel) {
				$billingCountry = $countryModel->name_local;
			}

			$countryModel = Doctrine_Query::create()
				->from('Default_Model_Country c')
				->addWhere('c.id = ? ', array($orderInfos['delivery_country_id']))
				->execute()
				->getFirst()
			;
			if ($countryModel) {
				$deliveryCountry = $countryModel->name_local;
			}

			/**
			 * pass through view
			 */
			$this->view->passThoughViewVars = $passThoughViewVars;
			$this->view->passThoughViewVarsWithoutID = $passThoughViewVarsWithoutID;
			$this->view->newProductItemQuantityParamArray = $newProductItemQuantityParamArray;

			$this->view->needToBeSaved = $needToBeSaved;

			$this->view->orderModel = $orderModel;
			$this->view->billingCountry = $billingCountry;
			$this->view->deliveryCountry = $deliveryCountry;
			$this->view->orderInfos = $orderInfos;

			if ($do == 'orderSave') {

				$tmpCartOrderItems = $cart->getAllProducts();
				foreach ($tmpCartOrderItems as $tmpCartOrderItem) {
					$tmpCartOrderItemModel = Doctrine_Query::create()
						->from('Default_Model_ProductOrderItem r')
						->addWhere('r.id = ? ', array($tmpCartOrderItem['order_item_id']))
						->execute()
						->getFirst()
					;
					if ($tmpCartOrderItemModel) {
						$tmpCartOrderItemModel['quantity'] = $tmpCartOrderItem['count'];
						$tmpCartOrderItemModel->save();
					}
				}

				$orderModel['sum_price'] = $cart->getCalculatedSumPrice();
				$orderModel->save();

				if ($couponHistoryModel) {

					$tmpPossibleOldCouponValue = $couponHistoryModel->Coupon['value'] + $couponHistoryModel['value'];
					$tmpPossibleCouponValue = round($cart->getOrderSum() + $cart->getPaymentServiceCosts() + $cart->getRefund() - ($tmpPossibleOldCouponValue), 2);
					if ($tmpPossibleCouponValue > 0) {
						$tmpCartNewCouponValue = 0;
						$tmpCartUesedCouponValue = $cart->getOrderSum() + $cart->getPaymentServiceCosts() + $cart->getRefund() - $tmpPossibleCouponValue;
						$tmpCartCouponUsed = TRUE;
					} else {
						$tmpCartNewCouponValue = abs($tmpPossibleCouponValue);
						$tmpCartUesedCouponValue = $tmpPossibleOldCouponValue - $tmpCartNewCouponValue;
						$tmpCartCouponUsed = FALSE;
					}

					$couponHistoryModel->Coupon['is_used'] = $tmpCartCouponUsed;
					$couponHistoryModel->Coupon['value'] = $tmpCartNewCouponValue;
					$couponHistoryModel['value'] = $tmpCartUesedCouponValue;
					$couponHistoryModel->save();

				}

				if ($cart->getCountProducts() == 0) {
					$this->_redirect($this->_helper->url('edit', 'shop-order', 'admin', array('id'=>$orderModel->id, 'do'=>'orderCancel'), NULL, TRUE));
				}

				$this->_redirect($this->_helper->url('edit', 'shop-order', 'admin', array('modelListName'=>'Default_Model_ProductOrder'), NULL, TRUE) . '?' . implode('&', $passThoughViewVars));

			} else
			if ($do == 'sendOrderMail' &&
				!$orderModel->order_mail_send) {

				if ($orderModel->billing_number == NULL) {

					if(L8M_Config::getOption('shop.admin.order.generateBill') == FALSE) {

						$billingNumberForm = new Admin_Form_ShopOrder_BillingNumber();

						if ($billingNumberForm->isSubmitted() &&
								$billingNumberForm->isValid($this->getRequest()->getPost())) {

							$orderModel->billing_number = $billingNumberForm->getValue('billing_number');
							$orderModel->save();

							$this->_redirect($this->_helper->url('edit', 'shop-order', 'admin', array('id'=>$orderModel->id, 'do'=>'sendOrderMail')));
						} else {

							$this->view->form = $billingNumberForm;

							/**
							 * render generate bill number
							 */
							$this->_helper->viewRenderer('edit-generate-bill');
						}

					} else {

						$orderModel->billing_number = L8M_Config::getOption('shop.admin.order.FirstBillNumber') + $orderModel->id;
						if (L8M_Config::getOption('shop.admin.order.usePrefix')) {

							$orderModel->billing_number = L8M_Config::getOption('shop.admin.order.prefix') . L8M_Config::getOption('shop.admin.order.prefixSeparator') . $orderModel->billing_number;
						}

						$orderModel->save();

						$this->_redirect($this->_helper->url('edit', 'shop-order', 'admin', array('id'=>$orderModel->id, 'do'=>'sendOrderMail')));

					}

				} else {

// 					die();

					$orderInfos['billing_number'] = $orderModel->billing_number;

					/**
					 * email recipient
					 */
					$customerEmail = $orderModel->email;
					$customerName = $orderModel->firstname . ' ' . $orderModel->lastname;
					$customerName = trim($customerName);

					/**
					 * create email
					 */
					$email = L8M_MailV2::factory('order_confirm');
					$email
						->setFrom(L8M_Config::getOption('resources.mail.defaultFrom.email'), L8M_Config::getOption('resources.mail.defaultFrom.name'))
						->addTo($customerEmail, $customerName)
						->addBcc(L8M_Config::getOption('resources.mail.defaultToStore.email'))
					;

					/**
					 * html for email
					 */
					$view = Zend_Layout::getMvcInstance()->getView();


					/**
					 * create products array
					 */
					$products = $cart->getAllProducts();

					/**
					 * content
					 */
					$content = L8M_MailV2_Part::factory('header', $email);
					$content
						->setDynamicVar('HEADER', $this->view->prjEmailHeader())
					;
					$email->addPart($content);

					$orderDate = date('Y-m-d', strtotime($orderModel->created_at));

					$paymentLink = L8M_Library::getSchemeAndHttpHost() . '/shop/cart/payment/order/' . $orderModel->getOrderHash();

					/**
					 * order
					 */
					$content = L8M_MailV2_Part::factory('order_confirm', $email);
					$content
						->setDynamicVar('FIRSTNAME',$orderInfos['firstname'])
						->setDynamicVar('LASTNAME', $orderInfos['lastname'])
						->setDynamicVar('PAYMENT_LINK', $paymentLink)
						->setDynamicVar('ORDER_CONTENT_HTML', $view->prjEmailOrder('html', $products, $orderInfos, TRUE, $orderDate))
						->setDynamicVar('ORDER_CONTENT_PLAIN', $view->prjEmailOrder('plain', $products, $orderInfos, TRUE, $orderDate))
						->setDynamicVar('ORDER_ID', $orderInfos['id'])
						->setDynamicVar('ORDERDATE', date('d.m.Y', strtotime($orderInfos['created_at'])))
						->setDynamicVar('ORDERTIME', date('H:i:s', strtotime($orderInfos['created_at'])))
						->setDynamicVar('ACCOUNT_LINK', L8M_Library::getSchemeAndHttpHost() . '/user')
					;
					$email->addPart($content);

					/**
					 * order end
					*/
					$content = L8M_MailV2_Part::factory('order_end', $email);
					$content
					->setDynamicVar('AGB_LINK', L8M_Library::getSchemeAndHttpHost() .  $view->url(array('module'=>'default', 'controller'=>'terms-and-conditions', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('CANCELLATION_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'instructions-of-cancellation', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('PRIVACY_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'privacy-policy', 'action'=>'index'), NULL, TRUE))
						->setDynamicVar('EMAIL', PRJ_SiteConfig::getOption('email'))
					;
					$email->addPart($content);

					/**
					 * footer
					*/
					$content = L8M_MailV2_Part::factory('footer', $email);
					$email->addPart($content);

					if (L8M_Config::getOption('shop.admin.order.generateBill') == TRUE) {

						/*
						 * create billing pdf
						 */
						$billingPDF = new PRJ_BillingPdf();
						$billingPDF
							->setData(array(
								'billing_address'=>array(
									'company'=>$orderInfos['company'],
									'name'=>$orderInfos['firstname'] . ' ' . $orderInfos['lastname'],
									'street'=>$orderInfos['billing_street'] . ' ' . $orderInfos['billing_street_number'] . ' ' . $orderInfos['billing_address_line_1'] . ' ' . $orderInfos['billing_address_line_2'],
									'zip'=>$orderInfos['billing_zip'],
									'city'=>$orderInfos['billing_city'],
									'country'=>$orderInfos['billing_country'],
								),
								'orderModel'=>$orderModel,
								'type'=>'billing',
								'billing_number'=>$orderModel['billing_number'],
								'billing_date'=>$orderModel['created_at'],
								'cart'=>$cart,
							))
							->createPdf('F')
						;


						/*
						 * attachment
						 */
						$billingPDFilename = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'TCPDF' . DIRECTORY_SEPARATOR . $orderModel->billing_number . '.pdf';
						if (file_exists($billingPDFilename)) {
							$emailAttachment = L8M_MailV2_Part_Attachment::factory('order_confirm_attachment', $email);
							$emailAttachment
								->addItem($billingPDFilename)
							;

							$email->addPart($emailAttachment);
						}

					}

					/**
					 * send email
					 */
					try {
						$email->send();
						$this->view->mailmessage = $this->view->translate('Bestellbestätigungsemail wurde verschickt.','de');

						$model = Doctrine_Query::create()
							->from('Default_Model_ProductOrder r')
							->addWhere('r.id = ? ', array($id))
							->execute()
							->getFirst()
						;

						$dataArray = array(
							'order_mail_send'=>TRUE,
							'state_dispose'=>TRUE
						);

						/**
						 * add data to model
						 */
						$model->merge($dataArray);

						/**
						 * save model
						 */
						$model->save();
					} catch (L8M_Mail_Exception $exception) {
						$this->view->mailmessage = $this->view->translate('Fehler beim Verschicken der Bestellbestätigungsemail.','de');
					}

					$this->_redirect($this->_helper->url('edit', 'shop-order', 'admin', array('modelListName'=>'Default_Model_ProductOrder'), NULL, TRUE) . '?' . implode('&', $passThoughViewVars));
				}
			}

			if ($do == 'orderPayed' &&
				$orderModel['state_wait_for_payment'] == TRUE) {

				/**
				 * get view
				 */
				$view = Zend_Layout::getMvcInstance()->getView();

				$orderModel['state_wait_for_payment'] = FALSE;
				$orderModel->save();

				/**
				 * email recipient
				 */
				$customerEmail = $orderModel->email;
				$customerName = $orderModel->firstname . ' ' . $orderModel->lastname;
				$customerName = trim($customerName);

				/**
				 * create email
				*/
				$email = L8M_MailV2::factory('order_payment');
				$email
					->setFrom(L8M_Config::getOption('resources.mail.defaultFrom.email'), L8M_Config::getOption('resources.mail.defaultFrom.name'))
					->addTo($customerEmail, $customerName)
					->addBcc(L8M_Config::getOption('resources.mail.defaultToStore.email'))
				;

				/**
				 * content
				 */
				$content = L8M_MailV2_Part::factory('header', $email);
				$content
					->setDynamicVar('HEADER', $this->view->prjEmailHeader())
				;
				$email->addPart($content);

				/**
				 * order
				 */
				$content = L8M_MailV2_Part::factory('order_payment', $email);
				$content
					->setDynamicVar('FIRSTNAME',$orderInfos['firstname'])
					->setDynamicVar('LASTNAME', $orderInfos['lastname'])
					->setDynamicVar('ORDER_ID', $orderInfos['id'])
					->setDynamicVar('AGB_LINK', L8M_Library::getSchemeAndHttpHost() .  $view->url(array('module'=>'default', 'controller'=>'terms-and-conditions', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('CANCELLATION_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'instructions-of-cancellation', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('PRIVACY_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'privacy-policy', 'action'=>'index'), NULL, TRUE))
				;
				$email->addPart($content);

				/**
				 * order end
				*/
				$content = L8M_MailV2_Part::factory('order_end', $email);
				$content
					->setDynamicVar('AGB_LINK', L8M_Library::getSchemeAndHttpHost() .  $view->url(array('module'=>'default', 'controller'=>'terms-and-conditions', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('CANCELLATION_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'instructions-of-cancellation', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('PRIVACY_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'privacy-policy', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('EMAIL', PRJ_SiteConfig::getOption('email'))
				;
				$email->addPart($content);

				/**
				 * footer
				*/
				$content = L8M_MailV2_Part::factory('footer', $email);
				$email->addPart($content);


				/**
				 * send email
				*/
				try {

					$email->send();
					$this->view->mailmessage = $this->view->translate('Zahlungseingangsbestätigungsemail wurde verschickt.','de');

					$model = Doctrine_Query::create()
						->from('Default_Model_ProductOrder r')
						->addWhere('r.id = ? ', array($id))
						->execute()
						->getFirst()
					;

					$dataArray = array(
							'state_dispose'=>TRUE
					);

					/**
					 * add data to model
					*/
					$model->merge($dataArray);

					/**
					 * save model
					*/
					$model->save();

				} catch (L8M_Mail_Exception $exception) {
					$this->view->mailmessage = $this->view->translate('Fehler beim Verschicken der Bestellbestätigungsemail.','de');
				}
				$this->_redirect($this->_helper->url('edit', 'shop-order', 'admin', array('modelListName'=>'Default_Model_ProductOrder'), NULL, TRUE) . '?' . implode('&', $passThoughViewVars));
			}

			if ($do == 'sendShipMail' &&
				!$orderModel->ship_mail_send) {

				/**
				 * get view
				 */
				$view = Zend_Layout::getMvcInstance()->getView();

				/**
				 * email recipient
				 */
				$customerEmail = $orderModel->email;
				$customerName = $orderModel->firstname . ' ' . $orderModel->lastname;
				$customerName = trim($customerName);

				/**
				 * create email

				/**
				 * create email
				*/
				$email = L8M_MailV2::factory('order_shipped');
				$email
					->setFrom(L8M_Config::getOption('resources.mail.defaultFrom.email'), L8M_Config::getOption('resources.mail.defaultFrom.name'))
					->addTo($customerEmail, $customerName)
					->addBcc(L8M_Config::getOption('resources.mail.defaultToStore.email'))
				;

				/**
				 * content
				 */
				$content = L8M_MailV2_Part::factory('header', $email);
				$content
					->setDynamicVar('HEADER', $this->view->prjEmailHeader())
				;
				$email->addPart($content);

				/**
				 * order
				 */
				$content = L8M_MailV2_Part::factory('order_shipped', $email);
				$content
					->setDynamicVar('FIRSTNAME',$orderInfos['firstname'])
					->setDynamicVar('LASTNAME', $orderInfos['lastname'])
				;
				$email->addPart($content);

				/**
				 * order end
				*/
				$content = L8M_MailV2_Part::factory('order_end', $email);
				$content
					->setDynamicVar('AGB_LINK', L8M_Library::getSchemeAndHttpHost() .  $view->url(array('module'=>'default', 'controller'=>'terms-and-conditions', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('CANCELLATION_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'instructions-of-cancellation', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('PRIVACY_LINK', L8M_Library::getSchemeAndHttpHost() . $view->url(array('module'=>'default', 'controller'=>'privacy-policy', 'action'=>'index'), NULL, TRUE))
					->setDynamicVar('EMAIL', PRJ_SiteConfig::getOption('email'))
				;
				$email->addPart($content);

				/**
				 * footer
				 */
				$content = L8M_MailV2_Part::factory('footer', $email);
				$email->addPart($content);
				$email->addBcc(L8M_Config::getOption('resources.mail.defaultToStore.email'));

				/**
				 * send email
				 */
				try {
					$email->send();
					$this->view->mailmessage = $this->view->translate('Versandbestätigungsemail wurde verschickt.','de');

					$model = Doctrine_Query::create()
						->from('Default_Model_ProductOrder r')
						->addWhere('r.id = ? ', array($id))
						->execute()
						->getFirst()
					;

					$dataArray = array(
						'ship_mail_send'=>TRUE,
						'state_shipped'=>TRUE
					);

					/**
					 * add data to model
					 */
					$model->merge($dataArray);

					/**
					 * save model
					 */
					$model->save();
				} catch (L8M_Mail_Exception $exception) {
					$this->view->mailmessage = $this->view->translate('Fehler beim Verschicken der Versandbestätigungsemail.','de');
				}
				$this->_redirect($this->_helper->url('edit', 'shop-order', 'admin', array('modelListName'=>'Default_Model_ProductOrder'), NULL, TRUE) . '?' . implode('&', $passThoughViewVars));
			}

			/**
			 * start model list
			 */
			$this->_modelList->editModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
				'doBeforeFormOutput'=>array(
				),
				'doBeforeSave'=>array(
				),
				'addGeneratedColumnValues'=>array(
				),
				'addGeneratedValues'=>array(
				),
				'doAfterSave'=>array(
				),
			)));
		}
	}

	/**
	 * rechnung action.
	 *
	 * @return void
	 */
	public function rechnungAction () {

		if (L8M_Config::getOption('shop.admin.order.generateBill') == FALSE) {

			$this->_redirect($this->_helper->url('index', 'shop-order', 'admin'));
		}


		$id = $this->_request->getParam('id', NULL, FALSE);

		$cart = PRJ_Shop_Cart_FromOrder::factory($id);
		$orderInfos = $cart->getOrderInfos();
		$orderModel = $cart->getOrderModel();

		if ($orderModel->billing_number == NULL) {

			$this->_redirect($this->_helper->url('edit', 'shop-order', 'admin', array('id'=>$orderModel->id, 'report'=>'no-mail')));
		}

		/**
		 * kill layout
		 */
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();

		/*
		 * create billing pdf
		*/
		$billingPDF = new PRJ_BillingPdf();
		$billingPDF
			->setData(array(
				'billing_address'=>array(
					'company'=>$orderInfos['company'],
					'name'=>$orderInfos['firstname'] . ' ' . $orderInfos['lastname'],
					'street'=>$orderInfos['billing_street'] . ' ' . $orderInfos['billing_street_number'] . ' ' . $orderInfos['billing_address_line_1'] . ' ' . $orderInfos['billing_address_line_2'],
					'zip'=>$orderInfos['billing_zip'],
					'city'=>$orderInfos['billing_city'],
					'country'=>$orderInfos['billing_country'],
				),
				'orderModel'=>$orderModel,
				'type'=>'billing',
				'billing_number'=>$orderModel['billing_number'],
				'billing_date'=>$orderModel['created_at'],
				'cart'=>$cart,
			))
			->createPdf('I')
		;
	}

	/**
	 * rechnung action.
	 *
	 * @return void
	 */
	public function lieferscheinAction () {

		$id = $this->_request->getParam('id', NULL, FALSE);

		$cart = PRJ_Shop_Cart_FromOrder::factory($id);
		$orderInfos = $cart->getOrderInfos();
		$orderModel = $cart->getOrderModel();

		/**
		 * kill layout
		 */
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();

		/*
		 * create billing pdf
		*/
		$deliveryPDF = new PRJ_DeliverPdf();
		$deliveryPDF
			->setData(array(
				'delivery_address'=>array(
					'company'=>$orderInfos['delivery_company'],
					'name'=>$orderInfos['delivery_firstname'] . ' ' . $orderInfos['delivery_lastname'],
					'street'=>$orderInfos['delivery_street'] . ' ' . $orderInfos['delivery_street_number'] . ' ' . $orderInfos['delivery_address_line_1'] . ' ' . $orderInfos['delivery_address_line_2'],
					'zip'=>$orderInfos['delivery_zip'],
					'city'=>$orderInfos['delivery_city'],
					'country'=>$orderInfos['delivery_country'],
				),
				'orderModel'=>$orderModel,
				'type'=>'billing',
				'billing_number'=>$orderInfos['billing_number'],
				'billing_date'=>$orderInfos['created_at'],
			))
			->createPdf('I')
		;
	}

	/**
	 * PDF action.
	 *
	 * @return void
	 */
	public function exportAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Export');

		/**
		 * this can go on for 5 minutes
		 */
		set_time_limit(300);

		/**
		 * start model list
		 */
		$this->_modelList->exportModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
		)));
	}

	/**
	 * download action.
	 *
	 * @return void
	 */
	public function downloadAction ()
	{

		$mediaID = $this->getRequest()->getParam('media', NULL, FALSE);

		$mediaModel = Default_Model_Media::getModelByID($mediaID, 'Default_Model_Media');

		if(!($mediaModel instanceof Default_Model_Media)) {
			$this->_redirect->url(array('index', 'shop-order', 'default'));
		}

		$fileName = $mediaModel->file_name;
		$filePath = $mediaModel->getLink(TRUE);

		/**
		 * set file name in attachment
		 */
		$attachment = 'attachment; filename="' . $fileName . '"';

		/**
		 * get mime type of file
		 */
		if (class_exists('finfo')) {
			$fi = new finfo(FILEINFO_MIME);
			$mime_type = $fi->buffer(file_get_contents($fileName));
		} else {
			$mime_type = NULL;
		}

		/**
		 * disable layout
		 */
		Zend_Layout::getMvcInstance()->disableLayout();
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);

		$this->getResponse()
			->setHeader('Content-Type', $mime_type)
			->setHeader('Content-Disposition', $attachment)
			->setBody(file_get_contents($filePath))
		;

		Zend_Registry::set('download', 'TRUE');
	}
}