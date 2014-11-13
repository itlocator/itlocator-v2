<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
get_header();
?>
<article>
    <div class="row-fluid">
        <div class="page-header">
            <h3><?php _e('Not Found', 'twentyten'); ?></h3>
        </div>
        <div class="row-fluid">
            <p><?php _e('Apologies, but the page you requested could not be found. Perhaps searching will help.', 'twentyten'); ?></p>
            <?php get_search_form(); ?>
        </div>
    </div>
</article>
<script type="text/javascript">
    // focus on search field after it has loaded
    document.getElementById('s') && document.getElementById('s').focus();
</script>

<?php get_footer(); ?>