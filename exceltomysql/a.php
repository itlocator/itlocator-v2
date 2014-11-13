<?php
	error_reporting(E_ALL);
	set_time_limit(0);
	date_default_timezone_set('Europe/London');
	
	$link = mysql_connect('itlprod.db.10859317.hostedresource.com', 'itlprod', 'ITL0cat0r01!');
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	
	$rv = mysql_select_db( 'itlprod', $link );
	
	// $createSql = "
		// CREATE TABLE IF NOT EXISTS `international` (
			// `id` INT NULL AUTO_INCREMENT,
			// `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `url` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `industries` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `address` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `address2` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `pc` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `state` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `country` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,			
			// `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `name2` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
			// `latitude` double NULL,
			// `longitude` double NULL,			
			// PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
	// ";
	// mysql_query( $createSql );
	
	$step = 3;
	
	if( $step == 1 ) {
		require_once 'reader.php';
		$data = new Spreadsheet_Excel_Reader();
		
		$data->read('a1-6.xls');
		$insertQuery = "INSERT INTO `alldata`(`name`, `url`, `industries`, `phone`, `address`, `address2`, `city`, `pc`, `country`, `description`, `latitude`, `longitude`) VALUES ";
		$firstflag = true;
		for( $i = 2; $i <= 4457; $i++ ) {
		// for( $i = 2; $i <= 5000; $i++ ) {
			if( !$firstflag ) { $insertQuery .= ","; }
			$insertQuery .= "('" . 
			( isset( $data->sheets[0]['cells'][$i][1] ) ? $data->sheets[0]['cells'][$i][1] : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][2] ) ? $data->sheets[0]['cells'][$i][2] : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][3] ) ? $data->sheets[0]['cells'][$i][3] : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][4] ) ? $data->sheets[0]['cells'][$i][4] : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][5] ) ? $data->sheets[0]['cells'][$i][5] : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][6] ) ? $data->sheets[0]['cells'][$i][6] : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][7] ) ? $data->sheets[0]['cells'][$i][7] : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][8] ) ? $data->sheets[0]['cells'][$i][8] : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][9] ) ? $data->sheets[0]['cells'][$i][9] : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][10] ) ? $data->sheets[0]['cells'][$i][10] : "NULL" ) ."'," . 
			( isset( $data->sheets[0]['cells'][$i][11] ) ? $data->sheets[0]['cells'][$i][11] : "NULL" ) ."," . 
			( isset( $data->sheets[0]['cells'][$i][12] ) ? $data->sheets[0]['cells'][$i][12] : "NULL" ) .")";
			$firstflag = false;
		}
		mysql_query( $insertQuery );
	} else if( $step == 2) {
		include 'Classes/PHPExcel/IOFactory.php';
		$inputFileType = 'Excel5';
		$inputFileName = 'a2-remove.xls';
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);	
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$insertQuery = "INSERT INTO `china_russia`(`name`, `url`, `industries`, `phone`, `address`, `address2`, `city`, `pc`, `country`, `description`, `latitude`, `longitude`) VALUES ";
		$firstflag = true;
		$a = 0;
		foreach( $sheetData as $data ){
			if( $a == 0 ) {
				$a = 1;
				continue;
			}
			if( !$firstflag ) { $insertQuery .= ","; }
			$insertQuery .= "('" . 
			( isset( $data["A"] ) ? $data["A"] : "NULL" ) ."','" . 
			( isset( $data["B"] ) ? $data["B"] : "NULL" ) ."','" . 
			( isset( $data["C"] ) ? $data["C"] : "NULL" ) ."','" . 
			( isset( $data["D"] ) ? $data["D"] : "NULL" ) ."','" . 
			( isset( $data["E"] ) ? $data["E"] : "NULL" ) ."','" . 
			( isset( $data["F"] ) ? $data["F"] : "NULL" ) ."','" . 
			( isset( $data["G"] ) ? $data["G"] : "NULL" ) ."','" . 
			( isset( $data["H"] ) ? $data["H"] : "NULL" ) ."','" . 
			( isset( $data["I"] ) ? $data["I"] : "NULL" ) ."','" . 
			( isset( $data["J"] ) ? $data["J"] : "NULL" ) ."'," . 
			( isset( $data["K"] ) ? $data["K"] : "NULL" ) ."," . 
			( isset( $data["L"] ) ? $data["L"] : "NULL" ) .")";
			$firstflag = false;
		}
		
		$File = "china_russia.sql"; 
		$Handle = fopen($File, 'w');
		fwrite($Handle, $insertQuery); 
		fclose($Handle);
		
		$result = mysql_query( $insertQuery );
		
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $insertQuery;
			die($message);
		}
	} else {
		require_once 'reader.php';
		$data = new Spreadsheet_Excel_Reader();
		
		$data->read('a3.xls');
		$insertQuery = "INSERT INTO `alldata`(`name`, `url`, `industries`, `phone`, `address`, `address2`, `city`, `pc`, `state`, `country`, `description`, `name2`, `title`, `email`, `latitude`, `longitude`) VALUES ";
		$firstflag = true;
		for( $i = 2; $i <= 2723; $i++ ) {
			if( !$firstflag ) { $insertQuery .= ","; }
			$insertQuery .= "('" . 
			( isset( $data->sheets[0]['cells'][$i][1] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][1] ) : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][2] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][2] ) : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][3] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][3] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][4] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][4] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][5] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][5] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][6] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][6] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][7] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][7] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][8] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][8] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][9] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][9] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][10] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][10] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][11] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][11] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][12] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][12] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][13] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][13] )  : "NULL" ) ."','" . 
			( isset( $data->sheets[0]['cells'][$i][14] ) ? mysql_real_escape_string ( $data->sheets[0]['cells'][$i][14] )  : "NULL" ) ."'," . 
			( isset( $data->sheets[0]['cells'][$i][15] ) ? $data->sheets[0]['cells'][$i][15] : "NULL" ) ."," . 
			( isset( $data->sheets[0]['cells'][$i][16] ) ? $data->sheets[0]['cells'][$i][16] : "NULL" ) .")";
			$firstflag = false;
		}
		$result = mysql_query( $insertQuery );
		
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $insertQuery;
			die($message);
		}
	}
	
	// $File = "industry2.sql"; 
	// $Handle = fopen($File, 'w');
	// fwrite($Handle, $insertQuery); 
	// fclose($Handle);
	
	echo '<h1>OK</h1>';
	
	mysql_close($link);
?>