<?php

if (!class_exists("adminItLocation")):

    class adminItLocation {

        function __construct() {
            add_action('admin_init', array($this, 'js_css'));
            add_action('admin_menu', array(&$this, 'mgn_menu'));
            add_filter('set-screen-option', array(&$this, 'table_set_option'), 10, 3);
            add_action('admin_head', array(&$this, 'admin_header'));

            add_action('wp_ajax_delete-company-admin-itlocation', array(&$this, 'delete_company'));
            add_action('wp_ajax_upgrade-company-admin-itlocation', array(&$this, 'upgrade_company'));
            add_action('wp_ajax_downgrade-company-admin-itlocation', array(&$this, 'downgrade_company'));
			
            add_action('wp_ajax_delete-comments-admin-itlocation', array(&$this, 'delete_comments'));
            add_action('wp_ajax_delete-subscribes-admin-itlocation', array(&$this, 'delete_subscribes'));

            add_action('wp_ajax_check-email-admin-itlocation', array(&$this, 'check_email_admin'));
            add_action('wp_ajax_check-username-admin-itlocation', array(&$this, 'check_username_admin'));

            add_action('delete_user', array(&$this, 'delete_user'));
            add_action('set_user_role', array(&$this, 'update_company_info'), 10, 2);

            add_action('save_post', array(&$this, 'save_post'));
            add_action('add_meta_boxes', array(&$this, 'add_custom_box'));
        }

        function js_css() {
            if (is_admin()) {
                wp_enqueue_script('admin-itlocation', get_bloginfo('template_url') . '/js/admin.js', array('jquery', 'json2'));
                wp_localize_script('admin-itlocation', 'admin_ajax', array(
                    'url' => admin_url('admin-ajax.php')
                ));

                $pos = strpos($_REQUEST['page'], 'subscribers_send_mail_itlocation');
                if ($pos !== false) {
                    wp_enqueue_script('ckeditor', get_bloginfo('template_url') . '/plugins/ckeditor/ckeditor.js', array('jquery'));
                }
            }
        }

        function admin_header() {
            $pos = strpos($_REQUEST['page'], 'subscribers_send_mail_itlocation');
            if ($pos !== false) {
                ?>
                <script>
                    window.onload = function() {
                        try {
                            CKEDITOR.replace( 'content' );
                            CKEDITOR.config.height = 250;
                            CKEDITOR.config.resize_minHeight = 300;
                            CKEDITOR.config.width = 600;
                        }catch(err) {}
                    };
                </script>
                <?php

            }
        }

        function add_custom_box() {
            $screens = array('member-contributions');

            foreach ($screens as $screen)
                add_meta_box('member-contributions-itlocator', __('Change User', 'twentyten'), array(&$this, 'inner_custom_box'), $screen);
        }

        function inner_custom_box($post) {
            wp_nonce_field('itlocator_inner_custom_box', 'itlocator_inner_custom_box_nonce');
            include( get_template_directory() . '/inc/admin/views/change_author.php' );
        }

        function save_post($post_id) {
            $post_info = get_post($post_id);
            if (($post_info->post_type == 'forum') || ($post_info->post_type == 'topic') || ($post_info->post_type == 'reply')) {
                if ($_POST['bbp_subscriber_itlocation']) {
                    $email = $_REQUEST['bbp_anonymous_email'];

                    if (email_exists($email)) {
                        $json_encode['error'] = 'Your email already exist.';
                    } else {
                        $subscribe = new subscribeMgnItlocation();
                        if ($subscribe->email_exists($email)) {
                            $json_encode['error'] = 'Your email already exist.';
                        } else {
                            $info['email'] = $email;
                            $confirm_url = $subscribe->insert($info);

                            $subject = 'title of subscribe';
                            if (get_option('itlocation_email_settings_subscribe_title') != '') {
                                $subject = stripslashes(get_option('itlocation_email_settings_subscribe_title'));
                            }
                            $content = 'content of subscribe';
                            if (get_option('itlocation_email_settings_subscribe_email_content') != '') {
                                $content = stripslashes(get_option('itlocation_email_settings_subscribe_email_content'));
                            }

                            $search_array = array('[#confirm_url#]');
                            $replace_array = array($confirm_url);
                            $content = str_replace($search_array, $replace_array, $content);

                            global $functions_ph;
                            $json_encode['error'] = $functions_ph->send_mail($email, '', $subject, $content);
                        }
                    }
                }
            }

            if ($post_info->post_type == 'member-contributions') {
                if (!is_admin())
                    return;

                // Check if our nonce is set.
                if (!isset($_POST['itlocator_inner_custom_box_nonce']))
                    return $post_id;

                $nonce = $_POST['itlocator_inner_custom_box_nonce'];

                // Verify that the nonce is valid.
                if (!wp_verify_nonce($nonce, 'itlocator_inner_custom_box'))
                    return $post_id;

                // If this is an autosave, our form has not been submitted, so we don't want to do anything.
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                    return $post_id;

                remove_action('save_post', array(&$this, 'save_post'));

                // Sanitize user input.
                $author_id = sanitize_text_field($_POST['change-author']);

                // Update the meta field in the database.
                $my_post = array(
                    'ID' => $post_id,
                    'post_author' => $author_id
                );
                wp_update_post($my_post);
                add_action('save_post', array(&$this, 'save_post'));
            }
        }

        function delete_user($user_id) {
            $company_model = new companyModelItlocation();
            $comp_info = $company_model->get_by_user_id($user_id);

            $company_file_model = new compFileMgnModelItlocation();
            $c_files = $company_file_model->get_list_by_comp_id($comp_info->id);

            foreach ($c_files as $c_file) {
                $upload_dir = wp_upload_dir();
                $del_file = $upload_dir["basedir"] . "/comp_files/" . $comp_info->id . '/' . $c_file->real_filename;
                unlink($del_file);
                $company_file_model->del($c_file->id);
            }

            $company_model->del_by_user_id($user_id);
            $company_model->clear_all_address();

            $comments = new commentsMgnItlocation();
            $comments->del_by_cid($comp_info->id);
        }

        function update_company_info($user_id) {
            $company_model = new companyModelItlocation();
            $company_model->update_role_by_uid($user_id);

            $address_model = new addressModelItlocation();
            $user = new WP_User($user_id);
            $role = 0;
            if ($user->roles[0] == 's2member_level1')
                $role = 1;
            if ($user->roles[0] == 's2member_level2')
                $role = 2;

            global $functions_ph;
            $comp_info = $company_model->get_by_user_id($user_id);

            $file_num = $functions_ph->get_default_member_limit('locations', $role);
            $tmp_a = $address_model->get_all_by_comp_id($comp_info->id);

            $idx = 0;
            foreach ($tmp_a as $val) {
                if ($file_num == '-1') {
                    $update_info['flag'] = '1';
                    $address_model->update($val->id, $update_info);
                } else {
                    if ($idx >= $file_num) {
                        $update_info['flag'] = '0';
                        $address_model->update($val->id, $update_info);
                    } else {
                        $update_info['flag'] = '1';
                        $address_model->update($val->id, $update_info);
                    }
                    ++$idx;
                }
            }

            $file_num = $functions_ph->get_default_member_limit('services', $role);
            $services_model = new generaldataCompanyRelationshipsModelItlocation('services');
            $tmp_a = $services_model->get_all_by_cid($comp_info->id);

            $idx = 0;
            foreach ($tmp_a as $val) {
                if ($file_num == '-1') {
                    $services_model->update_flag($val->comp_id, $val->service_id, '1');
                } else {
                    if ($idx >= $file_num) {
                        $services_model->update_flag($val->comp_id, $val->service_id, '0');
                    } else {
                        $services_model->update_flag($val->comp_id, $val->service_id, '1');
                    }
                    ++$idx;
                }
            }

            $file_num = $functions_ph->get_default_member_limit('certifications', $role);
            $services_model = new generaldataCompanyRelationshipsModelItlocation('certifications');
            $tmp_a = $services_model->get_all_by_cid($comp_info->id);

            $idx = 0;
            foreach ($tmp_a as $val) {
                if ($file_num == '-1') {
                    $services_model->update_flag($val->comp_id, $val->service_id, '1');
                } else {
                    if ($idx >= $file_num) {
                        $services_model->update_flag($val->comp_id, $val->service_id, '0');
                    } else {
                        $services_model->update_flag($val->comp_id, $val->service_id, '1');
                    }
                    ++$idx;
                }
            }

            $file_num = $functions_ph->get_default_member_limit('industries', $role);
            $services_model = new generaldataCompanyRelationshipsModelItlocation('industries');
            $tmp_a = $services_model->get_all_by_cid($comp_info->id);

            $idx = 0;
            foreach ($tmp_a as $val) {
                if ($file_num == '-1') {
                    $services_model->update_flag($val->comp_id, $val->service_id, '1');
                } else {
                    if ($idx >= $file_num) {
                        $services_model->update_flag($val->comp_id, $val->service_id, '0');
                    } else {
                        $services_model->update_flag($val->comp_id, $val->service_id, '1');
                    }
                    ++$idx;
                }
            }

            $file_num = $functions_ph->get_default_member_limit('partners', $role);
            $services_model = new generaldataCompanyRelationshipsModelItlocation('partners');
            $tmp_a = $services_model->get_all_by_cid($comp_info->id);

            $idx = 0;
            foreach ($tmp_a as $val) {
                if ($file_num == '-1') {
                    $services_model->update_flag($val->comp_id, $val->service_id, '1');
                } else {
                    if ($idx >= $file_num) {
                        $services_model->update_flag($val->comp_id, $val->service_id, '0');
                    } else {
                        $services_model->update_flag($val->comp_id, $val->service_id, '1');
                    }
                    ++$idx;
                }
            }

            $file_num = $functions_ph->get_default_member_limit('collateral', $role);
            $company_file_model = new compFileMgnModelItlocation();
            $tmp_a = $company_file_model->get_list_by_comp_id($comp_info->id, 'collateral');

            $idx = 0;
            foreach ($tmp_a as $val) {
                if ($file_num == '-1') {
                    $company_file_model->update_flag($val->id, '1');
                } else {
                    if ($idx >= $file_num) {
                        $company_file_model->update_flag($val->id, '0');
                    } else {
                        $company_file_model->update_flag($val->id, '1');
                    }
                    ++$idx;
                }
            }

            $file_num = $functions_ph->get_default_member_limit('case_studies', $role);
            $company_file_model = new compFileMgnModelItlocation();
            $tmp_a = $company_file_model->get_list_by_comp_id($comp_info->id, 'case_studies');

            $idx = 0;
            foreach ($tmp_a as $val) {
                if ($file_num == '-1') {
                    $company_file_model->update_flag($val->id, '1');
                } else {
                    if ($idx >= $file_num) {
                        $company_file_model->update_flag($val->id, '0');
                    } else {
                        $company_file_model->update_flag($val->id, '1');
                    }
                    ++$idx;
                }
            }
        }

        function check_username_admin() {
            check_ajax_referer('check-username-admin-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if (is_user_logged_in()) {
                    $cur_author = new WP_User($_REQUEST['uid']);
                    if ($cur_author->user_login != $_REQUEST['username']) {
                        if (username_exists($_REQUEST['username'])) {
                            $error = 'username exists';
                        }
                    }
                }
                header("Content-Type: application/json");
                echo json_encode(array(
                    'time' => time(),
                    'error' => $error,
                    'tmp' => $cur_author
                ));
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function check_email_admin() {
            check_ajax_referer('check-email-admin-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $error = '';
                if (is_user_logged_in()) {
                    $cur_author = new WP_User($_REQUEST['uid']);
                    if ($cur_author->user_email != $_REQUEST['user_email']) {
                        if (email_exists($_REQUEST['user_email'])) {
                            $error = 'email exists';
                        }
                    }
                }
                header("Content-Type: application/json");
                echo json_encode(array(
                    'time' => time(),
                    'error' => $error
                ));
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function delete_company() {
            check_ajax_referer('delete-company-admin-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $json_a = array();
                $json_a['cid'] = $_REQUEST['cid'];

                $company_model = new companyModelItlocation();
                $tmp = $company_model->get_by_id($json_a['cid']);

                $reassign = 1;
                wp_delete_user($tmp->user_id, $reassign);
                //$company_model->del_by_id($json_a['id']);

                $json_a['error'] = '';
                header("Content-Type: application/json");
                echo json_encode($json_a);

                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }
		
		function upgrade_company() {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $json_a = array();
                $cid = $_REQUEST['cid'];
				
				$company_model = new companyModelItlocation();
                $user_role = $company_model->get_user_role($cid);
				$tmp = $company_model->get_by_id( $cid );
								
				$user_id_role = new WP_User( $tmp->user_id );
				
				if( $user_role == 0 ){
					$user_id_role->set_role('s2member_level1');
					$company_model->set_user_role($cid, 1);
				}else if( $user_role == 1 ){
					$user_id_role->set_role('s2member_level2');
					$company_model->set_user_role($cid, 2);
				}else if( $user_role == 2 ){
					$user_id_role->set_role('s2member_level2');
					$company_model->set_user_role($cid, 2);
				}
				
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }
		
		function downgrade_company() {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $json_a = array();
                $cid = $_REQUEST['cid'];

                $company_model = new companyModelItlocation();
				$user_role = $company_model->get_user_role($cid);
				$tmp = $company_model->get_by_id( $cid );
								
				$user_id_role = new WP_User( $tmp->user_id );
				
				if( $user_role == 0 ){
					$user_id_role->set_role('subscriber');
					$company_model->set_user_role($cid, 0);
				}else if( $user_role == 1 ){
					$user_id_role->set_role('subscriber');
					$company_model->set_user_role($cid, 0);
				}else if( $user_role == 2 ){
					$user_id_role->set_role('s2member_level1');
					$company_model->set_user_role($cid, 1);
				}
				
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }
		
        function delete_comments() {
            check_ajax_referer('delete-comments-admin-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $json_a = array();
                $json_a['rid'] = $_REQUEST['rid'];

                $model = new commentsMgnItlocation();

                $model->del_by_id($json_a['rid']);

                $json_a['error'] = '';
                header("Content-Type: application/json");
                echo json_encode($json_a);

                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function delete_subscribes() {
            check_ajax_referer('delete-subscribes-admin-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $json_a = array();
                $json_a['id'] = $_REQUEST['id'];

                $model = new subscribeMgnItlocation();

                $model->del_by_id($json_a['id']);

                $json_a['error'] = '';
                header("Content-Type: application/json");
                echo json_encode($json_a);
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function mgn_menu() {
            add_menu_page(__('Company Manage'), __('Companies'), 'edit_theme_options', 'company_mgn_itlocation', array(&$this, 'company_list'), '', 7);

            $page_help = add_submenu_page('company_mgn_itlocation', __('All Companies'), __('All Companies'), 'edit_theme_options', 'company_mgn_itlocation', array(&$this, 'company_list'));
            add_action('load-' . $page_help, array($this, 'company_list_help'));

            add_submenu_page('company_mgn_itlocation', 'Add New', 'Add New', 'edit_theme_options', 'company_mgn_new_itlocation', array(&$this, 'company_mgn_new'));

            $page_help = add_submenu_page('company_mgn_itlocation', 'All Comments', 'All Comments', 'edit_theme_options', 'comments_itlocation', array(&$this, 'comment_list'));
            add_action('load-' . $page_help, array($this, 'comment_list_help'));

            $page_help = add_menu_page(__('Subscribers Manage'), __('Subscribers'), 'edit_theme_options', 'subscribers_itlocation', array(&$this, 'subscribers_list'), '', 8);
            add_action('load-' . $page_help, array($this, 'subscribers_list_help'));
            add_submenu_page('subscribers_itlocation', 'Subscribers Manage', 'Subscribers', 'edit_theme_options', 'subscribers_itlocation', array(&$this, 'subscribers_list'));

            $page_help = add_submenu_page('subscribers_itlocation', 'Sent Mail List', 'Sent Mail List', 'edit_theme_options', 'subscribers_sent_mail_list_itlocation', array(&$this, 'sent_mail_list'));
            add_action('load-' . $page_help, array($this, 'sent_mail_list_help'));
            add_submenu_page('subscribers_itlocation', 'Send Mail', 'Send Mail', 'edit_theme_options', 'subscribers_send_mail_itlocation', array(&$this, 'send_mail'));
        }

        function table_set_option($status, $option, $value) {
            return $value;
        }

        function sent_mail_list_help() {
            $option = 'per_page';
            $args = array(
                'label' => 'Companies',
                'default' => 10,
                'option' => 'sent_mail_per_page'
            );

            add_screen_option($option, $args);
        }

        function company_list_help() {
            $option = 'per_page';
            $args = array(
                'label' => 'Companies',
                'default' => 10,
                'option' => 'companies_per_page'
            );

            add_screen_option($option, $args);
        }

        function comment_list_help() {
            $option = 'per_page';
            $args = array(
                'label' => 'Comments',
                'default' => 10,
                'option' => 'comments_per_page'
            );

            add_screen_option($option, $args);
        }

        function subscribers_list_help() {
            $option = 'per_page';
            $args = array(
                'label' => 'Subscribers',
                'default' => 10,
                'option' => 'subscribers_per_page'
            );

            add_screen_option($option, $args);
        }

        function company_list() {
            include( get_template_directory() . '/inc/admin/views/companies.php' );
        }

        function company_mgn_new() {
            include( get_template_directory() . '/inc/admin/views/company_new.php' );
        }

        function comment_list() {
            include( get_template_directory() . '/inc/admin/views/comments.php' );
        }

        function subscribers_list() {
            include( get_template_directory() . '/inc/admin/views/subscribers.php' );
        }

        function send_mail() {
            include( get_template_directory() . '/inc/admin/views/admin_send_mail.php' );
        }

        function sent_mail_list() {
            include( get_template_directory() . '/inc/admin/views/sent_mail_list.php' );
        }

    }

    new adminItLocation();

endif;

require_once( get_template_directory() . '/inc/admin/companies_admin_list_table.class.php' );
require_once( get_template_directory() . '/inc/admin/comments_admin_list_table.class.php' );
require_once( get_template_directory() . '/inc/admin/subscribes_admin_list_table.class.php' );
require_once( get_template_directory() . '/inc/admin/sent_mails_admin_list_table.class.php' );
?>
