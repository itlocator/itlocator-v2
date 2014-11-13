<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class sent_Mails_Admin_List_Table extends WP_List_Table {

    function __construct() {
        //Set parent defaults
        parent::__construct(array(
            'singular' => 'rating', //singular name of the listed records
            'plural' => 'ratings', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'id':
            case 'subject';
            case 'content':
            case 'recipient':
            case 'attached_files':
            case 'date_registered':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_attached_files($item) {
        $f_obj = new fileMgnModelItlocation('admin_mail_attached_files');
        $data_obj = $f_obj->get_list_by_pid($item['id']);

        $ret_str = '';
        $upload_url = get_bloginfo('template_url') . '/inc/admin-file-download.php?file_id=';

        foreach ($data_obj as $data) {
            $tmp = $upload_url . $data->id;
            $ret_str .= '<div><a href="' . $tmp . '" title="Download" alt="Download">';
            $ret_str .= $data->filename;
            $ret_str .= '</a></div>';
        }
        return $ret_str;
    }

    function get_columns() {
        $columns = array(
            'subject' => __('Subject'),
            'content' => __('Content'),
            'recipient' => __('Recipient'),
            'attached_files' => __('Attached Files'),
            'date_registered' => __('Date')
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'subject' => array('subject', false), //true means it's already sorted
            'date_registered' => array('date_registered', false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
                //'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {
        
    }

    function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();

        /* -- Preparing your query -- */
        $query = "SELECT * FROM admin_sent_mails";
        if (!empty($_GET["s"]))
            $query .= " WHERE subject LIKE '%" . $_GET["s"] . "%' OR content LIKE '%" . $_GET["s"] . "%'";
        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'date_registered';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';
        if (!empty($orderby) & !empty($order)) {
            $query.= ' ORDER BY ' . $orderby . ' ' . $order;
        }

        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = $this->get_items_per_page('sent_mail_per_page', 10);
        //Which page is this?
        $paged = $this->get_pagenum();
        //!empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        //Page Number
        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }
        //How many pages do we have in total?
        $totalpages = ceil($totalitems / $perpage);
        //adjust the query to take pagination into account
        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        /* -- Register the pagination -- */
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));
        //The pagination links are automatically built according to those parameters

        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();

        /* -- Fetch the items -- */
        $objs = $wpdb->get_results($query);
        $idx = 0;
        foreach ($objs as $obj) {
            $this->items[$idx]['id'] = $obj->id;
            $this->items[$idx]['subject'] = stripslashes($obj->subject);
            $this->items[$idx]['content'] = stripslashes($obj->content);
            $this->items[$idx]['recipient'] = $obj->recipient;
            $this->items[$idx]['date_registered'] = date(get_option('date_format'), strtotime($obj->date_registered));
            ++$idx;
        }
    }

}

?>