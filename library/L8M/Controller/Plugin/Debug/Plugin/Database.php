<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Database.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Database.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Database
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Database extends ZFDebug_Controller_Plugin_Debug_Plugin_Database
{

	/**
	 *
	 *
	 * Interface Methods
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
		if (!$this->_db)
			return 'No adapter';

		foreach ($this->_db as $adapter) {
			$profiler = $adapter->getProfiler();
			$adapterInfo[] = $profiler->getTotalNumQueries() . ' in ' . number_format(round($profiler->getTotalElapsedSecs()*1000, 2), 0, ',', '.') . ' ms';
		}
		$html = implode(' / ', $adapterInfo);

		return $html;
	}

	/**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{
		if (!$this->_db) {
			return '';
		}

		ob_start();
?>
<h4>Database Adapters</h4>
<h5>Meta Data Cache</h5>
<?php
		$enabled = Zend_Db_Table_Abstract::getDefaultMetadataCache()
				 ? 'yes'
				 : 'no'
		;

		L8M_Library::dataShow(array(
			'enabled'=>$enabled,
		));

		foreach ($this->_db as $name => $adapter) {

			$profiles = $adapter->getProfiler()->getQueryProfiles();

			if ($profiles) {
				$profileData = array();
				foreach ($profiles as $profile) {
					$profileData[] = array(
						round($profile->getElapsedSecs()*1000, 2) . 'ms' =>L8M_Geshi::parse($profile->getQuery(), 'sql'),
					);
				}
			} else {
				$profileData = NULL;
			}

?>
<h4>Adapter <code><?php echo $name;  ?></code> (<?php echo count($profileData); ?>)</h4>
<?php

			if (count($profileData)>0) {

				L8M_Library::dataShow(array(
					'Queries'=>$profileData,
				));

			}

		}

		return ob_get_clean();
	}

}
