<?php
/*
VarkTech Minimum Purchase for WP E-Commerce
WPSC-specific functions
Parent Plugin Integration
*/


class VTMIN_Parent_Functions {
	
	public function __construct(){
    
 
	}
	
 	
	public function vtmin_load_vtmin_cart_for_processing(){
      global $wpdb, $wpsc_cart, $vtmin_cart, $vtmin_cart_item, $vtmin_info; 

      $vtmin_cart = new VTMIN_Cart;  

      foreach($wpsc_cart->cart_items as $key => $cart_item) {
        $vtmin_cart_item                = new VTMIN_Cart_Item;
        //load up the wpsc values into $vtmin_cart_item
        $vtmin_cart_item->product_id    = $cart_item->product_id;
        
        /* there's a WPEC variation title bug in the checkout cart.  Verify if product is 
        * a variation id, then if so, verify that the title has an 
        * open paren (standard variaiton title naming).  If not, go
        * directly to the variation post and get the title.                        
        */
        if (sizeof ($cart_item->variation_values) > 0 ) {  
           if ( !strstr($cart_item->product_name, '(') ) {
              $var_post = get_post($cart_item->product_id);
              $cart_item->product_name = $var_post->post_title ;
           }
        } 

        $vtmin_cart_item->product_name  = $cart_item->product_name;
        $vtmin_cart_item->quantity      = $cart_item->quantity;
        $vtmin_cart_item->unit_price    = $cart_item->unit_price;
        $vtmin_cart_item->total_price   = $cart_item->total_price;
       
        /*  *********************************
        ***  JUST the cat *ids* please...
        ************************************ */
        $vtmin_cart_item->prod_cat_list = wp_get_object_terms( $cart_item->product_id, $vtmin_info['parent_plugin_taxonomy'], $args = array('fields' => 'ids') );
        $vtmin_cart_item->rule_cat_list = wp_get_object_terms( $cart_item->product_id, $vtmin_info['rulecat_taxonomy'], $args = array('fields' => 'ids') );
        //*************************************              
        
        //add cart_item to cart array
        $vtmin_cart->cart_items[]       = $vtmin_cart_item; 
        
      } 
           
  }
      
 
   /*
    *  checked_list (o) - selection list from previous iteration of rule selection                               
    *                           
   */
   
   /*
      $product_variation_IDs = $this->vtmin_get_variations_list($product_ID);
      if ($product_variation_IDs) {
         $this->vtmin_post_category_meta_box($post, array( 'args' => array( 'taxonomy' => 'variations', 'tax_class' => 'var-in', 'checked_list' => $vtmin_rule->var_in_checked, 'product_ID' => $product_ID, 'product_variation_IDs' => $product_variation_IDs )));/
         /perform  vtmin_post_category_meta_box , use $tax_class = 'variations'
         // in the vtmin_post_category_meta_box
         $vtmin_parent_functions->vtmin_fill_variations_checklist($tax_class, $checked_list, $product_ID, $product_variation_IDs);  //add checked logic later
      } 
   */
    public function vtmin_fill_variations_checklist ($tax_class, $checked_list = NULL, $product_ID, $product_variation_IDs) { 
        global $post;
                  
       // echo '<br>$product_variation_IDs = <pre>'.print_r( $product_variation_IDs , true).'</pre><br>' ; 
       // echo '<br>$checked_list = <pre>'.print_r( $checked_list , true).'</pre><br>' ; 
        
        foreach ($product_variation_IDs as $product_variation_ID) {     //($product_variation_IDs as $product_variation_ID => $info)
            $post = get_post($product_variation_ID);
            $output  = '<li id='.$product_variation_ID.'>' ;
            $output  .= '<label class="selectit">' ;
            $output  .= '<input id="'.$product_variation_ID.'_'.$tax_class.' " ';
            $output  .= 'type="checkbox" name="tax-input-' .  $tax_class . '[]" ';
            $output  .= 'value="'.$product_variation_ID.'" ';
            if ($checked_list) {
                if (in_array($product_variation_ID, $checked_list)) {   //if variation is in previously checked_list   
                   $output  .= 'checked="checked"';
                }                
            }
            $output  .= '>'; //end input statement
            $output  .= '&nbsp;' . $post->post_title;
            $output  .= '</label>';            
            $output  .= '</li>';
              echo $output ;
         }
        return;   
    }
    

  /* ************************************************
  **   Get all variations for product
  *************************************************** */
  public function vtmin_get_variations_list($product_ID) {
        
    //do variations exist?
    $product_has_variations = $this->vtmin_test_for_variations ($product_ID); 
    
    if ($product_has_variations == "yes") {    
      //get all variation IDs (title will be obtained in checkbox logic)
      /*Loop through product variations saved previously and create array of the variations *only* 
      * tt.`parent` > '0' ==> parent = 0 indicates a variation set name rather than a variation set member    
      * the inner select gets the 'child' variation posts (status = 'inherit'), then the outer select passes by the variation set name post  
      * 
      *the inner select will eventually be slow, but won't be accessed that often, so is currently acceptable.  The alternative is massively complex
      * (use db_id to go to term_rel and get the variation set name term_tax_id, get all of the term_tax_ids of the varition set and variations, get all of the obj_id's they own and compare to posts.id...)                    	
       */
      global $wpdb;
    	$varsql = "SELECT tr.`object_id` 
          FROM `".$wpdb->term_relationships."` AS tr 
    			LEFT JOIN `".$wpdb->term_taxonomy."` AS tt
          ON  tr.`term_taxonomy_id` = 	tt.`term_taxonomy_id`	
    			WHERE  tr.`object_id` in 
               ( SELECT posts.`id` 
            			FROM `".$wpdb->posts."` AS posts			
            			WHERE posts.`post_status` = 'inherit' AND posts.`post_parent`= '" . $product_ID . "'
                )
           AND  tt.`parent` > '0'      
            ";                    
    	$product_variations_list = $wpdb->get_col($varsql);  // yields an array of child post ids (variations, where the $$, sku etc are held).
    } else  {
      $product_variations_list;
    }
    
    return ($product_variations_list);
  } 
  
  
  public function vtmin_test_for_variations ($prod_ID) { 
     $vartest_response = 'no';
     if ( wpsc_product_has_variations( $prod_ID ) )  {
        $vartest_response = 'yes';
     }
      return ($vartest_response);   
  }     

  //v1.07 begin
    
   function vtmin_format_money_element($price) { 
      //from woocommerce/woocommerce-core-function.php   function woocommerce_price
    	$return          = '';
    	$num_decimals    = (int) get_option( 'woocommerce_price_num_decimals' );
    	$currency_pos    = get_option( 'woocommerce_currency_pos' );
    	$currency_symbol = get_woocommerce_currency_symbol();
    	$decimal_sep     = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), ENT_QUOTES );
    	$thousands_sep   = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ), ENT_QUOTES );
    
    	$price           = apply_filters( 'raw_woocommerce_price', (double) $price );
    	$price           = number_format( $price, $num_decimals, $decimal_sep, $thousands_sep );
    
    	if ( get_option( 'woocommerce_price_trim_zeros' ) == 'yes' && $num_decimals > 0 )
    		$price = woocommerce_trim_zeros( $price );
    
    	//$return = '<span class="amount">' . sprintf( get_woocommerce_price_format(), $currency_symbol, $price ) . '</span>'; 

    $current_version =  WOOCOMMERCE_VERSION;
    if( (version_compare(strval('2'), strval($current_version), '>') == 1) ) {   //'==1' = 2nd value is lower     
      $formatted = number_format( $price, $num_decimals, stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) );
      $formatted = $currency_symbol . $formatted;
    } else {
      $formatted = sprintf( get_woocommerce_price_format(), $currency_symbol, $price );
    }
          
     return $formatted;
   }
   
   //****************************
   // Gets Currency Symbol from PARENT plugin   - only used in backend UI during rules update
   //****************************   
  function vtmin_get_currency_symbol() {    
    return get_woocommerce_currency_symbol();  
  } 


  function vtmin_debug_options(){   //v1.09 updated function
    global $vtmin_setup_options;
    if ( ( isset( $vtmin_setup_options['debugging_mode_on'] )) &&
         ( $vtmin_setup_options['debugging_mode_on'] == 'yes' ) ) {  
      error_reporting(E_ALL);  
    }  else {
      error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED ^ E_STRICT ^ E_USER_DEPRECATED ^ E_USER_NOTICE ^ E_USER_WARNING ^ E_RECOVERABLE_ERROR );    //only allow FATAL error types
    }
  }
  //v1.07 END      
} //end class
$vtmin_parent_functions = new VTMIN_Parent_Functions;