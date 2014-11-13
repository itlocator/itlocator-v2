<link rel='stylesheet' id='bootstrap-css'  href='<?php echo get_bloginfo('template_url'); ?>/plugins/jasny-bootstrap/css/bootstrap.min.css' type='text/css' media='all' />
<link rel='stylesheet' id='select2-css'  href='<?php echo get_bloginfo('template_url'); ?>/plugins/select2-3.4.3/select2.css' type='text/css' media='all' />
<link rel='stylesheet' id='jquery-checkbox-css'  href='<?php echo get_bloginfo('template_url'); ?>/plugins/jquery-checkbox.1.3.0b1/jquery.checkbox.css' type='text/css' media='all' />
<link rel='stylesheet' id='admin-itlocation-css'  href='<?php echo get_bloginfo('template_url'); ?>/css/admin.css' type='text/css' media='all' />

<script type='text/javascript'>
    /* <![CDATA[ */
    var images = {"url":"<?php echo get_bloginfo('template_url') ?>/images/"};
    var webroot = {"url":"<?php echo get_bloginfo('template_url') ?>"};
    /* ]]> */
</script>

<script type='text/javascript' src='<?php echo get_bloginfo('template_url'); ?>/plugins/jasny-bootstrap/js/bootstrap.min.js?ver=3.6.1'></script>
<script type='text/javascript' src='<?php echo get_bloginfo('template_url'); ?>/plugins/select2-3.4.3/select2.min.js?ver=3.6.1'></script>
<script type='text/javascript' src='<?php echo get_bloginfo('template_url'); ?>/plugins/liveValidation/jquery.liveValidation.js?ver=3.6.1'></script>
<script type='text/javascript' src='<?php echo get_bloginfo('template_url'); ?>/plugins/jquery-checkbox.1.3.0b1/jquery.checkbox.min.js?ver=3.6.1'></script>

<script type='text/javascript' src='http://maps.googleapis.com/maps/api/js?v=3.exp&#038;sensor=true&#038;ver=3.6.1'></script>
<script type='text/javascript' src='http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble.js?ver=3.6.1'></script>
<script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js?ver=3.6.1'></script>
<script type='text/javascript' src='<?php echo get_bloginfo('template_url'); ?>/js/google-map.js?ver=3.6.1'></script>

<style>
    input[type="text"], input[type="password"], input[type="number"], input[type="search"], input[type="email"], input[type="url"], textarea {
        -moz-box-sizing: initial;
        -webkit-box-sizing: initial;
        -ms-box-sizing: initial;
    }
</style>
<?php
global $functions_ph, $time_zone;

get_currentuserinfo();

$upload_dir = wp_upload_dir();
$company_model = new companyModelItlocation();
$address_model = new addressModelItlocation();
$current_company = $company_model->get_by_id($_GET['cid']);
$cur_author = new WP_User($current_company->user_id);

$fsize_limit = 1024;
if (get_option('itlocation_generals_comp_file_size_limit')) {
    $fsize_limit = get_option('itlocation_generals_comp_file_size_limit');
}

$error = array();
if ($_POST['submit']) {
    $destination_path = $upload_dir["basedir"] . "/user_photos/";
    if (!file_exists($destination_path))
        mkdir($destination_path, 0777);

    if (isset($_POST['user_photo']) && $_POST['user_photo'] == '') {
        $photo_nm = get_user_meta($cur_author->ID, 'user_photo_nm', true);
        if ($photo_nm)
            unlink($destination_path . $photo_nm);

        update_user_meta($cur_author->ID, 'user_photo_nm', '');
    } elseif ($_FILES['user_photo']['error'] == 0) {
        $fname = $_FILES['user_photo']['name'];
        if ($fname) {
            $real_fname = mktime() . "_" . $fname;
            $filetype = wp_check_filetype(basename($fname), null);
            $destination_file = $destination_path . $real_fname;

            if ($filetype['type'] == 'image/jpeg' || $filetype['type'] == 'image/png') {
                $fsize = round($_FILES['user_photo']['size'] / 1024.0);
                if ($fsize < $fsize_limit) {
                    if (move_uploaded_file($_FILES['user_photo']['tmp_name'], $destination_file)) {
                        $photo_nm = get_user_meta($cur_author->ID, 'user_photo_nm', true);
                        if ($photo_nm)
                            unlink($destination_path . $photo_nm);
                        update_user_meta($cur_author->ID, 'user_photo_nm', $real_fname);
                    } else {
                        $errors[] = 'error upload';
                    }
                } else {
                    $errors[] = 'error file size';
                }
            } else {
                $errors[] = 'error';
            }
        }
    }

    $destination_path = $upload_dir["basedir"] . "/comp_logo/";
    if (!file_exists($destination_path))
        mkdir($destination_path, 0777);
    if (isset($_POST['comp_logo']) && $_POST['comp_logo'] == '') {
        if ($current_company->logo_file_nm)
            unlink($destination_path . $current_company->logo_file_nm);
        $info['logo_file_nm'] = '';
        $company_model->update_by_id($current_company->id, $info);
    } elseif ($_FILES['comp_logo']['error'] == 0) {
        $fname = $_FILES['comp_logo']['name'];
        if ($fname) {
            $real_fname = mktime() . "_" . $fname;
            $filetype = wp_check_filetype(basename($fname), null);
            $destination_file = $destination_path . $real_fname;

            if ($filetype['type'] == 'image/jpeg' || $filetype['type'] == 'image/png') {
                $fsize = round($_FILES['comp_logo']['size'] / 1024.0);
                if ($fsize < $fsize_limit) {
                    if (move_uploaded_file($_FILES['comp_logo']['tmp_name'], $destination_file)) {
                        if ($current_company->logo_file_nm)
                            unlink($destination_path . $current_company->logo_file_nm);
                        $info['logo_file_nm'] = $real_fname;
                        $company_model->update_by_id($current_company->id, $info);
                    } else {
                        $errors[] = 'error upload';
                    }
                } else {
                    $errors[] = 'error file size';
                }
            } else {
                $errors['comp_logo'] = 'error';
            }
        }
    }

    if ($_REQUEST['new_pass']) {
        if ($_REQUEST['new_pass'] == $_REQUEST['conform_pass']) {
            $arg = array(
                'ID' => $cur_author->ID,
                'user_pass' => $_REQUEST['new_pass']
            );
            wp_update_user($arg);
        }
    }

    if (isset($_POST['services'])) {
        $tmp_a = $_POST['services'];
        $services_model = new generaldataCompanyRelationshipsModelItlocation('services');
        $services_model->delete_by_comp_id($current_company->id);
        foreach ($tmp_a as $value) {
            $services_model->insert_by_cid_sid($current_company->id, $value);
        }
    }

    if (isset($_POST['certifications'])) {
        $tmp_a = $_POST['certifications'];
        $services_model = new generaldataCompanyRelationshipsModelItlocation('certifications');
        $services_model->delete_by_comp_id($current_company->id);
        foreach ($tmp_a as $value) {
            $services_model->insert_by_cid_sid($current_company->id, $value);
        }
    }
    if (isset($_POST['industries'])) {
        $tmp_a = $_POST['industries'];
        $services_model = new generaldataCompanyRelationshipsModelItlocation('industries');
        $services_model->delete_by_comp_id($current_company->id);
        foreach ($tmp_a as $value) {
            $services_model->insert_by_cid_sid($current_company->id, $value);
        }
    }
    if (isset($_POST['partners'])) {
        $tmp_a = $_POST['partners'];
        $services_model = new generaldataCompanyRelationshipsModelItlocation('partners');
        $services_model->delete_by_comp_id($current_company->id);
        foreach ($tmp_a as $value) {
            $services_model->insert_by_cid_sid($current_company->id, $value);
        }
    }

    $info = array();

    if ($_POST['companyname'])
        $info['companyname'] = $_POST['companyname'];
    else
        $errors[] = 'Please insert company name';

    $info['firstname'] = $_POST['first_name'];
    $info['lastname'] = $_POST['last_name'];
    $info['contactemail'] = $_POST['user_email'];
    $info['phoneprim'] = $_POST['phoneprim'];
    $info['phonescond'] = $_POST['phonescond'];
    $info['companyurl'] = $_POST['companyurl'];
    $info['address1'] = $_POST['address1'];
    $info['address2'] = $_POST['address2'];
    $info['city'] = $_POST['comp_city'];
    $info['zip_code'] = $_POST['comp_zip_code'];
    $info['state'] = $_POST['comp_state'];
    $info['country'] = $_POST['comp_country'];
    
    global $states;//, $all_country_nms

    //$info['address'] = $_POST['address1'] . ' ' . $_POST['address2'] . ' ' . $_POST['comp_city'] . ' ' . $states[$_POST['comp_country']][$_POST['comp_state']] . ' ' . $all_country_nms[$_POST['comp_country']];
    $info['address'] = $states[$_POST['comp_country']][$_POST['comp_state']];

    $info['time_zone'] = $_POST['comp_time_zone'];
    $info['latitude'] = $_POST['latitude'];
    $info['longitude'] = $_POST['longitude'];
    $info['twitter'] = $_POST['twitter'];
    $info['linkedin'] = $_POST['linkedin'];
    $info['googleplus'] = $_POST['googleplus'];
    $info['facebook'] = $_POST['facebook'];
    $info['description'] = $_POST['comp_description'];
    $info['description'] = substr($info['description'], 0, 1000);
    $company_model->update_by_id($current_company->id, $info);

    if (isset($_POST['locations'])) {
        $locations = $_POST['locations'];
        $locations_lat = $_POST['locations_lat'];
        $locations_lng = $_POST['locations_lng'];
        //$locations_ids = $_POST['locations_ids'];

        $idx = 0;
        $info = array();
        $address_model->del_by_comp_id($current_company->id);
        foreach ($locations as $value) {
            if ($value) {
                $info['comp_id'] = $current_company->id;
                $info['address'] = $value;
                $info['lat'] = $locations_lat[$idx];
                $info['lng'] = $locations_lng[$idx];
                $address_model->new_insert($info);
            }
            ++$idx;
        }
    }

    update_user_meta($cur_author->ID, 'first_name', $_POST['first_name']);
    update_user_meta($cur_author->ID, 'last_name', $_POST['last_name']);
    update_user_meta($cur_author->ID, 'user_title', $_POST['user_title']);
    update_user_meta($cur_author->ID, 'phoneprim', $_POST['phoneprim']);
    update_user_meta($cur_author->ID, 'user_email_show_fg', $_POST['user_email_show_fg']);
    if ($_POST['user_phone_show_fg'])
        update_user_meta($cur_author->ID, 'user_phone_show_fg', 0);
    else
        update_user_meta($cur_author->ID, 'user_phone_show_fg', 1);
    update_user_meta($cur_author->ID, 'auto_renew', $_POST['auto_renew']);

    if ($_POST['user_email']) {
        if (!is_email($_POST['user_email'])) {
            $errors[] = 'Not A Valid Email address.';
        } else {
            if ($cur_author->user_email != $_POST['user_email']) {
                if (email_exists($_POST['user_email'])) {
                    $error = 'Not A Valid Email address.';
                } else {
                    wp_update_user(array('ID' => $cur_author->ID, 'user_email' => $_POST['user_email']));
                }
            }
        }
    } else {
        $errors[] = 'Not A Valid Email address.';
    }

    if ($_POST['username']) {
        if ($cur_author->user_login != $_POST['username']) {
            if (email_exists($_POST['username'])) {
                $error[] = 'username already exists';
            } else {
                global $wpdb;
                $wpdb->update($wpdb->users, array('user_login' => $_POST['username']), array('ID' => $cur_author->ID));
            }
        }
    } else {
        $errors[] = 'Please insert username.';
    }

    if (!count($errors)) {
        ?>
        <script>
            location.href = '?page=company_mgn_itlocation&cid=<?php echo $_GET['cid'] ?>'
        </script>
        <?php
    }
}

$current_company_a = array();
if (count($current_company))
    foreach ($current_company as $key => $value)
        $current_company_a[$key] = $value;
?>
<article>
    <div class="row-fluid">
        <div class="span12" id="jquery-live-validation-edit-profile">
            <?php
            if (count($errors)) {
                ?>
                <div class="alert alert-error">
                    <?php
                    foreach ($errors as $error) {
                        echo $error . '<br/>';
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <form method="post" action="" name="profile-form" class="form-horizontal" enctype="multipart/form-data">
                <div class="span8">
                    <?php wp_nonce_field('get-state-itlocation', 'get-state-security'); ?>
                    <?php wp_nonce_field('check-email-admin-itlocation', 'check-email-admin-itlocation-security'); ?>
                    <?php wp_nonce_field('check-username-admin-itlocation', 'check-username-admin-itlocation-security'); ?>
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $cur_author->ID; ?>" />
                    <h3><?php _e('User Info', 'twentyten') ?></h3>
                    <div class="underline"></div>

                    <div class="control-group">
                        <label class="control-label" for="username"><?php _e('Username', 'twentyten') ?></label>
                        <div class="controls">
                            <input type="text" id="username" name="username" value="<?php echo $cur_author->user_login; ?>" class="pull-left" uid="<?php echo $cur_author->ID; ?>">
                            <div class="loading pull-left" style="display:none"></div>
                        </div>
                    </div>
                    <?php
                    $user_metas = array();

                    $user_metas['first_name'] = 'First Name';
                    $user_metas['last_name'] = 'Last Name';
                    $user_metas['user_title'] = 'Title';
                    foreach ($user_metas as $key => $user_meta):
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php _e($user_meta); ?></label>
                            <div class="controls">
                                <input type="text" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php _e(get_user_meta($cur_author->ID, $key, true)) ?>" placeholder="<?php _e($user_meta); ?>">
                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                    <div class="control-group">
                        <label class="control-label" for="user_email"><?php _e('Email', 'twentyten') ?> <span class="imp_star_mark">*</span></label>
                        <div class="controls">
                            <input type="text" id="user_email" name="user_email" value="<?php echo $cur_author->user_email; ?>" placeholder="<?php _e('Email', 'twentyten') ?>" class="pull-left" uid="<?php echo $cur_author->ID; ?>"><div class="loading pull-left" style="display:none"></div>
                            <span class="font-size-14 margin-only-left-10"><input type="checkbox" name="user_email_show_fg" value="1" <?php echo ( get_user_meta($cur_author->ID, 'user_email_show_fg', true) == '1') ? 'checked="checked"' : '' ?> class="hidden-show"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="phoneprim"><?php _e('Phone', 'twentyten') ?></label>
                        <div class="controls">
                            <input type="text" id="phoneprim" name="phoneprim" value="<?php _e(get_user_meta($cur_author->ID, 'phoneprim', true)) ?>" placeholder="<?php _e('Phone', 'twentyten') ?>">
                            <span class="font-size-14 margin-only-left-10"><input type="checkbox" name="user_phone_show_fg" value="1" <?php echo (get_user_meta($cur_author->ID, 'user_phone_show_fg', true)) ? '' : 'checked="checked"' ?> class="hidden-show"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="new_pass"><?php _e('New Password', 'twentyten') ?></label>
                        <div class="controls">
                            <input type="password" id="new_pass" name="new_pass" value="" placeholder="<?php _e('New Password', 'twentyten') ?>">                       
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="conform_pass"><?php _e('Conform Password', 'twentyten') ?></label>
                        <div class="controls">
                            <input type="password" id="conform_pass" name="conform_pass" value="" placeholder="<?php _e('Conform Password', 'twentyten') ?>">                       
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="user_photo"><?php _e('User Photo', 'twentyten') ?></label>
                        <div class="controls" id="user-photo-grp">
                            <?php
                            $user_photo_nm = get_user_meta($cur_author->ID, 'user_photo_nm', true);
                            if ($user_photo_nm) {
                                $img_url = $upload_dir["baseurl"] . "/user_photos/" . $user_photo_nm;
                                ?>
                                <input type="hidden" name="image_exit_fg" value="1" />
                                <div class="fileupload fileupload-exists" data-provides="fileupload"><input type="hidden" value="" name="">
                                    <div class="fileupload-new thumbnail width-200 height-150"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"></div>
                                    <div class="fileupload-preview fileupload-exists thumbnail max-width-200 max-height-150"><img src="<?php echo $img_url; ?>" class="max-height-150"></div>
                                    <div>
                                        <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="user_photo" id="user_photo"></span>
                                        <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden" value="" name="">
                                    <div class="fileupload-new thumbnail width-200 height-150"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"></div>
                                    <div class="fileupload-preview fileupload-exists thumbnail max-width-200 max-height-150 line-height-20"></div>
                                    <div>
                                        <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="user_photo" id="user_photo"></span>
                                        <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="clearfix"></div>
                            <span class="act act-danger"><?php _e('Size of file must be small than ' . $fsize_limit . ' KMByte. <br/>And File type must be jpeg and png.', 'twentyten'); ?></span>
                        </div>
                    </div>
                    <h3><?php _e('Company Info', 'twentyten') ?></h3>
                    <div class="underline"></div>
                    <div class="control-group">
                        <label class="control-label" for="companyname"><?php _e('Company Name', 'twentyten'); ?> <span class="imp_star_mark">*</span></label>
                        <div class="controls">
                            <input type="text" id="companyname" name="companyname" value="<?php echo $current_company_a['companyname'] ?>" placeholder="<?php _e('Company Name', 'twentyten'); ?>">
                        </div>
                    </div>
                    <?php
                    for ($i = 1; $i <= 2; $i++) {
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="address<?php echo $i; ?>">Address Line <?php _e($i); ?></label>
                            <div class="controls">
                                <input type="text" id="address<?php echo $i; ?>" name="address<?php echo $i; ?>" value="<?php echo $current_company_a['address' . $i]; ?>" placeholder="Address Line <?php _e($i); ?>">
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="control-group">
                        <label class="control-label" for="comp_country"><?php _e('Country', 'twentyten') ?></label>
                        <div class="controls">
                            <?php
                            $opton_tmp = 'US';
                            if ($current_company_a['country'])
                                $opton_tmp = $current_company_a['country'];
                            echo do_shortcode('[countries-ctrl-itlocation id="comp_country" class="pull-left width-220" style="" cutomize_js_fg="yes" selected_option="' . $opton_tmp . '" /]');
                            ?>
                        </div>
                    </div>
                    <div class="control-group" id="comp_state_grp">
                        <label class="control-label" for="comp_state"><?php _e('State/Province', 'twentyten') ?></label>
                        <div class="controls">
                            <div class="loading" style="display: none"></div>

                            <div id="comp_state_ctrl">
                                <input type="text" id="comp_state" name="comp_state" value="<?php echo $current_company_a['comp_state'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="comp_city"><?php _e('City', 'twentyten') ?></label>
                        <div class="controls">
                            <div class="loading" style="display: none"></div>

                            <div id="comp_state_ctrl">
                                <input type="text" id="comp_city" name="comp_city" value="<?php echo $current_company_a['city'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="comp_zip_code"><?php _e('Zip Code/PC', 'twentyten') ?></label>
                        <div class="controls">
                            <input type="text" placeholder="<?php _e('Zip Code', 'twentyten') ?>" name="comp_zip_code" id="comp_zip_code" class="required" value="<?php echo $current_company_a['zip_code'] ?>">
                            <div class="clearfix"></div><br/>
                            <input type="button" value="<?php _e('Set Address on Map', 'twentyten') ?>" class="btn btn-mini btn-info" role="<?php echo $current_company->user_role; ?>" id="main_address_setting_lat_lng" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="latitude"><?php _e('Latitude', 'twentyten') ?></label>
                        <div class="controls">
                            <div id="comp_state_ctrl">
                                <input type="text" id="latitude" name="latitude" value="<?php echo $current_company_a['latitude'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="longitude"><?php _e('Longitude', 'twentyten') ?></label>
                        <div class="controls">
                            <div id="comp_state_ctrl">
                                <input type="text" id="longitude" name="longitude" value="<?php echo $current_company_a['longitude'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="comp_time_zone"><?php _e('Time Zone', 'twentyten') ?></label>
                        <div class="controls">
                            <select id="comp_time_zone" name="comp_time_zone" class="pull-left populate placeholder width-220">
                                <option value=""></option>
                                <?php
                                foreach ($time_zone as $key => $val) {
                                    $tmp_sel = '';
                                    if ($current_company_a['time_zone'] == $key)
                                        $tmp_sel = 'selected';
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $tmp_sel; ?>><?php echo $val; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php
                    $company_metas = array();
                    $company_metas['companyurl'] = 'URL';
                    $company_metas['phonescond'] = 'Phone';
                    $company_metas['twitter'] = 'Twitter';
                    $company_metas['linkedin'] = 'Linkedin';
                    $company_metas['googleplus'] = 'Google+';
                    $company_metas['facebook'] = 'Facebook';

                    foreach ($company_metas as $key => $company_meta):
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="<?php echo $key; ?>"><?php _e($company_meta); ?></label>
                            <div class="controls">
                                <input type="text" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $current_company_a[$key] ?>" placeholder="<?php echo _e($company_meta); ?>">
                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>

                    <h3><?php _e('Company Profile', 'twentyten') ?></h3>
                    <div class="underline"></div>
                    <div class="control-group">
                        <label class="control-label" for="comp_description"><?php _e('Description', 'twentyten') ?></label>
                        <div class="controls">
                            <textarea class="span11" name="comp_description" id="comp_description" rows="10"><?php echo $current_company_a['description'] ?></textarea>
                            <div class="clearfix"></div>
                            <div class="span11">
                                <?php
                                $tmp = 1000 - strlen($current_company_a['description']);
                                ?>
                                <span class="pull-right"><span id="desc_number"><?php echo $tmp; ?></span> characters left.</span>
                            </div>
                        </div>
                    </div>
                    <?php
                    $limit = $functions_ph->get_default_member_limit('services', $current_company->user_role);
                    if ($limit != '0') {
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="services"><?php _e('Services') ?></label>
                            <div class="controls">
                                <?php
                                echo do_shortcode('[services-ctrl-itlocation kind="services" id="services" class="width-300" style="width: 300px" comp_id="' . $current_company->id . '" limit="' . $limit . '" placeholder="Select Services"/]');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    $limit = $functions_ph->get_default_member_limit('industries', $current_company->user_role);
                    if ($limit != '0') {
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="industries"><?php _e('Industries') ?></label>
                            <div class="controls">
                                <?php
                                echo do_shortcode('[services-ctrl-itlocation kind="industries" id="industries" class="width-300" style="width: 300px" comp_id="' . $current_company->id . '" limit="' . $limit . '" placeholder="Select Industries"/]');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="control-group">
                        <label class="control-label" for="user_photo"><?php _e('Company Logo', 'twentyten') ?></label>
                        <div class="controls">
                            <?php
                            if ($current_company->logo_file_nm) {
                                $img_url = $upload_dir["baseurl"] . "/comp_logo/" . $current_company->logo_file_nm;
                                ?>
                                <div class="fileupload fileupload-exists" data-provides="fileupload"><input type="hidden" value="" name="">
                                    <div class="fileupload-new thumbnail width-200 height-150"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"></div>
                                    <div class="fileupload-preview fileupload-exists thumbnail max-width-200 max-height-150"><img src="<?php echo $img_url; ?>" class="max-height-150"></div>
                                    <div>
                                        <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="comp_logo" id="comp_logo"></span>
                                        <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="fileupload fileupload-new" data-provides="fileupload"><input type="hidden" value="" name="">
                                    <div class="fileupload-new thumbnail width-200 height-150"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image"></div>
                                    <div class="fileupload-preview fileupload-exists thumbnail max-width-200 max-height-150 line-height-20"></div>
                                    <div>
                                        <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name="comp_logo" id="comp_logo"></span>
                                        <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                    </div>
                                </div>

                                <?php
                            }
                            ?>
                            <div class="clearfix"></div>
                            <?php ?>
                            <span class="act act-danger"><?php _e('Size of file must be small than ' . $fsize_limit . ' KByte. <br/>And File type must be jpeg and png.', 'twentyten'); ?></span>
                        </div>
                    </div>
                    <?php
                    $limit = $functions_ph->get_default_member_limit('certifications', $current_company->user_role);
                    if ($limit != '0') {
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="certifications"><?php _e('Certifications') ?></label>
                            <div class="controls">
                                <?php
                                echo do_shortcode('[services-ctrl-itlocation kind="certifications" id="certifications" class="width-300" style="width: 300px" comp_id="' . $current_company->id . '" limit="' . $limit . '" placeholder="Select Certifications"/]');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    $limit = $functions_ph->get_default_member_limit('partners', $current_company->user_role);
                    if ($limit != '0') {
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="partners"><?php _e('Partners') ?></label>
                            <div class="controls">
                                <?php
                                echo do_shortcode('[services-ctrl-itlocation kind="partners" id="partners" class="width-300" style="width: 300px" comp_id="' . $current_company->id . '" limit="' . $limit . '" placeholder="Select Partners"/]');
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php
                    $file_num = $functions_ph->get_default_member_limit('collateral', $current_company->user_role);
                    if ($file_num != '0') {
                        ?>
                        <div class="control-group">
                            <label class="control-label"><?php _e('Collateral', 'twentyten') ?></label>
                            <div class="controls">
                                <?php
                                echo do_shortcode('[file-mgn-itlocation modal_id="collateral" file_type="collateral" comp_id="' . $current_company->id . '" default="0, 3, -1"/]');
                                ?>
                            </div>
                        </div>

                    <?php } ?>
                    <?php
                    $file_num = $functions_ph->get_default_member_limit('case_studies', $current_company->user_role);
                    if ($file_num != '0') {
                        ?>
                        <div class="control-group">
                            <label class="control-label"><?php _e('Case Studies', 'twentyten') ?></label>
                            <div class="controls">
                                <?php
                                echo do_shortcode('[file-mgn-itlocation modal_id="case_studies" file_type="case_studies" comp_id="' . $current_company->id . '" default="0, 1, -1"/]');
                                ?>
                            </div>
                        </div>

                    <?php } ?>
                    <div class="control-group">
                        <div class="controls">
                            <input type="submit" name="submit" id="edit-myprofile-btn" value="<?php _e('Save Details', 'twentyten') ?>" class="btn btn-primary">
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <div class="row-fluid">
                        <h3><?php _e('Membership Status', 'twentyten') ?></h3>
                        <div class="underline"></div>
                        <div class="control-group">
                            <?php
                            $role_info = $functions_ph->get_member_role_info_by_id($cur_author->ID);
                            ?>
                            <div class="pull-left"><label class="font-weight-bold"><?php echo $role_info['label']; ?></label></div><div class="<?php echo $role_info['icon']; ?> pull-right"></div>
                            <div class="clearfix"></div>
                            <label><?php _e('Status', 'twentyten') ?> : <?php _e('Active', 'twentyten') ?> </label>
                            <label><?php _e('Renew Date', 'twentyten') ?> : <?php echo ($current_company->renew_date) ? date(get_option('date_format'), strtotime($current_company->renew_date)) : '' ?> </label>
                            <label><?php _e('Auto Renew', 'twentyten') ?>: 
                                <input type="checkbox" name="auto_renew" value="1" <?php echo ( $current_company->auto_renew == '1' ) ? 'checked="checked"' : '' ?>>
                            </label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="clearfix margin-only-bottom-10"></div>
                        <?php
                        $num = $functions_ph->get_default_member_limit('locations', $current_company->user_role);
                        if ($num != 0) {
                            echo do_shortcode('[company-map-itlocation company_id="' . $current_company->id . '" address_ctrl_fg="1" /]');
                            ?>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</article>

<script>
    jQuery(document).ready(function() {
        $data = "action=get-state-itlocation&security=" + jQuery('#get-state-security').val() + "&country_id=" + jQuery('#comp_country').val() + "&state_id=<?php echo $current_company_a['state'] ?>";
        jQuery.ajax({
            type : "post",
            dataType : "html",
            url : admin_ajax.url,
            data: $data,
            success: function(response) {
                jQuery('#comp_state_ctrl').html(response);
                jQuery('#comp_state_ctrl').show();
                jQuery('#state_loading').hide();
            }
        });
        
        
        jQuery("#comp_country").select2({
            formatResult: countries_format,
            formatSelection: countries_format,
            placeholder: "Select Country",
            allowClear: true,
            escapeMarkup: function(m) {
                return m;
            }
        }).on("change", function(e) { 
            jQuery('#comp_state_ctrl').hide();
            jQuery('#comp_state_ctrl').prev().show();
            $data = "action=get-state-itlocation&security=" + jQuery('#get-state-security').val() + "&country_id=" + e.val;
            jQuery.ajax({
                type : "post",
                dataType : "html",
                url : admin_ajax.url,
                data: $data,
                success: function(response) {
                    jQuery('#comp_state_ctrl').html(response);
                    jQuery('#comp_state_ctrl').show();
                    jQuery('#comp_state_ctrl').prev().hide();
                }
            });
        });
    
        jQuery('#jquery-live-validation-edit-profile').liveValidation({
            validIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-valid.png', 
            invalidIco: webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png', 
            required: ['user_email', 'companyname', 'username', 'address1'],
            fields: {
                username: /^\S.*$/,
                address1: /^\S.*$/,
                user_email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
                companyname: /^\S.*$/
            }
        });
    
        jQuery("#phoneprim").live('focusout', function() {
            //check_phone_format("phoneprim");
        });
        jQuery("#phoneprim").live('keyup', function() {
            //check_phone_format("phoneprim");
        });
    
        jQuery("#phonescond").live('focusout', function() {
            //check_phone_format("phonescond");
        });
        jQuery("#phonescond").live('keyup', function() {
            //check_phone_format("phonescond");
        });
        jQuery("#user_email", jQuery("#jquery-live-validation-edit-profile")).live('keyup', function() {//focusout
            check_user_email(this, jQuery('#edit-myprofile-btn', jQuery("#jquery-live-validation-edit-profile")));
        });
        jQuery("#user_email", jQuery("#jquery-live-validation-edit-profile")).live('focusout', function() {//
            check_user_email(this, jQuery('#edit-myprofile-btn', jQuery("#jquery-live-validation-edit-profile")));
        });
        jQuery("#username", jQuery("#jquery-live-validation-edit-profile")).live('keyup', function() {//focusout
            check_login_name(this, jQuery('#edit-myprofile-btn', jQuery("#jquery-live-validation-edit-profile")));
        });
        jQuery("#username", jQuery("#jquery-live-validation-edit-profile")).live('focusout', function() {//
            check_login_name(this, jQuery('#edit-myprofile-btn', jQuery("#jquery-live-validation-edit-profile")));
        });
    
        jQuery(".delete_comp_file").live('click', function () {
            var r=confirm("Do you really delete!")
            if (r==true) {
                $this = this;
                jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : admin_ajax.url,
                    data: {
                        'action':'delete-profile-comp-file-itlocation',
                        'security':jQuery('#delete-profile-comp-file-security').val(), 
                        'file_id':jQuery(this).attr('file_id')
                    },                
                    success: function(response) {
                        jQuery($this).parent().parent().parent().parent().remove();
                    }
                });
            }
        });
    
        jQuery(".delete_comp_address").live('click', function() {
            var r=confirm("Do you really delete!")
            if (r==true) {
                jQuery(this).parent().remove();
            
                var $icon_url = images.url + 'marker-free.png';
                if(jQuery(this).attr('role') >= 2) {
                    $icon_url = images.url + 'marker-premium.png';
                } else if(jQuery(this).attr('role') == 1) {
                    $icon_url = images.url + 'marker-basic.png';
                }
                reflesh_map_by_location ($icon_url, '');

            }
        });  
   
        jQuery("#add-other-address").live('click', function() {
            jQuery("#ctrl-groups").append(jQuery("#ctrl-group-company-address").html());
        });  
        
    });
            
    //check_phone_format("user_phone");
    
    
    
    function countries_format(state) {
        if (!state.id) return state.text; // optgroup
        return "<img class='flag' src='"+images.url+"/flags/" + state.id.toLowerCase() + ".png'/>" + state.text;
    }


    function check_user_email($email_obj, $btn_obj) {
        if(jQuery($email_obj).parent().find('img[alt="Valid"]').length) {
            jQuery($btn_obj).attr('disabled', 'disabled');

            jQuery('img', jQuery($email_obj).parent()).hide();
            jQuery('img', jQuery($email_obj).parent()).attr('alt', 'Invalid');
            jQuery('.loading', jQuery($email_obj).parent()).show();

            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'check-email-admin-itlocation',
                    'security':jQuery('#check-email-admin-itlocation-security').val(),
                    'user_email':jQuery($email_obj).val(),
                    'uid':jQuery($email_obj).attr('uid')
                },
                success: function(response) {
                    jQuery($btn_obj).removeAttr('disabled');
                    jQuery('img', jQuery($email_obj).parent()).show();
                    jQuery('.loading', jQuery($email_obj).parent()).hide();
                    if(response.error) {
                        jQuery('img', jQuery($email_obj).parent()).attr('alt', 'Invalid');
                        jQuery('img', jQuery($email_obj).parent()).attr('src', webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png');
                    } else {
                        jQuery('img', jQuery($email_obj).parent()).attr('alt', 'Valid');
                    }
                }
            });
        }
    }


    function check_login_name($obj, $btn_obj) {
        if(jQuery($obj).parent().find('img[alt="Valid"]').length) {
            jQuery($btn_obj).attr('disabled', 'disabled');

            jQuery('img', jQuery($obj).parent()).hide();
            jQuery('img', jQuery($obj).parent()).attr('alt', 'Invalid');
            jQuery('.loading', jQuery($obj).parent()).show();

            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : admin_ajax.url,
                data: {
                    'action':'check-username-admin-itlocation',
                    'security':jQuery('#check-username-admin-itlocation-security').val(),
                    'username':jQuery($obj).val(),
                    'uid':jQuery($obj).attr('uid')
                },
                success: function(response) {
                    jQuery($btn_obj).removeAttr('disabled');
                    jQuery('img', jQuery($obj).parent()).show();
                    jQuery('.loading', jQuery($obj).parent()).hide();
                    if(response.error) {
                        jQuery('img', jQuery($obj).parent()).attr('alt', 'Invalid');
                        jQuery('img', jQuery($obj).parent()).attr('src', webroot.url + '/plugins/liveValidation/images/jquery.liveValidation-invalid.png');
                    } else {
                        jQuery('img', jQuery($obj).parent()).attr('alt', 'Valid');
                    }
                }
            });
        }
    }

</script>
