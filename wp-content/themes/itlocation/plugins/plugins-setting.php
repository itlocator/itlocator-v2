<?php

if (!class_exists("customFunctionsItlocation")):

    class customFunctionsItlocation {

        var $plugins_url = '';

        function __construct() {
            $this->plugins_url = get_bloginfo('template_url') . '/plugins/';
            add_action('wp_enqueue_scripts', array(&$this, 'js_css'));
        }

        function js_css() {
            if (!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
                wp_enqueue_style('bootstrap', $this->plugins_url . 'jasny-bootstrap/css/bootstrap.min.css');
                wp_enqueue_style('bootstrap-responsive', $this->plugins_url . 'jasny-bootstrap/css/bootstrap-responsive.min.css');
                wp_enqueue_style('select2', $this->plugins_url . 'select2-3.4.3/select2.css');
                //wp_enqueue_style('jquery-checkbox', $this->plugins_url . 'jquery-checkbox.1.3.0b1/jquery.checkbox.css');
                //wp_enqueue_style('jquery-checkbox-safari', $this->plugins_url . 'jquery-checkbox.1.3.0b1/jquery.safari-checkbox.css');
                wp_enqueue_style('itlocation-style', get_bloginfo('template_url') . '/style.css', array('bootstrap-responsive'));

                //wp_enqueue_script('bootstrap', $this->plugins_url . 'jasny-bootstrap/js/bootstrap.min.js', array('jquery'));
                wp_enqueue_script('select2', $this->plugins_url . 'select2-3.4.3/select2.min.js', array('jquery'));
                wp_enqueue_script('liveValidation', $this->plugins_url . 'liveValidation/jquery.liveValidation.js', array('jquery'));
                wp_localize_script('liveValidation', 'webroot', array(
                    'url' => get_bloginfo('template_url')
                ));

                wp_localize_script('jquery', 'plugin', array(
                    'url' => get_bloginfo('template_url') . '/plugins/'
                ));

                //wp_enqueue_script('jquery-checkbox', $this->plugins_url . 'jquery-checkbox.1.3.0b1/jquery.checkbox.min.js', array('jquery'));
                wp_enqueue_script('ckeditor', $this->plugins_url . 'ckeditor/ckeditor.js', array('jquery'));

                wp_enqueue_script('main-itlocation', get_bloginfo('template_url') . '/js/main.js', array('jquery'));
                wp_enqueue_script('cookie-itlocation', get_bloginfo('template_url') . '/js/jquery.cookie.js', array('jquery'));   
                wp_enqueue_script('jqueryform-itlocation', get_bloginfo('template_url') . '/js/jquery.form.min.js', array('jquery')); 
                wp_enqueue_script('starrating-itlocation', get_bloginfo('template_url') . '/js/jquery.raty.min.js', array('jquery')); 
             
            }
        }

    }

    new customFunctionsItlocation();

endif;
?>
