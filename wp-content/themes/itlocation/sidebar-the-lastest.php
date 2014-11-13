<?php
/* The footer widget area is triggered if any of the areas
 * have widgets. So let's check that first.
 *
 * If none of the sidebars have widgets, then let's bail early.
 */
?>

<div id="the-lastest-widget-area">
    <?php if (is_active_sidebar('sidebar-the-lastest')) : ?>
        <div id="fourth" class="widget-area">
            <ul class="xoxo">
                <?php dynamic_sidebar('sidebar-the-lastest'); ?>
            </ul>
        </div><!-- #fourth .widget-area -->
    <?php endif; ?>

</div><!-- #footer-widget-area -->
