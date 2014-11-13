<?php
include '../../../../wp-config.php';

$company = $_REQUEST['company'];
global $wpdb;

$query = "
	SELECT user_id, companyname FROM company 
	WHERE LOWER(companyname) LIKE '%" . strtolower( $company ) . "%' OR LOWER(description) LIKE '%" . strtolower( $company ) . "%' 
	ORDER BY companyname
";

$datalist = $wpdb->get_results( $query );

if( count( $datalist ) ){
	$html = "";
	
	foreach( $datalist as $data ){
		$html .= '<div><a href="' . get_author_posts_url( $data->user_id ) . '">' . $data->companyname . '</a></div>';
	}
	
	echo $html;
} else {	
	echo '<span class="claim-do-not">There is no search result.</span>';
}

exit;
?>
