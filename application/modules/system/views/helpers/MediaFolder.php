<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/views/helpers/MediaFolder.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MediaFolder.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_View_Helper_MediaFolder
 *
 *
 */
class System_View_Helper_MediaFolder extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns the mediafolder Path
	 *
	 * @param  integer
	 * @return string
	 */
	public function mediaFolder($mediaFolderID = NULL, $showInside = FALSE)
	{

		$returnValue = NULL;

		$dirVarArray = array();
		if (isset($this->view->dirVarArray)) {
			$dirVarArray = $this->view->dirVarArray;
		}
		$dirVarArray = array_merge($dirVarArray, array('module'=>'system', 'controller'=>'media', 'action'=>'list'));

		$dirVarParams = NULL;
		if (isset($this->view->dirVarParams)) {
			$dirVarParams = $this->view->dirVarParams;
		}


		$mediaFolderModel = Doctrine_Query::create()
			->from('Default_Model_MediaFolder m')
			->where('m.id = ?', $mediaFolderID)
			->limit(1)
			->execute()
			->getFirst()
		;

		$currentMediaFolder = $mediaFolderModel;

		if ($mediaFolderModel) {

			while ($mediaFolderModel->media_folder_id) {
				$url = $this->view->url(array_merge($dirVarArray, array('mediaFolderID'=>$mediaFolderModel->id)), NULL, TRUE) . $dirVarParams;
				$link = '<a href="' . $url . '">' . $mediaFolderModel->name . '</a>';
				$returnValue = $link . DIRECTORY_SEPARATOR . $returnValue;
				$mediaFolderModel = $mediaFolderModel->MediaFolder;
			}

			$url = $this->view->url(array_merge($dirVarArray, array('mediaFolderID'=>$mediaFolderModel->id)), NULL, TRUE) . $dirVarParams;
			$link = '<a href="' . $url . '">' . $mediaFolderModel->name . '</a>';
			$returnValue = $link . DIRECTORY_SEPARATOR . $returnValue;
		}

		$url = $this->view->url(array_merge($dirVarArray, array('mediaFolderID'=>NULL)), NULL, TRUE) . $dirVarParams;
		$link = '<a href="' . $url . '">[root]</a>';
		$returnValue = $link . DIRECTORY_SEPARATOR . $returnValue;

		$dirVarArray['action'] = 'directory';
		$listInside = NULL;
		if (!$showInside) {
			$url = $this->view->url(array_merge($dirVarArray, array('mediaFolderID'=>$mediaFolderID)), NULL, TRUE) . $dirVarParams;
			$link = '<a href="' . $url . '" class="change">' . $this->view->translate('Change') . '</a>';
		} else {
			$selectDirVarArray = $dirVarArray;
			$selectDirVarArray['action'] = 'list';
			$currentMediaFolderID = NULL;
			if ($currentMediaFolder) {
				$currentMediaFolderID = $currentMediaFolder->id;
			}
			$url = $this->view->url(array_merge($selectDirVarArray, array('mediaFolderID'=>$currentMediaFolderID)), NULL, TRUE) . $dirVarParams;
			$link = '<a href="' . $url . '" class="select">' . $this->view->translate('Select') . '</a>';

			$mediaFolderQuery = Doctrine_Query::create()
				->from('Default_Model_MediaFolder m')
			;
			if ($mediaFolderID === NULL) {
				$mediaFolderQuery = $mediaFolderQuery
					->where('m.media_folder_id IS NULL', array())
				;
			} else {
				$mediaFolderQuery = $mediaFolderQuery
					->where('m.media_folder_id = ?', $mediaFolderID)
				;
			}
			$mediaFolderCollection = $mediaFolderQuery
				->orderBy('m.name ASC')
				->execute()
			;
			if ($mediaFolderID) {
				$currentParentMediaFolderID = NULL;
				if ($currentMediaFolder) {
					$currentParentMediaFolderID = $currentMediaFolder->media_folder_id;
				}
				$url = $this->view->url(array_merge($dirVarArray, array('mediaFolderID'=>$currentParentMediaFolderID)), NULL, TRUE) . $dirVarParams;
				$listInside .= '<li><a href="' . $url . '">..</a></li>';
			}
			if ($mediaFolderCollection->count() > 0) {
				foreach ($mediaFolderCollection as $mediaFolderModel) {
					$url = $this->view->url(array_merge($dirVarArray, array('mediaFolderID'=>$mediaFolderModel->id)), NULL, TRUE) . $dirVarParams;
					$listInside .= '<li><a href="' . $url . '">' .$mediaFolderModel->name . '</a></li>';
				}
			}
			if ($listInside) {
				$listInside = '<ul class="media-folder">' . $listInside . '</ul>';
			}
		}

		ob_start();

?>
<p class="media-folder">
	Medias in <?php echo $returnValue; ?>
	<?php echo $link; ?>
</p>
<?php echo $listInside; ?>
<?php

		$returnValue = ob_get_clean();

		return $returnValue;
	}

}