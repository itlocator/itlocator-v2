<?php

if (!class_exists("addressModelItlocation")):

    class addressModelItlocation {

        private $tb_nm;
        private $_wpdb;

        function __construct($tablename = "company") {
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->tb_nm = $tablename . "_address";
            $query = "
                CREATE TABLE IF NOT EXISTS `" . $this->tb_nm . "` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `comp_id` bigint(20) NOT NULL,
                    `address` varchar(64) NOT NULL,
                    `lat` varchar(64) NOT NULL,
                    `lng` varchar(64) NOT NULL,
                    `flag` tinyint(1) NOT NULL DEFAULT '1',
                    PRIMARY KEY (`id`),
                    KEY (`comp_id`,`address`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
            ";
            $this->_wpdb->query($query);
        }

        function get_tb_nm() {
            return $this->tb_nm;
        }

        function get_by_comp_id($comp_id, $flag = 1, $all = true ) {
			if( $all ) {
				$query = "SELECT * FROM {$this->tb_nm} WHERE comp_id = {$comp_id} AND flag = {$flag} ORDER BY id ASC";
			} else {
				$query = "SELECT * FROM {$this->tb_nm} WHERE comp_id = {$comp_id} AND flag = {$flag} AND primary_address=false ORDER BY id ASC";
			}
			
            return $this->_wpdb->get_results($query);
        }

        function get_all_by_comp_id($comp_id) {
            $query = "SELECT * FROM {$this->tb_nm} WHERE comp_id = {$comp_id} ORDER BY id ASC";
            return $this->_wpdb->get_results($query);
        }

        function get_all() {
            $query = "SELECT * FROM {$this->tb_nm}";
            return $this->_wpdb->get_results($query);
        }

        function new_insert($info) {
            $last_id = 0;
            if (trim($info['comp_id'])) {
                $query = "INSERT INTO {$this->tb_nm} SET comp_id = " . $info['comp_id'] . ", address = '" . mysql_escape_string(trim($info['address'])) . "', lat = '" . $info['lat'] . "', lng='" . $info['lng'] . "', flag=1, primary_address={$info['primary_address']}";
                mysql_query($query);
                $last_id = mysql_insert_id();
            }
            return $last_id;
        }

        function free_flag($comp_id) {
            if (trim($comp_id)) {
                $query = "UPDATE {$this->tb_nm} SET flag = 0 WHERE comp_id = " . $comp_id;
                mysql_query($query);
            }
        }

        function get_by_comp_id_address($comp_id, $address) {
            $query = "SELECT * FROM {$this->tb_nm} WHERE comp_id = {$comp_id} AND address = " . mysql_escape_string(trim($address));
            return $this->_wpdb->get_results($query);
        }

        function update($id, $info) {
            $tmp = '';
            $idx = 0;

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

        function del_by_comp_id($comp_id) {
            if ($comp_id)
                $query = "DELETE FROM {$this->tb_nm} WHERE comp_id=" . $comp_id;
            mysql_query($query);
        }

        function del_by_id($id) {
            if ($id)
                $query = "DELETE FROM {$this->tb_nm} WHERE id=" . $id;
            mysql_query($query);
        }

        function my_clear($cid, $role) {
            $objs = $this->get_by_comp_id($cid);
            $idx = 0;
            if ($role == 0) {
                if (count($objs) != 1) {
                    foreach ($objs as $obj) {
                        if ($idx == 0) {
                            $info['flag'] = 1;
                        } else {
                            $info['flag'] = 0;
                        }
                        $this->update($obj->id, $info);
                        ++$idx;
                    }
                }
            }
            $this->del_by_id($obj->id);

            if ($role == 1) {
                if (count($objs) > 3) {
                    foreach ($objs as $obj) {
                        if ($idx < 3) {
                            $info['flag'] = 1;
                            $this->update($obj->id, $info);
                        } else {
                            $this->del_by_id($obj->id);
                        }
                        ++$idx;
                    }
                }
            }
        }

        function all_clear() {
            
        }

    }

    new addressModelItlocation();
endif;
?>
