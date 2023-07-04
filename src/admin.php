<?php

global $status;

// add to the menu thats already there named tools
add_action('admin_menu', 'sspp_admin_menu');

function sspp_admin_menu() {
    add_submenu_page(
        'tools.php',
        'SPP Protector',
        'SSP Protector',
        'manage_options',
        'simple-site-pwd-protector',
        'sspp_admin_page'
    );
}


function sspp_admin_page() {

    echo '<h1>Simple Site Password Protector</h1>';

    if (isset($_POST['sspp_save_settings_nonce'])) {
        sspp_handle_form_submission();
    }

    //general settings
    $password_hint = get_option('simple_site_pwd_protector_password_hint') ? get_option('simple_site_pwd_protector_password_hint') : false;
    $status = get_option('simple_site_pwd_protector_status') ? get_option('simple_site_pwd_protector_status') : false;
    $has_password = get_option('simple_site_pwd_protector_password') ? 'Update password' : 'Set password';
    $status_checked = $status ? 'checked' : '';

    //visual settings
    $background_image = get_option('simple_site_pwd_protector_background_image') ? get_option('simple_site_pwd_protector_background_image') : 'https://via.placeholder.com/100x100';
    $background_color = get_option('simple_site_pwd_protector_background_color') ? get_option('simple_site_pwd_protector_background_color') : false;
    $input_theme = get_option('simple_site_pwd_protector_input_theme') ? get_option('simple_site_pwd_protector_input_theme') : false;
    $dark = $input_theme === 'dark' ? 'selected' : '';
    $light = $input_theme === 'light' ? 'selected' : '';

    $form = '<form method="POST" id="sspp_admin_form">';
    $form .= wp_nonce_field('sspp_save_settings_nonce', 'sspp_save_settings_nonce', false, false);
    $form .= '<h2>General settings</h2>';
    $form .= '<p style="display:flex; align-items:center;"><input style="margin-right:10px;" type="checkbox" ' . $status_checked . ' name="simple_site_pwd_protector_status" />Activate SSP Protection</p>';
    $form .= '<div class="form-wrapper">';
    $form .= '<p><label>Password</label><br><input type="password" name="simple_site_pwd_protector_password" placeholder="' . $has_password . '" /></p>';
    $form .= '<p><label>Password hint</label><br><input type="text" name="simple_site_pwd_protector_password_hint" value="' . $password_hint . '" placeholder="Set a password hint" /></p>';
    $form .= '</div>';
    $form .= '<hr>';
    $form .= '<h2>Visual settings</h2>';
    $form .= '<div class="image_uploader">';
    $form .= '<input hidden type="text" value="' . esc_url($background_image) . '" name="simple_site_pwd_protector_background_image" id="sspp_background_image" readonly />';
    $form .= '<img id="sspp_background_image_preview" src="' . esc_url($background_image) . '" />';
    $form .= '<div class="btn-group">';
    $form .= '<button class="button" id="sspp_upload_image_button">Select Image</button>';
    $form .= '<button class="button" id="sspp_remove_image_button">Remove Image</button>';
    $form .= '</div></div>';
    $form .= '<div class="form-wrapper">';
    $form .= '<p><label>Background color</label><br><input type="text" name="simple_site_pwd_protector_background_color" value="' . $background_color . '" placeholder="#000000" /></p>';
    $form .= '<p><label>Text color</label><br><select name="simple_site_pwd_protector_input_theme">';
    $form .= '<option ' . $light . ' value="light">Light</option>';
    $form .= '<option ' . $dark . ' value="dark">Dark</option>';
    $form .= '</select></p>';
    $form .= '</div>';
    $form .= '<div class="form-wrapper">';
    $form .= '<p><label>Password placeholder text</label><br><input type="text" name="simple_site_pwd_protector_password_placeholder" value="' . get_option('simple_site_pwd_protector_password_placeholder') . '" placeholder="Type the password" /></p>';
    $form .= '<p><label>Failed password text</label><br><input type="text" name="simple_site_pwd_protector_failed_password_text" value="' . get_option('simple_site_pwd_protector_failed_password_text') . '" placeholder="Incorrect password" /></p>';
    $form .= '</div>';
    $form .= '<div class="form-wrapper">';
    $form .= '<p><label>Submit button text</label><br><input type="text" name="simple_site_pwd_protector_submit_button_text" value="' . get_option('simple_site_pwd_protector_submit_button_text') . '" placeholder="Submit" /></p>';
    $form .= '</div>';
    $form .= '<p><input id="sspp_submit" class="button button-primary button-large" type="submit" value="Save settings" /></p>';
    $form .= '</form>';

    echo $form;
}

function sspp_handle_form_submission() {
    
    $message = '';

    if (!isset($_POST['sspp_save_settings_nonce']) || !wp_verify_nonce($_POST['sspp_save_settings_nonce'], 'sspp_save_settings_nonce')) {
        wp_die('Invalid nonce.');
    }

    if(isset($_POST['simple_site_pwd_protector_status'])){

        update_option('simple_site_pwd_protector_status', true);

        if(isset($_POST['simple_site_pwd_protector_password'])){
            //check if empty
            if(!empty($_POST['simple_site_pwd_protector_password'])){
                // has to be minimum 6 characters
                $new_password = $_POST['simple_site_pwd_protector_password'] ? $_POST['simple_site_pwd_protector_password'] : false;
                if($new_password && strlen($new_password) < 6 || empty($_POST['simple_site_pwd_protector_password'])){
                    $message .= '<p style="color:red;">Password has to be minimum 6 characters. Try again!</p>';
                }else{
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    update_option('simple_site_pwd_protector_password', $hashed_password);
                    update_option('simple_site_pwd_protector_id', random_int(100000, 999999));
                    $message .= '<p style="color:green;">Your password has been updated. <a style="color:green;" href="#" id="copy_password">Click here to copy password</a></p>';
                    $message .= '<script>document.getElementById("copy_password").addEventListener("click", function(){navigator.clipboard.writeText("' . $new_password . '");});</script>';
                }
            }
        }

        if(isset($_POST['simple_site_pwd_protector_password_hint'])){
            $new_hint = $_POST['simple_site_pwd_protector_password_hint'] ? $_POST['simple_site_pwd_protector_password_hint'] : false;
            $old_hint = get_option('simple_site_pwd_protector_password_hint') ? get_option('simple_site_pwd_protector_password_hint') : false;
            if( $new_hint === $old_hint){
                // do nothing
            }else{
                update_option('simple_site_pwd_protector_password_hint', $new_hint);
            }
        }
    } else{
        $old_status = get_option('simple_site_pwd_protector_status') ? true : false;
        update_option('simple_site_pwd_protector_status', false);
        update_option('simple_site_pwd_protector_id', false);
        update_option('simple_site_pwd_protector_password', false);
        update_option('simple_site_pwd_protector_password_hint', false);
        if($old_status){
            $message .= '<p style="color:red;">SSP Protection deactivated and all settings has been reset</p>';
        }
    }

    // Visual settings
    $new_background_image = $_POST['simple_site_pwd_protector_background_image'] ? $_POST['simple_site_pwd_protector_background_image'] : false;
    update_option('simple_site_pwd_protector_background_image', $new_background_image);

    $new_background_color = $_POST['simple_site_pwd_protector_background_color'] ? $_POST['simple_site_pwd_protector_background_color'] : false;
    update_option('simple_site_pwd_protector_background_color', $new_background_color);

    $new_input_theme = $_POST['simple_site_pwd_protector_input_theme'] ? $_POST['simple_site_pwd_protector_input_theme'] : false;
    update_option('simple_site_pwd_protector_input_theme', $new_input_theme);

    $new_password_placeholder = $_POST['simple_site_pwd_protector_password_placeholder'] ? $_POST['simple_site_pwd_protector_password_placeholder'] : false;
    update_option('simple_site_pwd_protector_password_placeholder', $new_password_placeholder);
    
    $new_submit_button_text = $_POST['simple_site_pwd_protector_submit_button_text'] ? $_POST['simple_site_pwd_protector_submit_button_text'] : false;
    update_option('simple_site_pwd_protector_submit_button_text', $new_submit_button_text);

    $new_failed_password_text = $_POST['simple_site_pwd_protector_failed_password_text'] ? $_POST['simple_site_pwd_protector_failed_password_text'] : false;
    update_option('simple_site_pwd_protector_failed_password_text', $new_failed_password_text);

    if($message){
        echo '<div>' . $message . '</div>';
    }
}


add_action('admin_enqueue_scripts', 'sspp_enqueue_scripts');
function sspp_enqueue_scripts($hook) {
    if ($hook === 'tools_page_simple-site-pwd-protector') {
        wp_enqueue_media();
        $plugin_data = get_plugin_data( __FILE__ );
        $plugin_version = $plugin_data['Version'];
        wp_enqueue_script('sspp-media-upload', plugin_dir_url(__FILE__) . '../dist/js/sspp-media-upload.js', array('jquery'), $plugin_version, true);
        wp_enqueue_style('sspp-admin', plugin_dir_url(__FILE__) . '../dist/styles/sspp-admin.css', array(), $plugin_version, 'all');
    }
}