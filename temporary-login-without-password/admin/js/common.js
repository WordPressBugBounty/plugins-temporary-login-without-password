(function ($) {
	'use strict';

	$(document).ready(function () {

		if (typeof tempData !== 'undefined') {

			if (tempData.hasOwnProperty('is_temporary_login')) {

				var isTemporaryLogin = tempData.is_temporary_login;

				// Disable deactivation checkbox of Temporary Login Without Password plugin
				// for Temporary Logged in user so it cannot be selected via bulk actions
				if (isTemporaryLogin === "yes") {
					var pluginBaseName = tempData.plugin_base_name || 'temporary-login-without-password/temporary-login-without-password.php';
					var pluginSlug = tempData.plugin_slug || 'temporary-login-without-password';

					var getTLWPCheckbox = function () {
						return $('input[value="' + pluginBaseName + '"], input[value*="temporary-login-without-password"], input[value*="temporary-login"], tr[data-slug="' + pluginSlug + '"] th.check-column input, tr[data-slug*="temporary-login"] th.check-column input, tr[data-plugin="' + pluginBaseName + '"] th.check-column input, tr[data-plugin*="temporary-login"] th.check-column input');
					};

					var lockTLWPCheckbox = function () {
						var $checkbox = getTLWPCheckbox();
						if ($checkbox.length) {
							$checkbox.prop('checked', false).prop('disabled', true).attr('disabled', 'disabled').removeAttr('checked');
						}
					};

					lockTLWPCheckbox();
					setTimeout(lockTLWPCheckbox, 100);

					// Keep TLWP checkbox unchecked when header/footer "Select All" is clicked
					$(document).on('click change', 'th.check-column :checkbox, #cb-select-all-1, #cb-select-all-2, table', function () {
						setTimeout(lockTLWPCheckbox, 0);
					});
				}
			}
		}
	});

})(jQuery);
