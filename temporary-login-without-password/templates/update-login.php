<?php
/**
 * Update Login template
 *
 * @package Temporary Login Without Password
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h2> <?php echo esc_html__( 'Update Temporary Login', 'temporary-login-without-password' ); ?></h2>
<form method="post">
	<table class="form-table bg-white rounded-lg shadow-md">
		<tr class="form-field form-required">
			<th scope="row" class="wtlwp-form-row pt-10">
				<label for="user_email"><?php echo esc_html__( 'Email', 'temporary-login-without-password' ); ?> </label>
			</th>
			<td class="pt-10">
				<label for="user_email"><?php echo esc_attr( $temporary_user_data['email'] ); ?></label>
			</td>
		</tr>

		<tr class="form-field form-required">
			<th scope="row" class="wtlwp-form-row">
				<label for="user_first_name"><?php echo esc_html__( 'First Name', 'temporary-login-without-password' ); ?> </label>
			</th>
			<td>
				<input name="wtlwp_data[user_first_name]" type="text" id="user_first_name" value="<?php echo esc_attr( $temporary_user_data['first_name'] ); ?>" aria-required="true" maxlength="60" class="wtlwp-form-input form-input"/>
			</td>
		</tr>

		<tr class="form-field form-required">
			<th scope="row" class="wtlwp-form-row">
				<label for="user_last_name"><?php echo esc_html__( 'Last Name', 'temporary-login-without-password' ); ?> </label>
			</th>
			<td>
				<input name="wtlwp_data[user_last_name]" type="text" id="user_last_name" value="<?php echo esc_attr( $temporary_user_data['last_name'] ); ?>" aria-required="true" maxlength="60" class="wtlwp-form-input form-input"/>
			</td>
		</tr>

		<?php if ( is_network_admin() ) { ?>
			<tr class="form-field form-required">
				<th scope="row" class="wtlwp-form-row">
					<label for="user_super_admin"><?php echo esc_html__( 'Super Admin', 'temporary-login-without-password' ); ?> </label>
				</th>
				<td>
					<input type="checkbox" id="user_super_admin" name="wtlwp_data[super_admin]" class="form-checkbox">
					<?php echo esc_html__( 'Grant this user super admin privileges for the Network.', 'temporary-login-without-password' ); ?>
				</td>
			</tr>
		<?php } else { ?>
			<tr class="form-field">
				<th scope="row" class="wtlwp-form-row">
					<label for="adduser-role"><?php echo esc_html__( 'Role', 'temporary-login-without-password' ); ?></label>
				</th>
				<td>
					<select name="wtlwp_data[role]" id="user-role" class="form-select font-normal text-gray-600 h-8 shadow-sm">
						<?php
						$role = $temporary_user_data['role'];
						Wp_Temporary_Login_Without_Password_Common::tlwp_dropdown_roles( $visible_roles, $role );
						?>
					</select>
				</td>
			</tr>
		<?php } ?>

		<tr class="form-field">
			<th scope="row" class="wtlwp-form-row">
				<label for="redirect-to"><span class="text-sm font-medium text-gray-600 pb-2"><?php echo esc_html__( 'Redirect After Login', 'temporary-login-without-password' ); ?></span></label>
			</th>
			<td>
				<select name="wtlwp_data[redirect_to]" id="redirect-to" class="form-select font-normal text-gray-600 h-8 shadow-sm">
					<?php Wp_Temporary_Login_Without_Password_Common::tlwp_dropdown_redirect_to( $temporary_user_data['redirect_to'] ); ?>
				</select>
			</td>
		</tr>

		<tr class="form-field">
			<th scope="row" class="wtlwp-form-row">
				<label for="adduser-role"><?php echo esc_html__( 'Extend Expiry', 'temporary-login-without-password' ); ?></label>
			</th>
			<td>
				<span id="expiry-date-selection">
						<select name="wtlwp_data[expiry]" id="update-user-expiry-time" class="form-select font-normal text-gray-600 h-8 shadow-sm">
							<?php Wp_Temporary_Login_Without_Password_Common::get_expiry_duration_html( 'week' ); ?>
						</select>
				</span>

				<span style="display:none;" id="update-custom-date-picker">
					<input type="date" id="tlwp-datepicker" name="wtlwp_data[custom_date]" value="" class="update-custom-date-picker"/>
				</span>

			</td>
		</tr>
		<?php if ( Wp_Temporary_Login_Without_Password::is_pro() ) { ?>
			<tr class="form-field">
				<th scope="row" class="wtlwp-form-row">
					<label for="max-login-limit"><span class="text-sm font-medium text-gray-600 pb-2"><?php echo esc_html__( 'Max Login Limit', 'temporary-login-without-password' ); ?></span></label>
				</th>
				<td>
					<span id="max-login-limit">
					<input type="number" name="wtlwp_data[max_login_limit]" value="<?php echo esc_attr($temporary_user_data['max_login_limit'] ); ?>" class="wtlwp-form-input form-input text-center" min="0"/>					
					</span>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" class="wtlwp-form-row">
					<label for="tlwp-allowed-ips">
						<span class="text-sm font-medium text-gray-600 pb-2">
							<?php echo esc_html__( 'Allowed IP Addresses', 'temporary-login-without-password' ); ?>
						</span>
					</label>
					<p class="italic text-xs text-gray-400 mt-1 font-normal leading-snug">
						<?php echo esc_html__( 'Leave blank for no restriction', 'temporary-login-without-password' ); ?>
					</p>
				</th>
				<td>
					<input 
						type="text" 
						name="wtlwp_data[allowed_ips]" 
						id="tlwp-allowed-ips" 
						class="wtlwp-form-input form-input w-full"
						placeholder="<?php echo esc_attr__('e.g. 123.45.67.89, 10.0.0.1', 'temporary-login-without-password'); ?>"
						value="<?php echo isset( $temporary_user_data['allowed_ips'] ) ? esc_attr( $temporary_user_data['allowed_ips'] ) : ''; ?>"
					/>
					<p class="text-xs text-blue-600 mt-2 cursor-pointer hover:text-blue-800 tlwp-use-current-ip-btn">
						<span class="dashicons dashicons-location mt-1" style="font-size: 14px; vertical-align: middle;"></span>
						<?php 
						printf(
							/* translators: %s: User's current IP address */
							esc_html__('Use my current IP: %s', 'temporary-login-without-password'),
							'<strong class="tlwp-current-user-ip-value">' . esc_html(Wp_Temporary_Login_Without_Password_Common::get_client_ip()) . '</strong>'
						);
						?>
					</p>
				</td>
			</tr>
			<?php 
		} else { ?>
			<tr class="form-field">
				<th scope="row" class="wtlwp-form-row">
					<label for="max-login-limit"><span class="text-sm font-medium text-gray-600 pb-2"><?php echo esc_html__( 'Max Login Limit', 'temporary-login-without-password' ); ?></span></label>
				</th>
				<td>
					<a href="<?php echo admin_url( 'users.php?page=wp-temporary-login-without-password&tab=tlwp-pricing' );?>" id="wtlwp-max-login-upsale-field">
						<div style="display: flex; cursor: pointer">
							<input 
							type="number" 
							value="10"
							class="wtlwp-form-input form-input" min="0"
							style="color: transparent; text-shadow: 0 0 3px rgba(0,0,0,0.8); transition: all 0.2s; cursor: pointer; pointer-events: none; width: auto !important;" 
							readonly 
							/>
							<img src="<?php echo WTLWP_PLUGIN_URL . 'admin/assets/images/crown-icon.svg'; ?>" style="width: 18px; height: 18px; margin: 10px;">
						</div>
					</a>
				</td>
			</tr>
			<?php 
		}?>  
		<tr class="form-field">
			<th scope="row" class="wtlwp-form-row">
				<label for="language"><?php echo esc_html__( 'Language', 'temporary-login-without-password' ); ?></label>
			</th>
			<td scope="row" class="wtlwp-language-dropdown">
				<?php
				$locale = $temporary_user_data['locale'];
				wp_dropdown_languages( array( 'name' => 'wtlwp_data[locale]', 'selected' => $locale ) );
				?>
			</td>
		</tr>

		<tr class="form-field">
			<th scope="row" class="wtlwp-form-row"><label for="adduser-role"></label></th>
			<td>
				<p class="submit">
					<input type="submit" class="wtlwp-form-submit-button bg-indigo-600 p-2 rounded text-white cursor-pointer hover:bg-indigo-600" value="<?php esc_html_e( 'Submit', 'temporary-login-without-password' ); ?>" class="button button-primary" id="generatetemporarylogin" name="generate_temporary_login"> <?php esc_html_e( 'or', 'temporary-login-without-password' ); ?>
					<span class="cancel-update-login-form" id="cancel-update-login-form"><?php esc_html_e( 'Cancel', 'temporary-login-without-password' ); ?></span>
				</p>
			</td>
		</tr>
		<input type="hidden" name="wtlwp_action" value="update"/>
		<input type="hidden" name="wtlwp_data[user_id]" value="<?php echo esc_attr( $user_id ); ?>"/>
		<?php wp_nonce_field( 'manage-temporary-login_' . $user_id, 'manage-temporary-login', true, true ); ?>
	</table>
</form>
