// function for debug
var p = function () {
	var arg_lenght = arguments.length;
	for(var i=0; i<arg_lenght; i++) {
		if (typeof arguments[i] === 'object') {
			console.table(arguments[i]);
		}
		else {
			console.log(arguments[i]);
		}
	}
};

// Main Function
var Main = function () {

	// function to displays collapsible content panels
	var runPanelToggle = function () {
		// Tab panel active
		$(".list-group-item").on('click', function() {
			var $el = $(this).parent().closest(".list-group").children(".active");
			if ($el.hasClass("active")) {
				$el.removeClass("active");
				$(this).addClass("active");
			}
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
				cloneActiveShop = $.trim($('table[class="table"] tr:nth-child(2) th').first().html());
				$(cloneActiveShop).insertAfter("#tab_translation");
			}
		}

		// Custom Select
		$('.selectpicker').selectpicker();

		// Fix bug form builder + bootstrap select
		$('.selectpicker').each(function(){
			var select = $(this);
			select.on('click', function() {
				$(this).parents('.bootstrap-select').addClass('open');
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