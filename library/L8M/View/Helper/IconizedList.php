<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/IconizedList.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: IconizedList.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_IconizedList
 *
 *
 */
class L8M_View_Helper_IconizedList extends Zend_View_Helper_Abstract
{
    /**
     *
     *
     * Class Constants
     *
     *
     */

    const STYLE_FLOAT = 'float';
    const STYLE_FLOAT_SMALL = 'float small';
    const STYLE_SMALL = 'small';
    const STYLE_INLINE = 'float inline';

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Renders an iconized list.
     *
     * $items
     * ------
     * [x] can be a string - i.e., a list with only one item on it, e.g.
     *
     * 	   'An error has occured'.
     *
     * [x] can be an array (i.e., the values of the array are rendered as list
     *     items, e.g.
     *
     *     array('An error has occured',
     *           'And another error has occured')
     *
     * [x] an array of arrays (i.e., the list items are further defined, e.g.,
     *     with
     *
     *     array(array('class'=>'phone',
     * 				   'label'=>'Phone',
     * 				   'value'=>'(030) 1234567'))
     *
     *
     * $ulClass
     * --------
     * determines the class of the ul element. Can be, for example
     * [x] 'float', L8M_View_Helper_IconizedList::STYLE_FLOAT, which renders
     *     items floating to the left
     * [x] 'float inline', L8M_View_Helper_IconizedList::STYLE_INLINE, which
     *     renders items floating to the left and with reduced line-height to
     *     make it look like an inline list
     *
     * $liClass
     * --------
     * determines the class of the li elements. Can be, for example
     * [x] address
     * [x] email
     * [x] error
     * [x] fax
     * [x] mobile
     * [x] no
     * [x] phone
     * [x] yes
     *
     * if $items is passed as an array of arrays, $liClass will be merged with
     * the class for an item defined in the array
     *
     * see screen.sprites, section ul.iconized
     *
     * @param  array|string  $items
     * @param  string        $ulClass
     * @param  string        $liClass
     * @return string
     */
    public function iconizedList($items = NULL, $ulClass = NULL, $liClass = NULL)
    {

        /**
         * items
         */
        if ($items===NULL) {
            return NULL;
        }
        if (is_string($items)) {
            $items = array($items);
        }
        if (!is_array($items)) {
            return '';
        }

        /**
         * ulClass
         */
        $ulClass = trim((string) $ulClass);

        /**
         * liClass
         */
        $liClass = trim((string) $liClass);

        ob_start();

?>
<!-- iconizedList begin -->
<ul class="iconized<?php echo ($ulClass ? ' ' . $this->view->escape($ulClass) : ''); ?>">
<?php

        $itemCount = count($items);

        for($i = 0; $i<$itemCount; $i++) {

            $item = $items[$i];

            $class = $liClass ? array($liClass) : array();
            $label = NULL;
            $value = $item;

            if (is_array($item)) {
                if (array_key_exists('class', $item)) {
                    $class = array_merge($class, explode(' ', $item['class']));
                }
                if (array_key_exists('label', $item)) {
                    $label = $item['label'];
                }
                if (array_key_exists('value', $item)) {
                    $value = $item['value'];
                }
            }

            if ($i==$itemCount-1) {
                $class[] = 'last';
            }

            $class = implode(' ', $class);

?>
	<li<?php echo ($class ? ' class="' . $class . '"' : ''); ?>><?php echo ($label ? '<span class="label">' . $this->view->escape($label). '</span>' : ''); ?><?php echo $value; ?></li>
<?php

        }

?>
</ul>
<?php

    /**
     * floating?
     */
    if (preg_match('/float/', $ulClass)) {
?>
<br class="clear" />
<?php
    }

?>
<!-- iconizedList end -->
<?php

        return ob_get_clean();

    }
}