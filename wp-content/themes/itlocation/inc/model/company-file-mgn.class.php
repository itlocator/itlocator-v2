<?php

if (!class_exists("compFileMgnModelItlocation")):

    class compFileMgnModelItlocation extends DB_Base_PH {

//put your code here
        private $tb_nm;
        private $_wpdb;

        function __construct($tablename = "company_file_mgn") {
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->tb_nm = $tablename;
            parent::__construct($tablename);

            $query = "
                CREATE TABLE IF NOT EXISTS `" . $this->tb_nm . "` (
                    `id` bigint(20) NOT NULL auto_increment,
                    `comp_id` bigint(20) NOT NULL,
                    `filename` varchar(150) NOT NULL,
                    `real_filename` varchar(150) NOT NULL,
                    `title` varchar(100) NOT NULL,
                    `description` text NOT NULL,
                    `extension` varchar(8) NOT NULL,
                    `filesize` int(11) NOT NULL,
                    `filetype` varchar(20) NOT NULL,
                    `flag` tinyint(1) NOT NULL DEFAULT '1',
                    PRIMARY KEY  (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
            ";
            $this->_wpdb->query($query);
        }

        function get_info_by_id($id) {
            $query = "SELECT * FROM {$this->tb_nm} WHERE id={$id}";
            $tmp_obj = $this->_wpdb->get_results($query);
            return $tmp_obj[0];
        }

        function get_list_by_comp_id($comp_id, $file_type = '', $flag = '') {
            if (trim($file_type))
                $query = "SELECT * FROM {$this->tb_nm} WHERE comp_id={$comp_id} AND filetype='{$file_type}'";
            else
                $query = "SELECT * FROM {$this->tb_nm} WHERE comp_id={$comp_id}";

            if ($flag == 'true')
                $query .= ' AND flag=1';
            if ($flag == 'false')
                $query .= ' AND flag=0';

            return $this->_wpdb->get_results($query);
        }

        function del($id) {
            $query = "DELETE FROM {$this->tb_nm} WHERE id='" . $id . "'";
            return $this->_wpdb->query($query);
        }

        function update_flag($id, $flag) {
            $query = "UPDATE {$this->tb_nm} SET `flag`='" . $flag . "' WHERE `id` = " . $id;
            mysql_query($query);
        }

        /*
          function insert($info) {
          $query = "INSERT INTO {$this->tb_nm} SET ";
          $query .= "comp_id='" . $info['comp_id'] . "'";

          $query .= ",filename='" . mysql_escape_string($info['filename']) . "'";
          $query .= ",real_filename='" . mysql_escape_string($info['real_filename']) . "'";

          if ($info['title'])
          $query .= ",title='" . trim(mysql_escape_string($info['title'])) . "'";
          if ($info['description']) {
          $info['description'] = substr($info['description'], 0, 300);
          $query .= ",description='" . mysql_escape_string($info['description']) . "'";
          }

          if ($info['filetype']) {
          $query .= ",filetype='" . $info['filetype'] . "'";
          }

          $query .= ",extension='" . $info['extension'] . "'";
          $query .= ",filesize='" . $info['filesize'] . "'";

          mysql_query($query);
          return mysql_insert_id();
          }

          function update($id, $info) {
          $tmp = '';
          if ($info['comp_id'])
          $tmp .= "comp_id = " . $info['comp_id'];

          if ($info['filename'] != '') {
          if ($tmp)
          $dot = ',';
          $tmp .= $dot . "filename = '" . mysql_escape_string($info['filename']) . "'";
          }

          if ($info['real_filename'] != '') {
          if ($tmp)
          $dot = ',';
          $tmp .= $dot . "real_filename = '" . mysql_escape_string($info['real_filename']) . "'";
          }

          if ($info['title'] != '') {
          if ($tmp)
          $dot = ',';
          $tmp .= $dot . "title = '" . trim(mysql_escape_string($info['title'])) . "'";
          }

          if ($info['description'] != '') {
          if ($tmp)
          $dot = ',';
          $tmp .= $dot . "description = '" . mysql_escape_string($info['description']) . "'";
          }

          if ($info['extension'] != '') {
          if ($tmp)
          $dot = ',';
          $tmp .= $dot . "extension = '" . $info['extension'] . "'";
          }

          if ($info['filesize'] != '') {
          if ($tmp)
          $dot = ',';
          $tmp .= $dot . "filesize = " . $info['filesize'];
          }

          if ($info['filetype'] != '') {
          if ($tmp)
          $dot = ',';
          $tmp .= $dot . "filetype = '" . $info['filetype'] . "'";
          }

          if ($id) {
          $query = "UPDATE {$this->tb_nm} SET " . $tmp . " WHERE `id` = " . $id;
          mysql_query($query);
          }
          }
         * 
         */
    }

    global $comp_file_mgn_model_itlocation;

    $comp_file_mgn_model_itlocation = new compFileMgnModelItlocation();

endif;
?>
