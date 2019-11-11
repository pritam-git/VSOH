<?php
/**
 * L8M
 * 
 *
 * @filesource /library/L8M/View/Helper/Tags.php
 * 
 * @abstract   contains L8M_View_Helper_Tags class, extending 
 *             Zend_View_Helper_HeadScript
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Tags.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_Tags
 * 
 * 
 */
 class L8M_View_Helper_Tags extends Zend_View_Helper_Abstract
{

	/**
	 * Builds an A-Href-Tag and returns it.
	 * 
	 * @param string|array	$href 		Link to the source.
	 * @param string		$name		Name or Content of the link.	
	 * @param string|array	$cssClass	Css-Class(es).
	 * @param string|array	$css		Embedded CSS.
	 * @param string		$target		Target for link.
	 * @return string
	 */
	static function aTag($href = "", $name = "", $cssClass = array(), $css = array(), $target = "")
	{
		if (is_array($href))
		{
			/**
			 * todo
			 * make it work as static
			 */
			$helper = new Zend_View_Helper_Url();
			if (!isset($href["lang"]))
			{
				$href["lang"] = Zend_Registry::get('Zend_Locale')->getLanguage();
			}
			$source = $helper->url($href);
		} else {
			if (is_string($href)) {
				$source = $href;
			} else {
				$source = "";
			}
		}
		if ($target != "") {
			$target = 'target="' . $target . '"';
		}
		$tmpCssClass = self::mergeCssClass($cssClass);
		$tmpCss = self::mergeCss($css);
		$attrib = self::mergeAttributes(array($tmpCssClass, $tmpCss, $target), FALSE);
		ob_start();
?>
<a href="<?php echo $source; ?>"<?php echo $attrib; ?>><?php echo $name;?></a>
<?php
		return ob_get_clean();
	
	}
	
	/**
	 * Builds an Image-Tag and returns it.
	 * 
	 * @param string 		$source 	Link to the source.
	 * @param string		$alt		Alternative text.
	 * @param string|array	$cssClass	Css-Class(es).
	 * @param string|array	$css		Embedded CSS.
	 * @return string
	 */
	static function imgTag($source = "", $alt = "", $cssClass = array(), $css = array())
	{
		$imgCssClass = self::mergeCssClass($cssClass);
		$imgCss = self::mergeCss($css);
		$attrib = self::mergeAttributes(array($imgCssClass, $imgCss));
		ob_start();
?>
<img src="<?php echo $source; ?>" alt="<?php echo $alt; ?>"<?php echo $attrib; ?>/>
<?php
		return ob_get_clean();
	
	}
	
	/**
	 * 
	 * building up the css class statement for the tags
	 * 
	 * @param string|array	$cssClass
	 * @return string
	 */
	protected static function mergeCssClass($cssClass)
	{
		$tmpCssClass = "";
		if (is_array($cssClass))
		{
			if (count($cssClass) > 0) {
				$tmpCssClass = 'class="' . implode(" ", $cssClass) . '"';
			}
		} else {
			if (is_string($cssClass))
			{
				if (strlen(trim($cssClass)) > 0)
				{
					$tmpCssClass = 'class="' . $cssClass . '"';
				}
			}
		}
		return $tmpCssClass;
	}
	
	/**
	 * 
	 * building up the css-style statement for the tags
	 * 
	 * @param string|array $css
	 * @return string
	 */
	protected static function mergeCss($css)
	{
		$tmpCss = "";
		if (is_array($css))
		{
			if (count($css) > 0) {
				$cssTpl = "";
				foreach ($css as $key=>$value)
				{
					$cssTpl .= "$key:$value; ";
				}
				$tmpCss = 'style="' . trim($cssTpl) . '"';
			}
		} else {
			if (is_string($css))
			{
				if (strlen(trim($css)) > 0)
				{
					$tmpCss = 'style="' . $css . '"';
				}
			}
		}
		return $tmpCss;
	}
	
	/**
	 * 
	 * building up the atributes in a merged version including one space before and after
	 * 
	 * @param array 	$attributes
	 * @param boolean	$rightSpace
	 * @return string
	 */
	protected static function mergeAttributes($attributes, $rightSpace = TRUE)
	{
		$tmpAttrib = "";
		if (is_array($attributes))
		{
			for ($i = 0; $i < count($attributes); $i++)
			{
				if (trim($attributes[$i]) !== "")
				{
					$tmpAttrib .= " " . trim($attributes[$i]);
				}
			}
		}
		if (trim($tmpAttrib) != "")
		{
			if ($rightSpace)
			{
				$tmpAttrib = " " . $tmpAttrib . " ";
			} else {
				$tmpAttrib = " " . $tmpAttrib;
			}
		} else {
			$tmpAttrib = "";
		}
		return $tmpAttrib;
	}
}