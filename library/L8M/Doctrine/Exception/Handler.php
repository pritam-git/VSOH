<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Exception/Handler.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Handler.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Exception_Handler
 *
 *
 */
class L8M_Doctrine_Exception_Handler
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Handles Doctrine Exception, i.e., renders it.
	 *
	 * @param  Doctrine_Exception $exception
	 * @param  string             $prepend
	 * @return void
	 */
	public static function handleException($exception = NULL, $prepend = '</ul>')
	{
		if (!$exception instanceof Doctrine_Exception) {
			return;
		}

		echo $prepend;
?>
<h1>An exception has been thrown</h1>
<p><code><?php echo $exception->getMessage(); ?></code></p>
<?php

		$trace = explode('#', $exception->getTraceAsString());
		array_shift($trace);

		if (count($trace)>0) {
?>
<h2>Trace</h2>
<ul>
<?php
			foreach($trace as $traceStep) {
?>
	<li><?php echo $traceStep; ?></li>
<?php
			}

?>
</ul>
<?php
		}

		die();

	}

}