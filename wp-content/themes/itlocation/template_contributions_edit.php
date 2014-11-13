<style>
#preview{
   height: 200px;
    margin-left: 3px;
    margin-top: -179px;
    width: 200px;
   
}
#previewlogo{
   height: 200px;
    margin-left: 3px;
  margin-top: 7px;
    width: 200px;
   
}


#preview img {
height:190px!important;
width:200px!important;

}

#previewlogo img {
height:190px!important;
width:200px!important;

}

#contribution_image1{
opacity:0;
width:180px;
height:180px;
cursor: pointer;
}

/*#contribution_image2{
opacity:0;
width:180px;
height:180px
}*/
#upload{
    height: 200px;
    margin-bottom: 30px;
    margin-left: 26px;
    width: 200px;
}
#deleteimg{
cursor:pointer;
float:right;
margin-right: -21px;
margin-top: -211px;
}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
function PreviewImage(no) {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("contribution_image"+no).files[0]);

    oFReader.onload = function (oFREvent) {
        document.getElementById("previewimg"+no).src = oFREvent.target.result;
    };
}
jQuery(document).ready(function() {
// Function for Deleting Preview Image.
jQuery("#deleteimg").click(function() {
jQuery('#preview').css("display", "none");
jQuery('#file').val("");
});

});
</script>
<?php
/*
  Template Name: Contributions New & Edit Page
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

$upload_dir = wp_upload_dir();

$errors = array();

if ($_POST['publish-contributions-btn'] || $_POST['draft-contributions-btn']) {

    if (!trim($_POST['contributions_title'])) {

        $errors[] = 'Please Insert Title.';

    }

    if (!trim($_POST['contributions_content'])) {

        $errors[] = 'Please Insert Content.';

    }

    if ($_FILES['contribution_image1']['error'] == 0) {

        $fname = $_FILES['contribution_image1']['name'];

        if ($fname) {

            $filetype = wp_check_filetype(basename($fname), null);

            if ($filetype['type'] == 'image/jpeg' || $filetype['type'] == 'image/png') {

                $fsize_limit = 1024;

                if (get_option('itlocation_generals_comp_file_size_limit')) {

                    $fsize_limit = get_option('itlocation_generals_comp_file_size_limit');

                }

                $fsize = round($_FILES['contribution_image1']['size'] / 1024.0);

                if (!($fsize < $fsize_limit)) {

                    $errors[] = 'Size of Featured image must be small than ' . $fsize_limit . ' KByte.';

                }

            } else {

                $errors[] = 'Extension of Featured image must be jpg and png.';

            }

        }

    }

    if (!count($errors)) {

        if ($_POST['draft-contributions-btn'])

            $staus = 'draft';

        if ($_POST['publish-contributions-btn'])

            $staus = 'publish';

        if (!$_GET['id']) {

            $last_postid = wp_insert_post(array(

                'post_type' => 'member-contributions',

                'post_title' => $_POST['contributions_title'],

                'post_content' => $_POST['contributions_content'],

                'post_status' => $staus,

                'post_excerpt' => $_POST['contributions_excerpt'],

                'post_author' => $current_user->ID

                    ));

			
			if ($_FILES['contribution_image1']['error'] == 0) {

                $filetitle = $_FILES['contribution_image1']['name'];

                $filedest_a = $functions_ph->check_file_nm($upload_dir['path'], $_FILES['contribution_image1']['name']);

                $filetype = wp_check_filetype(basename($filedest_a['basename']), null);

                $filedest = $filedest_a['dirname'] . '/' . $filedest_a['basename'];

                if (!move_uploaded_file($_FILES['contribution_image1']['tmp_name'], $filedest)) {

                    $errors[] = 'File upload error.';

                } else {

                    $attachment = array(

                        'post_mime_type' => $filetype['type'],

                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filetitle)),

                        'post_content' => '',

                        'post_status' => 'inherit'

                    );

                    $attach_id = wp_insert_attachment($attachment, $filedest, $last_postid);

                    require_once( ABSPATH . "wp-admin" . '/includes/image.php' );

                    $attach_data = wp_generate_attachment_metadata($attach_id, $filedest);

                    wp_update_attachment_metadata($attach_id, $attach_data);

                    update_post_meta($last_postid, '_thumbnail_id', $attach_id);

                }

            }

			

			if (isset($_POST['contribution_image2']) && $_POST['contribution_image2'] == '') {

                update_post_meta( $last_postid, "logo_image_url", '');

            } elseif ($_FILES['contribution_image2']['error'] == 0) {

                $filetitle = $_FILES['contribution_image2']['name'];

                $filedest_a = $functions_ph->check_file_nm($upload_dir['path'], $_FILES['contribution_image2']['name']);

                $filetype = wp_check_filetype(basename($filedest_a['basename']), null);

                $filedest = $filedest_a['dirname'] . '/' . $filedest_a['basename'];

                if (!move_uploaded_file($_FILES['contribution_image2']['tmp_name'], $filedest)) {

                    $errors[] = 'File upload error.';

                } else {

                    $attachment = array(

                        'post_mime_type' => $filetype['type'],

                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filetitle)),

                        'post_content' => '',

                        'post_status' => 'inherit'

                    );

                    $attach_id = wp_insert_attachment($attachment, $filedest, $last_postid);

					 update_post_meta( $last_postid, "logo_image_url", wp_get_attachment_url( $attach_id ) );

                }

            }

			
			$your_title = $_POST['your_title'];

			$your_full_name = $_POST['your_full_name'];

			$your_phone = $_POST['your_phone'];

			$your_email = $_POST['your_email'];

			$your_web_address = $_POST['your_web_address'];

			update_post_meta($last_postid, "your_title", $your_title);

			update_post_meta($last_postid, "your_full_name", $your_full_name);

			update_post_meta($last_postid, "your_phone", $your_phone);

			update_post_meta($last_postid, "your_email", $your_email);

			update_post_meta($last_postid, "your_web_address", $your_web_address);

        } else {

            wp_update_post(array(

                'post_title' => $_POST['contributions_title'],

                'post_content' => $_POST['contributions_content'],

                'post_status' => $staus,

                'post_excerpt' => $_POST['contributions_excerpt'],

                'ID' => $_GET['id']

            ));

			if (isset($_POST['featured_image']) && $_POST['featured_image'] == '') {

                wp_delete_post(get_post_meta($_GET['id'], '_thumbnail_id', true));

                delete_post_meta($_GET['id'], '_thumbnail_id');

            } elseif ($_FILES['featured_image']['error'] == 0) {

                $filetitle = $_FILES['featured_image']['name'];

                $filedest_a = $functions_ph->check_file_nm($upload_dir['path'], $_FILES['featured_image']['name']);

                $filetype = wp_check_filetype(basename($filedest_a['basename']), null);

                $filedest = $filedest_a['dirname'] . '/' . $filedest_a['basename'];

                if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $filedest)) {

                    $errors[] = 'File upload error.';

                } else {

                    wp_delete_post(get_post_meta($_GET['id'], '_thumbnail_id', true));

                    delete_post_meta($_GET['id'], '_thumbnail_id');

                    $attachment = array(

                        'post_mime_type' => $filetype['type'],

                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filetitle)),

                        'post_content' => '',

                        'post_status' => 'inherit'

                    );

                    $attach_id = wp_insert_attachment($attachment, $filedest, $last_postid);

                    require_once( ABSPATH . "wp-admin" . '/includes/image.php' );

                    $attach_data = wp_generate_attachment_metadata($attach_id, $filedest);

                    wp_update_attachment_metadata($attach_id, $attach_data);

                    update_post_meta($_GET['id'], '_thumbnail_id', $attach_id);

                }

            }

			if (isset($_POST['contribution_image2']) && $_POST['contribution_image2'] == '') {

                update_post_meta($_GET['id'], "logo_image_url", '');

            } elseif ($_FILES['contribution_image2']['error'] == 0) {

                $filetitle = $_FILES['contribution_image2']['name'];

                $filedest_a = $functions_ph->check_file_nm($upload_dir['path'], $_FILES['contribution_image2']['name']);

                $filetype = wp_check_filetype(basename($filedest_a['basename']), null);

                $filedest = $filedest_a['dirname'] . '/' . $filedest_a['basename'];

                if (!move_uploaded_file($_FILES['contribution_image2']['tmp_name'], $filedest)) {

                    $errors[] = 'File upload error.';

                } else {

                    $attachment = array(

                        'post_mime_type' => $filetype['type'],

                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filetitle)),

                        'post_content' => '',

                        'post_status' => 'inherit'

                    );

                    $attach_id = wp_insert_attachment($attachment, $filedest, $last_postid);

					update_post_meta($_GET['id'], "logo_image_url", wp_get_attachment_url( $attach_id ) );

                }

            }

			
			$your_title = $_POST['your_title'];

			$your_full_name = $_POST['your_full_name'];

			$your_phone = $_POST['your_phone'];

			$your_email = $_POST['your_email'];

			$your_web_address = $_POST['your_web_address'];

			update_post_meta($_GET['id'], "your_title", $your_title);

			update_post_meta($_GET['id'], "your_full_name", $your_full_name);

			update_post_meta($_GET['id'], "your_phone", $your_phone);

			update_post_meta($_GET['id'], "your_email", $your_email);

			update_post_meta($_GET['id'], "your_web_address", $your_web_address);

        }



        if (get_option('itlocation_generals_contributions_list_page')) {

            $pid = get_option('itlocation_generals_contributions_list_page');

            $tmp_url = get_permalink($pid);

        }

        wp_redirect($tmp_url);

    }

}



$post_info = array();

if ($_GET['id']) {

    $post_info = get_post($_GET['id']);

    if ($post_info->post_author != $current_user->ID) {

        wp_redirect(get_site_url());

    }

}

get_header();

$your_logo_url = '';

$your_title = '';

$your_full_name = '';

$your_phone = '';

$your_email = '';

$your_web_address = '';

if( isset($_GET['id']) && $_GET['id'] > 0 ){

	$your_logo_url = get_post_meta($_GET['id'], "logo_image_url");

	$your_title = get_post_meta($_GET['id'], "your_title");

	$your_full_name = get_post_meta($_GET['id'], "your_full_name");

	$your_phone = get_post_meta($_GET['id'], "your_phone");

	$your_email = get_post_meta($_GET['id'], "your_email");

	$your_web_address = get_post_meta($_GET['id'], "your_web_address");

	$your_logo_url = $your_logo_url[0];

	$your_title = $your_title[0];

	$your_full_name = $your_full_name[0];

	$your_phone = $your_phone[0];

	$your_email = $your_email[0];

	$your_web_address = $your_web_address[0];

}

?>
</div>
</div>
</div>
</div><!-- /* Extra divs for header closing */ -->

<div class="page-sub-page inner-page">
    <div class="container">
    
        <div class="page-header">
            <h3 class="page-title-diff"><?php if ($_GET['id']) { _e('Update Content', 'twentyten'); } else {  _e('Insert Content', 'twentyten'); } ?>
            <a class="btn btn-sm btn-success pull-right" href="<?php echo $tmp_url ?>"><?php _e('List', 'twentyten') ?></a></h3>
            
            <?php
            if (get_option('itlocation_generals_contributions_list_page')) {
                $pid = get_option('itlocation_generals_contributions_list_page');
                $tmp_url = get_permalink($pid);
            }
            ?>
            
        </div>

        <div class="row">
            <?php if (count($errors)) { ?>
                <div class="alert alert-danger">
                    <?php
                    foreach ($errors as $error) {
                        echo $error . '<br/>';
                    } ?>
                </div>
                <?php } ?>

            <form id="contributions_edit_form" name="contributions_edit_form" method="post" action="" class="form-itlocation-ph" enctype="multipart/form-data">

                <?php wp_nonce_field('delete-contributions-itlocation', 'delete-contributions-itlocation-security'); ?>
                

                    <div class="col-lg-9 col-md-9 col-sm-9 base page-border">

                        <?php

                        $tmp = '';

                        if (isset($post_info->post_title)) {
                            $tmp = $post_info->post_title;
                        } elseif (count($errors)) {
                            $tmp = $_POST['contributions_title'];
                        } ?>
                        <br>
                        <div class="form-group">
						<label>Title</label>
                        <input type="text" name="contributions_title" value="<?php echo $tmp; ?>" placeholder="<?php _e('Enter title here', 'twentyten') ?>" class="form-control" /></div>

                        <?php
                        $tmp = '';
                        if (isset($post_info->post_content)) {
                            $tmp = $post_info->post_content;
                        } elseif (count($errors)) {
                            $tmp = $_POST['contributions_content'];
                        } ?>
                        
                        <div class="form-group">
                            <label>Content</label>
                            <textarea name="contributions_content" id="contributions_content"><?php echo $tmp; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                        <label><?php _e('Excerpt', 'twentyten') ?></label>
                        <?php
                        $tmp = '';
                        if (isset($post_info->post_excerpt)) {
                            $tmp = $post_info->post_excerpt;
                        } elseif (count($errors)) {
                            $tmp = $_POST['contributions_excerpt'];
                        } ?>

                        <textarea name="contributions_excerpt" id="contributions_excerpt" placeholder="<?php _e('Enter excerpt here', 'twentyten') ?>" class="form-control"><?php echo $tmp; ?></textarea>
                        </div>
                        
                        <br />
                        
                        <h4 class="page-title-diff">Member Detail Information</h4>                        
                       
                       
                       
                       <div class="row">
                           <div class="col-sm-5">                           
                                <div class="form-group logo-image">
                                	<label>Logo Image</label>
                                	<?php
if ($your_logo_url) {

    $logo_image= $your_logo_url;
	}
	else
	{
		//$logo_image='http://www.placehold.it/200x200/EFEFEF/AAAAAA&amp;text=no+image';
		$logo_image='http://www.itlocator.com/wp-content/themes/itlocation/images/image2.jpg';
	}
 ?>	
                               <!--     <div class="fileupload <?php echo (your_logo_url != '') ? 'fileupload-exists' : 'fileupload-new' ; ?>" data-provides="fileupload">
                                    <input type="hidden" value="" name="">    
                                    <div class="fileupload-new thumbnail">
                                    	<img src="http://www.placehold.it/200x200/EFEFEF/AAAAAA&amp;text=no+image">
                                        <div class="img-control">
                                            <a href="" class="btn"><i class="fa fa-upload"></i></a>
                                            <a href="" class="btn"><i class="fa fa-times-circle"></i></a>
                                        </div>
                                    </div>
                                    <?php if( $your_logo_url != '' ){ ?>                                    
                                        <div class="fileupload-preview fileupload-exists thumbnail max-width-200 max-height-150 line-height-20">    
                                        	<img src="<?php echo $your_logo_url; ?>">    
                                        </div>    
                                    <?php } ?>    
                                    <div>
                                    
                                    <span class="btn btn-file hidden">
                                        <span class="fileupload-new">Select image</span>
                                        <span class="fileupload-exists">Change</span>
                                        <input type="file" name="logo_image_url" id="logo_image_url">
                                    </span>
                                    <a href="#" class="btn fileupload-exists hidden" data-dismiss="fileupload">Remove</a>
                                    </div>
                                    
                                
                           <div class="">
                        							     <?php
                                if ($current_company->logo_file_nm) {
                                    $img_url = $upload_dir["baseurl"] . "/comp_logo/" . $current_company->logo_file_nm;
									}
									else
									{
										$img_url='http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image';
									}
                                    ?>
                                    <div class="fileupload fileupload-exists" data-provides="fileupload"><input type="hidden" value="" name=""> 
                           </div>-->
<div class="fileupload-new noMargin">
 <div id="upload">
<div id="previewlogo" class="thumbnail">
<img id="previewimg2" src="<?php echo $logo_image; ?>">
</div>
<input id="contribution_image2" name="contribution_image2" type="file" onchange="PreviewImage(2);">
</div>
</div>
                                </div>
                           
                           </div>
                           
                           <div class="col-sm-7">
                            <div class="form-group">
                                <label>Your Full Name</label>
                                <input type="text" class="form-control" name="your_full_name" value="<?php echo $your_full_name; ?>"/>
                            </div>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="your_title" class="form-control" value="<?php echo $your_title; ?>"/>
                            </div>
                            
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="your_phone" class="form-control" value="<?php echo $your_phone; ?>"/>
                            </div>
                            
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="your_email" class="form-control" value="<?php echo $your_email; ?>"/>
                            </div>
                            
                            <div class="form-group">
                                <label>Web address</label>
                                 <input type="text" name="your_web_address" class="form-control" value="<?php echo $your_web_address; ?>"/>
                                 <span class="help-block"><small>don't forget to enter 'http://'</small></span>
                            </div>
                            
                           </div>
                       </div>
                       
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 base-secondary page-margin">

                        <?php
                        $tmp = '';
                        if (isset($post_info->post_status)) {
                            $tmp = $post_info->post_status;
                            if ($tmp == 'publish')
                                $tmp = 'Publish';
                            elseif ($tmp == 'draft')
                                $tmp = 'Draft';
                        } else {
                            $tmp = 'New';
                        } ?>
                        <br />
                        <h5 class="label label-success"><?php _e('Status', 'twentyten') ?> : <span><?php echo $tmp ?></span></h5>

                        <h4><?php _e('Featured Image', 'twentyten') ?></h4>
                       <?php
                        $f_img_url = get_post_meta(get_post_meta($_GET['id'], '_thumbnail_id', true), '_wp_attached_file', true);
                        if ($f_img_url) {
			    $img_url = $upload_dir['baseurl'] . '/' . $f_img_url;
				echo '<input type="hidden" name="image_exit_fg" value="1" />';
				echo $f_img_url;
				}
				else
				{
					//$img_url='http://www.placehold.it/200x200/EFEFEF/AAAAAA&amp;text=no+image';
					$img_url='http://www.itlocator.com/wp-content/themes/itlocation/images/image2.jpg';
				}
			 ?>	
                      <!--  <?php if ($f_img_url) {?>

                            <input type="hidden" name="image_exit_fg" value="1" />

                            <div class="fileupload fileupload-exists logo-image" data-provides="fileupload"><input type="hidden" value="" name="">

                                <div class="fileupload-new thumbnail">
                                	<img src="http://www.placehold.it/200x200/EFEFEF/AAAAAA&amp;text=no+image">
                                    <div class="img-control">
                                        <a href="" class="btn"><i class="fa fa-upload"></i></a>
                                        <a href="" class="btn"><i class="fa fa-times-circle"></i></a>
                                    </div>
                                </div>

                                <div class="fileupload-preview fileupload-exists thumbnail max-width-200 max-height-150 line-height-20"><img src="<?php echo $upload_dir['baseurl'] . '/' . $f_img_url; ?>" class="max-height-150"></div>
                                
                             

                                <div>

                                    <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="featured_image" id="featured_image"></span>

                                    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>

                                </div>

                            </div>

                            <?php } else { ?>

                            <div class="fileupload fileupload-new logo-image" data-provides="fileupload">
                            	<input type="hidden" value="" name="">
                                <div class="fileupload-new thumbnail noMargin">
                                	<img src="http://www.placehold.it/200x200/EFEFEF/AAAAAA&amp;text=no+image">
                                    <div class="img-control">
                                        <a href="" class="btn"><i class="fa fa-upload"></i></a>
                                        <a href="" class="btn"><i class="fa fa-times-circle"></i></a>
                                    </div>
                                </div>
                              <!-- <div class="fileupload-preview fileupload-exists thumbnail max-width-200 max-height-150 line-height-20"></div>
                               <!--<div class="hidden">
                                    <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="featured_image" id="featured_image"></span>
                                    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>

                            <?php } ?><br/>-->
<div class="fileupload-new noMargin">
 <div id="upload">
<input id="contribution_image1" name="contribution_image1" type="file" onchange="PreviewImage(1);">
<div id="preview" class="thumbnail">
<img id="previewimg1" src="<?php echo $img_url; ?>">
</div>
</div>
</div>

                        <input type="submit" name="publish-contributions-btn" class="btn btn-success" id="publish-contributions-btn" value="<?php _e('Publish', 'twentyten') ?>">

                        <input type="submit" name="draft-contributions-btn" class="btn" id="draft-contributions-btn" value="<?php _e('Draft', 'twentyten') ?>">

                        <input type="button" name="delete-contributions-btn" class="btn" id="delete-contributions-btn" value="<?php _e('Delete', 'twentyten') ?>" pid="<?php echo $_GET['id']; ?>">
<br />
<br />

                    </div>
            </form>

        </div>

    </div>

</div>

<?php get_footer(); ?>