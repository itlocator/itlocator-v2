<?php
if (!class_exists("functionsPH")):

    class functionsPH {

        function __construct() {
            add_action('wp_head', array(&$this, 'header'));
            add_filter('show_admin_bar', '__return_false');

            add_image_size('thumb-70*70', 70, 70, FALSE); //(cropped)
            add_image_size('thumb-300*200', 300, 200, TRUE); //(cropped)
            add_image_size('thumb-70*70-cropped', 70, 70, TRUE); //(cropped)
            add_action('widgets_init', array(&$this, 'widgets'));
            add_action("init", array(&$this, "create_custom_post_type_itlocation"));

            add_action('after_setup_theme', array(&$this, 'theme_setup'));

            add_filter('wp_page_menu_args', array(&$this, 'page_menu_args'));
            add_filter('excerpt_length', array(&$this, 'excerpt_length'));
            add_filter('excerpt_more', array(&$this, 'auto_excerpt_more'));
            add_filter('get_the_excerpt', array(&$this, 'custom_excerpt_more'));									add_action('admin_menu', array(&$this, 'contribution_default_meta_value'));
        }
		function contribution_default_meta_value() {			add_submenu_page('edit.php?post_type=member-contributions', "Contribution Default Meta Value", "Contribution Default Meta Value", 9, "contribution-default-meta-value", array(&$this, 'contribution_default_meta_value_page'));		}				function contribution_default_meta_value_page(){			include 'admin-page/contribution_default_meta_value_page.php';		}		
        function header() {
            echo stripslashes(get_option('itlocation_generals_google_analytics_script'));
            global $post;
            if ($post->ID == get_option('itlocation_generals_contributions_edit_page')) {
                ?>
                <script>
                    window.onload = function() {
                        try {
                            CKEDITOR.replace( 'contributions_content' );
                            CKEDITOR.config.height = 400;
                            CKEDITOR.config.resize_minHeight = 600;
                        }catch(err) {}
                    };
                </script>
                <?php
            }
        }

        function send_mail($to, $from, $e_title, $content, $files = array(), $title = '', $reply_info = array()) {

            require_once (get_template_directory() . '/php-plugins/phpmailer/class.phpmailer.php');

            $mail = new PHPMailer();
            $mail->IsSendmail();
            $mail->Subject = $e_title;
            if (!$from) {
                $name = 'ItLocator Notification';
                if (get_option('itlocation_email_settings_from_email_nm') != '') {
                    $name = stripslashes(get_option('itlocation_email_settings_from_email_nm'));
                }
                $email = 'info@itlocator.com';
                if (get_option('itlocation_email_settings_from_email_address') != '') {
                    $email = stripslashes(get_option('itlocation_email_settings_from_email_address'));
                }
                $from = $email;
            }
            $mail->SetFrom($from, $name);

            if (!$to) {
                $to = 'info@itlocator.com';
                if (get_option('itlocation_email_settings_from_email_address') != '') {
                    $to = stripslashes(get_option('itlocation_email_settings_from_email_address'));
                }
            }
            $mail->AddAddress($to);

            if ($title == '')
                $title = $e_title;

            $body = $this->get_email_temp($title, $content);
            $mail->MsgHTML($body);
            if (count($files)) {
                foreach ($files as $file)
                    $mail->AddAttachment($file['path'], $file['name']);
            }

            if (count($files)) {
                if ($reply_info)
                    $mail->AddReplyTo($reply_info['email'], $reply_info['name']);
            }

            $error = '';
            if (!$mail->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
            return $error;
        }

        function get_email_temp($title, $content) {
            $temp = '<head><style type="text/css" media="screen">.menu-footer-menu-1-container ul li a,.menu-footer-menu-2-container ul li a,.menu-footer-menu-3-container ul li a {color: #F3F3F3;text-decoration: none;}</style></head>';
            $temp .= '<body style="margin: 0; padding: 0; background-color: #F0F0F0; font-family: TAHOMA; font-size: 14px; color: #333;">';

//$temp .= '<table border="0" style="background: url(\'' . get_bloginfo('template_url') . '/images/top-bg.png\') no-repeat top center;width: 100%;"><tbody><tr><td><img src="' . get_bloginfo('template_url') . '/images/top-bg.png"/>&nbsp;</td></tr></tbody></table>';

            $temp .= '<table border="0" style="width: 100%;border-bottom: 5px solid #E4E4E4"><tbody><tr><td><a href="' . get_site_url() . '"><img src="' . get_bloginfo('template_url') . '/images/logo.png" alt="Logo"/></a></td><td style="color:#F1570B"><font face="TAHOMA">The Best Solutions Come From Local Technology Partners</font></td></tr></tbody></table>';

            $temp .= '<table border="0" style="background: url(\'' . get_bloginfo('template_url') . '/images/bg-earth.png\') no-repeat top center; width: 100%;"><tbody><tr><td></td><td style="border: 1px solid #CCC;background: #FFF;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;padding: 20px;width: 70%;"><h3 style="font-size: 24.5px; margin: 0; border-bottom: 1px solid #EEE;"><font face="TAHOMA">' . $title . '</font></h3><p><font face="TAHOMA">' . $content . '</font></p></td><td></td></tr></tbody></table>';

            $temp .= '<table border="0" style="width: 100%;background-color: #8a8b8a; margin-top: 10px; padding-top: 10px"><tr><td></td><td style="width: 70%;"><table border="0" style="width: 100%;"><td valign="top" style="width: 25%;"><h5 style="margin-bottom: 10px;"><font face="TAHOMA">Site Navigation</font></h5>';
            $temp .= wp_nav_menu(array(
                'theme_location' => 'footer_menu_1',
                'items_wrap' => '<ul id="%1$s" class="%2$s" style="list-style: none;margin: 0;padding: 0; border-left: 1px dotted #F3F3F3; padding-left: 10px;font-size: 12px; ">%3$s</ul>',
                'echo' => false,
                'walker' => new walkerNavMenuEmailItLocation,
                'depth' => 1
                    ));
            $temp .= '</td><td valign="top" style="width: 25%;"><h5 style="margin-bottom: 10px;"><font face="TAHOMA">Why IT Locator</font></h5>';
            $temp .= wp_nav_menu(array(
                'theme_location' => 'footer_menu_2',
                'items_wrap' => '<ul id="%1$s" class="%2$s" style="list-style: none;margin: 0;padding: 0; border-left: 1px dotted #F3F3F3; padding-left: 10px;font-size: 12px; ">%3$s</ul>',
                'echo' => false,
                'walker' => new walkerNavMenuEmailItLocation,
                'depth' => 1
                    ));
            $temp .= '</td><td valign="top" style="width: 25%;"><h5 style="margin-bottom: 10px;"><font face="TAHOMA">Privacy Links</font></h5>';
            $temp .= wp_nav_menu(array(
                'theme_location' => 'footer_menu_3',
                'items_wrap' => '<ul id="%1$s" class="%2$s" style="list-style: none;margin: 0;padding: 0; border-left: 1px dotted #F3F3F3; padding-left: 10px;font-size: 12px; ">%3$s</ul>',
                'echo' => false,
                'walker' => new walkerNavMenuEmailItLocation,
                'depth' => 1
                    ));
            $temp .= '</td><td valign="top" style="width: 25%;"><h5><font face="TAHOMA">Contact us</font></h5><h2><font face="TAHOMA">' . get_option('itlocation_generals_contact_us_phone_number') . '</font></h2><a href="' . get_site_url() . '" class="logo" title="IT Locator"><img src="' . get_bloginfo('template_url') . '/images/logo-email.png" alt="IT Locator"></a></td></table></td><td></td></tr></table>';

            $temp .= '<table border="0" style="width: 100%; background: #7F7F7F;"><tr><td align="right" style="font-size: 12px;"><font face="TAHOMA">© 2009-2013 Itlocator. All rights reserved</font></td></tr></table>';

            $temp .= '</body>';

            return $temp;
        }

        function theme_setup() {
            add_theme_support('post-thumbnails');
            add_theme_support('automatic-feed-links');
            load_theme_textdomain('twentyten', get_template_directory() . '/languages');
            register_nav_menus(array(
                'primary' => __('Primary Navigation', 'twentyten'),
                'footer_menu_1' => 'My Custom Footer Menu 1',
                'footer_menu_2' => 'My Custom Footer Menu 2',
                'footer_menu_3' => 'My Custom Footer Menu 3',
            ));
            $source = get_template_directory() . '/inc/htaccess.txt';
            $upload_dir = wp_upload_dir();
            $dest = $upload_dir["basedir"] . "/admin_mail_attached_files/.htaccess";
            $path = $upload_dir["basedir"] . "/admin_mail_attached_files/";
            if (!is_dir($path)) {
                mkdir($path);
            }
            copy($source, $dest);
            $dest = $upload_dir["basedir"] . "/comp_files/.htaccess";
            $path = $upload_dir["basedir"] . "/comp_files/";
            if (!is_dir($path)) {
                mkdir($path);
            }
            copy($source, $dest);
        }

        function page_menu_args($args) {
            $args['show_home'] = true;
            return $args;
        }

        function excerpt_length($length) {
            return 40;
        }

        function continue_reading_link() {
            return ''; //' <a href="' . get_permalink() . '">' . __('Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten') . '</a>';
        }

        function auto_excerpt_more($more) {
            return ''; //' &hellip;' . $this->continue_reading_link();
        }

        function custom_excerpt_more($output) {
            if (has_excerpt() && !is_attachment()) {
                $output .= $this->continue_reading_link();
            }
            return $output;
        }

        function create_custom_post_type_itlocation() {
            $labels = array(
                'name' => 'Member Contributions',
                'singular_name' => 'Member Contributions',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Member Contribution',
                'edit' => 'Edit',
                'edit_item' => 'Edit Member Contribution',
                'new_item' => 'New Member Contribution',
                'view' => 'View',
                'view_item' => 'View Member Contribution',
                'search_items' => 'Search Member Contributions',
                'not_found' => 'No Member Contributions found',
                'not_found_in_trash' => 'No Member Contributions found in Trash',
                'parent' => 'Parent Member Contribution'
            );
            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'has_archive' => true,
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions', 'trackbacks', 'custom-fields')
            );

            register_post_type('member-contributions', $args);

            $labels = array(
                'name' => 'Industry News & Trends',
                'singular_name' => 'Industry News & Trends',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Industry New & Trend',
                'edit' => 'Edit',
                'edit_item' => 'Edit Industry New & Trend',
                'new_item' => 'New Industry New & Trend',
                'view' => 'View',
                'view_item' => 'View Industry New & Trend',
                'search_items' => 'Search Industry News & Trends',
                'not_found' => 'No Industry News & Trends found',
                'not_found_in_trash' => 'No Industry News & Trends found in Trash',
                'parent' => 'Parent Industry New & Trend'
            );
            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'has_archive' => true,
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions', 'trackbacks', 'custom-fields')
            );

            register_post_type('industry-news-trends', $args);

            $this->cron_job();
            $this->confirm_subscriber();
        }

        function cron_job() {
            // $securit = '123456789';
            $securit = 'asdfgh1';
            if (get_option('itlocation_cron_job_secret')) {
                $securit = get_option('itlocation_cron_job_secret');
            }
            if ($_GET["cron_job_itlocation"] === $securit) {
                /*
                  $company_model = new companyModelItlocation();
                  $company_model->clear_all_company();
                  $company_model->clear_all_address();
                  foreach (get_users() as $user) {
                  $user = new WP_User($user->ID);
                  $company_model->update_role_by_uid($user->ID);
                  }
                 * 
                 */
                /*
                 * send mail written by admin 
                 */
                $mail_mgn_obj = new admin_Sent_Mail_List_Admin();
                $file_mgn_obj = new fileMgnModelItlocation('admin_mail_attached_files');
                $upload_dir = wp_upload_dir();
                $destination_path = $upload_dir["basedir"] . "/admin_mail_attached_files/";

                $mail_data_objs = $mail_mgn_obj->get_info_by_status('confirm');


                if (count($mail_data_objs)) {
                    foreach ($mail_data_objs as $mail_data_obj) {
                        $file_a = array();
                        $file_data_objs = $file_mgn_obj->get_list_by_pid($mail_data_obj->id);
                        if (count($file_data_objs)) {
                            $idx = 0;
                            foreach ($file_data_objs as $file_data_obj) {
                                $file_a[$idx]['path'] = $destination_path . $file_data_obj->real_filename;
                                $file_a[$idx]['name'] = $file_data_obj->filename;
                                ++$idx;
                            }
                        }
                        $to_emails = array();
                        $to_emails = $this->get_emails_for_sent($mail_data_obj->recipient);
                        if (count($to_emails)) {
                            foreach ($to_emails as $to_email) {echo $to_email;
                                $mail_mgn_obj->update($mail_data_obj->id, array('sent_flag' => '1'));
                                $this->send_mail($to_email, '', $mail_data_obj->subject, $mail_data_obj->content, $file_a);
                            }
                        }
                    }
                }

                /*
                 * send contributions
                 */
                $to_emails = array();
                $to_emails = $this->get_emails_for_sent(get_option('itlocation_newsletter_contributions'));
                $args = array('post_type' => 'member-contributions');
                $contribution_a = get_posts($args);
                if (count($contribution_a)) {
                    $e_title = 'Contribution From ItLocator';
                    if (get_option('itlocation_email_settings_title_for_contributions'))
                        $e_title = stripslashes(get_option('itlocation_email_settings_title_for_contributions'));

                    foreach ($contribution_a as $contribution) {
                        if (!get_post_meta($contribution->ID, 'email_sent_flag', true)) {
                            if (count($to_emails)) {
                                update_post_meta($contribution->ID, 'email_sent_flag', '1');
                                $title = '<a href="' . get_permalink($contribution->ID) . '">' . $contribution->post_title . '</a>';
                                foreach ($to_emails as $to_email) {
                                    $this->send_mail($to_email, '', $e_title, $contribution->post_content, $file_a, $title);
                                }
                            }
                        }
                    }
                }

                /*
                 * send news
                 */
                $to_emails = array();
                $to_emails = $this->get_emails_for_sent(get_option('itlocation_newsletter_news'));
                $args = array('post_type' => 'industry-news-trends');
                $news_a = get_posts($args);
                if (count($news_a)) {
                    $e_title = 'News From ItLocator';
                    if (get_option('itlocation_email_settings_title_for_news'))
                        $e_title = stripslashes(get_option('itlocation_email_settings_title_for_news'));

                    foreach ($news_a as $news) {
                        if (!get_post_meta($news->ID, 'email_sent_flag', true)) {
                            if (count($to_emails)) {
                                update_post_meta($news->ID, 'email_sent_flag', '1');
                                $title = '<a href="' . get_permalink($news->ID) . '">' . $news->post_title . '</a>';
                                foreach ($to_emails as $to_email) {
                                    $this->send_mail($to_email, '', $e_title, $news->post_content, $file_a, $title);
                                }
                            }
                        }
                    }
                }

                exit();
            }
        }

        function get_emails_for_sent($recipient_txt) {
            $recipient_a = explode(',', $recipient_txt);
            if (count($recipient_a)) {
                foreach ($recipient_a as $recipient) {
                    if (trim($recipient) == 'subscribers') {
                        $subscribe_mgn_obj = new subscribeMgnItlocation();
                        $subscribe_data_objs = $subscribe_mgn_obj->get_info_by_status('confirm');

//print_r($subscribe_data_objs);

                        if (count($subscribe_data_objs)) {
                            foreach ($subscribe_data_objs as $subscribe_data_obj) {
                                        	echo $query="SELECT user_id FROM company where user_id='".$subscribe_data_obj->id."' and content_updates=1";
								$result=mysql_query($query);
								while($row=mysql_fetch_array($result))
								{
									echo $row['content_updates'];
									if($subscribe_data_obj->id==$row['user_id'])
									{
										$to_emails[] = $subscribe_data_obj->email;
									}
								}
                                $to_emails[] = $subscribe_data_obj->email;
                            }
                        }
                    }
                    if (trim($recipient) == 'listings') {
                        $company_mgn_obj = new companyModelItlocation();
                        $company_data_objs = $company_mgn_obj->get_info_by_status('listings');

                        if (count($company_data_objs)) {
                            foreach ($company_data_objs as $company_data_obj) {
                                $to_emails[] = $company_data_obj->contactemail;
                            }
                        }
                    }
                    if (trim($recipient) == 'members') {
                        $company_mgn_obj = new companyModelItlocation();
                        $company_data_objs = $company_mgn_obj->get_info_by_status('members');

                        if (count($company_data_objs)) {
                            foreach ($company_data_objs as $company_data_obj) {
                                $to_emails[] = $company_data_obj->contactemail;
                            }
                        }
                    }
                    if (trim($recipient) == 'platinums') {
                        $company_mgn_obj = new companyModelItlocation();
                        $company_data_objs = $company_mgn_obj->get_info_by_status('platinums');

                        if (count($company_data_objs)) {
                            foreach ($company_data_objs as $company_data_obj) {
                                $to_emails[] = $company_data_obj->contactemail;
                            }
                        }
                    }
                }
            }
            return $to_emails;
        }

        function confirm_subscriber() {
            if ($_GET["subscriber"] != '') {
                $subscriber_obj = new subscribeMgnItlocation();
                $info = $subscriber_obj->update_key($_GET["subscriber"]);
                if (get_option('itlocation_generals_confirm_subscriber_page')) {
                    $pid = get_option('itlocation_generals_confirm_subscriber_page');
                    $tmp_url = get_permalink($pid);
                }
                $tmp_url = $this->parse_url($tmp_url . '&email=' . $info->email);
                ?>
                <script>
                    location.href = '<?php echo $tmp_url; ?>';    
                </script>
                <?php
                //wp_redirect($tmp_url);
                exit();
            }
        }

        function widgets() {
// Area 1, located at the top of the sidebar.
            register_sidebar(array(
                'name' => __('Primary Widget Area', 'twentyten'),
                'id' => 'primary-widget-area',
                'description' => __('The primary widget area', 'twentyten'),
                'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
                'after_widget' => '</li>',
                'before_title' => '<h5 class="widget-title"><span>',
                'after_title' => '</span></h5>',
            ));

            register_sidebar(array(
                'name' => __('The Lastest Page Widget Area', 'twentytwelve'),
                'id' => 'sidebar-the-lastest',
                'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve'),
                'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
                'after_widget' => '</li>',
                'before_title' => '<h5 class="widget-title"><span>',
                'after_title' => '</span></h5>',
            ));
            register_widget('commentsWidgetItlocation');
            register_widget('postsWidgetItlocation');			            register_widget('rightSideAdsWidgetItlocation');
        }

        function get_icon_member_role($level) {
            $icon = 'user_level0_icon';
            if ($level == '1')
                $icon = 'user_level1_icon';
            elseif ($level == '2')
                $icon = 'user_level2_icon';
            return $icon;
        }

        function get_member_role_label_by_role_num($num) {
            $tmp = get_option('ws_plugin__s2member_options');

            $label = $tmp['level0_label'];
            if ($num == '1')
                $label = $tmp['level1_label'];
            elseif ($num == '2')
                $label = $tmp['level2_label'];
            return $label;
        }

        function get_member_role_info_by_id($uid) {
            $user = new WP_User($uid);
            $icon = 'user_level0_icon';
            if ($user->roles[0] == 's2member_level1')
                $icon = 'user_level1_icon';
            elseif ($user->roles[0] == 's2member_level2')
                $icon = 'user_level2_icon';

            $tmp = get_option('ws_plugin__s2member_options');

            $label = $tmp['level0_label'];
            if ($user->roles[0] == 's2member_level1')
                $label = $tmp['level1_label'];
            elseif ($user->roles[0] == 's2member_level2')
                $label = $tmp['level2_label'];

            $ret_a = array();
            $ret_a['icon'] = $icon;
            $ret_a['label'] = $label;

            return $ret_a;
        }

        function get_geteway_name($id) {
            $txt = 'PayPal®';
            switch ($id) {
                case 'alipay':
                    $txt = 'AliPay®';
                    break;
                case 'authnet':
                    $txt = 'Authorize.Net®';
                    break;
                case 'ccbill':
                    $txt = 'ccBill®';
                    break;
                case 'clickbank':
                    $txt = 'ClickBank®';
                    break;
                case 'google':
                    $txt = 'Google®';
                    break;
            }
            return $txt;
        }

        function string_max_length($content, $length) {
            $txt = '';
            $content = strip_tags($content);
            if (strlen($content) > $length) {
                $subex = substr($content, 0, $length);
                $txt .= $subex;
                $txt .= '...';
            } else {
                $txt .= $content;
            }
            return $txt;
        }

        function get_client_ip() {
            $ipaddress = '';
            if ($_SERVER['HTTP_CLIENT_IP'])
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if ($_SERVER['HTTP_X_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if ($_SERVER['HTTP_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if ($_SERVER['HTTP_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if ($_SERVER['REMOTE_ADDR'])
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';

            return $ipaddress;
        }

        function parse_url($url) {
            $url = str_replace('&amp;', '&', $url);
            $url = str_replace('&#038;', '&', $url);

            $pos = stripos($url, '?');
            if ($pos === false) {
                $pos = stripos($url, '&');
                if ($pos !== false) {
                    $url = substr_replace($url, '?', $pos) . substr($url, $pos + 1);
                }
            }
            return $url;
        }

        function add_url_http($url) {
            $pos = stripos($url, 'http://');
            if ($pos === false) {
                $url = 'http://' . $url;
            }
            return $url;
        }

        function check_file_nm($dir, $file) {
            $path_info = pathinfo($dir . '/' . $file);
            $t = 1;
            $filetitle = preg_replace('/\.[^.]+$/', '', $file);
            $filename = $dir . '/' . $filetitle;
            do {
                $filename = $dir . '/' . $filetitle . '_' . $t . '.' . $path_info["extension"];
                ++$t;
            } while (file_exists($filename));

            $path_info = pathinfo($filename);

            return $path_info;
        }

        var $totalpages = 0;

        function advanced_search($mc_fg = '') {
            $all_cids = array();
            $services = array();
            $certifications = array();
            $partners = array();
            $all_cids_str = '';
            $json_a = array();

            $services = $this->advanced_search_query('services', 'se');
            $certifications = $this->advanced_search_query('certifications', 'ce');
            $all_cids = $this->intersect_array($services, $certifications);
            $partners = $this->advanced_search_query('partners', 'pa');
            $all_cids = $this->intersect_array($all_cids, $partners);
            $industries = $this->advanced_search_query('industries', 'in');
            $all_cids = $this->intersect_array($all_cids, $industries);

            $all_cids_str = implode(",", $all_cids);

            $fg = 1;
            if ($_REQUEST['se'])
                $fg = 0;
            if ($_REQUEST['ce'])
                $fg = 0;
            if ($_REQUEST['pa'])
                $fg = 0;
            if ($_REQUEST['in'])
                $fg = 0;

            if ($fg || $all_cids_str) {
                $tmp_a1 = explode(',', trim($_REQUEST['lo']));
                if (count($tmp_a1) > 1) {
                    $s .= "city = '" . trim($tmp_a1[0]) . "' AND (state = '" . trim($tmp_a1[1]) . "' OR address = '" . trim($tmp_a1[1]) . "')";
                } elseif (trim($_REQUEST['lo'])) {
                    $s .= "city = '" . trim($_REQUEST['lo']) . "' OR state = '" . trim($_REQUEST['lo']) . "' OR address = '" . trim($_REQUEST['lo']) . "'";
                }
                /*
                  $tmp_a2 = explode(' ', trim($_REQUEST['lo']));
                  $idx = 0;
                  foreach ($tmp_a2 as $tmp) {
                  if (($idx == 0 ) && (count($tmp_a1) > 0))
                  $s .= " OR ";
                  $s .= "city = '" . trim($tmp) . "' OR state = '" . trim($tmp) . "' OR address = '" . trim($tmp) . "' ";
                  if (count($tmp_a2) != ($idx + 1))
                  $s .= " OR ";
                  ++$idx;
                  }
                 * 
                 */
                if ($s)
                    $tmp = "SELECT * FROM `company` WHERE (" . $s . ") AND description LIKE '%" . $_REQUEST['ke'] . "%' ";
                else
                    $tmp = "SELECT * FROM `company` WHERE description LIKE '%" . $_REQUEST['ke'] . "%' ";
                if ($_REQUEST['co']) {
                    $where .= " AND country = '" . $_REQUEST['co'] . "'";
                }

                $tmp .= $where;
                global $wpdb;

                if ($mc_fg == 'map') {
                    $query = '(SELECT a1.id AS comp_id, a1.user_id AS user_id, a1.companyname AS name, a1.user_role AS user_role, a1.description AS description, a2.lat AS lat, a2.lng AS lng, a1.logo_file_nm AS logo_file_nm FROM (SELECT * FROM (' . $tmp . ') AS a ';
                    if ($all_cids_str)
                        $query .= 'WHERE a.id IN (' . $all_cids_str . ')';
                    $query .= ') AS a1 INNER JOIN company_address AS a2 ON a1.id = a2.comp_id)';
                    $query .= ' UNION ';
                    $query .= '(SELECT a.id AS comp_id, a.user_id AS user_id, a.companyname AS name, a.user_role AS user_role, a.description AS description, a.latitude AS lat, a.longitude AS lng, a.logo_file_nm AS logo_file_nm FROM (' . $tmp . ') AS a ';
                    if ($all_cids_str)
                        $query .= 'WHERE a.id IN (' . $all_cids_str . ')';
                    $query .= ')';
                } else {
                    $query = 'SELECT a.id AS comp_id, a.user_id AS user_id, a.companyname AS name, a.user_role AS user_role, a.description AS description, a.logo_file_nm AS logo_file_nm FROM (' . $tmp . ') AS a WHERE a.id ';
                    if ($all_cids_str)
                        $query .= ' IN (' . $all_cids_str . ') ';
                    $query .= ' ORDER BY a.user_role DESC';

                    $totalitems = $wpdb->query($query); //return the total number of affected rows
                    $perpage = get_option('posts_per_page');
                    $paged = get_query_var('paged');
                    if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
                        $paged = 1;
                    }

                    $this->totalpages = ceil($totalitems / $perpage);

                    if (!empty($paged) && !empty($perpage)) {
                        $offset = ($paged - 1) * $perpage;
                        $query .= ' LIMIT ' . (int) $offset . ',' . (int) $perpage;
                    }
                }
                $tmp_list_a = $wpdb->get_results($query);
            }
//echo $query;
            $result_a = array();
            $upload_dir = wp_upload_dir();
            $destination_url = $upload_dir["baseurl"] . "/comp_logo/";

            $rating_obj = new commentsMgnItlocation();
            $rating_a = $rating_obj->get_info();

            $idx = 0;
            if (count($tmp_list_a)) {
                foreach ($tmp_list_a as $val) {
                    $user = new WP_User($val->user_id);

                    if ($user->roles[0] != 'administrator') {
                        if ($mc_fg == 'map') {
                            $result_a[$idx]['id'] = $val->user_id;
                            $result_a[$idx]['user_role'] = $val->user_role;
                            $result_a[$idx]['name'] = $val->name;
                            if ($result_a[$idx]['user_role'] != '0')
                                $result_a[$idx]['description'] = stripslashes(substr($val->description, 0, 300));

                            $result_a[$idx]['address'] = $val->address;
                            $result_a[$idx]['x'] = $val->lat;
                            $result_a[$idx]['y'] = $val->lng;

                            if ($result_a[$idx]['user_role'] != '0') {
                                if ($val->logo_file_nm)
                                    $result_a[$idx]['logo_url'] = $destination_url . $val->logo_file_nm;
                                else
                                    $result_a[$idx]['logo_url'] = get_bloginfo('template_url')."/images/no-image.png";
                            }
                            $result_a[$idx]['permalink'] = get_author_posts_url($val->user_id);

                            $rating_fg = $this->get_default_member_limit('rating', $result_a[$idx]['user_role']);

                            if ($rating_fg)
                                $result_a[$idx]['rating'] = $rating_a[$val->comp_id];
                            else
                                $result_a[$idx]['rating'] = 'no';
                        } else {
                            $result_a[$idx]['user_id'] = $val->user_id;
                            $result_a[$idx]['comp_id'] = $val->comp_id;
                            $result_a[$idx]['user_role'] = $val->user_role;
                            $result_a[$idx]['name'] = $val->name;
                            $result_a[$idx]['permalink'] = get_author_posts_url($val->user_id);
//if ($result_a[$idx]['user_role'] != '0')
                            $result_a[$idx]['description'] = stripslashes(substr($val->description, 0, 240));
                            $result_a[$idx]['description'] .= ' ...';
                            $result_a[$idx]['logo_url'] = get_bloginfo('template_url')."/images/no-image.png";
							//'http://www.placehold.it/70x70/EFEFEF/AAAAAA&text=no+image';
                            if ($val->logo_file_nm)
                                $result_a[$idx]['logo_url'] = $destination_url . $val->logo_file_nm;
                        }
                        ++$idx;
                    }
                }
            }
            return $result_a;
        }

        function intersect_array($c1_a, $c2_a) {
            $ret_a = array();
            if (count($c1_a) && count($c2_a))
                $ret_a = array_intersect($c1_a, $c2_a);
            elseif (count($c1_a))
                $ret_a = $c1_a;
            elseif (count($c2_a))
                $ret_a = $c2_a;

            return $ret_a;
        }

        function advanced_search_query($tb_nm, $para) {
            $return_a = array();
            if (isset($_REQUEST[$para])) {
                $query = 'SELECT comp_id FROM ' . $tb_nm . '_company_relationships ';

                $query .= ' WHERE ';
                $idx = 0;
                //$val_a = explode(',', $_REQUEST[$para]);
                if (count($_REQUEST[$para])) {
                    foreach ($_REQUEST[$para] as $val) {
                        if ($idx != 0)
                            $query .= ' OR ';
                        $query .= " service_id='" . $val . "'";

                        ++$idx;
                    }
                }
                $query .= 'GROUP BY comp_id';
                global $wpdb;
                $obj_a = $wpdb->get_results($query);
                foreach ($obj_a as $obj) {
                    $return_a[$obj->comp_id] = $obj->comp_id;
                }
            }
            return $return_a;
        }

        function get_default_member_limit($nm, $role) { 
            $limit_array = array(
				'contribution' 		=> array( '0' => '0', '1' => '1', '2' => '1' ),
				'rating' 			=> array( '0' => '0', '1' => '0', '2' => '1' ),
				'locations' 		=> array( '0' => '0', '1' => '3', '2' => '-1' ),
				'collateral' 		=> array( '0' => '0', '1' => '3', '2' => '-1' ),
				'services' 			=> array( '0' => '3', '1' => '10', '2' => '-1' ),
				'certifications' 	=> array( '0' => '0', '1' => '3', '2' => '-1' ),
				'partners' 			=> array( '0' => '0', '1' => '5', '2' => '-1' ),
				'industries' 		=> array( '0' => '3', '1' => '5', '2' => '-1' ),
				'case_studies' 		=> array( '0' => '0', '1' => '1', '2' => '-1' ),
			);
			
			$edit_fg = $limit_array[ $nm ][$role]; 
			// if( get_option( 'itlocation_member_limit_' . $nm . '_' . $role ) != '' ){
				// $edit_fg = get_option( 'itlocation_member_limit_' . $nm . '_' . $role );
			// }
			
			return $edit_fg;
        }

        function get_subscribers_number($status = '') {
            global $wpdb;
            if (!$status) {
                $query = "SELECT * FROM ((SELECT id AS id, email AS email, date_registered AS date_registered FROM subscribers) UNION (SELECT id AS id, contactemail AS email, register_date AS date_registered FROM company)) AS a";
            } elseif ($status == 'public') {
                $query = "SELECT * FROM (SELECT id AS id, email AS email, date_registered AS date_registered FROM subscribers) AS a";
            } elseif ($status == 'registered') {
                $query = "SELECT * FROM (SELECT id AS id, contactemail AS email, register_date AS date_registered FROM company) AS a";
            }
            $totalitems = $wpdb->query($query); //return the total number of affected rows

            return $totalitems;
        }

        function get_current_url() {
            $url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

            return $url;
        }

    }

    global $functions_ph;

    $functions_ph = new functionsPH();
endif;
?>