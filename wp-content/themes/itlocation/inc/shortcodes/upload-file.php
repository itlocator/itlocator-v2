<?php

include("../../../../../wp-config.php");

if (!is_user_logged_in()):
    wp_redirect(get_site_url());
endif;

$company_model = new companyModelItlocation();
$company_info = $company_model->get_by_id($_REQUEST['comp_id']);
$comp_file_mgn_model_itlocation = new compFileMgnModelItlocation();

$tmp_a = explode(',', $_REQUEST['default']);
foreach ($tmp_a as $tmp) {
    $default[] = trim($tmp);
}

global $functions_ph;
$file_num = $functions_ph->get_default_member_limit($_REQUEST['file_type'], $company_info->user_role);

$json_a = array();
if ($file_num != '0') {
    $file_infos = $comp_file_mgn_model_itlocation->get_list_by_comp_id($company_info->id, $_REQUEST['file_type'], 'true');

    if ($file_num == '-1') {
        $json_a = file_upload_itlocation($company_info, $comp_file_mgn_model_itlocation);
    } else {
        if (count($file_infos) < $file_num) {
            $json_a = file_upload_itlocation($company_info, $comp_file_mgn_model_itlocation);
        } else {
            $json_a['error'] = "file number limit";
        }
    }
} else {
    $json_a['error'] = "you can't upload file.";
}
echo json_encode($json_a);

function file_upload_itlocation($company_info, $comp_file_mgn_model_itlocation) {

    $error = '';
    if (isset($_POST['uploadfile']) && $_POST['uploadfile'] == '') {
        
    } elseif ($_FILES['uploadfile']['error'] == 0) {
        $upload_dir = wp_upload_dir();

        $destination_path = $upload_dir["basedir"] . "/comp_files";
        if (!file_exists($destination_path))
            mkdir($destination_path, 0777);

        $destination_path = $upload_dir["basedir"] . "/comp_files/" . $company_info->id;
        if (!file_exists($destination_path))
            mkdir($destination_path, 0777);

        $fname = $_FILES['uploadfile']['name'];
        $real_fname = mktime() . "_" . $fname;
        $filetype = wp_check_filetype(basename($fname), null);
        $fsize_limit = 1024;
        if (get_option('itlocation_generals_comp_file_size_limit')) {
            $fsize_limit = get_option('itlocation_generals_comp_file_size_limit');
        }
        $fsize = round($_FILES['uploadfile']['size'] / 1024.0);

        if ($fsize_limit > $fsize) {
            $destination_file = $destination_path . '/' . $real_fname;
            if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $destination_file)) {
                $info['comp_id'] = $company_info->id;
                $info['filename'] = $fname;
                $info['real_filename'] = $real_fname;
                $info['extension'] = $filetype['ext'];
                $info['filesize'] = $fsize;
                $info['filetype'] = $_REQUEST['file_type'];
                $id = $comp_file_mgn_model_itlocation->insert($info);
            } else {
                $error = "file upload error";
            }
        } else {
            $error = "file size limit";
        }
    }

    $tmp_a = array(
        'time' => time(),
        'error' => $error,
        'last_id' => $id
    );

    return $tmp_a;
}

?>
