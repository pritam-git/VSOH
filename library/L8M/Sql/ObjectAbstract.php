<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Object/ObjectAbstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ObjectAbstract.php 424 2015-09-21 14:24:22Z nm $
 */

/**
 *
 *
 * L8M_Sql_ObjectAbstract
 *
 *
 */
class L8M_Sql_ObjectAbstract implements ArrayAccess
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	protected $_valueArray = array();
	protected $_allowedColumns = array();
	protected $_modelClassName = NULL;

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 * Cunstruct L8M_Sql_ObjectAbstract.
	 *
	 * @param array $valueArray
	 * @param array $allowedColumns
	 * @param String $modelClassName
	 * @return L8M_Sql_ObjectAbstract
	 */
	public function __construct($valueArray, $allowedColumns, $modelClassName = FALSE) {
		if (is_array($valueArray)) {
			$this->_valueArray = $valueArray;
		}
		if (is_array($allowedColumns)) {
			$this->_allowedColumns = $allowedColumns;
		}
		if ($modelClassName &&
			class_exists($modelClassName)) {

			$this->_modelClassName = $modelClassName;
		}
	}

	/**
	 * Check if key exists in data
	 *
	 * @param   string $property
	 * @return  boolean whether or not this object contains $name
	 */
	public function __isset($property) {
		$returnValue = array_key_exists($property, $this->_valueArray);
		return $returnValue;
	}

	/**
	 * Remove key from data
	 *
	 * @param   string $property
	 * @return  void
	 */
	public function __unset($property) {
		unset($this->_valueArray[$property]);
	}

	/**
	 * Check if an offset exists
	 *
	 * @param   mixed $offset
	 * @return  boolean Whether or not this object contains $offset
	 */
	public function offsetExists($offset) {
		$returnValue = array_key_exists($offset, $this->_valueArray);
		return $returnValue;
	}

	/**
	 * An alias of get()
	 *
	 * @see     get, __get
	 * @param   mixed $offset
	 * @return  mixed
	 */
	public function offsetGet($offset) {
		return $this->__get($offset);
	}

	/**
	 * Sets $offset to $value
	 *
	 * @see     set, __set
	 * @param   mixed $offset
	 * @param   mixed $value
	 * @return  void
	 */
	public function offsetSet($offset, $value) {
		$this->__set($offset, $value);
	}

	/**
	 * Unset a given offset
	 *
	 * @see   set, offsetSet, __set
	 * @param mixed $offset
	 */
	public function offsetUnset($offset) {
		$this->__unset($offset);
	}

	/**
	 * Returns value-array as string
	 */
	public function __toString() {

		ob_start();

		/**
		 * type
		 */
		$type = self::_getType($this->_valueArray);

		/**
		 * caller
		 */
		$caller = debug_backtrace();
		$caller = $caller[0];

?>
<div class="box debug">
	<ul class="array-show iconized">
		<li class="page-white">
			<span class="key">File</span> <span class="value"><?php echo htmlentities($caller['file'], ENT_COMPAT, 'UTF-8'); ?></span>
		</li>
		<li class="page-white-code">
			<span class="key">Line</span> <span class="value"><?php echo htmlentities($caller['line'], ENT_COMPAT, 'UTF-8'); ?></span>
		</li>
		<li class="page-white-database">
			<span class="key">Data <span class="type"><?php echo $type; ?></span></span>
<?php

		self::_dataTraverse($this->_valueArray);

?>
		</li>
	</ul>
</div>
<?php

		return ob_get_clean();
	}

	/**
	 * Returns type of data.
	 *
	 * @param  mixed $data
	 * @return string
	 */
	protected static function _getType($data = NULL)
	{
		if (is_object($data)) {
			$type = 'object (' . get_class($data) . ')';
		} else

		if (is_array($data)) {
			$type = 'array (' . count($data). ')';
		} else


		if (is_resource($data)) {
			$type = 'resource';
		} else

		if (is_string($data)) {
			$type = 'string (' . strlen($data). ')';
		} else

		if ($data === FALSE ||
			$data === TRUE) {
			$type = 'boolean';
		} else

		if (is_float($data)) {
			$type = 'float';
		} else

		if (is_int($data)) {
			$type = 'integer';
		} else {
			$type = 'n/a';
		}

		return $type;
	}

	/**
	 * Traverses through data and outputs it.
	 *
	 * @param  mixed $data
	 * @return void
	 */
	protected static function _dataTraverse($data = NULL, $skipFirstUl = FALSE, $withType = TRUE)
	{
		/**
		 * data is traversable
		 */
		if (is_array($data) ||
			($data instanceof Traversable) &&
			count($data)>0) {

/*

?>
<span class="value"><?php echo (($data instanceof Traversable) ? 'TRAVERSABLE' : 'ARRAY'); ?></span>
<?php

*/

			if (!$skipFirstUl) {
				echo '<ul class="last">';
			}

			foreach($data as $key=>$value) {
				echo '<li><span class="key">' . htmlentities($key, ENT_COMPAT, 'UTF-8');
				if ($withType) {
					$type = self::_getType($value);
					echo ' <span class="type">' . htmlentities($type, ENT_COMPAT, 'UTF-8') . '</span>';
				}
				echo '</span>';
				self::_dataTraverse($value, FALSE, $withType);
				echo '</li>';
			}

			if (!$skipFirstUl) {
				echo '</ul>';
			}

		} else {

			if (is_object($data)) {
				$data = get_class($data);
			} else

			if ($data === TRUE) {
				$data = 'TRUE';
			} else

			if ($data === FALSE) {
				$data = 'FALSE';
			} else

			if ($data === '') {
				$data = 'EMPTY STRING';
			} else

			if ($data === NULL) {
				$data = 'NULL';
			}
?>
<div class="value"><?php echo htmlentities($data, ENT_COMPAT, 'UTF-8'); ?></div>
<?php
		}

	}
}