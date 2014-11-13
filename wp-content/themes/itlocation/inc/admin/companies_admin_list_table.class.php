<?php
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Companies_Admin_List_Table extends WP_List_Table {

    function __construct() {
        //Set parent defaults
        parent::__construct(array(
            'singular' => 'company', //singular name of the listed records
            'plural' => 'companies', //plural name of the listed records
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

        $tmp_url = add_query_arg('status', 'listings');
        $class = ($current == 'listings' ? ' class="current"' : '');
        $query = $this->get_sql_totalitems('listings');
        $totalitems = $wpdb->query($query);
        $views['listings'] = '<a href="' . $tmp_url . '" ' . $class . ' >Listings <span class="count">(' . $totalitems . ')</span></a>';

        $tmp_url = add_query_arg('status', 'members');
        $class = ($current == 'members' ? ' class="current"' : '');
        $query = $this->get_sql_totalitems('members');
        $totalitems = $wpdb->query($query);
        $views['members'] = '<a href="' . $tmp_url . '" ' . $class . ' >Members <span class="count">(' . $totalitems . ')</span></a>';

        $tmp_url = add_query_arg('status', 'platinums');
        $class = ($current == 'platinums' ? ' class="current"' : '');
        $query = $this->get_sql_totalitems('platinums');
        $totalitems = $wpdb->query($query);
        $views['platinums'] = '<a href="' . $tmp_url . '" ' . $class . ' >Platinums <span class="count">(' . $totalitems . ')</span></a>';

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
            case 'logo':
            case 'name':
            case 'role':
            case 'source':
            case 'address1':
            case 'country':
            case 'state':
            case 'city':
            case 'email':
            case 'description':
            case 'register_date':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_name($item) {
        $aurl = get_author_posts_url($item['uid']);
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&cid=%s">Edit</a>', $_REQUEST['page'], $item['id']),
            'delete' => sprintf('<a href="" class="delete_company" cid="%s">Delete</a>', $item['id']),
            'view' => '<a href="' . $aurl . '" target="_blank">View</a>',
            'upgrade' => sprintf('<a href="#" class="upgrade_company" cid="%s">Upgrade</a>', $item['id']),
            'downgrade' => sprintf('<a href="#" class="downgrade_company" cid="%s">Downgrade</a>', $item['id'])
        );
        return sprintf('<a href="?page=%s&cid=%s">%s</a> %s',
                        /* $1%s */ $_REQUEST['page'],
                        /* $3%s */ $item['id'],
                        /* $4%s */ $item['name'],
                        /* $5%s */ $this->row_actions($actions)
        );
    }

    function column_cb($item) {
        return sprintf(
                        '<input type="checkbox" name="%1$s[]" value="%2$s" />',
                        /* $1%s */ $this->_args['singular'],
                        /* $2%s */ $item['id']
        );
    }

    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'logo' => __('Logo'),
            'name' => __('Name'),
            'role' => __('Role'),
            'email' => __('Email'),
            'source' => __('Source'),
            'address1' => __('Address1'),
            'country' => __('Country'),
            'state' => __('State'),
            'city' => __('City'),
            'description' => __('Description'),
            'register_date' => __('Register Date')
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'name' => array('companyname', false), //true means it's already sorted
            'role' => array('user_role', false),
            'source' => array('source', false),
            'country' => array('country', false),
            'state' => array('state', false),
            'city' => array('city', false),
            'register_date' => array('register_date', false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete',
            'upgrade' => 'Upgrade',
            'downgrade' => 'Downgrade'
        );
        return $actions;
    }

    function process_bulk_action() {		
        if( $this->current_action() === 'delete' ){
            $com_id_a = $_GET['company'];
            $company_model = new companyModelItlocation();

            foreach ($com_id_a as $com_id) {
                $tmp = $company_model->get_by_id($com_id);
                $reassign = 1;
                wp_delete_user( $tmp->user_id, $reassign );
				$company_model->del_by_id($com_id);
            }
?>
	<script>
		window.location = 'admin.php?page=company_mgn_itlocation&deleted=<?php echo count($_GET['company']); ?>';
	</script>
<?php
        } else if( $this->current_action() === 'upgrade' ){
			$com_id_a = $_GET['company'];
			$company_model = new companyModelItlocation();
			
			foreach ($com_id_a as $com_id) {
				$user_role = $company_model->get_user_role($com_id);
				$tmp = $company_model->get_by_id( $com_id );
								
				$user_id_role = new WP_User( $tmp->user_id );
				
				if( $user_role == 0 ){
					$user_id_role->set_role('s2member_level1');
					$company_model->set_user_role($com_id, 1);
				}else if( $user_role == 1 ){
					$user_id_role->set_role('s2member_level2');
					$company_model->set_user_role($com_id, 2);
				}else if( $user_role == 2 ){
					$user_id_role->set_role('s2member_level2');
					$company_model->set_user_role($com_id, 2);
				}
            }
?>
	<script>
		window.location = 'admin.php?page=company_mgn_itlocation&deleted=<?php echo count($_GET['company']); ?>';
	</script>
<?php
		} else if( $this->current_action() === 'downgrade' ){
			$com_id_a = $_GET['company'];
			$company_model = new companyModelItlocation();
			
			foreach ($com_id_a as $com_id) {
				$user_role = $company_model->get_user_role($com_id);
				$tmp = $company_model->get_by_id( $com_id );
								
				$user_id_role = new WP_User( $tmp->user_id );
				
				if( $user_role == 0 ){
					$user_id_role->set_role('subscriber');
					$company_model->set_user_role($com_id, 0);
				}else if( $user_role == 1 ){
					$user_id_role->set_role('subscriber');
					$company_model->set_user_role($com_id, 0);
				}else if( $user_role == 2 ){
					$user_id_role->set_role('s2member_level1');
					$company_model->set_user_role($com_id, 1);
				}
            }
?>
	<script>
		window.location = 'admin.php?page=company_mgn_itlocation&deleted=<?php echo count($_GET['company']); ?>';
	</script>
<?php
		}
    }

    function get_sql_totalitems($status = '') {
        /* -- Preparing your query -- */
        if (!$status) {
            $query = "SELECT * FROM company";
        } elseif ($status == 'listings') {
            $query = "SELECT * FROM company WHERE user_role='0'";
        } elseif ($status == 'members') {
            $query = "SELECT * FROM company WHERE user_role='1'";
        } elseif ($status == 'platinums') {
            $query = "SELECT * FROM company WHERE user_role='2'";
        }

        return $query;
    }

    function prepare_items() {
        global $wpdb; //, $_wp_column_headers;
        //$screen = get_current_screen();

        /* -- Preparing your query -- */
        $query = "SELECT * FROM company";
        if (!empty($_GET["status"]))
            $query = $this->get_sql_totalitems($_GET["status"]);
        if (!empty($_GET["s"])) {
            if (empty($_GET["status"]))
                $query .= " WHERE ";
            $query .= " companyname LIKE '%" . $_GET["s"] . "%' OR description LIKE '%" . $_GET["s"] . "%' OR contactemail LIKE '%" . $_GET["s"] . "%'";
        }
        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'register_date';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';
        if (!empty($orderby) & !empty($order)) {
            $query.= ' ORDER BY ' . $orderby . ' ' . $order;
        }

        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = $this->get_items_per_page('companies_per_page', 10);
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
        global $all_country_nms, $states, $functions_ph;
        $upload_dir = wp_upload_dir();
        $objs = $wpdb->get_results($query);
        $idx = 0;
        foreach ($objs as $obj) {
            $this->items[$idx]['id'] = $obj->id;
            $this->items[$idx]['role'] = $functions_ph->get_member_role_label_by_role_num($obj->user_role);
            $this->items[$idx]['uid'] = $obj->user_id;
            $this->items[$idx]['name'] = stripslashes($obj->companyname);
            $this->items[$idx]['address1'] = stripslashes($obj->address1);
            $this->items[$idx]['country'] = $all_country_nms[$obj->country];
            if ($states[$obj->country][$obj->state])
                $this->items[$idx]['state'] = $states[$obj->country][$obj->state];
            else
                $this->items[$idx]['state'] = $obj->state;
            $this->items[$idx]['city'] = $obj->city;
            $this->items[$idx]['email'] = $obj->contactemail;

            $this->items[$idx]['logo'] = '<img src="';
            if ($obj->logo_file_nm)
                $this->items[$idx]['logo'] .= $upload_dir["baseurl"] . "/comp_logo/" . $obj->logo_file_nm;
            else
                $this->items[$idx]['logo'] .= 'http://www.placehold.it/70x70/EFEFEF/AAAAAA&amp;text=no+image';
            $this->items[$idx]['logo'] .= '" width="70" height="70">';

            $this->items[$idx]['source'] = $obj->source;
			
            $this->items[$idx]['description'] = substr(stripslashes($obj->description), 0, 80);
            $this->items[$idx]['register_date'] = date(get_option('date_format'), strtotime($obj->register_date));
            ++$idx;
        }
    }

}
?>