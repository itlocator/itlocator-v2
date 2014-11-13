<?php
/*
  Template Name: Deny Claim
*/
get_header();
	if( isset($_REQUEST['userid'])  ) {
		$user_id = $_REQUEST['userid'];
		$company_user_data = get_userdata( $user_id  );
		
		$login_name = $company_user_data->user_login;
		$new_password = wp_generate_password(12, false);
		
		wp_set_password( $new_password, $user_id );
		
		global $functions_ph;
		
		$email_from = get_option('admin_email');
		$email_to = $_REQUEST['email'];	
		//$email_to = $company_user_data->user_email;;
		// $email_to = 'xue_developer@126.com';
		
		$email_title = "We Could Not Approve Your Claim of " . stripslashes($_REQUEST['company']) . " was Approved!";
		$email_content = "<p>Unfortunately we were unable to approve your claim request for " . stripslashes($_REQUEST['company']) . ". Our internal claim review process was unable to definitely associate you with the company listed on IT Locator which is a requirement for approving any claim requests we receive.</p><p>We would still like to encourage you to join IT Locator. Because you took the time to claim your listing we want to present you with a special one-time discount if you upgrade your membership level as a way of saying thanks in making the effort.</p><p>Once you have your new listing up, please email us and if after comparison its clear we made a mistake in not initially authorizing you, we will gladly remove the current profile for " . stripslashes($_REQUEST['company']) . ".</p><p>Thanks</p><p>The IT Locator Team</p>";
		
		$functions_ph->send_mail($email_to, $email_from, $email_title, $email_content);
		
		$company_model = new companyModelItlocation();
		$company_model->setCompanyClaim( $user_id, 0 );
?>
<h1>Sent not approve email to <?php echo $email_to; ?></h1>
<?php
	}
	
	get_footer();
?>