<div class="wrap">
    <div id="icon-upload" class="icon32"><br></div>
    <h2>Sent Mails <a href="admin.php?page=subscribers_send_mail_itlocation" class="add-new-h2">New Mail</a></h2>
    <?php
    $wp_list_table = new sent_Mails_Admin_List_Table();
    $wp_list_table->prepare_items();
    ?>
    <form method="get" action="">
        <input type="hidden" name="page" value="subscribers_sent_mail_list_itlocation" />
        <?php
        $wp_list_table->search_box('search', 'search_id');
        ?>
    </form>
    <?php
    wp_nonce_field('delete-comments-admin-itlocation', 'delete-comments-admin-itlocation-security');
    $wp_list_table->display();
    ?>
</div>