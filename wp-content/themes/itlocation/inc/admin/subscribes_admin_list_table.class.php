<?php
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Subscribes_Admin_List_Table extends WP_List_Table {

    function __construct() {
        //Set parent defaults
        parent::__construct(array(
            'singular' => 'subscribe', //singular name of the listed records
            'plural' => 'subscribes', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
    }

    function get_views() {
        global $wpdb;
        $views = array();
        $current = (!empty($_REQUEST['status']) ? $_REQUEST['status'] : 'all');

        //All link
        $tmp_url = remove_query_arg('status');
        $class = ($current == 'all' ? ' class="current"' : '');
        $query = $this->get_sql_totalitems();
        $totalitems = $wpdb->query($query);
        $views['all'] = '<a href="' . $tmp_url . '" ' . $class . ' >All <span class="count">(' . $totalitems . ')</span></a>';

        $tmp_url = add_query_arg('status', 'confirm');
        $class = ($current == 'confirm' ? ' class="current"' : '');
        $query = $this->get_sql_totalitems('confirm');
        $totalitems = $wpdb->query($query);
        $views['public'] = '<a href="' . $tmp_url . '" ' . $class . ' >Confirm <span class="count">(' . $totalitems . ')</span></a>';

        $tmp_url = add_query_arg('status', 'unconfirm');
        $class = ($current == 'unconfirm' ? ' class="current"' : '');
        $query = $this->get_sql_totalitems('unconfirm');
        $totalitems = $wpdb->query($query);
        $views['registered'] = '<a href="' . $tmp_url . '" ' . $class . ' >Unconfirm <span class="count">(' . $totalitems . ')</span></a>';

        return $views;
    }

    function views() {
        $views = $this->get_views();
        $views = apply_filters('views_' . $this->screen->id, $views);

        if (empty($views))
            return;

        echo "<ul class='subsubsub'>\n";
        foreach ($views as $class => $view) {
            $views[$class] = "\t<li class='$class'>$view";
        }
        echo implode(" |</li>\n", $views) . "</li>\n";
        echo "</ul>";
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'id':
            case 'email':
            case 'date_registered';
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_email($item) {
        $actions = array(
            'delete' => sprintf('<a href="" class="delete_subscribe" id="%s">Delete</a>', $item['id'])
        );
        return sprintf('%s %s',
                        /* $3%s */ $item['email'],
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
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'email' => __('Email'),
            'date_registered' => __('Register Date'),
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'email' => array('email', false), //true means it's already sorted
            'date_registered' => array('date_registered', false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {
        if ('delete' === $this->current_action()) {
            $tmp_a = $_GET['subscribe'];
            $model = new subscribeMgnItlocation();
            foreach ($tmp_a as $tmp) {
                $model->del_by_id($tmp);
            }
            ?>
            <script>
                window.location = 'admin.php?page=subscribers_itlocation&deleted=<?php echo count($_GET['subscribe']); ?>';
            </script>
            <?php
        }
    }

    function get_sql_totalitems($status = '') {
        /* -- Preparing your query -- */
        if (!$status) {
            $query = "SELECT * FROM subscribers";
            if (!empty($_GET["s"]))
                $query .= " WHERE email LIKE '%" . $_GET["s"] . "%'";
        } elseif ($status == 'confirm') {
            $query = "SELECT * FROM subscribers WHERE confirm_key = 0";
            if (!empty($_GET["s"]))
                $query .= " AND email LIKE '%" . $_GET["s"] . "%'";
        } elseif ($status == 'unconfirm') {
            $query = "SELECT * FROM subscribers WHERE confirm_key != 0";
            if (!empty($_GET["s"]))
                $query .= " AND email LIKE '%" . $_GET["s"] . "%'";
        }

        return $query;
    }

    function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();

        /* -- Preparing your query -- */
        //$query = 'SELECT * FROM subscribers';
        $query = $this->get_sql_totalitems($_GET['status']);

        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'date_registered';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';
        if (!empty($orderby) & !empty($order)) {
            $query .= ' ORDER BY ' . $orderby . ' ' . $order;
        }

        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = $this->get_items_per_page('subscribers_per_page', 10);
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

        $objs = $wpdb->get_results($query);

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
        $idx = 0;
        foreach ($objs as $obj) {
            $this->items[$idx]['id'] = $obj->id;
            $this->items[$idx]['email'] = stripslashes($obj->email);
            $this->items[$idx]['date_registered'] = date(get_option('date_format'), strtotime($obj->date_registered));
            ++$idx;
        }
    }

}
?>