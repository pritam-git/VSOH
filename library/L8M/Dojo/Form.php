<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Dojo/Form.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Form.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Dojo_Form
 *
 *
 */
class L8M_Dojo_Form extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Methods
	 *
	 *
	 */

    /**
     * Initializes L8M_Dojo_Form instance
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        Zend_Dojo::enableForm($this);
    }

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Adds client side form validation so you won't have to take care of it in
	 * the view, and renders form
	 *
	 * @param  Zend_View_Interface $view
	 * @return string
	 */
    public function render(Zend_View_Interface $view = null)
    {

		/**
         * start capturing head script
		 */
		$this->_view->headScript()->captureStart();

?>

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

dojo.addOnLoad(function () {

 ////////////////////////////////////////////////////////////
 // formValidation
 ////////////////////////////////////////////////////////////

    dojo.connect(dijit.byId("<?php echo $this->getId(); ?>"), "onSubmit", function(event) {
    	return dijit.byId("<?php echo $this->getId(); ?>").validate();
    });
});

<?php

        /**
         * end capturing head script
         */
        $this->_view->headScript()->captureEnd();

		/**
		 * set dojoEnabled flag
		 */
		Zend_Registry::set('dojoEnabled',TRUE);

		return parent::render($view);
	}

}