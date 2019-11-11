<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Test/TinyMCE.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TinyMCE.php 28 2014-04-02 14:50:33Z nm $
 */


/**
 *
 *
 * Default_Form_Test_TinyMCE
 *
 *
 */
class Default_Form_Test_TinyMCE extends L8M_FormExpert
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
        	->setAttrib('id', 'formTestTinyMcd');

        /**
         * formTinyMCE
         */
        $formTinyMCE = new L8M_JQuery_Form_Element_TinyMCE('text');
        $this->addElement($formTinyMCE);

        /**
         * formSubmitButton
         */
        $formSubmitButton = new Zend_Dojo_Form_Element_SubmitButton('tinyMceSubmit');
        $formSubmitButton
        	->setLabel('Save')
            ->setDecorators(array('DijitElement'));
        $this->addElement($formSubmitButton);
    }
}