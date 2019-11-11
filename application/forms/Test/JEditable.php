<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Test/JEditable.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: JEditable.php 28 2014-04-02 14:50:33Z nm $
 */


/**
 *
 *
 * Default_Form_Test_JEditable
 *
 *
 */
class Default_Form_Test_JEditable extends L8M_FormExpert
{

    /**
     *
     *
     * Initialization Function
     *
     *
     */

    /**
     * Initializes Default_Form_Test_TinyMCE instance.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        /**
         * form
         */
        $this
        	->setMethod(Zend_Form::METHOD_POST)
            ->setAttrib('id', 'formTestJEditable');

        /**
         * formJEditable
         */
        $formJEditable = new L8M_JQuery_Form_Element_jEditable('text');
        $this->addElement($formJEditable);

        $this->setDecorators(array());

    }
}