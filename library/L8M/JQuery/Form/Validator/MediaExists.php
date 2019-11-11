<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Validator/MediaExists.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MediaExists.php 7 2014-03-11 16:18:40Z nm $
 */


/**
 *
 *
 * L8M_JQuery_Form_Validator_MediaExists
 *
 *
 */
class L8M_JQuery_Form_Validator_MediaExists extends Zend_Validate_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * Invalid ID
	 *
	 * @var string
	 */
	const INVALID = 'mediaExistsInvalid';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Validation failure message template definitions
	 *
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID => 'Media does not exists within that relation.',
	);

	/**
	 * MediaType.
	 *
	 * @var string
	 */
	protected $_mediaModel = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Form_Validator_Abstract instance.
	 *
	 * @param Zend_Form $form
	 */
	public function __construct($mediaType = NULL)
	{
		if ($mediaType == 'mediaID') {
			$mediaType = '';
		}

		if (!class_exists('Default_Model_Media' . $mediaType)) {
			throw new L8M_Exception('Class does not exist: Default_Model_Media' . $mediaType);
		}
		$this->_mediaModel = 'Default_Model_Media' . $mediaType;
	}

	/**
	 *
	 *
	 * MediaExists Methods
	 *
	 *
	 */

	/**
	 * Returns TRUE when the validation is ok.
	 *
	 * @return bool
	 */
	public function isValid($value)
	{
		$returnValue = TRUE;

		$mediaModel = Doctrine_Query::create()
			->from($this->_mediaModel . ' m')
			->addWhere('m.id = ? ', array($value))
			->limit(1)
			->execute()
			->getFirst()
		;

		if (!$mediaModel) {
			$returnValue = FALSE;
			$this->_error(self::INVALID);
		}

		return $returnValue;
	}
}