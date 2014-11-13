<?php
global $id, $class, $style, $cutomize_js_fg, $placeholder, $tags, $default_val, $limit;
$val_a = explode(',', $default_val);
?>
<select id="<?php echo $id; ?>" name="<?php echo $id; ?>[]" class="<?php echo $class; ?>" style="<?php echo $style; ?>" multiple>
    <?php
    if (count($tags)) {
        foreach ($tags as $key => $nm) {
            $tmp = '';
            foreach ($val_a as $val) {
                if ($val == $key) {
                    $tmp = 'selected';
                    break;
                }
            }
            ?>
            <option value="<?php echo $key ?>" <?php echo $tmp ?>><?php _e(stripslashes($nm)); ?></option>
            <?php
        }
    }
    ?>
</select>
<?php
if (!$cutomize_js_fg) {
    $tmp = '';
    if ($limit) {
        $tmp = ',maximumSelectionSize: ' . $limit;
    }
    ?>
    <script>
        jQuery(document).ready(function() {
            jQuery("#<?php echo $id; ?>").select2({
                placeholder: "<?php _e($placeholder) ?>"<?php echo $tmp ?>
            });
        });
    </script>
    <?php
}
?>