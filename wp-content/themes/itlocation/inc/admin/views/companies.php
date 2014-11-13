<div class="wrap">
    <div id="icon-upload" class="icon32"><br></div>
    <?php
    if ($_GET['cid']) {
        ?>
        <h2>Edit Company <a href="admin.php?page=company_mgn_new_itlocation" class="add-new-h2">Add New</a></h2>
        <?php
        include( get_template_directory() . '/inc/admin/views/company_edit.php' );
    } else {
        ?>
        <h2>Companies <a href="admin.php?page=company_mgn_new_itlocation" class="add-new-h2">Add New</a></h2>
        <?php
        if (isset($_GET['deleted'])) {
            ?>
            <div id="message" class="updated below-h2">
                <p><?php echo $_GET['deleted']; ?> items permanently deleted.</p>
            </div>
            <?php
        }
        $wp_list_table = new Companies_Admin_List_Table();
        $wp_list_table->prepare_items();
        ?>
        <form id="companies-table" method="get" action="">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <?php
            $wp_list_table->search_box('search', 'search_id');
            wp_nonce_field('delete-company-admin-itlocation', 'delete-company-admin-itlocation-security');

            $wp_list_table->views();
            $wp_list_table->display();
            ?>
        </form>
        <?php
    }
    ?>
</div>
