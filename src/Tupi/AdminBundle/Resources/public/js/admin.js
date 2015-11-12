/*
 * admin.js
 * Date: 31/03/2014
 */

(function($) {
	
	$.fn.extend({
		chooseFile: function(id) {
			$("#" + id).click();
		},
		
		showName: function() {
			
			var pathName = $(this).val();
			var pattern = /\w+\.\w+/gi;
			var result = pathName.match(pattern);
			
			if(result.length > 0) {
				$("#" + $(this).attr('id') + 'Label').html(result);
			}
		},
		
		checkImage: function() {
			$(this).parent().find('.style_checkbox input').click();
			//$(this).parent().find('.style_checkbox input').change();
			//alert('Ok ' + $(this).parent().find('.style_checkbox input').length);
		},
		
		onCheck: function() {
			//alert($(":checked").length);
			//alert($(this).attr('id'));
			
			if($(this).parent().hasClass('input_checkboxChecked')) {
				$(this).parent().parent().removeClass( "checked" );	
			}
			else {
				$(this).parent().parent().addClass( "checked" );
			}
		}
	});
})(jQuery);