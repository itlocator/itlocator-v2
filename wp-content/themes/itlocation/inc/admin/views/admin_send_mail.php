<?php
if ($_POST['submit']) {
    $obj = new admin_Sent_Mail_List_Admin();
    $info['subject'] = $_POST['subject'];
    $info['content'] = $_POST['content'];
    $recipient = '';
    if (is_array($_POST['recipient'])) {
        $recipient = implode(',', $_POST['recipient']);
        $info['recipient'] = $recipient;
    }
    $upload_dir = wp_upload_dir();
    $destination_path = $upload_dir["basedir"] . "/admin_mail_attached_files/";
    if (!file_exists($destination_path))
        mkdir($destination_path, 0777);

    if ($info['subject'] && $info['content'] && $recipient) {
        $last_id = $obj->insert($info);

        $uploadfiles = $_FILES['attached_file'];
        if (is_array($uploadfiles)) {
            foreach ($uploadfiles['name'] as $key => $value) {
                $info = array();
                $info['pid'] = $last_id;
                if ($uploadfiles['error'][$key] == 0) {
                    $fname = $uploadfiles['name'][$key];
                    global $functions_ph;

                    $real_fname = $functions_ph->check_file_nm($destination_path, $fname);
                    $tmp_a = wp_check_filetype(basename($fname), null);
                    $info['filename'] = $fname;
                    $info['real_filename'] = $real_fname['basename'];
                    $info['filetype'] = $tmp_a['type'];
                    $info['filesize'] = round($uploadfiles['size'][$key] / 1024.0);

                    $destination_file = $destination_path . $real_fname['basename'];
                    if (move_uploaded_file($uploadfiles['tmp_name'][$key], $destination_file)) {
                        $f_obj = new fileMgnModelItlocation('admin_mail_attached_files');
                        $f_obj->insert($info);
                    }
                }
            }
        }
        ?>
        <script>
            window.location = 'admin.php?page=subscribers_sent_mail_list_itlocation';
        </script>
        <?php
    }

    $errors = array();
    if (!$_POST['subject']) {
        $errors[] = 'Please insert subject.';
    }
    if (!$_POST['content']) {
        $errors[] = 'Please insert content.';
    }
    if (!$recipient) {
        $errors[] = 'Please check recipients.';
    }
}
?>
<div class="wrap">
    <div id="icon-upload" class="icon32"><br></div>
    <h2>Send Mail</h2>
    <?php
    if (count($errors)) {
        ?>
        <div class="error fade"><p><em>
                    <?php
                    foreach ($errors as $error) {
                        echo $error . '<br/>';
                    }
                    ?>
                </em></p></div>
        <?php
    }
    ?>
    <form method="post" id="subscribers_send_mail_form" action="" enctype="multipart/form-data">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="subject">Subject</label></th>
                    <td><input type="text" placeholder="Subject" class="regular-text" name="subject" id="subject" value="<?php echo $_POST['subject']; ?>"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="content">Content</label></th>
                    <td><textarea rows="12" cols="75" name="content" id="content"><?php echo $_POST['content']; ?></textarea></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="user_email">Files</label></th>
                    <td>
                        <div id="upload_files">
                            <div><input type="file" name="attached_file[]"> <a href="#" class="delete_file">Delete</a></div>
                        </div>
                        <input type="button" class="button-secondary" id="addmore_file" name="addmore" value="Add More Files">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="recipient">Recipients</label></th>
                    <td>
                        <?php
                        global $wpdb;
                        $wp_list_table = new Subscribes_Admin_List_Table();
                        $query = $wp_list_table->get_sql_totalitems('confirm');
                        $totalitems1 = $wpdb->query($query);

                        $wp_list_table = new Companies_Admin_List_Table();
                        $query = $wp_list_table->get_sql_totalitems();
                        $totalitems2 = $wpdb->query($query);
                        $totalitems = $totalitems1 + $totalitems2;
                        ?>
                        <label><input type="checkbox" name="recipient[]" value="subscribers" /> Subscribers (<?php echo $totalitems1; ?>)</label><br/>
                        <?php
                        $query = $wp_list_table->get_sql_totalitems('listings');
                        $totalitems = $wpdb->query($query);
                        ?>
                        <label><input type="checkbox" name="recipient[]" value="listings" /> Listings (<?php echo $totalitems; ?>)</label><br/>
                        <?php
                        $query = $wp_list_table->get_sql_totalitems('members');
                        $totalitems = $wpdb->query($query);
                        ?>
                        <label><input type="checkbox" name="recipient[]" value="members" /> Members (<?php echo $totalitems; ?>)</label><br/>
                        <?php
                        $query = $wp_list_table->get_sql_totalitems('platinums');
                        $totalitems = $wpdb->query($query);
                        ?>
                        <label><input type="checkbox" name="recipient[]" value="platinums" /> Platinums (<?php echo $totalitems; ?>)</label><br/>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Send"></p>
    </form>
    <div id="upload_files_tmp" style="display: none"><div><input type="file" name="attached_file[]"> <a href="#" class="delete_file">Delete</a></div></div>
</div>