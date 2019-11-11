<?php
class L8M_JQuery_Form_Decorator_Media extends Zend_Form_Decorator_Abstract
{
	private static $_CSSinit = FALSE;

	private function _renderCss()
	{
		$returnValue = NULL;
		if (!self::$_CSSinit) {
			self::$_CSSinit = TRUE;

			$returnValue = '
					<style type="text/css">
						.wraptocenter {
						    display: table-cell;
						    text-align: center;
						    vertical-align: middle;
						    width: 100px;
						    height: 68px;
						}
						.wraptocenter * {
						    vertical-align: middle;
						}
						/*\*//*/
						.wraptocenter {
						    display: block;
						}
						.wraptocenter span {
						    display: inline-block;
						    height: 100%;
						    width: 1px;
						}
						/**/
						</style>
						<!--[if lt IE 8]><style>
						.wraptocenter span {
						    display: inline-block;
						    height: 100%;
						}
					</style><![endif]-->
			';
		}

		return $returnValue;
	}

	/**
	 * Render JavaScript
	 *
	 * @param L8M_JQuery_Form_Element_Media $element
	 * @param $view
	 */
	private function _renderJavaScript($element, $view)
	{
		ob_start();

?>
<script type="text/javascript">
	//<![CDATA[

	function del<?php echo $element->getName(); ?>func() {
		$("img#<?php echo $element->getName(); ?>IMG").attr('src', '/img/system/icon/photo_delete.png');
		$("input#<?php echo $element->getName(); ?>").val('');
		$("div#<?php echo $element->getName(); ?>fileName")
			.addClass('no-file')
			.html('<?php echo $view->translate('No media selected.'); ?>')
		;
		$("li#<?php echo $element->getName(); ?>LiDownload").hide();
		$("li#<?php echo $element->getName(); ?>EditImage").hide();
	}

	function prepadd<?php echo $element->getName(); ?>func() {
		//window.open('<?php echo $element->getUrl(TRUE); ?>', '', '');

		/**
		 * prepare PopUp Background
		 */
		var popUpBackground = '<div class="mce-reset mce-fade mce-in mediaBrowserPopUpBackground" id="mce-modal-block" style="z-index: 65535;"></div>';
		$('body').append(popUpBackground);

		/**
		 * prepare PopUp
		 */
		var popUp = '<div class="mediaBrowserPopUp" style="z-index: 65537; position: fixed; width:860px; height:578px; top:10px; left:50%; margin-left:-430px; overflow:hidden; background: url(/img/js/ajax-loader.gif) no-repeat scroll center center #FFFFFF; border-radius: 6px 6px 6px 6px; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);">' +
					'	<div class="mce-window-head">' +
					'		<div class="mce-title">MediaBrowser</div>' +
					'		<button style="background-color: #ffffff; border: medium none; font-family: Helvetica Neue, Helvetica, Arial;" class="mce-close close-browser" aria-hidden="true" type="button">x</button>' +
					'	</div>' +
					'	<div class="innerMediaBrowserPopUp">' +
					'	</div>' +
					'</div>';
		$('body').append(popUp);

		/**
		 * close media browser
		 */
		$("button.close-browser").click(function() {
			$('div.mediaBrowserPopUp').remove();
			$('div.mediaBrowserPopUpBackground').remove();
		});

		/**
		 * add Browser
		 */
		var popUpClassID = '';
		if ($.browser == 'msie') {
			popUpClassID = ' classid="clsid:25336920-03F9-11CF-8FD0-00AA00686F13"';
		}

		var iframeObj = '<object' + popUpClassID + ' type="text/html" data="<?php echo $element->getUrl(TRUE); ?>" style="width:860px; height:550px;" width="860" height="537"></object>';
		iframeObj = '<iframe src="<?php echo $element->getUrl(TRUE); ?>" style="width:860px; height:550px;" width="860" height="537"></iframe>';
		$('div.innerMediaBrowserPopUp').append(iframeObj);
	}

	function prep<?php echo $element->getName(); ?>func() {
		//window.open('<?php echo $element->getUrl(); ?>', '', '');

		/**
		 * prepare PopUp Background
		 */
		var popUpBackground = '<div class="mce-reset mce-fade mce-in mediaBrowserPopUpBackground" id="mce-modal-block" style="z-index: 65535;"></div>';
		$('body').append(popUpBackground);

		/**
		 * prepare PopUp
		 */
		var popUp = '<div class="mediaBrowserPopUp" style="z-index: 65537; position: fixed; width:860px; height:578px; top:10px; left:50%; margin-left:-430px; overflow:hidden; background: url(/img/js/ajax-loader.gif) no-repeat scroll center center #FFFFFF; border-radius: 6px 6px 6px 6px; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);">' +
					'	<div class="mce-window-head">' +
					'		<div class="mce-title">MediaBrowser</div>' +
					'		<button style="background-color: #ffffff; border: medium none; font-family: Helvetica Neue, Helvetica, Arial;" class="mce-close close-browser" aria-hidden="true" type="button">x</button>' +
					'	</div>' +
					'	<div class="innerMediaBrowserPopUp">' +
					'	</div>' +
					'</div>';
		$('body').append(popUp);

		/**
		 * close media browser
		 */
		$("button.close-browser").click(function() {
			$('div.mediaBrowserPopUp').remove();
			$('div.mediaBrowserPopUpBackground').remove();
		});

		/**
		 * add Browser
		 */
		var popUpClassID = '';
		if ($.browser == 'msie') {
			popUpClassID = ' classid="clsid:25336920-03F9-11CF-8FD0-00AA00686F13"';
		}

		var iframeObj = '<object' + popUpClassID + ' type="text/html" data="<?php echo $element->getUrl(); ?>" style="width:860px; height:550px;" width="860" height="537"></object>';
		iframeObj = '<iframe src="<?php echo $element->getUrl(); ?>" style="width:860px; height:550px;" width="860" height="537"></iframe>';
		$('div.innerMediaBrowserPopUp').append(iframeObj);
	}
	function prepedit<?php echo $element->getName(); ?>func() {
		//window.open('<?php echo $element->getUrl(TRUE); ?>', '', '');

		/**
		 * prepare PopUp Background
		 */
		var popUpBackground = '<div class="mce-reset mce-fade mce-in mediaBrowserPopUpBackground" id="mce-modal-block" style="z-index: 65535;"></div>';
		$('body').append(popUpBackground);

		/**
		 * prepare PopUp
		 */
		var popUp = '<div class="mediaBrowserPopUp" style="z-index: 65537; position: fixed; width:860px; height:578px; top:10px; left:50%; margin-left:-430px; overflow:hidden; background: url(/img/js/ajax-loader.gif) no-repeat scroll center center #FFFFFF; border-radius: 6px 6px 6px 6px; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);">' +
					'	<div class="mce-window-head">' +
					'		<div class="mce-title">MediaBrowser</div>' +
					'		<button style="background-color: #ffffff; border: medium none; font-family: Helvetica Neue, Helvetica, Arial;" class="mce-close close-browser" aria-hidden="true" type="button">x</button>' +
					'	</div>' +
					'	<div class="innerMediaBrowserPopUp">' +
					'	</div>' +
					'</div>';
		$('body').append(popUp);

		/**
		 * close media browser
		 */
		$("button.close-browser").click(function() {
			$('div.mediaBrowserPopUp').remove();
			$('div.mediaBrowserPopUpBackground').remove();
		});

		var MediaId = $('.<?php echo $element->getName().'EditImageClass'; ?>').attr('id');
		var Editurl = "<?php echo str_replace('/create/','/edit-image/',$element->getUrl(TRUE)); ?>/mediaImageId/"+MediaId+"/imageResource/<?php echo $element->tableName.'.'.$element->modelNextId.'.'.str_replace($element->tableName.'_', '', $element->getName());?>";
		/**
		 * add Browser
		 */
		var popUpClassID = '';
		if ($.browser == 'msie') {
			popUpClassID = ' classid="clsid:25336920-03F9-11CF-8FD0-00AA00686F13"';
		}

		var iframeObj = '<object' + popUpClassID + ' type="text/html" data='+Editurl+' style="width:860px; height:550px;" width="860" height="537"></object>';
		iframeObj = '<iframe src='+Editurl+' style="width:860px; height:550px;" width="860" height="537"></iframe>';
		$('div.innerMediaBrowserPopUp').append(iframeObj);
	}

	//]]>
</script>
<?php

		return ob_get_clean();
	}

	public function render($content)
	{
		$request = new Zend_Controller_Request_Http();
		$urlPath = parse_url($request->getRequestUri('modelListName'),PHP_URL_PATH) .'/';
		$element = $this->getElement();

		$urlPath = explode('/',$urlPath);
		$urlPathValues = '';
		$tableName = '';
		$id = '';

		foreach($urlPath as $key => $urlPathValues) {
			if($urlPathValues === 'modelListName') {
				$modelName = $urlPath[$key + 1];

				$modelInstance = new $modelName();
				$tableName = $modelInstance->getTable()->getTableName();
				$element->tableName = $tableName;
			}
			if($urlPathValues === 'id') {
				$id = $urlPath[$key + 1];
			}
		}

		if(empty($id) && !empty($tableName)) {
			$requestedParms = $request->getParams();
			if(isset($requestedParms['id']) && !empty($requestedParms['id'])){
				$id = $requestedParms['id'];
			} else {
				//Get Next Id of model
				$aiParams = array(L8M_Config::getOption('resources.multidb.default.dbname'), $tableName);
				$aiQuery = 'SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?';
				$statement = L8M_Db::getConnection()->prepare($aiQuery);
				$statement->execute($aiParams);
				$result = $statement->fetch();
				$id = $result[0];
			}
		} else
		if(empty($tableName)) {
			throw new L8M_Exception(L8M_Translate::string('Model name not found in request uri.', 'de'));
		}
		$element->modelNextId = $id;


		if (!$element instanceof L8M_JQuery_Form_Element_Media) {
			// wir wollen nur das Element
			return $content;
		}

		$view = $element->getView();
		if (!$view instanceof Zend_View_Interface) {
			// verwenden von View Helfers, deshalb ist nichts zu tun
			// wenn keine View vorhanden ist
			return $content;
		}


		$view = Zend_Layout::getMvcInstance()->getView();

		$view->headLink()
			->appendStylesheet('/js/jquery/plugins/tinymce/skins/lightgray/skin.min.css')
		;

		$markupDelLink = NULL;
		if (!$element->isMediaRequired()) {
			$markupDelLink = '
				<li class="picture-delete"><a class="mediabrowser" href="" onclick="del' . $element->getName() . 'func();return false;">' . $view->translate('Delete Media') . '</a></li>
			';
		}

		$markupDownloadCss = '';
		$markupFileNameCss = '';
		if (!$element->getMediaUrl()) {
			$markupDownloadCss = 'display:none;';
			$markupFileNameCss = ' no-file';
		}
		$markupEditImageCss = '';
		$pos = strpos($element->getName(),'media_image_id');

		if(!$element->getMediaID() || $pos === false){
			$markupEditImageCss = 'display:none;';
		}

		$markup = '<div class="mediaselect box">'
				. '<input type="text" name="' . $element->getName() . '" id="' . $element->getName() . '" value="' . $element->getMediaID() . '" class="hidden" />'
				. '<div class="img-container">'
				. '<div class="wraptocenter">'
				. '<span></span>'
				. $element->getMediaImage()
				. '</div>'
				. '</div>'
				. '<ul class="iconized">'
				. '<li class="picture-add"><a class="mediabrowser" href="" onclick="prepadd' . $element->getName() . 'func();return false;">' . $view->translate('Upload Media') . '</a></li>'
				. '<li class="picture-go"><a class="mediabrowser" href="" onclick="prep' . $element->getName() . 'func();return false;">' . $view->translate('Select Media') . '</a></li>'
				. '<li class="picture-edit" id="' . $element->getName() . 'EditImage" style="' . $markupEditImageCss . '"><a class="mediabrowser '.$element->getName().'EditImageClass" id="'.$element->getMediaID().'" href="" onclick="prepedit' . $element->getName() . 'func();return false;">' . $view->translate('Edit Media') . '</a></li>'
				. $markupDelLink
				. '<li class="picture-link" id="' . $element->getName() . 'LiDownload" style="' . $markupDownloadCss . '"><a class="mediabrowser external" id="' . $element->getName() . 'download" href="' . $element->getMediaUrl() . '">' . $view->translate('Download') . '</a></li>'
				. '</ul>'
				. '<div class="media-filename' . $markupFileNameCss . '" id="' . $element->getName() . 'fileName">'
				. $element->getMediaFilename()
				. '</div>'
				. '</div>'
				. $this->_renderJavaScript($element, $view)
				. $this->_renderCss();

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $markup . $this->getSeparator() . $content;
			case self::APPEND:
			default:
				return $content . $this->getSeparator() . $markup;
		}
	}
}