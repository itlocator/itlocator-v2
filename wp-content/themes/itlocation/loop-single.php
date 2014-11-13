<?php if (have_posts()) while (have_posts()) : the_post(); ?>
<style>
.contributter_details div,img{
line-height:25px;	
}
</style>
 	<h3 class="page-title-diff dM"><?php the_title(); ?></h3>
    <div class="entry-meta">
		<small><?php twentytwelve_entry_meta(); ?></small>
        <?php edit_post_link(__('Edit', 'twentyten'), '<span class="edit-link">', '</span>'); ?>
    </div>
 
    <div class="row">
        <div class="col-sm-4">
            <div class="media billboard">
                <div class="img-round"><?php the_post_thumbnail('medium'); ?></div>
                
                <div class="entry-meta">
                    <?php if (has_post_thumbnail()) { ?>  
                    <?php
                        global $post;
                        $custom = get_post_custom($post->ID);
                        $logo_image_url = $custom['logo_image_url'][0];
                        $your_full_name = $custom['your_full_name'][0];
                        $your_title = $custom['your_title'][0];
                        $your_phone = $custom['your_phone'][0];
                        $your_email = $custom['your_email'][0];
                        $your_web_address = $custom['your_web_address'][0];
                        if( $logo_image_url != '' ){
                    ?>
                    <div class="contribution-detail">
                        <div class="client-logo">
                        	<img src="<?php echo $logo_image_url; ?>"/>
                        </div>
                        <address>
                            <i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;<strong><?php echo $your_full_name; ?></strong> (<?php echo $your_title; ?>)<br>
                            <i class="fa fa-external-link"></i>&nbsp;&nbsp;&nbsp;<a href="<?php echo $your_web_address; ?>" target="_blank"><?php echo $your_web_address; ?></a><br>
                            <i class="fa fa-phone"></i>&nbsp;&nbsp;&nbsp;<?php echo $your_phone; ?><br>
                            <i class="fa fa-envelope "></i>&nbsp;&nbsp;&nbsp;<a href="mailto:<?php echo $your_email; ?>" data-behavior="truncate"><?php echo $your_email; ?></a>
                        </address>
                    </div>
                    <?php } ?>                
                    <?php } ?>
                    </div>
                </div>
        </div>
        
        <div class="col-sm-8">
            <div id="post-<?php the_ID(); ?>" <?php post_class('row-fluid'); ?>>
                <div class="description"><?php the_content(); ?></div>
                <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'twentyten'), 'after' => '</div>')); ?>
            </div>
        </div>
    </div>
        
        
        <?php comments_template('', true); ?>
        <?php endwhile; // end of the loop. ?>