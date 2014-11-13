<?php
if (!class_exists("shortcodesItlocation")):

    class shortcodesItlocation {

        function __construct() {
            add_action('wp_enqueue_scripts', array(&$this, 'js_css'));
            add_action('wp_head', array(&$this, 'header'));

            add_action('wp_ajax_delete-profile-comp-file-itlocation', array(&$this, 'delete_profile_comp_file'));
            add_action('wp_ajax_update-profile-comp-file-itlocation', array(&$this, 'update_profile_comp_file'));

            add_action('wp_ajax_login-itlocation', array(&$this, 'login'));
            add_action('wp_ajax_nopriv_login-itlocation', array(&$this, 'login'));

            add_action('wp_ajax_check-username-itlocation', array(&$this, 'check_username'));
            add_action('wp_ajax_nopriv_check-username-itlocation', array(&$this, 'check_username'));

            add_action('wp_ajax_signup-itlocation', array(&$this, 'signup'));
            add_action('wp_ajax_nopriv_signup-itlocation', array(&$this, 'signup'));

            add_action('wp_ajax_search-map-itlocation', array(&$this, 'search_map'));
            add_action('wp_ajax_nopriv_search-map-itlocation', array(&$this, 'search_map'));

            add_action('wp_ajax_send-email-itlocation', array(&$this, 'send_email'));					
			
			add_action('wp_ajax_send-manual-claim-verification-email-itlocation', array(&$this, 'manual_claim_verification_send_email'));			
			
			add_action('wp_ajax_send-support-email-itlocation', array(&$this, 'send_support_email'));
			
            add_action('wp_ajax_nopriv_send-email-itlocation', array(&$this, 'send_email'));

            add_action('wp_ajax_company-comments-itlocation', array(&$this, 'company_comments'));
            add_action('wp_ajax_nopriv_company-comments-itlocation', array(&$this, 'company_comments'));

            add_action('wp_ajax_forgot-password-itlocation', array(&$this, 'forgot_password'));
            add_action('wp_ajax_nopriv_forgot-password-itlocation', array(&$this, 'forgot_password'));

            add_action('wp_ajax_subscribe-itlocation', array(&$this, 'subscribe'));
            add_action('wp_ajax_nopriv_subscribe-itlocation', array(&$this, 'subscribe'));

            add_action('wp_ajax_company-comments-list-itlocation', array(&$this, 'company_comments_list'));
            add_action('wp_ajax_nopriv_company-comments-list-itlocation', array(&$this, 'company_comments_list'));

            add_shortcode('login-itlocation', array(&$this, 'inter_login_form'));
            add_shortcode('signup-itlocation', array(&$this, 'inter_signup_form'));
            add_shortcode('search-map-itlocation', array(&$this, 'inter_search_map'));

            add_shortcode('company-map-itlocation', array(&$this, 'inter_company_map'));

            add_shortcode('services-ctrl-itlocation', array(&$this, 'inter_services_ctrl'));
            add_shortcode('services-data-itlocation', array(&$this, 'inter_services_datas'));

            add_shortcode('countries-ctrl-itlocation', array(&$this, 'inter_countries_ctrl'));
			add_shortcode('countries-my-location-ctrl-itlocation', array(&$this, 'inter_countries_my_location_ctrl'));
			 
            add_shortcode('payment-form-itlocation', array(&$this, 'inter_payment_form'));

            add_shortcode('file-mgn-itlocation', array(&$this, 'inter_file_mgn'));
            add_shortcode('send-email-itlocation', array(&$this, 'inter_send_email'));
            add_shortcode('comments-itlocation', array(&$this, 'inter_company_comments'));
            add_shortcode('subscriber-itlocation', array(&$this, 'inter_subscriber'));
            add_shortcode('display-get-data-itlocation', array(&$this, 'inter_display_get_data'));
        }

        function header() {
            ?>
            <script>
                GS_googleAddAdSenseService("ca-pub-9500073444472668");
                GS_googleEnableAllServices();            
            </script>
            <?php
        }

        function js_css() {
            if (!is_admin()) {
                wp_enqueue_script('shortcodes-itlocatio', get_template_directory_uri() . '/js/shortcodes.js');
                
				// wp_enqueue_script('google-maps-api', 'http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true');
				wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places');
				
                wp_enqueue_script('google-maps-infobubble', 'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble.js');
                wp_enqueue_script('google_service', 'http://partner.googleadservices.com/gampad/google_service.js');
                wp_enqueue_script('google-map', get_template_directory_uri() . '/js/google-map.js');

                wp_localize_script('jquery', 'images', array(
                    'url' => get_bloginfo('template_url') . '/images/'
                ));
            }
        }

        function company_comments_list() {
            check_ajax_referer('company-comments-list-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $json_encode = array();
                $json_encode['time'] = time();
                $json_encode['cid'] = $_POST['cid'];
                $json_encode['pid'] = $_POST['pid'];

                $rating_obj = new commentsMgnItlocation();
                ?>
                <div id="list_company_comments">
                    <?php
                    $paged = $_POST['pid'];
                    $perpage = 5;
                    $cid = $_POST['cid'];
                    $comments_obj = $rating_obj->get_info_by_cid($cid);
                    $totalRecords = count($comments_obj);

                    $comments_obj = $rating_obj->get_info_by_cid($cid, $paged, $perpage);
                    foreach ($comments_obj as $comments) {
                        ?>
                        <div class="star-rating pull-left" title="Rated <?php echo $comments->rating; ?> out of 5"><span style="width:<?php echo ($comments->rating / 5) * 100; ?>%"></span></div><div class="pull-right"><?php echo $comments->name; ?></div><div class="clearfix"></div>
                        <p><?php _e($comments->comment); ?></p>
                        <hr class="margin-all-0 margin-top-bottom-10" />
                        <?php
                    }
                    ?>
                </div>
                <div id="company-comments-list-nav" class="pagination pagination-mini">
                    <?php
                    $page_nav = new tc_pageNav($totalRecords, $paged);
                    $page_nav->setPerPage($perpage);
                    $page_nav->calculate();

                    echo($page_nav->printNavBarPortion());
                    ?>
                </div>
                <?php
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function subscribe() {
            check_ajax_referer('subscribe-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $json_encode = array();
                $json_encode['time'] = time();
                $json_encode['email'] = $_REQUEST['subscriber_email'];

                if (email_exists($_REQUEST['subscriber_email'])) {
                    $json_encode['error'] = 'Your email already exist.';
                } else {
                    $subscribe = new subscribeMgnItlocation();
                    if ($subscribe->email_exists($_REQUEST['subscriber_email'])) {
                        $json_encode['error'] = 'Your email already exist.';
                    } else {
                        $info['email'] = $_REQUEST['subscriber_email'];
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

                        $json_encode['confirm_url'] = $confirm_url;
                        $json_encode['subject'] = $subject;
                        $json_encode['content'] = $content;

                        global $functions_ph;
                        $json_encode['error'] = $functions_ph->send_mail($_REQUEST['subscriber_email'], '', $subject, $content);
                    }
                }

                header("Content-Type: application/json");
                echo json_encode($json_encode);
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function forgot_password() {
            check_ajax_referer('forgot-password-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $json_a = array();
                $json_a['time'] = time();
                $json_a['username_email'] = $_REQUEST['username_email'];

                if (strpos($_POST['username_email'], '@')) {
                    $user_data = get_user_by_email(trim($_POST['username_email']));
                    if (empty($user_data))
                        $json_a['error'] = 'There is no user registered with that email address.';
                } else {
                    $user_data = get_userdatabylogin(trim($_POST['username_email']));
                    if (empty($user_data))
                        $json_a['error'] = 'There is no user registered with that username.';
                }

                if (empty($user_data))
                    $json_a['error'] = 'Invalid username or e-mail.';

                $user_login = $user_data->user_login;
                $user_email = $user_data->user_email;

                do_action('retreive_password', $user_login);  // Misspelled and deprecated
                $new_pass = wp_generate_password(12, false);

                do_action('password_reset', $user_data, $new_pass);
                wp_set_password($new_pass, $user_data->ID);


                global $functions_ph;

                $title = 'Your password';
                if (get_option('itlocation_email_settings_forgot_pw_email_t_for_user'))
                    $title = stripslashes(get_option('itlocation_email_settings_forgot_pw_email_t_for_user'));

                $content = 'Your Password: [#password#]';
                if (get_option('itlocation_email_settings_forgot_pw_email_c_for_user'))
                    $content = stripslashes(get_option('itlocation_email_settings_forgot_pw_email_c_for_user'));

                $search_array = array('[#password#]');
                $replace_array = array($new_pass);

                $content = str_replace($search_array, $replace_array, $content);

                $functions_ph->send_mail($user_email, '', $title, $content);

                $json_a['user_data'] = $user_data;
                header("Content-Type: application/json");
                echo json_encode($json_a);
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function company_comments() {
            check_ajax_referer('company-comments-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $rating_obj = new commentsMgnItlocation();
                $json_a = array();
                $to_email = $_REQUEST['to_email'];
                $from_email = $_REQUEST['from_email'];
                if ($to_email != $from_email) {
                    if ($rating_obj->check_email($_REQUEST['cid'], $from_email)) {
                        $json_a['error'] = 'You already register rating.';
                    } else {
                        $json_a['cid'] = $_REQUEST['cid'];
                        $json_a['rating'] = $_REQUEST['rating'];
                        $json_a['name'] = $_REQUEST['full_name'];
                        $json_a['email'] = $from_email;
                        $json_a['uid'] = $_REQUEST['reg_uid'];
                        $json_a['comment'] = $_REQUEST['comment'];
                        $last_id = $rating_obj->insert($json_a);
                        $json_a['last_id'] = $last_id;
                        $json_a['error'] = '';
                    }
                } else {
                    $json_a['error'] = 'You are going to register rating for your company';
                }
                $json_a['time'] = time();

                header("Content-Type: application/json");
                echo json_encode($json_a);
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }
		function manual_claim_verification_send_email() {	
			check_ajax_referer('send-email-itlocation', 'security');						
			if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$company_user_data = get_userdata( $_REQUEST['user_id'] );
				$user_email = $company_user_data->user_email;
				
				global $functions_ph;				
				
				$email_title = 'Manual Claim Listing Request';
				$email_content = '<p>The following person was not automatically verified to claim the listing for ' . $_REQUEST['claim_company'] . '</p></br/>';
				
				$approveclaim_url = '';
				if (get_option('itlocation_generals_approve_claim')) {
					$pid = get_option('itlocation_generals_approve_claim');
					$approveclaim_url = get_permalink($pid);
				}
				
				$denyclaim_url = '';
				if (get_option('itlocation_generals_deny_page')) {
					$pid = get_option('itlocation_generals_deny_page');
					$denyclaim_url = get_permalink($pid);
				}
				
				$email_content .= '<p>Name : ' . $_REQUEST['claim_name'] . '</p>';
				$email_content .= '<p>Title : ' . $_REQUEST['claim_title'] . '</p>';
				$email_content .= '<p>Email : ' . $user_email . '</p>';
				$email_content .= '<p>Phone : ' . $_REQUEST['claim_phone'] . '</p>';
				$email_content .= '<p>Comments : ' . $_REQUEST['claim_comments'] . '</p>';
				$email_content .= '<p><a href="' . $approveclaim_url . '?userid=' . $_REQUEST['user_id'] . '&company=' . $_REQUEST['claim_company'] . '">Approve Claim</a> | <a href="' . $denyclaim_url . '?userid=' . $_REQUEST['user_id'] . '&company=' . $_REQUEST['claim_company'] . '">Deny Claim</a></p>';
				
				echo $functions_ph->send_mail($_REQUEST['claim_to_email'], $user_email, $email_title, $email_content);
				exit;
			}        
		}
		
        function send_email() {
            check_ajax_referer('send-email-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                global $functions_ph;
                $functions_ph->send_mail($_REQUEST['to_email'], $_REQUEST['from_email'], $_REQUEST['title'], $_REQUEST['content']);

                $json_a = array();
                /*
                  $json_a['headers'] = $headers;
                  $json_a['to_email'] = $to_email;
                  $json_a['from_email'] = $_REQUEST['from_email'];
                  $json_a['title'] = $_REQUEST['title'];
                  $json_a['content'] = $_REQUEST['content'];
                 * 
                 */
                $json_a['time'] = time();

                header("Content-Type: application/json");
                echo json_encode($json_a);
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }
		
		function send_support_email() {
            check_ajax_referer('send-email-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                global $functions_ph;
                $functions_ph->send_mail($_REQUEST['to_email'], 'support@itlocator.com', $_REQUEST['title'], $_REQUEST['content']);

                $json_a = array();
                /*
                  $json_a['headers'] = $headers;
                  $json_a['to_email'] = $to_email;
                  $json_a['from_email'] = $_REQUEST['from_email'];
                  $json_a['title'] = $_REQUEST['title'];
                  $json_a['content'] = $_REQUEST['content'];
                 * 
                 */
                $json_a['time'] = time();

                header("Content-Type: application/json");
                echo json_encode($json_a);
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }
		
        function search_map() {
            check_ajax_referer('search-map-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                global $functions_ph;

                $all_cids_str = '';
                if ($_REQUEST['services'])
                    $all_cids_str = implode(",", $this->search_query('services'));

                $fg = 1;
                if ($_REQUEST['services'])
                    $fg = 0;
                if ($_REQUEST['certifications'])
                    $fg = 0;
                if ($_REQUEST['partners'])
                    $fg = 0;

                if ($fg || $all_cids_str) {
                    $tmp_a = explode(',', $_REQUEST['keywords']);
                    foreach ($tmp_a as $tmp) {
                        $s .= "state LIKE '%" . trim($tmp) . "%' OR ";
                    }
                    $tmp_a = explode(',', $_REQUEST['keywords']);
                    foreach ($tmp_a as $tmp) {
                        $s .= "address1 LIKE '%" . trim($tmp) . "%' OR address2 LIKE '%" . trim($tmp) . "%' OR ";
                    }
                    /*
                      $tmp_a = explode(' ', $_REQUEST['keywords']);
                      foreach ($tmp_a as $tmp) {
                      $s .= "state LIKE '%" . trim($tmp) . "%' OR ";
                      }
                      $tmp_a = explode(' ', $_REQUEST['keywords']);
                      foreach ($tmp_a as $tmp) {
                      $s .= "address LIKE '%" . trim($tmp) . "%' OR ";
                      }
                     * 
                     */

                    $tmp = "SELECT * FROM `company` WHERE (" . $s . " description LIKE '%" . $_REQUEST['keywords'] . "%') ";
                    if ($_REQUEST['countries']) {
                        $where .= " AND country = '" . $_REQUEST['countries'] . "'";
                    }
                    /*
                      if ($_REQUEST['countries'] || $_REQUEST['keywords']) {
                      $where = ' WHERE ';

                      if ($_REQUEST['countries']) {
                      $where .= " country = '" . $_REQUEST['countries'] . "'";
                      }
                      if ($_REQUEST['keywords']) {
                      if ($_REQUEST['countries']) {
                      $where .= " AND ";
                      }
                      $where .= " description LIKE '%" . $_REQUEST['keywords'] . "%'";
                      }
                      }
                     * 
                     */

                    $tmp .= $where;

                    $query = '(SELECT a1.id AS comp_id, a1.user_id AS user_id, a1.companyname AS name, a1.user_role AS user_role, a1.description AS description, a2.lat AS lat, a2.lng AS lng, a1.logo_file_nm AS logo_file_nm FROM (SELECT * FROM (' . $tmp . ') AS a ';
                    if ($all_cids_str)
                        $query .= 'WHERE a.id IN (' . $all_cids_str . ')';
                    $query .= ') AS a1 INNER JOIN company_address AS a2 ON a1.id = a2.comp_id)';
                    $query .= ' UNION ';
                    $query .= '(SELECT a.id AS comp_id, a.user_id AS user_id, a.companyname AS name, a.user_role AS user_role, a.description AS description, a.latitude AS lat, a.longitude AS lng, a.logo_file_nm AS logo_file_nm FROM (' . $tmp . ') AS a ';
                    if ($all_cids_str)
                        $query .= 'WHERE a.id IN (' . $all_cids_str . ')';
                    $query .= ')';
                }
                global $wpdb;
                $tmp_a = $wpdb->get_results($query);

                $result_a = array();
                $idx = 0;
                $upload_dir = wp_upload_dir();
                $destination_url = $upload_dir["baseurl"] . "/comp_logo/";

                $rating_obj = new commentsMgnItlocation();
                $rating_a = $rating_obj->get_info();

                if (count($tmp_a)) {
                    foreach ($tmp_a as $val) {
                        $user = new WP_User($val->user_id);

                        if ($user->roles[0] != 'administrator') {
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
                                    $result_a[$idx]['logo_url'] = 'http://www.placehold.it/70x70/EFEFEF/AAAAAA&text=no+image';
                            }
                            $result_a[$idx]['permalink'] = get_author_posts_url($val->user_id);

                            $edit_fg = $functions_ph->get_default_member_limit('rating', $result_a[$idx]['user_role']);

                            if ($edit_fg)
                                $result_a[$idx]['rating'] = $rating_a[$val->comp_id];
                            else
                                $result_a[$idx]['rating'] = 'no';
                            ++$idx;
                        }
                    }
                }

                header("Content-Type: application/json");
                echo json_encode(array(
                    'time' => time(),
                    'error' => '',
                    'locations' => $result_a,
                    'query' => $query
                ));
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function search_query($tb_nm) {
            $return_a = array();
            $query = 'SELECT comp_id FROM ' . $tb_nm . '_company_relationships ';

            if (isset($_REQUEST[$tb_nm])) {
                if ($_REQUEST[$tb_nm] != '') {
                    $query .= ' WHERE ';
                    $idx = 0;
                    foreach ($_REQUEST[$tb_nm] as $name) {
                        if ($idx != 0)
                            $query .= ' OR ';
                        $query .= " service_id='" . $name . "'";

                        ++$idx;
                    }
                }
            }
            $query .= ' GROUP BY comp_id';
            global $wpdb;
            $obj_a = $wpdb->get_results($query);
            foreach ($obj_a as $obj) {
                $return_a[] = $obj->comp_id;
            }
            return $return_a;
        }

        function check_username() {
            check_ajax_referer('check-username-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $error = '';
                if (is_user_logged_in()) {
                    $error = 'please log out';
                } else {
                    $username = $_REQUEST['username'];
                    if (!validate_username($username)) {
                        $error = 'validate_username';
                    } elseif (username_exists($username)) {
                        $error = 'username_exists';
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

        function login() {
            check_ajax_referer('login-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $error = '';
                $ret_url = site_url();
                if( get_option( 'itlocation_generals_return_url_after_login' ) ){
                    $pid = get_option('itlocation_generals_return_url_after_login');
                    $ret_url = get_permalink($pid);
                }

                $creds = array();
                $creds['user_login'] = $_REQUEST['user_login'];
                $creds['user_password'] = $_REQUEST['user_password'];
                $creds['remember'] = true;
                $user = wp_signon($creds, false);
                
				if( is_wp_error( $user ) ){
                    $error = $user->get_error_message();
				}
				
                header("Content-Type: application/json");
                echo json_encode(array(
                    'time' => time(),
                    'error' => $error,
                    'ret_url' => $ret_url
                ));
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function signup() {
            check_ajax_referer('signup-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$company_name = $_POST['company_name'];
				$user_email = $_POST['user_email'];
				$username = $_POST['username'];
				$user_password = $_POST['user_password'];
				$member_level = $_POST['member_level'];
				$coupon_code = $_POST["coupon_code"];
				
				$resp = recaptcha_check_answer(
					get_option( 'itlocation_generals_recaptcha_private_key' ), 
					getenv( "REMOTE_ADDR" ), 
					$_REQUEST["recaptcha_challenge_field"], 
					$_REQUEST["recaptcha_response_field"]
				);
				
                if( $resp->error ){
                    header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => 'Retype the security text.'
					));
					exit;
                }
				
				if( is_user_logged_in() ){
					header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => 'You have already account.'
					));
					exit;
				}
				
				if( $username == '' || !validate_username( $username ) ){
					header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => 'User Name has wrong.'
					));
					exit;
				}
				
				if( username_exists( $username ) ){
					header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => 'User Name already exists.'
					));
					exit;
				}
				
				if( !is_email( $user_email ) ){
					header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => 'User Email has wrong.'
					));
					exit;
				}
				
				if( email_exists( $user_email ) ){
					header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => 'User Email is already exists.'
					));
					exit;
				}
					
				if( $user_password == '' ){
					header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => 'Password is empty.'
					));
					exit;
				}
				
				if( strlen( $user_password ) < 6 ){
					header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => 'Password should have at least 6 letters.'
					));
					exit;
				}
				
				if( $company_name == '' ){
					header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => 'Company Name is empty.'
					));
					exit;
				}
				
				$user_id = wp_create_user( $username, $user_password, $user_email );
				
				if( gettype( $user_id ) != 'integer' ){
					header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => 'Please check your user info.'
					));
					exit;
				}
				
				global $functions_ph;
				$company_model = new companyModelItlocation();
				$company_model->insert( $user_id, $company_name, $user_email );
				$company_id = $company_model->getMaxId();
				
				$author_meta = get_userdata( $user_id );
				update_user_meta( $user_id, 'tmp_s2member_level', $member_level );
				
				$member_level_str = '';
				if( $member_level == 0 ){
					$member_level_str = 'Free Listing';
				} else if( $member_level == 1 ){
					$member_level_str = 'Member';
				} else if( $member_level == 2 ){
					$member_level_str = 'Premium';
				}
				
				$search_array = array( '[#name#]', '[#username#]', '[#email#]', '[#password#]', '[#member-level#]', '[#enter-date#]', '[#company-name#]', '[#company-link]' );
				
				$replace_array = array( $_REQUEST['full_name'], $_REQUEST['username'], $_REQUEST['user_email'], $_REQUEST['user_password'], $member_level_str, $author_meta->user_registered, $_REQUEST['company_name'], get_author_posts_url($user_id) );
				
				$title = 'Thanks';
				if( get_option( 'itlocation_email_settings_register_user_email_title_for_user' ) ){
					$title = stripslashes( get_option('itlocation_email_settings_register_user_email_title_for_user') );
				}
				
				$content = 'Congratulations';
				if( get_option( 'itlocation_email_settings_register_user_email_content_for_user' ) ){
					$content = stripslashes( get_option('itlocation_email_settings_register_user_email_content_for_user') );
				}
				
				$content = str_replace( $search_array, $replace_array, $content );
				
				// $functions_ph->send_mail($_REQUEST['user_email'], '', $title, $content);
				
				$title = 'Register New User';
				if( get_option( 'itlocation_email_settings_register_user_email_title_for_admin' ) ){
					$title = stripslashes(get_option('itlocation_email_settings_register_user_email_title_for_admin'));
				}

				$content = 'Register New User';
				if( get_option( 'itlocation_email_settings_register_user_email_content_for_admin' ) ){
					$content = stripslashes(get_option('itlocation_email_settings_register_user_email_content_for_admin'));
				}

				$content = str_replace( $search_array, $replace_array, $content );
				// $functions_ph->send_mail( '', $_REQUEST['user_email'], $title, $content );
				
				$creds = array(
					'user_login' => $username,
					'user_password' => $_REQUEST['user_password'],
					'remember' => true
				);
				
				$user = wp_signon( $creds, false );
				if( is_wp_error( $user ) ){
					header("Content-Type: application/json");
					echo json_encode(array(
						'error' => true,
						'error_message' => $user->get_error_message()
					));
					exit;
				}
				
				$coupon_o_val = '';
                $original_val = '';
                if( trim( $coupon_code ) && ( $member_level == 1 || $member_level == 2 ) ){
                    $tmp = get_option('ws_plugin__s2member_options');
                    $tmp_a = preg_split("/\\r\\n|\\r|\\n/", $tmp['pro_coupon_codes']);

                    foreach( $tmp_a as $value ){
                        $ary = explode( '|', $value );
                        $coupon_codes_txt_a[] = $ary[0];
                        $coupon_codes_val_a[] = $ary[1];
                        if( isset( $ary[2] ) ){
                            $coupon_codes_date_a[] = strtotime($ary[2]);
                        } else {
                            $coupon_codes_date_a[] = '';
						}
                    }

                    if( $member_level == 1 ){
                        $sc = stripslashes( get_option('itlocation_payment_shortcode_paypal_level_1') );
                    } else if( $member_level == 2 ){
                        $sc = stripslashes( get_option('itlocation_payment_shortcode_paypal_level_2') );
                    }

                    preg_match_all("/\w+=\"[^\"]*\"/", $sc, $matches);
                    foreach( $matches[0] as $value ){
                        $tmp_a = explode('=', $value);
                        $tmp_v = str_replace('"', '', $tmp_a[1]);
                        if ($tmp_a[0] == 'ra')
                            $ra = intval($tmp_v);
                    }
					
                    $i = 0;
                    $coupon_kind = '';
                    $coupon_val = '';
                    $original_val = '$' . $ra;

                    foreach( $coupon_codes_txt_a as $value ){
                        if( $value == trim( $coupon_code ) ){
                            if( $coupon_codes_date_a[$i] ){
                                if( time() < $coupon_codes_date_a[$i] ){
                                    $pos = stripos( $coupon_codes_val_a[$i], '%' );
                                    if( $pos === false ){
                                        $coupon_o_val = '$' . $coupon_codes_val_a[$i];
                                        $ra = $ra - $coupon_codes_val_a[$i];
                                    } else {
                                        $coupon_o_val = $coupon_codes_val_a[$i];
                                        $coupon_kind = '%';
                                        $coupon_val = str_replace("%", "", $coupon_codes_val_a[$i]);
                                        $ra = $ra - $ra * $coupon_val / 100;
                                    }
                                }
                            } else {
                                $pos = stripos($coupon_codes_val_a[$i], '%');
                                if ($pos === false) {
                                    $coupon_o_val = '$' . $coupon_codes_val_a[$i];
                                    $ra = $ra - $coupon_codes_val_a[$i];
                                } else {
                                    $coupon_o_val = $coupon_codes_val_a[$i];
                                    $coupon_kind = '%';
                                    $coupon_val = str_replace("%", "", $coupon_codes_val_a[$i]);
                                    $ra = $ra - $ra * $coupon_val / 100;
                                }
                            }
                        }
                        ++$i;
                    }
                }
					
				$ret_url = site_url();
				if( get_option( 'itlocation_generals_return_url_after_login' ) ){
					$pid = get_option('itlocation_generals_return_url_after_login');
					$ret_url = get_permalink( $pid );
				}

                header("Content-Type: application/json");
                echo json_encode(array(
                    'error' => false,
                    'user_id' => $user_id,
                    'ret_url' => $ret_url,
                    'coupon_o_val' => $coupon_o_val,
                    'orignal_val' => $original_val,
                    'ra' => $ra
                ));
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function update_profile_comp_file() {
            check_ajax_referer('update-profile-comp-file-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                global $comp_file_mgn_model_itlocation;
                $info['title'] = $_REQUEST['title'];
                $info['description'] = substr($_REQUEST['desc'], 0, 300);
                $info['filetype'] = $_REQUEST['filetype'];
                $comp_file_mgn_model_itlocation->update($_REQUEST['file_id'], $info);

                $info = $comp_file_mgn_model_itlocation->get_info_by_id($_REQUEST['file_id']);

                $extension = 'default';
                if ($info->extension)
                    $extension = $info->extension;

                header("Content-Type: application/json");
                echo json_encode(array(
                    'time' => time(),
                    'id' => $info->id,
                    'filename' => $info->filename,
                    'title' => $info->title,
                    'description' => $info->description,
                    'extension' => strtoupper($info->extension),
                    'icon_url' => get_bloginfo('template_url') . "/images/" . strtolower($extension) . "-icon.png",
                    'filesize' => $info->filesize
                ));
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function delete_profile_comp_file() {
            check_ajax_referer('delete-profile-comp-file-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                global $comp_file_mgn_model_itlocation;
                $info = $comp_file_mgn_model_itlocation->get_info_by_id($_REQUEST['file_id']);

                $company_file_model = new compFileMgnModelItlocation();
                $company_file = $company_file_model->get_info_by_id($_REQUEST['file_id']);

                $upload_dir = wp_upload_dir();
                $del_file = $upload_dir["basedir"] . "/comp_files/" . $company_file->comp_id . '/' . $info->real_filename;
                unlink($del_file);

                $comp_file_mgn_model_itlocation->del($_REQUEST['file_id']);
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function inter_services_ctrl($atts, $content) {
            ob_start();
			
            $kind = trim($atts['kind']);
            $comp_services_rel_model = new generaldataCompanyRelationshipsModelItlocation($kind);

            global $id, $class, $style, $cutomize_js_fg, $placeholder, $tags, $default_val, $limit;

            $id = trim($atts['id']);
            $class = trim($atts['class']);
            $style = trim($atts['style']);
            $cutomize_js_fg = trim($atts['cutomize_js_fg']);
            $placeholder = trim($atts['placeholder']);
            $default_val = trim($atts['default_val']);
            $limit = trim($atts['limit']);
			
            $comp_id = trim($atts['comp_id']);
            if ($comp_id) {
                $default_val = '';
                $services_model = new generaldataCompanyRelationshipsModelItlocation($kind);
                $tmp_obj = $services_model->get_all_service_nm_by_compid($comp_id);
                $idx = 0;
                if (count($tmp_obj)) {
                    foreach ($tmp_obj as $obj) {
                        $default_val .= $obj->id;
                        if ($idx != (count($tmp_obj) - 1))
                            $default_val .= ',';
                        ++$idx;
                    }
                }
            }

            $tmp_obj = $comp_services_rel_model->get_all_list();

            $tags = array();
            if (count($tmp_obj)) {
                foreach ($tmp_obj as $obj) {
                    $tags[$obj->id] = $obj->name;
                }
            }

            include( get_template_directory() . '/inc/shortcodes/views/services-ctrl.php' );

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_login_form($atts, $content) {
            ob_start();

            include( get_template_directory() . '/inc/shortcodes/views/login-form.php' );

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_search_map($atts, $content) {
            ob_start();

            include( get_template_directory() . '/inc/shortcodes/views/search-map.php' );

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_company_map($atts, $content) {
            global $company_id, $address_ctrl_fg;
            ob_start();

            $address_ctrl_fg = $atts['address_ctrl_fg'];
            $company_id = $atts['company_id'];
            if ($company_id)
                include( get_template_directory() . '/inc/shortcodes/views/company-map.php' );

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_countries_ctrl($atts, $content) {
            ob_start();
            $atts = shortcode_atts(array(
                'id' => 'countries',
                'add_class' => '',
                'style' => '',
                'ctrl_attr' => '',
                'selected_option' => '',
                'cutomize_js_fg' => 'no',
                'disabled' => ''
                    ), $atts);
            global $id, $add_class, $style, $ctrl_attr, $selected_option, $cutomize_js_fg, $disabled_combo;

            $id = trim($atts['id']);
            $add_class = trim($atts['add_class']);
            $style = trim($atts['style']);
            $ctrl_attr = trim($atts['ctrl_attr']);
            $selected_option = trim($atts['selected_option']);
            $cutomize_js_fg = trim($atts['cutomize_js_fg']);
			$disabled_combo = trim($atts['disabled']);

            include( get_template_directory() . '/inc/shortcodes/views/countries-ctrl.php' );

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }
		
		function inter_countries_my_location_ctrl($atts, $content) {
            ob_start();
            $atts = shortcode_atts(array(
                'id' => 'countries',
                'add_class' => '',
                'style' => '',
                'ctrl_attr' => '',
                'selected_option' => '',
                'cutomize_js_fg' => 'no',
                'disabled' => ''
                    ), $atts);
            global $id, $add_class, $style, $ctrl_attr, $selected_option, $cutomize_js_fg, $disabled_combo;

            $id = trim($atts['id']);
            $add_class = trim($atts['add_class']);
            $style = trim($atts['style']);
            $ctrl_attr = trim($atts['ctrl_attr']);
            $selected_option = trim($atts['selected_option']);
            $cutomize_js_fg = trim($atts['cutomize_js_fg']);
			$disabled_combo = trim($atts['disabled']);

            include( get_template_directory() . '/inc/shortcodes/views/countries-ctrl-mylocation.php' );

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }
		
        function inter_signup_form($atts, $content) {
            ob_start();

            include( get_template_directory() . '/inc/shortcodes/views/signup-form.php' );

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_payment_form($atts, $content) {
            ob_start();

            global $id, $level;
            $id = trim($atts['id']);
            $level = trim($atts['level']);

            if ($id)
                include( get_template_directory() . '/inc/shortcodes/views/payment-form.php' );

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_file_mgn($atts, $content) {
            ob_start();

            global $comp_id, $modal_id, $file_type, $file_info_list, $default;

            $modal_id = trim($atts['modal_id']);
            $file_type = trim($atts['file_type']);
            $comp_id = trim($atts['comp_id']);
            $default = trim($atts['default']);
            $comp_file_mgn_model_itlocation = new compFileMgnModelItlocation();
            $file_info_list = $comp_file_mgn_model_itlocation->get_list_by_comp_id($comp_id, $file_type, 'true');

            include( get_template_directory() . '/inc/shortcodes/views/file-mgn.php' );

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_send_email($atts, $content) {
            ob_start();

            global $id, $to_email;
            $id = $atts['id'];
            $to_email = $atts['to_email'];
            if ($to_email)
                include( get_template_directory() . '/inc/shortcodes/views/send_email.php' );
            $toemail = '';
            $id = '';
            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_company_comments($atts, $content) {
            ob_start();
            global $cid, $uid, $left_cmt_fg, $view_cmt_fg, $grp_class, $star_class, $title;
            $cid = $atts['cid'];
            $uid = $atts['uid'];
            $left_cmt_fg = $atts['left_cmt_fg'];
            $view_cmt_fg = $atts['view_cmt_fg'];
            $grp_class = $atts['grp_class'];
            $star_class = $atts['star_class'];
            $title = $atts['title'];
            include( get_template_directory() . '/inc/shortcodes/views/company-comments.php' );

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_services_datas($atts, $content) {
            ob_start();
            global $kind, $company_id, $tag, $class;
            $kind = $atts['kind'];
            $company_id = $atts['company_id'];
            $tag = $atts['tag'];
            $class = $atts['class'];
            include( get_template_directory() . '/inc/shortcodes/views/services_datas.php' );
            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_subscriber($atts, $content) {
            ob_start();
            global $horizontal, $text_class;
            $horizontal = $atts['horizontal'];
            $text_class = $atts['text_class'];
            include( get_template_directory() . '/inc/shortcodes/views/subscriber.php' );
            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

        function inter_display_get_data($atts, $content) {
            ob_start();

            _e($_GET[$atts['get_arg']]);

            $output_string = ob_get_contents();
            ob_end_clean();
            return $output_string;
        }

    }

    new shortcodesItlocation();

endif;
?>
