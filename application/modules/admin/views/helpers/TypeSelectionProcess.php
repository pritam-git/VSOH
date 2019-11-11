<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/views/helpers/TypeSelectionProcess.php
 * @author     Krishna Bhatt <nm@l8m.com>
 * @version    $Id: TypeSelectionProcess.php 7 2019-01-03 14:18:40Z nm $
 */

/**
 *
 *
 * Admin_View_Helper_TypeSelectionProcess
 *
 *
 */
class Admin_View_Helper_TypeSelectionProcess extends Zend_View_Helper_Abstract
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
	 * @param int $controller
	 * @return string
	 */
	public function typeSelectionProcess($controller)
	{
		ob_start();

		$this->view->headLink()
		->appendStylesheet('/frameworks/bootstrap/css/bootstrap.min.css', 'all')
		->appendStylesheet('/css/default/custom.css', 'all')
		;

		/**
		 * salutationOptions
		 */
		$salutationOptions = Doctrine_Query::create()
		->from('Default_Model_Salutation s')
		->select('s.id, st.name')
		->leftJoin('s.Translation st')
		->addWhere('s.disabled = ?', 0)
		->addWhere('st.lang = ?', L8M_Locale::getLang())
		->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
		->execute(array())
		;

		if($this->view->published) {
			if($this->view->isTested) {
				if($this->view->language && $this->view->regions && $this->view->contractType && $this->view->brands && $this->view->department){
					?>
					<div id="SelectionListBlock">
						<h3><?php echo $this->view->translate('Please select options from following selections for sending push emails,'); ?></h3>

						<div class="row">
							<!--Language selection lists-->
							<?php if($this->view->language){ ?>
								<div class="col-lg-4">
									<div class="list-group" id="list1">
										<p class="list-group-item active"><?php echo $this->view->translate('Spoken Language'); ?></p>
										<?php foreach ($this->view->language as $languageValue){ ?>
											<p class="list-group-item">
												<?php echo $languageValue; ?>
												<input type="checkbox" class="pull-right" name="language" value="<?php echo $languageValue; ?>">
											</p>
										<?php } ?>
									</div>
								</div>
							<?php } ?>

							<!--Contract Type selection lists-->
							<?php if($this->view->contractType){ ?>
								<div class="col-lg-4">
									<div class="list-group" id="list1">
										<p class="list-group-item active"><?php echo $this->view->translate('Contract Type'); ?></p>
										<?php foreach ($this->view->contractType as $contractTypeValue){ ?>
											<p class="list-group-item">
												<?php echo $contractTypeValue->name; ?>
												<input type="checkbox" class="pull-right" name="contractType" value="<?php echo $contractTypeValue->id; ?>">
											</p>
										<?php } ?>
									</div>
								</div>
							<?php } ?>

							<!--Region selection lists-->
							<?php if($this->view->regions){ ?>
								<div class="col-lg-4">
									<div class="list-group" id="list1">
										<p class="list-group-item active"><?php echo $this->view->translate('Region'); ?></p>
										<?php foreach ($this->view->regions as $regionValue){ ?>
											<p class="list-group-item">
												<?php echo $regionValue->name; ?>
												<input type="checkbox" class="pull-right" name="region" value="<?php echo $regionValue->id; ?>">
											</p>
										<?php } ?>
									</div>
								</div>
							<?php } ?>

							<!--Brand selection lists-->
							<?php if($this->view->brands){ ?>
								<div class="col-lg-4">
									<div class="list-group" id="list1">
										<p class="list-group-item active"><?php echo $this->view->translate('Brands'); ?></p>
										<?php foreach ($this->view->brands as $brandValue){ ?>
											<p class="list-group-item">
												<?php echo $brandValue->name; ?>
												<input type="checkbox" class="pull-right" name="brands" value="<?php echo $brandValue->id; ?>">
											</p>
										<?php } ?>
									</div>
								</div>
							<?php } ?>

							<!--Department selection lists-->
							<?php if($this->view->department){ ?>
								<div class="col-lg-4">
									<div class="list-group" id="list1">
										<p class="list-group-item active"><?php echo $this->view->translate('Department'); ?></p>
										<?php foreach ($this->view->department as $departmentValue){ ?>
											<p class="list-group-item">
												<?php echo $departmentValue->name; ?>
												<input type="checkbox" class="pull-right" name="department" value="<?php echo $departmentValue->id; ?>">
											</p>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
						</div>

						<!--Send email button-->
						<button id="viewUsersList" class="btn btn-primary"><?php echo $this->view->translate('View Users List'); ?></button>
					</div>
					<script>
						var redirectUrl = '<?php echo $this->view->redirectUrl; ?>';
						var id = '<?php echo $this->view->protocolId; ?>';
						var process_step = function(step,lang,contractTypeIds,regionIds,brandIds,departmentIds,rowCount,totalStep,self) {
							var ajaxUrl = '<?php echo $this->view->url(array('module'=>'admin', 'controller'=>$controller, 'action'=>'mail-process-ajax'), NULL, TRUE)?>';

							$.ajax({
								type: 'POST',
								url: ajaxUrl,
								data: {
									id: id,
									totalStep: totalStep,
									rowCount: rowCount,
									step: step,
									selectedLang: lang,
									contractTypeIds: contractTypeIds,
									regionIds: regionIds,
									brandIds: brandIds,
									departmentIds: departmentIds
								},
								dataType: "json",
								success: function(response){
									if('done' == response.step){
										$('.progress-bar').animate({
											width: '100%'
										},50,function(){
											// Animation complete.
											$('.progress-bar').text('100% Complete');
										});
										$('#completed-step').text(response.completedStep);
										//$('#progressBlock').remove();
                                        $('#content').append('<div id="successBlock"><h3><?php echo $this->view->translate('Push emails are sent successfully to the all users.'); ?></h3></div>');
                                        $('#successBlock').append('<h3 id="mailCounterText"><?php echo $this->view->translate('You are redirecting in.'); ?> <span id="mailCounter"></span></h3>');
                                        // Total seconds to wait
                                        var seconds = 5;
                                        function countdown() {

                                            if (seconds < 0) {
                                                //$('#mailCounterText').remove();
                                                window.location = redirectUrl;
                                            } else {
                                                // Update remaining seconds
                                                $('#mailCounter').html(seconds);
                                                // Count down using javascript
                                                window.setTimeout(function(){
                                                    countdown()}, 1000);
                                            }
                                            seconds = seconds - 1;
                                        }
                                        // Run countdown function
                                        countdown();
									}else{
										$('.progress-bar').animate({
											width: response.percentage+'%'
										},50,function(){
											// Animation complete.
											$('.progress-bar').text(response.percentage+'% Complete');
										});
										$('#completed-step').text(response.completedStep);
										self.process_step(response.step,lang,contractTypeIds,regionIds,brandIds,departmentIds,rowCount,totalStep,self);
									}
								}
							}).fail(function(response){
								if(window.console && window.console.log){
									console.log(response);
								}
							});
						};

						//Start loader during ajax
						var startLoader = function() {
							$('body').append('<div id="custom_loader_div" class="ui-widget-overlay ui-front" style="z-index: 9999;"><img src="/img/default/prj/custom_loader.gif"></div>');
						};

						//Stop loader
						var stopLoader = function() {
							$('#custom_loader_div').remove();
						};

						//get filtered user details
						var get_selected_user = function(lang,contractTypeIds,regionIds,brandIds,departmentIds) {
							var ajaxUrl = '<?php echo $this->view->url(array('module'=>'admin', 'controller'=>$controller, 'action'=>'get-selected-user-ajax'), NULL, TRUE)?>';
							$.ajax({
								type: 'POST',
								url: ajaxUrl,
								data: {
									id: id,
									selectedLang: lang,
									contractTypeIds: contractTypeIds,
									regionIds: regionIds,
									brandIds: brandIds,
									departmentIds: departmentIds
								},
								dataType: "json",
								success: function(response){
									if(response.rowCount != 0){
										$('#SelectionListBlock').remove();
										$('#content').append('<div id="usersListBlock">'+response.listHTML+'</div>');
										$('#content #usersListBlock').append('<button id="sendPushEmails" class="btn btn-primary"><?php echo $this->view->translate('Send Emails'); ?></button>');
										self.stopLoader();

										$('#sendPushEmails').click(function (e) {
											$('#usersListBlock').remove();
											$('#content').append('<div id="progressBlock">'+response.processHTML+'</div>');

											self.process_step(1,lang,contractTypeIds,regionIds,brandIds,departmentIds,response.rowCount,response.totalStep,self);
										});
									} else {
										self.stopLoader();
										alert('<?php echo $this->view->translate('User not found with this selection criteria.'); ?>');
									}
								}
							}).fail(function(response){
								if(window.console && window.console.log){
									console.log(response);
								}
							});
						};

						$('#viewUsersList').click(function (e) {
							e.preventDefault();
							self.startLoader();

							var lang = [];
							if($('input[name="language"]:checked').length > 0) {
								//get selected language
								$('input[name="language"]:checked').each(function() {
									lang.push(this.value);
								});
							}
							else {
								//get all languages
								$('input[name="language"]').each(function() {
									lang.push(this.value);
								});
							}

							var contractTypeIds = [];
							if($('input[name="contractType"]:checked').length > 0) {
								//get selected contract type ids
								$('input[name="contractType"]:checked').each(function() {
									contractTypeIds.push(this.value);
								});
							}
							else {
								//get all contract type ids
								$('input[name="contractType"]').each(function() {
									contractTypeIds.push(this.value);
								});
							}

							var regionIds = [];
							if($('input[name="region"]:checked').length > 0) {
								//get selected region ids
								$('input[name="region"]:checked').each(function() {
									regionIds.push(this.value);
								});
							}
							else {
								//get all region ids
								$('input[name="region"]').each(function() {
									regionIds.push(this.value);
								});
							}

							var brandIds = [];
							if($('input[name="brands"]:checked').length > 0) {
								//get selected brand ids
								$('input[name="brands"]:checked').each(function() {
									brandIds.push(this.value);
								});
							}
							else {
								//get all brand ids
								$('input[name="brands"]').each(function() {
									brandIds.push(this.value);
								});
							}

							var departmentIds = [];
							if($('input[name="department"]:checked').length > 0) {
								//get selected department ids
								$('input[name="department"]:checked').each(function() {
									departmentIds.push(this.value);
								});
							}
							else {
								//get all department ids
								$('input[name="department"]').each(function() {
									departmentIds.push(this.value);
								});
							}

							self.get_selected_user(lang,contractTypeIds,regionIds,brandIds,departmentIds);

							/* if($('input[name="language"]:checked').length > 0 &&
								$('input[name="contractType"]:checked').length > 0 &&
								$('input[name="region"]:checked').length > 0 &&
								$('input[name="brands"]:checked').length > 0 &&
								$('input[name="department"]:checked').length > 0) {

								//get selected language
								var lang = [];
								$('input[name="language"]:checked').each(function() {
									lang.push(this.value);
								});

								//get selected contract type ids
								var contractTypeIds = [];
								$('input[name="contractType"]:checked').each(function() {
									contractTypeIds.push(this.value);
								});

								//get selected region ids
								var regionIds = [];
								$('input[name="region"]:checked').each(function() {
									regionIds.push(this.value);
								});

								//get selected brand ids
								var brandIds = [];
								$('input[name="brands"]:checked').each(function() {
									brandIds.push(this.value);
								});

								//get selected department ids
								var departmentIds = [];
								$('input[name="department"]:checked').each(function() {
									departmentIds.push(this.value);
								});

								//get user details for selected options
								self.get_selected_user(lang,contractTypeIds,regionIds,brandIds,departmentIds);
							} else {
								//self.stopLoader();

								//get selected language
								var lang = [];
								$('input[name="language"]').each(function() {
									lang.push(this.value);
								});

								//get selected contract type ids
								var contractTypeIds = [];
								$('input[name="contractType"]').each(function() {
									contractTypeIds.push(this.value);
								});

								//get selected region ids
								var regionIds = [];
								$('input[name="region"]').each(function() {
									regionIds.push(this.value);
								});

								//get selected brand ids
								var brandIds = [];
								$('input[name="brands"]').each(function() {
									brandIds.push(this.value);
								});

								//get selected department ids
								var departmentIds = [];
								$('input[name="department"]').each(function() {
									departmentIds.push(this.value);
								});

								//alert(lang+" "+contractTypeIds+" "+regionIds+" "+brandIds+" "+departmentIds);
								self.get_selected_user(lang,contractTypeIds,regionIds,brandIds,departmentIds);
							} */
						});
					</script>
					<?php
				} else { ?>
					<h3><?php echo $this->view->translate('Some selection options are missing for sending the PushEmails...'); ?></h3>
					<script>
						var redirectUrl = '<?php echo $this->view->redirectUrl; ?>';
						setTimeout(function(){
							window.location = redirectUrl;
						}, 5000);
					</script>
					<?php
				}
			} else{
				?>
				<div id="testEmailBlock">
					<h3><?php echo $this->view->translate('Please select language and provide an email address to send test email,'); ?></h3>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="<?php echo $this->view->translate('Enter your email-id'); ?>" name="email" id="email"/>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="<?php echo $this->view->translate('Enter your first name'); ?>" name="firstname" id="firstname"/>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="<?php echo $this->view->translate('Enter your last name'); ?>" name="lastname" id="lastname"/>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<select class="form-control" name="salutation" id="salutation">
									<?php foreach ($salutationOptions as $salutation) { ?>
									<option value="<?php echo $salutation['s_id']; ?>"><?php echo $salutation['st_name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<!--Language selection lists-->
						<?php if($this->view->language){ ?>
							<div class="col-md-6">
								<div class="list-group" id="list1">
									<p class="list-group-item active"><?php echo $this->view->translate('Spoken Language'); ?></p>
									<?php foreach ($this->view->language as $languageValue){ ?>
										<p class="list-group-item">
											<?php echo $languageValue; ?>
											<input type="checkbox" class="pull-right" name="language" value="<?php echo $languageValue; ?>">
										</p>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>

					<!--Send email button-->
					<button id="sendTestMailBtn" class="btn btn-primary mr-20"><?php echo $this->view->translate('Send test email'); ?></button>
				</div>

				<script>
					//Start loader during ajax
					var startLoader = function() {
						$('body').append('<div id="custom_loader_div" class="ui-widget-overlay ui-front" style="z-index: 9999;"><img src="/img/default/prj/custom_loader.gif"></div>');
					};

					//Stop loader
					var stopLoader = function() {
						$('#custom_loader_div').remove();
					};

					//Test form input
					var validateForm = function() {
						var emailInput = $('#email'),
							firstnameInput = $('#firstname'),
							lastnameInput = $('#lastname'),
							salutationInput = $('#salutation'),
							languageInput = $('input[name="language"]:checked'),
							filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

						if (emailInput.val().trim() === '' || !filter.test(emailInput.val())){
							return '<?php echo $this->view->translate('Please enter valid email id.'); ?>';
						}

						if (firstnameInput.val().trim() === ''){
							return '<?php echo $this->view->translate('Please enter your firstname.'); ?>';
						}

						if (lastnameInput.val().trim() === ''){
							return '<?php echo $this->view->translate('Please enter your lastname.'); ?>';
						}

						if (salutationInput.val() === ''){
							return '<?php echo $this->view->translate('Please select salutation.'); ?>';
						}

						if (languageInput.length <= 0){
							return '<?php echo $this->view->translate('Please select language filter option.'); ?>';
						}

						return true;
					};

					$('#sendTestMailBtn').click(function(){
						self.startLoader();
						var emailInput = $('#email'),
							firstnameInput = $('#firstname'),
							lastnameInput = $('#lastname'),
							salutationInput = $('#salutation'),
							languageInput = $('input[name="language"]:checked'),
							isValid = self.validateForm();

						if (isValid === true){
							//get selected language
							var lang = [];
							languageInput.each(function() {
								lang.push(this.value);
							});

							var ajaxUrl = '<?php echo $this->view->url(array('module'=>'admin', 'controller'=>$controller, 'action'=>'send-test-pushmails-ajax'), NULL, TRUE)?>';
							$.ajax({
								type: 'POST',
								url: ajaxUrl,
								data: {
									id: '<?php echo $this->view->protocolId; ?>',
									email: emailInput.val(),
									firstname: firstnameInput.val(),
									lastname: lastnameInput.val(),
									salutation: salutationInput.val(),
									selectedLang: lang
								},
								dataType: "json",
								success: function(response){
									if(response.isSent == true){
										stopLoader();
										if($('#testDoneWell').length <= 0){
											$('#testEmailBlock').append('<a href="<?php echo $this->view->currentUrl.'id='.$this->view->protocolId.'&do=TRUE'; ?>" class="btn btn-success" id="testDoneWell"><?php echo $this->view->translate('Test went well'); ?></a>');
										}
									}
								}
							}).fail(function(response){
								if(window.console && window.console.log){
									console.log(response);
								}
							});
						} else {
							self.stopLoader();
							alert(isValid);
						}
					});
				</script>
				<?php
			}
		} else { ?>
			<h3><?php echo $this->view->translate('The PushEmails could not be sent because the Protocol is not published...'); ?></h3>
			<script>
				var redirectUrl = '<?php echo $this->view->redirectUrl; ?>';
				setTimeout(function(){
					window.location = redirectUrl;
				}, 5000);
			</script>
			<?php
		}
		return ob_get_clean();
	}
}