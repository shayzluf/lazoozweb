<?php

/**
 * Function light_dose_wp_page_menu()
 */
function light_dose_wp_page_menu($args = array()) {
    $defaults = array('sort_column' => 'menu_order, post_title', 'menu_class' => '', 'echo' => true, 'link_before' => '', 'link_after' => '');
    $args = wp_parse_args($args, $defaults);
    $args = apply_filters('wp_page_menu_args', $args);
    $menu = '';

    $list_args = $args;

    // Show Home in the menu
    if (!empty($args['show_home'])) {
        if (true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'])
            $text = __('Home', 'Home');
        else
            $text = $args['show_home'];
        $class = '';
        if (is_front_page() && !is_paged())
            $class = 'class="active"';

        $menu .= '<li ' . $class . '><a href="' . home_url('/') . '" title="' . esc_attr($text) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
        // If the front page is a page, add it to the exclude list
        if (get_option('show_on_front') == 'page') {
            if (!empty($list_args['exclude'])) {
                $list_args['exclude'] .= ',';
            } else {
                $list_args['exclude'] = '';
            }
            $list_args['exclude'] .= get_option('page_on_front');
        }
    }

    $list_args['echo'] = false;
    $list_args['title_li'] = '';
    $menu .= str_replace(array("\r", "\n", "\t"), '', wp_list_pages($list_args));

    $menu = apply_filters('wp_page_menu', $menu, $args);
    if ($args['echo'])
        echo $menu;
    else
        return $menu;
}

function light_dose_set_element_id($uri) {
    $parts = explode('/', $uri);
    $elements = array();
    foreach ($parts as $el) {
        if (!empty($el)) {
            $elements[] = $el;
        }
    }
    $last_el = explode('-', end($elements));
    return reset($last_el);
}

// Customisation Menu Link
class description_walker extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-slide text-left pull-right user\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        global $wpdb;

        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';
        $link_class = 'scroll';
        $start_link = '<a class="%s"%s>';
        $children_count = $wpdb->get_var(
                $wpdb->prepare("
            SELECT COUNT(*) FROM $wpdb->postmeta
            WHERE meta_key = %s
            AND meta_value = %d
          ", '_menu_item_menu_item_parent', $item->ID)
        );

        if ($children_count > 0) {
            $link_class = 'dropdown';
        }
                
        $output .= $indent . '<li id="menu-item-' . $item->ID . '">';
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        
        if ($item->object == 'page' && $item->classes[0] !== 'page') {
            $varpost = get_post($item->object_id);
            $attributes .= ' href="#' . $varpost->post_name . '"';
        } else if($item->type == 'custom') {
            $attributes .= ' href="' . $item->url . '"';
        } elseif(substr($item->url, 0, 1) == '#') {
            $attributes .= ' href="' . $item->url . '"';
        } else {
            $url = home_url() . $item->url;
            $el_id = light_dose_set_element_id($item->url);
            if (function_exists('icl_get_home_url')) {
                $page = light_dose_get_page_by_post_name($el_id);
                if (isset($page)) {
                    $id = icl_object_id($page->ID, 'page');
                    $pages = $wpdb->get_results("SELECT post_name FROM $wpdb->posts WHERE ID=" . (int) $id . "");
                    if (count($pages)) {
                        $url = icl_get_home_url() . $pages[0]->post_name;
                    }
                }
            }
            $attributes .=!empty($item->url) ? ' href="' . $url . '" id="' . $el_id . '"' : '';
        }
        if (is_object($args)) {
            $item_output = $args->before;
            $item_output .= sprintf($start_link, $link_class, $attributes);
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID);
            $item_output .= $args->link_after;
            $item_output .= $depth == 0 ? "\n" . '<div class="overlay"></div>' : '';
            $item_output .= '</a>';

            $item_output .= $args->after;
            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id);
        }
    }

}

// Customisation Menu Link in bootom page
class description_walker_footer extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        global $wpdb;
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';
        $link_class = 'scroll';
        $page = explode('/', $item->url);
        $start_link = '<a class="%s"%s>';

        $output .= $indent . '<li id="menu-item-' . $item->ID . '">';
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        if ($item->object == 'page' && $item->classes[0] !== 'page') {
            $varpost = get_post($item->object_id);
            $attributes .= ' href="#' . $varpost->post_name . '"';
        } else if($item->type == 'custom') {
            $attributes .= ' href="' . $item->url . '"';
        } elseif(substr($item->url, 0, 1) == '#') {
            $attributes .= ' href="' . $item->url . '"';
        } else {
            $url = home_url() . $item->url;
            $el_id = light_dose_set_element_id($item->url);
            if (function_exists('icl_get_home_url')) {
                $page = light_dose_get_page_by_post_name($el_id);
                if (isset($page)) {
                    $id = icl_object_id($page->ID, 'page');
                    $pages = $wpdb->get_results("SELECT post_name FROM $wpdb->posts WHERE ID=" . (int) $id . "");
                    if (count($pages)) {
                        $url = icl_get_home_url() . $pages[0]->post_name;
                    }
                }
            }
            $attributes .=!empty($item->url) ? ' href="' . $url . '" id="' . $el_id . '"' : '';
        }
        if (is_object($args)) {
            $item_output = $args->before;
            $item_output .= sprintf($start_link, $link_class, $attributes);
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID);
            $item_output .= $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;
            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id);
            //$output = str_replace('</li>', null, $output);
        }
    }

}
