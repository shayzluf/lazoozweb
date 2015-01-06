(function($) {
    $(document).ready(function() {

        // Customization Page
        var post_type = $('#post_type').val();

        if (typeof post_type !== 'undefined' && post_type == 'page') {
            $('.wp-editor-area').vEditor({
                pattern: '#background-pattern option:selected',
                blur: '#page_blur_select',
                overlay: '#page_overlay_value',
                transparent: '#background_transparent',
                slider: {
                    target: '#page_overlay_slider',
                    result: '.slider_display_result'
                },
                colorpicker: {
                    background: '.wp-color-picker-background',
                    text: '.wp-color-picker-text',
                    overlay: '.wp-color-picker-overlay'
                },
                image: {
                    background: '#background_image',
                    id: '#background_image_id',
                    remove: '#remove_custom_background_image',
                    set: '#set_background_image'
                }
            });
        } else {
            $('.wp-color-picker-background').wpColorPicker();
        }        

        /*---------------------------------------------------------------------------------------------------------
         * Team Social Icon
         --------------------------------------------------------------------------------------------------------*/

        $('.team_social_preview ul').sortable({
            placeholder: "ui-state-highlight",
            beforeStop: function(event, item) {
                var value = '';
                $('.team_social_preview li').not('.ui-state-highlight').each(function() {
                    value += '[social type_icon=' + $('i', this).attr('class').replace('icon-', '') + ' href=' + $('a', this).attr('href') + ']';
                });
                $('#social_team_input').val(value);
            }
        });

        $('.team_social_preview').on('click', ".team_social_remove", function() {
            $('#social_team_input').val($('#social_team_input').val().replace(new RegExp('\\[[^\\]]+type_icon=' + $(this).closest("li").remove().find("i").attr('class').replace('icon-', '') + '[^\\]]+\\]', 'gi'), ''));
            $(this).closest('li').remove();
            return false;
        });

        $('.team_social_preview a').each(function() {
            $('<a href="#" class="team_social_remove button-secondary">Remove</a><br><div class="clearfix"></div>').insertAfter($(this));
        });

        $('#team_social_add').click(function() {
            if ($('.team_social_preview a').is(".icon-" + $(".team_social_select").val())) {
                alert("Such a social network is already added");
                return false;
            } else {
                $('<li><a href="' + $('.team_social_link').val() + '" target="_blank"><i class="icon-' + $(".team_social_select").val() + '"></i></a><a href="#" class="team_social_remove button-secondary">Remove</a></li>').appendTo($(".team_social_preview > ul"));
                $("#social_team_input").val($("#social_team_input").val() + '[social type_icon=' + $(".team_social_select").val() + ' href=' + $('.team_social_link').val() + ']');
            }
            return false;
        });
        /*-------------------------------------------------------------------------------------------------------*/


        /*---------------------------------------------------------------------------------------------------------
         * Footer Social Icon
         --------------------------------------------------------------------------------------------------------*/

        $('.footer_social_preview').sortable({
            placeholder: "ui-state-highlight",
            beforeStop: function(event, item) {
                var value = '';
                $('.footer_social_preview li').not('.ui-state-highlight').each(function() {
                    value += '[social type_icon=' + $('i', this).attr('class').replace('icon-', '') + ' href=' + $('a', this).attr('href') + ']';
                });
                $('#social_footer_input').val(value);
            }
        });


        $(".footer_social_preview").on('click', ".footer_social_remove", function() {
            $("#social_footer_input").val($("#social_footer_input").val().replace(new RegExp('\\[[^\\]]+type_icon=' + $(this).closest("li").remove().find("i").attr('class').replace('icon-', '') + '[^\\]]+\\]', 'gi'), ''));
            return false;
        });

        $(".footer_social_preview li").each(function() {
            $('<a href="javascript: void(0)" class="footer_social_remove button-secondary">Remove</a>').appendTo($(this));
        });

        $("#footer_social_add").click(function() {
            if ($('.footer_social_preview li a').is(".icon-" + $(".footer_social").val())) {
                alert("Such a social network is already added");
                return false;
            } else {
                $('<li><a href="' + $('.footer_social_link').val() + '"><i class="icon-' + $(".footer_social").val() + '"></i></a><a href="#" class="footer_social_remove button-secondary">Remove</a></li>').appendTo($(".footer_social_preview"));
                $("#social_footer_input").val($("#social_footer_input").val() + '[social type_icon=' + $(".footer_social").val() + ' href=' + $('.footer_social_link').val() + ']');
            }
            return false;
        });
        /*-------------------------------------------------------------------------------------------------------*/

        /*---------------------------------------------------------------------------------------------------------
         * Blog Social Icon
         --------------------------------------------------------------------------------------------------------*/

        $('.blog_social_preview').sortable({
            placeholder: "ui-state-highlight",
            beforeStop: function(event, item) {
                var value = '';
                $('.blog_social_preview li').not('.ui-state-highlight').each(function() {
                    value += '[social type_icon=' + $('i', this).attr('class').replace('icon-', '') + ' href=' + $('a', this).attr('href') + ']';
                });
                $('#social_blog_input').val(value);
            }
        });

        $(".blog_social_preview").on('click', ".blog_social_remove", function() {
            $("#social_blog_input").val($("#social_blog_input").val().replace(new RegExp('\\[[^\\]]+type_icon=' + $(this).closest("li").remove().find("i").attr('class').replace('icon-', '') + '[^\\]]+\\]', 'gi'), ''));
            return false;
        });

        $(".blog_social_preview li").each(function() {
            $('<a href="#" class="blog_social_remove button-secondary">Remove</a>').appendTo($(this));
        });

        $("#blog_social_add").click(function() {
            if ($('.blog_social_preview li a i').is(".icon-" + $(".blog_social").val())) {
                alert("Such a social network is already added");
                return false;
            } else {
                $('<li><a href="' + $('.blog_social_link').val() + '"><i class="icon-' + $(".blog_social").val() + '"></i></a><a href="#" class="blog_social_remove button-secondary">Remove</a></li>').appendTo($(".blog_social_preview"));
                $("#social_blog_input").val($("#social_blog_input").val() + '[social type_icon=' + $(".blog_social").val() + ' href=' + $('.blog_social_link').val() + ']');
            }
            return false;
        });
        /*---------------------------------------------------------------------------------------------------------
         * Services Icon
         --------------------------------------------------------------------------------------------------------*/
        $('.active_icon').click(function() {
            if ($('.icon_list').is('.open')) {
                $('.icon_list').removeClass('open');
            } else {
                $('.icon_list').addClass('open');
            }
        });

        $('.service_icon .icon_list ul li').click(function() {
            var icon = $('span', this).text();
            $('.icon_selected').val(icon);
            $('.active_icon').html('<i class="fa ' + icon + '"></i> <span class="i-name">' + icon + '</span>');
        });
        /*-------------------------------------------------------------------------------------------------------*/

        /*---------------------------------------------------------------------------------------------------------
         * Menu Uploader Icon and File
         --------------------------------------------------------------------------------------------------------*/

        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Select the images',
            multiple: false,
            button: {
                text: 'Add selected images'
            },
            library: {
                type: 'image'
            }
        });

        var file_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Select the file',
            multiple: false,
            button: {
                text: 'Add selected file'
            },
            library: {
                type: 'image'
            }
        });

        var gallery_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Select the images',
            multiple: true,
            button: {
                text: 'Add selected images'
            },
            library: {
                type: 'image'
            }
        });

        custom_uploader.on('select', function() {
            image = custom_uploader.state().get('selection').toArray();
            $.each(image, function(index, value) {
                addIcon(value.toJSON().id, value.toJSON().url, item_edit);
            });
        });

        file_uploader.on('select', function() {
            file = file_uploader.state().get('selection').toArray();
            $.each(file, function(index, value) {
                addFile(value.toJSON().id, value.toJSON().url, item_edit, value.toJSON().filename);
            });
        });

        $('#file_uploader_button').click(function(e) {
            file_uploader.open();
            return false;
        });

        $('#icon_upload_button').click(function(e) {
            custom_uploader.open();
            return false;
        });

        /*-------------------------------------------------------------------------------------------------------*/

        /*---------------------------------------------------------------------------------------------------------
         * Portfolio Gallery
         --------------------------------------------------------------------------------------------------------*/
        $('#add_gallery_img').click(function() {
            gallery_uploader.open();
            return false;
        });

        gallery_uploader.on('select', function() {
            image = gallery_uploader.state().get('selection').toArray();
            $.each(image, function(index, value) {
                addGallery(value.toJSON().id);
            });
            galleryList();
        });

        $('#gallery_list').on('click', '.remove_gallery_img', function() {
            $(this).closest('li').remove();
            galleryList();
            return false;
        });
    });
})(jQuery);

function galleryList() {
    var $ = jQuery,
            images_list = {},
            i = 0;
    $('#gallery_list li').each(function() {
        images_list[i++] = {
            id: $(this).attr('data-img-id')
        }
    });
    $('#portfolio_gallery, #custom_gallery').val(JSON.stringify(images_list));
}

function addGallery(id) {
    jQuery('#gallery_list').prepend(getImage(id));
}

function getGalleryImage(id) {
    var result;
    jQuery.ajax({
        async: false,
        type: "POST",
        url: light_doseParams.site_url + "/wp-admin/admin-ajax.php",
        data: {
            action: 'light_dose_get_attachment',
            img_id: id
        },
        success: function(data) {
            result = data;
        }
    });
    return result;
}

function getImage(id) {
    var result;
    jQuery.ajax({
        async: false,
        type: "POST",
        url: light_doseParams.site_url + "/wp-admin/admin-ajax.php",
        data: {
            action: 'light_dose_getImage',
            img_id: id
        },
        success: function(data) {
            result = data;
        }
    });
    return result;
}
function changeColor(hex) {
    if (!hex) {
        hex = '#ffffff';
    }
    var rgba = parseInt(hex.substring(1), 16);
    var r = (rgba & 0xff0000) >> 16;
    var g = (rgba & 0x00ff00) >> 8;
    var b = rgba & 0x0000ff;
    return [r, g, b];
}