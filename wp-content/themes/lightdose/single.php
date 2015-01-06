<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Light Dose
 */
get_header();
?>
<div class="theme-white background">			
    
    <section class="container theme-white background foreground links lower blog" id="blog">
        <div class="row">
            <aside class="col-lg-offset-1 col-md-offset-1 col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <ul class="widget">
                    <?php dynamic_sidebar('left-sidebar'); ?>
                </ul>
            </aside>
            <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12">
                <?php while (have_posts()) : the_post(); ?>

                    <?php get_template_part('content', 'single'); ?>	

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template
                    /*
                    if (comments_open() || '0' != get_comments_number())
                        comments_template();
                     * 
                     */
                    ?>

                    <div class="container">
                        <?php light_dose_content_nav('nav-below'); ?>
                    </div>


                <?php endwhile; // end of the loop.  ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>