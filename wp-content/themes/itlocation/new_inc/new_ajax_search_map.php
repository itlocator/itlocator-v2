<?php
ini_set('max_execution_time', 300);
add_action('wp_ajax_new-search-map-itlocation', 'new_search_map');
add_action('wp_ajax_nopriv_new-search-map-itlocation', 'new_search_map');

add_action('wp_ajax_new-advanced-search-map-itlocation', 'new_advanced_search_map');
add_action('wp_ajax_nopriv_new-advanced-search-map-itlocation', 'new_advanced_search_map');

add_action('wp_ajax_new-advanced-search-map-itlocation-html', 'new_advanced_search_map_html');
add_action('wp_ajax_nopriv_new-advanced-search-map-itlocation-html', 'new_advanced_search_map_html');

add_action('wp_ajax_new-advanced-search-map-round-my-location-itlocation', 'new_advanced_search_map_round_my_location');
add_action('wp_ajax_nopriv_new-advanced-search-map-round-my-location-itlocation', 'new_advanced_search_map_round_my_location');

add_action('wp_ajax_new-new-by-address-search-map', 'new_by_address_search_map');
add_action('wp_ajax_nopriv_new-by-address-search-map', 'new_by_address_search_map');

add_action('wp_ajax_new-advanced-search-map-round-my-location-itlocation-html', 'new_advanced_search_map_round_my_location_html');
add_action('wp_ajax_nopriv_new-advanced-search-map-round-my-location-itlocation-html', 'new_advanced_search_map_round_my_location_html');

function new_advanced_search_map_round_my_location_html(){	
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$lat = $_REQUEST['lat'];
		$lng = $_REQUEST['lng'];
              // $lat = 38.879896;
		//$lng = -77.10645299999999;
		$page = $_REQUEST['page'];
		
		$keyword = "";
		if( isset( $_REQUEST['ke'] ) ){
			$keyword = $_REQUEST['ke'];
		}

		$services = "";
		if( isset( $_REQUEST['se'] ) ){
			$services = $_REQUEST['se'];
		}

		$industries = "";
		if( isset( $_REQUEST['in'] ) ){
			$industries = $_REQUEST['in'];
		}

		$certifications = "";
		if( isset( $_REQUEST['ce'] ) ){
			$certifications = $_REQUEST['ce'];
		}

		$partners = "";
		if( isset( $_REQUEST['pa'] ) ){
			$partners = $_REQUEST['pa'];
		}
		 $option = "";
		if( isset( $_REQUEST['option'] ) ){
			$option = $_REQUEST['option'];
		}

		include_once 'new_company.php';
		$newCompanyCls = new newCompanyCls();	
		
		$total_count = $newCompanyCls->getAroundCompanyCount( $keyword, $services, $industries, $certifications, $partners, $lat, $lng );
		
		$datalist .= "<p>Search Results : <b>{$total_count}</b></p>";
		$currenturl= $_SERVER['HTTP_REFERER'];
		$datalist = '<div class="result-count">
                            <div class="row">
                                <div class="col-sm-6">
                                	Search Results <span class="badge">'.$total_count.'</span>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="sort navbar-right">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sort by <span class="caret"></span></a>
                                            <ul class="dropdown-menu" role="menu">
                                                
                                                <li><a href="'.$currenturl.'&option=1">Alphabetically </a></li>
                                                <li><a href="'.$currenturl.'&option=2" >Distance </a></li>
                                                <li><a href="'.$currenturl.'&option=3" >Membership</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>';
		
		$datalist .= $newCompanyCls->getAroundCompany( $keyword, $services, $industries, $certifications, $partners, $lat, $lng, $page, true ,$option);
		
		$datalist .= '<div class="pagination text-align-right">';

		$pageCount = round( $total_count / 20.0 + 0.45 );
		$datalist .= paginate_links(array(
			'base' => '',
			'format' => '',
			'type' => 'list',
			'prev_text' => __('&larr;'),
			'next_text' => __('&rarr;'),
			'current' => $page,
			'total' => $pageCount
		));

		$datalist .= '</div>';
		
		echo $datalist;
		exit;
	} else {
		header("Location: " . $_SERVER["HTTP_REFERER"]);
		exit;
	}		
}

function new_advanced_search_map_round_my_location(){	
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$lat = $_REQUEST['lat'];
		$lng = $_REQUEST['lng'];

		$keyword = "";
		if( isset( $_REQUEST['ke'] ) ){
			$keyword = $_REQUEST['ke'];
		}

		$services = "";
		if( isset( $_REQUEST['se'] ) ){
			$services = $_REQUEST['se'];
		}

		$industries = "";
		if( isset( $_REQUEST['in'] ) ){
			$industries = $_REQUEST['in'];
		}

		$certifications = "";
		if( isset( $_REQUEST['ce'] ) ){
			$certifications = $_REQUEST['ce'];
		}

		$partners = "";
		if( isset( $_REQUEST['pa'] ) ){
			$partners = $_REQUEST['pa'];
		}
		
		include_once 'new_company.php';
		$newCompanyCls = new newCompanyCls();	
		
		$datalist = $newCompanyCls->getAroundCompany( $keyword, $services, $industries, $certifications, $partners, $lat, $lng );
		
		// print_r($datalist);
		header("Content-Type: application/json");
		echo json_encode( $datalist );
		exit;
	} else {
		header("Location: " . $_SERVER["HTTP_REFERER"]);
		exit;
	}		
}
function new_by_address_search_map()
{
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		
		$x1 = "";
		if( isset( $_REQUEST['x1'] ) ){
			$x1 = $_REQUEST['x1'];
		}
		
		$y1 = "";
		if( isset( $_REQUEST['y1'] ) ){
			$y1 = $_REQUEST['y1'];
		}
		
		$x2 = "";
		if( isset( $_REQUEST['x2'] ) ){
			$x2 = $_REQUEST['x2'];
		}
		
		$y2 = "";
		if( isset( $_REQUEST['y2'] ) ){
			$y2 = $_REQUEST['y2'];
		}
		$latitude = "";
		if( isset( $_REQUEST['latitude'] ) ){
			$latitude = $_REQUEST['latitude'];
		}
		
		$longitude = "";
		if( isset( $_REQUEST['longitude'] ) ){
			$longitude = $_REQUEST['longitude'];
		}
		$service_query = "";
		
		if( isset($_REQUEST['services']) && is_array( $_REQUEST['services'] ) && count($_REQUEST['services']) > 0 ){
			$service_query .= "
				SELECT DISTINCT comp_id FROM services_company_relationships
				WHERE ";
			
			$first = true;
			foreach( $_REQUEST['services'] as $serviceid ){
				if( $first ) {
					$first = false;
				} else {
					$service_query .= " OR ";
				}
				
				$service_query .= " service_id =  " . $serviceid;
			}
			
			// echo $service_query;
		}
		
		$keyword_query = "";
		
		if( isset( $_REQUEST['keywords'] ) && $_REQUEST['keywords'] != '' ){
			$split_keywords = explode( ',', $_REQUEST['keywords'] );
			
			$first = true;			
			foreach( $split_keywords as $key_item ){
				if( $first ) {
					$first = false;
				} else {
					$keyword_query .= " OR ";
				}				
				$keyword_query .= " state LIKE '%" . trim( $key_item ) . "%' ";
				$keyword_query .= " OR address1 LIKE '%" . trim( $key_item ) . "%' OR address2 LIKE '%" . trim( $key_item ) . "%' OR description LIKE '%" . trim( $key_item ) . "%'";
			}
			
			$keyword_query = " AND ( " . $keyword_query . " ) ";
			// echo $keyword_query;
		}
		$location = $_REQUEST['lo'];
			 $location_query = "";
	
		
         		
			if( isset( $location ) && $location != '' ){
				global $states;
				
				$split_keywords = explode( ',', $location );
				
				$first = true;			
				 foreach( $split_keywords as $key_item ){
					if( $first ) {
						 $first = false;
					   } else {
						$location_query .= " OR ";
					 }				
					 $location_query .= " state LIKE '%" . trim( $key_item ) . "%' OR address LIKE '%" . trim( $key_item ) . "%' OR address1 LIKE '%" . trim( $key_item ) . "%' OR address2 LIKE '%" . trim( $key_item ) . "%' OR city LIKE '%" . trim( $key_item ) . "%'";
					
					 if( isset( $states["US"][strtoupper(trim( $key_item ))] ) ){
						 $location_query .= " OR state LIKE '%" . $states["US"][strtoupper(trim( $key_item ))] . "%'  OR address LIKE '%" . $states["US"][strtoupper(trim( $key_item ))] . "%'  OR address1 LIKE '%" . $states["US"][strtoupper(trim( $key_item ))] . "%'  OR address2 LIKE '%" . $states["US"][strtoupper(trim( $key_item ))] . "%' OR city LIKE '%" . $states["US"][strtoupper(trim( $key_item ))] . "%'";
					 }
				 }
				
				 $location_query = " AND ( " . $location_query . " ) ";
			 } 
			 
		$company_query = "
			SELECT * FROM company
			WHERE 1 " . $country_query . $keyword_query;

		if( isset( $x1 ) && $x1 != '' && isset( $x2 ) && $x2 != '' && isset( $y1 ) && $y1 != '' && isset( $y2 ) && $y2 != '' ){
				$location_query = "
					SELECT * FROM company_address
					WHERE {$x2} <= lat AND lat <= {$x1} AND {$y2} <= lng AND lng <= {$y1}
				";
                            
                
			}
			
		
			if( $location_query != "" ){
				$company_query = "
					SELECT aatbl.* FROM
					(
						{$company_query}
					) as aatbl
					INNER JOIN
					(
						{$location_query}
					) as bbtbl
					ON aatbl.id = bbtbl.comp_id
				";
			}
		
		//echo $company_query;
		
		
		if( $service_query != "" ){
			$company_query = "
				SELECT atbl.* FROM
				(
					{$company_query}
				) as atbl
				INNER JOIN
				(
					{$service_query}
				) as btbl
				ON atbl.id = btbl.comp_id
			";
		}
		
		$company_query = "
			SELECT ftbl.* FROM
			(
				SELECT etbl.*, company_address.lat as x, company_address.lng as y FROM
				(
					SELECT ctbl.id, ctbl.user_id, ctbl.user_role, ctbl.companyname as name, ctbl.address, ctbl.logo_file_nm, SUBSTRING(ctbl.description, 1, 300) as description, IF( ctbl.user_role=2, dtbl.rating, 'no') as rating FROM
					(
						{$company_query}
					) as ctbl
					LEFT JOIN
					(
						SELECT cid, AVG(rating) as rating FROM company_comments
						GROUP BY cid
					) as dtbl
					ON ctbl.id = dtbl.cid
				) as etbl
				LEFT JOIN
					company_address
				ON etbl.id = company_address.comp_id
			) as ftbl
		";
		

		
		// echo $company_query; exit;
		
		$upload_dir = wp_upload_dir();
		$destination_url = $upload_dir["baseurl"] . "/comp_logo/";
		
		$result = mysql_query( $company_query );
		$data_list = array();
		while( $row = mysql_fetch_array( $result ) ) {
			$data = array();
			
			$data['id'] = $row['id'];
			$data['user_id'] = $row['user_id'];
			$data['user_role'] = $row['user_role'];
			$data['name'] = $row['name'];
			$data['address'] = $row['address'];
			$data['x'] = $row['x'];
			$data['y'] = $row['y'];
			
			if( $data['user_role'] != '0' ){
				$data['description'] = stripslashes( $row['description'] );
				
				if( $row['logo_file_nm'] != null &&  $row['logo_file_nm'] != '' ){
					$data['logo_url'] = $destination_url . $row['logo_file_nm'];
				} else {
					$data['logo_url'] =  get_bloginfo('template_url')."/images/no-image.png";
				}
			}

			$data['permalink'] = get_author_posts_url( $data['user_id'] );
			$data['rating'] = $row['rating'];
			
			$data_list[] = $data;
		}

		header("Content-Type: application/json");
		echo json_encode( $data_list );
		exit;
	} else {
		header("Location: " . $_SERVER["HTTP_REFERER"]);
	}
}

function new_search_map(){
	check_ajax_referer('new-search-map-itlocation', 'security');
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		
		$service_query = "";
		
		if( isset($_REQUEST['services']) && is_array( $_REQUEST['services'] ) && count($_REQUEST['services']) > 0 ){
			$service_query .= "
				SELECT DISTINCT comp_id FROM services_company_relationships
				WHERE ";
			
			$first = true;
			foreach( $_REQUEST['services'] as $serviceid ){
				if( $first ) {
					$first = false;
				} else {
					$service_query .= " OR ";
				}
				
				$service_query .= " service_id =  " . $serviceid;
			}
			
			// echo $service_query;
		}
		
		$keyword_query = "";
		
		if( isset( $_REQUEST['keywords'] ) && $_REQUEST['keywords'] != '' ){
			$split_keywords = explode( ',', $_REQUEST['keywords'] );
			
			$first = true;			
			foreach( $split_keywords as $key_item ){
				if( $first ) {
					$first = false;
				} else {
					$keyword_query .= " OR ";
				}				
				//$keyword_query .= " state LIKE '%" . trim( $key_item ) . "%' ";
				//$keyword_query .= " OR address1 LIKE '%" . trim( $key_item ) . "%' OR address2 LIKE '%" . trim( $key_item ) . "%' OR description LIKE '%" . trim( $key_item ) . "%'";
				$keyword_query .= " state LIKE '%" . trim( $key_item ) . "%' OR address1 LIKE '%" . trim( $key_item ) . "%' OR address2 LIKE '%" . trim( $key_item ) . "%' OR description LIKE '%" . trim( $key_item ) . "%' OR companyname LIKE '%" . trim( $key_item ) . "%'";
			}
			
			$keyword_query = " AND ( " . $keyword_query . " ) ";
			// echo $keyword_query;
		}
		
		$country_query = "";
		if( isset( $_REQUEST['countries'] ) && $_REQUEST['countries'] != '' ){
			$country_query .= " AND country LIKE '" . $_REQUEST['countries'] . "'";
		}
		
		
		$company_query = "
			SELECT * FROM company
			WHERE 1 " . $country_query . $keyword_query;
		
		if( $service_query != "" ){
			$company_query = "
				SELECT atbl.* FROM
				(
					{$company_query}
				) as atbl
				INNER JOIN
				(
					{$service_query}
				) as btbl
				ON atbl.id = btbl.comp_id
			";
		}
		
		$company_query = "
			SELECT ftbl.* FROM
			(
				SELECT etbl.*, company_address.lat as x, company_address.lng as y FROM
				(
					SELECT ctbl.id, ctbl.user_id, ctbl.user_role, ctbl.companyname as name, ctbl.address, ctbl.logo_file_nm, SUBSTRING(ctbl.description, 1, 300) as description, IF( ctbl.user_role=2, dtbl.rating, 'no') as rating FROM
					(
						{$company_query}
					) as ctbl
					LEFT JOIN
					(
						SELECT cid, AVG(rating) as rating FROM company_comments
						GROUP BY cid
					) as dtbl
					ON ctbl.id = dtbl.cid
				) as etbl
				LEFT JOIN
					company_address
				ON etbl.id = company_address.comp_id
			) as ftbl
		";
		
		// echo $company_query; exit;
		
		$upload_dir = wp_upload_dir();
		$destination_url = $upload_dir["baseurl"] . "/comp_logo/";
		
		$result = mysql_query( $company_query );
		$data_list = array();
		while( $row = mysql_fetch_array( $result ) ) {
			$data = array();
			
			$data['id'] = $row['id'];
			$data['user_id'] = $row['user_id'];
			$data['user_role'] = $row['user_role'];
			$data['name'] = $row['name'];
			$data['address'] = $row['address'];
			$data['x'] = $row['x'];
			$data['y'] = $row['y'];
			
			if( $data['user_role'] != '0' ){
				$data['description'] = stripslashes( $row['description'] );
				
				if( $row['logo_file_nm'] != null &&  $row['logo_file_nm'] != '' ){
					$data['logo_url'] = $destination_url . $row['logo_file_nm'];
				} else {
					$data['logo_url'] =  get_bloginfo('template_url')."/images/no-image.png";
				}
			}

			$data['permalink'] = get_author_posts_url( $data['user_id'] );
			$data['rating'] = $row['rating'];
			
			$data_list[] = $data;
		}

		header("Content-Type: application/json");
		echo json_encode( $data_list );
		exit;
	} else {
		header("Location: " . $_SERVER["HTTP_REFERER"]);
	}
}

function new_advanced_search_map(){
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$location = "";
		if( isset( $_REQUEST['lo'] ) ){
			$location = $_REQUEST['lo'];
		}

		$country = "";
		if( isset( $_REQUEST['co'] ) ){
			$country = $_REQUEST['co'];
		}

		$keyword = "";
		if( isset( $_REQUEST['ke'] ) ){
			$keyword = $_REQUEST['ke'];
		}

		$services = "";
		if( isset( $_REQUEST['se'] ) ){
			$services = $_REQUEST['se'];
		}

		$industries = "";
		if( isset( $_REQUEST['in'] ) ){
			$industries = $_REQUEST['in'];
		}

		$certifications = "";
		if( isset( $_REQUEST['ce'] ) ){
			$certifications = $_REQUEST['ce'];
		}

		$partners = "";
		if( isset( $_REQUEST['pa'] ) ){
			$partners = $_REQUEST['pa'];
		}
		
		$x1 = "";
		if( isset( $_REQUEST['x1'] ) ){
			$x1 = $_REQUEST['x1'];
		}
		
		$y1 = "";
		if( isset( $_REQUEST['y1'] ) ){
			$y1 = $_REQUEST['y1'];
		}
		
		$x2 = "";
		if( isset( $_REQUEST['x2'] ) ){
			$x2 = $_REQUEST['x2'];
		}
		
		$y2 = "";
		if( isset( $_REQUEST['y2'] ) ){
			$y2 = $_REQUEST['y2'];
		}
		$lat = "";
		if( isset( $_REQUEST['lat'] ) ){
			$lat = $_REQUEST['lat'];
		}
		
		$long = "";
		if( isset( $_REQUEST['long'] ) ){
			$long = $_REQUEST['long'];
              	}	
		include_once 'new_company.php';
		$newCompanyCls = new newCompanyCls();	
		
		$datalist = $newCompanyCls->advanced_search( $location, $country, $keyword, $services, $industries, $certifications, $partners, $x1, $y1, $x2, $y2 ,$paged = -1, $html = false, $lat , $long );

		header("Content-Type: application/json");
		echo json_encode( $datalist );
		exit;
	} else {
		header("Location: " . $_SERVER["HTTP_REFERER"]);
	}
}

function new_advanced_search_map_html(){
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$location = "";
		if( isset( $_REQUEST['lo'] ) ){
			$location = $_REQUEST['lo'];
		}
		
		$country = "";
		if( isset( $_REQUEST['co'] ) ){
			$country = $_REQUEST['co'];
		}

		$keyword = "";
		if( isset( $_REQUEST['ke'] ) ){
			$keyword = $_REQUEST['ke'];
		}

		$services = "";
		if( isset( $_REQUEST['se'] ) ){
			$services = $_REQUEST['se'];
		}

		$industries = "";
		if( isset( $_REQUEST['in'] ) ){
			$industries = $_REQUEST['in'];
		}

		$certifications = "";
		if( isset( $_REQUEST['ce'] ) ){
			$certifications = $_REQUEST['ce'];
		}

		$partners = "";
		if( isset( $_REQUEST['pa'] ) ){
			$partners = $_REQUEST['pa'];
		}
		
		$page = "";
		if( isset( $_REQUEST['page'] ) ){
			$page = $_REQUEST['page'];
		}
		
		$x1 = "";
		if( isset( $_REQUEST['x1'] ) ){
			$x1 = $_REQUEST['x1'];
		}
		
		$y1 = "";
		if( isset( $_REQUEST['y1'] ) ){
			$y1 = $_REQUEST['y1'];
		}
		
		$x2 = "";
		if( isset( $_REQUEST['x2'] ) ){
			$x2 = $_REQUEST['x2'];
		}
		
		$y2 = "";
		if( isset( $_REQUEST['y2'] ) ){
			$y2 = $_REQUEST['y2'];
		}
		
                $lat = "";
		if( isset( $_REQUEST['lat'] ) ){
			$lat = $_REQUEST['lat'];
		}
		
		$long = "";
		if( isset( $_REQUEST['long'] ) ){
			$long = $_REQUEST['long'];
		}
                
                $option = "";
		if( isset( $_REQUEST['option'] ) ){
			$option = $_REQUEST['option'];
		}
		include_once 'new_company.php';
		$newCompanyCls = new newCompanyCls();	
		
		$total_count = $newCompanyCls->get_advanced_search_totalcount( $location, $country, $keyword, $services, $industries, $certifications, $partners, $x1, $y1, $x2, $y2 );
		
		$page_nav_list = '';
		
		$page_nav_list .= '<div class="pagination text-align-right">';
               
		$big = 999999999;
		$pageCount = round( $total_count / 20.0 + 0.45 );
		$page_nav_list .= paginate_links(array(
			'base' => '',
			'format' => '',
			'type' => 'list',
			'prev_text' => __('&larr;'),
			'next_text' => __('&rarr;'),
			'current' => $page,
			'total' => $pageCount
		));
                
              
		$page_nav_list .= '</div>';
		//$page_nav_list .= str_replace( "<ul class='page-numbers'>", '<ul class="pagination page-nav">', $page_nav_list );
		$datalist = "<p>Search Results : <b>{$total_count}</b></p>";

		$currenturl= $_SERVER['HTTP_REFERER'];		

                 $datalist = '<div class="result-count">
                            <div class="row">
                                <div class="col-sm-6">
                                	Search Results <span class="badge">'.$total_count.'</span>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="sort navbar-right">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sort by <span class="caret"></span></a>
                                            <ul class="dropdown-menu" role="menu">
                                                
                                                <li><a href="'.$currenturl.'&option=1">Alphabetically </a></li>
                                                <li><a href="'.$currenturl.'&option=2" >Distance </a></li>
                                                <li><a href="'.$currenturl.'&option=3" >Membership</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>';
                       
		
		
		$datalist .= $page_nav_list;
		$datalist .= $newCompanyCls->advanced_search( $location, $country, $keyword, $services, $industries, $certifications, $partners, $x1, $y1, $x2, $y2, $page, true, $lat, $long , $option);
		
		$datalist .= $page_nav_list;
							
		echo $datalist;
		exit;
	} else {
		header("Location: " . $_SERVER["HTTP_REFERER"]);
	}
}
?>