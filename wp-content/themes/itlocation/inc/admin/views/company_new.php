<?php
if ($_POST['submit']) {
    $user_login = trim(sanitize_user($_POST['user_login']));
    $user_email = trim($_POST['user_email']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $full_name = $first_name . ' ' . $last_name;

    $errors = array();
    if (!$first_name)
        $errors[] = 'Please enter a first name.';
    if (!$last_name)
        $errors[] = 'Please enter a last name.';

    if (!$user_login)
        $errors[] = 'Please enter a Login Name.';
    elseif (!validate_username($user_login))
        $errors[] = 'This Login Name is invalid because it uses illegal characters. Please enter a valid Login Name.';
    elseif (username_exists($user_login))
        $errors[] = 'This Login Name is already registered, please choose another one';

    if (!$user_email)
        $errors[] = 'Please enter a Email';
    elseif (!is_email($user_email))
        $errors[] = 'The Email address isn’t correct.';
    elseif (email_exists($user_email))
        $errors[] = 'This Email is already registered, please choose another one.';

    if (!count($errors)) {
        $user_pass = wp_generate_password(12, false);
        $sanitized_user_login = sanitize_user($user_login);
        $user_id = wp_create_user($sanitized_user_login, $user_pass, $user_email);
        update_user_meta($user_id, 'first_name', $_POST['first_name']);
        update_user_meta($user_id, 'last_name', $_POST['last_name']);

        $search_array = array('[#name#]', '[#username#]', '[#email#]', '[#password#]');
        $replace_array = array($full_name, $user_login, $user_email, $user_pass);

        $title = 'Welcome';
        if (get_option('itlocation_email_settings_reg_user_email_t_for_user_in_admin'))
            $title = stripslashes(get_option('itlocation_email_settings_reg_user_email_t_for_user_in_admin'));

        $content = 'You register by Admin';
        if (get_option('itlocation_email_settings_reg_user_email_c_for_user_in_admin'))
            $content = stripslashes(get_option('itlocation_email_settings_reg_user_email_c_for_user_in_admin'));

        $content = str_replace($search_array, $replace_array, $content);

        global $functions_ph;
        $functions_ph->send_mail($user_email, '', $title, $content);
        ?>
        <script>
            window.location = 'admin.php?page=company_mgn_new_itlocation';
        </script>
        <?php
    }
}
?>

<div class="wrap">
    <div id="icon-upload" class="icon32"><br></div>
    <h2><?php echo ($_GET['id']) ? 'Update Company' : 'New Company'; ?></h2>
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
    <form method="post">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="first_name">First name</label></th>
                    <td><input type="text" placeholder="First name" class="regular-text" name="first_name" id="first_name" value="<?php echo $_POST['first_name'] ?>"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="last_name">Last name</label></th>
                    <td><input type="text" placeholder="Last name" class="regular-text" name="last_name" id="last_name" value="<?php echo $_POST['last_name'] ?>"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="user_login">Login Name</label></th>
                    <td><input type="text" placeholder="Login Name" class="regular-text" name="user_login" id="user_login" value="<?php echo $_POST['user_login'] ?>"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="user_email">Email</label></th>
                    <td><input type="text" placeholder="Email" class="regular-text" name="user_email" id="user_email" value="<?php echo $_POST['user_email'] ?>"></td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Register"></p>
    </form>
</div>
