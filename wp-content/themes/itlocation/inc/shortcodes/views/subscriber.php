<!--<div class="position-absolute subscribe-alert"></div>-->
<?php global $horizontal, $text_class; ?>

<form action="" method="POST" id="subscriber_itlocation_form">
    <?php wp_nonce_field('subscribe-itlocation', 'subscribe-itlocation-security'); ?>
    <div class="row">
        <div class="col-sm-4">
        	<h4 class="page-title"><?php _e("Get other great insights like this directly in your inbox.", "twentyten"); ?></h4>
        </div>
        <div class="col-sm-5">
        	<input type="text" value="" name="subscriber_email" class="form-control subscriber_email <?php echo $text_class; ?>">
        </div>
        <div class="col-sm-3">
        	<input type="submit" value="Subscribe" name="subscriber_btn" class="btn btn-danger subscriber_btn">
        </div>
    </div>
    
    <?php if ($horizontal) { ?>
        <div class="clearfix margin-only-bottom-10"></div>
        <?php } ?>
    
</form>

