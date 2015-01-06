<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Light Dose
 */
?>
<?php $page = get_page(get_option('page_on_front')); ?>
<?php if (!preg_match('[singlepage]', $page->post_content)) : ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="text-center">
            <div class="sect">
                <div class="line line-left">
                </div>
                &sect;
                <div class="line line-right">
                </div>
            </div>
            <h2>
                <?php the_title(); ?>
            </h2>
        </header>
        <div class="container">
            <div class="entry-content">
                <?php echo wp_get_attachment_image(get_post_thumbnail_id(the_ID()), 'blog-attachment', false, array('class' => 'img-responsive')); ?>
                <?php the_content(); ?>
                <?php
                wp_link_pages(array(
                    'before' => '<div class="page-links">' . __('Pages:', 'light_dose'),
                    'after' => '</div>',
                ));
                ?>
            </div>
            <?php edit_post_link(__('Edit', 'light_dose'), '<span class="edit-link">', '</span>'); ?>
        </div>
    </article><!-- #post-## -->
<?php else : ?>
    <?php the_content(); ?>
<?php endif; ?>