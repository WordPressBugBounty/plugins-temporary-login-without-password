<?php

global $tlwp_tracker, $tlwp_feedback;

$es_install_url            = admin_url( 'plugin-install.php?s=email+subscribers&tab=search&type=term' );
$om_install_url            = admin_url( 'plugin-install.php?s=offermative&tab=search&type=term' );
$ig_install_url            = admin_url( 'plugin-install.php?s=icegram&tab=search&type=term' );
$rainmaker_install_url     = admin_url( 'plugin-install.php?s=rainmaker&tab=search&type=term' );
$smart_manager_install_url = admin_url( 'plugin-install.php?s=smart+manager&tab=search&type=term' );
$duplicate_install_url     = admin_url( 'plugin-install.php?s=icegram&tab=search&type=author' );
$ig_mailer_install_url     = admin_url( 'plugin-install.php?s=icegram%2520mailer&tab=search&type=term' );
$ig_coockie_manager_install_url     = admin_url( 'plugin-install.php?s=Icegram%2520Cookie%2520Manager&tab=search&type=term' );
$ig_user_switching_install_url		= admin_url( 'plugin-install.php?s=switch%2520user%2520login%2520by%2520icegram&tab=search&type=term' );
$deactivate_link           = admin_url( 'plugins.php' );
$icegram_plugin            = 'icegram/icegram.php';
$rainmaker_plugin          = 'icegram-rainmaker/icegram-rainmaker.php';
$smart_manager_plugin      = 'smart-manager-for-wp-e-commerce/smart-manager';
$temporary_login           = admin_url( 'plugin-install.php?s=temporary+login+without+password&tab=search&type=term' );
$active_plugins            = $tlwp_tracker::get_active_plugins();
$inactive_plugins          = $tlwp_tracker::get_inactive_plugins();
$all_plugins               = $tlwp_tracker::get_plugins();

$storeapps_url = Wp_Temporary_Login_Without_Password_Common::get_utm_tracking_url();

$args = array(
  'url' => 'https://icegram.com',
  'utm_campaign' => 'ig_upsell'
);

$ig_url = Wp_Temporary_Login_Without_Password_Common::get_utm_tracking_url( $args );

$plugins = array(

	array(
		'title'       => __( 'Email Subscribers', 'temporary-login-without-password' ),
		'logo'        => 'https://ps.w.org/email-subscribers/assets/icon-128x128.png',
		'desc'        => __( 'Simple and Effective Email Marketing WordPress Plugin. Email Subscribers is a complete newsletter plugin that lets you collect leads, send automated new blog post notification emails, create & send broadcasts', 'temporary-login-without-password' ),
		'name'        => 'email-subscribers/email-subscribers.php',
		'install_url' => $es_install_url,
		'plugin_url'  => 'https://wordpress.org/plugins/email-subscribers/',
	),

	array(
		'title'       => __( 'Icegram mailer', 'temporary-login-without-password' ),
		'logo'        => 'https://ps.w.org/icegram-mailer/assets/icon-256x256.png',
		'desc'        => __( 'Use our built-in service, Mailer, for stress-free email delivery. No SMTP, no tech setup required. Send emails that actually reach inboxes.', 'temporary-login-without-password' ),
		'name'        => 'icegram-mailer/icegram-mailer.php',
		'install_url' => $ig_mailer_install_url,
		'plugin_url'  => 'https://wordpress.org/plugins/icegram-mailer/',
	),

	array(
		'title'       => __( 'Icegram Engage', 'temporary-login-without-password' ),
		'logo'        => 'https://ps.w.org/icegram/assets/icon-128x128.png',
		'desc'        => __( 'The best WP popup plugin that creates a popup. Customize popup, target popups to show offers, email signups, social buttons, etc and increase conversions on your website.', 'temporary-login-without-password' ),
		'name'        => 'icegram/icegram.php',
		'install_url' => $ig_install_url,
		'plugin_url'  => 'https://wordpress.org/plugins/icegram/',
	),

	array(
		'title'       => __( 'Icegram Collect', 'temporary-login-without-password' ),
		'logo'        => 'https://ps.w.org/icegram-rainmaker/assets/icon-128x128.png',
		'desc'        => __( 'Get readymade contact forms, email subscription forms and custom forms for your website. Choose from beautiful templates and get started within seconds', 'temporary-login-without-password' ),
		'name'        => 'icegram-rainmaker/icegram-rainmaker.php',
		'install_url' => $rainmaker_install_url,
		'plugin_url'  => 'https://wordpress.org/plugins/icegram-rainmaker/',
	),

	array(
		'title'       => __( 'Duplicate Pages and Posts', 'temporary-login-without-password' ),
		'logo'        => 'https://ps.w.org/duplicate-post-page-copy-clone-wp/assets/icon-256X256.png',
		'desc'        => __( 'A Duplicate Pages and Posts Plugin is a tool for WordPress that enables users to easily create duplicate versions of existing posts, pages, or custom post types with just a click.', 'temporary-login-without-password' ),
		'name'        => 'duplicate-post-page-copy-clone-wp/duplicate-post-page-copy-clone-wp.php',
		'install_url' => $duplicate_install_url,
		'plugin_url'  => 'https://wordpress.org/plugins/duplicate-post-page-copy-clone-wp/',
	),
	
	array(
		'title'       => __( 'Icegram Cookie Manager', 'temporary-login-without-password' ),
		'logo'        => 'https://s.w.org/plugins/geopattern-icon/switch-user-login-by-icegram.svg',
		'desc'        => __( 'Show a clean cookie consent bar on your site. Stay GDPR compliant, control tracking scripts, and give visitors full transparency without slowing down your site.', 'temporary-login-without-password' ),
		'name'        => 'icegram-cookie-manager/icegram-cookie-manager.php',
		'install_url' => $ig_coockie_manager_install_url,
		'plugin_url'  => 'https://wordpress.org/plugins/icegram-cookie-manager/',
	),

	array(
		'title'       => __( 'Switch User Login By Icegram', 'temporary-login-without-password' ),
		'logo'        => 'https://s.w.org/plugins/geopattern-icon/switch-user-login-by-icegram.svg',
		'desc'        => __( 'Quickly switch between WordPress users without logging out. Simplify admin tasks, test user roles, and manage accounts effortlessly with one click.', 'temporary-login-without-password' ),
		'name'        => 'switch-user-login-by-icegram/switch-user-login-by-icegram.php',
		'install_url' => $ig_user_switching_install_url,
		'plugin_url'  => 'https://wordpress.org/plugins/switch-user-login-by-icegram/',
	),

	array(
		'title'       => __( 'Smart Manager For WooCommerce', 'temporary-login-without-password' ),
		'logo'        => 'https://ps.w.org/smart-manager-for-wp-e-commerce/assets/icon-128x128.png',
		'desc'        => __( 'The #1 and a powerful tool to manage stock, inventory from a single place. Super quick and super easy', 'temporary-login-without-password' ),
		'name'        => 'smart-manager-for-wp-e-commerce/smart-manager.php',
		'install_url' => $smart_manager_install_url,
		'plugin_url'  => 'https://wordpress.org/plugins/smart-manager-for-wp-e-commerce/',
	),

	array(
		'title'       => __( 'Offermative', 'temporary-login-without-password' ),
		'logo'        => 'https://ps.w.org/offermative-discount-pricing-related-products-upsell-funnels-for-woocommerce/assets/icon-128x128.png',
		'desc'        => __( 'Offermative: dynamic discount pricing, related product recommendations, upsells and funnels for WooCommerce', 'temporary-login-without-password' ),
		'name'        => 'Offermative: dynamic discount pricing, related product recommendations, upsells and funnels for WooCommerce/offermative.php',
		'install_url' => $om_install_url,
		'plugin_url'  => 'https://wordpress.org/plugins/offermative-discount-pricing-related-products-upsell-funnels-for-woocommerce/',
	),

	array(
		'title'       => __( 'Smart Coupons', 'temporary-login-without-password' ),
		'logo'        => 'https://woocommerce.com/wp-content/uploads/2012/08/wc-icon-smart-coupons.png',
		'desc'        => __( 'Create and send gift cards, bulk generate coupons, restrict coupons based on location, payment methods, auto-apply coupons using URLs, import-export and a lot more. The official WooCommerce coupons extension.', 'temporary-login-without-password' ),
		'name'        => 'woocommerce-smart-coupons/woocommerce-smart-coupons.php',
		'install_url' => 'https://woocommerce.com/products/smart-coupons/',
		'plugin_url'  => 'https://woocommerce.com/products/smart-coupons/',
		'is_premium'  => true
	),
	array(
		'title'       => __( 'Affiliate for WooCommerce', 'temporary-login-without-password' ),
		'logo'        => 'https://woocommerce.com/wp-content/uploads/2020/07/wc-icon-affiliate.png',
		'desc'        => __( 'Set-up your own affiliate program easily. Manage your affiliates from a single dashboard, create marketing campaigns, make payout via PayPal, set up commission plans and do a lot more.', 'temporary-login-without-password' ),
		'name'        => 'affiliate-for-woocommerce/affiliate-for-woocommerce.php',
		'install_url' => 'https://woocommerce.com/products/affiliate-for-woocommerce/',
		'plugin_url'  => 'https://woocommerce.com/products/affiliate-for-woocommerce/',
		'is_premium'  => true
	),

	array(
		'title'       => __( 'Smart Offers', 'temporary-login-without-password' ),
		'logo'        => 'https://www.storeapps.org/wp-content/uploads/2013/01/sa-icon-smart-offers.png',
		'desc'        => __( 'Upsells, one click upsells, cross-sells, one time offers, giveaway, order bump, BOGO etc. Create and run unlimited offers in the sales funnel based on powerful targeting rules. Your 24*7 money-minting machine.', 'temporary-login-without-password' ),
		'name'        => 'smart-offers/smart-offers.php',
		'install_url' => 'https://www.storeapps.org/product/smart-offers/?utm_source=tlwp-in-app-marketplace&utm_medium=button&utm_campaign=sa-plugins-promotion',
		'plugin_url'  => 'https://www.storeapps.org/product/smart-offers/?utm_source=tlwp-in-app-marketplace&utm_medium=button&utm_campaign=sa-plugins-promotion',
		'is_premium'  => true
	),


);

?>

<div class="container flex flex-wrap w-full mt-4 mb-7">
	<div class="block mt-1 text-center">
		<h3 class="text-2xl font-bold leading-9 text-gray-700 sm:truncate mb-3 text-center"><?php printf(esc_html__( 'Other awesome plugins from same author (%1$sStoreApps%2$s & %3$sIcegram%2$s)', 'temporary-login-without-password' ),'<a href="' . esc_url( $storeapps_url ) . '" target="_blank">','</a>','<a href="' . esc_url( $ig_url ) . '" target="_blank">'); ?></h3>
	</div>
	<div class="grid w-full grid-cols-3 ">
		<?php foreach ( $plugins as $ig_plugin ) { ?>
			<div class="flex flex-col mb-4 mr-3 bg-white rounded-lg shadow">
				<div class="flex h-48">
					<div class="flex pl-1">
						<div class="flex w-1/4 rounded">
							<div class="flex flex-col w-full h-6">
								<div>
									<img class="mx-auto my-4 border-0 h-15" src="<?php echo esc_url( $ig_plugin['logo'] ); ?>" alt="">
								</div>
							</div>
						</div>
						<div class="flex w-3/4 pt-2">
							<div class="flex flex-col">
								<div class="flex w-full">
									<a href="<?php echo esc_url( $ig_plugin['plugin_url'] ); ?>" target="_blank"><h3 class="pb-2 pl-2 mt-2 text-lg font-medium text-indigo-600"><?php echo esc_html( $ig_plugin['title'] ); ?></h3></a>
								</div>
								<div class="flex w-full pl-2 leading-normal xl:pb-4 lg:pb-2 md:pb-2">
									<h4 class="pt-1 pr-4 text-sm text-gray-700"><?php echo esc_html( $ig_plugin['desc'] ); ?></h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="flex flex-row mb-0 border-t">
					<div class="flex w-2/3 px-3 py-5 text-sm"><?php echo esc_html__( 'Status', 'temporary-login-without-password' ); ?>:
						<?php if ( in_array( $ig_plugin['name'], $active_plugins ) ) { ?>
							<span class="font-bold text-green-600">&nbsp;<?php echo esc_html__( 'Active', 'temporary-login-without-password' ); ?></span>
						<?php } elseif ( in_array( $ig_plugin['name'], $inactive_plugins ) ) { ?>
							<span class="font-bold text-red-600">&nbsp;<?php echo esc_html__( 'Inactive', 'temporary-login-without-password' ); ?></span>
						<?php } else { ?>
							<span class="font-bold text-orange-500">&nbsp;<?php echo esc_html__( 'Not Installed', 'temporary-login-without-password' ); ?></span>
						<?php } ?>
					</div>
					<div class="flex justify-center w-1/3 py-3 md:pr-4">
		  <span class="rounded-md shadow-sm">
				<?php if ( ! in_array( $ig_plugin['name'], $active_plugins ) ) { ?>
			  <a href="<?php echo esc_url( $ig_plugin['install_url'] ); ?>" target="_blank">
					<?php
				}

				if ( ! in_array( $ig_plugin['name'], $all_plugins ) ) {
					?>
						<button type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-white bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:shadow-outline-blue">
					<?php 
					if ( isset( $ig_plugin['is_premium'] ) && true === $ig_plugin['is_premium'] ) {
						echo esc_html__( 'Buy Now', 'temporary-login-without-password' );
					} else {
						echo esc_html__( 'Install', 'temporary-login-without-password' );
					} 
					?>
						 </button>
					<?php } elseif ( in_array( $ig_plugin['name'], $inactive_plugins ) ) { ?>
						<button type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:shadow-outline-blue">
					<?php echo esc_html__( 'Activate', 'temporary-login-without-password' ); ?> </button>
					<?php } ?>
			  </a>
			</span>
					</div>
				</div>
			</div>
		<?php } ?>

	</div>
</div>

