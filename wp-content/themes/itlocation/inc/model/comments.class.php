<?php

if (!class_exists("commentsMgnItlocation")):

    class commentsMgnItlocation {

        private $tb_nm;
        private $_wpdb;

        function __construct($tablename = "company_comments") {
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->tb_nm = $tablename;
            $query = "
                CREATE TABLE IF NOT EXISTS `" . $this->tb_nm . "` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `cid` bigint(20) NOT NULL,
                    `cnm` varchar(100) NOT NULL,
                    `ip` varchar(100) NOT NULL,
                    `rating` float NOT NULL,
                    `email` varchar(100) NOT NULL,
                    `name` varchar(100) NOT NULL,
                    `comment` text NOT NULL,
                    `uid` bigint(20) NOT NULL,
                    `date_registered` datetime NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
            ";
            $this->_wpdb->query($query);
        }

        function insert($info) {
            $last_id = 0;
            if (trim($info['rating']) && trim($info['cid'])) {
                global $functions_ph;
                $company_model = new companyModelItlocation();
                $tmp_obj = $company_model->get_by_id($info['cid']);
                $info['cnm'] = $tmp_obj->companyname;
                $query = "INSERT INTO " . $this->tb_nm . " SET ip='" . $functions_ph->get_client_ip() . "', date_registered='" . date('Y-m-d H:i:s') . "'";

                if (count($info)) {
                    foreach ($info as $key => $val) {
                        $query .= "," . $key . "='" . $val . "'";
                    }
                }
                mysql_query($query);
                $last_id = mysql_insert_id();
            }
            return $last_id;
        }

        function get_info() {
            $query = "SELECT cid, AVG(rating) AS rating FROM {$this->tb_nm} GROUP BY cid ORDER BY date_registered DESC";
            $tmp_a = $this->_wpdb->get_results($query);

            $ret_a = array();
            if (count($tmp_a)) {
                foreach ($tmp_a as $tmp) {
                    $ret_a[$tmp->cid] = $tmp->rating;
                }
            }
            return $ret_a;
        }

        function get_info_by_cid($cid, $paged = 0, $perpage = 0) {
            $query = 'SELECT * FROM ' . $this->tb_nm . ' WHERE cid=' . $cid;
            $query .= ' ORDER BY date_registered DESC ';
            if ($paged && $perpage) {
                $offset = ($paged - 1) * $perpage;
                $query .= ' LIMIT ' . (int) $offset . ',' . (int) $perpage;
            }
            return $this->_wpdb->get_results($query);
        }

        function check_email($cid, $email) {
            $query = "SELECT * FROM {$this->tb_nm} WHERE cid=" . $cid . " AND email='" . $email . "'";
            $tmp_a = $this->_wpdb->get_results($query);
            return count($tmp_a);
        }

        function del_by_id($id) {
            $query = "DELETE FROM {$this->tb_nm} WHERE id=" . $id;
            mysql_query($query);
        }

        function del_by_cid($cid) {
            $query = "DELETE FROM {$this->tb_nm} WHERE cid=" . $cid;
            mysql_query($query);
        }

    }

    new commentsMgnItlocation();

endif;
?>
