<?php

/**
 * L8M
 *
 *
 * @filesource /library/Mandala/HTML5/List/Breadcrumbs.php
 * @author     Robert Quint <rq@l8m.com>
 * @version    $Id: Breadcrumbs.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * Mandala_Bootstrap_Breadcrumbs
 *
 *
 */
class Mandala_Bootstrap_Breadcrumbs extends Mandala_Bootstrap_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * breadcrumb elements
	 * @var array
	 */
	private $_elements = array();

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * creates a bootstrap breadcrumbs with RDFa
	 * @param string $class
	 * @param string $id
	 * @return Mandala_Bootstrap_Breadcrumbs
	 */
	public function __construct($class = 'breadcrumb', $id = NULL) {

		parent::__construct('ol', $class, $id);

		return $this;

	}

	/**
	 * rendering breadcrumb
	 * @see Mandala_HTML5_Element::__toString()
	 */
	protected function _prepareContent() {

		$this->setAttr('vocab', 'http://schema.org/');
		$this->setAttr('typeof', 'BreadcrumbList');

		for ($i = 0; $i < count($this->_elements); $i++) {
			$listElement = new Mandala_HTML5_List_Element();
			$listElement->setAttr('property', 'itemListElement');
			$listElement->setAttr('typeof', 'ListItem');
			$text = new Mandala_HTML5_Element('span');
			$text->setAttr('property', 'name');
			$text->append($this->_elements[$i]['title']);

			if (($i + 1) < count($this->_elements)) {
				$link = new Mandala_HTML5_Link($this->_elements[$i]['href']);
				$link->setAttr('property', 'item');
				$link->setAttr('typeof', 'WebPage');
				$link->append($text);
				$listElement->append($link);
			} else {
				$listElement->append($text);
			}
			$listElement->append('<meta property="position" content="' . ($i + 1) . '">');
			$this->append($listElement);
		}

	}

	/**
	 * add element to breadcrumb list
	 * @param string $title
	 * @param string $url
	 *
	 * @return Mandala_Bootstrap_Breadcrumbs
	 */
	public function addElement($title, $url) {

		$this->_elements[] = array(
			'title'=>$title,
			'href'=>$url,
		);

		return $this;

	}


}