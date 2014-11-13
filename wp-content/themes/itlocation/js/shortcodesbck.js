jQuery(document).ready(function() {
    /*
     * edit profile
     */
   // jQuery(".delete_comp_file").live('click', function () {
 jQuery(".delete_comp_file").on('click', '.delete_comp_file', function(){
        var r=confirm("Do you really delete!")
        if (r==true) {
            $this = this;
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'delete-profile-comp-file-itlocation',
                    'security':jQuery('#delete-profile-comp-file-security').val(), 
                    'file_id':jQuery(this).attr('file_id')
                },                
                success: function(response) {
                    jQuery($this).parent().parent().parent().parent().remove();
                }
            });
        }
    });
    
    jQuery("#add-other-address").live('click', function() {
        jQuery("#ctrl-groups").append(jQuery("#ctrl-group-company-address").html());
    });  
    
    jQuery(".delete_comp_address").live('click', function() {
        if( confirm( "Do you really delete!" ) ){
            jQuery(this).parent().remove();
            
            var $icon_url = images.url + 'marker-free.png';
            if( jQuery(this).attr('role') >= 2 ){
                $icon_url = images.url + 'marker-premium.png';
            } else if( jQuery(this).attr('role') == 1 ){
                $icon_url = images.url + 'marker-basic.png';
            }
    		
            reflesh_map_by_location ( $icon_url, '' );
        }
    });  
   
    /*
     * login
     */
    jQuery("#mylogin-form").live('submit', function () {
		var $username = jQuery("#username", jQuery("#mylogin-form")).val(),
			$password = jQuery("#user_password", jQuery("#mylogin-form")).val();
		
		if( $username == '' || $password == '' || $username == ' ' ){
			jQuery(".alert-error", jQuery("#mylogin-form")).show();
		} else {
			jQuery('#login-btn', jQuery(this)).attr('disabled', 'disabled');
			$this = this;
			
			jQuery( "#login-form-process-img" ).removeClass('hide');
			
			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : admin_ajax.url,
				data: {
					'action' : 'login-itlocation',
					'security' : jQuery('#login-security').val(), 
					'user_login' : $username, 
					'user_password' : $password
				},
				success: function(response) {
					jQuery( "#login-form-process-img" ).addClass('hide');
					
					if( response.error ){
						jQuery('#login-btn', jQuery($this)).removeAttr('disabled');
						jQuery(".alert-error", jQuery("#mylogin-form")).show();
					} else {
						location.href = response.ret_url;
					}
				}
			});
		}
		
        return false;
    });
    
    jQuery("#go-forgot-pw").live('click', function () {
        jQuery("#forgot-password-part").show();
        jQuery("#login-part").hide();
    });
    jQuery("#go-login-from-forgot-pw-btn").live('click', function () {
        jQuery("#forgot-password-part").hide();
        jQuery("#login-part").show();
    });
    /*
     * forgot password
     */
    jQuery("#forgot-password-form").live('submit', function () {
		
        tmp = jQuery("#username_email", jQuery(this)).val();
        if(jQuery.trim(tmp)) {
			jQuery("#forget-password-form-process-img").removeClass("hide");
			
            jQuery(".alert-error", jQuery(this)).hide();
            jQuery('#forgot-password-btn', jQuery(this)).attr('disabled', 'disabled');
            $this = this;
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'forgot-password-itlocation',
                    'security':jQuery('#forgot-password-itlocation-security').val(), 
                    'username_email':jQuery("#username_email", jQuery(this)).val()
                },
                success: function(response) {
					jQuery("#forget-password-form-process-img").addClass("hide");
					
                    jQuery('#forgot-password-btn', jQuery($this)).removeAttr('disabled');
                    jQuery("#username_email", jQuery($this)).val('');
                    if(response.error) {
                        jQuery(".alert-error", jQuery($this)).show();
                        jQuery(".alert-error", jQuery($this)).html(response.error);
                    } else {
                        jQuery(".alert-error", jQuery($this)).show();
                        jQuery(".alert-error", jQuery($this)).html('Please check your email.');
                    }
                }
            });
        } else {
            jQuery(".alert-error", jQuery(this)).show();
            jQuery(".alert-error", jQuery(this)).html('Please insert your Email or username.');
            jQuery("#username_email", jQuery(this)).val('');
        }
        return false;
    });
    
    /*
     * signup
     */ 
      jQuery("#mysignup-form").submit(function( event ) {alert("hello");  
    //  jQuery("#mysignup-form").on('submit', '#cmysignup-form', function(){ 
    //jQuery("#mysignup-form").live('submit', function () { alert("hello");
		jQuery("#singn-up-form-process-img").removeClass("hide");
		
        jQuery('#signup-btn', jQuery(this)).attr('disabled', 'disabled');
        $this = this;
        member_level = jQuery('#member_level', jQuery("#mysignup-form")).val()
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: {
                'action' : 'signup-itlocation',
                'security' : jQuery('#signup-security', jQuery("#mysignup-form")).val(),
                'company_name' : jQuery('#company_name', jQuery("#mysignup-form")).val(),
                'user_email' : jQuery('#user_email', jQuery("#mysignup-form")).val(),
                'username' : jQuery('#username', jQuery("#mysignup-form")).val(),
                'coupon_code' : jQuery('#coupon_code', jQuery("#mysignup-form")).val(),
                'user_password' : jQuery('#user_password', jQuery("#mysignup-form")).val(),
                'recaptcha_response_field' : jQuery('#recaptcha_response_field', jQuery("#mysignup-form")).val(),
                'recaptcha_challenge_field' : jQuery('#recaptcha_challenge_field', jQuery("#mysignup-form")).val(),
                'member_level' : member_level
            },
            success: function(response) {
				jQuery("#singn-up-form-process-img").addClass("hide");
				
                Recaptcha.reload();
				
                if( response.error ) {
                    jQuery('.alert-error', jQuery($this)).show();
                    jQuery('.alert-error', jQuery($this)).html( response.error_message );
                    jQuery('#signup-btn', jQuery($this)).removeAttr('disabled');
                    jQuery('#recaptcha_response_field', jQuery($this)).focus();
                } else {
                    jQuery('.alert-error', jQuery($this)).hide();
                    if( member_level == 1 ) {
                        if( response.user_id ){
                            jQuery('#signup-paypal-1 input[name=os0]').val( response.user_id );
							 jQuery('#signup-paypal-1 input[name=on0]').val('Referencing Customer ID');
						
                            if( response.orignal_val ){
                                jQuery('#signup-paypal-1 input[name=a3]').val(response.ra);
                                jQuery('#signup-paypal-1 form').append('<input type="hidden" name="on3" value="ORIGINALLY">');
                                jQuery('#signup-paypal-1 form').append('<input type="hidden" name="os3" value="'+response.orignal_val+'">');
                                jQuery('#signup-paypal-1 form').append('<input type="hidden" name="on4" value="Coupon">');
                                jQuery('#signup-paypal-1 form').append('<input type="hidden" name="os4" value="'+response.coupon_o_val+'">');
								
                            }
                          jQuery('#signup-paypal-1 form').submit();
                        }
                    } else if( member_level == 2 ) {
                        if( response.user_id ){
                            jQuery('#signup-paypal-2 input[name=os0]').val( response.user_id );
							jQuery('#signup-paypal-2 input[name=on0]').val('Referencing Customer ID');
						
                            if( response.orignal_val ){
                                jQuery('#signup-paypal-2 input[name=a3]').val( response.ra );
							
                                jQuery('#signup-paypal-2 form').append('<input type="hidden" name="on3" value="Originally">');
                                jQuery('#signup-paypal-2 form').append('<input type="hidden" name="os3" value="'+response.orignal_val+'">');
                                jQuery('#signup-paypal-2 form').append('<input type="hidden" name="on4" value="Coupon">');
                                jQuery('#signup-paypal-2 form').append('<input type="hidden" name="os4" value="'+response.coupon_o_val+'">');
								
                            }
                          jQuery('#signup-paypal-2 form').submit();
                        }
                    } else {
                       jQuery('#signup-modal').modal('hide');
                       location.href = response.ret_url;
                    }
                }
            }
        });
        return false;
    });
    /*
     * payment
     */
    jQuery(".go-payment-panel").live('click', function () {
        if(jQuery(this).attr('val') == 'free') return;
        jQuery('#pricing-select').hide();
        jQuery('#coupon-code').show();
        jQuery('#upgrade-now').show();
		jQuery('#upgrade-coupon').show();
        jQuery('#'+jQuery(this).attr('val')).show();
        jQuery('#go-pricing-select', jQuery('#user-payment')).show();
        
        
    });
    jQuery("#go-pricing-select").live('click', function () {
        jQuery(this).hide();
        jQuery('#pricing-select').show();
        jQuery('#coupon-code').hide();
        jQuery('#upgrade-now').hide();
		jQuery('#upgrade-coupon').hide();
        jQuery('.tabbable', jQuery('#user-payment')).hide();
    });
    /*
    * company comments
    */

    jQuery("#company_comments_form").live('submit', function () {
        if(jQuery("#company-comments #rating_num").val()) {
            jQuery('#company-comments #reg_rating').attr('disabled','disabled');
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'company-comments-itlocation',
                    'security':jQuery('#company-comments-itlocation-security').val(),
                    'cid':jQuery("#company-comments #cid").val(),
                    'rating':jQuery("#company-comments #rating_num").val(),
                    'full_name':jQuery("#company-comments #full_name").val(),
                    'to_email':jQuery("#company-comments #to_email").val(),
                    'from_email':jQuery("#company-comments #from_email").val(),
                    'reg_uid':jQuery("#company-comments #reg_uid").val(),
                    'comment':jQuery("#company-comments #comment").val()
                },                
                success: function(response) {
                    if(response.error) {
                        jQuery('#company-comments #alert-error').html(response.error);
                        jQuery('#company-comments #alert-error').show();
                        jQuery('#company-comments #alert-error').fadeOut(4000, function() {
                            jQuery('#company-comments #reg_rating').removeAttr('disabled');
                        });
                    } else {
                        jQuery('#company-comments .ctrl-groups').remove();
                        jQuery('#company-comments #alert-error').show();
                        jQuery('#company-comments #alert-error').fadeOut(4000, function() {
                            jQuery('#company-comments #alert-error').remove();
                            location.reload();
                        //document.cookie = "reg_rating=1";
                        });
                    }
                }
            });
        }
        return false;
    });
    jQuery("#company-comments .stars a").live('click', function () {
        jQuery("#company-comments .stars a").removeClass('active');
        jQuery(this).addClass('active');
        jQuery("#company-comments #rating_num").val(jQuery(this).html());
        return false;
    });
    
    jQuery("#company-comments-list-nav a").live('click', function () {
        jQuery("#all_view_company_comments .comment-loading").show();
        jQuery("#all_view_company_comments #list_company_comments").html('');
        jQuery.ajax({
            type : "post",
            dataType : "html",
            url : admin_ajax.url,
            data: {
                'action':'company-comments-list-itlocation',
                'security':jQuery('#company-comments-list-itlocation-security').val(),
                'cid':jQuery("#company-comments #cid").val(),
                'pid':jQuery(this).html()
            },                
            success: function(response) {
                jQuery("#all_view_company_comments .comment-loading").hide();
                jQuery("#all_view_company_comments #list_company_comments_grp").html(response);
            }
        });
        return false;
    });
    /*
     * subscriber
     */
    jQuery("#subscriber_itlocation_form").live('submit', function () { 
        val = jQuery('.subscriber_email', jQuery(this).parent()).val();
        val = jQuery.trim(val);
        $this = this;
        if(val) {
            if(val.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
                jQuery('#subscriber_itlocation_form .subscriber_email').attr('readonly', 'readonly');
                jQuery('#subscriber_itlocation_form .subscriber_btn').attr('disabled', 'disabled');

                jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : admin_ajax.url,
                    data: {
                        'action':'subscribe-itlocation',
                        'security':jQuery('#subscribe-itlocation-security').val(), 
                        'subscriber_email':jQuery('.subscriber_email', jQuery(this).parent()).val()
                    },
                    success: function(response) {
                        jQuery('#subscriber_itlocation_form .subscriber_email').removeAttr('readonly');
                        jQuery('#subscriber_itlocation_form .subscriber_btn').removeAttr('disabled');
                        jQuery('#subscriber_itlocation_form .subscriber_email').val('');

                        if(!response.error) {
                            jQuery('#subscriber-alert-itlocation-modal #subscriber-alert-email').html(val);
                            jQuery('#subscriber-alert-itlocation-modal').modal('show');
                        }
                    }
                });
            } else {
                jQuery('.subscriber_email', jQuery(this).parent()).css('border-color', '#ff0000');
            }
        } else {
            jQuery('.subscriber_email', jQuery(this).parent()).css('border-color', '#B94A48');
            jQuery('.subscriber_email', jQuery(this).parent()).val(jQuery.trim(val));
        }
        return false;
    });
	
	
	    /*
     * Upgrade Coupon Code
     */    
    jQuery("#upgradeform").live('submit', function () {
		jQuery("#singn-up-form-process-img").removeClass("hide");
		
        jQuery('#signup-btn', jQuery(this)).attr('disabled', 'disabled');
        $this = this;
        member_level = jQuery('#member_level', jQuery("#upgradeform")).val()
	    member_level_type = jQuery('#member_level_type', jQuery("#upgradeform")).val()

        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: {
                'action' : 'upgradecoupon-itlocation',
                'security' : jQuery('#signup-security', jQuery("#upgradeform")).val(),
                'coupon_code' : jQuery('#coupon_code', jQuery("#upgradeform")).val(),
                'member_level' : member_level
            },
            success: function(response) {
				jQuery("#singn-up-form-process-img").addClass("hide");
				              				
                if( response.error ) {
                    jQuery('.alert-error', jQuery($this)).show();
                    jQuery('.alert-error', jQuery($this)).html( response.error_message );
                    jQuery('#signup-btn', jQuery($this)).removeAttr('disabled');
                   
                } else {
                    jQuery('.alert-error', jQuery($this)).hide();
                    if( member_level_type == 1 ) {
				
                        if( response.user_id ){ 
                            jQuery('#upgradecoupon-paypal-1 input[name=os0]').val( response.user_id );
							 jQuery('#upgradecoupon-paypal-1 input[name=on0]').val('Referencing Customer ID');
						
                            if( response.orignal_val ){
                                jQuery('#upgradecoupon-paypal-1 input[name=a3]').val(response.ra);
                                jQuery('#upgradecoupon-paypal-1 form').append('<input type="hidden" name="on3" value="ORIGINALLY">');
                                jQuery('#upgradecoupon-paypal-1 form').append('<input type="hidden" name="os3" value="'+response.orignal_val+'">');
                                jQuery('#upgradecoupon-paypal-1 form').append('<input type="hidden" name="on4" value="Coupon">');
                                jQuery('#upgradecoupon-paypal-1 form').append('<input type="hidden" name="os4" value="'+response.coupon_o_val+'">');
								
                            }
                          jQuery('#upgradecoupon-paypal-1 form').submit();
                        }
                    } else if( member_level_type == 2 ) {
						
                        if( response.user_id ){
                            jQuery('#upgradecoupon-paypal-2 input[name=os0]').val( response.user_id );
							jQuery('#upgradecoupon-paypal-2 input[name=on0]').val('Referencing Customer ID');
						
                            if( response.orignal_val ){
                                jQuery('#upgradecoupon-paypal-2 input[name=a3]').val( response.ra );
							
                                jQuery('#upgradecoupon-paypal-2 form').append('<input type="hidden" name="on3" value="Originally">');
                                jQuery('#upgradecoupon-paypal-2 form').append('<input type="hidden" name="os3" value="'+response.orignal_val+'">');
                                jQuery('#upgradecoupon-paypal-2 form').append('<input type="hidden" name="on4" value="Coupon">');
                                jQuery('#upgradecoupon-paypal-2 form').append('<input type="hidden" name="os4" value="'+response.coupon_o_val+'">');
								
                            }
                          jQuery('#upgradecoupon-paypal-2 form').submit();
                        }
                    } else {
                       jQuery('#signup-modal').modal('hide');
                       location.href = response.ret_url;
                    }
                }
            }
        });
        return false;
    });
	
jQuery('#credit_card_update').click(function(){

   window.location.href='http://dev.itlocator.com/credit-card/';
return false;
});	
});

