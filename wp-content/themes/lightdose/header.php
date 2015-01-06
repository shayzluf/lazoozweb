<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Light Dose
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <?php $light_dose_options_theme = get_option('light_dose_theme_options'); ?>
        <?php if(isset($light_dose_options_theme['theme_color'])) : ?>
        <?php $rgb = implode(", ", hex2rgb($light_dose_options_theme['theme_color'])); ?>
        <?php else: ?>
        <?php $rgb = implode(", ", hex2rgb('#054383')); ?>
        <?php endif; ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">        
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <script type="text/javascript">
            var $ = jQuery;
            var element = document.createElement( 'div' );
                element.id = 'spin';
                element.style.position = 'fixed';
                element.style.zIndex = 10000;
                element.style.top = 0;
                element.style.right = 0;
                element.style.bottom = 0;
                element.style.left = 0;
                element.style.width = '100%';
                element.style.height = '100%';
                element.style.background = '#FFF';
                document.body.appendChild( element );
                //	Starting a spin

                //<!--BEGIN Spinner options-->
                var opts = {
                  lines: 7, // The number of lines to draw
                  length: 0, // The length of each line
                  width: 7, // The line thickness
                  radius: 7, // The radius of the inner circle
                  corners: 0.9, // Corner roundness (0..1)
                  rotate: 0, // The rotation offset
                  direction: 1, // 1: clockwise, -1: counterclockwise
                  color: '#000', // #rgb or #rrggbb or array of colors
                  speed: 1.2, // Rounds per second
                  trail: 85, // Afterglow percentage
                  shadow: false, // Whether to render a shadow
                  hwaccel: false, // Whether to use hardware acceleration
                  className: 'spinner', // The CSS class to assign to the spinner
                  zIndex: 2e9, // The z-index (defaults to 2000000000)
                  top: 'auto', // Top position relative to parent in px
                  left: 'auto' // Left position relative to parent in px
                };
                //<!--END Spinner options-->
                var target = document.getElementById( 'spin' );
                var spinner = new Spinner( opts ).spin( target );
                var throttle = <?php echo!isset($light_dose_options_theme['throttle']) || !is_numeric($light_dose_options_theme['throttle']) ? 200 : $light_dose_options_theme['throttle']; ?>;
        </script>
        <style type="text/css">
            body {
                font-family: <?php echo isset($light_dose_options_theme['font_family']) && !empty($light_dose_options_theme['font_family']) ? $light_dose_options_theme['font_family'] : "'Lato', 'Helvetica Neue Light', 'Helvetica Neue', 'Segoe UI', sans-serif"; ?>;
                font-size: <?php echo isset($light_dose_options_theme['font_size']) ? $light_dose_options_theme['font_size'] : 16; ?>px; /* 100% 1em */
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: <?php echo isset($light_dose_options_theme['font_family']) && !empty($light_dose_options_theme['font_family']) ? $light_dose_options_theme['font_family'] : "'Lato', 'Helvetica Neue Light', 'Helvetica Neue', 'Segoe UI', sans-serif"; ?>;
            }
            /*--------------------------------------------------------------------*/
            @media ( min-width: 1025px ) {
              .theme-white.links a:hover {
                color: rgb( <?php echo $rgb; ?> );
                border-bottom: 1px rgba( <?php echo $rgb; ?>, 0.5 ) solid;
              }
              .theme-white.links a:active,
              .theme-white.links a:focus {
                color: rgb( <?php echo $rgb; ?> );
                border-bottom: 1px rgb( <?php echo $rgb; ?> ) solid;
              }
              .theme-white .grid.social-small a:hover,
              .theme-white .grid.social-small a:active,
              .theme-white .grid.social-small a:focus {
                color: rgb( <?php echo $rgb; ?> ) !important;
              }
              .theme-white .nav-footer > li > a:hover,
              .theme-white .nav-footer > li > a:focus,
              .theme-white .nav-footer > li > a:active {
                border-bottom: 2px rgba( <?php echo $rgb; ?>, 0.2 ) solid;
              }
              .theme-white.works .tags a:active,
              .theme-white.works .tags a:focus {
                background-color: rgb( <?php echo $rgb; ?> );
              }
                .theme-white .btn:hover {
                  background-color: rgb( <?php echo $rgb; ?> ) !important;
                }
              /*/----------------------------------------------------------/*/
                .theme-color .btn:hover {
                  color: rgb( <?php echo $rgb; ?> ) !important;
                }
            }
          /*--------------------------------------------------------------------*/
          .theme-white.foreground .caption.color {
            color: rgb( <?php echo $rgb; ?> );
          }
          .theme-white.links a {
            color: rgb( <?php echo $rgb; ?> );
            border-bottom: 1px rgba( <?php echo $rgb; ?>, 0.1 ) solid;
          }
          .theme-white.links a:link {
            color: rgb( <?php echo $rgb; ?> );
            border-bottom: 1px rgba( <?php echo $rgb; ?>, 0.1 ) solid;
          }
          .theme-white.links a:visited {
            color: rgb( <?php echo $rgb; ?> );
            border-bottom: 1px rgba( <?php echo $rgb; ?>, 0.1 ) solid;
          }
          .theme-white form .checkbox > input[type=checkbox]:checked + label:before {
            background-color: rgb( <?php echo $rgb; ?> );
          }
          .theme-white form .radio > input[type=radio]:checked + label:before {
            background-color: rgb( <?php echo $rgb; ?> );
          }
          .theme-white .blueimp a .overlay {
            background-color: rgba( <?php echo $rgb; ?>, 0.85 );
          }
          .theme-white .nav > li > a > .overlay {
            background-color: rgba( <?php echo $rgb; ?>, 0.3 );
          }
          .theme-white .menu.menu-dropdown > .active > a {
            border-bottom: 2px rgba( <?php echo $rgb; ?>, 0.3 ) solid !important;
          }
          .theme-white.works .grid li .overlay {
            background-color: rgba( <?php echo $rgb; ?>, 0.8 );
          }
          .theme-white.team .row > div > .wrapper > .overlay {
            background-color: rgba( <?php echo $rgb; ?>, 0.1 );
          }
          .theme-white .nav-footer > li > a,
          .theme-white .nav-footer > li > a:link,
          .theme-white .nav-footer > li > a:active,
          .theme-white .nav-footer > li > a:visited {
            color: rgb( <?php echo $rgb; ?> );
          }
          .theme-white a.more div.arrow {
            border-top: 1px rgb( <?php echo $rgb; ?> ) solid;
          }
          .theme-white a.more div.arrow span.tip {
            border-color: transparent transparent transparent rgb( <?php echo $rgb; ?> );
          }
          .theme-white.works .tags a.active {
            background-color: rgb( <?php echo $rgb; ?> );
          }
          .theme-white.blog .comment .entry-meta .reply {
            color: rgb( <?php echo $rgb; ?> );
          }
          .theme-white.faq .accordion .caption-wrapper .sign:before {
            color: rgb( <?php echo $rgb; ?> );
          }
          .theme-white.pricing .package,
          .theme-white.pricing .package:link,
          .theme-white.pricing .package:hover,
          .theme-white.pricing .package:active,
          .theme-white.pricing .package:focus,
          .theme-white.pricing .package:visited {
            background-color: rgb( <?php echo $rgb; ?> );
            border-color: rgb( <?php echo $rgb; ?> );
          }
            .theme-white .btn {
              color: rgb( <?php echo $rgb; ?> ) !important;
              border: 2px rgb( <?php echo $rgb; ?> ) solid !important;
            }
            .theme-white .btn:active/*,
            .theme-white .btn:focus*/ {
              background-color: rgba( <?php echo $rgb; ?>, 0.9 ) !important;
            }
          /*--------------------------------------------------------------------*/
          .theme-color.background {
            background-color: rgb( <?php echo $rgb; ?> );
          }
          .theme-color .underlay {
            background-color: rgba( <?php echo $rgb; ?>, 0.95 );
          }
          .theme-color.pricing .underlay {
            background-color: rgba( <?php echo $rgb; ?>, 0.5 );
          }
          .theme-color form .checkbox > input[type=checkbox]:checked + label:before {
            color: rgb( <?php echo $rgb; ?> );
          }
          .theme-color form .radio > input[type=radio]:checked + label:before {
            color: rgb( <?php echo $rgb; ?> );
          }
          .theme-color .menu.menu-dropdown {
            background-color: rgb( <?php echo $rgb; ?> );
          }
          .theme-color.faq .accordion .caption-wrapper .caption {
            background-color: rgb( <?php echo $rgb; ?> );
          }
          .theme-color.faq .accordion .caption-wrapper .sign:before {
            background-color: rgb( <?php echo $rgb; ?> );
          }
          .theme-color .menu .dropdown-slide {
            background-color: rgba( <?php echo $rgb; ?>, 0.97 );
          }
            .theme-color .btn:active/*,
            .theme-color .btn:focus*/ {
              color: rgb( <?php echo $rgb; ?> ) !important;
            }
            .theme-color .aside.search .overlay {
              background-color: rgba( <?php echo $rgb; ?>, 0.9 );
            }
          /*--------------------------------------------------------------------*/
            .theme-white.links a.demo.white,
            .theme-white.links a.demo.link.white {
              color: rgb( <?php echo $rgb; ?> ) !important;
              border-bottom: 1px rgba( <?php echo $rgb; ?>, 0.1 ) solid !important;
            }
            .theme-white.links a.demo.hover.white {
              color: rgb( <?php echo $rgb; ?> ) !important;
              border-bottom: 1px rgba( <?php echo $rgb; ?>, 0.5 ) solid !important;
            }
            .theme-white.links a.demo.active-focus.white {
              color: rgb( <?php echo $rgb; ?> ) !important;
              border-bottom: 1px rgb( <?php echo $rgb; ?> ) solid !important;
            }
            .theme-white.links a.demo.visited.white {
              color: rgb( <?php echo $rgb; ?> ) !important;
              border-bottom: 1px rgba( <?php echo $rgb; ?>, 0.1 ) solid !important;
            }
            .theme-white input[type=submit].btn.demo.hover.white {
              background-color: rgb( <?php echo $rgb; ?> ) !important;
            }
            .theme-white input[type=submit].btn.demo.active-focus.white {
              background-color: rgba( <?php echo $rgb; ?>, 0.9 ) !important;
            }
          /*--------------------------------------------------------------------*/
            .theme-color input[type=submit].demo.hover.color {
              color: rgb( <?php echo $rgb; ?> ) !important;
            }
            .theme-color input[type=submit].demo.active-focus.color {
              color: rgb( <?php echo $rgb; ?> ) !important;
            }
        </style>
        <style type="text/css">            
<?php if (!empty($light_dose_options_theme['services_background'])) : ?>
                .services{background-image: url( '<?php echo $light_dose_options_theme['services_background']; ?>' );}
                .services .underlay{background-color: transparent;}
<?php endif; ?>
<?php if (!empty($light_dose_options_theme['services_blurred'])) : ?>
                .services .overlay{background-image: url( '<?php echo $light_dose_options_theme['services_blurred']; ?>' );}
<?php endif; ?>
<?php if (!empty($light_dose_options_theme['stories_background'])) : ?>
                .stories{background-image: url( '<?php echo $light_dose_options_theme['stories_background']; ?>' );}
<?php endif; ?>
<?php if (!empty($light_dose_options_theme['stories_background'])) : ?>
                .action{background-image: url( '<?php echo $light_dose_options_theme['action_background']; ?>' );}
<?php endif; ?>
<?php if (!empty($light_dose_options_theme['pricing_background'])) : ?>
                .pricing{background-image: url( '<?php echo $light_dose_options_theme['pricing_background']; ?>' );}
<?php endif; ?>
<?php if (!empty($light_dose_options_theme['contacts_background'])) : ?>
                .contacts{background-image: url( '<?php echo $light_dose_options_theme['contacts_background']; ?>' );}
<?php endif; ?>
        </style>
        <?php
        $light_dose_options = get_option('theme_light_dose_options');
        if(isset($light_dose_options['site_bg'])) {
            if ($light_dose_options['site_bg'] == 2) {
                $result = '<div class="overlay">';
                $result .= '<video muted="muted" loop preload autoplay>';
                if (!empty($light_dose_options['video_webm'])) {
                    $result .= '<source src="' . $light_dose_options['video_webm'] . '" type="video/webm" />';
                }
                if (!empty($light_dose_options['video_mp4'])) {
                    $result .= '<source src="' . $light_dose_options['video_mp4'] . '" type="video/mp4" />';
                }
                if (!empty($light_dose_options['video_ogg'])) {
                    $result .= '<source src="' . $light_dose_options['video_ogg'] . '" type="video/ogg" />';
                }            
                $result .= '</video>';
                $result .= '</div>';
                echo $result;
            }
            if ($light_dose_options['site_bg'] == 1) {
                $blur = 0;
                if($light_dose_options['background_blur'] > 0) {
                    $blur = $light_dose_options['background_blur'];
                }
                $result = '<div class="overlay"'. ($blur > 0 ? ' blur="' . $blur . '"' : '') .'>';
                $result .= '<img src="' . $light_dose_options['background_image'] . '" alt="video" />';
                $result .= '</div>';
                echo $result;
           }
        }
        ?>
        <header class="theme-white background foreground topper" id="navigation">
            <div class="container stretch-height text-right">
                <a class="logo scroll stretch-height pull-left text-left" href="<?php echo home_url(); ?>#slider">
                    <div class="image pull-left">
                        <?php if (!empty($light_dose_options_theme['logo'])): ?>
                            <img src="<?php echo $light_dose_options_theme['logo']; ?>" />
                        <?php endif; ?>
                    </div>
                    <div class="text pull-left">
                        <?php echo isset($light_dose_options_theme['logo_text']) ? $light_dose_options_theme['logo_text'] : __('Light Dose<br />Flat & Minimal', 'light_dose'); ?>
                    </div>
                </a>                
                <div class="menu">
                    <div class="stretch-both">
                        <?php
                        if (has_nav_menu('primary')) {
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'container' => false,
                                'menu_class' => "nav list-inline navbar-nav scale-up",
                                'walker' => new description_walker,
                            ));
                        }
                        ?>
                    </div>
                </div>
                <?php if (function_exists('icl_get_languages')) : ?>
                    <?php $languages = icl_get_languages(); ?>                    
                    <?php if (!empty($languages)) : ?>
                        <div class="language-menu">
                            <div class="stretch-both">
                                <ul class="nav list-inline navbar-nav">                                    
                                    <?php foreach ($languages as $language) : ?>
                                        <?php if ($language['active']) : ?>
                                    <li class="selected"><a href="javascript:void(0)"><?php echo ucfirst($language['language_code']); ?></a></li>
                                        <?php else : ?>
                                            <li><a href="<?php echo $language['url']; ?>"><?php echo ucfirst($language['language_code']); ?></a></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <button type="button" class="menu-toggle fa">
                    &#xf0c9;
                </button>
            </div>
        </header>