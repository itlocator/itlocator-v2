<?php

include '../../../../wp-config.php';


function get_email_temp($title, $content) {

	$temp = '<head><style type="text/css" media="screen">.menu-footer-menu-1-container ul li a,.menu-footer-menu-2-container ul li a,.menu-footer-menu-3-container ul li a {color: #F3F3F3;text-decoration: none;}</style></head>';

	$temp .= '<body style="margin: 0; padding: 0; background-color: #F0F0F0; font-family: TAHOMA; font-size: 14px; color: #333;">';



//$temp .= '<table border="0" style="background: url(\'' . get_bloginfo('template_url') . '/images/top-bg.png\') no-repeat top center;width: 100%;"><tbody><tr><td><img src="' . get_bloginfo('template_url') . '/images/top-bg.png"/>&nbsp;</td></tr></tbody></table>';



	$temp .= '<table border="0" style="width: 100%;border-bottom: 5px solid #E4E4E4"><tbody><tr><td><a href="' . get_site_url() . '"><img src="' . get_bloginfo('template_url') . '/images/logo.png" alt="Logo"/></a></td><td style="color:#F1570B"><font face="TAHOMA">The Best Solutions Come From Local Technology Resellers</font></td></tr></tbody></table>';



	$temp .= '<table border="0" style="background: url(\'' . get_bloginfo('template_url') . '/images/bg-earth.png\') no-repeat top center; width: 100%;"><tbody><tr><td></td><td style="border: 1px solid #CCC;background: #FFF;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;padding: 20px;width: 70%;"><h3 style="font-size: 24.5px; margin: 0; border-bottom: 1px solid #EEE;"><font face="TAHOMA">' . $title . '</font></h3><p><font face="TAHOMA">' . $content . '</font></p></td><td></td></tr></tbody></table>';



	$temp .= '<table border="0" style="width: 100%;background-color: #8a8b8a; margin-top: 10px; padding-top: 10px"><tr><td></td><td style="width: 70%;"><table border="0" style="width: 100%;"><td valign="top" style="width: 25%;"><h5 style="margin-bottom: 10px;"><font face="TAHOMA">Site Navigation</font></h5>';

	$temp .= wp_nav_menu(array(

		'theme_location' => 'footer_menu_1',

		'items_wrap' => '<ul id="%1$s" class="%2$s" style="list-style: none;margin: 0;padding: 0; border-left: 1px dotted #F3F3F3; padding-left: 10px;font-size: 12px; ">%3$s</ul>',

		'echo' => false,

		'walker' => new walkerNavMenuEmailItLocation,

		'depth' => 1

			));

	$temp .= '</td><td valign="top" style="width: 25%;"><h5 style="margin-bottom: 10px;"><font face="TAHOMA">Why IT Locator</font></h5>';

	$temp .= wp_nav_menu(array(

		'theme_location' => 'footer_menu_2',

		'items_wrap' => '<ul id="%1$s" class="%2$s" style="list-style: none;margin: 0;padding: 0; border-left: 1px dotted #F3F3F3; padding-left: 10px;font-size: 12px; ">%3$s</ul>',

		'echo' => false,

		'walker' => new walkerNavMenuEmailItLocation,

		'depth' => 1

			));

	$temp .= '</td><td valign="top" style="width: 25%;"><h5 style="margin-bottom: 10px;"><font face="TAHOMA">Privacy Links</font></h5>';

	$temp .= wp_nav_menu(array(

		'theme_location' => 'footer_menu_3',

		'items_wrap' => '<ul id="%1$s" class="%2$s" style="list-style: none;margin: 0;padding: 0; border-left: 1px dotted #F3F3F3; padding-left: 10px;font-size: 12px; ">%3$s</ul>',

		'echo' => false,

		'walker' => new walkerNavMenuEmailItLocation,

		'depth' => 1

			));

	$temp .= '</td><td valign="top" style="width: 25%;"><h5><font face="TAHOMA">Contact us</font></h5><h2><font face="TAHOMA">' . get_option('itlocation_generals_contact_us_phone_number') . '</font></h2><a href="' . get_site_url() . '" class="logo" title="IT Locator"><img src="' . get_bloginfo('template_url') . '/images/logo-email.png" alt="IT Locator"></a></td></table></td><td></td></tr></table>';



	$temp .= '<table border="0" style="width: 100%; background: #7F7F7F;"><tr><td align="right" style="font-size: 12px;"><font face="TAHOMA">© 2009-2013 Itlocator. All rights reserved</font></td></tr></table>';



	$temp .= '</body>';



	return $temp;

}

		

function send_mail($to, $from, $e_title, $content, $files = array(), $title = '', $reply_info = array()) {

	require_once (get_template_directory() . '/php-plugins/phpmailer/class.phpmailer.php');



	$mail = new PHPMailer();

	$mail->IsSendmail();

	$mail->Subject = $e_title;

	if (!$from) {

		$name = 'ItLocator Notification';

		if (get_option('itlocation_email_settings_from_email_nm') != '') {

			$name = stripslashes(get_option('itlocation_email_settings_from_email_nm'));

		}

		$email = 'info@itlocator.com';

		if (get_option('itlocation_email_settings_from_email_address') != '') {

			$email = stripslashes(get_option('itlocation_email_settings_from_email_address'));

		}

		$from = $email;

	}

	$mail->SetFrom($from, $name);



	// if (!$to) {

		// $to = 'info@itlocator.com';

		// if (get_option('itlocation_email_settings_from_email_address') != '') {

			// $to = stripslashes(get_option('itlocation_email_settings_from_email_address'));

		// }

	// }

	foreach( $to as $item_email ){

		$mail->AddAddress( $item_email );

	}



	if ($title == '')

		$title = $e_title;



	$body = get_email_temp($title, $content);

	$mail->MsgHTML($body);

	if (count($files)) {

		foreach ($files as $file)

			$mail->AddAttachment($file['path'], $file['name']);

	}



	if (count($files)) {

		if ($reply_info)

			$mail->AddReplyTo($reply_info['email'], $reply_info['name']);

	}



	$error = '';

	if (!$mail->Send()) {

		echo "Mailer Error: " . $mail->ErrorInfo;

	}

	return $error;

}



global $functions_ph;				


$email_title = $_REQUEST['title'];

$email_content = '<p></p></br/>';


$email_content .= '<p>From : ' . $_REQUEST['title'] . '</p>';

//$email_content .= '<p>Email : ' . $user_email . '</p>';
$email_content .= '<p>Email : ' . $_REQUEST['from_email'] . '</p>';

$email_content .= '<p>Message :<br> ' . $_REQUEST['content'] . '</p>';
$email_content .= '<p></p></br/>';
//$send_email_address = array( get_option('admin_email'), 'xue_developer@126.com' );
$send_email_address = array($_REQUEST['to_email']);
//echo send_mail( $send_email_address,$_REQUEST['claim_to_email'], $email_title, $email_content );
send_mail( $send_email_address,$_REQUEST['from_email'], $email_title, $email_content );
$json_a['time'] = time();
header("Content-Type: application/json");
echo json_encode($json_a);
				
exit;

?>


