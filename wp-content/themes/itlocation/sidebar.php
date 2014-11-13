<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<div id="primary" class="widget-area" role="complementary">
    <ul class="xoxo">
        <?php
        if (!dynamic_sidebar('primary-widget-area')) :?>
        <?php endif; // end primary widget area  ?>
    </ul>
</div><!-- #primary .widget-area -->
