<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/Position.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id:Position.php 271 2012-02-27 13:56:59Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_Position
 *
 *
 */
class L8M_JQuery_Form_Element_Position extends Zend_Form_Element_Xhtml
{

 	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * contains model ID
	 *
	 * @var integer
	 */
	protected $_modelID = NULL;

	/**
	 * contains local class name
	 *
	 * @var string
	 */
	protected $_localClassName = NULL;

	/**
	 * contains short
	 *
	 * @var string
	 */
	protected $_modelShort = NULL;

	/**
	 * prefix
	 *
	 * @var string
	 */
	protected $_formPrefix = NULL;

	/**
	 * parent relation
	 *
	 * @var Doctrine_Relation_LocalKey
	 */
	protected $_parentRelation = NULL;

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

	public function getValues()
	{
		$returnValue = NULL;

		return $returnValue;
	}

	public function getModelID()
	{
		return $this->_modelID;
	}

	public function getModelShort()
	{
		return $this->_modelShort;
	}

	public function getItemPosition()
	{
		return $this->_itemPosition;
	}

	public function getLocalClassName()
	{
		return $this->_localClassName;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * constructor
	 *
	 * @param string $spec
	 * @param string $localClassName
	 * @param string $modelID
	 * @param string $useParentRelation
	 * @param string $options
	 * @throws L8M_Exception
	 */
	public function __construct($spec = NULL, $localClassName, $modelID = NULL, $useParentRelation = NULL, $options = NULL)
	{

		/**
		 * set decorator path
		 */
		$this->addPrefixPath(
			'L8M_JQuery_Form_Decorator',
			'L8M'. DIRECTORY_SEPARATOR . 'JQuery'. DIRECTORY_SEPARATOR . 'Form'. DIRECTORY_SEPARATOR . 'Decorator',
			'decorator'
		);

		/**
		 * set model id
		 */
		$this->_modelID = $modelID;

		/**
		 * set local class name
		 */
		if (!class_exists($localClassName)) {
			throw new L8M_Exception('Failure creationg Position-Form-Element. Class does not exist.');
		} else {
			$this->_localClassName = $localClassName;
		}

		/**
		 * prefix
		 */
		$filter = new Zend_Filter_Word_CamelCaseToUnderscore;
		$this->_formPrefix = strtolower($filter->filter(str_replace('Default_Model_', '', $localClassName)));

		/**
		 * parent relation
		 */
		$dummyModel = new $localClassName();
		$this->_parentRelation = $dummyModel->getParentRelation();
		if (!$this->_parentRelation &&
			$useParentRelation &&
			$dummyModel->getTable()->hasRelation($useParentRelation)) {

			$this->_parentRelation = $dummyModel->getTable()->getRelation($useParentRelation);
		}

		/**
		 * parent constructor
		 */
		parent::__construct($spec, $options);
	}

	public function hasParent()
	{
		$returnValue = FALSE;
		if ($this->_parentRelation) {
			$returnValue = TRUE;
		}
		return $returnValue;
	}

	public function getParentRelationAliasName()
	{
		$returnValue = NULL;
		if ($this->_parentRelation) {
			$returnValue = $this->_parentRelation->getAlias();
		}
		return $returnValue;
	}

	public function getFormElementNameIdOfParentFormElement()
	{
		$returnValue = '';
		if ($this->hasParent()) {
			$filter = new Zend_Filter_Word_CamelCaseToUnderscore();
			$returnValue = strtolower($filter->filter(str_replace('Default_Model_', '', $this->_localClassName))) . '_' . $this->_parentRelation->getLocalColumnName();
		}
		return $returnValue;
	}

	public function loadDefaultDecorators()
	{
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return;
		}

		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this
				->addDecorator('Position')
				->addDecorator('Errors')
				->addDecorator('Description', array(
					'tag'   => 'p',
					'class' => 'description'
					))
				->addDecorator('HtmlTag', array(
					'tag' => 'dd',
					'id'  => $this->getName() . '-element'
					))
				->addDecorator('Label', array('tag' => 'dt'))
			;
		}
	}
}