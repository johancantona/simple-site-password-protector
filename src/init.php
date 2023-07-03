<?php


add_action('template_redirect', 'sspp_redirect');


function sspp_redirect() {
    $is_logged_in = is_user_logged_in();
    $protector_key = isset($_COOKIE['pwd_protector_key']) ? $_COOKIE['pwd_protector_key'] : false;
    $protector_id = get_option('simple_site_pwd_protector_id') ? get_option('simple_site_pwd_protector_id') : false;
    $is_not_admin_and_logged_in = !current_user_can('administrator') && $is_logged_in;
    //check that the hashed password is the same as the one in the db
    if($protector_key !== $protector_id || $is_not_admin_and_logged_in){
        if (!is_front_page()) {
            wp_redirect(home_url());
            exit;
        } else {
            sspp_show_password_form();
        }
    }
}

function sspp_show_password_form() {
    include plugin_dir_path(__FILE__) . '../src/form.php';
    exit;
}