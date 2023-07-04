<?php
/**
 * Plugin Name: Simple Site Password Protector
 * Version:1.0.3
 * Author: Johan Carlsson | Balowie
 * Description: Protect your site with a password
 * URL: https://www.balowie.com
 */


if (!defined('ABSPATH')) {
    exit();
}

require plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://balowie.se/balowie-produkter/simple-site-password-protector/details.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'simple-site-password-protector'
);


global $status;
$status = get_option('simple_site_password_protector_status') ? get_option('simple_site_password_protector_status') : false;

require_once plugin_dir_path(__FILE__) . 'src/admin.php';

if($status){
    require_once plugin_dir_path(__FILE__) . 'src/init.php';
}

// Add link to plugin
function add_plugin_link( $plugin_actions, $plugin_file ) {
    $new_actions = array();
    if ( basename( plugin_dir_path( __FILE__ ) ) . '/simple-site-password-protector.php' === $plugin_file ) {
        $new_actions['cl_settings'] = sprintf( __( '<a href="%s">Settings</a>', 'comment-limiter' ), esc_url( admin_url( 'tools.php?page=simple-site-password-protector' ) ) );
    }
    return array_merge( $new_actions, $plugin_actions );
}
add_filter( 'plugin_action_links', 'add_plugin_link', 10, 2 );

// On delete, clean up all options
register_uninstall_hook(__FILE__, 'my_plugin_uninstall');
function my_plugin_uninstall() {
    delete_option('simple_site_password_protector_status');
    delete_option('simple_site_password_protector_id');
    delete_option('simple_site_password_protector_password');
    delete_option('simple_site_password_protector_password_hint');
    delete_option('simple_site_password_protector_background_image');
    delete_option('simple_site_password_protector_background_color');
    delete_option('simple_site_password_protector_input_theme');
    delete_option('simple_site_password_protector_password_placeholder');
    delete_option('simple_site_password_protector_submit_button_text');
    delete_option('simple_site_password_protector_failed_password_text');
}
