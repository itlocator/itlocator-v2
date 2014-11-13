<div class="wrap">
    <div id="icon-upload" class="icon32"><br></div>
    <h2>Comments</h2>
    <?php
    $wp_list_table = new Comments_Admin_List_Table();
    $wp_list_table->prepare_items();
    ?>
    <form method="get" action="">
        <input type="hidden" name="page" value="comments_itlocation" />
        <?php
        $wp_list_table->search_box('search', 'search_id');
        ?>
    </form>
    <?php
    wp_nonce_field('delete-comments-admin-itlocation', 'delete-comments-admin-itlocation-security');
    $wp_list_table->display();
    ?>
</div>