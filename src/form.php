<html>
    <head>
        <title><?php echo get_bloginfo('name'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex, nofollow">
        <link rel="stylesheet" href="<?php echo plugins_url('simple-site-password-protector/dist/styles/sspp-form-frontend.css'); ?>" type="text/css" media="all" />
    </head>
<body>
<?php

    if (isset($_POST['simple_site_password_protector_password'])) {
        $password = $_POST['simple_site_password_protector_password'];
        $correct_password = get_option('simple_site_password_protector_password') ? get_option('simple_site_password_protector_password') : false;
        $protector_id = get_option('simple_site_password_protector_id') ? get_option('simple_site_password_protector_id') : false;
        $validation = password_verify($password, $correct_password);
        if ($validation && $protector_id) {
            setcookie(strtolower(get_bloginfo('name')) . '_sspp', $protector_id, time() + (86400 * 30), "/");
            wp_redirect(home_url());
            exit;
        } else {
            $url = $_SERVER['REQUEST_URI'];
            $url = strtok($url, '?');
            wp_redirect($url . '?password_failed=true');
        }
    }

    $bg_image = get_option('simple_site_password_protector_background_image') ? get_option('simple_site_password_protector_background_image') : false;
    $bg_image = $bg_image !== 'https://via.placeholder.com/100x100' ? $bg_image : false;
    $bg_color = get_option('simple_site_password_protector_background_color') ? get_option('simple_site_password_protector_background_color') : '#000000';
    $input_theme = get_option('simple_site_password_protector_input_theme') ? get_option('simple_site_password_protector_input_theme') : 'light';
    $details_color = get_option('simple_site_password_protector_details_color') ? get_option('simple_site_password_protector_details_color') : '#2563eb';
    $password_placeholder = get_option('simple_site_password_protector_password_placeholder') ? get_option('simple_site_password_protector_password_placeholder') : 'Type the password';
    $submit_button_text = get_option('simple_site_password_protector_submit_button_text') ? get_option('simple_site_password_protector_submit_button_text') : 'Submit';
    $failed_password_text = get_option('simple_site_password_protector_failed_password_text') ? get_option('simple_site_password_protector_failed_password_text') : 'Incorrect password';
    $error = isset($_GET['password_failed']) ? $failed_password_text : '';
?>

<style>
    div#submit-button::after, #password::after{
        background-color:<?php echo $details_color; ?>;
    }
</style>

<div id="sspp-frontend-wrapper" style="background-color:<?php echo $bg_color; ?>;background-image:url(<?php echo $bg_image; ?>);">
    <form id="sspp-frontend-form" class="<?php echo $input_theme;?>" method="POST">
        <div id="fields"> 
            <div id="password">
                <input type="password" name="simple_site_password_protector_password" placeholder="<?php echo $password_placeholder; ?>" />
            </div>
            <div id="submit-button">
                <input type="submit" value="<?php echo $submit_button_text; ?>" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                </svg>
            </div>
        </div>
        <div id="error-message">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span><?php echo $error;?></span>
        </div>
    </form>
</div>

<script>
    document.getElementById('sspp-frontend-form').elements[0].focus();
</script>

</body>
</html>