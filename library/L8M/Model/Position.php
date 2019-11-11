<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Model/Position.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Position.php 42 2014-04-02 14:29:27Z nm $
 */

/**
 *
 *
 * L8M_Model_Position
 *
 *
 */
class L8M_Model_Position
{

	private $_function = NULL;
	private $_modelID = NULL;
	private $_modelName = NULL;
	private $_parentID = NULL;
	private $_parentRelation = NULL;
	private $_offset = NULL;

	private $_myPosition = NULL;

	private $result = array(
		'maxToLoad'=>0,
		'actShow'=>0,
		'firstLoaded'=>0,
		'lastLoaded'=>0,
		'loaded'=>0,
		'items'=>array(),
	);

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */
	public function __construct($modelID = NULL, $modelName = NULL, $parentRelationName = NULL) {
		$this->_modelID = $modelID;
		$this->_modelName = $modelName;

		if (strpos($this->_modelName, 'Default_Model_') === FALSE) {
			$this->_modelName = 'Default_Model_' . $this->_modelName;
		}
		if (class_exists($this->_modelName, TRUE)) {
			$dummyModel = new $this->_modelName();

			if ($dummyModel->getTable()->hasRelation($parentRelationName)) {
				$this->_parentRelation = $dummyModel->getTable()->getRelation($parentRelationName);
			} else {
				$this->_parentRelation = NULL;
			}

			if ($modelID !== NULL) {
				$model = Doctrine_Query::create()
					->from($this->_modelName . ' m')
					->addWhere('m.id = ? ', array($modelID))
					->execute()
					->getFirst()
				;
				if ($model) {
					$this->_modelID = $modelID;
					if ($this->_parentRelation) {
						$localParentColumnName = $this->_parentRelation->getLocalColumnName();
						$this->_parentID = $model->$localParentColumnName;
					}

					$this->_myPosition = new L8M_Model_Position_Existing($model, $this->_parentRelation);
				}
			}

			if (!$this->_myPosition) {
				$this->_myPosition = new L8M_Model_Position_New($dummyModel, $this->_parentRelation);
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
		if ($this->_myPosition) {
			$this->_myPosition->setParent($parentID, $parentRelation);
		}
	}

	public function load($offset = NULL) {
		$returnValue = array();

		if ($this->_myPosition) {
			$returnValue = $this->_myPosition->load($offset);
		}

		return $returnValue;
	}

}