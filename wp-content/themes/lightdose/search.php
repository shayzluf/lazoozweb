<?php
/**
 * The template for displaying Search Results pages.
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
                <?php if (have_posts()) : ?>
                    <?php /* Start the Loop */ ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('content', 'search'); ?>
                    <?php endwhile; ?>
                    <?php light_dose_content_nav('nav-below'); ?>
                <?php else : ?>
                    <?php get_template_part('no-results', 'search'); ?>
                <?php endif; ?>
                <!-- #content -->
            </div>
        </div>
    </section>
</div><!-- #primary -->

<?php get_footer(); ?>