<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/File.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: File.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_File
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_File extends ZFDebug_Controller_Plugin_Debug_Plugin_File
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
		return number_format(count($this->_getIncludedFiles()), 0, ',', '.') . ' Files';
	}

	/**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{

		/**
		 * included files
		 */
		$includedFiles = $this->_getIncludedFiles();

		/**
		 * total file size
		 */
		$totalFileSize = 0;
		foreach ($includedFiles as $file) {
			$totalFileSize += filesize($file);
		}

		/**
		 * application files
		 */
		$applicationFiles = array();

		/**
		 * library files
		 */
		$libraryFiles = array();

		foreach ($includedFiles as $file) {
			$file = str_replace($this->_basePath, '', $file);
			$inUserLib = false;
			foreach ($this->_library as $key => $libraryPrefix) {
				if ($libraryPrefix != '' &&
					strstr($file, $libraryPrefix) != FALSE) {
					$libraryFiles[$key]['prefix'] = $libraryPrefix;
					$libraryFiles[$key]['files'][] = $file;
					$inUserLib = TRUE;
				} else {
					// die($library);
				}
			}
			if (!$inUserLib) {
				$applicationFiles[] = $file;
			}

		}

		ob_start();

?>
<h4>File Information</h4>
<?php

		L8M_Library::dataShow(array(
			'Files included'=>count($includedFiles),
			'Total Size'=>L8M_Library::getBytes($totalFileSize),
			'Base Path'=>$this->_basePath,
		));
?>
<p><a class="iconized folder-explore require-once" href="/utility/require.php" title="Remove unneeded 'require_once' statements">Remove unneeded 'require_once' statements</a> <span class="require-once-data">&nbsp;</span></p>
<script type="text/javascript">

$(document).ready(function() {

	////////////////////////////////////////////////////////////
	// require once link
	////////////////////////////////////////////////////////////

	$("a.require-once").click(function(event) {
		jQuery.ajax({
			url: "/utility/require.php",
			data: {
				quiet: true
			},
			type: "GET",
			cache: false,
			complete: function (request, textStatus) {
			},
			success: function (data, textStatus) {
				$("#ZFDebug_debug span.require-once-data").html(data);
			},
			error: function (request, textStatus, errorThrown) {
				$("#ZFDebug_debug span.require-once-data").html(textStatus);
			}
 		});
		return false;
	});

});
</script>
<h4>Application Files</h4>
<ul>
	<li><?php echo implode('</li><li>', $applicationFiles); ?></li>
</ul>
<?php

		foreach($libraryFiles as $key=>$library) {

?>
<h4><?php echo $library['prefix']; ?> Library Files</h4>
<ul>
	<li><?php echo implode('</li><li>', $library['files']); ?></li>
</ul>
<?php

		}
		return ob_get_clean();
	}

}