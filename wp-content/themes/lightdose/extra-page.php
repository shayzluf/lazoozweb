<?php /* Template Name: Extra Page */ ?>
<?php
/**
 * The template for displaying extra page.
 *
 * This is the template that displays extra pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Light Dose
 */
get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <?php if (!is_front_page()) : ?>
        <?php $custom_background = set_custom_background(get_the_ID()); ?>        
        <?php $class = get_post_meta(get_the_ID(), 'class', true); ?>
        <article class="<?php echo $class; ?> extra page-light-dose background foreground links page-<?php the_ID(); ?>"<?php echo $custom_background['style']; ?>>
            <div class="underlay"<?php echo (!$custom_background['blur'] ? $custom_background['overlay'] : ''); ?>>
                <?php if ($custom_background['blur']) : ?>
                    <div blur="<?php echo $custom_background['blur']; ?>"></div>
                    <div class="overlay"<?php echo $custom_background['overlay']; ?>></div>
                <?php endif; ?>
                <header class="text-center" style="position: relative;">
                    <div class="sect">
                        <div class="line line-left"></div>
                        &sect;
                        <div class="line line-right"></div>
                    </div>
                    <h2><?php the_title(); ?></h2>
                </header>
                <div class="container">

                    <?php get_template_part('content', 'page'); ?>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template
                    if (comments_open() || '0' != get_comments_number()) {
                        ?>

                        <?php comments_template(); ?>

                        <?php
                    }
                    ?>

                </div>
            </div>
        </article>
    <?php endif; ?>
<?php endwhile; // end of the loop.    ?>
<?php get_footer(); ?>
