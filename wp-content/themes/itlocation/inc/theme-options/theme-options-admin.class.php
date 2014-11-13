<?php
if (!class_exists("Theme_Options_PH")):

    class Theme_Options_PH extends Analysis_Xml_PH {

        var $wp_version = 0;
        var $url = '';
        var $id = '';

        function __construct() {
            $xmlfilename = apply_filters('templ_theme_options_xmlpath_filter', dirname(__FILE__) . '/xml/custom-options.xml');
            parent::__construct($xmlfilename);

            $this->url = get_bloginfo('template_url') . '/inc/theme-options/';
            if (is_admin()) {
                add_action('admin_init', array(&$this, 'admin_init'));
                add_action('admin_menu', array(&$this, 'register_menu_page'));
            }
            add_action('admin_head', array(&$this, 'admin_header'));

            add_action('wp_ajax_edit-genaral-data-ph-custom', array(&$this, 'edit_ajax'));
            add_action('wp_ajax_nopriv_edit-genaral-data-ph-custom', array(&$this, 'edit_ajax'));
        }

        function admin_init() {
            $this->wp_version = get_bloginfo('version');
            $pos = strpos($_REQUEST['page'], 'custom-options-ph');
            if ($pos !== false) {
                wp_enqueue_script('custom-options-admin-ph', $this->url . 'js/general.js', array('jquery', 'json2'));
                wp_enqueue_style('custom-options-admin-ph', $this->url . 'css/style.css');

                wp_localize_script('custom-options-admin-ph', 'admin_ajax', array(
                    'url' => admin_url('admin-ajax.php')
                ));

                if ($this->wp_version < 3.5) {
                    wp_enqueue_style('thickbox'); // Stylesheet used by Thickbox
                    wp_enqueue_script('thickbox');
                    wp_enqueue_script('custom-options-ph-admin-media-less-3.5', $this->url . 'js/admin-media-less-3.5.js');
                } else {
                    wp_enqueue_media();
                    wp_register_script('custom_options_ph_media', $this->url . 'js/admin-media-3.5.js', array('jquery'), '1.0.0', true);
                    wp_localize_script('custom_options_ph_media', 'custom_options_ph_media', array(
                        'title' => __('Upload or Choose Your Custom Image File', 'base_shortcode'),
                        'button' => __('Insert Image into Input Field', 'base_shortcode'))
                    );
                    wp_enqueue_script('custom_options_ph_media');
                }
                wp_enqueue_script('ckeditor', get_bloginfo('template_url') . '/plugins/ckeditor/ckeditor.js', array('jquery'));
            }
        }

        function admin_header() {
            $pos = strpos($_REQUEST['page'], 'custom-options-ph');
            if ($pos !== false) {
                $root_obj = parent::get_root_info();
                $tab_obj = parent::get_tab_info($_REQUEST['tab']);
                $prefix = $root_obj->id . '_' . $tab_obj->id . '_';

                $item_a = parent::get_items($_REQUEST['tab']);
                ?>
                <script>
                    window.onload = function() {
                        try {
                <?php
                foreach ($item_a as $item) {
                    if ($item->type == 'texteditor') {
                        ?>
                                            CKEDITOR.replace( '<?php echo $prefix . $item->name; ?>' );
                                            CKEDITOR.config.height = 250;
                                            CKEDITOR.config.resize_minHeight = 300;
                                            CKEDITOR.config.width = 600;
                        <?php
                    }
                }
                ?>
                        }catch(err) {}
                    };
                </script>
                <?php
            }
        }

        function add_help() {
            $tmp = parent::get_root_info();

            $screen = get_current_screen();
            $screen->set_help_sidebar('<p>' . __($tmp->help_sidebar) . '</p>');

            $screen->add_help_tab(array(
                'id' => 'custom-options-help-tab',
                'title' => __('General'),
                'content' => '<p>' . __($tmp->help) . '</p>',
            ));
            $tmp = parent::get_tab_info($_REQUEST['tab']);
            $screen->add_help_tab(array(
                'id' => 'custom-options-help-tab-' . $tmp->id,
                'title' => __('Tab'),
                'content' => '<p>' . __($tmp->desc) . '</p>',
            ));
        }

        function render() {
            foreach ($_POST as $key => $val) {
                if (is_array($val)) {
                    update_option($key, implode(',', $val));
                } else {
                    if ($val != '')
                        update_option($key, $val);
                    else
                        delete_option($key);
                }
            }

            $tab_a = parent::get_tabs();
            ?>
            <div class="wrap" id="custom-options-ph">
                <div id="icon-themes" class="icon32"><br></div>
                <?php
                if (count($tab_a)) {
                    $idx = 0;
                    ?>
                    <h2 class="nav-tab-wrapper">
                        <?php
                        foreach ($tab_a as $tab) {
                            $class = 'nav-tab';
                            $tab_id = trim($tab->id);
                            if ($tab_id == $_REQUEST['tab'])
                                $class .= ' nav-tab-active';
                            else if (!$_REQUEST['tab'] && $idx == 0)
                                $class .= ' nav-tab-active';
                            ?>
                            <a href="<?php echo admin_url('themes.php?page=custom-options-ph&tab=' . $tab_id); ?>" class="<?php echo $class ?>"><?php _e($tab->label) ?></a>
                            <?php
                            ++$idx;
                        }
                        ?>
                    </h2>
                    <?php
                }
                ?>
                <form class="custom-options-form-ph" method="POST" action="">
                    <?php
                    $item_a = parent::get_items($_REQUEST['tab']);

                    $root_obj = parent::get_root_info();
                    $this->id = $root_obj->id;

                    if (count($item_a)) {
                        $tab_obj = parent::get_tab_info($_REQUEST['tab']);
                        $prefix = $root_obj->id . '_' . $tab_obj->id . '_';
                        ?>
                        <table class="form-table">
                            <?php
                            foreach ($item_a as $item) {
                                ?>
                                <?php
                                $option_nm = $prefix . trim($item->name);
                                $unit = trim($item->unit);
                                $desc = trim($item->desc);
                                ?>
                                <tr class="form-field form-required">
                                    <th scope="row">
                                        <label>
                                            <?php _e(trim($item->label)); ?>
                                        </label>
                                    </th>
                                    <?php
                                    if ($item->type == 'general_data' || $item->type == 'cron_job') {
                                        ?>
                                        <td class="ctrl" colspan="2">
                                            <?php $this->ctrl_check($prefix, $item); ?>
                                        </td>
                                    <?php } else { ?>
                                        <td class="ctrl">
                                            <?php $this->ctrl_check($prefix, $item); ?>
                                            <?php echo ($unit) ? '<span class="unit">' . __($unit) . '</span>' : '' ?>
                                            <?php echo ($desc) ? '<p class="description">' . __($desc) . '</p>' : '' ?>
                                        </td>
                                        <td class="option_nm"><?php _e($option_nm); ?></td>
                                    <?php } ?>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <?php
                    }
                    ?>

                    <div class="clearfix"></div>
                    <p class="submit">
            <!--                        <input type="reset" class="button reset pull-right button-large" value="<?php _e('Reset Options', 'custom_options_ph'); ?>"/>-->
                        <input type="submit" name="1111" class="button button-primary button-large" value="<?php _e('Save All Changes', 'custom_options_ph'); ?>"/>
                    </p>
                    <div class="clearfix"></div>
                </form>
            </div>
            <?php
        }

        function ctrl_check($prefix, $item) {
            $name = $prefix . $item->name;

            $default_val = $item->default;
            if (get_option($name))
                $default_val = get_option($name);

            $default_val = htmlspecialchars(stripslashes($default_val));

            $default_a = explode(",", trim($default_val));
            if (count($default_a) > 1)
                $default_val = $default_a;

            $title_a = explode(",", trim($item->option_titles));
            $val_a = explode(",", trim($item->option_values));
            $idx = 0;
            foreach ($title_a as $title) {
                $options[$title] = $val_a[$idx];
                ++$idx;
            }

            $create_ctrls = new Create_Ctrls_PH();
            switch ($item->type) {
                case 'input':
                    echo $create_ctrls->input_text($name, $default_val);
                    break;
                case 'select':
                    echo $create_ctrls->select($name, $options, $default_val);
                    break;
                case 'radio':
                    $attrs['style'] = 'margin: 0 10px;';
                    echo $create_ctrls->input_radio($name, $options, $default_val, $attrs);
                    break;
                case 'check':
                    $attrs['style'] = 'margin: 0 10px;';
                    echo $create_ctrls->input_check($name, $options, $default_val, $attrs);
                    //$this->check_ctrl($prefix, $item);
                    break;
                case 'texteditor':
                case 'textarea':
                    $default_val = $item->default;
                    if (get_option($name))
                        $default_val = get_option($name);
                    $default_val = htmlspecialchars(stripslashes($default_val));

                    $attrs['style'] = 'height: 150px;';
                    echo $create_ctrls->textarea($name, $default_val, $attrs);
                    break;
                case 'file_upload':
                    $this->file_upload_ctrl($prefix, $item);
                    break;
                case 'page':
                    $this->pages_select_ctrl($prefix, $item);
                    break;
                case 'general_data':
                    $this->general_data($item);
                    break;
                case 'cron_job':
                    $this->cron_job($prefix, $item);
                    break;
            }
        }

        function cron_job($prefix, $item) {
            $name = $prefix . trim($item->name);
            $mandatory = '';
            if (trim($item->mandatory) == 1)
                $mandatory = 'required="required"';

            $value = '';
            if (trim($item->default))
                $value = trim($item->default);
            if (get_option($name))
                $value = get_option($name);

            $class = '';
            ?>
            <input type="text" name="<?php echo $name ?>" class="<?php echo $class ?>" value="<?php echo $value; ?>" <?php echo $mandatory; ?> /><br/>
            <?php
            $cron_job_url = site_url() . '/?cron_job_' . $this->id . '=' . $value;
            echo $cron_job_url;
            ?>
            <input type="hidden" name="cron_job_url" id="cron_job_url" value="<?php echo $cron_job_url; ?>" />
            <p class="width-400"><?php _e('Please insert about url into cron job url input in cpanel.', 'custom_options_ph'); ?></p> 
            <?php
            /*
              wp_nonce_field('cron-job-ph-custom', 'cron-job-ph-security');
              ?>
              <input type="button" name="<?php echo $name ?>_btn" id="<?php echo $name ?>_btn" class="button button-primary button-large" value="Cron Job Action" style="width: initial;">
              <?php
             * 
             */
        }

        function general_data($item) {
            $obj = new generalDataPHCustom($item->name);
            ?>
            <form method="post" action="" name="<?php echo trim($item->name) ?>-form" id="<?php echo trim($item->name) ?>-form" class="general_data_form">
                <table id="<?php echo $item->name; ?>" class="general_data_tb">
                    <thead>
                        <tr>
                            <th style="width:auto;"><?php _e('No', 'custom_options_ph'); ?></th>
                            <th style="width:auto;"><?php _e('Name', 'custom_options_ph'); ?></th>
                            <th style="width:auto;"><?php _e('Description', 'custom_options_ph'); ?></th>
                            <th style="width:auto;"></th>
                        </tr>
                    </thead>
                    <tbody class="general_data_tbody">
                        <?php
                        $datas = $obj->get_all();
                        $idx = 1;
                        if (count($datas)) {
                            foreach ($datas as $data):
                                ?>
                                <tr class="view_datas">
                                    <td class="num"><?php echo $idx; ?></td>
                                    <td class="data_name"><?php _e(stripslashes($data->name)); ?></td>
                                    <td class="data_desc"><?php _e(stripslashes($data->desc)); ?></td>
                                    <td><div class="edit-icon"></div>&nbsp;&nbsp;&nbsp;&nbsp;<div class="delete-icon" did="<?php echo $data->id; ?>" tb_nm="<?php echo $item->name ?>"></div></td>                              
                                </tr>
                                <tr class="edit_ctrls" style="display:none">
                                    <td class="num" valign="top"><?php echo $idx; ?></td>
                                    <td valign="top"><input type="text" name="gname" value="<?php _e($data->name); ?>" style="width:auto;"/></td>
                                    <td valign="top"><textarea name="gdesc" style="width:auto;"><?php _e($data->desc); ?></textarea></td>
                                    <td valign="top"><input type="button" value="<?php _e('Edit', 'custom_options_ph'); ?>" class="button button-primary button-small general_data_edit" did="<?php echo $data->id; ?>" style="width:auto;margin-left: 7px;" tb_nm="<?php echo $item->name ?>"/>&nbsp;&nbsp;<input type="button" value="<?php _e('Cancel', 'custom_options_ph'); ?>" class="button button-primary button-small general_data_edit_cancel" style="width:auto;" tb_nm="<?php echo $item->name ?>"/></td>
                                </tr>
                                <?php
                                ++$idx;
                            endforeach;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <?php wp_nonce_field('edit-genaral-data-ph-custom', 'edit-genaral-data-ph-custom-security'); ?>
                            </td>
                            <td valign="top"><input type="text" name="gname" value="" style="width:auto;"/></td>
                            <td valign="top"><textarea name="gdesc" style="width:auto;"></textarea></td>
                            <td valign="top"><input type="button" value="<?php _e('Add', 'custom_options_ph'); ?>" class="button button-primary button-small general_data_insert" style="width:auto;margin-left: 7px;" tb_nm="<?php echo $item->name ?>"/></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
            <?php
        }

        function edit_ajax() {
            if (is_admin()) {
                check_ajax_referer('edit-genaral-data-ph-custom', 'security');

                $info = array();
                $info['name'] = $_REQUEST['name'];
                $info['desc'] = $_REQUEST['desc'];
                $edit_id = $_REQUEST['edit_id'];
                $edit_fg = $_REQUEST['edit_fg'];
                $tb_nm = $_REQUEST['tb_nm'];

                $obj = new generalDataPHCustom($tb_nm);

                $last_id = 0;
                if ($edit_fg == 'insert') {
                    $last_id = $obj->insert($info);
                }
                if ($edit_fg == 'edit') {
                    $obj->update($edit_id, $info);
                }
                if ($edit_fg == 'delete') {
                    $obj->delete($edit_id);
                }
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                    header("Content-Type: application/json");
                    echo json_encode(array(
                        'time' => time(),
                        'last_id' => $last_id
                    ));
                    exit;
                } else {
                    header("Location: " . $_SERVER["HTTP_REFERER"]);
                }
            }
        }

        function file_upload_ctrl($suffix, $item) {
            $name = $suffix . trim($item->name);
            $mandatory = '';
            if (trim($item->mandatory) == 1)
                $mandatory = 'required="required"';

            $value = '';
            if (trim($item->default))
                $value = trim($item->default);
            if (get_option($name))
                $value = get_option($name);

            if ($this->wp_version < 3.5) {
                $image_library_url = get_upload_iframe_src('image', null, 'library');
                $image_library_url = remove_query_arg(array('TB_iframe'), $image_library_url);
                $image_library_url = add_query_arg(array('context' => '', 'TB_iframe' => 1, 'width' => 670, 'height' => 500), $image_library_url);
                ?>
                <input type="text" name="<?php echo $name ?>" class="img-file-url" value="<?php echo $value; ?>" <?php echo $mandatory; ?> /><br/>
                <div class="clearfix"></div>
                <a href="<?php echo esc_url($image_library_url) ?>" class="button reset file-upload thickbox"><?php _e('Add Image', 'custom_options_ph'); ?></a>
                <div class="clearfix"></div>
                <div class="image-region">
                    <div class="delete-img <?php echo ($value) ? '' : 'hide' ?>"></div>
                    <?php
                    if ($value):
                        ?>
                        <img src="<?php echo $value ?>" width="260"/>
                        <?php
                    endif;
                    ?>
                </div>
                <?php
            } else {
                ?>
                <input type="text" name="<?php echo $name ?>" class="img-file-url" value="<?php echo $value; ?>" <?php echo $mandatory; ?> />
                <div class="clearfix"></div>
                <a href="javascript:void(0)" class="button reset file-upload"><?php _e('Add Image', 'custom_options_ph'); ?></a>
                <div class="clearfix"></div>
                <div class="image-region">
                    <div class="delete-img <?php echo ($value) ? '' : 'hide' ?>"></div>
                    <?php
                    if ($value):
                        ?>
                        <img src="<?php echo $value ?>" width="260"/>
                        <?php
                    endif;
                    ?>
                </div>
                <?php
            }
        }

        function pages_select_ctrl($suffix, $item) {
            $name = $suffix . trim($item->name);

            if (get_option($name))
                $value = get_option($name);

            $defaults = array(
                'depth' => 0, 'child_of' => 0,
                'selected' => $value, 'echo' => 1,
                'name' => 'page_id', 'id' => '',
                'show_option_none' => '', 'show_option_no_change' => '',
                'option_none_value' => ''
            );

            $r = wp_parse_args($args, $defaults);
            extract($r, EXTR_SKIP);

            $pages = get_pages($r);
            $output = '';
            // Back-compat with old system where both id and name were based on $name argument
            if (empty($id))
                $id = $name;

            if (!empty($pages)) {

                $output = "<select name='" . $name . "' " . $mandatory . " class=''>\n";

                $output .= "\t<option value=\"\">Select Page</option>";
                $output .= walk_page_dropdown_tree($pages, $depth, $r);
                $output .= "</select>\n";
            }

            $output = apply_filters('wp_dropdown_pages', $output);

            echo $output;
        }

// developer cusomized part
        function register_menu_page() {
            $tmp = parent::get_root_info();

            $page_help = add_theme_page($tmp->page_title, $tmp->menu_title, 'edit_theme_options', 'custom-options-ph', array($this, 'render'));
            add_action('load-' . $page_help, array($this, 'add_help'));
        }

    }

    new Theme_Options_PH();
endif;
?>
