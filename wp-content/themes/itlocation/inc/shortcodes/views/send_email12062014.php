<?php
global $id, $to_email;
global $current_user;
$user = new WP_User($current_user->ID);
if ($user->user_email != $to_email) {
    ?>
    <a href="#send_email_<?php echo $id; ?>" role="button" class="btn btn-large btn-primary" data-toggle="modal">EMAIL VAR</a>

    <div id="send_email_<?php echo $id; ?>" class="send-email-modal modal hide fade" tabindex="-1" role="dialog" aria-labelledby="send-email-modal-label" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="send-email-modal-label"><?php _e('Send Email', 'twentyten') ?></h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
                <div class="span12" id="jquery-live-validation-send_email_<?php echo $id; ?>">
                    <form class="form-horizontal" name="send_email_<?php echo $id; ?>-form" id="send_email_<?php echo $id; ?>-form" action="" method="post">
                        <?php wp_nonce_field('send-email-itlocation', 'send-email-itlocation-security'); ?>
                        <input type="hidden" name="to_email" id="to_email" value="<?php echo $to_email; ?>" />
                        <?php
                        if (is_user_logged_in()) {
                            ?>
                            <input type="hidden" name="from_email" id="from_email" value="<?php echo $user->user_email; ?>" />
                            <?php
                        } else {
                            ?>
                            <div class="control-group">
                                <label class="control-label" for="from_email"><?php _e('From Email', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                                <div class="controls">
                                    <input type="text" name="from_email" id="from_email" value="" />
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="email_title"><?php _e('Company', 'twentyten') ?></label>
                            <div class="controls">
                                <input type="text" name="email_title" id="email_title" value="" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="email_content"><?php _e('Message', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                            <div class="controls live-validation-textarea">
                                <textarea name="email_content" id="email_content" rows="4" cols="20"></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" class="btn btn-primary" id="send-email-btn" value="Send">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function() {
            jQuery("#send_email_<?php echo $id; ?>-form").live('submit', function () {
                jQuery('#send-email-btn', jQuery(this)).attr('disabled', 'disabled');
                $this = this;
                jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : admin_ajax.url,
                    data: {
                        'action':'send-email-itlocation',
                        'security':jQuery('#send-email-itlocation-security', jQuery(this)).val(),
                        'to_email':jQuery('#to_email', jQuery(this)).val(),
                        'from_email':jQuery('#from_email', jQuery(this)).val(),
                        'title':jQuery('#email_title', jQuery(this)).val(),
                        'content':jQuery('#email_content', jQuery(this)).val()
                    },
                    success: function(response) {
                        jQuery('#send-email-btn', jQuery($this)).removeAttr('disabled');
                        if(!response.error) {
                            jQuery('#email_title', jQuery($this)).val('');
                            jQuery('#email_content', jQuery($this)).val('');
                            jQuery('#send_email_<?php echo $id; ?>').modal('hide');
                        }
                    }
                });
                return false;
            });
            jQuery('#jquery-live-validation-send_email_<?php echo $id; ?>').liveValidation({
                validIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png', 
                invalidIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png', 
    <?php
    if (is_user_logged_in()) {
        ?>
                        required: ['email_content'],
                        fields: {
                            email_content: /^\S.*$/m,
                        }
        <?php
    } else {
        ?>
                        required: ['from_email', 'email_content'],
                        fields: {
                            from_email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
                            email_content: /^\S.*$/m
                        }
        <?php
    }
    ?>
            });
        });
    </script>

    <?php
}
?>