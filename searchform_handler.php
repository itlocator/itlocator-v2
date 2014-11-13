<?php

  require(dirname(__FILE__) . '/wp-load.php');  
  
  
      function advanced_search_query($tb_nm, $para) {
            $return_a = array();
            if (isset($_REQUEST[$para])) {
                $query = 'SELECT comp_id FROM ' . $tb_nm . '_company_relationships ';

                $query .= ' WHERE ';
                $idx = 0;
                //$val_a = explode(',', $_REQUEST[$para]);
                if (count($_REQUEST[$para])) {
                    foreach ($_REQUEST[$para] as $val) {
                        if ($idx != 0)
                            $query .= ' OR ';
                        $query .= " service_id='" . $val . "'";

                        ++$idx;
                    }
                }
                $query .= 'GROUP BY comp_id';
                global $wpdb;
                $obj_a = $wpdb->get_results($query);
                foreach ($obj_a as $obj) {
                    $return_a[$obj->comp_id] = $obj->comp_id;
                }
            }
            return $return_a;
        }
        
         function intersect_array($c1_a, $c2_a) {
            $ret_a = array();
            if (count($c1_a) && count($c2_a))
                $ret_a = array_intersect($c1_a, $c2_a);
            elseif (count($c1_a))
                $ret_a = $c1_a;
            elseif (count($c2_a))
                $ret_a = $c2_a;

            return $ret_a;
        }
        
        
                function get_default_member_limit($nm, $role) {
            $edit_fg = 0;
            switch ($nm) {
                case 'contribution':
                    if ($role == '0')
                        $edit_fg = '0';
                    if ($role == '1')
                        $edit_fg = '1';
                    if ($role == '2')
                        $edit_fg = '1';
                    break;
                case 'rating':
                    if ($role == '0')
                        $edit_fg = '0';
                    if ($role == '1')
                        $edit_fg = '0';
                    if ($role == '2')
                        $edit_fg = '1';
                    break;
                case 'locations':
                    if ($role == '0')
                        $edit_fg = '1';
                    if ($role == '1')
                        $edit_fg = '3';
                    if ($role == '2')
                        $edit_fg = '-1';
                    break;
                case 'collateral':
                    if ($role == '0')
                        $edit_fg = '0';
                    if ($role == '1')
                        $edit_fg = '3';
                    if ($role == '2')
                        $edit_fg = '-1';
                    break;
                case 'services':
                    if ($role == '0')
                        $edit_fg = '3';
                    if ($role == '1')
                        $edit_fg = '10';
                    if ($role == '2')
                        $edit_fg = '-1';
                    break;
                case 'certifications':
                    if ($role == '0')
                        $edit_fg = '0';
                    if ($role == '1')
                        $edit_fg = '3';
                    if ($role == '2')
                        $edit_fg = '-1';
                    break;
                case 'partners':
                    if ($role == '0')
                        $edit_fg = '0';
                    if ($role == '1')
                        $edit_fg = '5';
                    if ($role == '2')
                        $edit_fg = '-1';
                    break;
                case 'industries':
                    if ($role == '0')
                        $edit_fg = '3';
                    if ($role == '1')
                        $edit_fg = '5';
                    if ($role == '2')
                        $edit_fg = '-1';
                    break;
                case 'case_studies':
                    if ($role == '0')
                        $edit_fg = '0';
                    if ($role == '1')
                        $edit_fg = '1';
                    if ($role == '2')
                        $edit_fg = '-1';
                    break;
            }
            if (get_option('itlocation_member_limit_' . $nm . '_' . $role) != '') {
                $edit_fg = get_option('itlocation_member_limit_' . $nm . '_' . $role);
            }
            return $edit_fg;
        }
        
        
        
         function searchLatLong($address) {
    
        $unsigned_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address.", US")."&sensor=false";
        
          
        // Send Google API Call
        $ch = curl_init($unsigned_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch); // Google response
        curl_close($ch);  
        
     //   $data = file_get_contents($unsigned_url);
        
        
         
        $output = json_decode($data);
        
        
        
        if ($output->status != 'OK')
            return "bad";
        
        return array(
            "lat" => $output->results[0]->geometry->location->lat,
            "lng" => $output->results[0]->geometry->location->lng
        );
    }
    
    
    


    function distance($lat1, $lng1, $lat2, $lng2, $miles = true){

        $pi80 = M_PI / 180;

        $lat1 *= $pi80;

        $lng1 *= $pi80;

        $lat2 *= $pi80;

        $lng2 *= $pi80;

     

        $r = 6372.797; // mean radius of Earth in km

        $dlat = $lat2 - $lat1;

        $dlng = $lng2 - $lng1;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $km = $r * $c;

     

        return ($miles ? ($km * 0.621371192) : $km);

    }
    
    
    $current_pos =  searchLatLong($_POST['lo']);
    
    
    

            $all_cids = array();
            $services = array();
            $certifications = array();
            $partners = array();
            $all_cids_str = '';
            $json_a = array();

            $services = advanced_search_query('services', 'se');
            $certifications = advanced_search_query('certifications', 'ce');
            $all_cids = intersect_array($services, $certifications);
            $partners = advanced_search_query('partners', 'pa');
            $all_cids = intersect_array($all_cids, $partners);
            $industries = advanced_search_query('industries', 'in');
            $all_cids = intersect_array($all_cids, $industries);

            $all_cids_str = implode(",", $all_cids);

            $fg = 1;
            if ($_POST['se'])
                $fg = 0;
            if ($_POST['ce'])
                $fg = 0;
            if ($_POST['pa'])
                $fg = 0;
            if ($_POST['in'])
                $fg = 0;

            if ($fg || $all_cids_str) {
                $tmp_a1 = explode(',', trim($_POST['lo']));
                if (count($tmp_a1) > 1) {
                    $s .= "city = '" . trim($tmp_a1[0]) . "' AND (state = '" . trim($tmp_a1[1]) . "' OR address = '" . trim($tmp_a1[1]) . "')";
                } elseif (trim($_POST['lo'])) {
                    $s .= "city = '" . trim($_POST['lo']) . "' OR state = '" . trim($_POST['lo']) . "' OR address = '" . trim($_POST['lo']) . "'";
                }
                /*
                  $tmp_a2 = explode(' ', trim($_REQUEST['lo']));
                  $idx = 0;
                  foreach ($tmp_a2 as $tmp) {
                  if (($idx == 0 ) && (count($tmp_a1) > 0))
                  $s .= " OR ";
                  $s .= "city = '" . trim($tmp) . "' OR state = '" . trim($tmp) . "' OR address = '" . trim($tmp) . "' ";
                  if (count($tmp_a2) != ($idx + 1))
                  $s .= " OR ";
                  ++$idx;
                  }
                 * 
                 */
                if ($s)
                  //  $tmp = "SELECT * FROM `company` WHERE (" . $s . ") AND description LIKE '%" . $_POST['ke'] . "%' ";
                      $tmp = "SELECT * FROM `company`";
                else
                  //  $tmp = "SELECT * FROM `company` WHERE description LIKE '%" . $_POST['ke'] . "%' ";
                      $tmp = "SELECT * FROM `company`";
                if ($_POST['co']) {
                    $where .= " AND country = '" . $_POST['co'] . "'";
                }

                $tmp .= $where;
                global $wpdb;

                if ($_POST['mc_fg'] == 'map') {
                    $query = '(SELECT a1.id AS comp_id, a1.user_id AS user_id, a1.companyname AS name, a1.user_role AS user_role, a1.description AS description, a2.lat AS lat, a2.lng AS lng, a1.logo_file_nm AS logo_file_nm FROM (SELECT * FROM (' . $tmp . ') AS a ';
                    if ($all_cids_str)
                        $query .= 'WHERE a.id IN (' . $all_cids_str . ')';
                    $query .= ') AS a1 INNER JOIN company_address AS a2 ON a1.id = a2.comp_id)';
                    $query .= ' UNION ';
                    $query .= '(SELECT a.id AS comp_id, a.user_id AS user_id, a.companyname AS name, a.user_role AS user_role, a.description AS description, a.latitude AS lat, a.longitude AS lng, a.logo_file_nm AS logo_file_nm FROM (' . $tmp . ') AS a ';
                    if ($all_cids_str)
                        $query .= 'WHERE a.id IN (' . $all_cids_str . ')';
                    $query .= ')';
                    
                    
                } else {
                    $query = 'SELECT a.id AS comp_id, a.user_id AS user_id, a.companyname AS name, a.user_role AS user_role, a.description AS description, a.logo_file_nm AS logo_file_nm FROM (' . $tmp . ') AS a WHERE a.id ';
                    if ($all_cids_str)
                        $query .= ' IN (' . $all_cids_str . ') ';
                    $query .= ' ORDER BY a.user_role DESC';

                    $totalitems = $wpdb->query($query); //return the total number of affected rows
                    $perpage = get_option('posts_per_page');
                    $paged = get_query_var('paged');
                    if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
                        $paged = 1;
                    }

                //    totalpages = ceil($totalitems / $perpage);

                    if (!empty($paged) && !empty($perpage)) {
                        $offset = ($paged - 1) * $perpage;
                        $query .= ' LIMIT ' . (int) $offset . ',' . (int) $perpage;
                    }
                }
                $tmp_list_a = $wpdb->get_results($query);
            }
//echo $query;

            

            $result_a = array();
            $upload_dir = wp_upload_dir();
            $destination_url = $upload_dir["baseurl"] . "/comp_logo/";

            $rating_obj = new commentsMgnItlocation();
            $rating_a = $rating_obj->get_info();

            $idx = 0;
            if (count($tmp_list_a)) {
                foreach ($tmp_list_a as $val) {
                    $user = new WP_User($val->user_id);
                    
                    $distance = distance(floatval($current_pos['lat']),floatval($current_pos['lng']),floatval($val->lat),floatval($val->lng));
                     
                    if ($user->roles[0] != 'administrator') {
                        if ($_POST['mc_fg'] == 'map' ) {
                            
                            if(floatval($distance) < 25){
                            $result_a[$idx]['id'] = $val->user_id;
                            $result_a[$idx]['user_role'] = $val->user_role;
                            $result_a[$idx]['name'] = $val->name;
                            if ($result_a[$idx]['user_role'] != '0')
                                $result_a[$idx]['description'] = stripslashes(substr($val->description, 0, 300));

                            $result_a[$idx]['address'] = $val->address;
                            $result_a[$idx]['x'] = $val->lat;
                            $result_a[$idx]['y'] = $val->lng;

                            if ($result_a[$idx]['user_role'] != '0') {
                                if ($val->logo_file_nm)
                                    $result_a[$idx]['logo_url'] = $destination_url . $val->logo_file_nm;
                                else
                                    $result_a[$idx]['logo_url'] = 'http://www.placehold.it/100x100/EFEFEF/AAAAAA&text=no+image';
                            }
                            
                            $result_a[$idx]['permalink'] = get_author_posts_url($val->user_id);

                            $rating_fg = get_default_member_limit('rating', $result_a[$idx]['user_role']);

                                if ($rating_fg)
                                    $result_a[$idx]['rating'] = $rating_a[$val->comp_id];
                                else
                                    $result_a[$idx]['rating'] = 'no';
                                
                             ++$idx;
                             
                            }    
                                
                        } 
                    }
                }
            }
            echo json_encode($result_a);

        
  exit;
?>
