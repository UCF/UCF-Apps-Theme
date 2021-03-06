// Adds filter method to array objects
// https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/filter
if(!Array.prototype.filter){
	Array.prototype.filter=function(a){"use strict";if(this===void 0||this===null)throw new TypeError;var b=Object(this);var c=b.length>>>0;if(typeof a!=="function")throw new TypeError;var d=[];var e=arguments[1];for(var f=0;f<c;f++){if(f in b){var g=b[f];if(a.call(e,g,f,b))d.push(g)}}return d}
}

var WebcomAdmin = {};


WebcomAdmin.__init__ = function($){
	// Allows forms with input fields of type file to upload files
	$('input[type="file"]').parents('form').attr('enctype','multipart/form-data');
	$('input[type="file"]').parents('form').attr('encoding','multipart/form-data');
};


WebcomAdmin.shortcodeTool = function($){
	cls         = this;
	cls.metabox = $('#shortcodes-metabox');
	if (cls.metabox.length < 1){console.log('no meta'); return;}
	
	cls.form     = cls.metabox.find('form');
	cls.search   = cls.metabox.find('#shortcode-search');
	cls.button   = cls.metabox.find('button');
	cls.results  = cls.metabox.find('#shortcode-results');
	cls.select   = cls.metabox.find('#shortcode-select');
	cls.form_url = cls.metabox.find("#shortcode-form").val();
	cls.text_url = cls.metabox.find("#shortcode-text").val();
	
	cls.shortcodes = (function(){
		var shortcodes = new Array();
		cls.select.children('.shortcode').each(function(){
			shortcodes.push($(this).val());
		});
		return shortcodes;
	})();
	
	cls.shortcodeAction = function(shortcode){
		var text = "[" + shortcode + "]"
		send_to_editor(text);
	};
	
	cls.searchAction = function(){
		cls.results.children().remove();
		
		var value = cls.search.val();
		
		if (value.length < 1){
			return;
		}
		
		var found = cls.shortcodes.filter(function(e, i, a){
			return e.match(value);
		});
		
		if (found.length > 1){
			cls.results.removeClass('empty');
		}
		
		$(found).each(function(){
			var item      = $("<li />");
			var link      = $("<a />");
			link.attr('href', '#');
			link.addClass('shortcode');
			link.text(this.valueOf());
			item.append(link);
			cls.results.append(item);
		});
		
		
		if (found.length > 1){
			cls.results.removeClass('empty');
		}else{
			cls.results.addClass('empty');
		}
		
	};
	
	cls.buttonAction = function(){
		cls.searchAction();
	};
	
	cls.itemAction = function(){
		var shortcode = $(this).text();
		cls.shortcodeAction(shortcode);
		return false;
	};
	
	cls.selectAction = function(){
		var selected = $(this).find(".shortcode:selected");
		if (selected.length < 1){return;}
		
		var value = selected.val();
		cls.shortcodeAction(value);
	};
	
	//Resize results list to match size of input
	cls.results.width(cls.search.outerWidth());
	
	// Disable enter key causing form submit on shortcode search field
	cls.search.keyup(function(e){
		cls.searchAction();
		
		if (e.keyCode == 13){
			return false;
		}
	});
	
	// Search button click action, cause search
	cls.button.click(cls.buttonAction);
	
	// Option change for select, cause action
	cls.select.change(cls.selectAction);
	
	// Results click actions
	cls.results.find('li a.shortcode').live('click', cls.itemAction);
};


WebcomAdmin.themeOptions = function($){
	cls          = this;
	cls.active   = null;
	cls.parent   = $('.i-am-a-fancy-admin');
	cls.sections = $('.i-am-a-fancy-admin .fields .section');
	cls.buttons  = $('.i-am-a-fancy-admin .sections .section a');
	
	this.showSection = function(e){
		var button  = $(this);
		var href    = button.attr('href');
		var section = $(href);
		
		// Switch active styles
		cls.buttons.removeClass('active');
		button.addClass('active');
		
		cls.active.hide();
		cls.active = section;
		cls.active.show();
		
		history.pushState({}, "", button.attr('href'));
		var http_referrer = cls.parent.find('input[name="_wp_http_referer"]');
		http_referrer.val(window.location);
		return false;
	}
	
	this.__init__ = function(){
		cls.active = cls.sections.first();
		cls.sections.not(cls.active).hide();
		cls.buttons.first().addClass('active');
		cls.buttons.click(this.showSection);
		
		if (window.location.hash){
			cls.buttons.filter('[href="' + window.location.hash + '"]').click();
		}
		
		var fadeTimer = setInterval(function(){
			$('.updated').fadeOut(1000);
			clearInterval(fadeTimer);
		}, 2000);
	};
	
	if (cls.parent.length > 0){
		cls.__init__();
	}

	// Slider Meta Box Updates:
	// (only run this code if we're on a screen with #slider-slides-settings-basic;
	// i.e. if we're on a slider edit screen:
	if ($('#poststuff #slider-slides-settings-basic').length > 0) {

		var slide_count_widget 	 = $('#slider-slides-settings-count');		

		// Hide Preview Changes button
		// this is a temporary fix for an issue where previewing changes on a centerpiece deletes its uploaded media
		$('#minor-publishing-actions').hide();

		// Function that updates Slide Count value based on if a Slide's Content Type is selected:
		var checkSlideCount = function() {
			if (slide_count_widget.is('hidden')) {
				slide_count_widget.show();
			}

			var slideCount = $('input[name^="ss_type_of_content["]:checked').length;

			//alert('slideCount is: '+ slideCount + '; input value is: ' + $('input#ss_slider_slidecount').attr('value'));

			$("input#ss_slider_slidecount").attr('value', slideCount);

			if (slide_count_widget.is('visible')) {
				slide_count_widget.hide();
			}
		}


		// Update the Slide Sort Order:
		var updateSliderSortOrder = function() {
			var sortOrder = [];

			$('input[name^="ss_type_of_content["]:checked').each(function() {
				// get number by trimming the input ID
				var inputID =  ($(this).attr('name').split('ss_type_of_content[')[1])
				var inputID = inputID.substr(0, inputID.length - 1);

				sortOrder[sortOrder.length] = inputID;
			});

			if (slide_count_widget.is('hidden')) {
				slide_count_widget.show();
			}

			var orderString = '';
			$.each(sortOrder, function(index, value) {
				// make sure we only have number values (i.e. only slider widgets):
				if (!isNaN(value)) {
					orderString += value + ",";
				}
			});
			// add each value to Slide Order field value:
			$('#ss_slider_slideorder').attr('value', orderString);

			if (slide_count_widget.is('visible')) {
				slide_count_widget.hide();
			}
		}


		// If only one slide is available on the page, hide the 'Remove slide' button for that slide:
		var hideOnlyRemoveBtn = function() {
			if ($('#ss_slides_all li.custom_repeatable').length < 2) {
				$('#ss_slides_all li.custom_repeatable:first-child a.repeatable-remove').hide();
			}
			else {
				$('#ss_slides_all li.custom_repeatable a.repeatable-remove').show();
			}
		}


		// Sortable slides
		$('#ss_slides_all').sortable({
			handle      : 'h3.hndle',
			placeholder : 'sortable-placeholder',
			sort        : function( event, ui ) {
				$('.sortable-placeholder').height( $(this).find('.ui-sortable-helper').height() );
			},
			update		: function( event, ui ) {
				updateSliderSortOrder();
			},
			tolerance   :'pointer'
		});


		// Toggle slide with header click
		$('#slider_slides').delegate('.custom_repeatable .hndle', 'click', function() {
			$(this).siblings('.inside').toggle().end().parent().toggleClass('closed');
		});


		// Admin onload:
		slide_count_widget.hide();
		checkSlideCount();
		updateSliderSortOrder();
		hideOnlyRemoveBtn();
		if ($.browser.msie && $.browser.version < 8) {
			$('a.repeatable-add').remove().appendTo('#ss_slides_all');
		}


		// Add/remove Slide button functionality:
		$('.repeatable-add').click(function() {
			field = $(this).prev('li').clone(true);
			fieldLocation = $(this).prev('li');

			// Get the highest ID 'widget' number to prevent duplicate IDs after sorting:
			var widget_numbers = new Array();
			$('input[name^="ss_type_of_content["]').each(function() {
				// get number by trimming the input ID
				var inputID =  ($(this).attr('name').split('ss_type_of_content[')[1])
				var inputID = inputID.substr(0, inputID.length - 1);
				widget_numbers[widget_numbers.length] = inputID;
			});
			var highest_num = Math.max.apply(Math, widget_numbers);

			// Update 'name' attributes
			$('textarea, input[type="text"], input[type="select"], input[type="file"]', field).val('').attr('name', function(index, name) {
				return name.replace(/(\d+)/, highest_num + 1);
			});
			$('input[type="checkbox"], input[type="radio"]', field).attr('name', function(index, name) {
				return name.replace(/(\d+)/, highest_num + 1);
			});
			// Update 'for' attributes (in <label>)
			$('label', field).val('').attr('for', function(index, forval) {
				return forval.replace(/(\d+)/, highest_num + 1);
			});
			// Update 'id' attributes
			$('textarea, input[type="text"], input[type="select"], input[type="checkbox"], input[type="radio"]', field).attr('id', function(index, idval) {
				return idval.replace(/(\d+)/, highest_num + 1);
			});
			// Remove other existing data from previous slide:
			//$('input[type="radio"]', field).removeAttr('checked');
			$('label[for^="ss_slide_image["]', field).parent('th').next('td').children('a, br:nth-child(2)').remove();

			field.fadeIn().insertAfter(fieldLocation, $(this).prev('li'));

			hideOnlyRemoveBtn();
			checkSlideCount();
			updateSliderSortOrder();

			return false;
		});
		$('.repeatable-remove').click(function(){
			$(this).parent().remove();
			hideOnlyRemoveBtn();
			checkSlideCount();
			updateSliderSortOrder();
			return false;
		});
	}

	
};


(function($){
	WebcomAdmin.__init__($);
	WebcomAdmin.themeOptions($);
	WebcomAdmin.shortcodeTool($);
})(jQuery);
