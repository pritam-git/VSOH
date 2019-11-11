<?php

/**
 * L8M
 *
 * @filesource /library/L8M/Form/Decorator/HasException.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: HasException.php 70 2014-05-12 13:09:13Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_HasException
 *
 *
 */
class L8M_Form_Decorator_HasException extends Zend_Form_Decorator_Abstract
{

	/**
	 * contains exception
	 *
	 * @var Exception|array
	 */
	private $_exception = NULL;

	/**
	 * Constructor
	 *
	 * @param  Exception|array $exception
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($exception = NULL, $options = NULL)
	{

		if ($exception !== NULL) {

			$this->_exception = $exception;
		}

		parent::__construct($options);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Renders backbutton in form.
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{

		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();
		if ($this->_exception &&
			($this->_exception instanceof Exception || is_array($this->_exception)) &&
			$viewFromMVC) {

			$outputArray = array();
			if ($this->_exception instanceof Exception) {
				$outputArray[] = '<li class="exclamation">' . $this->_exception->getMessage() . '</li>';
			} else
			if (is_array($this->_exception)) {

				$outputMessages = array();
				foreach ($this->_exception as $exception) {
					$cssClass = NULL;
					$msg = NULL;
					if ($exception instanceof Exception) {

						$msg = $exception->getMessage();

						if ($exception instanceof L8M_ModelForm_MarkedForEditor_Exception) {
							$cssClass = ' marked-for-editor-error';
						}
					} else
					if (is_string($exception)) {
						$msg = $exception;
					}

					if ($msg &&
						!in_array($msg, $outputMessages)) {

						$outputArray[] = '<li class="exclamation' . $cssClass . '">' . $msg . '</li>';
						$outputMessages[] = $msg;
					}
				}
			}

			if (count($outputArray) > 0) {

				/**
				 * capture start
				 */
				ob_start();
?>
<div class="form-exception">
	<ul class="iconized">
		<?php echo implode(PHP_EOL, $outputArray); ?>
	</ul>
</div>
<?php

				/**
				 * capture end
				 */
				$content = ob_get_clean() . $content;
			}
		}

		return $content;
	}

}