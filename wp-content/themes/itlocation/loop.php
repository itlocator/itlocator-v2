<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if (!have_posts()) : ?>
    <div id="post-0" class="post error404 not-found">
        <h1 class="entry-title"><?php _e('Not Found', 'twentyten'); ?></h1>
        <div class="entry-content">
            <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten'); ?></p>
            <?php get_search_form(); ?>
        </div><!-- .entry-content -->
    </div><!-- #post-0 -->
<?php endif; ?>


<div class="row">

<?php
$idx = 0;
while (have_posts()) : the_post();
    if (($idx % 2) == 0) {
        if ($idx != 0)
            //echo '</div>';
        echo '<div class="clearfix visible-lg-block visible-md-block"></div>'; } ?>
        
    <div id="post-<?php the_ID(); ?>" class="col-lg-6">
        <div class="blog-cont">
            <div class="blog-img">
            <a href="<?php the_permalink(); ?>">
                <?php
                if (has_post_thumbnail()) { ?>
                    <?php the_post_thumbnail('thumb-300*200'); ?>
                    <?php } else { ?>
                    <img data-src="holder.js/300x200" alt="300x200" width="300" height="200" src="http://www.placehold.it/300x200/AFAFAF/fff&amp;text=No+Image">
                    <?php  } ?>
            </a>
            <h2 class="blog-title">
            <a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'twentyten'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
            <div class="help-block"> <?php twentyten_posted_on(); ?></div>
            </div>
          
            <div class="blog-content">
                <?php if (is_archive() || is_search()) : // Only display excerpts for archives and search.   ?>
                    <div class="entry-summary description">
                        <?php  the_excerpt(); ?>
                    </div><!-- .entry-summary -->
                <?php else : ?>
                    <div class="entry-content description">
                        <?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten')); ?>
                        <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'twentyten'), 'after' => '</div>')); ?>
                    </div><!-- .entry-content -->
                <?php endif; ?>
            </div>
            <div class="displayB aLeft tM more-btn">
            	<a href="<?php the_permalink(); ?>"><?php _e('More', 'twentyten'); ?></a>
            </div>
        </div>

    </div><!-- #post-## -->
    <?php
    ++$idx;
    if ($idx == count($wp_query->posts)) {
        echo '<div class="clearfix visible-lg-block visible-md-block"></div>'; } ?>
        
    <?php comments_template('', true); ?>
    <?php endwhile; // End the loop. Whew. ?>
    
   </div> 
   
   
<div class="pagination">
<ul class="pagination">
    <?php
    $big = 999999999; // need an unlikely integer

    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'type' => 'list',
        'prev_text' => __('&larr;'),
        'next_text' => __('&rarr;'),
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
	
    ?>
    </ul>
</div>