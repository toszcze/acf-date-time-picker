(function($) {
	function initialize_field($el) {
		var container = $el.find('div.acf-date_time_picker');
		
		var params = $.extend({}, acf.l10n.date_time_picker, {
			dateFormat: container.data('date-format'),
			timeFormat: container.data('time-format'),
			firstDay: container.data('first-day'),
			changeYear: true,
			changeMonth: true,
			showButtonPanel: true
		});
		
		if(container.data('field-type') == 'date_time') {
			$el.find('input').datetimepicker(params);
		}
		else if(container.data('field-type') == 'time') {
			$el.find('input').timepicker(params);
		}
		
		// wrap the datepicker (only if it hasn't already been wrapped)
		if($('body > #ui-datepicker-div').length > 0) {
			$('#ui-datepicker-div').wrap('<div class="ui-acf" />');
		}
	}
	
	if(typeof acf.add_action !== 'undefined') {	
		/**
		* ready append (ACF5)
		*
		* @since 1.0.0
		*
		* @param $el (jQuery selection) the jQuery element which contains the ACF fields
		*/
		acf.add_action('ready append', function($el) {
			acf.get_fields({ type: 'date_time_picker' }, $el).each(function() {
				initialize_field($(this));
			});
		});
	}
	else {
		/**
		* acf/setup_fields (ACF4)
		*
		* This event is triggered when ACF adds any new elements to the DOM. 
		*
		* @since 1.0.0
		*
		* @param event e: an event object. This can be ignored
		* @param Element postbox: An element which contains the new HTML
		*/
		$(document).on('acf/setup_fields', function(e, postbox) {
			$(postbox).find('.field[data-field_type="date_time_picker"]').each(function() {
				initialize_field($(this));
			});
		});
	}
})(jQuery);
