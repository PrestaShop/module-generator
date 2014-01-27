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
		// Hide ugly toolbar
		$('table[class="table"]').each(function(){
			$(this).hide();
			$(this).next('div.clear').hide();
		});

		// Hide ugly multishop select
		if (typeof(_PS_VERSION_) !== 'undefined') {
			var version = _PS_VERSION_.substr(0,3);
			if(version === '1.5') {
				$('.multishop_toolbar').addClass("panel panel-default");
				$('.shopList').removeClass("chzn-done").removeAttr("id").css("display", "block").next().remove();
				cloneMulti = $(".multishop_toolbar").clone(true, true);
				$(".multishop_toolbar").first().remove();
				cloneMulti.find('.shopList').addClass('selectpicker show-menu-arrow').attr('data-live-search', 'true');
				cloneMulti.insertBefore("#modulecontent");
				// Copy checkbox for multishop
				cloneActiveShop = j.trim($('table[class="table"] tr:nth-child(2) th').first().html());
				$(cloneActiveShop).insertAfter("#tab_translation");
			}
		}

		// Custom Select
		$('.selectpicker').selectpicker();

		// Fix bug form builder + bootstrap select
		$('.selectpicker').each(function(){
			var select = $(this);
			select.on('click', function() {
				$(this).parents('.bootstrap-select').toggleClass('open');
			});
		});

		// Custom Textarea
		$('.textarea-animated').autosize({append: "\n"});
	};
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