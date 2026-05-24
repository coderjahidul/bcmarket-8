<?php
// Mailtrap API settings
function mailtrap_api_page_content() {
    // Check if the form is submitted and process the data
    if (isset($_POST['mailtrap_settings_save'])) {
        // Verify nonce for security
        if (!check_admin_referer('mailtrap_settings_nonce', 'mailtrap_nonce_field')) {
            return;
        }

        // Sanitize and save the settings
        $mailtrap_email = sanitize_text_field($_POST['mailtrap_email']);
        $username = sanitize_text_field($_POST['mailtrap_username']);
        $password = sanitize_text_field($_POST['mailtrap_password']);
        $host = sanitize_text_field($_POST['mailtrap_host']);
        $port = intval($_POST['mailtrap_port']);

        update_option('mailtrap_email', $mailtrap_email);
        update_option('mailtrap_username', $username);
        update_option('mailtrap_password', $password);
        update_option('mailtrap_host', $host);
        update_option('mailtrap_port', $port);

        echo '<div class="updated"><p>Settings saved successfully!</p></div>';
    }

    // Retrieve saved options
    $mailtrap_email = get_option('mailtrap_email', '');
    $username = get_option('mailtrap_username', '');
    $password = get_option('mailtrap_password', '');
    $host = get_option('mailtrap_host', '');
    $port = get_option('mailtrap_port', '');

    // Display the form
    ?>
    <div class="wrap">
        <h1>Mailtrap SMTP Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field('mailtrap_settings_nonce', 'mailtrap_nonce_field'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="mailtrap_email">Email</label></th>
                    <td><input type="text" name="mailtrap_email" id="mailtrap_email" value="<?php echo esc_attr($mailtrap_email); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mailtrap_username">Username</label></th>
                    <td><input type="text" name="mailtrap_username" id="mailtrap_username" value="<?php echo esc_attr($username); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mailtrap_password">Password</label></th>
                    <td><input type="password" name="mailtrap_password" id="mailtrap_password" value="<?php echo esc_attr($password); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mailtrap_host">Host</label></th>
                    <td><input type="text" name="mailtrap_host" id="mailtrap_host" value="<?php echo esc_attr($host); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mailtrap_port">Port</label></th>
                    <td><input type="number" name="mailtrap_port" id="mailtrap_port" value="<?php echo esc_attr($port); ?>" class="small-text"></td>
                </tr>
            </table>
            <p class="submit">
                <button type="submit" name="mailtrap_settings_save" class="button-primary">Save Changes</button>
            </p>
        </form>
    </div>
    <?php
}
