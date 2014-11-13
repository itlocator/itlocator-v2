<?php
$tmp = get_option('ws_plugin__s2member_options');

$tmp_a = $tmp['pro_gateways_enabled'];
$tmp = '';

global $id, $level;
?>
<div id="<?php echo $id ?>" class="tabbable" style="display: none">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#<?php echo $id; ?>_paypal" data-toggle="tab"><?php _e('Process Your IT Locator Subscription') ?></a></li>
        <?php
        foreach ($tmp_a as $tmp) {
            if ($tmp == paypal)
                continue;
            $theme_option_nm = 'itlocation_payment_shortcode_' . $tmp . '_level_' . $level;

            $text = trim(get_option($theme_option_nm));
            if ($text == '')
                continue;

            global $functions_ph;
            ?>
            <li><a href="#<?php echo $id . '_' . $tmp ?>" data-toggle="tab"><?php _e($functions_ph->get_geteway_name($tmp)); ?></a></li>
            <?php
        }
        ?>
    </ul>
    <div class="tab-content">
        <?php
        $theme_option_nm = 'itlocation_payment_shortcode_paypal_level_' . $level;
        $text = trim(get_option($theme_option_nm));
        $shotcode = stripslashes($text);
        ?>
        <div class="tab-pane active" id="<?php echo $id; ?>_paypal"><?php echo do_shortcode($shotcode); ?></div>
        <?php
        foreach ($tmp_a as $tmp) {
            if ($tmp == paypal)
                continue;
            $theme_option_nm = 'itlocation_payment_shortcode_' . $tmp . '_level_' . $level;

            $text = trim(get_option($theme_option_nm));
            if ($text == '')
                continue;
            global $functions_ph;
            $shotcode = stripslashes($text);
            ?>
            <div class="tab-pane" id="<?php echo $id . '_' . $tmp ?>"><?php echo do_shortcode($shotcode); ?></div>
            <?php
        }
        ?>
    </div>
</div>