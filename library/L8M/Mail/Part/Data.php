<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mail/Part/Data.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Data.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Mail_Part_Data
 *
 *
 */
class L8M_Mail_Part_Data extends L8M_Mail_Part
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */


	/**
	 * An array with data.
	 *
	 * @var array
	 */
	protected $_data = NULL;

	private $_enableMaxLableLength = TRUE;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Sets data of this mail part.
	 *
	 * @param  array $data
	 * @return L8M_Mail_Part_Data
	 */
	public function setData($data = NULL)
	{
		if ($data &&
			!is_array($data)) {
			throw new L8M_Mail_Part_Data_Exception('Data needs to be passed as an array.');
		}
		$this->_data = $data;
		return $this;
	}

	/**
	 * Disable maximize lable length
	 *
	 * @return L8M_Mail_Part_Data
	 */
	public function disableMaximizeLableLength()
	{
		$this->_enableMaxLableLength = FALSE;
		return $this;
	}

	/**
	 * Adds data to L8M_Mail_Part_Data instance.
	 *
	 * @param  string $label
	 * @param  string $value
	 * @return L8M_Mail_Part_Data
	 */
	public function addData($label = NULL, $value = NULL)
	{
		if (!$label ||
			!is_string($label)) {
			throw new L8M_Mail_Part_Data_Exception('Label needs to be specified as string.');
		}

		$value = (string) $value;

		if (trim($value) == '') {
			return $this;
		}

		$this->_data[] = array(
			'label'=>$label,
			'data'=>$value,
		);

		return $this;

	}

	/**
	 * Renders content of this mail part.
	 *
	 * @param  string $mode
	 * @return string
	 */
	protected function _renderContent($mode = self::RENDER_TEXT)
	{
		$renderedContent = parent::_renderContent($mode)
						 . $this->_renderData($this->_data, $mode)
		;
		return $renderedContent;
	}

	/**
	 * Renders form data.
	 *
	 * @param  $form
	 * @param  $formFields
	 * @param  $mode
	 * @return string
	 */
	protected function _renderData($data = NULL, $mode = self::RENDER_TEXT)
	{
		if ($data &&
			!is_array($data)) {
			throw new L8M_Mail_Part_Data_Exception('Data needs to be passed as an array.');
		}
		if (count($data)>0) {

			ob_start();

			if ($mode == self::RENDER_TEXT) {

				$maxLabelLength = 0;
				if ($this->_enableMaxLableLength) {
					foreach($data as $item) {
						$maxLabelLength = max($maxLabelLength, strlen($item['label']));
					}
				}
				$indent = str_pad('', 2, ' ');
				foreach($data as $item) {

					/**
					 * actual item
					 */
					if (array_key_exists('label', $item) &&
						array_key_exists('data', $item) &&
						trim($item['data']) != '') {

						$content = $indent
								 . str_pad($item['label'], $maxLabelLength, ' ', STR_PAD_RIGHT)
								 . ' : '
								 . $item['data']
								 . PHP_EOL
						;
						echo $content;

					}


				}
			} else

			if ($mode == self::RENDER_HTML) {
?>
<ul style="margin:0px; padding:0px">
<?php

				foreach($data as $item) {
					if (trim($item['data']) != '') {
?>
<li style="list-style-type:none; margin:0px; padding:0px; margin-left:150px; color:#005AAA; font-family:Arial, Helvetica, sans-serif; font-size:12px;"><span style="position:absolute; margin-left:-150px; color:#FA7814; font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?php echo $this->escape($item['label']); ?></span><?php echo $this->escape($item['data']); ?></li>
<?php
					}
				}

?>
</ul>
<?php


			}

			return ob_get_clean();
		}

	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */


	/**
	 * Returns an L8M_Mail_Part_Data instance from the provided form.
	 *
	 * @param  Zend_Form $form
	 * @param  array	 $elements
	 * @return L8M_Mail_Part_Data
	 */
	public static function fromForm($form = NULL, $options = array(), $isUtf8 = TRUE, $formMatchingValues = NULL)
	{
		if (!($form instanceof Zend_Form)) {
			throw new L8M_Mail_Part_Data_Exception('Form needs to be specified as Zend_Form instance.');
		}

		/**
		 * retrieve view form mvc
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		/**
		 * retrieve form elements
		 */
		$formElements = $form->getElements();

		if (isset($options['include']) &&
			is_array($options['include'])) {

			$formElements = array_intersect_key(
				array_flip($options['include']),
				$formElements
			);
		} else

		if (isset($options['exclude']) &&
			is_array($options['exclude'])) {

			$formElements = array_diff_key(
				$formElements,
				array_flip($options['exclude'])
			);
		}

		$partData = new L8M_Mail_Part_Data();

		if (is_array($formElements) &&
			count($formElements) > 0) {

			foreach ($formElements as $key=>$element) {
				if (!($element instanceof Zend_Form_Element_Hash) &&
					!($element instanceof Zend_Form_Element_Hidden) &&
					!($element instanceof Zend_Form_Element_Submit)) {
					/* @var $element Zend_Form_Element */

					if (trim($element->getLabel() != '')) {

						/**
						 * prepare data label
						 */
						if (isset($options['replaceLabel']) &&
							is_array($options['replaceLabel']) &&
							array_key_exists($element->getName(), $options['replaceLabel'])) {

							$label = $options['replaceLabel'][$element->getName()];
						} else {
							if (isset($options['disableLableTranslation']) &&
								$options['disableLableTranslation'] === TRUE) {

								$label = strip_tags($element->getLabel());
							} else {
								$label = $viewFromMVC->translate(strip_tags($element->getLabel()));
							}
						}

						/**
						 * do we have form matching values to use
						 */
						$formValue = $element->getValue();
						if ($formMatchingValues !== NULL &&
							array_key_exists($element->getName(), $formMatchingValues)) {

							$formValue = $formMatchingValues[$element->getName()];
						}

						/**
						 * prepare data value
						 */
						/**
						 * do we have a checkbox?
						 */
						if ($element instanceof Zend_Form_Element_Checkbox) {

							/**
							 * check value
							 */
							if ($element->getCheckedValue() == $formValue) {
								$value = $viewFromMVC->translate('Yes');
							} else {
								$value = $viewFromMVC->translate('No');
							}
						} else

						/**
						 * do we have options?
						 */
						if ($element instanceof Zend_Form_Element_Select) {
							$value = $element->getMultiOption(strip_tags($formValue));
						} else {
							/**
							 * retrieve the value the easy way
							 */
							$value = strip_tags($formValue);
						}

						/**
						 * decode
						 */
						if (!$isUtf8) {
							$label = utf8_decode($label);
							$value = utf8_decode($value);
						}

						if (isset($options['extraEOL']) &&
							is_array($options['extraEOL']) &&
							in_array($element->getName(), $options['extraEOL'])) {

							$value .= PHP_EOL;
						}

						/**
						 * add data
						 */
						$partData->addData(
							$label,
							$value
						);
					}
				}
			}

		}

		/**
		 * @todo subforms!
		 */

		return $partData;
	}

}