<?php
ini_set('max_execution_time', 300);
    class newCompanyCls{
    	private $tb_nm;
		 
		function __construct( $tablename = 'company' ){
			$this->tb_nm = $tablename;
		}		
		
		function getCompanyDataByLoginUserId( $user_id ) {
			$data = null;
            $query = "SELECT * FROM {$this->tb_nm} WHERE user_id = {$user_id}";
			
			$result = mysql_query( $query );
			if( $row = mysql_fetch_array( $result ) ) {
				$data = array();
				
				$data['id'] = $row['id'];
				$data['user_id'] = $row['user_id'];
				$data['user_role'] = $row['user_role'];
			}
			
            return $data;
        }
		
		function getCompanyRoleByLoginUserId( $user_id ) {
			$data = null;
            $query = "SELECT user_role FROM {$this->tb_nm} WHERE user_id = {$user_id}";
			
			$result = mysql_query( $query );
			if( $row = mysql_fetch_array( $result ) ) {
				$data = $row['user_role'];
			}
			
            return $data;
        }
		
		function advanced_search( $location, $country, $keyword, $services, $industries, $certifications, $partners, $x1, $y1, $x2, $y2, $paged = -1, $html = false, $lat, $long ,$option=3 ){
		
            $service_query = "";
		
			if( isset($services) && is_array( $services ) && count($services) > 0 ){
				$service_query .= "
					SELECT DISTINCT comp_id FROM services_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $services as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$service_query .= " OR ";
					}
					
					$service_query .= " service_id =  " . $serviceid;
				}
				
			}

			$industry_query = "";
		
			if( isset($industries) && is_array( $industries ) && count($industries) > 0 ){
				$industry_query .= "
					SELECT DISTINCT comp_id FROM industries_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $industries as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$industry_query .= " OR ";
					}
					
					$industry_query .= " service_id =  " . $serviceid;
				}
				
				// echo $industry_query . '<br/><br/><br/>';
			}
			
			$certification_query = "";
		
			if( isset($certifications) && is_array( $certifications ) && count($certifications) > 0 ){
				$certification_query .= "
					SELECT DISTINCT comp_id FROM certifications_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $certifications as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$certification_query .= " OR ";
					}
					
					$certification_query .= " service_id =  " . $serviceid;
				}
				
				// echo $certification_query . '<br/><br/><br/>';
			}
			
			$partner_query = "";
		
			if( isset($partners) && is_array( $partners ) && count($partners) > 0 ){
				$partner_query .= "
					SELECT DISTINCT comp_id FROM partners_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $partners as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$partner_query .= " OR ";
					}
					
					$partner_query .= " service_id =  " . $serviceid;
				}
				
				// echo $partner_query . '<br/><br/><br/>';
			}
			
			$keyword_query = "";
			
			if( isset( $keyword ) && $keyword != '' ){
				$split_keywords = explode( ',', $keyword );
				
				$first = true;			
				foreach( $split_keywords as $key_item ){
					if( $first ) {
						$first = false;
					} else {
						$keyword_query .= " OR ";
					}				
					$keyword_query .= " state LIKE '%" . trim( $key_item ) . "%' OR address1 LIKE '%" . trim( $key_item ) . "%' OR address2 LIKE '%" . trim( $key_item ) . "%' OR description LIKE '%" . trim( $key_item ) . "%' OR companyname LIKE '%" . trim( $key_item ) . "%'";
				}
				
				$keyword_query = " AND ( " . $keyword_query . " ) ";
				// echo $keyword_query;
			}
			
			$country_query = "";
			if( isset( $country ) && $country != '' ){
				$country_query .= " AND country LIKE '" . $country . "'";
			}
            
			
           /* $location_query = "";    
    		if( isset( $location ) && $location != '' ){
				global $states;
				
				$split_keywords = explode( ',', $location );				
					
                 $location_query .= " state LIKE '%" . trim( $split_keywords[1] ) . "%' AND city LIKE '%" . trim( $split_keywords[0] ) . "%' AND  country LIKE '%US%'";
   			
                $location_query = " AND ( " . $location_query . " ) ";
			 } */       
            
            
            
			
			
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
			 
			 
			 
			 
			 
			 
			 
			
			 //echo $location_query;
			
			// $company_query = "
				// SELECT * FROM company
				// WHERE user_role != 1 " . $country_query . $keyword_query . $location_query;
			
			$company_query = "
				SELECT * FROM company
				WHERE 1 " . $country_query . $keyword_query;
				
			//$location_query = "";
			
			if( isset( $x1 ) && $x1 != '' && isset( $x2 ) && $x2 != '' && isset( $y1 ) && $y1 != '' && isset( $y2 ) && $y2 != '' ){
				$location_query = "
					SELECT * FROM company_address
					WHERE {$x2} <= lat AND lat <= {$x1} AND {$y2} <= lng AND lng <= {$y1}
				";
               
                
    			/* $location_query = "
					SELECT * FROM company_address
					WHERE {$x2} <= lat AND lat <= {$x1}
				";  */               
                
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
			
			if( $industry_query != "" ){
				$company_query = "
					SELECT a_table.* FROM
					(
						{$company_query}
					) as a_table
					INNER JOIN
					(
						{$industry_query}
					) as b_table
					ON a_table.id = b_table.comp_id
				";
			}
			
			if( $certification_query != "" ){
				$company_query = "
					SELECT c_table.* FROM
					(
						{$company_query}
					) as c_table
					INNER JOIN
					(
						{$certification_query}
					) as d_table
					ON c_table.id = d_table.comp_id
				";
			}
			
			if( $partner_query != "" ){
				$company_query = "
					SELECT e_table.* FROM
					(
						{$company_query}
					) as e_table
					INNER JOIN
					(
						{$partner_query}
					) as f_table
					ON e_table.id = f_table.comp_id
				";
			}
			$distanceQuery="( 3959 * acos( cos( radians(".$lat.") ) * cos( radians( company_address.lat ) ) * cos( radians(company_address.lng) - radians(".$long.")) + sin(radians(".$lat.")) * sin( radians(company_address.lat)))) AS distance ";

			if($option==1)
			{
				$orderby_query="ORDER BY ftbl.name";
			}
			elseif($option==2)
			{
				$orderby_query="ORDER BY ftbl.distance";
			}
			elseif($option==3)
			{
				$orderby_query="ORDER BY  ftbl.user_role DESC, ftbl.name ,ftbl.num_rating DESC ";
			}
			else{
				$orderby_query="ORDER BY ftbl.user_role DESC , ftbl.num_rating DESC, ftbl.name";
			}
			$company_query = "
				SELECT ftbl.* FROM
				(
					SELECT etbl.*, company_address.lat as x, company_address.lng as y , {$distanceQuery}  FROM
					(  
						SELECT ctbl.id, ctbl.user_id, ctbl.user_role, ctbl.companyname as name, ctbl.address, ctbl.phoneprim, ctbl.phonescond ,ctbl.contactemail as email ,ctbl.address1 ,ctbl.address2 ,ctbl.city ,ctbl.state ,ctbl.country ,ctbl.zip_code, ctbl.logo_file_nm, SUBSTRING(ctbl.description, 1, 300) as description, dtbl.rating as num_rating, IF( ctbl.user_role=2, dtbl.rating, NULL) as rating FROM
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
				
				{$orderby_query}
			";
//HAVING distance >= 10
// to set the max_allowed_packet to 500MB
//mysql_query( 'SET @@global.max_allowed_packet = ' . 500 * 1024 * 1024 );


			if( $paged > 0 ){
				$company_query .= " LIMIT " . ( $paged - 1 ) * 20 . ", " . $paged * 20;
			}
			//echo $company_query;			
			$upload_dir = wp_upload_dir();
			$destination_url = $upload_dir["baseurl"] . "/comp_logo/";
			
			$result = mysql_query( $company_query );
			$data_list = array();
			
			$html_content = "";
			
			// if( $html ) {
				// return $company_query;
			// } else {
				// return $company_query;
			// }
            
		if($result){ 	
			while( $row = mysql_fetch_array( $result ) ) { 
				if( $html ){
					$logo_url = "";
					
					if( $row['logo_file_nm'] != null &&  $row['logo_file_nm'] != '' ){
						$logo_url = $destination_url . $row['logo_file_nm'];
					} else {
						$logo_url = 'http://www.itlocator.com/wp-content/themes/itlocation/images/no-image.png';
					}
					/*if( isset( $lat ) && $lat != '' && isset( $long ) && $long != '')
					{
					   $miles=$this->calculateDistance($lat,$long,$row['x'],$row['y']);
                                        
						$kilometers = $miles * 1.609344;
						$meters = $miles * 1609.34;
						
					}*/
						     $usertype="";
				     if($row['user_role']==0)
					 {
					 	$usertype="Free listing";
					 }
					 elseif($row['user_role']==1)
					 {
					 	$usertype="Member";
					 }
					 else
					 {
					 	$usertype="Premium";
					 }       
                 $html_content .=' <div class="media client-list">
                            <div class="pull-left">
                            	<div class="img-holder"><a href="'. get_author_posts_url( $row['user_id'] ) .'"><img class="media-object width-100 height-100" src="'.$logo_url.'" /></a></div>
                            </div>
                            <div class="media-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="media-heading"><i class="fa fa-building-o"></i> <a href="'. get_author_posts_url( $row['user_id'] ) .'">'. stripslashes($row['name']) .'</a></h4>
                                        <p>' . stripslashes($row['description']) .'</p>
                                        <a href="'.get_author_posts_url( $row['user_id'] ).'" class="btn btn-default btn-sm"> More</a>
                                    </div>
                                    <div class="col-sm-3">
                                    	<dl>
                                            <dt><i class="fa fa-phone"></i> Prime Phone:</dt>
                                            <dd>'.$row['phoneprim'].'</dd>
                                            <dt><i class="fa fa-mobile-phone"></i> Second Phone:</dt>
                                            <dd>'.$row['phonescond'].'</dd>
                                            <dt><i class="fa fa-envelope"></i> Email:</dt>
                                            <dd><a href="mailto:'.$row['email'].'">'.$row['email'].'</a></dd>
                                        </dl>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="badge b'.$row['user_role'].'"><i class="fa fa-check-circle-o"></i> '.$usertype.'</div>
                                        <address>
                                            <strong><i class="fa fa-briefcase"></i> '.$row['address1'].'</strong><br>'.  
                                    $row['address2'] .' '
                               
                               .  $row['city'] . ' ' . $row['state'] . ' ' . $row['zip_code'] . '<br>' . $row['country']. '<br>
                                            <strong><i class="fa  fa-car"></i> '.round($row['distance']).'m | '.round($row['distance']* 1.609344).'km away</strong>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>';
				
				/*	$html_content .= "<div class='media member_bg_{$row['user_role']}'><a class='pull-left a-media' href='" . get_author_posts_url( $row['user_id'] ) . "'><img class='media-object width-100 height-100' src='{$logo_url}'></a><div class='media-body'><h4 class='media-heading'><a href='" . get_author_posts_url( $row['user_id'] ) . "'>" . stripslashes($row['name']) ."</a>";*/
					
					/*if( $row['user_role'] == 2 ) {
						$html_content .= "<div class='star-rating-grp pull-right' id='company-comments'><div class='star-rating' title='Rated  out of 5'><span style='width:0%'></span></div></div>";
					}
					
					$html_content .=  "</h4></div><p class='media-content'>" . stripslashes($row['description']) . '[...]' . "</p></div>";*/
				} else {
					$data = array();
					
					$data['id'] = $row['id'];
					$data['user_id'] = $row['user_id'];
					$data['user_role'] = $row['user_role'];
					$data['name'] = $row['name'];
					$data['address'] = $row['address'];
					$data['x'] = $row['x'];
					$data['y'] = $row['y'];
					
					$data['description'] = stripslashes( $row['description'] );
					
					if( $row['logo_file_nm'] != null &&  $row['logo_file_nm'] != '' ){
						$data['logo_url'] = $destination_url . $row['logo_file_nm'];
					} else {
						$data['logo_url'] = 'http://www.itlocator.com/wp-content/themes/itlocation/images/no-image.png';
					}

					$data['permalink'] = get_author_posts_url( $data['user_id'] );
					$data['rating'] = $row['rating'];
					
					$data_list[] = $data;
				}
			}
		}    
			
			if( $html ){
				return $html_content;
			}
			
			return $data_list;
        }
		
		function get_advanced_search_totalcount( $location, $country, $keyword, $services, $industries, $certifications, $partners, $x1, $y1, $x2, $y2 ){
		
            $service_query = "";
		
			if( isset($services) && is_array( $services ) && count($services) > 0 ){
				$service_query .= "
					SELECT DISTINCT comp_id FROM services_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $services as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$service_query .= " OR ";
					}
					
					$service_query .= " service_id =  " . $serviceid;
				}
				
				// echo $service_query;
			}

			$industry_query = "";
		
			if( isset($industries) && is_array( $industries ) && count($industries) > 0 ){
				$industry_query .= "
					SELECT DISTINCT comp_id FROM industries_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $industries as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$industry_query .= " OR ";
					}
					
					$industry_query .= " service_id =  " . $serviceid;
				}
				
				// echo $industry_query . '<br/><br/><br/>';
			}
			
			$certification_query = "";
		
			if( isset($certifications) && is_array( $certifications ) && count($certifications) > 0 ){
				$certification_query .= "
					SELECT DISTINCT comp_id FROM certifications_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $certifications as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$certification_query .= " OR ";
					}
					
					$certification_query .= " service_id =  " . $serviceid;
				}
				
				// echo $certification_query . '<br/><br/><br/>';
			}
			
			$partner_query = "";
		
			if( isset($partners) && is_array( $partners ) && count($partners) > 0 ){
				$partner_query .= "
					SELECT DISTINCT comp_id FROM partners_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $partners as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$partner_query .= " OR ";
					}
					
					$partner_query .= " service_id =  " . $serviceid;
				}
				
				// echo $partner_query . '<br/><br/><br/>';
			}
			
			$keyword_query = "";
			
			if( isset( $keyword ) && $keyword != '' ){
				$split_keywords = explode( ',', $keyword );
				
				$first = true;			
				foreach( $split_keywords as $key_item ){
					if( $first ) {
						$first = false;
					} else {
						$keyword_query .= " OR ";
					}				
					//$keyword_query .= " state LIKE '%" . trim( $key_item ) . "%' OR address1 LIKE '%" . trim( $key_item ) . "%' OR address2 LIKE '%" . trim( $key_item ) . "%' OR description LIKE '%" . trim( $key_item ) . "%'";
					
					$keyword_query .= "  description LIKE '%" . trim( $key_item )  . "%' OR companyname LIKE '%" . trim( $key_item )."%'";
				}
				
				$keyword_query = " AND ( " . $keyword_query . " ) ";
				// echo $keyword_query;
			}
			
			$country_query = "";
			if( isset( $country ) && $country != '' ){
				$country_query .= " AND country LIKE '" . $country . "'";
			}
			

            $location_query = "";    
        	if( isset( $location ) && $location != '' ){
				global $states;
				
				$split_keywords = explode( ',', $location );				
					
                 $location_query .= " state LIKE '%" . trim( $split_keywords[1] ) . "%' AND city LIKE '%" . trim( $split_keywords[0] ) . "%' AND  country LIKE '%US%'";
   			
                $location_query = " AND ( " . $location_query . " ) ";
			 } 


			// $location_query = "";			
			// if( isset( $location ) && $location != '' ){
				// global $states;
				
				// $split_keywords = explode( ',', $location );
				
				// $first = true;			
				// foreach( $split_keywords as $key_item ){
					// if( $first ) {
						// $first = false;
					// } else {
						// $location_query .= " OR ";
					// }
					
					// $location_query .= " state LIKE '%" . trim( $key_item ) . "%' OR address LIKE '%" . trim( $key_item ) . "%' OR address1 LIKE '%" . trim( $key_item ) . "%' OR address2 LIKE '%" . trim( $key_item ) . "%' OR city LIKE '%" . trim( $key_item ) . "%'";
					
					// if( isset( $states["US"][strtoupper(trim( $key_item ))] ) ){
						// $location_query .= " OR state LIKE '%" . $states["US"][strtoupper(trim( $key_item ))] . "%'  OR address LIKE '%" . $states["US"][strtoupper(trim( $key_item ))] . "%'  OR address1 LIKE '%" . $states["US"][strtoupper(trim( $key_item ))] . "%'  OR address2 LIKE '%" . $states["US"][strtoupper(trim( $key_item ))] . "%' OR city LIKE '%" . $states["US"][strtoupper(trim( $key_item ))] . "%'";
					// }
				// }
				
				// $location_query = " AND ( " . $location_query . " ) ";
			// }
			
			// $company_query = "
				// SELECT * FROM company
				// WHERE user_role != 1 " . $country_query . $keyword_query . $location_query;
			
			$company_query = "
				SELECT * FROM company
				WHERE id != 0 " . $country_query . $keyword_query;
				
			$location_query = "";
			
			if( isset( $x1 ) && $x1 != '' && isset( $x2 ) && $x2 != '' && isset( $y1 ) && $y1 != '' && isset( $y2 ) && $y2 != '' ){
				/*$location_query = "
					SELECT * FROM company_address
					WHERE {$x2} <= lat AND lat <= {$x1}
				";
				*/
               
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
			
			if( $industry_query != "" ){
				$company_query = "
					SELECT a_table.* FROM
					(
						{$company_query}
					) as a_table
					INNER JOIN
					(
						{$industry_query}
					) as b_table
					ON a_table.id = b_table.comp_id
				";
			}
			
			if( $certification_query != "" ){
				$company_query = "
					SELECT c_table.* FROM
					(
						{$company_query}
					) as c_table
					INNER JOIN
					(
						{$certification_query}
					) as d_table
					ON c_table.id = d_table.comp_id
				";
			}
			
			if( $partner_query != "" ){
				$company_query = "
					SELECT e_table.* FROM
					(
						{$company_query}
					) as e_table
					INNER JOIN
					(
						{$partner_query}
					) as f_table
					ON e_table.id = f_table.comp_id
				";
			}
			
			$company_query = "
				SELECT COUNT(ctbl.id) as num FROM
				(
					{$company_query}
				) as ctbl
			";
						
			$result = mysql_query( $company_query );
			$data = 0;
         
            if($result){   
			    if( $row = mysql_fetch_array( $result ) ) {
				    $data = $row['num'];
			    }
            }    
			
			return $data;
        }
		
		function getAroundCompany( $keyword, $services, $industries, $certifications, $partners, $lat, $lng, $page = -1, $html = false ,$option=0 ){
		
			$service_query = "";
		
			if( isset($services) && is_array( $services ) && count($services) > 0 ){
				$service_query .= "
					SELECT DISTINCT comp_id FROM services_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $services as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$service_query .= " OR ";
					}
					
					$service_query .= " service_id =  " . $serviceid;
				}
				
				// echo $service_query;
			}

			$industry_query = "";
		
			if( isset($industries) && is_array( $industries ) && count($industries) > 0 ){
				$industry_query .= "
					SELECT DISTINCT comp_id FROM industries_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $industries as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$industry_query .= " OR ";
					}
					
					$industry_query .= " service_id =  " . $serviceid;
				}
				
				// echo $industry_query . '<br/><br/><br/>';
			}
			
			$certification_query = "";
		
			if( isset($certifications) && is_array( $certifications ) && count($certifications) > 0 ){
				$certification_query .= "
					SELECT DISTINCT comp_id FROM certifications_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $certifications as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$certification_query .= " OR ";
					}
					
					$certification_query .= " service_id =  " . $serviceid;
				}
				
				// echo $certification_query . '<br/><br/><br/>';
			}
			
			$partner_query = "";
		
			if( isset($partners) && is_array( $partners ) && count($partners) > 0 ){
				$partner_query .= "
					SELECT DISTINCT comp_id FROM partners_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $partners as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$partner_query .= " OR ";
					}
					
					$partner_query .= " service_id =  " . $serviceid;
				}
				
				// echo $partner_query . '<br/><br/><br/>';
			}
			
			$keyword_query = "";
			
			if( isset( $keyword ) && $keyword != '' ){
				$split_keywords = explode( ',', $keyword );
				
				$first = true;			
				foreach( $split_keywords as $key_item ){
					if( $first ) {
						$first = false;
					} else {
						$keyword_query .= " OR ";
					}				
					//$keyword_query .= " state LIKE '%" . trim( $key_item ) . "%' OR address1 LIKE '%" . trim( $key_item ) . "%' OR address2 LIKE '%" . trim( $key_item ) . "%' OR description LIKE '%" . trim( $key_item ) . "%'";]
					
					$keyword_query .= "  description LIKE '%" . trim( $key_item )  . "%' OR companyname LIKE '%" . trim( $key_item )."%'";
				}
				
				$keyword_query = " AND ( " . $keyword_query . " ) ";
				// echo $keyword_query;
			}
			//WHERE user_role != 1 
			$company_query = "
				SELECT * FROM company
				" . $keyword_query;
			
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
			
			if( $industry_query != "" ){
				$company_query = "
					SELECT a_table.* FROM
					(
						{$company_query}
					) as a_table
					INNER JOIN
					(
						{$industry_query}
					) as b_table
					ON a_table.id = b_table.comp_id
				";
			}
			
			if( $certification_query != "" ){
				$company_query = "
					SELECT c_table.* FROM
					(
						{$company_query}
					) as c_table
					INNER JOIN
					(
						{$certification_query}
					) as d_table
					ON c_table.id = d_table.comp_id
				";
			}
			
			if( $partner_query != "" ){
				$company_query = "
					SELECT e_table.* FROM
					(
						{$company_query}
					) as e_table
					INNER JOIN
					(
						{$partner_query}
					) as f_table
					ON e_table.id = f_table.comp_id
				";
			}
			
			$r = 50; //my around 50 km
			$distanceQuery="( 3959 * acos( cos( radians(".$lat.") ) * cos( radians( atbl.x ) ) * cos( radians(atbl.y) - radians(".$lng.")) + sin(radians(".$lat.")) * sin( radians(atbl.x)))) AS distance ";

			if($option==1)
			{
				$orderby_query="ORDER BY etbl.name";
			}
			elseif($option==2)
			{
				$orderby_query="ORDER BY etbl.distance";
			}
			elseif($option==3)
			{
				$orderby_query="ORDER BY  etbl.user_role DESC, etbl.name ,etbl.num_rating DESC ";
			}
			else{
				$orderby_query="ORDER BY etbl.user_role DESC , etbl.num_rating DESC, etbl.name";
			}
			$query = "
				SELECT etbl.* FROM
				(
					SELECT ctbl.*, dtbl.rating as num_rating, IF( ctbl.user_role=2, dtbl.rating, NULL) as rating FROM
					(
						SELECT btbl.* FROM
						(
							SELECT ftbl.id, ftbl.user_id, ftbl.user_role, ftbl.companyname as name, ftbl.address, ftbl.phoneprim, ftbl.phonescond ,ftbl.contactemail as email ,ftbl.address1 ,ftbl.address2 ,ftbl.city ,ftbl.state ,ftbl.country ,ftbl.zip_code,ftbl.logo_file_nm, SUBSTRING(ftbl.description, 1, 300) as description, atbl.x, atbl.y ,{$distanceQuery} FROM
							(
								SELECT comp_id, lat as x, lng as y  FROM company_address
								WHERE (( 6371 * acos( cos( radians({$lat}) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( lat ) )))) < {$r}
							) as atbl
							INNER JOIN
							(
								{$company_query}
							) as ftbl
							ON atbl.comp_id = ftbl.id
						) as btbl
						ORDER BY btbl.name
					) as ctbl
					LEFT JOIN
					(
						SELECT cid, AVG(rating) as rating FROM company_comments
						GROUP BY cid
					) as dtbl
					ON ctbl.id = dtbl.cid
				) as etbl
				{$orderby_query}
			";
			
			if( $page > 0 ){
				$query .= " LIMIT " . ($page - 1) * 20 . ", " . 20;
			}
			
			$upload_dir = wp_upload_dir();
			$destination_url = $upload_dir["baseurl"] . "/comp_logo/";
			
			$result = mysql_query( $query );
			$data_list = array();
			$html_content = "";
			
			$data = array(
				'x' => $lat,
				'y' => $lng,
				'myloc' => 'true',
				'permalink' => '#'
			);
			
			$data_list[] = $data;
			
			while( $row = mysql_fetch_array( $result ) ) {
				$data = array();
				if( $html ) {
					$logo_url = "";
					
					if( $row['logo_file_nm'] != null &&  $row['logo_file_nm'] != '' ){
						$logo_url = $destination_url . $row['logo_file_nm'];
						
					} else {
						$logo_url = 'http://www.itlocator.com/wp-content/themes/itlocation/images/no-image.png';
					}
					
                                if( isset( $lat ) && $lat != '' && isset( $lng ) && $long != '')
					{
					   $miles=$this->calculateDistance($lat,$lng,$row['x'],$row['y']);
					   $kilometers = $miles * 1.609344;
					
					}
						     $usertype="";
				     if($row['user_role']==0)
					 {
					 	$usertype="Free listing";
					 }
					 elseif($row['user_role']==1)
					 {
					 	$usertype="Member";
					 }
					 else
					 {
					 	$usertype="Premium";
					 }       
                 $html_content .=' <div class="media client-list">
                            <div class="pull-left">
                            	<div class="img-holder"><a href="'. get_author_posts_url( $row['user_id'] ) .'"><img class="media-object width-100 height-100" src="'.$logo_url.'" /></a></div>
                            </div>
                            <div class="media-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="media-heading"><i class="fa fa-building-o"></i> <a href="'. get_author_posts_url( $row['user_id'] ) .'">'. stripslashes($row['name']) .'</a></h4>
                                        <p>' . stripslashes($row['description']) .'</p>
                                        <a href="'.get_author_posts_url( $row['user_id'] ).'" class="btn btn-default btn-sm"> More</a>
                                    </div>
                                    <div class="col-sm-3">
                                    	<dl>
                                            <dt><i class="fa fa-phone"></i> Prime Phone:</dt>
                                            <dd>'.$row['phoneprim'].'</dd>
                                            <dt><i class="fa fa-mobile-phone"></i> Second Phone:</dt>
                                            <dd>'.$row['phonescond'].'</dd>
                                            <dt><i class="fa fa-envelope"></i> Email:</dt>
                                            <dd><a href="mailto:'.$row['email'].'">'.$row['email'].'</a></dd>
                                        </dl>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="badge b'.$row['user_role'].'"><i class="fa fa-check-circle-o"></i> '.$usertype.'</div>
                                        <address>
                                            <strong><i class="fa fa-briefcase"></i> '.$row['address1'].'</strong><br>'.  
                                    $row['address2'] .' '
                               
                               .  $row['city'] . ' ' . $row['state'] . ' ' . $row['zip_code'] . '<br>' . $row['country']. '<br>
                                            <strong><i class="fa  fa-car"></i>'.round($row['distance']).'m | '.round($row['distance']* 1.609344).'km away</strong>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>';

					/*$html_content .= "<div class='media member_bg_{$row['user_role']}'><a class='pull-left a-media' href='" . get_author_posts_url( $row['user_id'] ) . "><img class='media-object width-100 height-100' src='{$logo_url}'></a><div class='media-body'><h4 class='media-heading'><a href='" . get_author_posts_url( $row['user_id'] ) . "'>" . stripslashes($row['name']) ."</a>";
					
					if( $row['user_role'] == 2 ) {
						$html_content .= "<div class='star-rating-grp pull-right' id='company-comments'><div class='star-rating' title='Rated  out of 5'><span style='width:0%'></span></div></div>";

					}
					
					$html_content .=  "</h4></div><p class='media-content'>" . stripslashes($row['description']) . '[...]' . "</p></div>";*/
				} else {
					$data['id'] = $row['id'];
					$data['user_id'] = $row['user_id'];
					$data['user_role'] = $row['user_role'];
					$data['name'] = $row['name'];
					$data['address'] = $row['address'];
					$data['x'] = $row['x'];
					$data['y'] = $row['y'];
					
					$data['description'] = stripslashes( $row['description'] );
					
					if( $row['logo_file_nm'] != null &&  $row['logo_file_nm'] != '' ){
						$data['logo_url'] = $destination_url . $row['logo_file_nm'];
						//$data['logo_url'] =  'http://www.itlocator.com/wp-content/uploads/comp_logo'. $row['logo_file_nm'];
					} else {
						$data['logo_url'] = 'http://www.itlocator.com/wp-content/themes/itlocation/images/no-image.png';
					}

					$data['permalink'] = get_author_posts_url( $data['user_id'] );
					$data['rating'] = $row['rating'];
					
					$data_list[] = $data;
				}
			}
			
			if( $html ) {
				return $html_content;
			}
			
			return $data_list;
		}
		
		function getAroundCompanyCount( $keyword, $services, $industries, $certifications, $partners, $lat, $lng ){
		
			$service_query = "";
		
			if( isset($services) && is_array( $services ) && count($services) > 0 ){
				$service_query .= "
					SELECT DISTINCT comp_id FROM services_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $services as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$service_query .= " OR ";
					}
					
					$service_query .= " service_id =  " . $serviceid;
				}
				
				// echo $service_query;
			}

			$industry_query = "";
		
			if( isset($industries) && is_array( $industries ) && count($industries) > 0 ){
				$industry_query .= "
					SELECT DISTINCT comp_id FROM industries_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $industries as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$industry_query .= " OR ";
					}
					
					$industry_query .= " service_id =  " . $serviceid;
				}
				
				// echo $industry_query . '<br/><br/><br/>';
			}
			
			$certification_query = "";
		
			if( isset($certifications) && is_array( $certifications ) && count($certifications) > 0 ){
				$certification_query .= "
					SELECT DISTINCT comp_id FROM certifications_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $certifications as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$certification_query .= " OR ";
					}
					
					$certification_query .= " service_id =  " . $serviceid;
				}
				
				// echo $certification_query . '<br/><br/><br/>';
			}
			
			$partner_query = "";
		
			if( isset($partners) && is_array( $partners ) && count($partners) > 0 ){
				$partner_query .= "
					SELECT DISTINCT comp_id FROM partners_company_relationships
					WHERE ";
				
				$first = true;
				foreach( $partners as $serviceid ){
					if( $first ) {
						$first = false;
					} else {
						$partner_query .= " OR ";
					}
					
					$partner_query .= " service_id =  " . $serviceid;
				}
				
				// echo $partner_query . '<br/><br/><br/>';
			}
			
			$keyword_query = "";
			
			if( isset( $keyword ) && $keyword != '' ){
				$split_keywords = explode( ',', $keyword );
				
				$first = true;			
				foreach( $split_keywords as $key_item ){
					if( $first ) {
						$first = false;
					} else {
						$keyword_query .= " OR ";
					}				
					$keyword_query .= " state LIKE '%" . trim( $key_item ) . "%' OR address1 LIKE '%" . trim( $key_item ) . "%' OR address2 LIKE '%" . trim( $key_item ) . "%' OR description LIKE '%" . trim( $key_item ) . "%'";
				}
				
				$keyword_query = " AND ( " . $keyword_query . " ) ";
				// echo $keyword_query;
			}
			
			$company_query = "
				SELECT * FROM company
				WHERE user_role != 1 " . $keyword_query;
			
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
			
			if( $industry_query != "" ){
				$company_query = "
					SELECT a_table.* FROM
					(
						{$company_query}
					) as a_table
					INNER JOIN
					(
						{$industry_query}
					) as b_table
					ON a_table.id = b_table.comp_id
				";
			}
			
			if( $certification_query != "" ){
				$company_query = "
					SELECT c_table.* FROM
					(
						{$company_query}
					) as c_table
					INNER JOIN
					(
						{$certification_query}
					) as d_table
					ON c_table.id = d_table.comp_id
				";
			}
			
			if( $partner_query != "" ){
				$company_query = "
					SELECT e_table.* FROM
					(
						{$company_query}
					) as e_table
					INNER JOIN
					(
						{$partner_query}
					) as f_table
					ON e_table.id = f_table.comp_id
				";
			}
			
			$r = 50; //my around 50 km
			
			$query = "
				SELECT COUNT(ctbl.comp_id) as total_count FROM
				(
					SELECT atbl.comp_id FROM
					(
						SELECT comp_id FROM company_address
						WHERE (( 6371 * acos( cos( radians({$lat}) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( lat ) )))) < {$r}
					) as atbl
					INNER JOIN
					(
						{$company_query}
					) as btbl
					ON atbl.comp_id = btbl.id
				) as ctbl
			";
			// return $query;
			$result = mysql_query( $query );
			if( $row = mysql_fetch_array( $result ) ){
				return $row['total_count'];
			}
			
			return 0;
		}
	function calculateDistance($latitude1, $longitude1, $latitude2, $longitude2) {
    $theta = $longitude1 - $longitude2;
    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    return $miles; 
}
	}
	

?>