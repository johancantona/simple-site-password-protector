<?php
/**
 * Plugin Name: Simple Site Password Protector
 * Version:1.0
 * Author: Johan Carlsson | Balowie
 * Description: Protect your site with a password
 * URL: https://www.balowie.com
 */


if (!defined('ABSPATH')) {
    exit();
}

global $status;
$status = get_option('simple_site_pwd_protector_status') ? get_option('simple_site_pwd_protector_status') : false;

require_once plugin_dir_path(__FILE__) . 'src/admin.php';

if($status){
    require_once plugin_dir_path(__FILE__) . 'src/init.php';
}
