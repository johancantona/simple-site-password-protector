jQuery(document).ready(function($) {
    $('#sspp_upload_image_button').click(function(e) {
        e.preventDefault();

        var mediaUploader = wp.media({
            title: 'Select Background Image',
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('input[name="simple_site_password_protector_background_image"]').val(attachment.url);
            $('#sspp_background_image_preview').attr('src', attachment.url);
        });

        mediaUploader.open();
    });

    $('#sspp_remove_image_button').click(function(e) {
        e.preventDefault();
        $('input[name="simple_site_password_protector_background_image"]').val('https://via.placeholder.com/100x100');
        $('#sspp_background_image_preview').attr('src', 'https://via.placeholder.com/100x100');
    }
    );
});