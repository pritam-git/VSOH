<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/SelectImage.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: SelectImage.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_SelectImage
 *
 *
 */
class L8M_JQuery_Form_Element_SelectImage extends Zend_Form_Element_Select
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
 	 * Contains TRUE when head script has been added to initialize all select image.
 	 *
 	 * @var bool
 	 */
 	protected static $_selectImageInitialized = FALSE;

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

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Render form element
	 *
	 * @param  Zend_View_Interface $view
	 * @return string
	 */
	public function render(Zend_View_Interface $view = null)
	{

		/**
		 * set css class
		 */
		$this->setAttrib('class', 'l8mSelectImage');

		/**
		 * html for form element
		 */
		ob_start();

		/**
		 * render head script
		 */
		$layout = Zend_Layout::getMvcInstance();
		if ($layout->isEnabled()) {
			$this->_renderHeadScript();
		}

		$content = ob_get_clean();
		$content.= parent::render($view);

		return $content;
	}

	/**
	 * Renders head script.
	 *
	 * @return L8M_Form_Element_TinyMCE
	 */
	protected function _renderHeadScript()
	{
		if (!self::$_selectImageInitialized) {
			$view = $this->getView();
			if ($view) {
				$view->headScript()->captureStart();
				$this->_renderInlineScript(TRUE);
				$view->headScript()->captureEnd();

			}
			self::$_selectImageInitialized = TRUE;
		}
		return $this;
	}

	/**
	 * Returns inline script.
	 *
	 * @return void
	 */
	protected function _renderInlineScript($documentReady = FALSE)
	{

		/**
		 * document ready start
		 */
		if ($documentReady) {

?>
$(document).ready(function() {
<?php

		}

?>

	$(".l8mSelectImage").attr('style', 'display:none;');
	$(".l8mSelectImage").parent().append('<div class="l8mSelectImageContainer" rel="0"><div class="l8mSelectImages"></div></div>');

	var $kids = $(".l8mSelectImage").children();
	$kids.each(function(i) {
			$previewImg = '<img src="' + $(this).html() + '" class="l8mSelectImagePreview" alt="" />';
			$("div.l8mSelectImageContainer div.l8mSelectImages").append($previewImg);
	});

	$("div.l8mSelectImageContainer div.l8mSelectImages img.l8mSelectImagePreview:nth-child(1)").addClass('l8mSelectImagePreview_show');

	$("div.l8mSelectImageContainer").append('<span class="item">0</span> <span class="itemof"><?php echo $this->getView()->translate('of'); ?></span> <span class="max">0</span>');
	$("div.l8mSelectImageContainer").append('<a href="" class="prev">Previous</a>');
	$("div.l8mSelectImageContainer").append('<a href="" class="next">Next</a>');

	$max = $(".l8mSelectImage").children().length;
	if ($max > 0) {
		$("div.l8mSelectImageContainer").attr('rel', 1);
		$("div.l8mSelectImageContainer span.item").html(1);
		$("div.l8mSelectImageContainer span.max").html($max);
		$(".l8mSelectImage option:nth-child(1)").attr('selected', 'selected');
	}

	$("div.l8mSelectImageContainer a.prev").click(function () {
		$n = $("div.l8mSelectImageContainer").attr('rel');
		$max = $(".l8mSelectImage").children().length;

		if ($max > 0 &&
			$n > 1) {

			$("div.l8mSelectImageContainer div.l8mSelectImages img.l8mSelectImagePreview:nth-child(" + $n + ")").removeClass('l8mSelectImagePreview_show');
			$(".l8mSelectImage option:nth-child(" + $n + ")").removeAttr('selected');
			$n--;
			$("div.l8mSelectImageContainer div.l8mSelectImages img.l8mSelectImagePreview:nth-child(" + $n + ")").addClass('l8mSelectImagePreview_show');
			$(".l8mSelectImage option:nth-child(" + $n + ")").attr('selected', 'selected');

			$("div.l8mSelectImageContainer").attr('rel', $n);
			$("div.l8mSelectImageContainer span.item").html($n);
		}
		return false;
	});

	$("div.l8mSelectImageContainer a.next").click(function () {
		$n = $("div.l8mSelectImageContainer").attr('rel');
		$max = $(".l8mSelectImage").children().length;

		if ($max > 0 &&
			$n < $max) {

			$("div.l8mSelectImageContainer div.l8mSelectImages img.l8mSelectImagePreview:nth-child(" + $n + ")").removeClass('l8mSelectImagePreview_show');
			$(".l8mSelectImage option:nth-child(" + $n + ")").removeAttr('selected');
			$n++;
			$("div.l8mSelectImageContainer div.l8mSelectImages img.l8mSelectImagePreview:nth-child(" + $n + ")").addClass('l8mSelectImagePreview_show');
			$(".l8mSelectImage option:nth-child(" + $n + ")").attr('selected', 'selected');

			$("div.l8mSelectImageContainer").attr('rel', $n);
			$("div.l8mSelectImageContainer span.item").html($n);
		}
		return false;
	});

<?php

		/**
		 * document ready end
		 */
		if ($documentReady) {
?>
});
<?php
		}

	}
}