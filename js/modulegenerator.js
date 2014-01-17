// Main Function
var j = jQuery.noConflict();

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
	// function to custom select
	var runCustomElement = function () {
		// Hide ugly toolbar
		j('table[class="table"]').each(function(){
			j(this).hide();
			j(this).next('div.clear').hide();
		});

		// Hide ugly multishop select
		j('.multishop_toolbar').addClass("panel panel-default");
		j('.shopList').removeClass("chzn-done").removeAttr("id").css("display", "block").next().remove();
		cloneMulti = j(".multishop_toolbar").clone(true, true);
		j(".multishop_toolbar:first").remove();
		cloneMulti.find('.shopList').addClass('selectpicker show-menu-arrow').attr('data-live-search', 'true');
		cloneMulti.insertBefore("#modulecontent");

		/*
		cloneActiveShop = j("input[name='activateModule']:first").clone();
		cloneActiveShop.insertAfter("#select_translation");
		*/

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
		j('#modulename').keyup(function()
		{
			j(this).val(j(this).val().toLowerCase().replace(/ /g, '').replace(/[^a-z]/g, '') );
		});

		// 
		j("div.btn-group[data-toggle='buttons-radio'] button").click(function() {
			j(this).parent().find('button').removeClass('active');
			j(this).addClass('active');
			j(this).parent().parent().find('input:first').val(j(this).val());
		});

		// 
		j(".toggle-select button").click(function() {
			j(this).parent().nextAll('.switch_display').removeClass('hide');
			if (j(this).val() === "0") {
				j(this).parent().nextAll('.switch_display').addClass('hide');
			}
		});
	};

	// function to display wizard
	var runWizard = function () {
		var $wizardContent = j('#wizard');
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
		j.validator.setDefaults({
			debug: true,
			errorElement: "span", // contain the error msg in a span tag
			errorClass: 'help-block',
			errorPlacement: function (error, element) { // render error placement for each input type
				if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { // for chosen elements, need to insert the error after the chosen container
					error.insertAfter($(element).closest('.form-group').children('div').children().last());
				} else {
					error.insertAfter(element);
					// for other inputs, just perform default behavior
				}
			},
			highlight: function (element) {
				$(element).closest('.help-block').removeClass('valid');
				// display OK icon
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
				// add the Bootstrap error class to the control group
			},
			unhighlight: function (element) { // revert the change done by hightlight
				$(element).closest('.form-group').removeClass('has-error');
				// set error class to the control group
			},
			success: function (label, element) {
				label.addClass('help-block valid');
				// mark the current input as valid and display OK icon
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
			}
		});
	};

	var leaveStep = function (obj, context) {
		j("#next-step").unbind("click");
		j("#back-step").unbind("click");
		p('leaveStep');
		return validateSteps(obj, context);
	};

	var validateSteps = function (obj, context) {
		var $firstForm = j("#validFirstStep");

		stepNumber = context.fromStep;
		nextStep = context.toStep;
		p('validateSteps');
		var isStepValid = true;

		if (numberOfSteps !== nextStep) {
			if ($firstForm.valid()) {
				$firstForm.validate().focusInvalid();
				animateBar(nextStep);
				j("#next-step").removeClass('hide');
				if (nextStep === 1) {
					j("#back-step").fadeIn("fast").addClass('hide');
				}
				else {
					j("#back-step").removeClass('hide');
				}
				return true;
			};
		}
		else
		{
			animateBar(nextStep);
			j("#back-step").removeClass('hide');
			j("#next-step").fadeIn("fast").addClass('hide');
			return true;
		}
	};

	var checkStep = function (obj, context) {
		var $wizardContent = j('#wizard');
		p('checkStep');

		j("#next-step").unbind("click").one("click", function (e) {
			e.preventDefault();
			$wizardContent.smartWizard("goForward");
		});

		j("#back-step").unbind("click").one("click", function(e) {
			e.preventDefault();
			$wizardContent.smartWizard("goBackward");
		});

		j(".finish-step").unbind("click").click(function (e) {
			e.preventDefault();
			data = j("#step-1").serializeAnything();
			form = j("#render").val();
			// Ajax call 
			$.ajax({
				url : "../modules/modulegenerator/ajax.php",
				type : "post", 
				async : false,
				data : {data: data, form: form}, 
				success : function (data){
				},
			});
		});
	}

	return {
		//main function to initiate template pages
		init: function () {
			runWizard();
			runPanelToggle();
			runCustomElement();
			runSpecialElement();
		}
	};
}();

(function($) {
	$.fn.serializeAnything = function() {
		var toReturn    = [];
		var els         = j(this).find(':input').get();
		$.each(els, function() {
			if (this.name && !this.disabled && (this.checked || /select|textarea/i.test(this.nodeName) || /text|hidden|password/i.test(this.type))) {
				var val = j(this).val();
				toReturn.push( encodeURIComponent(this.name) + "=" + encodeURIComponent( val ) );
			}
		});
		return toReturn.join("&").replace(/%20/g, "+");
	}
})(jQuery);

j(function() {
	// Load functions
	Main.init();
});

/*
<form>
	<div>
	<input type="text" name="content[]" placeholder="content" />
	<input type="button" class="add" value="Add" />
	</div>
</form>
j('form').on('click', '.add', function () {
	var row = j(this).closest('div'),
		new_row = row.clone();
	
	row.find('input:text').addClass('accepted');
	
	row.find('.add')
		.removeClass('add')
		.addClass('remove')
		.val('Remove');
	
	new_row.find('input:text').val('');
	
	row.after(new_row);
}).on('click', '.remove', function () {
	j(this).closest('div').remove();
});
*/