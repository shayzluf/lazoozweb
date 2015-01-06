jQuery(document).ready(function($) {

    var orig_send_to_editor = window.send_to_editor;
    var site_background = $('#site_background option:selected').val();
    $('.upload_image_button').click(function() {
        formfield = $(this).next('input');
        tb_show('Upload a image', 'media-upload.php?referer=light_dose_options&amp;type=image&amp;TB_iframe=true&amp;width=800&amp;height=600&amp;post_id=0', false);
        window.send_to_editor = function(html) {
            var imgurl = $('img', html).attr('src');
            formfield.val(imgurl);
            tb_remove();
            $('#' + formfield.attr('name')).val(imgurl);
            window.send_to_editor = orig_send_to_editor;
        }
        return false;
    });

    $('.upload_video_button').click(function() {
        formfield = $(this).next('input');
        tb_show('Add Media', 'media-upload.php?referer=light_dose_options&type=file&amp;TB_iframe=true&amp;width=800&amp;height=600');

        window.send_to_editor = function(html) {
            var imgurl = $('img', html).attr('src');
            if ($(imgurl).length == 0) {
                imgurl = $(html).attr('href');
            }
            formfield.val(imgurl);
            tb_remove();
            $('#' + formfield.attr('name')).val(imgurl);
            window.send_to_editor = orig_send_to_editor;
        }
        return false;
    });

    if (site_background != 1) {
        $('#form-light_dose-options').find('#background_image, #site_background_blur').closest('tr').hide();
    }

    $('#site_background').change(function() {
        if ($(this).val() == 1) {
            $('#form-light_dose-options').find('#background_image, #site_background_blur').closest('tr').show();
        } else {
            $('#form-light_dose-options').find('#background_image, #site_background_blur').closest('tr').hide();
        }
    });

    $("#site_background_blur").slider({
        value: $('#background_blur').val(),
        min: 0,
        max: 100,
        step: 1,
        slide: function(event, ui) {            
            $('#background_blur').val(ui.value);
            $('.site_background_blur_result').text(ui.value);
        }
    }).css({
        'max-width': 570 + 'px',
        'margin-left': 30 + 'px'
    });
});

