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

		/**
		 * WPMU Simple Form Search
		 */
		$('#wpmu_search').submit( function(e){
			e.preventDefault();
			$('#wpmu_search_button').prop('disabled', true);
			$.ajax({
				data: $(this).serialize(),
				type: 'post',
				url: custom_script.ajaxurl,

				error: function (request, error) {
					alert('OOPs!! Something Went Wrong, Please try again later.');
				},
				success: function (response) {
					//$('#wpmu_search').trigger("reset");
					$('#wpmu_search_button').prop('disabled', false);
					$('.wpmu_search_message').html(response.data.message);
					$('.mpmu-form-list').html(response.data.list);
				}
			});
		});

		/**
		 * search Pagination next
		 */
		$('body').on( 'click', '.wpmu_search_prev', function (){
			let offset = $(this).data("offset");
			let nonce = $(this).data("nonce");
			let key = $(this).attr("data-key");
			search_pagination({ method: 'search_wpmu_simple_form', action: 'wpmu_ajax', wpmu_search_key: key, search_nonce: nonce });

		} );

		/**
		 * search Pagination prev
		 */
		$('body').on( 'click', '.wpmu_search_next', function (){
			let offset = $(this).data("offset");
			let nonce = $(this).data("nonce");
			let key = $(this).attr("data-key");
			search_pagination({ method: 'search_wpmu_simple_form', action: 'wpmu_ajax', wpmu_search_key: key, search_nonce: nonce, offset:offset });
		} );

		/**
		 * Common Function for search pagination
		 * @param data
		 */
		function search_pagination( data ){
			$.ajax({
				data: data,
				type: 'post',
				url: custom_script.ajaxurl,

				error: function (request, error) {
					alert('OOPs!! Something Went Wrong, Please try again later.');
				},
				success: function (response) {
					$('.mpmu-form-list').html(response.data.list);
				}
			});
		}

		$('body').on( 'click', '.wpmu_list_prev', function (){
			let offset = $(this).data("offset");
			let nonce = $(this).data("nonce");

			list_pagination({ method: 'wpmu_simple_form_list', action: 'wpmu_ajax', search_nonce: nonce, offset: offset });
		});

		$('body').on( 'click', '.wpmu_list_next', function (){
			let offset = $(this).data("offset");
			let nonce = $(this).data("nonce");

			list_pagination({ method: 'wpmu_simple_form_list', action: 'wpmu_ajax', search_nonce: nonce, offset: offset });
		});

		/**
		 * Common Function for list pagination
		 * @param data
		 */
		function list_pagination( data ){
			$.ajax({
				data: data,
				type: 'post',
				url: custom_script.ajaxurl,

				error: function (request, error) {
					alert('OOPs!! Something Went Wrong, Please try again later.');
				},
				success: function (response) {
					$('.mpmu-form-list').html(response.data.list);
				}
			});
		}
	});
})( jQuery );
