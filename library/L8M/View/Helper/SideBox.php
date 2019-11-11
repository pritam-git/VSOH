<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/SideBox.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: SideBox.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_SideBox
 *
 *
 */
class L8M_View_Helper_SideBox extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

    /**
     * A constant containing an identifier for positioning sideboxes to the
     * left.
     */
    const POSITION_LEFT = 'left';

    /**
     * A constant containing an identifier for positioning sideboxes to the
     * right.
     */
    const POSITION_RIGHT = 'right';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

    /**
     * An array of sideboxes
     *
     * @var array
     */
    protected $_boxes = array();

    /**
     * A CSS identifier for the sidebox container
     *
     * @var string
     */
    protected $_sideboxContainerId = 'page-sideboxes';

    /**
     * The position of the sidebox bar.
     *
     * @var string
     */
    protected $_position = self::POSITION_LEFT;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Adds a sidebox if parameters are passed or simply returns this instance.
     *
     * @param  string                 $content
     * @param  string                 $class
     * @param  string                 $style
     * @param  int                    $position
     * @return L8M_View_Helper_SideBox
     */
    public function sideBox ($content = NULL, $class = NULL, $style = NULL, $position = NULL)
    {
        if (func_num_args()>0) {
            return $this->addSideBox($content, $class, $style, $position);
        } else {
            return $this;
        }
    }

    /**
     * Adds a side box with the specified parameters.
     *
     * @param  string                 $content
     * @param  string                 $style
     * @param  string                 $style
     * @param  int                    $position
     * @return L8M_View_Helper_SideBox
     */
    public function addSideBox($content = NULL, $class = NULL, $style = NULL, $position = NULL)
    {
        $sidebox = new stdClass();
        $sidebox->content = $content;
        $sidebox->class = $class;
        $sidebox->style = $style;
        if ($position!==NULL &&
            is_int($position)) {
            $position = $position>count($this->_boxes) ? count($this->_boxes) + 1 : $position;
            array_splice($this->_boxes, $position, 0, array($sidebox));
        } else {
            $this->_boxes[] = $sidebox;
        }
        return $this;
    }

    /**
     * Sets position of sidebox bar.
     *
     * @param  string                 $position
     * @return L8M_View_Helper_SideBox
     */
    public function setPosition($position = NULL)
    {
        if (in_array($position, array(self::POSITION_LEFT,
                                      self::POSITION_RIGHT))) {
            $this->_position = $position;
        }
        return $this;
    }

    /**
     * Returns TRUE if sideboxes have been added,
     *
     * @return bool
     */
    public function hasBoxes()
    {
        return count($this->_boxes)>0;
    }

    /**
     * Renders sideboxes.
     *
     * @return string
     */
    public function __toString()
    {
        if (count($this->_boxes)==0) {
            return NULL;
        } else {
            /**
             * make sure last class is set
             */
            if (!preg_match('/last/', $this->_boxes[count($this->_boxes)-1]->class)) {
                $this->_boxes[count($this->_boxes)-1]->class = implode(' ', array_merge(array('last'), explode(' ', $this->_boxes[count($this->_boxes)-1]->class)));
            }
            /**
             * render sideboxes
             */
            $sideboxes = array();
            foreach ($this->_boxes as $sidebox) {
                $sideboxes[] = $this->view->box($sidebox->content, $sidebox->class, $sidebox->style);
            }
            /**
             * wrap in div
             */
            return '<div id="' . $this->_sideboxContainerId. '"' . ($this->_position==self::POSITION_RIGHT ? ' class="right"' : '') . '>' . implode(PHP_EOL, $sideboxes) . '</div>';
        }
    }

}