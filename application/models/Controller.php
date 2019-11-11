<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Controller.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Controller.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Model_Controller
 *
 *
 */
class Default_Model_Controller extends Default_Model_Base_Controller
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * applies the changes made to this object into database
	 * this method is smart enough to know if any changes are made
	 * and whether to use INSERT or UPDATE statement
	 *
	 * this method also saves the related components
	 *
	 * @param Doctrine_Connection $conn	 optional connection parameter
	 * @throws Exception					if record is not valid and validation is active
	 * @return void
	 */
	public function save(Doctrine_Connection $conn = null)
	{
		$this->resource = L8M_Acl_Resource::getResourceName($this->Module->name,
															$this->name);

		if ($this->id &&
			$this->hasRelation('Translation')) {

			$this->_removeTranslationFromCache();
		}
		$this->_removeFieldNameFromCache();

		parent::save();
	}
}