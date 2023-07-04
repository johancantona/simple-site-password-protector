<?php
/**
 * Plugin Name: Simple Site Password Protector
 * Version:1.0.1
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
