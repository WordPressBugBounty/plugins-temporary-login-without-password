<?php
/**
 * IP Restriction Page Template
 *
 * @package Temporary Login Without Password
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo esc_html__( 'Access Restricted', 'temporary-login-without-password' ); ?></title>
    <?php 
    $wtlwp_css_url = WTLWP_PLUGIN_URL . 'public/css/style.css';
    ?>
    <link rel="stylesheet" href="<?php echo esc_url( $wtlwp_css_url ); ?>?ver=<?php echo esc_attr( WTLWP_PLUGIN_VERSION ); ?>">
</head>
<body class="tlwp-ip-restriction">
    <div class="tlwp-restriction-container">
        <div class="tlwp-restriction-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
            </svg>
        </div>
        
        <h1><?php echo esc_html__( 'Access Restricted', 'temporary-login-without-password' ); ?></h1>
        
        <p class="tlwp-restriction-message">
            <?php echo esc_html__( 'Your IP address is not authorized to access this login.', 'temporary-login-without-password' ); ?>
        </p>
        
        <div class="tlwp-ip-box">
            <div class="tlwp-ip-label"><?php echo esc_html__( 'Your Current IP Address', 'temporary-login-without-password' ); ?></div>
            <div class="tlwp-ip-wrapper">
                <div class="tlwp-ip-address" id="client-ip"><?php echo esc_html( $client_ip ); ?></div>
                <button type="button" class="tlwp-copy-btn" id="copy-ip-btn" title="<?php echo esc_attr__( 'Copy IP Address', 'temporary-login-without-password' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                        <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <p class="tlwp-help-text">
            <?php echo esc_html__( 'If you require access, please provide the above IP address to the site administrator so they can authorize access from your IP address.', 'temporary-login-without-password' ); ?>
        </p>
        
        <div class="tlwp-restriction-footer">
            <?php echo esc_html__( 'Temporary Login Without Password', 'temporary-login-without-password' ); ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.getElementById('copy-ip-btn');
            const clientIp = document.getElementById('client-ip');
            
            if (copyBtn && clientIp) {
                copyBtn.addEventListener('click', function() {
                    const ipText = clientIp.textContent;
                    
                    // Copy to clipboard
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(ipText).then(function() {
                            showCopiedFeedback();
                        }).catch(function(err) {
                            fallbackCopy(ipText);
                        });
                    } else {
                        fallbackCopy(ipText);
                    }
                });
            }
            
            function fallbackCopy(text) {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    showCopiedFeedback();
                } catch (err) {
                    console.error('Failed to copy:', err);
                }
                document.body.removeChild(textArea);
            }
            
            function showCopiedFeedback() {
                const svg = copyBtn.querySelector('svg');
                const originalSVG = svg.outerHTML;
                
                // Change to checkmark icon
                svg.outerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>';
                copyBtn.classList.add('copied');
                
                setTimeout(function() {
                    copyBtn.querySelector('svg').outerHTML = originalSVG;
                    copyBtn.classList.remove('copied');
                }, 2000);
            }
        });
    </script>
</body>
</html>