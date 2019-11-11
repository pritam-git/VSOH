<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Activation.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Action.php 86 2014-05-18 07:43:07Z nm $
 */

/**
 *
 *
 * Default_Model_Activation
 *
 *
 */
class Default_Model_Action extends Default_Model_Base_Action
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
		$this->resource = L8M_Acl_Resource::getResourceName($this->Controller->Module->name,
															$this->Controller->name,
															$this->name);

		if ($this->id &&
			$this->hasRelation('Translation')) {

			$this->_removeTranslationFromCache();
		}
		$this->_removeFieldNameFromCache();
		$this->_removeResourceFromCache();

		parent::save();
	}

	/**
	 * deletes this data access object and all the related composites
	 * this operation is isolated by a transaction
	 *
	 * this event can be listened by the onPreDelete and onDelete listeners
	 *
	 * @return boolean	  true if successful
	 */
	public function delete(Doctrine_Connection $conn = null)
	{
		if ($this->id &&
			$this->hasRelation('Translation')) {

			$this->_removeTranslationFromCache();
		}
		$this->_removeFieldNameFromCache();
		$this->_removeResourceFromCache();

		return parent::delete($conn);
	}

	/**
	 * remove resource from cache
	 */
	protected function _removeResourceFromCache()
	{
		$cache = L8M_Cache::getCache('Default_Model_Action');
		$modelID = $this->id;

		if ($cache &&
			$modelID) {

			/**
			 * helper 'cause doctrine query reffers to its-self by reference and overwrites changes
			 */
			$helperArray = $this->toArray();
			unset($helperArray['id']);
			unset($helperArray['create_at']);
			unset($helperArray['updated_at']);
			unset($helperArray['deleted_at']);

			$actionModel = Doctrine_Query::create()
				->from('Default_Model_Action m')
				->where('m.id = ?', array($modelID))
				->execute()
				->getFirst()
			;

			if ($actionModel) {

				/**
				 * merge with helper
				 */
				$this->merge($helperArray);

				$resource = $actionModel->resource;
				$resourceArray = explode('.', $resource);

				$cache->remove(L8M_Cache::getCacheId('resource', $resourceArray[0]));

				if (count($resourceArray) >= 2) {
					$cache->remove(L8M_Cache::getCacheId('resource', $resourceArray[0] . '.' . $resourceArray[1]));
					if (count($resourceArray) == 3) {
						$cache->remove(L8M_Cache::getCacheId('resource', $resource));
					}
				}
			}
		}

		$cache = L8M_Cache::getCache('L8M_Navigation');
		if ($cache) {
			$cache->clean();
		}

		$cache = L8M_Cache::getCache('L8M_Controller_Action_Param');
		if ($cache) {
			$cache->clean();
		}

		$cache = L8M_Cache::getCache('L8M_Controller_Action_Var');
		if ($cache) {
			$cache->clean();
		}

		L8M_Content::cleanCache();
	}

	/**
	 * Creates and returns a link to that action.
	 *
	 * @param array $params
	 * @return
	 */
	public function getLink($params = array()) {
		$returnValue = NULL;

		$viewFromMvc = Zend_Layout::getMvcInstance()->getView();
		if ($this->id &&
			$viewFromMvc &&
			is_array($params)) {

			$returnValue = $viewFromMvc->url(array_merge(array('module'=>$this->Controller->Module->name, 'controller'=>$this->Controller->name, 'action'=>$this->name), $params), NULL, TRUE);
		}

		return $returnValue;
	}
}