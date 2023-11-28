/**
 *  global jQuery, ajaxurl, custom_script
 */

( function ( $ ) {
	$(document).ready(function ($) {

		/**
		 * Script for submit the form
		 */
		$('#mpmu-simple-form').submit( function(e){
			e.preventDefault();
			$('#mpmu-submit-button').prop('disabled', true);
			$.ajax({
				data: $(this).serialize(),
				type: 'post',
				url: custom_script.ajaxurl,

				error: function (request, error) {
					alert('OOPs!! Something Went Wrong, Please try again later.');
				},
				success: function (response) {
					$('#mpmu-simple-form').trigger("reset");
					$('#mpmu-submit-button').prop('disabled', false);
					$('.wpmu_message').html(response.data.message);
					$('.mpmu-form-list').html(response.data.list);
				}
			});
		});
	});
})( jQuery );
