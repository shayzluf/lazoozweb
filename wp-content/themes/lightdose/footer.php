<!--BEGIN Footer-->
<footer class="footer theme-color background foreground text-center">
    <?php
    if (has_nav_menu('footer-menu')) {
        wp_nav_menu(array(
            'theme_location' => 'footer-menu',
            'container' => false,
            'menu_class' => "list-inline nav-footer",
            'walker' => new description_walker_footer,
        ));
    }
    $light_dose_options_theme = get_option('light_dose_theme_options');
    $light_dose_options = get_option('theme_light_dose_options');
    ?>    
    <div class="copyright">
        <?php echo isset($light_dose_options_theme['copyright']) ? $light_dose_options_theme['copyright'] : '' ?>
        <div class="sect">
            &sect;
        </div>
    </div>
    <div class="inner-wrapper text-center stretch-width">
        <div>
            <!--BEGIN Footer social-->
            <ul class="list-inline grid reverse social">                
                <?php echo do_shortcode($light_dose_options['footer_social']); ?>
            </ul>
            <!--END Footer social-->
        </div>
    </div>
</footer>
<!--END Footer-->

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides">
    </div>
    <h3 class="title">
    </h3>
    <a class="prev">
        ‹
    </a>
    <a class="next">
        ›
    </a>
    <a class="close">
        ×
    </a>
    <a class="play-pause">
    </a>
    <ol class="indicator">
    </ol>
</div>
<?php wp_footer(); ?>
</body>
</html>