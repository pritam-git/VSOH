<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/ProtocolDetailPage.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: ProtocolDetailPage.php 7 2019-01-18 15:58:40Z nm $
 */

/**
 *
 *
 * System_View_Helper_ProtocolDetailPage
 *
 *
 */
class Default_View_Helper_ProtocolDetailPage extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Wraps type selection process HTML
	 *
	 * @param int $model
	 * @return string
	 */
	public function protocolDetailPage($model)
	{
		$this->view->headLink()
			->appendStylesheet('/css/default/owl.carousel.css', 'all')
			->appendStylesheet('/css/default/owl.theme.default.css', 'all');

		$this->view->headScript()
			->appendFile('/js/jquery/owl.carousel.js', 'text/javascript');

		ob_start();
		?>
		<style>
			.carousel-control.left,.carousel-control.right{
				background-image: none;
				width: 5%;
			}

			#imageDiv {
				visibility: hidden;
				padding: 2px;
			}

			#imageDiv div.item{
				border: 0.5px solid #ccc;
				border-radius: 3px;
				display: flex;
				flex-wrap: wrap;
				align-content: center;
			}
			#imageDiv div.item:hover{
				cursor: pointer;
				border-color: #f7d900;
				box-shadow: 0 0 10px #f7d900;
			}

			#imageDiv .thumbnail{
				max-width: 100%;
				max-height: 100%;
				width: auto;
				margin: 0 auto;
			}

			#modal-carousel img{
				max-width: 100%;
				max-height: 100%;
				width: auto;
				margin: 0 auto;
			}
			#modal-carousel div.item {
				border: 0.5px solid #ccc;
				border-radius: 3px;
			}
			.modalImageContainer{
				display: flex;
    			flex-wrap: wrap;
    			align-content: center;
			}
			.detailsButtons a:first-child{
				margin-right: 0px;
			}
		</style>

		<script>
			$(document).ready(function() {

				/* activate the carousel */
				$("#modal-carousel").carousel({interval:false});

				/* when clicking a thumbnail */
				$("#imageDiv .item").click(function(){
					var id= this.id;
					$('.item').removeClass('active');
					$('#modalImage'+id).addClass("active");
					$(this).css('box-shadow','0 0 10px #f7d900');
					$(this).css('border-color','#f7d900');
					$("#modal-gallery").modal("show");
				});

				$('#modal-gallery').on('hidden.bs.modal', function () {
					$('.item').css('box-shadow','none');
					$('.item').css('border-color','');
				});

				$('.owl-carousel').owlCarousel({
					loop:true,
					margin:10,
					responsiveClass:true,
					responsive:{
						0:{
							items:1,
							nav:true
						},
						480:{
							items:2,
							nav:true
						},
						640:{
							items:4,
							nav:true
						},
						960:{
							items:8,
							nav:true,
							loop:false
						}
					}
				});

				$('#imageDiv div.item').height($('#imageDiv div.item').width() * 0.75);
				$('#imageDiv').css('visibility', 'visible');

				$(window).resize(function() {
					$('#imageDiv div.item').height($('#imageDiv div.item').width() * 0.75);
				});

			});
		</script>

		<?php
		if(!empty($model)) {
			//prepare URL for downloading presentation media
			$column = L8M_Locale::getLang() . '_media_id';
			$relatedMediaModelName = ucfirst(L8M_Locale::getLang()) . 'Media';
			$url = NULL;
			$media = NULL;
			if (isset($model->$column) &&
				$model->$column) {
				$url = $model->$relatedMediaModelName->getLink();
				$media = $model->$relatedMediaModelName->id;
			}

			//prepare URL for downloading presentation media
			$presentationColumn = L8M_Locale::getLang() . '_presentation_media_id';
			$relatedPresentationMediaModelName = ucfirst(L8M_Locale::getLang()) . 'PresentationMedia';
			$presentationUrl = NULL;
			if (isset($model->$presentationColumn) &&
				$model->$presentationColumn) {
				$presentationUrl = $model->$relatedPresentationMediaModelName->getLink();
			}

			$modelName = L8M_Acl_CalledFor::controller();
			if(strpos($modelName, 'archive-') === 0) {
				$modelName = str_replace('archive-', '', $modelName);
			}
			$modelNameParts = explode('-', $modelName);
			$modelNameFromController = '';
			foreach($modelNameParts as $modelNamePart) {
				$modelNameFromController .= ucfirst($modelNamePart);
			}
			if($modelNameFromController == 'Commissions'){
				$modelNameFromController = 'Protocol';
			}

			if(!class_exists('Default_Model_' . $modelNameFromController)) {
				throw new L8M_Exception('Unknown model being queried -> ' . $modelNameFromController);
			}

			$m2nImagesFieldName = $modelNameFromController . 'M2nMediaImage';

			?>

			<div class="detail-block">

				<?php if($model->title){ ?>
					<div class=" row detail-heading marb15">
						<h2 class="col-md-12 color3 margin0"><?php echo $model->title; ?></h2>
					</div>
				<?php  }

				?>
				<div class=" row mr-10 detailsButtons marb15">
					<?php if(!empty($url)) { ?>
						<a class="btn btn-warning action-block pull-right mr-5" href="<?php echo $url; ?>"><?php echo $this->view->translate('Download Protokoll', 'de'); ?> <i class="fa fa-download"></i></a>
						<?php }
						if(!empty($presentationUrl)) { ?>
						<a class="btn btn-warning action-block pull-right mr-5" href="<?php echo $presentationUrl; ?>"><?php echo $this->view->translate('Download Präsentation', 'de'); ?> <i class="fa fa-download"></i></a>
					<?php }
						//For Dates Model
					if($modelNameFromController == 'Dates' || $modelNameFromController == 'RegionDates' ){
						if(isset($this->view->isAdmin) || (!isset($this->view->isAdmin) && strtotime(date('Y-m-d')) <= strtotime($model->closed_registration_date))) {
							if(!$this->view->registrationDetails->status) {
						?>
							<a id="registerBtn" class="btn btn-warning action-block pull-right mr-5 registerActionBtn" data-toggle="modal" data-target="#registerEventModal"><?php echo $this->view->translate('Zum Event Anmelden','de'); ?></a>

						<?php
							} else {
						?>
							<a id="changeDetailsBtn" class="btn btn-warning action-block pull-right mr-5 registerActionBtn" data-toggle="modal" data-target="#registerEventModal"><?php echo $this->view->translate('Anmeldungsdaten ändern','de'); ?></a>
							<a id="unregisterBtn" class="btn btn-warning action-block pull-right mr-5 registerActionBtn" data-toggle="modal" data-target="#unregisterEventModal"><?php echo $this->view->translate('Abmelden','de'); ?></a>

						<?php
							}
						}
					} ?>

				</div>
				<?php
				if($model->$m2nImagesFieldName){
					$imageGallery = '';
					$modalImageGallery = '';
					foreach($model->$m2nImagesFieldName as $mediaM2nImage) {
						$mediaImageModel = Default_Model_Media::getModelByID($mediaM2nImage->media_image_id);
						$imgUrl = $mediaImageModel->getLink();
						$imageGallery .= '<div class="item p-5" id="'.$mediaM2nImage->id.'"> <img class="thumbnail" src="' .$imgUrl. '"> </div>';
						$modalImageGallery .= '<div class="item p-5" id="modalImage'.$mediaM2nImage->id.'"> <div class=" modalImageContainer h-100 w-100"><img class="thumbnail" src="' .$imgUrl. '"> </div></div>';
					}
				?>
					<div class="row mx-10 owl-carousel owl-theme" id="imageDiv" >
						<?php
							echo $imageGallery;
						?>
					</div>

					<div class="modal" id="modal-gallery" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-body">
									<div id="modal-carousel" class="carousel">
										<div class="carousel-inner center">
											<?php echo $modalImageGallery;?>
										</div>
										<a class="carousel-control left" href="#modal-carousel" style="display:block" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
										<a class="carousel-control right" href="#modal-carousel"style="display:block"  data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>

									</div>
								</div>
							</div>
						</div>
					</div>
			<?php
			}
			?>


			<!--Display special columns of dates model-->
			<?php
			if ($model->getTable()->getTableName() === 'dates' || $model->getTable()->getTableName() === 'region_dates' ) {
				//get content from translation
				if (isset($model->place) && !empty($model->place)) {
					?>
					<div class="row">
						<h4 class="col-lg-2"><?php echo $this->view->translate('Place'); ?></h4>
						<p class="col-md-10"><?php echo $model->place; ?></p>
					</div>
				<?php }
				if (isset($model->start_datetime) && !empty($model->start_datetime)) {
					?>
					<div class="row">
						<h4 class="col-lg-2"><?php echo $this->view->translate('Start datetime'); ?></h4>
						<p class="col-md-10"><?php echo $model->start_datetime; ?></p>
					</div>
				<?php }
				if (isset($model->end_datetime) && !empty($model->end_datetime)) {
					?>
					<div class="row">
						<h4 class="col-lg-2"><?php echo $this->view->translate('End datetime'); ?></h4>
						<p class="col-md-10"><?php echo $model->end_datetime; ?></p>
					</div>
				<?php }
				if (isset($model->subject_of_negotiations) && !empty($model->subject_of_negotiations)) {
					?>
					<div class="row">
						<h4 class="col-lg-2"><?php echo $this->view->translate('Subject of negotiations'); ?></h4>
						<p class="col-md-10"><?php echo $model->subject_of_negotiations; ?></p>
					</div>
				<?php }
				if (isset($model->comment) && !empty($model->comment)) { ?>
					<div class="row">
						<h4 class="col-lg-2"><?php echo $this->view->translate('Comment'); ?></h4>
						<p class="col-md-10"><?php echo $model->comment; ?></p>
					</div>
				<?php }
			}
			?>
			<p class="content"><?php echo $model->content; ?></p>
		</div>

			<?php
			if(!empty($media)) {
				//set url array for PDF <iframe>
				$urlArray = array(
					'module'=>'default',
					'controller'=>'commissions',
					'action'=>'set-pdf',
					'media'=>$media
				);
				?>
				<script>
					iframeObj = '<iframe src="<?php echo $this->view->url($urlArray, NULL, TRUE); ?>" width="100%" height="860"></iframe>';
					$('div.detail-block').append(iframeObj);
				</script>
				<?php
			}
		} else {
			echo '<h4 class="mart15 color3 font-normal">' . $this->view->translate('Kein Protokoll verfügbar.', 'de') . '</h4>';
		}
		return ob_get_clean();
	}
}