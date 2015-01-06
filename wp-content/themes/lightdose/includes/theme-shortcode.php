<?php
$all_pages = light_dose_get_pages();

function light_dose_slides_shortcode() {
    $light_dose_options = get_option('theme_light_dose_options');
    $light_dose_options_theme = get_option('light_dose_theme_options');
    $args = array(
        'post_type' => 'slides',
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'posts_per_page' => -1,
        'order' => 'ASC'
    );
    $posts = new WP_Query($args);

    $result = '';
    $slides = array();

    if ($posts->have_posts()) {
        foreach ($posts->posts as $key => $slide) {
            $image_id = get_post_thumbnail_id($slide->ID);
            $src = wp_get_attachment_image_src($image_id, 'full');
            $slides[$key] = array(
                'src' => $src,
                'title' => $slide->post_title,
                'text' => $slide->post_content,
            );
        }
        $header = array(
            'title' => count($slides) ? $slides[0]['title'] : 'Light Dose',
            'text' => count($slides) ? $slides[0]['text'] : 'Light Dose',
        );
        $result .= '<article class="slider theme-color foreground">
			<div class="text-center" id="slider">
                            <div class="inner-wrapper stretch-both"><div>
                                <div class="scale-down">
                                    <h1 class="data-short">';

        $result .= $header['title'];
        $result .= '</h1>
                    <h4 class="data-full">';
        $result .= $header['text'];
        $result .= '</h4>
                        </div>';
        if (!empty($light_dose_options_theme['location_link'])) {
            if (function_exists('icl_get_languages')) {
                $result .= '<a class="btn scroll" href="' . $light_dose_options_theme['location_link'] . '">' . $light_dose_options_theme['title_link_' . ICL_LANGUAGE_CODE] . '</a>';
            } else {
                $result .= '<a class="btn scroll" href="' . $light_dose_options_theme['location_link'] . '">' . $light_dose_options_theme['title_link'] . '</a>';
            }
        }
        $result .= '</div>
                </div>';

        if (empty($light_dose_options['render_header']) || $light_dose_options['render_header'] == 'slides') {
            foreach ($slides as $key => $slide) {
                $result .= '<img class="slide" src="' . $slide['src'][0] . '" data-short="' . $slide['title'] . '" data-full="' . $slide['text'] . '" alt="' . $slide['text'] . '" />';
            }
        } else {
            if (!$light_dose_options['video_bg']) {
                $result .= '<video class="slide" muted="muted" loop="loop" preload="" autoplay="autoplay" data-short="' . $header['title'] . '" data-full="' . $header['text'] . '">';
                if (!empty($light_dose_options['video_webm'])) {
                    $result .= '<source src="' . $light_dose_options['video_webm'] . '" type="video/webm" />';
                }
                if (!empty($light_dose_options['video_mp4'])) {
                    $result .= '<source src="' . $light_dose_options['video_mp4'] . '" type="video/mp4" />';
                }
                if (!empty($light_dose_options['video_ogg'])) {
                    $result .= '<source src="' . $light_dose_options['video_ogg'] . '" type="video/ogg" />';
                }
                $result .= '<img class="fallback" src="' . $light_dose_options['video_poster'] . '" alt="video" />';
                $result .= '</video>';
            }
            if (count($slides) > 1) {
                foreach ($slides as $key => $slide) {
                    $result .= '<div class="slide" data-short="' . $slide['title'] . '" data-full="' . $slide['text'] . '"></div>';
                }
            }
        }
        $result .= '</div>
		</article>';
    }
    return $result;
}

add_shortcode('slides', 'light_dose_slides_shortcode');

function light_dose_inline_block_fact_shortcode($atts, $content) {
    $result = '';
    if (!is_front_page()) {
        $result .= '<article class="facts page-light-dose theme-white background foreground links" id="facts">
                    <div class="container text-center">
                        <h5>Facts</h5>
                        <div class="row">';
    }
    $result .= '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <span class="fa ' . $atts['icon'] . '" data-color="' . $atts['color'] . '"></span>
                    <span class="counter">
                        <span class="value">' . $atts['value'] . '</span>
                        <small>' . $atts['text'] . '</small>
                    </span>
                </div>';
    if (!is_front_page()) {
        $result .= '</div>
                 </div>
              </article>';
    }
    return $result;
}

add_shortcode('inline_block_fact', 'light_dose_inline_block_fact_shortcode');

function light_dose_about_shortcode($atts, $content) {
    $post = isset($GLOBALS['post_id']) ? $GLOBALS['post_id'] : get_slug();
    $page = light_dose_get_page_by_post_name($post);
    $custom_background = set_custom_background(is_object($page) ? $page->ID : null);
    $class = get_post_meta($page->ID, 'class', true);

    $result = '';
    $result .= '<article class="about page-light-dose theme-white foreground links background ' . $class . '" id="' . $page->post_name . '"' . $custom_background['style'] . '>
                    <div class="underlay"' . (!$custom_background['blur'] ? $custom_background['overlay'] : '') . '>';
    if ($custom_background['blur']) {
        $result .= '<div blur="' . $custom_background['blur'] . '"></div>';
        $result .= '<div class="overlay"' . $custom_background['overlay'] . '></div>';
    }
    //$result .= '<div class="overlay"' . $custom_background['overlay'] . '></div>';
    $result .= light_dose_header(isset($page->post_title) ? $page->post_title : __('About', 'light_dose'));
    $result .= '<div class="container">
                   <div class="row">';
    $result .= do_shortcode($content);
    $result .= '</div>
            </div>
          </div>
	</article>';

    return $result;
}

add_shortcode('about', 'light_dose_about_shortcode');

function light_dose_inline_block_shortcode($atts, $content) {
    return '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <span class="caption color">' . $atts['title'] . '</span>
                        ' . do_shortcode($content) . '
            </div>';
}

add_shortcode('inline_block', 'light_dose_inline_block_shortcode');

function light_dose_about_gallery_shortcode() {
    $post = isset($GLOBALS['post_id']) ? $GLOBALS['post_id'] : get_slug();
    $page = light_dose_get_page_by_post_name($post);
    $result = '';
    if (!is_null($page)) {
        $gallery = get_post_meta($page->ID, 'page_portfolio_gallery', true);
        $attachments = json_decode(html_entity_decode($gallery), true);
        if (count($attachments)) {
            $result .= '<div class="blueimp col-lg-3 col-md-3 col-sm-6 col-xs-12 masonry">';
            foreach ($attachments as $image) {
                $original = wp_get_attachment_image_src($image['id'], 'about-large');
                $preview = wp_get_attachment_image_src($image['id'], 'about-small');
                $result .= '<a href="' . $original[0] . '" title="">
                                <img src="' . $preview[0] . '" alt="" />
                                <div class="overlay"></div>
                        </a>';
            }
            $result .= '</div>';
        }
    }
    return $result;
}

add_shortcode('images', 'light_dose_about_gallery_shortcode');

function light_dose_inline_block_skill_shortcode($atts, $content) {
    return '<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="' . $atts['now'] . '" aria-valuemin="' . $atts['min'] . '" aria-valuemax="' . $atts['max'] . '" data-color="' . $atts['color'] . '">
                       <span class="sr-only">' . $atts['text'] . '</span>
                    </div>
                </div>
            </div>';
}

add_shortcode('inline_block_skill', 'light_dose_inline_block_skill_shortcode');

function light_dose_stories_shortcode($atts) {
    $args = array(
        'post_type' => 'stories',
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'posts_per_page' => -1,
        'order' => 'DESC'
    );
    $post = isset($GLOBALS['post_id']) ? $GLOBALS['post_id'] : get_slug();
    $page = light_dose_get_page_by_post_name($post);
    $class = get_post_meta($page->ID, 'class', true);

    $items = new WP_Query($args);

    if ($items->have_posts()) {
        $custom_background = set_custom_background(is_object($page) ? $page->ID : null);
        $result = '';
        $result .= '<article class="stories page-light-dose theme-color background foreground links ' . $class . '" id="' . $page->post_name . '"' . $custom_background['style'] . '>
			<div class="underlay"' . ($custom_background['blur'] ? $custom_background['overlay'] : '') . '>';
        if ($custom_background['blur']) {
            $result .= '<div blur="' . $custom_background['blur'] . '"></div>';
            $result .= '<div class="overlay"' . $custom_background['overlay'] . '></div>';
        }
        $result .= '<header class="text-center">
                            <div class="sect">
                                    <div class="line line-left"></div>
                                    &sect;
                                    <div class="line line-right"></div>
                            </div>
                            <h2>' . $page->post_title . '</h2>
                            <h5>' . $atts['description'] . '</h5>
                    </header>
                    <div class="container">
                            ' . light_dose_controls(count($items->posts)) . '
                            <div class="row">
                                    <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
                                            <div class="caroufredsel enable-swipe enable-controls enable-pagination image-pagination">';

        foreach ($items->posts as $item) {
            $client = get_post_custom_values('stories_client', $item->ID);
            $company = get_post_custom_values('stories_company', $item->ID);
            $data_image = wp_get_attachment_url(get_post_thumbnail_id($item->ID, 'story-small'));
            $result .= '<div class="row" data-image=" ' . $data_image . ' ">
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-left text-right-md">';
            if (!empty($client[0])) {
                $result .= '<div><strong>' . __('Client', 'light_dose') . '</strong></div><div>' . $client[0] . '</div>';
            }
            if (!empty($company[0])) {
                $result .= '<em>' . isset($company[0]) ? $company[0] : '' . '</em>';
            }
            $result .= '</div>
                                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">' . $item->post_content . '</div>
                                <div class="clearfix"></div>
                        </div>';
        }

        $result .= '</div>
						</div>
					</div>
					<ul class="pagination grid list-inline text-center">
					</ul>
				</div>
			</div>
		</article>';

        return $result;
    }
}

add_shortcode('stories', 'light_dose_stories_shortcode');

function light_dose_last_posts_shortcode() {

    $light_dose_options = get_option('light_dose_theme_options');
    $posts_per_page = !isset($light_dose_options['blog_per_page']) ? get_option('posts_per_page') : (int) $light_dose_options['blog_per_page'];
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'orderby' => 'id',
        'order' => 'DESC',
        'posts_per_page' => $posts_per_page,
    );
    $post = isset($GLOBALS['post_id']) ? $GLOBALS['post_id'] : get_slug();
    $page = light_dose_get_page_by_post_name($post);
    $items = new WP_Query($args);

    if ($items->have_posts()) {
        $custom_background = set_custom_background(is_object($page) ? $page->ID : null);
        $class = get_post_meta($page->ID, 'class', true);
        $result = '';
        $result .= '<article class="page-light-dose theme-white background foreground links ' . $class . '" id="' . $page->post_name . '"' . $custom_background['style'] . '>
                        <div class="underlay"' . (!$custom_background['blur'] ? $custom_background['overlay'] : '') . '>';
        if ($custom_background['blur']) {
            $result .= '<div blur="' . $custom_background['blur'] . '"></div>';
            $result .= '<div class="overlay"' . $custom_background['overlay'] . '></div>';
        } else {
            $result .= '<div class="overlay"></div>';
        }
        $result .= light_dose_header(isset($page->post_title) ? $page->post_title : __('Last Posts', 'light_dose')) . '
                            <div class="container">
                                    ' . light_dose_controls(count($items->posts)) . '
                                    <div class="row">
                                            <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
                                                    <div class="caroufredsel enable-swipe enable-controls">';

        foreach ($items->posts as $item) {
            $user = get_userdata($item->post_author);
            $result .= '<div class="row">
                            <div class="col-lg-offset-2 col-lg-10 col-md-offset-2 col-md-10 col-sm-12 col-xs-12">
                                    <a href="' . get_permalink($item->ID) . '">
                                            <h5>' . $item->post_title . '</h5>
                                    </a>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-left text-right-md">
                                    <div>
                                            <small class="date">' . get_the_time(get_option('date_format'), $item->ID) . '</small>
                                    </div>
                                    <div>
                                            <small class="author">
                                                    ' . __('written by', 'light_dose') . ' ' . $user->display_name . '
                                            </small>
                                    </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">';
            $result .= wp_get_attachment_image(get_post_thumbnail_id($item->ID), 'post-attachment', false, array('class' => 'img-responsive', 'alt' => $item->post_title));
            $content = reset(explode('<!--more-->', $item->post_content));
            $result .= '<p>' . nl2br($content) . '</p>
                                    <a class="more" href="' . get_permalink($item->ID) . '">
                                            ' . __('Read More', 'light_dose') . '
                                            <div class="arrow">
                                                    <span class="tip"></span>
                                            </div>
                                    </a>
                            </div>
                    </div>';
        }

        $result .= '</div>
					</div>
				</div>
			</div>
                    </div>
		</article>';


        return $result;
    }
}

add_shortcode('last_posts', 'light_dose_last_posts_shortcode');

function light_dose_action_content_shortcode($atts, $content) {
    return '<div class="col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10 col-sm-offset-1 col-sm-10 col-xs-12">
                <p>
                    ' . do_shortcode($content) . '
                </p>
            </div>';
}

add_shortcode('content', 'light_dose_action_content_shortcode');

function light_dose_button_shortcode($atts, $content) {
    $target = null;
    if (!empty($atts['target'])) {
        $target = ' target="' . $atts['target'] . '"';
    }
    return '<div class="col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10 col-sm-offset-1 col-sm-10 col-xs-12 text-center">
                <a class="scroll btn" href="' . $atts['href'] . '"' . $target . '>
                    ' . $atts['text'] . '
                </a>
            </div>';
}

add_shortcode('button', 'light_dose_button_shortcode');

function oddity($value, $key, $result) {
    array_push($result[$key & 1], $value);
}

function light_dose_faq_shortcode() {

    $args = array(
        'post_type' => 'faq',
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'posts_per_page' => -1,
        'order' => 'DESC'
    );
    $post = isset($GLOBALS['post_id']) ? $GLOBALS['post_id'] : get_slug();
    $page = light_dose_get_page_by_post_name($post);
    $items = new WP_Query($args);

    if ($items->have_posts()) {
        $custom_background = set_custom_background(is_object($page) ? $page->ID : null);
        $class = get_post_meta($page->ID, 'class', true);
        $result = '';
        $result .= '<article class="' . $class . ' page-light-dose theme-white background foreground links" id="' . $page->post_name . '"' . $custom_background['style'] . '>
                        <div class="underlay"' . (!$custom_background['blur'] ? $custom_background['overlay'] : '') . '>';
        if ($custom_background['blur']) {
            $result .= '<div blur="' . $custom_background['blur'] . '"></div>';
            $result .= '<div class="overlay"' . $custom_background['overlay'] . '></div>';
        } else {
            $result .= '<div class="overlay"></div>';
        }
        $result .= light_dose_header(isset($page->post_title) ? $page->post_title : __('Faq', 'light_dose')) . '
                                <div class="container">
                                    <div class="row">';

        $even = $odd = array();
        array_walk($items->posts, "oddity", array(&$even, &$odd));
        $posts = array($even, $odd);

        for ($i = 0; $i < count($posts); $i++) {
            $result .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">';

            foreach ($posts[$i] as $key => $item) {
                $control = $key == 0 && $i == 0 ? ' open' : '';
                $result .= '<div class="accordion' . $control . '">
                                    <div class="caption-wrapper">
                                            <span class="caption">' . $item->post_title . '</span>
                                            <div class="line"></div>
                                            <span class="sign pull-right"></span>
                                    </div>
                                    <div class="contents">' . $item->post_content . '</div>
                            </div>';
            }

            $result .= '</div>';
        }

        $result .= '</div>
		</div>
            </div>
	</article>';

        return $result;
    }
}

add_shortcode('faq', 'light_dose_faq_shortcode');

function light_dose_price_shortcode($atts) {
    $args = array(
        'post_type' => 'price',
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'posts_per_page' => -1,
        'order' => 'ASC'
    );
    $post = isset($GLOBALS['post_id']) ? $GLOBALS['post_id'] : get_slug();
    $page = light_dose_get_page_by_post_name($post);
    $custom = get_post_custom($page->ID);
    $class = null;
    if (isset($custom['class']) && isset($custom['class'][0])) {
        $class = $custom['class'][0];
    }

    $items = new WP_Query($args);

    if ($items->have_posts()) {
        $custom_background = set_custom_background(is_object($page) ? $page->ID : null);
        $class = get_post_meta($page->ID, 'class', true);
        $result = '';
        $result .= '<article class="' . $class . ' page-light-dose theme-color background foreground links" id="' . $page->post_name . '"' . $custom_background['style'] . '>
			<div class="underlay"' . (!$custom_background['blur'] ? $custom_background['overlay'] : '') . '>';
        if ($custom_background['blur']) {
            $result .= '<div blur="' . $custom_background['blur'] . '"></div>';
            $result .= '<div class="overlay"' . $custom_background['overlay'] . '></div>';
        }
        $result .= '<header class="text-center">
                            <div class="sect">
                                    <div class="line line-left">
                                    </div>
                                    &sect;
                                    <div class="line line-right">
                                    </div>
                            </div>
                            <h2>' . $page->post_title . '</h2>
                            <h5>' . $atts['description'] . '</h5>
                    </header>
                    <div class="container">
                            <div class="row">';

        foreach ($items->posts as $item) {
            $icon = get_post_custom_values('price_icon', $item->ID);
            $color = get_post_custom_values('price_color', $item->ID);
            $link = get_post_custom_values('price_link', $item->ID);
            $content = preg_replace('/<ul>(.*)<\/ul>/is', "<ul class=\"features text-center\">$1</ul>", $item->post_content);
            $result .= '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <a class="package" data-color="' . $color[0] . '" href="' . $link[0] . '">
                                        <div class="caption">
                                                <span class="fa ' . $icon[0] . ' text-center"></span>
                                                <h5>' . $item->post_title . '</h5>
                                        </div>
                                        ' . $content . '
                                </a>
                        </div>';
        }

        $result .= '</div>
		</div>
            </div>
	</article>';

        return $result;
    }
}

add_shortcode('price', 'light_dose_price_shortcode');

function light_dose_left_column_shortcode($atts, $content) {
    return '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 video-container">' . do_shortcode($content) . '</div>';
}

add_shortcode('left_column', 'light_dose_left_column_shortcode');

function light_dose_right_column_shortcode($atts, $content) {
    return '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">' . do_shortcode($content) . '</div>';
}

add_shortcode('right_column', 'light_dose_right_column_shortcode');

function light_dose_contacts_shortcode($atts, $content) {
    $post = isset($GLOBALS['post_id']) ? $GLOBALS['post_id'] : get_slug();
    $page = light_dose_get_page_by_post_name($post);
    $custom = get_post_custom($page->ID);
    $class = null;
    if (isset($custom['class']) && isset($custom['class'][0])) {
        $class = $custom['class'][0];
    }
    $result = '';
    $custom_background = set_custom_background(is_object($page) ? $page->ID : null);
    $result .= '<article class="' . $class . ' page-light-dose theme-color background foreground links" id="' . $page->post_name . '"' . $custom_background['style'] . '>
			<div class="underlay"' . (!$custom_background['blur'] ? $custom_background['overlay'] : '') . '>';
    if ($custom_background['blur']) {
        $result .= '<div blur="' . $custom_background['blur'] . '"></div>';
        $result .= '<div class="overlay"' . $custom_background['overlay'] . '></div>';
    }
    $result .= light_dose_header(isset($page->post_title) ? $page->post_title : __('Contacts', 'light_dose')) . '
                <div class="container">
                    <div class="row">';
    $result .= do_shortcode($content);
    $result .= '</div>';

    if ($atts['form']) {
        $result .= '<form name="feedbackForm" id="feedback" method="POST" action="" class="small text-center validator">
                            <h4>' . $atts['title'] . '</h4>
                            <div class="notification"></div>
                            <input type="hidden" name="action" id="action" value="feedback">
                            <ul class="list-inline grid">
                               <li><input name="feedbackFormName" id="feedbackFormName" type="text" class="form-control" placeholder="' . __('Name', 'light_dose') . '" data-validator="required|alpha_numeric">
                               <li><input name="feedbackFormEmail" id="feedbackFormEmail" type="text" class="form-control" placeholder="' . __('Email', 'light_dose') . '" data-validator="required|valid_email">
                               <li><textarea name="feedbackFormComment" id="feedbackFormComment" class="form-control" placeholder="' . __('Comment', 'light_dose') . '" data-validator="required"></textarea>
                            </ul>
                            <input name="Submit" type="submit" class="btn btn-default" value="' . __('Submit', 'light_dose') . '" />
                    </form>';
    }
    $result .= '</div>
            </div>
	</article>';
    return $result;
}

add_shortcode('contacts', 'light_dose_contacts_shortcode');

function light_dose_contact_block_shortcode($atts, $content) {
    return '<div class="text-center col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <a href="' . $atts['href'] . '">
                            <h4 class="fa ' . $atts['icon'] . ' zoomIn"></h4>
                            <div class="overlay"></div>
                            <address>' . do_shortcode($content) . '</address>
                    </a>
                </div>';
}

add_shortcode('contact_block', 'light_dose_contact_block_shortcode');

function light_dose_works_shortcode() {
    $args = array(
        'post_type' => 'works',
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'posts_per_page' => -1,
        'order' => 'DESC'
    );
    $post = isset($GLOBALS['post_id']) ? $GLOBALS['post_id'] : get_slug();
    $page = light_dose_get_page_by_post_name($post);

    $items = new WP_Query($args);

    if ($items->have_posts()) {
        $custom_background = set_custom_background(is_object($page) ? $page->ID : null);
        $class = get_post_meta($page->ID, 'class', true);
        $result = '';
        $result .= '<article class="' . $class . ' page-light-dose theme-white background foreground" id="' . $page->post_name . '"' . $custom_background['style'] . '>
                        <div class="underlay"' . (!$custom_background['blur'] ? $custom_background['overlay'] : '') . '>';
        if ($custom_background['blur']) {
            $result .= '<div blur="' . $custom_background['blur'] . '"></div>';
            $result .= '<div class="overlay"' . $custom_background['overlay'] . '></div>';
        }
        $result .= light_dose_header(isset($page->post_title) ? $page->post_title : __('Works', 'light_dose'));
        $categories = get_terms('works-category', $args = array('hide_empty' => true));

        if (count($categories)) {
            $result .= '<div class="container">'
                    . '<div class="row tags text-center grid-filter">';
            $result .= '<a class="active" href="#" data-filter="*">' . __('All', 'light_dose') . '</a>';
            foreach ($categories as $category) {
                $result .= '<a href="#" data-filter=".' . $category->slug . '">' . $category->name . '</a>';
            }
            $result .= '</div>'
                    . '</div>';
        }

        $result .= '<ul class="list-inline grid small text-left">';
        foreach ($items->posts as $key => $item) {
            $categories[$key] = get_the_terms($item->ID, 'works-category');
            $text_hover = get_post_meta($item->ID, 'works_text_hover', true);
            $category_items = '';
            if ($categories[$key]) {
                $category_items_name = '';
                $current_category_item = 0;
                foreach ($categories[$key] as $category) {
                    if (count($categories[$key]) !== ++$current_category_item) {
                        $category_items_name .= $category->name . ' ';
                        $category_items .= $category->slug . ' ';
                    } else {
                        $category_items_name .= $category->name;
                        $category_items .= $category->slug;
                    }
                }
            }
            $result .= '<li class="' . $category_items . '">';
            $result .= wp_get_attachment_image(get_post_thumbnail_id($item->ID), 'works-small', false, array('class' => 'zoomIn img-responsive stretch-width animate-once attachment-works-small', 'alt' => $item->post_title));
            $result .= '<div class="overlay">
                            <div class="stretch-both">
                                <div>
                                    <h4>' . $item->post_title . '</h4>
                                    <p>' . $text_hover . '</p>
                                </div>
                            </div>
                        </div>';
            $result .= '</li>';
        }
        $result .= '</ul>';
        $result .= '<section class="page-light-dose collapse-height stretch-width">
			<div class="container">
                            <div class="row">';

        $result .= '<div class="col-lg-1 col-md-2 col-sm-12 col-xs-12 text-right controls">
                        <svg class="previous" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve">
                                <switch>
                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#000" d="M0,12L12.6,0L14,1.4L3.4,11.9L14,22.6L12.6,24L0,12"/>
                                        <foreignObject width="24" height="24">
                                                <img class="previous" src="' . get_template_directory_uri() . '/img/icon-previous.png" alt="' . __('Previous', 'light_dose') . '" title="' . __('Previous', 'light_dose') . '" />
                                        </foreignObject>
                                </switch>
                        </svg>
                        <svg class="next" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 14 24" enable-background="new 0 0 14 24" xml:space="preserve">
                                <switch>
                                        <path fill-rule="evenodd" clip-rule="evenodd" fill="#000" d="M14,12L1.4,24L0,22.6l10.6-10.7L0,1.4L1.4,0L14,12"/>
                                        <foreignObject width="24" height="24">
                                                <img class="next" src="' . get_template_directory_uri() . '/img/icon-next.png" alt="' . __('Next', 'light_dose') . '" title="' . __('Next', 'light_dose') . '" />
                                        </foreignObject>
                                </switch>
                        </svg>
                        <svg class="close" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="24px" viewBox="5 5 17 17" enable-background="new 0 0 27 27" xml:space="preserve">
                                <switch>
                                        <g>
                                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#000" d="M21.3,5L22,5.7L5.7,22L5,21.3L21.3,5z"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#000" d="M5,5.7L5.7,5L22,21.3L21.3,22L5,5.7z"/>
                                        </g>
                                        <foreignObject width="22" height="22">
                                                <img class="close" width="17" height="17" src="' . get_template_directory_uri() . '/img/icon-close.png" alt="' . __('Close', 'light_dose') . '" title="' . __('Close', 'light_dose') . '" />
                                        </foreignObject>
                                </switch>
                        </svg>
                    </div>';

        $result .= '<div class="col-lg-11 col-md-10 col-sm-12 col-xs-12">
			<div class="caroufredsel enable-controls">';

        foreach ($items->posts as $key => $item) {
            $gallery = get_post_meta($item->ID, 'portfolio_gallery', true);

            $client = get_post_meta($item->ID, 'works_client', true);
            $link = get_post_meta($item->ID, 'works_link', true);
            $video = get_post_meta($item->ID, 'works_video', true);
            $result .= '<div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="caroufredsel enable-swipe enable-pagination blueimp">';

            $attachments = json_decode(html_entity_decode($gallery), true);
            if (count($attachments) && empty($video)) {
                foreach ($attachments as $image) {
                    $attachment = wp_get_attachment_image_src($image['id'], 'works-large');
                    $image_tag = wp_get_attachment_image($image['id'], 'works-small', false, array('class' => 'img-responsive', 'alt' => $item->post_title));
                    $result .= '<div>
                                    <a class="img-responsive" href="' . $attachment[0] . '" title="' . $item->post_title . '">
                                        ' . $image_tag . '
                                        <div class="overlay"></div>
                                    </a>
                                </div>';
                }
            } else {
                if (!empty($video)) {
                    global $wp_embed;
                    $shortcode = '[embed width="560"]' . $video . '&w=315[/embed]';
                    $result .= $wp_embed->run_shortcode($shortcode);
                } else {
                    $attachment = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID), 'works-large');
                    $result .= '<div>
                                <a class="img-responsive" href="' . $attachment[0] . '" title="' . $item->post_title . '">
                                    ' . wp_get_attachment_image(get_post_thumbnail_id($item->ID), 'works-small', false, array('class' => 'img-responsive', 'alt' => $item->post_title)) . '
                                    <div class="overlay"></div>
                                </a>
                            </div>';
                }
            }

            if ($categories[$key]) {
                $category_items = '';
                $category_items_name = '';
                $current_category_item = 0;
                foreach ($categories[$key] as $category) {
                    if (count($categories[$key]) !== ++$current_category_item) {
                        $category_items_name .= $category->name . ', ';
                        $category_items .= $category->slug . ' ';
                    } else {
                        $category_items_name .= $category->name;
                        $category_items .= $category->slug;
                    }
                }
            }
            $result .= '</div>
                                <span class="pagination text-center"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <h4>' . $item->post_title . '</h4>
                                    <div class="meta-wrapper">';
            if (!empty($category_items_name)) {
                $result .= '<div class="category">
                                <b>' . __('Category', 'light_dose') . ':</b>
                                ' . $category_items_name . '
                        </div>';
            }
            if (!empty($client)) {
                $result .= '<div class="client">
                                    <b>' . __('Client', 'light_dose') . ':</b>
                                    ' . $client . '
                            </div>';
            }
            $result .= '</div>
                                    <div>
                                            <p>' . $item->post_content . '</p>
                                    </div>';
            if (!empty($link)) {
                $result .= '<a class = "btn btn-mono no-hover" href = "' . $link . '">' . __('Visit Site', 'light_dose') . '</a>';
            }
            $result .= '</div>
                    </div>';
        }

        $result .= '</div></div>';
        $result .= '</div>
		</div>
            </section>
            </div>';
        $result .= '</article>';
        return $result;
    }
}

add_shortcode('works', 'light_dose_works_shortcode');

function light_dose_team_shortcode() {
    $args = array(
        'post_type' => 'team',
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'posts_per_page' => -1,
        'order' => 'ASC'
    );
    $post = isset($GLOBALS['post_id']) ? $GLOBALS['post_id'] : get_slug();
    $page = light_dose_get_page_by_post_name($post);

    $items = new WP_Query($args);

    if ($items->have_posts()) {
        $custom_background = set_custom_background(is_object($page) ? $page->ID : null);
        $class = get_post_meta($page->ID, 'class', true);
        $result = '';
        $chanks = array_chunk($items->posts, 4);
        $result .= '<article class="' . $class . ' page-light-dose theme-white background foreground links" id="' . $page->post_name . '"' . $custom_background['style'] . '>
                        <div class="underlay"' . (!$custom_background['blur'] ? $custom_background['overlay'] : '') . '>';
        if ($custom_background['blur']) {
            $result .= '<div blur="' . $custom_background['blur'] . '"></div>';
            $result .= '<div class="overlay"' . $custom_background['overlay'] . '></div>';
        }
        $result .= light_dose_header(isset($page->post_title) ? $page->post_title : __('Team', 'light_dose')) . '
                            <div class="container">';
        foreach ($chanks as $posts) {
            $result .= '<div class="row">';

            foreach ($posts as $key => $item) {
                $animate = get_post_meta($item->ID, 'team_animate', true);
                $class = 'zoomIn';
                if ($animate == 0) {
                    $class = 'zoomIn transition-none';
                }
                $photo = wp_get_attachment_image(get_post_thumbnail_id($item->ID), 'team-small', false, array('class' => $class));
                $result .= '<div class="text-center col-lg-3 col-md-3 col-sm-6 col-xs-12">';
                if (!empty($photo)) {
                    $result .= '<span class="wrapper img-circle">
                                    ' . $photo . '
                                    <span class="overlay img-circle"></span>
                                </span>';
                }
                $result .= '<span class="caption color">' . $item->post_title . '</span>
                                <small>' . get_post_meta($item->ID, 'team_job', true) . '</small>
                                <p>' . $item->post_content . '</p>
                                <ul class="list-inline grid social-small">
                                        ' . do_shortcode(get_post_meta($item->ID, 'team_social', true)) . '
                                </ul>
                        </div>';
                if ($key % 2) {
                    $result .= '<div class="clearfix visible-sm"></div>';
                }
            }

            $result .= '</div>';
        }
        $result .= '</div>
                </div>
            </article>';

        return $result;
    }
}

add_shortcode('team', 'light_dose_team_shortcode');

function light_dose_services_shortcode($atts, $content) {
    $post = isset($GLOBALS['post_id']) ? $GLOBALS['post_id'] : get_slug();
    $page = light_dose_get_page_by_post_name($post);
    $class = get_post_meta($page->ID, 'class', true);
    $custom_background = set_custom_background(is_object($page) ? $page->ID : null);
    $result = '';
    $result .= '<article class="services page-light-dose theme-color background foreground ' . $class . '" id="' . $page->post_name . '"' . $custom_background['style'] . '>
                    <div class="underlay"' . (!$custom_background['blur'] ? $custom_background['overlay'] : '') . '>';
    if ($custom_background['blur']) {
        $result .= '<div blur="' . $custom_background['blur'] . '"></div>';
        $result .= '<div class="overlay ' . $class . '"' . $custom_background['overlay'] . '></div>';
    } else {
        $result .= '<div class="overlay ' . $class . '"></div>';
    }
    $result .= light_dose_header(isset($page->post_title) ? $page->post_title : __('Services', 'light_dose')) . '
                <div class="container">
                    <div class="row">';
    $result .= do_shortcode($content);

    $result .= '</div>
               </div>
            </div>
	</article>';

    return $result;
}

add_shortcode('services', 'light_dose_services_shortcode');

function light_dose_service_block_shortcode($atts, $content) {
    static $count;
    $result = '';
    if (!$count) {
        $count = 1;
    }
    $result .= '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <h4><span class="fa ' . $atts['icon'] . '"></span>' . $atts['title'] . '</h4>
                    <p>' . do_shortcode($content) . '</p>
            </div>';
    if (!($count % 2)) {
        $result .= '<div class="clearfix visible-sm"></div>';
    }
    if (!($count % 3)) {
        $result .= '<div class="clearfix visible-md"></div>';
    }
    $count++;
    return $result;
}

add_shortcode('service_block', 'light_dose_service_block_shortcode');

function light_dose_login() {
    global $wpdb;
    $home_url = home_url() . '/register';
    if (function_exists('icl_get_home_url')) {
        $page = light_dose_get_page_by_post_name('register');
        $id = icl_object_id($page->ID, 'page');
        $pages = $wpdb->get_results("SELECT post_name FROM $wpdb->posts WHERE ID=" . (int) $id . "");
        if (count($pages)) {
            $home_url = icl_get_home_url() . $pages[0]->post_name;
        }
    }
    return '<article class="lower login page-light-dose theme-color foreground links" id="page-login">
                    <div class="underlay">
                            ' . light_dose_header(__('Log In', 'light_dose')) . '
                            <div class="container">
                                    <form name="login" id="login" method="POST" action="" class="text-center validator">
                                            <div class="notification"></div>
                                            <input id="user_login" name="log" type="text" class="form-control" placeholder="' . __('Username', 'light_dose') . '" data-validator="required|alpha_numeric">
                                            <input id="user_pass" name="pwd" type="password" class="form-control" placeholder="' . __('Password', 'light_dose') . '" data-validator="required">
                                            <span class="checkbox text-left">
                                                    <input id="rememberme" type="checkbox" name="rememberme" value="forever">
                                                    <label for="rememberme">' . __('Remember Me', 'light_dose') . '</label>
                                            </span>
                                            <input name="wp-submit" id="wp-submit" type="submit" class="btn btn-default pull-left" value="' . __('Log In', 'light_dose') . '" />
                                            ' . wp_nonce_field('ajax-login-nonce', 'security') . '
                                            <a class="pull-right" href="' . $home_url . '">
                                                    ' . __('Register', 'light_dose') . '
                                            </a>
                                    </form>
                            </div>
                    </div>
            </article>';
}

add_shortcode('login', 'light_dose_login');

function light_dose_register() {
    global $wpdb;
    $home_url = home_url() . '/login';
    if (function_exists('icl_get_home_url')) {
        $page = light_dose_get_page_by_post_name('login');
        $id = icl_object_id($page->ID, 'page');
        $pages = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=" . (int) $id . "");
        if (count($pages)) {
            $home_url = icl_get_home_url() . $pages[0]->post_name;
        }
    }
    return '<article class="lower login page-light-dose theme-color foreground links" id="page-register">
			<div class="underlay">
				' . light_dose_header(__('Register', 'light_dose')) . '
				<div class="container">
					<form name="register" id="register" method="POST" action="index.html" class="text-center validator">
                                                <div class="notification"></div>
						<input name="user_name" id="user_name" type="text" class="form-control" placeholder="' . __('Username', 'light_dose') . '" data-validator="required|alpha_numeric">
						<input name="user_email" id="user_email" type="text" class="form-control" placeholder="' . __('Email', 'light_dose') . '" data-validator="required|valid_email">
						<input name="Submit" type="submit" class="btn btn-default pull-left" value="' . __('Register', 'light_dose') . '" />
						<a class="pull-right" href="' . $home_url . '">
							' . __('Already registered?', 'light_dose') . '
						</a>
					</form>
				</div>
			</div>
		</article>';
}

add_shortcode('register', 'light_dose_register');

function cmp($a, $b) {
    return $a->menu_order - $b->menu_order;
}

function light_dose_single_page_shortcode() {
    $locations = get_nav_menu_locations();
    $args = array(
        'order' => 'ASC',
        'orderby' => 'menu_order',
        'post_type' => 'nav_menu_item',
        'post_status' => 'publish',
        'output' => ARRAY_A,
        'output_key' => 'menu_order',
        'posts_per_page' => -1,
        'nopaging' => true,
        'update_post_term_cache' => false,
    );

    $custom_pages = array(
        'contacts',
        'works',
        'services',
        'team',
        'about',
    );

    $items = wp_get_nav_menu_items(count($locations) ? $locations["primary"] : null, $args);

    if ($items) {
        ob_start();
        $i = 0;
        //$total_items = count($items);
        $wp_query = new WP_Query();
        $all_pages = $wp_query->query(array('post_type' => 'page', 'posts_per_page' => -1,));

        foreach ($items as $item) {
            $post = get_post($item->object_id);
            if ($i !== 0) {
                if ($item->classes[0] !== 'page' && $item->object !== 'post' && $item->object !== 'custom' && is_object($post)) {
                    $GLOBALS['post_id'] = $post->ID;
                    $_class = get_post_custom($post->ID);
                    $class = $classes = null;
                    if (isset($_class['class'][0])) {
                        $class = $_class['class'][0];
                        $classes = explode(" ", $class);
                    }
                    if (in_array($classes[0], $custom_pages)) :
                        echo str_replace(']]>', ']]&gt;', apply_filters('the_content', $post->post_content));
                    else :
                        $custom_background = set_custom_background($post->ID);
                        ?>
                        <article class="<?php echo $class; ?> page-light-dose theme-white background foreground links"<?php echo $custom_background['style']; ?> id="<?php echo $post->post_name; ?>">                            
                            <div class="underlay"<?php echo (!$custom_background['blur'] ? $custom_background['overlay'] : ''); ?>>
                                <?php if ($custom_background['blur']) : ?>
                                    <div blur="<?php echo $custom_background['blur']; ?>"></div>
                                    <div class="overlay"<?php echo $custom_background['overlay']; ?>></div>
                                <?php endif; ?>                                
                                <?php echo light_dose_header($post->post_title); ?>
                                <div class="container">
                                    <?php
                                    echo str_replace(']]>', ']]&gt;', apply_filters('the_content', $post->post_content));
                                    ?>
                                </div>
                            </div>
                        </article>
                    <?php
                    endif;
                    $childrens = get_page_children($post->ID, $all_pages);
                    if (count($childrens)) {
                        usort($childrens, "cmp");
                        foreach ($childrens as $children) {
                            $_class = get_post_custom($children->ID);
                            if (isset($_class['class'][0])) {
                                $class = $_class['class'][0];
                                $classes = explode(" ", $class);
                            }
                            $GLOBALS['post_id'] = $children->ID;
                            $custom_background = set_custom_background($children->ID);
                            if (isset($_class['view']) && $_class['view'][0] == 'rows') :
                                echo str_replace(']]>', ']]&gt;', apply_filters('the_content', $children->post_content));
                            elseif ($classes[0] == 'video') :
                                ?>
                                <article class="video page theme-white background foreground links"<?php echo $custom_background['style']; ?> id="video">
                                    <div class="underlay"<?php echo (!$custom_background['blur'] ? $custom_background['overlay'] : ''); ?>>
                                        <?php if ($custom_background['blur']) : ?>
                                            <div blur="<?php echo $custom_background['blur']; ?>"></div>
                                            <div class="overlay"<?php echo $custom_background['overlay']; ?>></div>
                                        <?php endif; ?>
                                        <?php echo light_dose_header($children->post_title); ?>
                                        <div class="container">
                                            <div class="row">
                                                <?php
                                                echo str_replace(']]>', ']]&gt;', apply_filters('the_content', $children->post_content));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                <?php
                            elseif ($classes[0] == 'action') :
                                ?>
                                <article class="page-light-dose theme-color background foreground links <?php echo $class; ?>" id="<?php echo $children->post_name; ?>">
                                    <div class="underlay"<?php echo (!$custom_background['blur'] ? $custom_background['overlay'] : ''); ?>>
                                        <?php if ($custom_background['blur']) : ?>
                                            <div blur="<?php echo $custom_background['blur']; ?>"></div>
                                            <div class="overlay"<?php echo $custom_background['overlay']; ?>></div>
                                        <?php endif; ?>
                                        <div class="container">
                                            <h5 class="text-center">
                                                <strong><?php echo $children->post_title; ?></strong>
                                            </h5>
                                            <div class="row">
                                                <?php
                                                echo str_replace(']]>', ']]&gt;', apply_filters('the_content', $children->post_content));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                <?php
                            else :
                                $custom_background = set_custom_background($children->ID);
                                ?>
                                <article class="page-light-dose theme-white background foreground links <?php echo $class; ?>"<?php echo $custom_background['style']; ?> id="<?php echo $children->post_name; ?>">
                                    <div class="underlay"<?php echo (!$custom_background['blur'] ? $custom_background['overlay'] : ''); ?>>
                                        <?php if ($custom_background['blur']) : ?>
                                            <div blur="<?php echo $custom_background['blur']; ?>"></div>
                                            <div class="overlay"<?php echo $custom_background['overlay']; ?>></div>
                                        <?php endif; ?>
                                        <div class="container text-center">
                                            <h5><?php echo $children->post_title; ?></h5>
                                            <div class="row">
                                                <?php
                                                echo str_replace(']]>', ']]&gt;', apply_filters('the_content', $children->post_content));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php
                            endif;
                        }
                    }
                }
            } else {
                if ($item->classes[0] !== 'page') {
                    echo str_replace(']]>', ']]&gt;', apply_filters('the_content', $post->post_content));
                }
            }
            $i++;
        }
        $page_template = ob_get_contents();
        ob_end_clean();
        echo $page_template;
    }
}

function light_dose_header($title = '') {
    return '<header class="text-center">
                <div class="sect">
                    <div class="line line-left"></div>
                    &sect;
                    <div class="line line-right"></div>
                </div>
                <h2>' . $title . '</h2>
            </header>';
}

function light_dose_controls($count = 1) {
    if ($count > 1) {
        return '<div class="controls">
                    <svg class="previous" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve">
                            <switch>
                                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#000" d="M0,12L12.6,0L14,1.4L3.4,11.9L14,22.6L12.6,24L0,12"/>
                                    <foreignObject width="24" height="24">
                                            <img class="previous" src="' . get_template_directory_uri() . '/img/icon-previous.png" alt="' . __('Previous', 'light_dose') . '" title="' . __('Previous', 'light_dose') . '" />
                                    </foreignObject>
                            </switch>
                    </svg>
                    <svg class="next" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="24px" viewBox="0 0 14 24" enable-background="new 0 0 14 24" xml:space="preserve">
                            <switch>
                                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#000" d="M14,12L1.4,24L0,22.6l10.6-10.7L0,1.4L1.4,0L14,12"/>
                                    <foreignObject width="24" height="24">
                                            <img class="next" src="' . get_template_directory_uri() . '/img/icon-next.png" alt="' . __('Next', 'light_dose') . '" title="' . __('Next', 'light_dose') . '" />
                                    </foreignObject>
                            </switch>
                    </svg>
            </div>';
    }
}

add_shortcode('singlepage', 'light_dose_single_page_shortcode');

function light_dose_get_pages() {
    global $wpdb;
    $pages = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'page'");
    return $pages;
}

function light_dose_get_page_by_post_name($post) {
    global $all_pages;
    if (!empty($post)) {
        foreach ($all_pages as $page) {
            if (is_numeric($post) && $page->ID == $post) {
                return $page;
            }
            if (is_string($post) && $page->post_name == $post) {
                return $page;
            }
        }
    }
}

function light_dose_get_social_shortcode($atts, $content) {
    return '<li><a class="text-center" href="' . (isset($atts['href']) ? $atts['href'] : "#") . '" target="_blank"><i class="icon-' . $atts['type_icon'] . '"></i></a></li>';
}

add_shortcode('social', 'light_dose_get_social_shortcode');

function set_custom_background($id) {
    if (!isset($id)) {
        return null;
    }
    $background = get_post_meta($id);
    $custom_background = array();
    $overlay = null;
    $blur = 0;
    if (isset($background['page_background_blur'][0]) && $background['page_background_blur'][0] > 0) {
        $blur = (int) $background['page_background_blur'][0];
    }
    if (isset($background['page_background_transparent'][0]) && (int) $background['page_background_transparent'][0] == 0) {
        if (!empty($background['page_background_color'][0])) {
            $custom_background['page_background_color'] = "background-color: {$background['page_background_color'][0]}";
        }
        if (!empty($background['page_background_pattern'][0])) {
            $custom_background['page_background_pattern'] = "background: url('" . get_template_directory_uri() . "/{$background['page_background_pattern'][0]}') repeat";
        }

        if (!empty($background['background_image'][0])) {
            unset($custom_background['page_background_pattern'], $custom_background['page_background_color']);
            $custom_background['background_image'] = "background: url({$background['background_image'][0]});background-size:cover";
        }
    } else {
        $custom_background['background_transparent'] = "background-color: transparent";
    }

    if (!empty($background['page_text_color'][0])) {
        $custom_background['page_text_color'] = "color: {$background['page_text_color'][0]}";
    }
    if (!empty($background['page_overlay_color'][0]) && $background['page_overlay'][0] >= 0) {
        $overlay = ' style="background-color: rgba(' . implode(", ", hex2rgb($background['page_overlay_color'][0])) . ', ' . ($background['page_overlay'][0] == 0 ? 0 : $background['page_overlay'][0]) . ');"';
    }

    return array(
        'style' => ' style="' . implode(";", $custom_background) . '"',
        'overlay' => $overlay,
        'blur' => $blur,
    );
}

function light_dose_container_shortcode($atts, $content) {
    return do_shortcode($content);
}

add_shortcode('container', 'light_dose_container_shortcode');
