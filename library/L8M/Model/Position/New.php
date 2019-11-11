<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Model/Position/New.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: New.php 42 2014-04-02 14:29:27Z nm $
 */

/**
 *
 *
 * L8M_Model_Position_New
 *
 *
 */
class L8M_Model_Position_New
{

	/**
	 * @var String
	 */
	private $_modelName = NULL;

	/**
	 * @var integer
	 */
	private $_parentID = NULL;
	private $_limit = NULL;
	private $_itemPosition = NULL;
	private $_itemsCount = NULL;
	private $_maxItemsToLoad = NULL;
	private $_lastLoaded = NULL;
	private $_startForPosition = NULL;

	/**
	 * @var boolean
	 */
	private $_addElementNew = TRUE;

	/**
	 * @var Doctrine_Relation_LocalKey
	 */
	private $_parentRelation = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */
	public function __construct($model, $parentRelation) {
		if ($model &&
			$model instanceof Default_Model_Base_Abstract) {

			$this->_model = $model;
			$this->_modelName = $this->_model->getTable()->getClassnameToReturn();

			if ($parentRelation) {
				$this->_parentRelation = $parentRelation;
				$localParentColumnName = $this->_parentRelation->getLocalColumnName();
				$this->_parentID = $this->_model->$localParentColumnName;
			}
		}
	}

	/**
	 *
	 *
	 * Initialization Methods
	 *
	 *
	 */


	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	public function setParent($parentID = NULL, $parentRelation = NULL) {
		if ($parentRelation &&
			$this->_model->getTable()->hasRelation($parentRelation)) {

			$this->_parentRelation = $this->_model->getTable()->getRelation($parentRelation);
		}

		if ($this->_parentRelation &&
			$parentID) {
			$count = L8M_Sql::factory($this->_parentRelation->getClass())
				->addWhere($this->_parentRelation->getForeignColumnName() . ' = ?', array($parentID))
				->getCount()
			;
			if ($count) {
				$this->_parentID = $parentID;
			}
		}

		if ($parentID === NULL) {
			$this->_parentID = $parentID;
		}
	}

	private function _initPosition($offset = NULL) {

		/**
		 * total items
		 */
		$countSqlQuery = L8M_Sql::factory($this->_modelName);
		if ($this->_parentRelation) {
			if ($this->_parentID) {
				$countSqlQuery = $countSqlQuery
					->addWhere($this->_parentRelation->getLocalColumnName() . ' = ?', array($this->_parentID))
				;
			} else {
				$countSqlQuery = $countSqlQuery
					->addWhere($this->_parentRelation->getLocalColumnName() . ' IS NULL', array())
				;
			}
		}
		$this->_itemsCount = $countSqlQuery
			->getCount()
		;
		$this->_itemsCount = $this->_itemsCount;

		if ($offset !== NULL &&
			is_int($offset) &&
			$offset % 20 == 0) {

			$this->_addElementNew = FALSE;
		}
		if ($this->_addElementNew) {
			$this->_itemsCount = $this->_itemsCount + 1;
		}


		/**
		 * own position
		 */
		$this->_itemPosition = $this->_itemsCount;


		/**
		 * max items to load
		*/
		$itemsCountMod = $this->_itemsCount % 60;
		$this->_maxItemsToLoad = $this->_itemsCount - $itemsCountMod;
		if ($itemsCountMod > 0) {
			if ($itemsCountMod <= 20) {
				$this->_maxItemsToLoad = $this->_maxItemsToLoad + 20;
			} else
			if ($itemsCountMod <= 40) {
				$this->_maxItemsToLoad = $this->_maxItemsToLoad + 40;
			} else {
				$this->_maxItemsToLoad = $this->_maxItemsToLoad + 60;
			}
		}

		/**
		 * start for position
		 */
		if ($offset !== NULL &&
			is_int($offset) &&
			$offset % 20 == 0 &&
			$offset < $this->_maxItemsToLoad) {

			$this->_startForPosition = (int) $offset;
			$this->_limit = 20;
		} else {
			$this->_limit = 60;

			$itemsPositionMod = $this->_itemPosition % 60;
			if ($itemsPositionMod == 0 &&
				$this->_itemPosition >= 60) {

				$this->_startForPosition = $this->_itemPosition - 60;
			} else {
				$this->_startForPosition = $this->_itemPosition - $itemsPositionMod;
				if ($itemsPositionMod <= 20) {
					$this->_startForPosition = $this->_startForPosition - 40;
				} else
				if ($itemsPositionMod <= 40) {
					$this->_startForPosition = $this->_startForPosition - 20;
				}
				if ($this->_startForPosition < 0) {
					$this->_startForPosition = 0;
				}
			}
		}

		/**
		 * total items till offset
		 */
		$countSqlQuery = L8M_Sql::factory($this->_modelName);
		if ($this->_parentRelation) {
			if ($this->_parentID) {
				$countSqlQuery = $countSqlQuery
					->addWhere($this->_parentRelation->getLocalColumnName() . ' = ?', array($this->_parentID))
				;
			} else {
				$countSqlQuery = $countSqlQuery
					->addWhere($this->_parentRelation->getLocalColumnName() . ' IS NULL', array())
				;
			}
		}
		$itemsCountTillOffset = $countSqlQuery
			->addWhere('position <= ?', array($this->_startForPosition + 60))
			->getCount()
		;

		$itemsCountMod = $itemsCountTillOffset % 60;
		if ($itemsCountMod > 0) {
			$itemsCountTillOffset= $itemsCountTillOffset - $itemsCountMod;
			if ($itemsCountMod <= 20) {
				$itemsCountTillOffset = $itemsCountTillOffset + 20;
			} else
			if ($itemsCountMod <= 40) {
				$itemsCountTillOffset = $itemsCountTillOffset + 40;
			} else {
				$itemsCountTillOffset = $itemsCountTillOffset + 60;
			}
		}
		$this->_lastLoaded = $itemsCountTillOffset;
	}

	public function load($offset = NULL) {
		$this->_initPosition($offset);

		$localQuery = Doctrine_Query::create()
			->from($this->_modelName . ' m')
			->select('m.id, m.short, m.position')
			->orderBy('m.position ASC')
		;

		if ($this->_parentRelation) {
			if ($this->_parentID) {
				$localQuery = $localQuery
					->addWhere('m.' . $this->_parentRelation->getLocalColumnName() . ' = ?', array($this->_parentID))
				;
			} else {
				$localQuery = $localQuery
					->addWhere('m.' . $this->_parentRelation->getLocalColumnName() . ' IS NULL', array())
				;
			}
		}

		$localCollectionArray = $localQuery
			->limit($this->_limit)
			->offset($this->_startForPosition)
			->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
			->execute()
		;

		$posCounter = $this->_startForPosition + 1;
		$key = -1;
		$value = array(
			'position'=>0,
		);
		foreach ($localCollectionArray as $key=>$value) {
			$value['posCounter'] = $posCounter++;
			$localCollectionArray[$key] = $value;
		}

		if ($this->_addElementNew) {
			$localCollectionArray[$key + 1] = array(
				'id'=>'new',
				'short'=>L8M_Translate::string('new', 'en'),
				'position'=>$value['position'],
				'posCounter'=>$posCounter,
			);
			if (count($localCollectionArray) == 1 &&
				$localCollectionArray[0]['position'] == 1 &&
				$this->_parentRelation &&
				$this->_parentID) {

				$checkCollectionArray = Doctrine_Query::create()
					->from($this->_modelName . ' m')
					->select('m.id, m.short, m.position')
					->addWhere('m.' . $this->_parentRelation->getLocalColumnName() . ' IS NOT NULL', array())
					->orderBy('m.position DESC')
					->limit(1)
					->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
					->execute()
				;

				if (count($checkCollectionArray) == 1 &&
					isset($checkCollectionArray[0]['position'])) {

					$localCollectionArray[0]['position'] = $checkCollectionArray[0]['position'] + 1;
				}
			}
			$countAll = $this->_itemsCount;
		} else {
			$countAll = $this->_itemsCount + 1;
		}

		$result = array(
			'countAll'=>$countAll,
			'maxToLoad'=>$this->_maxItemsToLoad,
			'actShow'=>$this->_limit,
			'firstLoaded'=>$this->_startForPosition,
			'firstPosition'=>$localCollectionArray[0]['position'],
			'lastLoaded'=>$this->_startForPosition + $this->_limit,
			'loaded'=>count($localCollectionArray),
			'items'=>$localCollectionArray,
		);

		return $result;
	}
}