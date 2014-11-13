<?php
/*
  Template Name: Contact Us Page
 */
?>
<?php get_header(); ?>
</div>
</div>
</div>
</div>

<?php if (have_posts()) while (have_posts()) : the_post(); ?>

    <div class="page-sub-page inner-page">
        <div class="container">
        	<h3 class="page-title-diff"><?php the_title(); ?></h3>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 base">
                    <div class="base-content">
                    <?php the_content(); ?>
                        <div class="" id="jquery-live-validation-contactus">
                            <form method="post" action="" class="" id="contact-us-form" name="contact-us-form">
                                <?php wp_nonce_field('send-email-itlocation', 'send-email-itlocation-security'); ?>
                                <div class="form-group" id="alert-error" style="display: none">
                                    <div class="">
                                        <div class="span7"><div class="alert alert-success margin-only-bottom-0"><?php _e('Successfully Sent.', 'twentyten') ?></div></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="email_title"><?php _e('Title', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                                            <div class="">
                                                <input type="text" required="required" id="email_title" name="email_title" placeholder="Title" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                        <label class="control-label" for="from_email"><?php _e('Email', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                                            <div class="">
                                                <input type="email" required="required" id="from_email" name="from_email" placeholder="Email" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="email_content"><?php _e('Comment', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                                    <div class="live-validation-textarea">
                                        <textarea rows="3" required="required" id="email_content" name="email_content"  class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="">
                                        <input type="submit" name="submit" class="btn btn-success" id="send-email-btn" value="<?php _e('Contact Me', 'twentyten') ?>">
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                	</div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 base-secondary">
                	<div class="base-content">
                        <div class="address">
                            <header><h3 class="page-title title-20 dMx">Address:</h3></header>
                               <address>
                                <strong>IT Locator</strong><br>
                                7100 Wisconsin Ave
                                Bethesda, MD 20814, United States
                            </address>
                            <strong>Phone: </strong>800.659.9680<br>
                            <strong>Email: </strong><a href="mailto:info@itlocator.com">info@itlocator.com</a><br>
                            <strong>Skype: </strong>robin.miller5
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
       
    <?php endwhile; // end of the loop.   ?>
<?php get_footer(); ?>
