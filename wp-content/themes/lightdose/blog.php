<?php /* Template Name: Blog Posts */ ?>
<?php get_header(); ?>
<?php $light_dose_options = get_option('light_dose_theme_options'); ?>
<?php $posts_per_page = !isset($light_dose_options['blog_home_per_page']) ? get_option('posts_per_page') : (int) $light_dose_options['blog_home_per_page']; ?>

<?php query_posts('post_type=post&post_status=publish&posts_per_page=' . $posts_per_page . '&paged=' . get_query_var('paged')); ?>
<div class="theme-white background">
    <?php light_dose_get_blog_header(); ?>
    <section class="container theme-white background foreground links blog">
        <div class="row">
            <?php require 'sidebar.php'; ?>
            <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
                <?php if (have_posts()) : ?>

                    <?php while (have_posts()): the_post(); ?>
                        <?php $post_id = get_the_ID(); ?>

                        <article class="post" id="post-<?php echo $post_id; ?>-<?php echo get_the_time('U', $post_id); ?>">
                            <h2 class="entry-header">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <div class="entry-meta">
                                <span class="author">
                                    <?php echo get_the_author(); ?>,
                                </span>
                                <small class="date">
                                    <?php echo esc_html(get_the_date()); ?>,
                                </small>
                                <span class="comment-count">
                                    <?php
                                    $comments = wp_count_comments($post_id);
                                    echo $comments->total_comments;
                                    ?> <?php echo __('comments', 'light_dose'); ?>
                                </span>
                            </div>
                            <div class="entry">
                                <?php echo wp_get_attachment_image(get_post_thumbnail_id($post_id), 'blog-attachment', false, array('class' => 'img-responsive')); ?>
                                <?php global $more;
                                $more = false; ?>
        <?php the_content(__('Read More', 'light_dose')); ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <input type="hidden" name="last_post_id" id="last_post_id" value="<?php echo $post_id; ?>" />
                    <?php $total_posts = wp_count_posts('post'); ?>
    <?php if ($total_posts->publish > $posts_per_page) : ?>
                        <div class="text-center load-more">
                            <div>
                            </div>
                            <button class="btn btn-mono italic">
        <?php echo __('Load More', 'light_dose'); ?>
                            </button>
                        </div>
                    <?php endif; ?>
<?php else: ?>
                    <div id="post-404" class="noposts">
                        <p><?php _e('Not found.', 'light_dose'); ?></p>
                    </div><!-- /#post-404 -->
                <?php
                endif;
                wp_reset_query();
                ?>
            </div>
        </div>
    </section>
</div>


<!-- /#content -->

<?php get_footer() ?>