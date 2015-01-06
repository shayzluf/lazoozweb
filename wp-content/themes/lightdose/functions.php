<?php

/**
 * Light Dose functions and definitions
 *
 * @package Light Dose
 */
$light_dose_user = array('login', 'register');

if (!isset($content_width)) {
    $content_width = 670;
}

function ajax_init() {
    global $light_dose_user;
    wp_register_script('light_dose', get_template_directory_uri() . '/js/requests.js', array('jquery'));
    wp_enqueue_script('light_dose');

    wp_localize_script('light_dose', 'ajax', array(
        'url' => admin_url('admin-ajax.php'),
        'referer' => wp_get_referer(),
    ));
    $home_url = home_url() . '/';
    if (function_exists('icl_get_home_url')) {
        $home_url = icl_get_home_url();
    }
    wp_localize_script('light_dose', 'user', array(
        'exclude' => $light_dose_user,
        'home_url' => $home_url,
        'logout_url' => wp_logout_url($home_url),
        'logout_title' => __('Logout', 'light_dose'),
    ));
    add_action('wp_ajax_nopriv_ajaxlogin', 'light_dose_signin');
}

add_action('init', 'ajax_init');

function light_dose_feedback() {
    header('Content-Type: application/json');
    $light_dose_options = get_option('light_dose_theme_options');

    $to = $light_dose_options['form_email'];

    if (empty($to)) {
        print json_encode(array('success' => false, 'message' => __("Error sending email!", "light_dose")));
        die;
    }

    if (!empty($_POST['feedbackFormName']) && !empty($_POST['feedbackFormEmail']) && !empty($_POST['feedbackFormComment'])) {
        $from = htmlspecialchars($_POST['feedbackFormEmail'], ENT_QUOTES);
        $name = htmlspecialchars($_POST['feedbackFormName'], ENT_QUOTES);
        $subject = __("Contact Form", "light_dose");

        $headers = "From: $name <$from>\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8';
        $message = '
<html>
	<head>
		<title>' . __("Your Site Contact Form", "light_dose") . '</title>
	</head>
	<body>
		<h3>' . __('Name:', 'light_dose') . ' <span style="font-weight: normal;">' . $name . '</span></h3>
		<h3>' . __('Email:', 'light_dose') . ' <span style="font-weight: normal;">' . $from . '</span></h3>
		<div>
			<h3 style="margin-bottom: 5px;">' . __('Message:', 'light_dose') . '</h3>
			<div>' . htmlspecialchars($_POST ['feedbackFormComment'], ENT_QUOTES) . '</div>
		</div>
	</body>
</html>';
        if (is_email($from)) {
            $send = wp_mail($to, $subject, $message, $headers);
            if (!$send) {
                print json_encode(array('success' => false, 'message' => __("Error sending email!", "light_dose")));
            } else {
                print json_encode(array('success' => true, 'message' => __("Your email was sent!", "light_dose")));
            }
        } else {
            print json_encode(array('success' => false, 'message' => __("Invalid email address!", "light_dose")));
        }
    } else {
        print json_encode(array('success' => false, 'message' => __("All fields must be filled!", "light_dose")));
    }
    die;
}

add_action('wp_ajax_feedback', 'light_dose_feedback');
add_action('wp_ajax_nopriv_feedback', 'light_dose_feedback');

function light_dose_registration() {

    $info = array();
    $message = '';
    $success = true;
    $info['user_name'] = trim($_POST['user_name']);
    $info['user_email'] = trim($_POST['user_email']);

    $user = username_exists($info['user_name']);

    if (!$user and email_exists($info['user_email']) == false) {
        if (!is_email($info['user_email'])) {
            $success = false;
            $message = __('Invalid Email address!', 'light_dose');
        } else {
            $random_password = wp_generate_password($length = 8, $include_standard_special_chars = false);
            $user = wp_create_user($info['user_name'], $random_password, $info['user_email']);
            wp_new_user_notification($user, $random_password);
            $message = __('Registration complete. Please check your e-mail.', 'light_dose');
        }
    } else {
        $success = false;
        $message = __('User already exists!', 'light_dose');
    }
    echo json_encode(array('success' => $success, 'message' => $message));
    die;
}

add_action('wp_ajax_registration', 'light_dose_registration');
add_action('wp_ajax_nopriv_registration', 'light_dose_registration');

function light_dose_signin() {
    check_ajax_referer('ajax-login-nonce', 'security');
    $creds = array();
    $creds['user_login'] = $_POST['username'];
    $creds['user_password'] = $_POST['password'];
    $creds['remember'] = isset($_POST['remember']) ? true : false;
    $user = wp_signon($creds, false);
    $success = true;
    $message = '';
    if (is_wp_error($user)) {
        $success = false;
        $message = $user->get_error_message();
    }
    echo json_encode(array('success' =>
        $success, 'error' => $message));
    die;
}

function light_dose_get_last_post() {
    global $wpdb;
    header('Content-Type: application/json');
    $not_in = array();
    $id = 0;
    $timestamp = $_POST['timestamp'];
    sort($timestamp, SORT_NUMERIC);

    foreach ($timestamp as $ts) {
        $not_in[] = (int) $ts;
    }
    if (!count($not_in)) {
        print json_encode(array(
            'success' => false,
            'ts' => 0,
        ));
        exit;
    }
    $ts = min($not_in);
    
    if (function_exists('icl_object_id')) {
        $single_post = $wpdb->get_results("SELECT wposts.*
                                           FROM $wpdb->posts wposts, wp_icl_translations icl_translations
                                           WHERE wposts.ID = icl_translations.element_id
                                           AND icl_translations.language_code = '" . ICL_LANGUAGE_CODE . "'
                                           AND wposts.post_status = 'publish'
                                           AND wposts.post_type = 'post'
                                           AND UNIX_TIMESTAMP(wposts.`post_date`) < $ts
                                           AND icl_translations.element_id = wposts.ID
                                           LIMIT 1");
    } else {
        $single_post = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE `post_type`='post' AND UNIX_TIMESTAMP(`post_date`) < $ts AND `post_status`='publish' LIMIT 1");
    }
    $success = false;
    $html = null;
    $post = array_shift($single_post);
    if (!is_null($post)) {
        $success = true;
        $id = $post->ID;
        $ts = strtotime($post->post_date);
        $content = explode('<!--more-->', $post->post_content);
        $comments = wp_count_comments($id);
        $user = get_userdata($post->post_author);
        $more = count($content) > 1 ? '<a class="more" href="' . get_permalink($id) . '">' . __('Read More', 'light_dose') . '<div class="arrow"><span class="tip"></span></div></a>' : '';
        $html = '<article class="post" id="post-' . $id . '-' . $ts . '">
                            <h2 class="entry-header">
                                <a href="' . get_permalink($id) . '">
                                    ' . $post->post_title . '
                                </a>
                            </h2>
                            <div class="entry-meta">
                                <span class="author">
                                    ' . $user->display_name . ',
                                </span>
                                <small class="date">
                                    ' . date(get_option('date_format'), strtotime($post->post_date)) . ',
                                </small>
                                <span class="comment-count">
                                    ' . $comments->total_comments . ' ' . __('comments', 'light_dose') . '
                                </span>
                            </div>
                            <div class="entry">                                
                                ' . wp_get_attachment_image(get_post_thumbnail_id($id), 'blog-attachment', false, array('class' => 'img-responsive')) . '
                                ' . nl2br(reset($content)) . '
                                ' . $more . '
                            </div>
                        </article>';
    }
    print json_encode(array(
        'success' => $success,
        'html' => $html,
        'ts' => $ts,
    ));
    exit;
}

add_action('wp_ajax_get_last_post', 'light_dose_get_last_post');
add_action('wp_ajax_nopriv_get_last_post', 'light_dose_get_last_post');

function light_dose_get_attachment() {
    $thumbnail = wp_get_attachment_image_src($_POST['id'], 'medium');
    $image = wp_get_attachment_image_src($_POST['id'], 'full');
    print json_encode(array('thumbnail' => $thumbnail[0], 'image' => count($image) ? $image[0] : null));
    exit;
}

add_action('wp_ajax_get_attachment', 'light_dose_get_attachment');

function light_dose_is_logged_in() {
    global $light_dose_user;
    $home_url = home_url();
    if (function_exists('icl_get_home_url')) {
        $home_url = icl_get_home_url();
    }

    if (is_user_logged_in() && in_array(light_dose_set_element_id($_SERVER['REQUEST_URI']), $light_dose_user)) {
        wp_redirect($home_url);
        exit;
    }
}

add_action('init', 'light_dose_is_logged_in');

function getDefaultFonts() {
    return array(
        'Arial',
        'Century Gothic',
        'Courier New',
        'Georgia',
        'Helvetica',
        'Impact',
        'Lucida Console',
        'Lucida Sans Unicode',
        'Palatino Linotype',
        'sans-serif',
        'serif',
        'Tahoma',
        'Trebuchet MS',
        'Verdana',
    );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function light_dose_setup() {

    /**
     * Make theme available for translation
     * Translations can be filed in the /languages/ directory
     * If you're building a theme based on Light Dose, use a find and replace
     * to change 'light_dose' to the name of your theme in all the template files
     */ load_theme_textdomain('light_dose', get_template_directory() . '/languages');

    /**
     * Add default posts and comments RSS feed links to head
     */
    add_theme_support('automatic-feed-links');

    /**
     * Enable support for Post Thumbnails on posts and pages
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support('post-thumbnails');

    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();

    // Add support for custom backgrounds.
    /*
      add_theme_support('custom-background', array(
      // Let WordPress know what our default background color is.
      // This is dependent on our current color scheme.
      'default-color' => '#FFF',
      ));
     * 
     */

    /**
     * Enable support for Logo
     */
    /*
      add_theme_support('custom-header', array(
      'default-image' => get_template_directory_uri() . '/img/light-dose-logo.png',
      'width' => 50,
      'flex-width' => true,
      'flex-height' => false,
      'header-text' => false,
      ));
     * 
     */

    /**
     * Theme resize image.
     */
    add_theme_support('post-thumbnails');
    add_image_size('works-small', 580, 362, true);
    add_image_size('works-large', 1200, 0, true);
    add_image_size('story-small', 107, 0, true);
    add_image_size('about-small', 100, 0, true);
    add_image_size('about-large', 1200, 0, true);
    add_image_size('post-attachment', 887, 0, true);
    add_image_size('blog-attachment', 670, 0, true);
    add_image_size('team-small', 180, 180, true);

    /**
     * This theme uses wp_nav_menu() in one location.
     */
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'primary'), 'footer-menu' => __('Footer Menu', 'footer-menu'),
    ));

    /**
     * Enable support for Post Formats
     */ add_theme_support('post-formats', array('aside', 'image', 'video', 'quote', 'link'));
}

add_action(
        'after_setup_theme', 'light_dose_setup');

function light_dose_register_sidebar() {
    register_sidebar(array(
        'name' => __('Left Sidebar', 'light_dose'),
        'id' => 'left-sidebar', 'description' => __('Light Dose Left Sidebar', 'light_dose'),
        'before_widget', '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<small class="caption">',
        'after_title' => '</small>',
    ));
}

add_action('widgets_init', 'light_dose_register_sidebar');

/**
 * Enqueue styles
 */
function light_dose_enqueue_before_scripts() {
    global $is_IE;
    $light_dose_options_theme = get_option('light_dose_theme_options');
    $font = "Lato";
    if (isset($light_dose_options_theme['font_family']) && !empty($light_dose_options_theme['font_family']) && !in_array($light_dose_options_theme['font_family'], getDefaultFonts())) {
        $font = $light_dose_options_theme['font_family'];
    }

    wp_register_style('google-fonts-lato', '//fonts.googleapis.com/css?family=' . $font . ':400,100,100italic,300,300italic,400italic,700,700italic,900,900italic');
    wp_register_style('google-fonts-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600italic,600,700,700italic,800,800italic&subset=latin,cyrillic-ext,greek-ext,vietnamese,greek,latin-ext,cyrillic');
    wp_register_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css');

    wp_register_style('bootstrap.min', '//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css');
    wp_register_style('bootstrap-theme.min', '//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css');
    wp_register_style('blueimp-gallery.min', '//blueimp.github.io/Gallery/css/blueimp-gallery.min.css');

    wp_register_style('social-icons', get_template_directory_uri() . '/css/social-icons.css');
    wp_register_style('theme', get_template_directory_uri() . '/css/light-dose.css');
    wp_register_style('style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style(array('google-fonts-lato', 'google-fonts-sans', 'font-awesome', 'bootstrap.min', 'bootstrap-theme.min', 'blueimp-gallery.min', 'social-icons', 'theme', 'style'), null, true);

    if ($is_IE) {
        echo '<!--[if IE 8]>'
        . '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/light-dose-ie8.css" />'
        . '<![endif]-->';
    }

    if ($is_IE) {
        echo '<!--[if lt IE 9]>'
        . '<script type="text/javascript" src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>'
        . '<script type="text/javascript" src="//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>'
        . '<![endif]-->';
    }

    wp_register_script('spin', get_template_directory_uri() . '/js/spin.min.js', array('jquery'), null);
    wp_enqueue_script('spin');
}

add_action('wp_enqueue_scripts', 'light_dose_enqueue_before_scripts');

/**
 * Include javascript
 */
function light_dose_enqueue_after_scripts() {

    $scripts = array();
    $enqueue_scripts = array(
        'jquery-migrate-1.2.1.min',
        'bootstrap.min',
        'helper-plugins' => array(
            'jquery.ba-throttle-debounce.min',
            'jquery.mousewheel.min',
            'jquery.touchSwipe.min',
            'jquery.transit.min'
        ),
        'jquery.carouFredSel-6.2.1-packed',
        'jquery.customSelect.min',
        'masonry.pkgd.min',
        'validate.min',
        'jquery.blueimp-gallery.min',
        'StackBlur',
        'jquery.form',
        'porthole.config',
        'porthole.min',
    );

    foreach ($enqueue_scripts as $dir => $script) {
        if (is_array($script)) {
            foreach ($script as $key => $plugin_script) {
                wp_register_script($plugin_script, get_template_directory_uri() . "/js/$dir/$plugin_script.js", array(), null, true);
                array_push($scripts, $plugin_script);
            }
        } else {
            wp_register_script($script, get_template_directory_uri() . "/js/$script.js", array(), null, true);
            array_push($scripts, $script);
        }
    }
    array_unshift($scripts, 'jquery-ui-core');
    
    wp_enqueue_script('light-dose-scripts', get_template_directory_uri() . '/js/light-dose.js', $scripts, '1.0.0', true);
    

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'light_dose_enqueue_after_scripts');

/**
 * Enqueue scripts and styles to admin page
 */
function load_light_dose_admin_js_and_css() {
    wp_register_style('admin-style', get_template_directory_uri() . '/css/admin-style.css');
    wp_register_style('social-icons', get_template_directory_uri() . '/css/social-icons.css');
    wp_register_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');
    wp_register_style('ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/themes/base/jquery.ui.all.css');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_style('light-dose', get_stylesheet_uri(), array('admin-style', 'social-icons', 'thickbox', 'farbtastic', 'font-awesome', 'ui'), null, true);
    wp_enqueue_media();
    wp_enqueue_script('light_dose_admin_js', get_template_directory_uri() . '/js/admin-script.js', array('jquery', 'jquery-ui-sortable', 'jquery-masonry', 'wp-color-picker', 'jquery-ui-slider'), false);
    $l10n = array(
        'site_url' => site_url()
    );
    wp_localize_script('light_dose_admin_js', 'light_doseParams', $l10n);
    wp_register_script('slide', get_template_directory_uri() . '/js/slide.js', array());
    wp_register_script('wp-visual-editor', get_template_directory_uri() . '/js/wp-visual-editor.js', array());
    wp_register_script('blur', get_template_directory_uri() . '/js/blur.min.js', array());
    wp_enqueue_script('jquery');
    wp_enqueue_script('thickbox');
    wp_enqueue_script('light-dose-option-script');
    wp_enqueue_script('slide');
    wp_enqueue_script('wp-visual-editor');
    wp_enqueue_script('blur');
}

add_action(
        'admin_enqueue_scripts', 'load_light_dose_admin_js_and_css');

function newgravatar($avatar_defaults) {
    $avatar = get_template_directory_uri() . '/img/avatar.png';
    $avatar_defaults[$avatar] = "Light Dose Default Avatar";
    return $avatar_defaults;
}

add_filter('avatar_defaults', 'newgravatar');

function change_avatar_css($class) {
    $class = str_replace("class='avatar", "class='pull-left img-responsive avatar", $class);
    return $class;
}

add_filter('get_avatar', 'change_avatar_css');

function light_dose_form_defaults($defaults) {
    $defaults['title_reply'] = '';
    return $defaults;
}

add_filter(
        'comment_form_defaults', 'light_dose_form_defaults');

function light_dose_custom_upload_mimes($existing_mimes = array()) {
    $existing_mimes['svg'] = 'mime/type';
    return $existing_mimes;
}

add_filter(
        'upload_mimes', 'light_dose_custom_upload_mimes');

function search_filter($query) {
    if ($query->is_search) {
        $query->set('post_type', 'post');
    } return $query;
}

add_filter('pre_get_posts', 'search_filter');

function get_slug() {
    $request = $_SERVER ['REQUEST_URI'];
    $parts = explode('/', $request);
    $elements = array();
    if (count($parts)) {
        foreach ($parts as $el) {
            if (!empty($el)) {
                $elements[] = $el;
            }
        }
        return end($elements);
    }

    return str_replace("/", "", $request);
}

function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = array($r, $g, $b);
    return $rgb;
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/includes/extra.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/includes/customizer.php';

/**
 * Load theme functions.
 */
require get_template_directory() . '/includes/theme-function.php';

/**
 * Load theme settings.
 */
require get_template_directory() . '/includes/theme-dashboard.php';

/**
 * Theme Post Types.
 */
require get_template_directory() . '/includes/theme-post-types.php';

/**
 * Theme Shortcode.
 */
require get_template_directory() . '/includes/theme-shortcode.php';

/**
 * Load theme color.
 */
//require get_template_directory() . '/inc/theme-color.php';
