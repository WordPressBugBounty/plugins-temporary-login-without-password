<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// BFCM 2026 Campaign
if ( ( 'yes' !== get_option( 'ig_offer_bfcm_2026_wtlwp' ) ) && Wp_Temporary_Login_Without_Password_Admin::is_offer_period( 'bfcm' ) && ! Wp_Temporary_Login_Without_Password::is_pro() ) {

	$banner_img_url = WTLWP_PLUGIN_URL . 'admin/assets/images/tlwp-bfcm-2026.png';

	$offer_dismiss_url = add_query_arg(
		array(
			'wtlwp_dismiss_admin_notice' => '1',
			'wtlwp_option_name'          => 'ig_offer_bfcm_2026',
			'wtlwp_nonce'                => wp_create_nonce( 'wtlwp_dismiss_notice' ),
		)
	);
	?>
	<style type="text/css">
		.ig_sale_offer {
			width: 65%;
			margin: 0 auto;
			text-align: center;
			padding-top: 1.2em;
		}
		.ig_sale_offer img {
			width: 100%;
		}
	</style>
	<div class="ig_sale_offer">
		<a target="_blank" href="<?php echo esc_url( $offer_dismiss_url ); ?>">
			<img src="<?php echo esc_url( $banner_img_url ); ?>"/>
		</a>
	</div>
<?php } ?>