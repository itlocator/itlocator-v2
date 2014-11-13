<?php
/*
  Template Name: The Lastest Page
 */
global $functions_ph;
?>
<?php get_header(); ?>
<article>
    <div class="row-fluid">
        <div class="span12 position-relative">
            <div class="position-absolute font-color-ffc000 font-size-45 font-weight-bold top-115 left-60">What's The Latest <br/><br/><br/>In Technology News</div>
            <div class="position-absolute right-10 bottom-10">
                <img src="<?php echo get_bloginfo('template_url'); ?>/images/logo-white-all.png"/>
            </div>
            <?php
            $file_num = 'http://www.placehold.it/980x300/AFAFAF/fff&amp;text=980x300';
            if (get_option('itlocation_generals_image_the_lastest')) {
                $file_num = get_option('itlocation_generals_image_the_lastest');
            }
            ?>
            <img src="<?php echo $file_num; ?>"/>
        </div>
    </div>
</article>

<article>
    <div class="row-fluid">
        <div class="span12" id="the-lastest">
            <div class="span8">
                <h5 class="line-header"><span class="pull-left"><?php _e('IT Locator Latest Updates', 'twentyten'); ?></span><span class="pull-right padding-only-left-10"><a href="<?php echo get_site_url(); ?>/industry-news-trends/"><?php _e('Industry News', 'twentyten'); ?></a></span><span class="pull-right padding-only-left-10"><?php _e('|', 'twentyten'); ?></span><span class="pull-right padding-only-left-10"><a href="<?php echo get_site_url(); ?>/member-contributions/"><?php _e('Members', 'twentyten'); ?></a></span></h5>
                <?php
                $args = array(
                    'posts_per_page' => 6,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'post_type' => 'member-contributions',
                    'post_status' => 'publish',
                    'suppress_filters' => true);
                $member_contributions = get_posts($args);

                if (!count($member_contributions)):
                    ?>
                    <div id="post-0" class="post error404 not-found">
                        <h1 class="entry-title"><?php _e('Not Found', 'twentyten'); ?></h1>
                        <div class="entry-content">
                            <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten'); ?></p>
                            <?php get_search_form(); ?>
                        </div><!-- .entry-content -->
                    </div><!-- #post-0 -->
                    <?php
                else:
                    $idx = 0;
                    foreach ($member_contributions as $post) : setup_postdata($post);
                        if (($idx % 2) == 0) {
                            if ($idx != 0)
                                echo '</ul>';
                            echo '<ul class="thumbnails">';
                        }
                        ?>
                        <li class="span6">
                            <div class="thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('thumb-300*200');
                                    } else {
                                        ?>
                                        <img data-src="holder.js/300x200" alt="300x200" width="300" height="200" src="http://www.placehold.it/300x200/AFAFAF/fff&text=No+Image">
                                        <?php
                                    }
                                    ?>
                                </a>
                                <div class="caption">
                                    <h4><a href="<?php the_permalink(); ?>">
                                            <?php
                                            _e($functions_ph->string_max_length(get_the_title(), 25));
                                            ?>
                                        </a></h4>
                                    <p class="description"><?php _e($functions_ph->string_max_length(get_the_excerpt(), 220)); ?></p>
                                </div>
                                <div class="clearfix"></div>
                                <p><a href="<?php the_permalink(); ?>" class="btn btn-primary pull-right"><?php _e('More', 'twentyten'); ?></a></p>
                                <div class="clearfix"></div>
                            </div>
                        </li>
                        <?php
                        ++$idx;
                        if ($idx == count($member_contributions)) {
                            echo '</ul>';
                        }
                    endforeach;
                endif;
                wp_reset_postdata();
                ?>
                
                <?php
                $args = array(
                    'posts_per_page' => 6,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'post_type' => 'industry-news-trends',
                    'post_status' => 'publish',
                    'suppress_filters' => true);
                $member_contributions = get_posts($args);

                if (!count($member_contributions)):
                    ?>
                    <h5><?php _e('Not Found', 'twentyten'); ?></h5>
                    <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten'); ?></p>
                    <?php get_search_form(); ?>
                    <?php
                else:
                    $idx = 0;
                    foreach ($member_contributions as $post) : setup_postdata($post);
                        if (($idx % 2) == 0) {
                            if ($idx != 0)
                                echo '</ul>';
                            echo '<ul class="thumbnails">';
                        }
                        ?>
                        <li class="span6">
                            <div class="thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('thumb-300*200');
                                    } else {
                                        ?>
                                        <img data-src="holder.js/300x200" alt="300x200" width="300" height="200" src="http://www.placehold.it/300x200/AFAFAF/fff&text=No+Image">
                                        <?php
                                    }
                                    ?>
                                </a>
                                <div class="caption">
                                    <h4><a href="<?php the_permalink(); ?>">
                                            <?php
                                            _e($functions_ph->string_max_length(get_the_title(), 25));
                                            ?>
                                        </a></h4>
                                    <p><?php _e($functions_ph->string_max_length(get_the_excerpt(), 220)); ?></p>
                                </div>
                                <div class="clearfix"></div>
                                <p class="description"><a href="<?php the_permalink(); ?>" class="btn btn-primary pull-right"><?php _e('More', 'twentyten'); ?></a></p>
                                <div class="clearfix"></div>
                            </div>
                        </li>
                        <?php
                        ++$idx;
                        if ($idx == count($member_contributions)) {
                            echo '</ul>';
                        }
                    endforeach;
                endif;
                wp_reset_postdata();
                ?>
            </div>
            <div class="span4">
                <?php
                get_sidebar('the-lastest');
                ?>
            </div>
        </div>
    </div>
</article>

<?php get_footer(); ?>
