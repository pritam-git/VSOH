<?php

/**
 * L8M 
 * 
 *
 * @filesource /library/L8M/Saferpay.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Saferpay.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Saferpay
 * 
 * 
 */
class L8M_Saferpay
{
    
	/**
	 * 
	 * 
	 * Class Constants
	 * 
	 * 
	 */

	/**
	 * URL to be called when creating payment initialization
	 */
	const URL_CREATE_PAY_INIT = 'www.saferpay.com/hosting/CreatePayInit.asp';
	/**
	 * URL to be called when verifying payment confirmation
	 */
	const URL_VERIFY_PAY_CONFIRM = 'www.saferpay.com/hosting/VerifyPayConfirm.asp';		
	/**
	 * URL to be calles when completing payment
	 */
	const URL_PAY_COMPLETE = 'www.saferpay.com/hosting/PayComplete.asp';
	
	const ACTION_FAIL = 'fail';
	const ACTION_CANCEL = 'cancel';
	const ACTION_SUCCESS = 'success';
	
	/**
	 * Password needed for testMode enabled
	 */
	const TEST_PASSWORD = 'XAjc3Kna';
	/**
	 * accountId for testMode enabled
	 */
	const TEST_ACCOUNTID = '99867-94913159';
	
	/**
	 * 
	 * 
	 * Class Variables
	 * 
	 * 
	 */
	
	/**
	 *
	 *  
	 * Account Information
	 * 
	 * 
	 */

	/**
	 * Identifies the merchants account at the Saferpay System. An account may 
	 * be associated with different payment systems
	 *
	 * @var string 
	 */
	protected $_accountId = NULL;
	
	/**
	 * The amount to be reserved specified in minor currency unit. E.g. EUR 1.35
	 * must be passed as 135.
	 *
	 * @var string
	 */		
	protected $_amount = NULL;

	/**
	 * Specifies the transaction currency with a string ISO 4217 currency code, 
	 * e.g. EUR, CHF etc.
	 *
	 * @var string
	 */
	protected $_currency = NULL;
	
	/**
	 * A textual description of the article or offer which will be displayed in the Saferpay Virtual Terminal. 
	 *
	 * @var string
	 */
	protected $_description = NULL;

	/**
	 * The ORDERID is a string (max. 80 characters) which can be used by the 
	 * merchant for identifying the transaction. Saferpay converts the ORDERID 
	 * to the RetrievalNumber and passes it to the acquirer as your merchant 
	 * reference number.
	 *
	 * @var string
	 */
	protected $_orderId = NULL;
	
	/**
	 * 
	 * 
	 * Navigation
	 * 
	 * 
	 */
	
	/**
	 * An URL identifing the web page to display after the reservation has been 
	 * successfully completed. The confirmation message will be appended to this 
	 * URL before it is sent to the merchant"s web server.
	 *
	 * @var string
	 */
	protected $_successLink = NULL;
	
	/**
	 * This parameter indicates the page to display after a reservation attempt 
	 * has failed.
	 *
	 * @var string
	 */
	protected $_failLink = NULL;
	
	/**
	 * This page is displayed in the client"s browser if the transaction is 
	 * aborted.
	 *
	 * @var string
	 */
	protected $_backLink = NULL;

	/**
	 * optional, Specifies the period of time in seconds to close the VT 
	 * automatically; e.g. 10 closes the VT after 10 seconds and opens 
	 * SUCCESSLINK.
	 *
	 * @var string
	 */
	protected $_autoClose = NULL;
	
	/**
	 * optional, Saferpay sends the result of the a successful authorization or 
	 * payment directly to this URL (PayConfirm). The response data contains the
	 * DATA and SIGNATURE elements as POST parameters. Use VerifyPayConfirm to
	 * verify the content and it"s digital signature.
	 *
	 * @var string
	 */
	protected $_notifyUrl = NULL;
	
	/**
	 * 
	 * 
	 * Processing Options
	 * 
	 * 
	 */
	
	/**
	 * Must be set to "yes" or "no". Specified if multiple offers may be 
	 * collected in the Virtual Terminal before a reservation is performed on 
	 * the total amount. If this option is set to "yes", the DELIVERY option 
	 * must be activiated, otherwise the Virtual Terminal will deny the 
	 * reservation request.
	 *
	 * @var string
	 */
	protected $_allowCollect = NULL;
	
	/**
	 * Must be set to "yes" or "no" If set to "yes" a input form for the 
	 * customer delivery address appears during the Virtual Terminal session.
	 *
	 * @var string
	 */
	protected $_delivery = NULL;
	
	/**
	 * optional, If set to "yes" the CVC2/CVV2 input field is active.
	 *
	 * @var string
	 */
	protected $_ccCVC = NULL;
	
	/**
	 * optional, If set to "yes" the card holder name input field is active.
	 *
	 * @var string
	 */
	protected $_ccName = NULL;

	/**
	 * optional, Email address of the merchant. Saferpay sends a notification 
	 * message after a successful reservation
	 *
	 * @var string
	 */
	protected $_notifyAddress = NULL;

	/**
	 * optional, Email address of the customer. Saferpay sends a notification 
	 * message after a successful purchase.
	 *
	 * @var string
	 */
	protected $_userNotify = NULL;
	
	/**
	 * optional, Specifies the language for the Virtual Terminal session. 
	 * Possible values are "en" (English), "de" (German), "fr" (French) and "it" 
	 * (Italian). Per default the Virtual Terminal uses the browsers language 
	 * setting to determine the dialog language.
	 *
	 * @var string
	 */ 		
	protected $_langId = NULL;
	
	/**
	 * 
	 * 
	 * Recurring Payments
	 * 
	 * 
	 */
	
	/**
	 * optional, Flag for Recurring. The default is "no".
	 * "no" RECFREQ and RECEXP are ignored. Transaction is processed as non 
	 * "Recurring". "yes" RECFREQ and RECEXP must be set for initial payment; 
	 * for following payments not necessary.
	 *
	 * @var string
	 */
	protected $_recurring = NULL;
	
	/**
	 * optional " Integer formatted as YYYYMMDD,  Minimum numberof days between 
	 * payments, e.g. 28 days conform to 1 month. If RECFREQ is used then RECEXP
	 * is mandatory and vice versa.
	 *
	 * @var string
	 */
	protected $_recurringFrequency = NULL;

	/**
	 * optional - Integer formatted as YYYYMMDD
	 * Date when no more transactions will follow. Must be in future. If RECEXP 
	 * is used then RECFREQ is mandatory and vice versa. 3-D secure ACS verifies
	 *  whether card"s expiration will suffice.
	 *
	 * @var string
	 */
	protected $_recurringExpires = NULL;
	
	/**
	 * 
	 * 
	 * Installment Payments
	 * 
	 * 
	 */
	
	/**
	 * optional " Values "yes" or "no"
	 * Flag for Installment. The default is "no".
	 * "no" INSTCOUNT is ignored. Transaction is processed as non "Installment".
	 * "yes" INSTCOUNT must be set for initial payment; for following payments 
	 * not necessary.
	 *
	 * @var string
	 */
	protected $_installment = NULL;
	
	/**
	 * Optional - Integer
	 * Number of installment fees merchant and customer agreed.If set value must 
	 * be > 1. INSTALLMENT and RECURRING cannot be set at the same time. If so, 
	 * transaction will be declined.
	 *
	 * @var string
	 */
	protected $_installmentCount = NULL;
	
	/**
	 * 
	 * 
	 * Transaction Reference for Recurring and Installment Payments
	 *
	 *  
	 */
	
	/**
	 * optional - Is referenced to PayConfirm attribute "ID". Uses Saferpay 
	 * Transaction ID to reference to initial transaction.
	 *
	 * @var string
	 */
	protected $_referenceId = NULL;
	
	/**
	 * optional - Is referenced to PayInit attribute "ORDERID". Uses merchant 
	 * reference number to reference to initial transaction.
	 *
	 * @var string
	 */
	protected $_referenceOrderId = NULL;
	
	/**
	 * 
	 * 
	 * Other Options
	 * 
	 * 
	 */

	/**
	 * optional, Specifies the duration of the payment link. The value of this 
	 * parameter must be formatted as YYYYMMDDhhmmss. After the DURATION time 
	 * the payment link will be declined.
	 *
	 * @var string
	 */
	protected $_duration = NULL;

	/**
	 * optional, Use this parameter to show the customer specific payment 
	 * methods. PROVIDERSET must contain a comma delimited list of provider 
	 * id"s. A current list of provider id"s could be found here: 
	 * http://www.saferpay.com/help/ProviderTable.asp.
	 *
	 * @var string
	 */
	protected $_providerSet = NULL;
	
	/**
	 * 
	 * 
	 * Style Attributes
	 * 
	 * 
	 */
	
	/**
	 * optional, Specifies the color of the VT body in HTML format.
	 *
	 * @var string
	 */
	protected $_bodyColor = NULL;

	/**
	 * optional, Specifies the color of the header of the VT header.
	 *
	 * @var string
	 */
	protected $_headColor = NULL;
	
	/**
	 * optional, specifies the color of the head-line.
	 *
	 * @var string
	 */
	protected $_headlineColor = NULL;
	
	/**
	 * optional,Specifies the color of the menu bar background.
	 *
	 * @var string
	 */ 
	protected $_menuColor = NULL;
	
	/**
	 * optional, Specifies the font color of the body area.
	 *
	 * @var string
	 */
	protected $_bodyFontColor = NULL;
	
	/**
	 * optional, Specifies the font color of the head.
	 *
	 * @var string
	 */
	protected $_headFontColor = NULL;
	
	/**
	 * optional, Specifies the font color of the menu.
	 *
	 * @var string
	 */
	protected $_menuFontColor = NULL;

	/**
	 * optional, Specifies the font color of the links of the body area.
	 *
	 * @var string
	 */
	protected $_linkColor = NULL;
	
	/**
	 * optional, If set to "no" this option disables the language selector in 
	 * the menu section of the VT.
	 *
	 * @var string
	 */
	protected $_showLanguages = NULL;
	
	/**
	 * optional, Defines the font-face used in the VT
	 *
	 * @var string
	 */
	protected $_font = NULL;
	
	/**
	 * 
	 * 
	 * PayComplete Attributes
	 * 
	 * 
	 */
	
	/**
	 * optional - Specifies an extended action like settlement, close batch or 
	 * cancellation. 
	 *
	 * @var string
	 */
	protected $_action = NULL;
	
	/**
	 * 
	 * 
	 * SaferPay Returns
	 * 
	 * 
	 */
	
	/**
	 * data, contains XML data posted via $_GET to $this->notifyURL
	 *
	 * @var string
	 */
	protected $_data = NULL;
	
	/**
	 * signature, contains token posted via $_GET to $this->notifyURL
	 *
	 * @var string
	 */
	protected $_signature = NULL;
	
	/**
	 * SaferPay ID
	 *
	 * @var string
	 */
	protected $_saferPayId = NULL;
	
	/**
	 * token
	 *
	 * @var string
	 */
	protected $_token = NULL;
	
	/**
	 * Unique identifier of the payment provider.
	 *
	 * @var string
	 */
	protected $_providerId = NULL;
	
	/**
	 * Name of the payment provider.
	 *
	 * @var string
	 */
	protected $_providerName = NULL;
	
	/**
	 * Identifier of the payment application.
	 * 
	 * @var string
	 */
	protected $_paymentApplication = NULL;
	
	/**
	 * optional - Electronic Commerce Indicator:
	 * 0 = SSL-Transaction
	 * 1 = 3-D Secure transaction, fully authenticated (liability shift)
	 * 2 = 3-D Secure transaction, not authenticated (liability shift)
	 *
	 * @var string
	 */
	protected $_electronicCommerceIndicator = NULL;
	
	/**
	 * 
	 * 
	 * Internals
	 *  
	 * 
	 */

	/**
	 * Contains error message if error has occured
	 *
	 * @var string
	 */
	protected $_errors = NULL;
	
	/**
	 * Contains flag for testMode
	 *
	 * @var bool
	 */
	protected $_testModeEnabled = NULL;
	
	/**
	 * If switching into testMode, priorly set accountId will be stored here as
	 * a means to easily switch from testMode into liveMode
	 *
	 * @var string
	 */
	protected $_preTestModeAccountId = NULL;
	
	/**
	 * Contains timestamp of when the payment was initialized
	 *
	 * @var int
	 */
	protected $_payInitTime = NULL;
	
	/**
	 * Contains timestamp of when the payment was confirmed
	 *
	 * @var int
	 */		
	protected $_payConfirmTime = NULL;
	
	/**
	 * Contains timestamp of when the payment confirmation was verified
	 *
	 * @var int
	 */
	protected $_payConfirmVerifyTime = NULL;
	
	/**
	 * Contains timestamp of when the payment was completed
	 *
	 * @var int
	 */
	protected $_payCompleteTime = NULL;
	
	/**
	 * An array of allowed Electronic Commerce Indicator
	 *
	 * @var array
	 */
	private $_allowedElectronicCommerceIndicators = array('0'=>'SSL-Transaction',
														 '1'=>'3-D Secure transaction, fully authenticated (liability shift)',
														 '2'=>'3-D Secure transaction, not authenticated (liability shift)');
	
	/**
	 * An array of allowed actions
	 *
	 * @var array
	 */
	protected $_allowedActions = array('Settlement',
									   'CloseBatch',
									   'Cancel');	
	
	/**
	 * 
	 * 
	 * 
	 * Class Constructor
	 * 
	 * 
	 * 
	 */
			
	/**
	 * Constructs L8M_Saferpay instance.
	 *
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($options = NULL)
	{
	    $this->_init($options);
	}
	
	/**
	 * 
	 * 
	 * Initialization Methods
	 * 
	 * 
	 */
	
	/**
	 * Initializes L8M_Saferpay instance with passed options.
	 *
	 * @param  array|Zend_Config $options
	 * @return L8M_Saferpay
	 */
	protected function _init($options = NULL)
	{
	    $this->setOptions($options);
	    return $this;
	}
	
	/**
	 * 
	 * 
	 * Setter Methods
	 * 
	 * 
	 */
	
	/**
	 * Sets accountId
	 *
	 * @todo   validation
	 * @param  string      $id
	 * @return L8M_Saferpay
	 */
	public function setAccountId($id = NULL)
	{
        $this->_accountId = $id;
		return $this;
	}
	
	/**
	 * Sets amount for this payment
	 *
	 * @param  string      $amount
	 * @return L8M_Saferpay
	 */
	public function setAmount($amount = NULL)
	{
		if ($amount == NULL || 
		    preg_match('/^[1-9]+[0-9]*$/', $amount)) {
			$this->_amount = $amount;
		}
		return $this;
	}		
	
	/**
	 * Sets currency for this payment
	 *
	 * @param  string      $currency
	 * @return L8M_Saferpay 
	 */
	public function setCurrency($currency = NULL)
	{
		if ($currency == NULL || 
		    preg_match('/^[A-Z]{3}$/', $currency)) {
			$this->_currency = $currency;
		}
		return $this;
	}			
	
	/**
	 * Sets orderId
	 *
	 * @param  string      $id
	 * @return L8M_Saferpay
	 */
	public function setOrderId($id = NULL)
	{
		if ($id == NULL || 
		    preg_match('/^.{0,80}+$/', $id)) {
			$this->_orderId = $id;
		}
		return $this;
	}				
	
	/**
	 * Sets description
	 *
	 * @todo   validation
	 * @param  string      $description
	 * @return L8M_Saferpay
	 */		
	public function setDescription($description = NULL)
	{
		$this->_description = $description;
		return $this;
	}		

	
	/**
	 * Set link to which should be redirected on success
	 *
	 * @param  string      $link
	 * @return L8M_Saferpay
	 */
	public function setSuccessLink($link = NULL)
	{
		if ($link == NULL ||
		    L8M_Library::isLink($link)) {
			$this->_successLink = $link;
		}
		return $this;
	}		
	
	/**
	 * Sets link to be redirected to if payment fails
	 *
	 * @param  string      $link
	 * @return L8M_Saferpay
	 */
	public function setFailLink($link = NULL)
	{
		if ($link == NULL || 
		    L8M_Library::isLink($link)) {
			$this->_failLink = $link;
		}
		return $this;
	}		
	
	/**
	 * Sets link to be redirected to if payment is cancelled
	 *
	 * @param  string      $link
	 * @return L8M_Saferpay
	 */
	public function setBackLink($link = NULL)
	{
		if ($link == NULL || 
		    L8M_Library::isLink($link)) {
			$this->_backLink = $link;
		}
		return $this;
	}		
	
	/**
	 * Sets time in seconds after which payment window automatically redirects 
	 * after successful payment
	 *
	 * @param  string      $autoClose
	 * @return L8M_Saferpay
	 */
	public function setAutoClose($autoClose = NULL)
	{
		if ($autoClose == NULL || 
		    preg_match('/^[1-9]+[0-9]*$/', $autoClose)) {
			$this->_autoClose = $autoClose;
		}
		return $this;
	}		
	
	/**
	 * Sets URL to which the result of the payment authorization should be sent
	 * for payment verification  
	 *
	 * @param  string      $notifyUrl
	 * @return L8M_Saferpay
	 */
	public function setNotifyUrl($notifyUrl = NULL)
	{
		if ($notifyUrl == NULL || 
		    L8M_Library::isLink($notifyUrl)) {
			$this->_notifyUrl = $notifyUrl;
		}
		return $this;
	}		
	
	/**
	 * Sets allowCollect flag
	 *
	 * @param  string      $allowCollect
	 * @return L8M_Saferpay 
	 */
	public function setAllowCollect($allowCollect = NULL)
	{
	    $allowCollect = trim(strtolower($allowCollect));
		if ($allowCollect == NULL || 
	        in_array($allowCollect, array('yes', 'no'))) {
			$this->_allowCollect = strtolower($allowCollect);
		}
		return $this;
	}		
	
	/**
	 * Sets delivery flag
	 *
	 * @param  string      $delivery
	 * @return L8M_Saferpay
	 */
	public function setDelivery($delivery = NULL)
	{
	    $delivery = trim(strtolower($delivery));
		if ($delivery == NULL ||
		    in_array($delivery, array('yes', 'no'))) {
			$this->_delivery = $delivery;
		}
        return $this;
	}		
	
	/**
	 * Sets flag whether CVC2/CVV2 input is active
	 *
	 * @param  string  $ccCVC
	 * @return L8M_Saferpay
	 */
	public function setCcCVC($ccCVC = NULL)
	{
	    $ccCVC = trim(strtolower($ccCVC));
		if ($ccCVC == NULL || 
		    in_array($ccCVC, array('yes', 'no'))) {
			$this->_ccCVC = $ccCVC;
        }			
		return $this;
	}		
	
	/**
	 * Sets ccName flag
	 *
	 * @param  string      $ccName
	 * @return L8M_Saferpay
	 */
	public function setCcName($ccName = NULL)
	{
	    $ccName = trim(strtolower($ccName));
		if ($ccName == NULL || 
		    in_array($ccName, array('yes', 'no'))) {
			$this->_ccName = $ccName;
		}
		return $this;
	}		
	
	/**
	 * Sets eMail address to which notification will be sent on successful
	 * payment
	 *
	 * @param  string      $notifyAddress
	 * @return L8M_Saferpay
	 */
	public function setNotifyAddress($notifyAddress = NULL)
	{
		if ($notifyAddress == NULL || 
		    L8M_Library::isEmail($notifyAddress)) {
			$this->_notifyAddress = $notifyAddress;
		}
		return $this;
	}		
	
	/**
	 * Sets eMail address to which customer targeted message will be sent on 
	 * successful payment
	 *
	 * @param  string      $userNotify
	 * @return L8M_Saferpay
	 */
	public function setUserNotify($userNotify = NULL)
	{
		if ($userNotify == NULL ||
		    L8M_Library::isEmail($userNotify)) {
			$this->_userNotify = $userNotify;
		}
		return $this;
	}

	/**
	 * Sets two-letter language identifier for SaferPay payment interface
	 *
	 * @param  string      $id
	 * @return L8M_Saferpay
	 */
	public function setLangId($id = NULL)
	{
	    $id = trim(strtolower($id));
		if ($id == NULL ||
		    in_array($id, array('en', 'de', 'fr', 'it'))) {
			$this->_langId = $id;
		}
		return $this;
	}			

	/**
	 * Sets recurring flag (for recurring payments)
	 *
	 * @param  string      $recurring
	 * @return L8M_Saferpay
	 */
	public function setRecurring($recurring = NULL)
	{
		$recurring = trim(strtolower($recurring));
		if (in_array($recurring, array('yes', 'no'))) {
			$this->_recurring = $recurring;
		}
		return $this;
	}
	
	/**
	 * Sets recurring frequency (for recurring payments)
	 *
	 * @todo   validation 
	 * @param  string      $recurringFrequency
	 * @return L8M_Saferpay
	 */
	public function setRecurringFrequency($recurringFrequency = NULL)
	{
	    $this->_recurringFrequency = $recurringFrequency;
		return $this;
	}

	/**
	 * Sets recurring expires time (for recurring payments)
	 *
	 * @todo   validation
	 * @param  string      $recurringExpires
	 * @return L8M_Saferpay
	 */
	public function setRecurringExpires($recurringExpires = NULL)
	{
        $this->_recurringExpires = $recurringExpires;
        return $this;
	}
	
	/**
	 * Sets installment flag (for installment payments)
	 *
	 * @param  string      $installment
	 * @return L8M_Saferpay
	 */
	public function setInstallment($installment = NULL)
	{
	    $installment = trim(strtolower($installment));
		if ($installment!=NULL && 
		    in_array($installment, array('yes', 'no'))) {
			$this->_installment = $installment;
		}
		return $this;
	}		
	
	/**
	 * Sets installment count (for installment payments) 
	 *
	 * @param  string      $installmentCount
	 * @return L8M_Saferpay
	 */
	public function setInstallmentCount($installmentCount = NULL)
	{
		if ($installmentCount!=NULL && 
		    preg_match('/^[1-9]+[0-9]*$/', $installmentCount)) {
			$this->_installmentCount = $installmentCount;
		}
		return $this;
	}
	
	/**
	 * Sets referenceId
	 *
	 * @param  string      $id
	 * @return L8M_Saferpay
	 */
	public function setReferenceId($id = NULL)
	{
	    if (is_string($id)) {
		    $this->_referenceId = $id;
	    }		    
		return $this;
	}
	
	/**
	 * Sets referenceOrderId
	 *
	 * @param  string      $id
	 * @return L8M_Saferpay
	 */
	public function setReferenceOrderId($id = NULL)
	{
	    if (is_string($id)) {
		    $this->_referenceOrderId = $id;
	    }		    
		return $this;
	}		
	
	/**
	 * Sets duration, i.e. timestamp of time when payment handle expires
	 *
	 * @todo   verify whether validation is correct (see manual) 
	 * @param  string      $duration
	 * @return L8M_Saferpay 
	 */
	public function setDuration($duration = NULL)
	{
		if ($duration !== NULL && 
		    preg_match('/^[0-9]{14}$/', $duration) && 
		    date('YmdHis')<$duration) {
			$this->_duration = $duration;
		}
		return $this;
	}		
	
	/**
	 * Sets providers to be accepted for the payment as a comma-separated list, 
	 * see list at http://www.saferpay.com/help/ProviderTable.asp
	 *
	 * @param  string      $providerSet
	 * @return L8M_Saferpay
	 */
	public function setProviderSet($providerSet = NULL)
	{
		if ($providerSet == NULL || 
		    preg_match('/^(([0-9]+],)*)[0-9]+$/', $providerSet)) {
			$this->_providerSet = $providerSet;
		}
		return $this;
	}		
	
	/**
	 * Sets body color to provided value
	 *
	 * @param  string      $color
	 * @return L8M_Saferpay
	 */
	public function setBodyColor($color = NULL)
	{
		if ($color === NULL || 
		    L8M_Library::isHtmlColor($color)) {
			$this->_bodyColor = $color;
		}
		return $this;
	}		
	
	/**
	 * Sets headColor to provided value
	 *
	 * @param  string      $color
	 * @return L8M_Saferpay
	 */
	public function setHeadColor($color = NULL)
	{
		if ($color === NULL || 
		    L8M_Library::isHtmlColor($color)) {
			$this->_headColor = $color;
		}
		return $this;
	}		
	
	/**
	 * Sets headlineColor to provided value
	 *
	 * @param  string      $color
	 * @return L8M_Saferpay
	 */
	public function setHeadlineColor($color = NULL)
	{
		if ($color === NULL || 
		    L8M_Library::isHtmlColor($color)) {
			$this->_headlineColor = $color;
		}
		return $this;
	}		
	
	/**
	 * Sets menuColor to provided value
	 *
	 * @param  string      $color
	 * @return L8M_Saferpay
	 */
	public function setMenuColor($color = NULL)
	{
		if ($color === NULL || 
		    L8M_Library::isHtmlColor($color)) {
			$this->_menuColor = $color;
		}
		return $this;
	}		
	
	/**
	 * Sets bodyFontColor to provided value
	 *
	 * @param  string      $color
	 * @return L8M_Saferpay 
	 */
	public function setBodyFontColor($color = NULL)
	{
		if ($color === NULL || 
		    L8M_Library::isHtmlColor($color)) {
			$this->_bodyFontColor = $color;
		}
		return $this;
	}		
	
	/**
	 * Sets headFontColor to provided value
	 *
	 * @param  string      $color
	 * @return L8M_Saferpay 
	 */
	public function setHeadFontColor($color = NULL)
	{
		if ($color === NULL || 
		    L8M_Library::isHtmlColor($color)) {
			$this->_headFontColor = $color;
		}
		return $this;
	}		
	
	/**
	 * Sets menuFontColor to provided value
	 *
	 * @param  string      $color
	 * @return L8M_Saferpay
	 */
	public function setMenuFontColor($color = NULL)
	{
		if ($color === NULL || 
		    L8M_Library::isHtmlColor($color)) {
			$this->_menuFontColor = $color;
		}
		return $this;
	}		
	
	/**
	 * Sets linkColor to provided value
	 *
	 * @param  string      $color
	 * @return L8M_Saferpay
	 */
	public function setLinkColor($color = NULL)
	{
		if ($color === NULL || 
		    L8M_Library::isHtmlColor($color)) {
			$this->_linkColor = $color;
		}
		return $this;
	}		
	
	/**
	 * Sets showLanguages flag.
	 *
	 * @param  string      $showLanguages
	 * @return L8M_Saferpay
	 */
	public function setShowLanguages($showLanguages = NULL)
	{
	    $showLanguages = trim(strtolower($showLanguages));
		if (in_array($showLanguages, array(NULL, 'no'))) {
			$this->_showLanguages = $showLanguages;
		}
		return $this;
	}		
	
	/**
	 * Sets font
	 *
	 * @todo   validation 
	 * @param  string      $font
	 * @return L8M_Saferpay
	 */
	public function setFont($font = NULL)
	{
		if ($font == NULL || 
		    preg_match('/^.+$/',$font)) {
			$this->_font = $font;
		}
		return $this;
	}		
	
	/**
	 * Sets data
	 *
	 * @param  string      $data
	 * @return L8M_Saferpay
	 */
	public function setData($data = NULL)
	{
		$this->_data = $data;
		return $this;
	}
	
	/**
	 * Sets signature
	 *
	 * @param  string      $signature
	 * @return L8M_Saferpay
	 */
	public function setSignature($signature = NULL)
	{
		$this->_signature = $signature;
		return $this;
	}
	
	/**
	 * Sets saferPayID as returned by SaferPay upon confirmation
	 *
	 * @todo   validation
	 * @param  string      $id
	 * @return L8M_Saferpay
	 */
	public function setSaferPayId($id = NULL)
	{
		$this->_saferPayId = $id;
		return $this;
	}				
	
	/**
	 * Sets token as returned by SaferPay upon confirmation
	 *
	 * @todo   validation
	 * @param  string      $token
	 * @return L8M_Saferpay
	 */
	public function setToken($token = NULL)
	{
		$this->_token = $token;
		return $this;
	}
	
	/**
	 * Sets providerId. Also see http://www.saferpay.com/help/ProviderTable.asp
	 *
	 * @todo   validation
	 * @param  string      $id
	 * @return L8M_Saferpay 
	 */
	public function setProviderId($id = NULL)
	{
		if ($id == NULL ||
		    preg_match('/^[1-9]+[0-9]*$/', $id)) {
			$this->_providerId = $id;
		}
		return $this;
	}

	/**
	 * Sets providerName
	 *
	 * @todo   validation
	 * @param  string      $name
	 * @return L8M_Saferpay
	 */
	public function setProviderName($name = NULL)
	{
		$this->_providerName = $name;
		return $this;
	}
	
	/**
	 * Sets paymentApplication
	 *
	 * @todo   validation
	 * @param  string      $paymentApplication
	 * @return L8M_Saferpay
	 */
	public function setPaymentApplication($paymentApplication = NULL)
    {
		$this->_paymentApplication = $paymentApplication;
		return $this;
	}
	
	/**
	 * Sets electronicCommerceIndicator
	 *
	 * @param  string      $electronicCommerceIndicator
	 * @return L8M_Saferpay
	 */
	public function setElectronicCommerceIndicator($electronicCommerceIndicator = NULL)
	{
		if ($electronicCommerceIndicator !== NULL && 
		    array_key_exists($electronicCommerceIndicator, $this->_allowedElectronicCommerceIndicators)) {
			$this->_electronicCommerceIndicator = $electronicCommerceIndicator;
		}
		return $this;
	}
	
	/**
	 * Sets action
	 *
	 * @param  string      $action
	 * @return L8M_Saferpay
	 */
	public function setAction($action = NULL)
	{
	    if ($action == NULL ||
	        in_array($action, $this->_allowedActions)) {
			$this->_action = $action;
		}
		return $this;
	}
	
	/**
	 * Sets time when payment was initialized 
	 *
	 * @todo   consider accessibility 
	 * @param  int          $time
	 * @return L8M_Saferpays
	 */
	protected function _setPayInitTime($time = NULL)
	{
	    if ($time === NULL) {
	        $time = time();
	    }
	    $this->_payInitTime = $time;
	    return $this;
	}
	
	/**
	 * Sets time when payment confirmation was verified
	 *
	 * @todo   consider accessibility 
	 * @param  int         $time
	 * @return L8M_Saferpay
	 */
	protected function _setPayConfirmVerifyTime($time = NULL)
	{
        if ($time === NULL) {
	        $time = time();
	    }
	    $this->_payConfirmVerifyTime = $time;
	    return $this;
	}
	
	/**
	 * Sets time when payment was confirmed
	 *
	 * @todo   consider accessibility
	 * @param  int         $time
	 * @return L8M_Saferpay
	 */
	protected function _setPayConfirmTime($time = NULL)
	{
	    if ($time === NULL) {
	        $time = time();
	    }
	    $this->_payConfirmTime = $time;
	    return $this;
	}
	
	/**
	 * Sets time when payment was completed
	 *
	 * @return int
	 */
	protected function _setPayCompleteTime($time = NULL)
	{
	    if ($time === NULL) {
	        $time = time();
	    }
	    $this->_payCompleteTime = $time;
	    return $this;
	}	

	/**
	 * 
	 * 
	 * Getter Methods
	 * 
	 * 
	 */
	
	/**
	 * Returns accountId
	 *
	 * @return string
	 */
	public function getAccountId()
	{
	    return $this->_accountId;
	}
	
	/**
	 * Returns amount
	 *
	 * @return string
	 */
	public function getAmount()
	{
	    return $this->_amount;
	}		
	
	/**
	 * Returns currency
	 *
	 * @return string 
	 */
	public function getCurrency()
	{
	    return $this->_currency;
	}			
	
	/**
	 * Returns orderId
	 *
	 * @return string
	 */
	public function getOrderId()
	{
	    return $this->_orderId;
	}				
	
	/**
	 * Returns description
	 *
	 * @return string
	 */		
	public function getDescription()
	{
	    return $this->_description;
	}		

	
	/**
	 * Returns successLink
	 * 
	 * @return string
	 */
	public function getSuccessLink()
	{
	    return $this->_successLink;
	}		
	
	/**
	 * Returns failLink
	 *
	 * @return string
	 */
	public function getFailLink()
	{
	    return $this->_failLink;
	}		
	
	/**
	 * Returns backLink
	 *
	 * @return string
	 */
	public function getBackLink()
	{
	    return $this->_backLink;
	}		
	
	/**
	 * Returns autoClose
	 * 
	 * @return string
	 */
	public function getAutoClose()
	{
	    return $this->_autoClose;
	}		
	
	/**
	 * Returns notifyUrl  
	 *
	 * @return string
	 */
	public function getNotifyUrl()
	{
	    return $this->_notifyUrl;
	}		
	
	/**
	 * Returns allowCollect
	 * 
	 * @return string
	 */
	public function getAllowCollect()
	{
	    return $this->_allowCollect;
	}		
	
	/**
	 * Returns delivery flag
	 * 
	 * @return string
	 */
	public function getDelivery()
	{
	    return $this->_delivery;
	}		
	
	/**
	 * Returns ccCVC
	 * 
	 * @return string
	 */
	public function getCcCVC()
	{
	    return $this->_ccCVC;
	}		
	
	/**
	 * Returns ccName
	 * 
	 * @return string
	 */
	public function getCcName()
	{
	    return $this->_ccName;
	}		
	
	/**
	 * Returns eMail address to which notification will be sent on successful
	 * payment
	 *
	 * @return string
	 */
	public function getNotifyAddress()
	{
	    return $this->_notifyAddress;
	}		
	
	/**
	 * Returns eMail address to which customer targeted message will be sent on 
	 * successful payment
	 *
	 * @return string
	 */
	public function getUserNotify()
	{
	    return $this->_userNotify;
	}

	/**
	 * Returns two-letter language identifier for SaferPay payment interface
	 * 
	 * @return string
	 */
	public function getLangId()
	{
	    return $this->_langId;
	}			

	/**
	 * Returns recurring flag (for recurring payments)
	 * 
	 * @return string
	 */
	public function getRecurring()
	{
	    return $this->_recurring;
	}
	
	/**
	 * Returns recurring frequency (for recurring payments)
	 * 
	 * @return string
	 */
	public function getRecurringFrequency()
	{
	    return $this->_recurringFrequency;
	}

	/**
	 * Returns recurring expires time (for recurring payments)
	 * 
	 * @return string
	 */
	public function getRecurringExpires()
	{
	    return $this->_recurringExpires;
	}
	
	/**
	 * Returns installment flag (for installment payments)
	 * 
	 * @return string
	 */
	public function getInstallment()
	{
	    return $this->_installment;
	}		
	
	/**
	 * Returns installment count (for installment payments)
	 * 
	 * @return string 
	 */
	public function getInstallmentCount()
	{
	    return $this->_installmentCount;
	}
	
	/**
	 * Returns referenceId
	 * 
	 * @return string
	 */
	public function getReferenceId()
	{
	    return $this->_referenceId;
	}
	
	/**
	 * Returns referenceOrderId
	 *
	 * @return string
	 */
	public function getReferenceOrderId()
	{
	    return $this->_referenceOrderId;
	}		
	
	/**
	 * Returns duration, i.e. timestamp of time when payment handle expires
	 * 
	 * @return string
	 */
	public function getDuration()
	{
	    return $this->_duration;
	}		
	
	/**
	 * Returns providerSet
	 * 
	 * @return string
	 */
	public function getProviderSet()
	{
	    return $this->_providerSet;
	}		
	
	/**
	 * Returns body color
	 * 
	 * @return string
	 */
	public function getBodyColor()
	{
	    return $this->_bodyColor;
	}		
	
	/**
	 * Returns head color
	 * 
	 * @return string
	 */
	public function getHeadColor()
	{
	    return $this->_headColor;
	}		
	
	/**
	 * Returns headline color
	 * 
	 * @return string
	 */
	public function getHeadlineColor()
	{
	    return $this->_headlineColor;
	}		
	
	/**
	 * Returns menuColor
	 * 
	 * @return string
	 */
	public function getMenuColor()
	{
	    return $this->_menuColor;
	}		
	
	/**
	 * Returns bodyFontColor
	 * 
	 * @return string 
	 */
	public function getBodyFontColor()
	{
	    return $this->_bodyFontColor;
	}		
	
	/**
	 * Returns headFontColor
	 *
	 * @param  string      $color
	 * @return L8M_Saferpay 
	 */
	public function getHeadFontColor()
	{
	    return $this->_headFontColor;
	}		
	
	/**
	 * Returns menuFontColor
	 * 
	 * @return string
	 */
	public function getMenuFontColor()
	{
	    return $this->_menuFontColor;
	}		
	
	/**
	 * Returns linkColor
	 * 
	 * @return string
	 */
	public function getLinkColor()
	{
	    return $this->_linkColor;
	}		
	
	/**
	 * Returns showLanguages flag
	 * 
	 * @return string
	 */
	public function getShowLanguages()
	{
	    return $this->_showLanguages;
	}		
	
	/**
	 * Returns font
	 * 
	 * @return string
	 */
	public function getFont()
	{
	    return $this->_font;
	}		
	
	/**
	 * Returns data
	 * 
	 * @return string
	 */
	public function getData()
	{
	    return $this->_data;
	}
	
	/**
	 * Returns signature
	 * 
	 * @return string
	 */
	public function getSignature()
	{
	    return $this->_signature;
	}
	
	/**
	 * Returns saferpayId as returned by SaferPay upon confirmation
	 * 
	 * @return string
	 */
	public function getSaferPayId()
	{
	    return $this->_saferPayId;
	}				
	
	/**
	 * Returns token as returned by SaferPay upon confirmation
	 * 
	 * @return string
	 */
	public function getToken()
	{
	    return $this->_token;
	}
	
	/**
	 * Returns providerId. Also see http://www.saferpay.com/help/ProviderTable.asp
	 * 
	 * @return string
	 */
	public function getProviderId()
	{
	    return $this->_providerId;
	}

	/**
	 * Returns providerName
	 * 
	 * @return string
	 */
	public function getProviderName()
	{
	    return $this->_providerName;
	}
	
	/**
	 * Returns paymentApplication
	 * 
	 * @return string
	 */
	public function getPaymentApplication()
    {
	    return $this->_paymentApplication;
	}
	
	/**
	 * Returns electronicCommerceIndicator
	 * 
	 * @return string
	 */
	public function getElectronicCommerceIndicator()
	{
	    return $this->_electronicCommerceIndicator;
	}
	
	/**
	 * Returns electronicCommerceIndicator name
	 *
	 * @return string
	 */	
	public function getElectronicCommerceIndicatorName()
	{
	    if (array_key_exists($this->_electronicCommerceIndicator, $this->_allowedElectronicCommerceIndicators)) {
	        return $this->_allowedElectronicCommerceIndicators[$this->_electronicCommerceIndicator];
	    }
	    return NULL;
	}		
	
	/**
	 * Returns time when payment was initialized 
	 *
	 * @return int
	 */
	public function getPayInitTime()
	{
		return $this->_payInitTime;
	}
	
	/**
	 * Returns time when payment confirmation was verified
	 *
	 * @return int
	 */
	public function getPayConfirmVerifyTime()
	{
		return $this->_payConfirmVerifyTime;
	}
	
	/**
	 * Returns time when payment was confirmed
	 *
	 * @return int
	 */
	public function getPayConfirmTime() {
		return $this->_payConfirmTime;
	}
	
	/**
	 * Returns time when payment was completed
	 *
	 * @return int
	 */
	public function getPayCompleteTime() {
		return $this->_payCompleteTime;
	}

	/**
	 * 
	 * 
	 * Class Methods
	 * 
	 *
	 */
	
	/**
	 * Sets options of Saferpay_Instance. Does not reset object before, though.
	 * 
	 * @param  array|Zend_Config $options
	 * @return L8M_Saferpay
	 */
	public function setOptions($options = NULL) 
	{
	    if ($options instanceof Zend_Config) {
	        $options = $options->toArray();
	    }
	    if ($options!=NULL &&
	        !is_array($options)) {
            throw new L8M_Saferpay_Exception('Options passed need to be either NULL, an array or a Zend_Config instance.');
        }
        if (count($options)>0) {
            foreach ($options as $optionName=>$optionValue) {
                $optionSetter = 'set' . ucfirst($optionName);
                if (method_exists($this, $optionSetter)) {
                    $this->{$optionSetter}($optionValue);
                }
            }
        }
	    return $this;
	}	
	
	/**
	 * Adds error message
	 *
	 * @param  string      $error
	 * @return L8M_Saferpay
	 */
	public function addError($error = NULL)
	{
	    $this->_errors[] = $error;
		return $this;
	}

	/**
	 * Returns last error message
	 *
	 * @return string
	 */
	public function getError()
	{
	    if (count($this->_errors)>0) {
	        return $this->_errors[count($this->_errors)-1];
	    }
	    return NULL;	        
	}
	
	/**
	 * Returns error messages
	 *
	 * @return array
	 */
	public function getErrors()
	{
	    return $this->_errors;
	}
	
	/**
	 * Enables or disables test mode
	 *
	 * @param  bool        $enable
	 * @return L8M_Saferpay
	 */
	public function enableTestMode($enable = TRUE)
	{
	    $enable = (bool) $enable;
	    if ($enable === $this->isTestMode()) {
	        return $this;
	    }
	    $this->_testModeEnabled = $enable;
	    if ($enable == TRUE) {
	        $this->_preTestModeAccountId = $this->getAccountId();
	        $this->setAccountId(self::TEST_ACCOUNTID);
		} else {
		    $this->setAccountId($this->_preTestModeAccountId);
		    $this->_preTestModeAccountId = NULL;
		}
		return $this;
	}
	
	/**
	 * Disables testMode
	 * 
	 * @return L8M_Saferpay
	 */
	public function disableTestMode()
	{
	    return $this->enableTestMode(FALSE);
	}
	
	/**
	 * Returns TRUE if testMode is enabled
	 *
	 * @return bool
	 */
	public function isTestMode()
	{
		return (bool) $this->_testModeEnabled;
	}
	
	/**
	 * Translates internal to external params with provided mapping, which
	 * is an array externalName=>internalName
	 *
	 * @param  array $params
	 * @return array
	 */
	protected function _mapParams($params = NULL)
	{
	    /**
	     * @todo optimize, as we only want to pass in an array of external param 
	     *       names, intersect it with a param mapping (to be defined) and 
	     *       then iterate over params and return the resulting array
	     */
		if (is_array($params) && 
		    count($params)>0) {
			$mapped = array();
			foreach ($params as $externalName=>$internalName) {
			    $getterFunction = 'get' . ucfirst($internalName);
			    if (method_exists($this, $getterFunction)) {
			        $value = $this->{$getterFunction}();
			    } else {
			        $value = $this->{'_' . $internalName};
			    }
			    if ($value!=NULL) {
			        $mapped[$externalName] = $value;
				}
			}
			if (count($mapped)>0) {
			    return $mapped;
			}
		}
		return NULL;
	}
	
	/**
	 * Parses XML data as returned by SaferPay and then updates appropriate
	 * properties of this instance
	 *
	 * @todo   magic_quotes_gpc needs to be turned off as otherwise the slashes
	 *         added will trigger an error in parsing attempts
	 * @return bool
	 */
	public function parseData()
	{
	    /**
	     * @todo optimize as we want to define the actual mapping in one place only
	     */
		$parseableDataParams = array('ID'=>'saferpayId',
							 	     'TOKEN'=>'token',
								     'ACCOUNTID'=>'accountId',
								     'AMOUNT'=>'amount',
								     'CURRENCY'=>'currency',
								     'PROVIDERID'=>'providerId',
								     'PROVIDERNAME'=>'providerName',
								     'ORDERID'=>'orderId',
								     'PAYMENTAPPLICATION'=>'paymentApplication',
								     'ECI'=>'electronicCommerceIndicator',
								     'RECURRING'=>'recurring',
								     'RECFREQ'=>'recurringFrequency',
								     'RECEXP'=>'recurringExpires',
								     'INSTALLMENT'=>'installment',
								     'INSTCOUNT'=>'installmentCount',
								     'REFID'=>'referenceId',
								     'REFOID'=>'referenceOrderId',
								     'ACTION'=>'action');
		/**
		 * data?
		 */
		if ($this->_data!=NULL) {
		    
		    /**
		     * xmlParser
		     */
		    $xmlParser = L8M_Saferpay_Xml_Parser::factory();
		    
		    /**
		     * dataParsed
		     * 
		     * @todo verify functionality
		     */
		    $dataParsed = $xmlParser->parse($this->_data, $parseableDataParams);
		    if (is_array($dataParsed) &&
		        count($dataParsed)>0) {
                $this->setOptions($dataParsed);
                return TRUE;		            
            }
		}
		return FALSE;
	}

	/**
	 * 
	 * 
	 * 
	 * Payment Initialization
	 * 
	 * 
	 * 
	 */
	
	/**
	 * Returns an array of params needed for generation of Saferpay PayInit Url 
	 * 
	 * @return array
	 */
	protected function _getPayInitParams()
	{
		/**
	     * @todo optimize as we want to define the actual mapping in one place only
	     */
		$mapping = array('ACCOUNTID'=>'accountId',
				 	     'AMOUNT'=>'amount',
				 	     'CURRENCY'=>'currency',
				 	     'DESCRIPTION'=>'description',
					     'ORDERID'=>'orderId',		

					     'SUCCESSLINK'=>'successLink',
				 	     'FAILLINK'=>'failLink',
					     'BACKLINK'=>'backLink',
				 	     'AUTOCLOSE'=>'autoClose',
				 	     'NOTIFYURL'=>'notifyUrl',

					     'ALLOWCOLLECT'=>'allowCollect',
					     'DELIVERY'=>'delivery',
				 	     'CCCVC'=>'ccCVC',
				 	     'CCNAME'=>'ccName',
					     'NOTIFYADDRESS'=>'notifyAddress',
					     'USERNOTIFY'=>'userNotify',
				 	     'LANGID'=>'langId',

					     'RECURRING'=>'recurring',
					     'RECFREQ'=>'recurringFrequency',
					     'RECEXP'=>'recurringExpires',

					     'INSTALLMENT'=>'installment',
					     'INSTCOUNT'=>'installmentCount',

					     'REFID'=>'referenceId',
					     'REFOID'=>'referenceOrderId',

					     'DURATION'=>'duration',
					     'PROVIDERSET'=>'providerSet',

				 	     'BODYCOLOR'=>'bodyColor',
				 	     'HEADCOLOR'=>'headColor',
				 	     'HEADLINECOLOR'=>'headlineColor',
				 	     'MENUCOLOR'=>'menuColor',
				 	     'BODYFONTCOLOR'=>'bodyFontColor',
				 	     'HEADFONTCOLOR'=>'headFontColor',
				 	     'MENUFONTCOLOR'=>'menuFontColor',
					     'LINKCOLOR'=>'linkColor',						
				 	     'SHOWLANGUAGES'=>'showLanguages',
				 	     'FONT'=>'font');
		return $this->_mapParams($mapping);
	}
	
	
	/**
	 * Creates a Saferpay CreatePayInit Url and attempts to retrieve a Saferpay 
	 * PayInit Url from it
	 * 
	 * @return string
	 */
	protected function _getPayInitLink()
	{
	    /**
	     * params
	     */
	    $params = $this->_getPayInitParams();
	    if (is_array($params) &&
	        count($params)>0) {
            /**
             * createPayInitLink
             */	            
			$createPayInitLink = new L8M_Saferpay_Link();
			$createPayInitLink->setProtocol('https')
							  ->setBase(self::URL_CREATE_PAY_INIT)
							  ->setParams($params);
			/**
			 * payInitLink
			 */
			$payInitLink = file_get_contents($createPayInitLink->getLink());
			if ($payInitLink !== FALSE) {
				if (substr($payInitLink, 0, 6) == 'ERROR:') {
				    $this->addError(substr($payInitLink, 6));
				} else {
				    return trim($payInitLink);
				}
			}
		}
		return NULL;			
	}
	
	/**
	 * 
	 * 
	 * 
	 * Payment Confirmation
	 * 
	 * 
	 * 
	 */
	
	/**
	 * Returns an array of params needed for generation of Saferpay 
	 * VerifyPayConfirm Url 
	 *
	 * @return array
	 */
	protected function _getVerifyPayConfirmParams()
	{
	    /**
	     * @todo optimize as we want to define the actual mapping in one place only
	     */
	    $mapping = array('DATA'=>'data',
	    				 'SIGNATURE'=>'signature');
		return $this->_mapParams($mapping);
	}

	/**
	 * Returns Saferpay VerifyPayConfirm Url
	 *
	 * @return string
	 */
	protected function _getVerifyPayConfirmLink()
	{
	    /**
	     * params
	     */
		$params = $this->_getVerifyPayConfirmParams();
		if (is_array($params) && 
		    count($params)>0) {
	        /**
	         * verifyPayConfirmLink
	         */
			$verifyPayConfirmLink = new L8M_Saferpay_Link();
			$verifyPayConfirmLink->setProtocol('https')
			                     ->setBase(self::URL_VERIFY_PAY_CONFIRM)
			                     ->setParams($params);
            return $verifyPayConfirmLink->getLink();			                     
		}
		return NULL;
	}
	
	/**
	 * Verfies payment confirmation
	 * 
	 * @return bool
	 */
	public function executeVerifyPayConfirm()
	{
	    /**
	     * verifyPayConfirmLink
	     */
		$verifyPayConfirmLink = $this->_getVerifyPayConfirmLink();
		if ($verifyPayConfirmLink!=NULL) {
			/**
			 * verifyPayConfirm
			 * 
			 * @todo optimize (curl?, Zend_Http_Client?)
			 */
			$verifyPayConfirm = file_get_contents($verifyPayConfirmLink);
			if ($verifyPayConfirm !== FALSE) {
				if (substr($verifyPayConfirm, 0, 6) == 'ERROR:') {
				    $this->addError(substr($verifyPayConfirm, 6));
				} else
				if (substr($verifyPayConfirm, 0, 3) == 'OK:') {
				    $verifyPayConfirm = substr($verifyPayConfirm, 3);
				    $verifyPayConfirm = explode('&', $verifyPayConfirm);
					$verifyPayConfirmResult = array();
					foreach($verifyPayConfirm as $key=>$value) {
						$value = explode('=', $value);
						$verifyPayConfirmResult[$value[0]] = $value[1];
					}
					/**
					 * @todo optimize, map, then use setOptions
					 */
					$this->setSaferPayId($verifyPayConfirmResult['ID']);
					$this->setToken($verifyPayConfirmResult['TOKEN']);
					$this->_setPayConfirmVerifyTime(time());
					return TRUE;
				}
			}
		}
		return FALSE;
	}
	
	/**
	 * Returns whether Saferpay PayConfpayment confirmation has been verified
	 *
	 * @return bool
	 */
	public function isPayConfirmVerifyExecuted()
	{
		return $this->_payConfirmVerifyTime !== NULL;
	}

	/**
	 * 
	 * 
	 * 
	 * Payment Completion
	 * 
	 * 
	 * 
	 */
	
	/**
	 * Returns Saferpay PayComplete Url params
	 *
	 * @return array
	 */
	protected function _getPayCompleteParams()
	{
		$mapping = array('ACCOUNTID'=>'accountId',
						 'ID'=>'saferpayId');
		return $this->_mapParams($mapping);
	}
	
	/**
	 * Returns Saferpay PayComplete Url
	 *
	 * @return string
	 */
	protected function _getPayCompleteLink()
	{
		$params = $this->_getPayCompleteParams();
		if (is_array($params) && 
		    count($params)>0) {
			/**
			 * payCompleteLink
			 */
			$payCompleteLink = new L8M_Saferpay_Link();
			$payCompleteLink->setProtocol('https')
			                ->setBase(self::URL_PAY_COMPLETE)
			                ->setParams($params);
            /**
             * testMode?
             */			                
			if ($this->isTestMode()) {
				$payCompleteLink->addParam('spPassword', self::TEST_PASSWORD);
			}
			return $payCompleteLink->getLink();
		}
		return NULL;			
	}

	
	/**
	 * Complete payment
	 *
	 * @return bool
	 */
	public function executePayComplete()
	{
		/**
		 * payCompleteLink
		 */
		$payCompleteLink = $this->_getPayCompleteLink();
		if ($payCompleteLink !== NULL) {
		    /**
		     * payComplete
		     * 
			 * @todo optimize (curl?, Zend_Http_Client?)
		     */
			$payComplete = file_get_contents($payCompleteLink);
			if ($payComplete !== FALSE) {
				if (substr($payComplete, 0, 6) == 'ERROR:') {
					$this->addError(substr($payComplete, 6));
				} else
				/**
				 * @todo optimize, equality?, check manual 
				 */
				if (substr($payComplete, 0, 2) == 'OK') {
				    $this->_setPayCompleteTime(time());
				    return TRUE;
				}
			}
		}
		return FALSE;
	}
	
	/**
	 * Returns whether payment has been completed
	 *
	 * @return bool
	 */
	public function isPayCompleteExecuted()
	{
		return $this->_payCompleteTime !== NULL;			
	}
	
	/**
	 * 
	 * 
	 */
	public function validatesAgainstParams($params = NULL) {
		
		$validatedAgainstParams = FALSE;
		
		$paramsToBeValidated = array('accountID'=>FALSE,	
									 'amount'=>FALSE,
									 'currency'=>FALSE,
									 'orderID'=>FALSE);
					
		if ($params !== NULL && 
			is_array($params) && 
			count($params)>0) {
			$params = array_intersect_key($params,$paramsToBeValidated);
			if (count($params) == count($paramsToBeValidated)) {
				$validatedAgainstParams = TRUE;
				foreach($paramsToBeValidated as $paramKey=>$value) {
					if ($validatedAgainstParams === TRUE) {
						$validatedAgainstParams = $this->{$paramKey} == $params[$paramKey];
					}
				}					
			}
		}
		
		return $validatedAgainstParams;
		
	}
	
	/**
	 * 
	 * 
	 * View Methods
	 * 
	 * 
	 */
	
	/**
	 * Returns Saferpay Virtual Terminal URL
	 *
	 * @return string
	 */
	public function getVirtualTerminalUrl() 
	{
	    return $this->_getPayInitLink();
	}

	/**
	 * Returns Saferpay Virtual Terminal as an IFRAME or an error message if 
	 * Saferpay Virtual Terminal URL could not be retrieved from Saferpay
	 *
	 * @param  string $source
	 * @return string
	 */
	public function getVirtualTerminal($source = NULL)
	{
        $id = 'saferpayIFrame';
        if (!L8M_Library::isLink($source)) {
            $source = $this->getVirtualTerminalUrl();
        }
        if (L8M_Library::isLink($source)) {
            ob_start();
        
?>
<!-- saferpayVirtualTerminal start -->
<iframe id="<?php echo $id; ?>" src="<?php echo $source;?>"></iframe>
<!-- saferpayVirtualTerminal end -->
<?php
            return ob_get_clean();
        } else {
            return $this->getError();
        }            
    }	    
	
}