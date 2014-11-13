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

if (count($all_posts)):
    ?>
    <ul id="itlocation-comments" class="sidebar-ul">
        <?php
        $idx = 0;
        foreach ($all_posts as $key => $post) {
            if ($number == $idx)
                break;
            ?>
            <li id="comment-<?php echo $post['ID']; ?>">
            	<div class="media sidebar-post">
                    <h4 class="media-heading">
                        <a href="<?php echo get_permalink($post['ID']); ?>"><?php _e($functions_ph->string_max_length($post['post_title'], 25)); ?></a>
                    </h4>
                	<div class="detail dM">
                        <span class="author">
                            <a href="<?php echo get_author_posts_url($post['post_author']); ?>">
                                <?php
                                echo get_user_meta($post['post_author'], 'first_name', true);
                                echo ' ';
                                echo get_user_meta($post['post_author'], 'last_name', true);
                                ?>
                            </a>
                        </span>
                        <span class="date">
                            <i class="fa fa-calendar"></i> <?php echo date(get_option('date_format'), strtotime($post['post_date'])) ?>
                        </span>
                    </div>
					<?php if ($thumbnail) { ?>
                        <a href="<?php echo get_permalink($post['ID']); ?>" class="pull-left">
                            <div class="img-wrap"><?php
                            if (get_the_post_thumbnail($post['ID'], $thumbsize, array('class' => "")))
                                echo get_the_post_thumbnail($post['ID'], $thumbsize, array('class' => ""));
                            else
                                echo '<img src="http://www.placehold.it/70x70/AFAFAF/fff&amp;text=No+Image" class="">';
                            ?></div>
                        </a>
                    <?php } ?>
                    <div class="media-content">
                        <p class="content">
                            <?php _e($functions_ph->string_max_length($post['post_content'], 75)); ?>
                        </p>
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
