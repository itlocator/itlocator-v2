<?php

if (!class_exists("companyModelItlocation")):

    class companyModelItlocation extends addressModelItlocation {

        private $tb_nm;
        private $_wpdb;

        function __construct($tablename = "company") {
            parent::__construct();

            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->tb_nm = $tablename;
            $query = "
                CREATE TABLE IF NOT EXISTS `" . $this->tb_nm . "` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `user_id` bigint(20) NOT NULL,
                    `user_role` tinyint(1) NOT NULL,
                    `companyname` varchar(100) DEFAULT NULL,
                    `firstname` varchar(100) DEFAULT NULL,
                    `lastname` varchar(100) DEFAULT NULL,
                    `contactemail` varchar(100) DEFAULT NULL,
                    `backupemail` varchar(100) DEFAULT NULL,
                    `phoneprim` varchar(60) DEFAULT NULL,
                    `phonescond` varchar(60) DEFAULT NULL,
                    `companyurl` varchar(200) DEFAULT NULL,
                    `address1` varchar(200) DEFAULT NULL,
                    `address2` varchar(200) DEFAULT NULL,
                    `city` varchar(60) DEFAULT NULL,
                    `zip_code` varchar(20) DEFAULT NULL,
                    `state` varchar(200) DEFAULT NULL,
                    `country` varchar(10) DEFAULT NULL,
                    `address` varchar(400) DEFAULT NULL,
                    `isascii` tinyint(1) NOT NULL DEFAULT '1',
                    `time_zone` varchar(20) DEFAULT NULL,
                    `description` text DEFAULT NULL,
                    `latitude` varchar(64) NOT NULL,
                    `longitude` varchar(64) NOT NULL,
                    `twitter` varchar(200) DEFAULT NULL,
                    `linkedin` varchar(200) DEFAULT NULL,
                    `googleplus` varchar(200) DEFAULT NULL,
                    `facebook` varchar(200) DEFAULT NULL,
                    `logo_file_nm` varchar(150) DEFAULT NULL,
                    `auto_renew` tinyint(1) NOT NULL DEFAULT '1',
                    `register_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `renew_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                     `content_updates` ENUM(  '0',  '1' ) NOT NULL DEFAULT  '1' ,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `user_id` (`user_id`),
                    UNIQUE KEY `contactemail` (`contactemail`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
            ";
            $this->_wpdb->query($query);
        }
		
		function insert( $user_id, $companyname, $contactemail, $source ){
			$currentDate = Date('Y-m-d h:m');
			$renew_date = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($currentDate)));
			$autoinsert_value=1;
			$query = "INSERT INTO {$this->tb_nm}(`user_id`, `autoinsert`, `companyname`, `source`, `contactemail`, `register_date`,`renew_date`) VALUES ( {$user_id}, {$autoinsert_value}, '{$companyname}', '{$source}', '{$contactemail}', '{$currentDate}'  , '{$renew_date}' )";
			 //echo $query;
			 //exit;
			mysql_query( $query );
		}
		
		function getMaxId(){
			$query = "SELECT MAX(`id`) as max_id FROM {$this->tb_nm}";
			$result = mysql_query( $query );
			if( $row = mysql_fetch_array( $result ) ){
				return $row['max_id'];
			}
			
			return 0;
		}
		
        function create_comp( $user_id, $company_name = '' ){
            $last_id = '';
            
			if( $user_id ){
                $user = new WP_User( $user_id );
                if( $user->roles[0] != 'administrator' ){
                    $query = "INSERT INTO {$this->tb_nm} SET user_id='" . $user_id . "', companyname='" . $company_name . "', register_date='" . date('Y-m-d H:i:s') . "'";
                    
					echo $query;
					exit;
					
					mysql_query( $query );
					
                    $last_id = mysql_insert_id();
                }
            }
			
            return $last_id;
        }
		
		function setCompanyClaim( $userId, $claim ){
			// echo "UPDATE {$this->tb_nm} SET claim={$claim} WHERE user_id={$userId}";
			mysql_query( "UPDATE {$this->tb_nm} SET claim={$claim} WHERE user_id={$userId}" );
		}
		
        function get_all() {
            $query = "SELECT * FROM {$this->tb_nm}";
            return $this->_wpdb->get_results($query);
        }

        function get_info_by_status($status) {
            if (!$status) {
                $query = "SELECT * FROM " . $this->tb_nm;
            } elseif ($status == 'listings') {
                $query = "SELECT * FROM " . $this->tb_nm . " WHERE user_role = 0";
            } elseif ($status == 'members') {
                $query = "SELECT * FROM " . $this->tb_nm . " WHERE user_role = 1";
            } elseif ($status == 'platinums') {
                $query = "SELECT * FROM " . $this->tb_nm . " WHERE user_role = 2";
            }
            return $this->_wpdb->get_results($query);
        }

        function update_role_by_uid($user_id) {
            $user = new WP_User($user_id);
            $role = 0;
            if ($user->roles[0] == 's2member_level1')
                $role = 1;
            if ($user->roles[0] == 's2member_level2')
                $role = 2;
            $query = "UPDATE {$this->tb_nm} SET `user_role`='" . $role . "' WHERE `user_id` = " . $user_id;
            mysql_query($query);
        }

        function get_by_user_id($user_id) {
            $query = "SELECT * FROM {$this->tb_nm} WHERE user_id = {$user_id}";
            $tmp_obj = $this->_wpdb->get_results($query);
            return $tmp_obj[0];
        }

        function get_by_id($id) {
            $query = "SELECT * FROM {$this->tb_nm} WHERE id = {$id}";
            $tmp_obj = $this->_wpdb->get_results($query);
            return $tmp_obj[0];
        }

        function get_a_comp_address_by_comp_id($comp_id) {
            $query = "SELECT a1.user_role AS user_role, a2.address AS address, a2.lat AS lat, a2.lng , a2.primary_address FROM " . $this->tb_nm . " AS a1," . parent::get_tb_nm() . " AS a2 WHERE a1.id = a2.comp_id AND a2.flag=1 AND a2.comp_id=" . $comp_id;
			
            return $this->_wpdb->get_results($query);
        }
		
		function get_a_comp_address_by_comp_id_not_primary($comp_id) {
            $query = "SELECT a1.user_role AS user_role, a2.address AS address, a2.lat AS lat, a2.lng FROM " . $this->tb_nm . " AS a1," . parent::get_tb_nm() . " AS a2 WHERE a1.id = a2.comp_id AND a2.flag=1 AND a2.comp_id=" . $comp_id . " AND primary_address=0";
			
            return $this->_wpdb->get_results($query);
        }
		
        function clear_all_company() {
            global $wpdb;
            $query = "SELECT a.id, a.user_id, a.uID FROM (SELECT a1.id AS id, a1.user_id AS user_id, a2.ID AS uID FROM " . $this->tb_nm . " AS a1 LEFT JOIN " . $wpdb->users . " AS a2 ON a1.user_id = a2.ID) AS a WHERE a.uID IS NULL";

            $objs = $this->_wpdb->get_results($query);

            foreach ($objs as $obj) {
                $this->del_by_id($obj->id);
            }
        }

        function clear_all_address() {
            $query = "SELECT a.id, a.comp_id FROM (SELECT a1.id AS id, a1.comp_id AS comp_id, a2.id AS cid FROM " . parent::get_tb_nm() . " AS a1 LEFT JOIN " . $this->tb_nm . " AS a2 ON a1.comp_id = a2.id) AS a WHERE a.cid IS NULL";

            $objs = $this->_wpdb->get_results($query);

            foreach ($objs as $obj) {
                parent::del_by_id($obj->id);
            }
            parent::all_clear();
        }

        function clear_my_address($cid) {
            //$tmp = $this->get_by_id($cid);
            //parent::my_clear($cid, $tmp[0]->user_role);
        }

        function update_by_id($id, $info) {
            $tmp = '';
            $idx = 0;
            $info['renew_date'] = date('Y-m-d H:i:s');
            //$info['register_date'] = date('Y-m-d H:i:s');

//echo $info['content_updates'];
            foreach ($info as $key => $value) {
                $dot = '';
                if ($idx != 0)
                    $dot = ',';
                $tmp .= $dot . $key . "= '" . trim(mysql_escape_string($value)) . "'";
                ++$idx;
            }

            if ($id) {
                $query = "UPDATE {$this->tb_nm} SET " . $tmp . " WHERE `id` = " . $id;
                mysql_query($query);
            }
        }

        function del_by_id($id) {
            $query = "DELETE FROM {$this->tb_nm} WHERE id=" . $id;
            mysql_query($query);
        }

        function del_by_user_id($uid) {
            $query = "DELETE FROM {$this->tb_nm} WHERE user_id=" . $uid;
            mysql_query($query);
        }
		
		function get_user_role( $comp_id ){
			$query = "SELECT user_role FROM {$this->tb_nm} WHERE id={$comp_id}";
			$result = mysql_query( $query );
			if( $row = mysql_fetch_array( $result ) ){
				return $row['user_role'];
			}
			
			return -1;
		}
		
		function set_user_role( $comp_id, $user_role ){
			$query = "UPDATE {$this->tb_nm} SET user_role={$user_role} WHERE id={$comp_id}";
			mysql_query( $query );
		}
    }

    global $current_user;
    get_currentuserinfo();

    $company_model = new companyModelItlocation();
    //$company_model->create_comp($current_user->ID);
    //$company_model->update_role_by_uid($current_user->ID);

    global $current_company;
    $current_company = $company_model->get_by_user_id($current_user->ID);

//$company_model->clear_my_address($current_company->id);

endif;
?>