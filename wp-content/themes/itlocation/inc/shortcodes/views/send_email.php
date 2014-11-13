<?php

global $id, $to_email;
global $current_user;
$user = new WP_User($current_user->ID);
if ($user->user_email != $to_email) {
?>

    <a data-target="#send_email_<?php echo $id; ?>"  class="btn btn-large btn-success btn-block" data-toggle="modal">EMAIL VAR</a>
    
    <!-- Modal -->
    <div class="modal fade send-email-modal" id="send_email_<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 id="send-email-modal-label"><?php _e('Send Email', 'twentyten') ?></h3>
                </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12" id="jquery-live-validation-send_email_<?php echo $id; ?>">
                        <form name="send_email_<?php echo $id; ?>-form" id="send_email_<?php echo $id; ?>-form" action="" method="post">
                            <?php wp_nonce_field('send-email-itlocation', 'send-email-itlocation-security'); ?>
                            <input type="hidden" name="to_email" id="to_email" value="<?php echo $to_email; ?>" />
                            <?php  if (is_user_logged_in()) { ?>
                                <input type="hidden" name="from_email" id="from_email" value="<?php echo $user->user_email; ?>" />
    
                                <?php } else { ?>
                                <div class="form-group">
                                    <label class="control-label" for="from_email"><?php _e('From Email', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                                    <div class="">
                                        <input class="form-control" type="text" name="from_email" id="from_email" value="" />
                                    </div>
                                </div>
                                <?php } ?>
    
                            <div class="form-group">
                                <label class="control-label" for="email_title"><?php _e('Name', 'twentyten') ?><span class="imp_star_mark">*</span></label>
                                <div class="">
                                    <input class="form-control" type="text" name="email_title" id="email_title" value="" />
                                </div>
                            </div>
    
                            <div class="form-group">
                                <label class="control-label" for="email_content"><?php _e('Message', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                                <div class="live-validation-textarea">
                                    <textarea class="form-control" name="email_content" id="email_content" rows="4" cols="20"></textarea>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <div class="">
                                    <input type="submit" class="btn btn-primary" id="send-email-btn" value="Send">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>-->
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

                   url : '<?php  echo get_template_directory_uri(); ?>/ajax/send_mail_var.php',

                    data: {

                        /*'action':'send-email-itlocation',*/

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

                        required: ['email_content','email_title'],

                        fields: {

                            email_content: /^\S.*$/m,
							
							email_title: /^\S.*$/m

                        }

        <?php

    } else {

        ?>

                        required: ['from_email', 'email_content','email_title'],

                        fields: {

                            from_email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,

                            email_content: /^\S.*$/m,
							
							email_title: /^\S.*$/m

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