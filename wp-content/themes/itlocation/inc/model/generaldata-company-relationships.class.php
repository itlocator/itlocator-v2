<?php

if (!class_exists("generaldataCompanyRelationshipsModelItlocation")):

    class generaldataCompanyRelationshipsModelItlocation extends generalDataPHCustom {

        private $tb_nm;
        private $p_tb_nm;
        private $_wpdb;

        function __construct($p_tb_nm = "services") {
            parent::__construct($p_tb_nm);

            $this->p_tb_nm = $p_tb_nm;

            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->tb_nm = $p_tb_nm . '_company_relationships';
            $query = "
                CREATE TABLE IF NOT EXISTS `" . $this->tb_nm . "` (
                    `comp_id` bigint(20) NOT NULL,
                    `service_id` bigint(20) NOT NULL,
                    `flag` tinyint(1) NOT NULL DEFAULT '1',
                    PRIMARY KEY (`comp_id`,`service_id`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
            ";
            $this->_wpdb->query($query);
        }

        function get_table_nm() {
            return $this->tb_nm;
        }

        function get_by_cid($cid, $flag = 1) {
            $query = "SELECT * FROM " . $this->tb_nm . " WHERE comp_id = " . $cid . " AND flag=" . $flag;
            return $this->_wpdb->get_results($query);
        }
        
        function get_all_by_cid($cid) {
            $query = "SELECT * FROM {$this->tb_nm} WHERE comp_id = {$cid} ORDER BY service_id ASC";
            return $this->_wpdb->get_results($query);
        }

        function del_by_comp_id($comp_id) {
            $query = "DELETE FROM {$this->tb_nm} WHERE comp_id=" . $comp_id;
            mysql_query($query);
        }

        function insert_by_cid_sid($comp_id, $service_id) {
            if ($comp_id && $service_id) {
                $query = "INSERT INTO {$this->tb_nm} SET comp_id=" . $comp_id . ", service_id=" . $service_id;
                $this->_wpdb->query($query);
            }
        }

        function get_all_list() {
            return parent::get_all();
        }

        function get_all_service_nm_by_compid($comp_id) {
            $query = "SELECT ser.id AS id, ser.name AS name FROM (SELECT comp_id, service_id FROM " . $this->tb_nm . " WHERE comp_id=" . $comp_id . " AND flag=1) AS rel INNER JOIN (SELECT id, name FROM " . $this->p_tb_nm . ") AS ser ON rel.service_id = ser.id GROUP BY ser.name";
            return $this->_wpdb->get_results($query);
        }

        function delete_by_comp_id($id) {
            $query = "DELETE FROM {$this->tb_nm} WHERE comp_id='" . $id . "' AND flag=1";
            return $this->_wpdb->query($query);
        }

        function update_flag($cid, $sid, $flag) {
            $query = "UPDATE {$this->tb_nm} SET `flag`='" . $flag . "' WHERE `comp_id` = " . $cid . " AND `service_id` = " . $sid;
            mysql_query($query);
        }

    }

    endif;
?>
