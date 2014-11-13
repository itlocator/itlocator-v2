<?php

if (!class_exists("generalDataPHCustom")):

    class generalDataPHCustom extends DB_Base_PH {

        private $tb_nm;

        function __construct($tablename = "genaral_data") {
            $this->tb_nm = $tablename;
            parent::__construct($tablename);
            $query = "
                CREATE TABLE IF NOT EXISTS `" . $this->tb_nm . "` (
                    `id` bigint(20) NOT NULL AUTO_INCREMENT,
                    `name` varchar(64) NOT NULL,
                    `desc` text DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `name` (`name`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
            ";
            mysql_query($query);
        }

    }

    endif;
?>
