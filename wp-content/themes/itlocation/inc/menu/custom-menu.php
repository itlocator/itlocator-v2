<?php

class customMenuItLocation {
    /* --------------------------------------------*
     * Constructor
     * -------------------------------------------- */

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    function __construct() {


        // add custom menu fields to menu
        add_filter('wp_setup_nav_menu_item', array($this, 'add_custom_nav_fields'));

        // save menu custom fields
        add_action('wp_update_nav_menu_item', array($this, 'update_custom_nav_fields'), 10, 3);

        // edit menu walker
        add_filter('wp_edit_nav_menu_walker', array($this, 'edit_walker'), 10, 2);
    }

// end constructor

    /**
     * Add custom fields to $item nav object
     * in order to be used in custom Walker
     *
     * @access      public
     * @since       1.0 
     * @return      void
     */
    function add_custom_nav_fields($menu_item) {

        $menu_item->showfg = get_post_meta($menu_item->ID, '_menu_item_showfg', true);
        return $menu_item;
    }

    /**
     * Save menu custom fields
     *
     * @access      public
     * @since       1.0 
     * @return      void
     */
    function update_custom_nav_fields($menu_id, $menu_item_db_id, $args) {

        // Check if element is properly sent
        if (is_array($_REQUEST['menu-item-showfg'])) {
            $subtitle_value = $_REQUEST['menu-item-showfg'][$menu_item_db_id];
            update_post_meta($menu_item_db_id, '_menu_item_showfg', $subtitle_value);
        }
    }

    /**
     * Define new Walker edit
     *
     * @access      public
     * @since       1.0 
     * @return      void
     */
    function edit_walker($walker, $menu_id) {
        return 'Walker_Nav_Menu_Edit_ItLocation';
    }

}

// instantiate plugin's class
$GLOBALS['itlocatoin_custom_menu'] = new customMenuItLocation();

require_once( get_template_directory() . '/inc/menu/walker-nav-menu-edit.class.php' );
