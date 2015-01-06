<?php
/**
 * light_dose Theme Customizer
 *
 * @package Light Dose
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function light_dose_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
    $wp_customize->remove_section('title_tagline');
    $wp_customize->remove_section('static_front_page');
    $wp_customize->remove_section('nav');
    $wp_customize->remove_section('colors');
    $wp_customize->remove_section('background_image');

    $wp_customize->add_section('light_dose_header', array(
        'title' => __('Home Page', 'light_dose'),
        'priority' => 10,
    ));

    $wp_customize->add_section('light_dose_theme_options', array(
        'title' => __('Theme Options', 'light_dose'),
        'priority' => 20,
    ));

    $wp_customize->add_section('light_dose_button_home_page', array(
        'title' => __('Button On Home Page', 'light_dose'),
        'priority' => 30,
    ));

    $wp_customize->add_section('light_dose_background', array(
        'title' => __('Background Options', 'light_dose'),
        'priority' => 40,
    ));

    $wp_customize->add_section('light_dose_blog', array(
        'title' => __('Blog', 'light_dose'),
        'priority' => 50,
    ));

    $wp_customize->add_section('light_dose_contact_form', array(
        'title' => __('Feedback', 'light_dose'),
        'priority' => 60,
    ));

    $wp_customize->add_section('light_dose_footer', array(
        'title' => __('Footer', 'light_dose'),
        'priority' => 70,
    ));

    $wp_customize->add_setting('light_dose_theme_options[services_background]', array(
        'default' => get_template_directory_uri() . '/img/background-services.jpg',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'services_background', array(
        'label' => __('Section Services (Background)', 'light_dose'),
        'section' => 'light_dose_background',
        'settings' => 'light_dose_theme_options[services_background]',
    )));

    $wp_customize->add_setting('light_dose_theme_options[services_blurred]', array(
        'default' => get_template_directory_uri() . '/img/background-services-blurred.jpg',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'services_blurred', array(
        'label' => __('Section Services (Blurred)', 'light_dose'),
        'section' => 'light_dose_background',
        'settings' => 'light_dose_theme_options[services_blurred]',
    )));

    $wp_customize->add_setting('light_dose_theme_options[stories_background]', array(
        'default' => get_template_directory_uri() . '/img/background-action.jpg',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'stories_background', array(
        'label' => __('Section Stories (Background)', 'light_dose'),
        'section' => 'light_dose_background',
        'settings' => 'light_dose_theme_options[stories_background]',
    )));

    $wp_customize->add_setting('light_dose_theme_options[action_background]', array(
        'default' => get_template_directory_uri() . '/img/background-action.jpg',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'action_background', array(
        'label' => __('Section Action (Background)', 'light_dose'),
        'section' => 'light_dose_background',
        'settings' => 'light_dose_theme_options[action_background]',
    )));

    $wp_customize->add_setting('light_dose_theme_options[pricing_background]', array(
        'default' => get_template_directory_uri() . '/img/background-pricing.jpg',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'pricing_background', array(
        'label' => __('Section Pricing (Background)', 'light_dose'),
        'section' => 'light_dose_background',
        'settings' => 'light_dose_theme_options[pricing_background]',
    )));

    $wp_customize->add_setting('light_dose_theme_options[contacts_background]', array(
        'default' => get_template_directory_uri() . '/img/background-contacts.jpg',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'contacts_background', array(
        'label' => __('Section Contacts (Background)', 'light_dose'),
        'section' => 'light_dose_background',
        'settings' => 'light_dose_theme_options[contacts_background]',
    )));

    $wp_customize->add_setting('light_dose_theme_options[form_email]', array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('light_dose_form_email', array(
        'label' => __('Form Email', 'light_dose'),
        'section' => 'light_dose_contact_form',
        'settings' => 'light_dose_theme_options[form_email]',
    ));

    $wp_customize->add_setting('light_dose_theme_options[copyright]', array(
        'default' => 'Copyright &copy; ItemBridge inc.',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('light_dose_footer', array(
        'label' => __('Copyright', 'light_dose'),
        'section' => 'light_dose_footer',
        'settings' => 'light_dose_theme_options[copyright]',
    ));

    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages();
        foreach ($languages as $language) {
            $wp_customize->add_setting('light_dose_theme_options[title_link_' . $language['language_code'] . ']', array(
                'default' => 'View Our Works',
                'capability' => 'edit_theme_options',
                'type' => 'option',
            ));
            $wp_customize->add_control('light_dose_title_link_' . $language['language_code'] . '', array(
                'label' => sprintf(__('Title Link (%s)', 'light_dose'), $language['translated_name']),
                'section' => 'light_dose_button_home_page',
                'settings' => 'light_dose_theme_options[title_link_' . $language['language_code'] . ']',
            ));
        }
    } else {
        $wp_customize->add_setting('light_dose_theme_options[title_link]', array(
            'default' => 'View Our Works',
            'capability' => 'edit_theme_options',
            'type' => 'option',
        ));
        $wp_customize->add_control('light_dose_title_link', array(
            'label' => __('Title Link', 'light_dose'),
            'section' => 'light_dose_button_home_page',
            'settings' => 'light_dose_theme_options[title_link]',
        ));
    }

    $wp_customize->add_setting('light_dose_theme_options[location_link]', array(
        'default' => '#works',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('light_dose_location_link', array(
        'label' => __('Location Link', 'light_dose'),
        'section' => 'light_dose_button_home_page',
        'settings' => 'light_dose_theme_options[location_link]',
    ));

    $wp_customize->add_setting('light_dose_theme_options[theme_color]', array(
        'default' => '#054383',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'light_dose_theme_color', array(
        'label' => __('Theme Color', 'light_dose'),
        'section' => 'light_dose_theme_options',
        'settings' => "light_dose_theme_options[theme_color]",
        'priority' => 10,
    )));

    $wp_customize->add_setting('light_dose_theme_options[logo_text]', array(
        'default' => 'Light Dose<br />Flat & Minimal',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('light_dose_logo_text', array(
        'label' => __('Logo Text', 'light_dose'),
        'section' => 'light_dose_header',
        'settings' => 'light_dose_theme_options[logo_text]',
    ));

    $wp_customize->add_setting('light_dose_theme_options[throttle]', array(
        'default' => 200,
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('light_dose_throttle', array(
        'label' => __('Delay (ms.)', 'light_dose'),
        'section' => 'light_dose_header',
        'settings' => 'light_dose_theme_options[throttle]',
    ));

    $wp_customize->add_setting('light_dose_theme_options[logo]', array(
        'default' => get_template_directory_uri() . '/img/light-dose-logo.png',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo', array(
        'label' => __('Logo Image', 'light_dose'),
        'section' => 'light_dose_header',
        'settings' => 'light_dose_theme_options[logo]',
    )));

    $wp_customize->add_setting('light_dose_theme_options[blog_title]', array(
        'default' => 'Blog',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('light_dose_blog_title', array(
        'label' => __('Blog Title', 'light_dose'),
        'section' => 'light_dose_blog',
        'settings' => 'light_dose_theme_options[blog_title]',
    ));

    $wp_customize->add_setting('light_dose_theme_options[blog_text]', array(
        'default' => 'All the news of our company is here...',
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control('light_dose_blog_text', array(
        'label' => __('Blog Text', 'light_dose'),
        'section' => 'light_dose_blog',
        'settings' => 'light_dose_theme_options[blog_text]',
    ));

    $wp_customize->add_setting('light_dose_theme_options[blog_per_page]', array(
        'default' => 5,
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new Blog_Per_Page_Custom_Control($wp_customize, 'blog_per_page', array(
        'label' => __('Last Posts Show', 'light_dose'),
        'section' => 'light_dose_blog',
        'settings' => 'light_dose_theme_options[blog_per_page]',
    )));

    $wp_customize->add_setting('light_dose_theme_options[blog_home_per_page]', array(
        'default' => 5,
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new Blog_Per_Page_Custom_Control($wp_customize, 'blog_home_per_page', array(
        'label' => __('Blog Posts Show', 'light_dose'),
        'section' => 'light_dose_blog',
        'settings' => 'light_dose_theme_options[blog_home_per_page]',
    )));


    $wp_customize->add_setting('light_dose_theme_options[font_size]', array(
        'default' => 16,
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new Font_Size_Custom_Control($wp_customize, 'font_size', array(
        'label' => __('Font Size', 'light_dose'),
        'section' => 'light_dose_theme_options',
        'settings' => 'light_dose_theme_options[font_size]',
    )));

    $wp_customize->add_setting('light_dose_theme_options[font_family]', array(
        'default' => "",
        'capability' => 'edit_theme_options',
        'type' => 'option',
    ));

    $wp_customize->add_control(new Font_Family_Custom_Control($wp_customize, 'font_family', array(
        'label' => __('Font Family', 'light_dose'),
        'section' => 'light_dose_theme_options',
        'settings' => 'light_dose_theme_options[font_family]',
    )));
}

add_action('customize_register', 'light_dose_customize_register');

if (class_exists('WP_Customize_Control')) {

    class Blog_Per_Page_Custom_Control extends WP_Customize_Control {

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <select data-customize-setting-link="light_dose_theme_options[<?php echo $this->id; ?>]" id="<?php echo $this->id; ?>" class="postform" name="<?php echo $this->id; ?>">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
            </label>
            <?php
        }

    }

    class Font_Size_Custom_Control extends WP_Customize_Control {

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <div class="font_size_display_result"><?php echo $this->value(); ?></div>
                <input type="range" name="points" min="9" max="72" step="1" style="width:100%;" <?php $this->link(); ?>>
                <script>
                    (function($) {
                        $('input[type="range"]').change(function() {
                            $('.font_size_display_result').text($(this).val());
                        });
                    })(jQuery);
                </script>
            </label>
            <?php
        }

    }

    class Font_Family_Custom_Control extends WP_Customize_Control {

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>                                
                <select data-customize-setting-link="light_dose_theme_options[<?php echo $this->id; ?>]" id="<?php echo $this->id; ?>" class="postform" name="<?php echo $this->id; ?>">
                    <option value="">Default</option>
                    <optgroup label="Default Fonts">
                        <?php foreach (getDefaultFonts() as $font) : ?>
                            <option value="<?php echo $font; ?>"><?php echo $font; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="Google Fonts">
                        <?php foreach ($this->getWebFonts() as $font) : ?>
                            <option value="<?php echo $font; ?>"><?php echo $font; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </label>
            <?php
        }

        private function getWebFonts() {
            $file = get_template_directory() . $this->webFonts;
            $fonts = array();
            if (!is_readable($file)) {
                return $fonts;
            }
            return preg_split('/\r\n|\n|\r/', trim(file_get_contents($file)));
        }

        private $webFonts = '/includes/config/webFontNames.txt';
    }

}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function light_dose_customize_preview_js() {
    wp_enqueue_script('light_dose_customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), '20130508', true);
}

add_action('customize_preview_init', 'light_dose_customize_preview_js');
