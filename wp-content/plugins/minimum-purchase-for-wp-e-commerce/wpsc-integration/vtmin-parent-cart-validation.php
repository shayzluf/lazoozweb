<?php
/*
VarkTech Minimum Purchase for WP E-Commerce
WPSC-specific functions
Parent Plugin Integration
*/


class VTMIN_Parent_Cart_Validation {
	
	public function __construct(){
    
     /*  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++   
     *        Apply Minimum Amount Rules to ecommerce activity
     *                                                          
     *          WPSC-Specific Checkout Logic and triggers 
     *                                               
     *  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++   */
     
          
    //the form validation filter executes ONLY at click-to-pay time to invalidate purchase if rule criteria not met                                                                           
    add_filter( 'wpsc_checkout_form_validation', array(&$this, 'vtmin_wpsc_checkout_form_validation'), 1);         
    
    /*  =============+                                    
    *  add action for entry into the shopping cart page -
    *    if we are on the shopping cart page, use the init action to run the apply rule function
    *    and display any minimum purchase error messages at first viewof checkout page
    *            
       =============+                                    */
    $shopping_cart_url = get_option ('shopping_cart_url');    
    if ( $shopping_cart_url == $this->vtmin_currPageURL() ) {
        /*
          Priority of 99 to delay add_action execution.  Works normally on the 1st
          time through, and on any page refreshes.  The action kicks in 1st time on the page, and
          we're already on the shopping cart page and change to quantity happens.  The
          priority delays us in the exec sequence until after the quantity change has
          occurred, so we pick up the correct altered state.
          
          wpsc's own quantity change using:
               if ( isset( $_REQUEST['wpsc_update_quantity'] ) && ($_REQUEST['wpsc_update_quantity'] == 'true') ) {
        	add_action( 'init', 'wpsc_update_item_quantity' );
       */
       add_action( 'init', array(&$this, 'vtmin_wpsc_apply_checkout_cntl'),99 ); 
 
    }                                                                               
   /*  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++    */
	}
	

           
  /* ************************************************
  **   Application - Apply Rules at E-Commerce Checkout
  *************************************************** */
	public function vtmin_wpsc_apply_checkout_cntl(){
    global $vtmin_cart, $vtmin_cart_item, $vtmin_rules_set, $vtmin_rule;
        
    //input and output to the apply_rules routine in the global variables.
    //    results are put into $vtmin_cart
    
    /*  We arrive here from a couple of different filters, depending on the situation.
    If error messages already processed (any error messages would be already processed 
    and the js 'injected') - no further processing.
    */
    if ( $vtmin_cart->error_messages_processed == 'yes' ) {  
      return;  
    }
    
     $vtmin_apply_rules = new VTMIN_Apply_Rules;   
    
    //ERROR Message Path
    if ( sizeof($vtmin_cart->error_messages) > 0 ) {      
      //insert error messages into checkout page
      add_action( "wp_enqueue_scripts", array($this, 'vtmin_enqueue_error_msg_css') );
      add_action('wp_head', array(&$this, 'vtmin_display_rule_error_msg_at_checkout') );  //JS to insert error msgs 
    }     
  } 
    
           
  /* ************************************************
  **   Application - Apply Rules when Payment button is pressed at checkout
  *
  * filter comes from wpsc-inlcudes/checkout.class.php -  
  *   $states array is part of the filter call, and must be returned.   
  *************************************************** */
	public function vtmin_wpsc_checkout_form_validation($states){
    global $vtmin_cart, $vtmin_cart_item, $vtmin_rules_set, $vtmin_rule;
        
    //input and output to the apply_rules routine in the global variables.
    //    results are put into $vtmin_cart
    
    /*  We arrive here from a couple of different filters, depending on the situation.
    If error messages already processed (any error messages would be already processed 
    and the js 'injected') - no further processing.
    */
    if ( $vtmin_cart->error_messages_processed == 'yes' ) {  
      return $states;  
    }
    
     $vtmin_apply_rules = new VTMIN_Apply_Rules;   
    
    //ERROR Message Path
    if ( sizeof($vtmin_cart->error_messages) > 0 ) {      
      //insert error messages into checkout page
      add_action( "wp_enqueue_scripts", array($this, 'vtmin_enqueue_error_msg_css') );
      add_action('wp_head', array(&$this, 'vtmin_display_rule_error_msg_at_checkout') );  //JS to insert error msgs      
      
      /*  turn on the messages processed switch
          otherwise errors are processed and displayed multiple times when the
          wpsc_checkout_form_validation filter finds an error (causes a loop around, 3x error result...) 
      */
      $vtmin_cart->error_messages_processed = 'yes';
      
      /*  *********************************************************************
        Mark checkout as having failed edits, and can't progress to Payment Gateway. 
        This works only with the filter 'wpsc_checkout_form_validation', which is activated on submit of
        "payment" button. 
      *************************************************************************  */
      $is_valid = false;
      $bad_input_message =  '';
      $states = array( 'is_valid' => $is_valid, 'error_messages' => $bad_input_message );
     }
     
     return $states;   
  } 

  
  /* ************************************************
  **   Application - On Error Display Message on E-Commerce Checkout Screen  
  *************************************************** */ 
  public function vtmin_display_rule_error_msg_at_checkout(){
    global $vtmin_info, $vtmin_cart, $vtmin_setup_options;
     
    //error messages are inserted just above the checkout products, and above the checkout form
      //In this situation, this 'id or class Selector' may not be blank, supply wpsc checkout default - must include '.' or '#'
    if ( $vtmin_setup_options['show_error_before_checkout_products_selector']  <= ' ' ) {
       $vtmin_setup_options['show_error_before_checkout_products_selector'] = VTMIN_CHECKOUT_PRODUCTS_SELECTOR_BY_PARENT;             
    }
      //In this situation, this 'id or class Selector' may not be blank, supply wpsc checkout default - must include '.' or '#'
    if ( $vtmin_setup_options['show_error_before_checkout_address_selector']  <= ' ' ) {
       $vtmin_setup_options['show_error_before_checkout_address_selector'] = VTMIN_CHECKOUT_ADDRESS_SELECTOR_BY_PARENT;             
    }
     ?>     
        <script type="text/javascript">
        jQuery(document).ready(function($) {
    <?php 
    //loop through all of the error messages 
    //          $vtmin_info['line_cnt'] is used when table formattted msgs come through.  Otherwise produces an inactive css id. 
    for($i=0; $i < sizeof($vtmin_cart->error_messages); $i++) { 
     ?>
        <?php 
          if ( $vtmin_setup_options['show_error_before_checkout_products'] == 'yes' ){ 

        ?>
           $('<div class="vtmin-error" id="line-cnt<?php echo $vtmin_info['line_cnt'] ?>"><h3 class="error-title">Minimum Purchase Error</h3><p> <?php echo $vtmin_cart->error_messages[$i]['msg_text'] ?> </p></div>').insertBefore('<?php echo $vtmin_setup_options['show_error_before_checkout_products_selector'] ?>');
        <?php 
          } 
          if ( $vtmin_setup_options['show_error_before_checkout_address'] == 'yes' ){ 
           
        ?>
           $('<div class="vtmin-error" id="line-cnt<?php echo $vtmin_info['line_cnt'] ?>"><h3 class="error-title">Minimum Purchase Error</h3><p> <?php echo $vtmin_cart->error_messages[$i]['msg_text'] ?> </p></div>').insertBefore('<?php echo $vtmin_setup_options['show_error_before_checkout_address_selector'] ?>');
    <?php 
          }
    }  //end 'for' loop      
     ?>   
            });   
          </script>
     <?php    


     /* ***********************************
        CUSTOM ERROR MSG CSS AT CHECKOUT
        *********************************** */
     if ($vtmin_setup_options[custom_error_msg_css_at_checkout] > ' ' )  {
        echo '<style type="text/css">';
        echo $vtmin_setup_options[custom_error_msg_css_at_checkout];
        echo '</style>';
     }
     
     /*
      Turn off the messages processed switch.  As this function is only executed out
      of wp_head, the switch is only cleared when the next screenful is sent.
     */
     $vtmin_cart->error_messages_processed = 'no';   
 } 
 
   
  /* ************************************************
  **   Application - get current page url
  *************************************************** */ 
 public  function vtmin_currPageURL() {
     $pageURL = 'http';
     if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
     if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
     } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
     }
     return $pageURL;
  } 
 
    

  /* ************************************************
  **   Application - On Error enqueue error style
  *************************************************** */
  public function vtmin_enqueue_error_msg_css() {
    wp_register_style( 'vtmin-error-style', VTMIN_URL.'/core/css/vtmin-error-style.css' );  
    wp_enqueue_style('vtmin-error-style');
  } 
    
 
} //end class
$vtmin_parent_cart_validation = new VTMIN_Parent_Cart_Validation;