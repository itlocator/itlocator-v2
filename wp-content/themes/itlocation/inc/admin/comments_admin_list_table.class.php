<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Comments_Admin_List_Table extends WP_List_Table {

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
            case 'cid':
            case 'cnm';
            case 'rating':
            case 'email':
            case 'unm':
            case 'comment':
            case 'date_registered':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_cnm($item) {
        $company_model = new companyModelItlocation();
        $tmp_obj = $company_model->get_by_id($item['cid']);

        $aurl = get_author_posts_url($tmp_obj->user_id);
        $actions = array(
            'view' => '<a href="' . $aurl . '" target="_blank">View</a>',
            'delete' => sprintf('<a href="" class="delete_comments" rid="%s">Delete</a>', $item['id'])
        );
        return sprintf('<a href="%s" target="_blank">%s</a> %s',
                        /* $1%s */ $aurl,
                        /* $3%s */ $item['cnm'],
                        /* $4%s */ $this->row_actions($actions)
        );
    }

    function column_cb($item) {
        return sprintf(
                        '<input type="checkbox" name="%1$s[]" value="%2$s" />',
                        /* $1%s */ $this->_args['singular'], //Let's simply repurpose the table's singular label ("movie")
                        /* $2%s */ $item['id']                //The value of the checkbox should be the record's id
        );
    }

    function get_columns() {
        $columns = array(
            //'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'cnm' => __('Company Name'),
            'rating' => __('Rating'),
            'email' => __('Email'),
            'unm' => __('User Name'),
            'comment' => __('comment'),
            'date_registered' => __('Register Date'),
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'cnm' => array('name', false), //true means it's already sorted
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
        $query = "SELECT * FROM company_comments";
        if (!empty($_GET["s"]))
            $query .= " WHERE cnm LIKE '%" . $_GET["s"] . "%' OR name LIKE '%" . $_GET["s"] . "%' OR comment LIKE '%" . $_GET["s"] . "%'";
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
        $perpage = $this->get_items_per_page('comments_per_page', 10);
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
            $this->items[$idx]['cid'] = stripslashes($obj->cid);
            $this->items[$idx]['cnm'] = stripslashes($obj->cnm);
            $this->items[$idx]['rating'] = $obj->rating;
            $this->items[$idx]['email'] = stripslashes($obj->email);
            $this->items[$idx]['unm'] = stripslashes($obj->name);
            $this->items[$idx]['comment'] = $obj->comment;
            $this->items[$idx]['date_registered'] = date(get_option('date_format'), strtotime($obj->date_registered));
            ++$idx;
        }
    }

}

?>