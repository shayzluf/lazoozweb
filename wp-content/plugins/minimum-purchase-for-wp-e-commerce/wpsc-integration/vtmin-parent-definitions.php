<?php
/*
WPSC-specific functions
Parent Plugin Integration
*/


class VTMIN_Parent_Definitions {
	
	public function __construct(){
    
    define('VTMIN_PARENT_PLUGIN_NAME',                      'WP E-Commerce');
    define('VTMIN_EARLIEST_ALLOWED_PARENT_VERSION',         '3.8');
    define('VTMIN_TESTED_UP_TO_PARENT_VERSION',             '3.8.8.5');
    define('VTMIN_DOCUMENTATION_PATH_PRO_BY_PARENT',        'http://www.varktech.com/wp-e-commerce/minimum-purchase-pro-for-wp-e-commerce/?active_tab=tutorial');                                                                                                     //***
    define('VTMIN_DOCUMENTATION_PATH_FREE_BY_PARENT',       'http://www.varktech.com/wp-e-commerce/minimum-purchase-for-wp-e-commerce/?active_tab=tutorial');      
    define('VTMIN_INSTALLATION_INSTRUCTIONS_BY_PARENT',     'http://www.varktech.com/wp-e-commerce/minimum-purchase-for-wp-e-commerce/?active_tab=instructions');
    define('VTMIN_PRO_INSTALLATION_INSTRUCTIONS_BY_PARENT', 'http://www.varktech.com/wp-e-commerce/minimum-purchase-pro-for-wp-e-commerce/?active_tab=instructions');
    define('VTMIN_PURCHASE_PRO_VERSION_BY_PARENT',          'http://www.varktech.com/wp-e-commerce/minimum-purchase-pro-for-wp-e-commerce/');
    define('VTMIN_DOWNLOAD_FREE_VERSION_BY_PARENT',         'http://wordpress.org/extend/plugins/minimum-purchase-for-wp-e-commerce/');
    
    //html default selector locations in checkout where error message will display before.
    define('VTMIN_CHECKOUT_PRODUCTS_SELECTOR_BY_PARENT',    '.checkout_cart');
    define('VTMIN_CHECKOUT_ADDRESS_SELECTOR_BY_PARENT',     '.wpsc_checkout_forms');

    global $vtmin_info;      
    $vtmin_info = array(                                                                    
      	'parent_plugin' => 'wpsc',
      	'parent_plugin_taxonomy' => 'wpsc_product_category',
        'parent_plugin_taxonomy_name' => 'Product Category',
        'parent_plugin_cpt' => 'wpsc_product',
        'applies_to_post_types' => 'wpsc-product', //rule cat only needs to be registered to product, not rule as well...
        'rulecat_taxonomy' => 'vtmin_rule_category',
        'rulecat_taxonomy_name' => 'Minimum Purchase Rules',
        
        //elements used in vtmin-apply-rules.php at the ruleset level
        'error_message_needed' => 'no',
        'cart_grp_info' => '',
          /*  cart_grp_info will contain the following:
            array(
              'qty'    => '',
              'price'    => ''
            )
          */
        'cart_color_cnt' => '',
        'rule_id_list' => '',
        'line_cnt' => 0,
        'action_cnt'  => 0,
        'bold_the_error_amt_on_detail_line'  => 'no'
      );

	}
	
  
} //end class
$vtmin_parent_definitions = new VTMIN_Parent_Definitions;