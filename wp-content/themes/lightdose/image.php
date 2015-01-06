<?php
/**
 * The template for displaying image attachments.
 *
 * @package Light Dose
 */
get_header();
?>
<div class="theme-white background">
    <?php light_dose_get_blog_header(); ?>
    <section class="container theme-white background foreground links blog">
        <div class="row">
            <?php require 'sidebar.php'; ?>
            <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
                <?php while (have_posts()) : the_post(); ?>    

                    <article class="post" id="post-<?php echo get_the_ID(); ?>">
                        <?php the_title('<h2 class="entry-header">', '</h2>'); ?>
                        <div class="entry-meta">
                            <?php
                            $metadata = wp_get_attachment_metadata();
                            printf(__('Published <span class="entry-date"><time class="entry-date" datetime="%1$s">%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a>', 'light_dose'), esc_attr(get_the_date('c')), esc_html(get_the_date()), wp_get_attachment_url(), $metadata['width'], $metadata['height'], get_permalink($post->post_parent), esc_attr(strip_tags(get_the_title($post->post_parent))), get_the_title($post->post_parent)
                            );

                            edit_post_link(__('Edit', 'light_dose'), ' | <span class="edit-link">', '</span>');
                            ?>
                        </div><!-- .entry-meta -->

                        <nav role="navigation" id="image-navigation" class="navigation-image">
                            <div class="nav-previous"><?php previous_image_link(false, __('<span class="meta-nav">&larr;</span> Previous', 'light_dose')); ?></div>
                            <div class="nav-next"><?php next_image_link(false, __('Next <span class="meta-nav">&rarr;</span>', 'light_dose')); ?></div>
                        </nav><!-- #image-navigation -->				

                        <div class="container">
                            <div class="entry-attachment">
                                <div class="attachment">
                                    <?php light_dose_the_attached_image(); ?>
                                </div><!-- .attachment -->

                                <?php if (has_excerpt()) : ?>
                                    <div class="entry-caption">
                                        <?php the_excerpt(); ?>
                                    </div><!-- .entry-caption -->
                                <?php endif; ?>
                            </div><!-- .entry-attachment -->
                            <div class="entry-meta">
                                <?php
                                if (comments_open() && pings_open()) : // Comments and trackbacks open
                                    printf(__('<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a> or leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'light_dose'), get_trackback_url());
                                elseif (!comments_open() && pings_open()) : // Only trackbacks open
                                    printf(__('Comments are closed, but you can leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'light_dose'), get_trackback_url());
                                elseif (comments_open() && !pings_open()) : // Only comments open
                                    _e('Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.', 'light_dose');
                                elseif (!comments_open() && !pings_open()) : // Comments and trackbacks closed
                                    _e('Both comments and trackbacks are currently closed.', 'light_dose');
                                endif;

                                edit_post_link(__('Edit', 'light_dose'), ' <span class="edit-link">', '</span>');
                                ?>
                            </div>
                            <?php echo wp_get_attachment_image(get_post_thumbnail_id(the_ID()), 'blog-attachment', false, array('class' => 'img-responsive')); ?>
                            <?php
                            the_content();
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . __('Pages:', 'light_dose'),
                                'after' => '</div>',
                            ));
                            ?>

                            <?php
                            // If comments are open or we have at least one comment, load up the comment template
                            if (comments_open() || '0' != get_comments_number())
                                comments_template();
                            ?>
                        </div>
                    </article><!-- #post-## -->

                <?php endwhile; // end of the loop.  ?>

            </div>
        </div>
    </section>
</div>
<?php get_footer(); ?>