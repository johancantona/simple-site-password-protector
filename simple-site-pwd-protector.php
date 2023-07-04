<?php
/**
 * Plugin Name: Simple Site Password Protector
 * Version:1.0.1
 * Author: Johan Carlsson | Balowie
 * Description: Protect your site with a password
 * URL: https://www.balowie.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


if (!defined('ABSPATH')) {
    exit();
}

require plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://balowie.se/balowie-produkter/simple-site-pwd-protector/details.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'simple-site-pwd-protector'
);


global $status;
$status = get_option('simple_site_pwd_protector_status') ? get_option('simple_site_pwd_protector_status') : false;

require_once plugin_dir_path(__FILE__) . 'src/admin.php';

if($status){
    require_once plugin_dir_path(__FILE__) . 'src/init.php';
}
