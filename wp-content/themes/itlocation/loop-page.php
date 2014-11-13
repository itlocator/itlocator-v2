	<?php if (have_posts()) while (have_posts()) : the_post(); ?>
        <div class="page-sub-page inner-page">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                    	<h3 class="page-title-diff"><?php the_title(); ?></h3>
                    </div>
                </div><!-- #container -->
                
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 base">
                        <div class="base-content">
                            <div id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>
                                <?php the_content(); ?>
                                <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'twentyten'), 'after' => '</div>')); ?>
                            </div>
                        </div>
                    </div>
                </div><!-- #container -->
                
            </div>
        </div>
	<?php //comments_template('', true); ?>
	<?php endwhile; // end of the loop. ?>