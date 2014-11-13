<?php
/*
  Template Name: Contributions List Page
 */
if (!is_user_logged_in()):
    wp_redirect(get_site_url());
endif;

global $current_user, $current_company, $functions_ph;

$edit_fg = $functions_ph->get_default_member_limit('contribution', $current_company->user_role);

if (!$edit_fg) {
    wp_redirect(get_site_url());
}

$current_user = wp_get_current_user();

$status = $_GET['status'];
$tmp_a = split(',', $status);

$status_a = array();
if (isset($status)) {
    if (count($tmp_a)) {
        foreach ($tmp_a as $tmp) {
            $status_a[] = $tmp;
        }
    }
} else {
    $status_a[] = 'publish';
    $status_a[] = 'draft';
}

get_header();
$the_query = new WP_Query(array(
            'author' => $current_user->ID,
            'post_type' => 'member-contributions',
            'post_status' => $status_a,
            'paged' => get_query_var('paged')
        ));
?>
<?php wp_nonce_field('delete-contributions-itlocation', 'delete-contributions-itlocation-security'); ?>

</div>
</div>
</div>
</div><!-- /* Extra divs for header closing */ -->


<div class="page-sub-page inner-page">
    <div class="container">
        <div class="row">
             <h4 class="page-title-diff"><?php _e('My Contributions', 'twentyten') ?></h4>
            <div class="row">
                <div class="col-sm-12 dMxx">
                    <select id="contributions_status" name="contributions_status[]" class="pull-left width-170" multiple>
                        <?php
                        if (!isset($status) || ($status == 'publish,draft')) {
                            $publish_s = 'selected="selected"';
                            $draft_s = 'selected="selected"';
                        } else if ($status == 'publish') {
                            $publish_s = 'selected="selected"';
                            $draft_s = '';
                        } else if ($status == 'draft') {
                            $publish_s = '';
                            $draft_s = 'selected="selected"';
                        }
                        ?>
                        <option value="publish" <?php echo $publish_s; ?>>Publish</option>
                        <option value="draft" <?php echo $draft_s; ?>>Draft</option>
                    </select>
               
            <script>
                jQuery(document).ready(function() {
                    jQuery("#contributions_status").select2({
                        placeholder: ""
                    });
                    jQuery("#contributions_status").on("change", function(e) {
                        if(jQuery("#contributions_status").val())
                            location.href = '<?php echo $functions_ph->parse_url(get_permalink(get_the_ID()) . '&status='); ?>'+jQuery("#contributions_status").val();
                        else
                            location.href = '<?php echo get_permalink(get_the_ID()); ?>';
                        //alert(jQuery("#contributions_status").val());
                    })
                });
            </script>
            <?php
            if (get_option('itlocation_generals_contributions_edit_page')) {
                $pid = get_option('itlocation_generals_contributions_edit_page');
                $tmp_url = get_permalink($pid);
            }
            ?>
            <a class="btn btn-small btn-success pull-right" href="<?php echo $tmp_url ?>"><?php _e('New Contribution', 'twentyten') ?></a>
            <div class="clearfix"></div>
            <?php /* If there are no posts to display, such as an empty archive page */ ?>
            <?php if (!$the_query->have_posts()) : ?>
                <div id="post-0" class="post error404 not-found">
                    <h1 class="entry-title"><?php _e('Not Found', 'twentyten'); ?></h1>
                    <div class="entry-content">
                        <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten'); ?></p>
                        <?php get_search_form(); ?>
                    </div><!-- .entry-content -->
                </div><!-- #post-0 -->
            <?php endif; ?>
             </div>
            </div>
    
            <?php
            $idx = 0;
            while ($the_query->have_posts()) : $the_query->the_post();
                if (($idx % 4) == 0) {
                    if ($idx != 0)
                        echo '</ul>';
                    echo '<ul class="list-unstyled row">';
                }
                ?>
                <li id="post-<?php the_ID(); ?>" <?php post_class('col-md-3 col-sm-3'); ?>>
                    <div class="thumbnail">
                        <?php if (get_post_status(get_the_ID()) == 'publish') { ?>
                            <a href="<?php the_permalink() ?>">
                                <?php } if (has_post_thumbnail()) { the_post_thumbnail('thumb-300*200'); } else { ?>
                                <img data-src="holder.js/300x200" alt="300x200" width="300" height="200" src="http://www.placehold.it/300x200/AFAFAF/fff&amp;text=No+Image">
                                <?php } if (get_post_status(get_the_ID()) == 'publish') { ?>
                            </a>
                        <?php }  ?>
    
                        <div class="caption">
                            <h4 class="entry-title">
                                <?php if (get_post_status(get_the_ID()) == 'publish') { ?>
                                    <a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'twentyten'), the_title_attribute('echo=0')); ?>" rel="bookmark">
                                    <?php } ?>
                                    <?php the_title(); ?>
                                    <?php if (get_post_status(get_the_ID()) == 'publish') { ?>
                                    </a>
                                <?php } ?>
                            </h4>
    
                            <div class="entry-meta font-color-b2b2b2 margin-only-bottom-10">
                                <?php twentyten_posted_on_only_date(); ?>
                            </div><!-- .entry-meta -->
    
                            <div class="entry-meta margin-only-bottom-10">
                                <?php
                                if (get_post_status(get_the_ID()) == 'draft')
                                    echo 'Draft';
                                if (get_post_status(get_the_ID()) == 'publish')
                                    echo 'Publish';
                                if (get_option('itlocation_generals_contributions_edit_page')) {
                                    $pid = get_option('itlocation_generals_contributions_edit_page');
                                    $tmp_url = get_permalink($pid);
                                }
                                ?>
                            </div><!-- .entry-meta -->
    
                           <div class="entry-summary description">
    
                                <?php the_excerpt(); ?>
                            </div><!-- .entry-summary -->
                            <div class="aRight">
                                
                                <a class="btn btn-sm btn-success" href="<?php echo $functions_ph->parse_url($tmp_url . '&id=' . get_the_ID()); ?>"><?php _e('Edit', 'twentyten') ?></a>
                                <a class="btn btn-sm btn-danger delete-contributions-btn" pid="<?php the_ID(); ?>"><?php _e('Delete', 'twentyten') ?></a>
                               
                            </div>
                        </div>
                    </div>
    
                </li><!-- #post-## -->
                <?php
                ++$idx;
                if ($idx == count($the_query->posts)) {
                    echo '</ul>';
                }
                ?>
                <?php
            endwhile; // End the loop. Whew.  
            wp_reset_postdata();
            ?>
            <div class="pagination text-align-center">
                <?php
                $big = 999999999; // need an unlikely integer
    
                echo paginate_links(array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    'type' => 'list',
                    'prev_text' => __('&larr;'),
                    'next_text' => __('&rarr;'),
                    'current' => max(1, get_query_var('paged')),
                    'total' => $the_query->max_num_pages
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>