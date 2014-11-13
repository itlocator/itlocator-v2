<?php
// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die('Please do not load this page directly. Thanks!');

if (post_password_required()) {
    ?>
    <div class="alert alert-info"><?php _e("This post is password protected. Enter the password to view comments.", "bonestheme"); ?></div>
    <?php
    return;
}
?>

<div id="comments">
    <?php if (post_password_required()) : ?>
        <p class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.', 'twentyten'); ?></p>
        <?php
        echo '</div>';
        return;
    endif;
    ?>

    <?php
// You can start editing here -- including this comment!
    ?>

    <?php if (have_comments()) : ?>
        <h4 id="comments-title"><?php
    printf(_n('Reply to %2$s', 'Replies to %2$s', get_comments_number(), 'twentyten'), number_format_i18n(get_comments_number()), '<em>' . get_the_title() . '</em>');
        ?></h4>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through?   ?>
            <div class="navigation">
                <div class="nav-previous"><?php previous_comments_link(__('<span class="meta-nav">&larr;</span> Older Comments', 'twentyten')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Newer Comments <span class="meta-nav">&rarr;</span>', 'twentyten')); ?></div>
            </div> <!-- .navigation -->
        <?php endif; // check for comment navigation   ?>

        <ol class="commentlist">
            <?php
            wp_list_comments(array('callback' => 'twentyten_comment'));
            ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through?   ?>
            <div class="navigation">
                <div class="nav-previous"><?php previous_comments_link(__('<span class="meta-nav">&larr;</span> Older Comments', 'twentyten')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Newer Comments <span class="meta-nav">&rarr;</span>', 'twentyten')); ?></div>
            </div><!-- .navigation -->
        <?php endif; // check for comment navigation   ?>

        <?php
    else : // or, if we don't have comments:

        /* If there are no comments and comments are closed,
         * let's leave a little note, shall we?
         */
        if (!comments_open()) :
            ?>
            <p class="nocomments"><?php _e('Comments are closed.', 'twentyten'); ?></p>
        <?php endif; // end ! comments_open()  ?>

    <?php
    endif;
    ?>

    <section class="subscribe-form position-relative">
        <?php echo do_shortcode('[subscriber-itlocation text_class=""/]'); ?>
    </section>
    <?php
    /*
      ?>
      <div id="comment-respond" class="comment-form-elements">
      <?php
      $args = array('comment_notes_after' => '');
      comment_form($args);
      ?>
      </div>
      <?php
     * 
     */
    ?>
    <?php if (comments_open()) { ?>
        <section id="respond" class="respond-form">
            <h4 class="page-title"><?php comment_form_title(__("Leave a Reply", "twentyten"), __("Leave a Reply to", "twentyten") . ' %s'); ?></h4>
			<span style="color:#000;">Spam Protected</span>
            <div id="cancel-comment-reply">
                <p class="small"><?php cancel_comment_reply_link(__("Cancel", "twentyten")); ?></p>
            </div>
            <?php if (get_option('comment_registration') && !is_user_logged_in()) { ?>
                <div class="help">
                    <p><?php _e("You must be", "twentyten"); ?> <a href="<?php echo wp_login_url(get_permalink()); ?>"><?php _e("logged in", "twentyten"); ?></a> <?php _e("to post a comment", "twentyten"); ?>.</p>
                </div>
            <?php } else { ?>
                <div id="comment-respond" class="comment-form-elements">
                <div class="">
                <div class="">
                <div class="row">
                    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
                        <?php if (is_user_logged_in()) { ?>
                            <div class="col-sm-12">
                            <p class="comments-logged-in-as"><?php _e("Logged in as", "twentyten"); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e("Log out of this account", "twentyten"); ?>"><?php _e("Log out", "twentyten"); ?> &raquo;</a></p></div>
                        <?php } else { ?>
                        
                        
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="inputEmail"><?php _e("Name", "twentyten"); ?></label>
                                    <div class="">
                                        <input type="text" name="author" id="author" class="form-control" value="<?php echo esc_attr($comment_author); ?>" placeholder="<?php _e("Your Name", "twentyten"); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="email"><?php _e("Email", "twentyten"); ?></label>
                                    <div class="">
                                        <input type="email" name="email" id="email" class="form-control" value="<?php echo esc_attr($comment_author_email); ?>" placeholder="<?php _e("Your Email", "twentyten"); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="url"><?php _e("Website", "twentyten"); ?></label>
                                    <div class="">
                                        <input type="url" name="url" id="url" class="form-control" value="<?php echo esc_attr($comment_author_url); ?>" placeholder="<?php _e("Your Website", "twentyten"); ?>" tabindex="3" />
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                            <div class="form-group">
                                <div class="live-validation-textarea">
                                	<label class="control-label" for="url"><?php _e("Comments", "twentyten"); ?></label>
                                	<textarea rows="8" name="comment" id="comment" class="form-control" placeholder="<?php _e("Your Comment Here…", "twentyten"); ?>" tabindex="4"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary" name="submit" type="submit" id="submit" tabindex="5" value="<?php _e("Submit Comment", "twentyten"); ?>" />
                            <?php comment_id_fields(); ?>
                        </div>
                        <?php
                        do_action('comment_form()', $post->ID);
                        ?>
                    </form>
                    </div>
                    </div>
                </div>
                <?php } ?>
        </section><!-- #section -->
        <?php } ?>
</div><!-- #comments -->
