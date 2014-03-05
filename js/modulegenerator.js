/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

(function($) {
	$.fn.serializeAnything = function() {
		var toReturn = [];
		var els = $(this).find(':input').get();
		$.each(els, function() {
			if (this.name && !this.disabled && (this.checked || /select|textarea/i.test(this.nodeName) || /text|hidden|password/i.test(this.type))) {
				var val = $(this).val();
				toReturn.push( encodeURIComponent(this.name) + "=" + encodeURIComponent( val ) );
			}
		});
		return toReturn.join("&").replace(/%20/g, "+");
	}
})(jQuery);

// Main Function
var j = jQuery.noConflict();
var first = 0;

var Main = function () {
	// function for debug
	var p = function () {
		console.log(arguments);
	};
	// function to displays collapsible content panels
	var runPanelToggle = function () {
		j('.panel-tools .panel-collapse').bind('click', function (e) {
			e.preventDefault();
			var $el = jQuery(this).parent().closest(".panel").children(".panel-body");
			if (j(this).hasClass("collapses")) {
				// Hide the matched elements with a sliding motion.
				j(this).addClass("expand").removeClass("collapses");
				$el.slideUp(200);
			} else {
				// Display the matched elements with a sliding motion.
				j(this).addClass("collapses").removeClass("expand");
				$el.slideDown(200);
			}
			return false;
		});
	};

	var setSQL = function () {
		var prefix = 'PREFIXmodulename';
		var install = j('#sql_install').val();
		var uninstall = j('#sql_uninstall').val();
		if (first === 0) {
			var install = install.replace(prefix, 'PREFIXmodule_'+this.value);
			var uninstall = uninstall.replace(prefix, 'PREFIXmodule_'+this.value);
			j('#sql_install').val(install);
			j('#sql_uninstall').val(uninstall);
		} else {
			var install = install.replace(previous, this.value);
			var uninstall = uninstall.replace(previous, this.value);
			j('#sql_install').val(install);
			j('#sql_uninstall').val(uninstall);
		}
		previous = this.value;
		first++;
	};

	// function to custom select
	var runCustomElement = function () {

		// Hide ugly toolbar
		j('table[class="table"]').each(function(){
			j(this).hide();
			j(this).next('div.clear').hide();
		});

		// Hide ugly multishop select
		if (typeof(_PS_VERSION_) !== 'undefined') {
			var version = _PS_VERSION_.substr(0,3);
			if(version === '1.5') {
				j('.multishop_toolbar').addClass("panel panel-default");
				j('.shopList').removeClass("chzn-done").removeAttr("id").css("display", "block").next().remove();
				cloneMulti = j(".multishop_toolbar").clone(true, true);
				j(".multishop_toolbar").first().remove();
				cloneMulti.find('.shopList').addClass('selectpicker show-menu-arrow').attr('data-live-search', 'true');
				cloneMulti.insertBefore("#modulecontent");
				// Copy checkbox for multishop
				cloneActiveShop = j.trim(j('table[class="table"] tr:nth-child(2) th').first().html());
				j(cloneActiveShop).insertAfter("#tab_translation");
			}
		}

		// Custome SQL
		j('#modulename').keyup(j.debounce( 250, setSQL ));

		// Custom Select
		j('.selectpicker').selectpicker();

		// Fix bug form builder + bootstrap select
		j('.selectpicker').each(function(){
			var select = j(this);
			select.on('click', function() {
				j(this).parents('.bootstrap-select').toggleClass('open');
			});
		});

		// Custom Textarea
		j('.textarea-animated').autosize({append: "\n"});
	};

	var runSpecialElement = function () {
		// Force lowercase
		j('#modulename').keyup(function(){
			j(this).val(j(this).val().toLowerCase().replace(/ /g, '').replace(/[^a-z]/g, '') );
		});

		// Toggle hidden field
		j("span.switch label").click(function() {
			j(this).parent().nextAll('.switch_display').removeClass('hide');
			var $radio_check = j('#'+j(this).attr('for'));
			if ($radio_check.val() === "0") {
				j(this).parent().nextAll('.switch_display').addClass('hide');
			}
		});
	};

	var loadModal = function (ul) {
		var loader = '<div class="progress">'+
		'<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>'+
		'</div>';
		var reload = '<div class="bootstrap-dialog-message">'+
		'<span>Thank you for your patience during the image deletion process</span>'+
		loader+'</div>';

		BootstrapDialog.show({
			message: '<span>Are you sure you want to delete this image?</span>',
			buttons: [{
				icon: 'icon-location-arrow',
				label: ' I want delete this picture',
				cssClass: 'btn-primary',
				autospin: true,
				action: function(dialogRef) {
					dialogRef.enableButtons(false);
					dialogRef.setClosable(false);
					dialogRef.getModalFooter().hide();
					dialogRef.getModalBody().html(reload);
					j.ajax({
						type: 'POST',
						url: admin_modulegenerator_ajax_url,
						dataType: 'json',
						timeout: 2000,
						data: {
							controller : 'AdminModuleGenerator',
							action : 'moduleGeneratorDelete',
							ajax : true,
							id_tab : current_id_tab
						},
						success: function(jsonData)
						{
							dialogRef.getModalBody().children().children().next().children().css('width', '100%');
							dialogRef.getModalBody().children().children().next().children().attr('aria-valuenow', '100');
							setTimeout(function(){
								dialogRef.close();
									j('#drop').show();
								j('#drop').fadeTo( "slow", 1);
								ul.fadeOut(function(){
									ul.find('li').first().remove();
								});
							}, 1000);
						},
						error: function (jqXHR, textStatus, errorThrown) {
							dialogRef.getModalBody().children().children().next().children().addClass('progress-bar-danger');
							dialogRef.getModalBody().children().children().next().children().css('width', '100%');
							dialogRef.getModalBody().children().children().next().children().attr('aria-valuenow', '100');
							setTimeout(function(){
								dialogRef.close();
							}, 2000);
						}
					});
				}
			}, {
				label: 'Close',
				action: function(dialogRef){
					dialogRef.close();
				}
			}]
		});
	}

	var runUpload = function () {
		var ul = j('#upload ul');
		j('#drop a').click(function(){
			// Simulate a click on the file input button
			// to show the file browser dialog
			j(this).parent().find('input').click();
		});
		// Initialize the jQuery File Upload plugin
		j('#upload').fileupload({
			// This element will accept file drag/drop uploading
			dropZone: j('#drop'),
			limitMultiFileUploads: 1,
			limitConcurrentUploads: 1,
			maxNumberOfFiles: 1,
			url: admin_modulegenerator_ajax_url,
			dataType: 'json',
			formData: {
				controller: 'AdminModuleGenerator', 
				action: 'ModuleGeneratorUpload', 
				ajax : true,
				id_tab : current_id_tab
			},
			// This function is called when a file is added to the queue;
			// either via the browse button, or via drag/drop:
			add: function (e, data) {
				// Remove first li
				ul.find('li').first().remove();
				ul.fadeIn();

				// Create template for li
				var tpl = j('<li class="working">'+
					'<p></p>'+
					'<span></span>'+
					'<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div>'+
				'</li>');
				// Append the file name and file size
				tpl.find('p').text(data.files[0].name).append('<i>' + formatFileSize(data.files[0].size) + '</i>');
				// Add the HTML to the UL element
				data.context = tpl.appendTo(ul);
				// Listen for clicks on the cancel icon
				tpl.find('span').click(function(){
					var has_error = tpl.find('div').hasClass('alert');

					if(tpl.hasClass('working')){
						jqXHR.abort();
					}

					if(has_error === false){
						loadModal(ul);
					} else {
						tpl.fadeOut(function(){
							tpl.remove();
						});
					}
				});
				// Automatically upload the file once it is added to the queue
				var jqXHR = data.submit();
			},
			progress: function(e, data){
				// Calculate the completion percentage of the upload
				var progress = parseInt(data.loaded / data.total * 100, 10);
				// Update the hidden input field and trigger a change
				data.context.find('.progress-bar').css('width', progress + '%');
				data.context.find('.progress-bar').attr('aria-valuenow', progress);

				if(progress == 100){
					data.context.removeClass('working');
					data.context.find('span').addClass('icon-times');
				}
			},
			fail:function(e, data){
				// Something has gone wrong!
				data.context.find('.progress-bar').addClass('progress-bar-danger');
				data.context.prepend('<div class="alert alert-danger">'+data.jqXHR.responseText+'</div>');
			},
			done:function(e, data){
				var logo = '<img src="../modules/modulegenerator/tmp/logo.png" width="32" height="32" alt="logo" title="logo" />';
				data.context.fadeIn(function(){
					data.context.find('p').prepend(logo);
				});
				j('#drop').fadeOut(function(){
					this.hide();
				});
			}
		});
		// Prevent the default action when a file is dropped on the window
		j(document).on('drop dragover', function (e) {
			e.preventDefault();
		});
	};
	// Helper function that formats the file sizes
	var formatFileSize = function (bytes) {
		if (typeof bytes !== 'number') {
			return '';
		}
		if (bytes >= 1000000000) {
			return (bytes / 1000000000).toFixed(2) + ' GB';
		}
		if (bytes >= 1000000) {
			return (bytes / 1000000).toFixed(2) + ' MB';
		}
		return (bytes / 1000).toFixed(2) + ' KB';
	};
	// function to display wizard
	var runWizard = function () {
		var $wizardContent = j('#wizard');
		var $firstForm = j("#validFirstStep");

		$wizardContent.smartWizard({
			selected: 0,
			keyNavigation: false,
			onLeaveStep: leaveStep,
			onShowStep: checkStep,
		});

		var numberOfSteps = 0;
		animateBar();
		initValidator();
	};
	var animateBar = function (val) { 
		if ((typeof val == 'undefined') || val == "") { 
			val = 1;
		};
		numberOfSteps = j('.swMain > ul > li').length;
		var valueNow = Math.floor(100 / numberOfSteps * val);
		j('.step-bar').css('width', valueNow + '%');
	};
	var initValidator = function () {
		var e = $("#validFirstStep"),
			n = $(".alert-danger", e);
		j.validator.setDefaults({
			debug: true,
			errorElement: "span", // contain the error msg in a span tag
			errorClass: 'help-block-2',
			errorPlacement: function (error, element) { // render error placement for each input type
				if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { // for chosen elements, need to insert the error after the chosen container
					error.insertAfter(j(element).closest('.form-group').children('div').children().last());
				} else {
					error.insertAfter(element);
					// for other inputs, just perform default behavior
				}
			},
			invalidHandler: function () {
				n.removeClass('hide');
			},
			highlight: function (element) {
				j(element).closest('.help-block').removeClass('valid');
				// display OK icon
				j(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
				// add the Bootstrap error class to the control group
			},
			unhighlight: function (element) { // revert the change done by hightlight
				j(element).closest('.form-group').removeClass('has-error');
				// set error class to the control group
			},
			success: function (label, element) {
				label.addClass('help-block valid');
				// mark the current input as valid and display OK icon
				j(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
			}
		});
	};

	var leaveStep = function (obj, context) {
		return validateSteps(obj, context);
	};

	var validateSteps = function (obj, context) {
		var $firstForm = j("#validFirstStep");
		var isStepValid = true;
		var n = $(".alert-danger", $firstForm);

		stepNumber = context.fromStep;
		nextStep = context.toStep;
		if (numberOfSteps !== nextStep) {
			$firstForm.validate().focusInvalid();
			if ($firstForm.valid()) {
				n.addClass('hide');
				animateBar(nextStep);
				j("#next-step").removeClass('hide');
				if (nextStep === 1) {
					j("#back-step").fadeIn("fast").addClass('hide');
				}
				else {
					j("#back-step").removeClass('hide');
				}
				return true;
			}
		} else {
			animateBar(nextStep);
			j("#back-step").removeClass('hide');
			j("#next-step").fadeIn("fast").addClass('hide');
			return true;
		}
	};

	var validateAllSteps = function () {
		var isStepValid = true;
		return isStepValid;
	};

	var checkStep = function () {
		var $wizardContent = j('#wizard');
		j("#next-step").unbind("click").on("click", function (e) {
			e.preventDefault();
			$wizardContent.smartWizard("goForward");
		});
		j("#back-step").unbind("click").on("click", function(e) {
			e.preventDefault();
			$wizardContent.smartWizard("goBackward");
		});
		j(".finish-step").unbind("click").on("click", function(e) {
			e.preventDefault();
			onFinishForm();
		});
	};

	var onFinishForm = function () {
		if (validateAllSteps()) {
			data = j("#step-1").serializeAnything();
			form = j("#render").val();
			// Ajax call 
			j.ajax({
				type: 'POST',
				url: admin_modulegenerator_ajax_url,
				dataType: 'html',
				data: {
					data: data,
					form: form,
					controller : 'AdminModuleGenerator',
					action : 'moduleGeneratorDone',
					ajax : true,
					id_tab : current_id_tab
				},
				success : function (data){
					p(data);
					window.location.assign(data);
				},
			});
		}
	};

	return {
		//main function to initiate template pages
		init: function () {
			runUpload();
			runWizard();
			runPanelToggle();
			runCustomElement();
			runSpecialElement();
		}
	};
}();

j(function() {
	// Load functions
	Main.init();
});