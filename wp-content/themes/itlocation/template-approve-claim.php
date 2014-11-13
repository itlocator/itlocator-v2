<?php
/*
  Template Name: Approve Claim
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
			
		//$email_to = $company_user_data->user_email;

		$email_to = $_REQUEST['email'];
		// $email_to = 'xue_developer@126.com';

		$email_title = "Congratulations Your Claim of " . stripslashes($_REQUEST['company']) . " was Approved!";
		$email_content = "<p>Congratulations!  Your claim request for " . stripslashes($_REQUEST['company']) . " was reviews by Linked and approved.  You are free to login to your profile using the temporary login credentials below and populate your profile.</p><p></p><p>User Name : " . $login_name . "</p><p>Password : " . $new_password . "</p><p></p><p>We strongly encourage you to change your password when you login after the first time.</p><p>Because you took the time to claim your listing we want to present you with a special one-time discount if you upgrade your membership level as a way of saying thanks in participating in IT Locator.</p><p>Thanks</p><p>The IT Locator Team</p>";
		
		$functions_ph->send_mail($email_to, $email_from, $email_title, $email_content);
		
		$company_model = new companyModelItlocation();
		$company_model->setCompanyClaim( $user_id, true );
?>
<h1>Sent verify approve email to <?php echo $email_to; ?></h1>
<?php
	}
	
	get_footer();
?>