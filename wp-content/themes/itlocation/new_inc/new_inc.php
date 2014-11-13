<?php
	function get_subscribers_number( $status = '' ) {		
		global $wpdb;
		
		if ( $status == '' ) {
			$query = "
				SELECT SUM( atbl.num ) as num
				FROM
				(
					(
						SELECT COUNT( id ) AS num
						FROM subscribers
						WHERE confirm_key = 0
					)
					UNION
					(
						SELECT COUNT( id ) AS num
						FROM company
						WHERE user_role >= 1
					)
				) AS atbl
			";
		} elseif ( $status == 'public' ) {
			$query = "
				SELECT COUNT( id ) as num 
				FROM subscribers
			";
		} elseif ($status == 'registered') {
			$query = "
				SELECT COUNT( id ) as num
				FROM company
			";
		}
		
		$result = $wpdb->get_results( $query );
		return $result[0]->num;
	}
	
	function get_topic_count() {
		global $wpdb;
		
		$query = "
			SELECT COUNT( id ) as num FROM {$wpdb->posts}
			WHERE post_status LIKE 'publish' AND ( post_type LIKE 'topic' OR post_type LIKE 'industry-news-trends' )
		";
		
		$result = $wpdb->get_results( $query );
		return $result[0]->num;
	}
	
	function get_member_contribution_count(){
		// global $wpdb;
		
		// $query = "
			// SELECT COUNT( id ) as num FROM {$wpdb->posts}
			// WHERE post_type LIKE 'member-contributions'
		// ";
		
		// $result = $wpdb->get_results( $query );
		// return $result[0]->num;
		
		$result = wp_count_posts('member-contributions');
		
		return $result->publish;
	}
	
	function get_news_contribution_count(){
				
		$resultnews = wp_count_posts('industry-news-trends');		
		return $resultnews->publish;
	}
	
	function get_default_member_limit( $nm, $role ) {
		$limit_array = array(
			'contribution' 		=> array( '0' => '0', '1' => '1', '2' => '1' ),
			'rating' 			=> array( '0' => '0', '1' => '0', '2' => '1' ),
			'locations' 		=> array( '0' => '0', '1' => '3', '2' => '-1' ),
			'collateral' 		=> array( '0' => '0', '1' => '3', '2' => '-1' ),
			'services' 			=> array( '0' => '3', '1' => '10', '2' => '-1' ),
			'certifications' 	=> array( '0' => '0', '1' => '3', '2' => '-1' ),
			'partners' 			=> array( '0' => '0', '1' => '5', '2' => '-1' ),
			'industries' 		=> array( '0' => '3', '1' => '5', '2' => '-1' ),
			'case_studies' 		=> array( '0' => '0', '1' => '1', '2' => '-1' ),
		);
		
		$edit_fg = $limit_array[ $nm ][$role];
		
		// if( get_option( 'itlocation_member_limit_' . $nm . '_' . $role ) != '' ){
			// $edit_fg = get_option( 'itlocation_member_limit_' . $nm . '_' . $role );
		// }
		
		return $edit_fg;
	}
?>