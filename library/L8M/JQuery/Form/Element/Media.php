<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/Media.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Media.php 537 2017-08-19 17:10:09Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_Multi
 *
 *
 */
class L8M_JQuery_Form_Element_Media extends Zend_Form_Element_Xhtml
{

 	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * contains media ID
	 *
	 * @var array
	 */
	protected $_mediaID = NULL;

	/**
	 * image string of media
	 *
	 * @var string
	 */
	protected $_mediaImageString = NULL;

	/**
	 * media
	 *
	 * @var Default_Model_MediaImage
	 */
	protected $_media = NULL;

	/**
	 * media popup type for media browser
	 *
	 * @var string
	 */
	protected $_mediaType = NULL;

	/**
	 * media popup model for media browser
	 *
	 * @var string
	 */
	protected $_mediaModel = NULL;

	/**
	 * contains default media folder id
	 *
	 * @var integer
	 */
	protected $_defaultMediaFolderID = NULL;

	/**
	 * contains default media role
	 *
	 * @var Default_Model_Role
	 */
	protected $_defaultMediaRole = NULL;

	/**
	 * media popup column for media browser
	 *
	 * @var string
	 */
	protected $_mediaColumn = NULL;

	/**
	 * media required
	 *
	 * @var string
	 */
	protected $_mediaRequired = TRUE;

	/**
	 * model column name id
	 *
	 * @var integer
	 */
	protected $_modelColumnNameID = NULL;

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Set elements
	 *
	 * @param  integer $values
	 * @return L8M_JQuery_Form_Element_Media
	 */
	public function setMediaID($value)
	{

		if ($value !== NULL &&
			trim($value) !== '') {

			if (!is_numeric($value)) {
				throw new L8M_Exception('Sorry, set MediaID requires a numeric value.');
			}

			$media = Doctrine_Query::create()
				->from('Default_Model_Media m')
				->where('m.id = ? ', array($value))
				->execute()
				->getFirst()
			;

			if ($media !== FALSE &&
				$media instanceof Default_Model_Media) {

				$this->_mediaID = $value;
				$this->_media = $media;
			} else {
				$this->_media = NULL;
			}
		}
		return $this;
	}

	public function setMediaImageString()
	{
		/**
		 * media is an image
		 */
		if ($this->_mediaID !== NULL &&
			$this->_media instanceof Default_Model_MediaImage) {

			$imgInstance = $this->_media->maxBox(100, 54);
			$imgInstance->setHtmlAttribute('id', $this->getName() . 'IMG');
			$img = $imgInstance->getTag(FALSE);
		} else

		/**
		 * media is a file
		 */
		if ($this->_mediaID !== NULL &&
			$this->_media instanceof Default_Model_Media) {

			$mediaLink = '/img/system/icon/page_white_text.png';
			$fileTypes = array(
				'/img/system/icon/page_white_word.png'=>array('doc', 'docx', 'dot', 'dotx'),
				'/img/system/icon/page_white_excel.png'=>array('xlc', 'xls', 'xlsx'),
				'/img/system/icon/page_white_acrobat.png'=>array('pdf'),
				'/img/system/icon/page_white_flash.png'=>array('swf'),
				'/img/system/icon/page_white_compressed.png'=>array('zip', 'tar', 'rar', 'gzip'),
				'/img/system/icon/page_white_powerpoint.png'=>array('ppt', 'pptx'),
				'/img/system/icon/monitor.png'=>array('mp4', 'mpeg', 'flv', 'fl4', 'avi'),
			);
			$tmpFileArray = explode('.', $this->_media->file_name);
			$fileExtension = strtolower($tmpFileArray[count($tmpFileArray) - 1]);

			foreach ($fileTypes as $cssType => $fileTypeArray) {
				if (in_array($fileExtension, $fileTypeArray)) {
					$mediaLink = $cssType;
				}
			}

			$img = '<img id="' . $this->getName() . 'IMG" src="' . $mediaLink . '" alt="" />';
		} else

		/**
		 * somthing else or empty
		 */
		{
			$img = '<img id="' . $this->getName() . 'IMG" src="/img/system/icon/photo_delete.png" alt="" />';
		}

		$this->_mediaImageString = $img;
	}

	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

	public function getMediaFilename()
	{
		$returnValue = NULL;
		if ($this->_media instanceof Default_Model_Media) {
			$returnValue = $this->_media->file_name;
		} else {
			$view = Zend_Layout::getMvcInstance()->getView();
			$returnValue = $view->translate('No media selected.');
		}
		return $returnValue;
	}

	public function getMediaImage()
	{
		$this->setMediaImageString();
		return $this->_mediaImageString;
	}

	public function getMediaUrl()
	{
		$returnValue = NULL;
		if ($this->_mediaID !== NULL &&
			$this->_media instanceof Default_Model_MediaImage) {

			$returnValue = $this->_media->getLink();
		}
		return $returnValue;
	}

	/**
	 * Returns MediaID
	 *
	 * return integer
	 */
	public function getMediaID()
	{
		return $this->_mediaID;
	}

	/**
	 * is Media Required
	 *
	 * @return boolean
	 */
	public function isMediaRequired()
	{

		/**
		 * return required
		 */
		return $this->_mediaRequired;
	}

	/**
	 * Get Url
	 *
	 * @return String
	 */
	public function getUrl($addMediaOnly = FALSE)
	{
		/**
		 * prepare url
		 */
		$view = $this->getView();

		$urlArray = array(
			'module'=>'system',
			'controller'=>'media',
			'action'=>'index',
			'browserType'=>'popup-select',
			'type'=>$this->_mediaType,
			'jsObjRef'=>$this->getName(),
			'mediaModel'=>$this->_mediaModel,
			'mediaColumn'=>$this->_mediaColumn,
			'mediaFolderID'=>$this->_defaultMediaFolderID,
			'modelColumnNameID'=>$this->_modelColumnNameID,
		);

		if ($this->_defaultMediaRole) {
			$urlArray['mediaRole'] = $this->_defaultMediaRole->short;
		}

		if ($addMediaOnly) {
			$urlArray['action'] = 'create';
			$urlArray['add-media-only'] = 'true';
		}

		$popupUrl = $view->url(
			$urlArray,
			NULL,
			TRUE
		);

		/**
		 * return url
		 */
		return $popupUrl;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	public function __construct($spec, $type = NULL, $model = NULL, $column = NULL, $mediaRequired = TRUE, $defaultMediaFolder = NULL, $defaultMediaRole = NULL, $columnNameModel = NULL, $options = NULL)
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
		 * set popup type
		 */
		if ($type == '' ||
			$type == NULL) {

			$this->_mediaType = 'mediaID';
		} else {
			$this->_mediaType = $type;
		}

		/**
		 * set popup model
		 */
		$this->_mediaModel = $model;

		/**
		 * set popup column
		 */
		$this->_mediaColumn = $column;

		/**
		 * set required
		 */
		if (is_bool($mediaRequired)) {
			$this->_mediaRequired = $mediaRequired;
		}

		/**
		 * set validator
		 */
		if ($this->isMediaRequired()) {
			$this
				->setRequired()
				->addValidator(new L8M_JQuery_Form_Validator_MediaExists($this->_mediaType))
			;
		}

		/**
		 * directory fitting
		 */
		if ($defaultMediaFolder &&
			$defaultMediaFolder instanceof Default_Model_MediaFolder) {

			$this->_defaultMediaFolderID = $defaultMediaFolder->id;
		}

		/**
		 * role fitting
		 */
		if ($defaultMediaRole &&
			$defaultMediaRole instanceof Default_Model_Role) {

			$this->_defaultMediaRole = $defaultMediaRole;
		}

		/**
		 * model column name ID
		 */
		if ($columnNameModel instanceof Default_Model_ModelColumnName) {
			$this->_modelColumnNameID = $columnNameModel->id;
		}

		/**
		 * parent constructor
		 */
		parent::__construct($spec, $options);
	}

	public function loadDefaultDecorators()
	{
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return;
		}

		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this
				->addDecorator('Media')
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