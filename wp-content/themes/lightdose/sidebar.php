<?php
/**
 * The sidebar containing the menu
 *
 * Displays on posts and pages.
 *
 * @package Light Dose
 */
?>
<?php $theme_light_dose_options = get_option('theme_light_dose_options'); ?>
<aside class="col-lg-offset-1 col-md-offset-1 col-lg-3 col-md-3 col-sm-4 col-xs-12">
    <ul class="widget">
<?php dynamic_sidebar('left-sidebar'); ?>
    </ul>
    <ul class="text-left list-inline grid reverse social clear">
<?php if (isset($theme_light_dose_options['blog_social'])) : ?>
            <?php echo do_shortcode($theme_light_dose_options['blog_social']); ?>
        <?php endif; ?>
    </ul>
</aside>