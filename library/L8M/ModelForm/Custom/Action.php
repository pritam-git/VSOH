<?php

/**
 * L8M
 *
 * @filesource /library/L8M/ModelForm/Custom/Action.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Action.php 338 2015-04-28 09:59:47Z nm $
 */

/**
 *
 *
 * L8M_ModelForm_Custom_Action
 *
 *
 */
class L8M_ModelForm_Custom_Action extends L8M_ModelForm_Base
{
	protected $_modelName = 'Default_Model_Action';

	/**
	 * By default, many-relations will be ignored but let's enable them
	 */
	protected $_generateManyFields = true;

	/**
	 * Make the content column's field type 'textarea' instead of the default 'text'
	 */
	protected $_fieldTypes = array(
	//	'content' => 'textarea'
	);

	/**
	 * Give some human-friendly labels for the fields:
	 */
	protected $_fieldLabels = array(
	//	'name' => 'Article name',
	//	'content' => 'Article content',
	//	'category_id' => 'Category'
	);

	/**
	 * Give a label to a many relation
	 */
	protected $_relationLabels = array(
	//	'ArticleComment' => 'Comment'
	);

	/**
	 * this method is called before the form is generated
	 */
	protected function _preGenerate()
	{
	}

	/**
	 * this method is called after the form is generated
	 */
	protected function _postGenerate()
	{
		/**
		 * Add a submit button
		 */
		$this->addElement('submit', 'Save');
	}
}