<?php get_header(); ?>

</div>
</div>
</div>
</div>

<div class="page-sub-page inner-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-9 base single-base">
                <?php get_template_part('loop', 'single'); ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 base-secondary single-sidebar">
                <?php  get_sidebar(); ?>
            </div>
        </div><!-- #container -->
    </div>
</div>
<?php get_footer(); ?>
