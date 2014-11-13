<?php

global $kind, $company_id, $tag, $class;

$services_model = new generaldataCompanyRelationshipsModelItlocation($kind);
$tmp_obj = $services_model->get_all_service_nm_by_compid($company_id);
if (count($tmp_obj)) {
    foreach ($tmp_obj as $obj) {
        echo '<' . $tag . ' class="info"> <i class="fa fa-check-circle-o"></i> ' . stripslashes($obj->name) . '</' . $tag . '>&nbsp;&nbsp;';
    }
}
?>
