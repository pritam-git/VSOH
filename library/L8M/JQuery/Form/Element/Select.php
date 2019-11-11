<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/Select.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Select.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_Select
 *
 *
 */
class L8M_JQuery_Form_Element_Select extends Zend_Form_Element_Select
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of ids of selects that need to be updated when this select boxes
	 * value has changed.
	 *
	 * @var array
	 */
	protected $_onChange = NULL;

	/**
	 * table that is _column related to
	 *
	 * @var string
	 */
	protected $_key = NULL;

	/**
	 * column name the value should be matched with.
	 *
	 * @var string
	 */
	protected $_column = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * set the Key onChange should send with.
	 *
	 * @param string $name
	 * @return L8M_JQuery_Form_Element_Select
	 */
	public function setKey($name) {
		$this->_key = $name;

		return $this;
	}

	/**
	 * set the Column onChange should send with.
	 *
	 * @param string $name
	 * @return L8M_JQuery_Form_Element_Select
	 */
	public function setColumn($name) {
		$this->_column = $name;

		return $this;
	}

	/**
	 * Specifies the id of a select box which' content is updated when the value
	 * of this L8M_JQuery_Form_Element_Select has changed.
	 *
	 * @param  string $id
	 * @return L8M_JQuery_Form_Element_Select
	 */
	public function updateOnChange($id = NULL, $url = NULL)
	{
		if (!is_string($id)) {
			/**
			 * @todo throw an exception
			 */
		}
		if (!isset($this->_onChange[$id])) {
			$this->_onChange[$id] = $url;
		}
		return $this;
	}

	/**
	 * Render form element
	 *
	 * @param  Zend_View_Interface $view
	 * @return string
	 */
	public function render(Zend_View_Interface $view = null)
	{
		$content = parent::render($view);

		ob_start();

		if (is_array($this->_onChange) &&
			count($this->_onChange)>0) {

?>
<script type="text/javascript">
$(document).ready(function() {
<?php

			foreach($this->_onChange as $id => $url) {
?>
	$("select#<?php echo $this->getId(); ?>").change(function(){
		$("img.ajax-load").fadeIn();
		$.getJSON("<?php echo $url; ?>",{key: '<?php echo $this->_key; ?>', column: '<?php echo $this->_column; ?>', value: $(this).attr('value')}, function(data){
			var options = '';
			$.each(data.items, function(i,item) {
				options += '<option value="' + item.id + '">' + item.name + '</option>';
			});
			$("select#<?php echo $id; ?>").html(options).change();
		});
		$("img.ajax-load").fadeOut();
	});
<?php
			}
?>
});
</script>
<?php

		}

		$content.= ob_get_clean();

		return $content;
	}

}