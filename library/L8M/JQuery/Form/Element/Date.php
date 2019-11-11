<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/Date.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Date.php 436 2015-09-29 09:02:07Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_Date
 *
 *
 */
class L8M_JQuery_Form_Element_Date extends Zend_Form_Element_Xhtml
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	/**
	 *
	 * @var array
	 */
	protected static $_dateEditorIds = array();

	/**
	 * Contains TRUE when head script has been added to initialize all tiny mce
	 * editors on page.
	 *
	 * @var bool
	 */
	protected static $_dateInitialized = FALSE;

	/**
	 * datePickerOptions
	 */
	private $_datePickerOptions = array(
		'changeMonth'=>TRUE,
		'changeYear'=>TRUE,
//		'yearRange'=>'-10:+2',
	);

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */
	/**
	 * Set element name
	 *
	 * @param  string $name
	 * @return L8M_JQuery_Form_Element_Date
	 */
	public function setName($name)
	{
		unset(self::$_dateEditorIds[$this->getName()]);
		parent::setName($name);
		self::$_dateEditorIds[$name] = $name;
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
	 * Retrieve filtered element value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	public function __construct($spec, $datePickerOptions = NULL, $options = NULL)
	{

		/**
		 * set decorator path
		 */
		$this->addPrefixPath(
			'L8M_JQuery_Form_Decorator',
			'L8M'. DIRECTORY_SEPARATOR . 'JQuery'. DIRECTORY_SEPARATOR . 'Form'. DIRECTORY_SEPARATOR . 'Decorator',
			'decorator'
		);

		if (is_array($datePickerOptions)) {
			$this->_datePickerOptions = array_merge($this->_datePickerOptions, $datePickerOptions);
		}

		self::$_dateEditorIds[$spec] = $spec;

		/**
		 * parent constructor
		 */
		parent::__construct($spec, $options);
	}

	public function loadDefaultDecorators()
	{
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return;
		}

		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this
				->addDecorator('Date')
				->addDecorator('Errors')
				->addDecorator('Description', array(
					'tag'   => 'p',
					'class' => 'description'
					))
				->addDecorator('HtmlTag', array(
					'tag' => 'dd',
					'id'  => $this->getName() . '-element'
					))
				->addDecorator('Label', array('tag' => 'dt'))
			;
		}
	}

/**
	 * Render form element
	 *
	 * @param  Zend_View_Interface $view
	 * @return string
	 */
	public function render(Zend_View_Interface $view = null)
	{

		/**
		 * html for form element
		 */
		ob_start();

		/**
		 * render head script
		 */
		if (!self::$_dateInitialized) {

			/**
			 * language
			 */
			$language = Zend_Registry::isRegistered('Zend_Locale')
					  ? Zend_Registry::get('Zend_Locale')->getLanguage()
					  : NULL
			;

			$httpHostScheme = 'http';
			if (L8M_Library::isHttpHostSecure()) {
				$httpHostScheme = 'https';
			}

?>
<script src="<?php echo $httpHostScheme; ?>://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/i18n/jquery-ui-i18n.min.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {
		$.datepicker.setDefaults( $.datepicker.regional[ "" ] );
		$.datepicker.setDefaults( $.datepicker.regional[ "<?php echo $language; ?>" ] );
	});
<?php

			$notSelectableDaysArray = array();
			$notSelectableDayString = NULL;
			if (L8M_Config::getOption('L8M_JQuery_Form_Element_Date.notSelectableDay')) {
				foreach (L8M_Config::getOption('L8M_JQuery_Form_Element_Date.notSelectableDay') as $notSelectableDay) {
					$notSelectableDaysArray[] = '[' . $notSelectableDay . ']';
				}
				$notSelectableDayString = implode(', ' ,$notSelectableDaysArray);
			}

			$notSelectableDatesArray = array();
			$notSelectableDateString = NULL;
			if (L8M_Config::getOption('L8M_JQuery_Form_Element_Date.notSelectableDate')) {
				foreach (L8M_Config::getOption('L8M_JQuery_Form_Element_Date.notSelectableDate') as $notSelectableDate) {
					$notSelectableDate = str_replace('yyyy', date('Y'), $notSelectableDate);
					$notSelectableDate = str_replace('mm', date('m'), $notSelectableDate);
					$notSelectableDate = str_replace('dd', date('d'), $notSelectableDate);

					$notSelectableDatesArray[] = '[' . $notSelectableDate . ']';
				}
				$notSelectableDateString = implode(', ' ,$notSelectableDatesArray);
			}

?>
	function nonWorkingDates(date){
		var day = date.getDay(), Sunday = 0, Monday = 1, Tuesday = 2, Wednesday = 3, Thursday = 4, Friday = 5, Saturday = 6;
		var closedDates = [<?php echo $notSelectableDateString; ?>];
		var closedDays = [<?php echo $notSelectableDayString; ?>];
		for (var i = 0; i < closedDays.length; i++) {
			if (day == closedDays[i][0]) {
				return [false];
			}

		}

		for (i = 0; i < closedDates.length; i++) {
			if (date.getMonth() == closedDates[i][0] - 1 &&
			date.getDate() == closedDates[i][1] &&
			date.getFullYear() == closedDates[i][2]) {
				return [false];
			}
		}
<?php

			if (L8M_Config::getOption('L8M_JQuery_Form_Element_Date.notSelectableBeforeToday')) {

?>
		var today = new Date();
		today.setDate(today.getDate()-1);
		if (date <= today) {
			return [false];
		}
<?php

			}

?>
		return [true];
	}
//]]>
</script>
<?php

			self::$_dateInitialized = TRUE;
		}

?>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {
<?php
		foreach (self::$_dateEditorIds as $dateEditorId) {

?>
		$( "#<?php echo $dateEditorId; ?>" ).datepicker({
			beforeShowDay: nonWorkingDates,
			dateFormat: 'yy-mm-dd'<?php echo (count($this->_datePickerOptions) > 0) ? ',' : ''; ?>

<?php

			$datePickerOutputOption = array();
			foreach ($this->_datePickerOptions as $datePickerOptionKey => $datePickerOptionValue) {
				$tmp = $datePickerOptionKey . ':';
				if (is_bool($datePickerOptionValue)) {
					if ($datePickerOptionValue) {
						$tmp .= 'true';
					} else {
						$tmp .= 'false';
					}
				} else
				if (is_numeric($datePickerOptionValue)) {
						$tmp .= $datePickerOptionValue;
				} else {
					$tmp .= '\'' . $datePickerOptionValue . '\'';
				}
				$datePickerOutputOption[] = $tmp;
			}
			echo implode(',' . PHP_EOL, $datePickerOutputOption);

?>
		});
<?php

		}

?>
	});
//]]>
</script>
<?php


		$content = ob_get_clean()
				 . parent::render($view)
		;

		return $content;
	}
}