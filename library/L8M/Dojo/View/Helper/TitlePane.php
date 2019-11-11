<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Dojo/View/Helper/TitlePane.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TitlePane.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Dojo_View_Helper_TitlePane
 *
 *
 *
 */
class L8M_Dojo_View_Helper_TitlePane extends Zend_Dojo_View_Helper_DijitContainer
{

    /**
     *
     *
     *
     * Class Variables
     *
     *
     *
     */

    /**
     * Dijit being used
     * @var string
     */
    protected $_dijit  = 'dijit.TitlePane';

    /**
     * Module being used
     * @var string
     */
    protected $_module = 'dijit.TitlePane';

    /**
     *
     *
     *
     * Class Methods
     *
     *
     *
     */

    /**
     * Creates titlePane from the provided params
     *
     * @param  string $id
     * @param  string $content
     * @param  array  $params  Parameters to use for dijit creation
     * @param  array  $attribs HTML attributes
     * @return string
     */
    public function titlePane($id = null, $content = '', array $params = array(), array $attribs = array())
    {
        if (0 === func_num_args()) {
            return $this;
        }

        return $this->_createLayoutContainer($id, $content, $params, $attribs);
    }
}
