<?php

if (!class_exists("subscribeMgnItlocation")):

    class subscribeMgnItlocation {

        private $tb_nm;
        private $_wpdb;

        function __construct($tablename = "subscribers") {
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->tb_nm = $tablename;
            $query = "
                CREATE TABLE IF NOT EXISTS `" . $this->tb_nm . "` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `email` varchar(100) NOT NULL,
                    `confirm_key` bigint(20) NOT NULL,
                    `date_registered` datetime NOT NULL,
                    `date_confirmed` datetime NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `email` (`email`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
            ";
            $this->_wpdb->query($query);
        }

        function insert($info) {
            $last_id = 0;
            $confirm_url = '';
            if (trim($info['email'])) {
                $time_s = time();
                $query = "INSERT INTO {$this->tb_nm} SET email = '" . trim($info['email']) . "', confirm_key=" . $time_s . ", date_registered='" . date('Y-m-d H:i:s') . "'";
                mysql_query($query);
                $last_id = mysql_insert_id();
                $confirm_url = get_site_url() . '?subscriber=' . $time_s;
            }
            return $confirm_url;
        }

        function del_by_id($id) {
            $query = "DELETE FROM {$this->tb_nm} WHERE id=" . $id;
            mysql_query($query);
        }

        function get_info_by_status($status) {
            if (!$status) {
                $query = "SELECT * FROM " . $this->tb_nm;
            } elseif ($status == 'confirm') {
                $query = "SELECT * FROM " . $this->tb_nm . " WHERE confirm_key = 0";
            } elseif ($status == 'unconfirm') {
                $query = "SELECT * FROM " . $this->tb_nm . " WHERE confirm_key != 0";
            }

            return $this->_wpdb->get_results($query);
        }

        function email_exists($email) {
            global $wpdb;
            $query = "SELECT * FROM {$this->tb_nm} WHERE email='" . $email . "'";
            $totalitems = $wpdb->query($query);
            return $totalitems;
        }

        function update_key($key) {
            $query = "SELECT * FROM " . $this->tb_nm . " WHERE confirm_key = " . $key;
            $info = $this->_wpdb->get_results($query);

            $query = "UPDATE " . $this->tb_nm . " SET confirm_key = 0, date_confirmed='" . date('Y-m-d H:i:s') . "' WHERE confirm_key = " . $key;
            mysql_query($query);
            return $info[0];
        }

    }

    new subscribeMgnItlocation();

endif;
?>
