/**
 * Admin Settings Js.
 *
 * @package Temporary Login Without Password
 */

(function ($) {
	'use strict';

	$(document).ready(
		function () {

			$('#add-new-wtlwp-form-button').click(
				function () {
					$('#new-wtlwp-form').show();
					$('#update-wtlwp-form').hide();
				}
			);

			$('#cancel-new-login-form').click(
				function () {
					$('#new-wtlwp-form').hide();
					$('#update-wtlwp-form').show();
				}
			);

			$('#cancel-update-login-form').click(
				function () {
					$('#update-wtlwp-form').hide();
				}
			);

			if ($('.wtlwp-click-to-copy-btn').get(0)) {

				var clipboard = new Clipboard('.wtlwp-click-to-copy-btn');

				clipboard.on(
					'success', function (e) {
						var elem = e.trigger;
						var className = elem.getAttribute('class');
						$('#copied-text-message-' + className).text('Copied').fadeIn();
						$('#copied-text-message-' + className).fadeOut('slow');
					}
				);
			}

			if ($('.wtlwp-copy-to-clipboard').get(0)) {
				var clipboard_link = new Clipboard('.wtlwp-copy-to-clipboard');

				clipboard_link.on(
					'success', function (e) {
						var elem = e.trigger;
						var id = elem.getAttribute('id');
						$('#copied-' + id).text('Copied').fadeIn();
						$('#copied-' + id).fadeOut('slow');
					}
				);
			}

			$('#new-user-expiry-time').change(
				function () {
					var value = $(this).val();
					showDatePicker(value, 'new');
				}
			);

			$('#update-user-expiry-time').change(
				function () {
					var value = $(this).val();
					showDatePicker(value, 'update');
				}
			);

			function showDatePicker(value, datePickerClass) {

				var customDatePickerClass = '';
				var customDatePickerID = '';
				if ('new' === datePickerClass) {
					customDatePickerClass = '.new-custom-date-picker';
					customDatePickerID = '#new-custom-date-picker';
				} else {
					customDatePickerClass = '.update-custom-date-picker';
					customDatePickerID = '#update-custom-date-picker';
				}

				if (value === 'custom_date') {
					var tomorrowDate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
					$(customDatePickerClass).datepicker(
						{
							dateFormat: 'yy-mm-dd',
							minDate: tomorrowDate
						}
					);
					$(customDatePickerID).show();
				} else {
					$(customDatePickerID).hide();
				}
			}

			// Add current IP button handler for both forms
			$(document).on('click', '.tlwp-use-current-ip-btn', function (e) {
				e.preventDefault();

				// Find the closest form to get the right context
				var $clickedElement = $(this);
				var $form = $clickedElement.closest('form');

				// Find IP field and current IP element within this form's context
				var $ipField = $form.find('#tlwp-allowed-ips');
				var $currentIPElement = $form.find('.tlwp-current-user-ip-value');

				if (!$ipField.length || !$currentIPElement.length) {
					return;
				}

				var currentIP = $currentIPElement.text().trim();
				var existingIPs = $ipField.val().trim();

				// Check if IP already exists in the list
				var ipArray = existingIPs.split(',').map(function (ip) {
					return ip.trim();
				});

				if (ipArray.indexOf(currentIP) !== -1) {
					alert('This IP address is already in the list.');
					return;
				}

				// Add IP to the field
				if (existingIPs === '') {
					$ipField.val(currentIP);
				} else {
					$ipField.val(existingIPs + ', ' + currentIP);
				}

				// Visual feedback
				$ipField.focus();
				$ipField.css('border-color', '#4F46E5');
				setTimeout(function () {
					$ipField.css('border-color', '');
				}, 1000);
			});

			/* Add tailwind classes to language dropdown */
			$('.wtlwp-language-dropdown select').addClass('form-select font-normal text-gray-600 h-8 shadow-sm');

			// Confirm before delete
			$('.tlwp-delete').click(function () {
				// escape here if the confirm is false;
				return confirm('Do you want to delete temporary user?');
			});



		}
	);

})(jQuery);

jQuery(function ($) {
	$('#wtlwp-enable-one-click-login').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			url: data.admin_ajax_url,
			type: 'POST',
			data: {
				action: 'wtlwp_enable_one_click_login',
				nonce: data.nonce,
				user_id: $(this).data('user-id'),
				enable: 'enable'
			},
			success: function (response) {
				if (response.success) {
					$('#wtlwp-enable-one-click-login').hide();
					$('.wtlwp-copy-to-clipboard').removeClass('hidden');
					$('.wtlwp-copy-to-clipboard-text').removeClass('hidden');
				} else {
					console.log('Failed:', response.data.message);
				}
			},
			error: function (err) {
				console.log('AJAX error:', err);
			}
		});
	});


	$('#wtlwp-max-login-upsale-notice, #wtlwp-max-login-upsale-field').on('click', function (e) {
		
		var utm_campaign_flow = $(this).attr('data-tlwp-promotional') === 'admin-promotional-notice'
		? $(this).attr('data-tlwp-promotional')
		: 'max-login-field';
		
		$.ajax({
			url: data.admin_ajax_url,
			type: 'POST',
			data: {
				action: 'wtlwp_save_upsell_flow',
				nonce: data.nonce,
				flow: utm_campaign_flow,
			},
			success: function (response) {
				console.log('Success:', response.data.message);
			},
			error: function (err) {
				console.log('AJAX error:', err);
			}
		});
	});

});