<?php

function posts_type() {
    register_post_type('slides', array(
        'labels' => array(
            'name' => __('Slides', 'light_dose'),
            'singular_name' => __('Slide', 'light_dose'),
            'has_archive' => true,
            'add_new' => 'Add New Slide',
            'not_found' => 'Not found.',
            'not_found_in_trash' => 'In the cart slides found',
            'add_new_item' => 'Add New Slide'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array(
            'title',
            'thumbnail',
            'editor',
            'page-attributes',
        ),
    ));

    register_post_type('team', array(
        'labels' => array(
            'name' => __('Team', 'light_dose'),
            'singular_name' => __('Members Item', 'light_dose'),
            'all_items' => __('Members List', 'light_dose'),
            'add_new' => 'Add New',
            'not_found' => 'Not team members found.',
            'not_found_in_trash' => 'Not team members found in Trash',
            'add_new_item' => 'Add New Team Member'
        ),
        'show_in_nav_menus' => false,
        'public' => true,
        'has_archive' => true,
        'supports' => array(
            'title',
            'thumbnail',
            'editor',
            'page-attributes',
        ),
    ));

    register_post_type('works', array(
        'labels' => array(
            'name' => __('Works', 'light_dose'),
            'singular_name' => __('Work', 'light_dose'),
            'all_items' => __('Work List', 'light_dose'),
            'has_archive' => true,
            'add_new' => 'Add New',
            'not_found' => 'Not found.',
            'not_found_in_trash' => 'In the cart slides found',
            'add_new_item' => 'Add New Work'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array(
            'title',
            'thumbnail',
            'editor',
            'page-attributes',
        ),
    ));

    register_post_type('stories', array(
        'labels' => array(
            'name' => __('Stories', 'light_dose'),
            'singular_name' => __('Story Item', 'light_dose'),
            'all_items' => __('List', 'light_dose'),
            'add_new' => 'Add New Story',
            'not_found' => 'Not found.',
            'not_found_in_trash' => 'Not Item found in Trash',
            'add_new_item' => 'Add New Story'
        ),
        'show_in_nav_menus' => false,
        'public' => true,
        'supports' => array(
            'title',
            'thumbnail',
            'editor',
            'page-attributes',
        ),
    ));

    register_post_type('faq', array(
        'labels' => array(
            'name' => __('Faq', 'light_dose'),
            'singular_name' => __('Faq Item', 'light_dose'),
            'all_items' => __('Faq List', 'light_dose'),
            'add_new' => 'Add New',
            'not_found' => 'Not found.',
            'not_found_in_trash' => 'Not Item found in Trash',
            'add_new_item' => 'Add New Item'
        ),
        'show_in_nav_menus' => false,
        'public' => true,
        'supports' => array(
            'title',
            'editor',
            'page-attributes',
        ),
    ));

    register_post_type('price', array(
        'labels' => array(
            'name' => __('Price', 'light_dose'),
            'singular_name' => __('Price Item', 'light_dose'),
            'all_items' => __('Price List', 'light_dose'),
            'add_new' => 'Add New',
            'not_found' => 'Not found.',
            'not_found_in_trash' => 'Not Item found in Trash',
            'add_new_item' => 'Add New Item'
        ),
        'show_in_nav_menus' => false,
        'public' => true,
        'supports' => array(
            'title',
            'editor',
            'page-attributes',
        ),
    ));
}

add_action('init', 'posts_type');

register_taxonomy('works-category', 'works', array(
    'label' => __('Works Categories', 'precise'),
    'show_in_nav_menus' => false,
));

function add_light_dose_meta_box() {
    add_meta_box('team_social', __('Social Link', 'light_dose'), 'team_social', 'team');
    add_meta_box('team_job', __('Job Title', 'light_dose'), 'team_job', 'team');
    add_meta_box('team_animate', __('Animate', 'light_dose'), 'team_animate', 'team');
    add_meta_box('works_client', __('Client', 'light_dose'), 'works_client', 'works', 'side');
    add_meta_box('works_link', __('Project Link', 'light_dose'), 'works_link', 'works', 'side');
    add_meta_box('works_text_hover', __('Text Hover', 'light_dose'), 'works_text_hover', 'works', 'side');
    add_meta_box('works_gallery', __('Project Gallery', 'light_dose'), 'works_gallery', 'works');
    add_meta_box('works_video', __('Video Link', 'light_dose'), 'works_video', 'works', 'side');
    add_meta_box('stories_client', __('Client', 'light_dose'), 'stories_client', 'stories');
    add_meta_box('stories_company', __('Company', 'light_dose'), 'stories_company', 'stories');
    add_meta_box('price_icon', __('Icon', 'light_dose'), 'price_icon', 'price');
    add_meta_box('price_color', __('Background Color', 'light_dose'), 'price_color', 'price');
    add_meta_box('price_link', __('Hyperlink', 'light_dose'), 'price_link', 'price');
    add_meta_box('custom_gallery', __('Gallery', 'light_dose'), 'custom_gallery', 'page');
    add_meta_box('ve_page_text_color', __('Text Color', 'light_dose'), 've_page_text_color', 'page', 'side', 'high');
    add_meta_box('ve_page_background_pattern', __('Background Pattern', 'light_dose'), 've_page_background_pattern', 'page', 'side', 'high');
    add_meta_box('ve_page_background_image', __('Background Image', 'light_dose'), 've_page_background_image', 'page', 'side', 'high');
    add_meta_box('ve_page_background_color', __('Background Color', 'light_dose'), 've_page_background_color', 'page', 'side', 'high');
    add_meta_box('ve_page_overlay', __('Overlay', 'light_dose'), 've_page_overlay', 'page', 'side', 'high');
    add_meta_box('ve_page_background_transparent', __('Background Properties', 'light_dose'), 've_page_background_transparent', 'page', 'side', 'high');
    add_meta_box('ve_page_background_blur', __('Background Blur', 'light_dose'), 've_page_background_blur', 'page', 'side', 'high');
}

add_action('admin_init', 'add_light_dose_meta_box');


/*
 * Page Background Blur Meta Box Input
 */

function ve_page_background_blur($post) {
    $page_background_blur = get_post_meta($post->ID, 'page_background_blur', true);
    ?>
    <select name="page_background_blur" id="page_blur_select">
        <?php for ($i = 0; $i <= 100; $i++) : ?>
        <option value="<?php echo $i; ?>"<?php if($i == $page_background_blur) : ?> selected="selected"<?php endif; ?>><?php echo $i; ?></option>
        <?php endfor; ?>
    </select>     

    <?php
}

/*
 * Page Background Transparent Meta Box Input
 */

function ve_page_background_transparent($post) {
    $page_background_transparent = get_post_meta($post->ID, 'page_background_transparent', true);
    ?>
    <label for="background_transparent">
        <input type="checkbox" name="page_background_transparent" id="background_transparent" value="<?php echo $page_background_transparent ? 1 : 0; ?>" <?php echo $page_background_transparent ? ' checked="checked"' : ''; ?>/>
        <?php echo _e('Transparent', 'light_dose'); ?>
    </label>
    <?php
}

/*
 * Page Overlay Meta Box Input
 */

function ve_page_overlay($post) {
    $page_overlay = get_post_meta($post->ID, 'page_overlay', true);
    if (empty($page_overlay)) {
        $page_overlay = 0;
    }
    $color = get_post_meta($post->ID, 'page_overlay_color', true);
    if (empty($color)) {
        $color = '#000000';
    }
    ?>
    <input type="hidden" name="page_overlay" id="page_overlay_value" value="<?php echo $page_overlay; ?>">  
    <div class="slider_display_result"><?php echo $page_overlay; ?></div>
    <div id="page_overlay_slider"></div>
    <input type="text" name="page_overlay_color" class="wp-color-picker-overlay"  data-default-color="#000000" value="<?php echo $color; ?>">
    <?php
}

/*
 * Page Background Color Meta Box Input
 */

function ve_page_text_color($post) {
    $color = get_post_meta($post->ID, 'page_text_color', true);
    if (empty($color)) {
        $color = '#000000';
    }
    ?>
    <input type="text" name="page_text_color" class="wp-color-picker-text"  data-default-color="#000000" value="<?php echo $color; ?>">    
    <?php
}

/*
 * Page Background Pattern Meta Box Input
 */

function ve_page_background_pattern($post) {
    $pattern = get_post_meta($post->ID, 'page_background_pattern', true);
    ?>    
    <input type="hidden" name="template-path" id="template-path" value="<?php echo get_template_directory_uri(); ?>" />
    <select id="background-pattern" name="page_background_pattern">
        <option value="0">None</option>
        <?php
        get_background_patterns($pattern);
        ?>
    </select>    
    <?php
}

/*
 * Page Background Image Meta Box Input
 */

function ve_page_background_image($post) {
    $id = get_post_meta($post->ID, 'background_image_id', true);
    $image = wp_get_attachment_image_src($id, 'full');
    $thumbnail = wp_get_attachment_image($id, 'medium');
    if ($id) :
        ?>    
        <a href="#" id="set_background_image" class="set_background_image"><?php echo $thumbnail; ?></a>
        <a href="#" id="remove_custom_background_image" class="remove_custom_background_image"><?php _e('Remove Background Image', 'light_dose'); ?></a>
    <?php else: ?>
        <a href="#" id="set_background_image" class="set_background_image"><?php _e('Set Background Image', 'light_dose'); ?></a>
        <a href="#" id="remove_custom_background_image" class="remove_custom_background_image"><?php _e('Remove Background Image', 'light_dose'); ?></a>
    <?php endif; ?>
    <input type="hidden" name="background_image" id="background_image" value="<?php echo $image[0]; ?>" />
    <input type="hidden" name="background_image_id" id="background_image_id" value="<?php echo $id; ?>" />
    <input type="hidden" name="template-path" id="template-path" value="<?php echo get_template_directory_uri(); ?>" />        
    <?php
}

/*
 * Page Background Color Meta Box Input
 */

function ve_page_background_color($post) {
    $color = get_post_meta($post->ID, 'page_background_color', true);
    if (empty($color)) {
        $color = '#ffffff';
    }
    ?>
    <input type="text" name="page_background_color" class="wp-color-picker-background"  data-default-color="#ffffff" value="<?php echo $color; ?>">    
    <?php
}

/**
 * Animate Image
 */
function team_animate($post) {
    $value = get_post_meta($post->ID, 'team_animate', true);
    ?>
    <select name="team_animate" class="team_custom_input">
        <option value="0"<?php echo $value == 0 ? ' selected="selected"' : ''; ?>>None</option>
        <option value="1"<?php echo $value == 1 ? ' selected="selected"' : ''; ?>>Rotate</option>
    </select>    
    <?php
}

/*
 * Works Gllery
 */

function works_gallery($post) {
    $meta = get_post_meta($post->ID, 'portfolio_gallery', true);
    $json = json_decode(html_entity_decode($meta), true);
    ?>
    <input type="hidden" id="portfolio_gallery" name="portfolio_gallery" value="<?php echo $meta; ?>">
    <a href="#" id="add_gallery_img" class="button button-primary button-large"><?php _e('Add Gallery Image', 'light_dose'); ?></a>
    <ul id="gallery_list">        
        <?php if (!empty($json)) : ?>
            <?php foreach ($json as $item) : ?>
                <li data-img-id="<?php echo $item['id'] ?>">
                    <a href="#" class="remove_gallery_img button"><?php echo __('Remove', 'light_dose'); ?></a>
                    <?php echo wp_get_attachment_image($item['id'], 'works-small'); ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    <?php
}

/*
 * Page Gllery
 */

function custom_gallery($post) {
    $meta = get_post_meta($post->ID, 'page_portfolio_gallery', true);
    $json = json_decode(html_entity_decode($meta), true);
    ?>
    <input type="hidden" id="custom_gallery" name="page_portfolio_gallery" value="<?php echo $meta; ?>">
    <a href="#" id="add_gallery_img" class="button button-primary button-large"><?php _e('Add Gallery Image', 'light_dose'); ?></a>
    <ul id="gallery_list">        
        <?php if (!empty($json)) : ?>
            <?php foreach ($json as $item) : ?>
                <li data-img-id="<?php echo $item['id'] ?>">
                    <a href="#" class="remove_gallery_img button"><?php echo __('Remove', 'light_dose'); ?></a>
                    <?php echo wp_get_attachment_image($item['id'], 'about-small'); ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    <?php
}

function getImage() {
    echo '<li data-img-id="' . $_POST['img_id'] . '">';
    echo '<a href="#" class="remove_gallery_img button">' . __('Remove', 'light_dose') . '</a>';
    echo wp_get_attachment_image($_POST['img_id'], 'works-small');
    echo '</li>';
    die();
}

add_action('wp_ajax_light_dose_getImage', 'getImage');
add_action('wp_ajax_nopriv_light_dose_getImage', 'getImage');


/*
 * Price Link Meta Box Input
 */

function price_link($post) {
    ?>
    <input type="text" name="price_link" class="price_custom_input" value="<?php echo get_post_meta($post->ID, 'price_link', true) ?>">
    <?php
}

/*
 * Price Background Color Meta Box Input
 */

function price_color($post) {
    $color = get_post_meta($post->ID, 'price_color', true);
    if (empty($color)) {
        $color = '#ffffff';
    }
    ?>
    <input type="text" name="price_color"class="wp-color-picker-background"  data-default-color="<?php echo $color; ?>" value="<?php echo $color; ?>">    
    <?php
}

/*
 * Price Icon Meta Box Input
 */

function price_icon($post) {
    $file = get_template_directory() . '/includes/config/icons.json';
    $json = json_decode(file_get_contents($file), true);
    $price_icon = get_post_meta($post->ID, 'price_icon', true);
    ?>
    <div class="service_icon">
        <input type="hidden" name="price_icon" class="icon_selected" value="<?php echo $price_icon; ?>">    
        <?php if ($price_icon && !empty($price_icon)) : ?>
            <div class="active_icon"><i class="fa <?php echo $price_icon; ?>"></i> <span class="i-name"><?php echo $price_icon; ?></span></div>
        <?php else : ?>
            <div class="active_icon"><?php echo __('Select Icon', 'light_dose'); ?></div>
        <?php endif; ?>
        <div class="icon_list">
            <ul>
                <?php
                asort($json['icons']);
                foreach ($json['icons'] as $id => $icon) :
                    ?>
                    <li><i class="fa fa-<?php echo $id; ?>"></i><span class="i-name"> fa-<?php echo $id; ?></span></li>
                    <?php
                endforeach;
                ?>
            </ul>
        </div>
    </div>
    <?php
}

/*
 * Stories Client Meta Box Input
 */

function stories_client($post) {
    ?>
    <input type="text" name="stories_client" class="stories_custom_input" value="<?php echo get_post_meta($post->ID, 'stories_client', true) ?>">
    <?php
}

/*
 * Stories Company Meta Box Input
 */

function stories_company($post) {
    ?>
    <input type="text" name="stories_company" class="stories_custom_input" value="<?php echo get_post_meta($post->ID, 'stories_company', true) ?>">
    <?php
}

/*
 * Team Job Title Meta Box Input
 */

function team_job($post) {
    ?>
    <input type="text" name="team_job" class="team_custom_input" value="<?php echo get_post_meta($post->ID, 'team_job', true) ?>">
    <?php
}

/*
 * Works Client Meta Box Input
 */

function works_client($post) {
    ?>
    <input type="text" name="works_client" class="works_custom_input" value="<?php echo get_post_meta($post->ID, 'works_client', true) ?>">
    <?php
}

/*
 * Works Client Meta Box Input
 */

function works_video($post) {
    ?>
    <input type="text" name="works_video" class="works_custom_input" value="<?php echo get_post_meta($post->ID, 'works_video', true) ?>">
    <?php
}

/*
 * Works Link Meta Box Input
 */

function works_link($post) {
    ?>
    <input type="text" name="works_link" class="works_custom_input" value="<?php echo get_post_meta($post->ID, 'works_link', true) ?>">
    <?php
}

/*
 * Works Video Link Meta Box Input
 */

function works_text_hover($post) {
    ?>
    <input type="text" name="works_text_hover" class="works_custom_input" value="<?php echo get_post_meta($post->ID, 'works_text_hover', true) ?>">
    <?php
}

function team_social($post) {
    ?>
    <input id="social_team_input" type="hidden" name="team_social" value="<?php echo get_post_meta($post->ID, 'team_social', true); ?>" />
    <select class="team_social_select">
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
    </select>
    <input type="text" class="team_social_link" />
    <a href="#" id="team_social_add" class="button button-primary button-large"><?php _e('Add', 'light_dose'); ?></a>
    <div class="team_social_preview">
        <ul class="">
            <?php
            if (get_post_meta($post->ID, 'team_social', true) !== '') {
                echo do_shortcode(get_post_meta($post->ID, 'team_social', true));
            }
            ?>
        </ul>
    </div>
    <?php
}

/**
 * 
 * @param string $path Checking current background pattern
 */
function get_background_patterns($path) {
    $patterns = get_template_directory() . '/img/patterns';
    $extension = ".png";
    if (is_dir($patterns)) {
        foreach (glob($patterns . '/*' . $extension) as $pattern) {
            $image = 'img/patterns/' . basename($pattern);
            $title = ucfirst(str_replace("_", " ", basename($pattern, $extension)));
            $selected = $image == $path ? ' selected="selected"' : '';
            echo '<option value="' . $image . '"' . $selected . '>' . $title . '</option>';
        }
    }
}

/*
 * Save Meta Box
 */

function save_meta_box($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    if (!current_user_can('edit_post', $post_id))
        return $post_id;

    $post = get_post($post_id);
    if ($post->post_type == 'team') {
        $social = !empty($_POST['team_social']) ? $_POST['team_social'] : '';
        $job = !empty($_POST['team_job']) ? $_POST['team_job'] : '';
        $animate = !empty($_POST['team_animate']) ? $_POST['team_animate'] : '';
        update_post_meta($post_id, 'team_social', esc_attr($social));
        update_post_meta($post_id, 'team_job', esc_attr($job));
        update_post_meta($post_id, 'team_animate', esc_attr($animate));
    } elseif ($post->post_type == 'works') {
        $category = !empty($_POST['works_category']) ? $_POST['works_category'] : '';
        $client = !empty($_POST['works_client']) ? $_POST['works_client'] : '';
        $link = !empty($_POST['works_link']) ? $_POST['works_link'] : '';
        $video = !empty($_POST['works_video']) ? $_POST['works_video'] : '';
        $works_text_hover = !empty($_POST['works_text_hover']) ? $_POST['works_text_hover'] : '';
        $gallery = !empty($_POST['portfolio_gallery']) ? $_POST['portfolio_gallery'] : get_post_meta($post_id, 'portfolio_gallery', true);
        update_post_meta($post_id, 'works_category', esc_attr($category));
        update_post_meta($post_id, 'works_client', esc_attr($client));
        update_post_meta($post_id, 'works_link', esc_attr($link));
        update_post_meta($post_id, 'works_video', esc_attr($video));
        update_post_meta($post_id, 'works_text_hover', esc_attr($works_text_hover));
        update_post_meta($post_id, 'portfolio_gallery', esc_attr($gallery));
    } elseif ($post->post_type == 'stories') {
        $client = !empty($_POST['stories_client']) ? $_POST['stories_client'] : '';
        $company = !empty($_POST['stories_company']) ? $_POST['stories_company'] : '';
        update_post_meta($post_id, 'stories_client', esc_attr($client));
        update_post_meta($post_id, 'stories_company', esc_attr($company));
    } elseif ($post->post_type == 'price') {
        $icon = !empty($_POST['price_icon']) ? $_POST['price_icon'] : '';
        $color = !empty($_POST['price_color']) ? $_POST['price_color'] : '';
        $link = !empty($_POST['price_link']) ? $_POST['price_link'] : '';
        update_post_meta($post_id, 'price_icon', esc_attr($icon));
        update_post_meta($post_id, 'price_color', esc_attr($color));
        update_post_meta($post_id, 'price_link', esc_attr($link));
    } elseif ($post->post_type == 'page') {
        $color = !empty($_POST['page_background_color']) ? $_POST['page_background_color'] : '';
        $text_color = !empty($_POST['page_text_color']) ? $_POST['page_text_color'] : '';
        $pattern = !empty($_POST['page_background_pattern']) ? $_POST['page_background_pattern'] : '';
        update_post_meta($post_id, 'page_background_color', esc_attr($color));
        update_post_meta($post_id, 'page_text_color', esc_attr($text_color));
        update_post_meta($post_id, 'page_background_pattern', esc_attr($pattern));
        $gallery = !empty($_POST['page_portfolio_gallery']) ? $_POST['page_portfolio_gallery'] : get_post_meta($post_id, 'page_portfolio_gallery', true);
        update_post_meta($post_id, 'page_portfolio_gallery', esc_attr($gallery));
        $overlay = !empty($_POST['page_overlay']) ? $_POST['page_overlay'] : '';
        update_post_meta($post_id, 'page_overlay', esc_attr($overlay));
        $overlay_color = !empty($_POST['page_overlay_color']) ? $_POST['page_overlay_color'] : '';
        update_post_meta($post_id, 'page_overlay_color', esc_attr($overlay_color));
        $background_image = !empty($_POST['background_image']) ? $_POST['background_image'] : '';
        update_post_meta($post_id, 'background_image', esc_attr($background_image));
        $background_image_id = !empty($_POST['background_image_id']) ? $_POST['background_image_id'] : '';
        update_post_meta($post_id, 'background_image_id', esc_attr($background_image_id));
        $background_transparent = isset($_POST['page_background_transparent']) ? 1 : 0;
        update_post_meta($post_id, 'page_background_transparent', esc_attr($background_transparent));
        $background_blur = isset($_POST['page_background_blur']) ? $_POST['page_background_blur'] : 0;
        update_post_meta($post_id, 'page_background_blur', esc_attr($background_blur));
    }
    return $post_id;
}

add_action('save_post', 'save_meta_box');

/*
 * Social Icon Shortcode
 */

function social_shortcode($attr) {
    extract(shortcode_atts(array(
        'href' => '',
        'type_icon' => ''
                    ), $attr));
    return '<li><a href="' . $href . '" target="_blank"><i class="icon-' . $type_icon . '"></i></a></li>';
}

add_shortcode('social', 'social_shortcode');
