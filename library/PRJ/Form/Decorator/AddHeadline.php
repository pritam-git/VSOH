<?php

/**
 * L8M
 *
 * @filesource /library/PRJ/Form/Decorator/AddHeadline.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: AddHeadline.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_Form_Decorator_AddHeadline
 *
 *
 */
class PRJ_Form_Decorator_AddHeadline extends Zend_Form_Decorator_Abstract
{

	/**
	 * contains string for backbutton
	 *
	 * @var string
	 */
	private $_headline = NULL;

	/**
	 * Constructor
	 *
	 * @param  string $options
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($headline = NULL)
	{

		if ($headline !== NULL &&
			is_string($headline)) {

			$this->_headline = $headline;
		}

	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Renders ajaxable form.
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{

		ob_start();

		$entity = Zend_Auth::getInstance()->getIdentity();
		$viewFromMvc = Zend_Layout::getMvcInstance()->getView();

		echo '<div class="personal-data">';

?>

	<h3 class="no-top"><?php echo $this->_headline; ?></h3>

<?php

		return ob_get_clean() . $content . '</div>';
	}

}