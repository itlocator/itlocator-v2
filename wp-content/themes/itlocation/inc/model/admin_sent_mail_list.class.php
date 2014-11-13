<?php

if (!class_exists("admin_Sent_Mail_List_Admin")):

    class admin_Sent_Mail_List_Admin {

        private $tb_nm;
        private $_wpdb;

        function __construct($tablename = "admin_sent_mails") {
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->tb_nm = $tablename;
            $query = "
                CREATE TABLE IF NOT EXISTS `" . $this->tb_nm . "` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `subject` varchar(250) NOT NULL,
                    `content` longtext NOT NULL,
                    `recipient` varchar(100) NOT NULL,
                    `sent_flag` tinyint(1) NOT NULL DEFAULT '0',
                    `date_registered` datetime NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
            ";
            $this->_wpdb->query($query);
        }

        function insert($info) {
            $last_id = 0;
            $query = "INSERT INTO {$this->tb_nm} SET subject = '" . mysql_escape_string(trim($info['subject'])) . "', content = '" . mysql_escape_string(trim($info['content'])) . "', recipient = '" . $info['recipient'] . "', date_registered = '" . date('Y-m-d H:i:s') . "'";
            mysql_query($query);
            $last_id = mysql_insert_id();

            return $last_id;
        }

        function get_all() {
            $query = "SELECT * FROM {$this->tb_nm}";
            return $this->_wpdb->get_results($query);
        }

        function get_info_by_status($status) {
          
            if (!$status) {echo "hello if";
                $query = "SELECT * FROM " . $this->tb_nm;
            } elseif ($status == 'confirm') { echo "hello else";
                $query = "SELECT * FROM " . $this->tb_nm . " WHERE sent_flag = 0";
            }
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

    }

    new admin_Sent_Mail_List_Admin();
endif;
?>
