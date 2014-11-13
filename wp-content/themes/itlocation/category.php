<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
get_header();
?>
<article>
    <div class="row-fluid">
        <div class="span12">
            <div class="span8">
                <h5 class="category-title"><?php printf(__('%s', 'twentyten'), '<span>' . single_cat_title('', false) . '</span>'); ?></h5>
                <?php
                $category_description = category_description();
                if (!empty($category_description))
                    echo '<div class="archive-meta">' . $category_description . '</div>';

                get_template_part('loop', 'category');
                ?>
            </div>
            <div class="span4">
                <?php get_sidebar(); ?>
            </div>
        </div><!-- #content -->
    </div><!-- #container -->
</article>

<?php get_footer(); ?>
