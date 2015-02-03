<?php
/*
  Plugin Name: wpecommerce-autocart
  Plugin URI:
  Description:
  Version: 1.0.0
  Author: Abhishek
  Author URI:
  License: GPLv2
 */

function doOnLoad() {
    if (!is_admin()) {
        global $woocommerce;
        $product_id = 1025;
        $found = false;

        //check if product already in cart
        if (sizeof($woocommerce->cart->get_cart()) > 0) {
            foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
                $_product = $values['data'];
                if ($_product->id == $product_id)
                    $found = true;
            }
            // var_dump($found);
            // if product not found, add it
            if (!$found) {
                $cc = $woocommerce->cart->add_to_cart($product_id, 2841);
            } else {
                // $cc = $woocommerce->cart->add_to_cart($product_id);
            }
        } else {
            //var_dump('No cart content I must lod 2841');
            // if no products in cart, add it
            $cc = $woocommerce->cart->add_to_cart($product_id, 2841);
        }
    }
}

add_action('init', 'doOnLoad');

function register_autocart_widget() {
    register_widget('AutoCart_Widget');
}

add_shortcode('autocart', 'doAutoCart');

function doAutoCart($args = array()) {
    global $woocommerce;
    $a = shortcode_atts(array(
        'text' => 'this is terms and conditions',
        'product_id' => 1025,
        'quantity' => 2841
            ), $args);
    if (!empty($a['text'])) {
        ?>
        <style>
            .acoverlay{
               position: fixed;
left: 0;
top: 0;
background: rgba(0,0,0,0.6);
width: 100%;
height: 100%;
display:none;
z-index:333331;
            }
            .fncybox{
background: #fff;
padding: 1em 1em;
z-index: 333333;
text-align: left;
position: fixed;
top: 25%;
left: 50%;
display:none;
transform: translate(-50%,-50%);
            }
            
            #accept_terms[disabled='true']{
                background: #ccc;
            }
        </style>
<div class='acoverlay'></div>
        <div class="fncybox">
                <div style="height: 250px;overflow-y: auto" id="scrollbox">
                    <?php
                    echo str_replace("%total%", "<span class='crtttl'>" . $woocommerce->cart->get_total() . "</span>", html_entity_decode($a['text']));
                    ?>
                </div>
                <br/>
                <?php ?>
                <p>
                    <input type='button' disabled="true" value='Scroll Down To Enable' id='accept_terms'/>
                </p>
        </div>
        <script>
            var accheckouturl = "<?php echo $woocommerce->cart->get_checkout_url() ?>";
	   var request = JSON.parse('<?php echo json_encode($_GET['key'])?>');
        </script>
        <?php
	
        wp_enqueue_script('checkoutcontroller', plugin_dir_url(__FILE__) . "/js/checkoutcontroller.js", array('jquery'));
    }
}

add_action('wp_ajax_delete_auto_get_cart_total', 'auto_get_cart_total');

add_action('wp_ajax_nopriv_auto_get_cart_total', 'auto_get_cart_total');

function auto_get_cart_total() {
    extract($_REQUEST);
    parse_str($data, $output);
    extract($output);
    global $woocommerce;
    echo $woocommerce->cart->get_cart_total();
    wp_die();
}

//add ajax library
add_action('wp_head', 'add_ajax_library');

/**
 * Adds the WordPress Ajax Library to the frontend.
 */
function add_ajax_library() {

    $html = '<script type="text/javascript">';
    $html .= 'var ajaxurl = "' . admin_url('admin-ajax.php') . '"';
    $html .= '</script>';

    echo $html;
}