<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/Datetime.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Datetime.php 436 2015-09-29 09:02:07Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_Datetime
 *
 *
 */
class L8M_JQuery_Form_Element_Datetime extends Zend_Form_Element_Xhtml
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
		'showSecond'=>TRUE,
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
	 * @return L8M_JQuery_Form_Element_Datetime
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
				->addDecorator('Datetime')
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
		 * render head script
		 */
		if (!self::$_dateInitialized) {
			$httpHostScheme = 'http';
			if (L8M_Library::isHttpHostSecure()) {
				$httpHostScheme = 'https';
			}
			$viewFromMvC = Zend_Layout::getMvcInstance()->getView();
			$viewFromMvC->headScript()->appendFile($httpHostScheme . '://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/i18n/jquery-ui-i18n.min.js', 'text/javascript');
			$viewFromMvC->headScript()->appendFile('/js/jquery/plugins/timepicker/jquery-ui-timepicker-addon.js', 'text/javascript');
			$viewFromMvC->headLink()->appendStylesheet('/js/jquery/plugins/timepicker/jquery-ui-timepicker-addon.css', 'all');

			$viewFromMvC->headScript()->captureStart();
?>
	$(document).ready(function() {
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

			/**
			 * language
			 */
			$language = Zend_Registry::isRegistered('Zend_Locale')
					  ? Zend_Registry::get('Zend_Locale')->getLanguage()
					  : NULL
			;
			foreach(self::$_dateEditorIds as $dateEditorId) {

?>
		$( "#<?php echo $dateEditorId; ?>" ).datetimepicker({
			beforeShowDay: nonWorkingDates,
			dateFormat: 'yy-mm-dd',
			timeFormat: 'HH:mm:ss',
			timeText: '<?php echo $viewFromMvC->translate('Time'); ?>',
			hourText: '<?php echo $viewFromMvC->translate('Hour'); ?>',
			minuteText: '<?php echo $viewFromMvC->translate('Minute'); ?>',
			secondText: '<?php echo $viewFromMvC->translate('Second'); ?>',
			currentText: '<?php echo $viewFromMvC->translate('Now'); ?>',
			closeText: '<?php echo $viewFromMvC->translate('Done'); ?>'<?php echo (count($this->_datePickerOptions) > 0) ? ',' : ''; ?>

			prevText: '&#x3C;<?php echo $viewFromMvC->translate('Prev'); ?>',
			nextText: '<?php echo $viewFromMvC->translate('Next'); ?>&#x3E;',
			monthNames: ['<?php echo $viewFromMvC->translate('January'); ?>', '<?php echo $viewFromMvC->translate('February'); ?>', '<?php echo $viewFromMvC->translate('March'); ?>', '<?php echo $viewFromMvC->translate('April'); ?>', '<?php echo $viewFromMvC->translate('May'); ?>', '<?php echo $viewFromMvC->translate('June'); ?>', '<?php echo $viewFromMvC->translate('July'); ?>', '<?php echo $viewFromMvC->translate('August'); ?>', '<?php echo $viewFromMvC->translate('September'); ?>', '<?php echo $viewFromMvC->translate('October'); ?>', '<?php echo $viewFromMvC->translate('November'); ?>', '<?php echo $viewFromMvC->translate('December'); ?>'],
			monthNamesShort: ['<?php echo $viewFromMvC->translate('Jan'); ?>', '<?php echo $viewFromMvC->translate('Feb'); ?>', '<?php echo $viewFromMvC->translate('Mar'); ?>', '<?php echo $viewFromMvC->translate('Apr'); ?>', '<?php echo $viewFromMvC->translate('May'); ?>', '<?php echo $viewFromMvC->translate('Jun'); ?>', '<?php echo $viewFromMvC->translate('Jul'); ?>', '<?php echo $viewFromMvC->translate('Aug'); ?>', '<?php echo $viewFromMvC->translate('Sep'); ?>', '<?php echo $viewFromMvC->translate('Oct'); ?>', '<?php echo $viewFromMvC->translate('Nov'); ?>', '<?php echo $viewFromMvC->translate('Dec'); ?>'],
			dayNames: ['<?php echo $viewFromMvC->translate('Sunday'); ?>', '<?php echo $viewFromMvC->translate('Monday'); ?>', '<?php echo $viewFromMvC->translate('Tuesday'); ?>', '<?php echo $viewFromMvC->translate('Wednesday'); ?>', '<?php echo $viewFromMvC->translate('Thursday'); ?>', '<?php echo $viewFromMvC->translate('Friday'); ?>', '<?php echo $viewFromMvC->translate('Saturday'); ?>'],
			dayNamesShort: ['<?php echo $viewFromMvC->translate('Sun'); ?>', '<?php echo $viewFromMvC->translate('Mon'); ?>', '<?php echo $viewFromMvC->translate('Tue'); ?>', '<?php echo $viewFromMvC->translate('Wed'); ?>', '<?php echo $viewFromMvC->translate('Th'); ?>', '<?php echo $viewFromMvC->translate('Fr'); ?>', '<?php echo $viewFromMvC->translate('Sa'); ?>'],
			dayNamesMin: ['<?php echo $viewFromMvC->translate('Su'); ?>', '<?php echo $viewFromMvC->translate('Mo'); ?>', '<?php echo $viewFromMvC->translate('Tu'); ?>', '<?php echo $viewFromMvC->translate('We'); ?>', '<?php echo $viewFromMvC->translate('Th'); ?>', '<?php echo $viewFromMvC->translate('Fr'); ?>', '<?php echo $viewFromMvC->translate('Sa'); ?>'],
			isRTL: false,
			yearSuffix: '',

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
<?php

		}

?>
	});
<?php

			$viewFromMvC->headScript()->captureEnd();
			self::$_dateInitialized = TRUE;
		}

		$content = parent::render($view);

		return $content;
	}
}