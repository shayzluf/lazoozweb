<?php
/**
 * @package Light Dose
 */
?>

<!--BEGIN Blog content post-->
<article class="post">
    <h2 class="title"><?php the_title(); ?></h2>
    <div class="entry-meta">
        <small class="author">
            <?php the_author(); ?>
        </small>
        |
        <small class="date">
            <?php the_date(); ?>, <?php the_time(); ?>
        </small>
        <?php
        /* translators: used between list items, there is a space after the comma */
        $category_list = get_the_category_list(__(', ', 'light_dose'));

        /* translators: used between list items, there is a space after the comma */
        $tag_list = get_the_tag_list('', __(', ', 'light_dose'));

        if (!light_dose_categorized_blog()) {
            // This blog only has 1 category so we just need to worry about tags in the meta text

            if ('' != $tag_list) {
                $meta_text = __('<small>| This entry was tagged %2$s. | Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.</small>', 'light_dose');
            } else {
                $meta_text = __('<small>| Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.</small>', 'light_dose');
            }
        } else {
            // But this blog has loads of categories so we should probably display them here
            if ('' != $tag_list) {
                $meta_text = __('<small>| Posted in %1$s and tagged %2$s. | Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.</small>', 'light_dose');
            } else {
                $meta_text = __('<small>| Posted in %1$s. | Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.</small>', 'light_dose');
            }
        } // end check for categories on this blog

        printf(
                $meta_text, $category_list, $tag_list, get_permalink(), the_title_attribute('echo=0')
        );
        ?>

        <?php edit_post_link(__('Edit', 'light_dose'), '| <small class="edit-link">', '</small>'); ?>
    </div>
    <div class="entry">
        <?php echo wp_get_attachment_image(get_post_thumbnail_id(get_the_ID()), 'blog-attachment', false, array('class' => 'img-responsive')); ?>
        <?php the_content(); ?>
        <?php
        wp_link_pages(array(
            'before' => '<div class="page-links">' . __('Pages:', 'light_dose'),
            'after' => '</div>',
        ));
        ?>
    </div>    
    <?php
    if (!post_password_required() && (comments_open() || '0' != get_comments_number())) :
        printf(__('<h4>Comments <small>(%d)</small></h4>', 'light_dose'), get_comments_number());
        comments_template();
    endif;
    ?>
</article>
<!--END Blog content post-->
