<?php
	global $id, $add_class, $style, $ctrl_attr, $selected_option, $cutomize_js_fg, $disabled_combo;
	global $all_country_nms;

	$result = mysql_query( "SELECT DISTINCT country FROM company WHERE country IS NOT NULL" );
	$exist_country = array();

	while( $row = mysql_fetch_array( $result ) ){
		$exist_country[$row['country']] = 1;
	}
	
	$ct_list = array();
	foreach( $all_country_nms as $key => $val ){
		if( isset($exist_country[$key]) && $exist_country[$key] == 1 ){
			$ct_list[$key] = $val;
		}
	}
?>

<select id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="populate placeholder <?php echo $add_class; ?>" style="<?php echo $style; ?>" <?php echo $ctrl_attr; ?> <?php echo ($disabled_combo == 'disabled' )?'disabled="disabled"':''; ?>>
    <option value=""></option>
    <?php
		foreach ($ct_list as $key => $nm):
			$tmp = '';
			if ($selected_option == $key)
				$tmp = 'selected';
	?>
        <option value="<?php echo $key ?>" <?php echo $tmp ?>><?php _e($nm); ?></option>
	<?php
		endforeach;
    ?>
</select>
<?php
	if ($cutomize_js_fg == 'no') {
?>
    <script language="javascript">
        jQuery(document).ready(function() {
			<?php
				if( $disabled_combo == 'disabled' ){
			?>
				jQuery("#<?php echo $id; ?>").attr("disabled", "disabled");
			<?php
				}
			?>
		
            jQuery("#<?php echo $id; ?>").select2({
                formatResult: countries_format,
                formatSelection: countries_format,
                placeholder: "Select Country",
                allowClear: true,
                escapeMarkup: function(m) {
                    return m;
                }
            });
        });
    </script>
<?php
	}
?>
