<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/views/helpers/EditImage.php
 * @author     Nimisha Vyas <nimisha.vyas@bcssarl.com>
 * @version    $Id: EditImage.php 280 2019-04-02 11:18:09Z dp $
 */

/**
 *
 *
 * System_View_Helper_EditImage
 *
 *
 */
class System_View_Helper_EditImage extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Variables
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

	/**
	 * Returns custom html
	 * @param
	 *
	 * @return string
	 */
    public function editImage()
    {
		$this->view->headLink()
			->appendStylesheet('/frameworks/bootstrap/css/bootstrap.css', 'all')
			->appendStylesheet('/css/default/custom.css', 'all')
			->appendStylesheet('https://cdnjs.cloudflare.com/ajax/libs/rangeslider.js/2.3.2/rangeslider.css', 'all')
		;

		$this->view->headScript()
			->appendFile('/frameworks/bootstrap/js/bootstrap.min.js', 'text/javascript')
			->appendFile('/js/jquery/plugins/perfect-scrollbar/min/perfect-scrollbar.min.js', 'text/javascript')
			->appendFile('/js/jquery/plugins/fabric/fabric.2.4.3.min.js', 'text/javascript')
			->appendFile('https://cdnjs.cloudflare.com/ajax/libs/rangeslider.js/2.3.2/rangeslider.js', 'text/javascript')
		;

		$this->view->headStyle()->captureStart();
?>

#imageEditorContainer {
	margin: 0 auto;
	background : #000;
}
.canvas-container {
	margin: 0 auto;
}
#canvasContainerDiv {
	height: 350px;
}
#imageEditorCanvas{
	position: absolute;
	left: 50%;
	top: 50%;
	touch-action: none;
	user-select: none;
	border: 1px solid #000;
	background: #000;
}

#editToolTypes {
	height: 50px;
	background : #000;
	border-top: 0.5px solid #fff;
	display: flex;
	flex-wrap: wrap;
}

.editorToolTypeSelector {
	width: 30px;
	flex-direction: column;
	position: relative;
	cursor: pointer;
	box-sizing: border-box;
}
.editorToolTypeSelector * {
	opacity: 0.5;
}
.editorToolTypeSelector i {
	line-height: 29px;
	text-align: center;
	color: #fff;
	font-size: 15px;
}
.editorToolTypeSelector:hover * {
	opacity: 1;
}
.editorToolTypeSelector:hover i {
	font-size: 18px;
}
.editorToolType_active * {
	opacity: 1;
}
.editorToolType_active i {
	font-size: 18px;
}
.editorToolType_blocked:hover * {
	opacity: 0.5 !important;
}
.editorToolType_blocked:hover i {
	font-size: 15px !important;
}
.dummyForCornerBorder {
	position: absolute;
	height: 8px;
	width: 8px;
}
.dummyForCornerBorder.dummyBoxTL {
	top: 0px;
	left: 0px;
	border-top: 0.5px solid #fff;
	border-left: 0.5px solid #fff;
}
.dummyForCornerBorder.dummyBoxTR {
	top: 0px;
	right: 0px;
	border-top: 0.5px solid #fff;
	border-right: 0.5px solid #fff;
}
.dummyForCornerBorder.dummyBoxBR {
	bottom: 0px;
	right: 0px;
	border-bottom: 0.5px solid #fff;
	border-right: 0.5px solid #fff;
}
.dummyForCornerBorder.dummyBoxBL {
	bottom: 0px;
	left: 0px;
	border-bottom: 0.5px solid #fff;
	border-left: 0.5px solid #fff;
}
.rightAlignStart {
	margin-left: auto;
}

#editToolsContainer {
	height: auto;
	background : #000;
	border-top: 0.5px solid #fff;
	position: relative;
}
.editOptionsBar {
	border-bottom: 0.5px solid rgba(255, 255, 255, 0.5);
	display: none;
	overflow-x: hidden;
}
.editOption {
	box-sizing: border-box;
	display: none;
}

#editOptionsBar_Basic {
	height: 31px;
}
.editOptionType_Basic {
	height: 30px;
	width: 30px;
	opacity: 0.5;
	border-left: 0.5px solid rgba(255, 255, 255, 0.5);
	border-right: 0.5px solid rgba(255, 255, 255, 0.5);
}
.editOptionType_Basic i {
	line-height: 30px;
	text-align: center;
	color: #fff;
	font-size: 12px;
}
.editOptionType_Basic:hover {
	opacity: 1;
	cursor: pointer;
	border: 0.5px solid #fff;
	border-top: none;
}
.editOptionType_Basic:hover i {
	font-size: 15px;
}
.editTool_Basic_active {
	display: block;
}
.editTool_Basic_active #editOptionsBar_Basic {
	display: flex;
}
.editTool_Basic_active #editOptionsBar_Basic .editOptionType_Basic {
	display: block;
}

#editOptionsBar_Adjust {
	height: 31px;
}
.editOptionType_Adjust {
	height: 30px;
	width: 30px;
	opacity: 0.5;
	border-left: 0.5px solid rgba(255, 255, 255, 0.5);
	border-right: 0.5px solid rgba(255, 255, 255, 0.5);
}
.editOptionType_Adjust i {
	line-height: 30px;
	text-align: center;
	color: #fff;
	font-size: 12px;
}
.editOptionType_Adjust:hover {
	opacity: 1;
	cursor: pointer;
	border: 0.5px solid #fff;
	border-top: none;
}
.editOptionType_Adjust:hover i {
	font-size: 15px;
}
.editTool_Adjust_active {
	display: block;
}
.editTool_Adjust_active #editOptionsBar_Adjust {
	display: flex;
}
.editTool_Adjust_active #editOptionsBar_Adjust .editOptionType_Adjust {
	display: block;
}

#editOptionsBar_Effect {
	height: 31px;
}
.editOptionType_Effect {
	height: 30px;
	opacity: 0.5;
	border-left: 0.5px solid rgba(255, 255, 255, 0.5);
	border-right: 0.5px solid rgba(255, 255, 255, 0.5);
}
.editOptionType_Effect span.editOptionNameSpan {
	line-height: 30px;
	text-align: center;
	color: #fff;
	font-size: 12px;
	padding: 0px 10px;
	white-space: nowrap;
}
.editOptionType_Effect:hover {
	opacity: 1;
	cursor: pointer;
	border: 0.5px solid #fff;
	border-top: none;
}
.editTool_Effect_active {
	display: block;
}
.editTool_Effect_active #editOptionsBar_Effect {
	display: flex;
}
.editTool_Effect_active #editOptionsBar_Effect .editOptionType_Effect {
	display: block;
}

#editOptionsBar_Filter {
	height: 137px;
	flex-wrap: wrap;
	flex-direction: column;
}
.editOptionType_Filter {
	height: 137px;
	width: 120px;
	opacity: 0.5;
	border-left: 0.5px solid rgba(255, 255, 255, 0.5);
	border-right: 0.5px solid rgba(255, 255, 255, 0.5);
	flex-wrap: wrap;
	position: relative;
}
.editOptionType_Filter span.editOptionNameSpan {
	height: 30px;
	line-height: 30px;
	width: 100%;
	text-align: center;
	color: #fff;
	font-size: 12px;
	padding: 0px 10px;
	border-bottom: 0.5px solid rgba(255, 255, 255, 0.5);
}
.editOptionType_Filter:hover {
	opacity: 1;
	cursor: pointer;
	border: 0.5px solid #fff;
	border-top: none;
}
.editTool_Filter_active {
	display: block;
}
.editTool_Filter_active #editOptionsBar_Filter {
	display: flex;
}
.editTool_Filter_active #editOptionsBar_Filter .editOptionType_Filter {
	display: flex;
}

.editOption_active {
	opacity: 1;
	border: 0.5px solid #fff;
	border-top: none;
}
.editOption_active i {
	font-size: 15px;
}
.editOption_active span.after {
	content: '';
    position: absolute;
    width: 0;
	height: 0;
	padding: 0px;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid #fff;
    clear: both;
}

.scrollerContainer {
	position: absolute;
	top: 0px;
	left: 0px;
	background: none;
}
.scrollerDiv {
	height: 100%;
	width: 30px;
	position: absolute;
	top: 0px;
	display: none;
    justify-content: center;
    align-items: center;
	text-align: center;
	background: #222;
	color: #fff;
	border: 0.5px solid #fff;
}
.scrollerDiv_Left {
	left: 0px;
}
.scrollerDiv_Right {
	right: 0px;
}
.scrollerDiv i {
	/*display: table-cell;
	vertical-align: middle;*/
}
.scrollerDiv:hover {
	cursor: pointer;
	border-top: 0.5px solid #aaa;
	border-left: 0.5px solid #aaa;
	border-bottom: 0.5px solid #666;
	border-right: 0.5px solid #666;
}

#editToolInputsContainer {
	height: 105px;
}
.editOptionTitleSection {
	height: 30px;
	color: #fff;
	border-bottom: 0.5px solid #fff;
}
.editOptionTitleSection a {
	line-height: 30px;
	color: #fff;
	font-size: 12px;
	text-decoration: none;
}
.editOptionTitleSection span {
	line-height: 30px;
	color: #fff;
	font-size: 10px;
}
.editOptionTitleSection span:hover {
	text-decoration: underline;
	cursor: pointer;
}
.editOptionInputSection {
	height: calc(100% - 30px);
	padding: 20px 20px;
	display: flex;
	flex-wrap: wrap;
}
.editOptionInputContainerSection_active {

}

.inputContainerDiv {
	height: 100%;
	margin-right: 20px;
	display: flex;
    flex-direction: column;
	justify-content: center;
}
.inputContainerDiv:last-child {
	margin-right: 0px;
}

.buttonInputContainer {
	height: 30px;
	width: 30px;
	opacity: 0.6;
	border: 0.5px solid #fff;
	border-radius: 3px;
	cursor: pointer;
}
.buttonInputContainer a {
	display: block;
	color: #fff;
	font-size
}
.buttonInputContainer a i {
	height: 30px;
	width: 30px;
	line-height: 30px;
	font-size: 20px;
}
.buttonInputContainer:hover {
	opacity: 1;
}
.buttonInputContaner_active {
	opacity: 1;
}

.rangeInputContainer {
	flex-grow: 1
}
#editOptionInput_Rotate .rangeInputContainer {
	width: calc(100% - 100px);
}
.rangeInputContainer input[type="range"], .rangeslider {
	cursor: pointer;
	height: 5px;
	padding: 0px;
	border: 1px solid #ced4da;
	-webkit-appearance: none;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-webkit-box-shadow: inset 0px 0px 1px 1px rgba(255, 255, 255, 0.3), 0px 1px 1px 0px rgba(255, 255, 255, 0.7);
	-moz-box-shadow: inset 0px 0px 1px 1px rgba(255, 255, 255, 0.3), 0px 1px 1px 0px rgba(255, 255, 255, 0.7);
	box-shadow: inset 0px 0px 1px 1px rgba(255, 255, 255, 0.3), 0px 1px 1px 0px rgba(255, 255, 255, 0.7);

	background: #999;
	background: -moz-linear-gradient(left, #ccc 0%, #999 50%, #666 100%);
	background: -webkit-gradient(linear, left top, right top, color-stop(0%,#ccc), color-stop(50%,#999), color-stop(100%,#666));
	background: -webkit-linear-gradient(left, #ccc 0%,#999 50%,#666 100%);
	background: -o-linear-gradient(left, #ccc 0%,#999 50%,#666 100%);
	background: -ms-linear-gradient(left, #ccc 0%,#999 50%,#666 100%);
	background: linear-gradient(to right, #ccc 0%,#999 50%,#666 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ccc', endColorstr='#666', GradientType=1);
}
/* .rangeInputContainer input[type="range"]:hover {
	background: rgb(194, 139, 131);
	width: 130px;
	height: 6px;
	-webkit-appearance: none;
	border-radius: 8px;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	-webkit-box-shadow: inset 0px 0px 1px 1px rgba(0, 0, 0, 0.9), 0px 1px 1px 0px rgba(255, 255, 255, 0.13);
	-moz-box-shadow: inset 0px 0px 1px 1px rgba(0, 0, 0, 0.9), 0px 1px 1px 0px rgba(255, 255, 255, 0.13);
	box-shadow: inset 0px 0px 1px 1px rgba(0, 0, 0, 0.9), 0px 1px 1px 0px rgba(255, 255, 255, 0.13);
} */
.rangeInputContainer input[type="range"]::-webkit-slider-thumb, .rangeslider__handle {
	-webkit-appearance: none !important;
	width: 30px;
	height: 12px;
	top: -4px !important;
	-webkit-appearance: none;
	border-radius: 2px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	-webkit-box-shadow: inset 0px 0px 1px 1px rgba(0, 0, 0, 0.9), 0px 1px 1px 0px rgba(255, 255, 255, 0.13);
	-moz-box-shadow: inset 0px 0px 1px 1px rgba(0, 0, 0, 0.9), 0px 1px 1px 0px rgba(255, 255, 255, 0.13);
	box-shadow: inset 0px 0px 1px 1px rgba(0, 0, 0, 0.9), 0px 1px 1px 0px rgba(255, 255, 255, 0.13);

	background: #5e99ca;
	background: -moz-linear-gradient(left, #5e99ca 0%, #bde1ff 100%);
	background: -webkit-gradient(linear, left top, right top, color-stop(0%,#5e99ca), color-stop(100%,#bde1ff));
	background: -webkit-linear-gradient(left, #5e99ca 0%, #bde1ff 100%);
	background: -o-linear-gradient(left, #5e99ca 0%, #bde1ff 100%);
	background: -ms-linear-gradient(left, #5e99ca 0%, #bde1ff 100%);
	background: linear-gradient(to right, #5e99ca 0%, #bde1ff 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5e99ca', endColorstr='#bde1ff', GradientType=1);
}
.rangeInputContainer input[type="range"]::-webkit-slider-thumb:hover, .rangeslider__handle:hover {
	background: #5e99ff;
	background: -moz-linear-gradient(left, #5e99ff 0%, #aecbfc 100%);
	background: -webkit-gradient(linear, left top, right top, color-stop(0%,#5e99ff), color-stop(100%,#aecbfc));
	background: -webkit-linear-gradient(left, #5e99ff 0%, #aecbfc 100%);
	background: -o-linear-gradient(left, #5e99ff 0%, #aecbfc 100%);
	background: -ms-linear-gradient(left, #5e99ff 0%, #aecbfc 100%);
	background: linear-gradient(to right, #5e99ff 0%, #aecbfc 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5e99ff', endColorstr='#aecbfc', GradientType=1);
}
.editOptionInputSection {
	position: relative;
}
.range-slider__value {
	position: absolute;
    right: 3%;
    display: inline-block;
    width: 60px;
    color: #fff;
    line-height: 20px;
    text-align: center;
    border-radius: 3px;
    background: #2c3e50;
    padding: 5px 7px;
    margin-left: 8px;
	font-size: 11px;
}
.range-slider__value:after {
	position: absolute;
	top: 8px;
	left: -7px;
	width: 0;
	height: 0;
	border-top: 7px solid transparent;
	border-right: 7px solid #2c3e50;
	border-bottom: 7px solid transparent;
	content: "";
}
.rangeslider__value-bubble {
	font-size: 10px;
	border: 1px solid #ccc;
	display: block;
	position: absolute;
	bottom: 100%;
	margin-bottom: 12px;
	width: 40px;
	margin-left: -20px;
	height: 20px;
	line-height: 20px;
	text-align: center;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	background: #fff;
}
output.rangeslider__value-bubble {
	padding: 0px;
}
.rangeslider__value-bubble:before, .rangeslider__value-bubble:after {
	border-width: 5px;
	border-style: solid;
	border-color: transparent;
	content: "";
	display: block;
	margin: auto;
	width: 5px;
	position: absolute;
	top: 18px;
	left: 0;
	right: 0;
}
.rangeslider__value-bubble:before {
	border-top-color: #ccc;
	border-bottom-width: 0;
	bottom: -11px;
}
.rangeslider__value-bubble:after {
	border-top-color: #fff;
	border-bottom-width: 0;
	bottom: -10px;
}
.rangeslider__fill {
	background: none;
}
/* .rangeInputDatalist {
	font-size: 12px;
	color: #fff;
} */

<?php
		$this->view->headStyle()->captureEnd();
		?>
		<script src="https://use.fontawesome.com/7b57e84efd.js"></script>
		<div id="imageEditorContainer" class="col-sm-12 px-0 text-center">
			<div id="canvasContainerDiv" class="w-100">
				<canvas id="imageEditorCanvas" class=""></canvas>
			</div>
			<div id="editToolTypes" class="w-100 p-10">
				<div id="editTools_Basic" class="editorToolTypeSelector mr-10" title="<?php echo  $this->view->translate('Basic', 'en'); ?>">
					<div class="dummyBoxTL dummyForCornerBorder"></div>
					<div class="dummyBoxTR dummyForCornerBorder"></div>
					<div class="dummyBoxBL dummyForCornerBorder"></div>
					<div class="dummyBoxBR dummyForCornerBorder"></div>

					<i class="fa fa-wrench h-100 w-100" aria-hidden="true"></i>
				</div>
				<div id="editTools_Adjust" class="editorToolTypeSelector mr-10" title="<?php echo  $this->view->translate('Adjustments', 'en'); ?>">
					<div class="dummyBoxTL dummyForCornerBorder"></div>
					<div class="dummyBoxTR dummyForCornerBorder"></div>
					<div class="dummyBoxBL dummyForCornerBorder"></div>
					<div class="dummyBoxBR dummyForCornerBorder"></div>

					<i class="fa fa-sliders h-100 w-100" aria-hidden="true"></i>
				</div>
				<div id="editTools_Effect" class="editorToolTypeSelector mr-10" title="<?php echo  $this->view->translate('Effects', 'en'); ?>">
					<div class="dummyBoxTL dummyForCornerBorder"></div>
					<div class="dummyBoxTR dummyForCornerBorder"></div>
					<div class="dummyBoxBL dummyForCornerBorder"></div>
					<div class="dummyBoxBR dummyForCornerBorder"></div>

					<i class="fa fa-paint-brush h-100 w-100" aria-hidden="true"></i>
				</div>
				<div id="editTools_Filter" class="editorToolTypeSelector mr-10" title="<?php echo  $this->view->translate('Filters', 'en'); ?>">
					<div class="dummyBoxTL dummyForCornerBorder"></div>
					<div class="dummyBoxTR dummyForCornerBorder"></div>
					<div class="dummyBoxBL dummyForCornerBorder"></div>
					<div class="dummyBoxBR dummyForCornerBorder"></div>

					<i class="fa fa-filter h-100 w-100" aria-hidden="true"></i>
				</div>

				<div id="editTools_Restore" class="editorToolTypeSelector ml-10 float-right rightAlignStart" title="<?php echo  $this->view->translate('Restore Original Image', 'en'); ?>">
					<div class="dummyBoxTL dummyForCornerBorder"></div>
					<div class="dummyBoxTR dummyForCornerBorder"></div>
					<div class="dummyBoxBL dummyForCornerBorder"></div>
					<div class="dummyBoxBR dummyForCornerBorder"></div>
					<i class="fa fa-refresh h-100 w-100" aria-hidden="true"></i>
				</div>

				<div id="editTools_Undo" class="editorToolTypeSelector ml-10 float-right" title="<?php echo  $this->view->translate('Undo', 'en'); ?>">
					<div class="dummyBoxTL dummyForCornerBorder"></div>
					<div class="dummyBoxTR dummyForCornerBorder"></div>
					<div class="dummyBoxBL dummyForCornerBorder"></div>
					<div class="dummyBoxBR dummyForCornerBorder"></div>
					<i class="fa fa-arrow-left h-100 w-100" aria-hidden="true"></i>
				</div>
				<div id="editTools_Redo" class="editorToolTypeSelector ml-10 float-right" title="<?php echo  $this->view->translate('Redo', 'en'); ?>">
					<div class="dummyBoxTL dummyForCornerBorder"></div>
					<div class="dummyBoxTR dummyForCornerBorder"></div>
					<div class="dummyBoxBL dummyForCornerBorder"></div>
					<div class="dummyBoxBR dummyForCornerBorder"></div>
					<i class="fa fa-arrow-right h-100 w-100" aria-hidden="true"></i>
				</div>

				<div id="editTools_Save" class="editorToolTypeSelector ml-10 float-right" title="<?php echo  $this->view->translate('Save', 'en'); ?>">
					<div class="dummyBoxTL dummyForCornerBorder"></div>
					<div class="dummyBoxTR dummyForCornerBorder"></div>
					<div class="dummyBoxBL dummyForCornerBorder"></div>
					<div class="dummyBoxBR dummyForCornerBorder"></div>
					<i class="fa fa-floppy-o h-100 w-100" aria-hidden="true"></i>
				</div>
				<div id="save-media-dialogue" title="<?php echo $this->view->translate('Selection'); ?>" style="display:none;">
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php echo  $this->view->translate('Image saved successfully'); ?></p>
				</div>
			</div>
			<div id="editToolsContainer" class="w-100 hidden">
				<div id="editOptionsBar_Basic" class="w-100 editOptionsBar">
					<div id="editOption_Crop" class="editOption editOptionType_Basic" title="<?php echo  $this->view->translate('Crop Selection', 'en'); ?>">
						<i class="fa fa-crop h-100 w-100"></i>
					</div>
					<div id="editOption_Zoom" class="editOption editOptionType_Basic" title="<?php echo  $this->view->translate('Zoom', 'en'); ?>">
						<i class="fa fa-search-plus h-100 w-100"></i>
					</div>
					<div id="editOption_Rotate" class="editOption editOptionType_Basic" title="<?php echo  $this->view->translate('Rotate', 'en'); ?>">
						<i class="fa fa-repeat h-100 w-100"></i>
					</div>
				</div>
				<div id="editOptionsBar_Adjust" class="w-100 editOptionsBar">
					<div id="editOption_Brightness" class="editOption editOptionType_Adjust" title="<?php echo  $this->view->translate('Brightness', 'en'); ?>">
						<i class="fa fa-sun-o h-100 w-100"></i>
					</div>
					<div id="editOption_Contrast" class="editOption editOptionType_Adjust" title="<?php echo  $this->view->translate('Contrast', 'en'); ?>">
						<i class="fa fa-adjust h-100 w-100"></i>
					</div>
					<div id="editOption_Saturation" class="editOption editOptionType_Adjust" title="<?php echo  $this->view->translate('Saturation', 'en'); ?>">
						<i class="fa fa-tint h-100 w-100"></i>
					</div>
				</div>
				<div id="editOptionsBar_Effect" class="w-100 editOptionsBar">
					<div id="editOption_Hue" class="editOption editOptionType_Effect" title="<?php echo  $this->view->translate('Hue', 'en'); ?>">
						<span class="editOptionNameSpan"><?php echo  $this->view->translate('Hue', 'en'); ?></span>
					</div>
					<div id="editOption_Noise" class="editOption editOptionType_Effect" title="<?php echo  $this->view->translate('Noise', 'en'); ?>">
						<span class="editOptionNameSpan"><?php echo  $this->view->translate('Noise', 'en'); ?></span>
					</div>
					<div id="editOption_Pixelate" class="editOption editOptionType_Effect" title="<?php echo  $this->view->translate('Pixelate', 'en'); ?>">
						<span class="editOptionNameSpan"><?php echo  $this->view->translate('Pixelate', 'en'); ?></span>
					</div>
					<div id="editOption_Blur" class="editOption editOptionType_Effect" title="<?php echo  $this->view->translate('Blur', 'en'); ?>">
						<span class="editOptionNameSpan"><?php echo  $this->view->translate('Blur', 'en'); ?></span>
					</div>
					<div id="editOption_Sharpen" class="editOption editOptionType_Effect" title="<?php echo  $this->view->translate('Sharpen', 'en'); ?>">
						<span class="editOptionNameSpan"><?php echo  $this->view->translate('Sharpen', 'en'); ?></span>
					</div>
					<div id="editOption_Emboss" class="editOption editOptionType_Effect hidden" title="<?php echo  $this->view->translate('Emboss', 'en'); ?>">
						<span class="editOptionNameSpan"><?php echo  $this->view->translate('Emboss', 'en'); ?></span>
					</div>
				</div>
				<div id="editOptionsBar_Filter" class="w-100 editOptionsBar">
					<div id="editOption_Original" class="editOption editOptionType_Filter" title="<?php echo  $this->view->translate('Original', 'en'); ?>">
						<span class="editOptionNameSpan text-truncate"><?php echo  $this->view->translate('Original', 'en'); ?></span>
						<div class="editOptionFilterThumb"></div>
					</div>
					<div id="editOption_Invert" class="editOption editOptionType_Filter" title="<?php echo  $this->view->translate('Invert Colors', 'en'); ?>">
						<span class="editOptionNameSpan text-truncate"><?php echo  $this->view->translate('Invert Colors', 'en'); ?></span>
						<div class="editOptionFilterThumb"></div>
					</div>
					<div id="editOption_BnW" class="editOption editOptionType_Filter" title="<?php echo  $this->view->translate('Black and White', 'en'); ?>">
						<span class="editOptionNameSpan text-truncate"><?php echo  $this->view->translate('Black and White', 'en'); ?></span>
						<div class="editOptionFilterThumb"></div>
					</div>
					<div id="editOption_Sepia" class="editOption editOptionType_Filter" title="<?php echo  $this->view->translate('Sepia', 'en'); ?>">
						<span class="editOptionNameSpan text-truncate"><?php echo  $this->view->translate('Sepia', 'en'); ?></span>
						<div class="editOptionFilterThumb"></div>
					</div>
					<div id="editOption_Brownie" class="editOption editOptionType_Filter" title="<?php echo  $this->view->translate('Brownie', 'en'); ?>">
						<span class="editOptionNameSpan text-truncate"><?php echo  $this->view->translate('Brownie', 'en'); ?></span>
						<div class="editOptionFilterThumb"></div>
					</div>
					<div id="editOption_Vintage" class="editOption editOptionType_Filter" title="<?php echo  $this->view->translate('Vintage', 'en'); ?>">
						<span class="editOptionNameSpan text-truncate"><?php echo  $this->view->translate('Vintage', 'en'); ?></span>
						<div class="editOptionFilterThumb"></div>
					</div>
					<div id="editOption_Kodachrome" class="editOption editOptionType_Filter" title="<?php echo  $this->view->translate('Kodachrome', 'en'); ?>">
						<span class="editOptionNameSpan text-truncate"><?php echo  $this->view->translate('Kodachrome', 'en'); ?></span>
						<div class="editOptionFilterThumb"></div>
					</div>
					<div id="editOption_Technicolor" class="editOption editOptionType_Filter" title="<?php echo  $this->view->translate('Technicolor', 'en'); ?>">
						<span class="editOptionNameSpan text-truncate"><?php echo  $this->view->translate('Technicolor', 'en'); ?></span>
						<div class="editOptionFilterThumb"></div>
					</div>
					<div id="editOption_Polaroid" class="editOption editOptionType_Filter" title="<?php echo  $this->view->translate('Polaroid', 'en'); ?>">
						<span class="editOptionNameSpan text-truncate"><?php echo  $this->view->translate('Polaroid', 'en'); ?></span>
						<div class="editOptionFilterThumb"></div>
					</div>
				</div>
			</div>
			<div id="editToolInputsContainer" class="w-100 hidden">
				<div id="editOptionInputContainer_Crop" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Crop" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Crop Selection', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Crop" class="h-100 w-100 editOptionInputSection">
						<div class="buttonInputContainer inputContainerDiv">
							<a id="set_Crop_Circle" class="set_Crop_Option"><i class="fa fa-circle-thin" aria-hidden="true" title="<?php echo $this->view->translate('Set Circular Selection', 'en'); ?>"></i></a>
						</div>
						<div class="buttonInputContainer inputContainerDiv<?= ($this->view->flag) ? '' : ' buttonInputContaner_active'; ?>">
							<a id="set_Crop_Square" class="set_Crop_Option"><i class="fa fa-square-o" aria-hidden="true" title="<?php echo $this->view->translate('Set Square Selection', 'en'); ?>"></i></a>
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Zoom" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Zoom" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Zoom', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Zoom" class="h-100 w-100 editOptionInputSection">
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="zoomChange" class="w-100" min="1" max="3" step="0.002" value=1 />
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Rotate" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Rotate" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Rotate', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Rotate" class="h-100 w-100 editOptionInputSection">
						<div class="buttonInputContainer inputContainerDiv">
							<a id="set_Rotate_AntiClockwise"><i class="fa fa-undo" aria-hidden="true" title="<?php echo $this->view->translate('Rotate AntiClockwise', 'en'); ?>"></i></a>
						</div>
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="rotateChange" class="w-100" min="-180" max="180" step="1" value=0 />
						</div>
						<div class="buttonInputContainer inputContainerDiv">
							<a id="set_Rotate_Clockwise"><i class="fa fa-repeat" aria-hidden="true" title="<?php echo $this->view->translate('Rotate Clockwise', 'en'); ?>"></i></a>
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Brightness" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Brightness" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Brightness', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Brightness" class="h-100 w-100 editOptionInputSection">
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="brightnessChange" class="w-100" min="-1" max="1" step="0.002" value=0 />
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Contrast" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Contrast" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Contrast', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Contrast" class="h-100 w-100 editOptionInputSection">
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="contrastChange" class="w-100" min="-1" max="1" step="0.002" value=0 />
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Saturation" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Saturation" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Saturation', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Saturation" class="h-100 w-100 editOptionInputSection">
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="saturationChange" class="w-100" min="-1" max="1" step="0.002" value=0 />
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Hue" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Hue" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Hue', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Hue" class="h-100 w-100 editOptionInputSection">
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="hueChange" class="w-100" min="-2" max="2" step="0.004" value=0 />
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Noise" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Noise" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Noise', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Noise" class="h-100 w-100 editOptionInputSection">
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="noiseChange" class="w-100" min="0" max="1000" step="1" value=0 />
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Pixelate" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Pixelate" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Pixelate', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Pixelate" class="h-100 w-100 editOptionInputSection">
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="pixelateChange" class="w-100" min="0" max="10" step="0.01" value=0 />
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Blur" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Blur" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Blur', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Blur" class="h-100 w-100 editOptionInputSection">
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="blurChange" class="w-100" min="0" max="1" step="0.001" value=0 />
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Sharpen" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Sharpen" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Sharpen', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Sharpen" class="h-100 w-100 editOptionInputSection">
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="sharpenChange" class="w-100" min="4.9" max="9.9" step='0.005' value=4.9 />
						</div>
					</div>
				</div>
				<div id="editOptionInputContainer_Emboss" class="h-100 w-100 editOptionInputContainerSection hidden">
					<div id="editOptionTitle_Emboss" class="w-100 text-left editOptionTitleSection">
						<a class="h-100 px-10"><?php echo  $this->view->translate('Emboss', 'en'); ?></a>
						<span class="h-100 px-10 float-right editOptionInput_Reset hidden"><?php echo  $this->view->translate('Reset', 'en'); ?></span>
					</div>
					<div id="editOptionInput_Emboss" class="h-100 w-100 editOptionInputSection">
						<div class="rangeInputContainer inputContainerDiv">
							<input type="range" orient="vertical" id="embossChange" class="w-100" min="0.5" max="1" step="0.0005" value=0 />
						</div>
					</div>
				</div>
			</div>
			<div id="confirm-restore-image-dialogue" title="<?php echo $this->view->translate('Action'); ?>" style="display:none;">
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php echo $this->view->translate('Are you sure you want to restore to the saved image?'); ?></p>
			</div>
		</div>
<?php

$this->view->headScript()->captureStart();
?>

var canvas = false;

$(function() {
	var activeToolType = '';
	var activeEditOption = '';
	var stateObject = { };
	var stateArray = [];
	var newImageWidth = 0;
	var newImageHeight = 0;
	var filterIndex = 0;
	var imageFlag = <?= json_encode($this->view->flag); ?>;
	var idArray = ['editOption_Original'];
	var valueArray = [undefined];
	var activeChange = '';
	var currentFilter = 'editOption_Original';
	var previousFilter = 'editOption_Original';

	$('.editorToolTypeSelector').on('click', function() {
		var selectedToolType = this.id.split('_')[1];

		if(selectedToolType == 'Restore' ||
			selectedToolType == 'Undo' ||
			selectedToolType == 'Redo' ||
			selectedToolType == 'Save') {
			return;
		}

		var $editToolTypeSelectors = $('.editorToolTypeSelector');
		var $toolsContainer = $('#editToolsContainer');
		var $editOptionSelectors = $('.editOption');
		var $selectedOptionsBar = $('#editOptionsBar_' + selectedToolType);

		var activeClassForContainer = 'editTool_' + activeToolType + '_active';
		var selectedClassForContainer = 'editTool_' + selectedToolType + '_active';

		$('.scrollerContainer *').off();
		$('.scrollerContainer').remove();
		resetOptionsBarScrollPostion('#editOptionsBar_' + selectedToolType);
		var addScroller = false;

		if(activeEditOption !== '' && (!$('#editOption_'+activeEditOption).hasClass('editOptionType_Filter'))) {
			$('#editOption_'+activeEditOption+' span.after').remove();
			$('#editOption_'+activeEditOption).removeClass('editOption_active');
		}

		$('#'+currentFilter).addClass('editOption_active');
		$('.editOption_active span.editOptionNameSpan').after('<span class="after"></span>');
		$('.editOption_active span').css('left', ($('.editOption_active').outerWidth() / 2) - ($('.editOption_active span.after').outerWidth() / 2));
		

		$('#editToolInputsContainer').addClass('hidden');
		$('.editOptionInputContainerSection').addClass('hidden');
		$('.editOptionInputContainerSection').removeClass('editOptionInputContainerSection_active');

		if(activeToolType == selectedToolType) {
			$(this).toggleClass('editorToolType_active');

			$toolsContainer.toggleClass(selectedClassForContainer);
			$toolsContainer.toggleClass('hidden');

			if($toolsContainer.hasClass(selectedClassForContainer)) {
				addScroller = true;
			}
		} else {
			$editToolTypeSelectors.removeClass('editorToolType_active');
			$(this).addClass('editorToolType_active');

			$toolsContainer.removeClass('hidden');
			$toolsContainer.removeClass(activeClassForContainer);
			$toolsContainer.addClass(selectedClassForContainer);

			addScroller = true;
		}

		activeToolType = selectedToolType;

		if(addScroller) {
			createScroller();
		}
	});

	$('.editOption').on('click', function () {
		var selectedEditOption = this.id.split('_')[1];

		if($(this).hasClass('editOptionType_Filter')) {
			previousFilter = currentFilter;
			currentFilter = this.id;
		}

		var $editOptionSelectors = $('.editOption');

		$('.editOption_active span.after').remove();
		$('#editToolInputsContainer').addClass('hidden');
		$('.editOptionInputContainerSection').addClass('hidden');
		$('.editOptionInputContainerSection').removeClass('editOptionInputContainerSection_active');

		var showInputs = false;
		var dropOpen = false;

		if(activeEditOption == selectedEditOption) {
			if(!$(this).hasClass('editOptionType_Filter')) {
				$(this).toggleClass('editOption_active');

				if($(this).hasClass('editOption_active')) {
					dropOpen = true;
					showInputs = true;
				}
			} else {
				$(this).addClass('editOption_active');
				dropOpen = true;
			}
		} else {
			dropOpen = true;

			$editOptionSelectors.removeClass('editOption_active');
			$(this).addClass('editOption_active');			

			if(!$(this).hasClass('editOptionType_Filter')) {
				showInputs = true;
			}
		}

		if(dropOpen) {
			if(activeToolType != 'Filter') {
				$('.editOption_active').append('<span class="after"></span>');
				$('.editOption_active span').css('left', $('.editOption_active').offset().left - $('.editOption_active').parent().offset().left + ($('.editOption_active').outerWidth() / 2) - ($('.editOption_active span.after').outerWidth() / 2));
			} else {
				$('.editOption_active span.editOptionNameSpan').after('<span class="after"></span>');
				$('.editOption_active span').css('left', ($('.editOption_active').outerWidth() / 2) - ($('.editOption_active span.after').outerWidth() / 2));
			}
		}

		activeEditOption = selectedEditOption;

		if(showInputs) {
			$('#editToolInputsContainer').removeClass('hidden');
			$('#editOptionInputContainer_' + activeEditOption).removeClass('hidden');
			$('#editOptionInputContainer_' + activeEditOption).addClass('editOptionInputContainerSection_active');
		}
	});

	$('#editToolsContainer').on('click', '.scrollerDiv', function() {
		var $currentOptionsBar = $('#editOptionsBar_' + activeToolType);

		var currentLeftScrollPosition = $currentOptionsBar.scrollLeft();
		var defaultScrollValue = 50;

		var currentOptionBarWidth = $currentOptionsBar.width();
		var currentOptionBarScrollWidth = $currentOptionsBar[0].scrollWidth;

		if($(this).hasClass('scrollerDiv_Left')) {
			$(this).parent().find('.scrollerDiv_Right').css('display', 'flex');

			var scrollValue;
			if(currentLeftScrollPosition > defaultScrollValue) {
				scrollValue = defaultScrollValue;
			} else {
				scrollValue = currentLeftScrollPosition;
				$(this).css('display', 'none');
			}

			$currentOptionsBar.scrollLeft(currentLeftScrollPosition - scrollValue);
		} else
		if($(this).hasClass('scrollerDiv_Right')) {
			$(this).parent().find('.scrollerDiv_Left').css('display', 'flex');

			var scrollValue;
			if((currentOptionBarScrollWidth - (currentLeftScrollPosition + currentOptionBarWidth)) > defaultScrollValue) {
				scrollValue = defaultScrollValue;
			} else {
				scrollValue = currentOptionBarScrollWidth - (currentLeftScrollPosition + currentOptionBarWidth);
				$(this).css('display', 'none');
			}

			$currentOptionsBar.scrollLeft(currentLeftScrollPosition + scrollValue);
		}
	});


	//Reset to original state
	$('.editOptionInput_Reset').on('click', function() {
		if(activeEditOption.toLowerCase() != 'crop') {
			var selection = activeEditOption.toLowerCase()+'Change';
			var defaultValue = parseFloat(document.getElementById(selection).defaultValue);
			$('#'+selection).val(defaultValue);
			switch(activeEditOption.toLowerCase()) {
				case 'zoom':
					var zoom = parseFloat(defaultValue);
					handleZooming(zoom);
					break;
				case 'rotate':
					var angleVal = $(this).val();
					handleRotating(angleVal);
					break;
				case 'brightness':
					applyFilter(1, new filterObject.Brightness({brightness: defaultValue}), 1, 'brightness');
					updateCanvasState(selection, defaultValue);
					break;
				case 'contrast':
					applyFilter(2, new filterObject.Contrast({contrast: defaultValue}), 1, 'contrast');
					break;
				case 'saturation':
					applyFilter(3, new filterObject.Saturation({saturation: defaultValue}), 1, 'saturation');
					break;
				case 'hue':
					applyFilter(9, new filterObject.HueRotation({rotation: defaultValue}), 1, 'rotation');
					break;
				case 'noise':
					applyFilter(4, new filterObject.Noise({noise: defaultValue}), 1, 'noise');
					break;
				case 'pixelate':
					applyFilter(5, new filterObject.Pixelate({blocksize: defaultValue}), 2, 'blocksize');
					break;
				case 'blur':
					applyFilter(6, new filterObject.Blur({blur: defaultValue}), 1, 'blur');
					break;
				case 'sharpen':
					applyFilter(7, new filterObject.Convolute({
						matrix: [  0, -1,  0,
								-1, defaultValue, -1,
								0, -1,  0 ]
					}), 2, 'matrix');
					break;
				case 'emboss':
					applyFilter(8, new filterObject.Convolute({
						matrix: [ 1,   1,  1,
								1, defaultValue, -1,
								-1,  -1, -1 ]
					}), 2, 'matrix');
					break;
				default:
					// code block
			}

			updateCanvasState(selection, defaultValue);
		} else {
			selectionLayer = selectionSquareImage;
			$('#set_Crop_Square').parent().addClass('buttonInputContaner_active');
			$('#set_Crop_Circle').parent().removeClass('buttonInputContaner_active');
			canvas.remove(canvas.item(1));
			drawSelectionLayerObject();
		}
	});

	function resetOptionsBarScrollPostion(optionBarSelectorTag) {
		$(optionBarSelectorTag).scrollLeft(0);
	}

	function createScroller() {
		var $selectedOptionsBar = $('#editOptionsBar_' + activeToolType);

		var optionBarWidth = $selectedOptionsBar.width();
		var optionBarScrollWidth = $selectedOptionsBar[0].scrollWidth;

		if(optionBarScrollWidth > optionBarWidth) {
			var leftScrollerHtml = '<div class="scrollerDiv scrollerDiv_Left"><i class="fa fa-angle-double-left" aria-hidden="true"></i></div>';
			var rightScrollerHtml = '<div class="scrollerDiv scrollerDiv_Right"><i class="fa fa-angle-double-right" aria-hidden="true"></i></div>';
			var scrollerContainerHtml = '<div id="scrollerContainer_' + activeToolType + '" class="scrollerContainer w-100">' + leftScrollerHtml + rightScrollerHtml + '</div>';

			$selectedOptionsBar.parent().append(scrollerContainerHtml);
			$('.scrollerDiv').css({
				'height' : $selectedOptionsBar.outerHeight()
			});
			$('#scrollerContainer_' + activeToolType + ' .scrollerDiv_Right').css('display', 'flex');
		}
	}



	var imageLink = "<?php echo $this->view->imageLink; ?>";
	var currentImageLink = imageLink;

	var canvasDom = document.getElementById('canvasContainerDiv');
	var canvasWidth = (canvasDom.scrollWidth <= 760) ? canvasDom.scrollWidth : 760;
	var canvasHeight = canvasDom.scrollHeight;
	var selectionAreaWidth = 250;
	var selectionAreaHeight = 250;
	var selectionAreaLeft = 0;
	var selectionAreaRight = 0;
	var selectionAreaTop = 0;
	var selectionAreaBottom = 0;
	var cropImageUrl = '';
	var originalImageTopLeftPosition = {};
	var imageAttributes = {
		left: 0,
		top: 0,
		angle: 0,
		scaleX: 0,
		scaleY: 0
	}
	var oldZoom = 1;
	var oldRotation = 0;

	var selectionSquareImage = '/img/js/fabric/editorSelectionLayer_Square.png';
	var selectionCircleImage = '/img/js/fabric/editorSelectionLayer_Circle.png';

	var selectionLayer = selectionSquareImage;

	canvas = new fabric.Canvas('imageEditorCanvas', {
        height: canvasHeight,
		width: canvasWidth,
		selection: false,
		preserveObjectStacking: true
	});

	//Loading from Database or loading new image
	if(imageFlag) {
		var jsonArray = JSON.parse('<?= $this->view->editData ?>');

		canvasD = JSON.stringify(jsonArray['data']);

		canvas.loadFromJSON(canvasD, canvas.renderAll.bind(canvas));

		idArray = jsonArray['id'];
		valueArray = jsonArray['value'];
		var j;
		for(j = 0; j < jsonArray['id'].length; j++) {
			if(jsonArray['id'][j] != 0 && jsonArray['value'][j] != null && jsonArray['value'][j] != undefined) {
				$('#'+jsonArray['id'][j]).val(jsonArray['value'][j]);
				if(jsonArray['id'][j] == 'zoomChange') {
					oldZoom = jsonArray['value'][j];
				}
			}
			if(idArray[j] != 0 && idArray[j].indexOf('editOption') != -1) {
				currentFilter = idArray[j];
			}
		}

		newImageWidth = jsonArray['imageWidth'];
		newImageHeight = jsonArray['imageHeight'];

		canvas.on('object:added', function(object) {
			canvas.item(0).hasControls = false;
			canvas.item(0).hasBorders = false;

			if(canvas.item(0) !== undefined) {
				imageAttributes.left = canvas.item(0).left;
				imageAttributes.top = canvas.item(0).top;
				imageAttributes.angle = canvas.item(0).angle;
				imageAttributes.scaleX = canvas.item(0).scaleX;
				imageAttributes.scaleY = canvas.item(0).scaleY;
			}
			if(canvas.item(1) !== undefined) {
				var originalSource = canvas.item(1)._originalElement.currentSrc;
				if(originalSource.indexOf('Circle') != -1) {
					$('#set_Crop_Circle').parent().addClass('buttonInputContaner_active');
					$('#set_Crop_Square').parent().removeClass('buttonInputContaner_active');
				} else {
					$('#set_Crop_Square').parent().addClass('buttonInputContaner_active');
					$('#set_Crop_Circle').parent().removeClass('buttonInputContaner_active');
				}

				canvas.item(1).hasControls = false;
				canvas.item(1).selectable = false;
				canvas.item(1).lockMovementX = canvas.item(1).lockMovementY = true;
				canvas.item(1).evented = false;
			}
		});

		var positions = getTopLeftPositions(selectionAreaWidth, selectionAreaHeight);
		selectionAreaLeft = positions.x;
		selectionAreaRight = selectionAreaLeft + selectionAreaWidth;
		selectionAreaTop = positions.y;
		selectionAreaBottom = selectionAreaTop + selectionAreaHeight;
		handlePanning();
	} else {
		drawImageObject();
	}

	function drawOnCanvas(image, x, y) {
		return new fabric.Image(image, {
			top: y,
			left: x,
			hasControls: false,
			hasBorders: false
		});
	}

	function getTopLeftPositions(w, h) {
		return {
			x: canvas.width / 2 - w / 2,
			y: canvas.height / 2 - h / 2
		}
	}

	function drawImageObject() {
		var originalImage = new Image();
		originalImage.src = imageLink;
		zoneDiagonal = Math.sqrt(Math.pow(selectionAreaWidth, 2) + Math.pow(selectionAreaHeight, 2));

		imageWidth = originalImage.width;
		imageHeight = originalImage.height;
		imageAspectRatio = imageWidth / imageHeight;

		if(imageWidth < imageHeight) {
			newImageWidth = zoneDiagonal;
			newImageHeight = newImageWidth / imageAspectRatio;
		} else
		if(imageWidth > imageHeight) {
			newImageHeight = zoneDiagonal;
			newImageWidth = newImageHeight * imageAspectRatio;
		} else {
			newImageWidth = zoneDiagonal;
			newImageHeight = zoneDiagonal;
		}
		originalImage.onload = function () {
			var positions = getTopLeftPositions(newImageWidth, newImageHeight);

			var canvasImage = drawOnCanvas(originalImage, positions.x, positions.y);

			canvasImage.scaleToHeight(newImageHeight);
			canvasImage.scaleToWidth(newImageWidth);

			canvasImage.setCoords();

			canvas.add(canvasImage);

			originalImageTopLeftPosition = {x: canvasImage.left, y: canvasImage.top};
			imageAttributes.left = canvasImage.left;
			imageAttributes.top = canvasImage.top;
			imageAttributes.angle = canvasImage.angle;
			imageAttributes.scaleX = canvasImage.scaleX;
			imageAttributes.scaleY = canvasImage.scaleY;

			drawSelectionLayerObject();
		}
	}

	function drawSelectionLayerObject() {
		var selectionLayerImage = new Image();
		selectionLayerImage.src = selectionLayer;
		selectionLayerImage.onload = function () {
			var positions = getTopLeftPositions(selectionAreaWidth, selectionAreaHeight);
			selectionAreaLeft = positions.x;
			selectionAreaRight = selectionAreaLeft + selectionAreaWidth;
			selectionAreaTop = positions.y;
			selectionAreaBottom = selectionAreaTop + selectionAreaHeight;

			var positions = getTopLeftPositions(selectionLayerImage.width, selectionLayerImage.height);
			var canvasImage = drawOnCanvas(selectionLayerImage, positions.x, positions.y);
			canvasImage.set({
				opacity: 0.5
			});
			canvasImage.selectable = false;
			canvasImage.lockMovementX = true;
			canvasImage.lockMovementY = true;
			canvasImage.evented = false;

			canvas.add(canvasImage);
			canvas.renderAll();

			handlePanning();
		}
	}



	function checkBoundingPoints(checkedImage) {
		var angle = $('#rotateChange').val();

		var boundingArea = [
			[checkedImage.oCoords.bl.x, checkedImage.oCoords.bl.y],
			[checkedImage.oCoords.tl.x, checkedImage.oCoords.tl.y],
			[checkedImage.oCoords.tr.x, checkedImage.oCoords.tr.y],
			[checkedImage.oCoords.br.x, checkedImage.oCoords.br.y]
		];

		var flagTLCorner = ifPointInsideArea([selectionAreaLeft, selectionAreaTop ], boundingArea);
		var flagTRCorner = ifPointInsideArea([selectionAreaRight, selectionAreaTop ], boundingArea);
		var flagBRCorner = ifPointInsideArea([selectionAreaRight, selectionAreaBottom ], boundingArea);
		var flagBLCorner = ifPointInsideArea([selectionAreaLeft, selectionAreaBottom ], boundingArea);

		if(!flagTLCorner || !flagTRCorner || !flagBRCorner || !flagBLCorner) {
			if((!flagBLCorner && !flagTLCorner && !flagTRCorner) ||
				(!flagTLCorner && !flagTRCorner && !flagBRCorner) ||
				(!flagTRCorner && !flagBRCorner && !flagBLCorner) ||
				(!flagBRCorner && !flagBLCorner && !flagTLCorner)) {

				checkedImage.top = imageAttributes.top;
				checkedImage.left = imageAttributes.left;
				checkedImage.angle = imageAttributes.angle;
				checkedImage.scaleX = imageAttributes.scaleX;
				checkedImage.scaleY = imageAttributes.scaleY;
			} else
			if((!flagBLCorner && !flagTLCorner) ||
				(!flagTRCorner && !flagBRCorner)) {

				checkedImage.left = imageAttributes.left;
				checkedImage.angle = imageAttributes.angle;
				checkedImage.scaleX = imageAttributes.scaleX;
				checkedImage.scaleY = imageAttributes.scaleY;

				if(((checkedImage.oCoords.bl.x - checkedImage.oCoords.tl.x) != 0) && (Math.abs(angle) != 90)) {
					checkedImage.top = imageAttributes.top;
				}
			} else
			if((!flagTLCorner && !flagTRCorner) ||
				(!flagBRCorner && !flagBLCorner)) {

				checkedImage.top = imageAttributes.top;
				checkedImage.angle = imageAttributes.angle;
				checkedImage.scaleX = imageAttributes.scaleX;
				checkedImage.scaleY = imageAttributes.scaleY;

				if(((checkedImage.oCoords.tl.y - checkedImage.oCoords.tr.y) != 0) && (Math.abs(angle) != 90)) {
					checkedImage.left = imageAttributes.left;
				}
			} else {
				checkedImage.top = imageAttributes.top;
				checkedImage.left = imageAttributes.left;
				checkedImage.angle = imageAttributes.angle;
				checkedImage.scaleX = imageAttributes.scaleX;
				checkedImage.scaleY = imageAttributes.scaleY;
			}
		}
		checkedImage.setCoords();

		return checkedImage;
	}

	function ifPointInsideArea(point, area) {
		var x = point[0], y = point[1];

		var inside = false;
		for (var i = 0, j = area.length - 1; i < area.length; j = i++) {
			var xi = area[i][0], yi = area[i][1];
			var xj = area[j][0], yj = area[j][1];

			var intersect = ((yi > y) != (yj >= y))
				&& (x <= (xj - xi) * (y - yi) / (yj - yi) + xi);
			if (intersect) inside = !inside;
		}

		return inside;
	};

	function handlePanning () {
		canvas.on('object:moving', function(e) {
			var movedImage = e.target;
			movedImage.setCoords();

			var transformValues = fabric.util.qrDecompose(movedImage.calcTransformMatrix());

			var angle = $('#rotateChange').val();

			var boundingRect = movedImage.getBoundingRect();

			var zoom = canvas.getZoom();

			movedImage = checkBoundingPoints(movedImage);
			imageAttributes.left = movedImage.left;
			imageAttributes.top = movedImage.top;
		});
	}

	$('#set_Crop_Circle').on('click', function(e) {
		selectionLayer = selectionCircleImage;
		$('.set_Crop_Option').parent().removeClass('buttonInputContaner_active');
		$(this).parent().addClass('buttonInputContaner_active');
		canvas.remove(canvas.item(1));
		drawSelectionLayerObject();
	});

	$('#set_Crop_Square').on('click', function(e) {
		selectionLayer = selectionSquareImage;
		$('.set_Crop_Option').parent().removeClass('buttonInputContaner_active');
		$(this).parent().addClass('buttonInputContaner_active');
		canvas.remove(canvas.item(1));
		drawSelectionLayerObject();
	});

	$('#zoomChange').on('input', function (e) {
		var zoom = parseFloat(this.value);
		handleZooming(zoom);
		
		updateCanvasState(this.id, this.value);
	});

	function handleZooming(newZoomLevel) {
		var zoomedImage = canvas.item(0);
		var boundingRect = zoomedImage.getBoundingRect();
		var angle = $('#rotateChange').val();

		zoomedImage.scaleToHeight((boundingRect.height / oldZoom) * newZoomLevel);
		zoomedImage.scaleToWidth((boundingRect.width / oldZoom) * newZoomLevel);

		zoomedImage.setCoords();

		zoomedImage = checkBoundingPoints(zoomedImage);
		if((zoomedImage.scaleX != imageAttributes.scaleX) || (zoomedImage.scaleY != imageAttributes.scaleY)) {
			oldZoom = newZoomLevel;
		} else {
			$('#zoomChange').val(oldZoom);
		}
		imageAttributes.left = zoomedImage.left;
		imageAttributes.top = zoomedImage.top;
		imageAttributes.scaleX = zoomedImage.scaleX;
		imageAttributes.scaleY = zoomedImage.scaleY;

		canvas.renderAll();
	}

	$('#rotateChange').on('input', function () {
		var angleVal = $(this).val();
		handleRotating(angleVal);

		updateCanvasState(this.id, this.value);
	});

	$('#set_Rotate_AntiClockwise').on('click', function () {
		var angleChange = -90;
		rotateFromButton(angleChange);
	});

	$('#set_Rotate_Clockwise').on('click', function () {
		var angleChange = 90;
		rotateFromButton(angleChange);
	});

	fabric.Object.prototype.setOriginToCenter = function () {
		this._originalOriginX = this.originX;
		this._originalOriginY = this.originY;

		var center = this.getCenterPoint();

		this.set({
			originX: 'center',
			originY: 'center',
			left: center.x,
			top: center.y
		});
	};

	fabric.Object.prototype.setCenterToOrigin = function () {
		var originPoint = this.translateToOriginPoint(
		this.getCenterPoint(),
		this._originalOriginX,
		this._originalOriginY);

		this.set({
			originX: this._originalOriginX,
			originY: this._originalOriginY,
			left: originPoint.x,
			top: originPoint.y
		});
	};

	function rotateFromButton(angleChange) {
		var angleVal = 0;
		if(angleChange > 0) {
			if(parseInt($('#rotateChange').val()) >= 0) {
				angleVal = parseInt($('#rotateChange').val()) + 90 - (parseInt($('#rotateChange').val()) % 90);
			} else {
				angleVal = parseInt($('#rotateChange').val()) + (Math.abs(parseInt($('#rotateChange').val()) % 90));
			}
			if(angleVal == parseInt($('#rotateChange').val())) {
				angleVal += 90;
			}
		} else {
			if(parseInt($('#rotateChange').val()) >= 0) {
				angleVal = parseInt($('#rotateChange').val()) - (parseInt($('#rotateChange').val()) % 90);
			} else {
				angleVal = parseInt($('#rotateChange').val()) - 90 + (Math.abs(parseInt($('#rotateChange').val()) % 90));
			}
			if(angleVal == parseInt($('#rotateChange').val())) {
				angleVal -= 90;
			}
		}

		if(angleVal == 270) {
			angleVal = -90;
		} else
		if(angleVal == -270) {
			angleVal = 90;
		}

		$('#rotateChange').val(angleVal);
		$('#rotateChange').rangeslider('update', true);
		$('#rotateChange').trigger('input');
	}

	function handleRotating(angleOffset) {
		var rotatedImage = canvas.item(0);

		if (!rotatedImage) return;

		resetOrigin = false;
		var angle = parseInt(angleOffset);
		if ((rotatedImage.originX !== 'center' || rotatedImage.originY !== 'center') && rotatedImage.centeredRotation) {
			rotatedImage.setOriginToCenter && rotatedImage.setOriginToCenter();
			resetOrigin = true;
		}

		if(angle < 0) {
			angle = 360 + angle;
		} else {
			angle = angle;
		}
		rotatedImage.set('angle', angle).setCoords();
		if (resetOrigin) {
			rotatedImage.setCenterToOrigin && rotatedImage.setCenterToOrigin();
		}

		rotatedImage = checkBoundingPoints(rotatedImage);

		if(imageAttributes.angle == rotatedImage.angle) {
			$('#rotateChange').val(oldRotation);
		} else {
			imageAttributes.left = rotatedImage.left;
			imageAttributes.top = rotatedImage.top;
			imageAttributes.angle = rotatedImage.angle;

			oldRotation = $('#rotateChange').val();
		}
		canvas.renderAll();
	}

	function applyFilter(index, filter, flag, prop) {
		var obj = canvas.item(0);
		var fl = 0;

		if(!imageFlag) {
			index = obj.filters.length;
			if(flag == 0) {
				var filtersArray = ["Sepia", "Grayscale", "Brownie", "Invert", "Kodachrome", "Technicolor", "Polaroid", "Vintage"];
				if(obj.filters[0] != null && !filtersArray.includes(obj.filters[0]['type'])) {
					obj.filters.unshift(filter);
				} else {
					obj.filters[0] = filter;
				}
			} else
			if(flag == 1 || flag == 2) {
				var i;
				for(i = index-1; i > 0; i--) {
					if (prop in obj.filters[i]) {
						index = i;
						break;
					}
				}
				if(flag == 1) {
					obj.filters[index] = filter;
				} else
				if(flag == 2) {
					obj.filters.splice(index, 1);
				}
			} else
			if(flag == 3) {
				obj.filters.splice(0, 1);
			}
		} else {
			var filtersArray = ["Sepia", "Grayscale", "Brownie", "Invert", "Kodachrome", "Technicolor", "Polaroid", "Vintage"];
			if(flag == 0) {
				if(obj.filters[0] != null && !filtersArray.includes(obj.filters[0]['type'])) {
					obj.filters.unshift(filter);
				} else {
					obj.filters[0] = filter;
				}
				fl = 1;
			} else
			if(flag == 1 || flag == 2) {
				var i;
				for(i = index-1; i >= 0; i--) {
					if(obj.filters[i] != undefined && prop in obj.filters[i]) {
						index = i;
						break;
					}
				}
				if(flag == 1) {
					obj.filters[index] = filter;
				} else
				if(flag == 2) {
					obj.filters.splice(index, 1);
				}
			} else
			if(flag == 3) {
				obj.filters.splice(0, 1);
			}
		}
		obj.applyFilters();
		canvas.renderAll();
	}

	filterObject = fabric.Image.filters;
	$('#brightnessChange').on('input', function () {
		applyFilter(1, new filterObject.Brightness({brightness: this.value}), 1, 'brightness');
		updateCanvasState(this.id, this.value);
	});

	$('#contrastChange').on('input', function () {
		applyFilter(2, new filterObject.Contrast({contrast: this.value}), 1, 'contrast');
		updateCanvasState(this.id, this.value);
	});

	$('#saturationChange').on('input', function () {
		applyFilter(3, new filterObject.Saturation({saturation: this.value}), 1, 'saturation');
		updateCanvasState(this.id, this.value);
	});

	$('#noiseChange').on('input', function (event) {
		applyFilter(4, new filterObject.Noise({noise: this.value}), 1, 'noise');
		updateCanvasState(this.id, this.value);
	});

	$('#pixelateChange').on('input', function () {
		if(this.value == 0) {
			applyFilter(5, new filterObject.Pixelate({blocksize: this.value}), 2, 'blocksize');
		} else {
			applyFilter(5, new filterObject.Pixelate({blocksize: this.value}), 1, 'blocksize');
		}
		updateCanvasState(this.id, this.value);
	});

	$('#blurChange').on('input', function () {
		applyFilter(6, new filterObject.Blur({blur: this.value}), 1, 'blur');
		updateCanvasState(this.id, this.value);
	});

	$('#sharpenChange').on('input', function () {
		var flag;
		if(this.value == 4.9) {
			flag = 2;
		} else {
			flag = 1
		}
		applyFilter(7, new filterObject.Convolute({
			matrix: [  0, -1,  0,
					-1, this.value, -1,
					0, -1,  0 ]
		}), flag, 'matrix');
		updateCanvasState(this.id, this.value);
	});

	$('#embossChange').on('input', function () {
		var flag;
		if(this.value == 0.5) {
			flag = 2;
		} else {
			flag = 1;
		}
		applyFilter(8, new filterObject.Convolute({
			matrix: [ 1,   1,  1,
					1, this.value, -1,
					-1,  -1, -1 ]
		}), flag, 'matrix');
		updateCanvasState(this.id, this.value);
	});

	$('#hueChange').on('input', function () {
		applyFilter(9, new filterObject.HueRotation({rotation: this.value}), 1, 'rotation');
		updateCanvasState(this.id, this.value);
	});

	$('#editOption_Original').on('click', function () {
		applyFilter(0, null, 3, null);
		updateCanvasState(this.id, this.value);
	});

	$('#editOption_Sepia').on('click', function () {
		applyFilter(0, new filterObject.Sepia(), 0, null);
		updateCanvasState(this.id, this.value);
	});

	$('#editOption_BnW').on('click', function () {
		applyFilter(0, new filterObject.Grayscale(), 0, null);
		updateCanvasState(this.id, this.value);
	});

	$('#editOption_Brownie').on('click', function () {
		applyFilter(0, new filterObject.Brownie(), 0, null);
		updateCanvasState(this.id, this.value);
	});

	$('#editOption_Invert').on('click', function () {
		applyFilter(0, new filterObject.Invert(), 0, null);
		updateCanvasState(this.id, this.value);
	});

	$('#editOption_Kodachrome').on('click', function () {
		applyFilter(0, new filterObject.Kodachrome(), 0, null);
		updateCanvasState(this.id, this.value);
	});

	$('#editOption_Technicolor').on('click', function () {
		applyFilter(0, new filterObject.Technicolor(), 0, null);
		updateCanvasState(this.id, this.value);
	});

	$('#editOption_Polaroid').on('click', function () {
		applyFilter(0, new filterObject.Polaroid(), 0, null);
		updateCanvasState(this.id, this.value);
	});

	$('#editOption_Vintage').on('click', function () {
		applyFilter(0, new filterObject.Vintage(), 0, null);
		updateCanvasState(this.id, this.value);
	});

	$('#editTools_Restore').on('click', function() {
		$("#confirm-restore-image-dialogue").dialog({
			resizable: false,
			modal: true,
			buttons: {
				"<?php echo $this->view->translate('Cancel'); ?>": function() {
					$(this).dialog("close");
				},
				"<?php echo $this->view->translate('Ok'); ?>": function() {
					$(this).dialog("close");
					var panel= $("#imageEditorContainer");
					var inputs = panel.find('input[type="range"]');

					if(imageFlag) {
						_config.canvasState.length = 0;
						var jsonArray = JSON.parse('<?= $this->view->editData ?>');

						canvasD = JSON.stringify(jsonArray['data']);
						canvas.loadFromJSON(canvasD, canvas.renderAll.bind(canvas));

						idArray = jsonArray['id'];
						valueArray = jsonArray['value'];
						var j;
						for(j = 0; j < jsonArray['id'].length; j++) {
							if(jsonArray['id'][j] != 0 && jsonArray['value'][j] != null && jsonArray['value'][j] != undefined) {
								document.getElementById(jsonArray['id'][j]).value = jsonArray['value'][j];
								if(jsonArray['id'][j] == 'zoomChange') {
									oldZoom = jsonArray['value'][j];
								}
							}
							if(jsonArray['id'][j] != 0 && jsonArray['id'][j].indexOf('editOption') != -1) {
								$("#editOptionsBar_Filter>div.editOption_active").removeClass("editOption_active");
								$('#'+jsonArray['id'][j]).addClass('editOption_active');
								$('.editOption_active span.editOptionNameSpan').after('<span class="after"></span>');
								$('.editOption_active span').css('left', ($('.editOption_active').outerWidth() / 2) - ($('.editOption_active span.after').outerWidth() / 2));
								currentFilter = jsonArray['id'][j];
							}
						}

						inputs.each(function(index) {
							if(idArray.indexOf(inputs[index].id) != -1) {
								var i = idArray.indexOf(inputs[index].id);
								$(inputs[index]).val(valueArray[i]);
							} else {
								$(inputs[index]).val(inputs[index].defaultValue);
							}
							$('input[type="range"]').rangeslider('update', true);
						});

						newImageWidth = jsonArray['imageWidth'];
						newImageHeight = jsonArray['imageHeight'];

						var originalSource = canvas.item(1)._originalElement.currentSrc;
						if(originalSource.indexOf('Circle') != -1) {
							$('#set_Crop_Circle').parent().addClass('buttonInputContaner_active');
							$('#set_Crop_Square').parent().removeClass('buttonInputContaner_active');
						} else {
							$('#set_Crop_Square').parent().addClass('buttonInputContaner_active');
							$('#set_Crop_Circle').parent().removeClass('buttonInputContaner_active');
						}

						$('#editTools_Undo').addClass('editorToolType_blocked');
						$('#editTools_Redo').addClass('editorToolType_blocked');

						var positions = getTopLeftPositions(selectionAreaWidth, selectionAreaHeight);
						selectionAreaLeft = positions.x;
						selectionAreaRight = selectionAreaLeft + selectionAreaWidth;
						selectionAreaTop = positions.y;
						selectionAreaBottom = selectionAreaTop + selectionAreaHeight;

						handlePanning();
					} else {
						canvas.clear();
						canvas.renderAll();

						selectionLayer = selectionSquareImage;
						$('#set_Crop_Square').parent().addClass('buttonInputContaner_active');
						$('#set_Crop_Circle').parent().removeClass('buttonInputContaner_active');
						_config.canvasState.length = 0;

						inputs.each(function(index) {
							$(inputs[index]).val(inputs[index].defaultValue);
							$('input[type="range"]').rangeslider('update', true);
						});

						$("#editOptionsBar_Filter>div.editOption_active").removeClass("editOption_active");
						$('#editOption_Original').addClass('editOption_active');
						$('.editOption_active span.editOptionNameSpan').after('<span class="after"></span>');
						$('.editOption_active span').css('left', ($('.editOption_active').outerWidth() / 2) - ($('.editOption_active span.after').outerWidth() / 2));
						currentFilter = 'editOption_Original';

						drawImageObject();
					}
				}
			}
		});
    });

	$('#editTools_Undo').on('click', function () {
		undo();
	});

	$('#editTools_Redo').on('click', function () {
		redo();
	});

	var _config = {
		canvasState        : [],
		lastCanvasState    : {},
		currentStateIndex  : -1,
		undoStatus         : false,
		redoStatus         : false,
	};

	canvas.on(
		'object:modified', function() {
			updateCanvasState(0,null);
			if(!imageFlag) {
				if(_config.canvasState.length-1 < 1 && (_config.currentStateIndex < 1)) {
					$('#editTools_Undo').addClass('editorToolType_blocked');
				}
				if((_config.canvasState.length-1) == _config.currentStateIndex) {
					$('#editTools_Redo').addClass('editorToolType_blocked');
				}
			} else {
				if((_config.canvasState.length-1 < 2) && (_config.currentStateIndex < 2)) {
					$('#editTools_Undo').addClass('editorToolType_blocked');
					$('#editTools_Redo').addClass('editorToolType_blocked');
				}
			}
		}
	);

	canvas.on(
		'object:added', function() {
			updateCanvasState(0,null);
			if(!imageFlag) {
				if((_config.canvasState.length-1 < 1) && (_config.currentStateIndex < 1)) {
					$('#editTools_Undo').addClass('editorToolType_blocked');
				}
				if((_config.canvasState.length-1) == _config.currentStateIndex) {
					$('#editTools_Redo').addClass('editorToolType_blocked');
				}
			} else {
				if((_config.canvasState.length-1 < 2) && (_config.currentStateIndex < 2)) {
					$('#editTools_Undo').addClass('editorToolType_blocked');
					$('#editTools_Redo').addClass('editorToolType_blocked');
				}
			}
		}
	);

	var updateCanvasState = function(id, val) {
		var indexToBeInserted;
		if((_config.undoStatus == false && _config.redoStatus == false)) {
			var jsonData = canvas.toJSON();
			
			if(imageFlag && _config.canvasState.length == 1) {
				_config.canvasState[_config.canvasState.length-1]['id'] = currentFilter;
				id = currentFilter;
			}
			
			jsonData.id = id;
			jsonData.val = val;
			for(var i = 0; i < idArray.length; i++) {
				if((idArray[i] != 0 && idArray[i] == id) || (typeof idArray[i] == "string" && idArray[i].includes('editOption') && typeof id == "string" && id.includes('editOption') )) {
					
				}
				if(id != 0 && idArray[i] != 0 && !id.includes('editOption') && idArray[i] == id) {
					idArray.splice(i, 1);
					valueArray.splice(i, 1);
				}
			}
			if(typeof id == "string" && id.includes('editOption')) {
				idArray[0] = id;
			} else 
			if(id != 0 && id.indexOf('editOption') == -1) {
				idArray.push(id);
				valueArray.push(val);
			}
			jsonData.imageWidth = newImageWidth;
			jsonData.imageHeight = newImageHeight;

			var canvasAsJson = JSON.stringify(jsonData);
			if(_config.currentStateIndex < _config.canvasState.length-1) {
				indexToBeInserted = _config.currentStateIndex + 1;
				_config.canvasState[indexToBeInserted] = canvasAsJson;
				var numberOfElementsToRetain = indexToBeInserted + 1;
				_config.canvasState = _config.canvasState.splice(0, numberOfElementsToRetain);
			} else {
				if(id != 0 && _config.canvasState.length >= 2) {
					if(id === JSON.parse(_config.canvasState[_config.canvasState.length-1])['id']) {
						_config.canvasState.splice(_config.canvasState.length-1, 1);
					}
				}
				_config.canvasState.push(canvasAsJson);
				_config.lastCanvasState['id'] = idArray;
				_config.lastCanvasState['value'] = valueArray;
				_config.lastCanvasState['imageWidth'] = newImageWidth;
				_config.lastCanvasState['imageHeight'] = newImageHeight;
			}
			_config.currentStateIndex = _config.canvasState.length-1;
		}

		if((_config.canvasState.length-1 > 1) && (_config.currentStateIndex > 1)) {
			$('#editTools_Undo').removeClass('editorToolType_blocked');
		}

		if((_config.canvasState.length-1) != _config.currentStateIndex) {
			$('#editTools_Redo').removeClass('editorToolType_blocked');
		}
	}

	var undo = function() {
		if (!$('#editTools_Undo').hasClass("editorToolType_blocked")) {
			if(_config.currentStateIndex == 0) {
				_config.undoStatus = false;
			} else {
				if (_config.canvasState.length >= 1) {
					if(_config.currentStateIndex > 1) {
						_config.undoStatus = true;
						canvas.loadFromJSON(_config.canvasState[_config.currentStateIndex-1],function() {
							canvas.item(1).hasControls = false;
							canvas.item(1).selectable = false;
							canvas.item(1).lockMovementX = canvas.item(1).lockMovementY = true;
							canvas.item(1).evented = false;

							var jsonData = JSON.parse(_config.canvasState[_config.currentStateIndex-1]);
							var old = JSON.parse(_config.canvasState[_config.currentStateIndex]);
							var i, oldValue;
							var filter = 0;
							var filterId = 0;
							var f = 0, f1 = 0, f2 = 0;
							for(i = _config.currentStateIndex; i >= 0; i--) {
								if(JSON.parse(_config.canvasState[i])['id'] != 0) {
									if((JSON.parse(_config.canvasState[i-1])['id'] != 0 && JSON.parse(_config.canvasState[i-1])['id'].indexOf('editOption') != -1)) {
										filter = 1;
										if(f2 == 0) {
											filterId = JSON.parse(_config.canvasState[i-1])['id'];
											f2 = 1;
										}
									}
									if(i != _config.currentStateIndex && JSON.parse(_config.canvasState[i])['id'] === old['id']) {
										oldValue = JSON.parse(_config.canvasState[i])['val'];
										$('#'+old['id']).val(oldValue);
										var index = $.inArray(old['id'], idArray);
										if(index != -1) {
											valueArray[index] = oldValue;
										}
										if(old['id'] != 0 && old['id'].indexOf('editOption') == -1) {
											$('#'+old['id']).rangeslider('update', true);
										}
										break;
									} else {
										if(JSON.parse(_config.canvasState[i])['val'] !== null && old['id'] != 0) {
											if(imageFlag) {
												var jsonArray = JSON.parse('<?= $this->view->editData ?>');
												if($.inArray(old['id'], jsonArray['id']) != -1) {
													var oldValue = jsonArray['value'][$.inArray(old['id'], jsonArray['id'])];
													$('#'+old['id']).val(oldValue);
												} else {
													oldValue = document.getElementById(old['id']).defaultValue;
													$('#'+old['id']).val(oldValue);
												}
												if(old['id'] != 0 && old['id'].indexOf('editOption') == -1 && f == 0) {
													var index = $.inArray(old['id'], idArray);
													if(index != -1) {
														valueArray[index] = oldValue;
														f = 1;
													}
												}
												if(old['id'] != 0 && old['id'].indexOf('editOption') != -1) {
													if(filterId != 0) {
														idArray[0] = filterId;
													} else {
														idArray[0] = 'editOption_Original';
													}
												}
											} else {
												oldValue = document.getElementById(old['id']).defaultValue;
												$('#'+old['id']).val(oldValue);
												
												if(old['id'] != 0 && old['id'].indexOf('editOption') == -1 && f == 0) {
													var index = $.inArray(old['id'], idArray);
													if(index != -1) {
														valueArray[index] = oldValue;
														f = 1;
													}
												}																								
											}
											if(old['id'] != 0 && old['id'].indexOf('editOption') == -1) {
												$('#'+old['id']).rangeslider('update', true);
											}
										}
									}
								}
							}

							if(old['id'] != 0 && old['id'].indexOf('editOption') != -1) {
								if(filterId != 0) {
									idArray[0] = filterId;
								} else {
									idArray[0] = 'editOption_Original';
								}
							}

							if(filterId != 0 && ((jsonData['id'] != 0 && jsonData['id'].indexOf('editOption') != -1) || (old['id'] != 0 && old['id'].indexOf('editOption')))) {
								$("#editOptionsBar_Filter>div.editOption_active").removeClass("editOption_active");
								$('#'+filterId).addClass('editOption_active');
								$('.editOptionType_Filter.editOption_active span.editOptionNameSpan').after('<span class="after"></span>');
								$('.editOptionType_Filter.editOption_active span').css('left', ($('.editOption_active').outerWidth() / 2) - ($('.editOption_active span.after').outerWidth() / 2));
								currentFilter = filterId;
							}
							if(idArray[0] == 'editOption_Original') {
								$("#editOptionsBar_Filter>div.editOption_active").removeClass("editOption_active");
								currentFilter = 'editOption_Original';
								$('#editOption_Original').addClass('editOption_active');
								$('.editOptionType_Filter.editOption_active span.editOptionNameSpan').after('<span class="after"></span>');
								$('.editOptionType_Filter.editOption_active span').css('left', ($('.editOption_active').outerWidth() / 2) - ($('.editOption_active span.after').outerWidth() / 2));
							}

							if(jsonData['objects'][1]['src'] !== old['objects'][1]['src']) {
								$('.set_Crop_Option').parent().removeClass('buttonInputContaner_active');
								var newLayer = jsonData['objects'][1]['src'];
								var oldLayer = old['objects'][1]['src'];

								if(newLayer.indexOf('Circle') != -1) {
									$('#set_Crop_Circle').parent().addClass('buttonInputContaner_active');
									$('#set_Crop_Square').parent().removeClass('buttonInputContaner_active');
								} else {
									$('#set_Crop_Square').parent().addClass('buttonInputContaner_active');
									$('#set_Crop_Circle').parent().removeClass('buttonInputContaner_active');
								}
							}
							canvas.renderAll();
							_config.undoStatus = false;
							_config.currentStateIndex -= 1;
							if(_config.currentStateIndex !== _config.canvasState.length-1) {
								$('#editTools_Redo').removeClass('editorToolType_blocked');
							}
							if(_config.currentStateIndex == 1) {
								if(!$('#editTools_Undo').hasClass('editorToolType_blocked')) {
									$('#editTools_Undo').addClass('editorToolType_blocked');
								}
							}
						});
					} else
					if(_config.currentStateIndex == 1) {
						if(!$('#editTools_Undo').hasClass('editorToolType_blocked')) {
							$('#editTools_Undo').addClass('editorToolType_blocked');
						}
						$('#editTools_Redo').removeClass('editorToolType_blocked');
					}
				}
			}
		}
	}

	var indexForRedo;
	var redo = function() {
		if (!$('#editTools_Redo').hasClass("editorToolType_blocked")) {
			if((_config.currentStateIndex == _config.canvasState.length-1) && _config.currentStateIndex != -1) {
				$('#editTools_Redo').addClass('editorToolType_blocked');
			} else {
				if (_config.canvasState.length > _config.currentStateIndex && _config.canvasState.length != 1) {
					_config.redoStatus = true;
					if(_config.currentStateIndex == 0) {
						indexForRedo = _config.currentStateIndex + 2;
					} else {
						indexForRedo = _config.currentStateIndex + 1;
					}
					var f = 0;
					canvas.loadFromJSON(_config.canvasState[indexForRedo],function() {
						canvas.item(1).hasControls = false;
						canvas.item(1).selectable = false;
						canvas.item(1).lockMovementX = canvas.item(1).lockMovementY = true;
						canvas.item(1).evented = false;

						var old = JSON.parse(_config.canvasState[indexForRedo - 1]);
						var jsonData = JSON.parse(_config.canvasState[indexForRedo]);

						if(jsonData['id'] != 0) {
							$('#'+jsonData['id']).val(jsonData['val']);
							if(jsonData['id'] != 0 && jsonData['id'].indexOf('editOption') == -1) {
								$('#'+jsonData['id']).rangeslider('update', true);
							}
							if(jsonData['id'].indexOf('editOption') != -1) {
								var index = $.inArray(jsonData['id'], idArray);
								if(index == -1 && f == 0) {
									f = 1;
								}
							}
						}
						var f2 = 0;
						if(idArray.length == 0 && jsonData['id'] != 0 && jsonData['id'].indexOf('editOption') != -1) {
							idArray.push(jsonData['id']);
							valueArray.push(jsonData['val']);
						} else
						if(idArray.length != 0) {
							for(var i = idArray.length-1; i >= 0; i--) {
								if(idArray[i] == jsonData['id'] && jsonData['id'] != 0 && jsonData['id'].indexOf('editOption') == -1) {
									valueArray[i] = jsonData['val'];
								}
							}
						}

						if(jsonData['id'] != 0 && jsonData['id'].indexOf('editOption') != -1) {
							idArray[0] = jsonData['id'];
							$("#editOptionsBar_Filter>div.editOption_active").removeClass("editOption_active");
							$('#'+jsonData['id']).addClass('editOption_active');
							currentFilter = jsonData['id'];
						}

						if(jsonData['objects'][1]['src'] !== old['objects'][1]['src']) {
							var newLayer = jsonData['objects'][1]['src'];
							var oldLayer = old['objects'][1]['src'];

							if(newLayer.indexOf('Circle') != -1) {
								$('#set_Crop_Circle').parent().addClass('buttonInputContaner_active');
								$('#set_Crop_Square').parent().removeClass('buttonInputContaner_active');
							} else {
								$('#set_Crop_Square').parent().addClass('buttonInputContaner_active');
								$('#set_Crop_Circle').parent().removeClass('buttonInputContaner_active');
							}
						}

						canvas.renderAll();
						_config.redoStatus = false;
						_config.currentStateIndex += 1;
						if(_config.currentStateIndex != 0) {
							$('#editTools_Undo').removeClass('editorToolType_blocked');
						}
						if((_config.currentStateIndex == _config.canvasState.length-1) && _config.currentStateIndex != -1) {
							$('#editTools_Redo').addClass('editorToolType_blocked');
						}
					});
				}
			}
		}
	}

	$('#editTools_Save').on('click', function() {
		_config.lastCanvasState['data'] = JSON.parse(_config.canvasState[_config.currentStateIndex]);

		var canvasAsJson = JSON.stringify(_config.lastCanvasState);

		//store data into Database
		var imgData = JSON.stringify(saveCropImage());
		var originalImageId = '<?php echo  $this->view->mediaImageId;?>';
		var imageResource = '<?php echo  $this->view->imageResource;?>';
		var JsObjRef = '<?php echo  $this->view->JsObjRef;?>';
		var ajaxUrl = '<?php echo  L8M_Library::getSchemeAndHttpHost() . $this->view->url(array('module'=>'system', 'controller'=>'media', 'action'=>'edit-image'), NULL, TRUE)?>';
		var postData = {
			'editData' : canvasAsJson,
			'imgData'  : imgData,
			'originalImageId' : originalImageId,
			'imageResource' : imageResource
		};

		$.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: postData,
			dataType: 'json',
			success: function(response) {
				$('#' + JsObjRef + 'IMG', parent.document).attr("src", response.previewLink);
				$('#' + JsObjRef, parent.document).val(response.editImageId);
				$('.' + JsObjRef + 'EditImageClass', parent.document).attr("id",response.editImageId);
				$('#' + JsObjRef + 'download', parent.document).attr('href', '/media/' + response.editImageId);

				currentImageLink = response.previewLink;

				var tableName = imageResource.split('.')[0];
				var fieldName = imageResource.split('.')[2];
				$(window.parent.document).find('#imageResource_' + fieldName).remove();
				$(window.parent.document).find('form#' + tableName + '_form').append('<input id="imageResource_' + fieldName + '" name="image_resource_' + fieldName + '" type="hidden" value="' + imageResource + '"/>');

				$("#save-media-dialogue").dialog({
					resizable: false,
					modal: true,
					buttons: {
						"<?php echo $this->view->translate('ok'); ?>": function() {
							$(this).dialog("close");
							$(window.parent.document).find('div.mediaBrowserPopUpBackground').remove();
							$(window.parent.document).find('div.mediaBrowserPopUp').remove();
						}
					}
				});
			}
		}).fail(function(response) {
			if(window.console && window.console.log) {
				console.log(response);
			}
		});
	});

	function saveCropImage() {
		canvas.sendToBack(canvas.item(1));
		canvas.renderAll();
		var imgTop = canvas.height / 2 - (selectionAreaHeight / 2);
		var imgLeft = canvas.width / 2 - (selectionAreaWidth / 2);
		cropImageUrl = canvas.toDataURL({
			format: 'png',
			left: imgLeft,
			top: imgTop,
			width: selectionAreaWidth,
			height: selectionAreaHeight
		});
		canvas.sendToBack(canvas.item(1));
		canvas.renderAll();
		return cropImageUrl;
	}

	/**
	 * jQuery UI widget style fix due to clash with bootstarp classes
	 */
	$.widget("ui.dialog", $.ui.dialog, {
		open: function() {
			$(this.uiDialogTitlebarClose).addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only');
			$(this.uiDialogTitlebarClose).html("<span class=\"ui-button-icon-primary ui-icon ui-icon-closethick\"></span><span class=\"ui-button-text\">Close</span>");
			return this._super();
		}
	});

	var valueBubble = '<output class="rangeslider__value-bubble">';
	function updateValueBubble(pos, value, context) {
		pos = pos || context.position;
		value = value || context.value;
		var $valueBubble = $('.rangeslider__value-bubble', context.$range);
		var tempPosition = pos + context.grabPos;
		var position = (tempPosition <= context.handleDimension) ? context.handleDimension : (tempPosition >= context.maxHandlePos) ? context.maxHandlePos : tempPosition;

		if ($valueBubble.length) {
			$valueBubble[0].style.left = Math.ceil(position) + 'px';
			if(context.$element[0].id == 'zoomChange') {
				value = parseFloat(value) * 100;
				value = value.toFixed(1) + ' %';
			} else
			if(context.$element[0].id == 'rotateChange') {
				value = value.toFixed(0) + ' &#176;';
			} else
			if(context.$element[0].id == 'hueChange') {
				value = value.toFixed(3) / 2;
			} else
			if(context.$element[0].id == 'noiseChange') {
				value = value / 10;
				value = value.toFixed(1) + ' %';
			} else
			if(context.$element[0].id == 'pixelateChange') {
				value = value * 10;
				value = value.toFixed(1) + ' %';
			} else
			if(context.$element[0].id == 'blurChange') {
				value = value * 100;
				value = value.toFixed(1) + ' %';
			} else
			if(context.$element[0].id == 'sharpenChange') {
				value = (value - 4.9) * 20;
				value = value.toFixed(2) + ' %';
			}
			$valueBubble[0].innerHTML = value;
		}
	}

	$('input[type="range"]').rangeslider({
		polyfill: false,
		horizontalClass: 'rangeslider--horizontal',
		onInit: function() {
			this.$range.append($(valueBubble));
			updateValueBubble(null, null, this);
		},
		onSlide: function(pos, value) {
			updateValueBubble(pos, value, this);
		}
	});
});

function scrapCode() {
	/* if(angle == 0) {
		if(boundingRect.top > selectionAreaTop) {
			movedImage.top = selectionAreaTop;
		}

		if(boundingRect.left > selectionAreaLeft) {
			movedImage.left = selectionAreaLeft;
		}

		if(boundingRect.top < (selectionAreaBottom - boundingRect.height)) {
			movedImage.top = selectionAreaBottom - boundingRect.height;
		}

		if(boundingRect.left < (selectionAreaRight - boundingRect.width)) {
			movedImage.left = selectionAreaRight - boundingRect.width;
		}
	} else
	if(angle == -180 || angle == 180) {
		if(boundingRect.top > selectionAreaTop) {
			movedImage.top = selectionAreaTop + boundingRect.height;
		}

		if(boundingRect.left > selectionAreaLeft) {
			movedImage.left = selectionAreaLeft + boundingRect.width;
		}

		if(boundingRect.top < (selectionAreaBottom - boundingRect.height)) {
			movedImage.top = selectionAreaBottom;
		}

		if(boundingRect.left < (selectionAreaRight - boundingRect.width)) {
			movedImage.left = selectionAreaRight;
		}
	} else
	if(angle == 90) {
        if(boundingRect.top > selectionAreaTop){
            movedImage.top = selectionAreaTop;
        }

        if(boundingRect.left > selectionAreaLeft){
            movedImage.left = selectionAreaLeft + boundingRect.width;
        }

        if (boundingRect.top < selectionAreaBottom - boundingRect.height){
            movedImage.top = selectionAreaBottom - boundingRect.height;
        }

        if (boundingRect.left < selectionAreaRight - boundingRect.width) {
            movedImage.left = selectionAreaRight;
		}
	} else
    if(angle == -90) {
        if(boundingRect.top > selectionAreaTop) {
            movedImage.top = selectionAreaTop + boundingRect.height;
        }

        if(boundingRect.left > selectionAreaLeft) {
            movedImage.left = selectionAreaLeft;
        }

        if(boundingRect.top < (selectionAreaBottom - boundingRect.height)) {
            movedImage.top = selectionAreaBottom;
        }

        if(boundingRect.left < (selectionAreaRight - boundingRect.width)) {
            movedImage.left = selectionAreaRight - boundingRect.width;
        }
	} */

	/* var lowerAngle = 0;
		var higherAngle = 0;
		for(var angleCounter = -180; angleCounter <= 180; angleCounter = angleCounter + 15) {
			if(angleCounter < angleVal) {
				lowerAngle = angleCounter;
			} else
			if(angleCounter > angleVal) {
				higherAngle = angleCounter;
				break;
			}
		}

		var multiplier = 1;
		if(Math.abs(lowerAngle) > Math.abs(higherAngle)) {
			multiplier = -1;
		}

		if(Math.abs(lowerAngle - angleVal) < Math.abs(higherAngle - angleVal)) {
			angleVal = Math.abs(lowerAngle) * multiplier;
		} else {
			angleVal = Math.abs(higherAngle) * multiplier;
		}

		$(this).val(angleVal); */
}

<?php

$this->view->headScript()->captureEnd();
    }
}