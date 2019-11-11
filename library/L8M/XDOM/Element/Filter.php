<?php
/**
 * PHP DOM: How to get child elements by tag name in an elegant manner?
 *
 * @link       http://stackoverflow.com/a/19569921/367456
 * @filesource /library/L8M/XDOM/Element/Filter.php
 * @author     hakre <http://hakre.wordpress.com>
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Filter.php 189 2014-09-11 08:26:25Z nm $
 */

/**
 * Class XDOM_Element_Filter
 */
class XDOM_Element_Filter extends FilterIterator
{
	private $tagName;

	/**
	 * Returns a filtered DOMNodeList.
	 *
	 * @param DOMNodeList $nodeList
	 * @param string $tagName
	 */
	public function __construct(DOMNodeList $nodeList, $tagName = NULL)
	{
		$this->tagName = $tagName;
		parent::__construct(new IteratorIterator($nodeList));
	}

	/**
	 * @return bool true if the current element is acceptable, otherwise false.
	 */
	public function accept()
	{
		$current = $this->getInnerIterator()->current();

		if (!$current instanceof DOMElement) {
			return FALSE;
		}

		return $this->tagName === NULL || $current->tagName === $this->tagName;
	}
}