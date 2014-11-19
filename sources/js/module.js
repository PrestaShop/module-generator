// function for debug
var cleanInt = function (x) {
	x = Number(x);
	return x >= 0 ? Math.floor(x) : Math.ceil(x);
}

var p = function () {
	var i = 0,
	arg_lenght = arguments.length;
	if (arg_lenght > 0) {
		for (i; i<arg_lenght; i++) {
			if (arguments[i] instanceof Array) {
				console.log(arguments[i]);
			}
			else if (typeof(arguments[i]) === 'object') {
				console.table(arguments[i]);
			} else {
				// console.log(arguments.callee.caller.toString());
				console.log(arguments[i]);
			}
		}
	}
};

// Main Function
var Main = function () {

	// function to displays collapsible content panels
	var runPanelToggle = function () {
		$(".contactus").on('click', function() {
			$href = $.trim($(this).attr('href'));
			$(".list-group a.active").each(function() {
				$(this).removeClass("active");
			});

			$(".list-group a.contacts").addClass("active");
		});

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