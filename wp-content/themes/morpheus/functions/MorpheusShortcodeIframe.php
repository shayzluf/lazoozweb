<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sQrt121
 * Date: 9/16/13
 * Time: 2:36 PM
 * To change this template use File | Settings | File Templates.
 */

include_once('MorpheusShortCodeScriptLoader.php');

class MorpheusShortcodeIframe extends MorpheusShortCodeScriptLoader
{

    static $addedAlready = false;

    public function handleShortcode($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'class' => ''
        ), $atts));

	    $class .= 'coll-iframe-wrap js-coll-iframe';

        $output = '';
	    $output .= '<div class="' . $class . '">';
        $output .= $content;
        $output .= '</div>';

        return $output;
    }

    public function addScript()
    {
        if (!self::$addedAlready) {
            self::$addedAlready = true;

        }
    }
}

$sc = new MorpheusShortcodeIframe();
$sc->register('coll_iframe');

class MorpheusShortcodeTncButton extends MorpheusShortCodeScriptLoader
{

    static $addedAlready = false;

    public function handleShortcode($atts, $content = null)
    {
        
        $output = '';
	    $output .= "<button onclick='_gaq.push(['_trackEvent', 'outbound-article-int', 'http://lazooz.org/?termsandconds=true&amp;max-width:90%&amp;height=400', 'Terms and Conditions'/]);'>";
        $output .= Terms;
        $output .= '</button>';

        return $output;
    }

    public function addScript()
    {
        if (!self::$addedAlready) {
            self::$addedAlready = true;

        }
    }
}

$sctnc = new MorpheusShortcodeTncButton();
$sctnc->register('coll_tnc');