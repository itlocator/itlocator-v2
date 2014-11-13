<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
get_header();
?>
<article>
    <div class="row-fluid">
        <div class="span12" id="the-lastest">
      
            <div class="span8">
                <?php
                /* Queue the first post, that way we know
                 * what date we're dealing with (if that is the case).
                 *
                 * We reset this later so we can run the loop
                 * properly with a call to rewind_posts().
                 */
                if (have_posts())
                    the_post();
                ?>
                <div class="page-header">
                    <h3>
                        <?php if (is_day()) : ?>
                            <?php printf(__('Daily Archives: <span>%s</span>', 'twentyten'), get_the_date()); ?>
                        <?php elseif (is_month()) : ?>
                            <?php printf(__('Monthly Archives: <span>%s</span>', 'twentyten'), get_the_date(_x('F Y', 'monthly archives date format', 'twentyten'))); ?>
                        <?php elseif (is_year()) : ?>
                            <?php printf(__('Yearly Archives: <span>%s</span>', 'twentyten'), get_the_date(_x('Y', 'yearly archives date format', 'twentyten'))); ?>
                        <?php else : ?>
                            <?php _e($wp_query->queried_object->labels->name); ?>
                            <?php 
							$pagetitle=$wp_query->queried_object->labels->name;
							if($pagetitle=="Member Contributions")
							{
							 ?>
                            <span class="pull-right padding-only-left-10" style="font-size:12px !important"><a href="<?php echo get_site_url(); ?>/industry-news-trends/"><?php _e('Industry News', 'twentyten'); ?></a></span>
                            <?php } 
                          if($pagetitle=="Industry News & Trends")
							{
							 ?>
           <span class="pull-right padding-only-left-10" style="font-size:12px !important"><a href="<?php echo get_site_url(); ?>/member-contributions/"><?php _e('Members', 'twentyten'); ?></a></span>
                            <?php } ?> 
                            
                        <?php endif; ?>
                    </h3>
                </div>
                <?php
                /* Since we called the_post() above, we need to
                 * rewind the loop back to the beginning that way
                 * we can run the loop properly, in full.
                 */
                rewind_posts();

                /* Run the loop for the archives page to output the posts.
                 * If you want to overload this in a child theme then include a file
                 * called loop-archive.php and that will be used instead.
                 */
                get_template_part('loop', 'archive');
                ?>
            </div>
            <div class="span4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</article>
<?php get_footer(); ?>
