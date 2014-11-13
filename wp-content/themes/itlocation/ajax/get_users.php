<?php

include '../../../../wp-config.php';

global $wpdb;
$sql = 'SELECT * FROM ' . $wpdb->users . ' WHERE user_email LIKE  "' . $_GET['q'] . '%"';
$tmp_a = $wpdb->get_results($sql);
if (count($tmp_a)) {
    foreach ($tmp_a as $tmp)
        $answer[] = array("id" => $tmp->ID, "text" => $tmp->user_email);
} else {
    $answer[] = array("id" => "0", "text" => "No Results Found...");
}
echo json_encode($answer);
?>
