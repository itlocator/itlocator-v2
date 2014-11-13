<div class="wrap">
    <div id="icon-upload" class="icon32"><br></div>
    <h2>Subscribers</h2>
    <?php
    if (isset($_GET['deleted'])) {
        ?>
        <div id="message" class="updated below-h2">
            <p><?php echo $_GET['deleted']; ?> items permanently deleted.</p>
        </div>
        <?php
    }
    $wp_list_table = new Subscribes_Admin_List_Table();
    $wp_list_table->prepare_items();
    ?>
    <form method="get" action="">
        <input type="hidden" name="page" value="subscribers_itlocation" />
        <input type="hidden" name="status" value="<?php echo $_GET['status'] ?>" />
        <?php
        $wp_list_table->search_box('search', 'search_id');
        wp_nonce_field('delete-subscribes-admin-itlocation', 'delete-subscribes-admin-itlocation-security');
        $wp_list_table->views();
        $wp_list_table->display();
        ?>
    </form>
</div>