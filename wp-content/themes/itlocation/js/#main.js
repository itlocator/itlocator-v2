jQuery(document).ready(function() {
    /*
     * edit profile
     */
    
    jQuery("#comp_description").live('keyup', function(){
        var str=jQuery(this).val();
        strlength = str.length;
        jQuery("#desc_number").html(1000-strlength);
    });
    
    jQuery("#comp_country").select2({
        formatResult: countries_format,
        formatSelection: countries_format,
        placeholder: "Select Country",
        allowClear: true,
        escapeMarkup: function(m) {
            return m;
        }
    }).on("change", function(e) { 
        jQuery('#comp_state_ctrl').hide();
        jQuery('#comp_state_ctrl').prev().show();

        $data = "action=get-state-itlocation&security=" + jQuery('#get-state-security').val() + "&country_id=" + e.val;
        jQuery.ajax({
            type : "post",
            dataType : "html",
            url : admin_ajax.url,
            data: $data,
            success: function(response) {
                jQuery('#comp_state_ctrl').html(response);
                jQuery('#comp_state_ctrl').show();
                jQuery('#comp_state_ctrl').prev().hide();
            }
        });
    });
    
    jQuery('#jquery-live-validation-edit-profile').liveValidation({
        validIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png', 
        invalidIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png', 
        required: ['user_email', 'companyname', 'address1'],
        fields: {
            user_email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
            companyname: /^\S.*$/,
            address1: /^\S.*$/
        }
    });
    
    jQuery("#phoneprim").live('focusout', function() {
        //check_phone_format("phoneprim");
        });
    jQuery("#phoneprim").live('keyup', function() {
        //check_phone_format("phoneprim");
        });
    
    jQuery("#phonescond").live('focusout', function() {
        //check_phone_format("phonescond");
        });
    jQuery("#phonescond").live('keyup', function() {
        //check_phone_format("phonescond");
        });
    jQuery("#user_email", jQuery("#jquery-live-validation-edit-profile")).live('keyup', function() {//focusout
        check_user_email(this, jQuery('#edit-myprofile-btn', jQuery("#jquery-live-validation-edit-profile")));
    });
    jQuery("#user_email", jQuery("#jquery-live-validation-edit-profile")).live('focusout', function() {//
        check_user_email(this, jQuery('#edit-myprofile-btn', jQuery("#jquery-live-validation-edit-profile")));
    });
    
    jQuery("#conform_pass").live('focusout', function() {
        new_pass = jQuery("#new_pass").val();
        conform_pass = jQuery("#conform_pass").val();
        
        jQuery(this).parent().find('img[alt="Valid"]').remove();
        jQuery(this).parent().find('img[alt="Invalid"]').remove();

        if(new_pass != conform_pass) {
            jQuery('<img src="' + webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png" alt="Invalid" />').insertAfter(jQuery(this));
        }
    });
    
    /*
     * login
     */
    jQuery('#login-modal').on('hide', function () {
        jQuery('#username', jQuery('#login-modal')).val('');
        jQuery('#user_password', jQuery('#login-modal')).val('');
    });
        
    jQuery('#go-signup-btn', jQuery('#login-modal')).live('click', function() {
        jQuery('#signup-modal-label', jQuery('#signup-modal')).html('Signup for Basic IT Locator Listing – It’s Free!');
        jQuery('#login-modal').modal('hide');
    });
    /*
     * signup
     */
    
    jQuery('.btn_for_modal').live('click', function () {
        if(jQuery(this).attr('val') == 'free') {
            jQuery('#signup-modal-label', jQuery('#signup-modal')).html('Signup for Basic IT Locator Listing – It’s Free!');
            jQuery('#member_level', jQuery('#signup-modal')).val('0');
        }
        if(jQuery(this).attr('val') == 'member') {
            jQuery('#signup-modal-label', jQuery('#signup-modal')).html('Become an IT Locator Member and Stand Out From Your Competition!');
            jQuery('#member_level', jQuery('#signup-modal')).val('1');
        }
        if(jQuery(this).attr('val') == 'premium') {
            jQuery('#signup-modal-label', jQuery('#signup-modal')).html('Become a Premium IT Locator Member and Maximize Your Lead Generation!');
            jQuery('#member_level', jQuery('#signup-modal')).val('2');
        }
    });

    jQuery('#go-login-btn', jQuery('#signup-modal')).live('click', function() {
        jQuery('#signup-modal').modal('hide');
    });
    
    jQuery('#login-modal').on('hide', function () {
        jQuery('#full_name', jQuery('#signup-modal')).val('');
        jQuery('#user_email', jQuery('#signup-modal')).val('');
        jQuery('#username', jQuery('#signup-modal')).val('');
        jQuery('#user_password', jQuery('#signup-modal')).val('');
    });
    /*
     * subscriber modal
     */
    jQuery("#subscriber_itlocation_modal_form").live('submit', function () {
        jQuery('.subscriber_email_itlocation', jQuery(this)).css('border-color', '');
        val = jQuery('.subscriber_email_itlocation', jQuery(this)).val();
        val = jQuery.trim(val);
        if(val) {
            if(val.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
                jQuery('#subscriber-itlocation-modal .subscriber_email_itlocation').attr('readonly', 'readonly');
                jQuery('#subscriber-itlocation-modal .signup_btn').attr('disabled', 'disabled');
                jQuery('#subscriber-itlocation-modal #not_now_subscriber_itlocation_btn').attr('disabled', 'disabled');
                
                jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : admin_ajax.url,
                    data: {
                        'action':'subscriber-modal-itlocation',
                        'security':jQuery('#subscriber-modal-itlocation-security').val(),
                        'subscriber_email':val
                    },
                    success: function(response) {
                        jQuery('#subscriber-itlocation-modal .subscriber_email_itlocation').removeAttr('readonly');
                        jQuery('#subscriber-itlocation-modal .signup_btn').removeAttr('disabled');
                        jQuery('#subscriber-itlocation-modal #not_now_subscriber_itlocation_btn').removeAttr('disabled');

                        jQuery('#subscriber-itlocation-modal').modal('hide');
                        jQuery('#subscriber-alert-itlocation-modal').modal('show');
                        jQuery('#subscriber-alert-itlocation-modal #subscriber-alert-email').html(val);
                    }
                });
            } else {
                jQuery('.subscriber_email_itlocation', jQuery(this)).css('border-color', '#B94A48');
            }
        } else {
            jQuery('.subscriber_email_itlocation', jQuery(this)).css('border-color', '#B94A48');
            jQuery('.subscriber_email_itlocation', jQuery(this)).val('');
        }

        return false;
    });
    
    jQuery("#not_now_subscriber_itlocation_btn").live('click', function () {
        jQuery('#subscriber-itlocation-modal').modal('hide');
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: {
                'action':'subscriber-modal-itlocation',
                'security':jQuery('#subscriber-modal-itlocation-security').val()
            },                
            success: function(response) {
            }
        });
        return false;
    });
    jQuery("#subscriber_alert_close_btn").live('click', function () {
        jQuery('#subscriber-alert-itlocation-modal').modal('hide');
    });
    
    /*
     * contact us
     */
    jQuery('#jquery-live-validation-contactus').liveValidation({
        validIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png', 
        invalidIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png', 
        required: ['email_title', 'from_email', 'email_content'],
        fields: {
            from_email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
            email_title: /^\S.*$/,
            email_content: /^\S.*$/m
        }
    });
    jQuery("#contact-us-form").live('submit', function () {
        jQuery('#send-email-btn', jQuery(this)).attr('disabled', 'disabled');
        $this = this;
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: {
                'action':'send-email-itlocation',
                'security':jQuery('#send-email-itlocation-security', jQuery(this)).val(),
                'from_email':jQuery('#from_email', jQuery(this)).val(),
                'title':jQuery('#email_title', jQuery(this)).val(),
                'content':jQuery('#email_content', jQuery(this)).val()
            },
            success: function(response) {
                jQuery('#send-email-btn', jQuery($this)).removeAttr('disabled');
                if(!response.error) {
                    jQuery('#email_title', jQuery($this)).val('');
                    jQuery('#email_content', jQuery($this)).val('');
                    jQuery('#from_email', jQuery($this)).val('');
                    jQuery('#alert-error', jQuery($this)).show();
                    jQuery('#alert-error', jQuery($this)).fadeOut(4000);
                }
            }
        });
        return false;
    });
	
	jQuery("#support-us-form").live('submit', function () {
        jQuery('#send-email-btn', jQuery(this)).attr('disabled', 'disabled');
        $this = this;
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: {
                'action':'send-support-email-itlocation',
                'security':jQuery('#send-email-itlocation-security', jQuery(this)).val(),
                'from_email':jQuery('#from_email', jQuery(this)).val(),
                'title':jQuery('#email_title', jQuery(this)).val(),
                'content':jQuery('#email_content', jQuery(this)).val()
            },
            success: function(response) {
                jQuery('#send-email-btn', jQuery($this)).removeAttr('disabled');
                if(!response.error) {
                    jQuery('#email_title', jQuery($this)).val('');
                    jQuery('#email_content', jQuery($this)).val('');
                    jQuery('#from_email', jQuery($this)).val('');
                    jQuery('#alert-error', jQuery($this)).show();
                    jQuery('#alert-error', jQuery($this)).fadeOut(4000);
                }
            }
        });
        return false;
    });
	
    /*
     * comment
     */
    jQuery('#comment-respond').liveValidation({
        validIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png', 
        invalidIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png', 
        required: ['author', 'email', 'comment'],
        fields: {
            email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
            author: /^\S.*$/,
            comment: /^\S.*$/m
        }
    });
    
    /*
     * contributions management
     */
    jQuery("#delete-contributions-btn").live('click', function () {
        var r=confirm("Do you really delete!")
        if (r==true) {
            jQuery(this).attr('disabled', 'disabled');
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'delete-contributions-itlocation',
                    'security':jQuery('#delete-contributions-itlocation-security').val(),
                    'pid':jQuery(this).attr('pid')
                },
                success: function(response) {
                    location.href = response.r_url;
                }
            });
        }
        return false;
    });
    
    jQuery(".delete-contributions-btn").live('click', function () {
        var r=confirm("Do you really delete!")
        if (r==true) {
            jQuery(this).attr('disabled', 'disabled');
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'delete-contributions-itlocation',
                    'security':jQuery('#delete-contributions-itlocation-security').val(),
                    'pid':jQuery(this).attr('pid')
                },
                success: function(response) {
                    location.reload();
                }
            });
        }
        return false;
    });
    /*
     * advanced search
    jQuery("#advanced-search-btn").on("click", function(e) {
        $location = jQuery("#page_url").val();
        $tmp = URL.serialize({
            lo: jQuery("#lo").val(),
            co: jQuery("#co").val(),
            ke: jQuery("#ke").val(),
            se: jQuery("#se").val(),
            in_tmp_tmp: jQuery("#in").val(),
            ce: jQuery("#ce").val(),
            pa: jQuery("#pa").val(),
            us: jQuery("#us").is(':checked') ? 1 : 0
        });
        if($tmp) {
            $location += "?"+$tmp;
            $location = $location.replace("in_tmp_tmp","in");
        }

        document.location = $location;
    })
     */
    /*
     * comment
     */
    jQuery('.bbp-reply-form').liveValidation({
        validIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png', 
        invalidIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png', 
        required: ['bbp_anonymous_name', 'bbp_anonymous_email'],
        fields: {
            bbp_anonymous_email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
            bbp_anonymous_name: /^\S.*$/
        }
    });
    
    jQuery('.bbp-topic-form').liveValidation({
        validIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png', 
        invalidIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png', 
        required: ['bbp_anonymous_name', 'bbp_anonymous_email', 'bbp_topic_title'],
        fields: {
            bbp_anonymous_email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
            bbp_anonymous_name: /^\S.*$/,
            bbp_topic_title: /^\S.*$/
        }
    });

});

function countries_format(state) {
    if (!state.id) return state.text; // optgroup
    return "<img class='flag' src='"+images.url+"/flags/" + state.id.toLowerCase() + ".png'/>" + state.text;
}

function check_phone_format(obj_id) {
    val = jQuery.trim(jQuery("#"+obj_id).val());
    ret_val = 0;
    
    jQuery("#"+obj_id).parent().find('img[alt="Valid"]').remove();
    jQuery("#"+obj_id).parent().find('img[alt="Invalid"]').remove();
    
    if(val.match(/^\S.*$/)) {
        if(val.match(/^([1]-)?[0-9]{10}$/i)) {
            ret_val = 1;
        }
        if(val.match(/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i)) {
            ret_val = 1;
        }
        if(val.match(/^([1]-)?[0-9]{3}.[0-9]{3}.[0-9]{4}$/i)) {
            ret_val = 1;
        }
    
        if(ret_val == 1) {
            jQuery('<img src="' + webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png" alt="Valid" />').insertAfter(jQuery("#"+obj_id));
        } else {
            jQuery('<img src="' + webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png" alt="Invalid" />').insertAfter(jQuery("#"+obj_id));
        }
    }

}
function check_val_exist(obj_id) {
    val = jQuery("#"+obj_id).val();
    ret_val = 0;
    if(val.match(/^\S.*$/)) {
        ret_val = 1;
    }
    
    jQuery("#"+obj_id).parent().find('img[alt="Valid"]').remove();
    jQuery("#"+obj_id).parent().find('img[alt="Invalid"]').remove();
    
    if(ret_val == 1) {
        jQuery('<img src="' + webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png" alt="Valid" />').insertAfter(jQuery("#"+obj_id));
    } else {
        jQuery('<img src="' + webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png" alt="Invalid" />').insertAfter(jQuery("#"+obj_id));
    }
}

function getCookie(name) {
    var parts = document.cookie.split(name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}

function check_user_email($email_obj, $btn_obj) {
    if(jQuery($email_obj).parent().find('img[alt="Valid"]').length) {
        jQuery($btn_obj).attr('disabled', 'disabled');

        jQuery('img', jQuery($email_obj).parent()).hide();
        jQuery('img', jQuery($email_obj).parent()).attr('alt', 'Invalid');
        jQuery('.loading', jQuery($email_obj).parent()).show();

        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: {
                'action':'check-email-itlocation',
                'security':jQuery('#check-email-security').val(),
                'user_email':jQuery($email_obj).val()
            },
            success: function(response) {
                jQuery($btn_obj).removeAttr('disabled');
                jQuery('img', jQuery($email_obj).parent()).show();
                jQuery('.loading', jQuery($email_obj).parent()).hide();
                if(response.error) {
                    jQuery('img', jQuery($email_obj).parent()).attr('alt', 'Invalid');
                    jQuery('img', jQuery($email_obj).parent()).attr('src', webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png');
                } else {
                    jQuery('img', jQuery($email_obj).parent()).attr('alt', 'Valid');
                }
            }
        });
    }
}
function check_username($username_obj, $btn_obj) {
    if(jQuery($username_obj).parent().find('img[alt="Valid"]').length) {
        jQuery($btn_obj).attr('disabled', 'disabled');

        jQuery('img', jQuery($username_obj).parent()).hide();
        jQuery('img', jQuery($username_obj).parent()).attr('alt', 'Invalid');
        jQuery('.loading', jQuery($username_obj).parent()).show();
            
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: {
                'action':'check-username-itlocation',
                'security':jQuery('#check-username-security').val(),
                'username':jQuery($username_obj).val()
            },
            success: function(response) {
                jQuery($btn_obj).removeAttr('disabled');
                jQuery('img', jQuery($username_obj).parent()).show();
                jQuery('.loading', jQuery($username_obj).parent()).hide();
                if(response.error) {
                    jQuery('img', jQuery($username_obj).parent()).attr('alt', 'Invalid');
                    jQuery('img', jQuery($username_obj).parent()).attr('src', webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png');
                } else {
                    jQuery('img', jQuery($username_obj).parent()).attr('alt', 'Valid');
                }
            }
        });
    }
}


function getCookieData( c_name ) {
    var c_value = document.cookie;
    var c_start = c_value.indexOf(" " + c_name + "=");
    if (c_start == -1)
    {
        c_start = c_value.indexOf(c_name + "=");
    }
    if (c_start == -1)
    {
        c_value = null;
    }
    else
    {
        c_start = c_value.indexOf("=", c_start) + 1;
        var c_end = c_value.indexOf(";", c_start);
        if (c_end == -1)
        {
            c_end = c_value.length;
        }
        c_value = unescape(c_value.substring(c_start,c_end));
    }
    return c_value;
}


var URL = (function (a) {
    return {
        // create a querystring from a params object
        serialize: function (params) { 
            var key, query = [];
            for (key in params) {
                if(params[key])
                    query.push(encodeURIComponent(key) + "=" + encodeURIComponent(params[key]));
            }
            return query.join('&');
        },

        // create a params object from a querystring
        unserialize: function (query) {
            var pair, params = {};
            query = query.replace(/^\?/, '').split(/&/);
            for (pair in query) {
                pair = query[pair].split('=');
                params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }
            return params;
        },

        parse: function (url) {
            a.href = url;
            return {
                // native anchor properties
                hash: a.hash,
                host: a.host,
                hostname: a.hostname,
                href: url,
                pathname: a.pathname,
                port: a.port,
                protocol: a.protocol,
                search: a.search,
                // added properties
                file: a.pathname.split('/').pop(),
                params: URL.unserialize(a.search)
            };
        }
    };
}(document.createElement('a')));