jQuery(document).ready(function () {
    //manage upload image
    jQuery('#custom-options-ph table.form-table .ctrl .delete-img').live('click', function() {
        jQuery(this).addClass('hide');
        jQuery(this).next().remove();
        jQuery('input', jQuery(this).parent().parent()).val('');
    });


    jQuery(".general_data_insert").live('click', function () {
        $data = "action=edit-genaral-data-ph-custom&security=" + jQuery('#edit-genaral-data-ph-custom-security').val() + "&name=" + jQuery("input[name=gname]", jQuery(this).parent().parent()).val() + "&desc=" + jQuery("textarea[name=gdesc]", jQuery(this).parent().parent()).val() + "&edit_fg=insert&edit_id=" + jQuery("input[name=edit_id]", jQuery(this).parent().parent()).val() + "&tb_nm=" + jQuery(this).attr('tb_nm');

        $p_obj = jQuery(this).parent().parent().parent().parent().parent();
        $name_obj = jQuery("input[name=gname]", jQuery(this).parent().parent());
        $desc_obj = jQuery("textarea[name=gdesc]", jQuery(this).parent().parent());
        
        $name_txt = jQuery("input[name=gname]", jQuery(this).parent().parent()).val();
        $desc_txt = jQuery("textarea[name=gdesc]", jQuery(this).parent().parent()).val();
        $edit_fg = jQuery("input[name=edit_fg]", jQuery(this).parent().parent()).val();

        $tb_nm = jQuery(this).attr('tb_nm');
        
        $this = this;

        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : admin_ajax.url,
            data: $data,
            success: function(response) {
                if(response.last_id) {
                    $idx = 1;
                    jQuery("tr.view_datas td.num", jQuery($this).parent().parent().parent().parent()).each(function (i) {
                        ++$idx;
                    });

                    $tmp = '<tr class="view_datas"><td class="num">' + $idx + '</td><td class="data_name">' + $name_txt + '</td><td class="data_desc">' + $desc_txt + '</td><td><div class="edit-icon"></div>&nbsp;&nbsp;<div class="delete-icon" file_id="' + response.last_id + '"></div></td></tr>';

                    $tmp += '<tr class="edit_ctrls" style="display:none"><td class="num" valign="top">' + $idx + '</td><td valign="top"><input type="text" name="gname" value="' + $name_txt + '" style="width:auto;"/></td><td valign="top"><textarea name="gdesc" style="width:auto;">' + $desc_txt + '</textarea></td><td valign="top"><input type="button" value="Edit" class="button button-primary button-small general_data_edit" style="width:auto;" did="' + response.last_id + '" tb_nm="' + $tb_nm + '"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" class="button button-primary button-small general_data_edit_cancel" style="width:auto;" /></td>';
                    
                    jQuery('tbody.general_data_tbody:last', $p_obj).append($tmp);
                    
                    jQuery($name_obj).val('');
                    jQuery($desc_obj).val('');
                }
            }
        });
    });
    
    jQuery(".general_data_edit").live('click', function () {
        $data = "action=edit-genaral-data-ph-custom&security=" + jQuery('#edit-genaral-data-ph-custom-security').val() + "&edit_id=" + jQuery(this).attr('did') + "&name=" + jQuery("input[name=gname]", jQuery(this).parent().parent()).val() + "&desc=" + jQuery("textarea[name=gdesc]", jQuery(this).parent().parent()).val() + "&edit_fg=edit&tb_nm=" + jQuery(this).attr('tb_nm');
        
        $name_txt = jQuery("input[name=gname]", jQuery(this).parent().parent()).val();
        $desc_txt = jQuery("textarea[name=gdesc]", jQuery(this).parent().parent()).val();
        $this = this;

        jQuery.ajax({
            type : "post",
            dataType : "html",
            url : admin_ajax.url,
            data: $data,
            success: function(response) {
                console.log(response);
                jQuery('.data_name', jQuery($this).parent().parent().prev()).html($name_txt);
                jQuery('.data_desc', jQuery($this).parent().parent().prev()).html($desc_txt);
                
                jQuery(".view_datas", jQuery($this).parent().parent().parent()).show();
                jQuery(".edit_ctrls", jQuery($this).parent().parent().parent()).hide();
                jQuery($this).parent().parent().hide();
                jQuery($this).parent().parent().prev().show();
            }
        });
    });
    
    jQuery(".delete-icon").live('click', function () {
        var r=confirm("Do you really delete!")
        if (r==true) {
            $data = "action=edit-genaral-data-ph-custom&security=" + jQuery('#edit-genaral-data-ph-custom-security').val() + "&edit_fg=delete" + "&edit_id=" + jQuery(this).attr('did') + "&tb_nm=" + jQuery(this).attr('tb_nm');
            $this = this;
            
            jQuery.ajax({
                type : "post",
                dataType : "html",
                url : admin_ajax.url,
                data: $data,
                success: function(response) {
                    $obj = jQuery($this).parent().parent().parent();
                    jQuery($this).parent().parent().next().remove();
                    jQuery($this).parent().parent().remove();

                    $idx = 1;
                    jQuery("tr.view_datas", $obj).each(function (i) {
                        jQuery("td.num", jQuery(this)).html($idx);
                        ++$idx;
                    });

                    $idx = 1;
                    jQuery("tr.edit_ctrls", $obj).each(function (i) {
                        jQuery("td.num", jQuery(this)).html($idx);
                        ++$idx;
                    });
                }
            });
        }
    });
    jQuery(".edit-icon").live('click', function () {
        jQuery(".view_datas", jQuery(this).parent().parent().parent()).show();
        jQuery(".edit_ctrls", jQuery(this).parent().parent().parent()).hide();
        jQuery(this).parent().parent().hide();
        jQuery(this).parent().parent().next().show();
    });
    jQuery(".general_data_edit_cancel").live('click', function () {
        jQuery(".view_datas", jQuery(this).parent().parent().parent()).show();
        jQuery(".edit_ctrls", jQuery(this).parent().parent().parent()).hide();
        jQuery(this).parent().parent().hide();
        jQuery(this).parent().parent().prev().show();
    });
});