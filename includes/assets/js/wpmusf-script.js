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
			$.ajax({
				data: $(this).serialize(),
				type: 'post',
				url: custom_script.ajaxurl,

				error: function (request, error) {
					alert('OOPs!! Something Went Wrong, Please try again later.');
				},
				success: function (response) {

				}
			});
		});
	});
})( jQuery );
