// Main Function
var Main = function () {
	// function for debug
	var p = function () {
		console.log(arguments);
	};
	// function to displays collapsible content panels
	var runPanelToggle = function () {
		$('.panel-tools .panel-collapse').bind('click', function (e) {
			e.preventDefault();
			var $el = jQuery(this).parent().closest(".panel").children(".panel-body");
			if ($(this).hasClass("collapses")) {
				// Hide the matched elements with a sliding motion.
				$(this).addClass("expand").removeClass("collapses");
				$el.slideUp(200);
			} else {
				// Display the matched elements with a sliding motion.
				$(this).addClass("collapses").removeClass("expand");
				$el.slideDown(200);
			}
			return false;
		});
	};
	// function to custom select
	var runCustomElement = function () {
		// Custom Select
		$('.selectpicker').selectpicker();

		// Custom Radio
		$("div.btn-group[data-toggle='buttons-radio'] button").click(function() {
			$(this).parent().find('button').removeClass('active');
			$(this).addClass('active');
			$(this).parent().parent().find('input:first').val($(this).val());
		});

		// Hide module toolbar
		$('table.table').each(function(){
			$(this).hide();
			$(this).next('div.clear').hide();
		});

		// Custom Textarea
		$('.textarea-animated').autosize({append: "\n"});
	}
	return {
		//main function to initiate template pages
		init: function () {
			runPanelToggle();
			runCustomElement();
		}
	};
}();

$(function() {
	// Load functions
	Main.init();
});