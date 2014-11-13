<?php
function oauth_session_exists() {
	if((is_array($_SESSION)) && (array_key_exists('oauth', $_SESSION))) {
		return TRUE;
	} else {
		return FALSE;
	}
}

try {
	require_once('linkedin_3.2.0.class.php');
	
	if(!session_start()) {
		throw new LinkedInException('This script requires session support, which appears to be disabled according to session_start().');
	}
	
	$API_CONFIG = array(
		'appKey'       => '770okvm79acxel',
		'appSecret'    => 'dGfht3HPX0jn9V92',
		'callbackUrl'  => NULL 
	);

	define('CONNECTION_COUNT', 20);
	define('DEFAULT_COMPANY_SEARCH', 'Microsoft');
	define('PORT_HTTP', '80');
	define('PORT_HTTP_SSL', '443');
	define('UPDATE_COUNT', 10);
	define('RETURN_URL', "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI']);
	$_REQUEST[LINKEDIN::_GET_TYPE] = (isset($_REQUEST[LINKEDIN::_GET_TYPE])) ? $_REQUEST[LINKEDIN::_GET_TYPE] : '';
	
	//$_REQUEST[LINKEDIN::_GET_TYPE] = 'revoke';
	
	if( $_SESSION['oauth']['linkedin']['authorized'] !== TRUE ) {
		$_REQUEST[LINKEDIN::_GET_TYPE] = 'initiate';
	}
	
	switch($_REQUEST[LINKEDIN::_GET_TYPE]) {
		case 'initiate':
		
			$API_CONFIG['callbackUrl'] = RETURN_URL . '?' . LINKEDIN::_GET_TYPE . '=initiate&' . LINKEDIN::_GET_RESPONSE . '=1';
			
			$OBJ_linkedin = new LinkedIn($API_CONFIG);
      
			$_GET[LINKEDIN::_GET_RESPONSE] = (isset($_GET[LINKEDIN::_GET_RESPONSE])) ? $_GET[LINKEDIN::_GET_RESPONSE] : '';
			
			if(!$_GET[LINKEDIN::_GET_RESPONSE]) {
				$response = $OBJ_linkedin->retrieveTokenRequest();
				if($response['success'] === TRUE) {
					$_SESSION['oauth']['linkedin']['request'] = $response['linkedin'];
					header('Location: ' . LINKEDIN::_URL_AUTH . $response['linkedin']['oauth_token']);
				} else {
					echo "Request token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
				}
			} else {
				$response = $OBJ_linkedin->retrieveTokenAccess($_SESSION['oauth']['linkedin']['request']['oauth_token'], $_SESSION['oauth']['linkedin']['request']['oauth_token_secret'], $_GET['oauth_verifier']);
				if($response['success'] === TRUE) {
					$_SESSION['oauth']['linkedin']['access'] = $response['linkedin'];
					$_SESSION['oauth']['linkedin']['authorized'] = TRUE;
					// $_SESSION['claim_company'] = $_REQUEST['company'];
					header('Location: ' . RETURN_URL);
				} else {
					echo "Access token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
				}
			}
			break;
		case 'revoke':
			if(!oauth_session_exists()) {
				throw new LinkedInException('This script requires session support, which doesn\'t appear to be working correctly.');
			}
      
			$OBJ_linkedin = new LinkedIn($API_CONFIG);
			$OBJ_linkedin->setTokenAccess($_SESSION['oauth']['linkedin']['access']);
			$response = $OBJ_linkedin->revoke();
			
			if($response['success'] === TRUE) {
				session_unset();
				$_SESSION = array();
				if(session_destroy()) {
					header('Location: ' . $_SERVER['PHP_SELF']);
				} else {
					echo "Error clearing user's session";
				}
			} else {
				echo "Error revoking user's token:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
			}
			break; 
		case 'followCompany':
			if(!oauth_session_exists()) {
				throw new LinkedInException('This script requires session support, which doesn\'t appear to be working correctly.');
			}
			
			$OBJ_linkedin = new LinkedIn($API_CONFIG);
			$OBJ_linkedin->setTokenAccess($_SESSION['oauth']['linkedin']['access']);
			if(!empty($_GET['nCompanyId'])) {
				$response = $OBJ_linkedin->followCompany($_GET['nCompanyId']);
				if($response['success'] === TRUE) {
					header('Location: ' . RETURN_URL);
				} else {
					echo "Error 'following' company:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
				}
			} else {
				echo "You must supply a company ID to 'follow' a company.";
			}
			break;
		case 'unfollowCompany':
			if(!oauth_session_exists()) {
				throw new LinkedInException('This script requires session support, which doesn\'t appear to be working correctly.');
			}
      
			$OBJ_linkedin = new LinkedIn($API_CONFIG);
			$OBJ_linkedin->setTokenAccess($_SESSION['oauth']['linkedin']['access']);
			
			if(!empty($_GET['nCompanyId'])) {
				$response = $OBJ_linkedin->unfollowCompany($_GET['nCompanyId']);
				if($response['success'] === TRUE) {
					header('Location: ' . RETURN_URL);
				} else {
					echo "Error 'unfollowing' company:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
				}
			} else {
				echo "You must supply a company ID to 'unfollow' a company.";
			}
			break;
		default:
			if(version_compare(PHP_VERSION, '5.0.0', '<')) {
				throw new LinkedInException('You must be running version 5.x or greater of PHP to use this library.'); 
			} 
      
			if(extension_loaded('curl')) {
				$curl_version = curl_version();
				$curl_version = $curl_version['version'];
			} else {
				throw new LinkedInException('You must load the cURL extension to use this library.'); 
			}

			$_SESSION['oauth']['linkedin']['authorized'] = (isset($_SESSION['oauth']['linkedin']['authorized'])) ? $_SESSION['oauth']['linkedin']['authorized'] : FALSE;
			
			if($_SESSION['oauth']['linkedin']['authorized'] === TRUE) {
				$OBJ_linkedin = new LinkedIn($API_CONFIG);
				$OBJ_linkedin->setTokenAccess($_SESSION['oauth']['linkedin']['access']);
				$OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);
				$keywords = stripslashes($company_info->companyname);
				// $keywords = 'Apple';
				$query    = '?sort=relevance&keywords=' . urlencode($keywords);
				$response = $OBJ_linkedin->searchCompanies($query); 
				
				// echo "keywords=" . $keywords . "<br/>";
				// echo "<pre>" . print_r($response, TRUE) . "</pre>";
				// exit;
				
				if($response['success'] === TRUE) {
					$exist_company = 'not_in_linkedin';
					$linkedin_data = json_decode( $response['linkedin'] );
					
					if( $linkedin_data->companies->_total > 0 ) {
						foreach( $linkedin_data->companies->values as $item ){
							if( $item->name == $keywords ){
								$exist_company = 'yes_in_linkedin';
								break;
							}
						}
					}
				} else {
					echo "Error retrieving company search results:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";                
				}
			}
			
			break;
	}
} catch(LinkedInException $e) {
	echo $e->getMessage();
}
?>