<head>
    <title><?php echo get_bloginfo('name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="<?php echo plugins_url('simple-site-password-protector/dist/styles/sspp-form-frontend.css'); ?>" type="text/css" media="all" />
</head>

<?php

    if (isset($_POST['simple_site_pwd_protector_password'])) {
        $password = $_POST['simple_site_pwd_protector_password'];
        $correct_password = get_option('simple_site_pwd_protector_password') ? get_option('simple_site_pwd_protector_password') : false;
        $protector_id = get_option('simple_site_pwd_protector_id') ? get_option('simple_site_pwd_protector_id') : false;
        $validation = password_verify($password, $correct_password);
        if ($validation && $protector_id) {
            setcookie('pwd_protector_key', $protector_id, time() + (86400 * 30), "/");
            wp_redirect(home_url());
            exit;
        } else {
            $url = $_SERVER['REQUEST_URI'];
            $url = strtok($url, '?');
            wp_redirect($url . '?incorrect_password=true');
        }
    }

    $bg_image = get_option('simple_site_pwd_protector_background_image') ? get_option('simple_site_pwd_protector_background_image') : false;
    $bg_image = $bg_image !== 'https://via.placeholder.com/100x100' ? $bg_image : false;
    $bg_color = get_option('simple_site_pwd_protector_background_color') ? get_option('simple_site_pwd_protector_background_color') : '#000';
    $input_theme = get_option('simple_site_pwd_protector_input_theme') ? get_option('simple_site_pwd_protector_input_theme') : 'light';
    $password_placeholder = get_option('simple_site_pwd_protector_password_placeholder') ? get_option('simple_site_pwd_protector_password_placeholder') : 'Type the password';
    $submit_button_text = get_option('simple_site_pwd_protector_submit_button_text') ? get_option('simple_site_pwd_protector_submit_button_text') : 'Submit';
    $error = isset($_GET['incorrect_password']) ? 'error' : '';
?>

<div style="background-color:<?php echo $bg_color; ?>;width:100%;height:100vh;background-image:url(<?php echo $bg_image; ?>);background-size:cover;background-position:center center;background-repeat:no-repeat;display:flex;justify-content:center;align-items:center;flex-direction:column;">
    <form id="sspp" method="POST">
        <input class="<?php echo $input_theme;?> <?php echo $error;?>" type="password" name="simple_site_pwd_protector_password" placeholder="<?php echo $password_placeholder; ?>" />
        <input class="<?php echo $input_theme;?>" type="submit" value="<?php echo $submit_button_text; ?>" />
    </form>
</div>