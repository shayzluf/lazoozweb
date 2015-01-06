<?php
/**
 * @package Light Dose
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    <h2 class="entry-header">
        <a href="<?php the_permalink(); ?>" rel="bookmark">
            <?php the_title(); ?>
        </a>
    </h2>
    <div class="container">
        <?php if (is_search()) : // Only display Excerpts for Search ?>
            <div class="entry">
                <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->
        <?php else : ?>
            <div class="entry-meta">
                <?php if ('post' == get_post_type()) : // Hide category and tag text for pages on Search ?>

                    <?php light_dose_posted_on(); ?>
                    <?php if (!post_password_required() && ( comments_open() || '0' != get_comments_number() )) : ?>                        
                        <small><?php comments_popup_link(__('Leave a comment', 'light_dose'), __('1 Comment', 'light_dose'), __('% comments', 'light_dose')); ?>,</small>
                    <?php endif; ?>

                    <?php
                    /* translators: used between list items, there is a space after the comma */
                    $categories_list = get_the_category_list(__(', ', 'light_dose'));
                    if ($categories_list && light_dose_categorized_blog()) :
                        ?>
                        <small class="cat-links">
                            <?php printf(__('Posted in %1$s', 'light_dose'), $categories_list); ?>,
                        </small>
                    <?php endif; // End if categories ?>

                    <?php
                    /* translators: used between list items, there is a space after the comma */
                    $tags_list = get_the_tag_list('', __(', ', 'light_dose'));
                    if ($tags_list) :
                        ?>                        
                        <small class="tags-links">
                            <?php printf(__('Tagged %1$s', 'light_dose'), $tags_list); ?>,
                        </small>
                    <?php endif; // End if $tags_list ?>

                    <?php edit_post_link(__('Edit', 'light_dose'), '<small class="edit-link">', '</small>'); ?>

                <?php endif; // End if 'post' == get_post_type() ?>
            </div>
            <div class="entry">
                <?php echo wp_get_attachment_image(get_post_thumbnail_id(get_the_ID()), 'blog-attachment', false, array('class' => 'img-responsive')); ?>
                <?php the_content(__('Read More', 'light_dose')); ?><!-- .entry-content -->
            </div>
            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'light_dose'),
                'after' => '</div>',
            ));
            ?>
        <?php endif; ?>
    </div>
</article>