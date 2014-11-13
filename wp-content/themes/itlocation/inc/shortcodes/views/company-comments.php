<?php

global $cid, $uid, $current_user, $left_cmt_fg, $view_cmt_fg, $grp_class, $star_class, $title;
get_currentuserinfo();
$r_user = new WP_User($uid);
$rating_obj = new commentsMgnItlocation();
$rating_a = $rating_obj->get_info();
if ($current_user->ID) {
    if ($rating_obj->check_email($cid, $current_user->user_email))
        $left_cmt_fg = 0;
    if ($current_user->user_email == $r_user->user_email)
        $left_cmt_fg = 0;
}

//if (htmlspecialchars($_COOKIE["reg_rating"])) {
//    $fg = 1;
//}
?>

<div class="star-rating-grp <?php echo $grp_class; ?>" id="company-comments">

	<?php if ($title) { ?>
    	<label for="rating"><?php _e($title) ?></label>
    <?php } ?>

    <input type="hidden" name="cid" id="cid" value="<?php echo $cid; ?>">

    <div class="star-rating <?php echo $star_class; ?>" title="Rated <?php echo $rating_a[$cid]; ?> out of 5"><span style="width:<?php echo ($rating_a[$cid] / 5) * 100; ?>%"></span></div>

    <br/><br/>

    <div id="all_view_company_comments">

        <div class="comment-loading text-align-center" style="display:none">

            <img src="<?php echo get_bloginfo('template_url') ?>/images/loading-middle.gif" class="width-50 height-50">

        </div>

        <?php

        if ($view_cmt_fg) {

            wp_nonce_field('company-comments-list-itlocation', 'company-comments-list-itlocation-security');

            ?>

            <div id="list_company_comments_grp">
                <div id="list_company_comments">
                    
                    <?php
                    $paged = 1;
                    $perpage = 5;
                    $comments_obj = $rating_obj->get_info_by_cid($cid);
                    $totalRecords = count($comments_obj);
                    $comments_obj = $rating_obj->get_info_by_cid($cid, $paged, $perpage);
                    foreach ($comments_obj as $comments) { ?>
                    <div class="media" style="border:1px solid #d4d4d4; margin:0; margin-top:-1px; margin-left:-16px; margin-right:-16px; padding:10px;">
                        <div class="media-body">
                        	<div class="star-rating" title="Rated <?php echo $comments->rating; ?>" style="display:inline-block; *display:inline; *zoom:1"> 
                                <span style="width:<?php echo ($comments->rating / 5) * 100; ?>%"></span>
                            </div> out of 5
							<h4 class="media-heading"><?php echo $comments->name; ?></h4>
                            <p><?php _e($comments->comment); ?></p>
                        </div>
                    </div>
                    <?php } ?>
                    
                </div>

                <div id="company-comments-list-nav" class="pagination pagination-mini">
                    <?php
                    $page_nav = new tc_pageNav($totalRecords, $paged);
                    $page_nav->setPerPage($perpage);
                    $page_nav->calculate();
                    echo($page_nav->printNavBarPortion());
                    ?>
                </div>
            </div>
            <?php } ?>

    </div>

    <?php if ($left_cmt_fg) { ?>

        <div class="clearfix"></div>
        <div class="alert alert-error margin-only-bottom-0" id="alert-error" style="display: none">Thanks for your comment.</div>
        <div class="clearfix"></div>
        <div id="jquery-live-validation-company_comments">
            <form action="" method="post" id="company_comments_form">
                <?php wp_nonce_field('company-comments-itlocation', 'company-comments-itlocation-security'); ?>
                <input type="hidden" name="rating_num" id="rating_num" value="" />
                <input type="hidden" name="to_email" id="to_email" value="<?php echo $r_user->user_email; ?>">

                <div class="ctrl-groups">
                    <div class="ctrl-group">
                        <p class="stars"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>
                    </div>

                    <?php

                    if ($current_user->ID) {
                        $full_name = get_user_meta($current_user->ID, 'first_name', true);
                        $full_name .= ' ' . get_user_meta($current_user->ID, 'last_name', true);
                        ?>
                        <input type="hidden" name="reg_uid" id="reg_uid" value="<?php echo $current_user->ID; ?>">
                        <input type="hidden" name="full_name" id="full_name" value="<?php echo $full_name; ?>" />
                        <input type="hidden" name="from_email" id="from_email" value="<?php echo $current_user->user_email; ?>" />
                        <?php

                    } else { ?>

                        <div class="form-group">
                            <label for="full_name" class="control-label"><?php _e('Name', 'twentyten'); ?></label>
                            <input type="text" class="form-control" name="full_name" id="full_name" placeholder="<?php _e('Name', 'twentyten'); ?>" value="">
                        </div>

                        <div class="form-group">
                            <label for="from_email" class="control-label"><?php _e('Email', 'twentyten'); ?></label>
                            <input type="text" class="form-control" name="from_email" id="from_email" placeholder="<?php _e('Email', 'twentyten'); ?>" value="">
                        </div>

                        <?php } ?>

                    <div class="form-group live-validation-textarea">
                        <label for="comment" class="control-label"><?php _e('Comment', 'twentyten'); ?></label>
                        <textarea name="comment" id="comment" class="form-control" rows="4" cols="20" placeholder="<?php _e('Comment', 'twentyten'); ?>"></textarea>
                    </div>

                    <div class="form-group">
                        <input type="submit" id="reg_rating" class="btn btn-info btn-block" value="<?php _e('Submit Rating', 'twentyten'); ?>" />
                    </div>
                </div>

            </form>

        </div>

    <?php } ?>

</div>

<?php

if ($left_cmt_fg) {

    if ($current_user->ID) {

        ?>

        <script>

            jQuery(document).ready(function() {



                /*

                 * company comments

                 */

                jQuery('#jquery-live-validation-company_comments').liveValidation({

                    validIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png', 

                    invalidIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png', 

                    required: ['comment'],

                    fields: {

                        comment:  /^\S.*$/m

                    }

                });

            });

        </script>

    <?php } else { ?>

        <script>

            jQuery(document).ready(function() {



                /*

                 * company comments

                 */

                jQuery('#jquery-live-validation-company_comments').liveValidation({

                    validIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png', 

                    invalidIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png', 

                    required: ['full_name', 'from_email', 'comment'],

                    fields: {

                        full_name: /^\S.*$/,

                        from_email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,

                        comment:  /^\S.*$/m

                    }

                });

            });

        </script>

        <?php

    }

}

?>