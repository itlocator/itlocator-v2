<?php
/*
  Template Name: The Lastest Page
 */
global $functions_ph; ?>
<?php get_header(); ?>

<!--  Extra div closing */ -->
</div>
</div>
</div>
</div><!--  Extra div closing */ -->

<!-- /* page- container */ -->
<div class="page-sub-page is-blog">
    <div class="container">
    
        <div class="row">
            <div class="col-lg-9">
                <h3 class="page-title-diff"><?php _e('IT Locator Latest Updates', 'twentyten'); ?></h3>
                <div class="help-block">
                    <a href="<?php echo get_site_url(); ?>/member-contributions/"><?php _e('Members', 'twentyten'); ?></a>&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo get_site_url(); ?>/industry-news-trends/"><?php _e('Industry News', 'twentyten'); ?></a>
                </div>
                
                <?php        
                $args = array(        
                    'posts_per_page' => 6,        
                    'orderby' => 'post_date',        
                    'order' => 'DESC',        
                    'post_type' => 'member-contributions',        
                    'post_status' => 'publish',        
                    'suppress_filters' => true);        
                $member_contributions = get_posts($args);
                if (!count($member_contributions)): ?>

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
                                echo '</div>';        
                            echo '<div class="row">'; } ?>
                          <div class="col-sm-6">
                              <div class="blog-cont">
                                  <div class="blog-img">
                                    <a href="<?php the_permalink(); ?>">        
                                        <?php if (has_post_thumbnail()) {        
                                            the_post_thumbnail('thumb-300*200');
                                        } else { ?>
                                         <img data-src="holder.js/300x200" alt="300x200" width="300" height="200" src="http://www.placehold.it/300x200/AFAFAF/fff&text=No+Image">        
                                            <?php } ?>
                                     </a>
                                    <h2 class="blog-title"><a href="<?php the_permalink(); ?>">  <?php _e($functions_ph->string_max_length(get_the_title(), 80)); ?></a></h2>
                                  </div>
                                  <div class="blog-content">
                                    <p><?php _e($functions_ph->string_max_length(get_the_excerpt(), 90)); ?></p>
                                  </div>
                                  <div class="displayB aLeft tM more-btn">
                                    <a href="<?php the_permalink(); ?>"><?php _e('More', 'twentyten'); ?></a>
                                  </div>                
                            </div>
                        </div>
                        <?php
                        ++$idx;        
                        if ($idx == count($member_contributions)) {        
                            echo '<div class="clearfix visible-lg-block visible-md-block"></div>';        
                        }

                    endforeach;

                endif;        
                wp_reset_postdata(); ?>
                
                <?php
                     $args = array(        
                    'posts_per_page' => 6,        
                    'orderby' => 'post_date',        
                    'order' => 'DESC',        
                    'post_type' => 'industry-news-trends',        
                    'post_status' => 'publish',        
                    'suppress_filters' => true);        
                $member_contributions = get_posts($args);

                if (!count($member_contributions)): ?>

                    <h5><?php _e('Not Found', 'twentyten'); ?></h5>        
                    <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten'); ?></p>

                    <?php get_search_form(); ?>        
                    <?php        
                else:

                    $idx = 0;        
                    foreach ($member_contributions as $post) : setup_postdata($post);        
                        if (($idx % 2) == 0) {        
                            if ($idx != 0)        
                                echo '</div>';        
                            echo '<div class="row">';      
                        } ?>

                        <div class="col-sm-6">
                              <div class="blog-cont">
                                  <div class="blog-img">
                                    <a href="<?php the_permalink(); ?>">        
                                        <?php if (has_post_thumbnail()) {        
                                            the_post_thumbnail('thumb-300*200');
                                        } else { ?>
                                         <img data-src="holder.js/300x200" alt="300x200" width="300" height="200" src="http://www.placehold.it/300x200/AFAFAF/fff&text=No+Image">        
                                            <?php } ?>
                                     </a>
                                    <h2 class="blog-title">
                                    	<a href="<?php the_permalink(); ?>"><?php _e($functions_ph->string_max_length(get_the_title(), 80)); ?></a>
                                    </h2>
                                  </div>
                                  <div class="blog-content">
                                    <p><?php _e($functions_ph->string_max_length(get_the_excerpt(), 90)); ?></p>
                                  </div>
                                  <div class="displayB aLeft tM more-btn">
                                    <a href="<?php the_permalink(); ?>"><?php _e('More', 'twentyten'); ?></a>
                                  </div>                
                            </div>
                        </div>
                        <?php

                        ++$idx;

                        if ($idx == count($member_contributions)) {        
                            echo '<div class="clearfix visible-lg-block visible-md-block"></div>';         
                        }        
                    endforeach;        
                endif;
                 wp_reset_postdata();        
                ?>
                  
            </div>
            </div>
            </div>
                
                <!-- /* */ -->
                <div class="col-lg-3">
                    <?php get_sidebar('the-lastest'); ?>
                </div><!-- /* Sidebar ends */ -->
                
        </div>
        
    </div><!-- /* container ends */ -->
</div><!-- /* page container ends */ -->

</div>
</div>

<?php get_footer(); ?>

