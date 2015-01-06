<?php

function light_dose_register_custom_menu_page() {
    add_theme_page('Light Dose Options', 'Light Dose', 'administrator', 'light_dose_options', 'create_light_dose_panel');
}

add_action('admin_menu', 'light_dose_register_custom_menu_page');

function create_light_dose_panel() {
    ?>
    <div class="wrap">
        <?php screen_icon('themes'); ?>
        <h2>Theme Options</h2>
        <div class="light_dose">
            <?php settings_errors('light_dose-settings-errors'); ?>            
            <form id="form-light_dose-options" action="options.php" method="post" enctype="multipart/form-data">
                <?php
                settings_fields('theme_light_dose_options');
                do_settings_sections('light_dose');
                ?>
                <p class="submit">
                    <input name="theme_light_dose_options[submit]" id="submit_options_form" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'light_dose'); ?>" />
                    <input name="theme_light_dose_options[reset]" type="submit" class="button-secondary" value="<?php esc_attr_e('Reset Defaults', 'light_dose'); ?>" />      
                </p>
            </form>
        </div>
        <?php
    }

    function light_dose_options_settings_init() {

        register_setting('theme_light_dose_options', 'theme_light_dose_options', 'light_dose_options_validate');
        add_settings_section('light_dose_settings_header', __('Home Page Options', 'light_dose'), 'light_dose_settings_header_text', 'light_dose');

        // Render header media
        add_settings_field('light_dose_setting_header_render', __('Use Video Instead Slides', 'light_dose'), 'light_dose_setting_header_render', 'light_dose', 'light_dose_settings_header');
        // Video Background
        add_settings_field('light_dose_setting_site_bg', __('Body Background', 'light_dose'), 'light_dose_setting_site_bg', 'light_dose', 'light_dose_settings_header');
        // Upload Background Image
        add_settings_field('light_dose_setting_background_image', __('Background Image URL', 'light_dose'), 'light_dose_setting_background_image', 'light_dose', 'light_dose_settings_header');
        // Background Blur
        add_settings_field('light_dose_setting_background_blur', __('Background Blur', 'light_dose'), 'light_dose_setting_background_blur', 'light_dose', 'light_dose_settings_header');
        // Video Volume
        add_settings_field('light_dose_setting_video_volume', __('Enable Video Volume', 'light_dose'), 'light_dose_setting_video_volume', 'light_dose', 'light_dose_settings_header');
        // Video Poster
        add_settings_field('light_dose_setting_video_poster', __('Video Poster URL', 'light_dose'), 'light_dose_setting_video_poster', 'light_dose', 'light_dose_settings_header');
        // Video links
        add_settings_field('light_dose_setting_header_video_ogg', __('Ogg Theora (all but IE, Safari)', 'light_dose'), 'light_dose_setting_header_video_ogg', 'light_dose', 'light_dose_settings_header');
        add_settings_field('light_dose_setting_header_video_mp4', __('H.264 MPEG4 ( all but Opera )', 'light_dose'), 'light_dose_setting_header_video_mp4', 'light_dose', 'light_dose_settings_header');
        add_settings_field('light_dose_setting_header_video_webm', __('HVP8WebM ( all but IE, Safari )', 'light_dose'), 'light_dose_setting_header_video_webm', 'light_dose', 'light_dose_settings_header');
        
        add_settings_section('light_dose_settings_blog', __('Blog Social Icons', 'light_dose'), 'light_dose_settings_blog_text', 'light_dose');
        add_settings_field('light_dose_setting_blog_social', __('Social Icons', 'light_dose'), 'light_dose_setting_blog_social', 'light_dose', 'light_dose_settings_blog');

        add_settings_section('light_dose_settings_footer', __('Footer Social Icons', 'light_dose'), 'light_dose_settings_footer_text', 'light_dose');
        add_settings_field('light_dose_setting_footer_social', __('Social Icons', 'light_dose'), 'light_dose_setting_footer_social', 'light_dose', 'light_dose_settings_footer');
    }

    add_action('admin_init', 'light_dose_options_settings_init');

    function light_dose_settings_header_text() {
        ?>
        <p><?php _e('Manage Home Page Options for Light Dose Theme.', 'light_dose'); ?></p>
        <?php
    }

    function light_dose_settings_footer_text() {
        ?>
        <p><?php _e('Manage Footer Options for Light Dose Theme.', 'light_dose'); ?></p>
        <?php
    }
    
    function light_dose_settings_blog_text() {
        ?>
        <p><?php _e('Manage Blog Options for Light Dose Theme.', 'light_dose'); ?></p>
        <?php
    }

    function light_dose_settings_background_options_text() {
        ?>
        <p><?php _e('Manage Background Options for Light Dose Theme.', 'light_dose'); ?></p>
        <?php
    }

    function light_dose_get_default_options() {
        $options = array(
            'video_volume' => 1,
            'site_bg' => 0,
            'background_image' => '',
            'background_blur' => 0,
            'video_poster' => '',
            'video_ogg' => '',
            'video_mp4' => '',
            'video_webm' => '',
            'render_header' => '',
            'footer_social' => '',
            'blog_social' => '',
            'services_background' => '',
            'services_overlay' => '',
        );
        return $options;
    }

    function light_dose_options_init() {
        $light_dose_options = get_option('theme_light_dose_options');
        if (false === $light_dose_options) {
            // If not, we'll save our default options
            $light_dose_options = light_dose_get_default_options();
            add_option('theme_light_dose_options', $light_dose_options);
        }
    }

    add_action('after_setup_theme', 'light_dose_options_init');

    function light_dose_options_validate($input) {
        $default_options = light_dose_get_default_options();
        $valid_input = $default_options;        
        $submit = !empty($input['submit']) ? true : false;
        $reset = !empty($input['reset']) ? true : false;
        if ($submit) {
            $valid_input['render_header'] = $input['render_header'];
            $valid_input['site_bg'] = $input['site_bg'];
            $valid_input['background_image'] = $input['background_image'];
            $valid_input['background_blur'] = $input['background_blur'];
            $valid_input['video_volume'] = @$input['video_volume'];
            $valid_input['video_poster'] = $input['video_poster'];
            $valid_input['video_ogg'] = $input['video_ogg'];
            $valid_input['video_mp4'] = $input['video_mp4'];
            $valid_input['video_webm'] = $input['video_webm'];
            $valid_input['footer_social'] = $input['footer_social'];
            $valid_input['blog_social'] = $input['blog_social'];
        } elseif ($reset) {
            $valid_input['render_header'] = $default_options['render_header'];
            $valid_input['site_bg'] = $default_options['site_bg'];
            $valid_input['background_image'] = $default_options['background_image'];
            $valid_input['background_blur'] = $default_options['background_blur'];
            $valid_input['video_volume'] = $default_options['video_volume'];
            $valid_input['video_poster'] = $default_options['video_poster'];
            $valid_input['video_ogg'] = $default_options['video_ogg'];
            $valid_input['video_mp4'] = $default_options['video_mp4'];
            $valid_input['video_webm'] = $default_options['video_webm'];
            $valid_input['footer_social'] = $default_options['footer_social'];
            $valid_input['blog_social'] = $default_options['blog_social'];
        }
        return $valid_input;
    }

    function light_dose_setting_blog_social() {
        $light_dose_options = get_option('theme_light_dose_options');
        ?>
        <input id="social_blog_input" type="hidden" name="theme_light_dose_options[blog_social]" value="<?php echo !isset($light_dose_options['blog_social']) ? '' : $light_dose_options['blog_social']; ?>" />
        <select class="blog_social">
            <?php light_dose_social_tags(); ?>
        </select>
        <input type="text" class="blog_social_link">
        <a href="#" id="blog_social_add" class="button-secondary"><?php _e('Add', 'light_dose'); ?></a>
        <ul class="blog_social_preview">
            <?php
            if (isset($light_dose_options['blog_social'])) {
                echo do_shortcode($light_dose_options['blog_social']);
            }
            ?>
        </ul>
        <?php
    }
    
    function light_dose_setting_footer_social() {
        $light_dose_options = get_option('theme_light_dose_options');
        ?>
        <input id="social_footer_input" type="hidden" name="theme_light_dose_options[footer_social]" value="<?php echo !isset($light_dose_options['footer_social']) ? '' : $light_dose_options['footer_social']; ?>" />
        <select class="footer_social">
            <?php light_dose_social_tags(); ?>
        </select>
        <input type="text" class="footer_social_link">
        <a href="#" id="footer_social_add" class="button-secondary"><?php _e('Add', 'light_dose'); ?></a>
        <ul class="footer_social_preview">
            <?php
            if (isset($light_dose_options['footer_social'])) {
                echo do_shortcode($light_dose_options['footer_social']);
            }
            ?>
        </ul>
        <?php
    }
    
    function light_dose_social_tags() {
        ?>
        <option value="acrobat">acrobat</option>
            <option value="aim">aim</option>
            <option value="amazon">amazon</option>
            <option value="android">android</option>
            <option value="angellist">angellist</option>
            <option value="appnet">appnet</option>
            <option value="appstore">appstore</option>
            <option value="bitbucket">bitbucket</option>
            <option value="bitcoin">bitcoin</option>
            <option value="blogger">blogger</option>
            <option value="buffer">buffer</option>
            <option value="calendar">calendar</option>
            <option value="call">call</option>
            <option value="cart">cart</option>
            <option value="cc">cc</option>
            <option value="chrome">chrome</option>
            <option value="cloudapp">cloudapp</option>
            <option value="delicious">delicious</option>
            <option value="digg">digg</option>
            <option value="disqus">disqus</option>
            <option value="dribbble">dribbble</option>
            <option value="dropbox">dropbox</option>
            <option value="drupal">drupal</option>
            <option value="dwolla">dwolla</option>
            <option value="ebay">ebay</option>
            <option value="email">email</option>
            <option value="eventasaurus">eventasaurus</option>
            <option value="eventbrite">eventbrite</option>
            <option value="eventful">eventful</option>
            <option value="evernote">evernote</option>
            <option value="facebook">facebook</option>
            <option value="fivehundredpx">fivehundredpx</option>
            <option value="flattr">flattr</option>
            <option value="flickr">flickr</option>
            <option value="forrst">forrst</option>
            <option value="foursquare">foursquare</option>
            <option value="github">github</option>
            <option value="gmail">gmail</option>
            <option value="google">google</option>
            <option value="googleplay">googleplay</option>
            <option value="gowalla">gowalla</option>
            <option value="gplus">gplus</option>
            <option value="grooveshark">grooveshark</option>
            <option value="guest">guest</option>
            <option value="html5">html5</option>
            <option value="ie">ie</option>
            <option value="instagram">instagram</option>
            <option value="instapaper">instapaper</option>
            <option value="intensedebate">intensedebate</option>
            <option value="itunes">itunes</option>
            <option value="klout">klout</option>
            <option value="lanyrd">lanyrd</option>
            <option value="lastfm">lastfm</option>
            <option value="linkedin">linkedin</option>
            <option value="macstore">macstore</option>
            <option value="meetup">meetup</option>
            <option value="myspace">myspace</option>
            <option value="ninetyninedesigns">ninetyninedesigns</option>
            <option value="openid">openid</option>
            <option value="opentable">opentable</option>
            <option value="paypal">paypal</option>
            <option value="pinboard">pinboard</option>
            <option value="pinterest">pinterest</option>
            <option value="plancast">plancast</option>
            <option value="plurk">plurk</option>
            <option value="pocket">pocket</option>
            <option value="podcast">podcast</option>
            <option value="posterous">posterous</option>
            <option value="print">print</option>
            <option value="quora">quora</option>
            <option value="reddit">reddit</option>
            <option value="rss">rss</option>
            <option value="scribd">scribd</option>
            <option value="skype">skype</option>
            <option value="smashmag">smashmag</option>
            <option value="songkick">songkick</option>
            <option value="soundcloud">soundcloud</option>
            <option value="spotify">spotify</option>
            <option value="statusnet">statusnet</option>
            <option value="steam">steam</option>
            <option value="stripe">stripe</option>
            <option value="stumbleupon">stumbleupon</option>
            <option value="tumblr">tumblr</option>
            <option value="twitter">twitter</option>
            <option value="viadeo">viadeo</option>
            <option value="vimeo">vimeo</option>
            <option value="vk">vk</option>
            <option value="w3c">w3c</option>
            <option value="weibo">weibo</option>
            <option value="wikipedia">wikipedia</option>
            <option value="windows">windows</option>
            <option value="wordpress">wordpress</option>
            <option value="xing">xing</option>
            <option value="yahoo">yahoo</option>
            <option value="yelp">yelp</option>
            <option value="youtube">youtube</option>
            <option value="duckduckgo">duckduckgo</option>
        <?php
    }

    function light_dose_setting_site_bg() {
        $light_dose_options = get_option('theme_light_dose_options');
        if(!isset($light_dose_options['site_bg'])) {
            $light_dose_options['site_bg'] = 0;
        }
        ?>
        <select name="theme_light_dose_options[site_bg]" id="site_background">
            <option value="0" <?php echo $light_dose_options['site_bg'] == 0 ? 'selected="selected"' : ''; ?>><?php echo _e('Custom', 'light_dose'); ?></option>
            <option value="1" <?php echo $light_dose_options['site_bg'] == 1 ? 'selected="selected"' : ''; ?>><?php echo _e('Image', 'light_dose'); ?></option>
            <option value="2" <?php echo $light_dose_options['site_bg'] == 2 ? 'selected="selected"' : ''; ?>><?php echo _e('Video', 'light_dose'); ?></option>
        </select>
        <?php
    }

    function light_dose_setting_header_render() {
        $light_dose_options = get_option('theme_light_dose_options');
        ?>
        <select name="theme_light_dose_options[render_header]" id="render_header">
            <option value="slides" <?php echo $light_dose_options['render_header'] == 'slides' ? 'selected="selected"' : ''; ?>>No</option>
            <option value="video" <?php echo $light_dose_options['render_header'] == 'video' ? 'selected="selected"' : ''; ?>>Yes</option>
        </select>
        <?php
    }

    function light_dose_setting_video_volume() {
        $light_dose_options = get_option('theme_light_dose_options');
        $value = !isset($light_dose_options['video_volume']) ? '1' : '0';
        ?>
        <input type="checkbox" name="theme_light_dose_options[video_volume]" value="<?php echo $value; ?>" <?php echo isset($light_dose_options['video_volume']) ? ' checked="checked"' : ''; ?>/>
        <?php
    }
    
    function light_dose_setting_background_image() {
        $light_dose_options = get_option('theme_light_dose_options');
        ?>
        <input class="upload_image_button button" type="button" value="Upload Image" />
        <input type="text" id="background_image" name="theme_light_dose_options[background_image]" value="<?php echo esc_html($light_dose_options['background_image']); ?>" size="60"/>
        <?php
    }
    
    function light_dose_setting_background_blur() {
        $light_dose_options = get_option('theme_light_dose_options');
        $background_blur = 0;
        if(isset($light_dose_options['background_blur'])) {
            $background_blur = $light_dose_options['background_blur'];
        }
        ?>
        <span class="site_background_blur_result"><?php echo esc_html($background_blur); ?></span>
        <div id="site_background_blur"></div>
        <input type="hidden" id="background_blur" name="theme_light_dose_options[background_blur]" value="<?php echo esc_html($background_blur); ?>" />
        <?php
    }
    
    function light_dose_setting_video_poster() {
        $light_dose_options = get_option('theme_light_dose_options');
        ?>
        <input class="upload_image_button button" type="button" value="Upload Poster" />
        <input type="text" name="theme_light_dose_options[video_poster]" value="<?php echo esc_html($light_dose_options['video_poster']); ?>" size="60"/>
        <?php
    }

    function light_dose_setting_header_video_ogg() {
        $light_dose_options = get_option('theme_light_dose_options');
        if (!isset($light_dose_options['video_ogg'])) {
            $light_dose_options['video_ogg'] = null;
        }
        ?>
        <input class="upload_video_button button" type="button" value="Upload Video" />
        <input type="text" name="theme_light_dose_options[video_ogg]" value="<?php echo esc_html($light_dose_options['video_ogg']); ?>" size="60"/>
        <?php
    }

    function light_dose_setting_header_video_mp4() {
        $light_dose_options = get_option('theme_light_dose_options');
        if (!isset($light_dose_options['video_mp4'])) {
            $light_dose_options['video_mp4'] = null;
        }
        ?>
        <input class="upload_video_button button" type="button" value="Upload Video" />
        <input type="text" name="theme_light_dose_options[video_mp4]" value="<?php echo esc_html($light_dose_options['video_mp4']); ?>" size="60"/>
        <?php
    }

    function light_dose_setting_header_video_webm() {
        $light_dose_options = get_option('theme_light_dose_options');
        if (!isset($light_dose_options['video_webm'])) {
            $light_dose_options['video_webm'] = null;
        }
        ?>
        <input class="upload_video_button button" type="button" value="Upload Video" />
        <input type="text" name="theme_light_dose_options[video_webm]" value="<?php echo esc_html($light_dose_options['video_webm']); ?>" size="60"/>
        <?php
    }
    