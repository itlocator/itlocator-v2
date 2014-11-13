<?php
if (!class_exists("ajaxThemeItlocation")):

    class ajaxThemeItlocation {

        function __construct() {
            add_action('wp_enqueue_scripts', array(&$this, 'js_css'));
            add_action('wp_ajax_get-state-itlocation', array(&$this, 'get_state'));

            add_action('wp_ajax_check-email-itlocation', array(&$this, 'check_email'));
            add_action('wp_ajax_nopriv_check-email-itlocation', array(&$this, 'check_email'));

            add_action('wp_ajax_delete-contributions-itlocation', array(&$this, 'delete_contributions'));

            add_action('wp_ajax_subscriber-modal-itlocation', array(&$this, 'subscribe_modal'));
            add_action('wp_ajax_nopriv_subscriber-modal-itlocation', array(&$this, 'subscribe_modal'));
        }

        function js_css() {
            if (!is_admin()) {
                wp_localize_script('main-itlocation', 'admin_ajax', array(
                    'url' => admin_url('admin-ajax.php')
                ));
            }
        }

        function subscribe_modal() {
            check_ajax_referer('subscriber-modal-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $json_encode = array();
                $json_encode['time'] = time();
                if (email_exists($_REQUEST['subscriber_email'])) {
                    $json_encode['error'] = 'Your email already exist.';
                } else {
                    if ($_REQUEST['subscriber_email']) {
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
                            $functions_ph->send_mail($_REQUEST['subscriber_email'], '', $subject, $content);

                            setcookie("signup_subscriber_itlocation", time(), time() + 3600 * 24 * 30, "/");
                        }
                    } else {
                        setcookie("not_now_subscriber_itlocation", time(), time() + 3600 * 24 * 30, "/");
                    }
                }
                header("Content-Type: application/json");
                echo json_encode($json_encode);
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function check_email() {
            check_ajax_referer('check-email-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $error = '';
                if (is_user_logged_in()) {
                    global $current_user;
                    get_currentuserinfo();

                    if ($current_user->user_email != $_REQUEST['user_email']) {
                        if (email_exists($_REQUEST['user_email'])) {
                            $error = 'email exists';
                        }
                    }
                } else {
                    if (email_exists($_REQUEST['user_email'])) {
                        $error = 'email exists';
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

        function delete_contributions() {
            check_ajax_referer('delete-contributions-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if (get_option('itlocation_generals_contributions_list_page')) {
                    $pid = get_option('itlocation_generals_contributions_list_page');
                    $tmp_url = get_permalink($pid);
                }

                wp_delete_post(get_post_meta($_REQUEST['pid'], '_thumbnail_id', true));
                delete_post_meta($_GET['pid'], '_thumbnail_id');
                wp_delete_post($_REQUEST['pid']);

                header("Content-Type: application/json");
                echo json_encode(array(
                    'time' => time(),
                    'pid' => $_REQUEST['pid'],
                    'r_url' => $tmp_url
                ));
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

        function get_state() {
            check_ajax_referer('get-state-itlocation', 'security');
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                /* header("Content-Type: application/json");
                  echo json_encode(array(
                  'time' => time(),
                  'redirect' => $return_url
                  ));
                 * 
                 */

                global $states;
                if (count($states[$_REQUEST['country_id']])) {
                    $state = $states[$_REQUEST['country_id']];
                    ?>
                    <select name="comp_state" id="comp_state">
                        <option value=""><?php _e('Select A State', 'twentyten') ?></option>
                        <?php
                        foreach ($state as $key => $value) {
                            $tmp = '';
                            if ($_REQUEST['state_id'] == $key)
                                $tmp = 'selected';
                            ?>
                            <option value="<?php echo $key ?>" <?php echo $tmp ?>><?php _e($value); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <?php
                } else {
                    ?>
                    <input type="text" id="comp_state" name="comp_state" value="<?php echo $_REQUEST['state_id'] ?>">
                    <?php
                }
                exit;
            } else {
                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        }

    }

    new ajaxThemeItlocation();

endif;
?>
