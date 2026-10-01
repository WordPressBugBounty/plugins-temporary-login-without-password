<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Wp_Temporary_Login_Without_Password_Admin' ) ) {

/**
 * Main Temporary Login Without Password Admin Class
 *
 * Manage settings, Temporary Logins
 *
 * @since 1.0
 * @package Temporary Login Without Password
 */
	class Wp_Temporary_Login_Without_Password_Admin {
		/**
		 * Plugin Name
		 *
		 * @since 1.0
		 * @var string $plugin_name
		 *
		 */
		private $plugin_name;

		/**
		 * Plugin Version
		 *
		 * @since 1.0
		 * @var string $version
		 *
		 */
		private $version;

		/**
		 * Initialize Admin Class
		 *
		 * @param string $plugin_name
		 * @param string $version
		 *
		 * @since 1.0
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

			add_action( 'wp_ajax_wtlwp_enable_one_click_login', array( $this, 'handle_enable_one_click_login' )); 

			// Hook CSS for frontend 
			add_action( 'wp_head', array( $this, 'temporary_user_adminbar_styles' ), 999 );
			
			// Hook JavaScript for both frontend and admin
			add_action( 'admin_footer', array( $this, 'temporary_user_adminbar_script' ), 999 );

			add_action( 'wp_footer', array( $this, 'temporary_user_adminbar_script' ), 999 );
			
			//add_action( 'init', array( $this, 'generate_tlwp_temporary_login_link' ) ); //testing only
			add_action( 'wp_ajax_wtlwp_save_upsell_flow', array( $this, 'save_upsell_flow' ));
		}

		/**
		 * Enqueue CSS
		 *
		 * @since 1.0
		 */
		public function enqueue_styles() {

			if ( $this->is_plugin_page() ) {

				if ( ! wp_style_is( 'tailwind-css', 'enqueued' ) ) {
					wp_enqueue_style( 'tailwind-css', plugin_dir_url( __FILE__ ) . 'dist/main.css', array(), WTLWP_PLUGIN_VERSION, 'all' );
				}

				if ( ! wp_style_is( $this->plugin_name, 'enqueued' ) ) {
					wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-temporary-login-without-password-admin.css', array(), WTLWP_PLUGIN_VERSION, 'all' );
				}

				if ( ! wp_style_is( 'jquery-ui-css', 'enqueued' ) ) {
					wp_enqueue_style( 'jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
				}
			}

		}

		/**
		 * Enqueue JS
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {

			$is_temporary_login = 'no';
			$current_user_id    = get_current_user_id();
			if ( Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $current_user_id ) ) {
				$is_temporary_login = 'yes';
			}

			if ( $this->is_plugin_page() ) {

				if ( ! wp_script_is( $this->plugin_name, 'enqueued' ) ) {
					wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-temporary-login-without-password-admin.js', array( 'jquery' ), WTLWP_PLUGIN_VERSION, false );
				}

				if ( ! wp_script_is( 'clipboardjs', 'enqueued' ) ) {
					wp_enqueue_script( 'clipboardjs', plugin_dir_url( __FILE__ ) . 'js/clipboard.min.js', array( 'jquery' ), WTLWP_PLUGIN_VERSION, false );
				}

				if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
					wp_enqueue_script( 'jquery' );
				}

				if ( ! wp_script_is( 'jquery-ui-core', 'enqueued' ) ) {
					wp_enqueue_script( 'jquery-ui-core' );
				}

				if ( ! wp_script_is( 'jquery-ui-datepicker', 'enqueued' ) ) {
					wp_enqueue_script( 'jquery-ui-datepicker' );
				}

				$data = array(
				'admin_ajax_url'     => admin_url( 'admin-ajax.php', 'relative' ),
				'is_temporary_login' => $is_temporary_login,
				'nonce'              => wp_create_nonce( 'wtlwp_nonce' ),
				);

				wp_localize_script( $this->plugin_name, 'data', $data );

			}

			if ( ! wp_script_is( 'tlwp-common', 'enqueued' ) ) {
				wp_enqueue_script( 'tlwp-common', plugin_dir_url( __FILE__ ) . 'js/common.js', array( 'jquery' ), WTLWP_PLUGIN_VERSION, false );

				list( $plugin_base_name ) = Wp_Temporary_Login_Without_Password_Common::get_plugin_base_names();

				$data = array(
					'is_temporary_login' => $is_temporary_login,
					'plugin_base_name'   => $plugin_base_name,
					'plugin_slug'        => 'temporary-login-without-password',
				);

				wp_localize_script( 'tlwp-common', 'tempData', $data );
			}
		}

		/**
		 * Check whether current page is temporary login plugin page
		 *
		 * @since 1.5.17
		 */
		public function is_plugin_page() {
			return Wp_Temporary_Login_Without_Password_Common::is_tlwp_admin_page();
		}

		/**
		 * Add admin menu for 'Temporary Logins' inside users section
		 *
		 * @since 1.0
		 */
		public function admin_menu() {
			add_users_page(
			__( 'Temporary Logins', 'temporary-login-without-password' ), __( 'Temporary Logins', 'temporary-login-without-password' ), apply_filters( 'tempadmin_user_cap', 'manage_options' ), 'wp-temporary-login-without-password', array(
				__class__,
				'admin_settings',
			)
			);
		}

		public static function wtlwp_get_settings( $settings = array() ) {
			$defaults = array(
				'default_role'             => 'administrator',
				'default_expiry_time'      => 'week',
				'visible_roles'            => array(),
				'default_redirect_to'      => '',
				'delete_data_on_uninstall' => 0,
				'default_max_login_limit'  => defined( 'WTLWP_DEFAULT_MAX_LOGIN_LIMIT' ) ? WTLWP_DEFAULT_MAX_LOGIN_LIMIT : 5, // fallback
			);

			$final_settings = array();

			foreach ( $defaults as $key => $default ) {
				if ( ! empty( $settings ) && isset( $settings[ $key ] ) ) {
					$value = $settings[ $key ];
					$final_settings[ $key ] = is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : sanitize_text_field( $value );
				} else {
					$final_settings[ $key ] = $default;
				}
			}

			return $final_settings;
		}



		/**
		 * Manage admin settings
		 *
		 * @return void
		 *
		 * @since 1.0
		 */
		public static function admin_settings() {

			$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'home';
			//$_template_file      = WTLWP_PLUGIN_DIR . '/templates/admin-settings.php';
			$wtlwp_generated_url = isset( $_REQUEST['wtlwp_generated_url'] ) ? urldecode( wp_unslash( $_REQUEST['wtlwp_generated_url'] ) ) : '';
			$user_email          = isset( $_REQUEST['user_email'] ) ? sanitize_email( wp_unslash( $_REQUEST['user_email'] ) ) : '';

			$tlwp_settings       = maybe_unserialize( get_option( 'tlwp_settings', array() ) );		
			$action  = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
			$user_id = isset( $_GET['user_id'] ) ? absint( wp_unslash( $_GET['user_id'] ) ) : 0;

			$do_update           = ( 'update' === $action ) ? 1 : 0;

			$_template_file = WTLWP_PLUGIN_DIR . '/templates/admin-settings.php';

			$is_temporary_login = false;
			$current_user_id    = get_current_user_id();
			if ( Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $current_user_id ) ) {
				$is_temporary_login = true;
			}

			$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ( $is_temporary_login ? 'system-info' : 'home' );
 
			if ( ! $is_temporary_login ) {

				if ( ! empty( $user_id ) ) {
					$temporary_user_data = Wp_Temporary_Login_Without_Password_Common::get_temporary_logins_data( $user_id );
				}

				$settings = self::wtlwp_get_settings( $tlwp_settings );

				$default_role             = $settings['default_role'];
				$default_expiry_time      = $settings['default_expiry_time'];
				$visible_roles            = $settings['visible_roles'];
				$default_redirect_to      = $settings['default_redirect_to'];
				$delete_data_on_uninstall = $settings['delete_data_on_uninstall'];
				$default_max_login_limit  = $settings['default_max_login_limit'];

				if ( ! empty( $wtlwp_generated_url ) ) {
					$mailto_link = Wp_Temporary_Login_Without_Password_Common::generate_mailto_link( $user_email, $wtlwp_generated_url );
				}
			}

			include $_template_file;
		}

	//Temporary user creation by one click
	public function maybe_create_one_click_user() {
		$one_click_status = get_option( 'wtlwp_one_click_user_status' );

		if ( 'created' === $one_click_status || 'creating' === $one_click_status ) {
			return; // One-click user already created or creation in progress.
		}

		$tlwp_users = get_users( array(
			'meta_key'   => '_wtlwp_user',// phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value' => true, // phpcs:ignore WordPress.DB.SlowDBQuery
			'number'     => 1,
		) );

		if ( ! empty( $tlwp_users ) ) {
			update_option( 'wtlwp_one_click_user_status', 'created' );
			return;
		}

		if ( current_user_can( 'manage_options' ) && 'pending' === $one_click_status ) {
			update_option( 'wtlwp_one_click_user_status', 'creating' );
			self::create_one_click_user( );
		}
	}
		/**
		 * Create a Temporary user
		 *
		 * @return void
		 *
		 * @since 1.0
		 */
		public static function create_user( $user_data = array()) {

			if (
					empty( $_POST['wtlwp_data'] ) ||
					empty( $_POST['wtlwp-nonce'] ) ||
					( ! empty( $_POST['wtlwp_action'] ) && 'update' === sanitize_text_field( wp_unslash( $_POST['wtlwp_action'] ) ) ) ||
					! current_user_can( 'manage_options' ) ||
					! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wtlwp-nonce'] ) ), 'wtlwp_generate_login_url' )
				) {
				return;
			}

			$data  = self::sanitize_data( $_POST['wtlwp_data'] );

			$email  = $data['user_email'];
			$error  = true;
			$result = array(
			'status' => 'error',
			);

			$redirect_link = '';
			if ( false == Wp_Temporary_Login_Without_Password_Common::can_manage_wtlwp() ) {
				$result['message'] = 'unauthorised_access';
			} elseif ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wtlwp-nonce'] ) ), 'wtlwp_generate_login_url' ) ) {
				$result['message'] = 'nonce_failed';
			} elseif ( empty( $data['user_email'] ) ) {
				$result['message'] = 'empty_email';
			} elseif ( ! is_email( $email ) ) {
				$result['message'] = 'not_valid_email';
			} elseif ( ! empty( $data['user_email'] ) && email_exists( $data['user_email'] ) ) {
				$result['message'] = 'email_is_in_use';
			} else {
				$error = false;
			}

			if ( ! $error ) {
				$user = Wp_Temporary_Login_Without_Password_Common::create_new_user( $data );
				
				if ( isset( $user['error'] ) && true === $user['error'] ) {
					$result = array(
					'status'  => 'error',
					'message' => 'user_creation_failed',
					);
				} else {
					$result = array(
					'status'  => 'success',
					'message' => 'user_created',
					);

					$user_id       = isset( $user['user_id'] ) ? $user['user_id'] : 0;

					$redirect_link = Wp_Temporary_Login_Without_Password_Common::get_redirect_link( $result );
					$redirect_link = add_query_arg( 'user_email', $email, $redirect_link );

					$wtlwp_generated_url = urlencode( Wp_Temporary_Login_Without_Password_Common::get_login_url( $user_id ) );

					$redirect_link = add_query_arg( 'wtlwp_generated_url', $wtlwp_generated_url, $redirect_link );			
				}
			}

			if ( empty( $redirect_link ) ) {
				$redirect_link = Wp_Temporary_Login_Without_Password_Common::get_redirect_link( $result );
			}

			wp_safe_redirect( $redirect_link, 302 );
			exit();
		
		}

	public static function create_one_click_user() {
   
		$data         = self::get_user_creation_data();
		$data['source_of_creation'] = 'one-click';
		$result       = array( 'status' => 'error' );
		$redirect_link = '';
		$user_id      = 0;

		if ( false === Wp_Temporary_Login_Without_Password_Common::can_manage_wtlwp() ) {
			$result['message'] = 'unauthorised_access';
			update_option( 'wtlwp_one_click_user_status', 'pending' );
		} else {
			$user = Wp_Temporary_Login_Without_Password_Common::create_new_user( $data );

			if ( isset( $user['error'] ) && true === $user['error'] ) {
				$result['message'] = 'user_creation_failed';
				update_option( 'wtlwp_one_click_user_status', 'pending' );
			} else {
				$user_id = isset( $user['user_id'] ) ? $user['user_id'] : 0;

				if ( $user_id ) {
					set_transient( 'wtlwp_one_click_user_id', $user_id, 15 * HOUR_IN_SECONDS );
					set_transient( 'wtlwp_one_click_user_active', 'yes', 15 * HOUR_IN_SECONDS );
					// Mark as created successfully
					update_option( 'wtlwp_one_click_user_status', 'created' );
					update_user_meta( $user_id, '_wtlwp_expire', Wp_Temporary_Login_Without_Password_Common::get_current_gmt_timestamp() );
				} else {
					// User creation returned no user_id, reset status
					update_option( 'wtlwp_one_click_user_status', 'pending' );
				}

				$result['status']  = 'success';
				$result['message'] = 'user_created';
			}

			$redirect_link = Wp_Temporary_Login_Without_Password_Common::get_redirect_link( $result );
			if ( $user_id ) {
				$wtlwp_generated_url = urlencode(  Wp_Temporary_Login_Without_Password_Common::get_login_url( $user_id ) );
				$redirect_link       = add_query_arg( 'wtlwp_generated_url', $wtlwp_generated_url, $redirect_link );
			}
		}

		if ( empty( $redirect_link ) ) {
			$redirect_link = Wp_Temporary_Login_Without_Password_Common::get_redirect_link( $result );
		}

		wp_safe_redirect( $redirect_link, 302 );
		exit();
	}
		private static function get_user_creation_data() {
			
				$tlwp_settings       = maybe_unserialize( get_option( 'tlwp_settings', array() ) );
				$settings            = self::wtlwp_get_settings( $tlwp_settings );
				$default_role        = $settings['default_role'];
				$default_expiry_time = $settings['default_expiry_time'];
				$default_redirect_to = $settings['default_redirect_to'];
				
				return array(
				'user_email'       => '',
				'user_first_name'  => 'Temp',
				'user_last_name'   => 'User',
				'role'             => $default_role,
				'redirect_to'      => $default_redirect_to,
				'expiry'           => $default_expiry_time,
				);
			
		}
	
		/**
		 * Generate a temporary login link using the TLWP plugin.
		 *
		 * @return array Result containing status, login URL, user ID, and message.
		 */
		public static function generate_tlwp_temporary_login_link() {
			
			$data                = self::get_user_creation_data();
			$data['source_of_creation'] = 'tlwp-integration';
			$result              = array( 'status' => 'error' );
			$generated_login_url = '';

			if ( ! empty( $data ) && class_exists( 'Wp_Temporary_Login_Without_Password_Common' ) ) {
				$user    = Wp_Temporary_Login_Without_Password_Common::create_new_user( $data );
				$user_id = isset( $user['user_id'] ) ? (int) $user['user_id'] : 0;

				if ( $user_id ) {
					$login_url = Wp_Temporary_Login_Without_Password_Common::get_login_url( $user_id );

					if ( $login_url ) {
						$generated_login_url = urlencode( $login_url );

						$result = array(
							'status'    => 'success',
							'login_url' => $generated_login_url,
							'user_id'   => $user_id,
							'message'   => 'user_created',
						);
					}
				} else {
					$result['message'] = 'user_creation_failed';
				}
			}
			return $result;
		}

		/**
		 * Manage settings
		 *
		 * @return Void
		 *
		 * @since 1.4.6
		 */
		public function update_tlwp_settings() {

			if ( Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				return;
			}

			if ( empty( $_POST['tlwp_settings_data'] ) || empty( $_POST['wtlwp-settings-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wtlwp-settings-nonce'] ) ), 'wtlwp_login_settings' ) || ! current_user_can( 'manage_options' ) ) {
				return;
			}
			
			$data = $_POST['tlwp_settings_data'];

			$default_role             = isset( $data['default_role'] ) ? $data['default_role'] : 'administrator';
			$default_expiry_time      = isset( $data['default_expiry_time'] ) ? $data['default_expiry_time'] : 'week';
			$visible_roles            = isset( $data['visible_roles'] ) ? $data['visible_roles'] : array();
			$default_redirect_to      = isset( $data['default_redirect_to'] ) ? $data['default_redirect_to'] : '';
			$delete_data_on_uninstall = isset( $data['delete_data_on_uninstall'] ) ? 1 : 0;
			$default_max_login_limit = !empty( $data['default_max_login_limit'] ) ? $data['default_max_login_limit'] : WTLWP_DEFAULT_MAX_LOGIN_LIMIT;

			if ( ! in_array( $default_role, $visible_roles ) ) {
				$visible_roles[] = $default_role;
			}

			$tlwp_settings = array(
			'default_role'             => $default_role,
			'default_expiry_time'      => $default_expiry_time,
			'visible_roles'            => $visible_roles,
			'default_redirect_to'      => $default_redirect_to,
			'delete_data_on_uninstall' => $delete_data_on_uninstall,
			'default_max_login_limit'  => $default_max_login_limit
			);

			update_option( 'tlwp_settings', maybe_serialize( $tlwp_settings ), true );

			$result = array(
			'status'  => 'success',
			'message' => 'settings_updated',
			'tab'     => 'settings',
			);

			$redirect_link = Wp_Temporary_Login_Without_Password_Common::get_redirect_link( $result );

			wp_redirect( $redirect_link, 302 );
			exit();
		}

		/**
		 * Manage temporary logins
		 *
		 * @since 1.0
		 */
		public static function manage_temporary_login() {

			// Don't have wtlwp_action or user_id? Say Good Bye...
			if ( empty( $_REQUEST['wtlwp_action'] ) || empty( $_REQUEST['user_id'] ) ) {
				return;
			} 

			$action = $_REQUEST['wtlwp_action'];

			// We support following actions
			$valid_actions = array(
			'disable',
			'enable',
			'delete',
			'update',
			);

			if ( ! in_array( $action, $valid_actions ) ) {
				return;
			}

			// Can manage Temporary Logins? If yes..go ahead
			if ( ( false === Wp_Temporary_Login_Without_Password_Common::can_manage_wtlwp() ) || ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$error   = false;
			$user_id = absint( $_REQUEST['user_id'] );
			$nonce = ! empty( $_REQUEST['manage-temporary-login'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['manage-temporary-login'] ) ) : '';
			$result  = array();

			// Perform action only on the valid temporary login
			$is_valid_temporary_user = Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $user_id, false );

			if ( ! $is_valid_temporary_user ) {
				$result = array(
				'status'  => 'error',
				'message' => 'is_not_temporary_login',
				);
				$error  = true;
			} elseif ( ! wp_verify_nonce( $nonce, 'manage-temporary-login_' . $user_id ) ) {
				$result = array(
				'status'  => 'error',
				'message' => 'nonce_failed',
				);
				$error  = true;
			}

			if ( ! $error ) {
				if ( 'disable' === $action ) {
					$disable_login = Wp_Temporary_Login_Without_Password_Common::manage_login( absint( $user_id ), 'disable' );
					if ( $disable_login ) {
						$result = array(
						'status'  => 'success',
						'message' => 'login_disabled',
						);
					} else {
						$result = array(
						'status'  => 'error',
						'message' => 'default_error_message',
						);
					}
				} elseif ( 'enable' === $action ) {
					$enable_login = Wp_Temporary_Login_Without_Password_Common::manage_login( absint( $user_id ), 'enable' );
					if ( $enable_login ) {
						$result = array(
						'status'  => 'success',
						'message' => 'login_enabled'
						);

					} else {
						$result = array(
						'status'  => 'error',
						'message' => 'default_error_message',
						);
					}
				} elseif ( 'delete' === $action ) {
					$delete_user = wp_delete_user( $user_id, get_current_user_id() );

					// delete user from Multisite network too!
					if ( is_multisite() ) {

						// If it's a super admin, we can't directly delete user from network site.
						// We need to revoke super admin access first and then delete user
						if ( is_super_admin( $user_id ) ) {
							revoke_super_admin( $user_id );
						}

						$delete_user = wpmu_delete_user( $user_id );
					}

					if ( ! is_wp_error( $delete_user ) ) {
						$result = array(
						'status'  => 'success',
						'message' => 'user_deleted',
						);
					} else {
						$result = array(
						'status'  => 'error',
						'message' => 'default_error_message',
						);
					}
				} elseif ( 'update' === $action ) {

					$data = ! empty( $_POST['wtlwp_data'] ) ? self::sanitize_data( $_POST['wtlwp_data'] ) : array();

					$target_user_id = absint( $user_id );

					$update = Wp_Temporary_Login_Without_Password_Common::update_user( $target_user_id, $data );

					if ( $update ) {
						$result = array(
						'status'  => 'success',
						'message' => 'user_updated',
						);
					} else {
						$result = array(
						'status'  => 'error',
						'message' => 'default_error_message',
						);
					}
				} else {
					$result = array(
					'status'  => 'error',
					'message' => 'invalid_action',
					);
				}// End if().
			}// End if().
		
			$redirect_link = Wp_Temporary_Login_Without_Password_Common::get_redirect_link( $result );
			wp_redirect( $redirect_link, 302 );
			exit();
		}

		/**
		 * Display Success/ Error message
		 *
		 * @since 1.0
		 */
		public function tlwp_display_admin_notices() {

			if ( empty( $_REQUEST['page'] ) || ( empty( $_REQUEST['page'] ) && 'wp-temporary-login-without-password' !== $_REQUEST['page'] ) || ! isset( $_REQUEST['wtlwp_message'] ) || ( ! isset( $_REQUEST['wtlwp_error'] ) && ! isset( $_REQUEST['wtlwp_success'] ) ) ) { // Input var okay.
				return;
			}

			$messages = array(
			'user_creation_failed'    => __( 'User creation failed', 'temporary-login-without-password' ),
			'unauthorised_access'     => __( 'You do not have permission to create a temporary login', 'temporary-login-without-password' ),
			'email_is_in_use'         => __( 'Email is already in use', 'temporary-login-without-password' ),
			'empty_email'             => __( 'Please enter valid email address. Email field should not be empty', 'temporary-login-without-password' ),
			'not_valid_email'         => __( 'Please enter valid email address', 'temporary-login-without-password' ),
			'is_not_temporary_login'  => __( 'User you are trying to delete is not temporary', 'temporary-login-without-password' ),
			'nonce_failed'            => __( 'Nonce failed', 'temporary-login-without-password' ),
			'invalid_action'          => __( 'Invalid action', 'temporary-login-without-password' ),
			'default_error_message'   => __( 'Unknown error occurred', 'temporary-login-without-password' ),
			'user_created'            => __( 'Login created successfully!', 'temporary-login-without-password' ),
			'user_updated'            => __( 'Login updated successfully!', 'temporary-login-without-password' ),
			'user_deleted'            => __( 'Login deleted successfully!', 'temporary-login-without-password' ),
			'login_disabled'          => __( 'Login disabled successfully!', 'temporary-login-without-password' ),
			'login_enabled'           => __( 'Login enabled successfully!', 'temporary-login-without-password' ),
			'settings_updated'        => __( 'Settings have been updated successfully', 'temporary-login-without-password' ),
			'default_success_message' => __( 'Success!', 'temporary-login-without-password' ),
			);

			$class   = $message = '';
			$error   = ! empty( $_REQUEST['wtlwp_error'] ) ? true : false; // Input var okay.
			$success = ! empty( $_REQUEST['wtlwp_success'] ) ? true : false; // Input var okay.
			if ( $error ) {
				$message_type = ! empty( $_REQUEST['wtlwp_message'] ) ? sanitize_text_field( $_REQUEST['wtlwp_message'] ) : 'default_error_message';
				$message      = isset( $messages[ $message_type ] ) ? $messages[ $message_type ] : 'An error occurred. Please try again.';
				$class        = 'error';
			} elseif ( $success ) {
				$message_type = ! empty( $_REQUEST['wtlwp_message'] ) ? sanitize_text_field( $_REQUEST['wtlwp_message'] ) : 'default_success_message';
				$message      = isset( $messages[ $message_type ] ) ? $messages[ $message_type ] : 'Operation successful!';
				$class        = 'updated';
			}

			$class .= ' notice notice-succe is-dismissible';

			if ( ! empty( $message ) ) {
				$notice = '';
				$notice .= '<div id="notice" class="' . esc_attr( $class ) . '">';
				$notice .= '<p>' . esc_html( $message ) . '</p>';
				$notice .= '</div>';

				echo $notice;
			}

			return;
		}

		/**
		 * Disable welcome notification for temporary user.
		 *
		 * @param int $blog_id
		 * @param int $user_id
		 * @param string $password
		 * @param string $title
		 * @param string $meta
		 *
		 * @return bool
		 */
		public function disable_welcome_notification( $blog_id, $user_id, $password, $title, $meta ) {

			if ( ! empty( $user_id ) ) {
				$check_expiry = false;
				if ( Wp_Temporary_Login_Without_Password_Common::is_valid_temporary_login( $user_id, $check_expiry ) ) {
					return false;
				}
			}

			return true;
		}

		public function tlwp_show_promotion_notice() {

			if ( ! Wp_Temporary_Login_Without_Password_Common::is_tlwp_admin_page() ) {
				return;
			}

			if ( Wp_Temporary_Login_Without_Password::is_pro() ) {
				return;
			}

			if ( get_option( 'tlwp_close_promotion_notice' ) ) {
				return;
			}

			/* translators: 1. Anchor start tag 2. Anchor close tag */
			$promotion_optin_link = sprintf(__( ' %1$sGo Pro – More Power, Less Limits!%2$s', 'temporary-login-without-password' ), "<a class='wtlwp-promo-link text-indigo-600 font-bold' href='" . esc_url('https://www.icegram.com/temporary-login-without-password/?utm_source=in-app&utm_medium=admin-notice&utm_campaign=launch-promotion') . "' target='_blank'><b>", '</b></a>' );

			$promotion_plugin_name = sprintf(__( ' %1$sTemporary Login Without Password%2$s', 'temporary-login-without-password' ), '<b>', '</b>' );

			?>
			<div class="notice notice-success is-dismissible" id="wtlwp-promotion-custom-notice">
				<p>
					<?php
						echo sprintf( esc_html__( "Launch new %1\$s plugin's pro plan. %2\$s 🚀", 'temporary-login-without-password' ), wp_kses_post( $promotion_plugin_name ), wp_kses_post( $promotion_optin_link ) );
					?>
				</p>
				
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('#wtlwp-promotion-custom-notice').on('click', '.notice-dismiss, .wtlwp-promo-link', function() {
						$.ajax({
							method: 'POST',
							url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
							dataType: 'json',
							data: {
								action: 'tlwp_dismiss_promo_custom_notice',
								security: '<?php echo esc_html( wp_create_nonce( 'tlwp-promo-dismiss-action' ) ); ?>'
							}
						}).done(function(response){
							console.log( 'response: ', response );
						});
					});
				});
			</script>
			<?php
		}

	 // Sanitize the data array
		public static function sanitize_data( $data ) {
			if ( is_array( $data ) ) {
				foreach ( $data as $key => $value ) {
					$data[ $key ] = sanitize_text_field( wp_unslash( $value ) );
				}
			} else {
				$data = sanitize_text_field( wp_unslash( $data ) );
			}
			return $data;
		}
		public function tlwp_dismiss_promo_custom_notice() {
			$response = array(
			'status' => 'success',
			);
	
			check_ajax_referer( 'tlwp-promo-dismiss-action', 'security' );
	
			update_option( 'tlwp_close_promotion_notice', 'yes', false );
	
			wp_send_json( $response );
		}

		public function show_tlwp_mailer_promotion_notice() {

			$screen = get_current_screen();

			if ( $screen->base === 'users_page_wp-temporary-login-without-password' && isset($_GET['tab']) && $_GET['tab'] === 'tlwp-pricing') {
				return;
			}
			
			if ( ! Wp_Temporary_Login_Without_Password_Common::is_tlwp_admin_page() ) {
				return;
			}

			$mailer_plugin_path = 'icegram-mailer/icegram-mailer.php';

			if ( is_plugin_active($mailer_plugin_path) ) { 
				return;
			}

			if ( file_exists(WP_PLUGIN_DIR . '/' . $mailer_plugin_path) ) {
				$optin_url = admin_url( 'plugins.php' );
				$optin_btn_txt = esc_html('Activate Now →', 'temporary-login-without-password' );
			} else {
				$optin_url = admin_url( 'plugin-install.php?s=icegram%2520mailer&tab=search&type=term' );
				$optin_btn_txt = esc_html('Try Now →', 'temporary-login-without-password' );
			}

			$fallback_notice_dismissed = 'yes' === get_option( 'tlwp_mailer_is_promotion_notice_dismissed', 'no' );

			if ( ! $fallback_notice_dismissed ) {
				?>
				<div id="tlwp_es_mailer_promotion_notice" class="notice is-dismissible" style="border-left-width:1px;">
					<div style="display: flex;gap:0.8em;padding-top:0.5rem;padding-bottom:0.4rem;">
						<img src="https://ps.w.org/icegram-mailer/assets/icon-128x128.png" style="height:4em;margin-top: 0.1rem;" />
						<div style="color: rgb(55 65 81);">
							<p style="font-weight: bolder; font-size:0.8rem; margin:0; font-size:1.1em">
								<?php echo esc_html__( 'Get 200 Free Emails Every Month!', 'temporary-login-without-password' ); ?>
							</p>

							<p style="margin:0; font-size:1.1em;font-weight:400;">
								<?php echo wp_kses_post(
										sprintf(
											__( 'Start sending with confidence. No setup needed, no SMTP headaches. Enjoy 200 emails per month absolutely free with <span style="font-weight: 700;">%s</span>.', 'temporary-login-without-password' ),
											'Icegram Mailer'
										)
									); ?>
								<a href="<?php echo esc_url( $optin_url ); ?>" target="_blank" id="tlwp_mailer_promo_button">
									<span class="cursor-pointer ml-1 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white transition duration-150 ease-in-out px-3 py-1 hover:bg-gray-50 hover:text-gray-700 focus:ring-2 focus:ring-blue-200">
										<?php echo esc_html__( $optin_btn_txt, 'temporary-login-without-password'); ?>
									</span>
								</a>
							</p>
						</div>
					</div>
				</div>
				<script>
					jQuery(document).ready(function($) {
						jQuery('#tlwp_es_mailer_promotion_notice').on('click', '.notice-dismiss, #tlwp-es-mailer-promo-button', function() {
							jQuery.ajax({
								method: 'POST',
								url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
								dataType: 'json',
								data: {
									action: 'tlwp_dismiss_mailer_promotion_notice',
									security: '<?php echo wp_create_nonce( 'tlwp-dissmiss-mailer-notice' );?>',
								}
							}).done(function(response){
								console.log( 'response: ', response );
							});
						});

						jQuery('#tlwp_es_mailer_promotion_notice').on('click', '#tlwp_mailer_promo_button', function() {
							jQuery.ajax({
								method: 'POST',
								url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
								dataType: 'json',
								data: {
									action: 'tlwp_mailer_notice_clickable',
									security: '<?php echo wp_create_nonce( 'tlwp-mailer-notice-clickable' );?>',
								}
							}).done(function(response){
								console.log( 'response: ', response );
							});
						});
					});
				</script>
				<?php
			}
		}

		public function dismiss_tlwp_mailer_promotion_notice() {
			$response = array(
				'status' => 'success',
			);

			check_ajax_referer( 'tlwp-dissmiss-mailer-notice', 'security' );

			update_option( 'tlwp_mailer_is_promotion_notice_dismissed', 'yes', false );

			wp_send_json( $response );
		}

		public function mailer_notice_clickable() {
			$response = array(
				'status' => 'success',
			);

			check_ajax_referer( 'tlwp-mailer-notice-clickable', 'security' );

			update_option( 'tlwp_mailer_is_tried', 'yes', false );

			wp_send_json( $response );
		}

		/**
		 *
		 * Disable plugin deactivation link for the temporary user
		 *
		 * @param array $actions
		 * @param string $plugin_file
		 * @param array $plugin_data
		 * @param string $context
		 *
		 * @return mixed
		 * @since 1.4.5
		 *
		 */
		public function disable_plugin_deactivation( $actions, $plugin_file, $plugin_data, $context ) {

			if ( ! Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				return $actions;
			}

			list( $plugin_base_name, $default_base_name ) = Wp_Temporary_Login_Without_Password_Common::get_plugin_base_names();

			if ( $default_base_name === $plugin_file || $plugin_base_name === $plugin_file ) {
				// Remove actions by known stable keys.
				$restricted_keys = array( 'deactivate', 'settings');
				foreach ( $restricted_keys as $key ) {
					unset( $actions[ $key ] );
				}

				// Remove remaining actions by stable URL/attribute patterns (not display text).
				$restricted_patterns = array(
					'_action=request_data',
					'connect_icegram',
					'disconnect_icegram',
					'tab=settings',
				);

				foreach ( $actions as $key => $action_link ) {
					foreach ( $restricted_patterns as $pattern ) {
						if ( false !== stripos( $action_link, $pattern ) ) {
							unset( $actions[ $key ] );
							break;
						}
					}
				}
			}

			return $actions;
		}

		/**
		 * Prevent deactivating TLWP plugin and restricted actions by temporary login users via bulk action or direct URL.
		 *
		 * @since 1.8.4
		 */
		public function prevent_tlwp_deactivation() {

			if ( ! Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				return;
			}

			list( $plugin_base_name, $default_base_name ) = Wp_Temporary_Login_Without_Password_Common::get_plugin_base_names();

			// Exclude TLWP from bulk actions (deactivate, enable auto updates, disable auto updates)
			if ( isset( $_POST['checked'] ) && is_array( $_POST['checked'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				$checked_plugins = map_deep( wp_unslash( $_POST['checked'] ), 'sanitize_text_field' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

				$checked_plugins = array_values(
					array_filter(
						$checked_plugins,
						function( $plugin ) use ( $plugin_base_name, $default_base_name ) {
							return ( $plugin !== $plugin_base_name && $plugin !== $default_base_name );
						}
					)
				);

				$_POST['checked'] = $checked_plugins; // phpcs:ignore WordPress.Security.NonceVerification.Missing
				if ( isset( $_REQUEST['checked'] ) && is_array( $_REQUEST['checked'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$_REQUEST['checked'] = $checked_plugins; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				}
			}

			// Block direct single actions via URL (deactivate, enable-auto-update, disable-auto-update)
			if ( isset( $_GET['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$action = sanitize_text_field( wp_unslash( $_GET['action'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$restricted_actions = array( 'deactivate', 'enable-auto-update', 'disable-auto-update' );
				if ( in_array( $action, $restricted_actions, true ) ) {
					$plugin = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( $plugin_base_name === $plugin || $default_base_name === $plugin ) {
						wp_die( esc_html__( 'Temporary users are not allowed to perform this action.', 'temporary-login-without-password' ) );
					}
				}
			}

			// Block Check for updates request by temporary user
			$wtlwp_action = isset( $_GET['wtlwp_action'] ) ? sanitize_text_field( wp_unslash( $_GET['wtlwp_action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$get_action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( 'request_data' === $wtlwp_action || 'request_data' === $get_action ) {
				wp_die( esc_html__( 'Temporary users are not allowed to check for updates.', 'temporary-login-without-password' ) );
			}

			// Block temporary user accessing Settings tab directly
			global $pagenow;
			$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$tab  = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( 'users.php' === $pagenow && 'wp-temporary-login-without-password' === $page && 'settings' === $tab ) {
				wp_safe_redirect( admin_url( 'users.php?page=wp-temporary-login-without-password' ) );
				exit();
			}

		}

		/**
		 * Style TLWP checkbox as disabled and grayed-out, and hide auto-updates column in plugins list for temporary login users.
		 *
		 * @since 1.8.4
		 */
		public function disable_tlwp_bulk_action_assets() {

			global $pagenow;
			if ( ! in_array( $pagenow, array( 'plugins.php', 'plugins-network.php' ), true ) ) {
				return;
			}

			if ( ! Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				return;
			}
			?>
			<style type="text/css">
				input[value*="temporary-login-without-password"],
				input[value*="temporary-login"],
				tr[data-slug*="temporary-login"] th.check-column input[type="checkbox"],
				tr[data-plugin*="temporary-login"] th.check-column input[type="checkbox"],
				tr:has(input[value*="temporary-login"]) th.check-column input[type="checkbox"] {
					opacity: 0.4 !important;
					cursor: not-allowed !important;
					pointer-events: none !important;
					filter: grayscale(100%) !important;
				}
				tr[data-slug*="temporary-login"] th.check-column,
				tr[data-plugin*="temporary-login"] th.check-column,
				tr:has(input[value*="temporary-login"]) th.check-column,
				tr[data-slug*="temporary-login"] th.check-column label,
				tr[data-plugin*="temporary-login"] th.check-column label,
				tr:has(input[value*="temporary-login"]) th.check-column label {
					cursor: not-allowed !important;
					pointer-events: none !important;
				}
				tr[data-slug*="temporary-login"] td.column-auto-updates *,
				tr[data-plugin*="temporary-login"] td.column-auto-updates *,
				tr:has(input[value*="temporary-login"]) td.column-auto-updates * {
					display: none !important;
					pointer-events: none !important;
				}
			</style>
			<script type="text/javascript">
				(function($) {
					if (typeof $ === 'undefined') return;
					function lockTLWPCheckbox() {
						var $checkboxes = $('input[value*="temporary-login-without-password"], input[value*="temporary-login"], tr[data-slug*="temporary-login"] th.check-column input, tr[data-plugin*="temporary-login"] th.check-column input');
						$checkboxes.each(function() {
							$(this).prop('checked', false).prop('disabled', true).attr('disabled', 'disabled').removeAttr('checked');
						});
					}
					$(document).ready(lockTLWPCheckbox);
					lockTLWPCheckbox();
					$(window).on('load', lockTLWPCheckbox);
					$(document).on('click change', 'th.check-column :checkbox, #cb-select-all-1, #cb-select-all-2, table', function() {
						setTimeout(lockTLWPCheckbox, 0);
					});
				})(jQuery);
			</script>
			<?php

		}

		/**
		 * Filter TLWP row meta links for temporary login users.
		 *
		 * @param array  $plugin_meta
		 * @param string $plugin_file
		 * @param array  $plugin_data
		 * @param string $status
		 *
		 * @return array
		 * @since 1.8.4
		 */
		public function filter_tlwp_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

			if ( ! Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				return $plugin_meta;
			}

			list( $plugin_base_name, $default_base_name ) = Wp_Temporary_Login_Without_Password_Common::get_plugin_base_names();

			if ( $plugin_file === $plugin_base_name || $plugin_file === $default_base_name ) {
				$restricted_patterns = array(
					'disconnect_icegram',
					'connect_icegram',
				);

				foreach ( $plugin_meta as $key => $meta_link ) {
					foreach ( $restricted_patterns as $pattern ) {
						if ( false !== stripos( $meta_link, $pattern ) ) {
							unset( $plugin_meta[ $key ] );
							break;
						}
					}
				}
			}

			return $plugin_meta;

		}

		/**
		 * Filter TLWP auto update setting HTML for temporary login users.
		 *
		 * @param string $html
		 * @param string $plugin_file
		 * @param array  $plugin_data
		 *
		 * @return string
		 * @since 1.8.4
		 */
		public function filter_tlwp_auto_update_setting_html( $html, $plugin_file, $plugin_data ) {

			if ( ! Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				return $html;
			}

			list( $plugin_base_name, $default_base_name ) = Wp_Temporary_Login_Without_Password_Common::get_plugin_base_names();

			if ( $plugin_file === $plugin_base_name || $plugin_file === $default_base_name ) {
				return '';
			}

			return $html;

		}

		/**
		 * Block disconnect Icegram ajax action for temporary login users.
		 *
		 * @since 1.8.4
		 */
		public function block_tlwp_disconnect_for_temporary_user() {

			if ( Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Permission denied.', 'temporary-login-without-password' ) ) );
			}

		}

		/**
		 * Add settings link
		 *
		 * @param array $links
		 *
		 * @return array
		 * @since 1.5.7
		 *
		 */
		public function plugin_add_settings_link( $links ) {

			if ( Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				return $links;
			}

			$settings_link = '<a href="users.php?page=wp-temporary-login-without-password&tab=settings">' . __( 'Settings' ) . '</a>';
			$links[]       = $settings_link;

			return $links;
		}

		/**
		 * Filter TLWP specific action links at highest priority for temporary login users.
		 *
		 * @param array $links
		 *
		 * @return array
		 * @since 1.8.4
		 */
		public function filter_tlwp_specific_plugin_action_links( $links ) {

			if ( ! Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				return $links;
			}

			// Remove actions by known stable keys.
			$restricted_keys = array( 'deactivate', 'settings', 'edit' );
			foreach ( $restricted_keys as $key ) {
				unset( $links[ $key ] );
			}

			// Remove remaining actions by stable URL/attribute patterns (not display text).
			$restricted_patterns = array(
				'_action=request_data',
				'connect_icegram',
				'disconnect_icegram',
				'tab=settings',
			);

			foreach ( (array) $links as $key => $action_link ) {
				foreach ( $restricted_patterns as $pattern ) {
					if ( false !== stripos( $action_link, $pattern ) ) {
						unset( $links[ $key ] );
						break;
					}
				}
			}

			return array_values( $links );

		}

		/*
		 * Get short time format for admin bar
		 *
		 * @param int $expire_timestamp
		 * @return string
		 * @since 1.0
		 */
		private function get_short_expiry_time( $expire_timestamp ) {

			$expired_text = __( 'Expired', 'temporary-login-without-password' );
    
			if ( empty( $expire_timestamp ) || ! is_numeric( $expire_timestamp ) ) {
				return $expired_text;
			}

			$current_time = Wp_Temporary_Login_Without_Password_Common::get_current_gmt_timestamp();
			$time_diff =  (int) ( $expire_timestamp - $current_time );

			if ( $time_diff <= 0 ) {
				return $expired_text;
			}

			$days = floor( $time_diff / DAY_IN_SECONDS );
			$hours = floor( ( $time_diff % DAY_IN_SECONDS ) / HOUR_IN_SECONDS );
			$minutes = floor( ( $time_diff % HOUR_IN_SECONDS ) / MINUTE_IN_SECONDS );
			$seconds = $time_diff % MINUTE_IN_SECONDS;
 
			$parts = array();

			if ( $days > 0 ) {
				$parts[] = sprintf( '%dd', $days );
			}
			
			if ( $hours > 0 ) {
				$parts[] = sprintf( '%dh', $hours );
			}
			
			if ( $minutes > 0 ) {
				$parts[] = sprintf( '%dm', $minutes );
			}
			
			if ( $seconds > 0 || empty( $parts ) ) {
				$parts[] = sprintf( '%ds', $seconds );
			}

			return implode( ' ', $parts );
		}

		/**
		 * Display admin bar when Temporary user logged in.
		 *
		 * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference.
		 *
		 * @return bool
		 *
		 * @since 1.6.2
		 */
		public function tlwp_show_temporary_access_notice_in_admin_bar( $wp_admin_bar ) {

			$is_valid_temporary_user = Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user();

			if ( $is_valid_temporary_user ) {

				$user_id = get_current_user_id();
				
				$expire = get_user_meta( $user_id, '_wtlwp_expire', true );

				$expire = ! empty( $expire ) && is_numeric( $expire ) ? absint( $expire ) : 0;

				if ( empty( $expire ) ) {
					return true;
				}

				$expiry_text = $this->get_short_expiry_time( $expire );

				// Format the expiry date/time for tooltip
				$expiry_date_formatted = '';
				
				// Use wp_date() for proper timezone conversion (WP 5.3+)
				if ( function_exists( 'wp_date' ) ) {
					$expiry_date_formatted = wp_date( 
						get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), 
						$expire 
					);
				} else {
					// for older WordPress versions - convert GMT timestamp to local time
					$local_timestamp = $expire + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
					$expiry_date_formatted = date_i18n( 
						get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), 
						$local_timestamp 
					);
				}
				  
				// Create tooltip message
				$tooltip_message = ! empty( $expiry_date_formatted ) 
					? sprintf( __( 'Access expires on: %s', 'temporary-login-without-password' ), $expiry_date_formatted )
					: __( 'Temporary Access', 'temporary-login-without-password' );

					
				// lable and timer
        		$expiry_timer_html = '<span class="tlwp-access-wrapper" title="' . esc_attr( $tooltip_message ) . '">' .
					'<span class="tlwp-label-dark">' . __( 'Temporary Access: ', 'temporary-login-without-password' ) . '</span>' . 
					'<span class="tlwp-timer-wrapper" data-expire="' . esc_attr( $expire ) . '">' .
						'<span class="dashicons dashicons-clock tlwp-clock-icon"></span>' . 
						'<span class="tlwp-expiry-time">' . esc_html( $expiry_text ) . '</span>' .
					'</span>' .
				'</span>';

				// Add "Temporary Access" on the RIGHT side
				$wp_admin_bar->add_menu(
					array(
						'id'     => 'temporay-access-notice',
						'href'   => admin_url( 'users.php?page=wp-temporary-login-without-password' ),
						'parent' => 'top-secondary', // This keeps it on the right side
						'title'  => $expiry_timer_html,
						'meta'   => array( 'class' => 'temporay-access-mode-active' ),
					)
				);
			}

			return true;
		}

		/**
		 * Add temporary access bar css
		 *
		 * @since 1.6.2
		 */
		public function temporary_user_adminbar_styles() {
			if ( ! Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				return;
			}

			?>
			<style>
				#wpadminbar .temporay-access-mode-active > .ab-item {
					color: #fff !important;
					background-color: #2c3338 !important;
					padding: 0 !important;
					display: flex !important;
					align-items: center !important;
					line-height: 32px !important;
				}
 
				#wpadminbar .temporay-access-mode-active > .ab-item .tlwp-access-wrapper {
					display: flex !important;
					align-items: center !important;
					width: 100% !important;
				}

				#wpadminbar .temporay-access-mode-active > .ab-item .tlwp-label-dark {
					padding-left: 12px !important;
					display: inline-block !important;
					height: 32px !important;
					line-height: 32px !important;
				}

				#wpadminbar .temporay-access-mode-active > .ab-item .tlwp-timer-wrapper {
					display: inline-flex !important;
					align-items: center !important;
					margin-right: 12px !important;
					color: #ffba00;
				}

				#wpadminbar .temporay-access-mode-active > .ab-item .dashicons.tlwp-clock-icon {
					font-size: 20px !important;
					width: 16px !important;
					height: 16px !important;
					line-height: 18px !important;
					margin: 0 8px !important;
					display: inline-block !important;
					vertical-align: middle !important;
					font-family: dashicons !important;
				}

				#wpadminbar .temporay-access-mode-active > .ab-item .dashicons.tlwp-clock-icon:before {
					font-size: 18px !important; 
				}

				#wpadminbar .temporay-access-mode-active > .ab-item .tlwp-expiry-time {
					font-weight: normal !important;
					display: inline-block !important;
					font-size: 13px !important;
				}

				#wpadminbar .temporay-access-mode-active:hover > .ab-item {
					background-color: #363f47 !important;
					color: #fff !important;
				}
			</style>			 
			<?php
		}
		 
		/**
		 * Add temporary access bar javascript
		 *
		 * @since 1.6.2
		 */
		public function temporary_user_adminbar_script() {
			if ( ! Wp_Temporary_Login_Without_Password_Common::is_current_user_valid_temporary_user() ) {
				return;
			}

			?>
			<script type="text/javascript">
				(function() {

				const DAY = 86400;
				const HOUR = 3600;
				const MINUTE = 60;
				const EXPIRED_COLOR = '#ef4444';
				const WARNING_COLOR = '#ef4444';
				const NORMAL_COLOR = '#ffba00';
				const EXPIRED_TEXT = '<?php echo esc_js( __( 'Expired', 'temporary-login-without-password' ) ); ?>'; 
				var countdownInterval;

				var timerWrapper = document.querySelector('.tlwp-timer-wrapper');
				if (!timerWrapper) return;
				
				var expiryElement = timerWrapper.querySelector('.tlwp-expiry-time');
				if (!expiryElement) return;
				
				var expireTimestamp = parseInt(timerWrapper.getAttribute('data-expire'));
				if (!expireTimestamp) return;
				
				var countdownInterval;
				
				function updateCountdown() {
					var currentTime = Math.floor(Date.now() / 1000);
					var timeDiff = expireTimestamp - currentTime;
					
					if (timeDiff <= 0) {
						expiryElement.textContent = EXPIRED_TEXT;
						timerWrapper.style.color = EXPIRED_COLOR;
						clearInterval(countdownInterval); 
						return;
					}
					
					timerWrapper.style.color = (timeDiff < HOUR) ? WARNING_COLOR : NORMAL_COLOR;
					
					var days = Math.floor(timeDiff / DAY);
					var hours = Math.floor((timeDiff % DAY) / HOUR);
					var minutes = Math.floor((timeDiff % HOUR) / MINUTE);
					var seconds = timeDiff % MINUTE;
 
					var parts = [];
					if (days > 0) parts.push(days + 'd');
					if (hours > 0) parts.push(hours + 'h');
					if (minutes > 0) parts.push(minutes + 'm');
					if (seconds > 0 || parts.length === 0) parts.push(seconds + 's');
					
					expiryElement.textContent = parts.join(' ');
				}

				updateCountdown();
				countdownInterval = setInterval(updateCountdown, 1000);
			})();
			</script>
			<?php
		}

		/**
		 * Remove all unwanted admin notices
		 *
		 * @since 1.6.3
		 */
		public function remove_admin_notices() {
			global $wp_filter;

			if ( ! $this->is_plugin_page() ) {
				return;
			}

			$allow_display_notices = array(
			'show_review_notice',
			'tlwp_display_admin_notices',
			'tlwp_show_feature_survey',
			'in_app_offer',
			'show_tlwp_mailer_promotion_notice',
			);

			$filters = array(
			'admin_notices',
			'user_admin_notices',
			'all_admin_notices'
			);

			foreach ( $filters as $filter ) {

				if ( ! empty( $wp_filter[ $filter ]->callbacks ) && is_array( $wp_filter[ $filter ]->callbacks ) ) {

					foreach ( $wp_filter[ $filter ]->callbacks as $priority => $callbacks ) {

						foreach ( $callbacks as $name => $details ) {

							if ( is_object( $details['function'] ) && $details['function'] instanceof \Closure ) {
								unset( $wp_filter[ $filter ]->callbacks[ $priority ][ $name ] );
								continue;
							}

							if ( ! empty( $details['function'][0] ) && is_object( $details['function'][0] ) && count( $details['function'] ) == 2 ) {
								$notice_callback_name = $details['function'][1];
								if ( ! in_array( $notice_callback_name, $allow_display_notices ) ) {
									unset( $wp_filter[ $filter ]->callbacks[ $priority ][ $name ] );
								}
							}

							if ( ! empty( $details['function'] ) && is_string( $details['function'] ) ) {
								if ( ! in_array( $details['function'], $allow_display_notices ) ) {
									unset( $wp_filter[ $filter ]->callbacks[ $priority ][ $name ] );
								}
							}
						}
					}
				}

			}

		}

		/**
		 * Function to show in app offer - if available.
		 *
		 * @since 1.8.0
		 */
		public function wtlwp_may_be_show_sa_in_app_offer() {
			if ( ! class_exists( 'SA_TLWP_In_App_Offer' ) && file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/sa-includes/class-sa-tlwp-in-app-offer.php' ) ) {
				include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/sa-includes/class-sa-tlwp-in-app-offer.php';

				$get_page = ( ! empty( $_GET['page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore

				$args     = array(
				'file'           => plugin_dir_path( dirname( __FILE__ ) ) . 'includes/sa-includes/',
				'prefix'         => 'wtlwp',              // prefix/slug of your plugin.
				'option_name'    => 'ig_wtlwp_offer_bfcm_2024',
				'campaign'       => 'ig_offer_bfcm_2024',
				'start'          => '2024-11-26 12:30:00',
				'end'            => '2024-12-05 12:30:00',
				'is_plugin_page' => ( ! empty( $get_page ) && 'wp-temporary-login-without-password' === $get_page ) ? true : false,   // page where you want to show offer, do not send this if no plugin page is there and want to show offer on Products page.
				);
				$sa_offer = SA_TLWP_In_App_Offer::get_instance( $args );
			}
		}

		/**
		 * Update plugin notice
		 *
		 * @param $data
		 * @param $response
		 *
		 * @since 1.6.12
		 */
		public function in_plugin_update_message( $data, $response ) {

			if ( isset( $data['upgrade_notice'] ) ) {
				printf(
				'<div class="update-message">%s</div>',
				wpautop( esc_html( $data['upgrade_notice'] ) )
				);
			}
		}

		/**
		 * Update admin footer text
		 *
		 * @param $footer_text
		 *
		 * @return string
		 *
		 * @since 1.6.13
		 */
		public function update_admin_footer_text( $footer_text ) {
			if ( $this->is_plugin_page() ) {
				/* translators: %1$s: link to plugin on WordPress %2$s: Plugin version %3$s: StoreApps link */
				$footer_text = sprintf( __( '<span id="footer-thankyou">Thank you for creating with <a href="%1$s" target="_blank">WordPress</a> | Temporary Login Without Password <strong>%2$s</strong>. Developed by team <a href="%3$s" target="_blank">StoreApps</a></span>', 'temporary-login-without-password' ), 'https://wordpress.org/plugins/temporary-login-without-password/', WTLWP_PLUGIN_VERSION, 'https://www.storeapps.org/' );
			}

			return $footer_text;
		}

		public function handle_enable_one_click_login() {

            check_ajax_referer( 'wtlwp_nonce', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error(
					array( 'message' => esc_html__( 'Permission denied.', 'temporary-login-without-password' ) )
				);
			}

			$user_id = isset( $_POST['user_id'] ) ? absint( wp_unslash( $_POST['user_id'] ) ) : 0;
			$action  = isset( $_POST['enable'] ) ? sanitize_text_field( wp_unslash( $_POST['enable'] ) ) : '';

			if ( 0 === $user_id || '' === $action ) {
				wp_send_json_error( array( 'message' =>  esc_html__( 'Missing parameters.', 'temporary-login-without-password' ) ) );
			}

			$result = Wp_Temporary_Login_Without_Password_Common::manage_login( $user_id, $action );

			if ( $result ) {
				 delete_transient( 'wtlwp_one_click_user_active' );
                 delete_transient( 'wtlwp_one_click_user_id' );
				wp_send_json_success(
					array( 'message' => esc_html__( 'Action completed successfully.', 'temporary-login-without-password' ) )
				);
					
			}
				
			wp_send_json_error(
				array( 'message' => esc_html__( 'Failed to complete action.', 'temporary-login-without-password' ) )
			);
			
		}

		public function save_upsell_flow() {
			check_ajax_referer( 'wtlwp_nonce', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error(
					array( 'message' => esc_html__( 'Permission denied.', 'temporary-login-without-password' ) )
				);
			}

			$flow = isset( $_POST['flow'] ) ? sanitize_text_field( wp_unslash( $_POST['flow'] ) ) : '';

			if ( 'max-login-field' === $flow ) {
				update_option( 'wtlwp_upsell_flow', $flow, false );
				wp_send_json_success();
			}

			wp_send_json_error(
				array( 'message' => esc_html__( 'Failed to complete action.', 'temporary-login-without-password' ) )
			);
		}

	}

}
