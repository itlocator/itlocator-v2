<?php
/**
 * Flexible Posts Widget: Default widget template
 */
// Block direct requests
if (!defined('ABSPATH'))
    die('-1');

echo $before_widget;
if (!empty($title))
    echo $before_title . $title . $after_title;

global $functions_ph;

if (count($all_comments)):
    ?>
    <ul id="itlocation-comments" class="sidebar-ul">
        <?php
        $idx = 0;
        foreach ($all_comments as $key => $comment) {
            if ($number == $idx)
                break;
            $post_obj = get_post($comment['comment_post_ID']);
            ?>
            <li id="comment-<?php echo $comment['comment_ID']; ?>">
            	<div class="media sidebar-post">
            		<h4 class="media-heading">
                        <a href="<?php echo get_permalink($comment['comment_post_ID']); ?>"><?php _e($functions_ph->string_max_length($post_obj->post_title, 25)); ?></a>
                    </h4>
                    <div class="detail dM">
						<span class="author"><i class="fa fa-user"></i> <?php
                        if ($comment['user_id']) { ?>
                            <a href="<?php echo get_author_posts_url($comment['user_id']); ?>"><?php echo $comment['comment_author']; ?></a>
                        <?php } else { echo $comment['comment_author']; } ?> </span> &nbsp;&nbsp;
                        <span class="date"><i class="fa fa-calendar"></i> <?php echo date(get_option('date_format'), strtotime($comment['comment_date'])) ?></span>
                    </div>
           		<?php if ($thumbnail) { ?>
                    <a href="<?php echo get_permalink($comment['comment_post_ID']); ?>" class="pull-left">
                        <div class="img-wrap"><?php
                        if (get_the_post_thumbnail($comment['comment_post_ID'], $thumbsize, array('class' => "")))
                            echo get_the_post_thumbnail($comment['comment_post_ID'], $thumbsize, array('class' => ""));
                        else
                            echo '<img src="http://www.placehold.it/70x70/AFAFAF/fff&amp;text=No+Image" class="">';
                        ?></div>
                    </a>
                <?php } ?>
                <div class="media-body">
                    <p class="content"><?php _e($functions_ph->string_max_length($comment['comment_content'], 75)); ?></p>                    
                </div>
            </div>
            
            </li>
            <?php
            ++$idx;
        }
        ?>
    </ul><!-- .dpe-flexible-posts -->
<?php else: // We have no posts       ?>
    <div class="itlocation-comments no-posts">
        <p><?php _e('No post found', 'flexible-posts-widget'); ?></p>
    </div>
<?php
endif; // End have_posts()

echo $after_widget;
