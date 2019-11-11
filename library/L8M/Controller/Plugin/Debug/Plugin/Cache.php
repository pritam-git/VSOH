<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Cache.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Cache.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Cache
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Cache extends ZFDebug_Controller_Plugin_Debug_Plugin_Cache
{

	/**
	 *
	 *
	 * Interface Methods
	 *
	 *
	 */

	/**
	 * Gets identifier for this plugin
	 *
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Gets menu tab for the Debugbar
	 *
	 * @return string
	 */
	public function getTab()
	{
		$cacheCount = 0;
		foreach ($this->_cacheBackends as $name => $backend) {
			$cacheCount+= count($backend->getIds());
		}
		return 'Cache (<span class="cache-tab">' . $cacheCount . '/' . count($this->_cacheBackends) . '</span>)';
	}

	/**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{

		ob_start();

?>
<h4>Cache</h4>
<p><a class="iconized lightning cache-clear" href="/system/cache/clear/" title="Clear All Caches">Clear All Caches</a></p>
<div class="cache-data">
<?php
		/**
		 * apc
		 */
		if (function_exists('apc_sma_info') && ini_get('apc.enabled')) {
			$mem = apc_sma_info();
			$mem_size = $mem['num_seg']*$mem['seg_size'];
			$mem_avail = $mem['avail_mem'];
			$mem_used = $mem_size-$mem_avail;

			$cache = apc_cache_info();

?>
<h4>APC <?php echo phpversion('apc'); ?> Enabled</h4>
<?php


			L8M_Library::dataShow(array(
				'Available'=>L8M_Library::getBytes($mem_avail),
				'Used'=>L8M_Library::getBytes($mem_used),
				'Cached Files'=>$cache['num_entries'] . ' (' .  L8M_Library::getBytes($cache['mem_size']) . ')',
				'Hits'=>$cache['num_hits'] . ' (' . round($cache['num_hits'] * 100 / ($cache['num_hits']+$cache['num_misses']), 1) . '%',
				'Misses'=>$cache['num_misses'] . '(' . round($cache['num_misses'] * 100 / ($cache['num_hits']+$cache['num_misses']), 1),
				'Expunges'=>$cache['expunges'] . '(cache full count)',
			));

		}

		/**
		 * backends
		 */
		foreach ($this->_cacheBackends as $name => $backend) {

			$fillingPercentage = $backend->getFillingPercentage();
			$ids = $backend->getIds();

			$cacheSize = 0;
			foreach ($ids as $id)
			{
				# Calculate valid cache size
				$mem_pre = memory_get_usage();
				$cached = $backend->load($id);
				if ($cached) {
					$mem_post = memory_get_usage();
					$cacheSize += $mem_post-$mem_pre;
					unset($cached);
				}
			}

?>
<h4>Cache <code><?php echo $name; ?></code> (<?php echo count($ids); ?>)</h4>
<div id="<?php echo $name . '-data'; ?>">
<?php

			L8M_Library::dataShow(array(
//				'Class'=>get_class($backend),
				'Valid Cache Size'=>L8M_Library::getBytes($cacheSize),
				'Filling Percentage'=>$backend->getFillingPercentage() . '%',
				'Entries'=>count($ids),
			));

?>
</div>
<p><a id="<?php echo $name; ?>" class="iconized lightning cache-clear" href="/system/cache/clear/id/<?php echo $name;?>" title="Clear this Cache">Clear this Cache</a></p>
<?php

		}

?>
</div>
<script type="text/javascript">

$(document).ready(function() {

	////////////////////////////////////////////////////////////
	// clear cache link
	////////////////////////////////////////////////////////////

	$("a.cache-clear").click(function(event) {
		var id = event.target.id
		var replace = id;
		if (replace != "") {
			replace = ' #' + replace + '-data';
		}
		jQuery.ajax({
			url: "/system/cache/clear/format/html",
			data: {
				id: id
			},
			type: "GET",
			cache: false,
			complete: function (request, textStatus) {
			},
			success: function (data, textStatus) {
				$("#ZFDebug_debug span.cache-tab").html(data);
				$("#ZFDebug_debug div.cache-data" + replace).html("");
			},
			error: function (request, textStatus, errorThrown) {
				$("#ZFDebug_debug div.cache-data" + replace).html(textStatus);
			}
 		});
		return false;
	});

});
</script>
<?php

		return ob_get_clean();
	}
}