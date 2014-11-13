<?php

if (!class_exists("fileMgnModelItlocation")):

    class fileMgnModelItlocation extends DB_Base_PH {

        private $tb_nm;
        private $_wpdb;

        function __construct($tablename = "file_mgn") {
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->tb_nm = $tablename;
            parent::__construct($tablename);

            $query = "
                CREATE TABLE IF NOT EXISTS `" . $this->tb_nm . "` (
                    `id` bigint(20) NOT NULL auto_increment,
                    `pid` bigint(20) NOT NULL,
                    `filename` varchar(150) NOT NULL,
                    `real_filename` varchar(150) NOT NULL,
                    `filesize` int(11) NOT NULL,
                    `filetype` varchar(20) NOT NULL,
                    PRIMARY KEY  (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
            ";
            $this->_wpdb->query($query);
        }

        function get_list_by_pid($pid) {
            $query = "SELECT * FROM {$this->tb_nm} WHERE pid=" . $pid;
            return $this->_wpdb->get_results($query);
        }

    }

    new fileMgnModelItlocation();

endif;
?>
