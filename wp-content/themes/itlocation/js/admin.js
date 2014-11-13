jQuery(document).ready(function() {
    jQuery("#subscribers_send_mail_form #addmore_file").live('click', function () {
        html_tmp = jQuery('#upload_files_tmp').html();
        jQuery("#subscribers_send_mail_form #upload_files").append(html_tmp);
    });
    jQuery("#subscribers_send_mail_form .delete_file").live('click', function () {
        //if(jQuery("#subscribers_send_mail_form .delete_file").length > 1)
        jQuery(this).parent().remove();
        return false;
    });
    
    jQuery(".delete_company").live('click', function () {
        var r=confirm("Are you sure delete?");
        if (r==true) {
            $this = this;
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'delete-company-admin-itlocation',
                    'security':jQuery('#delete-company-admin-itlocation-security').val(), 
                    'cid':jQuery(this).attr('cid')
                },                
                success: function(response) {
                    if(!response.error) {
                        location.reload();
                    }
                }
            });
        }
        return false;
    });
	
	jQuery(".upgrade_company").live('click', function () {
        var r=confirm("Are you sure upgrade?");
        if (r==true) {
            $this = this;
            jQuery.ajax({
                type : "post",
                dataType : "html",
                url : admin_ajax.url,
                data: {
                    'action':'upgrade-company-admin-itlocation',
                    'cid':jQuery(this).attr('cid')
                },                
                success: function(response) {
                    if(!response.error) {
                        location.reload();
                    }
                }
            });
        }
        return false;
    });
	
	jQuery(".downgrade_company").live('click', function () {
        var r=confirm("Are you sure downgrade?");
        if (r==true) {
            $this = this;
            jQuery.ajax({
                type : "post",
                dataType : "html",
                url : admin_ajax.url,
                data: {
                    'action':'downgrade-company-admin-itlocation',
                    'cid':jQuery(this).attr('cid')
                },                
                success: function(response) {
                    if(!response.error) {
                        location.reload();
                    }
                }
            });
        }
        return false;
    });
	
    jQuery(".delete_comments").live('click', function () {
        var r=confirm("Do you really delete!")
        if (r==true) {
            $this = this;
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'delete-comments-admin-itlocation',
                    'security':jQuery('#delete-comments-admin-itlocation-security').val(), 
                    'rid':jQuery(this).attr('rid')
                },                
                success: function(response) {
                    if(!response.error) {
                        location.reload();
                    }
                }
            });
        }
        return false;
    });
    jQuery(".delete_subscribe").live('click', function () {
        var r=confirm("Do you really delete!")
        if (r==true) {
            $this = this;
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'delete-subscribes-admin-itlocation',
                    'security':jQuery('#delete-subscribes-admin-itlocation-security').val(), 
                    'id':jQuery(this).attr('id')
                },                
                success: function(response) {
                    if(!response.error) {
                        location.reload();
                    }
                }
            });
        }
        return false;
    });
    
    jQuery("#companies-table #doaction").live('click', function () {
		$value = jQuery("#companies-table select[name=action]").val();
		
		if( $value == 'delete' ){
			var r=confirm("Are you sure delete?");
		}else if( $value == 'upgrade' ){
			var r=confirm("Are you sure upgrade?");
		}else if( $value == 'downgrade' ){
			var r=confirm("Are you sure downgrade?");
		}
		
        if(r==true){
            return true;
        }else{
            return false;
        }
    });
});
