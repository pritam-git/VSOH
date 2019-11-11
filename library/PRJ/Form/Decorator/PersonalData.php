<?php

/**
 * L8M
 *
 * @filesource /library/PRJ/Form/Decorator/PersonalData.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: PersonalData.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_Form_Decorator_PersonalData
 *
 *
 */
class PRJ_Form_Decorator_PersonalData extends Zend_Form_Decorator_Abstract
{

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

	<h3 class="no-top"><?php echo $viewFromMvc->translate('Ständige Informationen', 'de'); ?></h3>
	<p class="bold"><?php echo $entity->firstname . ' ' . $entity->lastname; ?></p>
	<p class="bold"><?php echo $entity->email; ?></p>
	<h3><?php echo $viewFromMvc->translate('Persönliche Informationen ändern', 'de'); ?></h3>


<?php

		return ob_get_clean() . $content . '</div>';
	}

}