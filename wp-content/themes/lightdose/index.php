<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Light Dose
 */
get_header();
?>
<?php $check_localhost = false; ?>
<?php $i = 1; /* Start the Loop */ ?>
<?php $posts_home = 0;?>

<?php
if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
    global $wp_query;
    $posts_home = $wp_query->post_count - 1;
    $check_localhost = true;
}
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

                        <?php
                        /* Include the Post-Format-specific template for the content.
                         * If you want to overload this in a child theme then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        get_template_part('content', get_post_format());
                        if ($i == $posts_home && $check_localhost) {
                            break;
                        }
                        $i++;
                        ?>

                    <?php endwhile; ?>

                    <section class="container">
                        <?php light_dose_content_nav('nav-below'); ?>
                    </section>

                <?php else : ?>

                    <?php get_template_part('no-results', 'index'); ?>

                <?php endif; ?>
            </div>
        </div>
    </section>
</div>
<?php get_footer(); ?>