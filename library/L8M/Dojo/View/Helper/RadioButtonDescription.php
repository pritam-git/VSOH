<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Dojo/View/Helper/RadioButtonDescription.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: RadioButtonDescription.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Dojo_View_Helper_RadioButtonDescription
 *
 *
 */
class L8M_Dojo_View_Helper_RadioButtonDescription extends Zend_Dojo_View_Helper_Dijit
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

    /**
     * Dijit being used
     * @var string
     */
    protected $_dijit  = 'dijit.form.RadioButton';

    /**
     * Dojo module to use
     * @var string
     */
    protected $_module = 'dijit.form.CheckBox';

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * dijit.form.RadioButton
     *
     * @param  string $id
     * @param  string $value
     * @param  array $params  Parameters to use for dijit creation
     * @param  array $attribs HTML attributes
     * @param  array $options Array of radio options
     * @param  string $listsep String with which to separate options
     * @return string
     */
    public function radioButtonDescription($id, $value = NULL, array $params = array(), array $attribs = array(), array $options = NULL, $listsep = "<br />\n")
    {
        $attribs['name'] = $id;
        if (!array_key_exists('id', $attribs)) {
            $attribs['id'] = $id;
        }
        $attribs = $this->_prepareDijit($attribs, $params, 'element');

        if (is_array($options) &&
        	$this->_useProgrammatic() &&
        	!$this->_useProgrammaticNoScript()) {
            $baseId = $id;
            if (array_key_exists('id', $attribs)) {
                $baseId = $attribs['id'];
            }
            $filter = new Zend_Filter_Alnum();
            foreach (array_keys($options) as $key) {
                $optId = $baseId . '-' . $filter->filter($key);
                $this->_createDijit($this->_dijit, $optId, array());
            }
        }

        // $viewHelper = new L8M_View_Helper_FormRadioDescription();
        // return $viewHelper->formRadioDescription($id, $value, $attribs, $options, $listsep);
        return $this->view->formRadioDescription($id, $value, $attribs, $options, $listsep);
    }
}
