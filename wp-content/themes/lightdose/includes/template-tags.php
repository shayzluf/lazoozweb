<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Light Dose
 */
if (!function_exists('light_dose_content_nav')) :

    /**
     * Display navigation to next/previous pages when applicable
     */
    function light_dose_content_nav($nav_id) {
        global $wp_query, $post;

        // Don't print empty markup on single pages if there's nowhere to navigate.
        if (is_single()) {
            $previous = ( is_attachment() ) ? get_post($post->post_parent) : get_adjacent_post(false, '', true);
            $next = get_adjacent_post(false, '', false);

            if (!$next && !$previous)
                return;
        }

        // Don't print empty markup in archives if there's only one page.
        if ($wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ))
            return;

        $nav_class = ( is_single() ) ? 'navigation-post' : 'navigation-paging';
        ?>
        <nav role="navigation" id="<?php echo esc_attr($nav_id); ?>" class="<?php echo $nav_class; ?>">
            <h4 class="screen-reader-text"><?php _e('Post navigation', 'light_dose'); ?></h4>

            <?php if (is_single()) : // navigation links for single posts  ?>

                <?php previous_post_link('<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x('&larr;', 'Previous post link', 'light_dose') . '</span> %title'); ?>
                <?php next_post_link('<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x('&rarr;', 'Next post link', 'light_dose') . '</span>'); ?>

            <?php elseif ($wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() )) : // navigation links for home, archive, and search pages  ?>

                <?php if (get_next_posts_link()) : ?>
                    <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'light_dose')); ?></div>
                <?php endif; ?>

                <?php if (get_previous_posts_link()) : ?>
                    <div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'light_dose')); ?></div>
                <?php endif; ?>

            <?php endif; ?>

        </nav><!-- #<?php echo esc_html($nav_id); ?> -->
        <?php
    }

endif; // light_dose_content_nav

if (!function_exists('light_dose_comment')) :

    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     */
    function light_dose_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;

        if ('pingback' == $comment->comment_type || 'trackback' == $comment->comment_type) :
            ?>

            <li id="comment-<?php comment_ID(); ?>" <?php comment_class('comment'); ?>>
                <div class="comment-body">
                    <?php _e('Pingback:', 'light_dose'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('Edit', 'light_dose'), '<span class="edit-link">', '</span>'); ?>
                </div>

            <?php else : ?>

            <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>

                <?php if (0 != $args['avatar_size']) echo get_avatar($comment, 70); ?>
                <div class="entry-meta">
                    <small class="author">
                        <?php echo get_comment_author_link(); ?>
                    </small>
                    <span class="xs-hidden">|</span>
                    <br class="visible-xs" />
                    <small class="date">
                        <?php printf(_x('%1$s, %2$s', '1: date, 2: time', 'light_dose'), get_comment_date(), get_comment_time()); ?>
                        <?php edit_comment_link(__('Edit', 'light_dose'), '| <small>', '</small>'); ?>
                    </small>
                    <small class="reply pull-right italic">
                        <?php comment_reply_link(array_merge($args, array('add_below' => 'date', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                    </small>                    
                </div>
                <div class="clearfix visible-xs">                    
                </div>
                <div class="entry">
                    <?php if ('0' == $comment->comment_approved) : ?>
                        <p><?php _e('Your comment is awaiting moderation.', 'light_dose'); ?></p>
                    <?php endif; ?>
                    <p><?php comment_text(); ?></p>
                </div>
            <?php
            endif;
        }

    endif; // ends check for light_dose_comment()

    if (!function_exists('light_dose_the_attached_image')) :

        /**
         * Prints the attached image with a link to the next attached image.
         */
        function light_dose_the_attached_image() {
            $post = get_post();
            $attachment_size = apply_filters('light_dose_attachment_size', array(1200, 1200));
            $next_attachment_url = wp_get_attachment_url();

            /**
             * Grab the IDs of all the image attachments in a gallery so we can get the URL
             * of the next adjacent image in a gallery, or the first image (if we're
             * looking at the last image in a gallery), or, in a gallery of one, just the
             * link to that image file.
             */
            $attachments = array_values(get_children(array(
                'post_parent' => $post->post_parent,
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => 'ASC',
                'orderby' => 'menu_order ID'
            )));

            // If there is more than 1 attachment in a gallery...
            if (count($attachments) > 1) {
                foreach ($attachments as $k => $attachment) {
                    if ($attachment->ID == $post->ID)
                        break;
                }
                $k++;

                // get the URL of the next image attachment...
                if (isset($attachments[$k]))
                    $next_attachment_url = get_attachment_link($attachments[$k]->ID);

                // or get the URL of the first image attachment.
                else
                    $next_attachment_url = get_attachment_link($attachments[0]->ID);
            }

            printf('<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>', esc_url($next_attachment_url), the_title_attribute(array('echo' => false)), wp_get_attachment_image($post->ID, $attachment_size)
            );
        }

    endif;

    if (!function_exists('light_dose_posted_on')) :

        /**
         * Prints HTML with meta information for the current post-date/time and author.
         */
        function light_dose_posted_on() {
            printf(
                    __('<span class="author">%1$s</span>, '
                            . '<small class="date">%2$s, %3$s</small>,', 'light_dose'), get_the_author(), esc_html(get_the_date()), esc_html(get_the_time())
            );
            /*
              printf(
              __('<small>Posted on '
              . '<a href="%1$s" title="%2$s" rel="bookmark">'
              . '<time class="entry-date" datetime="%3$s">%4$s</time>'
              . '</a>'
              . '</small>'
              . '<span class="byline"> by '
              . '<span class="author vcard">'
              . '<a href="%5$s" title="%6$s" rel="author">%7$s</a>'
              . '</span>'
              . '</span>', 'light_dose'), esc_url(get_permalink()), esc_attr(get_the_time()), esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', 'light_dose'), get_the_author())), get_the_author()
              );
             *
             */
        }

    endif;

    /**
     * Returns true if a blog has more than 1 category
     */
    function light_dose_categorized_blog() {
        if (false === ( $all_the_cool_cats = get_transient('all_the_cool_cats') )) {
            // Create an array of all the categories that are attached to posts
            $all_the_cool_cats = get_categories(array(
                'hide_empty' => 1,
            ));

            // Count the number of categories that are attached to the posts
            $all_the_cool_cats = count($all_the_cool_cats);

            set_transient('all_the_cool_cats', $all_the_cool_cats);
        }

        if ('1' != $all_the_cool_cats) {
            // This blog has more than 1 category so light_dose_categorized_blog should return true
            return true;
        } else {
            // This blog has only 1 category so light_dose_categorized_blog should return false
            return false;
        }
    }

    /**
     * Flush out the transients used in light_dose_categorized_blog
     */
    function light_dose_category_transient_flusher() {
        // Like, beat it. Dig?
        delete_transient('all_the_cool_cats');
    }

    add_action('edit_category', 'light_dose_category_transient_flusher');
    add_action('save_post', 'light_dose_category_transient_flusher');

    function light_dose_get_blog_header() {
        $light_dose_options_theme = get_option('light_dose_theme_options');
        $blog_title = !isset($light_dose_options_theme['blog_title']) ? __('Blog', 'light_dose') : $light_dose_options_theme['blog_title'];
        $blog_text = !isset($light_dose_options_theme['blog_text']) ? __('All the news of our company is here...', 'light_dose') : $light_dose_options_theme['blog_text'];
        printf('<div class="theme-white background foreground links lower blog header text-center" id="blog">
            <table class="stretch-both">
                <tr>
                    <td>
                        <div class="scale-down">
                            <h1>%1$s</h1>
                            <h4>%2$s</h4>
                        </div>
                    </td>
                </tr>
            </table>
        </div>', $blog_title, $blog_text);
    }
    