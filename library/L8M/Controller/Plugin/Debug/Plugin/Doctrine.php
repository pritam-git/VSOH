<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Doctrine.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Doctrine.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Doctrine
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Doctrine extends Danceric_Controller_Plugin_Debug_Plugin_Doctrine
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

   /**
	* Gets menu tab for the Debugbar
	*
	* @return string
	*/
	public function getTab()
	{
		if (!$this->_profilers)
			return 'No Profiler';

		foreach ($this->_profilers as $profiler) {
			$time = 0;
			foreach ($profiler as $event) {
				$time += $event->getElapsedSecs();
			}
			$profilerInfo[] = $profiler->count() . ' in ' . number_format(round($time*1000, 2), 0, ',', '.') . ' ms';
		}
		$html = implode(' / ', $profilerInfo);

		return $html;
	}

	/**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{
		if (!$this->_profilers) {
			return '';
		}

		ob_start();

?>
<h4>Doctrine Connections</h4>
<?php

		/**
		 * iterate over profilers
		 */
		foreach ($this->_profilers as $name => $profiler) {

?>
<h4>Connection <code><?php echo $name; ?></code> (<?php echo count($profiler); ?>)</h4>
<?php

			/**
			 * any events?
			 */
			if (count($profiler)>0) {

?>
<ul class="array-show">
	<li><span class="key">enabled</span> <span class="value"><?php echo (Zend_Db_Table_Abstract::getDefaultMetadataCache() ? 'yes' : 'no'); ?></span></li>
</ul>
<h5>Connection <code><?php echo $name; ?></code> Queries</h5>
<ul class="array-show">
<?php

				/**
				 * iterate over events
				 */
				foreach ($profiler as $event) {
?>
	<li><span class="label"><?php echo $event->getName(); ?></span>
		<ul>
			<li><span class="key">Elapsed Time</span> <span class="value"><?php echo round($event->getElapsedSecs()*1000, 2); ?> ms</span></li>
<?php

					if (in_array($event->getName(), array('query', 'execute', 'exec'))) {
?>
			<li><span class="key">Query String</span> <span class="value"><?php echo L8M_Geshi::parse($event->getQuery(), 'MySql'); ?></span></li>
<?php
					}

					$params = $event->getParams();
					if(!empty($params)) {
?>
			<li><span class="key">Bindings</span>
				<ul>
					<li><?php echo implode('</li><li>', $params); ?></li>
				</ul>
			</li>
<?php
					}
?>
		</ul>
	</li>
<?php
				}
?>
</ul>
<?php
			}

		}

		return ob_get_clean();
	}

}