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
	define('RETURN_URL', 'http://www.itlocator.com/temp-linkedin/');
	
	$_REQUEST[LINKEDIN::_GET_TYPE] = (isset($_REQUEST[LINKEDIN::_GET_TYPE])) ? $_REQUEST[LINKEDIN::_GET_TYPE] : '';
	
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
?>
            <form id="linkedin_revoke_form" action="" method="get">
              <input type="hidden" name="<?php echo LINKEDIN::_GET_TYPE;?>" id="<?php echo LINKEDIN::_GET_TYPE;?>" value="revoke" />
              <input type="submit" value="Revoke Authorization" />
            </form>
<?php
				$OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_XML);
				$response = $OBJ_linkedin->company('1337:(id,name,ticker,description,logo-url,locations:(address,is-headquarters))');
			
				if($response['success'] === TRUE) {
					$company = new SimpleXMLElement($response['linkedin']);
				
					echo $company->name;
					echo $company->ticker;
					echo $company->{'logo-url'};
					echo $company->name;
					echo $company->name;

					foreach($company->locations->location as $location) {
						if($location->{'is-headquarters'} == 'true') {
							$address = $location->address;
						
							echo $address->street1;
							echo $address->city;
						}
					}
               
					echo $company->description;
					$response = $OBJ_linkedin->companyProducts('1337', ':(id,name,type,recommendations:(recommender,id))');
				
					if($response['success'] === TRUE) {
						$response['linkedin'] = new SimpleXMLElement($response['linkedin']);

						print_r($response['linkedin']);
					} else {
						echo "Error retrieving company products:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";
					}
				} else {
					echo "Error retrieving company information:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";
				}
            
				$OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_XML);
				$response = $OBJ_linkedin->followedCompanies();
			
				if($response['success'] === TRUE) {
					$followed = new SimpleXMLElement($response['linkedin']);
					if((int)$followed['total'] > 0) {
						foreach($followed->company as $company) {
							$cid  = $company->id;
							$name = $company->name;
							
							echo $name;
							echo $_SERVER['PHP_SELF'];
							echo LINKEDIN::_GET_TYPE;
							echo $cid;
						}
					} else {
						echo '<div>You do not currently follow any companies.</div>';
					}
				} else {
					echo "Error retrieving followed companies:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";
				}
				
				$OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_XML);
				$response = $OBJ_linkedin->suggestedCompanies();
				
				if($response['success'] === TRUE) {
					$suggested = new SimpleXMLElement($response['linkedin']);
					if((int)$suggested['count'] > 0) {
						foreach($suggested->company as $company) {
							$cid = (string)$company->id;
							if(!empty($cid)) {
								$name = $company->name;
								echo $name;
								echo '<a href="' . $_SERVER['PHP_SELF'] . '?' . LINKEDIN::_GET_TYPE . '=followCompany&amp;nCompanyId=' . $cid . '">Follow</a>';
							}
						}
					} else {
						echo '<div>LinkedIn is not suggesting any companies for you to follow at this time.</div>';
					}
				} else {
					echo "Error retrieving suggested companies:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";
				}
				
				$OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);
				$keywords = (isset($_GET['keywords'])) ? $_GET['keywords'] : DEFAULT_COMPANY_SEARCH;
?>
      			<form action="" method="get">
      				Search by Keywords: <input type="text" name="keywords" value="<?php echo $keywords;?>" /><input type="submit" value="Search" />
      			</form>
<?php
				$query    = '?sort=relevance&keywords=' . urlencode($keywords);
				$response = $OBJ_linkedin->searchCompanies($query); 
				if($response['success'] === TRUE) {
					echo "<pre>" . print_r($response['linkedin'], TRUE) . "</pre>";
				} else {
					echo "Error retrieving company search results:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";                
				}
			} else {
?>
				<form id="linkedin_connect_form" action="" method="get">
				  <input type="hidden" name="<?php echo LINKEDIN::_GET_TYPE;?>" id="<?php echo LINKEDIN::_GET_TYPE;?>" value="initiate" />
				  <input type="submit" value="Connect to LinkedIn" />
				</form>
<?php
			}
			break;
	}
} catch(LinkedInException $e) {
	echo $e->getMessage();
}
?>