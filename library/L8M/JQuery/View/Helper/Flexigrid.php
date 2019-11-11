<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/View/Helper/Flexigrid.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Flexigrid.php 569 2018-07-18 11:29:59Z nm $
 */

/**
 *
 *
 * L8M_JQuery_View_Helper_Flexigrid
 *
 *
 */
class L8M_JQuery_View_Helper_Flexigrid extends L8M_JQuery_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Default options for a column.
	 *
	 * @var array
	 */
	protected $_columnOptions = array(
		'display'=>'Code',
		'name'=>'code',
		'width'=>100,
		'sortable'=>true,
		'searchable'=>true,
		'align'=>'left',
		'hide'=>false,
	);

	protected $_buttonUrls = array(
		'add'=>array('action'=>'create'),
		'edit'=>array('action'=>'edit'),
		'delete'=>array('action'=>'delete'),
	);

	protected $_extraButtonUrls = array();

	protected $_allowedButtonUsageInMultiSelectArray = array(
		'allowOneSelected',
		'disabled',
		'array',
	);

	/**
	 * how the button to use in multi-selction-mode (singelSelect=FALSE)
	 * - 'allowOneSelected' (default)
	 * - 'disabled'
	 * - 'array'
	 */
	protected $_buttonUsageInMultiSelect = array(
		'add'=>'allowOneSelected',
		'edit'=>'allowOneSelected',
		'delete'=>'array',
	);

	/**
	 * Default flexigrid options.
	 *
	 * @var array
	 */
	protected $_options = array(
		'url'=>NULL,
		'dataType'=>'json',
		'colModel'=>array(
		),
		'buttons'=>array(
			array(
				'name'=>'Add',
				'bclass'=>'add',
				'onpress'=>'function:flexAdd',
			),
			array(
				'name'=>'Edit',
				'bclass'=>'edit',
				'onpress'=>'function:flexEdit',
			),
			array(
				'separator'=>'true',
			),
			array(
				'name'=>'Delete',
				'bclass'=>'delete',
				'onpress'=>'function:flexDelete',
			),
			array(
				'separator'=>'true',
			)
		),
		'activateStandardButtonsFunctions'=>TRUE,
		'searchitems'=>array(),
		'pagestat'=>'Displaying {from} to {to} of {total} items',
		'nomsg'=>'No items',
		'errormsg'=>'Connection Error',
		'procmsg'=>'Processing, please wait ...',
		'pcontrol'=>'Page {current} of {total}',
		'sortname'=>NULL,
		'sortorder'=>NULL,
		'query'=>NULL,
		'qtype'=>NULL,
		'usepager'=>TRUE,
		'title'=>NULL,
		'useRp'=>TRUE,
		'rp'=>100,
		'rpOptions'=>array(10, 15, 20, 25, 50, 100, 250, 500, 1000),
		'page'=>1,
		'showTableToggleBtn'=>FALSE,
		'cssClassName'=>'',
		'width'=>'auto',
		'height'=>'auto',
		'resizable'=>TRUE,
		'singleSelect'=>FALSE,
		'colorbox'=>FALSE,
	);

	/**
	 * Contains TRUE when head script has been added to initialize all Flexigrid
	 * instances on a page.
	 *
	 * @var bool
	 */
	protected static $_flexigridInitialized = FALSE;

	/**
	 * Contains TRUE when head script has to be loaded with colorbox
	 *
	 * @var bool
	 */
	protected static $_activateStandardButtons = FALSE;

	/**
	 * Contains Colorbox
	 *
	 * @var bool
	 */
	protected static $_activateColorbox = FALSE;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Renders Flexigrid content.
	 *
	 * $options = array(
	 * @param  string $id
	 * @param  string $action
	 * @param  array  $options
	 * @return string
	 */
	public function flexigrid($id = NULL, $options = array(), $useFlexigridForImages = FALSE)
	{
		if ($id &&
			is_string($id) &&
			is_array($options) &&
			isset($options['columns']) &&
			is_array($options['columns']) &&
			count($options['columns']) > 0) {

			/**
			 * translate messages
			 */
			$this->_options['pagestat'] = L8M_Translate::string($this->_options['pagestat']);
			$this->_options['nomsg'] = L8M_Translate::string($this->_options['nomsg']);
			$this->_options['errormsg'] = L8M_Translate::string($this->_options['errormsg']);
			$this->_options['procmsg'] = L8M_Translate::string($this->_options['procmsg']);
			$this->_options['pcontrol'] = L8M_Translate::string($this->_options['pcontrol']);

			/**
			 * flexigridOptions
			 */
			$flexigridOptions = $this->_options;

			/**
			 * url to retrieve json data from
			 */
			$flexigridOptions['url'] = isset($options['url'])
									 ? $options['url']
									 : NULL
			;

			/**
			 * rp
			 */
			$flexigridOptions['rp'] = isset($options['rp'])
										? $options['rp']
										: $flexigridOptions['rp']
			;

			/**
			 * rp
			 */
			$flexigridOptions['rpOptions'] = isset($options['rpOptions'])
											? $options['rpOptions']
											: $flexigridOptions['rpOptions']
			;

			/**
			 * page
			 */
			$flexigridOptions['newp'] = isset($options['page'])
										? $options['page']
										: $flexigridOptions['page']
			;

			/**
			 * title
			 */
			$flexigridOptions['title'] = isset($options['title'])
									   ? $options['title']
									   : 'n/a'
			;

			/**
			 * css class name
			 */
			$flexigridOptions['cssClassName'] = isset($options['cssClassName'])
									   ? $options['cssClassName']
									   : $flexigridOptions['cssClassName']
			;

			/**
			 * width
			 */
			$flexigridOptions['width'] = isset($options['width'])
									   ? $options['width']
									   : $flexigridOptions['width']
			;

			/**
			 * height
			 */
			$flexigridOptions['height'] = isset($options['height'])
										? $options['height']
										: $flexigridOptions['height']
			;

			/**
			 * resizable
			 */
			$flexigridOptions['resizable'] = isset($options['resizable'])
										   ? $options['resizable']
										   : $flexigridOptions['resizable']
			;

			/**
			 * singleSelect
			 */
			$flexigridOptions['singleSelect'] = isset($options['singleSelect'])
											  ? $options['singleSelect']
											  : $flexigridOptions['singleSelect']
			;

			/**
			 * sortname
			 */
			$flexigridOptions['sortname'] = isset($options['sortname'])
										  ? $options['sortname']
										  : NULL
			;

			/**
			 * sortorder
			 */
			$flexigridOptions['sortorder'] = isset($options['sortorder'])
										   ? $options['sortorder']
										   : NULL
			;

			/**
			 * query to search
			 */
			$flexigridOptions['query'] = isset($options['query'])
									   ? $options['query']
									   : NULL
			;

			/**
			 * query type - it's the coleModel
			 */
			$flexigridOptions['qtype'] = isset($options['qtype'])
									   ? $options['qtype']
									   : NULL
			;

			/**
			 * sortname
			 */
			$flexigridOptions['colorbox'] = isset($options['colorbox'])
										  ? $options['colorbox']
										  : FALSE
			;
			if ($flexigridOptions['colorbox'] == TRUE) {
				self::$_activateColorbox = TRUE;
			}

			/**
			 * buttons
			 */
			self::$_activateStandardButtons = false;
			if (isset($options['buttons']) &&
				$options['buttons'] === true) {

				/**
				 * activate standard buttons
				 */
				self::$_activateStandardButtons = true;
			} else {
				if (isset($options['buttons']) &&
					is_array($options['buttons'])) {

					$flexigridOptions['buttons'] = array();

					/**
					 * activate buttons via array
					 */
					foreach ($options['buttons'] as $button) {
						if (array_key_exists('bclass', $button) &&
							array_key_exists('name', $button) &&
							array_key_exists('onpress', $button)) {

							$tempButton = array(
								'bclass'=>$button['bclass'],
								'name'=>$button['name'],
								'onpress'=>$button['onpress'],
							);
							if (array_key_exists('url', $button)) {
								if ($button['onpress'] == 'function:flexAdd') {
									$this->_buttonUrls['add'] = $button['url'];
								} else
								if ($button['onpress'] == 'function:flexEdit') {
									$this->_buttonUrls['edit'] = $button['url'];
								} else
								if ($button['onpress'] == 'function:flexDelete') {
									$this->_buttonUrls['delete'] = $button['url'];
								}
								if (substr($button['onpress'], 0, strlen('flexPress'))) {
									$this->_extraButtonUrls[$button['onpress']]['url'] = $button['url'];
									if (!isset($button['needSelectedRow'])) {
										$button['needSelectedRow'] = TRUE;
									}
									$this->_extraButtonUrls[$button['onpress']]['needSelectedRow'] = $button['needSelectedRow'];
									if (!isset($button['useMultiSelect'])) {
										$button['useMultiSelect'] = FALSE;
									}
									if ($button['onpress'] != 'flexPressDownloadMedia') {
										$this->_extraButtonUrls[$button['onpress']]['useMultiSelect'] = $button['useMultiSelect'];
									}
									$tempButton['onpress'] = 'function:' . $button['onpress'];
								}
							}
							if (array_key_exists('usageInMultiSelect', $button)) {
								if ($button['onpress'] == 'function:flexAdd') {
									$this->_buttonUsageInMultiSelect['add'] = $button['usageInMultiSelect'];
								} else
								if ($button['onpress'] == 'function:flexEdit') {
									$this->_buttonUsageInMultiSelect['edit'] = $button['usageInMultiSelect'];
								} else
								if ($button['onpress'] == 'function:flexDelete') {
									$this->_buttonUsageInMultiSelect['delete'] = $button['usageInMultiSelect'];
								}
							}
						} else {
							$tempButton = $button;
						}
						$flexigridOptions['buttons'][] = $tempButton;
					}
//					$flexigridOptions['buttons'] = $options['buttons'];

					if (isset($options['activateStandardButtonsFunctions']) &&
						$options['activateStandardButtonsFunctions'] === true) {

						/**
						 * activate standard buttons
						 */
						self::$_activateStandardButtons = true;
					}
				} else {

					/**
					 * do not add buttons
					 */
					$flexigridOptions['buttons'] = NULL;
				}
			}

			/**
			 * columnOptions
			 */
			$columnOptions = $options['columns'];
			foreach($columnOptions as $columnOption) {

				/**
				 * columnOption
				 */
				$columnOption = array_merge($this->_columnOptions, $columnOption);

				/**
				 * searchable
				 */
				if (isset($columnOption['searchable']) &&
					$columnOption['searchable']) {
					$flexigridOptions['searchitems'][] = array(
						'display'=>$columnOption['display'],
						'name'=>$columnOption['name'],
					);
					unset($columnOption['searchable']);
				}

				/**
				 * column to display
				 */
				$flexigridOptions['colModel'][] = $columnOption;
			}

			$flexigridOptionsJson = L8M_Json_Encoder::encode($flexigridOptions);

			/**
			 * render headscript
			 */
			$this->_renderHeadScript();

			/**
			 * add headscript
			 */
			$this->view->headScript()->captureStart();

			/**
			 * do we have some extra buttons?
			 */
			/**
			 * do we need the standard functions for our standard buttons?
			 */
			if (count($this->_extraButtonUrls) > 0) {
				if ($flexigridOptions['colorbox']) {
					$ajaxLinkArray = array(
						'format'=>'html',
					);
				} else {
					$ajaxLinkArray = array();
				}
				if (!isset($options['urlArray'])){
					$options['urlArray'] = array();
				}

				foreach ($this->_extraButtonUrls as $functionName => $extraButtonUrlOption) {
					if ($functionName != 'flexPressDownloadMedia') {
						$extraButtonLink = $this->view->url(array_merge($options['leadThroughUrl'], $ajaxLinkArray, $extraButtonUrlOption['url']), NULL, TRUE);
						if ($flexigridOptions['colorbox']) {
							echo $this->_renderJavaScriptButtonFunctionColorBox($id, $extraButtonLink, $flexigridOptions, $functionName, $extraButtonUrlOption);
						} else {
							echo $this->_renderJavaScriptButtonFunction($id, $extraButtonLink, $flexigridOptions, $functionName, $extraButtonUrlOption);
						}
					}
				}
			}

			/**
			 * do we need the standard functions for our standard buttons?
			 */
			if (self::$_activateStandardButtons) {
				if ($flexigridOptions['colorbox']) {
					$ajaxLinkArray = array(
						'format'=>'html',
					);
				} else {
					$ajaxLinkArray = array();
				}
				if (!isset($options['urlArray'])){
					$options['urlArray'] = array();
				}
				$linkAdd = $this->view->url(array_merge($options['leadThroughUrl'], $ajaxLinkArray, $this->_buttonUrls['add']), NULL, TRUE);
				$linkEdit = $this->view->url(array_merge($options['leadThroughUrl'], $ajaxLinkArray, $this->_buttonUrls['edit']), NULL, TRUE);
				$linkDelete = $this->view->url(array_merge($options['leadThroughUrl'], $ajaxLinkArray, $this->_buttonUrls['delete']), NULL, TRUE);

				if ($flexigridOptions['colorbox']) {
					echo $this->_renderJavaScriptButtonAddColorBox($id, $linkAdd, $flexigridOptions);
					echo $this->_renderJavaScriptButtonEditColorBox($id, $linkEdit, $flexigridOptions);
					echo $this->_renderJavaScriptButtonDeleteColorBox($id, $linkDelete, $flexigridOptions);
				} else {
					echo $this->_renderJavaScriptButtonAdd($id, $linkAdd, $flexigridOptions);
					echo $this->_renderJavaScriptButtonEdit($id, $linkEdit, $flexigridOptions);
					echo $this->_renderJavaScriptButtonDelete($id, $linkDelete, $flexigridOptions);
				}
			}

?>

////////////////////////////////////////
// add media-download-function on load
////////////////////////////////////////

function flexPressDownloadMedia(btnName, grid) {
	var selectedGridRows = $('.trSelected', grid);
	if (selectedGridRows.length == 1) {
		flexPressDownloadMediaGoOn(btnName, grid);
	} else {
		$("#flexigrid-dialog-select-one").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	}
}

function flexPressDownloadMediaGoOn(btnName, grid) {
	var selectedGridRows = $('.trSelected', grid);

	if (selectedGridRows.length == 1) {
		var id = selectedGridRows.attr('id');

		// substring cause id of each row is 'row1...' cut row off...
		id = id.substring(3);
		window.open('/media/' + id);
	}
}

////////////////////////////////////////
// add onload-function on load
////////////////////////////////////////

$(document).ready(function () {
	$("#<?php echo $id; ?>").flexigrid(<?php echo $flexigridOptionsJson; ?>);
});

<?php

			$this->view->headScript()->captureEnd();

			if ($useFlexigridForImages) {
				$cssClass = 'preview-image-rows';
			} else {
				$cssClass = NULL;
			}
			ob_start();
?>
<div id="<?php echo $id; ?>" class="<?php echo $cssClass; ?>"></div>
<div id="flexigrid-dialog-confirm-one" title="<?php echo $this->view->translate('Action'); ?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php echo $this->view->translate('This will affect one record. Are you sure?'); ?></p>
</div>
<div id="flexigrid-dialog-confirm-more" title="<?php echo $this->view->translate('Action'); ?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php echo $this->view->translate('This will affect some records. Are you sure?'); ?></p>
</div>
<div id="flexigrid-dialog-confirm-one-delete" title="<?php echo $this->view->translate('Delete'); ?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php echo $this->view->translate('This record will be permanently deleted and cannot be recovered. Are you sure?'); ?></p>
</div>
<div id="flexigrid-dialog-confirm-more-delete" title="<?php echo $this->view->translate('Delete'); ?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php echo $this->view->translate('These records will be permanently deleted and cannot be recovered. Are you sure?'); ?></p>
</div>
<div id="flexigrid-dialog-do-not-select" title="<?php echo $this->view->translate('Selection'); ?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php echo $this->view->translate('It\'s not allowed to select an item, when using this function.'); ?></p>
</div>
<div id="flexigrid-dialog-select-one" title="<?php echo $this->view->translate('Selection'); ?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php echo $this->view->translate('You need to select one item, when using this function.'); ?></p>
</div>
<div id="flexigrid-dialog-select-more" title="<?php echo $this->view->translate('Selection'); ?>" style="display:none;">
	<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php echo $this->view->translate('You need to select one or more items, when using this function.'); ?></p>
</div>
<?php
			return ob_get_clean();
		}
		return NULL;
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Renders head script.
	 *
	 * @return L8M_JQuery_View_Helper_Flexigrid
	 */
	protected function _renderHeadScript()
	{
		if (self::$_flexigridInitialized === FALSE) {

			/**
			 * add javascript and css for flexigrid
			 */
			$this->view->jQuery()->addJavascriptFile(self::$_pluginPath . 'flexigrid/flexigrid.js');

			/**
			 * add headscript
			 */
			$this->view->headScript()->captureStart();

?>
function flexigridEditRecordColumn(element) {
	var linkId = element.id.split("_");
	var recordID = linkId[linkId.length - 1];
	var columnName = '';
	if (linkId.length >= 3) {
		for (i = 1; i < linkId.length - 1; i++) {
			if (columnName != '') {
				columnName = columnName + '_';
			}
			columnName = columnName + linkId[i];
		}
		$.get(element.href, function(data){
			$('span.flexigrid-switch_' + columnName + '_' + recordID).html(data);
		});
	} else {
		alert('Something went wrong!');
	}

	return false;
}
<?php

			$this->view->headScript()->captureEnd();

			/**
			 * add css
			 */
			$this->view->headLink()
				->appendStylesheet('/css/screen/js/flexigrid.css', 'screen')
				->appendStylesheet('/css/screen/js/flexigrid.fix.css', 'screen')
			;
			self::$_flexigridInitialized = TRUE;

			/**
			 * if we do have standard-buttons
			 * add colorbox as lightbox javascript and css
			 */
			if (self::$_activateStandardButtons &&
				self::$_activateColorbox) {
				$this->view->colorbox();
			}
		}
		return $this;
	}

	private function _renderJavaScriptButtonAdd($id, $linkAdd, $flexigridOptions)
	{

?>

////////////////////////////////////////
// add add-function on load
////////////////////////////////////////

function flexAdd(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);
	if (selectedGridRows.length == 0) {
		flexAddGoOn(btnName, grid);
	} else {
		$("#flexigrid-dialog-do-not-select").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	}
}

function flexAddGoOn(btnName, grid)
{
	var page = $("#<?php echo $id; ?>").flexGetCurrentPage();
	var rp = $("#<?php echo $id; ?>").flexGetResultsPerPage();
	var qtype = $("#<?php echo $id; ?>").flexGetSearchQueryType();
	var query = $("#<?php echo $id; ?>").flexGetSearchQuery();
	var sortorder = $("#<?php echo $id; ?>").flexGetSortOrder();
	var sortname = $("#<?php echo $id; ?>").flexGetSortName();

	var partUrl = '';
	var keys = $("#<?php echo $id; ?>").flexGetCustomParamKeys();

	for (var i = 0; i < keys.length; i++) {
		partUrl = partUrl + '&' + keys[i] + '=' + $("#<?php echo $id; ?>").flexGetCustomParam(keys[i]);
	}

	window.location.href = '<?php echo $linkAdd; ?>?page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl;
}
<?php

	}

	private function _renderJavaScriptButtonAddColorBox($id, $linkAdd, $flexigridOptions)
	{

?>

////////////////////////////////////////
// add add-function on load
////////////////////////////////////////

function flexAdd(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);
	if (selectedGridRows.length == 0) {
		flexAddGoOn(btnName, grid);
	} else {
		$("#flexigrid-dialog-do-not-select").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	}
}

function flexAddGoOn(btnName, grid)
{
	var page = $("#<?php echo $id; ?>").flexGetCurrentPage();
	var rp = $("#<?php echo $id; ?>").flexGetResultsPerPage();
	var qtype = $("#<?php echo $id; ?>").flexGetSearchQueryType();
	var query = $("#<?php echo $id; ?>").flexGetSearchQuery();
	var sortorder = $("#<?php echo $id; ?>").flexGetSortOrder();
	var sortname = $("#<?php echo $id; ?>").flexGetSortName();

	var partUrl = '';
	var keys = $("#<?php echo $id; ?>").flexGetCustomParamKeys();

	for (var i = 0; i < keys.length; i++) {
		partUrl = partUrl + '&' + keys[i] + '=' + $("#<?php echo $id; ?>").flexGetCustomParam(keys[i]);
	}

	jQuery.fn.colorbox({href:'<?php echo $linkAdd; ?>?page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl, open:true});
}
<?php

	}

	private function _renderJavaScriptButtonEdit($id, $linkEdit, $flexigridOptions)
	{

?>

////////////////////////////////////////
// add edit-function on load
////////////////////////////////////////


function flexEdit(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);
	if (selectedGridRows.length == 1) {
		flexEditGoOn(btnName, grid);
	} else {
		$("#flexigrid-dialog-select-one").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	}
}

function flexEditGoOn(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);

	var page = $("#<?php echo $id; ?>").flexGetCurrentPage();
	var rp = $("#<?php echo $id; ?>").flexGetResultsPerPage();
	var qtype = $("#<?php echo $id; ?>").flexGetSearchQueryType();
	var query = $("#<?php echo $id; ?>").flexGetSearchQuery();
	var sortorder = $("#<?php echo $id; ?>").flexGetSortOrder();
	var sortname = $("#<?php echo $id; ?>").flexGetSortName();

	var partUrl = '';
	var keys = $("#<?php echo $id; ?>").flexGetCustomParamKeys();

	for (var i = 0; i < keys.length; i++) {
		partUrl = partUrl + '&' + keys[i] + '=' + $("#<?php echo $id; ?>").flexGetCustomParam(keys[i]);
	}

	if (selectedGridRows.length == 1) {
		var id = selectedGridRows.attr('id');

		// substring cause id of each row is 'row1...' cut row off...
		id = id.substring(3);
		window.location.href = '<?php echo $linkEdit; ?>?id=' + id + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl;
	}
}
<?php

	}

	private function _renderJavaScriptButtonEditColorBox($id, $linkEdit, $flexigridOptions)
	{

?>

////////////////////////////////////////
// add edit-function on load
////////////////////////////////////////

function flexEdit(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);
	if (selectedGridRows.length == 1) {
		flexEditGoOn(btnName, grid);
	} else {
		$("#flexigrid-dialog-select-one").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	}
}

function flexEditGoOn(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);

	var page = $("#<?php echo $id; ?>").flexGetCurrentPage();
	var rp = $("#<?php echo $id; ?>").flexGetResultsPerPage();
	var qtype = $("#<?php echo $id; ?>").flexGetSearchQueryType();
	var query = $("#<?php echo $id; ?>").flexGetSearchQuery();
	var sortorder = $("#<?php echo $id; ?>").flexGetSortOrder();
	var sortname = $("#<?php echo $id; ?>").flexGetSortName();

	var partUrl = '';
	var keys = $("#<?php echo $id; ?>").flexGetCustomParamKeys();

	for (var i = 0; i < keys.length; i++) {
		partUrl = partUrl + '&' + keys[i] + '=' + $("#<?php echo $id; ?>").flexGetCustomParam(keys[i]);
	}

	if (selectedGridRows.length == 1) {
		var id = selectedGridRows.attr('id');

		// substring cause id of each row is 'row1...' cut row off...
		id = id.substring(3);
		jQuery.fn.colorbox({href:'<?php echo $linkEdit; ?>?id=' + id + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl, open:true});
	}
}
<?php

	}

	private function _renderJavaScriptButtonDelete($id, $linkDelete, $flexigridOptions)
	{

?>

////////////////////////////////////////
// add delete-function on load
////////////////////////////////////////

function flexDelete(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);
	var dialogSelector = 'zero';
	if (selectedGridRows.length == 1) {
		dialogSelector = 'one-delete';
	} else
	if (selectedGridRows.length > 1) {
		dialogSelector = 'more-delete';
	}

	if (selectedGridRows.length == 0) {
		$("#flexigrid-dialog-select-more").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	} else {
		$("#flexigrid-dialog-confirm-" + dialogSelector).dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Cancel'); ?>": function() {
					$(this).dialog("close");
				},
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
					flexDeleteGoOn(btnName, grid);
				}
			}
		});
	}
}

function flexDeleteGoOn(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);

	var page = $("#<?php echo $id; ?>").flexGetCurrentPage();
	var rp = $("#<?php echo $id; ?>").flexGetResultsPerPage();
	var qtype = $("#<?php echo $id; ?>").flexGetSearchQueryType();
	var query = $("#<?php echo $id; ?>").flexGetSearchQuery();
	var sortorder = $("#<?php echo $id; ?>").flexGetSortOrder();
	var sortname = $("#<?php echo $id; ?>").flexGetSortName();

	var partUrl = '';
	var keys = $("#<?php echo $id; ?>").flexGetCustomParamKeys();

	for (var i = 0; i < keys.length; i++) {
		partUrl = partUrl + '&' + keys[i] + '=' + $("#<?php echo $id; ?>").flexGetCustomParam(keys[i]);
	}

<?php

		if ($flexigridOptions['singleSelect'] == FALSE &&
			$this->_buttonUsageInMultiSelect['delete'] == 'disabled') {

?>
	return;
<?php

		} else
		if ($flexigridOptions['singleSelect'] == FALSE &&
			$this->_buttonUsageInMultiSelect['delete'] == 'allowOneSelected') {

?>
	if (selectedGridRows.length == 1) {
		selectedGridRows.each(function() {
			var id = $(this).attr('id');

			// substring cause id of each row is 'row1...' cut row off...
			id = id.substring(3);
			window.location.href = '<?php echo $linkDelete; ?>?id=' + id + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl;
		});
	}
<?php

		} else
		if ($flexigridOptions['singleSelect'] == FALSE &&
			$this->_buttonUsageInMultiSelect['delete'] == 'array') {

?>
	var strIds = '';
	selectedGridRows.each(function(i) {
		var id = $(this).attr('id');

		// substring cause id of each row is 'row1...' cut row off...
		if (i == 0) {
			strIds = strIds + 'ids[]=' + id.substring(3);
		} else {
			strIds = strIds + '&' + 'ids[]=' + id.substring(3);
		}
	});
	window.location.href = '<?php echo $linkDelete; ?>?' + strIds + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl;
<?php

				} else {

?>
	selectedGridRows.each(function() {
		var id = $(this).attr('id');

		// substring cause id of each row is 'row1...' cut row off...
		id = id.substring(3);
		window.location.href = '<?php echo $linkDelete; ?>?id=' + id + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl;
	});
<?php

				}

?>
}
<?php

	}

	private function _renderJavaScriptButtonDeleteColorBox($id, $linkDelete, $flexigridOptions)
	{

?>

////////////////////////////////////////
// add delete-function on load
////////////////////////////////////////

function flexDelete(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);
	var dialogSelector = 'zero';
	if (selectedGridRows.length == 1) {
		dialogSelector = 'one';
	} else
	if (selectedGridRows.length > 1) {
		dialogSelector = 'more';
	}

	if (selectedGridRows.length == 0) {
		$("#flexigrid-dialog-select-more").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	} else {
		$("#flexigrid-dialog-confirm-" + dialogSelector).dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Cancel'); ?>": function() {
					$(this).dialog("close");
				},
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
					flexDeleteGoOn(btnName, grid);
				}
			}
		});
	}
}

function flexDeleteGoOn(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);

	var page = $("#<?php echo $id; ?>").flexGetCurrentPage();
	var rp = $("#<?php echo $id; ?>").flexGetResultsPerPage();
	var qtype = $("#<?php echo $id; ?>").flexGetSearchQueryType();
	var query = $("#<?php echo $id; ?>").flexGetSearchQuery();
	var sortorder = $("#<?php echo $id; ?>").flexGetSortOrder();
	var sortname = $("#<?php echo $id; ?>").flexGetSortName();

	var partUrl = '';
	var keys = $("#<?php echo $id; ?>").flexGetCustomParamKeys();

	for (var i = 0; i < keys.length; i++) {
		partUrl = partUrl + '&' + keys[i] + '=' + $("#<?php echo $id; ?>").flexGetCustomParam(keys[i]);
	}

<?php

		if ($flexigridOptions['singleSelect'] == FALSE &&
			$this->_buttonUsageInMultiSelect['delete'] == 'disabled') {

?>
	return;
<?php

		} else
		if ($flexigridOptions['singleSelect'] == FALSE &&
			$this->_buttonUsageInMultiSelect['delete'] == 'allowOneSelected') {

?>
	if (selectedGridRows.length == 1) {
		selectedGridRows.each(function() {
			var id = $(this).attr('id');

			// substring cause id of each row is 'row1...' cut row off...
			id = id.substring(3);
			jQuery.fn.colorbox({href:'<?php echo $linkDelete; ?>?id=' + id + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl, open:true});
		});
	}
<?php

		} else
		if ($flexigridOptions['singleSelect'] == FALSE &&
			$this->_buttonUsageInMultiSelect['delete'] == 'array') {

?>
	var strIds = '';
	selectedGridRows.each(function(i) {
		var id = $(this).attr('id');

		// substring cause id of each row is 'row1...' cut row off...
		if (i == 0) {
			strIds = strIds + 'ids[]=' + id.substring(3);
		} else {
			strIds = strIds + '&' + 'ids[]=' + id.substring(3);
		}
	});
	jQuery.fn.colorbox({href:'<?php echo $linkDelete; ?>?' + strIds + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl, open:true});
<?php

		} else {

?>
	selectedGridRows.each(function() {
		var id = $(this).attr('id');

		// substring cause id of each row is 'row1...' cut row off...
		id = id.substring(3);
		jQuery.fn.colorbox({href:'<?php echo $linkDelete; ?>?id=' + id + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl, open:true});
	});
<?php

		}

?>
}
<?php

	}

	private function _renderJavaScriptButtonFunction($id, $extraButtonLink, $flexigridOptions, $functionName, $extraButtonUrlOption)
	{

?>

////////////////////////////////////////
// add <?php echo $functionName; ?>-function on load
////////////////////////////////////////

function <?php echo $functionName; ?>(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);
<?php

		if ($extraButtonUrlOption['needSelectedRow']) {
			if ($extraButtonUrlOption['useMultiSelect']) {

?>
	var dialogSelector = 'zero';
	if (selectedGridRows.length == 1) {
		dialogSelector = 'one';
	} else
	if (selectedGridRows.length > 1) {
		dialogSelector = 'more';
	}

	if (selectedGridRows.length == 0) {
		$("#flexigrid-dialog-select-more").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	} else {
		$("#flexigrid-dialog-confirm-" + dialogSelector).dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Cancel'); ?>": function() {
					$(this).dialog("close");
				},
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
					<?php echo $functionName; ?>GoOn(btnName, grid);
				}
			}
		});
	}
<?php

			} else {

?>
	if (selectedGridRows.length == 1) {
		<?php echo $functionName; ?>GoOn(btnName, grid);
	} else {
		$("#flexigrid-dialog-select-one").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	}
<?php

			}
		} else {

?>
	if (selectedGridRows.length == 0) {
		<?php echo $functionName; ?>GoOn(btnName, grid);
	} else {
		$("#flexigrid-dialog-do-not-select").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	}
<?php

		}

?>

}

function <?php echo $functionName; ?>GoOn(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);

	var page = $("#<?php echo $id; ?>").flexGetCurrentPage();
	var rp = $("#<?php echo $id; ?>").flexGetResultsPerPage();
	var qtype = $("#<?php echo $id; ?>").flexGetSearchQueryType();
	var query = $("#<?php echo $id; ?>").flexGetSearchQuery();
	var sortorder = $("#<?php echo $id; ?>").flexGetSortOrder();
	var sortname = $("#<?php echo $id; ?>").flexGetSortName();

	var partUrl = '';
	var keys = $("#<?php echo $id; ?>").flexGetCustomParamKeys();

	for (var i = 0; i < keys.length; i++) {
		partUrl = partUrl + '&' + keys[i] + '=' + $("#<?php echo $id; ?>").flexGetCustomParam(keys[i]);
	}

	if (selectedGridRows.length == 1) {
		var id = selectedGridRows.attr('id');

		// substring cause id of each row is 'row1...' cut row off...
		id = id.substring(3);
		window.location.href = '<?php echo $extraButtonLink; ?>?id=' + id + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl;
	} else
	if (selectedGridRows.length > 1) {
		var strIds = '';
		selectedGridRows.each(function(i) {
			var id = $(this).attr('id');

			// substring cause id of each row is 'row1...' cut row off...
			if (i == 0) {
				strIds = strIds + 'ids[]=' + id.substring(3);
			} else {
				strIds = strIds + '&' + 'ids[]=' + id.substring(3);
			}
		});
		window.location.href = '<?php echo $extraButtonLink; ?>?' + strIds + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl;
	} else {
		window.location.href = '<?php echo $extraButtonLink; ?>?' + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl;
	}
}
<?php

	}

	private function _renderJavaScriptButtonFunctionColorBox($id, $extraButtonLink, $flexigridOptions, $functionName, $extraButtonUrlOption)
	{

?>

////////////////////////////////////////
// add <?php echo $functionName; ?>-function on load
////////////////////////////////////////

function <?php echo $functionName; ?>(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);
<?php

		if ($extraButtonUrlOption['needSelectedRow']) {
			if ($extraButtonUrlOption['useMultiSelect']) {

?>
	var dialogSelector = 'zero';
	if (selectedGridRows.length == 1) {
		dialogSelector = 'one';
	} else
	if (selectedGridRows.length > 1) {
		dialogSelector = 'more';
	}

	if (selectedGridRows.length == 0) {
		$("#flexigrid-dialog-select-more").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	} else {
		$("#flexigrid-dialog-confirm-" + dialogSelector).dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Cancel'); ?>": function() {
					$(this).dialog("close");
				},
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
					<?php echo $functionName; ?>GoOn(btnName, grid);
				}
			}
		});
	}
<?php

			} else {

?>
	if (selectedGridRows.length == 1) {
		<?php echo $functionName; ?>GoOn(btnName, grid);
	} else {
		$("#flexigrid-dialog-select-one").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	}
<?php

			}
		} else {

?>
	if (selectedGridRows.length == 0) {
		<?php echo $functionName; ?>GoOn(btnName, grid);
	} else {
		$("#flexigrid-dialog-do-not-select").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
				}
			}
		});
	}
<?php

		}

?>

}

function <?php echo $functionName; ?>GoOn(btnName, grid)
{
	var selectedGridRows = $('.trSelected', grid);

	var page = $("#<?php echo $id; ?>").flexGetCurrentPage();
	var rp = $("#<?php echo $id; ?>").flexGetResultsPerPage();
	var qtype = $("#<?php echo $id; ?>").flexGetSearchQueryType();
	var query = $("#<?php echo $id; ?>").flexGetSearchQuery();
	var sortorder = $("#<?php echo $id; ?>").flexGetSortOrder();
	var sortname = $("#<?php echo $id; ?>").flexGetSortName();

	var partUrl = '';
	var keys = $("#<?php echo $id; ?>").flexGetCustomParamKeys();

	for (var i = 0; i < keys.length; i++) {
		partUrl = partUrl + '&' + keys[i] + '=' + $("#<?php echo $id; ?>").flexGetCustomParam(keys[i]);
	}

	if (selectedGridRows.length == 1) {
		var id = selectedGridRows.attr('id');

		// substring cause id of each row is 'row1...' cut row off...
		id = id.substring(3);
		jQuery.fn.colorbox({href:'<?php echo $extraButtonLink; ?>?id=' + id + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl, open:true});
	} else
	if (selectedGridRows.length > 1) {
		var strIds = '';
		selectedGridRows.each(function(i) {
			var id = $(this).attr('id');

			// substring cause id of each row is 'row1...' cut row off...
			if (i == 0) {
				strIds = strIds + 'ids[]=' + id.substring(3);
			} else {
				strIds = strIds + '&' + 'ids[]=' + id.substring(3);
			}
		});
		jQuery.fn.colorbox({href:'<?php echo $extraButtonLink; ?>?' + strIds + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl, open:true});
	} else {
		jQuery.fn.colorbox({href:'<?php echo $extraButtonLink; ?>?' + '&page=' + page + '&rp=' + rp + '&query=' + query + '&qtype=' + qtype + '&sortorder=' + sortorder + '&sortname=' + sortname + partUrl, open:true});
	}
}
<?php

	}
}