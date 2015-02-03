/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var chkoutform;
jQuery(window).load(function (e) {
    e.preventDefault();
    jQuery.post(ajaxurl, {
        action: 'auto_get_cart_total',
    }, function (responseText) {
	//alert(window.location.href.indexOf("?key")<0);
	if(window.location.href.indexOf("?key")<0){
		if(window.navigator.userAgent.indexOf('safari'>0)){
			jQuery(".fncybox").css({width: "75%",top: "-10%",left: "1%"});
			jQuery(".acoverlay").css({left: "-183px",top: "-274px"})	
		}
        	jQuery(".fncybox,.acoverlay").show();
	}else{
		jQuery(".fncybox,.acoverlay").hide();
	}
    })

})
jQuery(document).ready(function () {
    jQuery("#scrollbox").scroll(function () {
       if ((jQuery("#scrollbox")[0].scrollTop + jQuery("#scrollbox").height()) >= jQuery("#scrollbox")[0].scrollHeight) {
            jQuery("#accept_terms").attr('disabled', false).val('I agree to the terms and conditions');
        }
    });
    
})
jQuery("#accept_terms").click(function () {
     jQuery(".acoverlay,.fncybox").hide();
})